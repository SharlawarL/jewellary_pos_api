<?php

class Stock_model extends CI_Model
{
    public function module($headers){
        if($headers['Module'] == 'get-Stock')
        {
            return $this->getStock($headers);
        } else if($headers['Module'] == 'get-Stock-by-entry')
        {
            return $this->getStockByEntry($headers);
        } else if($headers['Module'] == 'get-drop-item')
        {
            return $this->getItemDrop($headers);
        }  else if($headers['Module'] == 'save-Stock')
        {
            return $this->saveStock($headers);
        }  else if($headers['Module'] == 'get-last-Stock')
        {
            return $this->getLastItem($headers);
        } else if($headers['Module'] == 'get-stock-by-id')
        {
            return $this->getStockById($headers);
        } else if($headers['Module'] == 'delete-Stock')
        {
            return $this->deleteStock($headers);
        }  else {
            $responce['status'] = 0;
            $responce['message'] = 'Something is wrong';
            $responce['data'] = '';
            return $responce;
        }
        
    }
    
    function getStock($headers){
        
        if((!$_POST['login_user']) && (($_POST['login_user'] == null)))
        {
            $responce['status'] = 0;
            $responce['message'] = 'login_user is required';
            $responce['data'] = '';
            return $responce;
        } else {
            
            // print_r($_POST);die(); stock-entry-list
            if(isset($_POST['from']) && isset($_POST['from']))
            {
                $_POST['from'] = ($_POST['from'] == 'Invalid date')?date('Y-m-d'):$_POST['from'];
                $_POST['to'] = ($_POST['to']== 'Invalid date')?date('Y-m-d'):$_POST['to'];
            }
            
           if($_POST['type'] == 'stock-item-type-report')
            {
                if(isset($_POST['checkBox']) && ($_POST['checkBox'] == true))
                {
                    $query11 = $this->db->query("SELECT lot_no,a.item_no,a.item_name, a.purity,b.price,SUM(quan) as quan,SUM(quan*net_weight) as net_weight,SUM((quan*net_weight)*b.price) as net_value
                    FROM stock a,master_today_market_price b 
                    WHERE a.branch=b.branch AND a.branch='".$_POST['branch']."' AND quan > 0 AND a.item_type=b.item_type AND a.purity=b.purity GROUP BY a.lot_no ORDER BY a.lot_no");
                    
                    $data = $query11->result_array();
                } else {
                    $query11 = $this->db->query("SELECT lot_no,a.item_no,a.item_name, a.purity,b.price,SUM(quan) as quan,SUM(quan*net_weight) as net_weight,SUM((quan*net_weight)*b.price) as net_value
                    FROM stock a,master_today_market_price b 
                    WHERE a.branch=b.branch AND a.branch='".$_POST['branch']."' AND quan > 0 AND a.item_type='".$_POST['drop']."' AND a.item_type=b.item_type AND a.purity=b.purity GROUP BY a.lot_no ORDER BY a.lot_no");
                    
                    $data = $query11->result_array();
                }
            }  else if($_POST['type'] == 'stock-purity-report')
            {
                if(isset($_POST['checkBox']) && ($_POST['checkBox'] == true))
                {
                    $query11 = $this->db->query("SELECT lot_no,a.item_no,a.item_name, a.purity,b.price,SUM(quan) as quan,SUM(quan*net_weight) as net_weight,SUM((quan*net_weight)*b.price) as net_value 
                    FROM stock a,master_today_market_price b 
                    WHERE a.branch=b.branch AND a.branch='".$_POST['branch']."' AND quan > 0 AND a.item_type=b.item_type AND a.purity=b.purity GROUP BY a.lot_no ORDER BY a.lot_no");
                    
                    $data = $query11->result_array();
                } else {
                    $query11 = $this->db->query("SELECT lot_no,a.item_no,a.item_name, a.purity,b.price,SUM(quan) as quan,SUM(quan*net_weight) as net_weight,SUM((quan*net_weight)*b.price) as net_value 
                    FROM stock a,master_today_market_price b 
                    WHERE a.branch=b.branch AND a.branch='".$_POST['branch']."' AND quan > 0 AND a.purity='".$_POST['drop']."' AND a.item_type=b.item_type AND a.purity=b.purity GROUP BY a.lot_no ORDER BY a.lot_no");
                    
                    $data = $query11->result_array();
                }
                
            } 
            else if($_POST['type'] == 'stock-price-type-report')
            {
                if(isset($_POST['checkBox']) && ($_POST['checkBox'] == true))
                {
                    $query11 = $this->db->query("SELECT lot_no,a.item_no,a.item_name, a.purity,b.price,SUM(quan) as quan,SUM(quan*net_weight) as net_weight,SUM((quan*net_weight)*b.price) as net_value 
                    FROM stock a,master_today_market_price b 
                    WHERE a.branch=b.branch AND a.branch='".$_POST['branch']."' AND quan > 0 AND a.item_type=b.item_type AND a.purity=b.purity GROUP BY a.lot_no ORDER BY a.lot_no");
                    
                    $data = $query11->result_array();
                } else {
                    $query11 = $this->db->query("SELECT lot_no,a.item_no,a.item_name, a.purity,b.price,SUM(quan) as quan,SUM(quan*net_weight) as net_weight,SUM((quan*net_weight)*b.price) as net_value 
                    FROM stock a,master_today_market_price b 
                    WHERE a.branch=b.branch AND a.branch='".$_POST['branch']."' AND quan > 0 AND a.price_type='".$_POST['drop']."' AND a.item_type=b.item_type AND a.purity=b.purity GROUP BY a.lot_no ORDER BY a.lot_no");
                    
                    $data = $query11->result_array();
                }
                
                
            } 
            else if($_POST['type'] == 'stock-list')
            {
                $query11 = $this->db->query("SELECT lot_no,DATE_FORMAT(dat,'%d/%m/%Y') as bdate,item_no,item_name,gross_weight,less_weight,net_weight,quan,SUM(quan*net_weight) as total_weight,price_type 
                FROM stock 
                WHERE branch='".$_POST['branch']."' and quan>0 GROUP BY lot_no ORDER BY lot_no");
                
                $data = $query11->result_array();
            } 
             else if($_POST['type'] == 'stock-entry-list')
            {
                $query11 = $this->db->query("SELECT DISTINCT entry_no,DATE_FORMAT(dat,'%d/%m/%Y') bdate,total_items,total_quan,tot_gross_weight,tot_less_weight,tot_net_weight 
                FROM stock_entry entry 
                WHERE dat BETWEEN '".$_POST['from']."' AND '".$_POST['to']."' AND branch='".$_POST['branch']."' ORDER BY dat, entry_no");
                
                $data = $query11->result_array();
            }
             else if($_POST['type'] == 'stock-old-list')
            {
                $query11 = $this->db->query("SELECT billno,DATE_FORMAT(dat,'%d/%m/%Y') bdate,iname,itype,qual,weight 
                FROM sales_old_items 
                WHERE dat BETWEEN '".$_POST['from']."' AND '".$_POST['to']."' AND branch='".$_POST['branch']."' ORDER BY dat,billno");
                
                $data = $query11->result_array();
            }
            else if($_POST['type'] == 'stock-summary')
            {
                $query11 = $this->db->query("SELECT 
                entry_type, purity, lot_no,DATE_FORMAT(dat,'%d/%m/%Y') as bdate,item_no,item_name,gross_weight,price,less_weight,net_weight,quan,SUM(quan*net_weight) as total_weight,price_type 
                FROM stock 
                Where branch='".$_POST['branch']."'  and quan>0
                group by entry_type 
                ORDER BY bdate,lot_no");
                
                $data = $query11->result_array();
            }
            else {
            //die();
                $this->db->order_by("lot_no", "desc");
                $shop =  $this->db->get('stock');
                $data = $shop->result_array();
            }
            

            if(count($data) > 0)
            {
                $responce['status'] = 1;
                $responce['message'] = 'Total stock details';
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
    
    function getItemDrop($headers)
    {
        if((!$_POST['login_user']) && (($_POST['login_user'] == null)))
        {
            $responce['status'] = 0;
            $responce['message'] = 'login_user is required';
            $responce['data'] = '';
            return $responce;
        } else {
            
            // print_r($_POST);
            // die();
            
            $data = array();
            
            if($_POST['type'] == 'item-supplier')
            {
                $query11 = $this->db->query("select distinct cname from purchase where branch='".$_POST['branch']."'");
                $data = $query11->result_array();
            }
            
            if($_POST['type'] == 'item-category')
            {
                $query11 = $this->db->query("SELECT DISTINCT category FROM master_item WHERE branch='".$_POST['branch']."'");
                $data = $query11->result_array();
            }
            
            if($_POST['type'] == 'item-type')
            {
                $query11 = $this->db->query("SELECT DISTINCT item_type FROM stock WHERE branch='".$_POST['branch']."'");
                $data = $query11->result_array();
            }
            
            if($_POST['type'] == 'item-purity')
            {
                $query11 = $this->db->query("SELECT DISTINCT purity FROM stock WHERE branch='".$_POST['branch']."'");
                $data = $query11->result_array();
            }
            if($_POST['type'] == 'item-price')
            {
                $query11 = $this->db->query("SELECT DISTINCT price_type FROM stock WHERE branch='".$_POST['branch']."'");
                $data = $query11->result_array();
            }
            
            // print_r($this->db->last_query());die();
            if(count($data) > 0)
            {
                $responce['status'] = 1;
                $responce['message'] = 'Total drop details';
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
    
    function getStockByEntry($headers){
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
        } else if((!$_POST['entry']) && (($_POST['entry'] == null)))
        {
            $responce['status'] = 0;
            $responce['message'] = 'Entry Number is required';
            $responce['data'] = '';
            return $responce;
        } else {
            
            $this->db->order_by('lot_no', 'DESC');
            $this->db->where('branch',$_POST['branch']);
            $this->db->where('lot_eno',$_POST['entry']);
            $shop =  $this->db->get('stock');
            $data = $shop->result_array();
            
            
            if(count($data) > 0)
            {
                $responce['status'] = 1;
                $responce['message'] = 'Total Stock By Entry details';
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
    
    
    function getLastItem($headers){
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
            
            //lot number
            $this->db->order_by('entry_no', 'DESC');
            $this->db->limit(1);
            $last =  $this->db->get('stock_entry');
            $last_code = $last->result_array();
            $data['lot_no'] = $last_code?$last_code[0]['entry_no'] + 1:1;
            
            //item number
            $this->db->order_by('lot_no', 'DESC');
            $this->db->where('branch',$_POST['branch']);
            $shop =  $this->db->get('stock');
            $data['item_no'] = count($shop->result_array()) + 1;
            
             //entry number
             $this->db->group_by('entry_no'); 
            $this->db->order_by('entry_no', 'DESC');
            $this->db->limit(1);
            $last =  $this->db->get('stock_entry');
            $last_code = $last->result_array();
            $data['entry_no'] = $last_code?$last_code[0]['entry_no'] + 1:1;
            

            if(count($data) > 0)
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
    
    function saveStock($headers){
        if((!$_POST['login_user']) && (($_POST['login_user'] == null)))
        {
            $responce['status'] = 0;
            $responce['message'] = 'login_user is required';
            $responce['data'] = '';
            return $responce;
        } else {
            
            $data = array();
            
            // print_r($_POST);
            // die();
            
            $stock = json_decode($_POST['selectedStock'], true);
            // print_r($stock);
            // die();
            
            // generate series number
            $this->db->order_by('lot_no', 'DESC');
            $shop =  $this->db->get('stock');
            
            $shop_count = $shop->result_array();
            $lot_no_stock = $shop_count?($shop_count[0]['lot_no']+1):1;
            
            $lot_no = 1;
            
            $entry_no = 0;
            
            $msg = '';
            
            $this->db->order_by('entry_no', 'DESC');
            $this->db->limit(1);
            $last =  $this->db->get('stock_entry');
            $stock_entry_data = $last->result_array();
                
            
            if($_POST['type'] == 'save')
            {
                
                $entry_no = $stock_entry_data?($stock_entry_data[0]['entry_no']+1):1;
                
                $msg = 'Stock submited successfully';
                
            } else {
                $this->db->where('entry_no', $_POST['entry_no']);
                $this->db->delete('stock_entry');
                
                $this->db->where('lot_eno', $_POST['entry_no']);
                $this->db->delete('stock');
                
                $entry_no = $_POST['entry_no'];
                $msg = 'Stock updated successfully';
            }
            
            
            
            $lot_no = $stock_entry_data?($stock_entry_data[0]['lot_no']+1):1;
            
            foreach($stock as $data)
            {
                $insertData = array(
                    'lot_no'            => $lot_no_stock,
                    'dat'               =>  date("Y/m/d h:i:sa"),
                    'item_no'           => isset($data['item_no'])?$data['item_no']:'.',
                    'item_name'         => isset($data['name'])?$data['name']:'.',
                    'gross_weight'      => isset($data['gross_weight'])?$data['gross_weight']:'.',
                    'less_weight'       => isset($data['loss_weight'])?$data['loss_weight']:'.',
                    'net_weight'        => isset($data['net_weight'])?$data['net_weight']:'.',
                    'wastage'           => isset($data['wastage'])?$data['wastage']:'.',
                    'making'            => isset($data['making_charges'])?$data['making_charges']:'.',
                    'quan'              => isset($data['qty'])?$data['qty']:'.',
                    'entry_type'        => isset($data['item_type'])?$data['item_type']:'.',
                    'price'             => isset($data['price'])?$data['price']:'.',
                    'lot_eno'           => $entry_no,
                    'user'              => isset($_POST['login_user'])?$_POST['login_user']:'.',
                    'last_modified_at'  => date("Y/m/d h:i:sa"),
                    'price_type'        => isset($data['price_type'])?$data['price_type']:'.',
                    'purity'            => isset($data['purity'])?$data['purity']:'.',
                    'item_type'         => isset($data['item_type'])?$data['item_type']:'.',
                    'branch'            => isset($data['branch'])?$data['branch']:'.',
                    
                );
                $this->db->insert('stock', $insertData);
                $lot_no_stock++;
                
                $insertEntryData = array(
                    'dat'               => date("Y/m/d h:i:sa"),
                    'entry_no'          => $entry_no,
                    'lot_no'            => $lot_no,
                    'item_no'           => $data['item_no'],
                    'item_name'         => $data['name'],
                    'gross_weight'      => $data['gross_weight'],
                    'less_weight'       => $data['loss_weight'],
                    'net_weight'        => $data['net_weight'],
                    'quan'              => $data['qty'],
                    'total_items'       => $_POST['totalItems'],
                    'total_quan'        => $_POST['totalQty'],
                    'tot_gross_weight'  => $_POST['totalGrossWeight'],
                    'tot_less_weight'   => $_POST['totalLessWeight'],
                    'tot_net_weight'    => $_POST['totalNetWeight'],
                    'price'             => 0,
                    'price_type'        => '',
                    'user'              => isset($_POST['login_user'])?$_POST['login_user']:'.',
                    'last_modified_at'  => date("Y/m/d h:i:sa"),
                    'branch'            => $_POST['branch'],
                );
                $result_data =  $this->db->insert('stock_entry', $insertEntryData);
                $lot_no++;
            }
            

            if($data)
            {
                $responce['status'] = 1;
                $responce['message'] = $msg;
                $responce['data'] = $data;
                return $responce;
            } else {
                $responce['status'] = 0;
                $responce['message'] = 'Try Again...';
                $responce['data'] = '';
                return $responce;
            }
            
        }
    }
    
    public function getStockById()
    {
        if((!$_POST['login_user']) && (($_POST['login_user'] == null)))
        {
            $responce['status'] = 0;
            $responce['message'] = 'login_user is required';
            $responce['data'] = '';
            return $responce;
        } else {
             if($data)
            {
                $responce['status'] = 1;
                $responce['message'] = 'Saved';
                $responce['data'] = $data;
                return $responce;
            } else {
                $responce['status'] = 0;
                $responce['message'] = 'Try Again...';
                $responce['data'] = '';
                return $responce;
            }
            
        }
    }
    
    public function deleteStock()
    {
        if((!$_POST['login_user']) && (($_POST['login_user'] == null)))
        {
            $responce['status'] = 0;
            $responce['message'] = 'login_user is required';
            $responce['data'] = '';
            return $responce;
        } else if((!$_POST['entry']) && (($_POST['entry'] == null)))
        {
            $responce['status'] = 0;
            $responce['message'] = 'Entry no is required';
            $responce['data'] = '';
            return $responce;
        } else {
            
            $this->db->where('entry_no', $_POST['entry']);
            $this->db->delete('stock_entry');
            
            $this->db->where('lot_eno', $_POST['entry']);
            $data = $this->db->delete('stock');
                
            
            if($data)
            {
                $responce['status'] = 1;
                $responce['message'] = 'Stock Deleted successfully';
                $responce['data'] = $data;
                return $responce;
            } else {
                $responce['status'] = 0;
                $responce['message'] = 'Try Again...';
                $responce['data'] = '';
                return $responce;
            }
            
        }
    }
    
}