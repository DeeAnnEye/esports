<?php

include_once('../config/database.php');
include_once('./objects/event.php');
include_once('token_validate.php');

$db = new Database();


$conn = $db->getConnection();

if ($conn) {

  //   echo "Database Connected!";

}

$event = new Event($conn);


$req = $_SERVER['REQUEST_METHOD'];

$token = validateToken();

switch ($_SERVER['REQUEST_METHOD']) {

  case 'GET':

    if ($token) {
      // get single event
      $id = $_GET['id'];
      // echo $event->getEventById($id);
      // echo $event->teamInEvent($id);
      $data  = new StdClass;
      $data->event = $event->getEventById($id);
      $data->team = $event->teamInEvent($id); 
      echo json_encode($data);
      break;
    } else {
      echo 'Access Denied!';
    }
    break;

  case 'POST':
    $post = $_POST;
    // print_r($post);
    // break;  
    $files = $_FILES;

    // print_r($files);
    // break;

    // create event function
    if (isset($post['create'])) {
      if ($token) {
      echo $event->createEvent($post);
    }
    else{
      echo 'Access Denied!';
    }
    break;
  }
    // update event function
    elseif (isset($post['update'])) {
      if ($token) {
        $id =  $_GET['id'];
        $token_user = $token->data->user_id;
        echo $event->updateEvent($id, $post, $token_user);
      } else {
        echo 'Access Denied!';
      }
    } elseif (isset($post['teamevent'])) {

      if ($token) {
        echo $event->teamEvent($post);
      } else {
        echo 'Access Denied!';
      }
    } 
    elseif (isset($post['modevent'])) {

      if ($token) {
        echo $event->getEventsByUser($post['user']);
      } else {
        echo 'Access Denied!';
      }
    }
    elseif (isset($post['playerevent'])) {

      if ($token) {
        echo $event->playerEvent($post);
      } else {
        echo 'Access Denied!';
      }
    }
    elseif (isset($post['getarchivedevents'])) {
     
      if ($token) {
        echo $event->getArchivedEvents($post);
      } else {
        echo 'Access Denied!';
      }
      break;
    }
      elseif(isset($files) && sizeof($files)) {
        if ($token) {
        echo $event->fileUpload();
      }
      else{
        echo 'Access Denied!';
      }
      break;
    }
    // get all events
    else {
      if ($token) {
        echo $event->getEvents();
      } else {
        echo 'Access Denied!';
      }
     
    }
    break;
  case 'DELETE':
    // delete event using id
    if ($token) {
      $id =  $_GET['id'];
      echo $event->deleteEvent($id);
    } else {
      echo 'Access Denied!';
    }
    break;
  default:
}
