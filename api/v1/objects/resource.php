<?php
class resource
{
    
    // database connection and table name
    private $conn;
    private $table_name = "resources";

    // object properties
    public $resource_id;
    public $name;
    public $category;
   

    // constructor with $db as database connection
    public function __construct($db)
    {
        $this->conn = $db;
    }

    public function getResources()
    {

        // select all query
        $query = "SELECT * from resources where 1 order by category";

        // prepare query statement
        $stmt = $this->conn->prepare($query);

        // execute query
        $stmt->execute();

        $resources = [];

        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            // extract row
            // this will make $row['name'] to
            // just $name only

            $resource_item = array(
                "resource_id" => $row['resource_id'],
                "name" => $row['name'],
                "category" => $row['category'],

            );

            array_push($resources, $resource_item);
        }

        return json_encode($resources);
    }

    
    public function getResourceById($id)
    {

        // select all query
        $query = "SELECT * from resources where 1 and resource_id=$id";

        // prepare query statement
        $stmt = $this->conn->prepare($query);

        // execute query
        $stmt->execute();

        $resource = $stmt->fetch(PDO::FETCH_ASSOC);

        return json_encode($resource);
    }

    public function createResource($data)
    {

        try {

            // query to insert record
            $query = "INSERT INTO " . $this->table_name .
                " SET resource_id=0,
                 name=:name,
                 category=:category";

            // prepare query
            $stmt = $this->conn->prepare($query);

            // sanitize
            $this->name = htmlspecialchars(strip_tags($data['name']));
            $this->category = htmlspecialchars(strip_tags($data['category']));

            // print_r($data);  

            $stmt->bindParam(":name", $this->name);
            $stmt->bindParam(":category", $this->category);
           
            // execute query
            return json_encode(["success" => $stmt->execute()]);
        } catch (PDOException $e) {
            throw $e;
        }
    }

  
    public function getPermissionsByRole($id)
    {

        // select all query
        $query = "SELECT resource_id from permissions where 1 and role_id=$id";

        // echo $query;
        // prepare query statement
        $stmt = $this->conn->prepare($query);

        // execute query
        $stmt->execute();

        $permissions = [];

        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {

            array_push($permissions, $row['resource_id']);
        }


        return $permissions;
    }

   function setPermission($data)
    {
        try {

            // query to insert record
            $query = "INSERT INTO permissions SET 
                 id=0,
                 role_id=:role_id,
                 resource_id=:resource_id";
                 

            // prepare query
            $stmt = $this->conn->prepare($query);

            // print_r($data);  

            $stmt->bindParam(":role_id", $data['role_id']);
            $stmt->bindParam(":resource_id",$data['resource_id'] );
           
            // execute query
            return json_encode(["success" => $stmt->execute()]);
        } catch (PDOException $e) {
            throw $e;
        }
    }

    function denyPermission($data)
    {
        try {

            // query to insert record
            $query = "DELETE from permissions where 1 and role_id = ? and resource_id = ?";
                 

            // prepare query
            $stmt = $this->conn->prepare($query);

            // print_r($data);  

            // bind id of record to delete
            $stmt->bindParam(1, $data['role_id'], PDO::PARAM_INT);
            $stmt->bindParam(2, $data['resource_id'], PDO::PARAM_INT);
           
            // execute query
            return json_encode(["success" => $stmt->execute()]);
        } catch (PDOException $e) {
            throw $e;
        }
    }
}
