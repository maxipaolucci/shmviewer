<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of User
 *
 * @author maxi
 */
class User {
  
  private $id = 0;
  private $firstname = "";
  private $Lastname = "";
  private $Username = "";
  private $password = "";
  private $email = '';
  private $valid = true; //a checker that the values in each instance of this class are valid an can be stored in db
  
  public function __construct() {}
  
  public function isValid() {
    return $this->valid;
  }
  
  public function getId() {
    return $this->id;
  }

  public function setId($id) {
    $this->id = $id;
  }
  
  public function getFirstname() {
    return $this->firstname;
  }
  
  public function setFirstname($firstname) {
    $this->username = $firstname;
  }
  
  public function getLastname() {
    return $this->lastname;
  }
  
  public function setLastname($name) {
    $this->username = $name;
  }
  
  public function getUsername() {
    return $this->username;
  }

  public function setUsername($name) {
    if (!empty($name) && is_string($name)) {
      $this->username = $name;
    } else {
      $this->valid = false;
    }
  }
  
  public function getPassword() {
    return $this->password;
  }

  public function setPassword($password) {
    if (!empty($password) && is_string($password)) {
      $this->password = $password;
    } else {
      $this->valid = false;
    }
  }

  public function getEmail() {
    return $this->email;
  }

  public function setEmail($email) {
    if (!empty($email) && is_string($email)) {
      $this->email = $email;
    } else {
      $this->valid = false;
    }
  }

}

?>
