<?php
// Your email address (receiver)
$to = "saifbd797@gmail.com";

// Google reCAPTCHA secret key
$secretKey = "6LcKNBYsAAAAAMqmXZxwKbSlfpnGKWJTsqjLcXaI";

// Check CAPTCHA
$captcha = $_POST['g-recaptcha-response'];
if (!$captcha) {
    echo "Please verify the reCAPTCHA.";
    exit;
}

// Verify CAPTCHA with Google
$verify = file_get_contents(
    "https://www.google.com/recaptcha/api/siteverify?secret={$secretKey}&response={$captcha}"
);
$response = json_decode($verify);
if (!$response->success) {
    echo "CAPTCHA verification failed.";
    exit;
}

// Get form data safely
$name      = isset($_POST['name']) ? htmlspecialchars(trim($_POST['name'])) : '';
$email     = isset($_POST['email']) ? htmlspecialchars(trim($_POST['email'])) : ''; // NEW
$mobile    = isset($_POST['mobile']) ? htmlspecialchars(trim($_POST['mobile'])) : '';
$unit      = isset($_POST['unit']) ? htmlspecialchars(implode(", ", $_POST['unit'])) : "Not selected";
$objective = isset($_POST['objective']) ? htmlspecialchars(implode(", ", $_POST['objective'])) : "Not selected";
$payment   = isset($_POST['payment']) ? htmlspecialchars(implode(", ", $_POST['payment'])) : "Not selected";
$message   = isset($_POST['message']) ? nl2br(htmlspecialchars(trim($_POST['message']))) : '';

// Prepare HTML email
$subject = "New Contact Form Submission";
$body = "
<html>
<head>
  <title>New Contact Form Submission</title>
  <style>
    body { font-family: Arial, sans-serif; line-height: 1.5; }
    .label { font-weight: bold; }
    .section { margin-bottom: 10px; }
  </style>
</head>
<body>
  <div class='section'><span class='label'>Full Name:</span> $name</div>
  <div class='section'><span class='label'>Email:</span> $email</div> <!-- NEW -->
  <div class='section'><span class='label'>Mobile Number:</span> $mobile</div>
  <div class='section'><span class='label'>Unit Type:</span> $unit</div>
  <div class='section'><span class='label'>Objective:</span> $objective</div>
  <div class='section'><span class='label'>Payment Method:</span> $payment</div>
  <div class='section'><span class='label'>Message:</span><br>$message</div>
</body>
</html>
";

// Set headers for HTML email
$headers = "From: info@book.sasithub.com\r\n"; // Keep domain email
$headers .= "Reply-To: $email\r\n"; // Now replies go to the customer
$headers .= "MIME-Version: 1.0\r\n";
$headers .= "Content-Type: text/html; charset=UTF-8\r\n";

// Send email
if (mail($to, $subject, $body, $headers)) {
    echo "Success";
} else {
    echo "Failed to send email.";
}
?>
