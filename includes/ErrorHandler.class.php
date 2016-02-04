<?php 
/* Error handler class */

class ErrorHandler
{
  public static $MYSQL_EXEC_ERROR = 1000;
  public static $MYSQL_CONNECTION_ERROR = 1001;
  public static $MYSQL_DATABASE_SELECTION_ERROR = 1002;
  public static $EMPTY_LAST_VIDEO_ID = 1003;
  public static $EMPTY_POST_UPDATE_LAST_ID = 1004;
  public static $ERROR_SAVING_NEW_USER = 1005;
  public static $INCOMPLETE_NEW_USER_DATA = 1006;
  
  private static $instance = null;

  private function __construct() {}
  
  public static function getInstance() {
    if (empty(self::$instance)) {
      self::$instance = new ErrorHandler();
    }
    return self::$instance;
  }

  /**
   * Retrieves the link needed to execute queries to the database
   * @param int $code Message code number
   * @param array params Aditional params to show in the error message (This is optional)
   * @param boolean isError - If true show the prefix ErrorHandler plus code in the message, else its just a message or notification
   * @return <string> Error message
   */
  public function getError($code, $params = array(), $isError = true) {
    return $this->getErrorMsg($code, $params, $isError);
  }

  private function getErrorMsg($code, $params, $isError) {
    if ($isError) {
      $message = "ErrorHandler - ERROR $code: ";
    }

    switch ($code) {
      case self::$MYSQL_EXEC_ERROR:
        $message .= "The query sent to the database failed:<br />$params[0]. " . mysql_error();
        break;

      case self::$MYSQL_CONNECTION_ERROR:
        $message .= "Database connection failed: " . mysql_error();
        break;

      case self::$MYSQL_DATABASE_SELECTION_ERROR:
        $message .= "The database: $params[0] could not be selected (maybe not exist).";
        break;

      case self::$EMPTY_LAST_VIDEO_ID:
        $message .= "lastVideoId param is empty, must be provided.";
        break;
      
      case self::$EMPTY_POST_UPDATE_LAST_ID:
        $message .= "lastId param is empty, must be provided.";
        break;
    
      case self::$ERROR_SAVING_NEW_USER:
        $message .= "The new user could not be saved, an error occurred.";
        break;
      
      case self::$INCOMPLETE_NEW_USER_DATA:
        $message .= "Username, password and email are required fields.";
        break;
    
      default:
        $message .= "ErrorHandler: A non identified error ocurrer.";
        break;
    }

    return $message;
  }
}

?>
