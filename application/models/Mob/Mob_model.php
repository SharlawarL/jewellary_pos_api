<?php

class Mob_model extends CI_Model
{
    public function module($headers){
        if($headers['Module'] == 'get-sales-sammary')
        {
            return $this->getMobReport($headers);
        } else if($headers['Module'] == 'get-estimate-sammary')
        {
            return $this->getEstimateSummary($headers);
        } else if($headers['Module'] == 'get-purchase-sammary')
        {
            return $this->getPurchaseSummary($headers);
        } else if($headers['Module'] == 'get-scheme-paymant-sammary')
        {
            return $this->getSchemePSummary($headers);
        } else if($headers['Module'] == 'get-order-sammary')
        {
            return $this->getOrderSummary($headers);
        }  else if($headers['Module'] == 'get-active-scheme')
        {
            return $this->getActiveScheme($headers);
        } else if($headers['Module'] == 'get-over-due')
        {
            return $this->getOverDue($headers);
        } else if($headers['Module'] == 'get-undelivered-order')
        {
            return $this->getUndelivered($headers);
        } else if($headers['Module'] == 'get-emp-performance')
        {
            return $this->getEmpPerform($headers);
        } else {
            $responce['status'] = 0;
            $responce['message'] = 'Module not found';
            $responce['data'] = '';
            return $responce;
        }
        
    }
    
