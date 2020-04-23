<?php

    // Start user session

    session_start();
        
    // collecting the data

    $errorArray = [];

    // Validating the user input

    //Validate user email

    if (empty($_POST["email"])) {

        $errorArray["email"] = "User's email cannot be empty";

    } else {
        
        $atCount = 0;
        $atFirstIndex = strpos($_POST["email"], "@");

        for ($i = 0; $i < strlen($_POST["email"]); $i++) {

            if ($_POST["email"][$i] == "@") {
                $atCount++;
            }

        }

        for ($i = 0; $i < strlen($_POST["email"]); $i++) {

            if ($_POST["email"][$i] == ".") {
                $dotLastIndex = $i;
            }

        }

        if (!($atCount === 1)) {
            $errorArray["email"] = "Email must include an @";
        } else if ($atFirstIndex < 5) {
            $errorArray["email"] = "Email requires a name of at least 5 characters";
        } else if (!strpos($_POST["email"], ".")) {
            $errorArray["email"] = "Email must include at least a dot '.'";
        } else if (($dotLastIndex - strpos($_POST["email"], "@")) < 2 || $dotLastIndex == (strlen($_POST["email"])-1)) {
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
    
    if(count($errorArray) > 0){

        $_SESSION["error"] = "Incorrect login credentials";
        header("location: login.php");

    } else {

        $users_dir = "./db/users";
        
        $all_users = array_diff(scandir($users_dir), array(".", ".."));

        // Confirm that user email exists

        $active_user_arr = array_filter($all_users, function ($user) use ($users_dir, $email) {
            return json_decode(file_get_contents($users_dir."/".$user))->email === $email;
        });

        if (!$active_user_arr) {
            // Redirect user to login page
            $_SESSION["error"] = "Incorrect email";
            header("location: login.php");
            return;
        }
        
        $active_user_file = $active_user_arr[array_key_first($active_user_arr)];

        $user = json_decode(file_get_contents($users_dir."/".$active_user_file));

        // Get user to verify password

        if (!password_verify($password, $user->password)) {
            // Redirect user to login page
            $_SESSION["error"] = "Incorrect password";
            header("location: login.php");
            return;
        }

        $user->last_login_time = time();
        $login_date = date("l jS \of F Y", $user->last_login_time);
        $reg_date = date("l jS \of F Y", $user->reg_time);

        $_SESSION["designation"] = $user->designation;
        $_SESSION["name"] = $user->first_name." ".$user->last_name;
        $_SESSION["email"] = $user->email;
        $_SESSION["department"] = $user->department;
        $_SESSION["uid"] = $user->id;
        $_SESSION["reg_date"] = $reg_date;
        $_SESSION["login_date"] = $login_date;
        $_SESSION["login_time"] = date("h:i:s", $user->last_login_time);

        $_SESSION["loggedin"] = TRUE;
        $_SESSION["message"] = "Login successful";


        file_put_contents($users_dir."/".$active_user_file, json_encode($user));

        switch ($user->designation) {
            case 'Patient':
                header("location: patient.php");
                break;
            
            case 'Medical team':
                header("location: medical_team.php");
                break;
                
            case 'Super admin':
                header("location: admin.php");
                break;

            default:
                print_r("Error in process database");
                break;
        }
        
    }

?>