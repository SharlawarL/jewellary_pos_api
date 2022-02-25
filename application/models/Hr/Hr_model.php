<?php

class Hr_model extends CI_Model
{
    public function module($headers){
        if($headers['Module'] == 'save-staff')
        {
            return $this->saveStaff($headers);
        } else if($headers['Module'] == 'get-last-staff')
        {
            return $this->getLastItem($headers);
        } else if($headers['Module'] == 'get-staff-deatils')
        {
            return $this->getStaffDetails($headers);
        }  else if($headers['Module'] == 'get-staff-deatils-id')
        {
            return $this->getStaffDetailsId($headers);
        } else if($headers['Module'] == 'get-staff')
        {
            return $this->getStaff($headers);
        } else if($headers['Module'] == 'save-salary-reg')
        {
            return $this->saveSalaryReg($headers);
        } else if($headers['Module'] == 'save-loan-reg')
        {
            return $this->saveLoanReg($headers);
        } else if($headers['Module'] == 'get-hr-reports')
        {
            return $this->getHrReports($headers);
        } else {
            $responce['status'] = 0;
            $responce['message'] = 'Something is wrong';
            $responce['data'] = '';
            return $responce;
        }
        
    }
    
    function getHrReports($headers){
        if((!$_POST['login_user']) && (($_POST['login_user'] == null)))
        {
            $responce['status'] = 0;
            $responce['message'] = 'login_user is required';
            $responce['data'] = '';
            return $responce;
        } else
        {
            $data = array();
            
            if($_POST['type'] == 'staff-reports')
            {
                $this->db->join('staff_photo', 'staff_entry.sid= staff_photo.sid');
                $shop =  $this->db->get('staff_entry');
                
                $data = $shop->result_array();
            }
            if($_POST['type'] == 'salary-reports')
            {
                $query11 = $this->db->query("select * from pay_advance");
                $data = $query11->result_array();
            }
            if($_POST['type'] == 'loan-reports')
            {
                $query11 = $this->db->query("select * from pay_loan");
                $data = $query11->result_array();
            }
            
            
            if(count($data) > 0)
            {
                $responce['status'] = 1;
                $responce['message'] = 'Hr reports';
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
    
    function getStaff($headers){
        if((!$_POST['login_user']) && (($_POST['login_user'] == null)))
        {
            $responce['status'] = 0;
            $responce['message'] = 'login_user is required';
            $responce['data'] = '';
            return $responce;
        } else
        {
            $query11 = $this->db->query("select * from staff_entry");
            $data = $query11->result_array();
            
            if(count($data) > 0)
            {
                $responce['status'] = 1;
                $responce['message'] = 'Staff List';
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
    
    function saveSalaryReg($headers){
        if((!$_POST['login_user']) && (($_POST['login_user'] == null)))
        {
            $responce['status'] = 0;
            $responce['message'] = 'login_user is required';
            $responce['data'] = '';
            return $responce;
        } else if($_POST['order_date'] == '.')
        {
            $responce['status'] = 0;
            $responce['message'] = 'Date is required';
            $responce['data'] = '';
            return $responce;
        } else if($_POST['customer_name'] == '.')
        {
            $responce['status'] = 0;
            $responce['message'] = 'Staff Name is required';
            $responce['data'] = '';
            return $responce;
        } else {
            
            // print_r($_POST);die();
            
            $this->db->order_by('sno', 'DESC');
            $shop =  $this->db->get('pay_advance');
            $item_no1 = $shop->result_array();
            $item_no = $item_no1?($item_no1[0]['sno'] + 1):1;
            
            $insertSalary = array(
                'sno'       =>  $item_no,
                'dat'       =>  $_POST['order_date'],
                'cid'       =>  $_POST['customer_id'],
                'cname'       =>  $_POST['customer_name'],
                'amount'       =>  $_POST['total_amt'],
                'remarks'       =>  $_POST['remark'],
                'last'       =>  date("Y/m/d h:i:sa"),
                'branch'       => $_POST['branch'],
            );
            
            $data =  $this->db->insert('pay_advance', $insertSalary);
            
            if($data)
            {
                $responce['status'] = 1;
                $responce['message'] = 'Submitted';
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
    
     function saveLoanReg($headers){
        if((!$_POST['login_user']) && (($_POST['login_user'] == null)))
        {
            $responce['status'] = 0;
            $responce['message'] = 'login_user is required';
            $responce['data'] = '';
            return $responce;
        } else if($_POST['order_date'] == '.')
        {
            $responce['status'] = 0;
            $responce['message'] = 'Date is required';
            $responce['data'] = '';
            return $responce;
        } else if($_POST['customer_name'] == '.')
        {
            $responce['status'] = 0;
            $responce['message'] = 'Staff Name is required';
            $responce['data'] = '';
            return $responce;
        } else {
            
            // print_r($_POST);die();
            
            $this->db->order_by('sno', 'DESC');
            $shop =  $this->db->get('pay_loan');
            $item_no1 = $shop->result_array();
            $item_no = $item_no1?($item_no1[0]['sno'] + 1):1;
            
            $insertSalary = array(
                'sno'       =>  $item_no,
                'dat'       =>  $_POST['order_date'],
                'cid'       =>  $_POST['customer_id'],
                'cname'       =>  $_POST['customer_name'],
                'tot'       =>  $_POST['total_amt'],
                'paid'       =>  $_POST['total_amt'],
                'remarks'       =>  $_POST['remark'],
                'status' => 'Active',
                'last'       =>  date("Y/m/d h:i:sa"),
                'branch'       => $_POST['branch'],
            );
            
            $data =  $this->db->insert('pay_loan', $insertSalary);
            
            if($data)
            {
                $responce['status'] = 1;
                $responce['message'] = 'Submitted';
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
    
    function saveStaff($headers){
        if((!$_POST['login_user']) && (($_POST['login_user'] == null)))
        {
            $responce['status'] = 0;
            $responce['message'] = 'login_user is required';
            $responce['data'] = '';
            return $responce;
        } else if($_POST['staffName'] == '.')
        {
            $responce['status'] = 0;
            $responce['message'] = 'Staff Name is required';
            $responce['data'] = '';
            return $responce;
        }  else {
            
            $this->db->where('sid',$_POST['staffId']);
            $checkStaff =  $this->db->get('staff_entry');
            $check = $checkStaff->result_array();
            
            $new_name = '';
            $imgdata = '';
            
            
            // print_r($_POST); die();
            
            $this->db->order_by('sno', 'DESC');
            $shop =  $this->db->get('staff_entry');
            $item_no1 = $shop->result_array();
            $item_no = $item_no1?($item_no1[0]['sno'] + 1):1;
            
            if((count($check) == 0) || ($_POST['type'] == 'alter') )
            {
            
            if(isset($_FILES['currentFile']))
            if($_FILES['currentFile'] != '.')
            {
                $config['upload_path'] = './uploads/';
                $config['allowed_types'] = 'gif|jpg|png';
                $config['max_size'] = '100';
                $config['max_width']  = '2024';
                $config['max_height']  = '2068';
                $config['overwrite'] = TRUE;
                $config['encrypt_name'] = FALSE;
                $config['remove_spaces'] = TRUE;
                $new_name = time().$_FILES["currentFile"]['name'];
                $config['file_name'] = $new_name;
                
                $type = pathinfo($new_name, PATHINFO_EXTENSION);
    
                if ( ! is_dir($config['upload_path']) ) die("THE UPLOAD DIRECTORY DOES NOT EXIST");
                $this->load->library('upload', $config);
                
                if ( ! $this->upload->do_upload('currentFile')) {
                    // print_r($this->upload->display_errors());
                    $responce['status'] = 0;
                    $responce['message'] = $this->upload->display_errors();
                    $responce['data'] = $this->upload->display_errors();
                    return $responce;
                } else {
                    //Get the image and convert into string
                    // $img = file_get_contents('http://erp.kuberalaxmijewellery.com/api/uploads/'.$new_name);
                      
                    // Encode the image string data into base64
                    $imgdata = base_url().'uploads/'.$new_name;
                      
                    // // Display the output
                    // echo $data;
                    $insertDataPic = array(
                        'sid'       => $item_no,
                        'photo'       => $imgdata,
                        'branch'       => $_POST['branch'],
                    );
                    
                    
                    if($_POST['branch'] == 'new')
                    {
                        
                        $data =  $this->db->insert('staff_photo', $insertDataPic);
                        
                        
                    } else {
                        
                        unset($insertDataPic["sid"]);
                        
                        $this->db->where('sid', $_POST['staffId']);
                        $data =  $this->db->update('staff_photo', $insertDataPic);
                    }
                }
                
                
            }
            
    
            


            
            
            
            
            
            $insertData = array(
                'sno'       => $item_no,
                'dat'       => $_POST['dat'],
                'tim'       => $_POST['tim'],
                'account'   => $_POST['department'],
                'desig'     => $_POST['designation'],
                'sid'       => $_POST['staffId'],
                'jdate'     => $_POST['dateOfJoin'],
                'sname'     => $_POST['staffName'],
                'fname'     => $_POST['fatherName'],
                'gender'    => $_POST['gender'],
                'bg'        => $_POST['bloodGrp'],
                'dob'       => $_POST['dateOfBirth'],
                'religion'  => $_POST['religion'],
                'nation'    => $_POST['nationality'],
                'add1'      => $_POST['address1'],
                'add2'      => $_POST['address2'],
                'add3'      => $_POST['address3'],
                'area'      => $_POST['area'],
                'pincode'   => $_POST['pincode'],
                'mobile'    => $_POST['mobile'],
                'phone'     => $_POST['phone'],
                'email'     => $_POST['email'],
                'mstatus'   => $_POST['marital'],
                'family'    => $_POST['familyDetails'],
                'edu'       => $_POST['educationDetails'],
                'exp'       => $_POST['experienceDetails'],
                'job'       => $_POST['jobDetails'],
                'langu'     => $_POST['language'],
                'adhaar'    => $_POST['adhaar'],
                'acno'      => $_POST['bank'],
                'remarks'   => $_POST['remarks'],
                'status'    => 'Active',
                'edate'     => date("Y/m/d h:i:sa"),
                'salary'    => $_POST['salary'],
                'branch'    => $_POST['branch'],
            );
            
            
            $msg = "";
            
            if($_POST['branch'] == 'new')
            {
                $data =  $this->db->insert('staff_entry', $insertData);
                
                $msg = 'Staff Submitted';
                
            } else {
                
                unset($insertData["sno"]);
                unset($insertData["sid"]);
                
                
                $this->db->where('sno', $_POST['entryNo']);
                $data =  $this->db->update('staff_entry', $insertData);

                
                $msg = 'Staff Updated';
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
            } else {
                $responce['status'] = 0;
                $responce['message'] = 'Already Exits';
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
            $last =  $this->db->get('staff_entry');
            $last_code = $last->result_array();
                
                
            $data['last_entry'] = $last_code?($last_code[0]['sno'] + 1):1;
            
            $data['staff_id'] = $last_code?($last_code[0]['sno'] + 1):1;
            

            if(count($data) > 0)
            {
                $responce['status'] = 1;
                $responce['message'] = 'Last Staff details';
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
    
    
    function getStaffDetails($headers){
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
            
            $this->db->where('sno', $_POST['sno']);
            $Stafflast =  $this->db->get('staff_entry');
            $data = $Stafflast->result_array();
            
            $this->db->where('sid', $_POST['sno']);
            $piclast =  $this->db->get('staff_photo');
            
            $pic = $piclast->result_array();
            

            if(count($data) > 0)
            {
                $data['pic'] = $pic;
                
                $responce['status'] = 1;
                $responce['message'] = 'Staff details';
                $responce['data'] = $data;
                return $responce;
            } else {
                $responce['status'] = 0;
                $responce['message'] = 'Staff not found';
                $responce['data'] = '';
                return $responce;
            }
            
        }
    }
    
    function getStaffDetailsId($headers){
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
            
            $this->db->where('sid', $_POST['sno']);
            $Stafflast =  $this->db->get('staff_entry');
            $data = $Stafflast->result_array();
            
            $this->db->where('sid', $_POST['sno']);
            $piclast =  $this->db->get('staff_photo');
            
            $pic = $piclast->result_array();
            

            if(count($data) > 0)
            {
                $data['pic'] = $pic;
                
                $responce['status'] = 1;
                $responce['message'] = 'Staff details';
                $responce['data'] = $data;
                return $responce;
            } else {
                $responce['status'] = 0;
                $responce['message'] = 'Staff not found';
                $responce['data'] = '';
                return $responce;
            }
            
        }
    }
}