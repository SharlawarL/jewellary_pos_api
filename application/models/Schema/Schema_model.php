<?php

class Schema_model extends CI_Model
{
    public function module($headers){
        if($headers['Module'] == 'save-schema-name-master')
        {
            return $this->saveSchemaNameMaster($headers);
        } else if($headers['Module'] == 'save-schema-master')
        {
            return $this->saveSchemaMaster($headers);
        }
        else if($headers['Module'] == 'save-schema-reg')
        {
            return $this->saveSchemaReg($headers);
        }
        else if($headers['Module'] == 'save-schema-pay')
        {
            return $this->saveSchemaPay($headers);
        }
        else if($headers['Module'] == 'save-schema')
        {
            return $this->saveSchema($headers);
        }
        else if($headers['Module'] == 'save-order-entry')
        {
            return $this->saveOrderEntry($headers);
        }
        else if($headers['Module'] == 'save-order-delivery')
        {
            return $this->saveOrderDelivery($headers);
        }
        else if($headers['Module'] == 'get-schema')
        {
            return $this->getSchema($headers);
        }
        
        else if($headers['Module'] == 'get-schema-reports')
        {
            return $this->getSchemaReports($headers);
        }
        
        else if($headers['Module'] == 'get-gstr-reports')
        {
            return $this->getGstrReports($headers);
        }
        
        else if($headers['Module'] == 'get-schema-reports-drop')
        {
            return $this->getSchemaReportsDrop($headers);
        }
        
        else if($headers['Module'] == 'get-schema-entry')
        {
            return $this->getSchemaEntry($headers);
        }
        else if($headers['Module'] == 'get-order')
        {
            return $this->getOrder($headers);
        }
        else if($headers['Module'] == 'get-schema-master')
        {
            return $this->getSchemaMaster($headers);
        } 
        else if($headers['Module'] == 'get-last-schema')
        {
            return $this->getLastSchema($headers);
        }
        else if($headers['Module'] == 'scheme-type')
        {
            return $this->getSchemeType($headers);
        }
        else if($headers['Module'] == 'get-customer-scheme-by-cat')
        {
            return $this->getCustSchemeCat($headers);
        }
        else if($headers['Module'] == 'get-scheme-by-cust')
        {
            return $this->getSchemeByCust($headers);
        }else if($headers['Module'] == 'save-purchase-entry')
        {
            return $this->savePurchaseEntry($headers);
        }else if($headers['Module'] == 'get-order-deatils')
        {
            return $this->getOrderDetails($headers);
        }
        else {
            $responce['status'] = 0;
            $responce['message'] = 'Something is wrong';
            $responce['data'] = '';
            return $responce;
        }
        
    }
    
