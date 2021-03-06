<?php
header("Access-Control-Allow-Origin: http://localhost/esports");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

// files needed to connect to database
include_once '../config/database.php';
include_once './objects/user.php';
include_once './objects/resource.php';

// get database connection
$database = new Database();
$db = $database->getConnection();

// instantiate user and resource object
$user = new User($db);
$resource = new Resource($db);

// get posted data
$data = json_decode(file_get_contents("php://input"));

// set product property values
$user->email = $data->email;
$email_exists = $user->emailExists();

// generate json web token
include_once '../config/core.php';
include_once '../libs/php-jwt-master/src/BeforeValidException.php';
include_once '../libs/php-jwt-master/src/ExpiredException.php';
include_once '../libs/php-jwt-master/src/SignatureInvalidException.php';
include_once '../libs/php-jwt-master/src/JWT.php';

use \Firebase\JWT\JWT;

// check if email exists and if password is correct
if ($email_exists && ($data->password == $user->password)) {
    $role = $user->role;
    $id = $user->user_id;
    // print_r($role);
    $token = array(
        "iat" => $issued_at,
        "exp" => $expiration_time,
        //    "iss" => $issuer,
        "data" => array(
            "user_id" => $user->user_id,
            "first_name" => $user->first_name,
            "last_name" => $user->last_name,
            "email" => $user->email
        )
    );

    // set response code
    http_response_code(200);

    // generate jwt
    $jwt = JWT::encode($token, $key);
    echo json_encode(
        array(
            "message" => "Login Successful",
            "userid" => $id,
            "jwt" => $jwt,
            "role" => $role,
            "status" =>$user->userStatus($id),
            "team" => $user->playerExistsInTeam($id),
            "teamname" => $user->playerTeamName($id),
            "teamimage" => $user->playerTeamImage($id),
            "permissions" => $resource->getPermissionsByRole($role)
        )
    );
}

// login failed
else {

    // set response code
    http_response_code(401);

    // tell the user login failed
    echo json_encode(array("message" => "Login failed."));
}
