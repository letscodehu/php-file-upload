<?php

namespace Session;

use Storage;

interface Session extends \Storage {

    /**
     * @return Storage
     */
    function flash();

}