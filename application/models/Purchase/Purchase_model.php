<?php

class Purchase_model extends CI_Model
{
    public function module($headers){
        if($headers['Module'] == 'get-Purchase')
        {
            return $this->getPurchase($headers);
        } else if($headers['Module'] == 'get-drop-item')
        {
            return $this->getPurchaseDrop($headers);
        } else if($headers['Module'] == 'get-Purchase-return')
        {
            return $this->getPurchaseReturn($headers);
        } else if($headers['Module'] == 'get-stck')
        {
            return $this->getStocks($headers);
        } else if($headers['Module'] == 'get-bill-deatils')
        {
            return $this->getBill($headers);
        }else if($headers['Module'] == 'get-view-bill')
        {
            return $this->viewBill($headers);
        } else if($headers['Module'] == 'save-Purchase')
        {
            return $this->savePurchase($headers);
        } else if($headers['Module'] == 'save-Purchase-return')
        {
            return $this->savePurchaseReturn($headers);
        } else if($headers['Module'] == 'get-last-Purchase')
        {
            return $this->getLastSales($headers);
        }  else if($headers['Module'] == 'delete-Purchase')
        {
            return $this->deletePurchases($headers);
        } else if($headers['Module'] == 'get-last-Purchase-return')
        {
            return $this->getLastSalesReturn($headers);
        }  else {
            $responce['status'] = 0;
            $responce['message'] = 'Something is wrong';
            $responce['data'] = '';
            return $responce;
        }
        
    }
    
