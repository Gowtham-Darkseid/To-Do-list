<?php
session_start();
$conn = new mysqli("localhost", "root", "", "lab");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    $stmt = $conn->prepare("SELECT password FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();
    $stmt->bind_result($db_password);
    $stmt->fetch();

    if ($stmt->num_rows > 0) {
        if (password_verify($password, $db_password)) {  
            $_SESSION['email'] = $email;
            echo "<script>alert('Login successful!'); window.location='todo.php';</script>";
        } else {
            echo "<script>alert('Invalid email or password!'); window.location='login.html';</script>";
        }
    } else {
        echo "<script>alert('Invalid email or password!'); window.location='login.html';</script>";
    }

    $stmt->close();
}
$conn->close();
?>

