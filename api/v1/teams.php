<?php


include_once('../config/database.php');
include_once('./objects/team.php');

$db = new Database();


$conn = $db->getConnection();

if($conn) {
//    echo "Database Connected!";
}

$team = new Team($conn);


$req = $_SERVER['REQUEST_METHOD'];

switch($_SERVER['REQUEST_METHOD'])
{

case 'GET': 
  $id = $_GET['id'];
  echo $team->getTeamById($id); 
  break;
case 'POST': 
  $post= $_POST;
  if (isset($post['create'])){
  echo $team->createTeam($post);
  } elseif (isset($post['update'])){
    $id =  $_GET['id'];
    echo $team->updateTeam($id,$post);
  } else {
  echo $team->getTeams();
  }
break; 
  case 'DELETE': 
    $id =  $_GET['id'];
    echo $team->deleteTeam($id);   
break;

default:
}
?>