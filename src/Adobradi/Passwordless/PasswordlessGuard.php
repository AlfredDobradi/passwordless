<?php namespace Adobradi\Passwordless;

use Illuminate\Auth\Guard;
use Adobradi\Passwordless\User as User;
use Adobradi\Passwordless\UserAttempt as UserAttempt;
use Illuminate\Auth as Auth;

class PasswordlessGuard extends Guard {

    public static function startProcess($email)
    {
        $hash = sha1(date('Y-m-d') . uniqid());
        $user = User::whereEmail($email)->first();
        if ( !$user ) {
            throw new EmailNotFoundException;
        }

        $now = new \DateTime();
        if ( !is_null($user->locked_at)
            && strtotime($user->locked_at) > $now->sub(new \DateInterval('PT30M'))->getTimestamp() ) {
            throw new UserIsLockedException;
        } elseif ( !is_null($user->locked_at) ) {
            $user->locked_at = null;
            $user->save();
        }
        
        $attempt = new UserAttempt;
        $attempt->user_id = $user->id;
        $attempt->success = false;
        $attempt->login_hash = $hash;
        
        $attempt->save();

        self::sendHash($email,$hash);

        return $hash;
    }

    public static function checkLoginHash($email=null,$hash=null)
    {
        $missing = array();
        if ( is_null($email) ) {
            $missing[] = 'email';
        }

        if ( is_null($hash) ) {
            $missing[] = 'hash';
        }

        if ( count($missing) > 0 ) {
            throw new AuthCredentialsMissingException($missing);
        }

        $user =  User::whereEmail($email)->first();
        $attempt = $user->attempts()->whereLogin_hash($hash)->whereSuccess(false)->whereRaw('created_at > DATE_SUB(NOW(), INTERVAL 30 MINUTE)')->first();

        if ( is_null($attempt) ) {
            $failed_attempts = $user->attempts()->whereSuccess(false)->whereRaw('created_at > DATE_SUB(NOW(), INTERVAL 30 MINUTE)')->count();
            if ( $failed_attempts > 3 ) {
                $user->locked_at = date('Y-m-d H:i:s');
                $user->save();
            }
            throw new IncorrectHashException;
        } else {
            $attempt->success = true;
            $attempt->save();

            return $user;
        }
    }

    private static function sendHash($email, $hash)
    {
        \Mail::pretend(true);

        $template = \Config::get('passwordless::email.view');
                $payload = array('hash' => $hash, 'email' => $email);
        \Mail::queue($template, $payload, function($message) {
            $message->to('alfreddobradi@gmail.com', 'ADobradi')->subject('Your login hash');
        });
    }
}