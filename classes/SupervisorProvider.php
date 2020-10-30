<?php
class SupervisorProvider {
    private $conn;

    public function __construct($conn) {
        $this->conn = $conn;
    }

    // get all supervisors from database
    // return as array
    public function getAllSupervisors() {
        $query = $this->conn->prepare("SELECT * FROM sample_supervisor");
        $query->execute();

        $results = [];

        while($row = $query->fetch(PDO::FETCH_ASSOC)) {
            array_push($results,$row);
        }
        return $results;
    }
    // gets supervisor from database given id
    // if no supervisor with that id exists, return null
    // otherwise return the user data as array
    public function getSupervisor($id) {
        $query = $this->conn->prepare("SELECT * FROM sample_supervisor WHERE id = :id");

        $query->bindParam(":id", $id, PDO::PARAM_INT);

        $query->execute();

        while($row = $query->fetch(PDO::FETCH_ASSOC)) {
            return $row;
        } 
        return NULL;
    }
    
    // takes in supervisor id and hostID (store id)
    // adds host_id to supervisor in database
    // checks if supervisor already has chosen a host
    public function addHost($id, $hostID) {

        $user = $this->getSupervisor($id);
        
        if(!is_null($user['host_id'])) {
            echo "<p>You have already set a host store!</p>";
            return;
        } else {
            $query = $this->conn->prepare("UPDATE sample_supervisor SET host_id = :hostID WHERE id = :id");

            $query->bindParam(":id", $id, PDO::PARAM_INT);
            $query->bindParam(":hostID", $hostID, PDO::PARAM_INT);
    
            $query->execute();
    
            echo "<p>Host Store Set </p>";
        }
    }

    // gets total host count from database, returns the count
    public function getTotalHosts() {
        $query = $this->conn->prepare("SELECT COUNT(host_id) as host_total FROM sample_supervisor WHERE host_id is not NULL or host_id = ''");

        $query->execute();

        $row = $query->fetch(PDO::FETCH_ASSOC);

        return $row;
    }
}
?>