<?php
// Load dependencies managed by Composer
require __DIR__ . '/../../php/vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require_once __DIR__ . '/LoggerFactory.php';
require_once __DIR__ . '/EnvLoader.php';

class SendMail
{
    private string $mailFrom;
    private string $mailFromName;
    private string $mailTo;
    private string $mailToName;
    private string $subject;
    private string $body;

    public function __construct(string $mailFrom, string $mailFromName, string $mailTo, string $mailToName, string $subject, string $body)
    {
        $this->mailFrom = $mailFrom;
        $this->mailFromName = $mailFromName;
        $this->mailTo = $mailTo;
        $this->mailToName = $mailToName;
        $this->subject = $subject;
        $this->body = $body;
    }

    public function sendMail(): bool
    {
        $envLoader = EnvLoader::getInstance();
        $logger = LoggerFactory::getLogger(__CLASS__);
        $logger->info("Sending mail to " . $this->mailTo);
        // Create an instance; passing 'true' enables structured error exceptions

        $maxTries = 3;
        $attempts = 0;
        $mailSent = false;
        while ($attempts < $maxTries && !$mailSent) {
            $attempts++;

            $mail = new PHPMailer(true);
            try {
                // 1. SMTP Server Settings Configuration
                // Route email using SMTP
                $mail->isSMTP();
                $mail->Host = $envLoader->getProperty("MAIL_SMTP_PROVIDER");
                // Activate SMTP authentication
                $mail->SMTPAuth = true;
                // Your SMTP account username
                $mail->Username = $this->mailFrom;
                // Your SMTP account password or app-token       
                $mail->Password = $envLoader->getProperty("MAIL_PASSWORD");
                // Secure connection using TLS                        
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
                // TCP port connection (587 or 465)          
                $mail->Port = $envLoader->getProperty("MAIL_SMTP_PORT");

                // 2. Sender and Recipient Address Management
                $mail->setFrom($this->mailFrom, $this->mailFromName); // Define sender's email and name
                $mail->addAddress($this->mailTo, $this->mailToName);
                //$mail->addReplyTo('info@example.com', 'Information Desk');

                // 3. Optional: Incorporating File Attachments
                // $mail->addAttachment('/path/to/document.pdf');          // Attach a local system file

                // 4. Content Formatting (HTML and Text Fallback)
                $mail->isHTML(true);                                        // Format email structure as HTML
                $mail->Subject = $this->subject;
                $mail->Body    = $this->body;
                //$mail->AltBody = 'Hello World! This is the plain text fallback for older email clients.';

                // 5. Fire dispatch action
                $mail->send();
                $logger->info("Mail sent successfully to " . $this->mailTo);
                $mailSent = true;
            } catch (Exception $e) {
                $logger->error("Error while sending mail to " . $this->mailTo);
                $logger->error($e->getMessage());
                $logger->error($mail->ErrorInfo);
                if ($attempts >= $maxTries) {
                    $logger->error("Email failed after {$maxTries} attempts. Last Error: " . $mail->ErrorInfo);
                }
                // Wait before trying again (avoid overloading the server)
                sleep(2); 
            }
        }
        return $mailSent;
    }
}
