<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<?php
if(!isset($navigation))
{
  echo $navigation = "dashboard";
}
if(!isset($sub_navigation))
{
  echo $sub_navigation = "none";
}
?>

<?php
$order_request = $this->dashboard_model->get_pending_request_order_count();
?>
<!-- Left side column. contains the logo and sidebar -->
  <aside class="main-sidebar">
    <!-- sidebar: style can be found in sidebar.less -->
    <section class="sidebar">
      <!-- Sidebar user panel -->
      <div class="user-panel">
        <div class="pull-left image">
          <img src="<?php echo $seller_details['image']; ?>" class="img-circle" alt="User Image">
        </div>
        <div class="pull-left info">
          <p><?php echo ucwords(strtolower($seller_details['shop_name'])); ?></p>
          <a href="#"><i class="fa fa-circle text-success"></i> Online</a>
        </div>
      </div>
      
      <!-- sidebar menu: : style can be found in sidebar.less -->
      <ul class="sidebar-menu" data-widget="tree">
        
        <li class="<?php if($navigation == 'dashboard') { ?>active<?php } ?>">
          <a href="<?php echo base_url(''); ?>">
            <i class="fa fa-dashboard"></i> <span>Dashboard</span>            
          </a>          
        </li>

        <li class="<?php if($navigation == 'profile') { ?>active<?php } ?>">
          <a href="<?php echo base_url('profile'); ?>">
            <i class="fa fa-user"></i> <span>My Profile</span>            
          </a>          
        </li>

        <li class="treeview <?php if($navigation == 'product') { ?>active<?php } ?>">
          <a href="#">
            <i class="fa fa-circle-o text-aqua"></i> <span>Product Management</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>

        <ul class="treeview-menu">
            <li class="<?php if($navigation == 'product' && $sub_navigation == 'product-list') { ?>active<?php } ?>"><a href="<?php echo base_url('product');?>"><i class="fa fa-circle-o"></i> Product List</a></li>
            <li class="<?php if($navigation == 'product' && $sub_navigation == 'product-add') { ?>active<?php } ?>"><a href="<?php echo base_url();?>product/add"><i class="fa fa-circle-o"></i> Create New Product</a></li>
          </ul>

        </li>

        <li class="<?php if($navigation == 'order-new') { ?>active<?php } ?>">
          <a href="<?php echo base_url('order/new'); ?>">
            <i class="fa fa-paper-plane"></i> <span>New Order Request</span>   

            <span class="pull-right-container" <?php if($order_request == 0) { ?> style="display:none" <?php } ?> >
              <small class="label pull-right bg-yellow"><?=$order_request?></small>
            </span>

          </a>          
        </li>

        <li class="<?php if($navigation == 'order') { ?>active<?php } ?>">
          <a href="<?php echo base_url('order'); ?>">
            <i class="fa fa-shopping-cart"></i> <span>My Order</span>            
          </a>          
        </li>

        

        


      </ul>
    </section>
    <!-- /.sidebar -->
  </aside>