<?php
class tbl_User {
    public $id;
    public $full_name;
    public $user_name;
    public $password;
    
   function get_user_by_login_name_parametarized($conn,$user_name,$password)
   {     
       $tbl_user= new tbl_User();
       $sql="Select id,full_name,user_name,password From tbl_user "
               .  "  Where user_name=? AND password =?";
               $stmt= $conn->prepare($sql);
               $stmt->bind_param("ss",$user_name,$password);
               $stmt->execute();
               $result=$stmt->get_result();
               if($result->num_rows>0){
                   
               
               if($row =$result->fetch_assoc()){
                   $tbl_user->id=$row["id"];
                    $tbl_user->full_name=$row["full_name"];
                     $tbl_user->user_name=$row["user_name"];
                      $tbl_user->password=$row["password"];
                            
               }
               } // end if functions
               return $tbl_user ;
   } // fuction of login 
   
    function save_users($conn,$full_name,$user_name,$password)
    {
    
            $sql="insert into tbl_user ( full_name,user_name,password)values('$full_name','$user_name','$password')";
            $conn->query($sql);
    }
    function get_tbl_users($conn)
    {
        $tbl_users = [];
         $sql="Select id,full_name,user_name,password From tbl_user";
         $stmt= $conn->prepare($sql);
         $stmt->execute();
          $result=$stmt->get_result();
               if($result->num_rows>0){
                    while($row =$result->fetch_assoc()){
                        $tbl_user=new tbl_User();
                   $tbl_user->id=$row["id"];
                    $tbl_user->full_name=$row["full_name"];
                     $tbl_user->user_name=$row["user_name"];
                      $tbl_user->password=$row["password"];
                      $tbl_users[]=$tbl_user;
                            
               }
        
    }
    return $tbl_users;
    }
    
    function get_tbl_user_by_id($id, $conn)
    {
        $tbl_user= new tbl_User();
        $sql= "SELECT id, full_name,user_name, password FROM tbl_user WHERE id=? ";
        $stmt=$conn->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result=$stmt->get_result();
        if($result->num_rows>0)
        {
            if ($row=$result->fetch_assoc())
            {
                $tbl_user->id=$row["id"];
                $tbl_user->full_name=$row["full_name"];
                $tbl_user->user_name=$row["user_name"];
                $tbl_user->password=$row["password"];
                
            }
        } // end if functions
        return $tbl_user ;
    } //  end function.
    function update_tbl_user($conn ,$id, $full_name, $user_name, $password)
    {
        $sql="UPDATE tbl_user SET full_name=?, user_name=?, password=? WHERE id=?" ;
        $stmt=$conn->prepare($sql);
        $stmt->bind_param("sssi",$full_name, $user_name, $password,$id);
        $stmt->execute();
                
    }
    function delete_tbl_user($conn,$id)
    {
        $sql="DELETE from tbl_user WHERE id=?";
      $stmt=$conn->prepare($sql);
      $stmt->bind_param("i", $id);
      $stmt->execute();

    }
}
