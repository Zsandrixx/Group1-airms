<?php
session_start();
include 'includes/db.php';

$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $username = $_POST['username'];
    $password = $_POST['password'];

    $sql = "SELECT * FROM ADMINS
            WHERE email = '$username' 
            AND pass = '$password'";

    $result = mysqli_query($conn, $sql);

    if (mysqli_num_rows($result) == 1) {

        $row = mysqli_fetch_assoc($result);

        $_SESSION['admin_id'] = $row['admin_id'];
        $_SESSION['name'] = $row['first_name'];

        header("Location: admin/dashboard.php");
        exit();

    } else {
        $error = "Invalid username or password";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" href="login.css">
    <title>AIR MS</title>
</head>

<body>
    
<div class="logo">
    <img src="images/AIRMS Logo.png">
</div>

<div class="login-box">
    <h1> LOGIN </h1>
    <h2> Welcome back! Please enter your credentials. </h2>

    <?php if ($error != ""): ?>
        <p class="error"><?php echo $error; ?></p>
    <?php endif; ?>

    <form method="POST">
        <input type="text" name="username" placeholder="Email" required>
        <input type="password" name="password" placeholder="Password" required>
        <button type="submit">Login</button>
    </form>

</div>

</body>
</html>