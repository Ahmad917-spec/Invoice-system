<?php

class tbl_stock_detail {
public $id;
public $stock_id;
public $item_id;
public $stock;
public $price;
public $qty;

function save_stock_detail($conn,$stock_id, $item_id,$stock,$price, $qty)
{
       $sql = "INSERT INTO tbl_stock_detail (stock_id, item_id, stock, price, qty)
            VALUES ('$stock_id', '$item_id', '$stock', '$price', '$qty')";
       $conn->query($sql);
}
function get_stock_details($conn)
{
    $tbl_stock_details = [];

    $sql = "SELECT stock_id, item_id, stock, price, qty FROM tbl_stock_detail ORDER BY id";
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $tbl_stock_detail = new tbl_Stock_detail();
            $tbl_stock_detail->id = $row["id"];
            $tbl_stock_detail->stock_id = $row["stock_id"];
            $tbl_stock_detail->item_id  = $row["item_id"];
            $tbl_stock_detail->stock    = $row["stock"];
            $tbl_stock_detail->price    = $row["price"];
            $tbl_stock_detail->qty      = $row["qty"];

            $tbl_stock_details[] = $tbl_stock_detail;
        }
    }

    return $tbl_stock_details;
}

}
