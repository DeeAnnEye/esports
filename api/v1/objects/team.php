<?php
class team
{

    // database connection and table name
    private $conn;
    private $table_name = "teams";

    // object properties
    public $id;
    public $name;
    public $image;
    public $created;
    public $createdby;
    public $modified;
    public $modifiedby;
    public $active;
    public $flag;

    // constructor with $db as database connection
    public function __construct($db)
    {
        $this->conn = $db;
    }

    public function getTeams()
    {

        // select all query
        $query = "SELECT * from teams where active=1 order by name";

        // prepare query statement
        $stmt = $this->conn->prepare($query);

        // execute query
        $stmt->execute();

        $teams = [];

        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            // extract row
            // this will make $row['name'] to
            // just $name only

            $team_item = array(
                "id" => $row['id'],
                "name" => $row['name'],
                "image" => $row['image'],
                "created" => $row['created'],
                "createdby" => $row['createdby'],
                "modified" => $row['modified'],
                "modifiedby" => $row['modifiedby'],
                "active" => $row['active'],
                "flag" => $row['flag']
            );

            array_push($teams, $team_item);
        }

        return json_encode($teams);
    }

    public function getTeamById($id)
    {

        // select all query
        $query = "SELECT t.*, cu.usertag as cutag, mu.usertag as mutag from teams t left join users cu on cu.user_id=t.createdby left join users mu on mu.user_id=t.modifiedby and id=$id";

        // prepare query statement
        $stmt = $this->conn->prepare($query);

        // execute query
        $stmt->execute();

        $team = $stmt->fetch(PDO::FETCH_ASSOC);

        return json_encode($team);
    }

    public function deleteTeam($id)
    {

        //  query to inactivate
        $query = "update teams set active=0 where 1 and id = ?";

        // prepare query statement
        $stmt = $this->conn->prepare($query);

        // bind id of record to delete
        $stmt->bindParam(1, $id, PDO::PARAM_INT);

        return json_encode(["success" => $stmt->execute()]);
    }

    public function createTeam($data)
    {
        try {

            // query to insert record
            $query = "INSERT INTO " . $this->table_name .
                " SET id=0,
                  name=:name,
                  image=:image,
                  created=:created,
                  createdby=:createdby,
                  modified=:modified,
                  modifiedby=:modifiedby,
                  active=1,
                  flag=0";

            // prepare query
            $stmt = $this->conn->prepare($query);

            // sanitize
            $this->name = htmlspecialchars(strip_tags($data['name']));
            $this->image = htmlspecialchars(strip_tags($data['image']));
            $this->created = htmlspecialchars(strip_tags($data['created']));
            $this->createdby = htmlspecialchars(strip_tags($data['createdby']));
            $this->modified = htmlspecialchars(strip_tags($data['modified']));
            $this->modifiedby = htmlspecialchars(strip_tags($data['modifiedby']));
            $this->flag = htmlspecialchars(strip_tags($data['flag']));

            $stmt->bindParam(":name", $this->name);
            $stmt->bindParam(":image", $this->image);
            $stmt->bindParam(":created", $this->created);
            $stmt->bindParam(":createdby", $this->createdby);
            $stmt->bindParam(":modified", $this->modified);
            $stmt->bindParam(":modifiedby", $this->modifiedby);

            // execute query
            return json_encode(["success" => $stmt->execute()]);
        } catch (PDOException $e) {
            throw $e;
        }
    }

    public function updateTeam($id, $data)
    {
        try {

            // query to insert record
            $query =  "UPDATE  $this->table_name " . "
                  SET
                  name=:name,
                  image=:image,
                  created=:created,
                  createdby=:createdby,
                  modified=:modified,
                  modifiedby=:modifiedby,
                  active=:active,
                  flag=:flag
                  WHERE id = :id";

            // prepare query
            $stmt = $this->conn->prepare($query);

            // sanitize
            $this->name = htmlspecialchars(strip_tags($data['name']));
            $this->image = htmlspecialchars(strip_tags($data['image']));
            $this->created = htmlspecialchars(strip_tags($data['created']));
            $this->createdby = htmlspecialchars(strip_tags($data['createdby']));
            $this->modified = htmlspecialchars(strip_tags($data['modified']));
            $this->modifiedby = htmlspecialchars(strip_tags($data['modifiedby']));
            $this->active = htmlspecialchars(strip_tags($data['active']));
            $this->flag = htmlspecialchars(strip_tags($data['flag']));


            $stmt->bindParam(":name", $this->name);
            $stmt->bindParam(":image", $this->image);
            $stmt->bindParam(":created", $this->created);
            $stmt->bindParam(":createdby", $this->createdby);
            $stmt->bindParam(":modified", $this->modified);
            $stmt->bindParam(":modifiedby", $this->modifiedby);
            $stmt->bindParam(":active", $this->active);
            $stmt->bindParam(":flag", $this->flag);
            $stmt->bindParam(":id", $id);

            // execute query
            return json_encode(["success" => $stmt->execute()]);
        } catch (PDOException $e) {
            throw $e;
        }
    }
}
