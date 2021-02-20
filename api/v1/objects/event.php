<?php
class event
{

    // database connection and table name
    private $conn;
    private $table_name = "events";

    // object properties
    public $event_id;
    public $event_name;
    public $event_start;
    public $event_end;
    public $image;
    public $game;
    public $region;
    public $category;
    public $max_participants;
    public $created;
    public $createdby;
    public $modified;
    public $modifiedby;
    public $last_date_of_registration;
    public $active;
    public $archive;


    // constructor with $db as database connection
    public function __construct($db)
    {
        $this->conn = $db;
    }

    public function getEvents()
    {
    
        // select all query
        $query = "SELECT e.*, cu.usertag as cutag, mu.usertag as mutag, g.gametype from events e left join games g on g.id= e.game left join users cu on cu.user_id=e.createdby left join users mu on mu.user_id=e.modifiedby  where e.active=1 order by e.event_name";

        // prepare query statement
        $stmt = $this->conn->prepare($query);

        // execute query
        $stmt->execute();

        $events = [];

        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            // extract row
            // this will make $row['name'] to
            // just $name only

            $event_item = array(
                "event_id" => $row['event_id'],
                "event_name" => $row['event_name'],
                "event_start" => $row['event_start'],
                "event_end" => $row['event_end'],
                "image" => $row['image'],
                "game" => $row['game'],
                "region" => $row['region'],
                "category" => $row['category'],
                "gametype" => $row['gametype'],
                "max_participants" => $row['max_participants'],
                "created" => $row['created'],
                "createdby" => $row['createdby'],
                'cutag' => $row['cutag'],
                "modified" => $row['modified'],
                "modifiedby" => $row['modifiedby'],
                'mutag' => $row['mutag'],
                "last_date_of_registration" => $row['last_date_of_registration'],
                "active" => $row['active'],
                "archive" => $row['archive']
            );

            array_push($events, $event_item);
        }

