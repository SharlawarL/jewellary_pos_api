<?php

class Item_model extends CI_Model
{
    public function module($headers){
        if($headers['Module'] == 'get-item')
        {
            return $this->getItem($headers);
        } else if($headers['Module'] == 'get-drop-item')
        {
            return $this->getItemDrop($headers);
        } else if($headers['Module'] == 'get-item-purity')
        {
            return $this->getItemPurity($headers);
        } else if($headers['Module'] == 'delete-item-purity')
        {
            return $this->deleteItemPurity($headers);
        } else if($headers['Module'] == 'get-default-value')
        {
            return $this->getDefaultValue($headers);
        } else if($headers['Module'] == 'save-default-value')
        {
            return $this->saveDefaultValue($headers);
        } else if($headers['Module'] == 'get-tax-value')
        {
            return $this->getTaxValue($headers);
        } else if($headers['Module'] == 'save-tax-value')
        {
            return $this->saveTaxValue($headers);
        } else if($headers['Module'] == 'get-last-item')
        {
            return $this->getLastItem($headers);
        } else if($headers['Module'] == 'delete-item')
        {
            return $this->deleteItem($headers);
        } else if($headers['Module'] == 'save-item')
        {
            return $this->saveItem($headers);
        } else if($headers['Module'] == 'save-item-purity')
        {
            return $this->saveItemPurity($headers);
        } else if($headers['Module'] == 'update-item')
        {
            return $this->updateItem($headers);
        }  else {
            $responce['status'] = 0;
            $responce['message'] = 'Something is wrong';
            $responce['data'] = '';
            return $responce;
        }
        
    }
    
