<?php 
    include("classes/UIController.php");
?>

<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" href="assets/css/styles.css?v=<?php echo time(); ?>">
</head>
<body>
    <h1>Supervisor Report</h1>
    <div class="reports container">
        <div class="totals-container">
            <?php 
                $controller = new UIController();
                $controller->displayHostTotal();
            ?>
        </div>
        <?php
            // get all supervisors and show details in html table
            $controller = new UIController();
            $controller->displaySupervisors();
        ?>
    </div>
</body>
</html>