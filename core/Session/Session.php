<?php

namespace Session;

use Storage;
use Symfony\Component\Security\Csrf\TokenStorage\TokenStorageInterface;

interface Session extends \Storage, TokenStorageInterface {

    /**
     * @return Storage
     */
    function flash();

}