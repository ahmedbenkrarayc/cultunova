<?php

class Logger{
    private const FILE_LOGS = __DIR__.'/../logs/logs.txt';

    public static function error_log($message){
        $content = date('Y-m-d H:i:s').'\t'.$message.'\n';
        file_put_contents($this->FILE_LOGS, $content, FILE_APPEND);
    }
}