<?php
    require_once "../functions.php";
    db_connect();
    $sql = "INSERT INTO comments (comment, comment_author, post_id) VALUES (?, ?, ?)";
    $statement = $conn->prepare($sql);
    $statement->bind_param('ssi', $_POST['content'], $_SESSION['user_username'], $_GET['postid']);
    if ($statement->execute()) {
        echo "/home.php";
    } else {
        echo "Error: " . $conn->error;
    }
    $conn->close();
?>