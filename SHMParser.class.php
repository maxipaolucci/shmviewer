<?php

include('./includes/lib/simple_html_dom.php');

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of SHMParser
 *
 * @author maxi
 */
class SHMParser {
  
  private static $PAGES_TO_PARSE = 3;
  private static $SHM_URL = 'http://www.skatehousemedia.com/home';
  private static $SHM_URL_FOR_PAGE = 'http://www.skatehousemedia.com/home/page/';
  private static $SHM_TEST_PAGE_URL = 'http://shmviewer.localhost/test_pages/';
  
  //PARSER SELECTORS
  private static $POSTS_SELECTOR = 'section[class=mk-blog-container] article';
  private static $POST_TITLE_SELECTOR = 'div[class=mk-blog-meta] h3[class=the-title] a'; 
  private static $POST_POSTED_ON_SELECTOR = 'div[class=mk-blog-meta] div[class=mk-blog-meta-wrapper] time a'; 
  private static $POST_VIDEO_URL_SELECTOR = 'div[class=mk-video-wrapper] iframe'; 
  private static $POST_VIDEO_URL_2_SELECTOR = 'div[class=entry-content] p object param[name=src]';
  private static $POST_VIDEO_URL_3_SELECTOR = 'div[class=entry-content] p object param[name=movie]';
  private static $POST_VIDEO_URL_4_SELECTOR = 'div[class=entry-content] p object embed';
  private static $POST_VIDEO_URL_5_SELECTOR = 'div[class=entry-summary] p object param[name=movie]';
  private static $POST_VIDEO_URL_6_SELECTOR = 'div[class=entry-summary] p object embed';
  private static $POST_VIDEO_URL_7_SELECTOR = 'div[class=entry-summary] p iframe'; 
  private static $POST_TAGS_SELECTOR = 'div[class=entry-utility] span[class=tag-links] a';
  private static $POST_CATEGORIES_SELECTOR = 'div[class=entry-utility] span[class=cat-links] a';
  
  public function __construct() {}
  
  /**
  *  get the last posts page from SHM and insert in the db just the new posts not already stored
  */
  public function getLastPosts() {
    $lastPostOnDB = PostTable::getInstance()->getLastPost_PostId();

    $html = file_get_html(self::$SHM_URL);
    $postsRaw = $html->find(self::$POSTS_SELECTOR);
    $postsRaw = $this->discardOldPosts($postsRaw, $lastPostOnDB);
    $this->parseAndInsertInDB($postsRaw);
  }
  
  /**
   * Reloads in the DB all the posts published after postid (exclusive).
   * @param type $postId - The post_id (shm id, not mine) of the last post correctly stored in db, this one not be loaded again
   * @param type $pageWherePostIs - The page where this post is, if null we assume that it is on the last page (home)
   */
  public function reloadPostsFrom($postId, $pageWherePostIs) {
    if (empty($postId)) {
      return false;
    }
    $url = "";
    if (empty($pageWherePostIs)) {
      $url = self::$SHM_URL;
    } else {
      $url = self::$SHM_URL_FOR_PAGE . $pageWherePostIs;
    }
    
    //insert on db the post after the one as param in the indicated page
    $html = file_get_html($url);
    $postsRaw = $html->find(self::$POSTS_SELECTOR);
    $postsRaw = $this->discardOldPosts($postsRaw, $postId);
    $this->parseAndInsertInDB($postsRaw);
    
    /* if the page param was not empty, download the follwing pages until home page */
    if (!empty($pageWherePostIs)) {
      $this->firstPostsLoad($pageWherePostIs - 1);
    }
  }

  /**
   * This method should be run the first time with an empty db. Insert into it all the post of the last n pages
   * @param int last_n_pages Could be null o the number of last n pages to parse
   * @param int from Works together with $to, both should be filled and to >= from. From which page start parsing
   * @param int to Works together with from, both should be filled and to >= from. Until which page end parsing.
   */
  public function firstPostsLoad($last_n_pages = null, $from = null, $to = null) {
    $from_page = null;
    $to_page = null;
    if (empty($from) || empty($to) || $to < $from) {
      $from_page = 2; //first page that is not  .../home
      $to_page = $last_n_pages == null ? self::$PAGES_TO_PARSE : $last_n_pages;
    } else {
      $from_page = $from;
      $to_page = $to;
    }
    
    $postsRaw = null;
    // get DOM from URL or file
    // go in reverse order to get the post inserted in the right order
    for ($i = $to_page; $i >= $from_page; $i-- ) {
      $pageUrl = self::$SHM_URL_FOR_PAGE . $i;
      $html = file_get_html($pageUrl);
      $postsRaw = $html->find(self::$POSTS_SELECTOR);
      $this->parseAndInsertInDB($postsRaw);
    }
    
    if (empty($from) || empty($to) || $to < $from) {
      $html = file_get_html(self::$SHM_URL);
      $postsRaw = $html->find(self::$POSTS_SELECTOR);
      $this->parseAndInsertInDB($postsRaw);
    }
  }
  
  
  public function parseHomePage() {
    $html = file_get_html(self::$SHM_URL);
    $postsRaw = $html->find(self::$POSTS_SELECTOR);
    $this->parseAndInsertInDB($postsRaw);
  }
  
