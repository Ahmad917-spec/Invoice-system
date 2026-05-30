
<?php
include './item.php';
include './config.php';

$item = new Item();
if (isset($_POST['btn_save'])) {
    $item_name = $_POST['txt_item_name'];
    $item_price = $_POST['txt_item_price'];
    $company_name = $_POST['txt_brand_name'];
    $stock = $_POST['txt_stock'];
    $items = $item->save($conn, $item_name, $item_price, $stock, $company_name);
    header('location: item-form.php ');
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
        <link href="item-form.css" rel="stylesheet" />
    <title>
        Item Form
    </title>
    </head>
    <body>
<?php include './sidebar.php'; ?>
        <div id="main-content" >

            <h1> Item Addition Form </h1>

            <input type="hidden" id="txt_item_id">

            <table>
                <tr>
                    <td>
                        Item Name:
                    </td>
                    <td>
                        <input type ="text" id ="txt_item_name"/>
                    </td>
                </tr>
                <tr>
                    <td>
                        Item Price:
                    </td>
                    <td>
                        <input type ="text" id ="txt_item_price"/>
                    </td>
                </tr>
                <tr>
                    <td>
                        Brand Name:
                    </td>
                    <td>
                        <input type ="text" id ="txt_brand_name"/>
                    </td>
                </tr>
                <tr>
                    <td>
                        Stock:
                    </td>
                    <td>
                        <input type ="number" id ="txt_stock"/>
                    </td>
                </tr>
                <tr>

                    <td>
                        <button onclick="save_item_by_ajax()" type ="submit" id ="btn_save">Save</button>
                        <button onclick="update_item_by_ajax()" type ="button" style="display: none;" id="btn_update">Update</button>
                    </td>
                </tr>
            </table>
            <table id="tbl_items">
                <thead>
                    <tr>
                        <th> Sr </th> <th> Item Name </th> <th> Item Price </th> <th> Brand Name </th> <th> Stock </th>
                    </tr>
                </thead>
                <tbody>

                </tbody>
            </table>
        </div>
    </body>
    <script>
        window.onload = function ()
        {
            get_items_by_ajax();
        }
        function get_items_by_ajax()
        {
            fetch("item-services.php?action_id=1")
                    .then(response => response.text())
                    .then(data => {

                        var items = JSON.parse(data);
                        fillTable(items);

                    })
                    .catch(error => console.error("Error:", error));

        } // end function 
        function fillTable(items)
        {
            const table = document.getElementById("tbl_items");
            var tag_elements = table.getElementsByTagName("tbody");
            var tbody = tag_elements[0];
            tbody.innerHTML = "";
            var row_index = 0;
            for (var i = 0; i < items.length; i++)
            {
                var item = items[i];
                var row = tbody.insertRow(row_index);
                row_index++;
                var Sr = document.createElement("td");
                Sr.innerHTML = item.id;
                row.appendChild(Sr);
                var item_name = document.createElement("td");
                item_name.innerHTML = item.item_name;
                row.appendChild(item_name);
                var item_price = document.createElement("td");
                item_price.innerHTML = item.item_price;
                row.appendChild(item_price);
                var company_name = document.createElement("td");
                company_name.innerHTML = item.company_name;
                row.appendChild(company_name);
                var stock = document.createElement("td");
                stock.innerHTML = item.stock;
                row.appendChild(stock);
                var action = document.createElement("td");
                var update_button = '<button type=button onclick="get_item_by_id_by_ajax(' + item.id + ')">EDIT</button>';
                var delete_button = '<button type= button onclick="delete_item_by_ajax(' + item.id + ')">Delete</button>';
                action.innerHTML = update_button + " " + delete_button;
                row.appendChild(action);
            }
        } // end filltable function
        function save_item_by_ajax()
        {
            var item_name_element = document.getElementById("txt_item_name");
            var item_name = item_name_element.value;
            var item_price_element = document.getElementById("txt_item_price");
            var item_price = item_price_element.value;
            var brand_name_element = document.getElementById("txt_brand_name");
            var brand_name = brand_name_element.value;
            var stock_element = document.getElementById("txt_stock");
            var stock = stock_element.value;
            var service_url = "item-services.php";
            var parameters = "action_id=2&item_name=" + item_name + "&item_price=" + item_price + "&brand_name=" + brand_name + "&stock=" + stock;
            var url = service_url + "?" + parameters;
            fetch(url)
                    .then(response => response.text())
                    .then(data => {

                        item_name_element.value = "";
                        item_price_element.value = "";
                        brand_name_element.value = "";
                        stock_element.value = "";

                        get_items_by_ajax();
                    })
                    .catch(error => console.error("Error:", error));
        } // end save_function
        function get_item_by_id_by_ajax(id)
        {
            fetch("item-services.php?action_id=3&item_id=" + id)
                    .then(response => response.text())
                    .then(data => {
                        var item = JSON.parse(data);
                        var id_element = document.getElementById("txt_item_id");
                        id_element.value = item.id;
                        var item_name = document.getElementById("txt_item_name");
                        item_name.value = item.item_name;
                        var item_price = document.getElementById("txt_item_price");
                        item_price.value = item.item_price;
                        var brand_name = document.getElementById("txt_brand_name");
                        brand_name.value = item.company_name;
                        var stock = document.getElementById("txt_stock");
                        stock.value = item.stock;

                        var btn_save = document.getElementById('btn_save');
                        btn_save.setAttribute('style', 'display:none;');

                        var btn_update = document.getElementById('btn_update');
                        btn_update.setAttribute('style', 'display:block;');

                    })
                    .catch(error => console.error("Error:", error));
        } // end function
        function update_item_by_ajax()
        {
            var id = document.getElementById("txt_item_id").value;
            var item_name = document.getElementById("txt_item_name").value;
            var item_price = document.getElementById("txt_item_price").value;
            var company_name = document.getElementById("txt_brand_name").value;
            var stock = document.getElementById("txt_stock").value;
            fetch("item-services.php?action_id=4&item_id=" + id + "&item_name=" + item_name + "&item_price=" + item_price + "&company_name=" + company_name + "&stock=" + stock)
                    .then(response => response.text())
                    .then(data => {
                        var item_name = document.getElementById("txt_item_name");
                        item_name.value = "";
                        var item_price = document.getElementById("txt_item_price");
                        item_price.value = "";
                        var brand_name = document.getElementById("txt_brand_name");
                        brand_name.value = "";
                        var stock = document.getElementById("txt_stock");
                        stock.value = "";




                        var btn_save = document.getElementById("btn_save");
                        btn_save.setAttribute("style", 'display: block;');


                        var btn_update = document.getElementById("btn_update");
                        btn_update.setAttribute("style", 'display: none;');
                        get_items_by_ajax();
                    })
                    .catch(error => console.error("Error:", error));
        } // end update_function
        function delete_item_by_ajax(id)
        {
            fetch("item-services.php?action_id=5&item_id=" + id)
                    .then(res => res.json())
                    .then(data => {
                        get_items_by_ajax(); // refresh table
                    })
                    .catch(err => console.error(err));
        }

    </script>
</html>