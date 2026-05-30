<?php
include './config.php';
include './item.php';
include './tbl_user.php';
?> 
<html>

    <head>         
        <script src="libs/jquery.min.js"></script>       
        <link href="libs/bootstrap.min.css" rel="stylesheet" />    
        <script src="libs/bootstrap.min.js"></script>
        <link href="libs/jquery-ui.min.css" rel="stylesheet" />
        <link href="reports.css" rel="stylesheet "/> 
        <script src="libs/jquery-ui.min.js"></script>
        <style>
            .logout{

                font-size: 19px;
                text-align: center;
                height: 25px;
                width: 73px;
                float:left;
                color:white;
                text-decoration: none;
                background-color:red;
                margin-top:20px;
                border-radius:5px;
                margin-right:10px;
            }
            th{
                padding:5px;
            }
            .add-user {
                display: inline-block;
                padding: 6px 12px;
                background-color: green;
                color: white;
                text-decoration: none;
                border-radius: 4px;
            }

            .add-user:hover {
                background-color: darkgreen;
            }
        </style>
        <script>
            $(document).ready(function () {
                get_users();
                get_items();
                
            });
            function get_items()
            {
                var parameters = {action_id: 5};
                $.ajax({
                    type: "GET",
                    url: "invoice-services.php",
                    data: parameters,
                    contentType: "application/json; charset=utf-8",
                    dataType: "json",
                    success: function (response)
                    {
                        var items = response;
                        show_items(items);
                    },
                    error: function (jqXHR, exception) {
                        console.log(jqXHR);
                        console.log(jqXHR.responseText);
                    }

                });

            } // end function 
            var selected_item_id = -1;
            function show_items(items)
            {
                $('#txt_item_name').autocomplete({
                    minLength: 0,
                    dataType: "json",
                    source: $.map(items, function (value, key)
                    {
                        return{
                            label: value.item_name,
                            item_id: value.id,
                            item_name: value.item_name
                        };
                    }),
                    select: function (event, ui) {
                        $('#txt_item_name').val(ui.item.item_name);
                        selected_item_id = (ui.item.item_id);
                    }

                });
            } // end function
            function show_report_by_item_name()
            {
                var parameters = {action_id: 11};
                $.ajax({
                    type: "GET",
                    url: "invoice-services.php",
                    data: parameters,
                    contentType: "application/json; charset=utf-8",
                    dataType: "json",
                    success: function (response)
                    {
                        var items_sale = response;
                        var table = document.getElementById("tbl_reports");
                        table.innerHTML = '';
                         var header_row = table.insertRow();
                         
                         var h1 = header_row.insertCell(0);
                          var h2 = header_row.insertCell(1);

                          h1.innerHTML = "<b>Item Name</b>";
                          h2.innerHTML = "<b> Amount </b>";
                        if (items_sale.length > 0)
                        {

                        }
                        for (var i = 0; i < items_sale.length; i++)
                        {
                            var row = table.insertRow();
                           
                            var item_name = row.insertCell(0);
                            var Amount = row.insertCell(1);

                            item_name.innerHTML = items_sale[i].item_name;
                            Amount.innerHTML = items_sale[i].total_sale_amount;

                        }
                        //fillTable(items_sale);
                      
                    },
                    error: function (jqXHR, exception) {
                        console.log(jqXHR);
                        console.log(jqXHR.responseText);
                        alert("failed");
                    }

                });


            } // end function
            function show_report_by_user_name()
            {
                var user_id = selected_user_id;
                var date_from = $('#txt_date_from').val();
                var date_to = $('#txt_date_to').val();
                var parameters = {
                    action_id: 9,
                    user_id: user_id,
                    date_from: date_from,
                    date_to: date_to

                };
                $.ajax({
                    type: "GET",
                    url: "invoice-services.php",
                    data: parameters,
                    contentType: "application/json; charset=utf-8",
                    dataType: "json",
                    success: function (response) {
                        var invoices = response;
                        fillTable(invoices);
                    },
                    error: function (jqXHR, exception) {
                        console.log(jqXHR);
                        console.log(jqXHR.responseText);
                    }
                });

            } // end function 
            function get_users()
            {
                var parameters = {action_id: 10};
                $.ajax({
                    type: "GET",
                    url: "invoice-services.php",
                    data: parameters,
                    contentType: "apllication/json; charset=utf-8",
                    dataType: "json",
                    success: function (response)
                    {
                        var users = response;
                        show_users(users);
                    },
                    error: function (jqXHR, exception) {
                        console.log(jqXHR);
                        console.log(jqXHR.responseText);
                    }
                });
            } // end function 
            var selected_user_id = -1;
            function show_users(users)
            {
                $('#txt_user_name').autocomplete({
                    minLength: 0,
                    dataType: "json",
                    source: $.map(users, function (value, key)
                    {
                        return{
                            label: value.full_name,
                            user_id: value.id,
                            full_name: value.full_name


                        };
                    }),
                    select: function (event, ui) {
                        $('#txt_user_name').val(ui.item.full_name);
                        selected_user_id = ui.item.user_id;



                    }
                });

            } // end function

            function show_report()
            {
                var date_from = $('#txt_date_from').val();
                var date_to = $('#txt_date_to').val();




                var parameters = {
                    action_id: 8,
                    date_from: date_from,
                    date_to: date_to

                };

                $.ajax({
                    type: "GET",
                    url: "invoice-services.php",
                    data: parameters,
                    contentType: "application/json; charset=utf-8",
                    dataType: "json",
                    success: function (response) {
                        console.log(response);
                        var invoices = response;
                        fillTable(invoices);

                    },

                    error: function (jqXHR, exception) {
                        console.log(jqXHR);
                        console.log(jqXHR.responseText);
                    }
                });
            }//end function
            function fillTable(invoices)
            {
                const table = document.getElementById("tbl_reports");
                var tag_elements = table.getElementsByTagName("tbody");
                var tbody = tag_elements[0];
                tbody.innerHTML = "";
                var row_index = 0;
                var sr = 1;
                var total_cash = 0.0;
                for (var i = 0; i < invoices.length; i++)
                {
                    var invoice = invoices[i];
                    var row = tbody.insertRow(row_index);
                    row_index++;
                    var e_sr = document.createElement('td');
                    e_sr.innerHTML = sr;
                    row.appendChild(e_sr);
                    sr++;
                    var id = document.createElement("td");
                    id.innerHTML = invoice.id;
                    row.appendChild(id);
                    var date = document.createElement("td");
                    date.innerHTML = invoice.date;
                    row.appendChild(date);
                    var invoice_by = document.createElement("td");
                    invoice_by.innerHTML = invoice.invoice_by;
                    row.appendChild(invoice_by);
                    var total = document.createElement("td");
                    total.innerHTML = invoice.total;
                    total_cash = total_cash + parseFloat(invoice.total);
                    row.appendChild(total);
                    var user_id = document.createElement("td");
                    user_id.innerHTML = invoice.user_id;
                    row.appendChild(user_id);
                }//end for-loop
                var row = tbody.insertRow();
                var e_total_label = document.createElement('td');
                e_total_label.setAttribute('colspan', 4);
                e_total_label.setAttribute('style', 'text-align:right; padding-right:3px;');
                e_total_label.innerHTML = "Total:";
                row.appendChild(e_total_label);
                var e_total_value = document.createElement('td');
                e_total_value.setAttribute('colspan', 2);
                e_total_value.innerHTML = total_cash;
                row.appendChild(e_total_value);


            } // end function

            function formatDate(dateStr) {
                console.log(dateStr);
                if (!dateStr)
                    return '';

                var parts = dateStr.split('/'); // mm/dd/YYYY
                var mm = parts[0];
                var dd = parts[1];
                var yyyy = parts[2];
                var formatted_date = yyyy + '-' + mm.padStart(2, '0') + '-' + dd.padStart(2, '0');
                return formatted_date;
            }
        </script>
    </head>

    <body>
