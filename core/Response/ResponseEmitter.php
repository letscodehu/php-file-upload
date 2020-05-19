<?php 

namespace Response;

class ResponseEmitter {

    public function emit(ResponseInterface $response) {
        $this->emitStatusLine($response->getStatusCode(), $response->getReasonPhrase());
        $this->emitHeaders($response->getHeaders());
        $response->emitBody();
    }

    private function emitStatusLine(int $statusCode, string $reasonPhrase) {
        header(sprintf(
            'HTTP/1.1 %d%s',
            $statusCode,
            ($reasonPhrase ? ' ' . $reasonPhrase : '')
        ), true, $statusCode);
    }

    private function emitHeaders(array $headers) {
        foreach ($headers as $key => $value) {
            header(sprintf("%s: %s", $key, $value));
        }
    }
}