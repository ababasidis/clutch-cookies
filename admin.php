<!doctype html>
<html>
<head>
  <?php include "includes/head.inc"; ?>
</head>
<body ng-app="myapp">
  <?php
    $ssn = new NewSession("student","student"); 
    $connect = new DatabaseResults(); 
    session_start();
    function __autoload($class_name) {
      //need to use filename path structure
      require_once "./classes/LIB_project1.php";
    }
  ?>
  <a class="admin_link" href="admin.php">Admin</a>
  <div class="container main">
    <?php 
      $ssn->getHeader(); 
      $connect->connectToDatabase();
    ?>
    <div class="container">
      <div class="row">
        <h2>Admin</h2>
        <?php $connect->showNotification(); ?>
        <div class="col-sm-8">
          <?php $connect->getAllItemsSelect(); ?>
        </div>
        <div class="col-sm-4">
          <h3><b/>Instructions<b/></h3>
          <p>Choose from the list of items to edit a product in the catalog or enter a new item.</p>
          <p class="margin-top-large">If no sale price, please enter 0.</p>
        </div>
      </div>
    </div>

  </div>
  <?php $ssn->getFooter(); ?>
  
</body>
</html>