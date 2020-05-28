<?php

namespace Session;

use Exception;

class FileSession implements Session {

    private $id;
    private $folder;
    private $filename;
    private $data = null;

    public function __construct(array $config)
    {
        $this->id = session_id();
        $this->folder = $config['folder'];
        $this->filename = $this->folder.DIRECTORY_SEPARATOR.$this->id;
    }

    function get($key)
    {
        return $this->getData()[$key];
    }

    function put($key, $value)
    {
        $this->getData();
        $this->data[$key] = $value;
        $this->persist();
    }

    function remove($key)
    {
        $this->getData();
        unset($this->data[$key]);
        $this->persist();
    }

    function clear()
    {
        $this->data = [];
        $this->persist();
    }

    function has($key)
    {
        return array_key_exists($key, $this->getData());
    }

    function toArray() {
        return $this->getData();
    }

    function flash() {
        return new Flash($this);
    }

    private function getData() {
        if ($this->data == null) {
            if (file_exists($this->filename)) {
                $this->data = unserialize(file_get_contents($this->filename));
            } else $this->data = [];
        }
        return $this->data;
    }

    private function persist() {
        if (!file_put_contents($this->filename, serialize($this->data))) {
            throw new \Exception("Cant write file: ". $this->filename);
        }
    }

    public function getToken(string $tokenId) {
        return $this->get('_csrf:'.$tokenId);
    }

    /**
     * Stores a CSRF token.
     */
    public function setToken(string $tokenId, string $token) {
        $this->put('_csrf:'.$tokenId, $token);
    }

    /**
     * Removes a CSRF token.
     *
     * @return string|null Returns the removed token if one existed, NULL
     *                     otherwise
     */
    public function removeToken(string $tokenId) {
        $this->remove('_csrf:'.$tokenId);
    }

    /**
     * Checks whether a token with the given token ID exists.
     *
     * @return bool Whether a token exists with the given ID
     */
    public function hasToken(string $tokenId) {
        return $this->has('_csrf:'.$tokenId);
    }
}