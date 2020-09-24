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

    // constructor with $db as database connection
    public function __construct($db){
        $this->conn = $db;
    }

    public function getTeams(){
        // todo
    }

    public function getTeamById(){
        // todo
    }

    public function deleteTeam(){
        // todo
    }

    public function createTeam(){
        // todo
    }

    public function updateTeam(){
        // todo
    }
}

?>