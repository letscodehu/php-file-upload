<?php 

namespace Middleware;

use Response\Response;
use Request\Request;

interface Middleware {
    function process(Request $request, Response $response, callable $next);
}