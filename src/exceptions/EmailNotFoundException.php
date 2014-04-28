<?php namespace Adobradi\Passwordless;

class EmailNotFoundException extends \Exception {
    public function __construct()
    {
        parent::__construct();
        
        $this->message = 'Email not found';
        $this->code = 'PL001';
    }
}