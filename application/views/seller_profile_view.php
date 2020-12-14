<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<style type="text/css">
  .required_cls
  {
    color:red;
  }
</style>
<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
<?php
if(isset($_REQUEST['error-message']))
{
  ?>
  <script type="text/javascript">
    swal({
            title: "Opps!",
            text: "<?php echo $_REQUEST['error-message']; ?>",
            icon: "error",
            button: "Ok",
          });
  </script>

  <?php
}
?>
<?php
if(isset($_REQUEST['success-message']))
{
  ?>
  <script type="text/javascript">
    swal({
            title: "Success!",
            text: "<?php echo $_REQUEST['success-message']; ?>",
            icon: "success",
            button: "Ok",
          });
  </script>
  <?php
}
?>
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        My Profile
      </h1>
      
    </section>

    <!-- Main content -->
    <section class="content">
      <!-- Small boxes (Stat box) -->
      <div class="row">
        <div class="col-md-3">

          <!-- Profile Image -->
          <div class="box box-primary">
            <div class="box-body box-profile">
              <img class="img-responsive" src="<?=$seller_details['image']?>" alt="User profile picture">

              <h3 class="profile-username text-center"><?=$seller_details['shop_name']?></h3>

              <p class="text-muted text-center"><?=ucwords(strtolower(SITE_TITLE." Seller"))?> </p>
              <?php
              $completed_order_count = $this->dashboard_model->get_completed_order_count();
              ?>
              <ul class="list-group list-group-unbordered">
                <li class="list-group-item">
                  <b>Completed Order</b> <a class="pull-right"><?=$completed_order_count?></a>
                </li>
                
                
              </ul>

              <a href="<?=base_url('order')?>" class="btn btn-primary btn-block"><b>View Order Details</b></a>
            </div>
            <div class="box-body">
              <strong><i class="fa fa-map-marker margin-r-5"></i> Location</strong>

              <p class="text-muted"><?=$seller_details['shop_address']?></p>
            </div>
            <!-- /.box-body -->
          </div>

        </div>
        <div class="col-md-9">
          <!-- general form elements -->
          <div class="box box-primary">
            <div class="box-header with-border">
              <h3 class="box-title">Update Profile Info</h3>
            </div>
            <!-- /.box-header -->
            <!-- form start -->
            <form method="post" role="form" action="<?php echo base_url('seller/edit_submit') ?>" id="seller-form" enctype="multipart/form-data">
              <input type="hidden" name="banner_form" value="1">
              <div class="box-body">               
                <input type="hidden" name="seller_id" id="seller_id" value="<?=$seller_details['id']?>">
                
                <div class="form-group col-md-6">
                  <label for="username">Username<span class="required_cls">*</span>&nbsp;&nbsp;<span id="username_report"></span></label>
                  <input type="text" class="form-control" id="username" name="username" placeholder="Username" maxlength="100" onkeyup="return seller_user_name_check();" value="<?=$seller_details['username']?>" readonly>
                  
                  <input type="hidden" name="username_status" id="username_status" value="1">
                </div>

                <div class="form-group col-md-6">
                  <label for="shop_name">Shop Name<span class="required_cls">*</span></label>
                  <input type="text" class="form-control" id="shop_name" name="shop_name" placeholder="Shop Name"  maxlength="200" value="<?=$seller_details['shop_name']?>" >
                </div>

                <div class="form-group col-md-12">
                  <label for="shop_address">Shop Address<span class="required_cls">*</span></label>
                  <input type="text" class="form-control" id="shop_address" name="shop_address" placeholder="Shop Name"  maxlength="200" value="<?=$seller_details['shop_address']?>" >
                </div>

                <div class="form-group col-md-6">
                  <label for="first_name">First Name<span class="required_cls">*</span></label>
                  <input type="text" class="form-control" id="first_name" name="first_name" placeholder="First Name" maxlength="100" value="<?=$seller_details['first_name']?>" >
                </div>

                <div class="form-group col-md-6">
                  <label for="last_name">Last Name<span class="required_cls">*</span></label>
                  <input type="text" class="form-control" id="last_name" name="last_name" placeholder="Last Name" maxlength="100" value="<?=$seller_details['last_name']?>">
                </div>

                <div class="form-group col-md-6">
                  <label for="image">Shop Image</label>
                  <input type="file" class="form-control" id="image" name="image" placeholder="Image" accept="image/*">
                </div>

                <div class="form-group col-md-6">
                  <label for="email">Email<span class="required_cls">*</span></label>
                  <input type="text" class="form-control" id="email" name="email" placeholder="Email Id" maxlength="100" value="<?=$seller_details['email']?>">
                </div>

                <div class="form-group col-md-6">
                  <input type="hidden" id="prev_phone" value="<?=$seller_details['phone']?>">
                  <label for="phone">Phone<span class="required_cls">*</span>&nbsp;&nbsp;<span id="phone_report"></span></label>
                  <input type="text" class="form-control" id="phone" name="phone" placeholder="10 Digit Phone No" maxlength="10" onkeyup="return phone_no_check();" value="<?=$seller_details['phone']?>">
                  <input type="hidden" name="phone_status" id="phone_status" value="1">
                </div>

                <div class="form-group col-md-6">
                  <label for="alternative_phone">Alternative Phone</label>
                  <input type="text" class="form-control" id="alternative_phone" name="alternative_phone" placeholder="10 Digit Phone No" maxlength="10" value="<?=$seller_details['alternative_phone']?>">
                </div>               

                <div class="form-group col-md-6">
                  <div id="verify_btn_div" style="display:none">
                  <button type="button" class="btn btn-primary pull-left" onclick="return send_otp_submit();" id="send_otp_btn">Verify</button>
                  <button type="button" class="btn btn-primary pull-right" onclick="return cancel_phone_change();" id="">Cancel</button>
                </div>
                <div class="clearfix"></div><br>
                <div id="otp_verify_div" style="display:none">
                  <label for="alternative_phone">Enter OTP<span class="required_cls">*</span></label>
                  <div class="row">
                  <div class="col-md-6">
                  <input type="text" class="form-control" id="phone_otp" name="phone_otp" placeholder="OTP" maxlength="4" value="" >
                  <span style="color:red" id="verify_message"></span>
                </div>
                <div class="col-md-6">
                  <button type="button" class="btn btn-primary pull-left" onclick="return verify_otp();" id="verify_otp_btn">Verify OTP</button>
                </div>
              </div>
                </div>
                </div>
                
                </form>
                

                
              </div>
              <!-- /.box-body -->

              <div class="box-footer">
                <button type="button" class="btn btn-primary pull-right" onclick="return update_seller_submit();" id="profile_btn">Update</button>
                
              </div>

                <div class="box-header with-border">
              <h3 class="box-title">Change Password</h3>
            </div>
            <!-- /.box-header -->
            <!-- form start -->
            <form method="post" action="<?php echo base_url('seller/password_update_submit') ?>" role="form" id="password-form">
              <div class="box-body">
                <div class="form-group col-md-6">
                  <label for="password">New Password</label>
                  <input type="password" class="form-control" id="password" name="password" placeholder="New Password">
                </div>
                <div class="form-group col-md-6">
                  <label for="confirm_password">Confirm New Password</label>
                  <input type="password" class="form-control" id="confirm_password" name="confirm_password" placeholder="Confirm New Password">
                </div>
              </div>
              <!-- /.box-body -->

              <div class="box-footer">
                <button type="button" class="btn btn-primary pull-right" onclick="return password_update_submit();">Update Password</button>
              </div>
            </form>
          </div>
            </form>
          
          </div>

        </div>

        
      

    </section>
    <!-- /.content -->
  </div>
  <script src="http://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
  <script type="text/javascript">
    function product_check(id) {
  if($('#pro_'+id).is(":checked")){
    $(".pro_"+id).prop('checked', true);
  }else{
    $(".pro_"+id).prop('checked', false);
  }

  


  
}

