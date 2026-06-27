<?php
class User {
  // Properties
  public string $name;
  public string $email;

  // Method to set the properties
  function set_details(string $name, string $email) {
    $this->name = $name;
    $this->email = $email;
  }

  // Method to display the properties
  function get_details() {
    echo "Name: " .$this->name ."<br/>";
    echo "Email: " .$this->email;
  }
}
?>