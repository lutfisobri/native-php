<?php
namespace Riyu\Http;

class Response
{
    protected $content;

    protected $status;

    protected $headers;

    public function __construct($content = '', $status = 200, $headers = [])
    {
        $this->content = $content;
        $this->status = $status;
        $this->headers = $headers;
    }

    public function send()
    {
        $this->sendHeaders();
        $this->sendContent();
    }

    public function sendHeaders()
    {
        if (!headers_sent()) {
            foreach ($this->headers as $key => $value) {
                header($key . ': ' . $value);
            }
        }
    }

    public function sendContent()
    {
        echo $this->content;
    }

    public function setContent($content)
    {
        $this->content = $content;
    }

    public function setStatusCode($status)
    {
        $this->status = $status;
    }

    public function setHeader($key, $value)
    {
        $this->headers[$key] = $value;
    }

    public function setContentLength()
    {
        $this->headers['Content-Length'] = strlen($this->content);
    }

    public function setContentType($type)
    {
        $this->headers['Content-Type'] = $type;
    }
}