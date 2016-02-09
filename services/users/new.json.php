<?php
// THIS POST SERVICE INSERTS A USER INTO THE USER TABLE

$path = '../..';
include("$path/includes/ErrorHandler.class.php");
include("$path/model.php");

$json = file_get_contents('php://input');
$arrayData = json_decode($json);

$firstname = empty($arrayData->firstname) ? null : $arrayData->firstname;
$lastname = empty($arrayData->lastname) ? null : $arrayData->lastname;
$username = empty($arrayData->username) ? null : $arrayData->username;
$password = empty($arrayData->password) ? null : $arrayData->password;
$email = empty($arrayData->email) ? null : $arrayData->email;

if (!empty($username) && !empty($password) && !empty($email)) {
    $user = new User();
    $user->setFirstname($firstname);
    $user->setLastname($lastname);
    $user->setUsername($username);
    $user->setPassword($password);
    $user->setEmail($email);
    $user->setAdmin(false);
    
    $user = UserTable::getInstance()->save($user);
    if ($user == ErrorHandler::$EXISTENT_USERNAME) {
      echo json_encode(array('status' => 'error', 'error_code' => ErrorHandler::$EXISTENT_USERNAME, 'message' => ErrorHandler::getInstance()->getError(ErrorHandler::$EXISTENT_USERNAME)));
    } else if ($user == ErrorHandler::$EXISTENT_EMAIL) {
      echo json_encode(array('status' => 'error', 'error_code' => ErrorHandler::$EXISTENT_EMAIL, 'message' => ErrorHandler::getInstance()->getError(ErrorHandler::$EXISTENT_EMAIL)));
    } else if ($user == ErrorHandler::$INCOMPLETE_NEW_USER_DATA) {
      echo json_encode(array('status' => 'error', 'error_code' => ErrorHandler::$INCOMPLETE_NEW_USER_DATA, 'message' => ErrorHandler::getInstance()->getError(ErrorHandler::$INCOMPLETE_NEW_USER_DATA)));
    } else if ($user->getId() > 0) {
        $savedUser = array('id' => $user->getId(), 
            'firstname' => $user->getFirstname(), 
            'lastname' => $user->getLastname(), 
            'username' => $user->getUsername(), 
            'email' => $user->getEmail(), 
            'admin' => $user->isAdmin());
        echo json_encode(array('user_id' => $user->getId(), 'status' => 'success', 'user' => $savedUser, 'message' => 'User saved successfully.'));
    } else {
        echo json_encode(array('status' => 'error', 'error_code' => ErrorHandler::$ERROR_SAVING_NEW_USER, 'message' => ErrorHandler::getInstance()->getError(ErrorHandler::$ERROR_SAVING_NEW_USER)));
    }
} else {
    echo json_encode(array('status' => 'error', 'error_code' => ErrorHandler::$INCOMPLETE_NEW_USER_DATA, 'message' => ErrorHandler::getInstance()->getError(ErrorHandler::$INCOMPLETE_NEW_USER_DATA)));
}
?>
