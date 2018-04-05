<?php
class BaseController extends Controller
{
    public function BeforeAction()
    {
        $this->EnqueuBasicCss();
        $this->EnqueuBasicJavascript();

        $this->SetLinks();
    }

    protected  function EnqueuBasicCss()
    {
        $this->EnqueueCssFiles([
            'bootstrap-darkly.min.css',
            'dashboard.css',
            'font-awesome.css'
        ]);
    }

    protected function EnqueuBasicJavascript()
    {
        $this->EnqueueJavascript([
            'jquery.min.js',
            'bootstrap.min.js',
            'dashboard.js',
            'sh_main.min.js',
            'sh_cpp.min.js',
            'sh_csharp.min.js',
            'sh_php.min.js'
        ]);
    }

    protected function SetLinks()
    {
        $this->Set('ApplicationLinks', $this->Helpers->ShellAuth->GetApplicationLinks()['data']);
    }
}