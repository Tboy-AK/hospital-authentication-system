<?php include_once('lib/header.php'); ?>

<?php

    session_unset();
    session_destroy();
    session_start();

    $_SESSION["message"]="Signout successful";

    header("location: login.php");

?>

<?php include_once('lib/footer.php') ?>