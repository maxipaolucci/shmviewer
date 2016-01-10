<?php

/**
 * When one of the GET params is loaded that means a first load of posts to the DB
 * If first isnt empty then it is a first load and will load the default num of pages or the pages specified
 * using last or (from and to)
 * If last is not empty the will load the last n pages
 */

$path = '.';
include("./includes/ErrorHandler.class.php");
include("./model.php");
include('./SHMParser.class.php');

set_time_limit(1000);

$SHMParser = new SHMParser();
/************************************************************************/
/************ FIRST TIME LOADING DATA ****************/
//this variable indicates that is the first time and the db should be filled because is empty
$first = isset($_GET['first']) ? $_GET['first'] : null;
//this var put in db the last n pages
$last = isset($_GET['last']) ? $_GET['last'] : null;
//this two vars set a range of pages to fill the db with posts
$from = isset($_GET['from']) ? $_GET['from'] : null;
$to = isset($_GET['to']) ? $_GET['to'] : null;
//this var is used to parse just the homepage (first page)
$home = isset($_GET['home']) ? $_GET['home'] : null;
//this var is used to parse a test page, the value should be the number of test page eg: if test3.html then 3
$test = isset($_GET['test']) ? $_GET['test'] : null;

/*********************************************************/
/** FOR RELOAD POSTS THAT WERE LOADED BEFORE WITH CORRUPTED VALUES***************/
// this is the shm id, not mine
$postId = isset($_GET['postid']) ? $_GET['postid'] : null;
//the page where that post is stored in skatehousemedia.com, could be null and we assume it is in home  page
$pageWherePostIs = isset($_GET['page']) ? $_GET['page'] : null;

/****************************PINT VARS***********************************************/
echo '<pre>test page? ' . print_r($test, true);
echo '<pre>home page? ' . print_r($home, true);
echo '<pre>first time? ' . print_r($first, true);
echo '<pre>last_n_pages: ' . print_r($last, true);
echo '<pre>from page: ' . print_r($from, true);
echo '<pre>to page: ' . print_r($to, true);
echo '<pre>post id: ' . print_r($postId, true);
echo '<pre>page where post id is: ' . print_r($pageWherePostIs, true);
/***************************************************************************/

if (!empty($test)) {
  $SHMParser->parseTestPage($test);
} else if (!empty($home)) {
  $SHMParser->parseHomePage();
} else if (!empty($first) || !empty($last) || !empty($from) || !empty($to)) {
  /* to be executed when we are loading the db with new data should be done once, and the lasta data 
   * loaded should be the last data in Skatehousemedia.com */
  $SHMParser->firstPostsLoad($last, $from, $to);
} else if (!empty($postId) || !empty($pageWherePostIs)) {
  $SHMParser->reloadPostsFrom($postId, $pageWherePostIs);
} else {
  /* To be executed by a cron task to get new videos */
  $SHMParser->getLastPosts();
}

?>