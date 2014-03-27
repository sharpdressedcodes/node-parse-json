<?php

/*

  Socket Stream Wrapper
  by Greg Kappatos
  24th September, 2013

*/


class SocketStream {

  public $server = null;
  public $port = null;

  private $_handle = null;
  private $_lastError = null;

  public function __destruct(){

    $this->close();

  }

  public function connect($server = null, $port = null){

    $this->_lastError = null;

    if (!is_null($server))
      $this->server = $server;

    if (!is_null($port) && is_int($port))
      $this->port = (int)$port;

    $this->close();

    try {

      $errNo = null;
      $errStr = null;
      $this->_handle = fsockopen($this->server, $this->port, $errNo, $errStr);

      if (is_null($this->_handle)){
        $this->_lastError = $errStr;
        return false;
      }

    } catch (Exception $e){
      $this->_lastError = $e->getMessage();
      return false;
    }

    return true;

  }

  public function close(){

    $this->_lastError = null;

    try {

      if (!is_null($this->_handle))
        fclose($this->_handle);

    } catch (Exception $e){

      $this->_lastError = $e->getMessage();
      $this->_handle = null;
      return false;

    }

    $this->_handle = null;
    return true;

  }

  public function send($data){

    $this->_lastError=null;

    if (is_null($this->_handle)){
      $this->_lastError = 'Error: not connected';
      return 0;
    }

    $bytesWritten = 0;

    try {
      $bytesWritten = fputs($this->_handle, $data, strlen($data));
    } catch (Exception $e){
      $this->_lastError = $e->getMessage();
    }

    return $bytesWritten;

  }

  public function receive($data=null){

    $this->_lastError = null;

    if (is_null($this->_handle)){
      $this->_lastError = 'Error: not connected';
      return null;
    }

    $buffer = array();

    try {

      while (!feof($this->_handle)){
        $packet = fgets($this->_handle, 4096);
        $buffer[] = $packet;
        if (!is_null($data) && strstr($packet,$data))
          break;
      }

    } catch (Exception $e){
      $this->_lastError = $e->getMessage();
      return null;
    }

    return count($buffer) > 0 ? implode('',$buffer) : null;

  }

}

?>
