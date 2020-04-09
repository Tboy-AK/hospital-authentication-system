<p >
    
    <?php if (!isset($_SESSION["loggedin"]) || empty($_SESSION["loggedin"])) {?>

        <a href="./index.php">Home</a>
        <span > | </span>
        <a href="./register.php">Register</a>
        <span > | </span>
        <a href="./login.php" >Login</a>
        <span > | </span>
        <a href="./forgot.php" >Forgot</a>

    <?php } else { ?>

        <a 
            <?php
                switch ($_SESSION["designation"]) {
                    case 'Patient':
                        echo("href='./patient.php'");
                        break;
                    
                    case 'Medical team':
                        echo("href='./medical_team.php'");
                        break;
                        
                    case 'Super admin':
                        echo("href='./admin.php'");
                        break;
        
                    default:
                        echo("href='./index.php'");
                        break;
                }
                
            ?>
        >Dashboard</a>
        <span > | </span>
        <?php if ($_SESSION["designation"] === "Super admin") { ?>
            <a href="./register.php" >Add user</a>
            <span > | </span>
        <?php } ?>
        <?php if ($_SESSION["designation"] === "Medical team") { ?>
            <a href="./register.php" >Add patient</a>
            <span > | </span>
        <?php } ?>
        <a href="./logout.php" >Logout</a>
        <span > | </span>
        <a href="./reset.php" >Reset Password</a>
        
    <?php } ?>
</p>
