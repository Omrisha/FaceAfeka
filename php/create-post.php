<?php
    require_once "../functions.php";
    db_connect();
    $sql = "INSERT INTO posts (content, pic1, pic2, pic3, pic4, pic5, pic6, user_id) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
    $statement = $conn->prepare($sql);
    $statement->bind_param('sssssssi', $_POST['content'], $_POST['img1'], $_POST['img2'], $_POST['img3'], $_POST['img4'], $_POST['img5'], $_POST['img6'], $_SESSION['user_id']);
    if ($statement->execute()) {
        echo "/home.php";
    } else {
        echo "Error: " . $conn->error;
    }
    $conn->close();
?>