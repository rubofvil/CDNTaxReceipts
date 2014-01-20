<?php
require_once 'CRM/Cdntaxreceipts/PseudoConstants.php';

$const = get_defined_constants(true);
foreach($const['user'] as $key => $value){
	$this->assign("$key", "$value");
}