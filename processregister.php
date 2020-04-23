<?php

    // Start user session

    session_start();
    
    // Collecting the user input errors

    $errorArray = [];

    // Validating the user input

    //Validate user first name
    
    if (empty($_POST["first_name"])) {

        $errorArray["first_name"] = "User's first name cannot be empty";

    } else if (strlen($_POST["first_name"]) < 2) {

        $errorArray["first_name"] = "User's name cannot be a single character";
    
    } else {

        $hasNumber = FALSE;

        for ($i = 0; $i < strlen($_POST["first_name"]); $i++) {

            if (ctype_digit($_POST["first_name"][$i])) {
                $hasNumber = TRUE;
            break;
            }

        }

        if ($hasNumber === TRUE) {
            $errorArray["first_name"] = "User's name should not contain number";
        } else {
            $first_name = $_POST["first_name"];
        };

    }

    //Validate user last name
    
    if (empty($_POST["last_name"])) {

        $errorArray["last_name"] = "User's last name cannot be empty";

    } else if (strlen($_POST["last_name"]) < 2) {

        $errorArray["last_name"] = "User's name cannot be a single character";
    
    } else {

        $hasNumber = FALSE;

        for ($i = 0; $i < strlen($_POST["last_name"]); $i++) {

            if (ctype_digit($_POST["last_name"][$i])) {
                $hasNumber = TRUE;
            break;
            }

        }

        if ($hasNumber === TRUE) {
            $errorArray["last_name"] = "User's name should not contain number";
        } else {
            $last_name = $_POST["last_name"];
        };

    }

    //Validate user email

    if (empty($_POST["email"])) {

        $errorArray["email"] = "User's email cannot be empty";

    } else {
        
        $atCount = 0;
        $atFirstIndex = strpos($_POST["email"], "@");

        for ($i = 0; $i < strlen($_POST["email"]); $i++) {

            if ($_POST["email"][$i] === "@") {
                $atCount++;
            }

        }

        for ($i = 0; $i < strlen($_POST["email"]); $i++) {

            if ($_POST["email"][$i] === ".") {
                $dotLastIndex = $i;
            }

        }

        if (!($atCount === 1)) {
            $errorArray["email"] = "Email must include an @";
        } else if ($atFirstIndex < 5) {
            $errorArray["email"] = "Email requires a name of at least 5 characters";
        } else if (!strpos($_POST["email"], ".")) {
            $errorArray["email"] = "Email must include at least a dot '.'";
        } else if (($dotLastIndex - strpos($_POST["email"], "@")) < 2 || $dotLastIndex === (strlen($_POST["email"])-1)) {
            $errorArray["email"] = "Email must have a domain e.g. email@domainname.com";
        } else {

            for ($i = $dotLastIndex + 1; $i < strlen($_POST["email"]); $i++) {
    
                if (!ctype_alpha($_POST["email"][$i])) {
                    $errorArray["email"] = "Domain anchor must be alphabetical letters e.g '.com', '.org'...";
                break;
                }
    
            }

            $email = $_POST["email"];
        }

    }

    //Validate user password

    if (empty($_POST["password"])) {
        
        $errorArray["password"] = "User's password cannot be empty";
        
    } else if (strlen($_POST["password"]) < 6) {

        $errorArray["password"] = "User's password must be at least 6 characters long";

    } else {

        for ($i = 0; $i < strlen($_POST["password"]); $i++) {

            if (ctype_digit($_POST["password"][$i])) {
                $hasNumber = TRUE;
                break;
            }

        }

        if ($hasNumber || strpos($_POST["password"], ".") || strpos($_POST["password"], "_") || strpos($_POST["password"], "*") || strpos($_POST["password"], "-")) {
            $password = $_POST["password"];
        } else {
            $errorArray["password"] = "User's password must contain at least a number or special characters including '.', '_', '*', '-'.";
        }
        
    }
    
    // Validate gender

    $gender = $_POST["gender"] != "" ? $_POST["gender"] : "male";

    // Validate designation

    $designation = $_POST["designation"] != "" ? $_POST["designation"] : "patient";

    //Validate user department
    
    if (empty($_POST["department"])) {
        
        $errorArray["department"] = "User's department cannot be empty";
        
    } else {

        $department = $_POST["department"];

    }

    // Confirm that there are no errors with the user input

    if (count($errorArray) > 0) {

        $_SESSION["first_name"] = $_POST["first_name"];
        $_SESSION["last_name"] = $_POST["last_name"];
        $_SESSION["email"] = $_POST["email"];
        $_SESSION["gender"] = $_POST["gender"];
        $_SESSION["designation"] = $_POST["designation"];
        $_SESSION["department"] = $_POST["department"];
        $_SESSION["error"] = $errorArray;

        header("location: register.php");

    } else {
        
        unset($errorArray);
        unset($_SESSION["error"]);

        // Get the count of users to assign a new sequential user id

        $users_dir = "./db/users";
        
        $all_users = array_diff(scandir($users_dir), array(".", "..", "keep.txt"));

        $id = count($all_users);

        // Confirm that user email does not already exist

        foreach ($all_users as &$user) {
            if (json_decode(file_get_contents($users_dir."/".$user))->email === $email) {

                // Redirect user to login page

                $_SESSION["error"] = "User already exists, please login";
                header("location: login.php");
                break;

            }
        }
        
        // Ignore the rest of the programme if email already exists
        
        if ($_SESSION["error"] === "User already exists, please login") {
            return;
        }

        // Assign new user id and register user to database

        $id++;

        $userObject = [
            "id" => $id,
            "first_name" => $first_name,
            "last_name" => $last_name,
            "email" => $email,
            "password" => password_hash($password, PASSWORD_BCRYPT),
            "gender" => $gender,
            "designation" => $email === "admin@sng.com" ? "Super admin" : $designation,
            "department" => $department,
            "reg_time" => time(),
        ];

        file_put_contents($users_dir."/".$email.".json", json_encode($userObject));

        // Redirect user to login page
        
        $_SESSION["message"] = "Registration successful, you can now log in ".$first_name;

        header("location: login.php");

    }
    
?>