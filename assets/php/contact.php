<?php
// Enable CORS for AJAX request if needed
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");

// Only allow POST method
if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    http_response_code(405);
    echo json_encode(["status" => "error", "message" => "Method not allowed."]);
    exit;
}

// Get POST data and sanitize
$name = strip_tags(trim($_POST["name"] ?? ''));
$email = filter_var(trim($_POST["email"] ?? ''), FILTER_SANITIZE_EMAIL);
$subject = strip_tags(trim($_POST["subject"] ?? ''));
$message = strip_tags(trim($_POST["message"] ?? ''));

// Validate inputs
if (empty($name) || empty($email) || empty($subject) || empty($message)) {
    http_response_code(400);
    echo json_encode(["status" => "error", "message" => "Please complete the form and try again."]);
    exit;
}

// Email config
$to = "jeevanghimire@jeevanghimire.name.np";
$headers = "From: $name <$email>\r\n";
$headers .= "Reply-To: $email\r\n";
$headers .= "Content-Type: text/plain; charset=UTF-8";

// Full message
$body = "You have received a new message from your website contact form:\n\n";
$body .= "Name: $name\n";
$body .= "Email: $email\n";
$body .= "Subject: $subject\n";
$body .= "Message:\n$message\n";

// Send email
if (mail($to, $subject, $body, $headers)) {
    $response = ["status" => "success", "message" => "Email sent via EmailJS."];
    echo json_encode($response);
    exit;
} else {
    http_response_code(500);
    echo json_encode(["status" => "error", "message" => "Something went wrong. Email could not be sent."]);
    exit;
}
?>
