<?php
// THIS SERVICE INSERTS A USER INTO THE USER TABLE

$path = '../..';
include("$path/includes/ErrorHandler.class.php");
include("$path/model.php");

$firstname = empty($_POST['firstname']) ? null : $_POST['firstname'];
$lastname = empty($_POST['lastname']) ? null : $_POST['lastname'];
$username = empty($_POST['username']) ? null : $_POST['username'];
$password = empty($_POST['password']) ? null : $_POST['password'];
$email = empty($_POST['email']) ? null : $_POST['email'];

if (!empty($username) && !empty($password) && !empty($email)) {
    $user = new User();
    $user->setFirstname($firstname);
    $user->setLastname($lastname);
    $user->setUsername($username);
    $user->setPassword($password);
    $user->setEmail($email);
    
    $user = UserTable::getInstance()->save($user);
    if ($user->getId() > 0) {
        $savedUser = array('id' => $user->getId(), 
            'firstname' => $user->getFirstname(), 
            'lastname' => $user->getLastname(), 
            'username' => $user->getUsername(), 
            'email' => $user->getEmail());
        echo json_encode(array('user_id' => $user->getId(), 'status' => 'success', 'user' => $savedUser, 'message' => 'User saved successfully.'));
    } else {
        echo json_encode(array('status' => 'error', 'error_code' => 1005, 'message' => ErrorHandler::getInstance()->getError(ErrorHandler::$ERROR_SAVING_NEW_USER)));
    }
} else {
    echo json_encode(array('status' => 'error', 'error_code' => 1006, 'message' => ErrorHandler::getInstance()->getError(ErrorHandler::$INCOMPLETE_NEW_USER_DATA)));
}
?>