function cancel_phone_change()
{
  $('#verify_btn_div').hide();
  $('#otp_verify_div').hide();  
  $('#phone_status').val("1"); 
  var prev_phone = document.getElementById("prev_phone").value.trim(); 
  $('#phone').val(prev_phone); 
  $( "#profile_btn" ).prop( "disabled", false);
  $("#send_otp_btn").html('Verify');
}

function send_otp_submit()
{
  var focusStatus = "N";
  var phone = document.getElementById("phone").value.trim();

   if(phone == '' || phone.length != 10 || isNaN(phone))
      {
        $('#phone').addClass('error_cls');
        if(focusStatus == 'N')
        {
            $('#phone').focus();
            focusStatus = 'Y';
        }     
      }

      if(focusStatus == "N")
      {
        $( "#send_otp_btn" ).prop( "disabled", true);
        var dataString = 'phone=' + phone;
    $.ajax({
        type: "POST",
        url: "<?=base_url('seller/send_phone_otp')?>",
        data: dataString,
        cache: false,
        success: function(html) {
          var obj = $.parseJSON(html);
          $( "#send_otp_btn" ).prop( "disabled", false );
          $("#send_otp_btn").html('Resend OTP');
          $("#otp_verify_div").show();


          

        }
        });
      }

      return false;

}
function verify_otp()
{
  
  $("#verify_message").text('');
  var focusStatus = "N";
  var phone = document.getElementById("phone").value.trim();
  var phone_otp = document.getElementById("phone_otp").value.trim();

   if(phone == '' || phone.length != 10 || isNaN(phone))
      {
        $('#phone').addClass('error_cls');
        if(focusStatus == 'N')
        {
            $('#phone').focus();
            focusStatus = 'Y';
        }     
      }

      if(phone_otp == '' || phone_otp.length != 4 || isNaN(phone_otp))
      {
        $('#phone_otp').addClass('error_cls');
        if(focusStatus == 'N')
        {
            $('#phone_otp').focus();
            focusStatus = 'Y';
        }     
      }

      if(focusStatus == "N")
      {
        $( "#verify_otp_btn" ).prop( "disabled", true);
        var dataString = 'phone=' + phone + '&otp=' + phone_otp;
    $.ajax({
        type: "POST",
        url: "<?=base_url('seller/phone_verify_otp')?>",
        data: dataString,
        cache: false,
        success: function(html) {
          var obj = $.parseJSON(html);
          $( "#verify_otp_btn" ).prop( "disabled", false );

          if(obj.status == "Y")
          {
            $("#verify_btn_div").hide();
            $("#otp_verify_div").hide();
            $("#phone_status").val('1');
            $( "#profile_btn" ).prop( "disabled", false );
            $('#prev_phone').val(phone); 
            
          }
          else
          {
            $("#verify_message").text(obj.message);
          }
          

          

        }
        });
      }

      return false;
}
function main_product_check(id)
  {


  }