  public function parseTestPage($testNumber) {
    $html = file_get_html(self::$SHM_TEST_PAGE_URL . 'test' . $testNumber . '.html');
    $postsRaw = $html->find(self::$POSTS_SELECTOR);
    $this->parseAndInsertInDB($postsRaw, true);
  }

  /**
  * get just the posts that are new for the db from the last page of posts
  * @param type $postsRaw
  * @param type $lastPostOnDB (shm post id, not mine)
  * @return array 
  */
  private function discardOldPosts($postsRaw, $lastPostOnDB) {
    $newPosts = array();
    $postsRawSize = count($postsRaw);
    $lastPostOnDBPositionInArray = $postsRawSize - 1;
    if ($postsRawSize > 0) {
      for ($i = $postsRawSize - 1; $i >= 0; $i--) {
        $postRaw = $postsRaw[$i];
        $postId = substr($postRaw->id, 5);
        if ($postId == $lastPostOnDB) {
          $lastPostOnDBPositionInArray = $i;
          break;
        }
      }

      if ($lastPostOnDBPositionInArray >= 0) {
        for ($i = 0; $i < $lastPostOnDBPositionInArray; $i++) {
          array_push($newPosts, $postsRaw[$i]);
        }
      }
    }

    return $newPosts;
  }
  
  /**
  * parse a list of post from shm and insert those into the db
  * @param type $postsRaw 
  */
  private function parseAndInsertInDB($postsRaw, $print = false) {
    $postsRawSize = count($postsRaw);
    if ($postsRawSize > 0) {
      for ($i = $postsRawSize - 1; $i >= 0; $i--) {
        $postRaw = $postsRaw[$i];
        $aTitle = $postRaw->find(self::$POST_TITLE_SELECTOR, 0);
        $title = $aTitle->innertext;
        $postUrl = $aTitle->href;
        $postedOn = $postRaw->find(self::$POST_POSTED_ON_SELECTOR, 0)->innertext;
        $videoUrl = $postRaw->find(self::$POST_VIDEO_URL_SELECTOR, 0)->src;
        //another way to get a video url
        if (empty($videoUrl)) {
          $videoUrl = $postRaw->find(self::$POST_VIDEO_URL_2_SELECTOR, 0)->value;
        }
        //another way to get a video url
        if (empty($videoUrl)) {
          $videoUrl = $postRaw->find(self::$POST_VIDEO_URL_3_SELECTOR, 0)->value;
        }
        //another way to get a video url
        if (empty($videoUrl)) {
          $videoUrl = $postRaw->find(self::$POST_VIDEO_URL_4_SELECTOR, 0)->src;
        }
        //another way to get a video url
        if (empty($videoUrl)) {
          $videoUrl = $postRaw->find(self::$POST_VIDEO_URL_5_SELECTOR, 0)->value;
        }
        //another way to get a video url
        if (empty($videoUrl)) {
          $videoUrl = $postRaw->find(self::$POST_VIDEO_URL_6_SELECTOR, 0)->src;
        }
        //another way to get a video url
        if (empty($videoUrl)) {
          $videoUrl = $postRaw->find(self::$POST_VIDEO_URL_7_SELECTOR, 0)->src;
        }
        $video = $this->getVideoProvider($videoUrl);
        $postType = empty($video) ? Post::$POST_TYPE_ARTICLE : Post::$POST_TYPE_VIDEO;
        $postId = substr($postRaw->id, 5);
        
        //get tags
        $aTags = $postRaw->find(self::$POST_TAGS_SELECTOR);
        $tags = array();
        foreach ($aTags as $aTag) {
          $tag = new Tag();
          $tag->setName(addslashes($aTag->innertext));
          $tag->setLink($aTag->href);
          $tag = TagTable::getInstance()->save($tag);
          if ($tag->isValid()) {
            array_push($tags, $tag);
          }
        }
        
        //get cats
        $aCats = $postRaw->find(self::$POST_CATEGORIES_SELECTOR);
        $cats = array();
        foreach ($aCats as $aCat) {
          $cat = new Category();
          $cat->setName(addslashes($aCat->innertext));
          $cat->setLink($aCat->href);
          $cat = CategoryTable::getInstance()->save($cat);
          if ($cat->isValid()) {
            array_push($cats, $cat);
          }
        }
        
        $post = new Post();
        $post->setPostId($postId);
        $post->setCats($cats);
        $post->setPostType($postType);
        $post->setPostUrl($postUrl);
        $post->setPostedOn($postedOn);
        $post->setTags($tags);
        $post->setTitle($title);
        if (!empty($video)) {
          $post->setProvider($video->getProvider());
          $post->setVideoUrl($video->getVideoUrl());
          $post->setVideoThumbUrl($video->getThumbUrl());
        }
        
        if (empty($print)) {
          $post = PostTable::getInstance()->save($post);
        } else {
          echo '<pre>' . print_r($post, true);
        }
      }
      
    } else {
      echo "</br></br></br>No hay posts nuevos para insertar.</br></br></br>";
    }
  }

  /**
  * Get the video provider from a video url
  * @param type $url
  * @return string 
  */
  private function getVideoProvider($url) {
    $video = null;

    if (!empty($url)) {
      if (strpos($url, Youtube::$PROVIDER_NAME)) {
        $video = new Youtube($url);
      } elseif (strpos($url, Vimeo::$PROVIDER_NAME)) {
        $video = new Vimeo($url);
      }
    }

    return $video;
  }
}

?>
