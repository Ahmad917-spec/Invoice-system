<?php
include './item.php';
include './config.php';
if(isset($_GET['action_id']))
{
 $action_id=$_GET["action_id"];
 $item=new Item();
 switch($action_id)
 {
     case 1:
         $items=$item->get_items($conn);
         echo json_encode($items);
         break;
     case 2:
         $item_name=$_GET["item_name"];
         $item_price=$_GET["item_price"];
         $company_name=$_GET["brand_name"];
         $stock=$_GET["stock"];
         $item->save($conn, $item_name, $item_price, $stock, $company_name);
         break;
     case 3:
         $item_id=$_GET["item_id"];
         $items=$item->get_item_by_id($conn, $item_id);
         echo json_encode($items);
         break;
    
         case 4:
            $item_id=$_GET["item_id"];
            $item_name=$_GET["item_name"];
            $item_price=$_GET["item_price"];
            $company_name=$_GET["company_name"];
            $stock=$_GET["stock"];
            $item->update_item($conn, $item_id, $item_name, $item_price, $company_name, $stock);
            break;
     case 5:
         $item_id=$_GET["item_id"];
         $items=$item->delete_item($conn, $item_id);
         echo json_encode($items);
         break;
         
}
    
}

