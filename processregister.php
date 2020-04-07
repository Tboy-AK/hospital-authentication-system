<?php

    // Start session for user data storage

    session_start();

    // Collecting the user input errors

    $errorArray = [];

    // Validating the user input

    $first_name = $_POST["first_name"] != "" ? $_POST["first_name"] : $errorArray["first_name"] = "User's first name cannot be empty";
    $last_name = $_POST["last_name"] != "" ? $_POST["last_name"] : $errorArray["last_name"] = "User's last name cannot be empty";
    $email = $_POST["email"] != "" ? $_POST["email"] : $errorArray["email"] = "User's email cannot be empty";
    $password = $_POST["password"] != "" ? $_POST["password"] : $errorArray["password"] = "User's password cannot be empty";
    $gender = $_POST["gender"];
    $designation = $_POST["designation"];
    $department = $_POST["department"] != "" ? $_POST["department"] : $errorArray["department"] = "User's department cannot be empty";

    // Confirm that there are no errors with the user input

    if (count($errorArray) > 0) {

        $_SESSION["first_name"] = $_POST["first_name"];
        $_SESSION["last_name"] = $_POST["last_name"];
        $_SESSION["email"] = $_POST["email"];
        $_SESSION["gender"] = $_POST["gender"];
        $_SESSION["designation"] = $_POST["designation"];
        $_SESSION["department"] = $_POST["department"];
        $_SESSION["error"] = "Incomplete registration credentials";

        header("location: register.php");

    } else {

        // Get the count of users to assign a new sequential user id

        $users_dir = "./db/users";
        
        $all_users = array_diff(scandir($users_dir), array(".", ".."));

        $id = count($all_users);

        // Confirm that user email does not already exist

        foreach ($all_users as &$user) {
            if (json_decode(file_get_contents($users_dir."/".$user), TRUE)["email"] == $email) {

                // Redirect user to login page

                $_SESSION["error"] = "User already exists, please login";
                header("location: login.php");
                break;

            }
        }
        
        // Ignore the rest of the programme if email already exists
        
        if ($_SESSION["error"] == "User already exists, please login") {
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
            "designation" => $designation,
            "department" => $department,
        ];

        file_put_contents($users_dir."/".$first_name."_".$last_name."_".$id.".json", json_encode($userObject));

        // Redirect user to login page
        
        $_SESSION["message"] = "Registration successful, you can now log in ".$first_name;

        header("location: login.php");

    }
    
?>