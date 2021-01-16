<?php


include_once('../config/database.php');
include_once('./objects/team.php');
include_once('token_validate.php');

$db = new Database();


$conn = $db->getConnection();

if ($conn) {
  //    echo "Database Connected!";
}

$team = new Team($conn);


$req = $_SERVER['REQUEST_METHOD'];

$token = validateToken();

switch ($_SERVER['REQUEST_METHOD']) {

  case 'GET':
    // get single team
    if ($token) {
      $id = $_GET['id'];
      echo $team->getTeamById($id);
      break;
    } else {
      echo 'Access Denied!';
    }
    break;

  case 'POST':
    $post = $_POST;
    // create team function
    if (isset($post['create'])) {
      if ($token) {
      echo $team->createTeam($post);
    }else{
      echo 'Access Denied!';
    }
  }
    //  update team function
    elseif (isset($post['update'])) {
      if ($token) {
        $id =  $_GET['id'];
        $token_user = $token->data->user_id;
        echo $team->updateTeam($id, $post, $token_user);
      } else {
        echo 'Access Denied!';
      }
    }
    // get all teams
    else {
      if ($token) {
        echo $team->getTeams();
      } else {
        echo 'Access Denied!';
      }
    }
    break;

  case 'DELETE':
    // delete team using id
    if ($token) {
      $id =  $_GET['id'];
      echo $team->deleteTeam($id);
    } else {
      echo 'Access Denied!';
    }
    break;

  default:
}
