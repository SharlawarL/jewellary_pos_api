<?php

class Login_model extends CI_Model
{
    public function module($headers){
        if($headers['Module'] == 'admin-login')
        {
            return $this->adminLogin($headers);
        } else if($headers['Module'] == 'user-login')
        {
            return $this->userLogin($headers);
        } else if($headers['Module'] == 'customer-login')
        {
            return $this->custLogin($headers);
        } else if($headers['Module'] == 'customer-get-otp')
        {
            return $this->custGetOtp($headers);
        } else if($headers['Module'] == 'customer-verify-otp')
        {
            return $this->custVerifyOtp($headers);
        } else if($headers['Module'] == 'customer-change-password')
        {
            return $this->custChangePass($headers);
        } else if($headers['Module'] == 'customer-forgot-password')
        {
            return $this->custForgotPass($headers);
        } else {
            $responce['status'] = 0;
            $responce['message'] = 'Something is wrong';
            $responce['data'] = '';
            return $responce;
        }
        
    }
    
    
    public function verify($token , $key){
        try {
            
        // print_r($key);die();
        // print_r(JWT::decode($token, $key, array('HS256')));die();
        $jwtToken_decode = JWT::decode($token, $key, array('HS256'));
        $decodedData = (array) $jwtToken_decode;
        
        // print_r($decodedData);die();
        
        
        if($decodedData)
        {
            $data =array();
            
            if($decodedData['type'] == 'headOffice')
            {
                $this->db->where('user_name',$decodedData['username']);
                $result = $this->db->get('users_headoffice');
                $data = $result->result_array();
            } else if($decodedData['type'] == 'emp')
            {
                $this->db->where('user_name',$decodedData['username']);
                $result = $this->db->get('users');
                $data = $result->result_array();
            } else if($decodedData['type'] == 'cust')
            {
                $this->db->where('mobile',$decodedData['username']);
                $result = $this->db->get('cust');
                $data = $result->result_array();
            } else {
                return false;
            }
            
            if($data)
            {
                return true;
            } else {
                return false;
            }
        }
        }
        catch (customException $e) {
          return false;
        }
        
        
        // print_r($decodedData);die();
    }

    function adminLogin($headers){
        if((!$_POST['username']) && (($_POST['username'] == null)))
        {
            $responce['status'] = 0;
            $responce['message'] = 'Username is required';
            $responce['data'] = '';
            return $responce;
        } else if((!$_POST['password']) && (($_POST['password'] == null)))
        {
            $responce['status'] = 0;
            $responce['message'] = 'Password is required';
            $responce['data'] = '';
            return $responce;
        } else {
            $this->db->where('user_name',$_POST['username']);
            $this->db->where('password',$_POST['password']);
            $result = $this->db->get('users_headoffice');
            $data = $result->result_array();
            
            //print_r($this->db->last_query());die();
            
            $user ='';
            foreach($data as $userData)
            {
                $user = $userData['user_name'];
            }
            
            $keyToken = array(
                'username'=>$_POST['username'],
                'type'=> 'headOffice',
                'dat' => date("Y-m-d")
                );
            
            //details
            $data['token'] =  JWT::encode($keyToken, $headers['key']);
            
            //todays rates details
            $rate =  $this->db->get('master_today_market_price');
            $data['todays'] = $rate->result_array();

            //todays rates details
            $purity =  $this->db->get('master_item_purity');
            $data['purity'] = $purity->result_array();
            
            if($user == $_POST['username'])
            {
                $responce['status'] = 1;
                $responce['message'] = 'Login successfull!';
                $responce['data'] = $data;
                return $responce;
            } else {
                $responce['status'] = 0;
                $responce['message'] = 'Username password incurrect';
                $responce['data'] = '';
                return $responce;
            }
        }
    }
    
