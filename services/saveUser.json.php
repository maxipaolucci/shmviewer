<?php
// THIS SERVICE INSERTS A USER INTO THE USER TABLE

$path = '..';
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
    echo json_encode(array('post_id' => $id, 'success' => 1, 'post' => array(), 'message' => 'No post found.'));
} else {
    echo json_encode(array('post_id' => $id, 'success' => 1, 'post' => array(), 'message' => 'You must provide a post ID.'));
}
?>
