<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
 <script type="text/javascript">
     window.setInterval(function(){
        location.reload();
      }, 120000);
   </script>
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">

      <h1><b>Welcome to Seller Dashboard</b></h1>
       <br>

       <?php if($seller_details['available_status'] == 'Y'){

        ?>
        <div class="alert alert-success alert-dismissible">                
          <h4><i class="fa fa-toggle-on"></i> You are active now</h4>
          Your order receive status is Active. You are currently visible in the shop listing for the customer end website and Apps. So, you will receive an order any time. 
        </div>
        <?php

       }
       else
       {
        ?>
        <div class="alert alert-danger alert-dismissible">                
          <h4><i class="fa fa-toggle-off"></i> You are inactive now</h4>
          Your order receive status is Inactive. You are currently not visible in the shop listing for the customer end website and Apps. 
        </div>

        <?php
       }
        ?>

      
             
        <form method="post" action="<?=base_url('dashboard/status_update')?>">
      <h2 class="text-right page-header">
        Order Receive Status&nbsp;&nbsp;
        <select class="form-control pull-right" style="width: 20%;" name="available_status"  onchange="this.form.submit()" >
          <option value="Y" <?php if($seller_details['available_status'] == 'Y'){ ?> selected <?php } ?> >Active</option>
          <option value="N" <?php if($seller_details['available_status'] == 'N'){ ?> selected <?php } ?> >Inactive</option>
        </select>
      </h2>
    </form>

      

      <div class="row">
         <div class="content-header">
          <?php
          if($order_filter == 'monthly') 
          {
            ?>
            <h2 class="page-header">Orders Count - <?=date("M, Y")?></h2>  
            <?php          
          }
          else
          {
            ?>
            <h2 class="page-header">Today's Orders Count</h2>            
            <?php
          }
            ?>
            
                        <form method="GET" action="" id="order_filter_form">
                        <select name="order-search" class="form-control pull-right" style="width: 20%;" onchange="return order_filter_type_change();">
                           <option value="todays" <?php if($order_filter == 'todays') { ?> selected <?php } ?> >Today's Order Count</option>
                           <option value="monthly" <?php if($order_filter == 'monthly') { ?> selected <?php } ?> ><?=date("M, Y")?> Order Count</option>
                        </select>
                      </form>
                     

            
         </div>
         <div class="clearfix"></div><br>
         <div class="col-lg-2 col-xs-6">
            <div class="small-box bg-yellow">
               <div class="inner">
                  <h3><?=$order_counter['new_request']?></h3>
                  <p>New Order Request Count</p>
               </div>
               
               <a target="_blank" href="<?=base_url('order/new')?>" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
            </div>
         </div>
         <div class="col-lg-2 col-xs-6">
            <div class="small-box bg-aqua">
               <div class="inner">
                  <h3><?=$order_counter['accepted_order']?></h3>
                  <p>Accepted Order Count</p>
               </div>
               
               <a target="_blank" href="<?=base_url('order')?>" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
            </div>
         </div>
         <div class="col-lg-2 col-xs-6">
            <div class="small-box bg-blue">
               <div class="inner">
                  <h3><?=$order_counter['shipped_order']?></h3>
                  <p>Out For Delivery Order Count</p>
               </div>
               
               <a target="_blank" href="<?=base_url('order')?>" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
            </div>
         </div>
         <div class="col-lg-2 col-xs-6">
            <div class="small-box bg-green">
               <div class="inner">
                  <h3><?=$order_counter['completed_order']?></h3>
                  <p>Completed Order Count</p>
               </div>
               
               <a target="_blank" href="<?=base_url('order')?>" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
            </div>
         </div>
         <div class="col-lg-2 col-xs-6">
            <div class="small-box bg-red">
               <div class="inner">
                  <h3><?=$order_counter['cancelled_order']?></h3>
                  <p>Cancelled Order Count</p>
               </div>
              
               <a target="_blank" href="<?=base_url('order')?>" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
            </div>
         </div>
         <div class="col-lg-2 col-xs-6">
            <div class="small-box bg-gray">
               <div class="inner">
                  <h3><?=$order_counter['returned_order']?></h3>
                  <p>Returned Order Count</p>
               </div>
               
               <a target="_blank" href="<?=base_url('order')?>" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
            </div>
         </div>
      </div>
      
    </section>

  
      


  </div>

  <script>
  function order_filter_type_change()
  {
    $("#order_filter_form" ).submit();    
  }

  </script>