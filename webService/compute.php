<?php
header("Content-Type:application/json");
$jsonSales;
$salesData;

if (isset($_GET['salesData']) && $_GET['salesData'] != "") {

    $jsonSales = $_GET['salesData'];
    $salesData = json_decode($jsonSales, true);
    $total = array('monthlySales' => MonthlySales($salesData), 'annualSales' => AnnualSales($salesData), 'stockNumber' => StockNumber($salesData), 'lowStock' => LowStock($salesData), 'allMonthSales' => AllMonthSales($salesData));

    $json_response = json_encode($total);
    echo "$json_response";
} else {
    echo "error";
}

function MonthlySales($salesData)
{
    $total = 0;
    foreach ($salesData as $sale) {
        $date = new DateTime($sale['salesDate']);
        $month = $date->format('m');

        if ($month == date('m')) {
            $total = $total + ($sale['salesPrice'] - $sale['costPrice']) * $sale['salesQuantity'];
        }
    }

    return $total;
}

function AllMonthSales($salesData)
{
    $total = [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0];

    foreach ($salesData as $sale) {
        $date = new DateTime($sale['salesDate']);
        $month = $date->format('m');
        for ($i = 1; $i <= 12; $i++) {
            if ($month == $i) {
                $total[$i - 1] = $total[$i - 1] + ($sale['salesPrice'] - $sale['costPrice']) * $sale['salesQuantity'];
            }
        }
    }

    return $total;
}

function AnnualSales($salesData)
{
    $total = 0;
    foreach ($salesData as $sale) {
        $date = new DateTime($sale['salesDate']);
        $month = $date->format('Y');

        if ($month == date('Y')) {
            $total = $total + ($sale['salesPrice'] - $sale['costPrice']) * $sale['salesQuantity'];
        }
    }

    return $total;
}

function StockNumber($salesData)
{
    $total = 0;
    foreach ($salesData as $sale) {
        $total = $total + $sale['productQuantity'];

    }

    return $total;
}

function LowStock($salesData)
{
    $total = 0;

    foreach ($salesData as $sale) {
        if ($sale['productQuantity'] < $sale['minQuantity']) {
            $total++;
        }
    }

    return $total;
}
?>