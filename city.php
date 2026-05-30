<?php
class City {
    public $id;
    public $city_name;
    public $zip_code;
    function save ($conn,$city_name, $zip_code)
    {
        $sql="insert into tbl_city (city_name,zip_code) values('$city_name', '$zip_code')";
        $stmt=$conn->prepare($sql);
        $stmt->execute();
                
    }
    function get_city ($conn)
    {
        $city=[];
        $sql="select id, city_name, zip_code from tbl_city ORDER by id  ";
        $stmt=$conn->prepare($sql);
        $stmt->execute();
        $result=$stmt->get_result();
        if($result->num_roes>0 ){
            while($row=$result->fetch_assoc()){
                $city= new City();
                $city->id=$row["id"];
                $city->city_name=$row["city_name"];
                $city->zip_code=$row["zip_code"];
                $citys[]=$city;
            }
    } // end if statment 
    return $citys ;
        }

}
