<?php
class user{
  
    // database connection and table name
    private $conn;
    private $table_name = "users";
  
    // object properties
    public $user_id;
    public $first_name;
    public $last_name;
    public $usertag;
    public $email;
    public $phone;
    public $password;
    public $photo;
    public $games;
    public $social_acc;
    public $role;
    public $mod_request;
    public $active;
    public $blocked;
    public $created;
    public $modified;
  
    // constructor with $db as database connection
    public function __construct($db){
        $this->conn = $db;
    }

    public function getUsers() {  

    // select all query
    $query = "SELECT * from users where active=1 order by first_name";
              
    // prepare query statement
    $stmt = $this->conn->prepare($query);
  
    // execute query
    $stmt->execute();

    $users = [];

    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
        // extract row
        // this will make $row['name'] to
        // just $name only
          
        $user_item=array(
            "user_id" => $row['user_id'],
            "first_name" => $row['first_name'],
            "last_name" => $row['last_name'],
            "usertag" => $row['usertag'],
            "email" => $row['email'],
            "phone" => $row['phone'],
            "password" => $row['password'],
            "photo" => $row['photo'],
            "games" => $row['games'],
            "social_acc" => $row['social_acc'],
            "role" => $row['role'],
            "mod_request" => $row['mod_request'],
            "active" => $row['active'],
            "blocked" => $row['blocked'],
            "created" => $row['created'],
            "modified" => $row['modified'] 
        );
  
        array_push($users, $user_item);
    }
  
