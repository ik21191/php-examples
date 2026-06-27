<?php
require_once "ContactUsModel.php";
require_once "ContactUs.php";
require_once "config.php";

// Read raw POST data from the request body
$json = file_get_contents('php://input');

// Decode the JSON into a PHP associative array
$data = json_decode($json, true);

if ($data) {
    // Access individual fields
    $username = $data['name'] ?? 'Unknown';
    $email = $data['email'] ?? 'No email';
    $subject = $data['subject'] ?? 'No subject';
    $description = $data['description'] ?? 'No Description';

    $contactUsModel = new ContactUsModel($username, $email, $subject, $description);

    $contactUs = new ContactUs();
    $contactUs->insertContactUs($contactUsModel);

    // Send a JSON response back to the client
    echo json_encode([
        "status" => "success",
        "message" => "Thanks for contacting us, we will revert you soon."
    ]);
} else {
    http_response_code(400);
    echo json_encode(["status" => "error", "message" => "Invalid JSON"]);
}
