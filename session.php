<?php 

session_start();

$sessObj = new Session();

class Session {
    function getSessionID(){
        return $_SESSION['userID'];
    }
}
