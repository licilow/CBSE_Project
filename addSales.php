<?php
require_once "pdo.php";
session_start();

// Get product names from the inventory table
$stmt = $pdo->query("SELECT productID, product_name FROM inventory");
$products = $stmt->fetchAll(PDO::FETCH_ASSOC);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validate form data
    $errors = array();
    // Validate sales ID
    $salesID = $_POST['salesID'];
    if (empty($salesID)) {
        $errors[] = "Sales ID is required.";
    }

    // Validate product ID
    $productID = $_POST['productID'];
    if (empty($productID)) {
        $errors[] = "Product ID is required.";
    }

    // Validate sales date
    $salesDate = $_POST['salesDate'];
    if (empty($salesDate)) {
        $errors[] = "Sales date is required.";
    }

    // Validate quantity
    $quantity = $_POST['quantity'];
    if (empty($quantity)) {
        $errors[] = "Quantity is required.";
    }

    // If there are no errors, insert the new row into the sales table
    if (empty($errors)) {
        $stmt = $pdo->prepare("INSERT INTO sales (salesID, productID, salesDate, quantity) VALUES (:salesID, :productID, :salesDate, :quantity)");
        $stmt->execute(
            array(
                ':salesID' => $salesID,
                ':productID' => $productID,
                ':salesDate' => $salesDate,
                ':quantity' => $quantity
            )
        );

        // Redirect to the sales table
        header("Location: salesTable.php");
        return;
    }
}
?>

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.4.1/dist/css/bootstrap.min.css"
    integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
<script src="https://code.jquery.com/jquery-3.4.1.slim.min.js"
    integrity="sha384-J6qa4849blE2+poT4WnyKhv5vZF5SrPo0iEjwBvKU7imGFAV0wwj1yYfoRSJoZ+n"
    crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js"
    integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo"
    crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.4.1/dist/js/bootstrap.min.js"
    integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6"
    crossorigin="anonymous"></script>

<div class="modal fade" id="addmodal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header bg-primary">
                <h5 class="modal-title text-white" id="exampleModalLabel">Add Sales</h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close"
                    onclick="javascript:window.location='salesTable.php'">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                <div class="form-group mx-3 mt-3">
                    <label for="salesID">Sales ID:</label>
                    <input type="text" class="form-control" id="salesID" name="salesID"
                        value="<?php echo isset($_POST['salesID']) ? $_POST['salesID'] : ''; ?>">
                </div>
                <div class="form-group mx-3 mt-3">
                    <label for="productID">Product:</label>
                    <select class="form-control" id="productID" name="productID">
                        <option value="">Select Product</option>
                        <?php
                        foreach ($products as $product) {
                            echo '<option value="' . $product['productID'] . '">' . $product['product_name'] . '</option>';
                        }
                        ?>
                    </select>
                </div>
                <div class="form-group mx-3 mt-3">
                    <label for="salesDate">Sales Date:</label>
                    <input type="date" class="form-control" id="salesDate" name="salesDate"
                        value="<?php echo isset($_POST['salesDate']) ? $_POST['salesDate'] : ''; ?>">
                </div>
                <div class="form-group mx-3 mt-3">
                    <label for="quantity">Quantity:</label>
                    <input type="number" class="form-control" id="quantity" name="quantity"
                        value="<?php echo isset($_POST['quantity']) ? $_POST['quantity'] : ''; ?>">
                </div>
                <button type="submit" class="btn btn-primary mx-3 mt-3">Add</button>
                <button type="button" class="btn btn-secondary mx-3 mt-3" data-dismiss="modal"
                    onclick="javascript:window.location='salesTable.php'"> Cancel </button>
            </form>
        </div>
    </div>
</div>

<script>
    $(document).ready(function () {
        $("#addmodal").modal('show');
    });
</script>