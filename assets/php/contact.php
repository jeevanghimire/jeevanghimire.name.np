<?php
// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

if ($_SERVER["REQUEST_METHOD"] != "POST") {
    http_response_code(405);
    echo "Method Not Allowed. Please use POST.";
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Collect and sanitize form input
    $name = htmlspecialchars(trim($_POST["name"]));
    $email = filter_var(trim($_POST["email"]), FILTER_SANITIZE_EMAIL);
    $subject = htmlspecialchars(trim($_POST["subject"]));
    $message = htmlspecialchars(trim($_POST["message"]));

    // Validate input
    if (empty($name) || empty($email) || empty($subject) || empty($message)) {
        http_response_code(400);
        echo "Please complete all fields.";
        exit;
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        http_response_code(400);
        echo "Invalid email format.";
        exit;
    }

    // Email content
    $to = "jeevanghimire@jeevanghimire.name.np";
    $email_subject = "New Contact Form Message: $subject";
    $email_body = "You have received a new message from your website contact form.\n\n" .
        "Here are the details:\n" .
        "Name: $name\n" .
        "Email: $email\n" .
        "Subject: $subject\n" .
        "Message:\n$message";

    $headers = "From: $name <$email>\r\n";
    $headers .= "Reply-To: $email\r\n";

    // Send the email
    if (mail($to, $email_subject, $email_body, $headers)) {
        http_response_code(200);
        echo "OK"; // Success response
    } else {
        http_response_code(500);
        echo "Oops! Something went wrong, and we couldn't send your message.";
    }
} else {
    http_response_code(403);
    echo "There was a problem with your submission. Please try again.";
}