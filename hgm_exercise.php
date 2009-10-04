<?php

	require_once 'classes/HighGearMediaProcess.php';
	
	$hgmObj = new HighGearMediaProcess();
	
	$hgmObj->setInput($_POST);
	
	$hgmObj->setContent();

	$return_JSON_obj = $hgmObj->getResults('json');
	
	print $return_JSON_obj;

?>