function update_seller_product()
  {
     $("#seller-product-form").submit();
     //alert('working');
  }
    function phone_no_check()
    {
      $('#verify_btn_div').hide();
      $('#otp_verify_div').hide();  

          
      $('#phone_report').text('');
      var phone = document.getElementById("phone").value.trim();
      var prev_phone = document.getElementById("prev_phone").value.trim();
      
      if(phone.length == 10)
      {

        if(prev_phone != phone)
        {
          var dataString = 'phone=' + phone + '&seller_id=<?=$seller_details["id"]?>';

        $.ajax({
        type: "POST",
        url: "<?=base_url('seller/phone_availability_check')?>",
        data: dataString,
        cache: false,
        success: function(html) {
          var obj = $.parseJSON(html);
          $( "#profile_btn" ).prop( "disabled", true );
          if(obj.status == 'Y')
          {
            $('#verify_btn_div').show();
            $('#phone_report').text("("+obj.message+")");
            document.getElementById("phone_status").value = 1;
            $('#phone_report').css('color', 'green'); 
            
          }
          else if(obj.status == 'N')
          {
            $('#phone_report').text("("+obj.message+")");
            document.getElementById("phone_status").value = 0;
            $('#phone_report').css('color', 'red');
          }
        }
        });

        }
        else
        {
          $( "#profile_btn" ).prop( "disabled", false);
          document.getElementById("phone_status").value = 1;
        }
        
      }
      else
      {
        document.getElementById("phone_status").value = 0;
      }

      return false;

    }


    function seller_user_name_check()
    {
      $('#username_report').text('');
      var username = document.getElementById("username").value.trim();
      if(username.length > 3)
      {
        var dataString = 'username=' + encodeURIComponent(username) + '&seller_id=<?=$seller_details["id"]?>';

        $.ajax({
        type: "POST",
        url: "<?=base_url('seller/user_name_availability_check')?>",
        data: dataString,
        cache: false,
        success: function(html) {
          var obj = $.parseJSON(html);
          if(obj.status == 'Y')
          {
            $('#username_report').text("("+obj.message+")");
            document.getElementById("username_status").value = 1;
            $('#username_report').css('color', 'green');
          }
          else if(obj.status == 'N')
          {
            $('#username_report').text("("+obj.message+")");
            document.getElementById("username_status").value = 0;
            $('#username_report').css('color', 'red');
          }
        }
        });
      }
      else
      {
        document.getElementById("username_status").value = 0;
      }

      return false;
    }
    // date check start
    function date_check(date_is)
    {
      return moment(date_is, 'DD/MM/YYYY',true).isValid();
    }
    // date check end

    // email check function start
    function validateEmail(email) {
        var re = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
        return re.test(String(email).toLowerCase());
    }
    // email check function end

    function update_seller_submit()
    {
      $('.form-control').removeClass('error_cls');
      var focusStatus = "N";

      
      var username = document.getElementById("username").value.trim();
      var username_status = document.getElementById("username_status").value;
      var shop_name = document.getElementById("shop_name").value.trim();
      var shop_address = document.getElementById("shop_address").value.trim();
      var first_name = document.getElementById("first_name").value.trim();
      var last_name = document.getElementById("last_name").value.trim();
      var email = document.getElementById("email").value.trim();
      var email_check = validateEmail(email);
      var phone = document.getElementById("phone").value.trim();
      var phone_status = document.getElementById("phone_status").value.trim();
      
      var alternative_phone = document.getElementById("alternative_phone").value.trim();      
      var image = document.getElementById("image").value;
      
      
      if(username.length < 3 || username_status == "0")
      {
        $('#username').addClass('error_cls');
        if(focusStatus == 'N')
        {
            $('#username').focus();
            focusStatus = 'Y';
        }     
      }

      if(shop_name == '')
      {
        $('#shop_name').addClass('error_cls');
        if(focusStatus == 'N')
        {
            $('#shop_name').focus();
            focusStatus = 'Y';
        }     
      }

      if(shop_address == '')
      {
        $('#shop_address').addClass('error_cls');
        if(focusStatus == 'N')
        {
            $('#shop_address').focus();
            focusStatus = 'Y';
        }     
      }

      if(first_name == '')
      {
        $('#first_name').addClass('error_cls');
        if(focusStatus == 'N')
        {
            $('#first_name').focus();
            focusStatus = 'Y';
        }     
      }

      if(last_name == '')
      {
        $('#last_name').addClass('error_cls');
        if(focusStatus == 'N')
        {
            $('#last_name').focus();
            focusStatus = 'Y';
        }     
      }

      if(email == '' || email_check == false)
      {
        $('#email').addClass('error_cls');
        if(focusStatus == 'N')
        {
            $('#email').focus();
            focusStatus = 'Y';
        }     
      }

      if(phone == '' || phone.length != 10 || isNaN(phone) || phone_status != "1")
      {
        $('#phone').addClass('error_cls');
        if(focusStatus == 'N')
        {
            $('#phone').focus();
            focusStatus = 'Y';
        }     
      }

      if(alternative_phone != "")
      {
        if(alternative_phone.length != 10 || isNaN(alternative_phone))
        {
          $('#alternative_phone').addClass('error_cls');
          if(focusStatus == 'N')
          {
              $('#alternative_phone').focus();
              focusStatus = 'Y';
          }     
        }
      }    

     
      if(focusStatus == "N")
      {
        // no validation error.. now submit the form
        $("#seller-form").submit();
      }

      return false;
    } 

    function password_update_submit()
    {
      $('.form-control').removeClass('error_cls');

      var password = document.getElementById("password").value.trim();
      var confirm_password = document.getElementById("confirm_password").value.trim();
      var focusStatus = "N";

      if(password == '' || password.length < 4)
      {
        $('#password').addClass('error_cls');
        if(focusStatus == 'N')
        {
            $('#password').focus();
            focusStatus = 'Y';
        }     
      }

      if(confirm_password == '' || password != confirm_password)
      {
        $('#confirm_password').addClass('error_cls');
        if(focusStatus == 'N')
        {
            $('#confirm_password').focus();
            focusStatus = 'Y';
        }     
      }

      if(focusStatus == 'N')
      {
        $("#password-form").submit();
      }

      return false;


    }

    
  </script>