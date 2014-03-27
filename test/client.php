<?php

require_once('SocketStream.php');

define('LOCAL_HOST', 'localhost');
define('LOCAL_PORT', 8080);
define('REMOTE_HOST', 'fast-cliffs-8080.herokuapp.com');
define('REMOTE_PORT', 80);
define('DATA_FILE', dirname(__FILE__) . '/sample_request.json');
define('POST_PACKET', "POST / HTTP/1.0\r\n" .
                      "Host: %host%\r\n" .
                      "Content-Length: %len%\r\n" .
                      "\r\n%data%"
);

function createPacket($hostname, $data){

    $packet = str_replace('%host%', $hostname, POST_PACKET);
    $packet = str_replace('%len%', strlen($data), $packet);
    $packet = str_replace('%data%', $data, $packet);

    return $packet;

}

function sendPacket($hostname, $port, $data){

    $socket = new SocketStream();
    $socket->connect($hostname, $port);
    $socket->send($data);
    $result = $socket->receive();
    $socket->close();

    return $result;

}

function main(){

    $local = isset($_GET['local']);

    if ($local){
        $hostname = LOCAL_HOST;
        $port = LOCAL_PORT;
    } else {
        $hostname = REMOTE_HOST;
        $port = REMOTE_PORT;
    }

    $data = file_get_contents(DATA_FILE);
    $request = createPacket($hostname, $data);
    $response = sendPacket($hostname, $port, $request);

    //echo str_replace("\r\n", '<br>', $request) . '<br><br>';
    echo str_replace("\r\n", '<br>', $response);

}

main();

?>