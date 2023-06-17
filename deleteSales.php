<?php
require_once "pdo.php";
session_start();

if ( isset($_POST['deleteSales']) && isset($_POST['sales_id']) ) {
  $sql = "DELETE FROM sales WHERE salesID = :sales_id";
  $stmt = $pdo->prepare($sql);
  $stmt->execute(array(':sales_id' => $_POST['sales_id']));
  $_SESSION['message'] = 'Sales Record Deleted Successfully.';
  header("Location: salesTable.php");
  return;
}

if (!isset($_GET['sales_id']))  {
  echo '<script> alert("Please login");</script>';
  header('refresh:0;url=login.php');
  return;
}else{
  $stmt = $pdo->prepare("SELECT p.product_name, s.salesID, s.quantity FROM sales s INNER JOIN inventory p
                         ON s.productID = p.productID  
                         WHERE salesID = :sales_id");
  $stmt->execute(array(":sales_id" => $_GET['sales_id']));
  $row = $stmt->fetch(PDO::FETCH_ASSOC);
}
?>

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.4.1/dist/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
<script src="https://code.jquery.com/jquery-3.4.1.slim.min.js" integrity="sha384-J6qa4849blE2+poT4WnyKhv5vZF5SrPo0iEjwBvKU7imGFAV0wwj1yYfoRSJoZ+n" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.4.1/dist/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>

<div class="modal fade" id="deletemodal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header bg-primary">
        <h5 class="modal-title" id="exampleModalLabel">Delete Sales Record</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close" onclick="javascript:window.location='salesTable.php'">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form action="deleteSales.php" method="post">
        <div class="modal-body">
            <input type="hidden" name="delete_id" id="delete_id">

            <h5> Are you sure to delete <?= htmlentities($row['quantity']) ?> sales record of <?= htmlentities($row['product_name']) ?> ?</h5>
        </div>
        <div class="modal-footer">
          <input type="hidden" name="sales_id" value="<?= $row['salesID'] ?>">
          <button type="submit" name="deleteSales" class="btn btn-danger"> Yes </button>
          <button type="button" class="btn btn-secondary" data-dismiss="modal" onclick="javascript:window.location='salesTable.php'"> No </button>
          </div>
        </form>
      </div>
    </div>
  </div>

  <script>
      $(document).ready(function(){
          $("#deletemodal").modal('show');
      });
  </script>