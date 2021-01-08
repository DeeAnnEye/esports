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
    public $rndF;
    public $rndS;
    public $lastRnd;

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

        public function insertFixtures($data)
        {
            try {
                foreach($data as $f) {
                
                // query to insert record
                $query = "INSERT INTO fixtures SET 
                     fixture_id=0,
                     event_id=:event_id,
                     `round`= :round,
                     match_id=:match_id,
                     match_date=:match_date,
                     team_A=:team_A,
                     team_B=:team_B,
                     WMA = :WMA,
                     WMB = :WMB,
                     winner=:winner;

                    // --  winner=:winner,
                    // --  winner_meets=:winner_meets";                     
    
                // prepare query
                $stmt = $this->conn->prepare($query);
                
    
                // print_r($data);  
                $stmt->bindParam(":event_id",$f['event_id'] );    
                $stmt->bindParam(":match_id", $f['match']);
                $stmt->bindParam(":round",$f['round'] );
                $stmt->bindParam(":match_date",$f['match_date'] );
                $stmt->bindParam(":team_A",$f['team_A']  );
                $stmt->bindParam(":team_B",$f['team_B']  );
                $stmt->bindParam(":WMA",$f['WMA']  );
                $stmt->bindParam(":WMB",$f['WMB']  );
                $stmt->bindParam(":winner", $f['winner'] );
                // $stmt->bindParam(":winner_meets",'' );  
                         
                $stmt->execute();
                // execute query
                // return json_encode(["success" => $stmt->execute()]);
                }
                return true;
            } catch (PDOException $e) {
                throw $e;
            }
        }


        public function createMatchFixture($id){

            $teamfetchquery =  "SELECT r.team_id,e.event_start as startdate,e.event_end as enddate from event_register r left join events e on e.event_id =r.event_id where 1 and r.event_id= $id ";

             // prepare query statement
             $stmt = $this->conn->prepare($teamfetchquery);

             // execute query
             $stmt->execute();

             $teams='';
             $start = "";

            // $teams=$stmt->fetch(PDO::FETCH_ASSOC);
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                 $teams .= $row['team_id'] . ',';
                 $start = $row['startdate'];

            //    $teams[]= $row['team_id'];
            }
            //print_r($teams)

            $teams = explode(',', substr($teams, 0, -1));

            $teams_length= sizeof($teams);

            $even = $teams_length % 2 == 0 ? true : false;
            $bye = 0;
            
            if(!$even) {
                array_push($teams,0);
            }
            
            $teams_length = $even ? $teams_length : $teams_length + 1;

            $round = [];

            while(true){
               $round[] =  sizeof($round) == 0 ? $teams_length/2 : $round[sizeof($round)-1]/2;
               if($round[sizeof($round)-1] == 1)  {
                 break;
               }
            }

            $m = 1;
            $fixtures = [];
            $rnd = '';
            $lastround = [];
            for($i=0;$i<=sizeof($round)-1;$i++){
                if($round[$i] == 4){
                    // echo "QF"."\n";
                    $rnd = "QF";
                }
                elseif($round[$i] == 2)
                {
                    // echo "SF"."\n";
                    $rnd = "SF";
                }
                elseif($round[$i] == 1){
                    // echo "F"."\n";
                    $rnd = "F";
                }
                else{
                    $rnd = $round[$i];
                    // echo "Round:".($rnd)."\n";
                
                }
                $this->lastRnd = $rnd;
                $matchdate = date("Y-m-d", strtotime("+$i day", strtotime($start)));
                // echo date("Y-m-d", $date);   
                
                for($j=1;$j<=$round[$i];$j++){
                    // echo "Match:".$m++."\n";
                    

                    if(sizeof($teams) != 0){

                     $rm = array_rand(array_flip($teams), 2);
                     $f = $rm[0];
                     $s = $rm[1];
                     $fkey = array_search($f, $teams);
                     unset($teams[$fkey]);
                     $skey = array_search($s, $teams);
                     unset($teams[$skey]);
                    // echo $f." vs ". $s ."\n";
                    $f = $f==0 ? NULL: $f;
                    $s = $s==0 ? NULL: $s;
                    $winner = $f==0 ? $s: NULL;
                    $winner = $s==0 ? $f: NULL;

                    array_push($fixtures,["round" => $rnd, "match" => $m++, "WMA" =>"", "team_A" => $f,"winner"=>$winner, 
                    "WMB" =>"", "team_B" => $s, "match_date" => $matchdate,"event_id" => $id]);
                    $lastround = $fixtures;                                

                    }
                    else{ 
                        
                        $rm = array_rand($lastround, 2);
                        
                         $this->rndF = $lastround[$rm[0]];
                         $this->rndS = $lastround[$rm[1]];
                                                 
                       $lastround = array_filter($lastround, function($fx) {
                            return $this->rndF["match"] != $fx["match"] && $this->rndS["match"] != $fx["match"] ;
                       });
                       
                    //    echo $this->rndF["match"]." vs ". $this->rndS["match"] ."\n";
                    // $winner = $this->rndF==0 ? $this->rndS: NULL;
                    // $winner = $this->rndS==0 ? $this->rndF: NULL;

                      $wma = $this->rndF['winner'] && $this->rndF['winner'] == $this->rndF['team_A'] ? $this->rndF['team_A'] : 'WM' . $this->rndF['match'];
                      $wmb = $this->rndS['winner'] && $this->rndS['winner'] == $this->rndS['team_B'] ? $this->rndS['team_B'] : 'WM' . $this->rndS['match'];
                      $team_A = is_numeric($wma)== true ? $wma : NULL ;
                      $team_B = is_numeric($wmb)== true ? $wmb : NULL ;
                      array_push($fixtures,["round" => $rnd, "match" => $m++, "team_A" =>$team_A,"winner"=>NULL, 
                      "WMA" => $wma, "team_B" => $team_B, "WMB" => $wmb,"match_date" => $matchdate,"event_id" => $id]);   
                         
                    }

                }
                $lastround = array_filter($fixtures, function($fx) {
                    return $fx["round"] == $this->lastRnd;
               });
            }
           
         $this->insertFixtures($fixtures);
         return json_encode(["fixtures" => $fixtures]);

        }
      
 
}
