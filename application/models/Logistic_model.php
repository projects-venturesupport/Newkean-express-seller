<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Logistic_model extends CI_Model
{

    function phone_availability_check($phone, $id)
    {
        $response = array("status" => "Y", "message" => "Phone available");

        $this->db->select("id");
        $this->db->from("LP_logistic");
        $this->db->where("status !=", "D");
        $this->db->where("phone", $phone);
        if($id > 0)
        {
            $this->db->where("id !=", $id);
        }
        $query = $this->db->get();
        if($query->num_rows() > 0)
        {
            $response = array("status" => "N", "message" => "Existing Logistics");
        }

        return $response;
    }

    function user_name_availability_check($username, $id)
    {
        $response = array("status" => "Y", "message" => "Username available");

        $this->db->select("id");
        $this->db->from("LP_logistic");
        $this->db->where("status !=", "D");
        $this->db->where("username", $username);
        if($id > 0)
        {
            $this->db->where("id !=", $id);
        }
        $query = $this->db->get();
        if($query->num_rows() > 0)
        {
            $response = array("status" => "N", "message" => "Username not available");
        }

        return $response;
    }

    function add_logistic($data)
    {
        $username = $data['username'];
        $phone = $data['phone'];
        
        $query = $this->db->query("select username, phone from LP_logistic where (username = '".$username."' or phone = '".$phone."' ) and status !='D'");
        if($query->num_rows() > 0)
        {
            $row = $query->row();
            if($row->username == $username && $row->phone == $phone)
            {
                $response = array("status" => "N", "message" => "Username and Phone number already registred in another Logistics.");
            }
            else if($row->username == $username)
            {
                $response = array("status" => "N", "message" => "Username already registred in another Logistics.");
            }
            else
            {
                 $response = array("status" => "N", "message" => "Phone number already registred in another Logistics.");
            }
        }
        else
        {
            $this->db->insert("LP_logistic", $data);
            $id = $this->db->insert_id();
            if($id > 0)
            {
                $response = array("status" => "Y", "message" => "Logistics user successfully created.", "id" => $id);
            }
            else
            {
                $response = array("status" => "N", "message" => "Logistics creation failed. Something is wrong. Please try with valid information.");
            }
        }

        return $response;
    }

    function update_logistic($id, $data)
    {
        $username = $data['username'];
        $phone = $data['phone'];
        
        $query = $this->db->query("select username, phone from LP_logistic where (username = '".$username."' or phone = '".$phone."' ) and status !='D' and id != '".$id."'");
        if($query->num_rows() > 0)
        {
            $row = $query->row();
            if($row->username == $username && $row->phone == $phone)
            {
                $response = array("status" => "N", "message" => "Username and Phone number already registred in another Logistics.");
            }
            else if($row->username == $username)
            {
                $response = array("status" => "N", "message" => "Username already registred in another Logistics.");
            }
            else
            {
                 $response = array("status" => "N", "message" => "Phone number already registred in another Logistics.");
            }
        }
        else
        {
            $this->db->where("id", $id);
            $this->db->update("LP_logistic", $data);
            $response = array("status" => "Y", "message" => "Logistics info successfully updated.", "id" => $id);
            
        }

        return $response;
    }

    function logistic_list($filter)
    {
        $list = array();
        $status = $filter['status'];

        $this->db->select("id");
        $this->db->from("LP_logistic");
        if($status == 'Y' || $status == "N")
        {
            $this->db->where("status", $status);
        }
        else
        {
            $this->db->where("status !=", "D");
        }
        $query = $this->db->get();
        if($query->num_rows() > 0)
        {
            foreach($query->result() as $row)
            {
                $logistic_details = $this->get_logistic_details_by_id($row->id);
                if(count($logistic_details) > 0)
                {
                    $list[] = $logistic_details;
                }
            }
        }

        return $list;
    }

    function get_logistic_details_by_id($id = 0)
    {
        $details = array();
        $this->db->select("*");
        $this->db->from("LP_logistic");
        $this->db->where("id", $id);
        $query = $this->db->get();
        if($query->num_rows() > 0)
        {
            $row = $query->row();
            if($row->image != NULL || $row->image != "")
            {
                $image = FRONT_URL.$row->image;
            }
            else
            {
                $image = "";
            }
            if($row->photo_id_image != NULL || $row->photo_id_image != "")
            {
                $photo_id_image = FRONT_URL.$row->photo_id_image;
            }
            else
            {
                $photo_id_image = "";
            }
            $details = array("id" => $row->id, "username" => $row->username, "first_name" => $row->first_name, "last_name" => $row->last_name, "name" => $row->first_name." ".$row->last_name, "phone" => $row->phone, "alternative_phone" => $row->alternative_phone, "email" => $row->email, "image" => $image, "photo_id_image" => $photo_id_image, "address" => $row->address,  "status" => $row->status, "is_active" => $row->is_active, "created_date" => $row->created_date, "updated_date" => $row->updated_date);
        }

        return $details;
    }

    function delete_logistic_by_id($id = 0)
    {
        $this->db->select("id");
        $this->db->from("LP_logistic");
        $this->db->where("id", $id);
        $this->db->where("status !=", "D");
        $query = $this->db->get();
        if($query->num_rows() == 0)
        {
            $response = array("status" => "N", "Logistics user already delete or invalid try.");
        }
        else
        {
            $delete_data = array("status" => "D", "updated_date" => date("Y-m-d H:i:s"));
            $this->db->where("id", $id);
            $this->db->update("LP_logistic", $delete_data);
            $response = array("status" => "Y", "message" => "Logistics user successfully deleted.");
        }
        return $response;
    }

    
}

?>
