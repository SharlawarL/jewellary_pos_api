<?php

class Common_model extends CI_Model
{
    public function module($headers){
        if($headers['Module'] == 'company-details')
        {
            return $this->company($headers);
        } else {
            $responce['status'] = 0;
            $responce['message'] = 'Something is wrong';
            $responce['data'] = '';
            return $responce;
        }
        
    }

    function company($headers){
            //shop details
            // $this->db->where('id', '1');
            $shop =  $this->db->get('setting_company');
            $data = $shop->result_array();
            

            if(count($data) > 0)
            {
                $responce['status'] = 1;
                $responce['message'] = 'Company Deatils!';
                $responce['data'] = $data;
                return $responce;
            } else {
                $responce['status'] = 0;
                $responce['message'] = 'Samething is wrong';
                $responce['data'] = '';
                return $responce;
            }
        
    }
    
    function verify($token,$key){
            // print_r($key);die();
            // $jwtToken_decode = JWT::decode($token, $key, array('HS256'));
            // $decodedData = (array) $jwtToken_decode;
            // print_r($decodedData);die();
        
    }
}