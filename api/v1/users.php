<?php

include_once('../config/database.php');
include_once('token_validate.php');
include_once('./objects/user.php');

$db = new Database();


$conn = $db->getConnection();

if ($conn) {
  // echo "Database Connected!";
}

$user = new User($conn);

$req = $_SERVER['REQUEST_METHOD'];


$token = validateToken();

switch ($_SERVER['REQUEST_METHOD']) {

  case 'GET':
    // get single user
    if ($token) {
      $id = $_GET['id'];
      echo $user->getUserById($id);
    } else {
      echo 'Access Denied!';
    }
    break;

  case 'POST':
    $post = $_POST;

    // create user function
    if (isset($post['create'])) {
      echo $user->createUser($post);
    }
    // update user function
    elseif (isset($post['update'])) {
      if ($token) {
        $id =  $_GET['id'];
        // $token_user = $token->data->user_id;
        echo $user->updateUser($id, $post);
      } else {
        echo 'Access Denied!';
      }
    }
    
    elseif (isset($post['jointeam'])) {

      if ($token) {
        echo $user->joinTeam($post);
      } else {
        echo 'Access Denied!';
      }
    }
    elseif (isset($post['modrequest'])) {

      if ($token) {
        $id =  $_GET['id'];
        echo $user->moderatorRequest($id);
      } else {
        echo 'Access Denied!';
      }
    }
    elseif (isset($post['updaterole'])) {

      if ($token) {
        $id =  $_GET['id'];
        echo $user->updateRole($id,$post);
      } else {
        echo 'Access Denied!';
      }
    }
    elseif (isset($post['updatemodrole'])) {

      if ($token) {
        $id =  $_GET['id'];
        echo $user->updateModRole($id);
      } else {
        echo 'Access Denied!';
      }
    }
    elseif (isset($post['cancelrequest'])) {

      if ($token) {
        $id =  $_GET['id'];
        echo $user->cancelModRequest($id);
      } else {
        echo 'Access Denied!';
      }
    }

    elseif (isset($post['reqplayers'])) {

      if ($token) {
        echo $user->getUserRequests();
      } else {
        echo 'Access Denied!';
      }
    }
    elseif (isset($post['removeplayer'])) {
      if ($token) {
        echo $user->removeFromTeam($post);
      } else {
        echo 'Access Denied!';
      }
    }
    
    // get all users
    else {
      if ($token) {
        echo $user->getUsers();
      } else {
        echo 'Access Denied!';
      }
    }
    break;

  case 'DELETE':

    // delete user using id
    if ($token) {
      if (isset($_GET['id'])) {
        $id =  $_GET['id'];
        echo $user->deleteUser($id);
      } 
    } else {
      echo 'Access Denied!';
    }
    break;

  default:
}
