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
  $id = $_GET['id'];
  echo $user->getUserById($id); 
  break;
case 'POST': 
  $post= $_POST;
  if (isset($post['create'])){
  echo $user->createUser($post);
  } elseif (isset($post['update'])){
    $id =  $_GET['id'];
    echo $user->updateUser($id,$post);
  } else {
  echo $user->getUsers();
  }
break; 
  case 'DELETE': 
    $id =  $_GET['id'];
    echo $user->deleteUser($id);   
break;

default:
}

