<?php

namespace Adobradi\Passwordless;


class UserIsLockedException extends \Exception {
    public function __construct()
    {
        parent::__construct();

        $this->message = 'This account is locked';
        $this->code = 'PL002';
    }
} 