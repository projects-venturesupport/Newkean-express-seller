<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Order extends CI_Controller {

	function __construct()
    {
        parent::__construct();       
    }

	//Banner List
	public function index()
	{
        // banner list
        $header_data = array();
        $page_data = array();
        $left_data = array();
        $footer_data = array();

        $header_data['title'] = "Order List";
        $left_data['navigation'] = "order"; 
        $left_data['sub_navigation'] = "order-list"; 

        // check login or not 
        if($this->common_model->user_login_check())
        {
            // allow 
            $seller_details = $this->common_model->get_admin_user_details();
            $header_data['seller_details'] = $seller_details;
            $left_data['seller_details'] = $seller_details;
        }
        else
        {
            redirect(base_url(''));
        }

        $export_flag = "N";
        
        if($this->input->post('filter'))
        {
            

            $search_type = $this->input->post('search-type');
            $custom_date = $this->input->post('custom-date');
            $order_status = $this->input->post('order-status');

            

            if($search_type == 'manual-date')
            {

                $date_range = $custom_date;
                $exp_date_range = explode(' - ', $date_range);

                $start_date = trim($exp_date_range[0]);
                $exp_start_date = explode('/', $start_date);
                $start_date_is = $exp_start_date[2]."-".$exp_start_date[0]."-".$exp_start_date[1];

                $end_date = trim($exp_date_range[1]);
                $exp_end_date = explode('/', $end_date);
                $end_date_is = $exp_end_date[2]."-".$exp_end_date[0]."-".$exp_end_date[1];

                // get days count for export                
                $datediff = strtotime($end_date_is." 23:59:59") - strtotime($start_date_is." 00:00:00");

                $total_days = round($datediff / (60 * 60 * 24));

                if($total_days < 91)
                {
                    $export_flag = "Y";
                }

                


                $filter_data = array("filter" => true, "search-type" => $search_type, "order-status" => $order_status, 'custom-date' => $custom_date);





            }
            else if($search_type == 'today-delivery')
            {
                $filter_data = array("filter" => true, "search-type" => $search_type, "order-status" => 'all', "custom-date" => "".date("m/d/Y")." - ".date("m/d/Y")."");
            }
            else
            {
                 $filter_data = array("filter" => false, "search-type" => 'default', "order-status" => 'all', "custom-date" => "".date("m/d/Y")." - ".date("m/d/Y")."");

            }
        }
        else
        {
            $filter_data = array("filter" => false, "search-type" => 'default', "order-status" => 'all', "custom-date" => "".date("m/d/Y")." - ".date("m/d/Y")."");
        }

        
        $page_data['filter_data'] = $filter_data;
        $page_data['export_flag'] = $export_flag;
 
        // get order list
        $order_list = $this->order_model->get_order_list("",$filter_data);
        $page_data['order_list'] = $order_list;

        
        
        $this->load->view('includes/header_view', $header_data);
        $this->load->view('includes/left_view', $left_data);
        $this->load->view('order/list_view', $page_data);
        $this->load->view('includes/footer_view', $footer_data);
    }

    function verify_logistic_otp()
    {

        if($this->common_model->user_login_check())
        {
            // allow            
        }
        else
        {
            redirect(base_url(''));
        }

        if($this->input->post('id') && $this->input->post('otp'))
        {
            $id = $this->input->post('id');
            $otp = $this->input->post('otp');

            $response = $this->order_model->validate_logistic_otp($id, $otp);            
            if($response['status'] == "Y")
            {
                $order_no = $this->order_model->get_order_no_by_id($id);
                $this->order_model->update_order_status($order_no, "S");

                // notification model calling start

                $ch = curl_init();
                $curlConfig = array(
                    CURLOPT_URL => ADMIN_URL."notification/send_notification/".$order_no,
                    CURLOPT_POST           => true,
                    CURLOPT_RETURNTRANSFER => true,
                    CURLOPT_POSTFIELDS     => array(
                        'generate' => 'Y'
                    )
                );
                curl_setopt_array($ch, $curlConfig);
                $result = curl_exec($ch);
                curl_close($ch); 

                // notification model calling end

                $this->session->set_flashdata('success_message', "OTP successfully validated and order: #".$order_no." status is successfully updated to 'Out For Delivery'."); 
            }


        }
        else
        {
            $response = array("status" => "N", "message" => "Invalid try.");
        }
        
        echo json_encode($response);           
    }

    public function new()
    {
        // banner list
        $header_data = array();
        $page_data = array();
        $left_data = array();
        $footer_data = array();

        $header_data['title'] = "New Order List";
        $left_data['navigation'] = "order-new"; 
        $left_data['sub_navigation'] = "new-order-list"; 

        // check login or not 
        if($this->common_model->user_login_check())
        {
            // allow 
            $seller_details = $this->common_model->get_admin_user_details();
            $header_data['seller_details'] = $seller_details;
            $left_data['seller_details'] = $seller_details;
        }
        else
        {
            redirect(base_url(''));
        }

        

        // get order list
        $order_list = $this->order_model->get_new_order_list();
        $page_data['order_list'] = $order_list;

        
        
        $this->load->view('includes/header_view', $header_data);
        $this->load->view('includes/left_view', $left_data);
        $this->load->view('order/new_list_view', $page_data);
        $this->load->view('includes/footer_view', $footer_data);
    }

    function export_order()
    {

        if(isset($_REQUEST['date-range']) && isset($_REQUEST['status']))
        {
            $date_range = $_REQUEST['date-range'];
            $order_status = $_REQUEST['status'];
            $filter_data = array("filter" => true, "search-type" => 'manual-date', "order-status" => $order_status, 'custom-date' => $date_range);

            $order_list = $this->order_model->get_order_list("",$filter_data);

            /*echo "<pre>";
            print_r($order_list);
            echo "</pre>"; exit;*/


        require(APPPATH.'third_party/PHPExcel-1.8/Classes/PHPExcel.php');
        require(APPPATH.'third_party/PHPExcel-1.8/Classes/PHPExcel/Writer/Excel2007.php');
        $objPHPExcel = new PHPExcel;

        $objPHPExcel->getProperties()->setCreator("");
        $objPHPExcel->getProperties()->setLastModifiedBy("");
        $objPHPExcel->getProperties()->setTitle("");
        $objPHPExcel->getProperties()->setSubject("");
        $objPHPExcel->getProperties()->setDescription("");

        $objPHPExcel->setActiveSheetindex(0);

        $objPHPExcel->getActiveSheet()->setCellValue('A1', 'ORDER DATE');
        $objPHPExcel->getActiveSheet()->setCellValue('B1', 'ORDER ID');
        $objPHPExcel->getActiveSheet()->setCellValue('C1', 'PRODUCT DETAILS');
        $objPHPExcel->getActiveSheet()->setCellValue('D1', 'SHIPPING DETAILS');
        $objPHPExcel->getActiveSheet()->setCellValue('E1', 'DELIVERY DATE');
        $objPHPExcel->getActiveSheet()->setCellValue('F1', 'CUSTOMER DETAILS');
        $objPHPExcel->getActiveSheet()->setCellValue('G1', 'PROMO CODE');
        $objPHPExcel->getActiveSheet()->setCellValue('H1', 'SUBTOTAL');
        $objPHPExcel->getActiveSheet()->setCellValue('I1', 'SHIPPING CHARGE');
        $objPHPExcel->getActiveSheet()->setCellValue('J1', 'PROMO DISCOUNT');
        $objPHPExcel->getActiveSheet()->setCellValue('K1', 'ORDER TOTAL');
        $objPHPExcel->getActiveSheet()->setCellValue('L1', 'ORDER STATUS');

        $row_no = 2;

        if(count($order_list) > 0)
        {
            foreach($order_list as $report_row)
            {
                $objPHPExcel->getActiveSheet()->setCellValue('A'.$row_no, $report_row['created_date']);
                $objPHPExcel->getActiveSheet()->setCellValue('B'.$row_no, $report_row['order_no']);
                
                //----------------------------
                $product_details = "";

                $product_count = count($report_row['product_details']);
                $no = 1;
                
                foreach($report_row['product_details'] as $order_product)
                {
                    $product_details.= $order_product['variation_details']['product_details']['name']." - ".$order_product['variation_details']['variation_details']['title']." x ".$order_product['quantity'];
                    if($no != $product_count)
                    {
                        $product_details.="\n";
                    }
                    $no++;
                }
                

                //--------------------------

                $objPHPExcel->getActiveSheet()->setCellValue('C'.$row_no, $product_details);
                
                //----------------------

                $shipping_details = "NAME: ".$report_row['address_details']['name']."\nPHONE: ".$report_row['address_details']['phone']."\nADDRESS 1: ".$report_row['address_details']['address_1']."\nADDRESS 2: ".$report_row['address_details']['address_2']."\nLANDMARK:".$report_row['address_details']['landmark']."\nCITY: ".$report_row['address_details']['city_name']."\nSTATE: ".$report_row['address_details']['state_name']."\nZIP CODE: ".$report_row['address_details']['zip_code'];


                //----------------------------

                $objPHPExcel->getActiveSheet()->setCellValue('D'.$row_no, $shipping_details);

                $delivery_date = $report_row['delivery_date']." (".$report_row['time_slot_details']['time_slot'].")";


                $objPHPExcel->getActiveSheet()->setCellValue('E'.$row_no, $delivery_date);
                $customer_details = "NAME: ".$report_row['customer_details']['full_name']."\n"."EMAIL: ".$report_row['customer_details']['email']."\nPHONE: ".$report_row['customer_details']['phone'];

                $objPHPExcel->getActiveSheet()->setCellValue('F'.$row_no, $customer_details);
                if(count($report_row['promo_code_details']) > 0)
                {
                    $promo_code = $report_row['promo_code_details']['promo_code'];
                }
                else
                {
                    $promo_code = "";
                }
                $objPHPExcel->getActiveSheet()->setCellValue('G'.$row_no, $promo_code);
               
                $objPHPExcel->getActiveSheet()->setCellValue('H'.$row_no, $report_row['total_price']);

                $objPHPExcel->getActiveSheet()->setCellValue('I'.$row_no, $report_row['delivery_charge']);

                $objPHPExcel->getActiveSheet()->setCellValue('J'.$row_no, $report_row['discount']);

                $objPHPExcel->getActiveSheet()->setCellValue('K'.$row_no, $report_row['order_total']);
                if($report_row['status'] == 'NOP')
                {
                    $order_status = "ORDER FAILED";
                }
                else if($report_row['status'] == 'P')
                {
                    $order_status = "PROCESSING";
                }
                else if($report_row['status'] == 'S')
                {
                    $order_status = "SHIPPING";
                }
                else if($report_row['status'] == 'D')
                {
                    $order_status = "COMPLETE";
                }
                else if($report_row['status'] == 'C')
                {
                    $order_status = "CANCELLED";
                }
                else if($report_row['status'] == 'SA')
                {
                    $order_status = "SELLER ACCEPTED";
                }
                else if($report_row['status'] == 'SR')
                {
                    $order_status = "SELLER REJECTED";
                }
                else if($report_row['status'] == 'R')
                {
                    $order_status = "RETURNED";
                }
                else
                {
                    $order_status = "UNKNOWN";
                }
                $objPHPExcel->getActiveSheet()->setCellValue('L'.$row_no, $order_status);
                
                $row_no++;
            }
        }       

        $exp_date_range = explode(' - ', $date_range);

        $start_date = trim($exp_date_range[0]);
        $exp_start_date = explode('/', $start_date);
        $start_date_is = $exp_start_date[2]."-".$exp_start_date[0]."-".$exp_start_date[1];

        $end_date = trim($exp_date_range[1]);
        $exp_end_date = explode('/', $end_date);
        $end_date_is = $exp_end_date[2]."-".$exp_end_date[0]."-".$exp_end_date[1];

        $filename = "ORDER-LIST[".$start_date_is." to ".$end_date_is."].xlsx";
        $objPHPExcel->getActiveSheet()->setTitle("Order List");

        header('Content-Type: application/vmd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="'.$filename.'"');
        header('Cache-Control: max-age-0');
        $writer = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
        //ob_end_clean();
        $writer->save('php://output');
        exit;

            
        }
        else
        {
            redirect(base_url('order'));
        }      

        

    }

    function gerenate_invoice()
    {
        $data = array();

        $html = $this->load->view('pdf-template/invoice', $data, true); 
        include_once (APPPATH.'third_party/mpdf/vendor/autoload.php');

        $pdf = new \Mpdf\Mpdf();
        $pdf->AddPage();
        $pdf->WriteHTML($html);
        $pdf_url = 'uploads/invoice.pdf';
        $content = $pdf->Output(FILE_UPLOAD_BASE_PATH.$pdf_url,'F');
        /*$update_data = array("payslip" => $pdf_url);
        $this->db->where("id", $record_id);
        $this->db->update("HRMS_employee_salary_record", $update_data);*/
    }


    function update_order_status()
    {
        //$this->load->model('order_model');
        if($this->common_model->user_login_check())
        {
            // allow to access
        }
        else
        {
            redirect(base_url(''));
        }

        $id = $this->input->post('id');
        $status = $this->input->post('status');

        $order_no = $this->order_model->get_order_no_by_order_id($id);
        $response = $this->order_model->update_order_status($order_no, $status); 

        if($response['status'] == "Y")
        {
            // call notification
            $ch = curl_init();
            $curlConfig = array(
                CURLOPT_URL => ADMIN_URL."notification/send_notification/".$order_no,
                CURLOPT_POST           => true,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_POSTFIELDS     => array(
                    'generate' => 'Y'
                )
            );
            curl_setopt_array($ch, $curlConfig);
            $result = curl_exec($ch);
            curl_close($ch); 

            $this->session->set_flashdata('success_message', $response['message']);
        }
        else
        {
            $this->session->set_flashdata('error_message', $response['message']);
        }

        
        
        //----------------------------------------------      
       
        echo json_encode($response);           
        

    }


    function return_order()
    {
        //$this->load->model('order_model');
        if($this->common_model->user_login_check())
        {
            // allow to access
        }
        else
        {
            redirect(base_url(''));
        }

        $id = $this->input->post('id');
        $status = "R";

        $order_no = $this->order_model->get_order_no_by_order_id($id);
        $response = $this->order_model->update_order_status($order_no, $status); 

        if($response['status'] == "Y")
        {
            // call notification
            $ch = curl_init();
            $curlConfig = array(
                CURLOPT_URL => ADMIN_URL."notification/send_notification/".$order_no,
                CURLOPT_POST           => true,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_POSTFIELDS     => array(
                    'generate' => 'Y'
                )
            );
            curl_setopt_array($ch, $curlConfig);
            $result = curl_exec($ch);
            curl_close($ch); 

            $this->session->set_flashdata('success_message', 'Order Successfully Return.');
        }
        else
        {
            $this->session->set_flashdata('error_message', $response['message']);
        }

        
        
        //----------------------------------------------      
       
        echo json_encode($response);           
        

    }

    function update_order_details()
    {
        //$this->load->model('order_model');
        if($this->common_model->user_login_check())
        {
            // allow to access
        }
        else
        {
            redirect(base_url(''));
        }

        $id = $this->input->post('id');
        $status = $this->input->post('status');
        $payment_method = $this->input->post('payment_method');

        $order_no = $this->order_model->get_order_no_by_order_id($id);
        $update_status = $this->order_model->update_order_details($order_no, $status, $payment_method); 
        // call notification
        $this->notification_model->send_notification($order_no);
        //----------------------------------------------      
        $this->session->set_flashdata('success_message', "Order details successfully updated.");

        $response = array("status" => "Y", "message" => "Successfully Updated.");

            echo json_encode($response);           
        

    }

    




    function details($id = 0)
    {

        // banner list
        $header_data = array();
        $page_data = array();
        $left_data = array();
        $footer_data = array();

        $header_data['title'] = "Order Details";
        $left_data['navigation'] = "Order"; 
        $left_data['sub_navigation'] = "order-list"; 

        // check login or not 

        if($this->common_model->user_login_check())
        {
            // allow 
            $seller_details = $this->common_model->get_admin_user_details();
            $header_data['seller_details'] = $seller_details;
            $left_data['seller_details'] = $seller_details;
        }
        else
        {
            redirect(base_url(''));
        }

        $order_no = $this->order_model->get_order_no_by_order_id($id);

        if($order_no == '')
        {
            redirect(base_url('order'));
        }
        else{
            $order_details = $this->order_model->order_details_by_no($order_no);
        }        
        

        
        $page_data['order_details'] = $order_details;
        
        $this->load->view('includes/header_view', $header_data);
        $this->load->view('includes/left_view', $left_data);
        $this->load->view('order/details_view', $page_data);
        $this->load->view('includes/footer_view', $footer_data);

    }
    
	
}
