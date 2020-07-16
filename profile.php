<?php require_once "functions.php"; ?>
<?php require "header.php" ?>

<?php
  check_auth();
  db_connect();

  $sql = "SELECT id, first_name, last_name, username, status, profile_image_url, location FROM users WHERE username = ?";
  $statement = $conn->prepare($sql);
  $statement->bind_param('s', $_GET['username']);
  $statement->execute();
  $statement->store_result();
  $statement->bind_result($id, $firstName, $lastName, $username, $status, $profile_image_url, $location);
  $statement->fetch();
?>

  <!-- main -->
  <main class="container-fluid" style="padding: 10px;">
    <div class="row mb-4">
      <div class="col-md-3">
        <?php if ($_SESSION['user_id'] == $id) {?> 
        <!-- edit profile -->
        <div class="card card-default">
          <div class="card-body">
            <h4>Edit profile</h4>
            <form method="post" action="php/edit-profile.php">
              <div class="form-group">
                <input class="form-control" type="text" name="status" placeholder="Status" value="">
              </div>

              <div class="form-group">
                <input class="form-control" type="text" name="location" placeholder="Location" value="">
              </div>

              <div class="form-group">
                <input class="btn btn-primary" type="submit" name="update_profile" value="Save">
              </div>
            </form>
          </div>
        </div>
        <?php }?>
        <!-- ./edit profile -->
      </div>
      <div class="col-md-6">
        <!-- user profile -->
        <div class="media">
          <div class="media-left">
            <img src="img/my_avatar.png" class="media-object" style="width: 128px; height: 128px;">
          </div>
          <div class="media-body">
            <h2 class="media-heading"><?php echo $firstName." ".$lastName; ?></h2>
            <p>Status: <?php echo $status; ?>, Location: <?php echo $location; ?></p>
          </div>
        </div>
        <!-- user profile -->

        <hr>

        <!-- timeline -->
        <div>
          <!-- post -->
          <?php 
            if ($_SESSION['user_id'] != $id) {
              $posts_sql = "SELECT * FROM posts WHERE user_id = {$id} AND is_private = 0 ORDER BY created_at DESC";
            } else 
            {
              $posts_sql = "SELECT * FROM posts WHERE user_id = {$id} ORDER BY created_at DESC";
            }
            
            $result = $conn->query($posts_sql);
            if ($result->num_rows > 0) {
              while($post = $result->fetch_assoc()) {
                $private_string = $post['is_private'] ? "private" : "public";
                $private_value = $post['is_private'] ? 0 : 1;
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
                    </div>
                    <div class="card-footer">
                      <?php
                        $sql = "SELECT first_name, last_name FROM users WHERE id = ? LIMIT 1";
                        $statement = $conn->prepare($sql);
                        $statement->bind_param('i', $post['user_id']);
                        $statement->execute();
                        $statement->store_result();
                        $statement->bind_result($post_author_first, $post_author_last);
                        $statement->fetch();
                      ?>
  
                      <span>posted <?php echo $post['created_at']; ?></span>
                      <?php if(is_auth() && $_SESSION['user_id'] == $id): ?>
                        <span class="pull-right" style="padding-left: 5px;"><a class="text-danger" href="php/delete-post.php?id=<?php echo $post['id']; ?>">[delete]</a></span>
                        <span class="pull-right"><a class="text-danger" href="php/edit-post.php?id=<?php echo $post['id']; ?>&value=<?php echo $private_value; ?>">[<?php echo $private_string; ?>]</a></span>
                      <?php endif; ?>
                      <?php if(is_auth() && $_SESSION['user_id'] != $id): ?>
                        <span class="pull-right">[<?php echo $private_string; ?>]</span>
                      <?php endif; ?>
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
        <!-- ./timeline -->
      </div>
      <div class="col-md-3">
        <!-- friends -->
      <div class="card card-default">
        <div class="card-body">
          <h4>Friends</h4>
          <?php 
            $sql = "SELECT * FROM friends WHERE user_id = {$id}";
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
                      <?php if ($_SESSION['user_id'] == $id) {?> 
                      <a class="text-danger" href="php/remove-friend.php?uid=<?php echo $fc_user['id']; ?>">[unfriend]</a>
                      <?php }?>
                    </li>
                <?php } ?>
              </ul>
            <?php } else { ?>
              <p class="text-center">No friend requests!</p>
            <?php } ?>
        </div>
      </div>
      <!-- ./friends -->
      </div>
    </div>
  </main>
  <!-- ./main -->
  <?php include "footer.php" ?>