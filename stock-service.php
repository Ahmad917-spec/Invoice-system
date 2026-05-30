<?php
include './config.php';
include './tbl_stock.php';
include './tbl_stock_detail.php';
include './item.php';
if (isset($_GET['action_id'])) {
    $action_id = $_GET["action_id"];
    $tbl_stock=new Tbl_stock();
    $item=new Item();
    switch($action_id)
    {
        case 1:
            $stocks=$tbl_stock->get_stocks($conn);
            echo json_encode($stocks);
            break;
        case 2:
            $bill_no=$_GET["bill_no"];
            $bill_date=$_GET["bill_date"];
            $bill_total=$_GET["bill_total"];
            $tbl_stock->save($conn, $bill_no, $bill_date, $bill_total);
            break;
        case 3:
               $items = $item->get_items($conn);
            echo json_encode($items);
            break;
        case 4:
            $items=$_GET["data"];
            $bill_no=$_GET["bill_no"];
            $bill_date=$_GET["bill_date"];
            $bill_total=$_GET["total"];
            $tbl_stock= new Tbl_stock();
            $Stock_id=$tbl_stock->save($conn, $bill_no, $bill_date, $bill_total);
            $detail= new tbl_Stock_detail();
            $Itemobj = new Item();
            foreach($items as $item)
            {
              $item_id=$item["id"];
              
              $price=$item["price"];
              $qty=$item["qty"];
              $stock=$qty;
              $detail->save_stock_detail($conn, $Stock_id, $item_id, $stock,  $price, $qty);
              $Item=$Itemobj->get_item_by_id($conn, $item_id);
              $new_stock=$Item->stock + $stock;
              $Itemobj->update_item_qty($conn, $item_id, $new_stock);
                      
            }
            echo $Stock_id;
            
            break;
    }
    
}