<?php include './sidebar.php'; ?>
       

        <div style="margin-left: 220px; padding: 28px 32px;">
            <h1 style="text-align: center; margin-top:30px; margin:0;" > Reports </h1>

            <table>
                <tr>
                    <td>Date From</td><td><input id="txt_date_from" class="form-control" type="date" ></td>
                </tr>
                <tr>
                    <td>Date To</td><td><input id="txt_date_to" class="form-control" type="date" ></td>
                </tr>
                <tr>
                    <td>User name</td><td><input id="txt_user_name" class="form-control" type="text" ></td>
                </tr>
                <tr>
                    <td>Item name</td><td><input id="txt_item_name" class="form-control" type="text" ></td>
                </tr>
                <tr>
                    <td colspan="2" style="text-align: center;"><button  onclick="show_report()" type="button" id="btn_show_report" class="btn btn-default btn-primary">Show Report</button></td>
                </tr>
                <tr>
                    <td colspan="2" style="text-align: center;"><button  onclick="show_report_by_user_name()" type="button" id="btn_show_report_by_user" class="btn btn-default btn-primary">Show Report By User</button></td>
                </tr>
                <tr>
                    <td colspan="2" style="text-align: center;"><button  onclick="show_report_by_item_name()" type="button" id="btn_show_report_by_item" class="btn btn-default btn-primary">Show Report By Items</button></td>
                </tr>
            </table>
            <table id="tbl_reports">
                <thead>
                    <tr>
                        <th>Sr.</th><th> ID </th> <th> Date </th> <th> Invoice By </th> <th> Total </th> <th> User Id </th> 

                         
                    </tr>
                </thead>

                <tbody>

                </tbody>
            </table>
        </div>


    </body>        





     

</html>