<?php require_once "functions.php"; ?>
<!DOCTYPE html>
<html lang="en">
<head>
  <!-- Required meta tags -->
  <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

  <link rel="stylesheet" type="text/css" href="css/bootstrap.min.css">
  <link rel="stylesheet" type="text/css" href="css/style.css">
  <title>FaceAfeka</title>
</head>
<body>
  <!-- nav -->
  <nav class="navbar navbar-default">
    <div class="container-fluid">
      <div class="navbar-header">
        <a class="navbar-brand" href="index.php">FaceAfeka</a>
      </div>
      
      <div class="collapse navbar-collapse" id="navbarSupportedContent">
        <?php if(is_auth()): ?>
          <ul class="nav navbar-nav navbar-right">
          <li><a href="home.php">Home</a></li>
          <li><a href="profile.php?username=<?php echo $_SESSION['user_username']; ?>">Profile</a></li>
          <li><a href="php/logout.php">Logout</a></li>
          </ul>
        <?php endif; ?>
      </div>
    </div>
  </nav>
  <!-- ./nav -->