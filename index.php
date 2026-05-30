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
        <script src="libs/jquery-ui.min.js"></script>
        <link href="style2.css"  rel="stylesheet"/>
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@latest/tabler-icons.min.css">
        <style>
           
        </style>

        <Script>



            function setItemDetails()
            {

            }

            $(document).ready(function () {

                get_items();

                document.getElementById("txt_qty").addEventListener("keydown", function (event) {
                    if (event.keyCode === 13) {
                        add_item_to_table();
                    }
                });

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
                    success: function (response) {                        
                        var items = response;
                        show_items(items);
                    },
                    error: function (jqXHR, exception) {
                        console.log(jqXHR);
                        console.log(jqXHR.responseText);
                    }
                });
            }// end function


            var selected_item_id = -1;
            function show_items(services) {
                $('#txt_item_selection').autocomplete({
                    minLength: 0,
                    dataType: "json",
                    source: $.map(services, function (value, key) {
                        return {
                            label: value.item_name,
                            id: value.id,
                            price: value.item_price,
                            brand_name: value.company_name,
                            description: value.item_name
                        };
                    }),

                    select: function (event, ui) {
                        selected_item_id = ui.item.id;
                        $('#txt_item_selection').val(ui.item.description);
                        $('#txt_price').val(ui.item.price);
                        $('#txt_brand_name').val(ui.item.brand_name);
                        update_item_amount_on_selection(ui.item.price);
                        $(this).css("background-color", "rgb(133, 255, 133)");
                        $('#txt_qty').focus().select();

                    }

                });


            }//end function

            function update_item_amount_on_selection(price)
            {
                var qty = $('#txt_qty').val();
                $('#txt_item_amount').focus();
                var amount = qty * price;
                $('#txt_item_amount').val(amount);


            }//end function

            function update_price_on_change_qty()
            {
                var qty = $('#txt_qty').val();
                var price = $('#txt_price').val();

                var amount = qty * price;

                $('#txt_item_amount').val(amount);




            }//end function

            function add_item_to_table()
            {
                if (selected_item_id < 1)
                {
                    return;
                }
                var item_id = selected_item_id;
                selected_item_id = -1;
                const table = document.getElementById("txt_table");
                var tag_elements = table.getElementsByTagName("tbody");
                var tbody = tag_elements[0];
                var item_name = $('#txt_item_selection').val();
                var qty = $('#txt_qty').val();
                var price = $('#txt_price').val();
                var amount = qty * price;
                var row = tbody.insertRow();

                row.setAttribute('class', 'item-row');
                row.setAttribute('data-item-id', item_id);
                row.setAttribute('data-item-price', price);

                var e_description = document.createElement("td");
                e_description.innerHTML = item_name;
                row.appendChild(e_description);
                var e_price = document.createElement("td");
                e_price.innerHTML = price;
                row.appendChild(e_price);
                var e_qty = document.createElement("td");
                var qty_input = document.createElement("input");
                qty_input.type = "number";
                qty_input.value = qty;
                qty_input.min = 1;
                qty_input.style.width = "60px";
                qty_input.oninput = input_event_handler;
                qty_input.setAttribute('id', 'item_id_'+item_id);
                e_qty.appendChild(qty_input);
                row.appendChild(e_qty);

                var e_amount = document.createElement("td");
                e_amount.innerHTML = amount;
                row.appendChild(e_amount);
                var prev_total = $('#lbl_total').html();
                prev_total = parseInt(prev_total);
                var current_total = prev_total + amount;
                $('#lbl_total').html(current_total);
                $('#txt_item_selection').val('');
                $('#txt_price').val(0);
                $('#txt_qty').val(1);
                $('#txt_item_amount').val(0);
                $('#txt_brand_name').val('');
                $('#txt_item_selection').focus();


            }//end function
            
function input_event_handler ()
                {
                    var row = this.closest("tr");

                    var price = row.cells[1].innerHTML;
                    price = parseInt(price);

                    var qty_value = this.value;
                    qty_value = parseInt(qty_value) || 0;

                    var amount = price * qty_value;

                    row.cells[3].innerHTML = amount;

                    update_total();
                }
function update_total()
{
    var total = 0;

    var table = document.getElementById("txt_table");
    var tbody = table.getElementsByTagName("tbody")[0];
    var rows = tbody.rows;

    for (var i = 0; i < rows.length; i++)
    {
        var amount = rows[i].cells[3].innerHTML;
        amount = parseInt(amount) || 0;

        total = total + amount;
    }

    $('#lbl_total').html(total);
}