        return json_encode($events);
    }

    public function getEventById($id)
    {

        // select all query
        $query = "SELECT e.*, cu.gametype as gametype from events e left join games cu on cu.id=e.game and event_id=$id";

        // prepare query statement
        $stmt = $this->conn->prepare($query);

        // execute query
        $stmt->execute();

        $event = $stmt->fetch(PDO::FETCH_ASSOC);

        return json_encode($event);

    }

    function getEventsByUser($id)
    {
        // print_r($id);

        // query
        $query = "SELECT * FROM events WHERE 1 AND createdby=$id AND active=1";

        // prepare query statement
        $stmt = $this->conn->prepare($query);

        // execute query
        $stmt->execute(); 

        $events = [];

        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            // extract row
            // this will make $row['name'] to
            // just $name only

            $event_item = array(
                "event_id" => $row['event_id'],
                "event_name" => $row['event_name'],
                "event_start" => $row['event_start'],
                "event_end" => $row['event_end'],
                "image" => $row['image'],
                "game" => $row['game'],
                "region" => $row['region'],
                "category" => $row['category'],
                "max_participants" => $row['max_participants'],
                "created" => $row['created'],
                "createdby" => $row['createdby'],
                "modified" => $row['modified'],
                "modifiedby" => $row['modifiedby'],
                "last_date_of_registration" => $row['last_date_of_registration'],
                "active" => $row['active'],
                "archive" => $row['archive']
            );

            array_push($events, $event_item);
        }

        return json_encode($events);
    }

    function teamInEvent($id){
        
        $teamquery = "SELECT er.team_id,t.name,t.image FROM event_register er LEFT JOIN teams t ON t.id = er.team_id where event_id=$id";

        // prepare query statement
        $stmt = $this->conn->prepare($teamquery);

        // execute query
        $stmt->execute();

        $teams = [];

        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {

            $team_item = array(
                "team_id" => $row['team_id'],
                "team_name" => $row['name'],
                "image" => $row['image']                
            );
            array_push($teams, $team_item);
        }
       
        return json_encode($teams);    


    }


    public function deleteEvent($id)
    {

        //  query to inactivate
        $query = "update events set active=0 where 1 and event_id = ?";

        // prepare query statement
        $stmt = $this->conn->prepare($query);

        // bind id of record to delete
        $stmt->bindParam(1, $id, PDO::PARAM_INT);

        return json_encode(["success" => $stmt->execute()]);
    }

    public function fileUpload(){

        $target_dir = "./uploads/";

        if(isset($_POST["rId"])) {

        if (!file_exists($target_dir.$_POST["userid"].'/'.$_POST["rId"])) {
            mkdir($target_dir.$_POST["userid"].'/'.$_POST["rId"], 0777, true);
            $target_dir = $target_dir.$_POST["userid"].'/'.$_POST["rId"];
        }

        }        
        
       
        $target_file = $target_dir . $_POST['name'];
        $uploadOk = 1;
        $imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
        // Check if image file is a actual image or fake image
        if(isset($_POST["submit"])) {
            $check = getimagesize($_FILES["name"]["tmp_name"]);
            if($check !== false) {
                echo "File is an image - " . $check["mime"] . ".";
                $uploadOk = 1;
            } else {
                echo "File is not an image.";
                $uploadOk = 0;
            }
        }
        // Check if file already exists
        if (file_exists($target_file)) {
            echo "Sorry, file already exists.";
            $uploadOk = 0;
        }
        
        // Check file size
        if ($_FILES["name"]["size"] > 5000000) {
            echo "Sorry, your file is too large.";
            $uploadOk = 0;
        }
        
        // Allow certain file formats
        // if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
        // && $imageFileType != "gif" ) {
        //     echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
        //     $uploadOk = 0;
        // }
        
        // Check if $uploadOk is set to 0 by an error
        if ($uploadOk == 0) {
            echo "Sorry, your file was not uploaded.";
        // if everything is ok, try to upload file
        } else {
            if (move_uploaded_file($_FILES["name"]["tmp_name"], $target_file)) {
            echo "The file ". htmlspecialchars( basename( $_FILES["name"]["name"])). " has been uploaded.";
            } else {
            echo "Sorry, there was an error uploading your file.";
            }
        }
    }

    public function createEvent($data)
    {
        try {

            // query to insert record
            $query = "INSERT INTO " . $this->table_name .
                " SET event_id=0,
          event_name=:event_name,
          event_start=:event_start,
          event_end=:event_end,
          image=:image,
          region=:region,
          category=:category,
          game=:game,
          max_participants=:max_participants,
          created=:created,
          createdby=:createdby,
          modified=:modified,
          modifiedby=:modifiedby,
          last_date_of_registration=:last_date_of_registration,
          active=1,
          archive=0";

            // prepare query
            $stmt = $this->conn->prepare($query);

            // sanitize
            $this->event_name = htmlspecialchars(strip_tags($data['event_name']));
            $this->event_start = htmlspecialchars(strip_tags($data['event_start']));
            $this->event_end = htmlspecialchars(strip_tags($data['event_end']));
            $this->image = htmlspecialchars(strip_tags($data['image']));
            $this->game = htmlspecialchars(strip_tags($data['game']));
            $this->region = htmlspecialchars(strip_tags($data['region']));
            $this->category = htmlspecialchars(strip_tags($data['category']));
            $this->max_participants = htmlspecialchars(strip_tags($data['max_participants']));
            $this->created = htmlspecialchars(strip_tags($data['created']));
            $this->createdby = htmlspecialchars(strip_tags($data['createdby']));
            $this->modified = htmlspecialchars(strip_tags($data['modified']));
            $this->modifiedby = htmlspecialchars(strip_tags($data['modifiedby']));
            $this->last_date_of_registration = htmlspecialchars(strip_tags($data['last_date_of_registration']));
            // $this->archive=htmlspecialchars(strip_tags($data['archive']));

            // print_r($data);  

            $stmt->bindParam(":event_name", $this->event_name);
            $stmt->bindParam(":event_start", $this->event_start);
            $stmt->bindParam(":event_end", $this->event_end);
            $stmt->bindParam(":image", $this->image);
            $stmt->bindParam(":game", $this->game);
            $stmt->bindParam(":region", $this->region);
            $stmt->bindParam(":category", $this->category);
            $stmt->bindParam(":max_participants", $this->max_participants);
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

    public function updateEvent($id, $data, $token_user)
    {
        try {

            // query to insert record
            $query =  "UPDATE  $this->table_name " . "
                  SET
                  event_name=:event_name,
                  event_start=:event_start,
                  event_end=:event_end,
                  image=:image,
                  game=:game,
                  region=:region,
                  category=:category,
                  max_participants=:max_participants,
                  created=:created,
                  createdby=:createdby,
                  modified=:modified,
                  modifiedby=:modifiedby,
                  last_date_of_registration=:last_date_of_registration,
                  active=:active,
                  archive=:archive
                  WHERE event_id = :event_id";

            // prepare query
            $stmt = $this->conn->prepare($query);

            // sanitize
            $this->event_name = htmlspecialchars(strip_tags($data['event_name']));
            $this->event_start = htmlspecialchars(strip_tags($data['event_start']));
            $this->event_end = htmlspecialchars(strip_tags($data['event_end']));
            $this->image = htmlspecialchars(strip_tags($data['image']));
            $this->game = htmlspecialchars(strip_tags($data['game']));
            $this->region = htmlspecialchars(strip_tags($data['region']));
            $this->category = htmlspecialchars(strip_tags($data['category']));
            $this->max_participants = htmlspecialchars(strip_tags($data['max_participants']));
            $this->created = htmlspecialchars(strip_tags($data['created']));
            $this->createdby = htmlspecialchars(strip_tags($data['createdby']));
            $this->modified = htmlspecialchars(strip_tags($data['modified']));
            $this->modifiedby = htmlspecialchars(strip_tags($data['modifiedby']));
            $this->last_date_of_registration = htmlspecialchars(strip_tags($data['last_date_of_registration']));

            // print_r($data);  

            $stmt->bindParam(":event_name", $this->event_name);
            $stmt->bindParam(":event_start", $this->event_start);
            $stmt->bindParam(":event_end", $this->event_end);
            $stmt->bindParam(":image", $this->image);
            $stmt->bindParam(":game", $this->game);
            $stmt->bindParam(":region", $this->region);
            $stmt->bindParam(":category", $this->category);
            $stmt->bindParam(":max_participants", $this->max_participants);
            $stmt->bindParam(":created", $this->created);
            $stmt->bindParam(":createdby", $this->createdby);
            $stmt->bindParam(":modified", $this->modified);
            $stmt->bindParam(":modifiedby", $this->modifiedby);
            $stmt->bindParam(":last_date_of_registration", $this->last_date_of_registration);
            $stmt->bindParam(":active", $this->active);
            $stmt->bindParam(":archive", $this->archive);
            $stmt->bindParam(":event_id", $id);


            // execute query
            return json_encode(["success" => $stmt->execute()]);
        } catch (PDOException $e) {
            throw $e;
        }
    }

    function teamEvent($data)
    {
        try {
            //code...

            // query to insert record
            $query = "INSERT INTO event_register SET
            event_id=:event_id,
            team_id=:team_id,
            player_id=null,
            removed=0 ";

            // prepare query statement
            $stmt = $this->conn->prepare($query);

            // bind parameters
            $stmt->bindParam(":event_id", $data['event_id']);
            $stmt->bindParam(":team_id", $data['team_id']);

            // execute query
            return json_encode(["success" => $stmt->execute()]);
        } catch (PDOException $e) {
            throw $e;
        }
    }

    function playerEvent($data)
    {
        try {
            //code...

            // query to insert record
            $query = "INSERT INTO event_register SET
            event_id=:event_id,
            team_id=null,
            player_id=:player_id,
            removed=0 ";

            // prepare query statement
            $stmt = $this->conn->prepare($query);

            // bind parameters
            $stmt->bindParam(":event_id", $data['event_id']);
            $stmt->bindParam(":player_id", $data['player_id']);

            // execute query
            return json_encode(["success" => $stmt->execute()]);
        } catch (PDOException $e) {
            throw $e;
        }
    }

    function insertEventPlayers($data)
    {
        try {
            //code...

            // query to insert record
            $query = "INSERT INTO event_players SET
            event_id=:event_id,
            player_id=:player_id";

            // prepare query statement
            $stmt = $this->conn->prepare($query);

            // bind parameters
            $stmt->bindParam(":event_id", $data['event_id']);
            $stmt->bindParam(":player_id", $data['player_id']);

            // execute query
            return json_encode(["success" => $stmt->execute()]);
        } catch (PDOException $e) {
            throw $e;
        }
    }

    function removePlayerFromEvent($data)
    {
        try {
            //code...
            // query to inactivate
            $query = "update event_register set removed=1 where 1 and player_id = ? and event_id = ?";

            // prepare query statement
            $stmt = $this->conn->prepare($query);

            // bind id of record to delete
            $stmt->bindParam(2, $data['player_id'], PDO::PARAM_INT);
            $stmt->bindParam(1, $data['event_id'], PDO::PARAM_INT);

            return json_encode(["success" => $stmt->execute()]);
        } catch (PDOException $e) {
            throw $e;
        }
    }

    function removeTeamFromEvent($data)
    {
        try {
            //code...
            // query to inactivate
            $query = "update event_register set removed=1 where 1 and team_id = ? and event_id = ?";

            // prepare query statement
            $stmt = $this->conn->prepare($query);

            // bind id of record to delete
            $stmt->bindParam(2, $data['team_id'], PDO::PARAM_INT);
            $stmt->bindParam(1, $data['event_id'], PDO::PARAM_INT);

            return json_encode(["success" => $stmt->execute()]);
        } catch (PDOException $e) {
            throw $e;
        }
    }
    public function getArchivedEvents()
    {

        // select all query
        $query = "SELECT e.*, cu.usertag as cutag, mu.usertag as mutag from events e left join users cu on cu.user_id=e.createdby left join users mu on mu.user_id=e.modifiedby  where e.archive=1 order by e.event_name";

        // prepare query statement
        $stmt = $this->conn->prepare($query);

        // execute query
        $stmt->execute();

        $events = [];

        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            // extract row
            // this will make $row['name'] to
            // just $name only

            $event_item = array(
                "event_id" => $row['event_id'],
                "event_name" => $row['event_name'],
                "event_start" => $row['event_start'],
                "event_end" => $row['event_end'],
                "image" => $row['image'],
                "game" => $row['game'],
                "region" => $row['region'],
                "category" => $row['category'],
                "max_participants" => $row['max_participants'],
                "created" => $row['created'],
                "createdby" => $row['createdby'],
                'cutag' => $row['cutag'],
                "modified" => $row['modified'],
                "modifiedby" => $row['modifiedby'],
                'mutag' => $row['mutag'],
                "last_date_of_registration" => $row['last_date_of_registration'],
                "active" => $row['active'],
                "archive" => $row['archive']
            );

            array_push($events, $event_item);
        }

        return json_encode($events);
    }
}
// test playerevent,teamevent,removefromevent