<?php
/*
*
* Ascio Web Service 
* http://aws.request.info
* Author: www.request.com - ml@webender.de
*
*/



//
//  WHMCS functions
//
require_once("lib/Request.php");
require_once("lib/DnsService.php");
require_once("lib/Tools.php");
require_once("lib/Zone.php");

function ascio_getConfigArray() {
	$configarray = array(
	 "Username" => array( "Type" => "text", "Size" => "20", "Description" => "Enter your username here", ),
	 "Password" => array( "Type" => "password", "Size" => "20", "Description" => "Enter your password here", ),
	 "TestMode" => array( "Type" => "yesno", )
	);
	return $configarray;
}
function ascio_ClientAreaCustomButtonArray() {
    $buttonarray = array(
	 "Expire_Domain" => "ExpireDomain"
	);
	return $buttonarray;
}
function ascio_GetNameservers($params) {	
	$request = createRequest($params);	
	$domain = $request->searchDomain(); 
	if (is_array($domain)) return $domain;
	$ns = $domain->NameServers;

	# Put your code to get the nameservers here and return the values below
	$values["ns1"] = $ns->NameServer1->HostName;
	$values["ns2"] = $ns->NameServer2->HostName;
	$values["ns3"] = $ns->NameServer3->HostName;
	$values["ns4"] = $ns->NameServer4->HostName;
	$values["status"] = "Active";
	return $values;
}
function ascio_SaveNameservers($params) {
	$request = createRequest($params);
	return $request->saveNameservers();
}

function ascio_GetRegistrarLock($params) {
	$request = createRequest($params);

	//getDomain

	# Put your code to get the lock status here
	if ($lock=="1") {
		$lockstatus="locked";
	} else {
		$lockstatus="unlocked";
	}
	return $lockstatus;
}

function saveRegistrarLock($params) {
	$request = createRequest($params);
	return $request->saveNameservers();
}

function ascio_GetEmailForwarding($params) {
	$request = createRequest($params);
	# Put your code to get email forwarding here - the result should be an array of prefixes and forward to emails (max 10)
	foreach ($result AS $value) {
		$values[$counter]["prefix"] = $value["prefix"];
		$values[$counter]["forwardto"] = $value["forwardto"];
	}
	return $values;
}

function ascio_SaveEmailForwarding($params) {
	$request = createRequest($params);
	foreach ($params["prefix"] AS $key=>$value) {
		$forwardarray[$key]["prefix"] =  $params["prefix"][$key];
		$forwardarray[$key]["forwardto"] =  $params["forwardto"][$key];
	}
	# Put your code to save email forwarders here
}

function ascio_GetDNS($params) {
	$zone = new DnsZone($params);
	$result =  $zone->convertToWhmcs($zone->get());
	return $result;
}
function ascio_SaveDNS($params) {	
	$zone = new DnsZone($params);
	return $zone->update($params);
}
function ascio_RegisterDomain($params) {
	$request = createRequest($params);
	return $request->registerDomain($params); 
}

function ascio_TransferDomain($params) {
	$request = createRequest($params);
	return $request->transferDomain($params);  
}

function ascio_RenewDomain($params) {
	$request = createRequest($params);
	return $request->renewDomain($params); 
}

function ascio_ExpireDomain($params) {
	$request = createRequest($params);
	return $request->expireDomain($params); 
}

function ascio_GetContactDetails($params) {
	$request = createRequest($params);
	$result = $request->searchDomain();
	$name = Tools::splitName($result->Registrant->Name);
	$values["Registrant"]["First Name"] = $name["first"];
	$values["Registrant"]["Last Name"]  = $name["last"];
	$values["Admin"]["First Name"] 		= $result->Admin->Firstname;
	$values["Admin"]["Last Name"] 		= $result->Admin->Lastname;
	$values["Tech"]["First Name"] 		= $result->Tech->Firstname;
	$values["Tech"]["Last Name"] 		= $result->Tech->Lastname;
	syslog(LOG_INFO, "WHMCS GetContactDetails");
	return $values;
}

function ascio_SaveContactDetails($params) {
	$request = createRequest($params);
	return $request->updateContacts($params);
}

function ascio_GetEPPCode($params) {
	$request = createRequest($params);
	return $request->getEPPCode($params);
}

function ascio_RegisterNameserver($params) {
	$request = createRequest($params);
    $nameserver = $params["nameserver"];
    $ipaddress = $params["ipaddress"];
    # Put your code to register the nameserver here
    # If error, return the error message in the value below
    $values["error"] = $error;
    return $values;
}

function ascio_ModifyNameserver($params) {
	$request = createRequest($params);
    $nameserver = $params["nameserver"];
    $currentipaddress = $params["currentipaddress"];
    $newipaddress = $params["newipaddress"];
    # If error, return the error message in the value below
    $values["error"] = $error;
    //Nameserver_Update
    return $values;
}

function ascio_DeleteNameserver($params) {
	$request = createRequest($params);
    $values["error"] = "Operation not allowed";
    return $values;
}
function ascio_Sync($params) {
	$request = createRequest($params);
	$domain = $request->searchDomain($params);	
	$d = new DateTime($domain->ExpDate);
	$values["expirydate"] = $d->format("Y-m-d");
	$values["active"] = true;
	syslog(LOG_INFO, "Syncing ". $params["sld"].".".$params["tld"]);
	echo "Syncing ". $params["sld"].".".$params["tld"];
	//var_dump($values);
	return $values;
}

?>
