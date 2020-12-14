<style type="text/css">
   .required_cls
   {
   color: red;
   }
   .reset_btn{
   margin-top: 24px;
   }
   .high_label
   {
   font-size: 12px;
   }
   .action_area_td
   {
   width: 13%;
   }
   .sl_margin
   {
   margin-right: 5px;
   }
</style>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
   <!-- Content Header (Page header) -->
   <section class="content-header">
      <h1>Order List</h1>
   </section>
   <?php
      // /print_r($filter_data);
      ?>
   <!-- Main content -->
   <section class="content">
      <div class="row">
         <div class="col-xs-12">
            <div class="box">
               <div class="box-body">
                  <form method="POST" action="<?=base_url('order')?>" id="filter_form">
                     <input type="hidden" name="filter" value="true">
                     <div class="form-group col-md-3">
                        <label for="search-type">Filter by </label>
                        <select name="search-type" id="search-type" class="form-control" onchange="return search_type_change();">
                           <option value="detault" <?php if($filter_data['search-type'] == 'detault') { ?> selected <?php } ?>>None - Last 30 Day's Order List</option>
                           <option value="manual-date" <?php if($filter_data['search-type'] == 'manual-date') { ?> selected <?php } ?> >Manual Date Renge</option>
                           <option value="today-delivery" <?php if($filter_data['search-type'] == 'today-delivery') { ?> selected <?php } ?> >Today's Delivery</option>
                        </select>
                     </div>
                     <div class="form-group col-md-3 manual-date-cls"  <?php if($filter_data['search-type'] != 'manual-date') { ?> style="display: none;" <?php } ?> >
                        <label for="official_email">Select Custom Date</label>
                        <div class="input-group">
                           <div class="input-group-addon">
                              <i class="fa fa-calendar"></i>
                           </div>
                           <input type="text" class="form-control pull-right" id="reservation" name="custom-date" value="<?=$filter_data['custom-date']?>" >
                           <input type="hidden" id="hidden-custom-date" value="<?=$filter_data['custom-date']?>">
                        </div>
                     </div>
                     <div class="form-group col-md-3 manual-date-cls" <?php if($filter_data['search-type'] != 'manual-date') { ?> style="display: none;" <?php } ?> >
                        <label for="order_status" >Order Status</label>
                        <input type="hidden" id="hidden-order-status" value="<?=$filter_data['order-status']?>">
                        <select id="order-status" class="form-control ord_status" name="order-status">
                           <option value="all" <?php if($filter_data['order-status'] == 'all') { ?> selected <?php } ?> >All Status (Except Failed Order)</option>
                           <option value="SA" <?php if($filter_data['order-status'] == 'SA') { ?> selected <?php } ?> >Accepted Order</option>
                           <option value="SR" <?php if($filter_data['order-status'] == 'SR') { ?> selected <?php } ?> >Rejected Order</option>
                           <option value="S" <?php if($filter_data['order-status'] == 'S') { ?> selected <?php } ?> >Out for Delivery</option>
                           <option value="D" <?php if($filter_data['order-status'] == 'D') { ?> selected <?php } ?>>Completed Order</option>
                           <option value="C" <?php if($filter_data['order-status'] == 'C') { ?> selected <?php } ?>>Cancelled Order</option>
                           <option value="R" <?php if($filter_data['order-status'] == 'R') { ?> selected <?php } ?>>Returned Order</option>
                        </select>
                     </div>
                     <div class="form-group col-md-1 manual-date-cls" <?php if($filter_data['search-type'] != 'manual-date') { ?> style="display: none;" <?php } ?>  >
                        <button type="button" class="btn btn-block btn-primary reset_btn " onclick="return submit_filter_form();">Filter</button>
                     </div>
                     <div class="form-group col-md-1" id="reset_btn_div" <?php if($filter_data['filter'] == false) { ?> style="display: none;" <?php } ?> >
                        <a href="<?=base_url('order')?>"><button type="button" class="btn btn-block btn-primary reset_btn ">Reset</button> </a>           
                     </div>
                     <div class="form-group col-md-1" <?php if($export_flag == "N" || count($order_list) == 0) { ?> style="display: none;" <?php } ?>>
                        <button type="button" class="btn btn-primary reset_btn " onclick="return export_order();"  >Export <i class="fa fa-file-excel-o"></i></button>
                     </div>
                     <div class="clearfix"></div>

                     <div class="col-md-12">
                      <p>Note: For export order data in Excel file, please use 'Manual Date Range' filter within 90 days limit.</p>
                     </div>

                  </form>

               </div>
            </div>

                  
         </div>
         
      </div>
      <div class="row">
         <div class="col-xs-12">
            <div class="box">
               <div class="box-header">
                  <!--<h3 class="box-title">Data Table With Full Features</h3>-->
               </div>
               <!-- /.box-header -->
               <div class="box-body">
                <div class="table-responsive">
                  <table id="example2" class="table table-bordered table-striped">
                     <thead>
                        <tr>
                           <th style="display: none;">No</th>
                           <th style="width:20%">Order Details</th>
                           <th style="width:20%">Shipping Details</th>
                           <th style="width:25%">Product Details</th>
                           <th style="width:15%">Price</th>
                           <th style="width:20%">Status</th>
                        </tr>
                     </thead>
                     <tbody>
                        <?php

                        /*echo "<pre>";
                        print_r($order_list); exit;*/
                           
                           $i = 1;
                           if(count($order_list) > 0)
                           {
                             foreach($order_list as $order_row)
                             {
                             ?>
                        <tr>
                           <th style="display: none;"><?=$i?></th>
                           <td style="width:20%">
                              <ul class="list-group">
                                 <li class="list-group-item">ID: <b><?=$order_row['order_no']?></b></li>
                                 <li class="list-group-item"><?=date("dS M, Y h:i A", strtotime($order_row['created_date']))?></li>
                                 
                                   <a target="_blank" href="<?=base_url('user-edit/'.$order_row['customer_details']['id'])?>"><li class="list-group-item text-center"  style="background: #3d8cbc; color:white;">
                                    User: <?php echo $order_row['customer_details']['full_name']; ?>

                                   </li>
                                   
                                    <a target="_blank" href="<?=base_url('order/details/'.$order_row['id'])?>"><li class="list-group-item" style="background: #3d8cbc; color:white;">
                                       <center>View Details</center>
                                       </b>
                                    </li>

                                 </a>
                                 <li class="list-group-item">
                                  <b>Seller Details:</b>
                                  <p><?php echo $order_row['seller_details']['shop_name'] ?>(<b><?php echo $order_row['seller_details']['username'] ?></b>)</p>
                                </li>
                              </ul>
                           </td>
                           <td style="width:20%">
                              <ul class="list-group">
                                 <li class="list-group-item">
                                    <p>
                                       <?=strtoupper($order_row['address_details']['name'])?>                              
                                       <br>
                                       <?=strtoupper($order_row['address_details']['phone'])?>
                                       <br>
                                       <?=strtoupper($order_row['address_details']['address_1'])?>, <?=strtoupper($order_row['address_details']['address_2'])?>, Landmark - <?=strtoupper($order_row['address_details']['landmark'])?>,<br>  <?=strtoupper($order_row['address_details']['city_name'])?>, <?=strtoupper($order_row['address_details']['state_name'])?>, INDIA - <?=$order_row['address_details']['zip_code']?>
                                    </p>
                                 </li>
                              </ul>
                           </td>
                           <td style="width:25%">
                              <ul class="list-group">
                                 <?php
                                    if(count($order_row['product_details']) > 0)
                                    {
                                      foreach($order_row['product_details'] as $product_row)
                                      {
                                      ?>
                                 <li class="list-group-item">
                                    <p><?=$product_row['variation_details']['product_details']['name']?> <br> <?=$product_row['variation_details']['variation_details']['title']?>&nbsp;<i class="fa fa-times"></i>&nbsp;<?=$product_row['quantity']?>  
                                    </p>
                                 </li>
                                 <?php
                                    }
                                    }
                                    ?>
                              </ul>
                           </td>
                           <td style="width:15%">
                              <ul class="list-group">
                                 <li class="list-group-item">
                                    <p>Subtotal<br><i class="fa fa-inr"></i><b><?=$order_row['total_price']?></b><br>
                                       Shipping(+)<br><i class="fa fa-inr"></i><b><?=$order_row['delivery_charge']?></b><br>
                                       Discount(-)<br><i class="fa fa-inr"></i><b><?=$order_row['discount']?></b>
                                       Total<br><i class="fa fa-inr"></i><b><?=$order_row['order_total']?></b><br>
                                       <?php 
                                       if($order_row['payment_method'] == 'online')
                                       {
                                        echo "<b>Online Payment</b>";
                                       }
                                       else
                                       {
                                        echo "<b>Pay On Delivery (POD)</b>";
                                       }
                                       ?>
                                    </p>
                                 </li>
                              </ul>
                           </td>
                           <td style="width:20%">
                              
                              <ul class="list-group">
                                 <li class="list-group-item"><b>
                                 
                                <?php
                                if($order_row['status'] == 'SA' && count($order_row['logistic_details']) > 0) 
                                {
                                  echo  '<label for="" >Status: Accepted Order</label>';
                                  ?>
                                  <hr>
                                  <p>Delivery Boy Details:</p>
                                  <p><i class="fa fa-user" aria-hidden="true"></i>&nbsp;&nbsp;<?=$order_row['logistic_details']['name']?></p>
                                  <a href="tel:+91<?=$order_row['logistic_details']['phone']?>"><i class="fa fa-phone-square" aria-hidden="true"></i>&nbsp;<?=$order_row['logistic_details']['phone']?></a>
                                  <p>Verify Delivery Boy OTP:</p>
                                  <div class="row">
                                      <div class="col-lg-6">
                                        <input type="text" name="" class="form-control" style="width: 80px !important;" placeholder="OTP" id="otp_<?=$order_row['id']?>">
                                      </div>
                                      <div class="col-lg-6">
                                        <button type="button" class="btn btn-info pull-right action_btn" onclick="return verify_delivery_boy_otp(<?=$order_row['id']?>);">Verify</button>
                                      </div>
                                  </div>
                                  <?php
                                }
                                else if($order_row['status'] == 'SA' && count($order_row['logistic_details']) == 0) 
                                {
                                  echo  '<label for="" >Status: Accepted Order</label><small>Assigning for delivery</small>';
                                }
                                else if($order_row['status'] == 'SR') 
                                {
                                  echo  '<label for="" >Status: Rejected Order</label>';
                                }
                                else if($order_row['status'] == 'S') 
                                {
                                  echo  '<label for="" >Status: Out for Delivery</label>';
                                  ?>
                                  <hr>
                                  
                                  <div class="row">
                                      
                                      <div class="col-lg-12 text-center">
                                        <p>Return Your Order</p>
                                        <button type="button" class="btn btn-info action_btn" onclick="return return_order(<?=$order_row['id']?>);">Return</button>
                                      </div>
                                  </div>
                                  <?php
                                }
                                else if($order_row['status'] == 'D') 
                                {
                                  echo  '<label for="" >Status: Delivered</label>';
                                }
                                else if($order_row['status'] == 'C') 
                                {
                                  echo  '<label for="" >Status: Cancelled</label>';
                                }
                                else if($order_row['status'] == 'D') 
                                {
                                  echo  '<label for="" >Status: Returned</label>';
                                }

                                ?>
                              
                              <div class="text-center" style="display:none" id="status_loader_<?=$order_row['id']?>">
                                <img src="<?=base_url('assets/dist/img/loader-mini.gif')?>" style="width: 100px;">
                              </div>

                                 </b>
                                 </li>
                                 
                                 <li class="list-group-item">Delivery Date & Time: <b><br><?=date("dS M, Y", strtotime($order_row['delivery_date']))?> (<?=$order_row['time_slot_details']['time_slot']?>)
                                    </b>
                                 </li>
                                 <a target="_blank" href="<?=$order_row['invoice']?>"><li class="list-group-item" style="background: #3d8cbc; color:white;"><b>
                                       <center>View/Download Invoice</center>
                                       </b>
                                    </li>
                                  </a>
                              </ul>
                              
                           </td>
                        </tr>
                        <?php
                           $i++;
                           }
                           }
                           else
                           {
                           ?>
                        <tr>
                           <td colspan="5">
                              <center>No Order Found.</center>
                           </td>
                        </tr>
                        <?php
                           }
                           ?>
                     </tbody>
                  </table>
                </a>
               </div>
               <!-- /.box-body -->
            </div>
            <!-- /.box -->
         </div>
         <!-- /.col -->
      </div>
      <!-- /.row -->
   </section>
   <!-- /.content -->
