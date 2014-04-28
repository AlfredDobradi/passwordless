<?php
/**
 * Created by PhpStorm.
 * User: alfreddobradi
 * Date: 27/04/14
 * Time: 18:30
 */

namespace Adobradi\Passwordless;


class AuthCredentialsMissingException extends \Exception {
    public function __construct($missing)
    {
        parent::__construct();

        if ( count($missing) == 2 ) {
            $e = ucfirst($missing[0]) . ' and ' . $missing[1] . ' are missing';
        } else {
            $e = ucfirst($missing[0]) . ' is missing';
        }

        $this->message = $e;
        $this->code = 'PL004';
    }

} 