function generate_invoice()
{
    var item_rows = document.getElementsByClassName('item-row');
    if(item_rows.length<1)
    {
        alert('Please select some item to add in invoice');
        return;
    }
    var total_amount = 0;
    var items = [];
    for(var i=0; i<item_rows.length; i++)
    {
        var item_row = item_rows[i];
        var item = {};
        var item_id = item_row.getAttribute('data-item-id');
        item.id= item_id;
        item.price= item_row.getAttribute('data-item-price');
        var price = parseFloat(item.price);
        
        var qty_element = document.getElementById('item_id_'+item_id);
        item.qty=qty_element.value;
        var qty = parseInt(item.qty);
        var amount = price*qty;
        total_amount += amount;
        items.push(item);
    }
    
    var parameters = {action_id: 7, data:items, total:total_amount};
                $.ajax({
                    type: "GET",
                    url: "invoice-services.php",
                    data: parameters,
                    contentType: "application/json; charset=utf-8",
                    dataType: "json",
                    success: function (response) {
                        console.log(response);
                        var invoice_id = response;
                        if(invoice_id>0)
                        {
                            var url = "invoice.php?invoice_id="+invoice_id;
                            window.open(url,'_blank');
                            $("#txt_table tbody").html("");
                            
                            total=0;
                            $("#lbl_total").html("0");
                           
                            
                        }else
                        {
                            alert('Some error occured');
                        }
                        
                    },
                    
                    error: function (jqXHR, exception) {
                        console.log(jqXHR);
                        console.log(jqXHR.responseText);
                    } 
                });
}//end function

        </script>

    </head>

    <body>

        <?php include './sidebar.php'; ?>

        <div id="main-content" style="float:left; padding-left: 10px; padding-top: 10px;">
            <div id="navbar"></div>
            <h1 style="text-align: center; margin-top:30px; margin:0;" > INVOICE SYSTEM </h1>

            <table>
                <tr>
                    <td style="font-weight:bold ; font-size:30px; ">   Asian Mall </td>
                </tr>
                <tr>
                    <td style="font-weight:bold ;">  Address:Islamabad f/6 </td>
                </tr>
                <tr> 
                    <td style="font-weight:bold ;">  Phone: 8465000 </td>
                </tr>
                <tr>
                    <td style="font-weight:bold ;">   website:google.com </td>
                </tr>
            </table>
            <table>
                <tr>
                    <td style="font-weight:bold; background-color:brown;  ">
                        Bill TO 
                    </td>
                </tr>
                <tr>
                    <td>
                        Name
                    </td>
                    <td>   
                        <input type="text" name="txt_name"/>
                    </td>
                </tr>
                <tr>
                    <td>
                        Company Name
                    </td>
                    <td>   
                        <input type="text" name="txt_company_name"/>
                    </td>
                </tr>
                <tr>
                    <td>
                        City
                    </td>
                    <td>   
                        <input type="text" name="txt_city"/>
                    </td>
                </tr>
                <tr>
                    <td>
                        ZIP Code 
                    </td>
                    <td>   
                        <input type="number" name="txt_zip_code"/>
                    </td>
                </tr>
                
    <strong>Payment:</strong>
    <select>
        <option value="">Select Payment Method</option>
        <option value="Cash">Cash</option>
        <option value="Card">Card</option>
    </select>


            </table>
            <table >

                <tr>
                    <th>
                        Items
                    </th>
                    <td>

                        <input type="text" id="txt_item_selection" class="form-control">
                    </td>


                    <th>
                        Quantity
                    </th>
                    <td>
                        <input id="txt_qty"  oninput="update_price_on_change_qty()" value="1" type="number" placeholder="Enter quantity"/>
                    </td>
                    <th>
                        Price
                    </th>
                    <td>
                        <input placeholder="price" type="number" readonly id="txt_price"/>
                    </td>
                    <th>
                        Amount
                    </th>
                    <td>
                        <input name="item_amount" value="0" placeholder="amount" type="number" readonly id="txt_item_amount"/>
                    </td>
                    <th>
                        Brand
                    </th>
                    <td>
                        <input placeholder="Brand name" type="text" readonly id="txt_brand_name"/>
                    </td>
                </tr>

            </table>
            <table id="txt_table" style="border: 2px solid;" >
                <thead>
                    <tr>

                        <th value="0"> Description </th><th value="0">Price</th><th value="0">Qty</th><th value="0"> Amount </th> 

                    </tr>
                </thead>
                <tbody>

                </tbody>
                <tfoot>
                    <tr> 
                    <th colspan="3">
                        Total:
                    </th>
                    <th><label id="lbl_total">0</label></th>
                </tr>
                <tr>
                    <td colspan="4" style="text-align: center;">
                        <button type="button" onclick="generate_invoice()">Generate Invoice</button>
                    </td>
                </tr>
                </tfoot>
            </table >
        </div>


    </body>        





</script>       

</html>