    function userLogin($headers){
        if((!$_POST['username']) && (($_POST['username'] == null)))
        {
            $responce['status'] = 0;
            $responce['message'] = 'Username is required';
            $responce['data'] = '';
            return $responce;
        } else if((!$_POST['password']) && (($_POST['password'] == null)))
        {
            $responce['status'] = 0;
            $responce['message'] = 'Password is required';
            $responce['data'] = '';
            return $responce;
        } else {
            
            // print_r($_POST);die();
            
            $this->db->where('user_name',$_POST['username']);
            $this->db->where('password',$_POST['password']);
            //  $this->db->join('master_branch', 'users.branch= master_branch.id');
            $result = $this->db->get('users');
            $data = $result->result_array();
            
            // print_r(count($data));die();
            
            $user ='';
            foreach($data as $userData)
            {
                $user = $userData['user_name'];
            }
            
            $keyToken = array(
                'username'=>$_POST['username'],
                'type'=> 'emp',
                'dat' => date("Y-m-d")
                );
            
            //details
            $data['token'] =  JWT::encode($keyToken, $headers['key']);
            
            // print_r($data['token']);die();


            //todays rates details
            $rate =  $this->db->get('master_today_market_price');
            $data['todays'] = $rate->result_array();
            
            //todays rates details
            $purity =  $this->db->get('master_item_purity');
            $data['purity'] = $purity->result_array();
            
            if($user == $_POST['username'])
            {
                $responce['status'] = 1;
                $responce['message'] = 'Login successfull!';
                $responce['data'] = $data;
                return $responce;
            } else {
                $responce['status'] = 0;
                $responce['message'] = 'Username password incorrect';
                $responce['data'] = '';
                return $responce;
            }
        }
        
    }
    
    
    function custLogin($headers){
        if((!$_POST['mobile']) && (($_POST['mobile'] == null)))
        {
            $responce['status'] = 0;
            $responce['message'] = 'Mobile is required';
            $responce['data'] = '';
            return $responce;
        } else if((!$_POST['password']) && (($_POST['password'] == null)))
        {
            $responce['status'] = 0;
            $responce['message'] = 'Password is required';
            $responce['data'] = '';
            return $responce;
        } else {
            $this->db->where('mobile',$_POST['mobile']);
            $this->db->where('password',$_POST['password']);
            $result = $this->db->get('cust');
            $data = $result->result_array();
            
            // print_r($data);die();
            
            $user ='';
            foreach($data as $userData)
            {
                $user = $userData['mobile'];
            }
            
            $keyToken = array(
                'username'=>$_POST['mobile'],
                'type'=> 'cust',
                'dat' => date("Y-m-d")
                );
            
            //details
            $data['token'] =  JWT::encode($keyToken, $headers['key']);


            //todays rates details
            $rate =  $this->db->get('master_today_default_price');
            $data['todays'] = $rate->result_array();
            
            //todays rates details
            $purity =  $this->db->get('master_item_purity');
            $data['purity'] = $purity->result_array();
            
            if($user == $_POST['mobile'])
            {
                $responce['status'] = 1;
                $responce['message'] = 'Login successfull!';
                $responce['data'] = $data;
                return $responce;
            } else {
                $responce['status'] = 0;
                $responce['message'] = 'Username password incorrect';
                $responce['data'] = '';
                return $responce;
            }
        }
        
    }
    
    
    function custGetOtp($headers){
        if((!$_POST['mobile']) && (($_POST['mobile'] == null)))
        {
            $responce['status'] = 0;
            $responce['message'] = 'Mobile is required';
            $responce['data'] = '';
            return $responce;
        } else {
            $this->db->where('mobile',$_POST['mobile']);
            $result = $this->db->get('cust');
            $data = $result->result_array();
            
            if($data)
            {
                $otp = rand(1000,9999);
                $otpData = array(
                    "mobile" => $_POST['mobile'],
                    "OTP"=> $otp
                    );
                
                $otpData = array(
                    "otp"=> $otp
                    );
                    
                $this->db->where('mobile', $_POST['mobile']);
                $result = $this->db->update('cust', $otpData);
                
                if($otpData)
                {
                    $responce['status'] = 1;
                    $responce['message'] = 'OTP Sent';
                    $responce['data'] = $otpData;
                    return $responce;
                } else {
                    $responce['status'] = 0;
                    $responce['message'] = 'Check Mobile Number';
                    $responce['data'] = '';
                    return $responce;
                }
                
            } else {
                $responce['status'] = 0;
                $responce['message'] = 'User Not Exits';
                $responce['data'] = '';
                return $responce;
            }
            
        }
        
    }
    
