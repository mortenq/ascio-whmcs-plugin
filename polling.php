<?
set_time_limit ( 6000 );
require_once("ascio-lib.php");
require_once("config.php");

$result = poll();
while ($result->item && $result->item->MsgId) {
	$item = $result->item;
	getCallbackData($item->OrderStatus,$item->MsgId,$item->OrderId);
	syslog(LOG_INFO,"Acking: ".$result->item->MsgId);
	$result = poll();
}
?>