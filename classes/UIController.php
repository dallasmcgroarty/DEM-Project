<?php 
    include('db/config.php');
    include('classes/SupervisorProvider.php');
    include('classes/StoreProvider.php');

// UIController class used to display all information from the 
// database on the web page
// has access to SupervisorProvider and StoreProvider classes
class UIController {

    // public function __construct() {
        
    // }

    // display all supervisors as html
    public function displaySupervisors() {
        global $conn;
        $provider = new SupervisorProvider($conn);
        $supervisors = $provider->getAllSupervisors();

        $results = "<table class='supervisors'>
                        <tr>
                            <th>Link to Form</th>
                            <th>Name</th>
                            <th>Host ID</th>
                            <th>Store Name</th>
                            <th>Region</th>
                        </tr>";

        foreach ($supervisors as $row) {
            $id = $row['id'];
            $name = $row['firstname'] . ' ' . $row['lastname'];
            $hostID = $row['host_id'];
            
            if (!is_null($hostID)) {
                $provider = new StoreProvider($conn);
                $store = $provider->getStore($hostID);

                $storeName = $store['name'];
                $region = $store['region'];
            } else {
                $storeName = '';
                $region = '';
            }

            $results .= "<tr>
                            <td>
                            <a href='form.php?id=$id'>$name's Form</a>
                            </td>
                            <td>$name</td>
                            <td>$hostID</td>
                            <td>$storeName</td>
                            <td>$region</td>
                        </tr>";
        }

        $results .= "</table>";
        echo $results;
    }

    // display all stores as html
    public function displayStores() {
        global $conn;
        $provider = new StoreProvider($conn);
        $stores = $provider->getAllStores();

        $results = "<table class='stores'>
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Type</th>
                            <th>Region</th>
                            <th>Host ID</th>
                            <th>Distance to Host (mi)</th>
                        </tr>";

        foreach ($stores as $row){
            $id = $row['id'];
            $name = $row['name'];
            $type = $row['type'];
            $region = $row['region'];
            $host = $row['host_id'];
            $distance = $row['distance_to_host'];

            $results .= "<tr>
                            <td>$id</td>
                            <td>$name</td>
                            <td>$type</td>
                            <td>$region</td>
                            <td>$host</td>
                            <td>$distance</td>
                        </tr>";
        }

        $results .= "</table>";
        echo $results;
    }

    // display stores as options in html select tag
    public function selectStores() {
        global $conn;
        $provider = new StoreProvider($conn);
        $stores = $provider->getAllStores();

        $result = '';

        foreach ($stores as $row) {
            $id = $row['id'];
            $name = $row['name'];

            $result .= "<option value=$id>$name</option>";
        }
        
        echo $result;
    }

    // display store totals as html
    public function displayStoreTotals() {
        global $conn;
        $provider = new StoreProvider($conn);
        $totals = $provider->getTotals();

        $host = $totals['host_total'];
        $general = $totals['general_total'];
        $surrounding = $totals['surrounding_total'];

        echo "<p class='totals'>Total Hosts: $host</p>
              <p class='totals'>Total Surrounding Stores: $surrounding</p>
              <p class='totals'>Total General Stores: $general</p>";

    }

    // display total hosts for supervisors
    public function displayHostTotal() {
        global $conn;
        $provider = new SupervisorProvider($conn);
        $supervisors = $provider->getTotalHosts();

        $host = $supervisors['host_total'];

        echo "<p class='totals'>Total Hosts: $host</p>";
    }
}
?>