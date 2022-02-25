<?php

class Goldsmith_model extends CI_Model
{
    public function module($headers){
        if($headers['Module'] == 'save-master')
        {
            return $this->saveMaster($headers);
        } else if($headers['Module'] == 'supplier-receipt-last')
        {
            return $this->supplierReceiptLast($headers);
        } else if($headers['Module'] == 'supplier-issue-last')
        {
            return $this->supplierIssueLast($headers);
        } else if($headers['Module'] == 'smith-receipt-last')
        {
            return $this->smithReceiptLast($headers);
        } else if($headers['Module'] == 'smith-issue-last')
        {
            return $this->smithIssueLast($headers);
        } else if($headers['Module'] == 'supplier-receipt-entry')
        {
            return $this->supplierReceiptEntry($headers);
        }  else if($headers['Module'] == 'supplier-issue-entry')
        {
            return $this->supplierIssueEntry($headers);
        } else if($headers['Module'] == 'get-smith')
        {
            return $this->getSmith($headers);
        }  else if($headers['Module'] == 'smith-receipt-entry')
        {
            return $this->smithIssueEntry($headers);
        }  else if($headers['Module'] == 'smith-issue-entry')
        {
            return $this->smithReceiptEntry($headers);
        } else if($headers['Module'] == 'smith-report')
        {
            return $this->smithReport($headers);
        }  else if($headers['Module'] == 'supplier-report')
        {
            return $this->supplierReport($headers);
        } else if($headers['Module'] == 'smith-day-book')
        {
            return $this->smithDayBook($headers);
        }  else if($headers['Module'] == 'supplier-day-book')
        {
            return $this->supplierDayBook($headers);
        }  else {
            $responce['status'] = 0;
            $responce['message'] = 'Something is wrong';
            $responce['data'] = '';
            return $responce;
        }
        
    }
    
