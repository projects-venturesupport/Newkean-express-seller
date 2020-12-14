<style type="text/css">
  .required_cls
  {
    color: red;
  }
</style>
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>My Product List</h1>
    </section>
    <!-- Main content -->
    <section class="content">
      <!-- Small boxes (Stat box) -->
      <div class="row">
        <div class="col-md-12">
          <!-- general form elements -->
          <div class="box box-primary">
            <div class="box-header with-border">
              <h3 class="box-title">Update Available Products</h3>
            </div>

            <div class="box-body">
              <form method="post" action="<?=base_url('seller/seller_product_update')?>" id="seller-product-form">
                <input type="hidden" name="p_seller_id" value="<?=$seller_details['id']?>">
              <div class="box-group" id="accordion">
                
                <?php

                /*echo "<pre>";
                print_r($product_by_category);
                echo "</pre>"; exit;*/
                $pc = 1;
                foreach($product_by_category as $product_by_category_row)
                {
                  ?>

                  <div class="panel box box-primary">
                  <div class="box-header with-border">
                    <h4 class="box-title">
                      <a data-toggle="collapse" data-parent="#accordion" href="#collapse<?=$product_by_category_row['id']?>">
                        <?=$product_by_category_row['title']?>
                      </a>
                    </h4>
                  </div>
                  <div id="collapse<?=$product_by_category_row['id']?>" class="panel-collapse collapse <?php if($pc == 1) { ?> in <?php } ?>">
                    <div class="box-body">

                      <?php 

                      if($product_by_category_row['product_list'] > 0)
                      {
                        ?>

                        <div class="box-body table-responsive no-padding">
                          <table class="table table-hover">
                            <tbody>

                              <tr>
                              <th>&nbsp;</th>
                              <th>ID</th>
                              <th>SKU</th>
                              <th>Product Name</th>
                              <th>Veriation</th>
                              <th>Status</th>                         
                            </tr>
                            <?php 
                            foreach($product_by_category_row['product_list'] as $product_row)
                            {
                              ?>

                              <tr>
                                <td>
                                  <input type="checkbox" id="pro_<?=$product_row['id']?>" class="" onchange="return product_check(<?=$product_row['id']?>);" <?php if(in_array($product_row['id'], $sellers_product_variation['product_ids'])){?> checked="checked" <?php } ?> >
                                </td>
                                <td><?=$product_row['id']?></td>
                                <td><?=$product_row['SKU']?></td>
                                <td><?=$product_row['name']?></td>
                                <td>
                                  <ul class="list-group list-group-unbordered"> 

                                    <?php 
                                    foreach($product_row['variation_list'] as $var_row)
                                    {
                                      ?>

                                      <li class="list-group-item">

                                        <input type="checkbox" name="var_ids[]" class="pro_<?=$product_row['id']?>" onchange="return main_product_check(<?=$product_row['id']?>);" value="<?=$var_row['id']?>" <?php if(in_array($var_row['id'], $sellers_product_variation['variation_ids'])){?> checked="checked" <?php } ?> >


                                        <?php 
                                        echo $var_row['title'];
                                        ?>

                                        <a class="pull-right"><i class="fa fa-rupee"></i><?php echo $var_row['sale_price']; ?></a>
                                      </li>

                                      <?php
                                    }
                                    ?>

                                  </ul>

                                  
                                </td>
                                <td>
                                  <?php
                                    if($product_row['status'] == 'Y')
                                    {
                                      echo "<label style='color:green'><b>Active<b></label>";
                                    }
                                    else
                                    {
                                      echo "<label style='color:red'>Inactive</label>";
                                    }
                                  ?>
                                </td>
                              </tr>

                              <?php
                            }

                            ?>
                            
                            
                          </tbody></table>
                        </div>

                        <?php
                      }

                      ?>
                    
                  </div>
                  </div>
                </div>

                  <?php
                  $pc++;

                  if(count($product_by_category_row['child']) > 0)
                  {
                    foreach($product_by_category_row['child'] as $product_by_category_row_child)
                    {
                      ?>

                      <div class="panel box box-primary">
                  <div class="box-header with-border">
                    <h4 class="box-title">
                      <a data-toggle="collapse" data-parent="#accordion" href="#collapse<?=$product_by_category_row['id']?>">
                        <?=$product_by_category_row_child['title']?>
                      </a>
                    </h4>
                  </div>
                  <div id="collapse<?=$product_by_category_row_child['id']?>" class="panel-collapse collapse <?php if($pc == 1) { ?> in <?php } ?>">
                    <div class="box-body">

                      <?php 

                      if($product_by_category_row_child['product_list'] > 0)
                      {
                        ?>

                        <div class="box-body table-responsive no-padding">
                          <table class="table table-hover">
                            <tbody>

                              <tr>
                              <th>&nbsp;</th>
                              <th>ID</th>
                              <th>SKU</th>
                              <th>Product Name</th>
                              <th>Veriation</th>
                              <th>Status</th>                         
                            </tr>
                            <?php 
                            foreach($product_by_category_row_child['product_list'] as $product_row)
                            {
                              ?>

                              <tr>
                                <td>
                                  <input type="checkbox" id="pro_<?=$product_row['id']?>" class="" onchange="return product_check(<?=$product_row['id']?>);" <?php if(in_array($product_row['id'], $sellers_product_variation['product_ids'])){?> checked="checked" <?php } ?> >
                                </td>
                                <td><?=$product_row['id']?></td>
                                <td><?=$product_row['SKU']?></td>
                                <td><?=$product_row['name']?></td>
                                <td>
                                  <ul class="list-group list-group-unbordered"> 

                                    <?php 
                                    foreach($product_row['variation_list'] as $var_row)
                                    {
                                      ?>

                                      <li class="list-group-item">

                                        <input type="checkbox" name="var_ids[]" class="pro_<?=$product_row['id']?>" onchange="return main_product_check(<?=$product_row['id']?>);"  <?php if(in_array($var_row['id'], $sellers_product_variation['variation_ids'])){?> checked="checked" <?php } ?> >


                                        <?php echo $var_row['title']; ?>

                                        <a class="pull-right"><i class="fa fa-rupee"></i><?php echo $var_row['sale_price']; ?></a>
                                      </li>

                                      <?php
                                    }
                                    ?>

                                  </ul>

                                  
                                </td>
                                <td>
                                  <?php
                                    if($product_row['status'] == 'Y')
                                    {
                                      echo "<label style='color:green'><b>Active<b></label>";
                                    }
                                    else
                                    {
                                      echo "<label style='color:red'>Inactive</label>";
                                    }
                                  ?>
                                </td>
                              </tr>

                              <?php
                            }

                            ?>
                            
                            
                          </tbody></table>
                        </div>

                        <?php
                      }

                      ?>
                    
                  </div>
                  </div>
                </div>

                      <?php
                    }
                  }
                }

                ?>


                <div class="box-footer">
                <button type="button" class="btn btn-primary pull-right" onclick="return update_seller_product();">Update Seller Products</button>
                <a href="<?php echo base_url('seller-list'); ?>"><button type="button" class="btn btn-default pull-left">Cancel</button></a>
              </div>

                
              
              
              </div>
            </form>
            </div>
           


          </div>

        </div>

      
      </div>
      

    </section>
    <!-- /.content -->
  </div>
  
  <script type="text/javascript">
    function product_check(id) {
  if($('#pro_'+id).is(":checked")){
    $(".pro_"+id).prop('checked', true);
  }else{
    $(".pro_"+id).prop('checked', false);
  }

  


  
}
function main_product_check(id)
  {

  }
function update_seller_product()
  {
     $("#seller-product-form").submit();
  }

</script>