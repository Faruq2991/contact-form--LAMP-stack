<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php'; // Adjust the path if needed
require 'config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validate and sanitize inputs
    $full_name = htmlspecialchars(trim($_POST['full_name']));
    $email = filter_var(trim($_POST['email']), FILTER_SANITIZE_EMAIL);
    $telephone = htmlspecialchars(trim($_POST['telephone']));
    $school_name = htmlspecialchars(trim($_POST['school_name']));

    // Validate required fields
    if (empty($full_name) || empty($email) || empty($telephone) || empty($school_name)) {
        die('All fields are required.');
    }

    // Database connection
    $conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Insert data
    $stmt = $conn->prepare("INSERT INTO applicants (full_name, email, telephone, school_name) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $full_name, $email, $telephone, $school_name);

    if ($stmt->execute()) {
        // Create a new PHPMailer instance
        $mail = new PHPMailer(true);
    
        try {
            // Server settings
            $mail->isSMTP(); // Use SMTP
            $mail->Host       = 'smtp.mailgun.org'; // Mailgun SMTP server
            $mail->SMTPAuth   = true; // Enable SMTP authentication
            $mail->Username   = 'postmaster@sandboxfb3780bac80641b0ac46a0115e7b9e04.mailgun.org'; // Mailgun SMTP username (replace with your Mailgun postmaster address)
            $mail->Password   = 'a430ff730591b8af578491d7611dd4cc-ac3d5f74-cb254959'; // Mailgun SMTP password (replace with your Mailgun password)
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS; // Enable TLS encryption
            $mail->Port       = 587; // TCP port for TLS encryption
        
            // Send user confirmation email
            $mail->setFrom(ADMIN_EMAIL, 'Your Organization Name'); // Sender
            $mail->addAddress($email, $full_name); // Recipient (user)
            $mail->Subject = 'Application Received';
            $mail->Body    = "Dear $full_name,<br><br>Thank you for your application. We have received your details:<br><br>";
            $mail->Body   .= "School: $school_name<br>";
            $mail->Body   .= "Status: Pending Review";
            $mail->AltBody = "Dear $full_name,\n\nThank you for your application. We have received your details:\n\n";
            $mail->AltBody .= "School: $school_name\n";
            $mail->AltBody .= "Status: Pending Review";
        
            $mail->send(); // Send the email to the user
        
            // Clear recipients for the next email
            $mail->clearAddresses();
        
            // Send admin notification email
            $mail->addAddress(ADMIN_EMAIL); // Recipient (admin)
            $mail->Subject = "New Application: $full_name";
            $mail->Body    = "New applicant details:<br><br>";
            $mail->Body   .= "Name: $full_name<br>";
            $mail->Body   .= "Email: $email<br>";
            $mail->Body   .= "Phone: $telephone<br>";
            $mail->Body   .= "School: $school_name";
            $mail->AltBody = "New applicant details:\n\n";
            $mail->AltBody .= "Name: $full_name\n";
            $mail->AltBody .= "Email: $email\n";
            $mail->AltBody .= "Phone: $telephone\n";
            $mail->AltBody .= "School: $school_name";
        
            $mail->send(); // Send the email to the admin
        
            // Redirect on success
            header('Location: contact.php?success=1');
        } catch (Exception $e) {
            echo "Email could not be sent. Error: {$mail->ErrorInfo}";
        }        

    $stmt->close();
    $conn->close();
}
?>
