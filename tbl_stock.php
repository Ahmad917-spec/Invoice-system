<?php

class tbl_stock {
    public $id;
public $bill_no;
public $bill_date;
public $bill_total;
 function save($conn, $bill_no, $bill_date, $bill_total)
 {
     $sql="INSERT into tbl_stock (bill_no, bill_date, bill_total) values('$bill_no', '$bill_date', '$bill_total' )";
     $conn->query($sql);
     return $conn->insert_id;
     
             
 }
 function get_stocks($conn)
 {
     $tbl_stocks=[];
     $sql="SELECT id, bill_no, bill_date, bill_total from tbl_stock ORDER by id";
     $stmt=$conn->prepare($sql);
     $stmt->execute();
     $result=$stmt->get_result();
     if($result->num_rows>0 ){
         while($row=$result->fetch_assoc()){
             $tbl_stock= new Tbl_stock();
             $tbl_stock->id=$row["id"];
             $tbl_stock->bill_no=$row["bill_no"];
             $tbl_stock->bill_date=$row["bill_date"];
             $tbl_stock->bill_total=$row["bill_total"];
             $tbl_stocks[]=$tbl_stock;
             
         }
     }
     return $tbl_stocks;        
 } // end function
function get_stock_by_id($conn, $id)
{
    
$tbl_stock= new Tbl_stock();
     $sql="SELECT bill_no, bill_date, bill_total from tbl_stock where id =?";
     $stmt=$conn->prepare($sql);
     $stmt->bind_param("i", $id);
     $stmt->execute();
     $result=$stmt->get_result();
     if($result->num_Rows>0 ){
         if($row=$result->fetch_assoc()){
             $tbl_stock->id=$row["id"];
             $tbl_stock->bill_no=$row["bill_no"];
             $tbl_stock->bill_date=$row["bill_date"];
             $tbl_stock->bill_total=$row["bill_total"];
             
}
     } // 
     return $tbl_stock;
} // end function
 function update_stock($conn, $id, $bill_no, $bill_date, $bill_total)
 {
     $sql="update tbl_stock SET bill_no='$bill_no',bill_date='$bill_date', bill_total='$bill_total' where id='$id'";
     $conn->query($sql);
 }
}
