<?php

function custom_log($data, $append = true)
{
    $fileName = $_SERVER['DOCUMENT_ROOT'] . '/log.log';
    $flag = $append ? FILE_APPEND : 0;
    file_put_contents(
        $fileName,
        var_export(date('H:i:s'), true) . PHP_EOL . var_export($data, true) . PHP_EOL . PHP_EOL,
        $flag
    );
}