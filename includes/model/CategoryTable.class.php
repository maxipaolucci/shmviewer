<?php

class CategoryTable extends Table
{
  private static $instance = null;
  public static $TABLE = 'category';

  protected function  __construct() {
    parent::__construct();
  }

  public static function getInstance() {
    if (self::$instance == null) {
      self::$instance = new CategoryTable();
    }
    return self::$instance;
  }

  /**
   * Return an Category record as an assoc array
   * @param <int> $id
   * @return Category category with the id as param
   */
  public function getCategoryById($id) {
    $query = "SELECT * FROM " . self::$TABLE . " WHERE id = $id LIMIT 1";
    $resultSet = parent::executeQuery($query);
    
    $array = $this->getRecordAsArray($resultSet);
    return $this->arrayToObject($array);
  }
  
  /**
   * Returns Categories
   * @return array<Category>
   */
  public function getCategories() {
    $query = "SELECT * FROM " . self::$TABLE;
    
    $rs = parent::executeQuery($query);
    $result = array();
    while ($row = mysql_fetch_assoc($rs)) {
      array_push($result, $this->arrayToObject($row));
    }
    
    return $result;
  }
  
  /**
   * Returns a category as an assoc array
   * @param <string> $name
   * @return Category category with the name as param
   */
  public function getByName($name) {
    $query = "SELECT * FROM " . self::$TABLE . " WHERE name = '$name' LIMIT 1";
    $resultSet = parent::executeQuery($query);
    
    $array = $this->getRecordAsArray($resultSet);
    return $this->arrayToObject($array);
  }
  
  /**
   * Convert a category array into a category object
   * @param array $aRow 
   * @return Category
   */
  public function arrayToObject($aRow) {
    if (empty($aRow)) {
      return null;
    }
    $category = new Category();
    $category->setId($aRow['id']);
    $category->setName($aRow['name']);
    $category->setLink($aRow['link']);
    return $category;
  }
  
  /**
   * Inserts categories into this Table
   * @param <Category> | array<Category> $categories
   * @return <Category> or null .the the inserted object (just if it not was a 
   * bulk insert, otherwise returns null)
   */
  
  public function save($categories) {
    if (!empty($categories)) {
      $query = "INSERT INTO " . self::$TABLE . "(
                id, 
                name, 
                link) VALUES";
      
      if (is_array($categories)) {
        //make a bulk insert
        $rowsValues = "";
        foreach ($categories as $category) {
          if ($category->isValid()) {
            //check if the category already exists
            $existentCat = $this->getByName($category->getName());
            if (empty($existentCat)) {
              $rowsValues .= self::populateRowValuesFromObject($category) . ", ";
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
        if ($categories->isValid()) {
          //check if the category already exists
          $existentCat = $this->getByName($categories->getName());
          if (empty($existentCat)) {
            $query .= self::populateRowValuesFromObject($categories) . ";";
            parent::executeQuery($query);
            $categories->setId($this->lastInsertID());
            return $categories;
          }
          return $existentCat;
        }
      }
    }
    //category already exists.
    return null;
  }
  
  /**
   * Populates the VALUES section for an INSERT sql statement from a category object
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