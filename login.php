<?php include_once('lib/header.php'); ?>

<?php 
    session_start();
?>

<h1 >Login to SNG</h1>
<p >...Hospital for the ignorant</p><br />

<?php
    if (isset($_SESSION["message"]) && !empty($_SESSION["message"])) {
        echo "<p style='color: green' >".$_SESSION["message"]."</p>";
        $_SESSION["message"] = "";
    }
?>

<?php
    if (isset($_SESSION["error"]) && !empty($_SESSION["error"])) {
        echo "<p style='color: red' >".$_SESSION["error"]."</p>";
        $_SESSION["error"] = "";
    }
?>

<form method="POST" action="processlogin.php">
    <?php
        if (isset($_SESSION["error"]) && !empty($_SESSION["error"])) {
            echo "<p style='color: red' >". $_SESSION["error"]."</p>";
            session_unset();
        }
    ?>
    <p >
        <label >Email</label><br/>
        <input type="text" name="email" placeholder="Email"  />
    </p>
    <p >
        <label >Password</label><br/>
        <input type="password" name="password" placeholder="Password"  />
    </p>
    <p >
        <button type="submit">Submit</button>
    </p>
</form>

<?php include_once('lib/footer.php'); ?>