<?php
interface IOutputCache
{
    public function CacheOutput($request, $httpResult);
    public function GetCache($request);
    public function Invalidate($request);
}