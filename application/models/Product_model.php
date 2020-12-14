<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Product_model extends CI_Model
{

	function check_slug_exist($slug, $product_id = 0)
    {
        $this->db->select("id");
        $this->db->from("LP_product");
        $this->db->where("slug", $slug);
        $this->db->where("status !=", "D");
        if($product_id > 0)
        {
            $this->db->where("id !=", $product_id);
        }
        $query = $this->db->get();
        if($query->num_rows() > 0)
        {
            // exist / not avilable
            $status = "Y";
        }
        else
        {
            // avilable
            $status = "N";
        }
        return $status;
    }

    function add_product($data)
    {
        $seller_id = $this->session->userdata("seller_id");
        $category_id = $data['category_id'];
        $title = $data['title'];
        $slug = $data['slug'];
        $description = $data['description'];
        $short_description = $data['short_description'];
        $status = $data['status'];
        $image = $data['image'];
        $variation_title = $data['variation_title'];
        $price = $data['price'];
        $discount = $data['discount'];
        $ai_title = $data['ai_title'];
        $ai_value = $data['ai_value'];

        // check slug
        $slug_status = $this->check_slug_exist($slug, 0);

        if($slug_status == 'N')
        {
            $product_data = array("seller_id" => $seller_id,"SKU" => "P".time(), "slug" => $slug, "category_id" => $category_id, "title" => $title, "short_description" => $short_description, "description" => $description, "status" => $status, "created_date" => date("Y-m-d H:i:s"));
            $this->db->insert("LP_product", $product_data);
            $product_id = $this->db->insert_id();

            if($product_id > 0)
            {

                // insert image
                $img_insert_data = array("product_id" => $product_id, "image" => $image, "created_date" => date("Y-m-d H:i:s"));
                $this->db->insert("LP_product_image", $img_insert_data);

                


                $variation_count = count($variation_title);
                for($i = 0; $i < $variation_count; $i++)
                {
                    $var_title = $variation_title[$i];
                    $var_price = $price[$i];
                    $var_discount = $discount[$i];

                    $var_insert_data = array("product_id" => $product_id, "title" => $var_title, "price" => $var_price, "discount" =>  $var_discount, "created_date" => date("Y-m-d H:i:s"), "status" => "Y", "ord_by" => 0);
                    $this->db->insert("LP_product_variation", $var_insert_data);
                    $variation_id = $this->db->insert_id();
                    //insert seller data
                    $seller_insert_data = array("seller_id" => $seller_id, "product_id" => $product_id, "variation_id" => $variation_id, "created_date" => date("Y-m-d H:i:s"));
                    $this->db->insert("LP_seller_available_product_variation_list", $seller_insert_data);
                }

                $ai_count = count($ai_title);
                for($ai = 0; $ai < $ai_count; $ai++)
                {
                    $ai_title_str = $ai_title[$ai];
                    $ai_value_str = $ai_value[$ai];

                    $ai_data = array("product_id" => $product_id, "info_key" => $ai_title_str, "info_value" => $ai_value_str, "created_date" => date("Y-m-d H:i:s"));
                    $this->db->insert("LP_product_additional_information", $ai_data);

                }

                $response = array("status" => "Y", "message" => "New product successfully created.", "product_id" => $product_id);
            }
            else
            {
                $response = array("status" => "N", "message" => "Internal server error.");
            }

        }
        else
        {
            $response = array("status" => "N", "message" => "Product creation failed! Product slug already exist.");
        }

        return $response;


    }

    function update_product($data)
    {
        $id = $data['id'];
    	$category_id = $data['category_id'];
    	$title = $data['title'];
    	$slug = $data['slug'];
    	$description = $data['description'];
    	$short_description = $data['short_description'];
    	$status = $data['status'];
    	$image = $data['image'];
        $variation_id = $data['variation_id'];
        $variation_type = $data['variation_type'];
    	$variation_title = $data['variation_title'];
    	$price = $data['price'];
    	$discount = $data['discount'];
    	$ai_title = $data['ai_title'];
    	$ai_value = $data['ai_value'];

    	// check slug
    	$slug_status = $this->check_slug_exist($slug, $id);

    	if($slug_status == 'N')
    	{
    		$product_data = array("slug" => $slug, "category_id" => $category_id, "title" => $title, "short_description" => $short_description, "description" => $description, "status" => $status, "updated_date" => date("Y-m-d H:i:s"));
            $this->db->where("id", $id);
    		$this->db->update("LP_product", $product_data);
            $product_id = $id;
    		if($product_id > 0)
    		{
                if($image != '')
                {
                    // delete image
                    $this->db->where("product_id", $product_id);
                    $this->db->delete("LP_product_image");

                    // insert image
                    $img_insert_data = array("product_id" => $product_id, "image" => $image, "created_date" => date("Y-m-d H:i:s"));
                    $this->db->insert("LP_product_image", $img_insert_data);
                }  			


    			$variation_count = count($variation_title);

                $old_var = array();
	    		for($i = 0; $i < $variation_count; $i++)
	    		{
	    			$var_id = $variation_id[$i];
                    $var_type = $variation_type[$i];
                    $var_title = $variation_title[$i];
	    			$var_price = $price[$i];
	    			$var_discount = $discount[$i];

                    if($var_type == 'old')
                    {
                        
                        $var_update_data = array("title" => $var_title, "price" => $var_price, "discount" =>  $var_discount, "updated_date" => date("Y-m-d H:i:s"), "status" => "Y", "ord_by" => 0);
                        $this->db->where("id", $var_id);
                        $this->db->update("LP_product_variation", $var_update_data);
                        $old_var[] = $var_id;
                    }
                    else if($var_type == 'new')
                    {
                       
                        $var_insert_data = array("product_id" => $product_id, "title" => $var_title, "price" => $var_price, "discount" =>  $var_discount, "created_date" => date("Y-m-d H:i:s"), "status" => "Y", "ord_by" => 0);
                        $this->db->insert("LP_product_variation", $var_insert_data);
                        $var_insert_id = $this->db->insert_id();
                        $old_var[] = $var_insert_id;
                    }
                    else
                    {
                        
                        // do nothing
                    }

                    if(count($old_var) > 0)
                    {
                        $this->db->where("product_id", $product_id);
                        $this->db->where_not_in("id", $old_var);
                        $var_update_data = array("status" => "D", "updated_date" => date("Y-m-d H:i:s"));
                        $this->db->update("LP_product_variation", $var_update_data);
                    }
                    else
                    {
                        $this->db->where("product_id", $product_id);
                        $var_update_data = array("status" => "D", "updated_date" => date("Y-m-d H:i:s"));
                        $this->db->update("LP_product_variation", $var_update_data);
                    }

	    			
	    		}

                // delete ai
                $this->db->where("product_id", $product_id);
                $this->db->delete("LP_product_additional_information");

	    		$ai_count = count($ai_title);
	    		for($ai = 0; $ai < $ai_count; $ai++)
	    		{
	    			$ai_title_str = $ai_title[$ai];
	    			$ai_value_str = $ai_value[$ai];

	    			$ai_data = array("product_id" => $product_id, "info_key" => $ai_title_str, "info_value" => $ai_value_str, "created_date" => date("Y-m-d H:i:s"));
	    			$this->db->insert("LP_product_additional_information", $ai_data);
	    		}

	    		$response = array("status" => "Y", "message" => "Product successfully updated.");
    		}
    		else
    		{
    			$response = array("status" => "N", "message" => "Internal server error.");
    		}

    	}
    	else
    	{
    		$response = array("status" => "N", "message" => "Product update failed! Product slug already exist.");
    	}

    	return $response;


    }

 
    function get_product_list($filter = array("status" => "all", "cate1" => 0, "cate3" => 0))
    {
        $seller_id = $this->session->userdata("seller_id");
        // get category in
        $cate_in =array();

        if($filter['cate1'] > 0 && $filter['cate2'] > 0)
        {
            $cate_in[] = $filter['cate1'];
            $cate_in[] = $filter['cate2'];
        }
        else if($filter['cate1'] > 0 && $filter['cate2'] == 0)
        {
            $cate_in[] = $filter['cate1'];
            $this->db->select("id");
            $this->db->from("LP_product_category");
            $this->db->where("parent_id", $filter['cate1']);
            $this->db->where("status !=", "D");            
            $get_ch = $this->db->get();
            if($get_ch->num_rows() > 0)
            {
                foreach($get_ch->result() as $ch_row)
                {
                    $cate_in[] = $ch_row->id;
                }
            }
        }



    	$products = array();
    	$this->db->select("id");
    	$this->db->from("LP_product");
        $this->db->where("seller_id", $seller_id);
    	$this->db->where("status !=", "D");
    	if($filter['status'] != 'all')
    	{
    		$this->db->where("status", $filter['status']);
    	}
        if(count($cate_in) > 0)
        {
            $this->db->where_in("category_id", $cate_in);
        }
    	$this->db->order_by("id", "DESC");

    	$query = $this->db->get();
    	if($query->num_rows() > 0)
    	{
    		foreach($query->result() as $product_row)
    		{
    			$product_details = $this->get_product_details_by_id($product_row->id);
    			if(count($product_details) > 0)
    			{
    				$products[] = $product_details;
    			}

    		}
    	}

    	return $products;
    }


    function get_product_list_by_category_id($cate_id)
    {

        $products = array();
        $this->db->select("id");
        $this->db->from("LP_product");
        $this->db->where("status !=", "D");
        $this->db->where("category_id", $cate_id);        
        $this->db->order_by("id", "DESC");

        $query = $this->db->get();
        if($query->num_rows() > 0)
        {
            foreach($query->result() as $product_row)
            {
                $product_details = $this->get_product_details_by_id($product_row->id);
                if(count($product_details) > 0)
                {
                    $products[] = $product_details;
                }

            }
        }

        return $products;
    }

    function get_product_id_by_variation_id($variation_id)
    {
        $product_id = 0;

        $this->db->select("product_id");
        $this->db->from("LP_product_variation");
        $this->db->where("id", $variation_id);
        $query = $this->db->get();
        if($query->num_rows() > 0)
        {
            $row = $query->row();
            $product_id = $row->product_id;
        }

        return $product_id;
    }

    function get_product_details_by_id($product_id = 0)
    {
    	$details = array();


    	$products = array();
    	$this->db->select("*");
    	$this->db->from("LP_product");
    	$this->db->where("status !=", "D");
    	$this->db->where("id", $product_id);

    	$query = $this->db->get();
    	if($query->num_rows() > 0)
    	{
	    		$product_row = $query->row();
    			$category_details = $this->category_model->get_category_short_details_by_id($product_row->category_id);
    			$variation_list = $this->get_variation_list_by_product_id($product_row->id);

                $category_history = $this->category_model->get_parent_list_by_category_id($product_row->category_id);

    			$additional_information_list = $this->get_product_additional_information_list($product_row->id);

    			$image_list = $this->get_product_image_by_product_id($product_row->id);

    			$details = array("id" => $product_row->id, "name" => $product_row->title, "SKU" => $product_row->SKU, "image_list" => $image_list, "category_details" => $category_details, "category_history" => $category_history, "slug" => $product_row->slug, "short_description" => $product_row->short_description, "description" => $product_row->description, "status" => $product_row->status, "created_date" => $product_row->created_date, "updated_date" => $product_row->updated_date, "variation_list" => $variation_list, "additional_information_list" => $additional_information_list, "ord_by" => $product_row->ord_by);

    		
    	}

    	

    	return $details;
    }

    function get_variation_list_by_product_id($product_id = 0)
    {
    	$variation_list = array();

    	$this->db->select("*");
    	$this->db->from("LP_product_variation");
    	$this->db->where("product_id", $product_id);
    	$this->db->where("status !=", "D");
    	$this->db->order_by("ord_by", "ASC");
    	$query = $this->db->get();
    	if($query->num_rows() > 0)
    	{
    		foreach($query->result() as $var_row)
    		{
    			if($var_row->discount > 0)
    			{
    				$discount_amount = round($var_row->price * $var_row->discount / 100);   
    				$discount_amount = number_format($discount_amount, 2); 				
    			}
    			else
    			{
    				$discount_amount = number_format(0, 2);
    			}

    			$sale_price = $var_row->price - $discount_amount;
    			$sale_price = number_format($sale_price, 2); 

    			$variation_list[] = array("id" => $var_row->id, "title" => $var_row->title, "price" => $var_row->price, "discount_percent" => $var_row->discount, "discount_amount" => $discount_amount, "sale_price" => $sale_price, "created_date" => $var_row->created_date, "updated_date" => $var_row->updated_date, "status" => $var_row->status, "order" => $var_row->ord_by);
    		}
    	}

    	return $variation_list;
    }

    function get_product_additional_information_list($product_id = 0)
    {
    	$additional_information = array();

    	$this->db->select("*");
    	$this->db->from("LP_product_additional_information");
    	$this->db->where("product_id", $product_id);

    	$query = $this->db->get();
    	if($query->num_rows() > 0)
    	{
    		foreach($query->result() as $row)
    		{
    			$additional_information[] = array("id" => $row->id, "info_key" => $row->info_key, "info_value" => $row->info_value);
    		}
    	}

    	return $additional_information;
    }

    function get_product_image_by_product_id($product_id = 0)
    {
    	$list = array();

    	$this->db->select("*");
    	$this->db->from("LP_product_image");
    	$this->db->where("product_id", $product_id);
    	$query = $this->db->get();
    	if($query->num_rows() > 0)
    	{
    		foreach($query->result() as $row)
    		{
    			$list[] = array("id" => $row->id, "image" => FRONT_URL.$row->image);
    		}
    	}

    	return $list;
    }

    function delete_product_by_id($id = 0)
    {
        $this->db->select("id");
        $this->db->from("LP_product");
        $this->db->where("id", $id);
        $this->db->where("status !=", "D");
        $check_query = $this->db->get();
        if($check_query->num_rows() > 0)
        {
            $update_data = array("status" => "D", "updated_date" => date("Y-m-d H:i:s"));
            $this->db->where("id", $id);
            $this->db->update("LP_product", $update_data);
            $response = array("status" => "Y", "message" => "Product successfully deleted.");
        }
        else
        {
            $response = array("status" => "N", "message" => "Product already deleted or not found.");
        }

        return $response;

    }

    //////////////////------------------

    function get_product_name_by_id($product_id = 0)
    {
        $name = "";
        $this->db->select("title");
        $this->db->from("LP_product");
        $this->db->where("id", $product_id);
        $query = $this->db->get();
        if ($query->num_rows() > 0)
        {
            $row  = $query->row();
            $name = $row->title;
        }
        return $name;
    }

    

    function get_product_status_by_id($product_id = 0)
    {
        $status = "D";
        $this->db->select("status");
        $this->db->from("LP_product");
        $this->db->where("id", $product_id);
        $query = $this->db->get();
        if ($query->num_rows() > 0)
        {
            $row    = $query->row();
            $status = $row->status;
        }
        return $status;
    }

    function get_veriation_full_details_by_id($id = 0)
    {
        $details = array();
        $this->db->select("*");
        $this->db->from("LP_product_variation");
        $this->db->where("id", $id);
        $query = $this->db->get();
        if ($query->num_rows() > 0)
        {
            $row               = $query->row();
            $product_name      = $this->get_product_name_by_id($row->product_id);
            $product_image     = $this->get_product_image_by_product_id($row->product_id);
            $product_status    = $this->get_product_status_by_id($row->product_id);
            $product_details   = array(
                "id"   => $row->product_id,
                "name" => $product_name,
                "image" => $product_image,
                "status" => $product_status
            );
            $price             = $row->price;
            $discount          = $row->discount;
            $discount_amount   = $price * $discount / 100;
            $sale_price        = round($price - $discount_amount);
            //$sale_price        = number_format($sale_price, 2);
            $price_details     = array(
                "price" => $price,
                "discount_percent" => $discount,
                "discount_amount" => $discount_amount,
                "sale_price" => $sale_price
            );
            $variation_details = array(
                "id" => $row->id,
                "title" => $row->title,
                "price_details" => $price_details,
                "status" => $row->status
            );
            if ($product_status == "Y" && $row->status == "Y")
            {
                $availability_status = "Y";
            }
            else
            {
                $availability_status = "N";
            }
            $details = array(
                "variation_details" => $variation_details,
                "product_details" => $product_details,
                "availability_status" => $availability_status
            );
        }
        return $details;
    }

    function update_product_order($product_id, $order_value)
    {
        $update_data = array("ord_by" => $order_value);
        $this->db->where("id", $product_id);
        $this->db->update("LP_product", $update_data);
        return true;
    }

    
    
}

?>