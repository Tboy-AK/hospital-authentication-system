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
    
    if(count($errorArray) > 0){
        $_SESSION["email"] = $_POST["email"];
        $_SESSION["error"] = $errorArray;

        header("location: forgot.php");

    } else {

        $users_dir = "./db/users";
        
        $all_users = array_diff(scandir($users_dir), array(".", ".."));

        // Confirm that user email exists

        $active_user_arr = array_filter($all_users, function ($user) use ($users_dir, $email) {
            return json_decode(file_get_contents($users_dir."/".$user))->email === $email;
        });

        if (!$active_user_arr) {
            // Redirect user to forgot page
            $_SESSION["error"] = "Email not registered with us ".$email;
            header("location: forgot.php");
            return;
        }

        // Send reset link to the user's email address

        // Token generation and storage

        $token = bin2hex(random_bytes(64));
        $tokens_file = "./db/tokens/reset_tokens.json";
        $id = 0;
        
        $tokens_file_content = json_decode(file_get_contents($tokens_file));
        $all_tokens = $tokens_file_content->data;

        $id = count($all_tokens);

        $tokenObject = [
            "id" => $id++,
            "email" => $email,
            "token" => password_hash($token, PASSWORD_BCRYPT),
            "reg_time" => time(),
        ];

        array_push($all_tokens, $tokenObject);

        $tokens_file_content->data = $all_tokens;

        try {
            file_put_contents($tokens_file, json_encode($tokens_file_content));

        } catch (Exception $e) {
            $_SESSION["error"] = "Something went wrong. Try resetting your password again";
            $_SESSION["email"] = $_POST["email"];

            header("location: forgot.php");
            return;
        }

        $subject = "Password Reset Link";
        $message = "A password reset has been initiated from your account. ".
        "If you did not initiate this action, please ignore this message, otherwise, visit localhost/hospital-authentication-system/reset.php?".
        "token=".$token."&email=".$email;
        $headers = "From: no-reply@snh.ng"."\r\n"."CC: oluwatobiloba_akanji@snh.ng";

        try {
            mail($email, $subject, $message, $headers);

            $_SESSION["message"] = "Password reset has been sent to your email";

            header("location: login.php");

        } catch (Exception $e) {
            $_SESSION["error"] = "Something went wrong. Try resetting your password again";
            $_SESSION["email"] = $_POST["email"];

            header("location: forgot.php");
            return;
        }

        /*$active_user_file = $active_user_arr[array_key_first($active_user_arr)];

        $user = json_decode(file_get_contents($users_dir."/".$active_user_file));

        $_SESSION["email"] = $user->email;

        $_SESSION["message"] = "You can now login";

        file_put_contents($users_dir."/".$active_user_file, json_encode($user));*/

    }

?>