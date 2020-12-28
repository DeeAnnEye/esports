<?php

include_once('../config/database.php');
include_once('./objects/resource.php');
include_once('token_validate.php');

$db = new Database();


$conn = $db->getConnection();

if ($conn) {

    // echo "Database Connected!";

}

$resource = new Resource($conn);

$token = validateToken();

switch ($_SERVER['REQUEST_METHOD']) {

    case 'GET':
  
      if ($token) {
      // get single resource
        $id = $_GET['id'];
        echo $resource->getResourceById($id);
        break;
      } else {
        echo 'Access Denied!';
      }
      break;
  
    case 'POST':
      $post = $_POST;
    //   // create resource function
      if (isset($post['create'])) {
        if($token){
        echo $resource->createResource($post);
        }
        else{
          echo 'Access Denied!';
        }
      }
      elseif (isset($post['set'])) {
        if($token){
        echo $resource->setPermission($post);
        }
        else{
          echo 'Access Denied!';
        }
      }
      elseif (isset($post['deny'])) {
        if($token){
        echo $resource->denyPermission($post);
        }else{
          echo 'Access Denied!';
        }
      }
    
     // get all resources
      else {
        if ($token) {
          echo $resource->getResources();
        }
         else {
          echo 'Access Denied!';
         }
      }
  
      break;
  
    default:
  }