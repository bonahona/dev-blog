<?php
class DatabaseOutputCache implements IOutputCache
{
    public $OutputCacheConfig;

    public function __construct($config)
    {
        $this->OutputCacheConfig = $config;
    }

    public function CacheOutput($request, $expires, $data)
    {

    }

    public function IsCached($request)
    {
        return false;
    }

    public function GetCache($request)
    {
    }

    public function Invalidate($request)
    {

    }
}