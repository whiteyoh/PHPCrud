<?php
class Response {
  private $_success;
  private $_httpStatusCode;
  private $_messages = array();
  private $_data;
  private $_toCache = false;
  private $_responseData = array();

public function setSuccess($success){
  $this->_success = $success;

}
public function setHttpStatusCode($httpStatusCode){
  $this->_httpStatusCode = $httpStatusCode;
}
public function addMessage($message){
  $this->_messages[] = $message;
}
public function setData($data){
  $this->_data = $data;
}
public function toCache($toCache){
  $this->_toCache = $toCache;
}
public function send(){
  header('Content-type: application/json; charset=utf-8');

  if($this->_toCache === true){
    header('Cache-control: max-age=60');

  } else {
    header('Cache-control: no-cache, no-store');
  }

  if(($this->_success !== false && $this->_success !==true) || !is_numeric($this->_httpStatusCode)){
    $this->_responseData['success'] = false;
    http_response_code(500);
    $this->_responseData['statusCode'] = 500;
    $this->addMessage("500 Internal Server Error");
    $this->_responseData['messages'] = $this->_messages;
  } else {
    $this->_responseData['success'] = true;
    http_response_code($this->_httpStatusCode);
    $this->_responseData['statusCode'] = $this->_httpStatusCode;
    $this->_responseData['messages'] = $this->_messages;
    $this->_responseData['data'] = $this->_data;
  }
 if(array_key_exists('format', $_GET)&&$_GET ['format'] == 'json'){
    header('Content-type: application/json; charset=utf-8');
    echo json_encode($this->_responseData);
  } elseif(array_key_exists('format', $_GET)&&$_GET ['format'] == 'xml'){
    header('Content-Type: application/xml; charset=utf-8');
    $xml_data = new SimpleXMLElement('<?xml version="1.0"?><data></data>');
    array_to_xml( $this->_responseData, $xml_data);
  } else {
    echo "missing format";
  }
}
}
?>
