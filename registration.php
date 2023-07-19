<?php
    $name = $email = $password = $confirmPassword = "";
    $nameErr = $emailErr = $passwordErr = $confirmPasswordErr = "";

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        if (empty($_POST["name"])) {
            $nameErr = "Name is required";
        } else {
            $name = test_input($_POST["name"]);
            if (!preg_match("/^[a-zA-Z-' ]*$/", $name)) {
                $nameErr = "Only letters and white space allowed";
            }
        }

        if (empty($_POST["email"])) {
            $emailErr = "Email is required";
        } else {
            $email = test_input($_POST["email"]);
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $emailErr = "Invalid email format";
            }
        }

        if (empty($_POST["password"])) {
            $passwordErr = "Password is required";
        } else {
            $password = test_input($_POST["password"]);
            // Password must be at least 8 characters long and contain letters and numbers.
            if (strlen($password) < 8 || !preg_match("/[a-zA-Z]/", $password) || !preg_match("/\d/", $password)) {
                $passwordErr = "Password must be at least 8 characters long and contain letters and numbers";
            }
        }

        if (empty($_POST["confirmPassword"])) {
            $confirmPasswordErr = "Confirm Password is required";
        } else {
            $confirmPassword = test_input($_POST["confirmPassword"]);
            if ($confirmPassword !== $password) {
                $confirmPasswordErr = "Passwords do not match";
            }
        }

        // If there are no errors, proceed to store the user information.
        if (empty($nameErr) && empty($emailErr) && empty($passwordErr) && empty($confirmPasswordErr)) {
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

            // Create an associative array with user information.
            $user = [
                "name" => $name,
                "email" => $email,
                "hashedPassword" => $hashedPassword
            ];

            // Read the existing user data from the "users.json" file.
            $usersData = [];
            if (file_exists("users.json")) {
                $usersData = json_decode(file_get_contents("users.json"), true);
            }

            // Add the new user to the array of registered users.
            $usersData[] = $user;

            // Write the updated user data back to the "users.json" file.
            file_put_contents("users.json", json_encode($usersData));

            // Clear the form fields after successful registration.
            $name = $email = $password = $confirmPassword = "";
        }
    }

    function test_input($data) {
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Registration System</title>
</head>
<body>
    <!--Bijay Gurung (2357590)-->
    <div class="User Registration">
        <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
            <input type="text" name="name" placeholder="Name:" value="<?php echo $name; ?>">
            <span class="error">* <?php echo $nameErr; ?></span><br>
            <input type="email" name="email" placeholder="Email:" value="<?php echo $email; ?>">
            <span class="error">* <?php echo $emailErr; ?></span><br>
            <input type="password" name="password" placeholder="Password:" value="<?php echo $password; ?>">
            <span class="error">* <?php echo $passwordErr; ?></span><br>
            <input type="password" name="confirmPassword" placeholder="Confirm Password:" value="<?php echo $confirmPassword; ?>">
            <span class="error">* <?php echo $confirmPasswordErr; ?></span><br>
            <button type="submit">Submit</button>
        </form>
    </div>
</body>
</html>
