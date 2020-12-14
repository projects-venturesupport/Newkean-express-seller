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
   .my-3
   {
    margin-top: .5rem;
    margin-bottom: .5rem;
   }
</style>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
   <!-- Content Header (Page header) -->
   <section class="content-header">
      <h1>New Order List</h1>
   </section>
   <?php
      // /print_r($filter_data);
      ?>
   <!-- Main content -->
   <section class="content">
      
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
                                 <li class="list-group-item">
                                  <div class="row">
                                    <div class="col-sm-6"> 
                                      <button type="button" class="btn btn-success action_btn my-3" onclick="return accept_order(<?=$order_row['id']?>);">Accept</button>
                                    </div>
                                 
                                    <div class="col-sm-6"> 
                                  <button type="button" class="btn btn-danger action_btn my-3" onclick="return reject_order(<?=$order_row['id']?>);">Reject</button>
                                </div>
                                  </div>
                                 </li>
                                 <li class="list-group-item">Delivery Date & Time: <b><br><?=date("dS M, Y", strtotime($order_row['delivery_date']))?> (<?=$order_row['time_slot_details']['time_slot']?>)
                                    </b>
                                 </li>
                                 <li class="list-group-item">
                                  <div class="text-center" style="display:none" id="status_loader_<?=$order_row['id']?>">
                                    <img src="<?=base_url('assets/dist/img/loader-mini.gif')?>" style="width: 100px;">
                                  </div>
                                </li>
                                 
                                  
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
                              <center>No New Order Found.</center>
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
   function accept_order(id)
   {
    $( ".action_btn" ).prop( "disabled", true );
      $("#status_loader_"+id).show();
      // submit this form
      var dataString = 'id=' + id + '&status=SA';
      $.ajax({
      type: "POST",
      url: "<?=base_url('order/update_order_status')?>",
      data: dataString,
      cache: false,
      success: function(html) {
        var obj = $.parseJSON(html);
        $( ".action_btn" ).prop( "disabled", false );
        $("#status_loader_"+id).hide();
        
        location.reload();
      }
      });



   }

   function reject_order(id)
   {
    if (confirm("You can't undo this operation. Are sure want to reject this order?")) {

      $( ".action_btn" ).prop( "disabled", true );
      $("#status_loader_"+id).show();
      // submit this form
      var dataString = 'id=' + id + '&status=SR';
      $.ajax({
      type: "POST",
      url: "<?=base_url('order/update_order_status')?>",
      data: dataString,
      cache: false,
      success: function(html) {
        var obj = $.parseJSON(html);
        $( ".action_btn" ).prop( "disabled", false );
        $("#status_loader_"+id).hide();
        
        location.reload();
      }
      });


    } 
    return false;
   }
</script>