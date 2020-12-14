<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Login_model extends CI_Model
{
    function user_login($username, $password)
    {
        $response = array("status" => "N", "message" => "You have entered an invalid username or password.");
        $password = md5($password);

        $query = $this->db->query("select * from LP_seller where username = '".$username."' and status != 'N'");
        if($query->num_rows() > 0)
        {
            $row = $query->row();
            if($row->status == 'N')
            {
                $response = array("status" => "N", "message" => "You are currently inactive by admin. Please contact site admin.");
            }
            else if($row->password == $password)
            {
                $response = array("status" => "Y", "message" => "Login Success.");
                $this->session->set_userdata('seller_id', $row->id);
                
            }
            else
            {
                $response = array("status" => "N", "message" => "You have entered an invalid password.");
            }
        }
        else
        {
            $response = array("status" => "N", "message" => "You have entered an invalid username.");
        }
        return $response;
    }
    
}

?>