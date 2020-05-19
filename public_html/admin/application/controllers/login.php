<?php

if (! defined('BASEPATH'))
    exit('No direct script access allowed');

class Login extends CI_Controller
{

    function index()
    {
        $data['title'] = '登录';
        $this->load->view('index_view', $data);
    }

    function log()
    {   
        // 设置验证规则
        $this->form_validation->set_rules('loginname', '用户名', 'required|min_length[3]');
        
        if (! $this->form_validation->run()) {
            $data['title'] = '登录';
            $data['error'] = '请输入正确的用户名和密码！';
            // $this->load->view('index_view', $data);
            redirect('../../index.php?error=1');
        } else {
            
            $check = $this->Users_model->check();
            if ($check) {
                $session['is_login'] = TRUE;
                $session['login_user'] = $check->loginname;
                $session['login_user_id'] = $check->user_id;
                $session['login_user_type'] = $check->user_type;
                $session['login_user_status'] = $check->status;//zxgadd
                $session['login_alias'] = $check->alias;
                $ip = $_SERVER['REMOTE_ADDR'];
                $table = 'loginlogs';
                $add_data = array();
                $add_data = array(
                    'user_id' => $check->user_id,
                    'login_time' => date('Y-m-d H:i:s'),
                    'ip' => $ip
                ); 
                $this->Users_model->add($table, $add_data);
                
                $this->session->set_userdata($session);
                
                //zxg add 冻结验证
                if($session['login_user_status']==1)//1冻结 0正常
                {
                    $data['title'] = '登录';
                    $data['error'] = '账号已冻结！'; 
                    redirect('../../index.php?error=2');
                    return;
                }
                
                if ($check->user_type == 'admin') {
                    redirect('member/user'); // 登录成功
                } else {
                    redirect('member/devices_now'); // 登录成功  //redirect('member/devices'); // 登录成功
                }
            } else {
                $data['title'] = '登录';
                $data['error'] = '登录失败！';
                // $this->load->view('index_view', $data);
                redirect('../../index.php?error=1');
            }
        }
	}
	
	function signup() {
		$data['title'] = '注册';
		$this->load->view('signup_view', $data);
	}
	
	function sign() {
		
		$this->form_validation->set_rules('loginname', 'Username', 'required|min_length[5]');
		$this->form_validation->set_rules('password', 'Password', 'required|min_length[5]');
		$this->form_validation->set_rules('password2', 'Password Config', 'required|min_length[5]|matches[passwd]');
		
		//设置验证规则
		if (!$this->form_validation->run()) {
			$data['title'] = '登录';
			$data['error'] = '登录失败了，请检查你的信息！';//此处应该是注册失败。
			//$this->load->view('signup_view', $data);
      redirect('../../index.php?error=1');
		} else {
		
			if ($this->Users_model->add()) {
				//redirect('login');
        redirect('../../index.php?error=1');
			} else {
				$this->signup();//这里也可以设置错误提示
			}
		}
	}
	
	function logout() {
		$this->session->sess_destroy();
		//redirect('login');
        redirect('../../');
	}
	
	
	
}