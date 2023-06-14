<?php

include("pdo.php");

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["submit"])) {

    $username = $_POST["userID"];
    $password = $_POST["password"];


    $sql = "SELECT * FROM users WHERE userID='$username' AND password='$password'";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();

    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    if (!empty($row)) {
        session_start();
        $_SESSION["username"] = $username;
        $_SESSION['user_id'] = $row['user_id'];
        echo ("success");
        header("Location: index.html"); //Route to new page
        exit();
    } else {

        echo "<script>alert('Login failed, Please check Username and Password'); window.location.href='login.html';</script>";
        exit();
    }
}

?>