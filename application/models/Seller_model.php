<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Seller_model extends CI_Model
{

    function send_phone_otp($phone)
    {
        //$otp = rand(1111, 9999);
        $otp = '1234';

        $sms_text = "Liquesip.com: Your phone verification code is ".$otp;
        $this->common_model->send_sms($phone, $sms_text);

        $insert_data = array("phone" => $phone, "user_type" => "seller", "otp" => $otp, "is_expired" => "N", "created_date" => date("Y-m-d H:i:s"));
        $this->db->insert("LP_phone_otp_list", $insert_data);
        return $response = array("status" => "Y", "message" => "OTP successfully sent.");
    }


    function verify_phone_otp($phone, $otp)
    {
        $response = array("status" => "N", "message" => "Invalid OTP.");
        $cur_date = date("Y-m-d");
        $this->db->select("id");
        $this->db->from("LP_phone_otp_list");
        $this->db->where("user_type", "seller");
        $this->db->where("phone", $phone);
        $this->db->where("otp", $otp);
        $this->db->where("is_expired", "N");
        $this->db->where("created_date LIKE ", "%".$cur_date."%");
        $query = $this->db->get();
        if($query->num_rows() > 0)
        {
            // found otp
            $update_data = array("is_expired" => "Y");
            $this->db->where("phone", $phone);
            $this->db->where("is_expired", "N");
            $this->db->update("LP_phone_otp_list", $update_data);
            $response = array("status" => "Y", "message" => "OTP successfully verified.");
        }

        return $response;

    }

    function get_seller_short_details_by_id($seller_id = 0)
    {
        $details = array();

        $this->db->select("*");
        $this->db->from("LP_seller");
        $this->db->where("id", $seller_id);
        $query = $this->db->get();
        if($query->num_rows() > 0)
        {
            $row = $query->row();
            $details = array("id" => $row->id, "username" => $row->username, "shop_name" => $row->shop_name, "legal_entity_name" => $row->legal_entity_name, "shop_address" => $row->shop_address, "state" => $row->state, "city" => $row->city, "first_name" => $row->first_name, "last_name" => $row->last_name, "image" => FRONT_URL.$row->image, "phone" => $row->phone, "alternative_phone" => $row->alternative_phone, "email" => $row->email, "status" => $row->status,);
        }        
        return $details;
    }


    //=========================================================
    //Get seller list
    function seller_list($filter_data)
    {
        $list = array();

        $this->db->select("id");
        $this->db->from("LP_seller");
        if($filter_data['status'] == 'Y')
        {
            $this->db->where("status", "Y");
        }
        elseif($filter_data['status'] == 'N')
        {
            $this->db->where("status", "N");
        }
        else
        {
            $this->db->where("status !=", "D");
        }

        $this->db->order_by("id", "desc");
        $query = $this->db->get();
        if($query->num_rows() > 0)
        {
            foreach($query->result() as $row)
            {
                $seller_details = $this->get_seller_details_by_id($row->id);
                if(count($seller_details) > 0)
                {
                    $list[] = $seller_details;
                }
            }
        }
        return $list;
    }

    //-----------------------------------------------------------------

    function get_seller_details_by_id($seller_id = 0)
    {
        $details = array();

        $this->db->select("*");
        $this->db->from("LP_seller");
        $this->db->where("id", $seller_id);
        $query = $this->db->get();
        if($query->num_rows() > 0)
        {
            $row = $query->row();
            $details = array("id" => $row->id, "username" => $row->username, "shop_name" => $row->shop_name, "shop_address" => $row->shop_address, "first_name" => $row->first_name, "last_name" => $row->last_name, "image" => FRONT_URL.$row->image, "phone" => $row->phone, "alternative_phone" => $row->alternative_phone, "email" => $row->email, "status" => $row->status, "created_date" => $row->created_date, "updated_date" => $row->updated_date, "latitude" => $row->latitude, "longitude" => $row->longitude);
        }        
        return $details;
    }

    //-----------------------------------------------------------------

    //Add seller data
    function add_seller($data)
    {
        $response = array("status" => "N", "message" => "Seller registration failed.");

        $username = strtolower($data["username"]);
        $shop_name = ucwords(strtolower($data["shop_name"]));
        $shop_address = $data["shop_address"];
        $first_name = ucwords(strtolower($data["first_name"]));
        $last_name = ucwords(strtolower($data["last_name"]));
        $email  = strtolower($data["email"]);
        $phone = $data["phone"];
        $alternative_phone = $data["alternative_phone"];
        $status = $data["status"];
        $longitude = $data["longitude"];
        $latitude = $data["latitude"];

        $password = rand(111111, 999999);
        $data['password'] = $password;
        $encripted_password = md5($password);

        $query = $this->db->query("select id from LP_seller where status != 'D' and (username = '".$username."' or phone = '".$phone."')");
        if($query->num_rows() > 0)
        {
            $row = $query->row();
        }
        else
        {   

            $seller_data = array("username" => $username, "shop_name" => $shop_name, "shop_address" => $shop_address, "first_name" => $first_name, "last_name" => $last_name, "email" => $email, "password" => $encripted_password, "phone" => $phone, "alternative_phone" => $alternative_phone, "status" => $status, "latitude" => $latitude, "longitude" => $longitude, "created_date" => date("Y-m-d H:i:s"));
            $this->db->insert("LP_seller", $seller_data);
            $seller_id = $this->db->insert_id();


            // send welcome email start
            $email_body = $this->load->view('email-template/seller_welcome_email_view', $data, true);
            $seller_email = $data['email'];
            $email_subject = "Welcome to ".PROJECT_NAME." Seller";            
            $email_cc = "";
            $send_email = $this->common_model->email_send($seller_email, $email_subject, $email_body, $email_cc);

            // send welcome email end

            $response = array("status" => "Y", "message" => "Seller account successfully created.", "id" => $seller_id);
        }

        return $response;

    }

    //-----------------------------------------------------------------

    // Update image data
    function update_image($id, $image, $update_type)
    {
        if($update_type == 'first')
        {
            $update_data = array("image" => $image);
        }
        else
        {
            $update_data = array("image" => $image, "updated_date" => date("Y-m-d H:i:s"));
        }

        $this->db->where("id", $id);
        $this->db->update("LP_seller", $update_data);
        return true;
        
    }

    //-----------------------------------------------------------------

    function update_seller_variation($seller_id, $variation_ids)
    {
        $this->db->where("seller_id", $seller_id);
        $this->db->delete("LP_seller_available_product_variation_list");

        if(count($variation_ids) > 0)
        {
            foreach($variation_ids as $variation_id)
            {
                $product_id = $this->product_model->get_product_id_by_variation_id($variation_id);

                $insert_data = array("seller_id" => $seller_id, "product_id" => $product_id, "variation_id" => $variation_id, "created_date" => date("Y-m-d H:i:s"));
                $this->db->insert("LP_seller_available_product_variation_list", $insert_data);
            }
        }

        return true;
    }

    function get_seller_products_availability_list($seller_id)
    {
        $product_ids = array();
        $variation_ids = array();
        

        $this->db->select("variation_id, product_id");
        $this->db->from("LP_seller_available_product_variation_list");
        $this->db->where("seller_id", $seller_id);
        $query = $this->db->get();
        if($query->num_rows() > 0)
        {
            foreach($query->result() as $rows)
            {
                $product_ids[] = $rows->product_id;
                $variation_ids[] = $rows->variation_id;
            }

            $products_list = array_unique($product_ids);
        }
        else
        {
            $products_list = array();
        }



        $list = array("product_ids" => $products_list, "variation_ids" => $variation_ids);

        return $list;
    }

    //-----------------------------------------------------------------

    function update_seller_password($password, $seller_id)
    {
        $update_data = array("password" => md5($password), "updated_date" => date("Y-m-d H:i:s"));
        $this->db->where("id", $seller_id);
        $this->db->update("LP_seller", $update_data);
        $response = array("status" => "Y", "message" => "Password successfully changed.");
        return $response;
    }

    //-----------------------------------------------------------------

    function update_seller($data)
    {
        $response = array("status" => "N", "message" => "Seller not found.");

        $id = $data["id"];
        $username = strtolower($data["username"]);
        $shop_name = ucwords(strtolower($data["shop_name"]));
        $shop_address = $data["shop_address"];
        $first_name = ucwords(strtolower($data["first_name"]));
        $last_name = ucwords(strtolower($data["last_name"]));
        $email  = strtolower($data["email"]);
        $phone = $data["phone"];
        $alternative_phone = $data["alternative_phone"];        

        $query = $this->db->query("select id from LP_seller where status != 'D' and id=".$id."");
        if($query->num_rows() > 0)
        {
            $seller_data = array("username" => $username, "shop_name" => $shop_name, "shop_address" => $shop_address, "first_name" => $first_name, "last_name" => $last_name, "email" => $email, "phone" => $phone, "alternative_phone" => $alternative_phone, "updated_date" => date("Y-m-d H:i:s"));
            $this->db->where("id", $id);
            $this->db->update("LP_seller", $seller_data);        


            $response = array("status" => "Y", "message" => "Profile info successfully updated.", "id" => $id);
        }
        
        return $response;
    }

    
    //-----------------------------------------------------------------

    function user_name_availability_check($username, $seller_id)
    {
        $response = array("status" => "Y", "message" => "Username available");

        $this->db->select("id");
        $this->db->from("LP_seller");
        $this->db->where("status !=", "D");
        $this->db->where("username", $username);
        if($seller_id > 0)
        {
            $this->db->where("id !=", $seller_id);
        }
        $query = $this->db->get();
        if($query->num_rows() > 0)
        {
            $response = array("status" => "N", "message" => "Username not available");
        }

        return $response;
    }

    //-----------------------------------------------------------------

    function phone_availability_check($phone, $seller_id)
    {
        $response = array("status" => "Y", "message" => "Phone available");

        $this->db->select("id");
        $this->db->from("LP_seller");
        $this->db->where("status !=", "D");
        $this->db->where("phone", $phone);
        if($seller_id > 0)
        {
            $this->db->where("id !=", $seller_id);
        }
        $query = $this->db->get();
        if($query->num_rows() > 0)
        {
            $response = array("status" => "N", "message" => "Existing Seller");
        }

        return $response;
    }

    //-----------------------------------------------------------------
    
}

?>