    return json_encode($users);

    }
    public function getUserById($id) {

          // select all query
    $query = "SELECT * from users where 1 and user_id=$id";
              
    // prepare query statement
    $stmt = $this->conn->prepare($query);
  
    // execute query
    $stmt->execute();

    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    return json_encode($user);
  
    }
    public function deleteUser($id) {
        
        // query to inactivate
        $query = "update users set active=0 where 1 and user_id = ?";
              
    // prepare query statement
    $stmt = $this->conn->prepare($query);
    
    // bind id of record to delete
    $stmt->bindParam(1, $id, PDO::PARAM_INT);
   
    return json_encode(["success" => $stmt->execute()]);
    }

    public function createUser($data) {
        try {
            //code...
        
     
    // query to insert record
    $query = "INSERT INTO " . $this->table_name . 
    " SET user_id=0, 
         first_name=:first_name,
         last_name=:last_name,
         usertag=:usertag,
         email=:email,
         phone=:phone,
         password=:password,
         photo=:photo,
         games=:games,
         social_acc=:social_acc,
         role=:role,
         mod_request=:mod_request,
         active=:active,
         blocked=:blocked,
         created=:created,
         modified=:modified";

          // prepare query
    $stmt = $this->conn->prepare($query);

          // sanitize
    $this->first_name=htmlspecialchars(strip_tags($data['first_name']));
    $this->last_name=htmlspecialchars(strip_tags($data['last_name']));
    $this->usertag=htmlspecialchars(strip_tags($data['usertag']));
    $this->email=htmlspecialchars(strip_tags($data['email']));
    $this->phone=htmlspecialchars(strip_tags($data['phone']));
    $this->password=htmlspecialchars(strip_tags($data['password']));
    $this->photo=htmlspecialchars(strip_tags($data['photo']));
    $this->games=htmlspecialchars(strip_tags($data['games']));
    $this->social_acc=htmlspecialchars(strip_tags($data['social_acc']));
    $this->role=htmlspecialchars(strip_tags($data['role']));
    $this->mod_request=htmlspecialchars(strip_tags($data['mod_request']));
    $this->active=htmlspecialchars(strip_tags($data['active']));
    $this->blocked=htmlspecialchars(strip_tags($data['blocked']));
    $this->created=htmlspecialchars(strip_tags($data['created']));
    $this->modified=htmlspecialchars(strip_tags($data['modified']));


    $stmt->bindParam(":first_name", $this->first_name);
    $stmt->bindParam(":last_name", $this->last_name);
    $stmt->bindParam(":usertag", $this->usertag);
    $stmt->bindParam(":email", $this->email);
    $stmt->bindParam(":phone", $this->phone);
    $stmt->bindParam(":password", $this->password);
    $stmt->bindParam(":photo", $this->photo);
    $stmt->bindParam(":games", $this->games);
    $stmt->bindParam(":social_acc", $this->social_acc);
    $stmt->bindParam(":role", $this->role);
    $stmt->bindParam(":mod_request", $this->mod_request);
    $stmt->bindParam(":active", $this->active);
    $stmt->bindParam(":blocked", $this->blocked);
    $stmt->bindParam(":created", $this->created);
    $stmt->bindParam(":modified", $this->modified);

      // execute query
     
      return json_encode(["success" => $stmt->execute()]);
    } catch (PDOException $e) {
        throw $e;
        
    }
         }

    public function updateUser($id,$data) {
     try {
         //code...
    
    // update query
    $query = "UPDATE  $this->table_name "."
            SET 
            first_name=:first_name,
            last_name=:last_name,
            email=:email,
            phone=:phone,
            password=:password,
            photo=:photo,
            games=:games,
            social_acc=:social_acc,
            role=:role,
            mod_request=:mod_request,
            active=:active,
            blocked=:blocked,
            created=:created,
            modified=:modified,
            usertag=:usertag             
            WHERE user_id = :user_id";
 
    // prepare query statement
    $stmt = $this->conn->prepare($query);

     
    // sanitize
    // $this->user_id=htmlspecialchars(strip_tags($data['user_id']));
    $this->first_name=htmlspecialchars(strip_tags($data['first_name']));
    $this->last_name=htmlspecialchars(strip_tags($data['last_name']));
    $this->usertag=htmlspecialchars(strip_tags($data['usertag']));
    $this->email=htmlspecialchars(strip_tags($data['email']));
    $this->phone=htmlspecialchars(strip_tags($data['phone']));
    $this->password=htmlspecialchars(strip_tags($data['password']));
    $this->photo=htmlspecialchars(strip_tags($data['photo']));
    $this->games=htmlspecialchars(strip_tags($data['games']));
    $this->social_acc=htmlspecialchars(strip_tags($data['social_acc']));
    $this->role=htmlspecialchars(strip_tags($data['role']));
    $this->mod_request=htmlspecialchars(strip_tags($data['mod_request']));
    $this->active=htmlspecialchars(strip_tags($data['active']));
    $this->blocked=htmlspecialchars(strip_tags($data['blocked']));
    $this->created=htmlspecialchars(strip_tags($data['created']));
    $this->modified=htmlspecialchars(strip_tags($data['modified']));

        // bind new values
    $stmt->bindParam(":first_name", $this->first_name);
    $stmt->bindParam(":last_name", $this->last_name);
    $stmt->bindParam(":usertag", $this->usertag);
    $stmt->bindParam(":email", $this->email);
    $stmt->bindParam(":phone", $this->phone);
    $stmt->bindParam(":password", $this->password);
    $stmt->bindParam(":photo", $this->photo);
    $stmt->bindParam(":games", $this->games);
    $stmt->bindParam(":social_acc", $this->social_acc);
    $stmt->bindParam(":role", $this->role);
    $stmt->bindParam(":mod_request", $this->mod_request);
    $stmt->bindParam(":active", $this->active);
    $stmt->bindParam(":blocked", $this->blocked);
    $stmt->bindParam(":created", $this->created);
    $stmt->bindParam(":modified", $this->modified);
    $stmt->bindParam(":user_id", $id);
   
    // print_r($stmt->debugDumpParams());
    // execute the query
    return json_encode(["success" => $stmt->execute()]);
    } catch (PDOException $e) {
        throw $e;
    }
}

}

?>