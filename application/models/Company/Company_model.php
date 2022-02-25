<?php

class Company_model extends CI_Model
{
    public function module($headers){
        if($headers['Module'] == 'company-details')
        {
            return $this->company($headers);
        } else if($headers['Module'] == 'get-total-branch')
        {
            return $this->getTotalBranch($headers);
        } else if($headers['Module'] == 'get-todays-value')
        {
            return $this->getTodaysValue($headers);
        } else if($headers['Module'] == 'save-todays-value')
        {
            return $this->saveTodaysValue($headers);
        } else if($headers['Module'] == 'get-default-branch-value')
        {
            return $this->getDefaultValue($headers);
        } else if($headers['Module'] == 'save-default-branch-value')
        {
            return $this->saveDefaultValue($headers);
        }else if($headers['Module'] == 'get-default-sms-value')
        {
            return $this->getDefaultSMSValue($headers);
        } else if($headers['Module'] == 'save-default-sms-value')
        {
            return $this->saveDefaultSMSValue($headers);
        }
        else if($headers['Module'] == 'get-default-wapp-value')
        {
            return $this->getDefaultWTValue($headers);
        } else if($headers['Module'] == 'save-default-wapp-value')
        {
            return $this->saveDefaultWTValue($headers);
        }
        else if($headers['Module'] == 'update-todays-value')
        {
            return $this->updateTodaysValue($headers);
        }else if($headers['Module'] == 'delete-todays-value')
        {
            return $this->deleteTodaysValue($headers);
        }  else if($headers['Module'] == 'get-user-home')
        {
            return $this->getUserHome($headers);
        } else if($headers['Module'] == 'get-admin-home')
        {
            return $this->getAdminHome($headers);
        } else if($headers['Module'] == 'get-mobile-home')
        {
            return $this->getMobileHome($headers);
        } else if($headers['Module'] == 'save-branch')
        {
            return $this->saveBranch($headers);
        } else if($headers['Module'] == 'update-branch')
        {
            return $this->updateBranch($headers);
        } else if($headers['Module'] == 'delete-branch')
        {
            return $this->deleteBranch($headers);
        } else {
            $responce['status'] = 0;
            $responce['message'] = 'Something is wrong';
            $responce['data'] = '';
            return $responce;
        }
        
    }

    function company($headers){
            //shop details
            // $this->db->where('id', '1');
            $shop =  $this->db->get('setting_company');
            $data = $shop->result_array();
            

            if(count($data) > 0)
            {
                $responce['status'] = 1;
                $responce['message'] = 'Company Deatils!';
                $responce['data'] = $data;
                return $responce;
            } else {
                $responce['status'] = 0;
                $responce['message'] = 'Samething is wrong';
                $responce['data'] = '';
                return $responce;
            }
        
    }
    
