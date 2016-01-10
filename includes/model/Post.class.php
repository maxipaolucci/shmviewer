<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Post
 *
 * @author maxi
 */
class Post {
  
  private $id = 0;
  private $postId = 0;
  private $title = "";
  private $postUrl = null;
  private $videoUrl = null;
  private $postedOn = null;
  private $tags = null;
  private $cats = null;
  private $postType = "video";
  private $provider = null;
  private $videoThumbUrl = null;
  private $valid = true; //a checker that the values in each instance of this class are valid an can be stored in db
  
  public static $POST_TYPE_ARTICLE = "article";
  public static $POST_TYPE_VIDEO = "video";
  
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

  public function getPostId() {
    return $this->postId;
  }
  
  public function getVideoThumbUrl() {
    return $this->videoThumbUrl;
  }

  public function setVideoThumbUrl($videoThumbUrl) {
    if (!empty($videoThumbUrl) && is_string($videoThumbUrl)) {
      $this->videoThumbUrl = $videoThumbUrl;
    }
  }

  public function setPostId($postId) {
    if (!empty($postId) && is_numeric($postId)) {
      $this->postId = $postId;
    } else {
      $this->postId = 0;
      $this->valid = false;
    }
  }

  public function getTitle() {
    return $this->title;
  }

  public function setTitle($title) {
    if (!empty($title) && is_string($title)) {
      $this->title = $title;
    } else {
      $this->title = "";
      $this->valid = false;
    }
  }

  public function getPostUrl() {
    return $this->postUrl;
  }

  public function setPostUrl($postUrl) {
    if (!empty($postUrl) && is_string($postUrl)) {
      $this->postUrl = $postUrl;
    }
  }

  public function getVideoUrl() {
    return $this->videoUrl;
  }

  public function setVideoUrl($videoUrl) {
    if (!empty($videoUrl) && is_string($videoUrl)) {
      $this->videoUrl = $videoUrl;
    }
  }

  public function getPostedOn() {
    return $this->postedOn;
  }

  public function setPostedOn($postedOn) {
    if (!empty($postedOn) && is_string($postedOn)) {
      $this->postedOn = $postedOn;
    }
  }

  public function getTags() {
    return $this->tags;
  }

  public function setTags($tags) {
    $this->tags = $tags;
  }

  public function getCats() {
    return $this->cats;
  }

  public function setCats($cats) {
    $this->cats = $cats;
  }

  public function getPostType() {
    return $this->postType;
  }

  public function setPostType($postType) {
    if ($postType === self::$POST_TYPE_ARTICLE || $postType === self::$POST_TYPE_VIDEO) {
      $this->postType = $postType;
    } else {
      $this->postType = "video";
      $this->valid = false;
    }
  }

  public function getProvider() {
    return $this->provider;
  }

  public function setProvider($provider) {
    if ($provider === Vimeo::$PROVIDER_NAME || $provider === Youtube::$PROVIDER_NAME) {
      $this->provider = $provider;
    }
  }

}

?>
