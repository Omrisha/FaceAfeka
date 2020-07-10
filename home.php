<?php require_once "functions.php"; ?>
<?php include "header.php" ?>
<?php 
    check_auth();
    db_connect();
?>
<!-- main -->
  <main class="container">
    <!-- messages -->
    <?php if(isset($_GET['request_sent'])): ?>
      <div class="alert alert-success">
        <p>Friend request sent!</p>
      </div>
    <?php endif; ?>
    <!-- ./messages -->
    <div class="row">
      <div class="col-md-3">
        <!-- profile brief -->
        <div class="panel panel-default">
          <div class="panel-body">
            <?php 
              $sql = "SELECT * FROM users WHERE id = {$_SESSION['user_id']}";
              $result = $conn->query($sql);
              $user = $result->fetch_assoc();
            ?>
            <h4><?php echo $user['username']; ?></h4>
            <p><?php echo $user['status']; ?></p>
          </div>
        </div>
        <!-- ./profile brief -->

        <!-- friend requests -->
        <div class="panel panel-default">
          <div class="panel-body">
            <h4>friend requests</h4>
            <?php
                $sql = "SELECT * FROM friend_requests WHERE friend_id = {$_SESSION['user_id']}";
                $result = $conn->query($sql);

                if ($result->num_rows > 0) {
                    ?><ul><?php

                    while($f_request = $result->fetch_assoc()) {
                        ?><li><?php
                            
                        $u_sql = "SELECT * FROM users WHERE id = {$f_request['user_id']} LIMIT 1";
                        $u_result = $conn->query($u_sql);
                        $fr_user = $u_result->fetch_assoc();
                        
                        ?><a href="profile.php?username=<?php echo $fr_user['username']; ?>">
                            <?php echo $fr_user['username']; ?>
                        </a> 
                            
                        <a class="text-success" href="php/accept-request.php?uid=<?php echo $fr_user['id']; ?>">
                            [accept]
                        </a> 
                            
                        <a class="text-danger" href="php/remove-request.php?uid=<?php echo $fr_user['id']; ?>">
                            [decline]
                        </a>

                        </li><?php
                    }

                    ?></ul><?php
                } else {
                    ?><p class="text-center">No friend requests!</p><?php
                }
            ?>
          </div>
        </div>
        <!-- ./friend requests -->
      </div>
      <div class="col-md-6">
        <!-- post form -->
        <form method="post" action="php/create-post.php">
        <div class="input-group">
            <input class="form-control" type="text" name="content" placeholder="Make a post…">
            <input class="form-control" type="image" name="content" placeholder="Choose a photo…">
            <span class="input-group-btn">
            <button class="btn btn-success" type="submit" name="post">Post</button>
            </span>
        </div>
        </form><hr>
        <!-- ./post form -->

        <!-- feed -->
        <div>
          <!-- post -->
          <?php 
              $sql = "SELECT id, content, user_id, created_at, is_private, (SELECT COUNT(*) FROM friends WHERE friends.user_id = posts.user_id AND friends.friend_id = {$_SESSION['user_id']}) AS is_friend FROM posts WHERE is_private = false HAVING is_friend > 0 ORDER BY created_at DESC";
              $result = $conn->query($sql);
              if ($result->num_rows > 0) {
                  while($post = $result->fetch_assoc()) {
                    $private_string = $post['is_private'] ? "private" : "public";
          ?>
                      <div class="panel panel-default">
                      <div class="panel-body">
                          <p><?php echo $post['content']; ?></p>
                      </div>
                      <div class="panel-footer">
                        <?php
                          $sql = "SELECT username FROM users WHERE id = ? LIMIT 1";
                          $statement = $conn->prepare($sql);
                          $statement->bind_param('i', $post['user_id']);
                          $statement->execute();
                          $statement->store_result();
                          $statement->bind_result($post_author);
                          $statement->fetch();
                        ?>
                          <span>posted <?php echo $post['created_at']; ?> by <a href="profile.php?username=<?php echo $post_author; ?>"><?php echo $post_author; ?></a></span> 
                          <span class="pull-right">[<?php echo $private_string; ?>]</span>
                      </div>
                      </div>
          <?php
                  }
              } else {
          ?>
                  <p class="text-center">No posts yet!</p>
          <?php
              }
          ?>
          <!-- ./post -->
        </div>
        <!-- ./feed -->
      </div>
      <div class="col-md-3">
        <!-- friends -->
        <div class="panel panel-default">
          <div class="panel-body">
            <h4>Friends</h4>
            <?php 
              $sql = "SELECT * FROM friends WHERE user_id = {$_SESSION['user_id']}";
              $result = $conn->query($sql);

              if ($result->num_rows > 0) { ?>
                <ul>
                  <?php
                    while($friend = $result->fetch_assoc()) { ?>
                      <li>
                        <?php
                          $u_sql = "SELECT * FROM users WHERE id = {$friend['friend_id']} LIMIT 1";
                          $u_result = $conn->query($u_sql);
                          $fc_user = $u_result->fetch_assoc();
                        ?>
                        <a href="profile.php?username=<?php echo $fc_user['username']; ?>"><?php echo $fc_user['username']; ?></a> 
                        <a class="text-danger" href="php/remove-friend.php?uid=<?php echo $fc_user['id']; ?>">[unfriend]</a>
                      </li>
                  <?php } ?>
                </ul>
              <?php } else { ?>
                <p class="text-center">No friend requests!</p>
              <?php } ?>
          </div>
        </div>
        <!-- ./friends -->
      <!-- add friend -->
        <div class="panel panel-default">
          <div class="panel-body">
            <h4>add friend</h4>
            <form >
              <div class="input-group">
                <input type="text" name="search_text" id="search_text" placeholder="Search" >
              </div>
            </form>
            <br />
            <div id="result"></div>
          </div>
        </div>
        <!-- ./add friend -->
      </div>
    </div>
  </main>
  <!-- ./main -->
  <?php include "footer.php" ?>