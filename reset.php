<?php include_once('lib/header.php'); ?>

<?php

    // Redirect user to the dashboard if already logged in

    if (isset($_SESSION["loggedin"]) && !empty($_SESSION["loggedin"]) && $_SESSION["designation"] !== "Super admin") {
        switch ($_SESSION["designation"]) {
            case 'Patient':
                header("location: patient.php");
                return;
            
            case 'Medical team':
                header("location: medical_team.php");
                return;
                
            default:
                header("location: logout.php");
                return;
        }
        
    }

    if (!isset($_GET["token"]) || empty($_GET["token"]) || !isset($_GET["email"]) || empty($_GET["email"])) {
        $_SESSION["error"] = "You are not allowed to view that page";
        header("location: login.php");
        return;
    }

?>

<header >
    <h1 >Reset Password</h1>
    <p >Reset the password associated with your account</p><br/>
</header>

<main >
    <form method="POST" action="processreset.php">

        <?php
            if (isset($_SESSION["error"]["email"]) && !empty($_SESSION["error"]["email"])) {
                echo "<p style='color: red' >".$_SESSION["error"]["email"]."</p>";
                $_SESSION["error"]["email"] = "";

            } else if (isset($_SESSION["error"]) && !empty($_SESSION["error"])) {
                echo "<p style='color: red' >".$_SESSION["error"]."</p>";
                $_SESSION["error"] = "";
                
            } else if (isset($_SESSION["message"]) && !empty($_SESSION["message"])) {
                echo "<p style='color: green' >".$_SESSION["message"]."</p>";
                $_SESSION["message"] = "";
            }
        ?>

        <input name="token" type="hidden" value="<?php echo($_GET["token"]) ?>" />

        <p >
            <label for="email" >Email</label><br/>
            <input readonly value="<?php echo($_GET["email"]) ?>"
                type="email" name="email" placeholder="Email" />
        </p>

        <?php
            if (isset($_SESSION["error"]["password"]) && !empty($_SESSION["error"]["password"])) {
                echo "<p style='color: red' >".$_SESSION["error"]["password"]."</p>";
                $_SESSION["error"]["password"] = "";
            }
        ?>        
        <p >
            <label for="password" >New Password</label><br/>
            <input type="password" name="password" placeholder="Password"  />
        </p>

        <p >
            <button type="submit">Reset Password</button>
        </p>
    </form>

    <?php
        if (isset($_SESSION["error"]) && !empty($_SESSION["error"])) {
            session_unset();
        }
    ?>
</main>

<?php include_once('lib/footer.php'); ?>