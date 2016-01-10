<?php

class TagTable extends Table
{
  private static $instance = null;
  public static $TABLE = 'tag';

  protected function  __construct() {
    parent::__construct();
  }

  public static function getInstance() {
    if (self::$instance == null) {
      self::$instance = new TagTable();
    }
    return self::$instance;
  }

  /**
   * Return an Tag record as an assoc array
   * @param int $id
   * @return Tag tag with the id as param
   */
  public function getTagById($id) {
    $query = "SELECT * FROM " . self::$TABLE . " WHERE id = $id LIMIT 1";
    $resultSet = parent::executeQuery($query);
    
    $array = $this->getRecordAsArray($resultSet);
    return $this->arrayToObject($array);
  }
  
  /**
   * Returns Tags
   * @return array<Tag>
   */
  public function getTags() {
    $query = "SELECT * FROM " . self::$TABLE;
    
    $rs = parent::executeQuery($query);
    $result = array();
    while ($row = mysql_fetch_assoc($rs)) {
      array_push($result, $this->arrayToObject($row));
    }
    
    return $result;
  }
  
  /**
   * Returns a tag as an assoc array
   * @param <string> $name
   * @return Tag tag with the name as param
   */
  public function getByName($name) {
    $query = "SELECT * FROM " . self::$TABLE . " WHERE name = '$name' LIMIT 1";
    $resultSet = parent::executeQuery($query);
    
    $array = $this->getRecordAsArray($resultSet);
    return $this->arrayToObject($array);
  }
  
  
  /**
   * Convert a category array into a tag object
   * @param array $aRow 
   * @return Tag
   */
  public function arrayToObject($aRow) {
    if (empty($aRow)) {
      return null;
    }
    $tag = new Tag();
    $tag->setId($aRow['id']);
    $tag->setName($aRow['name']);
    $tag->setLink($aRow['link']);
    return $tag;
  }
  
  /**
   * Inserts tags into this Table
   * @param <Tag> | array<Tag> $tags
   * @return <Tag> or null .the inserted object (just if it not was a 
   * bulk insert, otherwise returns null)
   */  
  public function save($tags) {
    if (!empty($tags)) {
      $query = "INSERT INTO " . self::$TABLE . "(
                id, 
                name, 
                link) VALUES";
      
      if (is_array($tags)) {
        //make a bulk insert
        $rowsValues = "";
        foreach ($tags as $tag) {
          if ($tag->isValid()) {
            //check if the tag already exists
            $existentTag = $this->getByName($tag->getName());
            if (empty($existentTag)) {
              $rowsValues .= self::populateRowValuesFromObject($tag) . ", ";
            }
          }
        }
        if (!empty($rowsValues)) {
          $rowsValues = substr($rowsValues, 0, strlen($rowsValues) - 2);
          $query .= $rowsValues . ";";
          parent::executeQuery($query);
        }
      } else {
        //make a single row insert
        if ($tags->isValid()) {
          //check if the tag already exists
          $existentTag = $this->getByName($tags->getName());
          if (empty($existentTag)) {
            $query .= self::populateRowValuesFromObject($tags) . ";";
            parent::executeQuery($query);
            $tags->setId($this->lastInsertID());
            return $tags;
          }
          return $existentTag;
        }
      }
    }
    //tag already exists
    return null;
  }
  
  /**
   * Populates the VALUES section for an INSERT sql statement from a tag object
   * @param <Tag> $object
   * @return string 
   */
  private function populateRowValuesFromObject($object) {
    $rowValue = "(" . $object->getId() . ", '"
              . $object->getName() . "','"
              . $object->getLink() . "')";
    return $rowValue;
  }
}

?>