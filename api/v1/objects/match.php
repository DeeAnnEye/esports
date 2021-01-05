<?php
class match
{

    // database connection and table name
    private $conn;
    private $table_name = "matches";

    // object properties
    public $match_id;
    public $event_id;
    public $match_date;

     // constructor with $db as database connection
     public function __construct($db)
     {
         $this->conn = $db;
     }

     public function getMatches()
     {
 
         // select all query
         $query = "SELECT * from matches where 1 order by match_id";
 
         // prepare query statement
         $stmt = $this->conn->prepare($query);
 
         // execute query
         $stmt->execute();
 
         $matches = [];
 
         while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
             // extract row
             // this will make $row['name'] to
             // just $name only
 
             $match_item = array(
                 "match_id" => $row['match_id'],
                 "event_id" => $row['event_id'],
                 "match_date" => $row['match_date']
             );
 
             array_push($matches, $match_item);
         }
 
         return json_encode($matches);
     }

     public function getMatchById($id)
    {
        // select all query
        $query = "SELECT * from matches where 1 and match_id=$id";

        // prepare query statement
        $stmt = $this->conn->prepare($query);

        // execute query
        $stmt->execute();

        $match = $stmt->fetch(PDO::FETCH_ASSOC);

        return json_encode($match);

    }

    public function createMatch($data)
    {

        try {

            // query to insert record
            $query = "INSERT INTO " . $this->table_name .
                " SET match_id=0,
                 event_id=:event_id,
                 match_date=:match_date";

            // prepare query
            $stmt = $this->conn->prepare($query);

            // sanitize
            $this->event_id = htmlspecialchars(strip_tags($data['event_id']));
            $this->match_date = htmlspecialchars(strip_tags($data['match_date']));

            // print_r($data);  

            $stmt->bindParam(":event_id", $this->event_id);
            $stmt->bindParam(":match_date", $this->match_date);
           
            // execute query
            return json_encode(["success" => $stmt->execute()]);
        } catch (PDOException $e) {
            throw $e;
        }
    }

    public function matchTeams($data)
        {
            try {
    
                // query to insert record
                $query = "INSERT INTO match_teams SET 
                     match_id=:match_id,
                     player_id=null,
                     team_id=:team_id";
                     
    
                // prepare query
                $stmt = $this->conn->prepare($query);
    
                // print_r($data);  
    
                $stmt->bindParam(":match_id", $data['match_id']);
                $stmt->bindParam(":team_id",$data['team_id'] );
               
                // execute query
                return json_encode(["success" => $stmt->execute()]);
            } catch (PDOException $e) {
                throw $e;
            }
        }

        public function createMatchFixture($id){

            $teamfetchquery =  "SELECT team_id from event_register where 1 and event_id= $id ";

             // prepare query statement
             $stmt = $this->conn->prepare($teamfetchquery);

             // execute query
             $stmt->execute();

             $teams=[];
 
             while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {

                $team_item = $row['team_id'];

             }
             array_push($teams,$team_item);

             print_r($teams);

        }

       
 
}
