<?php

    sleep(4); 
	
	$response = [];
	
	$response['status'] = 'success';
    $response['message'] = "Task successfully completed.";

   header('Content-Type: application/json');
	echo json_encode($response);
	exit;
?>