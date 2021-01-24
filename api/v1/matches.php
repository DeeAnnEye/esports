<?php

include_once('../config/database.php');
include_once('./objects/match.php');
include_once('token_validate.php');

$db = new Database();


$conn = $db->getConnection();

if ($conn) {

    //   echo "Database Connected!";

}

$match = new Match($conn);


$req = $_SERVER['REQUEST_METHOD'];

$token = validateToken();

switch ($_SERVER['REQUEST_METHOD']) {

    case 'GET':

        if ($token) {
            // get single event
            $id = $_GET['id'];
            echo $match->getMatchById($id);
            break;
        } else {
            echo 'Access Denied!';
        }
        break;

    case 'POST':
        $post = $_POST;
        // create event function
        if (isset($post['create'])) {
            echo $match->createMatch($post);
        }
        // // update event function
        // elseif (isset($post['update'])) {
        //     // print_r($token);
        //     if ($token) {
        //         $id =  $_GET['id'];
        //         $token_user = $token->user_id;
        //         echo $result->updateResult($id, $post, $token_user);
        //     } else {
        //         echo 'Access Denied!';
        //     }
        // }
         elseif (isset($post['setteam'])) {

            if ($token) {
                echo $match->matchTeams($post);
            } else {
                echo 'Access Denied!';
            }
        }
         elseif (isset($post['fixture'])) {

            $id = $_GET['id'];

            if ($token) {
                echo $match->createMatchFixture($id);
            } else {
                echo 'Access Denied!';
            }
        }
        // // get all events
        else {
            if ($token) {
                echo $match->getFixtures();
            } else {
                echo 'Access Denied!';
            }
        }

        break;

    case 'DELETE':
        // delete event using id
        if ($token) {
            $id =  $_GET['id'];
            echo $match->deleteMatch($id);
        } else {
            echo 'Access Denied!';
        }
        break;
    default:
}
