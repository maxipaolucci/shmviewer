<?php 
/* Conexion a la base de DATOS */

class Connection
{
  private static $link = null;
  
  //localhost
  private $mysql_host = "localhost";
  private $mysql_database = "shmviewer";
  private $mysql_user = "root";
  private $mysql_password = "admin";
  
  private static $instance = null;

  private function __construct() {
    self::$link = mysql_connect($this->mysql_host,$this->mysql_user,$this->mysql_password) or die(ErrorHandler::getInstance()->getError(ErrorHandler::$MYSQL_CONNECTION_ERROR));
    if (self::$link){
      mysql_select_db($this->mysql_database) or die(ErrorHandler::getInstance()->getError(ErrorHandler::$MYSQL_DATABASE_SELECTION_ERROR, array($this->mysql_database)));
    }
  }
  
  public static function getInstance() {
    if (empty(self::$instance) || empty(self::$link)) {
      self::$instance = new Connection();
    }
    return self::$instance;
  }

  /**
   * Retrieves the link needed to execute queries to the database
   * @return <resourse> link
   */
  public function getDBLink() {
    return self::$link;
  }
}

?>
