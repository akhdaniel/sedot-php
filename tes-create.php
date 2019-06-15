<?php
require_once('ripcord/ripcord.php');

$url 	= "http://127.0.0.1:8088";
$db 	= "retail";
$username = "admin";
$password = "1";
$common = ripcord::client("$url/xmlrpc/2/common");
$uid = $common->authenticate($db, $username, $password, array());
if ($uid==-1)
	die("gagal koneksi ke ODoo");
var_dump($uid);





$models = ripcord::client($url . '/xmlrpc/2/object');
$res = $models->execute_kw( 
	$db, $uid, $password, 
	'res.partner',
	'create',
	array( 
		array('name'=>"Partner Baru dari XML")		
	)
);
echo "<pre>";
var_dump($res);












