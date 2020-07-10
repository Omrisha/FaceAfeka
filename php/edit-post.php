<?php 
    require_once "../functions.php";
    db_connect();
    $sql = "UPDATE posts SET is_private = ? WHERE id = ?";
    $statement = $conn->prepare($sql);
    $statement->bind_param('si', $_GET['value'], $_GET['id']);
    if ($statement->execute()) {
        redirect_to("/profile.php?username={$_SESSION['user_username']}");
    } else {
        echo "Error: " . $conn->error;
    }
    $conn->close();
?>