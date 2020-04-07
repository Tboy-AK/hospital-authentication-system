<?php include_once('lib/header.php'); ?>

<?php 
    session_start();
?>

<header >
    <h1 >Welcome to SNG</h1>
    <p >...Hospital for the ignorant</p><br/>
</header>

<main >
    <form method="POST" action="processregister.php">
        <?php
            if (isset($_SESSION["error"]) && !empty($_SESSION["error"])) {
                echo "<p style='color: red' >".$_SESSION["error"]."</p>";
                $_SESSION["error"] = "";
            }
        ?>
        <p >
            <label for="first_name" >First Name</label><br/>
            <input
                <?php
                    if (isset($_SESSION["first_name"]) && !empty($_SESSION["first_name"])) {
                        echo "value=".$_SESSION["first_name"];
                        $_SESSION["first_name"]="";
                    }
                ?>
                type="text" name="first_name" placeholder="First Name"
            />
        </p>
        <p >
            <label for="last_name" >Last Name</label><br/>
            <input
                <?php
                    if (isset($_SESSION["last_name"]) && !empty($_SESSION["last_name"])) {
                        echo "value=".$_SESSION["last_name"];
                        $_SESSION["last_name"]="";
                    }
                ?>
                type="text" name="last_name" placeholder="Last Name"  />
        </p>
        <p >
            <label for="email" >Email</label><br/>
            <input
                <?php
                    if (isset($_SESSION["email"]) && !empty($_SESSION["email"])) {
                        echo "value=".$_SESSION["email"];
                        $_SESSION["email"]="";
                    }
                ?>
                type="text" name="email" placeholder="Email"  />
        </p>
        <p >
            <label for="password" >Password</label><br/>
            <input type="password" name="password" placeholder="Password"  />
        </p>
        <p >
            <label for="department" >Gender</label><br/>
            <select name="gender" >
                <option
                    <?php
                        if (isset($_SESSION["gender"]) && $_SESSION["gender"] == "Male") {
                            echo "selected";
                            $_SESSION["gender"]="";
                        }
                    ?>
                >Male</option>
                <option
                    <?php
                        if (isset($_SESSION["gender"]) && $_SESSION["gender"] == "Female") {
                            echo "selected";
                            $_SESSION["gender"]="";
                        }
                    ?>
                >Female</option>
            </select>
        </p>
        <p >
            <label for="designation" >Designation</label><br/>
            <select name="designation" >
                <option
                    <?php
                        if (isset($_SESSION["designation"]) && $_SESSION["designation"] == "Patient") {
                            echo "selected";
                            $_SESSION["designation"]="";
                        }
                    ?>
                >Patient</option>
                <option
                    <?php
                        if (isset($_SESSION["designation"]) && $_SESSION["designation"] == "Medical team") {
                            echo "selected";
                            $_SESSION["designation"]="";
                        }
                    ?>
                >Medical team</option>
            </select>
        </p>
        <p >
            <label for="department" >Department</label><br/>
            <input
                <?php
                    if (isset($_SESSION["department"]) && !empty($_SESSION["department"])) {
                        echo "value=".$_SESSION["department"];
                        $_SESSION["department"]="";
                    }
                ?>
                type="text" name="department" list="departments" placeholder="Department"
            />
            <datalist id="departments" >
                <option >ENT - ear, nose and throat</option>
                <option >ICU - intensive care unit</option>
            </datalist>
        </p>
        <p >
            <button type="submit">Submit</button>
        </p>
    </form>

    <?php
        if (isset($_SESSION["error"]) && !empty($_SESSION["error"])) {
            session_unset();
        }
    ?>
</main>


<?php include_once('lib/footer.php'); ?>