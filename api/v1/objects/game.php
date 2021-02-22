<?php
class game{
      
    // database connection and table name
    private $conn;
    private $table_name = "games";

    // object properties
    public $id;
    public $name;
    public $image;
    public $wallpaper;
    public $gametype;
    public $number_of_players;
    public $created;

    
    // constructor with $db as database connection
    public function __construct($db)
    {
        $this->conn = $db;
    }

    public function getGames()
    {

        // select all query
        $query = "SELECT * from games where 1 order by name";

        // prepare query statement
        $stmt = $this->conn->prepare($query);

        // execute query
       if( $stmt->execute()){
           http_response_code(200);
       }
       else{
           http_response_code(400);
       }

        $games = [];

        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            // extract row
            // this will make $row['name'] to
            // just $name only

            $game_item = array(
                "id" => $row['id'],
                "name" => $row['name'],
                "image" => $row['image'],
                "wallpaper" => $row['wallpaper'],
                "gametype" => $row['gametype'],
                "number_of_players" => $row['number_of_players'],
                "created" => $row['created']
            );

            array_push($games, $game_item);
        }
        
        return json_encode($games);
    }

    public function getGameById($id)
    {

        // select all query
        $query = "SELECT * from games where 1 and id=$id";

        // prepare query statement
        $stmt = $this->conn->prepare($query);

        // execute query
        $stmt->execute();

        $game = $stmt->fetch(PDO::FETCH_ASSOC);

        return json_encode($game);
    }

    public function createGame($data)
    {

        try {

            // query to insert record
            $query = "INSERT INTO " . $this->table_name .
                " SET id=0,
                 name=:name,
                 image=:image,
                 wallpaper=:wallpaper,
                 gametype=:gametype,
                 number_of_players=:number_of_players";

            // prepare query
            $stmt = $this->conn->prepare($query);

            // sanitize
            $this->name = htmlspecialchars(strip_tags($data['name']));
            $this->image = htmlspecialchars(strip_tags($data['image']));
            $this->wallpaper = htmlspecialchars(strip_tags($data['wallpaper']));
            $this->gametype = htmlspecialchars(strip_tags($data['gametype']));
            $this->number_of_players = htmlspecialchars(strip_tags($data['number_of_players']));
            

            // print_r($data);  

            $stmt->bindParam(":name", $this->name);
            $stmt->bindParam(":image", $this->image);
            $stmt->bindParam(":wallpaper", $this->wallpaper);
            $stmt->bindParam(":gametype", $this->gametype);
            $stmt->bindParam(":number_of_players", $this->number_of_players);
           
            // execute query
            return json_encode(["success" => $stmt->execute()]);
        } catch (PDOException $e) {
            throw $e;
        }
    }

    public function updateGame($id, $data, $token_user)
    {
        try {
            // query to insert record
            $query =  "UPDATE  $this->table_name " . "
             SET  name=:name,
             image=:image,
             wallpaper=:wallpaper,
             gametype=:gametype,
             number_of_players=:number_of_players
             WHERE id = :id";

            // prepare query
            $stmt = $this->conn->prepare($query);

            $this->name = htmlspecialchars(strip_tags($data['name']));
            $this->image = htmlspecialchars(strip_tags($data['image']));
            $this->wallpaper = htmlspecialchars(strip_tags($data['wallpaper']));
            $this->gametype = htmlspecialchars(strip_tags($data['gametype']));
            $this->number_of_players = htmlspecialchars(strip_tags($data['number_of_players']));

            //  print_r($data);  
            

            $stmt->bindParam(":id", $id);
            $stmt->bindParam(":name", $this->name);
            $stmt->bindParam(":image", $this->image);
            $stmt->bindParam(":wallpaper", $this->wallpaper);
            $stmt->bindParam(":gametype", $this->gametype);
            $stmt->bindParam(":number_of_players", $this->number_of_players);

            // execute query
            return json_encode(["success" => $stmt->execute()]);
        } catch (PDOException $e) {
            throw $e;
        }
    }


   

}
?>