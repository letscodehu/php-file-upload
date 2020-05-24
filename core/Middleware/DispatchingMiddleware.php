<?php

namespace Middleware;

use Response\ResponseFactory;
use Response\Response;
use Request\Request;

class DispatchingMiddleware implements Middleware {

    private $dispatcher;
    private $responseFactory;

    public function __construct(\Dispatcher $dispatcher, ResponseFactory $responseFactory) {
        $this->dispatcher = $dispatcher;
        $this->responseFactory = $responseFactory;
    }

    function process(Request $request, Response $response, callable $next) {
        $controllerResult = $this->dispatcher->dispatch($request);
        logMessage("INFO","Creating response");
        return $this->responseFactory->createResponse($controllerResult, $request);
    }

}