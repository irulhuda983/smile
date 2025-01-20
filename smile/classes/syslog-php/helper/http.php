<?php

namespace Syslog\Helper;

class HttpHelper
{
    public static function getHeaderContentType()
    {
        if (isset($_SERVER['CONTENT_TYPE'])) {
            $contentType = $_SERVER['CONTENT_TYPE'];
        }
        return ($contentType ?? '');
    }

    public static function getHttpMethodType()
    {
        if (isset($_SERVER['REQUEST_METHOD'])) {
            $httpMethod = $_SERVER['REQUEST_METHOD'];
        }
        return ($httpMethod ?? '');
    }

    public static function getHttpURLPath()
    {
        if (isset($_SERVER['REQUEST_URI'])) {
            $urlPath = $_SERVER['REQUEST_URI'];
        }
        $urlPath = ($urlPath ?? '');
        $path = "";
        if ($urlPath != "") {
            $paths = explode("?", $urlPath);
            $path = $paths[0];
        }
        return $path;
    }

    public static function getClientIP()
    {
        $xRealIP = isset($_SERVER['HTTP_X_REAL_IP']) ? $_SERVER['HTTP_X_REAL_IP'] : null;
        $xForwardedFor = isset($_SERVER['HTTP_X_FORWARDED_FOR']) ? $_SERVER['HTTP_X_FORWARDED_FOR'] : null;

        if ($xRealIP) {
            return $xRealIP;
        }

        if ($xForwardedFor) {
            $splitIps = explode(',', $xForwardedFor);
            foreach ($splitIps as $ip) {
                $ip = trim($ip);
                if (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4)) {
                    return $ip;
                }
            }
        }

        $ip = '0.0.0.0';
        if (isset($_SERVER['HTTP_CLIENT_IP'])){
            $ip = $_SERVER['HTTP_CLIENT_IP'];
        } elseif (isset($_SERVER['REMOTE_ADDR'])) {
            $ip = $_SERVER['REMOTE_ADDR'];
        }
        
        return filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4) ? $ip : '0.0.0.0';
    }

    public static function getTraceID()
    {
        if (isset($_SERVER['HTTP_X_TRACE_ID'])) {
            $traceID = $_SERVER['HTTP_X_TRACE_ID'];
        }
        return ($traceID ?? '');
    }

    public static function getPrevTransID()
    {
        if (isset($_SERVER['HTTP_X_PREV_TRANS_ID'])) {
            $prevTransID = $_SERVER['HTTP_X_PREV_TRANS_ID'];
        }
        return ($prevTransID ?? '');
    }
}
