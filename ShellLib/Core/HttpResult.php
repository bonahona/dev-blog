<?php
class HttpResult
{
    public $Content;
    public $ReturnCode;
    public $MimeType;
    public $Location;

    public function __construct($content = '', $mimeType = 'text/plain', $returnCode = 200)
    {
        $this->Content = $content;
        $this->MimeType = $mimeType;
        $this->ReturnCode = $returnCode;
        $location = null;
    }
}