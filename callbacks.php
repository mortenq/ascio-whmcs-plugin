<?
require("ascio-lib.php");
require_once("config.php");


$orderId = $_GET["OrderId"];
$messageId = $_GET["MessageId"];
$orderStatus = $_GET["OrderStatus"];

getCallbackData($orderStatus,$messageId,$orderId);

?>