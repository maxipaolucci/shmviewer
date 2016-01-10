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
class Vimeo {
  
  private $key = null;
  private $videoUrl = null;
  private $thumbUrl = null;
  public static $PROVIDER_NAME = "vimeo";
  
  public function __construct($dirtyUrl) {
    $videoUrl = null;
    $http = strpos($dirtyUrl, 'http') === FALSE ? 'http:' : '';
    //check if string moogaloop is not present because if it is then probably we need the whole dirtyurl
    if (strpos($dirtyUrl, '?') && strpos($dirtyUrl, 'moogaloop') === FALSE) {
      $videoUrl = $http . substr($dirtyUrl, 0, strpos($dirtyUrl, '?'));
    } else {
      $videoUrl = $http . $dirtyUrl;
    }
    $this->videoUrl = $videoUrl;
    $this->extractKey($videoUrl);
  }
  
  private function extractKey($videoUrl) {
    $keyStartPos = strpos($videoUrl, '/video/');
    if ($keyStartPos === FALSE) {
      $key = "0000";
      $this->thumbUrl = null;
    } else {
      $key = substr($videoUrl, $keyStartPos + 7);
      $this->retrieveVideoThumb($key);
    }
    
    $this->key = $key;
  }
  
  private function retrieveVideoThumb($key) {
      $data = file_get_contents("http://vimeo.com/api/v2/video/$key.json");
      if (!empty($data)) {
        $data = json_decode($data);
        $this->thumbUrl = $data[0]->thumbnail_medium;
      } else {
        $this->thumbUrl = null;
      }
  }
  
  public function getKey() {
    return $this->key;
  }
  
  public function getVideoUrl() {
    return $this->videoUrl;
  }

  public function getThumbUrl() {
    return $this->thumbUrl;
  }
  
  public function setThumbUrl($thumbUrl) {
    $this->thumbUrl = $thumbUrl;
  }
  
  public function getProvider() {
    return self::$PROVIDER_NAME;
  }
}

?>
