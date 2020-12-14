<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Order_model extends CI_Model
{
    

    function get_order_list($order_no = "" ,$filter_data)
    {

        /*"filter" => true, "search-type" => $search_type, "order-status" => $order_status, 'custom-date' => $custom_date*/

        $order = array();
        $seller_id = $this->session->userdata('seller_id');
        $today_date = date("Y-m-d");
        $till_date = date('Y-m-d', strtotime('-30 days'));

        $this->db->select("*");
        $this->db->from("LP_order");
        
        
        if($order_no != '')
        {
            $this->db->where("order_no", $order_no);
        }

        $filter_flag = "N";
        if($filter_data['filter'] == true)
        {
            // check_status 
            if($filter_data['filter'] == true)
            {
                $filter_flag = "Y";

                // check type
                if($filter_data['search-type'] == "manual-date")
                {
                    $date_range = $filter_data['custom-date'];
                    $exp_date_range = explode(' - ', $date_range);

                    $start_date = trim($exp_date_range[0]);
                    $exp_start_date = explode('/', $start_date);
                    $start_date_is = $exp_start_date[2]."-".$exp_start_date[0]."-".$exp_start_date[1];

                    $end_date = trim($exp_date_range[1]);
                    $exp_end_date = explode('/', $end_date);
                    $end_date_is = $exp_end_date[2]."-".$exp_end_date[0]."-".$exp_end_date[1];

                    if($start_date_is == $end_date_is)
                    {
                        $this->db->where("created_date LIKE ", "%".$start_date_is."%");
                    }
                    else
                    {
                        $this->db->where("created_date BETWEEN '".$start_date_is." 00:00:00' AND '".$end_date_is." 23:59:59'");
                    }

                    if($filter_data['order-status'] != 'all')
                    {
                        $this->db->where("status", $filter_data['order-status']);
                    }
                    else
                    {
                        //-------------
                        $this->db->where("status !=", "NOP");
                        $this->db->where("status !=", "P");
                    }

                    
                }
                else if($filter_data['search-type'] == "today-delivery")
                {

                    $this->db->where("delivery_date LIKE ", "%".$today_date."%");
                    $this->db->where("status !=", "NOP");
                    $this->db->where("status !=", "P");
                }
                else
                {
                    $this->db->where("status !=", "NOP");
                    $this->db->where("status !=", "P");
                    $this->db->where("created_date BETWEEN '".$till_date." 00:00:00' AND '".$today_date." 23:59:59'");

                }

            }
            else
            {
                //-------------

                $this->db->where("status !=", "NOP");
                $this->db->where("status !=", "P");
                $this->db->where("created_date BETWEEN '".$till_date." 00:00:00' AND '".$today_date." 23:59:59'");
            }

        }
        else
        {
            //-------------
            
            $this->db->where("status !=", "NOP");
            $this->db->where("status !=", "P");
            $this->db->where("created_date BETWEEN '".$till_date." 00:00:00' AND '".$today_date." 23:59:59'");
        }

        $this->db->where("seller_id", $seller_id);
        $this->db->order_by("id", "DESC");
        $query = $this->db->get();

        /*echo $this->db->last_query();
        exit;*/

        if($query->num_rows() > 0)
        {
            foreach($query->result() as $order_row)
            {
                $customer_id = $order_row->customer_id;
                $address_details = $this->user_model->get_address_details_by_id($order_row->address_id);
                if($order_row->promo_code_id == NULL || $order_row->promo_code_id == 0)
                {
                    $promo_code_details = array();
                    
                }
                else
                {
                    $promo_code_details = $this->promo_code_model->get_promo_code_details_by_id($order_row->promo_code_id);
                   
                }
                $time_slot_details = $this->common_model->get_delivery_time_slot_detail_by_id($order_row->delivery_time_slot);
                // get order product details

                $product_details = $this->get_product_details_order_id($order_row->id);

                $customer_details = $this->user_model->user_details_by_id($customer_id);

                $seller_details = $this->seller_model->get_seller_short_details_by_id($order_row->seller_id);

                if($order_row->logistic_id == NULL)
                {
                    $logistic_details = array();
                }
                else
                {
                    $logistic_details = $this->logistic_model->get_logistic_details_by_id($order_row->logistic_id);
                }



                $order[] = array("id" => $order_row->id, "order_no" => $order_row->order_no, "seller_details" => $seller_details, "customer_details" => $customer_details,  "address_details" => $address_details, "total_price" => $order_row->total_price, "delivery_charge" => $order_row->delivery_charge, "discount" => $order_row->discount, "order_total" => $order_row->order_total, "promo_code_details" => $promo_code_details, "payment_method" => $order_row->payment_method, "transaction_id" => $order_row->transaction_id, "delivery_date" => $order_row->delivery_date, "time_slot_details" => $time_slot_details, "notes" => $order_row->notes, "invoice" => FRONT_URL.$order_row->invoice, "product_details" => $product_details, "status" => $order_row->status, "created_date" => $order_row->created_date, "logistic_details" => $logistic_details);
            }
        }

        return $order;

    }



    function get_new_order_list()
    {

        $seller_id = $this->session->userdata('seller_id');

        $order = array();
        $today_date = date("Y-m-d");
        $this->db->select("*");
        $this->db->from("LP_order");        
        $this->db->where("status", "P");
        $this->db->where("seller_id", $seller_id);
        $this->db->order_by("id", "DESC");
        $query = $this->db->get();

        /*echo $this->db->last_query();
        exit;*/

        if($query->num_rows() > 0)
        {
            foreach($query->result() as $order_row)
            {
                $customer_id = $order_row->customer_id;
                $address_details = $this->user_model->get_address_details_by_id($order_row->address_id);
                if($order_row->promo_code_id == NULL || $order_row->promo_code_id == 0)
                {
                    $promo_code_details = array();
                    
                }
                else
                {
                    $promo_code_details = $this->promo_code_model->get_promo_code_details_by_id($order_row->promo_code_id);
                   
                }
                $time_slot_details = $this->common_model->get_delivery_time_slot_detail_by_id($order_row->delivery_time_slot);
                // get order product details

                $product_details = $this->get_product_details_order_id($order_row->id);

                $customer_details = $this->user_model->user_details_by_id($customer_id);

                $seller_details = $this->seller_model->get_seller_short_details_by_id($order_row->seller_id);



                $order[] = array("id" => $order_row->id, "order_no" => $order_row->order_no, "seller_details" => $seller_details, "customer_details" => $customer_details,  "address_details" => $address_details, "total_price" => $order_row->total_price, "delivery_charge" => $order_row->delivery_charge, "discount" => $order_row->discount, "order_total" => $order_row->order_total, "promo_code_details" => $promo_code_details, "payment_method" => $order_row->payment_method, "transaction_id" => $order_row->transaction_id, "delivery_date" => $order_row->delivery_date, "time_slot_details" => $time_slot_details, "notes" => $order_row->notes, "invoice" => FRONT_URL.$order_row->invoice, "product_details" => $product_details, "status" => $order_row->status, "created_date" => $order_row->created_date);
            }
        }

        return $order;

    }




    function order_details_by_no($order_no)
    {      

        $order = array();      

        $this->db->select("*");
        $this->db->from("LP_order");       
        $this->db->where("order_no", $order_no);        
        $query = $this->db->get();

        if($query->num_rows() > 0)
        {
            foreach($query->result() as $order_row)
            {
                $customer_id = $order_row->customer_id;
                $address_details = $this->user_model->get_address_details_by_id($order_row->address_id);
                if($order_row->promo_code_id == NULL || $order_row->promo_code_id == 0)
                {
                    $promo_code_details = array();
                    
                }
                else
                {
                    $promo_code_details = $this->promo_code_model->get_promo_code_details_by_id($order_row->promo_code_id);
                   
                }
                $time_slot_details = $this->common_model->get_delivery_time_slot_detail_by_id($order_row->delivery_time_slot);
                // get order product details

                $product_details = $this->get_product_details_order_id($order_row->id);

                $customer_details = $this->user_model->user_details_by_id($customer_id);

                $seller_details = $this->seller_model->get_seller_short_details_by_id($order_row->seller_id);

                $order = array("id" => $order_row->id, "seller_details" => $seller_details, "order_no" => $order_row->order_no, "customer_details" => $customer_details,  "address_details" => $address_details, "total_price" => $order_row->total_price, "delivery_charge" => $order_row->delivery_charge, "discount" => $order_row->discount, "order_total" => $order_row->order_total, "promo_code_details" => $promo_code_details, "payment_method" => $order_row->payment_method, "transaction_id" => $order_row->transaction_id, "delivery_date" => $order_row->delivery_date, "time_slot_details" => $time_slot_details, "notes" => $order_row->notes, "invoice" => $order_row->invoice, "product_details" => $product_details, "status" => $order_row->status, "created_date" => $order_row->created_date);
            }
        }

        return $order;

    }
 
    function get_product_details_order_id($order_id = 0)
    {
        $order_details = array();
        $this->db->select("*");
        $this->db->from("LP_order_details");
        $this->db->where("order_id", $order_id);
        $query = $this->db->get();
        if($query->num_rows() > 0)
        {
            foreach($query->result() as $product_row)
            {
                $variation_details = $this->product_model->get_veriation_full_details_by_id($product_row->variation_id);
                $order_details[] = array("variation_details" => $variation_details, "unit_price" => $product_row->unit_price, "quantity" => $product_row->quantity, "total_price" => $product_row->total_price);
            }
        }

        return $order_details;
    }

    function get_order_no_by_id($id = 0)
    {
        $order_no = "";

        $seller_id = $this->session->userdata('seller_id');

        $this->db->select("order_no");
        $this->db->from("LP_order");
        $this->db->where("id", $id);
        $this->db->where("seller_id", $seller_id);
        $query = $this->db->get();
        if($query->num_rows() > 0)
        {
            $row = $query->row();
            $order_no = $row->order_no;
        }
        return $order_no;
    }

    function get_order_no_by_order_id($id = 0)
    {
        $order_no = "";
        $this->db->select("order_no");
        $this->db->from("LP_order");
        $this->db->where("id", $id);
        $query = $this->db->get();
        if($query->num_rows() > 0)
        {
            $row = $query->row();
            $order_no = $row->order_no;

        }
        return $order_no;
    }

    function update_order_status($order_no = "", $status)
    {
        $response = array("status" => "N", "message" => "Update Failed.");

        if($status == "NOP" || $status == "P" || $status == "SA" || $status == "SR"  || $status == "C" || $status == "D" || $status == "S" || $status == "R")
        {
            $this->db->select('status');
            $this->db->from("LP_order");
            $this->db->where("order_no", $order_no);
            $query = $this->db->get();
            if($query->num_rows() > 0)
            {
                $row  = $query->row();
                if($row->status == "SR" && $status == "SA")
                {
                    $response = array("status" => "N", "message" => "You are already rejected this order.");
                }
                else if($row->status == "SA" && $status == "SR")
                {
                    $response = array("status" => "N", "message" => "You are already accepted this order.");
                }
                else
                {
                    $update_data = array("status" => $status, "updated_date" => date("Y-m-d H:i:s"));
                    $this->db->where("order_no", $order_no);
                    $this->db->update("LP_order", $update_data);
                    if($status == "SA")
                    {
                        $response = array("status" => "Y", "message" => "Order successfully accepted.");
                    }
                    else if($status == "SR")
                    {
                        $response = array("status" => "Y", "message" => "Order successfully rejected.");
                    }
                    else if($status == "S")
                    {
                        $response = array("status" => "Y", "message" => "Order successfully shipped.");
                    }
                    else
                    {
                        $response = array("status" => "Y", "message" => "Order status successfully updated.");
                    }
                    

                }
            }

            
        }

        return $response;
        
    }

    function update_order_details($order_no = "", $status, $payment_method)
    {

        if($status == "NOP" || $status == "P" || $status == "SA" || $status == "SR" || $status == "C" || $status == "D" || $status == "S" || $status == "R")
        {
            $update_data = array("status" => $status, "payment_method" => $payment_method, "updated_date" => date("Y-m-d H:i:s"));
            $this->db->where("order_no", $order_no);
            $this->db->update("LP_order", $update_data);
        }

        return "success";
        
    }

    

    function validate_logistic_otp($id = 0, $otp = "none")
    {
        $response = array("status" => "N", "message" => "Invalid OTP. Please try with valid OTP.");

        $this->db->select("logistic_otp");
        $this->db->from("LP_order");
        $this->db->where("id", $id);
        $query = $this->db->get();
        if($query->num_rows() > 0)
        {
            $row = $query->row();
            if($row->logistic_otp == $otp)
            {
                $response = array("status" => "Y", "message" => "OTP successfully validated and order status is successfully updated to 'Out For Delivery'.");
            }
        }
        return $response;
    }

}
?>