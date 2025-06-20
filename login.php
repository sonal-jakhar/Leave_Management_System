<?php
session_start();
include "db.php";
$email = $_POST['email'];
$pass = $_POST['password'];

$sql = "SELECT * FROM users WHERE email='$email' AND password='$pass'";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $user = $result->fetch_assoc();
    $_SESSION['user'] = $user;

    if ($user['role'] == 'admin') {
        header("Location: admin_panel.php");
    } else {
        header("Location: employee_dashboard.php");
    }
} else {
    echo "Login failed. <a href='index.php'>Try again</a>";
}
?>
