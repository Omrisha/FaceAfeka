<?php require_once "functions.php"; ?>
<?php include "header.php" ?>
<?php 
    check_auth();
    db_connect();
?>
<!-- main -->
  <main class="container-fluid " style="padding: 10px;">
    <!-- messages -->
    <?php if(isset($_GET['request_sent'])): ?>
      <div class="alert alert-success">
        <p>Friend request sent!</p>
      </div>
    <?php endif; ?>
    <!-- ./messages -->
    <div class="row mb-4 ">
      <div class="col-md-3">
        <!-- profile brief -->
        <div class="card card-default">
          <div class="card-body">
            <?php 
              $sql = "SELECT * FROM users WHERE id = {$_SESSION['user_id']}";
              $result = $conn->query($sql);
              $user = $result->fetch_assoc();
            ?>
            <h4><?php echo $user['first_name']." ".$user['last_name']; ?></h4>
            <p><?php echo $user['status']; ?></p>
          </div>
        </div>
        <!-- ./profile brief -->

        <!-- friend requests -->
        <div class="card card-default">
          <div class="card-body">
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
                            <?php echo $fr_user['first_name']." ".$fr_user['last_name']; ?>
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
        Share: <a
        <!-- post form -->
        <div class="form-control">
        <!-- <form id="uploadPost" method="post"> -->
        <div class="input-group">
            <input class="form-control" type="text" name="content" id="content" placeholder="Make a post…">            
        </div>
        <div class="custom-file">
          <input type="file" class="custom-file-input" id="file" name="file" multiple>
          <label class="custom-file-label" for="file" id="imgFile">Choose file</label>
        </div>
        <div id="photos" class="form-group row text-center text-lg-left"></div>
        <button class="btn btn-success" type="submit" name="post" id="post" >Post</button>
        </div><hr/>
        <!-- ./post form -->

        <!-- feed -->
        <div>
          <!-- post -->
          <?php 
              $sql = "SELECT * FROM posts WHERE is_private = false ORDER BY created_at DESC";
              //$sql = "SELECT id, content, user_id, created_at, is_private, (SELECT COUNT(*) FROM friends WHERE friends.user_id = posts.user_id AND friends.friend_id = {$_SESSION['user_id']}) AS is_friend FROM posts WHERE is_private = false HAVING is_friend > 0 ORDER BY created_at DESC";
              $result = $conn->query($sql);
              if ($result->num_rows > 0) {
                  while($post = $result->fetch_assoc()) {
                    $private_string = $post['is_private'] ? "private" : "public";
          ?>
                      <div class="card card-default">
                      <div class="card-body">
                      <div id="photos" class="form-group row text-center text-lg-left">
                      <?php if ($post['pic1'] != "") {?> 
                        <div class="col-lg-3 col-md-4 col-6">
                          <a data-fancybox data-caption="Caption for single image" href="<?php echo $post['pic1']; ?>"><img src="<?php echo $post['pic1']; ?>" class="img-thumbnail"></a>
                        </div>
                        <?php }?>
                        <?php if ($post['pic2'] != "") {?> 
                        <div class="col-lg-3 col-md-4 col-6">
                        <a data-fancybox data-caption="Caption for single image" href="<?php echo $post['pic2']; ?>"><img src="<?php echo $post['pic2']; ?>" class="img-thumbnail"></a>
                        </div>
                        <?php }?>
                        <?php if ($post['pic3'] != "") {?> 
                        <div class="col-lg-3 col-md-4 col-6">
                        <a data-fancybox data-caption="Caption for single image" href="<?php echo $post['pic3']; ?>"><img src="<?php echo $post['pic3']; ?>" class="img-thumbnail"></a>
                        </div>
                        <?php }?>
                        <?php if ($post['pic4'] != "") {?> 
                        <div class="col-lg-3 col-md-4 col-6">
                        <a data-fancybox data-caption="Caption for single image" href="<?php echo $post['pic4']; ?>"><img src="<?php echo $post['pic4']; ?>" class="img-thumbnail"></a>
                        </div>
                        <?php }?>
                        <?php if ($post['pic5'] != "") {?> 
                        <div class="col-lg-3 col-md-4 col-6">
                        <a data-fancybox data-caption="Caption for single image" href="<?php echo $post['pic5']; ?>"><img src="<?php echo $post['pic5']; ?>" class="img-thumbnail"></a>
                        </div>
                        <?php }?>
                        <?php if ($post['pic6'] != "") {?> 
                        <div class="col-lg-3 col-md-4 col-6">
                        <a data-fancybox data-caption="Caption for single image" href="<?php echo $post['pic6']; ?>"><img src="<?php echo $post['pic6']; ?>" class="img-thumbnail"></a>
                        </div>
                        <?php }?>
                        </div>
                          <p><?php echo $post['content']; ?></p>
                          <div class="input-group">
                          <input class="form-control" type="text" name="<?php echo $post['id']; ?>" id="commentcontent<?php echo $post['id']; ?>" placeholder="Make a comment...">            
                          <button class="btn btn-success" type="submit" name="comment" id="comment" onclick="addComment(<?php echo $post['id']; ?>)">Send</button>
                          </div>
                          <hr>
                          <p>Comments:</p>
                          <?php
                              $sql1 = "SELECT * FROM comments WHERE post_id = ".$post['id'];
                              $result1 = $conn->query($sql1);
                              if ($result1->num_rows > 0) {
                                  while($comment = $result1->fetch_assoc()) {
                                    ?>
                                    <div id="comments" class="card-footer">
                                    <span><?php echo $comment['comment']; ?>, By: <?php echo $comment['comment_author']; ?></span>
                                    </div>
                                    <?php
                                  }
                              }
                          ?>
                      </div>
                      <div class="card-footer">
                        <?php
                          $sql = "SELECT username, first_name, last_name FROM users WHERE id = ? LIMIT 1";
                          $statement = $conn->prepare($sql);
                          $statement->bind_param('i', $post['user_id']);
                          $statement->execute();
                          $statement->store_result();
                          $statement->bind_result($post_author, $post_author_first, $post_author_last);
                          $statement->fetch();
                        ?>
                          <span>posted <?php echo $post['created_at']; ?> by <a href="profile.php?username=<?php echo $post_author; ?>"><?php echo $post_author_first." ".$post_author_last; ?></a></span> 
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
        <div class="card card-default">
          <div class="card-body">
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
                        <a href="profile.php?username=<?php echo $fc_user['username']; ?>"><?php echo $fc_user['first_name']." ".$fc_user['last_name']; ?></a> 
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
      <div class="card card-default">
        <div class="card-body">
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
  </main>
  <!-- ./main -->
  <?php include "footer.php" ?>