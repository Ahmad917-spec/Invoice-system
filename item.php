<?php

class Item {

    public $id;
    public $item_name;
    public $item_price;
    public $stock;
    public $company_name;

    function save($conn, $item_name, $item_price, $stock, $company_name) {
        $sql = "INSERT  into tbl_items (item_name, item_price, stock, company_name) values('$item_name', '$item_price', '$stock', '$company_name') ";

        $conn->query($sql);
    }

    function get_items($conn) {
        $sql = "select id, item_name, item_price, stock, company_name from tbl_items ORDER by id ";
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $item = new Item();
                $item->id = $row["id"];
                $item->item_name = $row["item_name"];
                $item->item_price = $row["item_price"];
                $item->stock = $row["stock"];
                $item->company_name = $row["company_name"];
                $items[] = $item;
            } // end while 
        } // end if statment 
        return $items;
    }

    function get_item_by_id($conn, $id) {
        $item = new Item();
        $sql = "SELECT id,item_name, item_price, stock, company_name FROM tbl_items WHERE id=?  ";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows > 0) {
            if ($row = $result->fetch_assoc()) {
                $item->id = $row["id"];
                $item->item_name = $row["item_name"];
                $item->item_price = $row["item_price"];
                $item->stock = $row["stock"];
                $item->company_name = $row["company_name"];
            }
        }
        return $item;
    }

// end function

    function update_item($conn, $id, $item_name, $item_price, $company_name, $stock) {
        $sql = "UPDATE tbl_items SET item_name='$item_name', item_price='$item_price', company_name='$company_name', stock='$stock' WHERE id=?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();
    }

    function update_item_qty($conn, $id, $stock) {
        $sql = "UPDATE tbl_items SET  stock='$stock' WHERE id='$id'";
        $stmt = $conn->prepare($sql);
        $stmt->execute();
    }

    function delete_item($conn, $id) {
        $sql = "DELETE from tbl_items WHERE id='$id'";
        $conn->query($sql);
    }
}
