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
    public $game_id;
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
        $query = "SELECT e.*, cu.usertag as cutag, mu.usertag as mutag from events e left join users cu on cu.user_id=e.createdby left join users mu on mu.user_id=e.modifiedby  where e.active=1 order by e.event_name";

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
                "game_id" => $row['game_id'],
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
        $query = "SELECT * from events where 1 and event_id=$id";

        // prepare query statement
        $stmt = $this->conn->prepare($query);

        // execute query
        $stmt->execute();

        $event = $stmt->fetch(PDO::FETCH_ASSOC);

        return json_encode($event);
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
          game_id=:game_id,
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
            $this->game_id = htmlspecialchars(strip_tags($data['game_id']));
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
            $stmt->bindParam(":game_id", $this->game_id);
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
                  game_id=:game_id,
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
            $this->game_id = htmlspecialchars(strip_tags($data['game_id']));
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
            $stmt->bindParam(":game_id", $this->game_id);
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
                "game_id" => $row['game_id'],
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