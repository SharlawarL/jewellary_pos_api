<?php

class Profile_model extends CI_Model
{
    public function module($headers){
        if($headers['Module'] == 'update-profile')
        {
            return $this->updateProfile($headers);
        } else if($headers['Module'] == 'update-account')
        {
            return $this->updateAccount($headers);
        } else if($headers['Module'] == 'get-total-user')
        {
            return $this->getTotalUser($headers);
        } else if($headers['Module'] == 'get-total-branch-user')
        {
            return $this->getTotalBranchUser($headers);
        } else if($headers['Module'] == 'get-total-admin-user')
        {
            return $this->getTotalAdminUser($headers);
        } else if($headers['Module'] == 'save-user')
        {
            return $this->saveUser($headers);
        }  else if($headers['Module'] == 'save-admin-user')
        {
            return $this->saveAdminUser($headers);
        } else if($headers['Module'] == 'delete-admin-user')
        {
            return $this->deleteAdminUser($headers);
        }  else if($headers['Module'] == 'delete-user')
        {
            return $this->deleteUser($headers);
        } else if($headers['Module'] == 'update-user')
        {
            return $this->updateUser($headers);
        } else {
            $responce['status'] = 0;
            $responce['message'] = 'Something is wrong';
            $responce['data'] = '';
            return $responce;
        }
        
    }

    function updateProfile($headers){
        if(!$_POST['username'])
        {
            $responce['status'] = 0;
            $responce['message'] = 'Username is required';
            $responce['data'] = '';
            return $responce;
        } else {
            $this->db->set('company',$_POST['shopname']);
            $this->db->where('company', $_POST['preShopname']);
            $result = $this->db->update('setting_company');
            
            //shop details
            $shop =  $this->db->get('setting_company');
            $data = $shop->result_array();

            if($result)
            {
                $responce['status'] = 1;
                $responce['message'] = 'Updated successfull!';
                $responce['data'] = $data;
                return $responce;
            } else {
                $responce['status'] = 0;
                $responce['message'] = 'Failed to update';
                $responce['data'] = '';
                return $responce;
            }
        }
    }
    
    
    function updateAccount($headers){
        if(!$_POST['username'])
        {
            $responce['status'] = 0;
            $responce['message'] = 'Username is required';
            $responce['data'] = '';
            return $responce;
        } else {
            
            //shop details
            $this->db->where('username',$_POST['username']);
            $shop =  $this->db->get('admin');
            $user = $shop->result_array();
            
            if($user[0]['password'] ==  $_POST['oldPassword'])
            {
                $this->db->set('username',$_POST['newUsername']);
                $this->db->set('password',$_POST['password']);
                $this->db->where('id', 1);
                $result = $this->db->update('admin');
                
                //shop details
                $this->db->where('id', '1');
                $shop =  $this->db->get('shop');
                $data = $shop->result_array();
    
                if($result)
                {
                    $responce['status'] = 1;
                    $responce['message'] = 'Updated successfull!';
                    $responce['data'] = $data;
                    return $responce;
                } else {
                    $responce['status'] = 0;
                    $responce['message'] = 'Failed to update';
                    $responce['data'] = '';
                    return $responce;
                }
                
            } else {
                $responce['status'] = 0;
                $responce['message'] = 'Old Password not match';
                $responce['data'] = '';
                return $responce;
            }
            
        }
    }
    
