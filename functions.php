<?php
    session_start();
    //mysql://b1bee8946f0524:bdd81574@us-cdbr-east-02.cleardb.com/heroku_01d7bd2902d3fbf?reconnect=true
    function db_connect() {
        global $conn; // db connection variable
        //$db_server = "localhost";
        $db_server = "us-cdbr-east-02.cleardb.com";
        //$username = "root";
        $username = "b1bee8946f0524";
        //$password = "root";
        $password = "bdd81574";
        //$db_name = "faceafeka";
        $db_name = "heroku_01d7bd2902d3fbf";
        // create a connection
        $conn = new mysqli($db_server, $username, $password, $db_name);
        // check connection for errors
        if ($conn->connect_error) {
            die("Error: " . $conn->connect_error);
        }
    }

    function redirect_to($url) {
        header("Location: " . $url);
        exit();
    }

    function is_auth() {
        return isset($_SESSION['user_id']);
    }
    
    function check_auth() {
        if(!is_auth()) {
            redirect_to("/index.php?logged_in=false");
        }
    }
?>