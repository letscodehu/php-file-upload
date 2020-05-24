<?php

namespace Request;

use Session\Session;
use RuntimeException;

class Request {

    private $body;
    private $headers;
    private $cookies;
    private $params;
    private $uri;
    private $method;
    private $session;
    private $files;

    public function __construct($uri, $method, Session $session, $body = null, $headers = [], $cookies = [], $params = [], $files = []) {
        $this->uri = $uri;
        $this->method = $method;
        $this->body = $body;
        $this->headers = $headers;
        $this->cookies = $cookies;
        $this->params = $params;
        $this->session = $session;
        $this->files = $files;
    }

    public function getUri() {
        return $this->uri;
    }

    public function getBody() {
        return $this->body;
    }

    public function getHeaders() {
        return $this->headers;
    }

    public function getCookies() {
        return $this->cookies;
    }

    public function getParams() {
        return $this->params;
    }

    public function getMethod() {
        return $this->method;
    }

    /**
     * @return Session
     */
    public function getSession()
    {
        return $this->session;
    }
    public function getFiles()
    {
        return $this->files;
    }

    /**
     * @return string
     */
    public function getParam($fieldName) {
        if (!array_key_exists($fieldName, $this->params)) {
            return null;
        }
        return $this->params[$fieldName];
    }

    public function getFile($fieldName)
    {
        if (!array_key_exists($fieldName, $this->files)) {
            return null;
        }
        return $this->files[$fieldName];
    }

}