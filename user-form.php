<?php
include './config.php';
include './tbl_user.php';

$tbl_User = new tbl_User();
if (isset($_POST['btn_save'])) {

    $full_name = $_POST['txt_full_name'];
    $user_name = $_POST['txt_user_name'];
    $password = $_POST['txt_password'];

    $usr = $tbl_User->save_users($conn, $full_name, $user_name, $password);
    header('location: index.php');
    exit();
}
?>


<html>
    <head> 
           <script src="libs/jquery.min.js"></script>       
        <link href="libs/bootstrap.min.css" rel="stylesheet" />    
        <script src="libs/bootstrap.min.js"></script>
        <link href="libs/jquery-ui.min.css" rel="stylesheet" />
        <script src="libs/jquery-ui.min.js"></script>
        <link href="user-form.css" rel="stylesheet" />
    </head>
    <body>
        <?php include './sidebar.php'; ?>
        <div id="main-content" >
                    <h1 style="text-align: center;"> User-Addition Form </h1>
        <input type="hidden" id="txt_user_id">

        <table >
            <tr>
                <td> FULL NAME: </td>
                <td> <input type="text" id="txt_full_name"/></td>
            </tr>
            <tr>
                <td> USER NAME: </td>
                <td><input type="text" id="txt_user_name"/></td>
            </tr>         
            <tr>
                <td> PASSWORD: </td>
                <td><input type="text" id="txt_password"/></td>
            </tr>
            <tr>
                <td>

                    <button onclick="save_users_by_ajax()" type="button" id="btn_save"> SAVE </button>
                    <button onclick="update_user_by_ajax()" id="btn_update" style="display: none;" type="button" >Update</button>
                </td>
            </tr>

        </table>  
        <table id="tbl-users">
            <thead>
                <tr>
                    <th> Id </th><th> Full Name </th> <th> User Name </th> <th> Password </th>
                </tr>
            </thead>
            <tbody>

            </tbody>
        </table>
        </div> 
    </body>   
</html>
<script>
    window.onload = function ()
    {
        get_users_by_ajax()
    }
    function get_users_by_ajax()
    {
        fetch("invoice-services.php?action_id=1")
                .then(response => response.text())
                .then(data => {
                    console.log(data);
                    var users = JSON.parse(data);
                    fillTable(users);
                })
                .catch(error => console.error("Error:", error));
    } // end function
    function save_users_by_ajax()
    {
        var name_element = document.getElementById("txt_full_name");
        var name = name_element.value;
        var user_name_element = document.getElementById("txt_user_name");
        var user_name = user_name_element.value;
        var password_element = document.getElementById("txt_password");
        var password = password_element.value;
        var service_url = "invoice-services.php";
        var parameters = "action_id=2&full_name=" + name + "&user_name=" + user_name + "&password=" + password;
        var url = service_url + "?" + parameters;
        fetch(url)
                .then(response => response.text())
                .then(data => {
                    name_element.value = "";
                    user_name_element.value = "";
                    password_element.value = "";
                    get_users_by_ajax();


                })

                .catch(error => console.error("Error:", error));

    }
    function fillTable(objects)
    {
        const table = document.getElementById("tbl-users");
        var tag_elements = table.getElementsByTagName("tbody");
        var tbody = tag_elements[0];
        tbody.innerHTML = "";
        var row_index = 0;
        for (var i = 0; i < objects.length; i++)
        {
            var user = objects[i];
            var row = tbody.insertRow(row_index);
            row_index++;
            var id = document.createElement("td");
            id.innerHTML = user.id;
            row.appendChild(id);
            var full_name = document.createElement("td");
            full_name.innerHTML = user.full_name;
            row.appendChild(full_name);
            var user_name = document.createElement("td");
            user_name.innerHTML = user.user_name;
            row.appendChild(user_name);
            var password = document.createElement("td");
            password.innerHTML = user.password;
            row.appendChild(password);
            var action = document.createElement("td");
            var update_button = '<button type=button onclick="get_user_by_id_by_ajax(' + user.id + ')">EDIT</button>';
            var delete_button = '<button type= button onclick="delete_user_by_ajax(' + user.id + ')">Delete</button>';
            action.innerHTML = update_button + " " + delete_button;
            row.appendChild(action);

        }
    } // end filltable function
    function get_user_by_id_by_ajax(id)
    {
        fetch("invoice-services.php?action_id=3&user_id=" + id)
                .then(response => response.text())
                .then(data => {
                    var user = JSON.parse(data);
                    var id_element = document.getElementById("txt_user_id");
                    id_element.value = user.id;
                    var full_name = document.getElementById("txt_full_name");
                    full_name.value = user.full_name;
                    var user_name = document.getElementById("txt_user_name");
                    user_name.value = user.user_name;
                    var password = document.getElementById("txt_password");
                    password.value = user.password;

                    var btn_save = document.getElementById('btn_save');
                    btn_save.setAttribute('style', 'display:none;');

                    var btn_update = document.getElementById('btn_update');
                    btn_update.setAttribute('style', 'display:block;');

                })
                .catch(error => console.error("Error:", error));
    } // end function 
    function update_user_by_ajax()
    {
        var id = document.getElementById("txt_user_id").value;
        var full_name = document.getElementById("txt_full_name").value;
        var user_name = document.getElementById("txt_user_name").value;
        var password = document.getElementById("txt_password").value;
        fetch("invoice-services.php?action_id=4&user_id=" + id + "&full_name=" + full_name + "&user_name=" + user_name + "&password=" + password)
                .then(response => response.text())
                .then(data => {
                    var full_name = document.getElementById("txt_full_name");
                    full_name.value = "";
                    var user_name = document.getElementById("txt_user_name");
                    user_name.value = "";
                    var password = document.getElementById("txt_password");
                    password.value = "";

                    var btn_save = document.getElementById("btn_save");
                    btn_save.setAttribute('style', 'display: block;');


                    var btn_update = document.getElementById("btn_update");
                    btn_update.setAttribute('style', 'display: none;');
                    get_users_by_ajax();


                })
                .catch(error => console.error("Error:", error));
    }
    function delete_user_by_ajax(id)
    {
        fetch("invoice-services.php?action_id=6&user_id=" + id)
                .then(res => res.json())
                .then(data => {
                    get_users_by_ajax(); // refresh table
                })
                .catch(err => console.error(err));
    }



</script>