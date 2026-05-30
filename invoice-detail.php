<?php

class Invoice_detail {

    public $id;
    public $invoice_id;
    public $item_id;
    public $item_price;
    public $item_quantity;
    public $item_name;
    public $total_sale_amount;

    function save($conn, $invoice_id, $item_id, $item_price, $item_quantity) {
        $sql = "insert into tbl_invoice_detail(invoice_id,item_id, item_price,item_quantity) values('$invoice_id', '$item_id', '$item_price', $item_quantity) ";
        $stmt = $conn->prepare($sql);
        $stmt->execute();
    }

    function get_invoice_details($conn) {
        $invoice_details = [];
        $sql = "Select id, invoice_id, item_id, "
                . " item_price, item_quantity  "
                . " from tbl_inovice_detail";
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $invoice_detail = new Invoice_detail();
                $invoice_detail->id = $row["id"];
                $invoice_detail->invoice_id = $row["invoice_id"];
                $invoice_detail->item_id = $row["item_id"];
                $invoice_detail->item_price = $row["item_price"];
                $invoice_detail->item_quantity = $row["item_quantity"];
                $invoice_details[] = $invoice_detail;
            }
        }
        return $invoice_details;
    }

// end function 

    function get_total_item_sales_amount($conn) {
        $invoice_details = [];
        $sql = "SELECT itm.item_name, ind.item_id,  SUM( ind.item_price * ind.item_quantity ) AS Amount "
                . " FROM  tbl_invoice_detail ind  "
                . "  JOIN tbl_items itm ON ind.item_id = itm.id "
                . "  GROUP BY  ind.item_id";
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $invoice_detail = new Invoice_detail();                
                $invoice_detail->item_id = $row["item_id"];
                $invoice_detail->item_name = $row["item_name"];
                $invoice_detail->total_sale_amount = $row["Amount"];
                $invoice_details[] = $invoice_detail;
            }
        }
        return $invoice_details;
    }

// end function     





    function get_invoice_details_by_invoice_id($conn, int $invoice_id) {
        $invoice_details = [];
        $sql = "Select ind.id, ind.invoice_id, ind.item_id, ind.item_price"
                . ", ind.item_quantity, itm.item_name "
                . "  from tbl_invoice_detail ind "
                . " JOIN tbl_items itm ON ind.item_id=itm.id "
                . " where invoice_id=$invoice_id";
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $invoice_detail = new Invoice_detail();
                $invoice_detail->id = $row["id"];
                $invoice_detail->invoice_id = $row["invoice_id"];
                $invoice_detail->item_id = $row["item_id"];
                $invoice_detail->item_price = $row["item_price"];
                $invoice_detail->item_quantity = $row["item_quantity"];
                $invoice_detail->item_name = $row['item_name'];
                $invoice_details[] = $invoice_detail;
            }
        }
        return $invoice_details;
    }

// end function
}
