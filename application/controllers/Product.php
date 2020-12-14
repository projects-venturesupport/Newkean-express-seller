<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Product extends CI_Controller {

	function __construct()
    {
        parent::__construct();
        $this->load->model('product_model');  
        $this->load->model('category_model');      
        $this->load->model('common_model');  
        $this->load->model('meta_data_model');   


    }

	//product List
	public function index()
	{
        // product list
        $header_data = array();
        $page_data = array();
        $left_data = array();
        $footer_data = array();

        $header_data['title'] = "Product List";
        $left_data['navigation'] = "product"; 
        $left_data['sub_navigation'] = "product-list"; 

        // check login or not 
        if($this->common_model->user_login_check())
        {
            // allow and get admin details
            $seller_details = $this->common_model->get_admin_user_details();
            $header_data['seller_details'] = $seller_details;
            $left_data['seller_details'] = $seller_details;
        }
        else
        {
            redirect(base_url(''));
        }

        if(isset($_REQUEST['filter']))
        {
            $filter_data = array("status" => $_REQUEST['status'], "cate1" => $_REQUEST['cate1'], "cate2" => $_REQUEST['cate2']);
        }
        else
        {
            $filter_data = array("status" => 'all', "cate1" => 0, "cate2" => 0);
        }


        $page_data['filter_data'] = $filter_data;

        $parent_category = $this->category_model->get_category_list_by_parent_id(0);
        $page_data['main_parent'] = $parent_category;

        // get product list
        $product_list = $this->product_model->get_product_list($filter_data);
        $page_data['product_list'] = $product_list;
        
        $this->load->view('includes/header_view', $header_data);
        $this->load->view('includes/left_view', $left_data);
        $this->load->view('product/list_view', $page_data);
        $this->load->view('includes/footer_view', $footer_data);
    }

    // product add page
    public function add()
    {
        $header_data = array();
        $page_data = array();
        $left_data = array();
        $footer_data = array();

        $header_data['title'] = "Add new product";
        $left_data['navigation'] = "product"; 
        $left_data['sub_navigation'] = "product-add"; 

        // check login or not 
        if($this->common_model->user_login_check())
        {
            // allow and get admin details
            $seller_details = $this->common_model->get_admin_user_details();
            $header_data['seller_details'] = $seller_details;
            $left_data['seller_details'] = $seller_details;
        }
        else
        {
            redirect(base_url(''));
        }

        // get all parent_category
        $parent_category = $this->category_model->get_category_list_by_parent_id(0);
        $page_data['main_parent'] = $parent_category;
        
        $this->load->view('includes/header_view', $header_data);
        $this->load->view('includes/left_view', $left_data);
        $this->load->view('product/add_view', $page_data);
        $this->load->view('includes/footer_view', $footer_data);
    }

    
    //product add submit
    function add_submit()
    {
        if($this->common_model->user_login_check())
        {
            // allow to access
        }
        else
        {
            redirect(base_url(''));
        }        

        if($this->input->post('product_form'))
        {            
            $form_data = array();
            if($this->input->post('cate2') > 0)
            {
               $category_id = $this->input->post('cate2');
            }
            else
            {
                $category_id = $this->input->post('cate1');
            }
            //$category_id = $this->input->post('cate3');
            $name = $this->input->post('name');
            $slug = $this->input->post('slug');
            $description = $this->input->post('description');
            $short_description = $this->input->post('short_description');
            $status = $this->input->post('status');

            $variation_title = $this->input->post('variation_title');
            $price = $this->input->post('price');
            $discount = $this->input->post('discount'); 

            $meta_title = $this->input->post("meta_title");
            $meta_description = $this->input->post("meta_description");
            $meta_keyword = $this->input->post("meta_keyword");


            if($this->input->post('ai_title') != null && $this->input->post('ai_value') != null)
            {
                $ai_title = $this->input->post('ai_title');
                $ai_value = $this->input->post('ai_value');
            }        
            else
            {
                $ai_title = array();
                $ai_value  = array();
            }

            if($_FILES['image']['name'] != '')
            {
                $upload_dir = FILE_UPLOAD_BASE_PATH.'uploads/product/';
                $rand_name = time()."-";
                $upload_file = $upload_dir.$rand_name.basename($_FILES['image']['name']);
                $actual_path = 'uploads/product/'.$rand_name.basename($_FILES['image']['name']);
                if (move_uploaded_file($_FILES['image']['tmp_name'], $upload_file))
                {
                   $image = $actual_path;
                }
                else
                {
                    $image = "uploads/default/no-image.png";
                }
            }
            else
            {
                $image = "uploads/default/no-image.png";
            }



            $form_data['category_id'] = $category_id;
            $form_data['image'] = $image;
            $form_data['title'] = $name;
            $form_data['slug'] = $slug;
            $form_data['description'] = $description;
            $form_data['short_description'] = $short_description;
            $form_data['status'] = $status;
            $form_data['variation_title'] = $variation_title;
            $form_data['price'] = $price;
            $form_data['discount'] = $discount;
            $form_data['ai_title'] = $ai_title;
            $form_data['ai_value'] = $ai_value;
            

            $add_data = $this->product_model->add_product($form_data);
            if($add_data['status'] == "Y")
            {
                $product_id = $add_data['product_id'];
                // add meta data
                $add_meta_data = array("product_id" => $product_id, "meta_title" => $meta_title, "meta_description" => $meta_description, "meta_keyword" => $meta_keyword);
                $add_meta = $this->meta_data_model->update_meta_data_for_product($add_meta_data);


                $this->session->set_flashdata('success_message', $add_data['message']);
                redirect(base_url('product'));
            }
            else
            {
                $this->session->set_flashdata('error_message', $add_data['message']);
                redirect(base_url('product'));
            }
        }
        else
        {
            redirect(base_url('product'));
        }

    }
    //category Edit page
    public function edit($id = 0)
    {        
        $header_data = array();
        $page_data = array();
        $left_data = array();
        $footer_data = array();

        $header_data['title'] = "Edit product";
        $left_data['navigation'] = "product"; 
        $left_data['sub_navigation'] = "product-list"; 

        // check login or not 
        if($this->common_model->user_login_check())
        {
            // allow and get admin details
            $seller_details = $this->common_model->get_admin_user_details();
            $header_data['seller_details'] = $seller_details;
            $left_data['seller_details'] = $seller_details;
        }
        else
        {
            redirect(base_url(''));
        }

        // get all parent_category
        $parent_category = $this->category_model->get_category_list_by_parent_id(0);
        $page_data['main_parent'] = $parent_category;

        $product_meta = $this->meta_data_model->get_product_meta_data_by_id($id);
        $page_data['product_meta'] = $product_meta;

        // product details
        $product_details = $this->product_model->get_product_details_by_id($id);
        $page_data['product_details'] = $product_details;


        
        $this->load->view('includes/header_view', $header_data);
        $this->load->view('includes/left_view', $left_data);
        $this->load->view('product/edit_view', $page_data);
        $this->load->view('includes/footer_view', $footer_data);
    }
    //category Update
    function edit_submit()
    {

        if($this->common_model->user_login_check())
        {
            // allow to access
        }
        else
        {
            redirect(base_url(''));
        }        

        if($this->input->post('product_form'))
        {            
            $form_data = array();
            if($this->input->post('cate2') > 0)
            {
               $category_id = $this->input->post('cate2');
            }
            else
            {
                $category_id = $this->input->post('cate1');
            }
            //$category_id = $this->input->post('cate3');
            $id = $this->input->post('product_id');
            $name = $this->input->post('name');
            $slug = $this->input->post('slug');
            $description = $this->input->post('description');
            $short_description = $this->input->post('short_description');
            $status = $this->input->post('status');

            $variation_id = $this->input->post('option_u_id');
            $variation_title = $this->input->post('variation_title');
            $price = $this->input->post('price');
            $discount = $this->input->post('discount');
            $variation_type = $this->input->post('option_type');

            $meta_title = $this->input->post("meta_title");
            $meta_description = $this->input->post("meta_description");
            $meta_keyword = $this->input->post("meta_keyword");


           if($this->input->post('ai_title') != null && $this->input->post('ai_value') != null)
            {
                $ai_type = $this->input->post('ai_type');
                $ai_title = $this->input->post('ai_title');
                $ai_value = $this->input->post('ai_value');
            }        
            else
            {
                $ai_type = array();
                $ai_title = array();
                $ai_value  = array();
            }

            $image = "";

            if($_FILES['image']['name'] != '')
            {
                $upload_dir = FILE_UPLOAD_BASE_PATH.'uploads/product/';
                $rand_name = time()."-";
                $upload_file = $upload_dir.$rand_name.basename($_FILES['image']['name']);
                $actual_path = 'uploads/product/'.$rand_name.basename($_FILES['image']['name']);
                if (move_uploaded_file($_FILES['image']['tmp_name'], $upload_file))
                {
                   $image = $actual_path;
                }
                
            }
            


            $form_data['id'] = $id;
            $form_data['category_id'] = $category_id;
            $form_data['image'] = $image;
            $form_data['title'] = $name;
            $form_data['slug'] = $slug;
            $form_data['description'] = $description;
            $form_data['short_description'] = $short_description;
            $form_data['status'] = $status;
            $form_data['variation_id'] = $variation_id;
            $form_data['variation_type'] = $variation_type;
            $form_data['variation_title'] = $variation_title;
            $form_data['price'] = $price;
            $form_data['discount'] = $discount;
            $form_data['ai_title'] = $ai_title;
            $form_data['ai_value'] = $ai_value;
            $form_data['ai_type'] = $ai_type;

            
            

            $update_data = $this->product_model->update_product($form_data);
            if($update_data['status'] == "Y")
            {
                
                 $product_id = $id;
                // add meta data
                $add_meta_data = array("product_id" => $product_id, "meta_title" => $meta_title, "meta_description" => $meta_description, "meta_keyword" => $meta_keyword);
                $add_meta = $this->meta_data_model->update_meta_data_for_product($add_meta_data);

                $this->session->set_flashdata('success_message', $update_data['message']);
                redirect(base_url('product'));
            }
            else
            {
                $this->session->set_flashdata('error_message', $update_data['message']);
                redirect(base_url('product'));
            }
        }
        else
        {
            redirect(base_url('product'));
        }
    
    }
    //Banner Delete
    function delete($id = 0)
    {
        if($this->common_model->user_login_check())
        {
            // allow to access
        }
        else
        {
            redirect(base_url(''));
        } 

        $delete_product = $this->product_model->delete_product_by_id($id);
        if($delete_product['status'] == "Y")
        {
            $this->session->set_flashdata('success_message', $delete_product['message']);
        }
        else
        {
            $this->session->set_flashdata('error_message', $delete_product['message']);
        }
        redirect(base_url('product'));

    }

    function ajax_get_category_list_by_parent_id()
    {
        $response = array("status" => "N", "message" => "Something was wrong");

        if($this->input->post('parent_id'))
        {
            $parent_id = $this->input->post('parent_id');
            $category_rows = $this->category_model->get_category_list_by_parent_id($parent_id);

            if(count($category_rows) > 0)
            {
                $html = '<option value="0">Select Child Category</option>';
                foreach($category_rows as $category_row)
                {
                    $html.= '<option value="'.$category_row["id"].'">'.$category_row["title"].'</option>';
                }
                $response = array("status" => "Y", "message" => "List Found.", "html" => $html);
            }
            else
            {
                $html = '<option value="0">Select Child Category</option>';
                $response = array("status" => "Y", "message" => "List Found.", "html" => $html);
            }

        }
        echo json_encode($response);
    }

    function ajax_get_product_slug()
    {
        $response = array("status" => "N", "slug" => "");
        if($this->input->post('name'))
        {
            $name = urldecode($this->input->post('name'));
            $slug = $this->common_model->slugify($name); 
            if($this->input->post('product_id'))
            {
                $product_id = $this->input->post('product_id');
            }
            else
            {
                $product_id = 0;
            }
            $slug_status = $this->product_model->check_slug_exist($slug, $product_id);
            if($slug_status == 'N')
            {
                $response = array("status" => "Y", "slug" => $slug);
            }
            else
            {
                $response = array("status" => "N", "slug" => $slug);
            }

        }
        echo json_encode($response);
    }

    function check_custom_slug()
    {
        $response = array("status" => "N", "slug" => "");
        if($this->input->post('slug'))
        {
            if($this->input->post('slug'))
            {
                $product_id = $this->input->post('product_id');
            }
            else
            {
                $product_id = 0;
            }

            $slug = urldecode($this->input->post('slug'));
            $slug_status = $this->product_model->check_slug_exist($slug, $product_id);
            if($slug_status == 'N')
            {
                $response = array("status" => "Y", "slug" => $slug);
            }
            else
            {
                $response = array("status" => "N", "slug" => $slug);
            }

        }
        echo json_encode($response);
    }

    function update_product_order()
    {
        if($this->common_model->user_login_check())
        {
            // allow to access
        }
        else
        {
            redirect(base_url(''));
        }

        $product_id = $this->input->post('id');
        $order_value = $this->input->post('order_value');

        $this->product_model->update_product_order($product_id, $order_value);

        echo "success";
    }

	
}
