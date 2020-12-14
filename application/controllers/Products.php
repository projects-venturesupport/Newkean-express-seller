<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Products extends CI_Controller {

	function __construct()
    {
        parent::__construct();
        
    }

	public function index()
	{
		$header_data = array();
		$page_data = array();
		$left_data = array();
		$footer_data = array();

		$header_data['title'] = "My Product List";
		$left_data['navigation'] = "products"; 
		$left_data['sub_navigation'] = "none"; 

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

		$seller_id = $this->session->userdata("seller_id");

		$category_list_tree_products = $this->category_model->category_list_tree_with_products(array("status" => "all"));
        $page_data['product_by_category'] = $category_list_tree_products;

        $sellers_product_variation = $this->seller_model->get_seller_products_availability_list($seller_id);
        $page_data['sellers_product_variation'] = $sellers_product_variation;		

		$this->load->view('includes/header_view', $header_data);
		$this->load->view('includes/left_view', $left_data);
		$this->load->view('products_view', $page_data);
		$this->load->view('includes/footer_view', $footer_data);
	}
}
