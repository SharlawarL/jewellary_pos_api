<?php

class Supplier_model extends CI_Model
{
    public function module($headers){
        if($headers['Module'] == 'get-supplier')
        {
            return $this->getSupplier($headers);
        } else if($headers['Module'] == 'get-drop-supplier')
        {
            return $this->getSupplierDrop($headers);
        } else if($headers['Module'] == 'save-supplier')
        {
            return $this->saveSupplier($headers);
        } else if($headers['Module'] == 'delete-supplier')
        {
            return $this->deleteSupplier($headers);
        }  else {
            $responce['status'] = 0;
            $responce['message'] = 'Something is wrong';
            $responce['data'] = '';
            return $responce;
        }
        
    }
    
    function getSupplier($headers){
        if((!$_POST['login_user']) && (($_POST['login_user'] == null)))
        {
            $responce['status'] = 0;
            $responce['message'] = 'login_user is required';
            $responce['data'] = '';
            return $responce;
        } else {
            // die();
            
            $date = date("Y-m-d");
            
            if($_POST['type'] == 'supplier-due-report')
            {
                $query11 = $this->db->query("SELECT billno,date_format(dat,'%d/%m/%Y') as bdate,date_format(ddate,'%d/%m/%Y') as ddate,cname,tot,paid,tot-paid 
                FROM ven_bal 
                WHERE tot-paid>0 and branch='".$_POST['branch']."' ORDER BY dat,billno");
                $data = $query11->result_array();
                
            }
            else  if($_POST['type'] == 'supplier-supplier')
            {
                $query11 = $this->db->query("SELECT billno,date_format(dat,'%d/%m/%Y') as bdate,date_format(ddate,'%d/%m/%Y') as ddate,cname,tot,paid,tot-paid 
                FROM ven_bal 
                WHERE tot-paid>0 and branch='".$_POST['branch']."' and cname='".$_POST['drop']."' ORDER BY dat,billno");
                $data = $query11->result_array();
                
            }
            else  if($_POST['type'] == 'supplier-overdue-report')
            {
                $query11 = $this->db->query("SELECT billno,date_format(dat,'%d/%m/%Y') as bdate,date_format(ddate,'%d/%m/%Y') as ddate,datediff(ddate,'".$date."') as diff,cname,tot-paid 
                FROM ven_bal 
                WHERE ddate < '".$date."' AND tot-paid>0 AND branch='".$_POST['branch']."' order by ddate,billno");
                $data = $query11->result_array();
                
            }
            else  if($_POST['type'] == 'supplier-payment-report')
            {
                $query11 = $this->db->query("SELECT DISTINCT sno,date_format(dat,'%d/%m/%Y') as bdate,tim,cname,net,pby,remarks,user,last 
                FROM ven_pay  
                WHERE dat BETWEEN '".$_POST['from']."' and '".$_POST['to']."' AND branch='".$_POST['branch']."' ORDER BY dat,sno");
                $data = $query11->result_array();
                
            }
            else  if($_POST['type'] == 'supplier-wise-payment-report')
            {
                $query11 = $this->db->query("SELECT DISTINCT sno,date_format(dat,'%d/%m/%Y') as bdate,tim,net,pby,remarks,user,last 
                FROM ven_pay 
                WHERE dat BETWEEN '".$_POST['from']."' and '".$_POST['to']."' AND branch='".$_POST['branch']."' AND cname='".$_POST['drop']."' ORDER BY dat,sno");
                $data = $query11->result_array();
                
            }
            else  if($_POST['type'] == 'supplier-bill-report')
            {
                $query11 = $this->db->query("SELECT sno,cname,billno,date_format(dat,'%d/%m/%Y') as bdate,date_format(ddate,'%d/%m/%Y') as ddate,amount,remarks,user,last 
                FROM ven_bill 
                WHERE dat BETWEEN '".$_POST['from']."' and '".$_POST['to']."' AND branch='".$_POST['branch']."' ORDER BY  dat,sno");
                $data = $query11->result_array();
                
            }
            else  if($_POST['type'] == 'supplier-area')
            {
                $query11 = $this->db->query("SELECT DISTINCT cname,add1,add2,add3,city,mobile,phone,email,tax_no,state,scode,duedays,remarks FROM vendor 
                WHERE  branch='".$_POST['branch']."' and city='".$_POST['drop']."' ORDER BY cname");
                $data = $query11->result_array();
                
            }
                else  if($_POST['type'] == 'supplier-list')
            {
                $query11 = $this->db->query("SELECT DISTINCT cname,add1,add2,add3,city,mobile,phone,email,tax_no,state,scode,duedays,remarks FROM vendor Group By cname");
                $data = $query11->result_array();
                
            }
            else {
                
                // print_r($_POST);die();
            
            if (isset($_POST['search_key']) && $_POST['search_key'] != '' && $_POST['search_key'] != null) {
				$search_key = $_POST['search_key'];
				$like = "(
				    vendor.vname like '%$search_key%' or 
				    vendor.add1 like '%$search_key%' or 
				    vendor.city like '%$search_key%' or 
				    vendor.mobile like '%$search_key%' or 
				    vendor.phone like '%$search_key%' or 
				    vendor.email like '%$search_key%' or 
				    vendor.state_name like '%$search_key%' or 
				    vendor.state_code like '%$search_key%' or 
				    vendor.gstno like '%$search_key%' or 
				     vendor.remarks like '%$search_key%' or 
				    vendor.branch like '%$search_key%
				    ')"; 
				$this->db->where($like);
			}
			$this->db->distinct();
            
            $this->db->order_by("sno", "desc");
            $shop =  $this->db->get('vendor');
            $data = $shop->result_array();
            
            }
            

            if(count($data) > 0)
            {
                $responce['status'] = 1;
                $responce['message'] = 'Supplier details';
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
    
    function getSupplierDrop($headers){
        if((!$_POST['login_user']) && (($_POST['login_user'] == null)))
        {
            $responce['status'] = 0;
            $responce['message'] = 'login_user is required';
            $responce['data'] = '';
            return $responce;
        } else {
            $data = array();
            
            if($_POST['type'] == 'supplier-supplier')
            {
                $query11 = $this->db->query("SELECT DISTINCT cname FROM ven_bal WHERE tot-paid>0 AND branch='".$_POST['branch']."'");
                $data = $query11->result_array();
            }
            
            if($_POST['type'] == 'supplier-category')
            {
                $query11 = $this->db->query("SELECT DISTINCT category FROM master_item WHERE branch='".$_POST['branch']."'");
                $data = $query11->result_array();
            }
            
            if($_POST['type'] == 'supplier-type')
            {
                $query11 = $this->db->query("SELECT DISTINCT item_type FROM master_item WHERE branch='".$_POST['branch']."'");
                $data = $query11->result_array();
            }
            
            if($_POST['type'] == 'supplier-purity')
            {
                $query11 = $this->db->query("SELECT DISTINCT purity FROM master_item WHERE branch='".$_POST['branch']."'");
                $data = $query11->result_array();
            }
            if($_POST['type'] == 'supplier-price')
            {
                $query11 = $this->db->query("SELECT DISTINCT price_type FROM master_item WHERE branch='".$_POST['branch']."'");
                $data = $query11->result_array();
            }
            if($_POST['type'] == 'supplier-city')
            {
                $query11 = $this->db->query("SELECT DISTINCT city FROM vendor WHERE branch='".$_POST['branch']."' ORDER BY city");
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
    
    
    function saveSupplier($headers){
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
                'add1'          => $_POST['address'],
                'city'          => $_POST['area'],
                'phone'        => $_POST['mobile'],
                'email'         => $_POST['email'],
                'state'    => $_POST['state'],
                'scode'    => $_POST['stateCode'],
                'duedays'      => $_POST['dueDays'],
                'remarks'       => $_POST['remark'],
                'branch'        => $_POST['branch'],
            );
            $data =  $this->db->insert('vendor', $insertData);
            

            if($data)
            {
                $responce['status'] = 1;
                $responce['message'] = 'Item Submitted';
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
    
    function deleteSupplier($headers){
        if(!$_POST['login_user'])
        {
            $responce['status'] = 0;
            $responce['message'] = 'login_user is required';
            $responce['data'] = '';
            return $responce;
        } else if(!$_POST['vendor'])
        {
            $responce['status'] = 0;
            $responce['message'] = 'Username is required';
            $responce['data'] = '';
            return $responce;
        }  else {
            
                $this->db->where('sno', $_POST['vendor']);
                $result =  $this->db->delete('vendor');
    
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
    
}