</div>
<!-- /.content-wrapper -->
<script type="text/javascript">

  function export_order()
   {    
      var date_range = document.getElementById('hidden-custom-date').value;
      var order_status = document.getElementById('hidden-order-status').value;
      
        swal({
            title: "Success",
            text: "Excel file successfully created.",
            icon: "success",
            button: "Ok",
          });
        window.location.href = "<?php echo base_url('order/export_order?'); ?>date-range=" + date_range + "&status=" + order_status;    
      
   }

   //-----------------------------------------------
  function update_status(id)
  {
    var status_val = $('#status_' + id).val();
    $('#status_loader_'+id).show();
    $(".ord_status").prop('disabled', true);
    var dataString = 'id=' + id + '&status=' + status_val;
      $.ajax({
      type: "POST",
      url: "<?=base_url('order/update_order_status')?>",
      data: dataString,
      cache: false,
      success: function(html) {
        var obj = $.parseJSON(html);
        if(obj.status == "Y")
        {
          
          $('#status_loader_'+id).hide();
          $(".ord_status").prop('disabled', false);
          swal("Success!", "Order status successfully changed.", "success");
          
        }
        else
        {
          
          $('#status_loader_'+id).hide();
          $(".ord_status").prop('disabled', false);
          swal("Failed!", "Order status change failed!", "error");
          

        }
        
        
      }
      });
  }
   function search_type_change()
   {
     $(".manual-date-cls").hide();
     $("#reset_btn_div").show();
     
   
     var search_type = $("#search-type").val();
     if(search_type == 'manual-date')
     {
       $(".manual-date-cls").show();
       $("#reset_btn_div").show();
     }
     else if(search_type == 'today-delivery')
     {
       $("#filter_form").submit();
     }
     else
     {
       window.location.href = "<?=base_url('order')?>";
     }
   
     //alert(search_type);
   }
   
   function submit_filter_form()
   {
     $("#filter_form").submit();

   }

   function verify_delivery_boy_otp(id)
   {
      var otp = document.getElementById('otp_'+id).value;
      $(".action_btn").prop("disabled", true);
      $("#status_loader_"+id).show();
      // submit this form
      var dataString = 'id=' + id + '&otp=' + otp;
      $.ajax({
      type: "POST",
      url: "<?=base_url('order/verify_logistic_otp')?>",
      data: dataString,
      cache: false,
      success: function(html) {
        var obj = $.parseJSON(html);
        $( ".action_btn" ).prop( "disabled", false );
        $("#status_loader_"+id).hide();

        if(obj.status == "Y")
        {
          location.reload();
        }
        else
        {
          swal({
            title: "Invalid OTP",
            text: obj.message,
            icon: "error",
            button: "Ok",
          });
        }       
        
      }
      });


      



   }

   function return_order(id)
   {
    if(confirm("Are you sure, you have get all the items from delivery boy and continue order return?") == true)
    {

      $(".action_btn").prop("disabled", true);
      $("#status_loader_"+id).show();
      // submit this form
      var dataString = 'id=' + id;
      $.ajax({
      type: "POST",
      url: "<?=base_url('order/return_order')?>",
      data: dataString,
      cache: false,
      success: function(html) {
        var obj = $.parseJSON(html);
        $( ".action_btn" ).prop( "disabled", false );
        $("#status_loader_"+id).hide();

        if(obj.status == "Y")
        {
          location.reload();
        }
        else
        {
          swal({
            title: "Invalid OTP",
            text: obj.message,
            icon: "error",
            button: "Ok",
          });
        }       
        
      }
      });


    }

      

   }
</script>