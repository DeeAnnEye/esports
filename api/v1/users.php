<?php

include_once('../config/database.php');
include_once('./objects/user.php');

$db = new Database();


$conn = $db->getConnection();

if($conn) {
  // echo "Database Connected!";
}

$user = new User($conn);


$req = $_SERVER['REQUEST_METHOD'];

switch($_SERVER['REQUEST_METHOD'])
{

case 'GET': 
  // get single user
  $id = $_GET['id'];
  echo $user->getUserById($id); 
  break;
  
case 'POST': 
  $post= $_POST;
  // create user function
  if (isset($post['create'])){
  echo $user->createUser($post);
  } 
  // update user function
  elseif (isset($post['update'])){
    $id =  $_GET['id'];
    echo $user->updateUser($id,$post);
  } 
  // get all users
  else {
  echo $user->getUsers();
  }
break; 

  case 'DELETE': 
    // delete user using id
    $id =  $_GET['id'];
    echo $user->deleteUser($id);   
break;

default:
}

