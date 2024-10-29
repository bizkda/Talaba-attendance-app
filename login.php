<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Form</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css" integrity="sha384-Zenh87qX5JnK2Jl0vWa8Ck2rdkQ2Bzep5IDxbcnCeuOxjzrPF/et3URy9Bv1WTRi" crossorigin="anonymous">
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container mt-5">
    <?php
       ini_set('display_errors', 1);
       ini_set('display_startup_errors', 1);
       error_reporting(E_ALL);
       session_start();
                       
       if (isset($_POST["login"])) {
        // Fetch email and password from POST
        $email = $_POST["email"] ?? null;
        $password = $_POST["password"] ?? null;
    
        // Debugging output to verify values
        echo "Email: " . htmlspecialchars($email) . "<br>";
        echo "Password: " . htmlspecialchars($password) . "<br>";
    
        // Proceed with the login logic
        if ($email && $password) {
            require_once "connect.php";
            $sql = "SELECT * FROM users WHERE email = '$email'";
            $result = mysqli_query($conn, $sql);
            $user = mysqli_fetch_array($result, MYSQLI_ASSOC);
            // i'm a genius 
            $password = $_POST["password"] ?? null;

            if ($user) {
                // After verifying the password
                if (password_verify($password, $user["password"])) {
                    if (session_status() === PHP_SESSION_NONE) {
                        session_start();
                    }
                    
                    $_SESSION["chikh_name"] = $user["full_name"]; // Store the full name in the session
                    $_SESSION["user_id"] = $user["id"]; // Store the user ID in the session
                    $_SESSION["user"] = "yes"; // Indicate that the user is logged in
                    
                    header("Location: table.php"); // Redirect to the table page
                    exit(); // Exit to ensure no further script execution
                } else {
                    echo "<div class='alert alert-danger'>Password does not match</div>";
                }

                    
            } else {
                echo "<div class='alert alert-danger'>Email does not match</div>";
            }
        } else {
            echo "<div class='alert alert-danger'>Please fill in both email and password</div>";
        }
    }
    

    ?>
    <form action="login.php" method="post">
        <div class="form-group">
            <input type="email" placeholder="Email:" name="email" class="form-control">
        </div>
        <div class="form-group">
            <input type="password" placeholder=":كلمة المرور" name="password" class="form-control">
        </div>
        <div class="form-btn">
            <input type="submit" value="دخول" name="login" class="btn btn-primary">
        </div>
    </form>
    <div><p>لست مسجلا ؟ <a href="registration.php">سجل هنا</a></p>
    </div>
</body>
</html>