    function custVerifyOtp($headers){
        if((!$_POST['mobile']) && (($_POST['mobile'] == null)))
        {
            $responce['status'] = 0;
            $responce['message'] = 'Mobile is required';
            $responce['data'] = '';
            return $responce;
        } else if(!isset($_POST['otp']))
        {
            $responce['status'] = 0;
            $responce['message'] = 'Mobile is required';
            $responce['data'] = '';
            return $responce;
        } else {
            $this->db->where('mobile',$_POST['mobile']);
            $result = $this->db->get('cust');
            $data = $result->result_array();
            
            if($data)
            {
                
                if($_POST['otp'] == $data[0]['otp'])
                {
                    $responce['status'] = 1;
                    $responce['message'] = 'OTP verified';
                    $responce['data'] = $data;
                    return $responce;
                } else {
                    $responce['status'] = 0;
                    $responce['message'] = 'Check OTP Number';
                    $responce['data'] = '';
                    return $responce;
                }
                
            } else {
                $responce['status'] = 0;
                $responce['message'] = 'User Not Exits';
                $responce['data'] = '';
                return $responce;
            }
            
        }
        
    }
    
    function custChangePass($headers){
        if((!$_POST['mobile']) && (($_POST['mobile'] == null)))
        {
            $responce['status'] = 0;
            $responce['message'] = 'Mobile is required';
            $responce['data'] = '';
            return $responce;
        } else {
            $this->db->where('mobile',$_POST['mobile']);
            $result = $this->db->get('cust');
            $data = $result->result_array();
            if($data)
            {
            
            if($data[0]['password'] == $_POST['passwordold'])
            {
                
                $otpData = array(
                    "password"=> $_POST['passwordnew']
                    );
                    
                $this->db->where('mobile', $_POST['mobile']);
                $result = $this->db->update('cust', $otpData);
                
                if($result)
                {
                    $responce['status'] = 1;
                    $responce['message'] = 'Password Changed';
                    $responce['data'] = '';
                    return $responce;
                } else {
                    $responce['status'] = 0;
                    $responce['message'] = 'Value';
                    $responce['data'] = '';
                    return $responce;
                }
                
            } else {
                $responce['status'] = 0;
                $responce['message'] = 'Old Password Not Match';
                $responce['data'] = '';
                return $responce;
            }
            
            } else {
                $responce['status'] = 0;
                $responce['message'] = 'User Not Exits';
                $responce['data'] = '';
                return $responce;
            }
        }
        
    }
    
    function custForgotPass($headers){
        if((!$_POST['mobile']) && (($_POST['mobile'] == null)))
        {
            $responce['status'] = 0;
            $responce['message'] = 'Mobile is required';
            $responce['data'] = '';
            return $responce;
        } else {
            $this->db->where('mobile',$_POST['mobile']);
            $result = $this->db->get('cust');
            $data = $result->result_array();
            if($data)
            {

                
                $otpData = array(
                    "password"=> $_POST['passwordnew']
                    );
                    
                $this->db->where('mobile', $_POST['mobile']);
                $result = $this->db->update('cust', $otpData);
                
                if($result)
                {
                    $responce['status'] = 1;
                    $responce['message'] = 'Password Changed';
                    $responce['data'] = '';
                    return $responce;
                } else {
                    $responce['status'] = 0;
                    $responce['message'] = 'Value';
                    $responce['data'] = '';
                    return $responce;
                }
            
            } else {
                $responce['status'] = 0;
                $responce['message'] = 'User Not Exits';
                $responce['data'] = '';
                return $responce;
            }
        }
        
    }
}