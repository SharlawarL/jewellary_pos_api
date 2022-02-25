<?php

class Account_model extends CI_Model
{
    public function module($headers){
        if($headers['Module'] == 'save-account-master')
        {
            return $this->saveAccountMaster($headers);
        } else {
            $responce['status'] = 0;
            $responce['message'] = 'Something is wrong';
            $responce['data'] = '';
            return $responce;
        }
        
    }

    function saveAccountMaster($headers){
        if((!$_POST['login_user']) && (($_POST['login_user'] == null)))
        {
            $responce['status'] = 0;
            $responce['message'] = 'login_user is required';
            $responce['data'] = '';
            return $responce;
        } else if((!$_POST['account']) && (($_POST['account'] == null)))
        {
            $responce['status'] = 0;
            $responce['message'] = 'Account Group Name is required';
            $responce['data'] = '';
            return $responce;
        } else if((!$_POST['itemType']) && (($_POST['itemType'] == null)))
        {
            $responce['status'] = 0;
            $responce['message'] = 'Under Name is required';
            $responce['data'] = '';
            return $responce;
        } else {
            
            // print_r($_POST);die();
            
            $insertData = array(
                'atype' => $_POST['account'],
                'under' => $_POST['itemType'],
                'company' => $_POST['company'],
            );
            
            
            $data =  $this->db->insert('master_accounts', $insertData);
            
            if($data)
            {
                $responce['status'] = 1;
                $responce['message'] = 'Account Master Succefully created';
                $responce['data'] = $data;
                return $responce;
            } else {
                $responce['status'] = 0;
                $responce['message'] = 'Samething is wrong';
                $responce['data'] = '';
                return $responce;
            }
        }
        
    }
    
    
}