<?php 
    include("classes/UIController.php");

    // check url params for id
    if(!empty($_GET['id'])) {
        $id = $_GET['id'];
        $provider = new SupervisorProvider($conn);
        $user = $provider->getSupervisor($id);
    }
?>

<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" href="assets/css/styles.css?v=<?php echo time(); ?>">
</head>
<body>
    <?php
        // if user doesnt exit throw error
        if (is_null($user)) {
            echo '<p class="status-code">404 NOT FOUND</p>';
            exit();
        }
    ?>
    <h1>Welcome <?php
        // supervisor exists, welcome them 
        echo $user['firstname'] . ' ' . $user['lastname'];
     ?></h1>
    <?php
        // if user has chosen a host already,
        // they cannot proceed with the form, exit
        if (!is_null($user['host_id'])) {
            echo '<p class="form-msg">You have already chosen a host</p>';
            exit();
        } 
    ?>
    <!-- Form for selecting a host store-->

    <p class="info-text">Select a single store as a host from the dropdown menu</p>
    <form action="process_form.php" method="POST">
        <select class="store-select" name="store">
            <option value="">None</option>
            <?php
                // get all stores and show store name as an option
                $controller = new UIController();
                $controller->selectStores();
            ?>
        </select>
        <input type="Number" class="user-id" name="id" value=<?php echo $id ?>>
        <button class="btn-submit" type="submit" value="Submit">Submit</button>
    </form>
</body>
</html>