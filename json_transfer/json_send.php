<?php 

$conn = mysqli_connect("localhost", "root", "", "acare") or die(mysqli_error($conn));

$query = "select * from mststate";
$result = mysqli_query($conn, $query);

while ($row = mysqli_fetch_assoc($result)) {
	$returnArray[] = $row;
}

echo json_encode($returnArray);
