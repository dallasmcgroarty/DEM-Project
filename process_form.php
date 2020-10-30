<?php
    include('db/config.php');
    include('classes/SupervisorProvider.php');
    include('./surrounding_stores_finder.php');

    // if no host is chosen, set msg
    if(empty($_POST['store'])) {
        $msg = "<p>No host chosen!</p>";
    }

    // check form and user
    if(!empty($_POST['id'])) {
        $user_id = $_POST['id'];
        $provider = new SupervisorProvider($conn);
        $user = $provider->getSupervisor($user_id);

        // get store id from POST and store it
        $store_id = $_POST['store'];
    } else {
        // error if no id
        $msg = '<p>401 Unauthorized</p>';
    }
?>

<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" href="assets/css/styles.css?v=<?php echo time(); ?>">
</head>
<body>
    <?php 
        if (!empty($msg)) {
            echo $msg;
            exit();
        }

        // error if user has chosen a host already
        if (!is_null($user['host_id'])) {
            echo '<p class="form-msg">You have already chosen a host</p>';
            exit();
        }

        // add host_id to supervisor in database
        $provider->addHost($user_id, $store_id);

        // change chosen store type to host in database
        $provider = new StoreProvider($conn);
        $provider->changeStoreType($store_id, 'Host');

        // after adding a new host, update the surrounding stores
        findSurroundingStores();
        
    ?>
</body>
</html>
