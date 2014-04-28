<?php
/**
 * Created by PhpStorm.
 * User: alfreddobradi
 * Date: 27/04/14
 * Time: 18:27
 */

namespace Adobradi\Passwordless;


class IncorrectHashException extends \Exception {
    public function __construct()
    {
        parent::__construct();

        $this->message = 'The supplied code is invalid';
        $this->code = 'PL003';
    }
} 