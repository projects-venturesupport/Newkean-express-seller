<?php
  /*echo "<pre>";
  print_r($product_details);
  echo "</pre>"; exit;*/
?>
<style type="text/css">
  .required_cls
  {
    color: red;
  }
  .option_div
  {
    /* border: 2px solid #e2d6d6; */
  }
  .box{
    margin-bottom: 30px;
  }
  .box.box-solid{
    border: 1px solid #d2d6de;
  }
  .px-0{
    padding-left: 0;
    padding-right: 0;
  }
  .box-header>.fa {
    margin-right: 0;
    cursor: pointer;
  }
  .pt-2{
    padding-top: 1em;
  }
</style>
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>Edit Product</h1>
    </section>
    <!-- Main content -->
    <section class="content">
      <!-- Small boxes (Stat box) -->
      <div class="row">
        <div class="col-md-12">
          <!-- general form elements -->
          <div class="box box-primary">
            <div class="box-header with-border">
              <h3 class="box-title">Product Details</h3>
            </div>
            <!-- /.box-header -->
            <!-- form start --> 
            <form method="post" role="form" action="<?php echo base_url('product/edit_submit') ?>" id="product-form" enctype="multipart/form-data">
              <input type="hidden" name="product_form" value="1">
              <input type="hidden" name="product_id" id="product_id" value="<?=$product_details['id']?>">
              <div class="box-body">
                <div class="form-group col-md-12">

                  <center><img style="height: 300px; width: auto;" src="<?=$product_details['image_list'][0]['image']?>"></center>

                </div>

                <div class="form-group col-md-6">
                  <label for="cate1">Category<span class="required_cls">*</span></label>
                  <select name="cate1" id="cate1" class="form-control" onchange="return get_child_category(1);">
                    <option value="0">Select Category</option>
                    <?php
                    if(count($main_parent) > 0)
                    {
                      foreach($main_parent as $parent_row)
                      {
                        ?>
                        <option value="<?php echo $parent_row['id']; ?>" <?php if($product_details['category_history'][0]['id'] == $parent_row['id']) { ?> selected="selected" <?php } ?> ><?php echo $parent_row['title']; ?></option>
                        <?php
                      }
                    }
                    ?>
                  </select>
                </div>

                <div class="form-group col-md-6" id="sub_child_main_div_2" style="display: none;">
                  <label for="cate_2">Child Category<span class="required_cls">*</span></label>
                  <div id="sub_child_sub_div_2">
                  <select name="cate2" id="cate2" class="form-control">
                    <option value="0">Select</option>
                    
                  </select>
                </div>
                </div>


                

                <div class="clearfix"></div>
                

                <div class="form-group col-md-6">
                  <label for="name">Name<span class="required_cls">*</span></label>
                  <input type="text" class="form-control" id="name" name="name" placeholder="Product Name" onblur="return check_slug();" value="<?=$product_details['name']?>" >
                </div>

                <div class="form-group col-md-6">
                  <input type="hidden" id="slug_status" value="1">
                  <label for="slug">Slug<span class="required_cls">*</span></label>
                  <input type="text" class="form-control" id="slug" name="slug" placeholder="Category Slug" onblur="return check_custom_slug();" value="<?=$product_details['slug']?>" >
                </div>

                <div class="form-group col-md-6">
                  <label for="image">Image<span class="required_cls">*</span></label>
                  <input type="file" class="form-control" id="image" name="image" placeholder="Image" accept="image/*">
                </div>                

                <div class="form-group col-md-6">
                  <label for="status">Status</label>
                  <select class="form-control" name="status" id="status">
                    <option value="Y" <?php if($product_details['status'] == "Y") { ?> selected="selected" <?php } ?> >Active</option>
                    <option value="N" <?php if($product_details['status'] == "N") { ?> selected="selected" <?php } ?> >Inactive</option>
                  </select>
                </div>

                <div class="form-group col-md-12">
                  <label for="short_description" >Short Description<span class="required_cls">*</span></label>
                  <textarea name="short_description" id="short_description" class="form-control"><?=$product_details['short_description']?></textarea>
                </div>

                <div class="form-group col-md-12">
                  <label for="description" >Description<span class="required_cls">*</span></label>
                  <textarea name="description" id="description" class="form-control"><?=$product_details['description']?></textarea>
                </div>

                <div class="form-group col-md-12 px-0" id="ai_div">

                  <div class="col-md-6">
                    <label class="pt-2">Additional Information</label>
                  </div>

                  <div class="form-group col-md-6">
                    <button class="btn btn-primary pull-right" onclick="return new_ai();" type="button">Add Information</button>
                  </div>

                  <?php

                  if(count($product_details['additional_information_list']) > 0)
                  {
                    foreach($product_details['additional_information_list'] as $ai_row)
                    {
                      $ai_row_id = $ai_row['id'];
                      ?>

                      <div class="col-md-12 option_div" id="ai_div_<?=$ai_row_id?>">
                        <input type="hidden" class="ai_u_id" name="ai_u_id[]" value="<?=$ai_row_id?>">
                        <input type="hidden" name="ai_type[]" value="old">
                      <div class="box box-solid with-border">
                        <div class="box-header with-border">
                          <i class="fa fa-times-circle pull-right" style="color:red" onclick="return remove_ai(<?=$ai_row_id?>);" ></i>
                        </div>
                        <div class="box-body">
                          <div class="col-md-6 form-group">
                            <label>Title<span class="required_cls">*</span></label>
                            <input type="text" name="ai_title[]" class="form-control" id="ai_title_<?=$ai_row_id?>" value="<?=$ai_row['info_key']?>">
                          </div>
                          <div class="col-md-6 form-group">
                            <label>Value<span class="required_cls">*</span></label>
                            <input type="text" name="ai_value[]" class="form-control" id="ai_value_<?=$ai_row_id?>" value="<?=$ai_row['info_value']?>">
                          </div>
                        </div>
                      </div>
                    </div>

                      <?php
                    }
                  }
                  ?>
                </div>

                <div class="form-group col-md-12 px-0" id="variation_div">
                  <div class="col-md-6">
                    <label class="pt-2">Product Variation<span class="required_cls">*</span></label>
                  </div>
                  <div class="form-group col-md-6">
                    <button class="btn btn-primary pull-right" onclick="return new_veriation();" type="button">Add New Variation</button>
                  </div>
                  <br>

                  <?php
                  if(count($product_details['variation_list']) > 0)
                  {
                  foreach($product_details['variation_list'] as $option_row)
                  {
                    $option_id = $option_row['id'];
                  ?>

                  <div class="col-md-6 option_div" id="div_<?=$option_id?>">
                    <input type="hidden" class="option_u_id" name="option_u_id[]" value="<?=$option_id?>">
                    <input type="hidden" name="option_type[]" value="old">
                    <div class="box box-solid with-border">
                      <div class="box-header with-border">
                        <i class="fa fa-times-circle pull-right" style="color:red" onclick="return remove_option(<?=$option_id?>);" ></i>
                      </div>
                      <div class="box-body">
                        <div class="col-md-12 form-group">
                          <label>Variation Title<span class="required_cls">*</span></label>
                          <input type="text" name="variation_title[]" class="form-control" id="variation_title_<?=$option_id?>" value="<?=$option_row['title']?>">
                        </div>
                        <div class="col-md-6 form-group">
                          <label>Price<span class="required_cls">*</span></label>
                          <input type="number" name="price[]" class="form-control" id="price_<?=$option_id?>" value="<?=$option_row['price']?>">
                        </div>
                        <div class="col-md-6 form-group">
                          <label>Discount(%)</label>
                          <input type="number" name="discount[]" id="discount_<?=$option_id?>" class="form-control" value="<?=$option_row['discount_percent']?>">
                        </div>
                      </div>
                    </div>
                  </div>
                

                <?php
              }
              }
              ?>
              
              

              </div>

              



              </div>

              <div class="box-header with-border">
                        <h3 class="box-title">SEO/Meta Data Details</h3>
                    </div>

                    <div class="box-body">

                        <div class="form-group col-md-6">
                            <label for="meta_title">Meta Title</label>
                            <textarea name="meta_title" id="meta_title" class="form-control" placeholder="Enter Meta Title" rows="6"><?=$product_meta['meta_title']?></textarea>
                        </div>

                        <div class="form-group col-md-6">
                            <label for="meta_description">Description</label>
                            <textarea name="meta_description" id="meta_description" class="form-control" placeholder="Enter Meta description" rows="6"><?=$product_meta['meta_description']?></textarea>
                        </div>

                        <div class="form-group col-md-6">
                            <label for="meta_keyword">Meta Keywords</label>
                            <textarea name="meta_keyword" id="meta_keyword" class="form-control" placeholder="Enter Meta Keywords" rows="6"><?=$product_meta['meta_keyword']?></textarea>
                        </div>
                    </div>
              <!-- /.box-body -->

              <div class="box-footer">
                <button type="button" class="btn btn-primary pull-right" onclick="return form_submit();">Update Product</button>
                <a href="<?php echo base_url('product'); ?>"><button type="button" class="btn btn-default pull-left">Cancel</button></a>
              </div>
            </form>
          </div>

        </div>

      
      </div>
      

    </section>
    <!-- /.content -->
  </div>

  <script type="text/javascript">
    $(document).ready(function() 
    {
      get_child_category(1);

      

    });
  </script>
  
  <script type="text/javascript">

    //new_veriation();

    function remove_ai(u_id)
    {
      $( "#ai_div_"+u_id ).remove();
    }

    function remove_option(u_id)
    {
      $( "#div_"+u_id ).remove();
    }

    function new_ai()
    {
      var u_id = Math.round(new Date().getTime()/1000);

      var html_str = '<div class="col-md-12 option_div" id="ai_div_'+u_id+'"><input type="hidden" class="ai_u_id" name="ai_u_id[]" value="'+u_id+'"><input type="hidden" name="ai_type[]" value="new"><div class="box box-solid with-border"><div class="box-header with-border"><i class="fa fa-times-circle pull-right" style="color:red" onclick="return remove_ai('+u_id+');" ></i></div><div class="box-body"><div class="col-md-6 form-group"><label>Title<span class="required_cls">*</span></label><input type="text" name="ai_title[]" class="form-control" id="ai_title_'+u_id+'"></div><div class="col-md-6 form-group"><label>Value<span class="required_cls">*</span></label><input type="text" name="ai_value[]" class="form-control" id="ai_value_'+u_id+'"></div></div></div>';

      $( "#ai_div" ).append( html_str );




    }

    function new_veriation()
    {
      var u_id = Math.round(new Date().getTime()/1000);

      var html_str = '<div class="col-md-6 option_div" id="div_'+u_id+'"><input type="hidden" class="option_u_id" name="option_u_id[]" value="'+u_id+'"><input type="hidden" name="option_type[]" value="new"><div class="box box-solid with-border"><div class="box-header with-border"><i class="fa fa-times-circle pull-right" style="color:red" onclick="return remove_option('+u_id+');" ></i></div<div class="box-body"><div class="col-md-12 form-group"><label>Variation Title<span class="required_cls">*</span></label><input type="text" name="variation_title[]" class="form-control" id="variation_title_'+u_id+'"></div><div class="col-md-6 form-group"><label>Price<span class="required_cls">*</span></label><input type="number" name="price[]" class="form-control" id="price_'+u_id+'"></div><div class="col-md-6 form-group"><label>Discount(%)</label><input type="number" name="discount[]" id="discount_'+u_id+'" class="form-control"></div></div></div></div>';

      $( "#variation_div" ).append( html_str );




    }
    // parent change function
    function get_child_category(parent_level)
    {      
      var main_parent = document.getElementById("cate"+parent_level).value;
      
      if(main_parent > 0)
      {
        
        var dataString = 'parent_id=' + main_parent;

        $.ajax({
        type: "POST",
        url: "<?=base_url('product/ajax_get_category_list_by_parent_id')?>",
        data: dataString,
        cache: false,
        success: function(html) {          
          var obj = $.parseJSON(html);
          if(obj.status == 'Y')
          {
          if(parent_level == 1)    
          {
            $( "#sub_child_sub_div_2" ).empty();
            
            var row_html = '<select name="cate2" id="cate2" class="form-control">'+obj.html+'</select>';
            $( "#sub_child_sub_div_2" ).append( row_html );
            //$( "#sub_child_main_div_2").show();

            

            if(<?=$product_details['category_history'][0]['id']?> == main_parent)
            {
              $('#cate2').val(<?=$product_details['category_history'][1]['id']?>);
            }
            
            

          }
          else
          {
            // do nothing
          }
           
            
          }
          
        }
        });
        
      }
      else
      {  
      
        if(parent_level == 1)         
        {
          
          $( "#sub_child_sub_div_2" ).empty();
          $( "#sub_child_sub_div_2" ).append( '<select name="cate2" id="cate2" class="form-control"><option value="0">Select Child</option></select>' );
          $( "#sub_child_main_div_2" ).hide();


        }
        else
        {
          // do nothing
        }

        
            
      }

      return false;
    }

    // check slug
    function check_slug()
    {    
      
      var name = document.getElementById("name").value.trim();
      var product_id = document.getElementById("product_id").value;
      if(name != '')
      {
        var dataString = 'name=' + encodeURI(name) + '&product_id=' + product_id;

        $.ajax({
        type: "POST",
        url: "<?=base_url('product/ajax_get_product_slug')?>",
        data: dataString,
        cache: false,
        success: function(html) {          
          var obj = $.parseJSON(html);
          
          if(obj.status == 'Y')
          { 
            // avilable
            document.getElementById("slug_status").value = '1';    
            document.getElementById("slug").value = obj.slug;  
            $('#slug').removeClass('error_cls');
          }
          else
          {
            // not avilable
            document.getElementById("slug_status").value = '0';    
            document.getElementById("slug").value = obj.slug;
            $('#slug').addClass('error_cls');
          }
          
        }
        });
      }

      return false;
    }

    function check_custom_slug()
    {
      var slug = document.getElementById("slug").value.trim();
      var product_id = document.getElementById("product_id").value;
      if(slug != '')
      {
        var dataString = 'slug=' + encodeURI(slug) + '&product_id=' + product_id;
        $.ajax({
        type: "POST",
        url: "<?=base_url('product/check_custom_slug')?>",
        data: dataString,
        cache: false,
        success: function(html) {          
          var obj = $.parseJSON(html);
          if(obj.status == 'Y')
          { 
            // avilable
            document.getElementById("slug_status").value = '1';    
            document.getElementById("slug").value = obj.slug;  
            $('#slug').removeClass('error_cls');
          }
          else
          {
            // not avilable
            document.getElementById("slug_status").value = '0';    
            document.getElementById("slug").value = obj.slug;
            $('#slug').addClass('error_cls');
          }
          
        }
        });
      }
      else
      {
            document.getElementById("slug_status").value = '0';    
            document.getElementById("slug").value = obj.slug;
            $('#slug').addClass('error_cls');
      }

      return false;
    }
    

    function form_submit()
    {
      $('.form-control').removeClass('error_cls');
      var focusStatus = "N";

      var cate1 = document.getElementById("cate1").value;
      var cate2 = document.getElementById("cate2").value;
      //var cate3 = document.getElementById("cate3").value;

      
      var name = document.getElementById("name").value.trim();
      var slug = document.getElementById("slug").value.trim();
      var image = document.getElementById("image").value;
      var short_description = document.getElementById("short_description").value;
      var description = CKEDITOR.instances['description'].getData().replace(/<[^>]*>/gi, '').length;


      

      if(cate1 == '0')
      {
        $('#cate1').addClass('error_cls');
        if(focusStatus == 'N')
        {
            $('#cate1').focus();
            focusStatus = 'Y';
        }     
      }
      
      if(name == '')
      {
        $('#name').addClass('error_cls');
        if(focusStatus == 'N')
        {
            $('#name').focus();
            focusStatus = 'Y';
        }     
      }

      if(slug == '' || slug_status == '0')
      {
        $('#slug').addClass('error_cls');
        if(focusStatus == 'N')
        {
            $('#slug').focus();
            focusStatus = 'Y';
        }     
      }      

      
      if(short_description < 10 )
      {
        
        $('#cke_short_description').addClass('error_cls');
        if(focusStatus == 'N')
        {
            focusStatus = 'Y';
        }     
      }
      else
      {
        
        $('#cke_short_description').removeClass('error_cls');
      }

      if(description < 10)
      {
        $('#cke_description').addClass('error_cls');
        if(focusStatus == 'N')
        {
            focusStatus = 'Y';
        }     
      }
      else
      {
        $('#cke_description').removeClass('error_cls');
      }



      var ai_count = $('.ai_u_id').length;



      if(ai_count > 0)
      {

        $("input[name='ai_u_id[]']")
              .map(function(){


                var ai_u_id = $(this).val();
                var ai_title = document.getElementById("ai_title_"+ai_u_id).value;
                var ai_value = document.getElementById("ai_value_"+ai_u_id).value;
                  

                if(ai_title == '')
                {
                  $('#ai_title_'+ai_u_id).addClass('error_cls');
                  if(focusStatus == 'N')
                  {
                      $('#ai_title_'+ai_u_id).focus();
                      focusStatus = 'Y';
                  }  
                }

                if(ai_value == '')
                {
                  $('#ai_value_'+ai_u_id).addClass('error_cls');
                  if(focusStatus == 'N')
                  {
                      $('#ai_value_'+ai_u_id).focus();
                      focusStatus = 'Y';
                  }  
                }


              }).get();
      }


      var variation_count = $('.option_u_id').length;      
      
      if(variation_count == 0)
      {        
        focusStatus = 'Y';
        new_veriation();
        swal({
              title: "Required Variation",
              text: "You must add at least one variation for create new product.",
              icon: "error",
            });
        variation_count++;
      }

      if(variation_count > 0)
      {
        $("input[name='option_u_id[]']")
              .map(function(){
                var option_u_id = $(this).val();
                var op_title = document.getElementById("variation_title_"+option_u_id).value;
                var op_price = document.getElementById("price_"+option_u_id).value;
                var op_discount = document.getElementById("discount_"+option_u_id).value;

              
                if(op_title == '')
                {
                  $('#variation_title_'+option_u_id).addClass('error_cls');
                  if(focusStatus == 'N')
                  {
                      $('#variation_title_'+option_u_id).focus();
                      focusStatus = 'Y';
                  }  
                }

                if(op_price < 0 || op_price == 0 || op_price == '')
                {
                  $('#price_'+option_u_id).addClass('error_cls');
                  if(focusStatus == 'N')
                  {
                      $('#price_'+option_u_id).focus();
                      focusStatus = 'Y';
                  }  
                }

                if(op_discount < 0 || op_discount > 99)
                {
                  $('#discount_'+option_u_id).addClass('error_cls');
                  if(focusStatus == 'N')
                  {

                      $('#discount_'+option_u_id).focus();
                      focusStatus = 'Y';
                  }  
                }                
                

              }).get();
      }
              
        
          



      if(focusStatus == "N")
      {
        // no validation error.. now submit the form
        $("#product-form").submit();
        //alert('all right');
      }

      return false;
    }
  </script>


