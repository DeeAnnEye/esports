<?php
class event{

      // database connection and table name
      private $conn;
      private $table_name = "events";
    
      // object properties
      public $event_id;
      public $event_name;
      public $event_start;
      public $event_end;
      public $game_id;
      public $max_participants;
      public $permission;
      public $created;
      public $createdby;
      public $modified;
      public $modifiedby;
      public $last_date_of_registration;

      
    // constructor with $db as database connection
    public function __construct($db){
        $this->conn = $db;
    }
    
    public function getEvents(){
         // select all query
    $query = "SELECT * from events where 1 order by event_name";

     // prepare query statement
     $stmt = $this->conn->prepare($query);

      // execute query
    $stmt->execute();

    $events = [];

    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
        // extract row
        // this will make $row['name'] to
        // just $name only
          
        $event_item=array(
            "event_id" => $row['event_id'],
            "event_name" => $row['event_name'],
            "event_start" => $row['event_start'],
            "event_end" => $row['event_end'],
            "game_id" => $row['game_id'],
            "max_participants" => $row['max_participants'],
            "permission" => $row['permission'],
            "created" => $row['created'],
            "createdby" => $row['createdby'],
            "modified" => $row['modified'],
            "modifiedby" => $row['modifiedby'],
            "last_date_of_registration" => $row['last_date_of_registration']            
        );
  
        array_push($events, $event_item);
    }
  
    return json_encode($events);
    }

    public function getEventById($id){
              // select all query
    $query = "SELECT * from events where 1 and event_id=$id";
              
    // prepare query statement
    $stmt = $this->conn->prepare($query);
  
    // execute query
    $stmt->execute();

    $event = $stmt->fetch(PDO::FETCH_ASSOC);

    return json_encode($event);
    }

     public function deleteEvent($id){
        //  query to inactivate
        $query = "update events set active=0 where 1 and event_id = ?";
              
        // prepare query statement
        $stmt = $this->conn->prepare($query);
        
        // bind id of record to delete
        $stmt->bindParam(1, $id, PDO::PARAM_INT);
       
        return json_encode(["success" => $stmt->execute()]);
    }

    public function createEvent($data){
        try {
                         
    // query to insert record
    $query = "INSERT INTO " . $this->table_name . 
    " SET event_id=0,
          event_name=:event_name,
          event_start=:event_start,
          event_end=:event_end,
          game_id=:game_id,
          max_participants=:max_participants,
          permission=:permission,
          created=:created,
          createdby=:createdby,
          modified=:modified,
          modifiedby=:modifiedby,
          last_date_of_registration=:last_date_of_registration";

          // prepare query
    $stmt = $this->conn->prepare($query);

          // sanitize
    $this->event_name=htmlspecialchars(strip_tags($data['event_name']));
    $this->event_start=htmlspecialchars(strip_tags($data['event_start']));
    $this->event_end=htmlspecialchars(strip_tags($data['event_end']));
    $this->game_id=htmlspecialchars(strip_tags($data['game_id']));
    $this->max_participants=htmlspecialchars(strip_tags($data['max_participants']));
    $this->permission=htmlspecialchars(strip_tags($data['permission']));
    $this->created=htmlspecialchars(strip_tags($data['created']));
    $this->createdby=htmlspecialchars(strip_tags($data['createdby']));
    $this->modified=htmlspecialchars(strip_tags($data['modified']));
    $this->modifiedby=htmlspecialchars(strip_tags($data['modifiedby']));
    $this->last_date_of_registration=htmlspecialchars(strip_tags($data['last_date_of_registration']));
          
    // print_r($data);  

    $stmt->bindParam(":event_name", $this->event_name);
    $stmt->bindParam(":event_start", $this->event_start);
    $stmt->bindParam(":event_end", $this->event_end);
    $stmt->bindParam(":game_id", $this->game_id);
    $stmt->bindParam(":max_participants", $this->max_participants);
    $stmt->bindParam(":permission", $this->permission);
    $stmt->bindParam(":created", $this->created);
    $stmt->bindParam(":createdby", $this->createdby);
    $stmt->bindParam(":modified", $this->modified);
    $stmt->bindParam(":modifiedby", $this->modifiedby);
    $stmt->bindParam(":last_date_of_registration", $this->last_date_of_registration);
    
      // execute query
      return json_encode(["success" => $stmt->execute()]);
    } catch (PDOException $e) {
        throw $e;
        
    }
         
}    

    public function updateEvent($id,$data){
        try {
                         
            // query to insert record
            $query =  "UPDATE  $this->table_name "."
                  SET
                  event_name=:event_name,
                  event_start=:event_start,
                  event_end=:event_end,
                  game_id=:game_id,
                  max_participants=:max_participants,
                  permission=:permission,
                  created=:created,
                  createdby=:createdby,
                  modified=:modified,
                  modifiedby=:modifiedby,
                  last_date_of_registration=:last_date_of_registration
                  WHERE event_id = :event_id";
        
                  // prepare query
            $stmt = $this->conn->prepare($query);
        
                  // sanitize
            $this->event_name=htmlspecialchars(strip_tags($data['event_name']));
            $this->event_start=htmlspecialchars(strip_tags($data['event_start']));
            $this->event_end=htmlspecialchars(strip_tags($data['event_end']));
            $this->game_id=htmlspecialchars(strip_tags($data['game_id']));
            $this->max_participants=htmlspecialchars(strip_tags($data['max_participants']));
            $this->permission=htmlspecialchars(strip_tags($data['permission']));
            $this->created=htmlspecialchars(strip_tags($data['created']));
            $this->createdby=htmlspecialchars(strip_tags($data['createdby']));
            $this->modified=htmlspecialchars(strip_tags($data['modified']));
            $this->modifiedby=htmlspecialchars(strip_tags($data['modifiedby']));
            $this->last_date_of_registration=htmlspecialchars(strip_tags($data['last_date_of_registration']));
                  
            // print_r($data);  
        
            $stmt->bindParam(":event_name", $this->event_name);
            $stmt->bindParam(":event_start", $this->event_start);
            $stmt->bindParam(":event_end", $this->event_end);
            $stmt->bindParam(":game_id", $this->game_id);
            $stmt->bindParam(":max_participants", $this->max_participants);
            $stmt->bindParam(":permission", $this->permission);
            $stmt->bindParam(":created", $this->created);
            $stmt->bindParam(":createdby", $this->createdby);
            $stmt->bindParam(":modified", $this->modified);
            $stmt->bindParam(":modifiedby", $this->modifiedby);
            $stmt->bindParam(":last_date_of_registration", $this->last_date_of_registration);
            $stmt->bindParam(":event_id", $id);
              // execute query
             
              return json_encode(["success" => $stmt->execute()]);
            } catch (PDOException $e) {
                throw $e;
                
            }
                 
        }    
    }

?>