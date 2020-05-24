<?php

namespace Session;

use Storage;

class Flash implements Storage {

    private $session;

    public function __construct(Session $session)
    {
        $this->session = $session;
    }

    function get($key)
    {
        if ($this->session->has("flash")) {
            $flash = $this->session->get("flash");
            if (array_key_exists($key, $flash)) {
                return $flash[$key];
            }
        }
    }

    function put($key, $value)
    {
        if ($this->session->has("flash")) {
            $flash = $this->session->get("flash");
            $flash[$key] = $value;
            $this->session->put("flash", $flash);
        } else {
            $this->session->put("flash", [
                $key => $value
            ]);
        }
    }

    function remove($key)
    {
        if ($this->session->has("flash")) {
            $flash = $this->session->get("flash");
            unset($flash[$key]);
            $this->session->put("flash", $flash);
        }
    }

    function clear()
    {
        $this->session->remove("flash");
    }

    function has($key)
    {
        if ($this->session->has("flash")) {
            $flash = $this->session->get("flash");
            return array_key_exists($key, $flash);
        }
    }


}