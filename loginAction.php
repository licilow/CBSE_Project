<?php
include("pdo.php");

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["login"])) {

    $userID = $_POST["userID"];
    $password = $_POST["password"];


    $sql = "SELECT * FROM users WHERE userID='$userID' AND password='$password'";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();

    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    if (!empty($row)) {
        session_start();
        $_SESSION["userID"] = $userID;
        echo ("success");
        header("Location: index.php"); //Route to new page
        exit();
    } else {

        echo "<script>alert('Login failed, Please check UserID and Password'); window.location.href='login.php';</script>";
        exit();
    }
}

?>