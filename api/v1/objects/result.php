<?php
class result
{

    // database connection and table name
    private $conn;
    private $table_name = "results";

    // object properties
    public $result_id;
    public $event_id;
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
                "published" => $row['published']
            );

            array_push($results, $result_item);
        }

        return json_encode($results);
    }

    public function getResultById($id)
    {
        // select all query
        $query = "SELECT * from results where 1 and result_id=$id";

        // prepare query statement
        $stmt = $this->conn->prepare($query);

        // execute query
        $stmt->execute();

        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        return json_encode($result);
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
                overview=:overview,
                published=0,
                removed=0";

            // prepare query
            $stmt = $this->conn->prepare($query);

            // sanitize
            $this->overview = htmlspecialchars(strip_tags($data['overview']));
            $this->event_id = htmlspecialchars(strip_tags($data['event_id']));

            $stmt->bindParam(":event_id", $this->event_id);
            $stmt->bindParam(":overview", $this->overview);

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
             published=:published,
             removed=:removed
             WHERE result_id = :result_id";

            // prepare query
            $stmt = $this->conn->prepare($query);

            $this->event_id = htmlspecialchars(strip_tags($data['event_id']));
            $this->overview = htmlspecialchars(strip_tags($data['overview']));
            $this->published = htmlspecialchars(strip_tags($data['published']));
            $this->removed = htmlspecialchars(strip_tags($data['removed']));

            $stmt->bindParam(":result_id", $this->result_id);
            $stmt->bindParam(":event_id", $this->event_id);
            $stmt->bindParam(":overview", $this->overview);
            $stmt->bindParam(":published", $this->published);
            $stmt->bindParam(":removed", $this->removed);

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
                     player_id=:player_id,
                     position=:position,
                     score=:score";

            // prepare query
            $stmt = $this->conn->prepare($query);

            // sanitize
            $this->player_id = htmlspecialchars(strip_tags($data['player_id']));
            $this->position = htmlspecialchars(strip_tags($data['position']));
            $this->score = htmlspecialchars(strip_tags($data['score']));

            // bind parameters
            $stmt->bindParam(":player_id", $this->player_id);
            $stmt->bindParam(":position", $this->position);
            $stmt->bindParam(":score", $this->score);

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
                     team_id=team_id,
                     player_id=null,
                     position=:position,
                     score=:score";

            // prepare query
            $stmt = $this->conn->prepare($query);

            // sanitize
            $this->team_id = htmlspecialchars(strip_tags($data['team_id']));
            $this->position = htmlspecialchars(strip_tags($data['position']));
            $this->score = htmlspecialchars(strip_tags($data['score']));

            // bind parameters
            $stmt->bindParam(":team_id", $this->team_id);
            $stmt->bindParam(":position", $this->position);
            $stmt->bindParam(":score", $this->score);

            // execute query
            return json_encode(["success" => $stmt->execute()]);
        } catch (PDOException $e) {
            throw $e;
        }
    }
}
