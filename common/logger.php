<?php

class Logger
{
    public static function addlog($msg)
    {
        file_put_contents(sprintf('%s-error.log', date('Y-m-d')), $msg, FILE_APPEND);

    }
    
}
