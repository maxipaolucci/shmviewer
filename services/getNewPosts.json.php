<?php
// THIS SERVICE RETRIVE JUST THE POSTS POSTED AFTER THE POST ID PASSED AS PARAM

$path = '..';
include("$path/includes/ErrorHandler.class.php");
include("$path/model.php");

$lastPostId = $_GET['lastVideoId'];

if (!empty($lastPostId)) {
  $posts = PostTable::getInstance()->getNewPosts($lastPostId);
  $arrayPosts = array();

  if (count($posts)) {
    foreach ($posts as $post) {
      $postId = $post->getId();
      $oTags = PostTable::getInstance()->getPostTags($postId);
      $aTags = array();
      foreach ($oTags as $oTag) {
        array_push($aTags, array('id' => $oTag->getId(), 
            'name' => $oTag->getName(), 
            'link' => $oTag->getLink()));
      }
      $oCats = PostTable::getInstance()->getPostCategories($postId);
      $aCats = array();
      foreach ($oCats as $oCat) {
        array_push($aCats, array('id' => $oCat->getId(), 
            'name' => $oCat->getName(), 
            'link' => $oCat->getLink()));
      }
      array_push($arrayPosts, array('id' => $postId,
          'post_id' => $post->getPostId(),
          'post_type' => $post->getPostType(),
          'title' => $post->getTitle(),
          'post_url' => $post->getPostUrl(),
          'provider' => $post->getProvider(),
          'video_url' => $post->getVideoUrl(), 
          'posted_on' => $post->getPostedOn(),
          'video_thumb_url' => $post->getVideoThumbUrl(), 
          'categories' => $aCats,
          'tags' => $aTags));
    }

    echo json_encode(array('posts' => $arrayPosts, 'success' => 1, 'message' => 'ok'));
  } else {
    echo json_encode(array('success' => 1, 'posts' => array(),'message' => 'None new videos.'));
  }
} else {
  echo json_encode(array('success' => 0, 'message' => ErrorHandler::getInstance()->getError(ErrorHandler::$EMPTY_LAST_VIDEO_ID)));
}
?>
