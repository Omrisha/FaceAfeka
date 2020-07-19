<?php
    require_once "../functions.php";
    db_connect();
    
    $output = '';
    if ($_GET['query'] == "*"){
      $sql = "SELECT id, username, first_name, last_name, (SELECT COUNT(*) FROM friends WHERE friends.user_id = users.id AND friends.friend_id = {$_SESSION['user_id']}) AS is_friend
            FROM users 
            WHERE id != {$_SESSION['user_id']}
            HAVING is_friend = 0";
    } else {
      $sql = "SELECT id, username, first_name, last_name, (SELECT COUNT(*) FROM friends WHERE friends.user_id = users.id AND friends.friend_id = {$_SESSION['user_id']}) AS is_friend
              FROM users 
              WHERE username LIKE '%{$_GET['query']}%' AND id != {$_SESSION['user_id']}
              HAVING is_friend = 0";
    }
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
      $output .= '<ul>';
      while($row=$result->fetch_assoc())
      {
        $output .= "<li><a href='php/profile.php?username={$row['username']}'>{$row['first_name']} {$row['last_name']}</a></li>";
        $output .= "<li><a href='php/add-friend.php?uid={$row['id']}'>[add]</a></li>";
      }
      $output .= '</ul>';
    }
    else {
      $output = "Data not found";
    }
    
    echo $output;
?>