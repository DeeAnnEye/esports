<?php
include_once('./objects/event.php');
include_once('./objects/team.php');

class user
{

    // database connection and table name
    private $conn;
    private $table_name = "users";
    private $team;
    private $event;

    // object properties
    public $user_id;
    public $first_name;
    public $last_name;
    public $usertag;
    public $email;
    public $phone;
    public $password;
    public $image;
    public $games;
    public $social_acc;
    public $role;
    public $language;
    public $region;
    public $mod_request;
    public $active;
    public $blocked;
    public $flag;
    public $created;
    public $modified;

    // constructor with $db as database connection
    public function __construct($db)
    {
        $this->conn = $db;
        $this->event = new Event($this->conn);
        $this->team = new Team($this->conn);
    }

    public function getUsers()
    {

        // select all query
        $query = "SELECT u.*,tm.`name` as teamname from users u
        LEFT JOIN team_player t ON u.user_id = t.player_id
        LEFT JOIN teams tm ON t.team_id = tm.id 
        WHERE u.active = 1
        order by user_id";

        // prepare query statement
        $stmt = $this->conn->prepare($query);

        // execute query
        $stmt->execute();

        $users = [];

        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            // extract row
            // this will make $row['name'] to
            // just $name only

            $user_item = array(
                "user_id" => $row['user_id'],
                "first_name" => $row['first_name'],
                "last_name" => $row['last_name'],
                "usertag" => $row['usertag'],
                "email" => $row['email'],
                "phone" => $row['phone'],
                "password" => $row['password'],
                "image" => $row['image'],
                "games" => $row['games'],
                "social_acc" => $row['social_acc'],
                "role" => $row['role'],
                "language" => $row['language'],
                "region" => $row['region'],
                "mod_request" => $row['mod_request'],
                "active" => $row['active'],
                "blocked" => $row['blocked'],
                "flag" => $row['flag'],
                "team" => $row['teamname'],
                "created" => $row['created'],
                "modified" => $row['modified']
            );

            array_push($users, $user_item);
        }

