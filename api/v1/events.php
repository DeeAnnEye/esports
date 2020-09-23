<?php

include_once('../config/database.php');
include_once('./objects/event.php');

$db = new Database();


$conn = $db->getConnection();

if($conn) {

//   echo "Database Connected!";

}

$event = new Event($conn);


$req = $_SERVER['REQUEST_METHOD'];

switch($_SERVER['REQUEST_METHOD'])
{

case 'GET': 
  $id = $_GET['id'];
  echo $event->getEventById($id); 
  break;
case 'POST': 
  $post= $_POST;
  if (isset($post['create'])){
  echo $event->createEvent($post);
  } elseif (isset($post['update'])){
    $id =  $_GET['id'];
    echo $event->updateEvent($id,$post);
  } else {
  echo $event->getEvents();
  }
break; 
  case 'DELETE': 
    $id =  $_GET['id'];
    echo $event->deleteEvent($id);   
break;

default:

}

?>