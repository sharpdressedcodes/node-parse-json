<?php

require_once('SocketStream.php');

$hostname = 'localhost';
$port = 8080;

$data = file_get_contents('sample_request.json');
$request = "POST / HTTP/1.0\r\n" .
           "Host: $hostname\r\n" .
           "Content-Length: " . strlen($data) . "\r\n" .
           "\r\n" . $data;
//echo str_replace("\r\n", '<br>', $request) . '<br><br>';

$socket = new SocketStream();
$socket->connect($hostname, $port);
$socket->send($request);
$result = $socket->receive();
$socket->close();

echo "Finished<br>" .str_replace("\r\n", '<br>',$result);

?>