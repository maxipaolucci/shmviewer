<?php
// THIS POST SERVICE EDIT A USER INTO THE USER TABLE

$path = '../..';
include("$path/includes/ErrorHandler.class.php");
include("$path/model.php");

$json = file_get_contents('php://input');
$arrayData = json_decode($json);

$firstname = empty($arrayData->firstname) ? null : $arrayData->firstname;
$lastname = empty($arrayData->lastname) ? null : $arrayData->lastname;
$password = empty($arrayData->password) ? null : $arrayData->password;
$email = empty($arrayData->email) ? null : $arrayData->email;
$admin = empty($arrayData->admin) ? false : $arrayData->admin;
$id = empty($arrayData->id) ? null : $arrayData->id;

if (!empty($id) && !empty($password) && !empty($email)) {
    $user = UserTable::getInstance()->getById($id);
    if (!empty($user)) {
        $user->setFirstname($firstname);
        $user->setLastname($lastname);
        $user->setPassword($password);
        $user->setEmail($email);
        $user->setAdmin($admin);

        $user = UserTable::getInstance()->update($user);
        if ($user == ErrorHandler::$EXISTENT_EMAIL) {
          echo json_encode(array('status' => 'error', 'error_code' => ErrorHandler::$EXISTENT_EMAIL, 'message' => ErrorHandler::getInstance()->getError(ErrorHandler::$EXISTENT_EMAIL)));
        } else if ($user == ErrorHandler::$INCOMPLETE_NEW_USER_DATA) {
          echo json_encode(array('status' => 'error', 'error_code' => ErrorHandler::$INCOMPLETE_NEW_USER_DATA, 'message' => ErrorHandler::getInstance()->getError(ErrorHandler::$INCOMPLETE_NEW_USER_DATA)));
        } else if ($user->getId() > 0) {
            $updatedUser = array('id' => $user->getId(), 
                'firstname' => $user->getFirstname(), 
                'lastname' => $user->getLastname(), 
                'username' => $user->getUsername(), 
                'email' => $user->getEmail(), 
                'admin' => $user->isAdmin());
            echo json_encode(array('user_id' => $user->getId(), 'status' => 'success', 'user' => $updatedUser, 'message' => 'User updated successfully.'));
        } else {
            echo json_encode(array('status' => 'error', 'error_code' => ErrorHandler::$ERROR_SAVING_NEW_USER, 'message' => ErrorHandler::getInstance()->getError(ErrorHandler::$ERROR_SAVING_NEW_USER)));
        }
    } else {
        echo json_encode(array('status' => 'error', 'error_code' => ErrorHandler::$NON_USER_WITH_THAT_ID, 'message' => ErrorHandler::getInstance()->getError(ErrorHandler::$NON_USER_WITH_THAT_ID, array($id))));
    }
} else {
    echo json_encode(array('status' => 'error', 'error_code' => ErrorHandler::$INCOMPLETE_NEW_USER_DATA, 'message' => ErrorHandler::getInstance()->getError(ErrorHandler::$INCOMPLETE_NEW_USER_DATA)));
}
?>
