<?php
// include your DB connection and classes
require_once "config.php";
require_once "invoice-master.php";
require_once "invoice-detail.php";

// check invoice_id
if (!isset($_GET['invoice_id']) || !is_numeric($_GET['invoice_id'])) {
    die("Invalid Invoice ID");
}

$invoice_id = (int) $_GET['invoice_id'];

// create object and fetch data
$invoice_master_obj = new Invoice_master();
$invoice = $invoice_master_obj->get_invoice_master_by_id($conn, $invoice_id);

// simple check
if (!$invoice || !$invoice->id) {
    die("Invoice not found");
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>View Invoice</title>
    <link href="invoice.css" rel="stylesheet"/>
    <style>
        body { font-family: Arial; }
        table { border-collapse: collapse; width: 80%; margin-top: 20px; }
        table, th, td { border: 1px solid black; }
        th, td { padding: 10px; text-align: center; }
        .header { margin-bottom: 20px; }
    </style>
   
</head>
<body>
<div class="invoice-wrap">
<h2>Invoice Details</h2>

<div class="header">
    <p><strong>Invoice ID:</strong> <?php echo $invoice->id; ?></p>
    <p><strong>Date:</strong> <?php echo $invoice->date; ?></p>
    <p><strong>Cashier:</strong> <?php echo $invoice->full_name; ?></p>
    <p><strong>Sales Tax:</strong> <?php echo $invoice->sales_tax; ?>%</p>
    <p><strong>User ID:</strong> <?php echo $invoice->user_id; ?></p>
               
</div>

<h3>Invoice Items</h3>

<table>
    <tr>
        <th>Item ID</th>
        <th>Product Name</th>
        <th>Price</th>
        <th>Quantity</th>
        <th>Total</th>
    </tr>

    <?php
    $grand_total = 0;

    if (!empty($invoice->details)) {
        foreach ($invoice->details as $item) {
            $total = $item->item_price * $item->item_quantity;
            $grand_total += $total;
    ?>
        <tr>
            <td><?php echo $item->item_id; ?></td>
            <td><?php echo $item->item_name; ?></td>
            <td><?php echo $item->item_price; ?></td>
            <td><?php echo $item->item_quantity; ?></td>
            <td><?php echo $total; ?></td>
        </tr>
    <?php
        }
    }
    ?>

    <tr>
        <td colspan="4"><strong>Grand Total</strong></td>
        <td><strong><?php echo $grand_total; ?></strong></td>
    </tr>
</table>
</div>
</body>
<script>
    window.onload= function(){
         setInterval(update_Time, 1000);
    update_Time();
    // window.print();
    };
   // window.onafterprint=function(){
     // window.close();
    };
    function update_Time()
    {
        var now = new Date();
        var hours=now.getHours();
        var minutes= now.getMinutes();
        var seconds= now.getSeconds();
        if( hours<10) hours = "0" + hours;
        if(minutes<10) minutes = "0" + minutes;
        if(seconds<10) seconds = "0" + seconds;
        document.getElementById("live_time").innerHTML = 
                hours + ":" + minutes + ":" + seconds;
    }
    setInterval(update_Time, 1000);
    update_Time();

   
    </script>
</html>