    function getPurchase($headers){
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
            // $shop =  $this->db->get('purchase');
            // $data = $shop->result_array();
            
            if($_POST['type'] == 'purchase-report')
            {
                $query11 = $this->db->query("SELECT grn,date_format(dat,'%d/%m/%Y') as bdate,billno,date_format(bdate,'%d/%m/%Y') as ddate,pby,cname,sub,disp,disamt,taxp,taxamt,tcsp,tcsamt,gt,addamt,round_amt,net 
                from purchase 
                where bdate between '".$_POST['from']."' and '".$_POST['to']."'  AND branch='".$_POST['branch']."' order by bdate,grn");
                $data = $query11->result_array();
            }
            
            if($_POST['type'] == 'purchase-report-supplier')
            {
                $query11 = $this->db->query("SELECT grn,date_format(dat,'%d/%m/%Y') as bdate,billno,date_format(bdate,'%d/%m/%Y') as ddate,pby,cname,sub,disp,disamt,taxp,taxamt,tcsp,tcsamt,gt,addamt,round_amt,net 
                from purchase 
                where bdate between '".$_POST['from']."' and '".$_POST['to']."'  AND branch='".$_POST['branch']."' and cname='".$_POST['cname']."' order by bdate,grn");
                $data = $query11->result_array();
            }
            
            
            if($_POST['type'] == 'purchase-summary')
            {
                $query11 = $this->db->query("SELECT DISTINCT a.billno,date_format(a.bdate,'%d/%m/%Y') as bdate,ino,iname,price,weight,amount 
                from purchase a,purchase_items b 
                where a.bdate BETWEEN '".$_POST['from']."' and '".$_POST['to']."' and a.branch='".$_POST['branch']."' and a.grn=b.grn and a.branch=b.branch order by a.bdate,a.grn");
                $data = $query11->result_array();
            }
            
            if($_POST['type'] == 'purchase-item-summary')
            {
                $query11 = $this->db->query("select ino,iname,sum(quan) as quan, price ,sum(weight) as weight,sum(amount) as amount 
                from purchase_items WHERE bdate BETWEEN '".$_POST['from']."' and '".$_POST['to']."' AND branch='".$_POST['branch']."' group by ino ORDER BY SUM(amount) desc");
                $data = $query11->result_array();
            }
            
            if($_POST['type'] == 'purchase-cat-summary')
            {
                $query11 = $this->db->query("select category,sum(quan) as quan,sum(amount) as amount,sum(weight) as weight
                from purchase_items a,master_item b 
                where bdate BETWEEN '".$_POST['from']."' and '".$_POST['to']."' and a.branch='".$_POST['branch']."' and a.branch=b.branch and a.ino=b.ino group by category order by SUM(amount) desc");
                $data = $query11->result_array();
            }
            
            if($_POST['type'] == 'purchase-sup-summary')
            {
                $query11 = $this->db->query("select cname,sum(quans) as quan,sum(net) as amount,sum(weights)  as weight
                from purchase 
                where bdate BETWEEN '".$_POST['from']."' and '".$_POST['to']."' and branch='".$_POST['branch']."' group by cname order by SUM(net) desc");
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
    
    function getPurchaseDrop($headers)
    {
        if((!$_POST['login_user']) && (($_POST['login_user'] == null)))
        {
            $responce['status'] = 0;
            $responce['message'] = 'login_user is required';
            $responce['data'] = '';
            return $responce;
        } else {
            if($_POST['type'] == 'item-supplier')
            {
                $query11 = $this->db->query("SELECT grn,date_format(dat,'%d/%m/%Y') as bdate,billno,date_format(bdate,'%d/%m/%Y') as ddate,pby,cname,sub,disp,disamt,taxp,taxamt,tcsp,tcsamt,gt,addamt,round_amt,net 
                from purchase 
                where bdate between '".$_POST['from']."' and '".$_POST['to']."'  AND branch='".$_POST['branch']."' order by bdate,grn");
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
    
    function getPurchaseReturn($headers){
        if((!$_POST['login_user']) && (($_POST['login_user'] == null)))
        {
            $responce['status'] = 0;
            $responce['message'] = 'login_user is required';
            $responce['data'] = '';
            return $responce;
        } else {
            $shop =  $this->db->get('purchase_return');
            $data = $shop->result_array();
            
            if(count($data) > 0)
            {
                $responce['status'] = 1;
                $responce['message'] = 'Total purchase_return details';
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
    
    
    
    function getBill($headers){
        if((!$_POST['login_user']) && (($_POST['login_user'] == null)))
        {
            $responce['status'] = 0;
            $responce['message'] = 'login_user is required';
            $responce['data'] = '';
            return $responce;
        } else {
            
            $this->db->where('billno', $_POST['billno']);
            $shop =  $this->db->get('sales_items');
            $data['list'] = $shop->result_array();
            
            
            $this->db->where('billno', $_POST['billno']);
            $shop =  $this->db->get('sales');
            $userData = $shop->result_array();
            
            $data['sales'] = $userData[0];
            
            $user = $userData[0]['cname'];
            
            $this->db->where('cname', $user);
            $shop =  $this->db->get('master_customer');
            
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
    
    
    function viewBill($headers){
        if((!$_POST['login_user']) && (($_POST['login_user'] == null)))
        {
            $responce['status'] = 0;
            $responce['message'] = 'login_user is required';
            $responce['data'] = '';
            return $responce;
        } else {
            
            // print_r($_POST);die();
            $this->db->where('billno', $_POST['viewBill']);
            $shop =  $this->db->get('purchase');
            $userData = $shop->result_array();
            
            if($userData)
            {
                
            
            $this->db->where('billno', $_POST['viewBill']);
            $shop =  $this->db->get('purchase_items');
            $data['items'] = $shop->result_array();
            
            
            $this->db->where('billno', $_POST['viewBill']);
            $shop =  $this->db->get('purchase');
            $userData = $shop->result_array();
            
            $data['purchase'] = $userData?$userData[0]:'';
            
                
            // print_r($data);die();
            
            if(count($data) > 0)
            {
                $responce['status'] = 1;
                $responce['message'] = 'Purchase Bill details';
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
            $this->db->join('master_item', 'stock_entry.item_name = master_item.item_name');
            $this->db->select('
            stock_entry.item_no, 
            stock_entry.entry_no, 
            stock_entry.item_name,
            stock_entry.item_name ,
            stock_entry.lot_no ,
            master_item.price,
            stock_entry.quan ,
            stock_entry.branch ,
            stock_entry.net_weight ,
            master_item.wastage ,
            master_item.making_charge,
            master_item.hsn_code,
            master_item.price_type,
            ,');
            $shop =  $this->db->get('stock_entry');
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
            $last =  $this->db->get('purchase');
            $last_code = $last->result_array();
                
            $data['ip'] = $_SERVER['REMOTE_ADDR'];
            $data['billno'] = $last_code?($last_code[0]['billno'] + 1):1;
            $data['grn'] = $last_code?($last_code[0]['sno'] + 1):1;
            
            $this->db->order_by('sno', 'DESC');
            // $this->db->where('company',$_POST['branch']);
            $shop =  $this->db->get('purchase_items');
            $data['purchase_no'] = count($shop->result_array()) + 1;
            

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
    
    
    function getLastSalesReturn($headers){
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
            $last =  $this->db->get('purchase_return_items');
            $last_code = $last->result_array();
                
            $data['ip'] = $_SERVER['REMOTE_ADDR'];
            $data['billno'] = $last_code?($last_code[0]['billno'] + 1):1;
            $data['grn'] = $last_code?($last_code[0]['sno'] + 1):1;
            
            $this->db->order_by('sno', 'DESC');
            // $this->db->where('company',$_POST['branch']);
            $shop =  $this->db->get('purchase_items');
            $data['purchase_no'] = count($shop->result_array()) + 1;
            

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
    
    function savePurchase($headers){
        if((!$_POST['login_user']) && (($_POST['login_user'] == null)))
        {
            $responce['status'] = 0;
            $responce['message'] = 'login_user is required';
            $responce['data'] = '';
            return $responce;
        } else if((!$_POST['suppliername']) && (($_POST['suppliername'] == null)))
        {
            $responce['status'] = 0;
            $responce['message'] = 'Supplier Name is required';
            $responce['data'] = '';
            return $responce;
        } else {
           
        //   print_r($_POST);
        //   die();
            
            $stock = json_decode($_POST['selectedItem'], true);
            // print_r($stock);
            // die();
            
            if($_POST['type'] == 'alter')
            {
                $this->db->delete('purchase_items', array('grn' => $_POST['grn_number'])); 
            }
            
            $this->db->where('branch', $_POST['branch']);
            $branchQuery =  $this->db->get('setting_branch');
            $branch = $branchQuery->result_array();
            
            $this->db->where('cname', $_POST['suppliername']);
            $vendorQuery =  $this->db->get('vendor');
            $vendor = $vendorQuery->result_array();
            
            // print_r($branch);
            // die();
          
            $branch_state = $branch?$branch[0]['state_name']:1;
            $vendor_state = $vendor?$vendor[0]['state']:1;
            
            
            
            
            $this->db->order_by('sno', 'DESC');
            $shop =  $this->db->get('purchase_items');
            $lot_no_purchase = count($shop->result_array()) + 1;
            
            $this->db->order_by('sno', 'DESC');
            $this->db->limit(1);
            $last =  $this->db->get('purchase');
            $last_code = $last->result_array();
            $lot_no = $last_code?($last_code[0]['sno'] + 1):1;
            
            foreach($stock as $data)
            {
                $insertData = array(
                    'sno'               => $lot_no_purchase,
                    'grn'               => $lot_no,
                    'billno'            => $_POST['billno'],
                    'bdate'             => $_POST['todaydate'],
                    'cname'             => isset($_POST['suppliername'])?$_POST['suppliername']:'.',
                    'tax_no'             => isset($_POST['tax_no'])?$_POST['tax_no']:'.',
                    'ino'               => $data['item_code'],
                    'iname'             => $data['name'],
                    'price'             => $data['rate'],
                    'quan'              => $data['qty'],
                    'weight'            => $data['weight'],
                    'amount'            => $data['amount'],
                    'hsn'               => $data['hsn_code'],
                    'tax_type'          => ($branch_state == $vendor_state)?'Local':'IGST',
                    'branch'            => $_POST['branch'],
                    
                );
                $this->db->insert('purchase_items', $insertData);
                $lot_no_purchase++;
            }
            
            
                
                
            
            
            $insertData = array(
                'sno'               =>  $lot_no,
                'grn'               =>  $lot_no,
                'dat'               => date("Y/m/d h:i:sa"),
                'cname'             => isset($_POST['suppliername'])?$_POST['suppliername']:'.',
                'tax_no'             => isset($_POST['tax_no'])?$_POST['tax_no']:'.',
                'billno'            => isset($_POST['billno'])?$_POST['billno']:'.',
                'bdate'             => isset($_POST['todaydate'])?$_POST['todaydate']:'.',
                'pby'               => isset($_POST['payMod'])?$_POST['payMod']:'.',
                'ddate'             => isset($_POST['due_date'])?$_POST['due_date']:'.',
                'sub'               => isset($_POST['sub_total'])?$_POST['sub_total']:'.',
                'disp'              => isset($_POST['dis_per'])?$_POST['dis_per']:'.',
                'disamt'            => isset($_POST['dis_amt'])?$_POST['dis_amt']:'.',
                'taxp'              => isset($_POST['gst_per'])?$_POST['gst_per']:'.',
                'taxamt'            => isset($_POST['gst_amt'])?$_POST['gst_amt']:'.',
                'tcsp'              => isset($_POST['tcs_per'])?$_POST['tcs_per']:'.',
                'tcsamt'            => isset($_POST['tcs_amt'])?$_POST['tcs_amt']:'.',
                'gt'                => isset($_POST['gst_amt'])?$_POST['grant_total']:'.',
                'addamt'            => isset($_POST['add_amount'])?$_POST['add_amount']:'.',
                'round_amt'         => isset($_POST['round'])?$_POST['round']:'.',
                'net'               => isset($_POST['total_weight'])?$_POST['total_weight']:'.',
                'items'             => isset($_POST['items'])?$_POST['items']:'.',
                'quans'             => isset($_POST['quan'])?$_POST['quan']:'.',
                'weights'           => isset($_POST['tweight'])?$_POST['tweight']:'.',
                'tax_type'          => ($branch_state == $vendor_state)?'Local':'IGST',
                'user'              => isset($_POST['login_user'])?$_POST['login_user']:'.',
                'last'              => date("Y/m/d h:i:sa"),
                'branch'            => isset($_POST['branch'])?$_POST['branch']:'.',
            );
            
            if($_POST['type'] == 'alter')
            {
                unset($insertData["sno"]);
                unset($insertData["grn"]);
                
                $this->db->where('grn', $_POST['grn_number']);
                
                $data =  $this->db->update('purchase', $insertData);
            } else {
                 $data =  $this->db->insert('purchase', $insertData);
            }
            
           
            

            if($data)
            {
                $responce['status'] = 1;
                $responce['message'] = 'purchase Submitted';
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
    
    
    function savePurchaseReturn($headers){
        if((!$_POST['login_user']) && (($_POST['login_user'] == null)))
        {
            $responce['status'] = 0;
            $responce['message'] = 'login_user is required';
            $responce['data'] = '';
            return $responce;
        } else {
           
        //   print_r($_POST);
        //   die();
            
            $stock = json_decode($_POST['selectedItem'], true);
            // print_r($stock);
            // die();
            
            $this->db->order_by('sno', 'DESC');
            $shop =  $this->db->get('purchase_return_items');
            $lot_no_purchase = count($shop->result_array()) + 1;
            
            $this->db->order_by('sno', 'DESC');
            $this->db->limit(1);
            $last =  $this->db->get('purchase_return');
            $last_code = $last->result_array();
            $lot_no = $last_code?($last_code[0]['sno'] + 1):1;
            
            foreach($stock as $data)
            {
                $insertData = array(
                    'sno'               => $lot_no_purchase,
                    'grn'               => $_POST['grn_number'],
                    'billno'            => $_POST['billno'],
                    'pdate'             => $_POST['todaydate'],
                    'ino'               => $data['item_code'],
                    'iname'             => $data['name'],
                    'price'             => $data['rate'],
                    'quan'              => $data['qty'],
                    'weight'            => $data['weight'],
                    'amount'            => $data['amount'],
                    'hsn'               => $data['hsn_code'],
                    'branch'            => $_POST['branch'],
                    
                );
                $this->db->insert('purchase_return_items', $insertData);
                $lot_no_purchase++;
            }
            
            
                
                
            
            
            $insertData = array(
                'sno'               =>  $lot_no,
                'grn'               => $_POST['grn_number'],
                'dat'               => date("Y/m/d h:i:sa"),
                'cname'             => $_POST['suppliername'],
                'billno'            => $_POST['billno'],
                'bdate'             => $_POST['todaydate'],
                'pby'               => $_POST['login_user'],
                'ddate'             => $_POST['due_date'],
                'sub'               => $_POST['sub_total'],
                'disp'              => $_POST['dis_per'],
                'disamt'            => $_POST['dis_amt'],
                'taxp'              => $_POST['gst_per'],
                'taxamt'            => $_POST['gst_amt'],
                'grant'             => $_POST['grant_total'],
                'addamt'            => $_POST['add_amount'],
                'round_amt'         => $_POST['round'],
                'net'               => $_POST['total_weight'],
                'items'             => $_POST['totalRate'],
                'quans'             => $_POST['totalQty'],
                'weights'           => $_POST['totalGrossWeight'],
                'tax_type'          => '',
                'user'              => $_POST['login_user'],
                'last'              => '',
                'branch'            => $_POST['branch'],
            );
            $data =  $this->db->insert('purchase_return', $insertData);
            

            if($data)
            {
                $responce['status'] = 1;
                $responce['message'] = 'purchase Submitted';
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
    
    function deletePurchases($headers){
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
            
            $this->db->where('grn', $_POST['billno']);
            $this->db->limit(1);
            $bill =  $this->db->get('purchase');
            $billData = $bill->result_array();
            
            if($billData)
            {
                
                $data = $this->db->delete('purchase', array('grn' => $_POST['billno']));
                $this->db->delete('purchase_items', array('grn' => $_POST['billno']));
                // $this->db->delete('sales_old', array('billno' => $_POST['billno']));
                
                
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