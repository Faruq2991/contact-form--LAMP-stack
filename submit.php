<?php
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
        // Send user confirmation email
        $user_subject = "Application Received";
        $user_message = "Dear $full_name,\n\nThank you for your application. We have received your details:\n\n";
        $user_message .= "School: $school_name\n";
        $user_message .= "Status: Pending Review";
        $user_headers = "From: " . ADMIN_EMAIL;
        mail($email, $user_subject, $user_message, $user_headers);

        // Send admin notification
        $admin_subject = "New Application: $full_name";
        $admin_message = "New applicant details:\n\n";
        $admin_message .= "Name: $full_name\n";
        $admin_message .= "Email: $email\n";
        $admin_message .= "Phone: $telephone\n";
        $admin_message .= "School: $school_name";
        $admin_headers = "From: " . ADMIN_EMAIL;
        mail(ADMIN_EMAIL, $admin_subject, $admin_message, $admin_headers);

        header('Location: contact.php?success=1');
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
}
?>
