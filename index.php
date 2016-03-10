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
  <div class="container main" ng-controller="CatalogController">
    <?php 
      $ssn->getHeader(); 
      $connect->connectToDatabase();
    ?>

    <div class="on_sale" ng-controller="dbCtrl">
      <h2>Combos</h2>
      <?php $connect->getItemsOnSale(); ?>
    </div>

    <div class="all_items" ng-controller="dbCtrl">
      <h2>All Items</h2>
      <div id="content">
        <div class="row">
          <?php $connect->getAllItems(); ?>
        </div>
      </div>
      <div id="pagingControls" class="right"></div>
    </div>

  </div>
  <?php $ssn->getFooter(); ?>
  
</body>
</html>