    function getMobReport($headers){
        if(empty($_POST['login_user']))
        {
            $responce['status'] = 0;
            $responce['message'] = 'Login User is required';
            $responce['data'] = '';
            return $responce;
        } else if(empty($_POST['from']))
        {
            $responce['status'] = 0;
            $responce['message'] = 'From Date is required';
            $responce['data'] = '';
            return $responce;
        } else if(empty($_POST['to']))
        {
            $responce['status'] = 0;
            $responce['message'] = 'To date is required';
            $responce['data'] = '';
            return $responce;
        } else {
            
            $query11 = $this->db->query("SELECT branch,COUNT(billno) as billcount,SUM(net) as net FROM sales WHERE dat BETWEEN '".$_POST['from']."' AND '".$_POST['to']."' GROUP BY branch ORDER BY branch");
            $data = $query11->result_array();
            
            
            // print_r($this->db->last_query());die();
            
            if(count($data) > 0)
            {
                $responce['status'] = 1;
                $responce['message'] = 'Sales Summary';
                $responce['data'] = $data;
                return $responce;
            } else {
                $responce['status'] = 0;
                $responce['message'] = 'No data found';
                $responce['data'] = '';
                return $responce;
            }
            
        }
    }
    
    
    function getEstimateSummary($headers){
        if(empty($_POST['login_user']))
        {
            $responce['status'] = 0;
            $responce['message'] = 'Login User is required';
            $responce['data'] = '';
            return $responce;
        } else if(empty($_POST['from']))
        {
            $responce['status'] = 0;
            $responce['message'] = 'From Date is required';
            $responce['data'] = '';
            return $responce;
        } else if(empty($_POST['to']))
        {
            $responce['status'] = 0;
            $responce['message'] = 'To date is required';
            $responce['data'] = '';
            return $responce;
        } else {
            
            $query11 = $this->db->query("SELECT branch,COUNT(billno) as billcount,SUM(net) as net FROM estimate WHERE dat BETWEEN '".$_POST['from']."' AND '".$_POST['to']."' GROUP BY branch ORDER BY branch");
            $data = $query11->result_array();
            
            // print_r($this->db->last_query());die();
            
            if(count($data) > 0)
            {
                $responce['status'] = 1;
                $responce['message'] = 'Estimate Summary';
                $responce['data'] = $data;
                return $responce;
            } else {
                $responce['status'] = 0;
                $responce['message'] = 'No data found';
                $responce['data'] = '';
                return $responce;
            }
            
        }
    }
    
    
    function getPurchaseSummary($headers){
        if(empty($_POST['login_user']))
        {
            $responce['status'] = 0;
            $responce['message'] = 'Login User is required';
            $responce['data'] = '';
            return $responce;
        } else if(empty($_POST['from']))
        {
            $responce['status'] = 0;
            $responce['message'] = 'From Date is required';
            $responce['data'] = '';
            return $responce;
        } else if(empty($_POST['to']))
        {
            $responce['status'] = 0;
            $responce['message'] = 'To date is required';
            $responce['data'] = '';
            return $responce;
        } else {
            $query11 = $this->db->query("SELECT branch,COUNT(billno) as billcount,SUM(net) as net FROM purchase WHERE bdate BETWEEN '".$_POST['from']."' AND '".$_POST['to']."' GROUP BY branch ORDER BY branch");
            $data = $query11->result_array();
            
            // print_r($this->db->last_query());die();
            
            if(count($data) > 0)
            {
                $responce['status'] = 1;
                $responce['message'] = 'Purchase Summary';
                $responce['data'] = $data;
                return $responce;
            } else {
                $responce['status'] = 0;
                $responce['message'] = 'No data found';
                $responce['data'] = '';
                return $responce;
            }
            
        }
    }
    
    
    function getSchemePSummary($headers){
        if(empty($_POST['login_user']))
        {
            $responce['status'] = 0;
            $responce['message'] = 'Login User is required';
            $responce['data'] = '';
            return $responce;
        } else if(empty($_POST['from']))
        {
            $responce['status'] = 0;
            $responce['message'] = 'From Date is required';
            $responce['data'] = '';
            return $responce;
        } else if(empty($_POST['to']))
        {
            $responce['status'] = 0;
            $responce['message'] = 'To date is required';
            $responce['data'] = '';
            return $responce;
        } else {
    
            $query11 = $this->db->query("SELECT paid_by_branch,COUNT(billno) as billcount,SUM(amount) as net FROM scheme_pay WHERE dat BETWEEN '".$_POST['from']."' AND '".$_POST['to']."' GROUP BY paid_by_branch ORDER BY paid_by_branch");
            $data = $query11->result_array();
            
            // print_r($this->db->last_query());die();
            
            if(count($data) > 0)
            {
                $responce['status'] = 1;
                $responce['message'] = 'Scheme Payment Summary';
                $responce['data'] = $data;
                return $responce;
            } else {
                $responce['status'] = 0;
                $responce['message'] = 'No data found';
                $responce['data'] = '';
                return $responce;
            }
            
        }
    }
    
    
    function getOrderSummary($headers){
        if(empty($_POST['login_user']))
        {
            $responce['status'] = 0;
            $responce['message'] = 'Login User is required';
            $responce['data'] = '';
            return $responce;
        } else if(empty($_POST['from']))
        {
            $responce['status'] = 0;
            $responce['message'] = 'From Date is required';
            $responce['data'] = '';
            return $responce;
        } else if(empty($_POST['to']))
        {
            $responce['status'] = 0;
            $responce['message'] = 'To date is required';
            $responce['data'] = '';
            return $responce;
        } else {
    
            $query11 = $this->db->query("SELECT entered_by_branch,COUNT(sno) as snocount,SUM(tot) as tot FROM orders_entry WHERE dat BETWEEN '".$_POST['from']."' AND '".$_POST['to']."' GROUP BY entered_by_branch ORDER BY entered_by_branch");
            $data = $query11->result_array();
            
            // print_r($this->db->last_query());die();
            
            if(count($data) > 0)
            {
                $responce['status'] = 1;
                $responce['message'] = 'Order Summary';
                $responce['data'] = $data;
                return $responce;
            } else {
                $responce['status'] = 0;
                $responce['message'] = 'No data found';
                $responce['data'] = '';
                return $responce;
            }
            
        }
    }
    
