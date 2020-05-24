<?php

namespace Middleware;

use Request\Request;
use Response\Response;

class FlashMessageCleanupMiddleware implements Middleware {

    function process(Request $request, Response $response, callable $next)
    {
        /**
         * @var Response
         */
        $finished = $next($request, $response);
        if ($finished->getStatusCode() < 300) {
            $request->getSession()->flash()->clear();    
            logMessage("INFO","Clearing up flash messages");
        }
        return $finished;
    }

}