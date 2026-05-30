<?php

Class Invoice_master {

    public $id;
    public $date;
    public $invoice_by;
    public $sales_tax;
    public $user_id;
    public $details = [];
    public $full_name;
    public $total;

    function save(mysqli $conn, $sales_tax, $user_id, $total) {
        $sql = "insert into tbl_invoice_master(sales_tax, user_id, total ) values( '$sales_tax', '$user_id', '$total') ";
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        return $conn->insert_id;
    }
    
    function get_invoices_by_date($conn,$date_from, $date_to) {
    $invoice_masters = [];

    $sql = "SELECT id, date,  sales_tax, user_id, total 
            FROM tbl_invoice_master 
            WHERE date BETWEEN ? AND ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $date_from, $date_to);
    $stmt->execute();

    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $invoice_master = new Invoice_master();
            $invoice_master->id = $row["id"];
            $invoice_master->date = $row["date"];
            $invoice_master->sales_tax = $row["sales_tax"];
            $invoice_master->user_id = $row["user_id"];
            $invoice_master->total = $row["total"];
            
            $invoice_masters[] = $invoice_master;
        }
    }

    return $invoice_masters;
}
function get_invoices_by_user($conn, $user_id, $date_from, $date_to)
{
    $invoice_masters = [];
    $sql= "SELECT id, date,sales_tax, total FROM tbl_invoice_master WHERE user_id='$user_id' AND (date BETWEEN ? AND ?) ";
    $stmt=$conn->prepare($sql);
    $stmt->bind_param("ss", $date_from, $date_to);
    $stmt->execute();
    $result=$stmt->get_result();
     if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $invoice_master = new Invoice_master();
            $invoice_master->id = $row["id"];
            $invoice_master->date = $row["date"];
            $invoice_master->sales_tax = $row["sales_tax"];
           
            $invoice_master->total = $row["total"];
            
            $invoice_masters[] = $invoice_master;
        }
    }

    return $invoice_masters;
    
    
    
} // end function 

    function get_invoice_masters($conn) {
        $invoice_masters = [];
        $sql = "Select id, date, sales_tax, user_id from tbl_invoice_master";
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $invoice_master = new Invoice_master();
                $invoice_master->id = $row["id"];
                $invoice_master->date = $row["date"];
                $invoice_master->sales_tax = $row["sales_tax"];
                $invoice_master->user_id = $row["user_id"];
                $invoice_masters[] = $invoice_master;
            }
        }
        return $invoice_masters;
    }

//end function

    function get_invoice_master_by_id($conn, int $id) {
        $invoice_master = new Invoice_master();
        $sql = "Select inm.id, inm.date, inm.sales_tax, inm.user_id,us.full_name"
                . " FROM tbl_invoice_master inm  "
                . " JOIN tbl_user us ON inm.user_id=us.id "
                . " where inm.id=$id";
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows > 0) {
            if ($row = $result->fetch_assoc()) {
                $invoice_master->id = $row["id"];
                $invoice_master->date = $row["date"];
                $invoice_master->sales_tax = $row["sales_tax"];
                $invoice_master->user_id = $row["user_id"];
                $invoice_master->full_name=$row["full_name"];
            }
        }
        $detail = new Invoice_detail();
        $details = $detail->get_invoice_details_by_invoice_id($conn, $id);
        $invoice_master->details = $details;
        return $invoice_master;
    }

//end function
}

//end class
