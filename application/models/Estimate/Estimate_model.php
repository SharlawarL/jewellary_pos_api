<?php

class Estimate_model extends CI_Model
{
    public function module($headers){
        if($headers['Module'] == 'get-sales')
        {
            return $this->getSales($headers);
        } else if($headers['Module'] == 'get-sales-reports')
        {
            return $this->getSalesReport($headers);
        } else if($headers['Module'] == 'get-tax')
        {
            return $this->getTax($headers);
        } else if($headers['Module'] == 'get-stock')
        {
            return $this->getStocks($headers);
        } else if($headers['Module'] == 'get-bill-deatils')
        {
            return $this->getBill($headers);
        } else if($headers['Module'] == 'get-bill-format')
        {
            return $this->getBillFormate($headers);
        } else if($headers['Module'] == 'get-view-bill')
        {
            return $this->getViewBill($headers);
        } else if($headers['Module'] == 'get-retrive-bill')
        {
            return $this->getRetriveBill($headers);
        } else if($headers['Module'] == 'save-sales')
        {
            return $this->saveSales($headers);
        } else if($headers['Module'] == 'delete-sales')
        {
            return $this->deleteSales($headers);
        } else if($headers['Module'] == 'get-last-sales')
        {
            return $this->getLastSales($headers);
        }  else {
            $responce['status'] = 0;
            $responce['message'] = 'Something is wrong';
            $responce['data'] = '';
            return $responce;
        }
        
    }
    