    function getItem($headers){
        if((!$_POST['login_user']) && (($_POST['login_user'] == null)))
        {
            $responce['status'] = 0;
            $responce['message'] = 'login_user is required';
            $responce['data'] = '';
            return $responce;
        } else {
            
            // print_r($_POST);
            // die();
            
            if($_POST['type'] == 'item-report')
            {
                $query11 = $this->db->query("SELECT DISTINCT ino,iname,category,item_type,purity,making_charge,wastage,rack,hsn_code,price_type 
                FROM master_item 
                WHERE branch='".$_POST['branch']."' ORDER BY ino DESC");
                $data = $query11->result_array();
                
            } else if($_POST['type'] == 'item-category')
            {
                
                if(isset($_POST['checkBox']) && ($_POST['checkBox'] == true))
                {
                    $query11 = $this->db->query("SELECT DISTINCT ino,iname,category,item_type,purity,making_charge,wastage,rack,hsn_code,price_type,price 
                    FROM master_item WHERE branch='".$_POST['branch']."' ORDER BY iname");
                    $data = $query11->result_array();
                } else {
                    $query11 = $this->db->query("SELECT DISTINCT ino,iname,category,item_type,purity,making_charge,wastage,rack,hsn_code,price_type,price 
                    FROM master_item WHERE branch='".$_POST['branch']."' and category='".$_POST['drop']."' ORDER BY iname");
                    $data = $query11->result_array();
                }
                
                
            } else if($_POST['type'] == 'item-type')
            {
                if(isset($_POST['checkBox']) && ($_POST['checkBox'] == true))
                {
                    $query11 = $this->db->query("SELECT DISTINCT ino,iname,category,item_type,purity,making_charge,wastage,rack,hsn_code,price_type 
                    FROM master_item WHERE branch='".$_POST['branch']."' ORDER BY iname");
                    $data = $query11->result_array();
                } else {
                    $query11 = $this->db->query("SELECT DISTINCT ino,iname,category,item_type,purity,making_charge,wastage,rack,hsn_code,price_type 
                    FROM master_item WHERE branch='".$_POST['branch']."' and item_type='".$_POST['drop']."' ORDER BY iname");
                    $data = $query11->result_array();
                }
                
            } else if($_POST['type'] == 'item-purity')
            {
                // print_r($_POST);die();
                if(isset($_POST['checkBox']) && ($_POST['checkBox'] == true))
                {
                    $query11 = $this->db->query("SELECT DISTINCT ino,iname,category,purity,item_type,making_charge,wastage,rack,hsn_code,price_type FROM master_item  
                    WHERE branch='".$_POST['branch']."' ORDER BY iname");
                    $data = $query11->result_array();
                } else {
                    $query11 = $this->db->query("SELECT DISTINCT ino,iname,category,purity,item_type,making_charge,wastage,rack,hsn_code,price_type FROM master_item  
                    WHERE branch='".$_POST['branch']."' and purity='".$_POST['drop']."' ORDER BY iname");
                    $data = $query11->result_array();
                }
                // print_r($this->db->last_query());die();
                
                
            }  else if($_POST['type'] == 'item-price')
            {
                if(isset($_POST['checkBox']) && ($_POST['checkBox'] == true))
                {
                    $query11 = $this->db->query("SELECT DISTINCT ino,iname,category,item_type,making_charge,wastage,rack,hsn_code,price_type 
                     FROM master_item  
                     WHERE branch='".$_POST['branch']."'  ORDER BY iname");
                    $data = $query11->result_array();
                } else {
                     $query11 = $this->db->query("SELECT DISTINCT ino,iname,category,item_type,making_charge,wastage,rack,hsn_code,price_type 
                     FROM master_item  
                     WHERE branch='".$_POST['branch']."' and price_type='".$_POST['drop']."' ORDER BY iname");
                    $data = $query11->result_array();
                }
            } else if($_POST['type'] == 'item-category-summary')
            {
                $query11 = $this->db->query("SELECT DISTINCT category,COUNT(ino) as count FROM master_item WHERE branch='".$_POST['branch']."' GROUP BY category ORDER BY category");
                $data = $query11->result_array();
                
            }  else if($_POST['type'] == 'item-hsn-summary')
            {
                $query11 = $this->db->query("SELECT DISTINCT hsn_code,COUNT(ino) as count FROM master_item WHERE branch='".$_POST['branch']."' GROUP BY hsn_code ORDER BY hsn_code");
                $data = $query11->result_array();
                
            } else {
            
            //die();
            if (isset($_POST['search_key']) && $_POST['search_key'] != '' && $_POST['search_key'] != null) {
				$search_key = $_POST['search_key'];
				$like = "(
				    master_item.item_name like '%$search_key%' or 
				    master_item.category like '%$search_key%' or 
				    master_item.item_type like '%$search_key%' or 
				    master_item.purity like '%$search_key%' or 
				    master_item.making_charge like '%$search_key%' or 
				    master_item.rack like '%$search_key%' or 
				    master_item.wastage like '%$search_key%' or 
				    master_item.price_type like '%$search_key%' or 
				    master_item.hsn_code like '%$search_key%' or 
				    master_item.price like '%$search_key%' or 
				    master_item.branch like '%$search_key%
				    ')"; 
				$this->db->where($like);
			}
            
            $this->db->order_by("sno", "desc");
            // $this->db->join('master_branch', 'users.branch= master_branch.id');
            // $this->db-select('master_branch.branch as branchname');
            $shop =  $this->db->get('master_item');
            $data = $shop->result_array();
            
            }
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
                $query11 = $this->db->query("SELECT DISTINCT item_type FROM master_item WHERE branch='".$_POST['branch']."'");
                $data = $query11->result_array();
            }
            
            if($_POST['type'] == 'item-purity')
            {
                $query11 = $this->db->query("SELECT DISTINCT purity FROM master_item WHERE branch='".$_POST['branch']."'");
                $data = $query11->result_array();
            }
            if($_POST['type'] == 'item-price')
            {
                $query11 = $this->db->query("SELECT DISTINCT price_type FROM master_item WHERE branch='".$_POST['branch']."'");
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
    
    
    function getItemPurity($headers)
    {
        if((!$_POST['login_user']) && (($_POST['login_user'] == null)))
        {
            $responce['status'] = 0;
            $responce['message'] = 'login_user is required';
            $responce['data'] = '';
            return $responce;
        } else {
            
            
            $query11 = $this->db->query("select * from master_item_purity where branch='".$_POST['branch']."'");
            $data = $query11->result_array();
            
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
    
    function deleteItemPurity($headers)
    {
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
        } else if((!$_POST['purity']) && (($_POST['purity'] == null)))
        {
            $responce['status'] = 0;
            $responce['message'] = 'purity is required';
            $responce['data'] = '';
            return $responce;
        } else {
            
            $this->db->where('purity', $_POST['purity']);
            $this->db->where('branch', $_POST['branch']);
            $data = $this->db->delete('master_item_purity');
            
            if($data)
            {
                $responce['status'] = 1;
                $responce['message'] = 'Item Purity Deleted';
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
    
    function getDefaultValue($headers){
        if((!$_POST['login_user']) && (($_POST['login_user'] == null)))
        {
            $responce['status'] = 0;
            $responce['message'] = 'login_user is required';
            $responce['data'] = '';
            return $responce;
        } else {
            
            
            $query11 = $this->db->query("select * from master_today_default_price where branch='".$_POST['branch']."'");
            $data = $query11->result_array();
            
            if(count($data) > 0)
            {
                $responce['status'] = 1;
                $responce['message'] = 'Get Default Value';
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
    
    function saveDefaultValue($headers){
        if((!$_POST['login_user']) && (($_POST['login_user'] == null)))
        {
            $responce['status'] = 0;
            $responce['message'] = 'login_user is required';
            $responce['data'] = '';
            return $responce;
        } else {
            
            
            $data1 = array();
            $data2 = array();
            
            $insertData1 = array(
                'iname' => 'gold',
                'price' => $_POST['gold'],
                'branch' => $_POST['branch'],
            );
            
            $insertData2 = array(
                'iname' => 'silver',
                'price' => $_POST['silver'],
                'branch' => $_POST['branch'],
            );
            
            if( $_POST['type'] == 'new')
            {
                $data1 =  $this->db->insert('master_today_default_price', $insertData1);
            
            
                $data2 =  $this->db->insert('master_today_default_price', $insertData2);
            } else {
                 $insertData1 = array(
                    'iname' => 'gold',
                    'price' => $_POST['gold'],
                    'branch' => $_POST['branch'],
                );
                $this->db->where('iname', 'gold');
                $this->db->where('branch', $_POST['branch']);
                $data1 =  $this->db->update('master_today_default_price', $insertData1);
            
            
                 $insertData2 = array(
                    'iname' => 'silver',
                    'price' => $_POST['silver'],
                    'branch' => $_POST['branch'],
                );
                $this->db->where('iname', 'silver');
                $this->db->where('branch', $_POST['branch']);
                $data2 =  $this->db->update('master_today_default_price', $insertData2);
            }
            
            
            if($data1)
            {
                $responce['status'] = 1;
                $responce['message'] = 'Value Updated';
                $responce['data'] = '';
                return $responce;
            } else {
                $responce['status'] = 0;
                $responce['message'] = 'No data found';
                $responce['data'] = '';
                return $responce;
            }
        }
    }
    
    function getTaxValue($headers){
        if((!$_POST['login_user']) && (($_POST['login_user'] == null)))
        {
            $responce['status'] = 0;
            $responce['message'] = 'login_user is required';
            $responce['data'] = '';
            return $responce;
        } else {
            
            
            $query11 = $this->db->query("select * from setting_tax");
            $data = $query11->result_array();
            
            if(count($data) > 0)
            {
                $responce['status'] = 1;
                $responce['message'] = 'Get Tax Value';
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
    
    function saveTaxValue($headers){
        if((!$_POST['login_user']) && (($_POST['login_user'] == null)))
        {
            $responce['status'] = 0;
            $responce['message'] = 'login_user is required';
            $responce['data'] = '';
            return $responce;
        } else {
            
            
            $data = array();
            
            $insertData = array(
                'taxp'      => $_POST['taxp'],
                'tax_type'  => $_POST['tax_type']
            );
            $this->db->where('taxp', $_POST['taxp_old']);
            $data =  $this->db->update('setting_tax', $insertData);
            
            
            if($data)
            {
                $responce['status'] = 1;
                $responce['message'] = 'Value Updated';
                $responce['data'] = '';
                return $responce;
            } else {
                $responce['status'] = 0;
                $responce['message'] = 'No data found';
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
            
            $this->db->order_by('sno', 'DESC');
            $this->db->limit(1);
            $last =  $this->db->get('master_item');
            $last_code = $last->result_array();

            $data['last_id'] = $last_code?$last_code[0]['sno'] + 1:1;
            
            $this->db->where('branch',$_POST['branch']);
            $this->db->order_by('ino', 'DESC');
            $this->db->limit(1);
            $last =  $this->db->get('master_item');
            $item_code = $last->result_array();
            $data['item_no'] = $item_code?$item_code[0]['ino'] + 1:1;
            

            if(count($data) > 0)
            {
                $responce['status'] = 1;
                $responce['message'] = 'Last items details';
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
    
    function deleteItem($headers){
        if((!$_POST['login_user']) && (($_POST['login_user'] == null)))
        {
            $responce['status'] = 0;
            $responce['message'] = 'login_user is required';
            $responce['data'] = '';
            return $responce;
        } else {
            // print_r($_POST);
            // die();
            $this->db->where('ino', $_POST['ino']);
            $data = $this->db->delete('master_item');
            

            if($data)
            {
                $responce['status'] = 1;
                $responce['message'] = 'Item Deleted';
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
    
    function saveItem($headers){
        if((!$_POST['login_user']) && (($_POST['login_user'] == null)))
        {
            $responce['status'] = 0;
            $responce['message'] = 'login_user is required';
            $responce['data'] = '';
            return $responce;
        } else
        if((!$_POST['name']) && (($_POST['name'] == null)))
        {
            $responce['status'] = 0;
            $responce['message'] = 'Name is required';
            $responce['data'] = '';
            return $responce;
        } else
        if((!$_POST['itemType']) && (($_POST['itemType'] == null)))
        {
            $responce['status'] = 0;
            $responce['message'] = 'ItemType is required';
            $responce['data'] = '';
            return $responce;
        } else {
            // print_r($_POST);
            // die();
            
            $this->db->where('branch',$_POST['branch']);
            $this->db->where('iname',$_POST['name']);
            $shop =  $this->db->get('master_item');
            $check = $shop->result_array();
            
            
            if(count($check) == 0)
            {
            
                $this->db->order_by('sno', 'DESC');
                $this->db->where('branch',$_POST['branch']);
                // $this->db-select('master_branch.branch as branchname');
                $shop =  $this->db->get('master_item');
                $item_no1 = $shop->result_array();
                $item_no = $item_no1?($item_no1[0]['sno'] + 1):1;
                
                $this->db->where('branch',$_POST['branch']);
                $this->db->order_by('ino', 'DESC');
                $this->db->limit(1);
                $last =  $this->db->get('master_item');
                $item_code = $last->result_array();
                $data_item = $item_code?$item_code[0]['ino'] + 1:1;
                
                
                $insertData = array(
                    'ino' => $data_item,
                    'iname' => $_POST['name'],
                    'category' => $_POST['category'],
                    'item_type' => $_POST['itemType'],
                    'purity' => $_POST['purity'],
                    'making_charge' => $_POST['makingCharges'],
                    'rack' => '.',
                    'wastage' => $_POST['wastage'],
                    'hsn_code' => $_POST['hsnCode'],
                    'price_type' => $_POST['priceType'],
                    'price' => $_POST['price'],
                    'location' => $_POST['location'],
                    'branch' => $_POST['branch'],
                );
                $data =  $this->db->insert('master_item', $insertData);
                
    
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
            } else {
                $responce['status'] = 0;
                $responce['message'] = 'Already exits.';
                $responce['data'] = '';
                return $responce;
            }
            
        }
    }
    
    
    function saveItemPurity($headers){
        if((!$_POST['login_user']) && (($_POST['login_user'] == null)))
        {
            $responce['status'] = 0;
            $responce['message'] = 'Login user is required';
            $responce['data'] = '';
            return $responce;
        } else if((!$_POST['purity']) && (($_POST['purity'] == null)))
        {
            $responce['status'] = 0;
            $responce['message'] = 'Purity is required';
            $responce['data'] = '';
            return $responce;
        } else {
            // print_r($_POST);
            // die();
            
            $this->db->where('branch',$_POST['branch']);
            $this->db->where('purity',$_POST['purity']);
            $shop =  $this->db->get('master_item_purity');
            $item_no1 = $shop->result_array();
            
            if(count($item_no1) == 0)
            {
            
                $insertData = array(
                    'purity' => $_POST['purity'],
                    'branch' => $_POST['branch'],
                );
                $data =  $this->db->insert('master_item_purity', $insertData);
    
    
                if($data)
                {
                    $responce['status'] = 1;
                    $responce['message'] = 'Item Purity Submitted';
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
    
     function updateItem($headers){
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
                'iname' => $_POST['name'],
                'category' => $_POST['category'],
                'item_type' => $_POST['itemType'],
                'purity' => $_POST['purity'][0]['code'],
                'making_charge' => $_POST['makingCharges'],
                'rack' => '.',
                'wastage' => $_POST['wastage'],
                'hsn_code' => $_POST['hsnCode'],
                'price_type' => $_POST['priceType'],
                'price' => $_POST['price'],
                'location' => $_POST['location'],
                'branch' => $_POST['branch'],
            );
            $this->db->where('ino', $_POST['ino']);
            $data =  $this->db->update('master_item', $insertData);
            

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
    
}