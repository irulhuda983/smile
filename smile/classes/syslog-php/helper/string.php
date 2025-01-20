<?php

namespace Syslog\Helper;

require_once __DIR__ . '/../vendor/autoload.php';

use Twig\Environment;
use Twig\Loader\ArrayLoader;

$loader = new ArrayLoader();
$twig = new Environment($loader);

class StringHelper
{
    public static function safeSubstring($input, $start, $end)
    {
        if ($input === null) {
            return '';
        }
        if ($start < 0) {
            $start = 0;
        }
        if ($end > strlen($input)) {
            $end = strlen($input);
        }
        if ($start > $end) {
            [$start, $end] = [$end, $start];
        }
        return substr($input, $start, $end - $start);
    }

    public static function specialChar($sep)
    {
        return str_replace(['\\t', '\\n', '\\r'], ["\t", "\n", "\r"], $sep);
    }

    public static function maskingString($input, $start, $end, $maskChar)
    {
        if ($start < 0) {
            $start = 0;
        }
        if ($end > strlen($input)) {
            $end = strlen($input);
        }
        $maskStr = str_repeat($maskChar, $end - $start);
        $prefix = substr($input, 0, $start);
        $suffix = substr($input, $end);
        return $prefix . $maskStr . $suffix;
    }

    public static function renderTemplate($template, $data = [])
    {
        global $twig;
        $template = $twig->createTemplate($template);
        $output = $template->render($data);
        return $output;
    }
}
