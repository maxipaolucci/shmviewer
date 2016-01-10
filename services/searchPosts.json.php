<?php
// THIS SERVICE RETRIVE ALL THE POST FROM THE POST TABLE

$path = '..';
include("$path/includes/ErrorHandler.class.php");
include("$path/model.php");

$searchString = empty($_GET['by']) ? null : $_GET['by'];
$pageNum = $_GET['page_num'] === '0' ? 0 : (empty($_GET['page_num']) ? null : $_GET['page_num']);
$pageSize = $_GET['page_size'] === '0' ? 0 : (empty($_GET['page_size']) ? null : $_GET['page_size']);

$posts = PostTable::getInstance()->searchPosts($searchString, $pageNum, $pageSize);
$postsIds = array();
$arrayPosts = array();

if (!empty($posts)) {
  foreach ($posts as $post) {
    $postId = $post->getId();
    array_push($postsIds, $postId);
  }

  $postsTags = PostTable::getInstance()->getPostsTags($postsIds);
  $postsCats = PostTable::getInstance()->getPostsCategories($postsIds);
  
  foreach ($posts as $post) {
    $postId = $post->getId();
    $postTags = $postsTags[$postId];
    $postCats = $postsCats[$postId];
    
    $aTags = array();
    if (is_array($postTags)) {
        foreach ($postTags as $oTag) {
            array_push($aTags, array('id' => $oTag->getId(), 
              'name' => $oTag->getName(), 
              'link' => $oTag->getLink()));
        }
    }
    
    $aCats = array();
    if (is_array($postCats)) {
        foreach ($postCats as $oCat) {
            array_push($aCats, array('id' => $oCat->getId(), 
              'name' => $oCat->getName(), 
              'link' => $oCat->getLink()));
        }
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
  echo json_encode(array('posts' => $arrayPosts, 'message' => 'ok', 'success' => 1));
} else {
  echo json_encode(array('success' => 1, 'posts' => array(), 'message' => 'No posts found for: "' . $searchString . '".'));
}
?>
