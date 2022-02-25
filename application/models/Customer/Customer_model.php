<?php

class Customer_model extends CI_Model
{
    public function module($headers){
        if($headers['Module'] == 'get-customer')
        {
            return $this->getCustomer($headers);
        } else if($headers['Module'] == 'get-drop-customer')
        {
            return $this->getCustomerDrop($headers);
        }else if($headers['Module'] == 'save-customer')
        {
            return $this->saveCustomer($headers);
        }else if($headers['Module'] == 'save-customer-agent')
        {
            return $this->saveCustomerAgent($headers);
        }  else if($headers['Module'] == 'update-customer')
        {
            return $this->updateCustomer($headers);
        }  
        else if($headers['Module'] == 'delete-customer')
        {
            return $this->deleteCustomer($headers);
        }  
        else if($headers['Module'] == 'get-last-agent')
        {
            return $this->getLastAgent($headers);
        }
        else {
            $responce['status'] = 0;
            $responce['message'] = 'Module missing';
            $responce['data'] = '';
            return $responce;
        }
        
    }
    
    function getCustomer($headers){
        if((!$_POST['login_user']) && (($_POST['login_user'] == null)))
        {
            $responce['status'] = 0;
            $responce['message'] = 'login_user is required';
            $responce['data'] = '';
            return $responce;
        } else {
            
            $date = date("Y-m-d");
            
            if(isset($_POST['type']))
            {
            
            if($_POST['type'] == 'customer-due-report')
            {
                $query11 = $this->db->query("SELECT billno,date_format(dat,'%d/%m/%Y') as bdate,date_format(ddate,'%d/%m/%Y') as ddate,cid,cname,tot,paid,tot-paid 
                FROM cust_bal 
                WHERE tot-paid>0 and branch='".$_POST['branch']."' ORDER BY dat,billno");
                $data = $query11->result_array();
                
            }
            else if($_POST['type'] == 'customer-overdue-report')
            {
                $query11 = $this->db->query("SELECT billno,date_format(dat,'%d/%m/%Y') as bdate,date_format(ddate,'%d/%m/%Y') as ddate,datediff(ddate,'".$date."') as diff, a.cid,a.cname,tot-paid,mobile 
                FROM cust_bal a,cust b 
                WHERE ddate < '".$date."' AND tot-paid>0 AND a.cid=b.cid AND a.branch=b.branch AND a.branch='".$_POST['branch']."' ORDER BY  ddate,billno");
                $data = $query11->result_array();
                
            }
            else if($_POST['type'] == 'customer-overall-balance')
            {
                $query11 = $this->db->query("select a.cid,a.cname,city,mobile,sum(tot-paid) 
                from cust_bal a,cust b 
                where a.cid=b.cid AND a.branch=b.branch AND a.branch='".$_POST['branch']."'  GROUP BY a.cid having sum(tot-paid)>0 order by city");
                $data = $query11->result_array();
                
            }
            else if($_POST['type'] == 'customer-receipt-report')
            {
                $query11 = $this->db->query("SELECT DISTINCT  sno,date_format(dat,'%d/%m/%Y') as bdate,tim,cid,cname,net,pby,remarks,user,last 
                FROM  cust_pay 
                WHERE dat BETWEEN  '".$_POST['from']."' AND '".$_POST['to']."' AND branch='".$_POST['branch']."' ORDER BY dat,sno");
                $data = $query11->result_array();
                
            }
            else if($_POST['type'] == 'customer-bill-report')
            {
                $query11 = $this->db->query("SELECT sno,cid,cname,billno,date_format(dat,'%d/%m/%Y') as bdate,date_format(ddate,'%d/%m/%Y') as ddate,amount,remarks,user,last 
                FROM cust_bill 
                WHERE dat BETWEEN '".$_POST['from']."' AND '".$_POST['to']."' AND branch='".$_POST['branch']."' ORDER BY  dat,sno");
                $data = $query11->result_array();
                
            }
            else if($_POST['type'] == 'customer-list-report')
            {
                $query11 = $this->db->query("SELECT cid,cname,nominee,add1,add2,add3,add4,city,pincode,mobile,phone,email,state,scode,category,tax_no,climit,duedays,DATE_FORMAT(date_of_birth,'%d/%m/%Y') as bdate,DATE_FORMAT(wedding_date,'%d/%m/%Y') as wdate,id_proof,remarks 
                FROM cust 
                WHERE branch='".$_POST['branch']."' and cname!='' ORDER BY cid");
                $data = $query11->result_array();
                
            }
            else if($_POST['type'] == 'agent-list')
            {
                $query11 =  $this->db->get('agent');
                $data = $query11->result_array();
                
            }
             else if($_POST['type'] == 'customer-list-area-report')
            {
                $query11 = $this->db->query("SELECT cid,cname,nominee,add1,add2,add3,add4,city,pincode,mobile,category,phone,email,state,scode,tax_no,climit,duedays,DATE_FORMAT(date_of_birth,'%d/%m/%Y') as bdate,DATE_FORMAT(wedding_date,'%d/%m/%Y') as wdate,id_proof,remarks 
                FROM cust 
                WHERE branch='".$_POST['branch']."' and city='".$_POST['drop']."' ORDER BY cid");
                $data = $query11->result_array();
                
            }
            else if($_POST['type'] == 'customer-state')
            {
                $query11 = $this->db->query("SELECT cid,cname,nominee,add1,add2,add3,add4,city,pincode,mobile,phone,category,email,state,scode,tax_no,climit,duedays,DATE_FORMAT(date_of_birth,'%d/%m/%Y') as bdate,DATE_FORMAT(wedding_date,'%d/%m/%Y') as wdate,id_proof,remarks 
                FROM cust 
                WHERE branch='".$_POST['branch']."' and state='".$_POST['drop']."' ORDER BY cid");
                $data = $query11->result_array();
                
            }
            }
            else {
                $this->db->order_by("cid", "desc");
                // $this->db->join('master_branch', 'users.branch= master_branch.id');
                // $this->db-select('master_branch.branch as branchname');
                $this->db->where('cname !=','');
                $shop =  $this->db->get('cust');
                $data = $shop->result_array();
            }
            

            if(count($data) > 0)
            {
                $responce['status'] = 1;
                $responce['message'] = 'Customer details';
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
    function getCustomerDrop($headers){
        if((!$_POST['login_user']) && (($_POST['login_user'] == null)))
        {
            $responce['status'] = 0;
            $responce['message'] = 'login_user is required';
            $responce['data'] = '';
            return $responce;
        } else {
            $data = array();
            
            if($_POST['type'] == 'customer-customer')
            {
                $query11 = $this->db->query("SELECT cid FROM cust_bal WHERE branch='".$_POST['branch']."'");
                $data = $query11->result_array();
            }
            
            if($_POST['type'] == 'customer-category')
            {
                $query11 = $this->db->query("SELECT DISTINCT category FROM master_item WHERE branch='".$_POST['branch']."'");
                $data = $query11->result_array();
            }
            
            if($_POST['type'] == 'customer-type')
            {
                $query11 = $this->db->query("SELECT DISTINCT item_type FROM master_item WHERE branch='".$_POST['branch']."'");
                $data = $query11->result_array();
            }
            
            if($_POST['type'] == 'customer-purity')
            {
                $query11 = $this->db->query("SELECT DISTINCT purity FROM master_item WHERE branch='".$_POST['branch']."'");
                $data = $query11->result_array();
            }
            if($_POST['type'] == 'customer-price')
            {
                $query11 = $this->db->query("SELECT DISTINCT price_type FROM master_item WHERE branch='".$_POST['branch']."'");
                $data = $query11->result_array();
            }
            if($_POST['type'] == 'customer-city')
            {
                $query11 = $this->db->query("SELECT DISTINCT city FROM vendor WHERE branch='".$_POST['branch']."' ORDER BY city");
                $data = $query11->result_array();
            }
            if($_POST['type'] == 'customer-state')
            {
                $query11 = $this->db->query("SELECT DISTINCT state FROM cust WHERE branch='".$_POST['branch']."' and state !='' ");
                $data = $query11->result_array();
            }
            
            if(count($data) > 0)
            {
                $responce['status'] = 1;
                $responce['message'] = 'Total sales details';
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
    
    function saveCustomer($headers){
        if((!$_POST['customerName']) && (($_POST['customerName'] == null)))
        {
            $responce['status'] = 0;
            $responce['message'] = 'Customer Name is required';
            $responce['data'] = '';
            return $responce;
        } else if((!$_POST['mobile']) && (($_POST['mobile'] == null)))
        {
            $responce['status'] = 0;
            $responce['message'] = 'Mobile is required';
            $responce['data'] = '';
            return $responce;
        } else {
            
            // print_r($_POST);
            // die();
            
            $data = array();
            $msg = "Customer Created";
            
            if($_POST['type'] == 'update')
            {
                $cid = isset($_POST['cid'])?$_POST['cid']:'0';
                
                $this->db->where('cid',$cid);
                $this->db->order_by("cid", "desc");
                $shop =  $this->db->get('cust');
                $userData = $shop->result_array();
                
                    
                $insertData = array(
                    'cname'         => isset($_POST['customerName'])?$_POST['customerName']:'.',
                    'nominee'       => isset($_POST['nomineeName'])?$_POST['nomineeName']:'.',
                    'add1'          => isset($_POST['address'])?$_POST['address']:'.',
                    'city'          => isset($_POST['area'])?$_POST['area']:'.',
                    'pincode'       => isset($_POST['pincode'])?$_POST['pincode']:'.',
                    'mobile'        => isset($_POST['mobile'])?$_POST['mobile']:'.',
                    'phone'         => isset($_POST['altMobile'])?$_POST['altMobile']:'.',
                    'email'         => isset($_POST['email'])?$_POST['email']:'.',
                    'state'         => isset($_POST['state'])?$_POST['state']:'.',
                    'scode'         => isset($_POST['stateCode'])?$_POST['stateCode']:'.',
                    'category'      => isset($_POST['category'])?$_POST['category']:'.',
                    'tax_no'        => isset($_POST['gstVatNumber'])?$_POST['gstVatNumber']:'.',
                    'climit'        => isset($_POST['creditLimit'])?$_POST['creditLimit']:'.',
                    'duedays'       => isset($_POST['dueDays'])?$_POST['dueDays']:'.',
                    'remarks'       => isset($_POST['remark'])?$_POST['remark']:'.',
                    'password'       => isset($_POST['password'])?$_POST['password']:'.',
                );
                
                $this->db->where('cid', $cid);
                $data =  $this->db->update('cust', $insertData);
                
                $msg = "Customer Updated";
            
            } else {
                
                $mob = isset($_POST['mobile'])?$_POST['mobile']:'.';
                
                $this->db->where('mobile',$mob);
                $this->db->order_by("cid", "desc");
                $shopData =  $this->db->get('cust');
                $userDataData = $shopData->result_array();
                
                if(!$userDataData)
                {
                    $this->db->order_by("cid", "desc");
                    $shop =  $this->db->get('cust');
                    $userData = $shop->result_array();
                    
                    if($userData)
                    
                        
                    $insertData = array(
                        'cid' => $userData?($userData[0]['cid']+1):0,
                        'cname'         => isset($_POST['customerName'])?$_POST['customerName']:'.',
                        'nominee'       => isset($_POST['nomineeName'])?$_POST['nomineeName']:'.',
                        'add1'          => isset($_POST['address'])?$_POST['address']:'.',
                        'dat'          =>   date("Y/m/d h:i:sa"),
                        'city'          => isset($_POST['area'])?$_POST['area']:'.',
                        'pincode'       => isset($_POST['pincode'])?$_POST['pincode']:'.',
                        'mobile'        => isset($_POST['mobile'])?$_POST['mobile']:'.',
                        'phone'         => isset($_POST['altMobile'])?$_POST['altMobile']:'.',
                        'email'         => isset($_POST['email'])?$_POST['email']:'.',
                        'state'         => isset($_POST['state'])?$_POST['state']:'.',
                        'scode'         => isset($_POST['stateCode'])?$_POST['stateCode']:'.',
                        'category'      => isset($_POST['category'])?$_POST['category']:'.',
                        'tax_no'        => isset($_POST['gstVatNumber'])?$_POST['gstVatNumber']:'.',
                        'climit'        => isset($_POST['creditLimit'])?$_POST['creditLimit']:'.',
                        'duedays'       => isset($_POST['dueDays'])?$_POST['dueDays']:'.',
                        'remarks'       => isset($_POST['remark'])?$_POST['remark']:'.',
                        'branch'        => isset($_POST['branch'])?$_POST['branch']:'.',
                        'password'       => isset($_POST['password'])?$_POST['password']:'.',
                    );
                    $data =  $this->db->insert('cust', $insertData);
                    
                } else {
                    $responce['status'] = 0;
                    $responce['message'] = 'User Already Exits';
                    $responce['data'] = '';
                    return $responce;
                }
            }
            

            if($data)
            {
                $responce['status'] = 1;
                $responce['message'] = $msg;
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
    
    
    function saveCustomerAgent($headers){
        if((!$_POST['login_user']) && (($_POST['login_user'] == null)))
        {
            $responce['status'] = 0;
            $responce['message'] = 'login_user is required';
            $responce['data'] = '';
            return $responce;
        } else {
            // print_r($_POST);
            // die();
            $this->db->order_by("acode", "desc");
            $shop =  $this->db->get('agent');
            $userData = $shop->result_array();
            
                
            $insertData = array(
                'acode' => ($userData[0]['acode']+1),
                'aname'         => $_POST['customerName'],
                'add1'          => $_POST['address'],
                'city'          => $_POST['area'],
                'mobile'        => $_POST['mobile'],
                'phone'      => $_POST['altMobile'],
                'state'    => $_POST['state'],
                'per'    => $_POST['earning'],
                'category'      => $_POST['category'],
                'remarks'       => $_POST['remark'],
                'entered_by_branch'        => $_POST['branch'],
            );
            $data =  $this->db->insert('agent', $insertData);
            

            if($data)
            {
                $responce['status'] = 1;
                $responce['message'] = 'Agent Submitted';
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
    
     function updateCustomer($headers){
        if((!$_POST['login_user']) && (($_POST['login_user'] == null)))
        {
            $responce['status'] = 0;
            $responce['message'] = 'login_user is required';
            $responce['data'] = '';
            return $responce;
        } else {
            // print_r($_POST);
            // die();
            $insertData = array(
                'cname'         => $_POST['customerName'],
                'nominee'       => $_POST['nomineeName'],
                'add1'          => $_POST['address'],
                'city'          => $_POST['area'],
                'pincode'       => $_POST['pincode'],
                'mobile'        => $_POST['mobile'],
                'alt_cnos'      => $_POST['altMobile'],
                'email'         => $_POST['email'],
                'state_name'    => $_POST['state'],
                'state_code'    => $_POST['stateCode'],
                'country'       => $_POST['country'],
                'gst_vat_no'    => $_POST['gstVatNumber'],
                'category'      => $_POST['category'],
                'credit_limit'  => $_POST['creditLimit'],
                'due_days'      => $_POST['dueDays'],
                'card_no'       => $_POST['customerCardNumber'],
                'date_of_birth' => $_POST['dateOfBith'],
                'wedding_date'  => $_POST['weddingDate'],
                'remarks'       => $_POST['remark'],
                'branch'        => $_POST['branch'],
            );
            $data =  $this->db->update('cust', $insertData);
            

            if($data)
            {
                $responce['status'] = 1;
                $responce['message'] = 'Customer Submitted';
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
    
    function getLastAgent($headers)
    {
        if((!$_POST['login_user']) && (($_POST['login_user'] == null)))
        {
            $responce['status'] = 0;
            $responce['message'] = 'login_user is required';
            $responce['data'] = '';
            return $responce;
        } else {
            
            $this->db->order_by('acode', 'DESC');
            $this->db->limit(1);
            $last =  $this->db->get('agent');
            $last_code = $last->result_array();
            
            $data['last_id'] = $last_code[0]['acode'] + 1;
            
            if(count($data) > 0)
            {
                $responce['status'] = 1;
                $responce['message'] = 'last agent ID';
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
    
    function deleteCustomer($headers){
        if((!$_POST['login_user']) && (($_POST['login_user'] == null)))
        {
            $responce['status'] = 0;
            $responce['message'] = 'login_user is required';
            $responce['data'] = '';
            return $responce;
        } else {
            // print_r($_POST);
            // die();
            $this->db->where('cid', $_POST['cno']);
            $data = $this->db->delete('cust');
            

            if($data)
            {
                $responce['status'] = 1;
                $responce['message'] = 'User Deleted';
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