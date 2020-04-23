<?php include_once('lib/header.php'); ?>

<?php

    // Redirect user to the dashboard if already logged in

    if (isset($_SESSION["loggedin"]) && !empty($_SESSION["loggedin"]) && $_SESSION["designation"] !== "Super admin") {
        switch ($_SESSION["designation"]) {
            case 'Patient':
                header("location: patient.php");
                break;
            
            case 'Medical team':
                header("location: medical_team.php");
                break;
                
            default:
                header("location: logout.php");
                break;
        }
        
    }

?>

<header >
    <h1 >Reset Password</h1>
    <p >Reset the password associated with your account : [email]</p><br/>
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
        <p >
            <label for="email" >Email</label><br/>
            <input readonly value="[email]"
                <?php
                    if (isset($_SESSION["email"]) && !empty($_SESSION["email"])) {
                        echo "value="."'".$_SESSION["email"]."'";
                        $_SESSION["email"]="";
                    }
                ?>
                type="email" name="email" placeholder="Email"  />
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