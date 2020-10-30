<?php

    include("db/config.php");
    include("classes/StoreProvider.php");

    // function to compute distance between to locations
    // given both locations longitude and latitude
    function calculateDistance($lat1, $lon1, $lat2, $lon2) {
        if (($lat1 == $lat2) && ($lon1 == $lon2)) {
            return 0;
        } else {
            $theta = $lon1 - $lon2;
            $dist = sin(deg2rad($lat1)) * sin(deg2rad($lat2)) +  cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * cos(deg2rad($theta));
            $dist = acos($dist);
            $dist = rad2deg($dist);
            $miles = $dist * 60 * 1.1515;
        }

        return $miles;
    }

    // function to find surrounding stores 15 miles within a host store
    // nested loop used to check each store against all other stores
    function findSurroundingStores() {
        global $conn;
        $provider = new StoreProvider($conn);
        $stores = $provider->getAllStores();

        for($i = 0;$i < count($stores);$i++) {
            for($j = 0;$j < count($stores);$j++) {
                // check if we have a host store
                if(($stores[$i]['id'] != $stores[$j]['id']) && ($stores[$i]['type'] == 'Host') && ($stores[$j]['type'] != 'Host')) {
                    // calculate distance for all other stores from the host store
                    $miles = calculateDistance($stores[$i]['latitude'], $stores[$i]['longitude'], $stores[$j]['latitude'], $stores[$j]['longitude']);
                    // if distance is <= 15, we have a surrounding store
                    if ($miles <= 15) {
                        // check if this store already has a host associated with it
                        if(!is_null($stores[$j]['host_id'])) {
                            // get stores distance value to its current host and check against the new one
                            // if the new stores distance is greater skip to next iteration
                            // otherwise continue this iteration
                            if($miles >= $stores[$j]['distance_to_host']) {
                                continue;
                            }
                        }
                        // update the store info in the database
                        // i.e. change type, give surrounding store host_id, add distance
                        $provider->updateSurroundingStoreHost($stores[$i]['id'], $stores[$j]['id']);
                        $provider->changeStoreType($stores[$j]['id'],'Surrounding');
                        $provider->updateDistanceToHost($stores[$j]['id'], round($miles,1));
                    }
                }
            }
        }
    }
?>