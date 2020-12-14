<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Seller extends CI_Controller {

	function __construct()
    {
        parent::__construct();
        $this->load->model('seller_model');        
    }

	//Saller List
	public function index()
	{ 
        redirect(base_url(''));
    }

    //-----------------------------------------------------------------
    
    

    function phone_availability_check()
    {        
        $response_status  = "N";
        $response_message = "Something was wrong.";
        try
        {
            $missing_key = array();
            // check phone
            if ($this->input->post('phone') == null)
            {
                $missing_key[] = 'phone';
            }
            else
            {
                $phone = $this->input->post("phone");
            }

            // check username
            if ($this->input->post('seller_id') == null)
            {
                $missing_key[] = 'seller_id';
            }
            else
            {
                $seller_id = $this->input->post("seller_id");
            }
            
            if (count($missing_key) == 0)
            {
                $response = $this->seller_model->phone_availability_check($phone, $seller_id);
                echo json_encode($response);
            }
            else
            {
                throw new MissingException();
            }
        }
        catch (MissingException $ex)
        {
            $implode_missing_key = implode(', ', $missing_key);
            $response_message    = $implode_missing_key . " - key or value missing";
            $response            = array(
                "status" => $response_status,
                "message" => $response_message
            );
            echo json_encode($response);
        }
        catch (Exception $x)
        {
            $response = array(
                "status" => $response_status,
                "message" => $response_message
            );
            echo json_encode($response);
        }

    }

    function phone_verify_otp()
    {
        $response_status  = "N";
        $response_message = "Something was wrong.";
        try
        {
            $missing_key = array();
            
            // check phone
            if ($this->input->post('phone') == null)
            {
                $missing_key[] = 'phone';
            }
            else
            {
                $phone = $this->input->post("phone");
            }
            // check otp
            if ($this->input->post('otp') == null)
            {
                $missing_key[] = 'otp';
            }
            else
            {
                $otp = $this->input->post("otp");
            }
            if (count($missing_key) == 0)
            { 
                $response = $this->seller_model->verify_phone_otp($phone, $otp);
                echo json_encode($response);
                
                
            }
            else
            {
                throw new MissingException();
            }
        }
        catch (MissingException $ex)
        {
            $implode_missing_key = implode(', ', $missing_key);
            $response_message    = $implode_missing_key . " - key or value missing";
            $response            = array(
                "status" => $response_status,
                "message" => $response_message
            );
            echo json_encode($response);
        }
        catch (Exception $x)
        {
            $response = array(
                "status" => $response_status,
                "message" => $response_message
            );
            echo json_encode($response);
        }
    }

    //-----------------------------------------------------------------
    function send_phone_otp()
    {
        $response_status  = "N";
        $response_message = "Something was wrong.";
        try
        {
            $missing_key = array();
            // check phone
            if ($this->input->post('phone') == null)
            {
                $missing_key[] = 'phone';
            }
            else
            {
                $phone = $this->input->post("phone");
            }          
            
            if (count($missing_key) == 0)
            {
                $response = $this->seller_model->send_phone_otp($phone);
                echo json_encode($response);
            }
            else
            {
                throw new MissingException();
            }
        }
        catch (MissingException $ex)
        {
            $implode_missing_key = implode(', ', $missing_key);
            $response_message    = $implode_missing_key . " - key or value missing";
            $response            = array(
                "status" => $response_status,
                "message" => $response_message
            );
            echo json_encode($response);
        }
        catch (Exception $x)
        {
            $response = array(
                "status" => $response_status,
                "message" => $response_message
            );
            echo json_encode($response);
        }

    }
    function user_name_availability_check()
    {
        $response_status  = "N";
        $response_message = "Something was wrong.";
        try
        {
            $missing_key = array();
            // check username
            if ($this->input->post('username') == null)
            {
                $missing_key[] = 'username';
            }
            else
            {
                $username = urldecode($this->input->post("username"));
            }

            // check username
            if ($this->input->post('seller_id') == null)
            {
                $missing_key[] = 'seller_id';
            }
            else
            {
                $seller_id = $this->input->post("seller_id");
            }
            
            if (count($missing_key) == 0)
            {
                $response = $this->seller_model->user_name_availability_check($username, $seller_id);
                echo json_encode($response);
            }
            else
            {
                throw new MissingException();
            }
        }
        catch (MissingException $ex)
        {
            $implode_missing_key = implode(', ', $missing_key);
            $response_message    = $implode_missing_key . " - key or value missing";
            $response            = array(
                "status" => $response_status,
                "message" => $response_message
            );
            echo json_encode($response);
        }
        catch (Exception $x)
        {
            $response = array(
                "status" => $response_status,
                "message" => $response_message
            );
            echo json_encode($response);
        }
    }

    //-----------------------------------------------------------------

    function password_update_submit()
    {

        if($this->common_model->user_login_check())
        {
            // allow to access
        }
        else
        {
            redirect(base_url(''));
        }  

        $response_status  = "N";
        $response_message = "Something was wrong.";
        try
        {
            $missing_key = array();
            // check username
            if ($this->input->post('password') == null)
            {
                $missing_key[] = 'password';
            }
            else
            {
                $password = urldecode($this->input->post("password"));
            }

            
            if (count($missing_key) == 0)
            {
                $seller_id = $this->session->userdata("seller_id");
                $response = $this->seller_model->update_seller_password($password, $seller_id);
                $this->session->set_flashdata('success_message', $response["message"]);

                redirect(base_url('profile'));
            }
            else
            {
                throw new MissingException();
            }
        }
        catch (MissingException $ex)
        {
            $implode_missing_key = implode(', ', $missing_key);
            $response_message    = $implode_missing_key . " - key or value missing";
            $response            = array(
                "status" => $response_status,
                "message" => $response_message
            );
            echo json_encode($response);
        }
        catch (Exception $x)
        {
            $response = array(
                "status" => $response_status,
                "message" => $response_message
            );
            echo json_encode($response);
        }

    }

    

    //-----------------------------------------------------------------
    function seller_product_update()
    {
        if($this->common_model->user_login_check())
        {
            // allow to access
        }
        else
        {
            redirect(base_url(''));
        }  

        if($this->input->post('p_seller_id'))
        {
            $seller_id = $this->input->post('p_seller_id');

            if($this->input->post('var_ids') == null)
            {
                $variation = array();
            }
            else
            {
                $variation = $this->input->post('var_ids');
            }
            
            $update_variation = $this->seller_model->update_seller_variation($seller_id, $variation);

            $this->session->set_flashdata('success_message', "Your product list availability successfully updated.");

            redirect(base_url('products'));


            
        }
        else
        {
            redirect(base_url(''));
        }



    }
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

        if($this->input->post('seller_id'))
        {
            $form_data = array();
            $form_data['id'] = $this->session->userdata('seller_id');
            $form_data['username'] = $this->input->post('username');
            $form_data['shop_name'] = $this->input->post('shop_name');
            $form_data['shop_address'] = $this->input->post('shop_address');
            $form_data['first_name'] = $this->input->post('first_name');
            $form_data['last_name'] = $this->input->post('last_name');
            $form_data['email'] = $this->input->post('email');
            $form_data['phone'] = $this->input->post('phone');
            $form_data['alternative_phone'] = $this->input->post('alternative_phone');

            $update_data = $this->seller_model->update_seller($form_data);
            //print_r($add_data); exit;
            if($update_data['status'] == "Y")
            {
                $id = $form_data["id"];

                // upload image and update image path in database
                if($_FILES['image']['name'] != '')
                {
                    $upload_dir = FILE_UPLOAD_BASE_PATH.'uploads/seller/';
                    $rand_name = time()."-";
                    $upload_file = $upload_dir.$rand_name.basename($_FILES['image']['name']);
                    $actual_path = 'uploads/seller/'.$rand_name.basename($_FILES['image']['name']);
                    if (move_uploaded_file($_FILES['image']['tmp_name'], $upload_file))
                    {
                       $image = $actual_path;

                        $update_type = "second";
                        $this->seller_model->update_image($id, $image, $update_type);
                    }
                    
                }
                
                // update image
               
                $this->session->set_flashdata('success_message', $update_data['message']);
                redirect(base_url('profile'));
            }
            else
            {
                $this->session->set_flashdata('error_message', $update_data['message']);
                redirect(base_url('profile'));
            }
        }
        else
        {
            redirect(base_url('profile'));
        }

    }

    //-----------------------------------------------------------------

    
	
}
