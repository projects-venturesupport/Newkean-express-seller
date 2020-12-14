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

<?php
  if(isset($_REQUEST['filter']))
  {
    $filter_status = 1;
  }
  else
  {
    $filter_status = 0;
  }
?>

<!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>Product List</h1>
    </section>
    <!-- Main content -->
    <section class="content">
      <div class="row">
        <div class="col-xs-12">
          <div class="box">
            <div class="box-body">
              <form method="get" action="" id="filter_form">
                <input type="hidden" name="filter" value="true">

                <div class="form-group col-md-3">
                  <label for="official_email">Filter by Category </label>
                <select name="cate1" id="cate1" class="form-control" onchange="return get_child_category(1);">
                    <option value="0">All Category</option>
                    <?php
                    if(count($main_parent) > 0)
                    {
                      foreach($main_parent as $parent_row)
                      {
                        ?>
                        <option value="<?php echo $parent_row['id']; ?>" <?php if($filter_data['cate1'] == $parent_row['id']) { ?> selected="selected" <?php } ?> ><?php echo $parent_row['title']; ?></option>
                        <?php
                      }
                    }
                    ?>
                  </select>

                </div>

                <div class="form-group col-md-3" id="sub_child_main_div_2" <?php if($filter_data['cate1'] == 0) { ?> style="display:none;" <?php } ?> >
                  <label for="cate_2">Child Category</label>
                  <div id="sub_child_sub_div_2">
                  <select name="cate2" id="cate2" class="form-control">
                    <option value="0">Select</option>
                    
                  </select>
                </div>
                </div>
              
                <div class="form-group col-md-2">
                  <label for="official_email">Filter by Status </label>
                  <select name="status" id="status" class="form-control">
                    <option value="all" <?php if($filter_data['status'] == 'all') { ?> selected <?php } ?>>All</option>
                    <option value="Y" <?php if($filter_data['status'] == 'Y') { ?> selected <?php } ?>>Active</option>
                    <option value="N" <?php if($filter_data['status'] == 'N') { ?> selected <?php } ?>>Inactive</option>
                  </select>
                </div>

                <div class="form-group col-md-2">
                  <button type="button" class="btn btn-block btn-primary reset_btn" onclick="return form_submit();">Filter</button>
                </div>

                <div class="form-group col-md-2" <?php if($filter_status == 0) { ?> style="display: none;" <?php } ?> >
                  <a href="<?php echo base_url('product'); ?>"><button type="button" class="btn btn-block btn-primary reset_btn">Reset</button></a>
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
                      <th style="display: none;">Sl. No</th>
                          <th style="width: 20%">Name</th>
                          <th style="width: 10%">SKU</th>
                          <th style="width: 10%">Image</th>
                          <th style="width: 10%">Variation</th>
                          <th style="width: 10%">Category</th>
                          <th style="width: 10%">Date</th>
                          <th style="width: 10%">Status</th>
                          <th style="width: 10%">Order</th>
                          <th style="width: 10%">Action</th>
                        
                      </tr>
                </thead>
                <tbody> 
                <?php
               
                  if(count($product_list) > 0)
                  {
                    $rc = 0;
                    foreach($product_list as $product_row)
                    {
                      
                      ?>
                      <tr>
                        <td style="display: none;"><?=$rc?></td>
                        <td style="width: 20%">
                          <?=$product_row['name']?>
                        </td>
                        <td style="width: 10%">
                          <?=$product_row['SKU']?>
                        </td>
                        <td style="width: 10%">
                          <img style="height: 100px; width: 100px; object-fit: cover;" src="<?=$product_row['image_list'][0]['image']?>" class="img-responsive">
                        </td>

                        <td style="width: 10%">

                          <ul class="list-group list-group-unbordered">                           
                          <?php
                          foreach($product_row['variation_list'] as $variation)
                          {
                            ?>
                            <li class="list-group-item">
                            <b><?=$variation['title']?></b>
                            <a class="pull-right"><i class="fa fa-rupee"></i><?=$variation['sale_price']?></a></li>
                            <?php
                          }                           
                          ?>

                          </ul>
                        </td>

                        <td style="width: 10%">
                          <?=$product_row['category_details']['title']?>
                        </td>
                        <td style="width: 10%">
                          <?php
                          echo "<b>Published</b><br>".date("d/m/y H:i", strtotime($product_row['created_date']));
                          if($product_row['updated_date'] != NULL)
                          {
                            echo "<br><b>Last Updated</b><br>".date("d/m/y H:i", strtotime($product_row['updated_date']));
                          }
                          else
                          {
                            echo "<br><b>Last Updated</b> Never";
                          }
                          ?>                            
                          </td>
                                                
                        <td style="width: 10%">
                          <?php
                          if($product_row['status'] == 'Y')
                          {
                            ?>
                            <center><span style="color:green"><b>Active</b></span></center>
                            <?php
                          }
                          else
                          {
                            ?>
                            <center><span style="color:red"><b>Inactive</b></span></center>
                            <?php
                          }
                          ?>
                        </td>

                        <th style="width: 10%"><input type="number" id="ord_<?=$product_row['id']?>" class="form-control" style="width: 80px;" onblur="return order_change(<?=$product_row['id']?>);" value="<?=$product_row['ord_by']?>"></th>

                        <td style="width: 10%">
                          <a href="<?php echo base_url('product/delete/'.$product_row['id']); ?>" onclick="return confirm('Are you sure want to delete this product?')">
                            <button type="button" class="btn bg-red btn-sm pull-right sl_margin" title="Delete"><i class="fa fa-trash"></i>
                            </button>
                          </a>

                          <a href="<?php echo base_url('product/edit/'.$product_row['id']); ?>">
                            <button type="button" class="btn bg-yellow btn-sm pull-right sl_margin" title="Edit Details"><i class="fa fa-edit"></i>
                            </button>
                          </a>

                        

                        </td>
                          
                        </tr>
                      <?php
                      $rc++;
                    }
                  }
                ?>
                </tbody>
                
              </table>
            </div>
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

  <script type="text/javascript">
    function order_change(id)
    {
      var order_value = document.getElementById("ord_"+id).value;
      if(order_value == '' || order_value < 0)
      {
        document.getElementById("ord_"+id).value = '0';
        order_value = 0;
      }
      var dataString = 'id=' + id + '&order_value=' + order_value;

        $.ajax({
        type: "POST",
        url: "<?=base_url('product/update_product_order')?>",
        data: dataString,
        cache: false,
        success: function(html) {          
          //alert('success');
          
        }
        });

    }
  </script>

  <?php
 if($filter_data['cate1'] > 0)
 {
  ?>

  <script type="text/javascript">
    $(document).ready(function() 
    {
      get_child_category(1);

    });
  </script>
  <?php
 } 
?>
  <!-- /.content-wrapper -->
<script type="text/javascript">

  function select_cate_2() {
    $("#cate2").val(<?=$filter_data['cate2']?>);
    }
    function form_submit()
    {
      $("#filter_form").submit();
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
            $( "#sub_child_main_div_2").show();

            <?php
            if($filter_data['cate2'] > 0)
            {
              ?>
              if(main_parent == <?=$filter_data['cate1']?>)
              {
                select_cate_2();
              }
              
              <?php
            }

            ?>
            
            

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
  </script>