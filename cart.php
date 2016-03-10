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
        <div class="container">
          <?php
            $connect->showNotification();
          ?>
          <h2>Your Cart</h2>
          <div class="col-sm-9">
            <h3><b>Items</b></h3>
            <?
              $connect->addToCart();
              $connect->getCartItems(); 
              $connect->emptyCart();
            ?>

          </div>
          <div class="col-sm-3">
            <h3><b>Total<b/></h3>
            <?php $connect->getTotal(); ?>
          </div>
        </div>
      </div>

    </div>
  </div>
  <?php $ssn->getFooter(); ?>
  
</body>
</html>