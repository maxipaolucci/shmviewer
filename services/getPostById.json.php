<?php
// THIS SERVICE RETRIVE A POST BY ID FROM THE POST TABLE
/*
 * If the id is -1 then get a random post
 */

$path = '..';
include("$path/includes/ErrorHandler.class.php");
include("$path/model.php");

$id = empty($_GET['id']) ? null : $_GET['id'];

if ($id < 0) {
    //this means that we have to retrieve a random post
    $maxMinArray = PostTable::getInstance()->getMaxMinIdPosts();
    $id = rand($maxMinArray['min'], $maxMinArray['max']);
}

$post = PostTable::getInstance()->getPostById($id);
$arrayPost = array();
if (!empty($id)) {
    if (!empty($post)) {
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
        $arrayPost = array('id' => $postId,
            'post_id' => $post->getPostId(),
            'post_type' => $post->getPostType(),
            'title' => $post->getTitle(),
            'post_url' => $post->getPostUrl(),
            'provider' => $post->getProvider(),
            'video_url' => $post->getVideoUrl(), 
            'posted_on' => $post->getPostedOn(),
            'video_thumb_url' => $post->getVideoThumbUrl(), 
            'categories' => $aCats,
            'tags' => $aTags);


      echo json_encode(array('post_id' => $id, 'post' => $arrayPost, 'message' => 'ok', 'success' => 1));
    } else {
      echo json_encode(array('post_id' => $id, 'success' => 1, 'post' => array(), 'message' => 'No post found.'));
    }
} else {
    echo json_encode(array('post_id' => $id, 'success' => 1, 'post' => array(), 'message' => 'You must provide a post ID.'));
}
?>
