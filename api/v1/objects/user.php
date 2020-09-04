<?php
class user{
  
    // database connection and table name
    // todo
    private $conn;
    private $table_name1 = "users";
  
    // object properties
    public $user_id;
    public $first_name;
    public $last_name;
    public $tag;
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
    $query = "SELECT * from users where 1 order by first_name";
              
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
            "tag" => $row['tag'],
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
        $query = "DELETE from users where 1 and user_id = ?";
              
    // prepare query statement
    $stmt = $this->conn->prepare($query);
    
    // bind id of record to delete
    $stmt->bindParam(1, $id, PDO::PARAM_INT);
   
    return json_encode(["success" => $stmt->execute()]);
    }
    public function createUser($data) {
     
    // query to insert record
    $query = "INSERT INTO users SET first_name= ";

// prepare query
$stmt = $this->conn->prepare($query);
    }
    public function updateUser($data) {
        //todo

        return null;
    }
}
?>