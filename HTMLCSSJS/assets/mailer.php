<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get form data
    $full_name = $_POST['full-name'];
    $email = $_POST['email'];
    $phone_number = $_POST['phone-number'];
    $budget = $_POST['budget'];
    $message = $_POST['message'];

    // Create email body
    $to = 'purveshpatil111@gmail.com'; // Change this to your email address
    $subject = 'New Message from Contact Form';
    $body = "Name: $full_name\n";
    $body .= "Email: $email\n";
    $body .= "Phone: $phone_number\n";
    $body .= "Budget: $budget\n";
    $body .= "Message:\n$message";

    // Headers
    $headers = "From: $full_name <$email>\r\n";
    $headers .= "Reply-To: $email\r\n";

    // Check if file uploaded
    if (isset($_FILES['file']) && $_FILES['file']['error'] == UPLOAD_ERR_OK) {
        $file_name = $_FILES['file']['name'];
        $file_tmp = $_FILES['file']['tmp_name'];
        $file_type = $_FILES['file']['type'];
        $file_size = $_FILES['file']['size'];

        // Read file content
        $file_content = file_get_contents($file_tmp);
        $file_content = chunk_split(base64_encode($file_content));

        // Add attachment to email
        $boundary = md5(time());
        $headers .= "Content-Type: multipart/mixed; boundary=\"$boundary\"\r\n\r\n";
        $body = "--$boundary\r\n";
        $body .= "Content-Type: text/plain; charset=ISO-8859-1\r\n";
        $body .= "Content-Transfer-Encoding: 7bit\r\n\r\n";
        $body .= "$message\r\n";
        $body .= "--$boundary\r\n";
        $body .= "Content-Type: $file_type; name=\"$file_name\"\r\n";
        $body .= "Content-Disposition: attachment; filename=\"$file_name\"\r\n";
        $body .= "Content-Transfer-Encoding: base64\r\n";
        $body .= "X-Attachment-Id: " . rand(1000, 99999) . "\r\n\r\n";
        $body .= "$file_content\r\n";
        $body .= "--$boundary--";
    }

    // Send email
    if (mail($to, $subject, $body, $headers)) {
        echo '<div class="alert alert-success messenger-box-contact__msg" role="alert">';
        echo 'Your message was sent successfully.';
        echo '</div>';
    } else {
        echo '<div class="alert alert-danger messenger-box-contact__msg" role="alert">';
        echo 'There was a problem sending your message. Please try again later.';
        echo '</div>';
    }
} else {
    // Redirect back to form if accessed directly
    header("Location: index.html");
    exit;
}
?>
