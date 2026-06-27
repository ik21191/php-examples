<?php
class ContactUsModel {
    private string $name;
    private string $email;
    private string $subject;
    private string $description;

    public function __construct(string $name, string $email, string $subject, string $description) {
        $this->name = $name;
        $this->email = $email;
        $this->subject = $subject;
        $this->description = $description;
    }

    public function getName() {
        return $this->name;
    }

    public function getEmail() {
        return $this->email;
    }

    public function getSubject() {
        return $this->subject;
    }

    public function getDescription() {
        return $this->description;
    }
}