    function saveSchemaNameMaster($headers){
        
        if((!$_POST['login_user']) && (($_POST['login_user'] == null)))
        {
            $responce['status'] = 0;
            $responce['message'] = 'login_user is required';
            $responce['data'] = '';
            return $responce;
        } else {
            // print_r($_POST);die();
            
            $insertData = array(
                'scheme_id'               =>  $_POST['scheme_number'],
                'scheme_name'               =>  $_POST['scheme_name'],
                'amount'               =>  '',
                'months'               =>  $_POST['due_date'],
                'free'               =>  '',
                'category_name'               =>  '',
                'category'               =>  '',
                'status'               =>  'Active',
            );
            $data =  $this->db->insert('scheme_entry', $insertData);
            

            if($data)
            {
                $responce['status'] = 1;
                $responce['message'] = 'Sales Submitted';
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
    
    function saveSchemaMaster($headers){
        
        if((!$_POST['login_user']) && (($_POST['login_user'] == null)))
        {
            $responce['status'] = 0;
            $responce['message'] = 'login_user is required';
            $responce['data'] = '';
            return $responce;
        } else {
            // print_r($_POST);die();
            
            $this->db->where('sname',$_POST['scheme_name']);
            $shop =  $this->db->get('scheme_master');
            $schema = $shop->result_array();
            
            if($schema)
            {
                $responce['status'] = 0;
                $responce['message'] = 'Already Scheme exits';
                $responce['data'] = '';
                return $responce;
            }
            
            $insertData = array(
                'category'  =>  $_POST['category'],
                'sname'     =>  $_POST['scheme_name'],
                'due'       =>  $_POST['due_month'],
                'months'    =>  $_POST['total_month'],
            );
            $data =  $this->db->insert('scheme_master', $insertData);
            

            if($data)
            {
                $responce['status'] = 1;
                $responce['message'] = 'Scheme Master Submitted';
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
    
    
    function saveSchemaReg($headers){
        
        if((!$_POST['login_user']) && (($_POST['login_user'] == null)))
        {
            $responce['status'] = 0;
            $responce['message'] = 'login_user is required';
            $responce['data'] = '';
            return $responce;
        } else {
            // print_r($_POST);die();
            
             $this->db->order_by("sno", "desc");
            $shop =  $this->db->get('scheme_entry');
            $schema = $shop->result_array();
            
            // if($schema)
            // {
            //     $responce['status'] = 0;
            //     $responce['message'] = 'Already Schema exits';
            //     $responce['data'] = '';
            //     return $responce;
            // }
            
            $gold = 0;
            $silver = 0;
            
            $weight = 0;
            
            if(isset($_POST['category']))
            {
                if($_POST['category'] == 'Weight Wise')
                {
                    $deafault = $this->db->query("select * from master_today_default_price where branch='".$_POST['branch']."'");
                    $temp = $deafault->result_array();
                    
                    foreach($temp as $data)
                    {
                        if($data['iname'] == 'gold')
                        {
                            $gold = $data['price'];
                        }
                        if($data['iname'] == 'silver')
                        {
                            $silver = $data['price'];
                        }
                        
                    }
                    
                    $weight =  $_POST['due_date']/$gold;
                    $weight = number_format((float)$weight, 3, '.', '');
                }
            }
            // print_r($silver);
            // die();
            
            $total = $_POST['total_month']* $_POST['due_date'];
            
            $month = date("m");
            $year = date("Y");
            
            $ndate = $year.'-'.($month+1).'-10';
            
            $insertData = array(
                'sno'           =>  $schema?($schema[0]['sno']+1):0,
                'dat'           =>  date("Y-m-d"),
                'sname'         =>  isset($_POST['scheme_name'])?$_POST['scheme_name']:'.',
                'category'      =>  isset($_POST['category'])?$_POST['category']:'.',
                'dues'          =>  isset($_POST['total_month'])?$_POST['total_month']:'.',
                'due_amount'    =>  isset($_POST['due_date'])?$_POST['due_date']:'.',
                'total'         =>  $total,
                'paid'          =>  isset($_POST['due_date'])?$_POST['due_date']:'.',
                'paid_dues'     =>  1,
                'weight'        =>  $weight,
                'cid'           =>  isset($_POST['customer_id'])?$_POST['customer_id']:'.',
                'cname'         =>  isset($_POST['customer_name'])?$_POST['customer_name']:'.',
                'mobile'        =>  isset($_POST['mobile'])?$_POST['mobile']:'.',
                'acode'         =>  isset($_POST['client_id'])?$_POST['client_id']:'.',
                'aname'         =>  isset($_POST['client_name'])?$_POST['client_name']:'.',
                'remarks'       =>  isset($_POST['remark'])?$_POST['remark']:'.',
                'pby'       =>  isset($_POST['payment_mode'])?$_POST['payment_mode']:'.',
                'ndate'         =>  $ndate,
                'status'               =>  'Active',
                'billno'               =>  '.',
                'entered_by_branch'    =>  isset($_POST['branch'])?$_POST['branch']:'.',
            );
            $data =  $this->db->insert('scheme_entry', $insertData);
            
            $this->db->order_by("billno", "desc");
            $pay =  $this->db->get('scheme_pay');
            $schemaPay = $pay->result_array();
            
            
            
            
             $insertDataPay = array(
                'sno'           =>  $schema?($schema[0]['sno']+1):0,
                'billno'        =>  $schemaPay?($schemaPay[0]['billno']+1):0,
                'dat'           =>  date("Y-m-d"),
                'sname'         =>  isset($_POST['scheme_name'])?$_POST['scheme_name']:'.',
                'cid'           =>  isset($_POST['customer_id'])?$_POST['customer_id']:'.',
                'cname'         =>  isset($_POST['customer_name'])?$_POST['customer_name']:'.',
                'mobile'        =>  isset($_POST['mobile'])?$_POST['mobile']:'.',
                'acode'         =>  isset($_POST['client_id'])?$_POST['client_id']:'.',
                'aname'         =>  isset($_POST['client_name'])?$_POST['client_name']:'.',
                'remarks'       =>  isset($_POST['remark'])?$_POST['remark']:'.',
                'dues'          =>  isset($_POST['total_month'])?$_POST['total_month']:'.',
                'amount'        =>  isset($_POST['due_date'])?$_POST['due_date']:'.',
                'pby'           =>  isset($_POST['payment_mode'])?$_POST['payment_mode']:'.',
                'payment_id'    =>  isset($_POST['payment_id'])?$_POST['payment_id']:'.',
                'order_id'      =>  isset($_POST['order_id'])?$_POST['order_id']:'.',
                'signature'     =>  isset($_POST['signature'])?$_POST['signature']:'.',
                'paid_by_branch'    =>  isset($_POST['scheme_name'])?$_POST['branch']:'.',
            );
            $data =  $this->db->insert('scheme_pay', $insertDataPay);
            

            if($data)
            {
                $responce['status'] = 1;
                $responce['message'] = 'Scheme Master Submitted';
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
    
    function saveSchemaPay($headers){
        
        if((!$_POST['login_user']) && (($_POST['login_user'] == null)))
        {
            $responce['status'] = 0;
            $responce['message'] = 'login_user is required';
            $responce['data'] = '';
            return $responce;
        } else {
            // print_r($_POST);die();
            
            $this->db->order_by("billno", "desc");
            $shop =  $this->db->get('scheme_pay');
            $schema = $shop->result_array();
            
            // if($schema)
            // {
            //     $responce['status'] = 0;
            //     $responce['message'] = 'Already Schema exits';
            //     $responce['data'] = '';
            //     return $responce;
            // }
            
            $this->db->where('sno',$_POST['scheme_number']);
            $demo =  $this->db->get('scheme_entry');
            $demo_data = $demo->result_array();
            
            $demo_temp = $demo_data?$demo_data[0]:'.';
            
            if($demo_temp)
            {
            
            // print_r($demo_data[0]);die();
            
            $gold = 0;
            $silver = 0;
            
            $weight = 0;
            
            $weight_total = 0;
            
            $paid_dues = 0;
            
            $deafault = $this->db->query("select * from master_today_default_price where branch='".$_POST['branch']."'");
            $temp = $deafault->result_array();
            
            foreach($temp as $data)
            {
                if($data['iname'] == 'gold')
                {
                    $gold = $data['price'];
                }
                if($data['iname'] == 'silver')
                {
                    $silver = $data['price'];
                }
                
            }
            
            $weight =  $_POST['amount_paid']/$gold;
            
            $weight = number_format((float)$weight, 3, '.', '');
            
            $weight_total = $demo_temp['weight'] + $weight;
            
            // print_r($gold);die();
            
            $paid = $demo_temp['paid'] + $_POST['amount_paid'];
            $paid_dues = $demo_temp['paid_dues'] + $_POST['due_date'];
            
            $month = date("m");
            $year = date("Y");
            
            $ndate = $year.'-'.($month+1).'-10';
    
            $query11 = $this->db->query("UPDATE scheme_entry SET paid='".$paid."', paid_dues='".$paid_dues."', weight='".$weight_total."' , ndate='".$ndate."' WHERE sno=".$_POST['scheme_number'].";");
            // $data11 = $query11->result_array();
            
            $insertData = array(
                'sno'           =>   isset($_POST['scheme_number'])?$_POST['scheme_number']:'.',
                'billno'        =>  $schema?($schema[0]['billno']+1):0,
                'dat'           =>  date("Y-m-d"),
                'sname'         =>  isset($demo_temp['sname'])?$demo_temp['sname']:'.',
                'cid'           =>  isset($_POST['cid'])?$_POST['cid']:'.',
                'cname'         =>  isset($_POST['cname'])?$_POST['cname']:'.',
                'mobile'        =>  isset($_POST['mobile'])?$_POST['mobile']:'.',
                'acode'         =>  isset($_POST['acode'])?$_POST['acode']:'.',
                'aname'         =>  isset($_POST['aname'])?$_POST['aname']:'.',
                'remarks'       =>  isset($_POST['remark'])?$_POST['remark']:'.',
                'dues'          =>  isset($_POST['due_date'])?$_POST['due_date']:'.',
                'amount'        =>  isset($_POST['amount_paid'])?$_POST['amount_paid']:'.',
                'pby'           =>  isset($_POST['payment_mode'])?$_POST['payment_mode']:'.',
                'pay_type'      =>  isset($_POST['pay_type'])?$_POST['pay_type']:'direct',
                'payment_id'    =>  isset($_POST['payment_id'])?$_POST['payment_id']:'.',
                'order_id'      =>  isset($_POST['order_id'])?$_POST['order_id']:'.',
                'signature'     =>  isset($_POST['signature'])?$_POST['signature']:'.',
                'paid_by_branch'=>  isset($_POST['branch'])?$_POST['branch']:'.',
            );
            $data =  $this->db->insert('scheme_pay', $insertData);
            

            if($data)
            {
                $responce['status'] = 1;
                $responce['message'] = 'Scheme Master Submitted';
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
                $responce['message'] = 'Scheme not exits';
                $responce['data'] = '';
                return $responce;
            }
            
        }
       
    }
    
    function saveSchema($headers){
        
        if((!$_POST['username']) && (($_POST['username'] == null)))
        {
            $responce['status'] = 0;
            $responce['message'] = 'login_user is required';
            $responce['data'] = '';
            return $responce;
        } else {
            
            $insertData = array(
                'sname'               =>  $_POST['sname'],
            );
            $data =  $this->db->insert('scheme_name_master', $insertData);
            

            if($data)
            {
                $responce['status'] = 1;
                $responce['message'] = 'Scheme Submitted';
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
    
    
    function saveOrderEntry($headers){
        
        if((!$_POST['login_user']) && (($_POST['login_user'] == null)))
        {
            $responce['status'] = 0;
            $responce['message'] = 'login_user is required';
            $responce['data'] = '';
            return $responce;
        } else {
            
            
            // $key = "bTxh2c7y0QWjEl6w";	
            // $mbl="919657256675"; 	
            // /*or $mbl="XXXXXXXXXX,XXXXXXXXXX";*/
            // $message_content=urlencode("hello there");
            
            // $senderid="123";	
            // $route= 1;
            // $templateid="1234";
            // $url = "http://sms.selromsoft.in/vb/apikey.php?apikey=$key&senderid=$senderid&templateid=$templateid&number=$mbl&message=$message_content";
            					
            // $output = file_get_contents($url);
            
            // $data =  "http://sms.selromsoft.in/vb/apikey.php?apikey=bTxh2c7y0QWjEl6w&senderid=123&templateid=321&number=9657256675&message=Hello There"; 
            //echo $data;
            // $ch = curl_init();
            // curl_setopt( $ch, CURLOPT_URL, $data);
            // curl_setopt($ch, CURLOPT_RETURNTRANSFER, True);
            // curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, False);
            // $result = curl_exec($ch);
            // curl_close($ch);
            // echo $result;
            
            // echo '<pre>',
            // print_r($output);

            // print_r($_POST);die();
            
            $this->db->order_by('sno', 'DESC');
            $this->db->limit(1);
            $last =  $this->db->get('orders_entry');
            $last_code = $last->result_array();
            
            $insertData = array(
                'sno'               => $last_code[0]['sno'] + 1,
                'dat'               =>  $_POST['order_date'],
                'cid'               =>  $_POST['customer_id'],
                'cname'             =>  $_POST['customer_name'],
                'mobile'            =>  $_POST['mobile'],
                'otype'             =>  $_POST['order_type'],
                'particulars'       =>  $_POST['particulars'],
                'tot'               =>  $_POST['total_amt'],
                'bal'               =>  $_POST['blc_amt'],
                'paid'              =>  $_POST['paid_amt'],
                'ddate'             =>  $_POST['delivery_date'],
                'remarks'           =>  $_POST['remark'],
                'status'            =>  'Active',
                'entered_by_branch' =>  $_POST['branch'],
                
            );
            $data =  $this->db->insert('orders_entry', $insertData);
            

            if($data)
            {
                $responce['status'] = 1;
                $responce['message'] = 'Order Submitted';
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
    
    function saveOrderDelivery($headers){
        
        if((!$_POST['login_user']) && (($_POST['login_user'] == null)))
        {
            $responce['status'] = 0;
            $responce['message'] = 'login_user is required';
            $responce['data'] = '';
            return $responce;
        } else {
            
            // print_r($_POST);die();
            
            $this->db->order_by('billno', 'DESC');
            $this->db->limit(1);
            $last =  $this->db->get('orders_delivery');
            $last_code = $last->result_array();
            
            $insertData = array(
                'billno' => $last_code[0]['billno'] + 1,
                'dat'               =>  $_POST['order_date'],
                'onum'               =>  $_POST['order_number'],
                'otype'               =>  $_POST['order_type'],
                'ddate'               =>  $_POST['delivery_date'],
                'cid'               =>  $_POST['customer_id'],
                'cname'               =>  $_POST['customer_name'],
                'mobile'               =>  $_POST['mobile'],
                'otype'               =>  $_POST['order_type'],
                'particulars'               =>  $_POST['particulars'],
                'tot'               =>  $_POST['total_amt'],
                'paid'               =>  $_POST['paid_amt'],
                'others'               =>  $_POST['other_amt'],
                'discount'               =>  $_POST['dis_amt'],
                'net'               =>  $_POST['net'],
                'remarks'               =>  $_POST['remark'],
                'entered_by_branch'               =>  $_POST['branch'],
                
            );
            $data =  $this->db->insert('orders_delivery', $insertData);
            

            if($data)
            {
                $responce['status'] = 1;
                $responce['message'] = 'Order Submitted';
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
    
    function getSchema($headers)
    {
        if((!$_POST['username']) && (($_POST['username'] == null)))
        {
            $responce['status'] = 0;
            $responce['message'] = 'Username is required';
            $responce['data'] = '';
            return $responce;
        } else {
            
            // $this->db->order_by("id", "desc");
            $shop =  $this->db->get('scheme_name_master');
            $data = $shop->result_array();
            

            if(count($data) > 0)
            {
                $responce['status'] = 1;
                $responce['message'] = 'Scheme List';
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
    
    function getSchemaReports($headers)
    {
        if((!$_POST['login_user']) && (($_POST['login_user'] == null)))
        {
            $responce['status'] = 0;
            $responce['message'] = 'Username is required';
            $responce['data'] = '';
            return $responce;
        } else {
            
            // print_r($_POST);die();
            $date = date("Y/m/d");
            
            $data = array();
            
             if(isset($_POST['from']) && isset($_POST['from']))
            {
                $_POST['from'] = ($_POST['from'] == 'Invalid date')?date('Y-m-d'):$_POST['from'];
                $_POST['to'] = ($_POST['to']== 'Invalid date')?date('Y-m-d'):$_POST['to'];
            }
            
            if($_POST['type'] == 'scheme-report')
            {
                $query11 = $this->db->query("SELECT 
                sno,DATE_FORMAT(dat,'%d/%m/%Y') as dat,sname,category,dues,paid_dues,due_amount,total,paid,pby,weight,cid,cname,mobile,acode,aname,remarks,STATUS 
                FROM scheme_entry 
                WHERE dat BETWEEN '".$_POST['from']."' AND '".$_POST['to']."' 
                AND entered_by_branch='".$_POST['branch']."' ORDER BY dat,sno ");
                $data = $query11->result_array();
            }
            
            if($_POST['type'] == 'scheme-report-cat')
            {
                $query11 = $this->db->query("SELECT 
                sno,DATE_FORMAT(dat,'%d/%m/%Y') as dat,sname,category,dues,paid_dues,due_amount,total,paid,weight,cid,cname,mobile,acode,aname,remarks,STATUS 
                FROM scheme_entry 
                WHERE dat BETWEEN '".$_POST['from']."' AND '".$_POST['to']."' 
                AND entered_by_branch='".$_POST['branch']."'  
                AND category='".$_POST['drop']."'  ORDER BY dat,sno ");
                $data = $query11->result_array();
            }
            
            if($_POST['type'] == 'scheme-report-cust')
            {
                $query11 = $this->db->query("SELECT 
                sno,DATE_FORMAT(dat,'%d/%m/%Y') as dat,sname,category,dues,paid_dues,due_amount,total,paid,weight,cid,cname,mobile,acode,aname,remarks,STATUS 
                FROM scheme_entry 
                WHERE dat BETWEEN '".$_POST['from']."' AND '".$_POST['to']."' 
                AND entered_by_branch='".$_POST['branch']."'  
                AND cname='".$_POST['drop']."'  ORDER BY dat,sno ");
                $data = $query11->result_array();
            }
            
            if($_POST['type'] == 'scheme-report-client')
            {
                $query11 = $this->db->query("SELECT 
                sno,DATE_FORMAT(dat,'%d/%m/%Y') as dat,sname,category,dues,paid_dues,due_amount,total,paid,weight,cid,cname,mobile,acode,aname,remarks,STATUS 
                FROM scheme_entry 
                WHERE dat BETWEEN '".$_POST['from']."' AND '".$_POST['to']."' 
                AND entered_by_branch='".$_POST['branch']."'  
                AND aname='".$_POST['drop']."'  ORDER BY dat,sno ");
                $data = $query11->result_array();
            }
            
            if($_POST['type'] == 'scheme-report-over')
            {
                $query11 = $this->db->query("SELECT 
                sno,sname,TIMESTAMPDIFF(MONTH,ndate, CURDATE()) AS No_of_Dues, ((due_amount)* (TIMESTAMPDIFF(MONTH,ndate, CURDATE()))) AS Overdue_Amount, 
                cid,cname,mobile,acode,aname,remarks 
                FROM scheme_entry 
                WHERE entered_by_branch='".$_POST['branch']."' AND ndate <  CURDATE() ORDER BY ndate desc ");
                $data = $query11->result_array();
            }
            
            if($_POST['type'] == 'scheme-pay-report')
            {
                $query11 = $this->db->query("SELECT 
                billno,DATE_FORMAT(dat,'%d/%m/%Y') as dat,sno,sname,cid,cname,mobile,dues,amount,pby,remarks 
                FROM scheme_pay 
                WHERE dat BETWEEN '".$_POST['from']."' AND '".$_POST['to']."' 
                AND paid_by_branch='".$_POST['branch']."' ORDER BY dat,billno");
                $data = $query11->result_array();
            }
            
            
            if($_POST['type'] == 'scheme--pay-report-mode')
            {
                $query11 = $this->db->query("SELECT 
                billno,DATE_FORMAT(dat,'%d/%m/%Y') as dat,sno,sname,cid,cname,mobile,dues,amount,pby,remarks 
                FROM scheme_pay 
                WHERE dat BETWEEN '".$_POST['from']."' AND '".$_POST['to']."'
                AND paid_by_branch='".$_POST['branch']."' AND pby='".$_POST['drop']."' ORDER BY dat,billno");
                $data = $query11->result_array();
            }
            
            if($_POST['type'] == 'scheme-pay-report-cust')
            {
                $query11 = $this->db->query("SELECT 
                billno,DATE_FORMAT(dat,'%d/%m/%Y') as dat,sno,sname,cid,cname,mobile,dues,amount,pby,remarks 
                FROM scheme_pay 
                WHERE dat BETWEEN '".$_POST['from']."' AND '".$_POST['to']."'
                AND paid_by_branch='".$_POST['branch']."' AND cname='".$_POST['drop']."' ORDER BY dat,billno");
                $data = $query11->result_array();
            }
            
            if($_POST['type'] == 'scheme-pay-report-sname')
            {
                $query11 = $this->db->query("SELECT 
                billno,DATE_FORMAT(dat,'%d/%m/%Y') as dat,sno,sname,cid,cname,mobile,dues,amount,pby,remarks 
                FROM scheme_pay 
                WHERE dat BETWEEN '".$_POST['from']."' AND '".$_POST['to']."'
                AND paid_by_branch='".$_POST['branch']."' AND sname='".$_POST['drop']."' ORDER BY dat,billno");
                $data = $query11->result_array();
            }
            
            if($_POST['type'] == 'scheme-pay-report-aname')
            {
                $query11 = $this->db->query("SELECT 
                billno,DATE_FORMAT(dat,'%d/%m/%Y') as dat,sno,sname,cid,cname,mobile,dues,amount,pby,remarks 
                FROM scheme_pay 
                WHERE dat BETWEEN '".$_POST['from']."' AND '".$_POST['to']."'
                AND paid_by_branch='".$_POST['branch']."' AND aname='".$_POST['drop']."' ORDER BY dat,billno");
                $data = $query11->result_array();
            }
            
            if($_POST['type'] == 'scheme-list')
            {
                $query11 = $this->db->query("SELECT category,sname,due,months FROM scheme_master ORDER BY category");
                $data = $query11->result_array();
            }
            
            if($_POST['type'] == 'scheme-list-cat')
            {
                $query11 = $this->db->query("SELECT category,sname,due,months FROM scheme_master WHERE category='".$_POST['drop']."'");
                $data = $query11->result_array();
            }
            
            if($_POST['type'] == 'order-active')
            {
                $query11 = $this->db->query("SELECT 
                sno,DATE_FORMAT(ddate,'%d/%m/%Y') as ddate,DATE_FORMAT(dat,'%d/%m/%Y') as dat,cid,cname,mobile,otype,particulars,tot,paid,bal,pby,remarks 
                FROM orders_entry 
                WHERE STATUS='Active' AND entered_by_branch='".$_POST['branch']."' ORDER BY ddate desc");
                $data = $query11->result_array();
            }
            
            if($_POST['type'] == 'undelivered-orders')
            {
                $query11 = $this->db->query("SELECT 
                sno,DATE_FORMAT(ddate,'%d/%m/%Y') as ddate,DATE_FORMAT(dat,'%d/%m/%Y') as dat,cid,cname,mobile,otype,particulars,tot,paid,bal,pby,remarks 
                FROM orders_entry 
                WHERE STATUS='Active' AND entered_by_branch='".$_POST['branch']."' AND ddate < CURDATE()   ORDER BY ddate desc");
                $data = $query11->result_array();
            }
            
            if($_POST['type'] == 'order-reports')
            {
                $query11 = $this->db->query("SELECT 
                billno,DATE_FORMAT(ddate,'%d/%m/%Y') as ddate,DATE_FORMAT(dat,'%d/%m/%Y') as dat,cid,cname,mobile,onum,otype,particulars,tot,others,discount,paid,net,pby,remarks 
                FROM orders_delivery 
                WHERE dat BETWEEN '".$_POST['from']."' AND '".$_POST['to']."'
                AND entered_by_branch='".$_POST['branch']."' ORDER BY dat,billno");
                $data = $query11->result_array();
            }
            
            if($_POST['type'] == 'order-reports-type')
            {
                $query11 = $this->db->query("SELECT 
                billno,DATE_FORMAT(ddate,'%d/%m/%Y') as ddate,DATE_FORMAT(dat,'%d/%m/%Y') as dat,cid,cname,mobile,onum,otype,particulars,tot,others,discount,paid,net,pby,remarks 
                FROM orders_delivery 
                WHERE dat BETWEEN '".$_POST['from']."' AND '".$_POST['to']."'
                AND entered_by_branch='".$_POST['branch']."' AND otype='".$_POST['drop']."' ORDER BY dat,billno");
                $data = $query11->result_array();
            }
            
            
            

            if(count($data) > 0)
            {
                $responce['status'] = 1;
                $responce['message'] = 'Scheme reports ';
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
    
    
    function getGstrReports($headers)
    {
        if((!$_POST['login_user']) && (($_POST['login_user'] == null)))
        {
            $responce['status'] = 0;
            $responce['message'] = 'Username is required';
            $responce['data'] = '';
            return $responce;
        } else {
            
            // print_r($_POST);die();
            $date = date("Y/m/d");
            
            $data = array();
            
            if($_POST['type'] == 'gstr-purchase-report')
            {
                $query11 = $this->db->query("SELECT 
                cname, tax_no,billno,DATE_FORMAT(bdate,'%d/%m/%Y') as bdate,taxp,gt,taxamt,gt+taxamt,grn,tax_type 
                FROM purchase 
                WHERE bdate BETWEEN '".$_POST['from']."' AND '".$_POST['to']."' 
                AND branch='".$_POST['branch']."' 
                ORDER BY bdate,grn");
                $data = $query11->result_array();
            }
            
            if($_POST['type'] == 'gstr-purchase-return-report')
            {
                $query11 = $this->db->query("SELECT 
                cname, tax_no,billno,DATE_FORMAT(bdate,'%d/%m/%Y') as bdate,taxp,gt,taxamt,gt+taxamt,grn,tax_type 
                FROM purchase_return  
                WHERE bdate BETWEEN '".$_POST['from']."' AND '".$_POST['to']."' 
                AND branch='".$_POST['branch']."' 
                ORDER BY bdate,grn");
                $data = $query11->result_array();
            }
            
            
            if($_POST['type'] == 'gstr-purchase-summary')
            {
                $query11 = $this->db->query("SELECT 
                taxp,sum(gt) as gt,sum(taxamt) as taxamt,sum(gt+taxamt) as net,tax_type 
                FROM purchase 
                WHERE bdate BETWEEN '".$_POST['from']."' AND '".$_POST['to']."' 
                AND branch='".$_POST['branch']."'  
                group by taxp,tax_type ORDER BY taxp");
                $data = $query11->result_array();
            }
            
            if($_POST['type'] == 'gstr-purchase-return-summary')
            {
                $query11 = $this->db->query("SELECT 
                taxp,sum(gt) as gt,sum(taxamt) as taxamt,sum(gt+taxamt) as net,tax_type 
                FROM purchase_return 
                WHERE bdate BETWEEN '".$_POST['from']."' AND '".$_POST['to']."' 
                AND branch='".$_POST['branch']."'  
                group by taxp,tax_type ORDER BY taxp");
                $data = $query11->result_array();
            }
            
            if($_POST['type'] == 'gstr-sales-report')
            {
                $query11 = $this->db->query("SELECT 
                cname,tax_no,billno,DATE_FORMAT(dat,'%d/%m/%Y') as dat,taxp,gross_amt,tax_amt,(gross_amt+tax_amt) as net,tax_type
                FROM sales 
                WHERE dat BETWEEN '".$_POST['from']."' AND '".$_POST['to']."'
                AND branch='".$_POST['branch']."'  
                ORDER BY dat,billno;");
                $data = $query11->result_array();
            }
            
            if($_POST['type'] == 'gstr-sales-return-report')
            {
                $query11 = $this->db->query("SELECT 
                cname,tax_no,billno,DATE_FORMAT(dat,'%d/%m/%Y') as dat,taxp,gross_amt,tax_amt,(gross_amt+tax_amt) as net,tax_type
                FROM sales_return  
                WHERE dat BETWEEN '".$_POST['from']."' AND '".$_POST['to']."'
                AND branch='".$_POST['branch']."'  
                ORDER BY dat,billno;");
                $data = $query11->result_array();
            }
            
            if($_POST['type'] == 'gstr-sales-summary')
            {
                $query11 = $this->db->query("SELECT 
                taxp,sum(gross_amt) as gt,sum(tax_amt) as tax_amt,sum(gross_amt+tax_amt) as net,tax_type 
                FROM sales 
                WHERE dat BETWEEN '".$_POST['from']."' AND '".$_POST['to']."'
                AND branch='".$_POST['branch']."'   
                group by taxp,tax_type 
                ORDER BY taxp");
                $data = $query11->result_array();
            }
            
            if($_POST['type'] == 'gstr-sales-return-summary')
            {
                $query11 = $this->db->query("SELECT 
                taxp,sum(gross_amt) as gt,sum(tax_amt) as tax_amt,sum(gross_amt+tax_amt) as net,tax_type 
                FROM sales_return 
                WHERE dat BETWEEN '".$_POST['from']."' AND '".$_POST['to']."'
                AND branch='".$_POST['branch']."'   
                group by taxp,tax_type 
                ORDER BY taxp");
                $data = $query11->result_array();
            }
            
           
            
            
            

            if(count($data) > 0)
            {
                $responce['status'] = 1;
                $responce['message'] = 'GSTR reports ';
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
    
    function getSchemaReportsDrop($headers)
    {
        if((!$_POST['login_user']) && (($_POST['login_user'] == null)))
        {
            $responce['status'] = 0;
            $responce['message'] = 'Username is required';
            $responce['data'] = '';
            return $responce;
        } else {
            
            // print_r($_POST);die();
            
            $data = array();
            
            if($_POST['type'] == 'customer')
            {
                $query11 = $this->db->query("SELECT distinct cname FROM scheme_entry where entered_by_branch='".$_POST['branch']."'");
                $data = $query11->result_array();
            }
            
            if($_POST['type'] == 'client')
            {
                $query11 = $this->db->query("SELECT distinct aname FROM scheme_entry where entered_by_branch='".$_POST['branch']."'");
                $data = $query11->result_array();
            }
            
            if($_POST['type'] == 'pby')
            {
                $query11 = $this->db->query("SELECT distinct pby FROM scheme_pay  where paid_by_branch='".$_POST['branch']."'");
                $data = $query11->result_array();
            }
            
            if($_POST['type'] == 'scheme')
            {
                $query11 = $this->db->query("SELECT distinct sname FROM scheme_pay  where paid_by_branch='".$_POST['branch']."'");
                $data = $query11->result_array();
            }
            
            
            

            if(count($data) > 0)
            {
                $responce['status'] = 1;
                $responce['message'] = 'Scheme reports ';
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
    
    function getSchemaEntry($headers)
    {
        if((!$_POST['username']) && (($_POST['username'] == null)))
        {
            $responce['status'] = 0;
            $responce['message'] = 'Username is required';
            $responce['data'] = '';
            return $responce;
        } else {
            
            // $this->db->order_by("id", "desc");
            $shop =  $this->db->get('scheme_entry');
            $data = $shop->result_array();
            

            if(count($data) > 0)
            {
                $responce['status'] = 1;
                $responce['message'] = 'Scheme List';
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

    function getOrder($headers)
    {
        if((!$_POST['username']) && (($_POST['username'] == null)))
        {
            $responce['status'] = 0;
            $responce['message'] = 'Login User is required';
            $responce['data'] = '';
            return $responce;
        } else {
            
            $this->db->order_by("sno", "desc");
            $shop =  $this->db->get('orders_entry');
            $data = $shop->result_array();
            

            if(count($data) > 0)
            {
                $responce['status'] = 1;
                $responce['message'] = 'Order List';
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
    
    function getLastSchema($headers)
    {
        if((!$_POST['username']) && (($_POST['username'] == null)))
        {
            $responce['status'] = 0;
            $responce['message'] = 'Username is required';
            $responce['data'] = '';
            return $responce;
        } else {
            
            $this->db->order_by('billno', 'DESC');
            $this->db->where('paid_by_branch',$_POST['branch']);
            $this->db->limit(1);
            $last =  $this->db->get('scheme_pay');
            $last_code = $last->result_array();
                
                
            $data['last_id_pay'] = $last_code?($last_code[0]['billno'] + 1):1;
            $data['today'] = date("Y-m-d");
            
            // $this->db->where('master_branch.branch',$_POST['branch']);
            // $this->db->join('master_branch', 'master_item.branch= master_branch.branch');
            // // $this->db-select('master_branch.branch as branchname');
            // $shop =  $this->db->get('master_item');
            // $data['item_no'] = count($shop->result_array()) + 1;
            

            if(count($data) > 0)
            {
                $responce['status'] = 1;
                $responce['message'] = 'last Scheme';
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
    
    function getSchemaMaster($headers)
    {
        if((!$_POST['username']) && (($_POST['username'] == null)))
        {
            $responce['status'] = 0;
            $responce['message'] = 'Username is required';
            $responce['data'] = '';
            return $responce;
        } else {
            
            // $this->db->order_by("id", "desc");
            $shop =  $this->db->get('scheme_master');
            $data = $shop->result_array();
            

            if(count($data) > 0)
            {
                $responce['status'] = 1;
                $responce['message'] = 'Scheme Master';
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
    
    function getSchemeType($headers)
    {
        if((!$_POST['login_user']) && (($_POST['login_user'] == null)))
        {
            $responce['status'] = 0;
            $responce['message'] = 'login_user is required';
            $responce['data'] = '';
            return $responce;
        } else {
            
            // $data = array("Weight Wise","Amount Wise","Lucky Draw");
            $this->db->order_by('sno', 'ASC');
            $last =  $this->db->get('scheme_name_master');
            $data = $last->result_array();
            

            if(count($data) > 0)
            {
                $responce['status'] = 1;
                $responce['message'] = 'Scheme Type';
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
    
    function getCustSchemeCat($headers)
    {
        if((!$_POST['login_user']) && (($_POST['login_user'] == null)))
        {
            $responce['status'] = 0;
            $responce['message'] = 'login_user is required';
            $responce['data'] = '';
            return $responce;
        } else {
            
            // $data = array("Weight Wise","Amount Wise","Lucky Draw");
            $this->db->where('mobile',$_POST['mobile']);
            $this->db->where('category',$_POST['category']);
            $last =  $this->db->get('scheme_entry');
            $data = $last->result_array();
            

            if(count($data) > 0)
            {
                $responce['status'] = 1;
                $responce['message'] = 'Scheme Type';
                $responce['data'] = $data;
                return $responce;
            } else {
                $responce['status'] = 0;
                $responce['message'] = 'No Data Found!';
                $responce['data'] = '';
                return $responce;
            }
            
        }
    }

    function getSchemeByCust($headers)
    {
        if((!$_POST['login_user']) && (($_POST['login_user'] == null)))
        {
            $responce['status'] = 0;
            $responce['message'] = 'login_user is required';
            $responce['data'] = '';
            return $responce;
        } else if((!$_POST['mobile']) && (($_POST['mobile'] == null)))
        {
            $responce['status'] = 0;
            $responce['message'] = 'mobile is required';
            $responce['data'] = '';
            return $responce;
        } else {
            
            // $data = array("Weight Wise","Amount Wise","Lucky Draw");
            
            $this->db->where('mobile',$_POST['mobile']);
            if($_POST['category'] != 'all')
                $this->db->where('category',$_POST['category']);
            $last =  $this->db->get('scheme_entry');
            $data = $last->result_array();
            
            

            if(count($data) > 0)
            {
                $responce['status'] = 1;
                $responce['message'] = 'Scheme Type';
                $responce['data'] = $data;
                $responce['category'] = $dataCat;
                return $responce;
            } else {
                $responce['status'] = 0;
                $responce['message'] = 'No Data Found!';
                $responce['data'] = '';
                return $responce;
            }
            
        }
    }
    
    function savePurchaseEntry($headers){
        
        if((!$_POST['login_user']) && (($_POST['login_user'] == null)))
        {
            $responce['status'] = 0;
            $responce['message'] = 'login_user is required';
            $responce['data'] = '';
            return $responce;
        } else {
            
            $last = 0;
            // print_r($_POST);die();
            
            if($_POST['type'] == 'purchase-entry')
            {
                $insertData = array(
                    'dat'       =>  $_POST['dat']?$_POST['dat']:date('Y-m-d'),
                    'jobw'      =>  $_POST['jobw']?$_POST['jobw']:'.',
                    'part'      =>  $_POST['part']?$_POST['part']:'.',
                    'billno'    =>  $_POST['billno']?$_POST['billno']:'.',
                    'bdate'     =>  $_POST['bdate']?$_POST['bdate']:date('Y-m-d'),
                    'hsn'       =>  $_POST['hsn'],
                    'weight'    =>  $_POST['weight'],
                    'alloywt'   =>  $_POST['alloywt'],
                    'alloyhsn'  =>  $_POST['alloyhsn'],
                    'rate'      =>  $_POST['rate'],
                    'amt'       =>  $_POST['amt'],
                    'gst'       =>  $_POST['gst'],
                    'gstrs'     =>  $_POST['gstrs'],
                    'tds'       =>  $_POST['tds'],
                    'tdsrs'     =>  $_POST['tdsrs'],
                    'total'     =>  $_POST['total'],
                    'user'      =>  $_POST['login_user'],
                    'entered_by_branch' =>  $_POST['branch'],
                    
                );
                $data =  $this->db->insert('order_entry', $insertData);
                $msg = "Purchase Entry Submitted";
                
                $this->db->order_by('sno', 'DESC');
                $this->db->limit(1);
                $last =  $this->db->get('order_entry');
                $last_code = $last->result_array();
                
                $last = $last_code[0]['sno'];
            }
            
            if($_POST['type'] == 'issue-manu')
            {
                $insertData = array(
                    'dat'       =>  $_POST['dat']?$_POST['dat']:date('Y-m-d'),
                    'jobw'      =>  $_POST['jobw']?$_POST['jobw']:'.',
                    'part'      =>  $_POST['part']?$_POST['part']:'.',
                    'hsn'       =>  $_POST['hsn'],
                    'weight'    =>  $_POST['weight'],
                    'alloywt'   =>  $_POST['alloywt'],
                    'alloyhsn'  =>  $_POST['alloyhsn'],
                    'rate'      =>  $_POST['rate'],
                    'amt'       =>  $_POST['amt'],
                    'gst'       =>  $_POST['gst'],
                    'gstrs'     =>  $_POST['gstrs'],
                    'stone'       =>  $_POST['tds'],
                    'tweight'     =>  $_POST['tdsrs'],
                    'total'     =>  $_POST['total'],
                    'user'      =>  $_POST['login_user'],
                    'entered_by_branch' =>  $_POST['branch'],
                    
                );
                $data =  $this->db->insert('order_issue', $insertData);
                
                $msg = "Issue to Manufacturer Submitted";
                
                $this->db->order_by('sno', 'DESC');
                $this->db->limit(1);
                $last =  $this->db->get('order_issue');
                $last_code = $last->result_array();
                
                $last = $last_code[0]['sno'];
            }
            
            if($_POST['type'] == 'manu-show')
            {
                $insertData = array(
                    'dat'       =>  $_POST['dat']?$_POST['dat']:date('Y-m-d'),
                    'jobw'      =>  $_POST['jobw']?$_POST['jobw']:'.',
                    'part'      =>  $_POST['part']?$_POST['part']:'.',
                    'billno'    =>  $_POST['billno']?$_POST['billno']:'.',
                    'bdate'     =>  $_POST['bdate']?$_POST['bdate']:date('Y-m-d'),
                    'hsn'       =>  $_POST['hsn'],
                    'weight'    =>  $_POST['weight'],
                    'alloywt'   =>  $_POST['alloywt'],
                    'alloyhsn'  =>  $_POST['alloyhsn'],
                    'rate'      =>  $_POST['rate'],
                    'amt'       =>  $_POST['amt'],
                    'gst'       =>  $_POST['gst'],
                    'gstrs'     =>  $_POST['gstrs'],
                    'tds'       =>  $_POST['tds'],
                    'tdsrs'     =>  $_POST['tdsrs'],
                    'total'     =>  $_POST['total'],
                    'user'      =>  $_POST['login_user'],
                    'entered_by_branch' =>  $_POST['branch'],
                    
                );
                $data =  $this->db->insert('order_manu', $insertData);
                $msg = "Manufacture to Showroom Submitted";
                
                $this->db->order_by('sno', 'DESC');
                $this->db->limit(1);
                $last =  $this->db->get('order_manu');
                $last_code = $last->result_array();
                
                $last = $last_code[0]['sno'];
            }
            
             if($_POST['type'] == 'issue-cust')
            {
                $insertData = array(
                    'dat'       =>  $_POST['dat']?$_POST['dat']:date('Y-m-d'),
                    'jobw'      =>  $_POST['jobw']?$_POST['jobw']:'.',
                    'part'      =>  $_POST['part']?$_POST['part']:'.',
                    'hsn'       =>  $_POST['hsn'],
                    'weight'    =>  $_POST['weight'],
                    'alloywt'   =>  $_POST['alloywt'],
                    'alloyhsn'  =>  $_POST['alloyhsn'],
                    'rate'      =>  $_POST['rate'],
                    'amt'       =>  $_POST['amt'],
                    'gst'       =>  $_POST['gst'],
                    'gstrs'     =>  $_POST['gstrs'],
                    'stone'       =>  $_POST['tds'],
                    'tweight'     =>  $_POST['tdsrs'],
                    'total'     =>  $_POST['total'],
                    'user'      =>  $_POST['login_user'],
                    'entered_by_branch' =>  $_POST['branch'],
                    
                );
                $data =  $this->db->insert('cust_issue', $insertData);
                
                $msg = "Issue to Customer Submitted";
                
                $this->db->order_by('sno', 'DESC');
                $this->db->limit(1);
                $last =  $this->db->get('cust_issue');
                $last_code = $last->result_array();
                
                $last = $last_code[0]['sno'];
            }
            
            

            if($data)
            {
                $responce['status'] = 1;
                $responce['message'] = $msg;
                $responce['data'] = $data;
                $responce['last'] = $last;
                return $responce;
            } else {
                $responce['status'] = 0;
                $responce['message'] = 'Samething is wrong';
                $responce['data'] = '';
                return $responce;
            }
            
        }
       
    }
    
    
    function getOrderDetails($headers){
        
        if((!$_POST['login_user']) && (($_POST['login_user'] == null)))
        {
            $responce['status'] = 0;
            $responce['message'] = 'login_user is required';
            $responce['data'] = '';
            return $responce;
        } else {
            
            $last = 0;
            // print_r($_POST);die();
            
            if($_POST['type'] == '/purchase-entry')
            {
                $msg = "Purchase-entry data";
                
                $this->db->where('sno',$_POST['billno']);
                $last =  $this->db->get('order_entry');
                $data = $last->result_array();
            }
            
            if($_POST['type'] == '/issue-to-menu')
            {
                $msg = "Issue to Manufacturer data";
                
                $this->db->where('sno',$_POST['billno']);
                $last =  $this->db->get('order_issue');
                $data = $last->result_array();
            }
            
            if($_POST['type'] == '/menu-to-show')
            {
                $msg = "Manufacture to Showroom data";
                
                $this->db->where('sno',$_POST['billno']);
                $last =  $this->db->get('order_manu');
                $data = $last->result_array();
                
            }
            
             if($_POST['type'] == '/issue-to-cust')
            {
                $msg = "Issue to Customer data";
                
                $this->db->where('sno',$_POST['billno']);
                $last =  $this->db->get('cust_issue');
                $data = $last->result_array();
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
    
}