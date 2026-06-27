<?php require_once __DIR__ . '/LoggerFactory.php';
$logger = LoggerFactory::getLogger(__FILE__);
require_once __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . '/Singleton.php';

// Pass the path to the directory containing your .env file
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/');
$dotenv->load();

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Sample</title>
</head>

<body>
    <h1>Welcome to My PHP Site</h1>
    <div>
        <?php
        $instance = Singleton::getInstance();
        // Display the current date and time
        echo "Current date and time: " . date("Y-m-d H:i:s");
        $logger->info('Page loaded successfully');
        $dbPassword = $_ENV['MY_KEY'];
        echo "<br/>My key value is : " . $dbPassword;
        echo "<br/> My key1 is : " . $instance->getPproperty("key1");
        $templatePath = $_SERVER['DOCUMENT_ROOT'] . '/email-template/email_template.html';
        echo "<br/> {$templatePath}";

        // 2. Fetch the HTML content safely
        if (file_exists($templatePath)) {
            $htmlContent = file_get_contents($templatePath);

            // 3. Define the dynamic variables you want to insert
            $placeholders = [
                '{{TITLE}}' => 'Email Activation',
                '{{NAME}}' => 'John Doe',
                '{{CTA_URL}}' => 'http://localhost:5000',
                '{{CTA_TEXT}}' => 'Click here to confirm',
                '{{BODY_TEXT}}' => '<p>Please click the link below to activate your email.</p>'
            ];

            // 4. Inject the variables into the HTML template
            $emailBody = str_replace(array_keys($placeholders), array_values($placeholders), $htmlContent);

            // Ready to be assigned to your mailer (e.g., PHPMailer or mail())
            echo $emailBody;
        } else {
            die("Template file not found.");
        }

        ?>

    </div>
</body>

</html>