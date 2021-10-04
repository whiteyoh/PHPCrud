<?php
require_once('Response.php');

$response = new Response();

$response->setSuccess(true);
$response->setHttpStatusCode(200);
$response->addMessage("Test");
$response->addMessage("Second test");
$response->send();
 ?>
