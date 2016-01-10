<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Tag
 *
 * @author maxi
 */
class Tag {
  
  private $id = 0;
  private $name = "";
  private $link = null;
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

  public function getName() {
    return $this->name;
  }

  public function setName($name) {
    if (!empty($name) && is_string($name)) {
      $this->name = $name;
    } else {
      $this->valid = false;
    }
  }

  public function getLink() {
    return $this->link;
  }

  public function setLink($link) {
    if (!empty($link) && is_string($link)) {
      $this->link = $link;
    }
  }

}

?>
