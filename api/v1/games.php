<?php

include_once('../config/database.php');
include_once('./objects/game.php');
include_once('token_validate.php');

$db = new Database();


$conn = $db->getConnection();

if ($conn) {

    // echo "Database Connected!";

}

$game = new Game($conn);

$token = validateToken();

switch ($_SERVER['REQUEST_METHOD']) {

    case 'GET':
  
      if ($token) {
      // get single resource
        $id = $_GET['id'];
        echo $game->getGameById($id);
        break;
      } else {
        echo 'Access Denied!';
      }
      break;
  
    case 'POST':
      $post = $_POST;
      // create game function
      if (isset($post['create'])) {
        if($token){
        echo $game->createGame($post);
        }
        else{
          echo 'Access Denied!';
        }
      }
      elseif (isset($post['update'])) {
        if($token){
            $id =  $_GET['id'];
            // $token_user = $token->user_id;
        echo $game->updateGame( $id, $post);
        }
        else{
          echo 'Access Denied!';
        }
      }
    //   elseif (isset($post['deny'])) {
    //     if($token){
    //     echo $resource->denyPermission($post);
    //     }else{
    //       echo 'Access Denied!';
    //     }
    //   }
    
    //  // get all resources
      else {
        if ($token) {
          echo $game->getGames();
        }
         else {
          echo 'Access Denied!';
         }
      }
  
      break;

      case 'DELETE':
        // delete game using id
        if ($token) {
          $id =  $_GET['id'];
          echo $game->deleteGame($id);
        } else {
          echo 'Access Denied!';
        }
        break;
  
    default:
  }