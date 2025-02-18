# contact-form--LAMP-stack
This project is a PHP-based web application that allows users to submit their application details (name, email, phone, and school name). The data is stored in a MySQL database, and confirmation emails are sent to both the user and the admin using PHPMailer and Mailgun.

### Prerequisites
Before running the project, ensure you have the following installed:

#### PHP (version 7.4 or higher)

#### MySQL (or any compatible database)

#### Composer (for dependency management)

#### Web Server (Apache, Nginx, or built-in PHP server)

#### Mailgun Account (for sending emails)

#### Setup Instructions
```
-1. Clone the Repository
    git clone https://github.com/your-username/application-submission- 
  system.git
    cd application-submission-system

 2. Install Dependencies
    composer install

 3. Set Up the Database
    CREATE DATABASE applications;

Create the applicants table:
USE applications;

CREATE TABLE applicants (
    id INT AUTO_INCREMENT PRIMARY KEY,
    full_name VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL,
    telephone VARCHAR(20) NOT NULL,
    school_name VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

4. Configure the Project
Write the config file and name it config.php:
Update the config.php file with your database credentials and Mailgun SMTP settings:

php
Copy
<?php
define('DB_HOST', 'localhost');
define('DB_USER', 'your_db_username');
define('DB_PASS', 'your_db_password');
define('DB_NAME', 'application_system');

define('ADMIN_EMAIL', 'admin@example.com'); // Admin email for notifications

// Mailgun SMTP credentials
define('MAILGUN_USERNAME', 'postmaster@your-mailgun-domain.com');
define('MAILGUN_PASSWORD', 'your-mailgun-password');
?>
5. Run the Application
Start the PHP built-in server (or use Apache/Nginx):

bash
Copy
php -S localhost:8000
Open your browser and navigate to:

Copy
http://localhost:8000
Project Structure
Copy
application-submission-system/
├── vendor/                  # Composer dependencies
├── config.php               # Configuration file
├── index.php                # Main application file
├── contact.php              # Form submission handling
├── README.md                # Project documentation
├── composer.json            # Composer configuration
└── composer.lock            # Composer dependency lock file
Key Features
Form Validation: Ensures all fields are filled and sanitizes user inputs.

Database Integration: Stores application data in a MySQL database.

Email Notifications:

Sends a confirmation email to the user.

Sends a notification email to the admin.

Error Handling: Displays meaningful error messages for debugging.

Troubleshooting
Common Issues
HTTP 500 Error:

Check the PHP error logs for details.

Ensure the config.php file is correctly configured.

Verify that the database and Mailgun credentials are correct.

Emails Not Sending:

Ensure Mailgun SMTP credentials are correct.

Check if your Mailgun account has sufficient credits.

Database Connection Issues:

Verify the database credentials in config.php.

Ensure the MySQL server is running.

Contributing
Contributions are welcome! Please follow these steps:

Fork the repository.

Create a new branch for your feature or bugfix.

Submit a pull request with a detailed description of your changes.

License
This project is licensed under the MIT License. See the LICENSE file for details.

Acknowledgments
PHPMailer for email functionality.

Mailgun for SMTP services.
