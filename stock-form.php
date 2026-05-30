
<?php
include './tbl_stock.php';
include './config.php';
include './item.php';

$item = new Item();
$tbl_stock = new Tbl_stock();
if (isset($_POST['btn_save'])) {
    $bill_no = $_POST['txt_bill_no'];
    $bill_date = $_POST['txt_bill_date'];
    $bill_total = $_POST['txt_bill_total'];
    $stocks = $tbl_stock->save($conn, $bill_no, $bill_date, $bill_total);
}
?>
<html>

    <head>
        <title> Stock Page </title>
        <script src="libs/jquery.min.js"></script>       
        <link href="libs/bootstrap.min.css" rel="stylesheet" />    
        <script src="libs/bootstrap.min.js"></script>
        <link href="libs/jquery-ui.min.css" rel="stylesheet" />
        <script src="libs/jquery-ui.min.js"></script>
        <link rel="stylesheet" href="stocks.css">
    </head>
    <body>
<?php include './sidebar.php'; ?>
        <div style="margin-left: 220px; padding: 28px 32px;">
            <h1><strong> Stock Addition Page </strong>  </h1>  
            <table>
                <tr>
                    <td>
                        Bill No:
                    </td>
                    <td>
                        <input type="text" id="txt_bill_no"/>
                    </td>
                </tr>
                <tr>
                    <td>
                        Bill Date:
                    </td>
                    <td>
                        <input type="text" id="txt_bill_date"/>
                    </td>
                </tr>
                                           
            </table>
            <table>
                <tr>
                    <th>
                        Items
                    </th>
                    <td>
                        <input type="text" placeholder="Select items" id="txt_items" />
                    </td>
                    <th>
                        Quantity
                    </th>
                    <td>
                        <input type="text" placeholder="Enter quantity" id="txt_qty" />
                    </td>
                    <th>
                        Price
                    </th>
                    <td>
                        <input type="text" placeholder="Enter price" id="txt_price" />
                    </td>


                </tr>

            </table>
            <table id="tbl_items">
                <thead>
                <tr>
                    <th> Description </th> <th> Qty </th> <th> Price </th> <th> Amount </th>
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
                        <button type="button" onclick="Save_stock()">Save Stock</button>
                    </td>
                </tr>
                </tfoot>
            </table>
        </div>

    </body>

</html>
<script>
    $(document).ready(function () {
        get_items();
        document.getElementById("txt_price").addEventListener("keydown", function (event) {
                    if (event.keyCode === 13) {
                        add_items_to_table();
     }
                });

            });

    function get_stocks_by_ajax()
    {
        fetch("stock-service.php?action_id=1")
                .then(response => response.text())
                .then(data => {
                    console.log(data);
                    var stocks = JSON.parse(data);
                    // fillTable(stocks);
                })
                .catch(error => console.error("Error:", error));
    } // end function
    
    function get_items()
    {
        var parameters = {action_id: 3};
        $.ajax({
            type: "GET",
            url: "stock-service.php",
            data: parameters,
            contentType: "apllication/json; charset=utf-8",
            dataType: "json",
            success: function (response)
            {
                var Items = response;
                show_items(Items);
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
        $('#txt_items').autocomplete({
            minLength: 0,
            dataType: "json",
            source: $.map(items, function (value, key)
            {
                return{
                    label: value.item_name,
                    item_id: value.id,
                    description: value.item_name


                };
            }),
            select: function (event, ui) {
                $('#txt_items').val(ui.item.description);
                selected_item_id=ui.item.item_id;
                $('#txt_qty').focus().select();
               

            }
        });
    }// end function
    function update_total()
            {
               var total=0;
               var table= document.getElementById("tbl_items");
               var tbody = table.getElementsByTagName("tbody");
               var rows= tbody.rows;
               for (var i=0; i<rows.length; i++)
               {
                   var amount= rows[i].cells[3].innerHTML;
                   amount= parseInt(amount) || 0;
                   total +=amount;
            }
            $('lbl_total').html(total);
        }
         
    function add_items_to_table()
    {
         if (selected_item_id < 1)
                {
                    alert('SElect item');
                    return;
                }
        const table = document.getElementById("tbl_items");
        var tag_elements = table.getElementsByTagName("tbody");
        var tbody = tag_elements[0];
       var item_id=selected_item_id;
       selected_item_id=-1;
        var row = tbody.insertRow();
        var item_name = $("#txt_items").val();
        var qty = $("#txt_qty").val();
        var price = $("#txt_price").val();
        var amount= qty*price;
        
        row.setAttribute('class', 'item-row');
        row.setAttribute('data-item-id', item_id);
        row.setAttribute('data-item-price', price);
        row.setAttribute('data-item-qty', qty);
        var e_description = document.createElement("td");
        e_description.innerHTML=item_name;
        row.appendChild(e_description);
        var e_qty = document.createElement("td");
        e_qty.innerHTML=qty;
        row.appendChild(e_qty);
        var e_price = document.createElement("td");
        e_price.innerHTML=price;
        row.appendChild(e_price);
          var e_amount = document.createElement("td");
                e_amount.innerHTML = amount;
                row.appendChild(e_amount);
                var prev_total = $('#lbl_total').html();
                prev_total=parseInt(prev_total);
                var current_total=prev_total + amount;
                $('#lbl_total').html(current_total);
        
        $('#txt_items').val('');
        $('#txt_qty').val(1);
        $('#txt_price').val(0);
        $('#txt_items').focus();
        
        
    }// end function
        
function Save_stock()
{
    var item_rows=document.getElementsByClassName('item-row');
    if(item_rows.length<1){
        alert("Please add some items")
    } 
    var total_amount=0;
    var items=[];
    var e_bill_no = document.getElementById("txt_bill_no");
     var bill_no =e_bill_no.value;
     var e_bill_date = document.getElementById("txt_bill_date");
     var bill_date = e_bill_date.value;
    for (var i=0; i<item_rows.length; i++)
    {
        var item_row = item_rows[i];
        var item={};
       
        var item_id = item_row.getAttribute('data-item-id');
        item.id = item_id;
        item.price=item_row.getAttribute('data-item-price');
        var price= parseFloat(item.price);
        item.qty= item_row.getAttribute('data-item-qty');
        var qty= parseFloat(item.qty);
        
        var amount=qty*price;
        total_amount += amount;
        items.push(item);
    }
    var parameters={action_id: 4, data:items, total: total_amount, bill_no:bill_no, bill_date:bill_date };
    console.log(parameters);
    $.ajax({
        type: "GET",
        url: "stock-service.php",
        data : parameters,
        contentType: "application/json; charset=utf-8",
                    dataType: "json",
                    success: function(response) 
                    {
                        alert("succeded");
                    console.log(response);
                    $("#tbl_items tbody").html("");
                     total=0;
                            $("#lbl_total").html("0");
                    
                    },
                    error: function (jqXHR, exception) {
                        console.log(jqXHR);
                        console.log(jqXHR.responseText);
                    }
                
    }) ;
}


    






</script>