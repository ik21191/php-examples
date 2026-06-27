<?php
require_once "Database.php";
require_once "ContactUsModel.php";

class ContactUs extends Database
{
    public function insertContactUs(ContactUsModel $contactUsModel)
    {
        try {
            $connection = $this->getConnection();
            $stmt = $connection->prepare("INSERT INTO contact_us (name, email, subject, description) VALUES (?, ?, ?, ?)");
            $name = $contactUsModel->getName();
            $email = $contactUsModel->getEmail();
            $subject = $contactUsModel->getSubject();
            $description = $contactUsModel->getDescription();       

            $stmt->bind_param("ssss", $name, $email, $subject, $description);
            $stmt->execute();
            $stmt->close();
            return true;
        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }
    }
}
