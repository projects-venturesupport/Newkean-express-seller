<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<?php
if(!isset($title))
{
   $title = "Welcome to admin panel";
}  
?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title><?php echo $title; ?></title>


  <!-- loader part start here -->
<div id="loading">
    <center>
      <img id="loading-image" src="<?php echo ASSETS_URL."dist/img/loader.gif" ?>" alt="Loading..." />
    </center>
</div>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>

  <style type="text/css">
    #loading {
    width: 100%;
    height: 100%;
    top: 0px;
    left: 0px;
    position: fixed;
    display: block;
    opacity: 0.7;
    background-color: #fff;
    z-index: 99;
    text-align: center;
  }

  #loading-image {
    top: 50%;
    left: 50%;
    z-index: 100;
  }
  </style>

  <script type="text/javascript">
    $(document).ready(function() 
    {
  $('#loading').hide();
    });
  </script>
  <!-- loader part end here -->



   <!-- Tell the browser to be responsive to screen width -->
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
  <!-- Bootstrap 3.3.7 -->
  <link rel="stylesheet" href="<?php echo ASSETS_URL; ?>bower_components/bootstrap/dist/css/bootstrap.min.css">
  <!-- Font Awesome -->
  <!-- DataTables -->
  <link rel="stylesheet" href="<?php echo ASSETS_URL; ?>bower_components/datatables.net-bs/css/dataTables.bootstrap.min.css">
  <link rel="stylesheet" href="<?php echo ASSETS_URL; ?>bower_components/font-awesome/css/font-awesome.min.css">
  <!-- Ionicons -->
  <link rel="stylesheet" href="<?php echo ASSETS_URL; ?>bower_components/Ionicons/css/ionicons.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="<?php echo ASSETS_URL; ?>dist/css/AdminLTE.min.css">
  <link rel="stylesheet" href="<?php echo ASSETS_URL; ?>dist/css/custom.css">
  <!-- AdminLTE Skins. Choose a skin from the css/skins
       folder instead of downloading all of them to reduce the load. -->
  <link rel="stylesheet" href="<?php echo ASSETS_URL; ?>dist/css/skins/_all-skins.min.css">
  <!-- Morris chart -->
  <link rel="stylesheet" href="<?php echo ASSETS_URL; ?>bower_components/morris.js/morris.css">
  <!-- jvectormap -->
  <link rel="stylesheet" href="<?php echo ASSETS_URL; ?>bower_components/jvectormap/jquery-jvectormap.css">
  <!-- Date Picker -->
  <link rel="stylesheet" href="<?php echo ASSETS_URL; ?>bower_components/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css">
  <!-- Daterange picker -->
  <link rel="stylesheet" href="<?php echo ASSETS_URL; ?>bower_components/bootstrap-daterangepicker/daterangepicker.css">
  <!-- bootstrap wysihtml5 - text editor -->
  <link rel="stylesheet" href="<?php echo ASSETS_URL; ?>plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.min.css">



  <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
  <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
  <!--[if lt IE 9]>
  <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
  <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
  <![endif]-->

  <!-- Google Font -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
  <style type="text/css">
    .error_cls{
      border-color: #fd0000 !important;
    }
  </style>
</head>
<body class="hold-transition skin-yellow-light sidebar-mini">
<div class="wrapper">

  <header class="main-header">
    <!-- Logo -->
    <!--<a href="<?php echo base_url(''); ?>" class="logo">
      
      <span class="logo-mini"><b>Seller</b></span>
      <span class="logo-lg"><b>Seller</b>Panel</span>
    </a>-->

    <a href="<?=base_url('')?>" class="logo company_logo bg-gray">
      <span class="logo-mini">
      <!-- <img src="<?=ASSETS_URL.'dist/img/half-logo.png'?>" data-pagespeed-url-hash="132196651" onload="pagespeed.CriticalImages.checkImageForCriticality(this);"> -->
      SL
      </span>
      <span class="logo-lg">
      <!-- <img src="<?=ASSETS_URL.'dist/img/brand_logo.png'?>" data-pagespeed-url-hash="3778899244" onload="pagespeed.CriticalImages.checkImageForCriticality(this);"> -->
      Seller
      </span>
      </a>
    <!-- Header Navbar: style can be found in header.less -->
    <nav class="navbar navbar-static-top">
      <!-- Sidebar toggle button-->
      <a href="#" class="sidebar-toggle" data-toggle="push-menu" role="button">
        <span class="sr-only">Toggle navigation</span>
      </a>
      <span class="hed_title">Seller Management System</span>

      <div class="navbar-custom-menu">
        <ul class="nav navbar-nav">
          <li class="dropdown user user-menu">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
              <img src="<?php echo $seller_details['image']; ?>" class="user-image" alt="User Image">
              <span class="hidden-xs"><?php echo $seller_details['shop_name']; ?> <i class="fa fa-caret-down" aria-hidden="true"></i></span>
            </a>
            <ul class="dropdown-menu">
              <!-- User image -->
              <li class="user-header">
                <img src="<?php echo $seller_details['image']; ?>" class="img-circle" alt="User Image">

                <p>
                  <?php echo ucwords(strtolower($seller_details['shop_name'])); ?>
                  <small><?php echo "+91 ".$seller_details['phone'];  ?></small>
                </p>
              </li>
              
              <li class="user-footer">
                <div class="pull-left">
                  <a href="<?php echo base_url('profile'); ?>" class="btn btn-default btn-flat">Profile</a>
                </div>
                <div class="pull-right">
                  <a href="<?php echo base_url('logout'); ?>" class="btn btn-default btn-flat">Sign out</a>
                </div>
              </li>
            </ul>
          </li>
          
        </ul>
      </div>
    </nav>
  </header>

<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
<?php
if($success_message = $this->session->flashdata('success_message'))
{
  ?>
  <script type="text/javascript">
    swal({
            title: "Success!",
            text: "<?php echo $success_message; ?>",
            icon: "success",
            button: "Ok",
          });
  </script>

  <?php
}
?>
<?php
if($error_message = $this->session->flashdata('error_message'))
{
  ?>
  <script type="text/javascript">
    swal({
            title: "Failed!",
            text: "<?php echo $error_message; ?>",
            icon: "error",
            button: "Ok",
          });
  </script>

  <?php
}
?>
  