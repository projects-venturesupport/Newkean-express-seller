<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Common_model extends CI_Model
{
    function email_send($send_to, $subject, $body)
    {
        $this->load->library('email');
        $result = $this->email
            ->from(FROM_EMAIL, 'Goezzy')
            ->to($send_to)
            ->subject($subject)
            ->message($body)
            ->send();
            return $result;
    }
    
    function user_login_check()
    {
        $return_status = false;
        $user_id = $this->session->userdata('seller_id');
        if($user_id > 0)
        {
            $return_status = true;
        }
        return $return_status;
    }    

    function get_admin_user_details()
    {
        $response = array();
        $user_id = $this->session->userdata('seller_id');

        $this->db->select("*");
        $this->db->from("LP_seller");
        $this->db->where("id", $user_id);
        $query = $this->db->get();
        if($query->num_rows() > 0)
        {
            $row = $query->row();
            $response = array("id" => $row->id, "username" => $row->username, "shop_name" => $row->shop_name, "shop_address" => $row->shop_address, "first_name" => $row->first_name, "last_name" => $row->last_name, "name" => $row->first_name." ".$row->last_name, "image" => FRONT_URL.$row->image, "phone" => $row->phone, "alternative_phone" => $row->alternative_phone, "email" => $row->email, "latitude" => $row->latitude, "longitude" => $row->longitude, "status" => $row->status, "created_date" => $row->created_date, "updated_date" => $row->updated_date, "available_status" => $row->available_status);
        }
        return $response;

    }

    function get_delivery_time_slot_detail_by_id($id = 0)
    {
        $time_slot = array();
        $current_hour = date("H");

        $this->db->select("*");
        $this->db->from("LP_delivery_time_slot");
        $this->db->where("id", $id);
        $query = $this->db->get();       

        if($query->num_rows() > 0)
        {
            foreach($query->result() as $rows)
            {
                $start_time = $rows->start_time;
                $end_time = $rows->end_time;
                if($start_time == 12)
                {
                    $start_str = $start_time ." PM";
                }
                else if($start_time > 12)
                {
                    $start_str = $start_time - 12 ." PM";
                }
                else
                {
                    $start_str = $start_time ." AM";
                }

                if($end_time == 12)
                {
                    $end_str = $end_time ." PM";
                }
                else if($end_time > 12)
                {
                    $end_str = $end_time - 12 ." PM";
                }
                else
                {
                    $end_str = $end_time ." AM";
                }
                $time_slot = array("id" => $rows->id, "time_slot" => $start_str." - ".$end_str);
            }
        }

        return $time_slot;

    }

    public function slugify($text)
    {
      // replace non letter or digits by -
      $text = preg_replace('~[^\pL\d]+~u', '-', $text);

      // transliterate
      $text = iconv('utf-8', 'us-ascii//TRANSLIT', $text);

      // remove unwanted characters
      $text = preg_replace('~[^-\w]+~', '', $text);

      // trim
      $text = trim($text, '-');

      // remove duplicate -
      $text = preg_replace('~-+~', '-', $text);

      // lowercase
      $text = strtolower($text);

      if (empty($text)) {
        return 'n-a';
      }

      return $text;
    }


    function send_sms($phone_number, $text)
    {
        // Account details
        //$apiKey = urlencode('jdVFaG7htak-Qw0hm5m9nbrW7IavjgLgtQz0wnb6Zt');
        
        // Message details
        $sender = urlencode('TLTEST');
        $message = rawurlencode($text);
     
        $numbers = $phone_number;
     
        // Prepare data for POST request
        $data = array('apikey' => $apiKey, 'numbers' => $numbers, "sender" => $sender, "message" => $message);
     
        // Send the POST request with cURL
        $ch = curl_init('https://api.textlocal.in/send/');
        curl_setopt($ch, CURLOPT_POST, true);
        //curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($ch);
        curl_close($ch);
        
        // Process your response here
        return $response;
    }

    function product_count_by_category_id($category_id = 0)
    {
        $count = 0;

        $this->db->select("COUNT(id) as product_count");
        $this->db->from("LP_product");
        $this->db->where("status !=", "D");
        $this->db->where("category_id", $category_id);
        $query = $this->db->get();
        if($query->num_rows() > 0)
        {
            $count = $query->row()->product_count;
        }

        return $count;
    }

}

?>