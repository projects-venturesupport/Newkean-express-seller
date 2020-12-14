<?php
defined('BASEPATH') OR exit('No direct script access allowed'); 

class Dashboard extends CI_Controller {

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

		$header_data['title'] = "Welcome to seller dashboard";
		$left_data['navigation'] = "dashboard"; 
		$left_data['sub_navigation'] = "none"; 

		// check login or not 
		if($this->common_model->user_login_check())
		{
			// allow and get admin details
			$seller_details = $this->common_model->get_admin_user_details();
			$header_data['seller_details'] = $seller_details;
			$left_data['seller_details'] = $seller_details;
			$page_data['seller_details'] = $seller_details;

			if(isset($_GET['order-search']))
			{
				$order_filter = $_GET['order-search'];
			}
			else
			{
				$order_filter = 'todays';
			}


			// get order counter
			$dashboard_counter = $this->dashboard_model->dashboard_order_counter($order_filter);
			$page_data['order_counter'] = $dashboard_counter;
			$page_data['order_filter'] = $order_filter;


		}
		else
		{
			redirect(base_url(''));
		}		

		$this->load->view('includes/header_view', $header_data);
		$this->load->view('includes/left_view', $left_data);
		$this->load->view('dashboard_view', $page_data);
		$this->load->view('includes/footer_view', $footer_data);
	}


	public function status_update()
	{
		$header_data = array();
		$page_data = array();
		$left_data = array();
		$footer_data = array();

		// check login or not 
		if($this->common_model->user_login_check())
		{
			// allow and get admin details
			$seller_details = $this->common_model->get_admin_user_details();

			if($this->input->post('available_status') != NULL)
			{
				$available_status = $this->input->post('available_status');
				$this->dashboard_model->available_status_update($seller_details['id'], $available_status);
				redirect(base_url(''));
			}
			else
			{
				redirect(base_url(''));
			}
			
		}
		else
		{
			redirect(base_url(''));
		}			
	}
}
