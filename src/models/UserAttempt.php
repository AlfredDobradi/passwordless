<?php namespace Adobradi\Passwordless;

class UserAttempt extends \Eloquent {
    protected $table = 'user_attempt';
    
    public function user()
    {
        $this->belongsTo('User','id','user_id');
    }
}