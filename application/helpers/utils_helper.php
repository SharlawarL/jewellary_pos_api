<?php
defined('BASEPATH') OR exit('No direct script access allowed');

function headers()
{
    $_HEADERS = array();
    foreach (getallheaders() as $name => $value) { 
        $_HEADERS[$name] = $value;
    }
    return $_HEADERS;
}