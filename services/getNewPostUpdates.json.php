<?php
// THIS SERVICE RETRIVE JUST THE POSTS UPDATES GREATER THAN THE POST UPDATE ID PASSED AS PARAM

$path = '..';
include("$path/includes/ErrorHandler.class.php");
include("$path/model.php");

$lastId = isset($_GET['lastId']) ? $_GET['lastId'] : null;

if (!is_null($lastId) && $lastId >= 0) {
  $postUpdates = PostUpdateTable::getInstance()->getItemsGreaterThan($lastId);
  $arrayPostUpdates = array();

  if (count($postUpdates)) {
    foreach ($postUpdates as $postUpdate) {
      array_push($arrayPostUpdates, array(
          'id' => $postUpdate->getId(),
          'post_id' => $postUpdate->getPostId())
      );
    }

    echo json_encode(array('postUpdates' => $arrayPostUpdates, 'success' => 1, 'message' => 'ok'));
  } else {
    echo json_encode(array('success' => 1, 'postUpdates' => array(), 'message' => 'None new post updates.'));
  }
} else {
  echo json_encode(array('success' => 0, 'message' => ErrorHandler::getInstance()->getError(ErrorHandler::$EMPTY_POST_UPDATE_LAST_ID)));
}
?>
