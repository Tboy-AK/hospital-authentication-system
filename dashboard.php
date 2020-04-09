<?php include_once('lib/header.php'); ?>

<?php

    // Redirect user to login to have access to this page

    if (!isset($_SESSION["loggedin"]) || empty($_SESSION["loggedin"])) {
        header("location: login.php");
        return;
    }
?>

<header >
    <h3 >Dashboard</h3>
    <p >Hello <?php print_r($_SESSION["name"]); ?></p>
    <h1 >Welcome to SNG</h1>
    <p >...Hospital for the ignorant</p><br/>
</header>

<footer >
    <p ><?php echo $_SESSION["name"]." of ".$_SESSION["office"]." is logged in as (".$_SESSION["role"].") "." with user id (".$_SESSION["loggedin"].")"; ?></p>
    <?php include_once('lib/footer.php'); ?>
</footer>