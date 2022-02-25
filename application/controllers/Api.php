<?php
Header('Access-Control-Allow-Origin: *'); //for allow any domain, insecure
Header('Access-Control-Allow-Headers: *'); //for allow any headers, insecure
Header('Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE'); //method allowed

defined('BASEPATH') OR exit('No direct script access allowed');
// require APPPATH.'/libraries/JWT.php';

class Api extends CI_Controller {
    
    public function __construct(){
        parent::__construct();
    }

	public function apiRequest()
	{
        //load files
        $this->load->database();

        //variables
        $headers = headers();
        $responce = array();
        $headers['key'] = "Lalit_Selrom";
        
        //models
        $this->load->model('Company/Company_model','Company');
        $this->load->model('Login/Login_model','Login');
        $this->load->model('Profile/Profile_model','Profile');
        $this->load->model('Goldsmith/Goldsmith_model','Goldsmith');
        $this->load->model('Account/Account_model','Account');
         
        
        $temp = $this->input->post();
        if(count($temp) > 0)
        {
            $_POST = $temp;
        } else{ 
            $_POST = json_decode(file_get_contents('php://input'),true);
        }
        
        if(!isset($headers['Action']))
        {
            $headers['Action'] = isset($headers['action'])?$headers['action']:'';
            $headers['Module'] = isset($headers['module'])?$headers['module']:'';
        }
        
        
        
        $verify = false;
        
        
        
        
        if(($headers['Action'] == 'Login')
        || ($headers['Module'] == 'company-details') 
        || ($headers['Module'] == 'user-login') 
        || ($headers['Module'] == 'admin-login') 
        || ($headers['Module'] == 'save-customer'))
        {
            $verify = true;
        } else {
            if(isset($headers['Authorization']))
            {
                // $verify = true;
                
                $tokenData = (isset($headers['Authorization']))?explode(" ",$headers['Authorization']):[];
                
                if($tokenData[1])
                {
                    $verify =  $this->Login->verify($tokenData[1], $headers['key']);
                } else {
                    $responce['status'] = 0;
                    $responce['message'] = 'Check Authorization key';
                    $responce['data'] = '';
                    return $responce;
                }
                
            }
        }
        
        if($verify)
        {
        //check
        if($_POST)
        {
            if((isset($headers['Action']))&&($headers != null)&&($headers['Action'] == 'Company')&&($_POST != null))
            {
                $responce = $this->Company->module($headers);

            } else if((isset($headers['Action']))&&($headers != null)&&($headers['Action'] == 'Login')&&($_POST != null))
            {
                $responce = $this->login($headers);

            } else if((isset($headers['Action']))&&($headers != null)&&($headers['Action'] == 'Account')&&($_POST != null))
            {
                $responce = $this->Account->module($headers);

            } else if((isset($headers['Action']))&&($headers != null)&&($headers['Action'] == 'Profile')&&($_POST != null))
            {
                $responce = $this->Profile->module($headers);

            } else if((isset($headers['Action']))&&($headers != null)&&($headers['Action'] == 'Item')&&($_POST != null))
            {
                $responce = $this->item($headers);

            } else if((isset($headers['Action']))&&($headers != null)&&($headers['Action'] == 'Customer')&&($_POST != null))
            {
                $responce = $this->customer($headers);

            } else if((isset($headers['Action']))&&($headers != null)&&($headers['Action'] == 'Sales')&&($_POST != null))
            {
                $responce = $this->sales($headers);

            } else if((isset($headers['Action']))&&($headers != null)&&($headers['Action'] == 'Supplier')&&($_POST != null))
            {
                $responce = $this->supplier($headers);

            }  else if((isset($headers['Action']))&&($headers != null)&&($headers['Action'] == 'Stock')&&($_POST != null))
            {
                $responce = $this->stock($headers);

            }  else if((isset($headers['Action']))&&($headers != null)&&($headers['Action'] == 'Purchase')&&($_POST != null))
            {
                $responce = $this->purchase($headers);

            } else if((isset($headers['Action']))&&($headers != null)&&($headers['Action'] == 'Schema')&&($_POST != null))
            {
                $responce = $this->schema($headers);

            } else if((isset($headers['Action']))&&($headers != null)&&($headers['Action'] == 'Estimate')&&($_POST != null))
            {
                $responce = $this->estimate($headers);

            } else if((isset($headers['Action']))&&($headers != null)&&($headers['Action'] == 'Mobile')&&($_POST != null))
            {
                $responce = $this->mobile($headers);

            } else if((isset($headers['Action']))&&($headers != null)&&($headers['Action'] == 'Hr')&&($_POST != null))
            {
                $responce = $this->hr($headers);

            } else if((isset($headers['Action']))&&($headers != null)&&($headers['Action'] == 'Goldsmith')&&($_POST != null))
            {
                
	            $responce = $this->Goldsmith->module($headers);
            } else {
                $responce['status'] = 2;
                $responce['message'] = 'Action & Module Missing';
                $responce['data'] = '';
            }
        } else {
            $responce['status'] = 3;
            $responce['message'] = 'All field required';
            $responce['data'] = '';
        }
        
        } else {
            $responce['status'] = 4;
            $responce['message'] = 'API Authontication fail';
            $responce['data'] = '';
        }
        
        print_r(json_encode($responce)); 
        
	}
	
	
	public function login($headers){
	     return $this->Login->module($headers);
	}
	
	public function verify($token, $key){
	     return $this->Login->verify($token, $key);
	}
	
	public function item($headers){
	    $this->load->model('Item/Item_model','Item');
	    return $this->Item->module($headers);
	}
	
	public function customer($headers){
	    $this->load->model('Customer/Customer_model','Customer');
	    return $this->Customer->module($headers);
	}
	
	public function sales($headers){
	    $this->load->model('Sales/Sales_model','Sales');
	    return $this->Sales->module($headers);
	}
	
	public function supplier($headers){
	    $this->load->model('Supplier/Supplier_model','Supplier');
	    return $this->Supplier->module($headers);
	}
	
	public function stock($headers){
	    $this->load->model('Stock/Stock_model','Stock');
	    return $this->Stock->module($headers);
	}
	public function purchase($headers){
	    $this->load->model('Purchase/Purchase_model','Purchase');
	    return $this->Purchase->module($headers);
	}
	
	public function schema($headers){
	    $this->load->model('Schema/Schema_model','Schema');
	    return $this->Schema->module($headers);
	}
	
	public function estimate($headers){
	    $this->load->model('Estimate/Estimate_model','Estimate');
	    return $this->Estimate->module($headers);
	}
	
	public function mobile($headers){
	    $this->load->model('Mob/Mob_model','Mobile');
	    return $this->Mobile->module($headers);
	}
	
	public function common($token,$key){
	    $this->load->model('Common/Common_model','Common');
	    return $this->Common->verify($token,$key);
	}
	
	public function hr($headers){
	    $this->load->model('Hr/Hr_model','Hr');
	    return $this->Hr->module($headers);
	}
	
}
