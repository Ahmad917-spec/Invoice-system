<?php
session_start();
include './tbl_User.php';
include './config.php';
$tbl_User = new tbl_User();
if (isset($_POST["btn_login"])) {
    $login_name = $_POST["txt_login_name"];
    $password = $_POST["txt_password"];
    $usr = $tbl_User->get_user_by_login_name_parametarized($conn, $login_name, $password);
    if ($usr->user_name != null) {
        $_SESSION["login"] = true;
        $_SESSION["full_name"] = $usr->full_name;
        $_SESSION["student_id"] = $usr->id;
        $_SESSION["user_id"] = $usr->id;
        header('location: index.php');
        exit();
    }
}
?>
<html>
    <head>
        <title> USER LOGIN </title>
        <link href="libs/bootstrap.min.css" rel="stylesheet" />    
        <script src="libs/bootstrap.min.js"></script>
        <link href="style.css" rel="stylesheet" />
    </head>
    <body>
        <div id="container">
          <h1 Class="text-center lg-4">SYSTEM ACCESS</h1>
<h2 class="login-title">Invoice Login System</h2>
<form method="post">
            <table>
                 

                <tr>
                    <td> Login name: </td>
                    <td>
                        <input   type="text"  name="txt_login_name" />

                    </td>

                </tr>

                
                <tr>
                    <td> Password </td>
                    <td><input  type="password"  name="txt_password" />  </td>
                </tr>
                
                
                <tr>
                    <td><button type="submit"  name="btn_login" style="cursor:pointer;"> Login </button>  </td>
                </tr>
                
            </table>  
        </form>
        <p class="footer">Authorized personnel only</p>
        </div>       
    </body>
      

 
</html>


