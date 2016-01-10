<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of PostUpdate
 *
 * @author maxi
 */
class PostUpdate {
  
  private $id = 0;
  private $postId = null;
  
  public function __construct() {}

  public function getId() {
    return $this->id;
  }

  public function setId($id) {
    $this->id = $id;
  }
  
  public function getPostId() {
    return $this->postId;
  }

  public function setPostId($postId) {
    $this->postId = $postId;
  }

}

?>
