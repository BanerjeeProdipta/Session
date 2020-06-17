<?php // Do not put any HTML above this line
session_start();

require_once "pdo.php";

if ( ! isset($_SESSION['name']) ) {
    die('Not logged in');
}
elseif (isset($_POST['make']) && isset($_POST['year']) && isset($_POST['mileage'])) {
    if (!is_numeric($_POST['year']) || !is_numeric($_POST['mileage'])) {
        $_SESSION['error'] = 'Mileage and year must be numeric';
        header("Location: add.php");
        return;
    }
    elseif (strlen($_POST['make']) < 1 ) {
        $_SESSION['error'] = 'Make is required';
        header("Location: add.php");
        return;
    }
    else {
        $stmt = $pdo->prepare('INSERT INTO autos (make, year, mileage) VALUES ( :make, :year, :mileage)');
        $stmt->execute(array(
                ':make' => $_POST['make'],
                ':year' => $_POST['year'],
                ':mileage' => $_POST['mileage'])
        );
        $_SESSION['success'] = "Record inserted";
        header("Location: view.php");
        return;
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <?php require_once "bootstrap.php"; ?>
    <title>96b5871b</title>
</head>
<body>
<div class="container">
    <h1>Tracking Autos for <?php echo $_SESSION['name']; ?></h1>
    <?php
    if ( isset($_SESSION['error']) ) {
    echo('<p style="color: red;">'.htmlentities($_SESSION['error'])."</p>\n");
    unset($_SESSION['error']);
    }
    elseif( isset($_SESSION['success']) ) {
    echo('<p style="color: green;">'.htmlentities($_SESSION['success'])."</p>\n");
    unset($_SESSION['success']);
    }
    ?>
    <form method="post">
        <p>Make:
            <input type="text" name="make" size="60"/></p>
        <p>Year:
            <input type="text" name="year"/></p>
        <p>Mileage:
            <input type="text" name="mileage"/></p>
        <input type="submit" value="Add">
        <input type="submit" name="logout" value="Logout">
    </form>
</div>
</body>