        return json_encode($users);
    }
    public function getUserById($id)
    {

        // select all query
        $query = "SELECT
        u.*, 
        tm.`name` as teamname
    FROM
        `users` u
    LEFT JOIN team_player t ON u.user_id = t.player_id
    LEFT JOIN teams tm ON t.team_id = tm.id
    WHERE
        1
    and user_id=$id";

        // prepare query statement
        $stmt = $this->conn->prepare($query);

        // execute query
        $stmt->execute();

        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        // todo fetch role,events,teams,teamreq

        return json_encode($user);

        // $user_teams = json_decode($this->team->getTeams());


        return json_encode(["profile" => $user, "teams" => $user_teams]);
    }
    public function deleteUser($id)
    {

        // query to inactivate
        $query = "update users set active=0 where 1 and user_id = ?";

        // prepare query statement
        $stmt = $this->conn->prepare($query);

        // bind id of record to delete
        $stmt->bindParam(1, $id, PDO::PARAM_INT);

        return json_encode(["success" => $stmt->execute()]);
    }

    public function createUser($data)
    {
        try {
            //code...
            $rolequery = "SELECT role_id from roles where 1 and name= 'player' ";

            // prepare query statement
            $stmt = $this->conn->prepare($rolequery);

            // execute query
            $stmt->execute();

            $row = $stmt->fetch(PDO::FETCH_ASSOC);

            $user_role = $row['role_id'];


            // query to insert record
            $query = "INSERT INTO " . $this->table_name .
                " SET user_id=0, 
         first_name= null,
         last_name=null,
         email=:email,
         phone=null,
         password=:password,
         usertag=:usertag,
         image=null,
         games=null,
         language=null,
         region=null,
         social_acc=null,
         role=:role,
         mod_request=0,
         active=1,
         blocked=0,
         flag=0";

            // prepare query
            $stmt = $this->conn->prepare($query);


            // sanitize
            $this->usertag = htmlspecialchars(strip_tags($data['usertag']));
            $this->email = htmlspecialchars(strip_tags($data['email']));
            $this->password = htmlspecialchars(strip_tags($data['password']));

            if (
                !empty($this->usertag) &&
                !empty($this->email) &&
                !empty($this->password)
            ) {

                $stmt->bindParam(":usertag", $this->usertag);
                $stmt->bindParam(":email", $this->email);
                $stmt->bindParam(":password", $this->password);
                $stmt->bindParam(":role", $user_role);

                // execute query
                if ($stmt->execute()) {

                    // set response code
                    http_response_code(200);

                    echo json_encode(array("message" => "True"));
                } else {

                    // set response code
                    http_response_code(400);
                    echo json_encode(array("message" => "False"));
                }
            } else {
                // set response code
                http_response_code(400);
                echo json_encode(array("message" => "Fields empty"));
            }
        } catch (PDOException $e) {
            throw $e;
        }
    }

    public function updateUser($id, $data, $token_user)
    {
        try {
            //code...

            // update query
            $query = "UPDATE  $this->table_name " . "
            SET 
            first_name=:first_name,
            last_name=:last_name,
            email=:email,
            phone=:phone,
            password=:password,
            image=:image,
            games=:games,
            social_acc=:social_acc,
            role=:role,
            language=:language,
            region=:region,
            mod_request=:mod_request,
            active=:active,
            blocked=:blocked,
            flag=:flag,
            usertag=:usertag             
            WHERE user_id = :user_id";

            // prepare query statement
            $stmt = $this->conn->prepare($query);


            // sanitize
            // $this->user_id=htmlspecialchars(strip_tags($data['user_id']));
            $this->first_name = htmlspecialchars(strip_tags($data['first_name']));
            $this->last_name = htmlspecialchars(strip_tags($data['last_name']));
            $this->usertag = htmlspecialchars(strip_tags($data['usertag']));
            $this->email = htmlspecialchars(strip_tags($data['email']));
            $this->phone = htmlspecialchars(strip_tags($data['phone']));
            $this->password = htmlspecialchars(strip_tags($data['password']));
            $this->image = htmlspecialchars(strip_tags($data['image']));
            $this->games = htmlspecialchars(strip_tags($data['games']));
            $this->social_acc = htmlspecialchars(strip_tags($data['social_acc']));
            $this->role = htmlspecialchars(strip_tags($data['role']));
            $this->language = htmlspecialchars(strip_tags($data['language']));
            $this->region = htmlspecialchars(strip_tags($data['region']));
            $this->mod_request = htmlspecialchars(strip_tags($data['mod_request']));
            $this->active = htmlspecialchars(strip_tags($data['active']));
            $this->blocked = htmlspecialchars(strip_tags($data['blocked']));
            $this->flag = htmlspecialchars(strip_tags($data['flag']));
           

            // bind new values
            $stmt->bindParam(":first_name", $this->first_name);
            $stmt->bindParam(":last_name", $this->last_name);
            $stmt->bindParam(":usertag", $this->usertag);
            $stmt->bindParam(":email", $this->email);
            $stmt->bindParam(":phone", $this->phone);
            $stmt->bindParam(":password", $this->password);
            $stmt->bindParam(":image", $this->image);
            $stmt->bindParam(":games", $this->games);
            $stmt->bindParam(":social_acc", $this->social_acc);
            $stmt->bindParam(":role", $this->role);
            $stmt->bindParam(":language", $this->language);
            $stmt->bindParam(":region", $this->region);
            $stmt->bindParam(":mod_request", $this->mod_request);
            $stmt->bindParam(":active", $this->active);
            $stmt->bindParam(":blocked", $this->blocked);
            $stmt->bindParam(":flag", $this->flag);
            $stmt->bindParam(":user_id", $id);

            // print_r($stmt->debugDumpParams());
            // execute the query
            return json_encode(["success" => $stmt->execute()]);
        } catch (PDOException $e) {
            throw $e;
        }
    }

    // check if given email exist in the database
    function emailExists()
    {

        // query to check if email exists
        $query = "SELECT user_id, first_name, last_name, password, role
			FROM " . $this->table_name . "
			WHERE email = ?
			LIMIT 0,1";

        // prepare the query
        $stmt = $this->conn->prepare($query);

        // sanitize
        $this->email = htmlspecialchars(strip_tags($this->email));

        // bind given email value
        $stmt->bindParam(1, $this->email);

        // execute the query
        $stmt->execute();

        // get number of rows
        $num = $stmt->rowCount();

        // if email exists, assign values to object properties for easy access and use for php sessions
        if ($num > 0) {

            // get record details / values
            $row = $stmt->fetch(PDO::FETCH_ASSOC);

            // assign values to object properties
            $this->user_id = $row['user_id'];
            $this->first_name = $row['first_name'];
            $this->last_name = $row['last_name'];
            $this->password = $row['password'];
            $this->role = $row['role'];

            // return true because email exists in the database
            return true;
        }

        // return false if email does not exist in the database
        return false;
    }

   public function joinTeam($data)
    {
        try {
            //code...

            // query to insert record
            $query = "INSERT INTO team_player SET
          team_id=:team_id,
          player_id=:player_id,
          removed=:removed";

            // prepare query statement
            $stmt = $this->conn->prepare($query);

            // bind parameters
            $stmt->bindParam(":team_id", $data['team_id']);
            $stmt->bindParam(":player_id", $data['player_id']);
            $stmt->bindParam(":removed", $data['removed']);


            // execute query
            return json_encode(["success" => $stmt->execute()]);
        } catch (PDOException $e) {
            throw $e;
        }
    }

    function removeFromTeam($data)
    {
        try {
            //code...
            // query to inactivate
            $query = "update team_player set removed=1 where 1 and team_id = ? and player_id = ?";

            // prepare query statement
            $stmt = $this->conn->prepare($query);

            // bind id of record to delete
            $stmt->bindParam(1, $data['team_id'], PDO::PARAM_INT);
            $stmt->bindParam(2, $data['player_id'], PDO::PARAM_INT);

            return json_encode(["success" => $stmt->execute()]);
        } catch (PDOException $e) {
            throw $e;
        }
    }

    function playerExistsInTeam($id){

        // select all query
        $query = "SELECT
                  tp.team_id as team_id,
                  t.`name` as teamname
                  FROM
                  team_player tp
                  LEFT JOIN teams t
                  ON t.id = tp.team_id
                  where player_id=$id";

        // prepare query statement
        $stmt = $this->conn->prepare($query);

         // execute the query
        $stmt->execute();

        // get number of rows
        $num = $stmt->rowCount();

        if($num>0){

            // fetch details
            $row = $stmt->fetch(PDO::FETCH_ASSOC);

            $team = $row['team_id'];            

            return $team;

        }
        else{
            return false;
        }


    }

    function playerTeamName($id){

        // select all query
        $query = "SELECT
                  tp.team_id as team_id,
                  t.`name` as teamname
                  FROM
                  team_player tp
                  LEFT JOIN teams t
                  ON t.id = tp.team_id
                  where player_id=$id";

        // prepare query statement
        $stmt = $this->conn->prepare($query);

         // execute the query
        $stmt->execute();

        // get number of rows
        $num = $stmt->rowCount();

        if($num>0){

            // fetch details
            $row = $stmt->fetch(PDO::FETCH_ASSOC);

            $team = $row['teamname'];            

            return $team;

        }
        else{
            return false;
        }


    }

    function userStatus($id){

        // select all query
        $query = "SELECT active FROM `users` where user_id =$id";

        // prepare query statement
        $stmt = $this->conn->prepare($query);

         // execute the query
        $stmt->execute();

        // get number of rows
        $num = $stmt->rowCount();

        if($num>0){

            // fetch details
            $row = $stmt->fetch(PDO::FETCH_ASSOC);

            $status = $row['active'];            

            return $status;

        }
        else{
            return false;
        }


    }


    function playerTeamImage($id){
        
        // select all query
        $query = "SELECT
                  tp.team_id as team_id,
                  t.`image` as `image`
                  FROM
                  team_player tp
                  LEFT JOIN teams t
                  ON t.id = tp.team_id
                  where player_id=$id";

        // prepare query statement
        $stmt = $this->conn->prepare($query);

         // execute the query
        $stmt->execute();

        // get number of rows
        $num = $stmt->rowCount();

        if($num>0){

            // fetch details
            $row = $stmt->fetch(PDO::FETCH_ASSOC);

            $team = $row['image'];            

            return $team;

        }
        else{
            return false;
        }


    }

    function moderatorRequest($id){

        // query for request
        $query = "update users set mod_request=1 where 1 and user_id=$id";
 
        // prepare query statement
        $stmt = $this->conn->prepare($query);

        return json_encode(["success" => $stmt->execute()]);

    }

    function updateRole($id,$data){

        // query for request
        $query = "update users set
         role = :role
         where user_id=$id;";
 
        // prepare query statement
        $stmt = $this->conn->prepare($query);

        $this->role = htmlspecialchars(strip_tags($data['role']));

        $stmt->bindParam(":role", $this->role);

        return json_encode(["success" => $stmt->execute()]);

    }

    function updateModRole($id){

        // query for request
        $query = "update users set role=2,mod_request=0 where 1 and user_id=$id";
 
        // prepare query statement
        $stmt = $this->conn->prepare($query);

        return json_encode(["success" => $stmt->execute()]);

    }

    function cancelModRequest($id){

        // query for request
        $query = "update users set mod_request=0 where 1 and user_id=$id";
 
        // prepare query statement
        $stmt = $this->conn->prepare($query);

        return json_encode(["success" => $stmt->execute()]);

    }


    public function getUserRequests()
    {

        // select all query
        $query = "SELECT * from users where 1 and mod_request=1";

        // prepare query statement
        $stmt = $this->conn->prepare($query);

        // execute query
        if( $stmt->execute()){
            http_response_code(200);
        }
        else{
            http_response_code(400);
        }
 
         $users = [];
 
         while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
             // extract row
             // this will make $row['name'] to
             // just $name only
 
             $user_item = array(
                 "user_id" => $row['user_id'],
                 "usertag" => $row['usertag'],
                 "social_acc" => $row['social_acc']
             );
 
             array_push($users, $user_item);
         }
         
         return json_encode($users);
    }
    
}
