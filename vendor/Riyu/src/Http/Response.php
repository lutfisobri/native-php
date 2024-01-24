<?php
namespace Riyu\Http;

class Response
{
    protected $content;

    protected $status;

    protected $headers;

    public function __construct($content = '', $status = 200, $headers = [])
    {
        $this->content($content);
        $this->code($status);
        $this->headers = $headers;
    }

    public function send()
    {
        $this->sendHeaders();
        $this->sendStatusCode();
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

    public function sendStatusCode()
    {
        http_response_code($this->status);
    }

    public function content($content)
    {
        $this->content = $content;

        return $this;
    }

    public function code($status)
    {
        $this->status = $status;

        return $this;
    }

    public function header($key, $value)
    {
        $this->headers[$key] = $value;

        return $this;
    }

    public function contentType($type)
    {
        $this->headers['Content-Type'] = $type;

        return $this;
    }

    public function json($content)
    {
        $this->content = json_encode($content);
        $this->contentType('application/json');

        return $this;
    }
}