    function getSmith($headers){
        if((!$_POST['login_user']) && (($_POST['login_user'] == null)))
        {
            $responce['status'] = 0;
            $responce['message'] = 'login_user is required';
            $responce['data'] = '';
            return $responce;
        } else {
            
            
            $last =  $this->db->get('order_smith_master');
            $data = $last->result_array();
            

            if($data)
            {
                $responce['status'] = 1;
                $responce['message'] = 'Goldsmith Master Data';
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
    
    function smithReport($headers){
        if((!$_POST['login_user']) && (($_POST['login_user'] == null)))
        {
            $responce['status'] = 0;
            $responce['message'] = 'login_user is required';
            $responce['data'] = '';
            return $responce;
        } else {
            
            
            $query11 = $this->db->query("SELECT gname,stock FROM order_smith_stock ORDER BY gname");
            $data = $query11->result_array();
            

            if($data)
            {
                $responce['status'] = 1;
                $responce['message'] = 'Goldsmith Master Data';
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
    
    function supplierReport($headers){
        if((!$_POST['login_user']) && (($_POST['login_user'] == null)))
        {
            $responce['status'] = 0;
            $responce['message'] = 'login_user is required';
            $responce['data'] = '';
            return $responce;
        } else {
            
            
            $query11 = $this->db->query("SELECT cname,stock FROM order_supplier_stock ORDER BY cname");
            $data = $query11->result_array();
            

            if($data)
            {
                $responce['status'] = 1;
                $responce['message'] = 'Supplier Master Data';
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
    
    function saveMaster($headers){
        if((!$_POST['login_user']) && (($_POST['login_user'] == null)))
        {
            $responce['status'] = 0;
            $responce['message'] = 'login_user is required';
            $responce['data'] = '';
            return $responce;
        } else {
            
            //check goldsmith already exits
            $this->db->where('gname',$_POST['name']);
            $shop =  $this->db->get('order_smith_master');
            $item_no1 = $shop->result_array();
            
            if(count($item_no1) == 0)
            {
            
                $insertData = array(
                    'gname' => $_POST['name']
                );
                $data =  $this->db->insert('order_smith_master', $insertData);
                
    
                if($data)
                {
                    $responce['status'] = 1;
                    $responce['message'] = 'Goldsmith Master Saved Successfully!!!';
                    $responce['data'] = $data;
                    return $responce;
                } else {
                    $responce['status'] = 0;
                    $responce['message'] = 'Samething is wrong';
                    $responce['data'] = '';
                    return $responce;
                }
            } else {
                $responce['status'] = 0;
                $responce['message'] = 'Already Exits';
                $responce['data'] = '';
                return $responce;
            }
        }
        
        
    }
    
    function supplierReceiptLast($headers){
        if((!$_POST['login_user']) && (($_POST['login_user'] == null)))
        {
            $responce['status'] = 0;
            $responce['message'] = 'login_user is required';
            $responce['data'] = '';
            return $responce;
        } else {
            
            $this->db->order_by('sno', 'DESC');
            $this->db->limit(1);
            $supplier =  $this->db->get('order_supplier_receipt');
            $sresult = $supplier->result_array();
            
            
            $data = $sresult?($sresult[0]['sno'] + 1):0;
            
            if($data)
            {
                $responce['status'] = 1;
                $responce['message'] = 'Total items details';
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
    
    function supplierIssueLast($headers){
        if((!$_POST['login_user']) && (($_POST['login_user'] == null)))
        {
            $responce['status'] = 0;
            $responce['message'] = 'login_user is required';
            $responce['data'] = '';
            return $responce;
        } else {
            
            $this->db->order_by('sno', 'DESC');
            $this->db->limit(1);
            $supplier =  $this->db->get('order_supplier_issue');
            $sresult = $supplier->result_array();
            
            $data = $sresult?($sresult[0]['sno'] + 1):0;
            
            if($data)
            {
                $responce['status'] = 1;
                $responce['message'] = 'Total items details';
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
    
    
    function supplierReceiptEntry($headers){
        if((!$_POST['login_user']) && (($_POST['login_user'] == null)))
        {
            $responce['status'] = 0;
            $responce['message'] = 'login_user is required';
            $responce['data'] = '';
            return $responce;
        } else {
            // print_r($_POST);die();
            
            $this->db->where('cname',$_POST['supplier']);
            $supplier =  $this->db->get('order_supplier_stock');
            $sresult = $supplier->result_array();
            
            if(count($sresult) == 0)
            {
                $insertDataSupplier = array(
                    'cname' => $_POST['supplier'],
                    'stock' => $_POST['netwt'],
                );
                $data =  $this->db->insert('order_supplier_stock', $insertDataSupplier);
            } else {
                 $insertDataSupplier = array(
                    'stock' => $_POST['netwt'] + $sresult[0]['stock'],
                );
                $this->db->where('cname', $_POST['supplier']);
                $data =  $this->db->update('order_supplier_stock', $insertDataSupplier);
            }
            
            
            
            $insertData = array(
                'dat' => $_POST['dat'],
                'cname' => $_POST['supplier'],
                'iname' => $_POST['part'],
                'hsn' => $_POST['hsn'],
                'weight' => $_POST['weight'],
                'alloyp' => $_POST['alloyhsn'],
                'alloy_wt' => $_POST['alloywt'],
                'nweight' => $_POST['netwt'],
                'user' => $_POST['login_user'],
                'last' =>  date("Y/m/d h:i:sa"),
                'branch' => $_POST['branch']
            );
            $data =  $this->db->insert('order_supplier_receipt', $insertData);
            
            $lastid = $this->db->insert_id();
            
            $this->db->order_by('sno', 'DESC');
            $this->db->limit(1);
            $supplierid =  $this->db->get('order_supplier_account');
            $last_code = $supplierid->result_array();
            
            
            // supplier account
            $insertSupplierData = array(
                'dat' => $_POST['dat'],
                'billno' => $_POST['bill'],
                'gname' => $_POST['supplier'],
                'part' => 'Receipt Entry',
                'debit' => 0,
                'credit' => $_POST['netwt'],
                'page' => 'receipt'
            );
            $dataOrder =  $this->db->insert('order_supplier_account', $insertSupplierData);
            

            if($data)
            {
                $responce['status'] = 1;
                $responce['message'] = 'Supplier Receipt Entry Submitted';
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
    
    
    
    function supplierIssueEntry($headers){
        if((!$_POST['login_user']) && (($_POST['login_user'] == null)))
        {
            $responce['status'] = 0;
            $responce['message'] = 'login_user is required';
            $responce['data'] = '';
            return $responce;
        } else {
            // print_r($_POST);die();
            
            $this->db->where('cname',$_POST['supplier']);
            $supplier =  $this->db->get('order_supplier_stock');
            $sresult = $supplier->result_array();
            
            if(count($sresult) == 0)
            {
                $insertDataSupplier = array(
                    'cname' => $_POST['supplier'],
                    'stock' => $_POST['total'],
                );
                $data =  $this->db->insert('order_supplier_stock', $insertDataSupplier);
            } else {
                 $insertDataSupplier = array(
                    'stock' => $sresult[0]['stock'] - $_POST['total'] ,
                );
                $this->db->where('cname', $_POST['supplier']);
                $data =  $this->db->update('order_supplier_stock', $insertDataSupplier);
            }
            
            
            
            $insertData = array(
                'dat' => $_POST['dat'],
                'cname' => $_POST['supplier'],
                'iname' => $_POST['part'],
                'hsn' => $_POST['hsn'],
                'nweight' => $_POST['weight'],
                'pweight' => $_POST['weight'],
                'rate' => $_POST['rate'],
                'amount' => $_POST['amt'],
                'taxp' => $_POST['gst'],
                'taxamt' => $_POST['gstamt'],
                'net' => $_POST['amt'] + $_POST['gstamt'],
                'tdsp' => $_POST['tsd'],
                'tdsamt' => $_POST['tdsamt'],
                'total' => $_POST['total'],
                'user' => $_POST['login_user'],
                'last' =>  date("Y/m/d h:i:sa"),
                'branch' => $_POST['branch']
            );
            $data =  $this->db->insert('order_supplier_issue', $insertData);
            
            $lastid = $this->db->insert_id();
            
            $this->db->order_by('sno', 'DESC');
            $this->db->limit(1);
            $supplierid =  $this->db->get('order_supplier_account');
            $last_code = $supplierid->result_array();
            
            // supplier account
            $insertSupplierData = array(
                'dat' => $_POST['dat'],
                'billno' => $_POST['bill'],
                'gname' => $_POST['supplier'],
                'part' => 'Issue Entry',
                'debit' => $_POST['weight'],
                'credit' =>0,
                'page' => 'issue'
            );
            $dataOrder =  $this->db->insert('order_supplier_account', $insertSupplierData);
            

            if($data)
            {
                $responce['status'] = 1;
                $responce['message'] = 'Supplier Issue Entry Submitted';
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
    
    function smithReceiptLast($headers){
        if((!$_POST['login_user']) && (($_POST['login_user'] == null)))
        {
            $responce['status'] = 0;
            $responce['message'] = 'login_user is required';
            $responce['data'] = '';
            return $responce;
        } else {
            
            $this->db->order_by('sno', 'DESC');
            $this->db->limit(1);
            $supplier =  $this->db->get('order_smith_receipt');
            $sresult = $supplier->result_array();
            
            $data = $sresult?($sresult[0]['sno'] + 1):0;
            
            if($data)
            {
                $responce['status'] = 1;
                $responce['message'] = 'Order Smith Receipt details';
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
    
    function smithIssueLast($headers){
        if((!$_POST['login_user']) && (($_POST['login_user'] == null)))
        {
            $responce['status'] = 0;
            $responce['message'] = 'login_user is required';
            $responce['data'] = '';
            return $responce;
        } else {
            
            $this->db->order_by('sno', 'DESC');
            $this->db->limit(1);
            $supplier =  $this->db->get('order_smith_issue');
            $sresult = $supplier->result_array();
            
            $data = $sresult?($sresult[0]['sno'] + 1):0;
            
            if($data)
            {
                $responce['status'] = 1;
                $responce['message'] = 'Order Smith Issue details';
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
    
     function smithIssueEntry($headers){
        if((!$_POST['login_user']) && (($_POST['login_user'] == null)))
        {
            $responce['status'] = 0;
            $responce['message'] = 'login_user is required';
            $responce['data'] = '';
            return $responce;
        } else {
            // print_r($_POST);die();
            
            $this->db->where('gname',$_POST['supplier']);
            $supplier =  $this->db->get('order_smith_stock');
            $sresult = $supplier->result_array();
            
            if(count($sresult) == 0)
            {
                $insertDataSupplier = array(
                    'gname' => $_POST['supplier'],
                    'stock' => $_POST['netwt'],
                );
                $data =  $this->db->insert('order_smith_stock', $insertDataSupplier);
            } else {
                 $insertDataSupplier = array(
                    'stock' => $_POST['netwt'] + $sresult[0]['stock'],
                );
                $this->db->where('gname', $_POST['supplier']);
                $data =  $this->db->update('order_smith_stock', $insertDataSupplier);
            }
            
            
            
            $insertData = array(
                'dat' => $_POST['dat'],
                'gname' => $_POST['supplier'],
                'iname' => $_POST['part'],
                'hsn' => $_POST['hsn'],
                'weight' => $_POST['weight'],
                'alloyp' => $_POST['alloyhsn'],
                'alloy_wt' => $_POST['alloywt'],
                'nweight' => $_POST['netwt'],
                'user' => $_POST['login_user'],
                'last' =>  date("Y/m/d h:i:sa"),
                'branch' => $_POST['branch']
            );
            $data =  $this->db->insert('order_smith_issue', $insertData);
            
            $lastid = $this->db->insert_id();
            
            $this->db->order_by('sno', 'DESC');
            $this->db->limit(1);
            $supplierid =  $this->db->get('order_smith_account');
            $last_code = $supplierid->result_array();
            
            
            // supplier account
            $insertSupplierData = array(
                'dat' => $_POST['dat'],
                'billno' => $_POST['bill'],
                'gname' => $_POST['supplier'],
                'part' => 'Issue Entry',
                'debit' => $_POST['netwt'],
                'credit' => 0,
                'page' => 'issue'
            );
            $dataOrder =  $this->db->insert('order_smith_account', $insertSupplierData);
            

            if($data)
            {
                $responce['status'] = 1;
                $responce['message'] = 'GOLD Smith Issue Entry Submitted';
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
    
    function smithReceiptEntry($headers){
        if((!$_POST['login_user']) && (($_POST['login_user'] == null)))
        {
            $responce['status'] = 0;
            $responce['message'] = 'login_user is required';
            $responce['data'] = '';
            return $responce;
        } else {
            // print_r($_POST);die();
            
            $this->db->where('gname',$_POST['supplier']);
            $supplier =  $this->db->get('order_smith_stock');
            $sresult = $supplier->result_array();
            
            if(count($sresult) == 0)
            {
                $insertDataSupplier = array(
                    'gname' => $_POST['supplier'],
                    'stock' => $_POST['total'],
                );
                $data =  $this->db->insert('order_smith_stock', $insertDataSupplier);
            } else {
                 $insertDataSupplier = array(
                    'stock' => $sresult[0]['stock'] - $_POST['total'] ,
                );
                $this->db->where('gname', $_POST['supplier']);
                $data =  $this->db->update('order_smith_stock', $insertDataSupplier);
            }
            
            
            
            $insertData = array(
                'dat' => $_POST['dat'],
                'gname' => $_POST['supplier'],
                'iname' => $_POST['part'],
                'hsn' => $_POST['hsn'],
                'nweight' => $_POST['weight'],
                'pweight' => $_POST['weight'],
                'rate' => $_POST['rate'],
                'amount' => $_POST['amt'],
                'taxp' => $_POST['gst'],
                'taxamt' => $_POST['gstamt'],
                'net' => $_POST['amt'] + $_POST['gstamt'],
                'tdsp' => $_POST['tsd'],
                'tdsamt' => $_POST['tdsamt'],
                'total' => $_POST['total'],
                'user' => $_POST['login_user'],
                'last' =>  date("Y/m/d h:i:sa"),
                'branch' => $_POST['branch']
            );
            $data =  $this->db->insert('order_smith_receipt', $insertData);
            
            $lastid = $this->db->insert_id();
            
            $this->db->order_by('sno', 'DESC');
            $this->db->limit(1);
            $supplierid =  $this->db->get('order_smith_account');
            $last_code = $supplierid->result_array();
            
            
            // supplier account
            $insertSupplierData = array(
                'dat' => $_POST['dat'],
                'billno' => $_POST['bill'],
                'gname' => $_POST['supplier'],
                'part' => 'Receipt Entry',
                'debit' => 0,
                'credit' => $_POST['weight'],
                'page' => 'receipt'
            );
            $dataOrder =  $this->db->insert('order_smith_account', $insertSupplierData);
            

            if($data)
            {
                $responce['status'] = 1;
                $responce['message'] = 'GOLD Smith Receipt Entry Submitted';
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
    
    public function smithDayBook($headers){
        if((!$_POST['login_user']) && (($_POST['login_user'] == null)))
        {
            $responce['status'] = 0;
            $responce['message'] = 'login_user is required';
            $responce['data'] = '';
            return $responce;
        } else if((!$_POST['from']) && (($_POST['from'] == null)))
        {
            $responce['status'] = 0;
            $responce['message'] = 'From Date is required';
            $responce['data'] = '';
            return $responce;
        } else if((!$_POST['to']) && (($_POST['to'] == null)))
        {
            $responce['status'] = 0;
            $responce['message'] = 'To date is required';
            $responce['data'] = '';
            return $responce;
        } else if((!$_POST['gname']) && (($_POST['gname'] == null)))
        {
            $responce['status'] = 0;
            $responce['message'] = 'Gold Smith is required';
            $responce['data'] = '';
            return $responce;
        } else {
            
            $data = array();
            
            $query11 = $this->db->query("SELECT sno, DATE_FORMAT(dat,'%d/%m/%Y') as dat,billno,part,debit,credit FROM order_smith_account WHERE dat BETWEEN '".$_POST['from']."' AND '".$_POST['to']."' and gname='".$_POST['gname']."' ORDER BY dat");
            $data['daybook'] = $query11->result_array();
            
            $query12 = $this->db->query("select sum(credit-debit) as crd from order_smith_account WHERE dat BETWEEN '".$_POST['from']."' AND '".$_POST['to']."' and gname='".$_POST['gname']."'");
            $tempOpn = $query12->result_array();
            $data['open_blc'] = $tempOpn?$tempOpn[0]['crd']:0;
            
            $data['dat'] = date('d-m-Y');
            

            if($data)
            {
                $responce['status'] = 1;
                $responce['message'] = 'Smith Day Book Data';
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
    
    public function supplierDayBook($headers){
        if((!$_POST['login_user']) && (($_POST['login_user'] == null)))
        {
            $responce['status'] = 0;
            $responce['message'] = 'login_user is required';
            $responce['data'] = '';
            return $responce;
        } else {
            
            $data = array();
            
            $query11 = $this->db->query("SELECT sno, DATE_FORMAT(dat,'%d/%m/%Y') as dat,billno,part,debit,credit FROM order_supplier_account WHERE dat BETWEEN '".$_POST['from']."' AND '".$_POST['to']."' and gname='".$_POST['gname']."' ORDER BY dat");
            $data['daybook'] = $query11->result_array();
            
            $query12 = $this->db->query("select sum(credit-debit) as crd from order_supplier_account WHERE dat BETWEEN '".$_POST['from']."' AND '".$_POST['to']."' and gname='".$_POST['gname']."'");
            $tempOpn = $query12->result_array();
            $data['open_blc'] = $tempOpn?$tempOpn[0]['crd']:0;
            
            $data['dat'] = date('d/m/Y');
            

            if($data)
            {
                $responce['status'] = 1;
                $responce['message'] = 'Supplier Book Data';
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