
<?php
include "db.php";

$message = "";

if (isset($_POST["register"])) {

    $username = trim($_POST["username"]);
    $password = $_POST["password"];
    $password2 = $_POST["password2"];

    if ($username == "" || $password == "" || $password2 == "") {

        $message = "Please fill in all fields.";

    } elseif ($password != $password2) {

        $message = "Passwords do not match.";

    } else {

        $check = mysqli_prepare($conn, "SELECT id FROM users WHERE username = ?");
        mysqli_stmt_bind_param($check, "s", $username);
        mysqli_stmt_execute($check);
        mysqli_stmt_store_result($check);

        if (mysqli_stmt_num_rows($check) > 0) {

            $message = "Username already exists.";

        } else {

            $hashed_password = password_hash($password, PASSWORD_DEFAULT);

            $sql = mysqli_prepare($conn, "INSERT INTO users (username, password) VALUES (?, ?)");
            mysqli_stmt_bind_param($sql, "ss", $username, $hashed_password);

            if (mysqli_stmt_execute($sql)) {
                $message = "Registration successful! You can now sign in.";
            } else {
                $message = "Registration failed.";
            }

            mysqli_stmt_close($sql);
        }

        mysqli_stmt_close($check);
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Register</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<div class="container">

    <h2>Create Account</h2>
    <p class="subtitle">Register your account</p>

    <?php if ($message != "") { ?>
        <p class="message">
            <?php echo htmlspecialchars($message); ?>
        </p>
    <?php } ?>

    <form method="POST">

        <label>Username</label>
        <input type="text" name="username"
        placeholder="Enter username" required>

        <label>Password</label>
        <input type="password" name="password"
        placeholder="Enter password" required>

        <label>Confirm Password</label>
        <input type="password" name="password2"
        placeholder="Re-enter password" required>

        <button type="submit" name="register">
            Register
        </button>

    </form>

    <p class="link">
        Already have an account?
        <a href="login.php">Sign In</a>
    </p>

</div>

</body>
</html>
