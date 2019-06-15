<?php
include_once("ripcord/ripcord.php");
ini_set('memory_limit', '1024M'); 
ini_set('max_execution_time',0);

$start = time();
$employees = pull_employee_mysql();
$total_rec = push_odoo($employees);
$end = time();
print "Total time " . (($end-$start)/60) . " minutes for ". $total_rec ." records";


function push_odoo($data){
	$url 	= "http://127.0.0.1:9000";
	$db 	= "fave2";
	$username = "admin";
	$password = "1";
	$common = ripcord::client("$url/xmlrpc/2/common");
	$uid = $common->authenticate($db, $username, $password, array());
	if ($uid==-1)
		die("gagal koneksi ke Odoo");

	$models = ripcord::client($url . '/xmlrpc/2/object');

	$i = 0;
	foreach ($data as $key => $value) {
		$res = $models->execute_kw( 
			$db, $uid, $password, 
			'hr.employee',
			'create',
			array( 
				array('name'=>$value['first_name'] . " " . $value['last_name']),
				array('birthdate'=>$value['birth_date']),
				array('identification_id'=>$value['emp_no'])
			)
		);
		$i++;
	}

	return $i;


}

function pull_employee_mysql(){
	$servername = "127.0.0.1";
	$username = "root";
	$password = "1";
	$dbname = "employees";
	// Create connection
	$conn = new mysqli($servername, $username, $password, $dbname);
	// Check connection
	if ($conn->connect_error) {
	    die("Connection failed: " . $conn->connect_error);
	} 


	$data = [];

	$sql = "SELECT * FROM employees";
	$result = $conn->query($sql);

	if ($result->num_rows > 0) {
	    while($row = $result->fetch_assoc()) {
	        $data[] = $row;
	    }
	} 
    return $data;

	$conn->close();
}