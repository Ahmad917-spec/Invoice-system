<?php

class Customer {
   public $id;
   public $name;
   public $phone_no;
   public $street_address;
   public $company_name;
   public $city_id;
   function save ($conn,$name,$phone_no,$street_address,$company_name)
   {
       $sql="insert into tbl_customer(name,phone_no,street_address,company_name)values('$name','$phone_no'; '$street_address', '$company_name' ) ";
       $stmt=$conn->prepare($sql);
       $stmt->execute();
   }
   function get_customers($conn)
   {
       $customers=[];
       $sql="select id,name,phone_no,street_address,company_name city_id from tbl_city ORDER by id";
       $stmt=$conn->prepare($sql);
       $stmt->execute();
       $result=$stmt->get_result();
        if($result->num_roes>0 ){
            while($row=$result->fetch_assoc()){
                $customer= new Customer();
                $customer->id=$row["id"];
                 $customer->name=$row["name"];
                  $customer->phone_no=$row["phone_no"];
                   $customer->street_address=$row["street_address"];
                    $customer->company_name=$row["company_name"];
                     $customer->city_id=$row["city_id"];
                     $customers[]=$customer;
            }
   } // end if statement
   return $customers;
   }
}
