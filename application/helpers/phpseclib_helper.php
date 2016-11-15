<?php

function phpseclib()
{
    $path = __DIR__ . '/phpseclib';
    set_include_path(get_include_path() . PATH_SEPARATOR . $path);
    require_once('phpseclib/Net/SFTP.php');
    //require_once('phpseclib/Crypt/RSA.php');
}