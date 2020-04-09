<?php include_once('lib/header.php'); ?>

<?php

    // Redirect user to login to have access to this page

    if (!isset($_SESSION["loggedin"]) || empty($_SESSION["loggedin"])) {
        header("location: login.php");
        return;
    }
?>

<header >
    <h3 >Patient Dashboard</h3>
    <p >Hello <?php print_r($_SESSION["name"]); ?></p>
    <p ><?php print_r($_SESSION["email"]); ?></p>
    <h1 >Welcome to SNG</h1>
    <p >...Hospital for the ignorant</p><br/>
</header>

<footer >
    <p ><?php echo "User ID: ".$_SESSION["uid"]; ?></p>
    <p ><?php echo "Access level: ".$_SESSION["designation"]; ?></p>
    <p ><?php echo "Department: ".$_SESSION["department"]; ?></p>
    <p ><?php echo "Date of registration: ".$_SESSION["reg_date"]; ?></p>
    <p ><?php echo "Date of last login: ".$_SESSION["login_date"]; ?></p>
    <p ><?php echo "Time of last login: ".$_SESSION["login_time"]; ?></p>
    <?php include_once('lib/footer.php'); ?>
</footer>