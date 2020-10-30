<?php 
    include("classes/UIController.php");
?>

<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" href="assets/css/styles.css?v=<?php echo time(); ?>">
</head>
<body>
    <h1>Store Report</h1>
    <div class="reports container">
        <div class="totals-container">
            <?php 
                $controller = new UIController();
                $controller->displayStoreTotals();
            ?>
        </div>
        <?php
            // get all stores from database and show details in html table
            $controller->displayStores();
        ?>
    </div>
</body>
</html>