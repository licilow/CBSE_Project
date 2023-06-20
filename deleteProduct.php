<?php
require_once "pdo.php";
session_start();

if ( isset($_POST['deleteProduct']) && isset($_POST['product_id']) ) {
  try{
    $sql = "DELETE FROM inventory WHERE productID = :product_id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(array(':product_id' => $_POST['product_id']));
    $_SESSION['message'] = 'Product Item Deleted Successfully.';
    header("Location: inventoryTable.php");
    return;
  }
  catch (PDOException $e) {
    if ($e->getCode() === '23000') {
      echo '<script>alert("Sales record that reference this product is found. Please delete the sales record first.");</script>';
    } else {
      echo '<script>alert("An error occurred: ' . $e->getMessage() . '");</script>';
    }
  }
}

if (!isset($_GET['product_id']))  {
  // echo '<script> alert("Please login");</script>';
  header('refresh:0;url=inventoryTable.php');
  return;
}else{
// if (isset($_GET['product_id']))  {
  $stmt = $pdo->prepare("SELECT product_name, productID FROM inventory where productID = :product_id");
  $stmt->execute(array(":product_id" => $_GET['product_id']));
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
        <h5 class="modal-title text-white" id="exampleModalLabel">Delete Product Item</h5>
        <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close" onclick="javascript:window.location='inventoryTable.php'">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form action="deleteProduct.php" method="post">
        <div class="modal-body">
            <input type="hidden" name="delete_id" id="delete_id">

            <h5> Are you sure to delete <?= htmlentities($row['product_name']) ?> ?</h5>
        </div>
        <div class="modal-footer">
          <input type="hidden" name="product_id" value="<?= $row['productID'] ?>">
          <button type="submit" name="deleteProduct" class="btn btn-danger"> Yes </button>
          <button type="button" class="btn btn-secondary" data-dismiss="modal" onclick="javascript:window.location='inventoryTable.php'"> No </button>
          </div>
        </form>
      </div>
    </div>
  </div>

  <script>
      $(document).ready(function(){
          $("#deletemodal").modal('show');
      });
      // Add the following code to redirect after the alert box is closed
      $(document).on('hidden.bs.modal', '#deletemodal', function () {
          window.location.href = 'inventoryTable.php';
      });
  </script>