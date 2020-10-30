<?php

// StoreProvider class used for all interactions with the
// store table from the database
class StoreProvider {
    private $conn;

    public function __construct($conn) {
        $this->conn = $conn;
    }

    // get all stores from database and returns as array
    public function getAllStores() {
        $query = $this->conn->prepare("SELECT * FROM sample_storelist ORDER BY name ASC");
        $query->execute();

        $results = [];

        while($row = $query->fetch(PDO::FETCH_ASSOC)) {
            array_push($results, $row);
        }

        return $results;
    }

    // takes in store id
    // returns store info from database if exists, else returns null
    public function getStore($id) {
        $query = $this->conn->prepare("SELECT * FROM sample_storelist WHERE id = :id");

        $query->bindParam(":id", $id, PDO::PARAM_INT);

        $query->execute();

        while($row = $query->fetch(PDO::FETCH_ASSOC)) {
            return $row;
        } 
        return NULL;
    }

    // after adding host to supervisor call this function
    // takes in the store id and changes the type to host
    public function changeStoreType($storeID, $type) {
        $query = $this->conn->prepare("UPDATE sample_storelist SET type = :type WHERE id = :storeID");

        $query->bindParam(":storeID", $storeID, PDO::PARAM_INT);
        $query->bindParam(":type", $type);

        $query->execute();
    }

    // get totals from database returns array of totals
    public function getTotals() {
        $query = $this->conn->prepare("SELECT SUM(type = 'host') as host_total, SUM(type = 'General') as general_total, SUM(type = 'Surrounding') as surrounding_total FROM sample_storelist");

        $query->execute();

        $row = $query->fetch(PDO::FETCH_ASSOC);

        return $row;
    }

    // update surrounding stores host_id
    // takes in hostid and storeid
    public function updateSurroundingStoreHost($hostID, $storeID) {
        $query = $this->conn->prepare("UPDATE sample_storelist SET host_id = :hostID WHERE id = :storeID");

        $query->bindParam(":hostID", $hostID, PDO::PARAM_INT);
        $query->bindParam(":storeID", $storeID, PDO::PARAM_INT);

        $query->execute();
    }

    // update distance to host for a surrounding store
    // takes in storeid and distance value as decimal
    public function updateDistanceToHost($storeID, $distance) {
        $query = $this->conn->prepare("UPDATE sample_storelist SET distance_to_host = :distance WHERE id = :storeID");

        $query->bindParam(":storeID", $storeID, PDO::PARAM_INT);
        $query->bindParam(":distance", $distance);

        $query->execute();
    }
}
?>