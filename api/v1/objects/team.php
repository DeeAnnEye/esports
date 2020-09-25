<?php
class team{
   
      // database connection and table name
      private $conn;
      private $table_name = "teams";
    
      // object properties
      public $id;
      public $name;
      public $created;
      public $createdby;
      public $modified;
      public $modifiedby; 
      public $active;   

    // constructor with $db as database connection
    public function __construct($db){
        $this->conn = $db;
    }

    public function getTeams(){

          // select all query
    $query = "SELECT * from teams where 1 order by name";

    // prepare query statement
    $stmt = $this->conn->prepare($query);

     // execute query
   $stmt->execute();

   $teams = [];

   while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
       // extract row
       // this will make $row['name'] to
       // just $name only
         
       $team_item=array(
           "id" => $row['id'],
           "name" => $row['name'],
           "created" => $row['created'],
           "createdby" => $row['createdby'],
           "modified" => $row['modified'],
           "modifiedby" => $row['modifiedby'],
           "active" => $row['active']                
       );
 
       array_push($teams, $team_item);
   }
 
   return json_encode($teams);
    }

    public function getTeamById($id){
        
              // select all query
    $query = "SELECT * from teams where 1 and id=$id";
              
    // prepare query statement
    $stmt = $this->conn->prepare($query);
  
    // execute query
    $stmt->execute();

    $team = $stmt->fetch(PDO::FETCH_ASSOC);

    return json_encode($team);
    }

    public function deleteTeam($id){

         //  query to inactivate
         $query = "update teams set active=0 where 1 and id = ?";
              
         // prepare query statement
         $stmt = $this->conn->prepare($query);
         
         // bind id of record to delete
         $stmt->bindParam(1, $id, PDO::PARAM_INT);
        
         return json_encode(["success" => $stmt->execute()]);
    }

    public function createTeam(){
        // todo
    }

    public function updateTeam(){
        // todo
    }
}

?>