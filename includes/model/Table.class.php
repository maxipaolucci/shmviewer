<?php

abstract class Table
{
  protected function  __construct() {}
  
  /**
   * Execute the query as param
   * @param <string> $query
   * @return For SELECT, SHOW, DESCRIBE, EXPLAIN and other statements returning resultset, mysql_query returns a resource on success, or false on error.
   *   For other type of SQL statements, INSERT, UPDATE, DELETE, DROP, etc, mysql_query returns true on success or false on error.
   */
  public function executeQuery($query) {
    $result = mysql_query($query, Connection::getInstance()->getDBLink());
    if ($result === FALSE) {
      $error = ErrorHandler::getInstance()->getError(ErrorHandler::$MYSQL_EXEC_ERROR, array($query));
      $subject = "SHM VIEWER - SQL error";
      $body = "ERROR IN SQL QUERY: " . $error;
      Mail::getInstance()->send($subject, $body);
      die($error);
    }
    return $result;
  }

  /**
   * Returns the num of rows of the resultset as param
   * @param <resultset> $resultSet
   * @return <int> num or rows
   */
  public function numRows($resultSet) {
    return mysql_num_rows($resultSet);
  }

  /**
   * Returns the id of the last record inserted in the last query
   * @return <int>
   */
  public function lastInsertID() {
    return mysql_insert_id();
  }

  /**
   * Returns a table record as an assoc array
   * @param <resultSet> $record - If the result set has more than one record, this will returns the first one
   * @return <array> the record as an array
   */
  public function getRecordAsArray($record) {
    $result = array();
    $numRows = $this->numRows($record);
    if (!empty($numRows)) {
      $result = mysql_fetch_assoc($record);
    }
    return $result;
  }
  
  /**
   * implement this method to convert an array that represent a complete row of this table
   * into an object that represent each row of this table
   * @param array One complet table row
   * @result object 
   */
  public abstract function arrayToObject($array);
}