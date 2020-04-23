<?php

    // Start user session

    session_start();

    // Collecting the user input errors

    $errorArray = [];

    // Validating the user input

    //Validate user token
    
    if (empty($_POST["token"])) {

        $errorArray["token"] = "User's token cannot be empty";

    }

    //Validate user email

    if (empty($_POST["email"])) {

        $errorArray["email"] = "User's email cannot be empty";

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

    if (count($errorArray) > 0) {

        $_SESSION["error"] = $errorArray["password"];
        
        header("location: reset.php?token=$token&email=$email");

    } else {

        $users_dir = "./db/users";
        
        if (count(array_filter(scandir($users_dir), function ($v) {return $v == $_POST["email"].".json";})) > 0) {
            $user_file_arr = array_filter(scandir($users_dir), function ($v) {return $v == $_POST["email"].".json";});
            $user_file = $user_file_arr[array_key_first($user_file_arr)];
        }

        // Confirm that user email exists
        
        $user = json_decode(file_get_contents($users_dir."/".$user_file));
        $user->password = password_hash($password, PASSWORD_BCRYPT);

        try {
            file_put_contents($user_file, json_encode($user));
        } catch (Exception $e) {
            $_SESSION["error"] = "Internal server error. Try resetting your password again";

            header("location: reset.php?token=$token&email=$email");
            return;
        }
    }
?>