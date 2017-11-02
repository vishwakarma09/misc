<?php 

$conn = mysqli_connect("localhost", "root", "", "acare") or die(mysqli_error($conn));

$json = $_POST['payload'];

/*$json = '[{"id":"1","name":"Andaman and Nicobar"},{"id":"2","name":"Andhra Pradesh"},{"id":"3","name":"Arunachal Pradesh"},{"id":"4","name":"Assam"},{"id":"5","name":"Bihar"},{"id":"6","name":"Chandigarh"},{"id":"7","name":"Chattisgarh"},{"id":"8","name":"Dadar and Nagar Haveli"},{"id":"9","name":"Daman and Diu"},{"id":"10","name":"Goa"},{"id":"11","name":"Gujarat"},{"id":"12","name":"Haryana"},{"id":"13","name":"Himachal Pradesh"},{"id":"14","name":"Jammu and Kashmir"},{"id":"15","name":"Jharkhand"},{"id":"16","name":"Karnataka"},{"id":"17","name":"Kerala"},{"id":"18","name":"Lakshadweep"},{"id":"19","name":"Madhya Pradesh"},{"id":"20","name":"Maharashtra"},{"id":"21","name":"Manipur"},{"id":"22","name":"Meghalaya"},{"id":"23","name":"Mizoram"},{"id":"24","name":"Nagaland"},{"id":"25","name":"New Delhi"},{"id":"26","name":"Orisa"},{"id":"27","name":"Pondicherry"},{"id":"28","name":"Punjab"},{"id":"29","name":"Rajasthan"},{"id":"30","name":"Sikkim"},{"id":"31","name":"Tamil Nadu"},{"id":"32","name":"Tripura"},{"id":"33","name":"Uttar Pradesh"},{"id":"34","name":"Uttaranchal"},{"id":"35","name":"West Bengal"}]';*/

$decoded = json_decode($json);
foreach ($decoded as $row) {

	$exist_query = "select * from mststate where name = '".$row->name."'";
	$exist_result = mysqli_query($conn, $exist_query);
	if (mysqli_num_rows($exist_result) > 0) {
		// do update here
		$update_query = "update mststate set name = '".$row->name."' where id = ".$row->id;
		$update_result = mysqli_query($conn, $update_query);
	}else{
		// do insert here
		$insert_query = "insert into mststate (id, name) values (".$row->id.", '".$row->name."')";
		$insert_result = mysqli_query($conn, $insert_query);
	}
}

echo "sync complete!";