    function getUserHome($headers){
        if((!$_POST['login_user']) && (($_POST['login_user'] == null)))
        {
            $responce['status'] = 0;
            $responce['message'] = 'login_user is required';
            $responce['data'] = '';
            return $responce;
        } else {
            
            $data = array();
            $date = date("Y-m-d");
            
            $query11 = $this->db->query("select price from master_today_default_price where iname='gold' and branch='".$_POST['branch']."'");
            $query12 = $query11->result_array();
            $data['gold-rate']= (!empty($query12))?$query12[0]['price']:0;
            
            $query21 = $this->db->query("select price from master_today_default_price where iname='silver' and branch='".$_POST['branch']."'");
            $query22 = $query21->result_array();
            $data['silver-rate']= $query22?($query22[0]['price']):0;
            
            $query31 = $this->db->query("select sum(net) as net from sales where dat between '".$date."'and branch='".$_POST['branch']."'");
            $query32 = $query31->result_array();
            $data['sales']= $query32?($query32[0]['net']):0;
            
            $query41 = $this->db->query("select count(ino) as ino from master_item where branch='".$_POST['branch']."'");
            $query42 = $query41->result_array();
            $data['items']= $query42?($query42[0]['ino']):0;
            
            $date = date('Y-m-d');
            
            $query51 = $this->db->query("select count(billno) as net from sales where dat='".$date."' and branch='".$_POST['branch']."'");
            $query52 = $query51->result_array();
            $data['salesno']= $query52?($query52[0]['net']):0;
            
            $query61 = $this->db->query("select count(grn) as net from purchase where bdate='".$date."' and branch='".$_POST['branch']."'");
            $query62 = $query61->result_array();
            $data['purchaseno']= $query62?($query62[0]['net']):0;
            
            $query71 = $this->db->query("select count(billno) as net from account_transfer where dat='".$date."' and branch='".$_POST['branch']."'");
            $query72 = $query71->result_array();
            $data['voucher']= $query72?($query72[0]['net']):0;
            
            $query81 = $this->db->query("select count(sno) as net from scheme_entry where dat='".$date."' and entered_by_branch='".$_POST['branch']."'");
            $query82 = $query81->result_array();
            $data['schems']= $query82?($query82[0]['net']):0;
            
            
            
            if(count($data) > 0)
            {
                $responce['status'] = 1;
                $responce['message'] = 'Home Page Deatils!';
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
    
    function getAdminHome($headers){
        if((!$_POST['login_user']) && (($_POST['login_user'] == null)))
        {
            $responce['status'] = 0;
            $responce['message'] = 'login_user is required';
            $responce['data'] = '';
            return $responce;
        } else {
            
            $data = array();
            
            $query11 = $this->db->query("select count(branch) as branch from master_branch");
            $query12 = $query11->result_array();
            $data['total-branch']= $query12?$query12[0]['branch']:0;
            
            $query21 = $this->db->query("select count(sno) as scheme_no from scheme_entry");
            $query22 = $query21->result_array();
            $data['scheme_no']= $query22?($query22[0]['scheme_no']):0;
            
            $query31 = $this->db->query("select count(cid) as cid from cust");
            $query32 = $query31->result_array();
            $data['c_count']= $query32?($query32[0]['cid']):0;
            
            $query41 = $this->db->query("select count(user_name) as user_name from users");
            $query42 = $query41->result_array();
            $data['users']= $query42?($query42[0]['user_name']):0;
            
            $date = date('Y-m-d');
            
            $query51 = $this->db->query("select sum(net) as net from sales where dat='".$date."'");
            $query52 = $query51->result_array();
            $data['sales']= $query52?($query52[0]['net']):0;
            
            $query61 = $this->db->query("select sum(net) as net from purchase where bdate='".$date."'");
            $query62 = $query61->result_array();
            $data['purchase']= $query62?($query62[0]['net']):0;
            
            $query71 = $this->db->query("select count(sno) as scheme_no  from scheme_entry where dat='".$date."'");
            $query72 = $query71->result_array();
            $data['scheme']= $query72?($query72[0]['scheme_no']):0;
            
            $query81 = $this->db->query("select count(sno) as net from orders_entry where dat='".$date."'");
            $query82 = $query81->result_array();
            $data['voucher']= $query82?($query82[0]['net']):0;
            
            $querySale = $this->db->query("SELECT branch, COUNT(billno)  as count,SUM(net) as net FROM sales WHERE dat='".$date."' GROUP BY branch ORDER BY SUM(net) DESC");
            $querySaleR = $querySale->result_array();
            $data['sales-list']= $querySaleR?($querySaleR):0;
            
            $queryParchase = $this->db->query("select branch,count(billno) as count,sum(net) as net from purchase   where dat='".$date."' GROUP BY branch ORDER BY SUM(net) DESC");
            $queryPurchaseR = $queryParchase->result_array();
            $data['purchase-list']= $queryPurchaseR?($queryPurchaseR):0;
            
            $queryOrder = $this->db->query("SELECT entered_by_branch as branch, COUNT(sno) as sno,SUM(tot) as tot FROM orders_entry WHERE dat='".$date."' GROUP BY entered_by_branch ORDER BY sum(tot) DESC;");
            $queryOrderR = $queryOrder->result_array();
            $data['order-list']= $queryOrderR?($queryOrderR):[];
            
            $queryUnOrder = $this->db->query("SELECT entered_by_branch as branch, COUNT(sno) as sno ,SUM(bal) as bal FROM orders_entry WHERE STATUS='Active' AND ddate< CURDATE() GROUP BY entered_by_branch ORDER BY SUM(bal) desc;");
            $queryUnOrderR = $queryUnOrder->result_array();
            $data['unorder-list']= $queryUnOrderR?($queryUnOrderR):[];
            
            $queryScheme = $this->db->query("SELECT entered_by_branch as branch, COUNT(sno) as sno FROM scheme_entry WHERE dat='".$date."' GROUP BY entered_by_branch ORDER BY COUNT(sno) DESC");
            $querySchemeR = $queryScheme->result_array();
            $data['scheme-list']= $querySchemeR?($querySchemeR):[];
            
            $queryOverDue = $this->db->query("select entered_by_branch as branch, TIMESTAMPDIFF(MONTH,ndate, CURDATE()) AS No_of_Overdues, ((due_amount)* (TIMESTAMPDIFF(MONTH,ndate, CURDATE()))) AS Overdue_Amount FROM scheme_entry where ndate < CURDATE() GROUP BY entered_by_branch");
            $queryOverDueR = $queryOverDue->result_array();
            $data['overdues']= $queryOverDueR?($queryOverDueR):[];
            
            
            
            
            
            if(count($data) > 0)
            {
                $responce['status'] = 1;
                $responce['message'] = 'Home Page Deatils!';
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
    
    function getMobileHome($headers){
        if((!$_POST['login_user']) && (($_POST['login_user'] == null)))
        {
            $responce['status'] = 0;
            $responce['message'] = 'login_user is required';
            $responce['data'] = '';
            return $responce;
        } else {
            $date = date('Y-m-d');
            // $date = '2021-05-31';
            
            $data = array();
            
            $query11 = $this->db->query("SELECT SUM(net) as sales FROM sales WHERE dat='".$date."'");
            $query12 = $query11->result_array();
            $data['Today Status']['Sales']= (isset($query12) && isset($query12[0]['sales'])) ?(double)$query12[0]['sales']:0;
            
            $query21 = $this->db->query("SELECT SUM(net) as net FROM purchase WHERE bdate='".$date."'");
            $query22 = $query21->result_array();
            $data['Today Status']['Purchase']= (isset($query22) && isset($query22[0]['net']))?((double)$query22[0]['net']):0;
            
            $query31 = $this->db->query("SELECT SUM(net) as net FROM estimate WHERE dat='".$date."'");
            $query32 = $query31->result_array();
            $data['Today Status']['Estimate']= (isset($query32) && isset($query32[0]['net'])) ?((double)$query32[0]['net']):0;
            
            $query41 = $this->db->query("SELECT SUM(amount) as amt FROM scheme_pay WHERE dat='".$date."'");
            $query42 = $query41->result_array();
            $data['Today Status']['Schema Amount']= (isset($query42) && isset($query42[0]['amt']))?((double)$query42[0]['amt']):0;
            
            $query51 = $this->db->query("SELECT SUM(tot) as total FROM orders_entry WHERE dat='".$date."'");
            $query52 = $query51->result_array();
            $data['Today Status']['Orders Amount']= (isset($query52) && isset($query52[0]['total']))?((double)$query52[0]['total']):0;
            
            
            $query61 = $this->db->query("SELECT COUNT(sno) as sno FROM scheme_entry WHERE dat='".$date."'");
            $query62 = $query61->result_array();
            $data['Today New']['Schemes']= (isset($query62) && isset($query62[0]['sno']))?((double)$query62[0]['sno']):0;
            
            $query71 = $this->db->query("SELECT COUNT(sno) as sno FROM orders_entry WHERE dat='".$date."'");
            $query72 = $query71->result_array();
            $data['Today New']['Orders']= (isset($query72) && isset($query72[0]['sno']))?((double)$query72[0]['sno']):0;
            
            $query81 = $this->db->query("SELECT COUNT(cid) as cid FROM cust WHERE dat='".$date."'");
            $query82 = $query81->result_array();
            $data['Today New']['Customers']= (isset($query82) && isset($query82[0]['cid']))?((double)$query82[0]['cid']):0;
            
        
            $query111 = $this->db->query("SELECT COUNT(acode) as acode FROM agent WHERE dat='".$date."'");
            $query112 = $query111->result_array();
            $data['Today New']['Clients']= (isset($query112) && isset($query112[0]['acode']))?((double)$query112[0]['acode']):0;
            
            
            $query121 = $this->db->query("SELECT COUNT(sno) as sno FROM delete_sales WHERE dat='".$date."'");
            $query122 = $query121->result_array();
            $data['Alerts']['Delted Bills']= (isset($query122) && isset($query122[0]['sno']))?((double)$query122[0]['sno']):0;
            
            
            $query131 = $this->db->query("SELECT COUNT(sno) as sno FROM alter_sales WHERE dat='".$date."'");
            $query132 = $query131->result_array();
            $data['Alerts']['Altered Bills']= (isset($query132) && isset($query132[0]['sno']))?((double)$query132[0]['sno']):0;
            
            $query141 = $this->db->query("SELECT COUNT(sno) as sno FROM delete_estimate WHERE dat='".$date."'");
            $query142 = $query141->result_array();
            $data['Alerts']['Deleted Estimate']= (isset($query142) && isset($query142[0]['sno']))?((double)$query142[0]['sno']):0;
            
            $query141 = $this->db->query("SELECT COUNT(sno) as sno FROM alter_estimate WHERE dat='".$date."'");
            $query142 = $query141->result_array();
            $data['Alerts']['Altered Estimate']= (isset($query142) && isset($query142[0]['sno']))?((double)$query142[0]['sno']):0;
            
            $query151 = $this->db->query("SELECT COUNT(branch) as sno FROM master_branch");
            $query152 = $query151->result_array();
            $data['Total Branch']= (isset($query152) && isset($query152[0]['sno']))?((double)$query152[0]['sno']):0;
            
            $query161 = $this->db->query("SELECT COUNT(user_name) as sno FROM users");
            $query162 = $query161->result_array();
            $data['Total Users']= (isset($query162) && isset($query162[0]['sno']))?((double)$query162[0]['sno']):0;
            
            
            
            
            if(count($data) > 0)
            {
                $responce['status'] = 1;
                $responce['message'] = 'Mobile Home Page Deatils!';
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
    
    function getTotalBranch($headers){
        if((!$_POST['username']) && (($_POST['username'] == null)))
        {
            $responce['status'] = 0;
            $responce['message'] = 'Username is required';
            $responce['data'] = '';
            return $responce;
        } else {
            
            // $this->db->order_by("id", "desc");
            $shop =  $this->db->get('master_branch');
            $data = $shop->result_array();
            

            if(count($data) > 0)
            {
                $responce['status'] = 1;
                $responce['message'] = 'Total branch details';
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
    
    function getTodaysValue($headers)
    {
        if((!$_POST['username']) && (($_POST['username'] == null)))
        {
            $responce['status'] = 0;
            $responce['message'] = 'Username is required';
            $responce['data'] = '';
            return $responce;
        } else {
            
            // $this->db->order_by("id", "desc");
            // if(isset($_POST['username']))
            //     $this->db->where('branch',$_POST['branch']);
            $shop =  $this->db->get('master_today_market_price');
            $data = $shop->result_array();
            

            if(count($data) > 0)
            {
                $responce['status'] = 1;
                $responce['message'] = 'Todays Value details';
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
    
    function saveTodaysValue($headers)
    {
        if((!$_POST['username']) && (($_POST['username'] == null)))
        {
            $responce['status'] = 0;
            $responce['message'] = 'Username is required';
            $responce['data'] = '';
            return $responce;
        } else if(!isset($_POST['item_type']))
        {
            $responce['status'] = 0;
            $responce['message'] = 'Item type is required';
            $responce['data'] = '';
            return $responce;
        } else if(!isset($_POST['purity']))
        {
            $responce['status'] = 0;
            $responce['message'] = 'Purity is required';
            $responce['data'] = '';
            return $responce;
        } else if(!isset($_POST['price']))
        {
            $responce['status'] = 0;
            $responce['message'] = 'Price is required';
            $responce['data'] = '';
            return $responce;
        } else {
            
            // $this->db->order_by("id", "desc");
            // $this->db->where('branch',$_POST['branch']);
            // $this->db->where('item_type',$_POST['item_type']);
            
            // $shop =  $this->db->get('master_today_market_price');
            // $branch = $shop->result_array();
            
            $this->db->where('item_type',$_POST['item_type']);
            $this->db->where('purity',$_POST['purity']);
            $this->db->where('branch',$_POST['branch']);
            $this->db->where('price',$_POST['price']);
            $shop =  $this->db->get('master_today_market_price');
            $branch = $shop->result_array();
            
            
            if(!$branch)
            {
                
                $data = array();
            
                $date = date("Y-m-d");
                
                $insertData = array(
                            'last_modified_at' => $date,
                            'branch' => $_POST['branch'],
                            'item_type' => $_POST['item_type'],
                            'purity' => $_POST['purity'],
                            'price' => $_POST['price']
                );
                $data =  $this->db->insert('master_today_market_price', $insertData);
    
                if($data)
                {
                    $responce['status'] = 1;
                    $responce['message'] = 'Value Submitted';
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
                $responce['message'] = 'Already exits';
                $responce['data'] = '';
                return $responce;
            }
            
        }
    }
    
    function updateTodaysValue($headers)
    {
        if((!$_POST['username']) && (($_POST['username'] == null)))
        {
            $responce['status'] = 0;
            $responce['message'] = 'Username is required';
            $responce['data'] = '';
            return $responce;
        } else if((!$_POST['item_type']) && (($_POST['item_type'] == null)))
        {
            $responce['status'] = 0;
            $responce['message'] = 'Item type is required';
            $responce['data'] = '';
            return $responce;
        } else if((!$_POST['purity']) && (($_POST['purity'] == null)))
        {
            $responce['status'] = 0;
            $responce['message'] = 'Purity is required';
            $responce['data'] = '';
            return $responce;
        } else if((!$_POST['price']) && (($_POST['price'] == null)))
        {
            $responce['status'] = 0;
            $responce['message'] = 'Price is required';
            $responce['data'] = '';
            return $responce;
        } else {
            
            // print_r($_POST);die();
            
            $date = date("Y-m-d");
            
            $this->db->set('price',$_POST['price']);
            $this->db->set('last_modified_at',$date);
            $this->db->where('item_type',$_POST['item_type']);
            $this->db->where('purity',$_POST['purity']);
            $this->db->where('branch',$_POST['branchname']);
            $data =  $this->db->update('master_today_market_price');
            

            if($data)
            {
                $responce['status'] = 1;
                $responce['message'] = 'Value updated successfully!!';
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
    
    function deleteTodaysValue($headers)
    {
        if((!$_POST['username']) && (($_POST['username'] == null)))
        {
            $responce['status'] = 0;
            $responce['message'] = 'Username is required';
            $responce['data'] = '';
            return $responce;
        } else if((!$_POST['branch']) && (($_POST['branch'] == null)))
        {
            $responce['status'] = 0;
            $responce['message'] = 'Branch name is required';
            $responce['data'] = '';
            return $responce;
        } else {
            
            
            $this->db->where('branch', $_POST['branch']);
            $this->db->where('item_type', $_POST['item_type']);
            $this->db->where('purity', $_POST['purity']);
            $result =  $this->db->delete('master_today_market_price');
            

            if($result)
            {
                $responce['status'] = 1;
                $responce['message'] = 'Value deleted..!!';
                $responce['data'] = '';
                return $responce;
            } else {
                $responce['status'] = 0;
                $responce['message'] = 'Samething is wrong';
                $responce['data'] = '';
                return $responce;
            }
            
        }
        
    }
    
    function saveBranch($headers){
        if((!$_POST['username']) && (($_POST['username'] == null)))
        {
            $responce['status'] = 0;
            $responce['message'] = 'Username is required';
            $responce['data'] = '';
            return $responce;
        } else if((!$_POST['branchname']) && (($_POST['branchname'] == null)))
        {
            $responce['status'] = 0;
            $responce['message'] = 'Branch name is required';
            $responce['data'] = '';
            return $responce;
        } else {
            
            //shop details
            $this->db->where('branch',$_POST['branchname']);
            $shop =  $this->db->get('master_branch');
            $branch = $shop->result_array();
            
            
            if(!$branch)
            {
            
        //         $this->db->order_by('id', 'DESC');
        //         $this->db->limit(1);
        //         $last =  $this->db->get('master_branch');
        //         $last_code = $last->result_array();
    			
        //         if ($last_code) {
    				// $string = 'BRANCH_'. $last_code[0]['id'];
    				// $string = explode('_', $string);
    				// $zero = '';
    				// $number = (double) $string[1];
    				// for ($i = mb_strlen( ++$number); $i < 5; $i++) {
    				// 	$zero = $zero . '0';
    				// }
    				// $_POST['code'] = $string[0] .'_' . $zero . $number;
        //             //$_POST['code'] = generateTicketCode('TKT/'.date('Y').'/' . $last_code[0]['id']);
        //         } else {
        //             $_POST['code'] = 'BRANCH_00001';
        //         }
                    
                $insertData = array(
                        'branch' => $_POST['branchname']
                );
                $data =  $this->db->insert('master_branch', $insertData);
                
    
                if($data)
                {
                    $responce['status'] = 1;
                    $responce['message'] = 'Branch submitted successfully!!';
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
                $responce['message'] = 'Branch already exits';
                $responce['data'] = '';
                return $responce;
            }
            
        }
    }
    
    function updateBranch($headers){
        if((!$_POST['username']) && (($_POST['username'] == null)))
        {
            $responce['status'] = 0;
            $responce['message'] = 'Username is required';
            $responce['data'] = '';
            return $responce;
        } else if((!$_POST['branchname']) && (($_POST['branchname'] == null)))
        {
            $responce['status'] = 0;
            $responce['message'] = 'Branch name is required';
            $responce['data'] = '';
            return $responce;
        } else {
            $this->db->set('branch',$_POST['branchname']);
            $this->db->where('id',$_POST['id']);
            $data =  $this->db->update('master_branch');
            

            if($data)
            {
                $responce['status'] = 1;
                $responce['message'] = 'Branch updated successfully!!';
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
    
    
    function deleteBranch($headers){
        if((!$_POST['username']) && (($_POST['username'] == null)))
        {
            $responce['status'] = 0;
            $responce['message'] = 'Username is required';
            $responce['data'] = '';
            return $responce;
        } else if((!$_POST['branch']) && (($_POST['branch'] == null)))
        {
            $responce['status'] = 0;
            $responce['message'] = 'Branch name is required';
            $responce['data'] = '';
            return $responce;
        } else {
            
            
            $this->db->where('branch', $_POST['branch']);
            $result =  $this->db->delete('master_branch');
            

            if($result)
            {
                $responce['status'] = 1;
                $responce['message'] = 'Branch deleted..!!';
                $responce['data'] = '';
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
            
            
            $query11 = $this->db->query("select * from setting_branch where branch='".$_POST['branch']."'");
            $data = $query11->result_array();
            
            if(count($data) > 0)
            {
                $responce['status'] = 1;
                $responce['message'] = 'Get Default branch Value';
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
            
            // print_r($_POST); die();
            
            
            $data1 = array();
            $data2 = array();
            
            $insertData1 = array(
                'bill_name'             => isset($_POST['name'])?$_POST['name']:'',
                'bill_add1'             => isset($_POST['add1'])?$_POST['add1']:'',
                'bill_add2'             => isset($_POST['add2'])?$_POST['add2']:'',
                'bill_add3'             => isset($_POST['add3'])?$_POST['add3']:'',
                'bill_add4'             => isset($_POST['add4'])?$_POST['add4']:'',
                'state_name'            => isset($_POST['sname'])?$_POST['sname']:'',
                'state_code'            => isset($_POST['scode'])?$_POST['scode']:'',
                'bill_prefix'           => isset($_POST['bprefix'])?$_POST['bprefix']:'',
                'bill_format'           => isset($_POST['bformat'])?$_POST['bformat']:'.',
                'estimate_format'       => isset($_POST['eformat'])?$_POST['eformat']:'',
                'scheme_receipt_format' => isset($_POST['srformat'])?$_POST['srformat']:'',
                'order_receipt_format'  => isset($_POST['orformat'])?$_POST['orformat']:'',
                'estimate_head'         => isset($_POST['eHead'])?$_POST['eHead']:'',
                'max_bill_discount'     => isset($_POST['bDis'])?$_POST['bDis']:'',
                'no_of_bill_copies'     => isset($_POST['bCopy'])?$_POST['bCopy']:'',
                'no_of_estimate_copies' => isset($_POST['esCopy'])?$_POST['esCopy']:'',
                'port_name'             => isset($_POST['portNumber'])?$_POST['portNumber']:'',
                'dot_matrix_lines'      => isset($_POST['dmLines'])?$_POST['dmLines']:'',
                'bill_message1'         => isset($_POST['msg1'])?$_POST['msg1']:'',
                'bill_message2'         => isset($_POST['msg2'])?$_POST['msg2']:'',
                'bill_message3'         => isset($_POST['msg3'])?$_POST['msg3']:'',
                'bill_message4'         => isset($_POST['msg4'])?$_POST['msg4']:'',
                'last_modified'         => date("Y-m-d"),
                'gstin'                 => $_POST['gstin'],
                    'branch'            => $_POST['branch'],
            );
            
            if( $_POST['type'] == 'new')
            {
                $data1 =  $this->db->insert('setting_branch', $insertData1);
                
            } else {
                $this->db->where('branch', $_POST['branch']);
                $data1 =  $this->db->update('setting_branch', $insertData1);
        
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
    
    
    function getDefaultSMSValue($headers){
        if((!$_POST['login_user']) && (($_POST['login_user'] == null)))
        {
            $responce['status'] = 0;
            $responce['message'] = 'login_user is required';
            $responce['data'] = '';
            return $responce;
        } else {
            
            
            $query11 = $this->db->query("select sms_option,api_key,mobile1,mobile2,footer,sender_id from setting_sms");
            $data = $query11->result_array();
            
            if(count($data) > 0)
            {
                $responce['status'] = 1;
                $responce['message'] = 'Get Default SMS Value';
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
    
    function saveDefaultSMSValue($headers){
        if((!$_POST['login_user']) && (($_POST['login_user'] == null)))
        {
            $responce['status'] = 0;
            $responce['message'] = 'login_user is required';
            $responce['data'] = '';
            return $responce;
        } else {
            
            // print_r($_POST); die();
            
            
            $data1 = array();
            
            $insertData1 = array(
                'sms_option' => $_POST['option'],
                'api_key' => $_POST['apiKey'],
                'footer' => $_POST['smsFooter'],
                'mobile1' => $_POST['mob1'],
                'mobile2' => $_POST['mob2'],
                'sender_id' => $_POST['sender']
            );
            
            if( $_POST['type'] == 'new')
            {
                $data1 =  $this->db->insert('setting_sms', $insertData1);
                
            } else {
                $data1 =  $this->db->update('setting_sms', $insertData1);
        
            }
            
            
            if($data1)
            {
                $responce['status'] = 1;
                $responce['message'] = 'SMS Setting Updated';
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
    
    function getDefaultWTValue($headers){
        if((!$_POST['login_user']) && (($_POST['login_user'] == null)))
        {
            $responce['status'] = 0;
            $responce['message'] = 'login_user is required';
            $responce['data'] = '';
            return $responce;
        } else {
            
            
            $query11 = $this->db->query("select sms_option,api_key from setting_whatsapp_sms");
            $data = $query11->result_array();
            
            if(count($data) > 0)
            {
                $responce['status'] = 1;
                $responce['message'] = 'Get Default Whats App Value';
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
    
    function saveDefaultWTValue($headers){
        if((!$_POST['login_user']) && (($_POST['login_user'] == null)))
        {
            $responce['status'] = 0;
            $responce['message'] = 'login_user is required';
            $responce['data'] = '';
            return $responce;
        } else {
            
            // print_r($_POST); die();
            
            
            $data1 = array();
            $data2 = array();
            
            $insertData1 = array(
                'sms_option' => $_POST['option'],
                'api_key' => $_POST['apiKey'],
            );
            
            if( $_POST['type'] == 'new')
            {
                $data1 =  $this->db->insert('setting_whatsapp_sms', $insertData1);
                
            } else {
                $data1 =  $this->db->update('setting_whatsapp_sms', $insertData1);
        
            }
            
            
            if($data1)
            {
                $responce['status'] = 1;
                $responce['message'] = 'Whats App Setting Updated';
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
}