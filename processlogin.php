<?php

    session_start();
        
    // collecting the data

    $errorArray = [];

    // Validating the user input

    $email = $_POST["email"] != "" ? $_POST["email"] : $errorArray["email"] = "User's email cannot be empty";
    $password = $_POST["password"] != "" ? $_POST["password"] : $errorArray["password"] = "User's password cannot be empty";

    if(count($errorArray) > 0){

        $_SESSION["error"] = "Incorrect login credentials";
        header("location: login.php");

    } else {

        $users_dir = "./db/users";
        
        $all_users = array_diff(scandir($users_dir), array(".", ".."));

        // Confirm that user email exists

        $active_user_arr = array_filter($all_users, function ($user) use ($users_dir, $email) {
            return in_array($email, json_decode(file_get_contents($users_dir."/".$user), TRUE));
        });

        if (!$active_user_arr) {
            // Redirect user to login page
            $_SESSION["error"] = "Incorrect email";
            header("location: login.php");
            return;
        }

        // Get user to verify password
        
        $active_user_file = $active_user_arr[array_key_first($active_user_arr)];

        $user = json_decode(file_get_contents($users_dir."/".$active_user_file), TRUE);

        if (!password_verify($password, $user["password"])) {
            // Redirect user to login page
            $_SESSION["error"] = "Incorrect password";
            header("location: login.php");
            return;
        }

        $_SESSION["user"] = json_encode(array_diff($user, array($user["password"])));
        $_SESSION["message"] = "Login successful";
        print_r($_SESSION["user"]);
        die();
        
    }

?>