    function getActiveScheme($headers){
        if(empty($_POST['login_user']))
        {
            $responce['status'] = 0;
            $responce['message'] = 'Login User is required';
            $responce['data'] = '';
            return $responce;
        } else {
    
            $query11 = $this->db->query("SELECT entered_by_branch,COUNT(sno) as snocount FROM scheme_entry WHERE status='Active' GROUP BY entered_by_branch ORDER BY entered_by_branch");
            $data = $query11->result_array(); 
            
            // print_r($this->db->last_query());die();
            
            if(count($data) > 0)
            {
                $responce['status'] = 1;
                $responce['message'] = 'Order Summary';
                $responce['data'] = $data;
                return $responce;
            } else {
                $responce['status'] = 0;
                $responce['message'] = 'No data found';
                $responce['data'] = '';
                return $responce;
            }
            
        }
    }
    
    
    function getOverDue($headers){
        if(empty($_POST['login_user']))
        {
            $responce['status'] = 0;
            $responce['message'] = 'Login User is required';
            $responce['data'] = '';
            return $responce;
        } else {
    
            $query11 = $this->db->query("SELECT entered_by_branch,COUNT(sno) as snocount FROM scheme_entry WHERE STATUS='Active'  AND '".date('Y-m-d')."' > ndate GROUP BY entered_by_branch ORDER BY entered_by_branch");
            $data = $query11->result_array(); 
            
            // print_r($this->db->last_query());die();
            
            if(count($data) > 0)
            {
                $responce['status'] = 1;
                $responce['message'] = 'Over Dues';
                $responce['data'] = $data;
                return $responce;
            } else {
                $responce['status'] = 0;
                $responce['message'] = 'No data found';
                $responce['data'] = '';
                return $responce;
            }
            
        }
    }
    
    
    function getUndelivered($headers){
        if(empty($_POST['login_user']))
        {
            $responce['status'] = 0;
            $responce['message'] = 'Login User is required';
            $responce['data'] = '';
            return $responce;
        } else {
    
            $query11 = $this->db->query("SELECT entered_by_branch,COUNT(sno) as snocount FROM orders_entry WHERE STATUS='Active'  AND '".date('Y-m-d')."' > ddate GROUP BY entered_by_branch ORDER BY entered_by_branch");
            $data = $query11->result_array(); 
            
            // print_r($this->db->last_query());die();
            
            if(count($data) > 0)
            {
                $responce['status'] = 1;
                $responce['message'] = 'Undelivered';
                $responce['data'] = $data;
                return $responce;
            } else {
                $responce['status'] = 0;
                $responce['message'] = 'No data found';
                $responce['data'] = '';
                return $responce;
            }
            
        }
    }
    
    
    function getEmpPerform($headers){
        if(empty($_POST['login_user']))
        {
            $responce['status'] = 0;
            $responce['message'] = 'Login User is required';
            $responce['data'] = '';
            return $responce;
        } else if(empty($_POST['branch']))
        {
            $responce['status'] = 0;
            $responce['message'] = 'Branch is required';
            $responce['data'] = '';
            return $responce;
        }  else if(empty($_POST['from']))
        {
            $responce['status'] = 0;
            $responce['message'] = 'From Date is required';
            $responce['data'] = '';
            return $responce;
        } else if(empty($_POST['to']))
        {
            $responce['status'] = 0;
            $responce['message'] = 'To date is required';
            $responce['data'] = '';
            return $responce;
        } else {
    
            $query11 = $this->db->query("SELECT sname,sid,SUM(tot) as tot FROM sales_items WHERE dat BETWEEN '".$_POST['from']."' AND '".$_POST['to']."' and branch='".$_POST['branch']."' GROUP BY sid ORDER BY SUM(tot) desc");
            $data = $query11->result_array(); 
            
            // print_r($this->db->last_query());die();
            
            if(count($data) > 0)
            {
                $responce['status'] = 1;
                $responce['message'] = 'Emp Performance';
                $responce['data'] = $data;
                return $responce;
            } else {
                $responce['status'] = 0;
                $responce['message'] = 'No data found';
                $responce['data'] = '';
                return $responce;
            }
            
        }
    }
    
    
}