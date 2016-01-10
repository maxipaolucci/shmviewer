<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Youtube
 *
 * @author maxi
 */
class Youtube {
  
  private $key = null;
  public static $PROVIDER_NAME = "youtube";
  
  public function __construct($dirtyUrl) {
    $this->extractKey($dirtyUrl);
  }
  
  private function extractKey($dirtyUrl) {
    $keyStartPos = strpos($dirtyUrl, '/embed/');
      
    if ($keyStartPos === FALSE) {
      //another possible url format with v instead of embed 
      $keyStartPos = strpos($dirtyUrl, '/v/') + 3;
    } else {
      $keyStartPos += 7; //move the number of chars in "/embed/"
    }
    $keyPart = substr($dirtyUrl, $keyStartPos);
    $keyEndPos = strpos($keyPart, '?');
    $key = $keyEndPos === FALSE ? substr($keyPart, 0) : substr($keyPart, 0, $keyEndPos);
     
    $this->key = $key;
  }
  
  public function getKey() {
    return $this->key;
  }
  
  public function getVideoUrl() {
    return 'http://www.youtube.com/watch?v=' . $this->key;
  }

  public function getThumbUrl() {
    return "http://img.youtube.com/vi/$this->key/1.jpg";
  }
  
  public function getProvider() {
    return self::$PROVIDER_NAME;
  }
}

?>