    function getTotalUser($headers){
        if((!$_POST['login_user']) && (($_POST['login_user'] == null)))
        {
            $responce['status'] = 0;
            $responce['message'] = 'login_user is required';
            $responce['data'] = '';
            return $responce;
        } else {
            // $this->db->join('master_branch', 'users.branch= master_branch.id');
            // $this->db-select('master_branch.branch as branchname');
            $shop =  $this->db->get('users');
            $data = $shop->result_array();
            

            if(count($data) > 0)
            {
                $responce['status'] = 1;
                $responce['message'] = 'Total users details';
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
    
    function getTotalBranchUser($headers){
        if((!$_POST['login_user']) && (($_POST['login_user'] == null)))
        {
            $responce['status'] = 0;
            $responce['message'] = 'login_user is required';
            $responce['data'] = '';
            return $responce;
        } else {
            $this->db->where('user_name !=',$_POST['login_user']);
            $this->db->where('branch',$_POST['branch']);
            $shop =  $this->db->get('users');
            $data = $shop->result_array();
            

            if(count($data) > 0)
            {
                $responce['status'] = 1;
                $responce['message'] = 'Total users details';
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
    
    function getTotalAdminUser($headers){
        if((!$_POST['login_user']) && (($_POST['login_user'] == null)))
        {
            $responce['status'] = 0;
            $responce['message'] = 'login_user is required';
            $responce['data'] = '';
            return $responce;
        } else {
            
            // $this->db->order_by("id", "desc");
            // $this->db->join('master_branch', 'users.branch= master_branch.id');
            // $this->db-select('master_branch.branch as branchname');
             $this->db->where('user_name !=',$_POST['login_user']);
            $shop =  $this->db->get('users_headoffice');
            $data = $shop->result_array();
            

            if(count($data) > 0)
            {
                $responce['status'] = 1;
                $responce['message'] = 'Total users details';
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
    
    
    function saveUser($headers){
        if(!$_POST['login_user'])
        {
            $responce['status'] = 0;
            $responce['message'] = 'login_user is required';
            $responce['data'] = '';
            return $responce;
        } else if(!$_POST['username'])
        {
            $responce['status'] = 0;
            $responce['message'] = 'Username is required';
            $responce['data'] = '';
            return $responce;
        } else if(!$_POST['branchname'])
        {
            $responce['status'] = 0;
            $responce['message'] = 'Branch is required';
            $responce['data'] = '';
            return $responce;
        } else if(!$_POST['password'])
        {
            $responce['status'] = 0;
            $responce['message'] = 'password is required';
            $responce['data'] = '';
            return $responce;
        } else if(!$_POST['role'])
        {
            $responce['status'] = 0;
            $responce['message'] = 'role is required';
            $responce['data'] = '';
            return $responce;
        } else {
            
            //shop details
            $this->db->where('user_name',$_POST['username']);
            $shop =  $this->db->get('users');
            $user = $shop->result_array();
            
            
            if(!$user)
            {
                
            
                $insertData = array(
                    'branch' => $_POST['branchname'],
                    'user_name' => $_POST['username'],
                    'password' => $_POST['password'],
                    'user_level' => $_POST['role'],
                    'last_modified' => date("Y/m/d h:i:sa"),
                    'modified_by' =>  $_POST['login_user'] 
                );
                $data =  $this->db->insert('users', $insertData);
    
                if($data)
                {
                    $responce['status'] = 1;
                    $responce['message'] = 'User submitted successfull!';
                    $responce['data'] = $data;
                    return $responce;
                } else {
                    $responce['status'] = 0;
                    $responce['message'] = 'Something is wrong';
                    $responce['data'] = '';
                    return $responce;
                }
                
            } else {
                $responce['status'] = 0;
                $responce['message'] = 'User already exits';
                $responce['data'] = '';
                return $responce;
            }
            
        }
    }
    
    
    function saveAdminUser($headers){
        if(!$_POST['login_user'])
        {
            $responce['status'] = 0;
            $responce['message'] = 'login_user is required';
            $responce['data'] = '';
            return $responce;
        } else if(!$_POST['username'])
        {
            $responce['status'] = 0;
            $responce['message'] = 'Username is required';
            $responce['data'] = '';
            return $responce;
        }  else if(!$_POST['password'])
        {
            $responce['status'] = 0;
            $responce['message'] = 'password is required';
            $responce['data'] = '';
            return $responce;
        } else {
            
            //shop details
            $this->db->where('user_name',$_POST['username']);
            $shop =  $this->db->get('users_headoffice');
            $user = $shop->result_array();
            
            
            if(!$user)
            {
               
                $insertData = array(
                    'user_name' => $_POST['username'],
                    'password' => $_POST['password'],
                    'user_level' => $_POST['role'],
                    'last_modified' => date("Y/m/d h:i:sa")
                );
                $data =  $this->db->insert('users_headoffice', $insertData);
    
                if($data)
                {
                    $responce['status'] = 1;
                    $responce['message'] = 'User submitted successfull!';
                    $responce['data'] = $data;
                    return $responce;
                } else {
                    $responce['status'] = 0;
                    $responce['message'] = 'Something is wrong';
                    $responce['data'] = '';
                    return $responce;
                }
                
            } else {
                $responce['status'] = 0;
                $responce['message'] = 'User already exits';
                $responce['data'] = '';
                return $responce;
            }
            
        }
    }
    
    
     function deleteAdminUser($headers){
        if(!$_POST['login_user'])
        {
            $responce['status'] = 0;
            $responce['message'] = 'login_user is required';
            $responce['data'] = '';
            return $responce;
        } else if(!$_POST['username'])
        {
            $responce['status'] = 0;
            $responce['message'] = 'Username is required';
            $responce['data'] = '';
            return $responce;
        }  else {
            
                $this->db->where('user_name', $_POST['username']);
                $result =  $this->db->delete('users_headoffice');
    
                if($result)
                {
                    $responce['status'] = 1;
                    $responce['message'] = 'User deleted!';
                    $responce['data'] = "";
                    return $responce;
                } else {
                    $responce['status'] = 0;
                    $responce['message'] = 'Something is wrong';
                    $responce['data'] = '';
                    return $responce;
                }
                
            
            
        }
    }
    
    function deleteUser($headers){
        if(!isset($_POST['login_user']))
        {
            $responce['status'] = 0;
            $responce['message'] = 'login_user is required';
            $responce['data'] = '';
            return $responce;
        } else if(!isset($_POST['username']))
        {
            $responce['status'] = 0;
            $responce['message'] = 'Username is required';
            $responce['data'] = '';
            return $responce;
        }  else {
            
                $this->db->where('user_name', $_POST['username']);
                $this->db->where('branch', $_POST['branch']);
                $result =  $this->db->delete('users');
    
                if($result)
                {
                    $responce['status'] = 1;
                    $responce['message'] = 'User deleted!';
                    $responce['data'] = "";
                    return $responce;
                } else {
                    $responce['status'] = 0;
                    $responce['message'] = 'Something is wrong';
                    $responce['data'] = '';
                    return $responce;
                }
                
            
            
        }
    }
    
    function updateUser($headers){
        if(!isset($_POST['login_user']))
        {
            $responce['status'] = 0;
            $responce['message'] = 'login_user is required';
            $responce['data'] = '';
            return $responce;
        } else if(!isset($_POST['username']))
        {
            $responce['status'] = 0;
            $responce['message'] = 'Username is required';
            $responce['data'] = '';
            return $responce;
        }  else {
                $data = array(
                    'password'=> $_POST['password']
                    );
                    
                $this->db->where('user_name', $_POST['username']);
                $this->db->where('branch', $_POST['branch']);
                
                $result =  $this->db->update('users',$data);
    
                if($result)
                {
                    $responce['status'] = 1;
                    $responce['message'] = 'User udpated!';
                    $responce['data'] = "";
                    return $responce;
                } else {
                    $responce['status'] = 0;
                    $responce['message'] = 'Something is wrong';
                    $responce['data'] = '';
                    return $responce;
                }
                
            
            
        }
    }
}