    function getSales($headers){
        if((!$_POST['login_user']) && (($_POST['login_user'] == null)))
        {
            $responce['status'] = 0;
            $responce['message'] = 'login_user is required';
            $responce['data'] = '';
            return $responce;
        } else {
//             if (isset($_POST['search_key']) && $_POST['search_key'] != '' && $_POST['search_key'] != null) {
// 				$search_key = $_POST['search_key'];
// 				$like = "(master_sales.item like '%$search_key%' or master_sales.sales_code like '%$search_key%' or master_customer.cname like '%$search_key%')"; 
// 				$this->db->where($like);
// 			}
			
//            $this->db->order_by("sales_no", "desc");
            // $this->db->join('master_item', 'stock_entry.item_name = master_item.item_name');
            // $this->db->select('master_item.item_no as master_item_no');
            $shop =  $this->db->get('estimate');
            $data = $shop->result_array();
            
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
    
    function getSalesReport($headers)
    {
        if((!$_POST['login_user']) && (($_POST['login_user'] == null)))
        {
            $responce['status'] = 0;
            $responce['message'] = 'login_user is required';
            $responce['data'] = '';
            return $responce;
        } else {
            $data = array();
            
            if($_POST['type'] == 'report-day')
            {
                $query11 = $this->db->query("select t.cashier,Count(t.billno) as billcount, Sum(case when t.pby = 'Cash' then t.net else null end) as Cash,Sum(case when t.pby = 'Card' then t.net else null end) as Card, Sum(case when t.pby = 'Credit' then t.net else null end) as Credit, Sum(case when t.pby = 'Others' then t.net else null end) as Others,   Sum(t.net) as Total 
                                                from estimate t 
                                                where t.dat between '".$_POST['from']."' and '".$_POST['to']."'  AND t.branch='".$_POST['branch']."' group by t.cashier order by t.cashier");
                $data = $query11->result_array();
            }
            
            if($_POST['type'] == 'report-total')
            {
                $query11 = $this->db->query("select billno,date_format(dat,'%d/%m/%Y') as bdate, tim, cashier,items, quans,tweight,sub_total,make,old_amt,dis_per,dis_amt,gross_amt,tax_amt,net,pby,cid,cname,mobile,remarks
                                                from estimate
                                                where dat between '".$_POST['from']."' and '".$_POST['to']."'  AND branch='".$_POST['branch']."' order by dat,billno");
                $data = $query11->result_array();
            }
            
            if($_POST['type'] == 'report-total')
            {
                $query11 = $this->db->query("select billno,date_format(dat,'%d/%m/%Y') as bdate, tim, cashier,items, quans,tweight,sub_total,make,old_amt,dis_per,dis_amt,gross_amt,tax_amt,net,pby,cid,cname,mobile,remarks
                                                from estimate
                                                where dat between '".$_POST['from']."' and '".$_POST['to']."'  AND branch='".$_POST['branch']."' order by dat,billno DESC");
                $data = $query11->result_array();
            }
            
            if($_POST['type'] == 'report-customer')
            {
                $query11 = $this->db->query("select billno,date_format(dat,'%d/%m/%Y') as bdate, tim, cashier,items, quans,tweight,sub_total,make,old_amt,dis_per,dis_amt,gross_amt,tax_amt,net,pby,cid,cname,mobile,remarks
                                                from estimate
                                                where dat between '".$_POST['from']."' and '".$_POST['to']."'  AND branch='".$_POST['branch']."' and cid='".$_POST['id']."' order by dat,billno");
                $data = $query11->result_array();
            }
            
            if($_POST['type'] == 'report-cashier')
            {
                $query11 = $this->db->query("select billno,date_format(dat,'%d/%m/%Y') as bdate, tim, cashier,items, quans,tweight,sub_total,make,old_amt,dis_per,dis_amt,gross_amt,tax_amt,net,pby,cid,cname,mobile,remarks
                                                from estimate
                                                where dat between '".$_POST['from']."' and '".$_POST['to']."'  AND branch='".$_POST['branch']."' and cashier='".$_POST['customer']."' order by dat,billno");
                $data = $query11->result_array();
            }
            
            if($_POST['type'] == 'report-cashier')
            {
                $query11 = $this->db->query("select billno,date_format(dat,'%d/%m/%Y'), tim, cashier,items, quans,tweight,sub_total,make,old_amt,dis_per,dis_amt,gross_amt,tax_amt,net,pby,cid,cname,mobile,remarks
                                                from estimate
                                                where dat between '".$_POST['from']."' and '".$_POST['to']."'  AND branch='".$_POST['branch']."' and cashier='".$_POST['customer']."' order by dat,billno");
                $data = $query11->result_array();
            }
            
            if($_POST['type'] == 'report-pay')
            {
                $query11 = $this->db->query("select billno,date_format(dat,'%d/%m/%Y'), tim, cashier,items, quans,tweight,sub_total,make,old_amt,dis_per,dis_amt,gross_amt,tax_amt,net,pby,cid,cname,mobile,remarks
                                                from estimate
                                                where dat between '".$_POST['from']."' and '".$_POST['to']."'  AND branch='".$_POST['branch']."' and pby='".$_POST['customer']."' order by dat,billno");
                $data = $query11->result_array();
            }
            
            if($_POST['type'] == 'report-ref')
            {
                $query11 = $this->db->query("select billno,date_format(dat,'%d/%m/%Y'), tim, cashier,items, quans,tweight,sub_total,make,old_amt,dis_per,dis_amt,gross_amt,tax_amt,net,pby,cid,cname,mobile,remarks
                                                from estimate
                                                where dat between '".$_POST['from']."' and '".$_POST['to']."'  AND branch='".$_POST['branch']."' and reference='".$_POST['customer']."' order by dat,billno");
                $data = $query11->result_array();
            }
            
            if($_POST['type'] == 'report-remark')
            {
                $query11 = $this->db->query("select billno,date_format(dat,'%d/%m/%Y'), tim, cashier,items, quans,tweight,sub_total,make,old_amt,dis_per,dis_amt,gross_amt,tax_amt,net,pby,cid,cname,mobile,remarks
                                                from estimate
                                                where dat between '".$_POST['from']."' and '".$_POST['to']."'  AND branch='".$_POST['branch']."' and remarks='".$_POST['customer']."' order by dat,billno");
                $data = $query11->result_array();
            }
            
            if($_POST['type'] == 'report-day-wise')
            {
                $query11 = $this->db->query("select date_format(dat,'%d/%m/%Y') as day,min(billno) as min_bill,max(billno) as max_bill,count(billno) as num_bill,sum(net) as total
                                                from estimate
                                                where dat between '".$_POST['from']."' and '".$_POST['from']."'  AND branch='".$_POST['branch']."' group by day order by day");
                $data = $query11->result_array();
            }
            
            if($_POST['type'] == 'report-summary')
            {
                $query11 = $this->db->query("select a.ino,a.iname,sum(quan) as quan ,sum(amount) as amount,category,item_type 
                from sales_items a,master_item b 
                where dat between '".$_POST['from']."' and '".$_POST['from']."' and a.branch='".$_POST['branch']."' and a.ino=b.ino and a.branch=b.branch group by a.ino order by a.ino");
                $data = $query11->result_array();
            }
            
            if($_POST['type'] == 'report-summary')
            {
                $query11 = $this->db->query("select a.ino,a.iname,sum(quan) as quan ,sum(amount) as amount,category,item_type 
                from sales_items a,master_item b 
                where dat between '".$_POST['from']."' and '".$_POST['from']."' and a.branch='".$_POST['branch']."' and a.ino=b.ino and a.branch=b.branch group by a.ino order by a.ino");
                $data = $query11->result_array();
            }
            
            if($_POST['type'] == 'report-item')
            {
                $query11 = $this->db->query("select ino,iname,sum(quan) as quan,sum(amount) as amount from sales_items 
                where dat between '".$_POST['from']."' and '".$_POST['from']."' and branch='".$_POST['branch']."' group by ino order by ino");
                $data = $query11->result_array();
            }
            
            
            if($_POST['type'] == 'report-category')
            {
                $query11 = $this->db->query("select category,sum(quan) as quan,sum(amount) as amount from sales_items a,master_item b 
                where dat between '".$_POST['from']."' and '".$_POST['from']."' and a.branch='".$_POST['branch']."' and a.ino=b.ino and a.branch=b.branch group by category order by category");
                $data = $query11->result_array();
            }
            
            if($_POST['type'] == 'report-item-type')
            {
                $query11 = $this->db->query("select item_type,sum(quan)  as quan,sum(amount) as amount from sales_items a,master_item b 
                where dat between '".$_POST['from']."' and '".$_POST['from']."' and a.branch='".$_POST['branch']."' and a.ino=b.ino and a.branch=b.branch group by item_type order by item_type");
                $data = $query11->result_array();
            }
            
            if($_POST['type'] == 'hold-data')
            {
                $query11 = $this->db->query("select * from sales_hold");
                $data = $query11->result_array();
            }
            
            if(count($data) > 0)
            {
                $responce['status'] = 1;
                $responce['message'] = 'sales report details';
                $responce['data'] = $data;
                return $responce;
            } else {
                $responce['status'] = 0;
                $responce['message'] = 'NO result found!';
                $responce['data'] = '';
                return $responce;
            }
        }
    }
    
    
    function getBill($headers){
        if(empty($_POST['login_user']))
        {
            $responce['status'] = 0;
            $responce['message'] = 'login_user is required';
            $responce['data'] = '';
            return $responce;
        } else if(empty($_POST['billno']))
        {
            $responce['status'] = 0;
            $responce['message'] = 'bill no is required';
            $responce['data'] = '';
            return $responce;
        } else {
            
            $this->db->where('billno', $_POST['billno']);
            $shop =  $this->db->get('estimate_items');
            $data['list'] = $shop->result_array();
            
            
            $this->db->where('billno', $_POST['billno']);
            $shop =  $this->db->get('estimate');
            $userData = $shop->result_array();
            
            $data['estimate'] = empty($userData)?'':$userData[0];
            
            $user = empty($userData)?'':$userData[0]['cname'];
            
            $this->db->where('cname', $user);
            $shop =  $this->db->get('cust');
            
            $data['customer'] = array();
            if($shop->result_array())
                $data['customer'] = $shop->result_array()[0];
            
            
            if(count($data) > 0)
            {
                $responce['status'] = 1;
                $responce['message'] = 'Bill details';
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
    
    function getBillFormate($headers){
        if(empty($_POST['login_user']))
        {
            $responce['status'] = 0;
            $responce['message'] = 'login_user is required';
            $responce['data'] = '';
            return $responce;
        } else {
            
            
            $this->db->where('branch', $_POST['branch']);
            $branch =  $this->db->get('setting_branch');
            $branchData = $branch->result_array();
            
            
            if(count($branchData) > 0)
            {
                $responce['status'] = 1;
                $responce['message'] = 'Bill Fomate';
                $responce['data'] = empty($branchData)?[]:$branchData[0];;
                return $responce;
            } else {
                $responce['status'] = 0;
                $responce['message'] = 'Samething is wrong';
                $responce['data'] = '';
                return $responce;
            }
            
        }
    }
    
    function getViewBill($headers)
    {
        if((!$_POST['login_user']) && (($_POST['login_user'] == null)))
        {
            $responce['status'] = 0;
            $responce['message'] = 'login_user is required';
            $responce['data'] = '';
            return $responce;
        } else {
            $this->db->where('billno', $_POST['viewBill']);
            $shop =  $this->db->get('estimate');
            $userData = $shop->result_array();
            
            if($userData)
            {
            
            $data = $userData;
            
            $this->db->join('master_item', 'sales_items.iname = master_item.iname');
            $this->db->where('billno', $_POST['viewBill']);
            $this->db->select('
                sales_items.ino, 
                sales_items.sno, 
                sales_items.iname,
                sales_items.iname as name,
                sales_items.lno as lot_no ,
                master_item.price,
                master_item.price as rate,
                sales_items.make ,
                sales_items.amount ,
                sales_items.weight ,
                sales_items.quan ,
                sales_items.quan as qty,
                sales_items.branch ,
                sales_items.tot as total,
                sales_items.net_weight ,
                sales_items.ino as item_code ,
                sales_items.waste as westage ,
                master_item.making_charge,
                master_item.hsn_code,
                master_item.price_type,
                ,');
            $shop =  $this->db->get('estimate_items');
            $data['selectedItem'] = $shop->result_array();
            
            
            
            
            $user = $userData?$userData[0]['cname']:'';
            
            $this->db->where('cname', $user);
            $shop =  $this->db->get('cust');
            
            $data['customer'] = array();
            if($shop->result_array())
                $data['customer'] = $shop->result_array()[0];
                
            $this->db->where('billno', $_POST['viewBill']);
            $oldItem =  $this->db->get('sales_old_items');
            
            $data['selectedOldItem'] = array();
            if($oldItem->result_array())
                $data['selectedOldItem'] = $oldItem->result_array();
            
            if(count($data) > 0)
            {
                $responce['status'] = 1;
                $responce['message'] = 'Bill details';
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
                $responce['message'] = 'Sorry, No Records Were Found';
                $responce['data'] = '';
                return $responce;
            }
        }
        
    }
    
    
    function getRetriveBill($headers)
    {
        if((!$_POST['login_user']) && (($_POST['login_user'] == null)))
        {
            $responce['status'] = 0;
            $responce['message'] = 'login_user is required';
            $responce['data'] = '';
            return $responce;
        } else {
            
            $this->db->where('billno', $_POST['viewBill']);
            $shop =  $this->db->get('estimate_hold');
            $userData = $shop->result_array();
            
            $data = $userData;
            
            $this->db->join('master_item', 'sales_hold_items.iname = master_item.iname');
            $this->db->where('billno', $_POST['viewBill']);
            $this->db->select('
            sales_hold_items.ino, 
            sales_hold_items.sno, 
            sales_hold_items.iname,
            sales_hold_items.iname as name,
            sales_hold_items.sno as lot_no ,
            master_item.price,
            master_item.price as rate,
            sales_hold_items.make ,
            sales_hold_items.amount ,
            sales_hold_items.weight ,
            sales_hold_items.quan ,
            sales_hold_items.quan as qty,
            sales_hold_items.branch ,
            sales_hold_items.tot as total,
            sales_hold_items.net_weight ,
            sales_hold_items.ino as item_code ,
            sales_hold_items.waste as westage ,
            master_item.making_charge,
            master_item.hsn_code,
            master_item.price_type,
            ,');
            $shop =  $this->db->get('sales_hold_items');
            $data['selectedItem'] = $shop->result_array();
            
            
            
            
            $user = $userData[0]['cname'];
            
            $this->db->where('cname', $user);
            $shop =  $this->db->get('cust');
            
            $data['customer'] = array();
            if($shop->result_array())
                $data['customer'] = $shop->result_array()[0];
                
            
            $this->db->delete('estimate_hold', array('billno' => $_POST['viewBill']));
            $this->db->delete('sales_hold_items', array('billno' => $_POST['viewBill']));
            $this->db->delete('sales_hold_old', array('billno' => $_POST['viewBill']));
            
            if(count($data) > 0)
            {
                $responce['status'] = 1;
                $responce['message'] = 'Retrive Bill details';
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
    
    
    function getStocks($headers){
        if((!$_POST['login_user']) && (($_POST['login_user'] == null)))
        {
            $responce['status'] = 0;
            $responce['message'] = 'login_user is required';
            $responce['data'] = '';
            return $responce;
        } else {
//             if (isset($_POST['search_key']) && $_POST['search_key'] != '' && $_POST['search_key'] != null) {
// 				$search_key = $_POST['search_key'];
// 				$like = "(master_sales.item like '%$search_key%' or master_sales.sales_code like '%$search_key%' or master_customer.cname like '%$search_key%')"; 
// 				$this->db->where($like);
// 			}
			
//             $this->db->order_by("sales_no", "desc");
//             $this->db->join('master_customer', 'master_sales.cid= master_customer.cid');
            // $this->db-select('master_branch.branch as branchname');
            $this->db->group_by('stock.lot_no');
            $this->db->where('stock.branch',$_POST['branch']);
            $this->db->join('master_item', 'stock.item_name = master_item.iname');
            $this->db->select('
            stock.item_no, 
            stock.lot_no, 
            stock.item_name,
            stock.item_name ,
            stock.lot_no ,
            master_item.price,
            stock.quan ,
            stock.branch ,
            stock.net_weight ,
            stock.gross_weight ,
            stock.less_weight ,
            stock.wastage ,
            stock.making as making_charge,
            master_item.hsn_code,
            master_item.price_type,
            master_item.item_type,
            ,');
            $shop =  $this->db->get('stock');
            $data = $shop->result_array();
            

            if(count($data) > 0)
            {
                $responce['status'] = 1;
                $responce['message'] = 'Total sales stock details';
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
    
    function getTax($headers){
        if((!$_POST['login_user']) && (($_POST['login_user'] == null)))
        {
            $responce['status'] = 0;
            $responce['message'] = 'login_user is required';
            $responce['data'] = '';
            return $responce;
        } else {
            $last =  $this->db->get('setting_tax');
            $data = $last->result_array();
            
            if($data)
            {
                $responce['status'] = 1;
                $responce['message'] = 'tax setting';
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
    
    function getLastSales($headers){
        if((!$_POST['login_user']) && (($_POST['login_user'] == null)))
        {
            $responce['status'] = 0;
            $responce['message'] = 'login_user is required';
            $responce['data'] = '';
            return $responce;
        } else if((!$_POST['branch']) && (($_POST['branch'] == null)))
        {
            $responce['status'] = 0;
            $responce['message'] = 'branch is required';
            $responce['data'] = '';
            return $responce;
        } else {
            
            
            $this->db->order_by('sno', 'DESC');
            $this->db->limit(1);
            $last =  $this->db->get('estimate');
            $last_code = $last->result_array();
                
            $data['ip'] = $_SERVER['REMOTE_ADDR'];
            $data['billno'] = $last_code?($last_code[0]['billno'] + 1):1;
            $data['lot_no'] = $last_code?($last_code[0]['sno'] + 1):1;
            
            $this->db->order_by('sno', 'DESC');
            // $this->db->where('company',$_POST['branch']);
            $shop =  $this->db->get('estimate_items');
            $data['item_no'] = count($shop->result_array()) + 1;
            

            if(count($data) > 0)
            {
                $responce['status'] = 1;
                $responce['message'] = 'Last sales id';
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
    
    function saveSales($headers){
        if((!$_POST['login_user']) && (($_POST['login_user'] == null)))
        {
            $responce['status'] = 0;
            $responce['message'] = 'login_user is required';
            $responce['data'] = '';
            return $responce;
        } else {
           date_default_timezone_set('Asia/Kolkata');
            // print_r($_POST);
            // die();
            
            $billno = (isset($_POST['billno']))?$_POST['billno']:'.';
          
            $lot_no_stock = 0;
            
            $stock = json_decode($_POST['selectedItem'], true);
            
            if(isset($_POST['selectedOldItem']) && ($_POST['selectedOldItem'] != 'null'))
            {
                
                $this->db->delete('sales_old_items', array('billno' => $_POST['billno'])); 
                
                $stockOld = json_decode($_POST['selectedOldItem'], true);
                // print_r($stockOld);
                
                $this->db->order_by('sno', 'DESC');
                $old =  $this->db->get('sales_old_items');
                $oldS = $old->result_array();
                $old_no = $oldS?($oldS[0]['sno'] + 1):1;
                
                $tquan = 0;
                $tweight = 0;
                $tamt = 0;
                
                if($stockOld)
                {
                    foreach($stockOld as $old)
                    {
                        $tquan = $tquan + $old['qtyOld'];
                        $tweight = $tweight + $old['weightOld'];
                        $tamt =  $tamt + $old['amountOld'];
                    }
                    
                    foreach($stockOld as $old)
                    {
                         $insertDataOld = array(
                            'sno'   => $old_no,
                            'billno'   => (isset($_POST['billno']))?$_POST['billno']:'.',
                            'dat'       => date("Y/m/d h:i:sa"),
                            'iname'   => (isset($old['nameOld']))?$old['nameOld']:'.',
                            'itype'   => (isset($old['type']))?$old['type']:'.',
                            'qual'   => (isset($old['purity']))?$old['purity']:'.',
                            'quan'   => (isset($old['qtyOld']))?$old['qtyOld']:'.',
                            'weight'   => (isset($old['weightOld']))?$old['weightOld']:'.',
                            'rate'   => (isset($old['rateOld']))?$old['rateOld']:'.',
                            'amount'   => (isset($old['amountOld']))?$old['amountOld']:'.',
                            'tquan'   => (isset($tquan))?$tquan:'.',
                            'tweight'   => (isset($tweight))?$tweight:'.',
                            'tamount'   => (isset($tamt))?$tamt:'.',
                            'branch'   => (isset($_POST['branch']))?$_POST['branch']:'.',
                        );
                        
                        $this->db->insert('sales_old_items', $insertDataOld);  
                        $old_no++;
                    }
                }
                
                
            }
            // die();
            
            $this->db->order_by('branch', $_POST['branch']);
            $shop =  $this->db->get('setting_branch');
            $branch = $shop->result_array();
            
          
            $branch_state = $branch?$branch[0]['branch']:1;
            
            
            
            
            
            $this->db->order_by('serial', 'DESC');
            $shop =  $this->db->get('estimate_items');
            $serial = $shop->result_array();
            $lot_no_stock = $serial?($serial[0]['serial'] + 1):1;
            
            // print_r($lot_no_stock);
            // die();
            
            $this->db->order_by('sno', 'DESC');
            $this->db->limit(1);
            $last =  $this->db->get('estimate');
            $last_code = $last->result_array();
            $lot_no = $last_code?($last_code[0]['sno'] + 1):1;
            
            
            if($_POST['type'] == 'hold' )
            {
                $this->db->order_by('serial', 'DESC');
                $shop =  $this->db->get('sales_hold_items');
                $last_code11 = $shop->result_array();
                $lot_no_stock = $last_code11?($last_code11[0]['sno'] + 1):1;
                
                
                $this->db->order_by('sno', 'DESC');
                $this->db->limit(1);
                $last =  $this->db->get('estimate_hold');
                $last_code = $last->result_array();
                $lot_no = $last_code?($last_code[0]['sno'] + 1):1;
            }
            
            if($_POST['type'] == 'alter' )
            {
                $this->db->where('billno', $_POST['billno']);
                $item =  $this->db->get('estimate_items');
                $itemData = $item->result_array();
                
                foreach($itemData as $data)
                {
                    $this->db->where('lot_no', $data['lno']);
                    $stock12 =  $this->db->get('stock');
                    $stockData12 = $stock12->result_array();
                    
                    $stockQty = $stockData12?$stockData12[0]['quan']:0;
                    
                    $quan = $stockQty + $data['quan'];
                    
                    $updateData = array(
                        'quan' => $quan?$quan:0,
                        'last_modified_at' => date("Y/m/d h:i:sa"),
                        );
                    $this->db->where('lot_no', $data['lno']);
                    $this->db->update('stock', $updateData);
                }
                
                $this->db->delete('estimate_items', array('billno' => $_POST['billno'])); 
            }
            
            foreach($stock as $data)
            {
                $this->db->where('lot_no', $data['lot_no']);
                $stock11 =  $this->db->get('stock');
                $stockData11 = $stock11->result_array();
                
                $stockQty = $stockData11?$stockData11[0]['quan']:0;
                
                $quan11 = $stockQty - $data['qty'];
                
                $updateData11 = array(
                    'quan' => $quan11?$quan11:0,
                    'last_modified_at' => date("Y/m/d h:i:sa"),
                    );
                $this->db->where('lot_no', $data['lot_no']);
                $this->db->update('stock', $updateData11);
                    
                $insertData = array(
                    'serial'            => $lot_no_stock,
                    'sno'               => $lot_no_stock,
                    'billno'            => $lot_no?$lot_no:'.',
                    'dat'               => date("Y/m/d h:i:sa"),
                    'lno'               => $data['lot_no'],
                    'iname'             => $data['name']?$data['name']:'.',
                    'quan'              => $data['qty']?$data['qty']:'.',
                    'weight'            => $data['weight']?$data['weight']:'.',
                    'waste'             => $data['westage']?$data['westage']:'.',
                    'net_weight'        => $data['net_weight']?$data['net_weight']:'.',
                    'price'             => $data['rate']?$data['rate']:'.',
                    'amount'            => $data['amount']?$data['amount']:'.',
                    'make'              => $data['making_charges']?$data['making_charges']:'.',
                    'tot'               => $data['total']?$data['total']:'.',
                    'hsn'               => $data['hsn_code']?$data['hsn_code']:'.',
                    'cid'               => (isset($_POST['customer_id']))?$_POST['customer_id']:'.',
                    'cname'               =>(isset($_POST['customer_name']))?$_POST['customer_name']:'.',
                    'tax_type'               => ($branch_state == $_POST['branch'])?'Local':'IGST',
                    'ino'               => $lot_no,
                    'sid'               => '.',
                    'sname'               => '.',
                    'cid'               => $_POST['customer_id']?$_POST['customer_id']:'.',
                    'ptype'             => (isset($_POST['pby']))?$_POST['pby']:'.',
                    'branch'            => (isset($_POST['branch']))?$_POST['branch']:'.',
                    'ctype'               => (isset($_POST['ctype']))?$_POST['ctype']:'.',
                    'tax_no'               => (isset($_POST['ctaxno']))?$_POST['ctaxno']:'.',
                );
                
                if($_POST['type'] == 'hold' )
                {
                    $data =  $this->db->insert('sales_hold_items', $insertData);
                } else if($_POST['type'] == 'alter' )
                {
                    $insertData['billno'] = $_POST['billno'];
                    
                    $this->db->insert('estimate_items', $insertData);
                } else {
                    
                    $this->db->insert('estimate_items', $insertData);
                }
                $lot_no_stock++;
            }
            
            
            
                
                
            
            
            $insertData = array(
                'sno'               =>  $lot_no?$lot_no:'.',
                'billno'            => $lot_no?$lot_no:'.',
                'dat'               => date("Y/m/d h:i:sa"),
                'tim'               => date("h:i:s"),
                'cashier'           => $_POST['login_user']?$_POST['login_user']:'.',
                'system_ip'         => $_POST['ipAddress']?$_POST['ipAddress']:'.',
                
                'sub_total'         => (isset($_POST['sub_total']))?$_POST['sub_total']:'.',
                'gross_amt'         => (isset($_POST['gross_total']))?$_POST['gross_total']:'.',
                'net'               => (isset($_POST['net_total']))?$_POST['net_total']:'.',
                'round_amt'               => (isset($_POST['round_amt']))?$_POST['round_amt']:'.',
                'paid'               => (isset($_POST['tender_total']))?$_POST['tender_total']:'.',
                
                'taxp'              => (isset($_POST['tax_per']))?$_POST['tax_per']:'.',
                'tax_amt'           => (isset($_POST['tax_amt']))?$_POST['tax_amt']:'.',
                'dis_per'           => (isset($_POST['descount']))?$_POST['descount']:'.',
                'dis_amt'           => (isset($_POST['desamt']))?$_POST['desamt']:'.',
                
                'items'             => (isset($_POST['totalItems']))?$_POST['totalItems']:'.',
                
                'make'              => (isset($_POST['making_charges']))?$_POST['making_charges']:'.',
                
                'quans'             => (isset($_POST['totalQty']))?$_POST['totalQty']:'.',
                'pby'               => (isset($_POST['pby']))?$_POST['pby']:'.',
                'branch'            => (isset($_POST['branch']))?$_POST['branch']:'.',
                'cid'               => (isset($_POST['customer_id']))?$_POST['customer_id']:'.',
                'paid'              => (isset($_POST['afterDes']))?$_POST['afterDes']:'.',
                'bal'               => (isset($_POST['bal']))?$_POST['bal']:'.',
                'remarks'           => (isset($_POST['remark']))?$_POST['remark']:'.',
                'reference'         => (isset($_POST['reference']))?$_POST['reference']:'.',
                'cname'             => (isset($_POST['customer_name']))?$_POST['customer_name']:'.',
                'mobile'             => (isset($_POST['mobile']))?$_POST['mobile']:'.',
                'ctype'               => (isset($_POST['ctype']))?$_POST['ctype']:'.',
                'tax_no'               => (isset($_POST['ctaxno']))?$_POST['ctaxno']:'.',
                
                'add_amt'           => (isset($_POST['othersAdd']))?$_POST['othersAdd']:'.',
                'old_amt'           => (isset($_POST['amountOldTotal']))?$_POST['amountOldTotal']:'.',
                
                'tweight'           => (isset($_POST['totalGrossWeight']))?$_POST['totalGrossWeight']:'.',
                'acode'             => (isset($_POST['acode']))?$_POST['acode']:'.',
                'aname'             => (isset($_POST['reference']))?$_POST['reference']:'.',
                
                'cash'              => (isset($_POST['cash']))?$_POST['cash']:'.',
                'card'              => (isset($_POST['card']))?$_POST['card']:'.',
                'others'            => (isset($_POST['others']))?$_POST['others']:'.',
                'pay_remarks'       => (isset($_POST['pay_remarks']))?$_POST['pay_remarks']:'.',
                
                'tax_type'          => ($branch_state == $_POST['branch'])?'Local':'IGST'
            );
            
            if($_POST['type'] == 'hold' )
            {
                $data =  $this->db->insert('estimate_hold', $insertData);
            } 
            else if($_POST['type'] == 'alter' )
            {
                unset($insertData["sno"]);
                unset($insertData["billno"]);
                $billno = $_POST['billno'];
                
                $this->db->where('billno', $_POST['billno']);
                $data =  $this->db->update('estimate', $insertData);
            } else {
                
                $billno = $lot_no;
                
                $data =  $this->db->insert('estimate', $insertData);
            }
            
            $msg = 'Sales Submitted';
            
            if($_POST['type'] == 'hold' )
            {
                $msg = 'Hold Successfully';
            } else if($_POST['type'] == 'alter' )
            {
                $msg = 'Alter Sales Updated';
            } else {
                $msg = 'Sales Submitted';
            }
            
            

            if($data)
            {
                $responce['status'] = 1;
                $responce['message'] = $msg;
                $responce['data'] = $billno;
                return $responce;
            } else {
                $responce['status'] = 0;
                $responce['message'] = 'Samething is wrong';
                $responce['data'] = '';
                return $responce;
            }
            
        }
    }
    
    
    function deleteSales($headers){
        if((!$_POST['login_user']) && (($_POST['login_user'] == null)))
        {
            $responce['status'] = 0;
            $responce['message'] = 'login_user is required';
            $responce['data'] = '';
            return $responce;
        } else if((!$_POST['billno']) && (($_POST['billno'] == null)))
        {
            $responce['status'] = 0;
            $responce['message'] = 'Bill number required';
            $responce['data'] = '';
            return $responce;
        } else {
            
            date_default_timezone_set('Asia/Kolkata');
            
            $this->db->where('sno', $_POST['billno']);
            $this->db->limit(1);
            $bill =  $this->db->get('estimate');
            $billData = $bill->result_array();
            
            if($billData)
            {
                
                $this->db->where('billno', $_POST['billno']);
                $item =  $this->db->get('estimate_items');
                $itemData = $item->result_array();
                
                foreach($itemData as $data)
                {
                    $this->db->where('lot_no', $data['lno']);
                    $stock =  $this->db->get('stock');
                    $stockData = $stock->result_array();
                    
                    $stockQty = $stockData?$stockData[0]['quan']:0;
                    
                    $quan = $stockQty + $data['quan'];
                    
                    $updateData = array(
                        'quan' => $quan?$quan:0,
                        'last_modified_at' => date("Y/m/d h:i:sa"),
                        );
                    $this->db->where('lot_no', $data['lno']);
                    $this->db->update('stock', $updateData);
                }
                
                // print_r($itemData);die();
            
                $data = $this->db->delete('estimate', array('billno' => $_POST['billno']));
                $this->db->delete('estimate_items', array('billno' => $_POST['billno']));
                // $this->db->delete('estimate_old_items', array('billno' => $_POST['billno']));
                
                
                if($data)
                {
                    $responce['status'] = 1;
                    $responce['message'] = 'Deleted Successfully';
                    $responce['data'] = '';
                    return $responce;
                } else {
                    $responce['status'] = 0;
                    $responce['message'] = 'Samething is wrong';
                    $responce['data'] = '';
                    return $responce;
                }
            
            } else {
                $responce['status'] = 0;
                $responce['message'] = 'Sorry, No records Were Found';
                $responce['data'] = '';
                return $responce;
            }
            
        }
        
    }
}