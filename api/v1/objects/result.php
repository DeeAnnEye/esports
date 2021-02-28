<?php
class result
{

    // database connection and table name
    private $conn;
    private $table_name = "results";

    // object properties
    public $result_id;
    public $event_id;
    public $team_id;
    public $position;
    public $overview;
    public $published;
    public $removed;

    // constructor with $db as database connection
    public function __construct($db)
    {
        $this->conn = $db;
    }

    public function getResults()
    {

        // select all query
        $query = "SELECT * from results where published=1 and removed=0 order by result_id";

        // prepare query statement
        $stmt = $this->conn->prepare($query);

        // execute query
        $stmt->execute();

        $results = [];

        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            // extract row
            // this will make $row['name'] to
            // just $name only

            $result_item = array(
                "result_id" => $row['result_id'],
                "event_id" => $row['event_id'],
                "overview" => $row['overview'],
                "team_id" => $row['team_id'],
                "position" => $row['position'],
                "published" => $row['published']
            );

            array_push($results, $result_item);
        }

        return json_encode($results);
    }

    public function getResultById($id)
    {
        // select all query
        $query = "SELECT
        r.*, p.player_id,p.`kill` as `kill`,p.death as death,p.assist as assist,
    u.usertag as usertag,t.`name` as `name`,e.event_name as event_name,t.image as teamimage
    FROM
        `results` r
    LEFT JOIN placements p ON p.team_id = r.team_id
    LEFT JOIN events e on e.event_id=r.r_event_id
    LEFT JOIN teams t on t.id=r.team_id
    LEFT JOIN users u on u.user_id=p.player_id
    WHERE
        r.r_event_id = $id;";

        // prepare query statement
        $stmt = $this->conn->prepare($query);

        // execute query
        $stmt->execute();

      
        $results = [];

        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            // extract row
            // this will make $row['name'] to
            // just $name only

            $result_item = array(
                "result_id" => $row['result_id'],
                "r_event_id" => $row['r_event_id'],
                "overview" => $row['overview'],
                "team_id" => $row['team_id'],
                "position" => $row['position'],
                "published" => $row['published'],
                "player_id" => $row['player_id'],
                "kill" => $row['kill'],
                "death" => $row['death'],
                "assist" => $row['assist'],
                "usertag" => $row['usertag'],
                "name" => $row['name'],
                "event_name" => $row['event_name'],
                "teamimage" => $row['teamimage']
            );

            array_push($results, $result_item);
        }

        return json_encode($results);
    }


    public function deleteResult($id)
    {
        //  query to inactivate
        $query = "update results set removed=1 where 1 and result_id = ?";

        // prepare query statement
        $stmt = $this->conn->prepare($query);

        // bind id of record to delete
        $stmt->bindParam(1, $id, PDO::PARAM_INT);

        return json_encode(["success" => $stmt->execute()]);
    }

    public function createResult($data)
    {
        try {
            // query to insert record
            $query = "INSERT INTO " . $this->table_name .
                " SET result_id=0,
                event_id=:event_id,
                team_id=:team_id,
                overview=:overview,
                position=:position,
                published=0,
                removed=0";

            // prepare query
            $stmt = $this->conn->prepare($query);

            // sanitize
            $this->overview = htmlspecialchars(strip_tags($data['overview']));
            $this->team_id = htmlspecialchars(strip_tags($data['team_id']));
            $this->position = htmlspecialchars(strip_tags($data['position']));
            $this->event_id = htmlspecialchars(strip_tags($data['event_id']));

            $stmt->bindParam(":event_id", $this->event_id);
            $stmt->bindParam(":overview", $this->overview);
            $stmt->bindParam(":team_id", $this->team_id);
            $stmt->bindParam(":position", $this->position);

            // execute query
            return json_encode(["success" => $stmt->execute()]);
        } catch (PDOException $e) {
            throw $e;
        }
    }

    public function updateResult($id, $data, $token_user)
    {
        try {
            // query to insert record
            $query =  "UPDATE  $this->table_name " . "
             SET  overview=:overview,
             event_id=:event_id,
             team_id=:team_id,
             published=:published,
             removed=:removed,
             position=:position
             WHERE result_id = :result_id";

            // prepare query
            $stmt = $this->conn->prepare($query);

            $this->event_id = htmlspecialchars(strip_tags($data['event_id']));
            $this->team_id = htmlspecialchars(strip_tags($data['team_id']));
            $this->overview = htmlspecialchars(strip_tags($data['overview']));
            $this->published = htmlspecialchars(strip_tags($data['published']));
            $this->removed = htmlspecialchars(strip_tags($data['removed']));
            $this->position = htmlspecialchars(strip_tags($data['position']));


            $stmt->bindParam(":result_id", $id);
            $stmt->bindParam(":event_id", $this->event_id);
            $stmt->bindParam(":team_id", $this->team_id);
            $stmt->bindParam(":overview", $this->overview);
            $stmt->bindParam(":published", $this->published);
            $stmt->bindParam(":removed", $this->removed);
            $stmt->bindParam(":position", $this->position);

            // execute query
            return json_encode(["success" => $stmt->execute()]);
        } catch (PDOException $e) {
            throw $e;
        }
    }

    function createPlayerPlacement($data)
    {
        try {


            // query to insert record
            $query = "INSERT INTO placements SET
                     placement_id=0,
                     team_id=null,
                     event_id=:event_id,
                     player_id=:player_id,
                     `kill`=:`kill`,
                     death=:death,
                     assist=:assist";

            // prepare query
            $stmt = $this->conn->prepare($query);

            // sanitize
            $this->player_id = htmlspecialchars(strip_tags($data['player_id']));
            $this->event_id = htmlspecialchars(strip_tags($data['event_id']));
            $this->kill = htmlspecialchars(strip_tags($data['kill']));
            $this->death = htmlspecialchars(strip_tags($data['death']));
            $this->assist = htmlspecialchars(strip_tags($data['assist']));


            // bind parameters
            $stmt->bindParam(":player_id", $this->player_id);
            $stmt->bindParam(":event_id", $this->event_id);
            $stmt->bindParam(":kill", $this->kill);
            $stmt->bindParam(":death", $this->death);
            $stmt->bindParam(":assist", $this->assist);

            // execute query
            return json_encode(["success" => $stmt->execute()]);
        } catch (PDOException $e) {
            throw $e;
        }
    }

    function createTeamPlacement($data)
    {
        try {


            // query to insert record
            $query = "INSERT INTO placements SET
                     placement_id=0,
                     team_id=:team_id,
                     event_id=:event_id,
                     player_id=:player_id,
                     `kill`=:`kill`,
                     death=:death,
                     assist=:assist";

            // prepare query
            $stmt = $this->conn->prepare($query);

            // sanitize
            $this->team_id = htmlspecialchars(strip_tags($data['team_id']));
            $this->event_id = htmlspecialchars(strip_tags($data['event_id']));
            $this->player_id = htmlspecialchars(strip_tags($data['player_id']));
            $this->kill = htmlspecialchars(strip_tags($data['kill']));
            $this->death = htmlspecialchars(strip_tags($data['death']));
            $this->assist = htmlspecialchars(strip_tags($data['assist']));

            // bind parameters
            $stmt->bindParam(":team_id", $this->team_id);
            $stmt->bindParam(":player_id", $this->player_id);
            $stmt->bindParam(":event_id", $this->event_id);
            $stmt->bindParam(":kill", $this->kill);
            $stmt->bindParam(":death", $this->death);
            $stmt->bindParam(":assist", $this->assist);

            // execute query
            return json_encode(["success" => $stmt->execute()]);
        } catch (PDOException $e) {
            throw $e;
        }
    }
    // todo result placements bridge table
    public function resultPlacements($data){
        try {
            //code...

            // query to insert record
            $query = "INSERT INTO placement_results SET
            placement_id=:placement_id,
            result_id=:result_id ";

            // prepare query statement
            $stmt = $this->conn->prepare($query);

            // bind parameters
            $stmt->bindParam(":placement_id", $data['placement_id']);
            $stmt->bindParam(":result_id", $data['result_id']);

            // execute query
            return json_encode(["success" => $stmt->execute()]);
        } catch (PDOException $e) {
            throw $e;
        }
    }
}
