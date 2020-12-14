<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard_model extends CI_Model {

   

    function dashboard_order_counter($filter_type = "monthly")
    {

        $seller_id = $this->session->userdata('seller_id');

        if($filter_type == "monthly")
        {
            $start_date = date("Y-m-01"); 
            $end_date = date("Y-m-t"); 
            $filter_str = " delivery_date >= '".$start_date."' and delivery_date <= '".$end_date."'  ";
            

        }
        else
        {
            // todays            
            $filter_str = " delivery_date = '".date("Y-m-d")."' ";

        }
        

        $new_order_request_query = $this->db->query("SELECT count(id) as total_count FROM `LP_order` where status = 'P' and seller_id = '".$seller_id."' and  ".$filter_str);
        $new_request_count = $new_order_request_query->row()->total_count;

        
        $accepted_order_query = $this->db->query("SELECT count(id) as total_count FROM `LP_order` where status = 'SA' and seller_id = '".$seller_id."' and  ".$filter_str);
        $accepted_order_count = $accepted_order_query->row()->total_count;

        $shipped_order_query = $this->db->query("SELECT count(id) as total_count FROM `LP_order` where status = 'S' and seller_id = '".$seller_id."' and  ".$filter_str);
        $shipped_order_count = $shipped_order_query->row()->total_count;

        $completed_order_query = $this->db->query("SELECT count(id) as total_count FROM `LP_order` where status = 'D' and seller_id = '".$seller_id."' and  ".$filter_str);
        $completed_order_count = $completed_order_query->row()->total_count;

        $cancelled_order_query = $this->db->query("SELECT count(id) as total_count FROM `LP_order` where status = 'C' and seller_id = '".$seller_id."' and  ".$filter_str);
        $cancelled_order_count = $cancelled_order_query->row()->total_count;

        $returned_order_query = $this->db->query("SELECT count(id) as total_count FROM `LP_order` where status = 'R' and seller_id = '".$seller_id."' and  ".$filter_str);
        $returned_order_count = $returned_order_query->row()->total_count;
        



        $response = array("new_request" => $new_request_count, "accepted_order" => $accepted_order_count, "shipped_order" => $shipped_order_count, "completed_order" => $completed_order_count, "cancelled_order" => $cancelled_order_count, "returned_order" => $returned_order_count);

       

        return $response;
    }

    function get_completed_order_count()
    {
        $seller_id = $this->session->userdata('seller_id');
        $count = 0;

        $query = $this->db->query("select count(id) as completed_order_count from LP_order where seller_id = '".$seller_id."' and status = 'D'");
        $count = $query->row()->completed_order_count;
        return $count;

    }

    function get_pending_request_order_count()
    {
        $seller_id = $this->session->userdata('seller_id');
        $count = 0;

        $query = $this->db->query("select count(id) as new_request from LP_order where seller_id = '".$seller_id."' and status = 'P'");
        $count = $query->row()->new_request;
        return $count;

    }

    function available_status_update($id, $status)
    {
        $this->db->query("UPDATE `LP_seller` SET `available_status` = '".$status."' WHERE `LP_seller`.`id` = ".$id."");

        return true;

    }


}
