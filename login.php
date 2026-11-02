
<?php
include "db.php";

$message = "";

if (isset($_POST["login"])) {

    $username = trim($_POST["username"]);
    $password = $_POST["password"];

    $sql = mysqli_prepare($conn, "SELECT password FROM users WHERE username = ?");
    mysqli_stmt_bind_param($sql, "s", $username);
    mysqli_stmt_execute($sql);
    mysqli_stmt_store_result($sql);

    if (mysqli_stmt_num_rows($sql) == 1) {

        mysqli_stmt_bind_result($sql, $hashed_password);
        mysqli_stmt_fetch($sql);

        if (password_verify($password, $hashed_password)) {
            $message = "Login successful! Welcome, " . htmlspecialchars($username) . "!";
        } else {
            $message = "Incorrect username or password.";
        }

    } else {
        $message = "Incorrect username or password.";
    }

    mysqli_stmt_close($sql);
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Sign In</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<div class="container">

    <h2>Welcome Back!</h2>
    <p class="subtitle">Sign in to your account</p>

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

        <button type="submit" name="login">
            Sign In
        </button>

    </form>

    <p class="link">
        Don't have an account?
        <a href="register.php">Register</a>
    </p>

</div>

</body>
</html>
