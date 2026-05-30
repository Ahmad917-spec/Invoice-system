<?php

session_start();
include './tbl_user.php';
include './customer.php';
include './item.php';
include './config.php';
include './invoice-detail.php';
include './invoice-master.php';
if (isset($_GET['action_id'])) {
    $action_id = $_GET["action_id"];
    $tbl_user = new tbl_User();
    $customer = new Customer();
    $item = new Item();
    $invoice_detail= new Invoice_detail();
    switch ($action_id) {
        case 1:
            $users = $tbl_user->get_tbl_users($conn);
            echo json_encode($users);
            break;
        case 2:
            $full_name = $_GET["full_name"];
            $user_name = $_GET["user_name"];
            $password = $_GET ["password"];
            $tbl_user->save_users($conn, $full_name, $user_name, $password);
            break;
        case 3:
            $user_id = $_GET["user_id"];
            $users = $tbl_user->get_tbl_user_by_id($user_id, $conn);
            echo json_encode($users);
            break;
        case 4:
            $user_id = $_GET["user_id"];
            $full_name = $_GET["full_name"];
            $user_name = $_GET["user_name"];
            $password = $_GET["password"];
            $tbl_user->update_tbl_user($conn, $user_id, $full_name, $user_name, $password);
            break;
        case 5:
            $items = $item->get_items($conn);
            echo json_encode($items);
            break;
        case 6:
            $user_id = $_GET["user_id"];
            $users = $tbl_user->delete_tbl_user($conn, $user_id);
            echo json_encode($users);
            break;
        case 7:
            if(!isset($_SESSION["user_id"]))
            {
                echo '-1';
                break;
            }
            $user_id = $_SESSION["user_id"];
            $items = $_GET['data'];
            $total= $_GET["total"];
            $invoice = new Invoice_master();
            $sales_tax=0;
            $date = time();
            $invoice_id = $invoice->save($conn, $sales_tax, $user_id, $total);
            $detail = new Invoice_detail();
            $ItemObj = new Item();
            foreach ($items as $item) {
                $item_id = $item['id'];
                $item_price = $item['price'];
                $item_quantity = $item['qty'];
                $detail->save($conn, $invoice_id, $item_id, $item_price, $item_quantity); 
                $Item = $ItemObj->get_item_by_id($conn, $item_id);
                
                $stock = $Item->stock - $item_quantity;
                $ItemObj->update_item_qty($conn, $item_id, $stock);
                
                
            }
            
            echo $invoice_id;
            break;
            case 8:
                $date_from = $_GET['date_from'];
                $date_to = $_GET['date_to'].' 23:59:59';
                
            $invoice = new Invoice_master();
                $invoices = $invoice->get_invoices_by_date($conn, $date_from, $date_to);
            echo json_encode($invoices);
            break;
        case 9: 
             $user_id=$_GET['user_id'];
             $date_from = $_GET['date_from'];
                $date_to = $_GET['date_to'].' 23:59:59';
                  $invoice = new Invoice_master();
                  $invoices=$invoice->get_invoices_by_user($conn, $user_id, $date_from, $date_to);
                  echo json_encode($invoices);
            break;
        case 10:
            $users=$tbl_user->get_tbl_users($conn);
            echo json_encode($users);
            break;
        case 11:
            $items_sale=$invoice_detail->get_total_item_sales_amount($conn);
            echo json_encode($items_sale);
            break;
            }
}



