<?php

class PostUpdateTable extends Table
{
  private static $instance = null;
  public static $TABLE = 'post_update';

  protected function  __construct() {
    parent::__construct();
  }

  public static function getInstance() {
    if (self::$instance == null) {
      self::$instance = new PostUpdateTable();
    }
    return self::$instance;
  }
  
  /**
   * Returns all the rows with column id greater than lastId as param.
   * @return array<PostUpdate>
   */
  public function getItemsGreaterThan($lastId) {
    $query = "SELECT * FROM " . self::$TABLE 
            . " WHERE id > $lastId";
    
    $rs = parent::executeQuery($query);
    $result = array();
    while ($row = mysql_fetch_assoc($rs)) {
      array_push($result, $this->arrayToObject($row));
    }
    
    return $result;
  }
  
  /**
   * Convert a postUpdate array into a postUpdate object
   * @param array $aRow 
   * @return Tag
   */
  public function arrayToObject($aRow) {
    if (empty($aRow)) {
      return null;
    }
    $postUpdate = new PostUpdate();
    $postUpdate->setId($aRow['id']);
    $postUpdate->setPostId($aRow['post_id']);
    return $postUpdate;
  }

}

?>