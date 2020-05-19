<?php

namespace Response;

interface ResponseInterface {

    function getHeaders();

    function emitBody();

    function getStatusCode();

    function getReasonPhrase();

}