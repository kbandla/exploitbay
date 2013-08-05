<?php
// This is part of the exploitbay honeypot 
// github.com/kbandla/exploitbay

$logdir = 'logs/';

function getallheaders() { 
   $headers = ''; 
   foreach ($_SERVER as $name => $value) { 
       if (substr($name, 0, 5) == 'HTTP_') { 
           $headers[str_replace(' ', '-', ucwords(strtolower(str_replace('_', ' ', substr($name, 5)))))] = $value; 
       } 
   } 
   return $headers; 
} 

function getUserIpAddr(){
    if (!empty($_SERVER['HTTP_CLIENT_IP'])){
        //if from shared
        return $_SERVER['HTTP_CLIENT_IP'];
    } else if (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])){
       //if from a proxy
        return $_SERVER['HTTP_X_FORWARDED_FOR'];
    } else {
        return $_SERVER['REMOTE_ADDR'];
    }
}

function log_data($filename){
    $content = array(
        "request" => $_REQUEST, 
        "headers" => getallheaders(),
        "timestamp" => time(),
        "ipaddr"  => getUserIpAddr(),
         );
    $content = json_encode($content);
    file_put_contents($logdir.$filename, $content.PHP_EOL, FILE_APPEND);
}

$method = $_SERVER['REQUEST_METHOD'];
$logfile = strtolower($method)."_".date("m-d-Y").".log";
log_data($logfile);

?>
