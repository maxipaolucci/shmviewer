<?php

class UserTable extends Table
{
  private static $instance = null;
  public static $TABLE = 'user';

  protected function  __construct() {
    parent::__construct();
  }

  public static function getInstance() {
    if (self::$instance == null) {
      self::$instance = new UserTable();
    }
    return self::$instance;
  }
  
  /**
   * Returns Users
   * @return array<User>
   */
  public function getAll() {
    $query = "SELECT * FROM " . self::$TABLE;
    
    $rs = parent::executeQuery($query);
    $result = array();
    while ($row = mysql_fetch_assoc($rs)) {
      array_push($result, $this->arrayToObject($row));
    }
    
    return $result;
  }
  
  /**
   * Return a User record as an assoc array
   * @param <int> $id
   * @return User user with the id as param
   */
  public function getById($id) {
    $query = "SELECT * FROM " . self::$TABLE . " WHERE id = $id LIMIT 1";
    $resultSet = parent::executeQuery($query);
    
    $array = $this->getRecordAsArray($resultSet);
    return $this->arrayToObject($array);
  }
  
  /**
   * Returns a User as an assoc array
   * @param <string> $username
   * @return User user with the username as param
   */
  public function getByUsername($username) {
    $query = "SELECT * FROM " . self::$TABLE . " WHERE username = '$username' LIMIT 1";
    $resultSet = parent::executeQuery($query);
    
    $array = $this->getRecordAsArray($resultSet);
    return $this->arrayToObject($array);
  }
  
  /**
   * Returns a User as an assoc array
   * @param <string> $email
   * @return User user with the email as param
   */
  public function getByEmail($email) {
    $query = "SELECT * FROM " . self::$TABLE . " WHERE email = '$email' LIMIT 1";
    $resultSet = parent::executeQuery($query);
    
    $array = $this->getRecordAsArray($resultSet);
    return $this->arrayToObject($array);
  }
  
  /**
   * Convert a user array into a user object
   * @param array $aRow 
   * @return User
   */
  public function arrayToObject($aRow) {
    if (empty($aRow)) {
      return null;
    }
    
    $user = new User();
    $user->setId($aRow['id']);
    $user->setFirstname($aRow['firstname']);
    $user->setLastname($aRow['lastname']);
    $user->setUsername($aRow['username']);
    $user->setPassword($aRow['password']);
    $user->setEmail($aRow['email']);
    $user->setAdmin($aRow['admin']);
    return $user;
  }
  
  public function update($user) {
      if (!empty($user)) {
          $query = "UPDATE " . self::$TABLE . " SET "
                  . "firstname = '" . $user->getFirstname()
                  . "', lastname = '" . $user->getLastname()
                  . "', password = '" . $user->getPassword()
                  . "', email = '" . $user->getEmail()
                  . "', admin = '" . $user->getIsAdmin()
                  . "' WHERE id = " . $user->getId() . ";";
        if ($user->isValid()) {
          //check if the user already exists
          $userByEmail = $this->getByEmail($user->getEmail());
          if (empty($userByEmail)) {
            parent::executeQuery($query);
            return $user;
          } else {
            return ErrorHandler::$EXISTENT_EMAIL;
          }
        } else {
            return ErrorHandler::$INCOMPLETE_NEW_USER_DATA;
        }
    }
    //user already exists.
    return ErrorHandler::$INCOMPLETE_NEW_USER_DATA;
  }
  
  /**
   * Insert a user into this Table
   * @param <User> $user
   * @return <User> or string if the user already exists by username or email 
   */
  public function save($user) {
    if (!empty($user)) {
      $query = "INSERT INTO " . self::$TABLE . "(
                id, 
                firstname,
                lastname,
                username,
                password,
                email,
                admin) VALUES";
       
        if ($user->isValid()) {
          //check if the user already exists
          $userByUsername = $this->getByUsername($user->getUsername());
          $userByEmail = $this->getByEmail($user->getEmail());
          if (empty($userByUsername) && empty($userByEmail)) {
            $query .= self::populateRowValuesFromObject($user) . ";";
            parent::executeQuery($query);
            $user->setId($this->lastInsertID());
            return $user;
          } else if (!empty($userByUsername)) {
              return ErrorHandler::$EXISTENT_USERNAME;
          }
          //otherwise
          return ErrorHandler::$EXISTENT_EMAIL;
        } else {
            return ErrorHandler::$INCOMPLETE_NEW_USER_DATA;
        }
    
    }
    //user already exists.
    return ErrorHandler::$INCOMPLETE_NEW_USER_DATA;
  }
  
  /**
   * Populates the VALUES section for an INSERT sql statement from a user object
   * @param <User> $object
   * @return string 
   */
  private function populateRowValuesFromObject($object) {
    $rowValue = "(" . $object->getId() . ", '"
              . $object->getFirstname() . "','"
              . $object->getLastname() . "','"
              . $object->getUsername() . "','"
              . $object->getPassword() . "','"
              . $object->getEmail() . "','"
              . $object->isAdmin() . "')";
    return $rowValue;
  }
}

?>