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
  // get single team
  $id = $_GET['id'];
  echo $team->getTeamById($id); 
  break;

case 'POST': 
  $post= $_POST;
  // create team function
  if (isset($post['create'])){
  echo $team->createTeam($post);
  }
  //  update team function
   elseif (isset($post['update'])){
    $id =  $_GET['id'];
    echo $team->updateTeam($id,$post);
  } 
  // get all teams
  else {
  echo $team->getTeams();
  }
break; 

  case 'DELETE': 
    // delete team using id
    $id =  $_GET['id'];
    echo $team->deleteTeam($id);   
break;

default:
}
?>