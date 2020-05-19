<?php 

namespace Response;

class BinaryFileResponse implements ResponseInterface {

    private $headers = [];
    private $fileName;
    private $statusCode;
    private $reasonPhrase;

    public function __construct(string $fileName) 
    {
        $this->headers = [
            "Content-Type" => mime_content_type($fileName),
            "Content-Length" => filesize($fileName)
        ];
        $this->fileName = $fileName;
        $this->statusCode = 200;
        $this->reasonPhrase = "OK";
    }

    public function getHeaders() {
        return $this->headers;
    }

    public function emitBody()
    {
        readfile($this->fileName);
    }

    public function getStatusCode() {
        return $this->statusCode;
    }
    public function getReasonPhrase() {
        return $this->reasonPhrase;
    }


}