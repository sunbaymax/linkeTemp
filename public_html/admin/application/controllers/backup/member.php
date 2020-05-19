<?php
if (! defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Member extends CI_Controller
{

    function __construct()
    {
        parent::__construct();
        
        // 设置全局编码
        header('Content-Type: text/html; charset=utf-8');
        
        // 这些内容也可以在 autoload 文件中加载，但为了讲解方便放在
        $this->load->helper(array(
            'form',
            'html',
            'url'
        ));
        $this->load->model('Users_model');
        $this->load->database();
        $this->load->library(array(
            'table',
            'pagination'
        ));
        date_default_timezone_set('PRC');
        // 设置默认时区
    }

    function index()
    {
        if ($this->session->userdata('is_login')) {
            $data['title'] = '慧联科技';
            $data['right_tpl'] = 'default';
            $data['login_user'] = $this->session->userdata('login_user');
            $data['login_alias'] = $this->session->userdata('login_alias');
            $data['login_user_id'] = $this->session->userdata('login_user_id');
            $data['login_user_type'] = $this->session->userdata('login_user_type');
            $this->load->view('header', $data);
            $this->load->view('member', $data);
            $this->load->view('footer', $data);
        } else {
            $data['title'] = '登录';
            $this->load->view('index_view', $data);
        }
    }

    function update_password()
    {
        if ($this->session->userdata('is_login')) {
            $data['title'] = '修改密码';
            $data['login_user'] = $this->session->userdata('login_user');
            $data['login_alias'] = $this->session->userdata('login_alias');
            $data['login_user_id'] = $this->session->userdata('login_user_id');
            $data['login_user_type'] = $this->session->userdata('login_user_type');
            
            $this->form_validation->set_rules('old_password', '原密码','required|min_length[5]');
            $this->form_validation->set_rules('password', '新密码', 'required|min_length[5]');
            $this->form_validation->set_rules('password2', '确认密码', 'required|min_length[5]|matches[password]');
            if (! $this->form_validation->run()) {
                $this->load->view('header', $data);
                $this->load->view('update_password', $data);
                $this->load->view('footer', $data);
            } else {
                $table = 'users';
                $field = 'user_id';
                $value = $this->input->post('user_id');
                $query = $this->Users_model->show_where($table, $field, $value);
                if ($query->num_rows() > 0) {
                  $row = $query->row(); 
                  if(sha1($this->input->post('old_password')) == $row->password) {
                    $condition['key'] = 'user_id';
                    $condition['val'] = $this->input->post('user_id');
                    $update_data = array(
                        'password' => sha1($this->input->post('password'))
                    );
                    
                    if ($this->Users_model->update($table, $condition, $update_data)) {
                        $data['message'] = '密码修改成功,请重新登录';
                    } else {
                        $data['message'] = '密码修改失败';
                    }                    
                  } else {
                    $data['message'] = '原密码不正确';
                  }                                   
                } else {
                  $data['message'] = '原密码不正确';
                }                
                $this->load->view('header', $data);
                $this->load->view('update_password', $data);
                $this->load->view('footer', $data);
            }
        } else {
            $data['title'] = '登录';
            $this->load->view('index_view', $data);
        }
    }    

    //管理员登陆后界面_old_
    function user_old_($type = 'default', $id = NULL)
    {
        if ($this->session->userdata('is_login')) {
            $data['title'] = '欢迎管理员登陆';
            $data['login_user'] = $this->session->userdata('login_user');
            $data['login_alias'] = $this->session->userdata('login_alias');
            $data['login_user_id'] = $this->session->userdata('login_user_id');
            $data['login_user_type'] = $this->session->userdata('login_user_type');
            $data['right_tpl'] = 'user';
            // get setting value
            $table = 'users';
            if ($data['login_user_type'] == 'admin') {
                $field = 'user_id > ';
                $value = 0;
                $per_page = 20;
                $config['total_rows'] = $this->db->count_all('users');
                $config['per_page'] = $per_page;
                $config['num_links'] = 10;
                $config['first_link'] = '首页';
                $config['last_link'] = '尾页';
                $config['base_url'] = site_url('/member/user/');
                $this->pagination->initialize($config);
                $data['total_rows'] = $config['total_rows'];
                $data['pagination'] = $this->pagination->create_links();
                $sql = 'u.*';
                $table = 'users u';
                $join_table = array();
                $join_field = array();
                $join_type = array();
                $where = array(
                    'key' => 'u.user_id > ',
                    'value' => '0'
                );
                $order = array(
                    'field' => 'u.user_id',
                    'type' => 'desc'
                );
                if (is_numeric($type)) {
                    // this time, $type is page number
                    $query = $this->Users_model->show_join($table, $sql, $join_table, $join_field, $join_type, $where, $order, $per_page, $type);
                } else {
                    $query = $this->Users_model->show_join($table, $sql, $join_table, $join_field, $join_type, $where, $order, $per_page, 0);
                }
            } else {
                $field = 'loginname';
                $value = $data['login_user'];
                $query = $this->Users_model->show_where($table, $field, $value, 10, 0);
            }
            
            $data['query'] = $query;
            
            $this->load->view('header', $data);
            $this->load->view('user', $data);
            $this->load->view('footer', $data);
        } else {
            $data['title'] = '登录';
            $this->load->view('index_view', $data);
        }
    }

    //管理员登陆后界面
    function user($type = 'default', $id = NULL, $keyword = NULL , $keyword2 = NULL, $keyword3 = NULL) //好像不加不行 //$keyword3 = NULL  这些条件貌似不用加 zxg
    {
        if ($this->session->userdata('is_login')) {
            $data['title'] = '欢迎管理员登陆';
            $data['login_user'] = $this->session->userdata('login_user');
            $data['login_alias'] = $this->session->userdata('login_alias');
            $data['login_user_id'] = $this->session->userdata('login_user_id');
            $data['login_user_type'] = $this->session->userdata('login_user_type');
            $data['right_tpl'] = 'user';
            // get setting value
            $table = 'users';
             
            //where 组装
            $where = FALSE;
            //--
            $keyword = base64_decode($keyword);
            if(empty($keyword)) {
                $keyword = trim($this->input->post('keyword'));
            }
            if(empty($keyword) || $keyword === "登陆账号") {
                $keyword = 'default';
            }
            if($keyword != 'default') {
                $where = array(
                    'u.loginname like' => '%' . $keyword . '%'
                );
            }
            //--
            $keyword2 = base64_decode($keyword2);
            if(empty($keyword2)) {
                $keyword2 = trim($this->input->post('keyword2'));
            }
            if(empty($keyword2) || $keyword2 === "用户名称") {
                $keyword2 = 'default';
            }
            if($keyword2 != 'default') {
                $add_where = array(
                    'u.alias like' => '%' . $keyword2 . '%'
                );
                if($where !== FALSE)
                    $where = array_merge($where,$add_where);
                else
                    $where = $add_where;
            }
            //--
            $keyword3 = base64_decode($keyword3);
            if(empty($keyword3)) {
                $keyword3 = trim($this->input->post('keyword3'));
            }
            if(empty($keyword3) || $keyword3 === "") {
                $keyword3 = 'default';
            }
            if($keyword3 != 'default') {
                $add_where = array(
                    'u.status =' => '' . $keyword3 . ''
                );
                if($where !== FALSE)
                    $where = array_merge($where,$add_where);
                else
                    $where = $add_where;
            }
            //END组装------------------------------------------------------
            //获取命令代号
            $action = -1; //-1首次进入页面
            $submit1_run = $this->input->post('Submit_chaxun');
            $submit2_run = $this->input->post('Submit_del');  
            if($submit1_run=="刷新")
                $action=1; 
            else if($submit2_run=="删除所选")
                $action=2;
            
            //zxg删除补丁拦截
            if($action == 2)
            {
                $checkbox = $this->input->post('check');
                if (count($checkbox) > 0 && $checkbox[0] > 0) {
                    // check value, del this user
                    foreach ($checkbox as $key => $value) {
                        if ($value > 0) {
                            $table_del = 'users';
                            if ($this->Users_model->delete($table_del, 'user_id', $value)) {
                                $data['message'] = '用户删除成功';
                            } else {
                                $data['message'] = '用户删除失败';
                            }
                        }
                    }
                }
            }
                
            if ($data['login_user_type'] == 'admin') {
                $field = 'user_id > ';
                $value = 0;
                $per_page = 20;
                $config['total_rows'] = $this->db->count_all('users');
                $config['per_page'] = $per_page;
                $config['num_links'] = 10;
                $config['first_link'] = '首页';
                $config['last_link'] = '尾页';
                $config['base_url'] = site_url('/member/user/');
                $this->pagination->initialize($config);
                $data['total_rows'] = $config['total_rows'];
                $data['pagination'] = $this->pagination->create_links();
                $sql = 'u.*';
                $table = 'users u';
                $join_table = array();
                $join_field = array();
                $join_type = array();
                 
                //zxg 注释了
                //$add_where = array(
                //    'u.user_id > ' => '0'   //zxg 废条件...
                //);
                //if($where !== FALSE)
                //    $where = array_merge($where,$add_where);
                //else
                //    $where = $add_where;
                
                $order = array(
                    'field' => 'u.user_id',
                    'type' => 'desc'
                );
                if (is_numeric($type)) {
                    // this time, $type is page number
                    $query = $this->Users_model->show_join_zxg($table, $sql, $join_table, $join_field, $join_type, $where, $order, $per_page, $type);
                } else {
                    $query = $this->Users_model->show_join_zxg($table, $sql, $join_table, $join_field, $join_type, $where, $order, $per_page, 0);
                }
            } else {
                $field = 'loginname';
                $value = $data['login_user'];
                $query = $this->Users_model->show_where($table, $field, $value, 10, 0);
            }
    
            $data['query'] = $query;
    
            $this->load->view('header', $data);
            $this->load->view('user', $data);
            $this->load->view('footer', $data);
        } else {
            $data['title'] = '登录';
            $this->load->view('index_view', $data);
        }
    }
        
    function add_user($user_id = NULL,$action = NULL)  //zxg 添加 $action = NULL
    {
        if ($this->session->userdata('is_login')) {
            $data['title'] = '用户管理';
            $data['login_user'] = $this->session->userdata('login_user');
            $data['login_alias'] = $this->session->userdata('login_alias');
            $data['login_user_id'] = $this->session->userdata('login_user_id');
            $data['login_user_type'] = $this->session->userdata('login_user_type');
            $data['right_tpl'] = 'add_user';
            
            //确定命令zxg
            if($action == NULL)
                $action = $this->input->post('action');
            
            
            if ($action == 'add') {
                $this->form_validation->set_rules('loginname', '登陆账号', 'required|min_length[2]|max_length[20]');
                $this->form_validation->set_rules('password', '密码', 'required|min_length[6]|max_length[20]');
                $this->form_validation->set_rules('alias', '用户名称', 'required|min_length[2]|max_length[20]');
                if (! $this->form_validation->run()) {
                    // do nothing;
                } else {
                    // add value
                    $table = 'users';
                    $add_data = array();
                    $add_data = array(
                        'loginname' => trim($this->input->post('loginname')),
                        'password' => sha1($this->input->post('password')),
                        'alias' => trim($this->input->post('alias')),
                        'user_type' => trim($this->input->post('user_type')),
                        'contact' => trim($this->input->post('contact')),
                        'status' => trim($this->input->post('status')),
                        'message_alert' => trim($this->input->post('message_alert')),
                        'alert_phone1' => trim($this->input->post('alert_phone1')),
                        'alert_phone2' => trim($this->input->post('alert_phone2')),
                        'alert_phone3' => trim($this->input->post('alert_phone3')),
                        'alert_content' => trim($this->input->post('alert_content')),
                        'note' => trim($this->input->post('note')),
                        'add_time' => date('Y-m-d H:i:s')
                    );
                    if ($this->Users_model->check_exist($table, 'loginname', trim($this->input->post('loginname')))) {
                        $data['message'] = '用户 ' . $this->input->post('loginname') . ' 已经存在';
                    } else {
                        if ($this->Users_model->add($table, $add_data)) {
                            $data['message'] = '用户添加成功';
                            redirect('/member/user');
                            exit();
                        } else {
                            $data['message'] = '用户添加失败';
                        }
                    }
                }
            } elseif ($action == 'del') {
                $checkbox = $this->input->post('check');
                if (count($checkbox) > 0 && $checkbox[0] > 0) {
                    // check value, del this user
                    foreach ($checkbox as $key => $value) {
                        if ($value > 0) {
                            $table = 'users';
                            if ($this->Users_model->delete($table, 'user_id', $value)) {
                                $data['message'] = '用户删除成功';
                            } else {
                                $data['message'] = '用户删除失败';
                            }
                        }
                    }
                }
                redirect('/member/user');
                exit();
            } elseif (! empty($user_id) && empty($action)) {                
                if(substr($user_id, 0, 6) == 'rules_') {
                  //show rule data
                  $session_data = $this->session->all_userdata();
                  if ($session_data['login_user_type'] == 'admin') {
                      $sql = 'r.*';
                      $table = 'view_rules_mustalias r';
                      $join_table = array();
                      $join_field = array();
                      $join_type = array();
                      $where = FALSE;
                      $order = array(
                          'field' => 'r.host_code,r.rule_id',
                          'type' => 'desc'
                      );
                  } else {
                      $sql = 'count(*) as num';
                      $table = 'view_rules_mustalias r';
                      $join_table = array(
                          'hosts h',
                          'user_to_host u2h'
                      );
                      $join_field = array(
                          'r.host_code = h.host_code',
                          'h.host_id = u2h.host_id'
                      );
                      $join_type = array(
                          'inner join',
                          'inner join'
                      );
                      $where = array(
                          'key' => 'u2h.user_id',
                          'value' => substr($user_id, 6)
                      );
                      $order = FALSE;
                      $query = $this->Users_model->show_join($table, $sql, $join_table, $join_field, $join_type, $where);
                      $row = $query->row();
                      
                      $sql = 'r.*';
                      $order = array(
                          'field' => 'r.host_code,r.rule_id',
                          'type' => 'desc'
                      );
                  }
                  $query2 = $this->Users_model->show_join($table, $sql, $join_table, $join_field, $join_type, $where, $order, 20, 0);
                  //echo $this->db->last_query();
                  $data['query2'] = $query2;

                  $data['type'] = 'rule';
                  $value = substr($user_id, 6);

                } else {
                  $data['type'] = 'user';
                  $value = $user_id;
                }     
                $table = 'users';
                $field = 'user_id';
                $query = $this->Users_model->show_where($table, $field, $value, 1, 0);
                if ($query->num_rows() > 0) {
                    $row = $query->row();
                    $data['row'] = $row;
                }
            } elseif (! empty($user_id) && $action == 'update') {
                $type = $this->input->post('type');
                $data['type'] = $type;
                if($type == 'user') {
                  //user type                  
                  $this->form_validation->set_rules('loginname', '登陆账号', 'required|min_length[2]|max_length[20]');
                  $this->form_validation->set_rules('alias', '用户名称', 'required|min_length[2]|max_length[20]');
                  if (! $this->form_validation->run()) {
                      $table = 'users';
                      $field = 'user_id';
                      $value = $user_id;
                      $query = $this->Users_model->show_where($table, $field, $value, 1, 0);
                      if ($query->num_rows() > 0) {
                          $row = $query->row();
                          $data['row'] = $row;
                      }
                  } else {                    
                      $table = 'users';
                      $condition['key'] = 'user_id';
                      $condition['val'] = $user_id;
                      $alert_0 = $this->input->post('alert_0') == 'on' ? 1 : 0;
                      $alert_1 = $this->input->post('alert_1') == 'on' ? 1 : 0;
                      $alert_2 = $this->input->post('alert_2') == 'on' ? 1 : 0;
                      $alert_3 = $this->input->post('alert_3') == 'on' ? 1 : 0;
                      $alert_4 = $this->input->post('alert_4') == 'on' ? 1 : 0;
                      $alert_5 = $this->input->post('alert_5') == 'on' ? 1 : 0;
                      $alert_6 = $this->input->post('alert_6') == 'on' ? 1 : 0;
                      $alert_7 = $this->input->post('alert_7') == 'on' ? 1 : 0;
                      $alert_8 = $this->input->post('alert_8') == 'on' ? 1 : 0; 
                      $alert_content = '';
                      $alert_content .= $alert_0;
                      $alert_content .= $alert_1;
                      $alert_content .= $alert_2;
                      $alert_content .= $alert_3;
                      $alert_content .= $alert_4;
                      $alert_content .= $alert_5;
                      $alert_content .= $alert_6;
                      $alert_content .= $alert_7;
                      $alert_content .= $alert_8;
                      
                      if ($this->input->post('loginname') != $this->input->post('loginname2')) {
                          if ($this->Users_model->check_exist($table, 'loginname', trim($this->input->post('loginname')))) {
                              $data['message'] = '用户 ' . $this->input->post('loginname') . ' 已经存在';
                          } else {
                              $update_data = array(
                                  'loginname' => trim($this->input->post('loginname')),
                                  'alias' => trim($this->input->post('alias')),
                                  'user_type' => trim($this->input->post('user_type')),
                                  'contact' => trim($this->input->post('contact')),
                                  'status' => trim($this->input->post('status')),
                                  'message_alert' => trim($this->input->post('message_alert')),
                                  'alert_phone1' => trim($this->input->post('alert_phone1')),
                                  'alert_phone2' => trim($this->input->post('alert_phone2')),
                                  'alert_phone3' => trim($this->input->post('alert_phone3')),
                                  'alert_content' => $alert_content,
                                  'note' => trim($this->input->post('note')),
                                  'add_time' => trim($this->input->post('add_time'))
                              );
                              $password = $this->input->post('password');
                              if (! empty($password)) {
                                  $update_data['password'] = sha1($password);
                              }
                              if ($this->Users_model->update($table, $condition, $update_data)) {
                                  $data['message'] = '用户修改成功';
                              } else {
                                  $data['message'] = '用户修改失败';
                              }
                          }
                      } else {
                          $update_data = array(
                              'alias' => trim($this->input->post('alias')),
                              'user_type' => trim($this->input->post('user_type')),
                              'contact' => trim($this->input->post('contact')),
                              'status' => trim($this->input->post('status')),
                              'message_alert' => trim($this->input->post('message_alert')),
                              'alert_phone1' => trim($this->input->post('alert_phone1')),
                              'alert_phone2' => trim($this->input->post('alert_phone2')),
                              'alert_phone3' => trim($this->input->post('alert_phone3')),
                              'alert_content' => $alert_content,
                              'note' => trim($this->input->post('note')),
                              'add_time' => date('Y-m-d H:i:s')
                          );
                          $password = $this->input->post('password');
                          if (! empty($password)) {
                              $update_data['password'] = sha1($password);
                          }
                          if ($this->Users_model->update($table, $condition, $update_data)) {
                              $data['message'] = '用户修改成功';
                          } else {
                              $data['message'] = '用户修改失败';
                          }
                      }
                      
                      $table = 'users';
                      $field = 'user_id';
                      $value = $user_id;
                      $query = $this->Users_model->show_where($table, $field, $value, 1, 0);
                      if ($query->num_rows() > 0) {
                          $row = $query->row();
                          $data['row'] = $row;
                      }
                  }
                } else {
                  //rule type
                    $table = 'users';
                    $condition['key'] = 'user_id';
                    $condition['val'] = $user_id;
                    $alert_0 = $this->input->post('alert_0') == 'on' ? 1 : 0;
                    $alert_1 = $this->input->post('alert_1') == 'on' ? 1 : 0;
                    $alert_2 = $this->input->post('alert_2') == 'on' ? 1 : 0;
                    $alert_3 = $this->input->post('alert_3') == 'on' ? 1 : 0;
                    $alert_4 = $this->input->post('alert_4') == 'on' ? 1 : 0;
                    $alert_5 = $this->input->post('alert_5') == 'on' ? 1 : 0;
                    $alert_6 = $this->input->post('alert_6') == 'on' ? 1 : 0;
                    $alert_7 = $this->input->post('alert_7') == 'on' ? 1 : 0;
                    $alert_8 = $this->input->post('alert_8') == 'on' ? 1 : 0; 
                    $alert_content = '';
                    $alert_content .= $alert_0;
                    $alert_content .= $alert_1;
                    $alert_content .= $alert_2;
                    $alert_content .= $alert_3;
                    $alert_content .= $alert_4;
                    $alert_content .= $alert_5;
                    $alert_content .= $alert_6;
                    $alert_content .= $alert_7;
                    $alert_content .= $alert_8;
                    $update_data = array(
                        'message_alert' => trim($this->input->post('message_alert')),
                        'alert_phone1' => trim($this->input->post('alert_phone1')),
                        'alert_phone2' => trim($this->input->post('alert_phone2')),
                        'alert_phone3' => trim($this->input->post('alert_phone3')),
                        'alert_content' => $alert_content,
                        'note' => trim($this->input->post('note'))
                    );
                    if ($this->Users_model->update($table, $condition, $update_data)) {
                        $data['message'] = '我的报警信息修改成功.';
                    } else {
                        $data['message'] = '我的报警信息修改失败.';
                    }
                    $table = 'users';
                    $field = 'user_id';
                    $value = $user_id;
                    $query = $this->Users_model->show_where($table, $field, $value, 1, 0);
                    if ($query->num_rows() > 0) {
                        $row = $query->row();
                        $data['row'] = $row;
                    }

                    //show rule data
                    $session_data = $this->session->all_userdata();
                    if ($session_data['login_user_type'] == 'admin') {
                        $sql = 'r.*';
                        $table = 'view_rules_mustalias r';
                        $join_table = array();
                        $join_field = array();
                        $join_type = array();
                        $where = FALSE;
                        $order = array(
                            'field' => 'r.host_code,r.rule_id',
                            'type' => 'desc'
                        );
                    } else {
                        $sql = 'count(*) as num';
                        $table = 'view_rules_mustalias r';
                        $join_table = array(
                            'hosts h',
                            'user_to_host u2h'
                        );
                        $join_field = array(
                            'r.host_code = h.host_code',
                            'h.host_id = u2h.host_id'
                        );
                        $join_type = array(
                            'inner join',
                            'inner join'
                        );
                        $where = array(
                            'key' => 'u2h.user_id',
                            'value' => $user_id
                        );
                        $order = FALSE;
                        $query = $this->Users_model->show_join($table, $sql, $join_table, $join_field, $join_type, $where);
                        $row = $query->row();
                        
                        $sql = 'r.*';
                        $order = array(
                            'field' => 'r.host_code,r.rule_id',
                            'type' => 'desc'
                        );
                    }
                    $query2 = $this->Users_model->show_join($table, $sql, $join_table, $join_field, $join_type, $where, $order, 20, 0);
                    //echo $this->db->last_query();
                    $data['query2'] = $query2;
                }//end rule type
            }//end update
            
            
            if(empty($data['type'])) {
              $data['type'] = 'user';
            }
            $this->load->view('header', $data);
            $this->load->view('add_user', $data);
            $this->load->view('footer', $data);
        } else {
            $data['title'] = '登录';
            $this->load->view('index_view', $data);
        }
    }

 //我的报警规则设定
    function rulesAndPhoneChange()
    {  
        $user_id = NULL;//zxg
        if ($this->session->userdata('is_login')) {
            $data['title'] = '我的报警规则设定';
            $data['login_user'] = $this->session->userdata('login_user');
            $data['login_alias'] = $this->session->userdata('login_alias');
            $data['login_user_id'] = $this->session->userdata('login_user_id');
            $data['login_user_type'] = $this->session->userdata('login_user_type');
            $data['right_tpl'] = '???';
            //err $action = $this->input->post('action');
            //获取命令代号
            $action = $this->input->post('action_xiaoma'); //""首次进入页面  1查询 2确认
            //$action = -1; //-1首次进入页面  1查询 2确认
            //$submit1_run = $this->input->post('submit_chaxun');
            //$submit2_run = $this->input->post('Submit_queren');
            //if($submit1_run=="查询")
            //    $action=1;
            //if($submit2_run=="确认")
            //    $action=2;
            
            $user_id = $data['login_user_id'];//zxg
            //==================另加=========================================//
            //where 组装
            $where = FALSE;
            //--
            $keyword = ""; //base64_decode($keyword);
            if(empty($keyword)) {
                $keyword = trim($this->input->post('keyword'));
            }
            if(empty($keyword) || $keyword === "标签编号") {
                $keyword = 'default';
            }
            if($keyword != 'default') {
                $where = array(
                    'r.label_code like' => '%' . $keyword . '%'
                );
            }
            //--
            $keyword2 = ""; // base64_decode($keyword2);
            if(empty($keyword2)) {
                $keyword2 = trim($this->input->post('keyword2'));
            }
            if(empty($keyword2) || $keyword2 === "主机编号") {
                $keyword2 = 'default';
            }
            if($keyword2 != 'default') {
                $add_where = array(
                    'r.host_code like' => '%' . $keyword2 . '%'
                );
                if($where !== FALSE)
                    $where = array_merge($where,$add_where);
                else
                    $where = $add_where;
            }
            //--
            if($where === FALSE)
            {    
                //意思是让其查到所有内容
                //$where = array(
                //    'r.host_code != ' => 'nullnullnullnull'  //整个肯定差不到的条件zxg
                //);
                
                //意思是让其查不到内容
                //$where = array(
                //    'r.host_code like' => '%nullnullnullnull%'  //整个肯定差不到的条件zxg
                //);
            }
            //==================另加=========================================//
            if(!empty($user_id) && ($action == "1" || $action == "") )  
            {  
                //show rule data
                $session_data = $this->session->all_userdata();
                if ($session_data['login_user_type'] == 'user') { 
                    $sql = 'count(*) as num';
                    $table = 'view_rules_mustalias r'; 
                    $join_table = array(
                        'hosts h',
                        'user_to_host u2h'
                    );
                    $join_field = array(
                        'r.host_code = h.host_code',
                        'h.host_id = u2h.host_id'
                    );
                    $join_type = array(
                        'inner join',
                        'inner join'
                    );
                    //$where = array(
                    //    'key' => 'u2h.user_id',
                    //    'value' => $user_id
                    //);
                    if($where === FALSE){
                        $where = array(
                            'u2h.user_id =' =>  $user_id
                        );
                    }
                    else {
                        $whereTemp = array(
                            'u2h.user_id =' =>  $user_id
                        );
                        $where = array_merge($where,$whereTemp);
                    } 
                    $order = FALSE;
                    //$query = $this->Users_model->show_join($table, $sql, $join_table, $join_field, $join_type, $where);
                    $query = $this->Users_model->show_join_zxg($table, $sql, $join_table, $join_field, $join_type, $where);
                    $row = $query->row(); //先查出结果总行数

                    $sql = 'r.*';
                    $order = array(
                        'field' => 'r.host_code,r.rule_id',
                        'type' => 'desc'
                    ); 
                    
                    $query2 = $this->Users_model->show_join_zxg($table, $sql, $join_table, $join_field, $join_type, $where, $order, 120, 0);
                    //echo $this->db->last_query();
                    $data['query2'] = $query2;
     
                    $value = $user_id; 
              
                    $table = 'users';
                    $field = 'user_id';
                    $query3 = $this->Users_model->show_where($table, $field, $value, 1, 0);
                    if ($query3->num_rows() > 0) {
                        $row = $query3->row();
                        $data['row'] = $row;   //zxg  保存了用户实体行信息
                    }
                }  
            } elseif (! empty($user_id) && $action == '2') { 
                $table = 'users';
                $condition['key'] = 'user_id';
                $condition['val'] = $user_id;
                $alert_0 = $this->input->post('alert_0') == 'on' ? 1 : 0;
                $alert_1 = $this->input->post('alert_1') == 'on' ? 1 : 0;
                $alert_2 = $this->input->post('alert_2') == 'on' ? 1 : 0;
                $alert_3 = $this->input->post('alert_3') == 'on' ? 1 : 0;
                $alert_4 = $this->input->post('alert_4') == 'on' ? 1 : 0;
                $alert_5 = $this->input->post('alert_5') == 'on' ? 1 : 0;
                $alert_6 = $this->input->post('alert_6') == 'on' ? 1 : 0;
                $alert_7 = $this->input->post('alert_7') == 'on' ? 1 : 0;
                $alert_8 = $this->input->post('alert_8') == 'on' ? 1 : 0;
                $alert_content = '';
                $alert_content .= $alert_0;
                $alert_content .= $alert_1;
                $alert_content .= $alert_2;
                $alert_content .= $alert_3;
                $alert_content .= $alert_4;
                $alert_content .= $alert_5;
                $alert_content .= $alert_6;
                $alert_content .= $alert_7;
                $alert_content .= $alert_8;
                $update_data = array(
                    'message_alert' => trim($this->input->post('message_alert')),
                    'alert_phone1' => trim($this->input->post('alert_phone1')),
                    'alert_phone2' => trim($this->input->post('alert_phone2')),
                    'alert_phone3' => trim($this->input->post('alert_phone3')),
                    'alert_content' => $alert_content,
                    'note' => trim($this->input->post('note'))
                );
                if ($this->Users_model->update($table, $condition, $update_data)) {
                    $data['message'] = '我的报警信息修改成功..';
                } else {
                    $data['message'] = '我的报警信息修改失败..';
                }
                $table = 'users';
                $field = 'user_id';
                $value = $user_id;
                $query = $this->Users_model->show_where($table, $field, $value, 1, 0);
                if ($query->num_rows() > 0) {
                    $row = $query->row();
                    $data['row'] = $row;
                }

                //show rule data
                $session_data = $this->session->all_userdata();
                if ($session_data['login_user_type'] == 'user') { 
                    $sql = 'count(*) as num';
                    $table = 'view_rules_mustalias r';
                    $join_table = array(
                        'hosts h',
                        'user_to_host u2h'
                    );
                    $join_field = array(
                        'r.host_code = h.host_code',
                        'h.host_id = u2h.host_id'
                    );
                    $join_type = array(
                        'left join',
                        'left join'
                    );
                    $where = array(
                        'key' => 'u2h.user_id',
                        'value' => $user_id
                    );
                    $order = FALSE;
                    $query = $this->Users_model->show_join($table, $sql, $join_table, $join_field, $join_type, $where);
                    $row = $query->row();
					
                    $sql = 'r.*';
                    $order = array(
                        'field' => 'r.host_code,r.rule_id',
                        'type' => 'desc'
                    );
                }
					  $query2 = $this->Users_model->show_join($table, $sql, $join_table, $join_field, $join_type, $where, $order, 120, 0);
                //echo $this->db->last_query();
                $data['query2'] = $query2; 
            } 
            $this->load->view('header', $data);
            $this->load->view('rulesAndPhoneChange', $data);
            $this->load->view('footer', $data);
        } else {
            $data['title'] = '登录';
            $this->load->view('index_view', $data);
        }
    }
      
    //我的设备参数设定
    function setparm()
    {
        $user_id = NULL;//zxg
        if ($this->session->userdata('is_login')) {
            $data['title'] = '我的设备参数设定';
            $data['login_user'] = $this->session->userdata('login_user');
            $data['login_alias'] = $this->session->userdata('login_alias');
            $data['login_user_id'] = $this->session->userdata('login_user_id');
            $data['login_user_type'] = $this->session->userdata('login_user_type');
            $data['right_tpl'] = '???';
            $user_id = $data['login_user_id'];//zxg
            //获取命令代号
            $submit1_run = $this->input->post('Submit1');
            $submit2_run = $this->input->post('Submit2');
            $submit3_run = $this->input->post('Submit3');
            $submit4_run = $this->input->post('Submit4');
            $host_code_1 = $this->input->post('host_code_1');
            $host_code_2 = $this->input->post('host_code_2');
            $host_code_3 = $this->input->post('host_code_3');
            $lable_code_3 = $this->input->post('lable_code_3');
            $host_code_4 = $this->input->post('host_code_4');
            $lable_code_4 = $this->input->post('lable_code_4');
            $ticklong_4 = $this->input->post('ticklong_4');
            $action = -1;
            if($submit1_run=="指令下发")
                $action=1;
            if($submit2_run=="指令下发")
                $action=2;
            if($submit3_run=="指令下发")
                $action=3;
            if($submit4_run=="指令下发")
                $action=4;
            
            //指令下发
            if(!empty($user_id))
            { 
                //提示:$data['message'] = $action.'...';
                if($action==1) 
                {
                    //[主机参数查询]
                    if(empty($host_code_1)) 
                        $data['message'] = "错误:请填写主机号!"; 
                    else 
                    {
                        $execRNum = $this->Users_model->add_sendComToHost($action,$host_code_1,"",""); 
                        $data['message']="[主机参数查询]指令已下发，请[刷新]关注主机应答记录!";
                    }
                }
                if($action==2) 
                {
                    //[服务器报警规则设置至主机]
                    if(empty($host_code_2)) 
                        $data['message'] = "错误:请填写主机号!"; 
                    else 
                    {
                        $execRNum = $this->Users_model->add_sendComToHost($action,$host_code_2,"",""); 
                        $data['message']="[服务器报警规则设置至主机]指令已下发，请[刷新]关注主机应答记录!";
                    }
                }
                if($action==3) 
                {
                    //[标签采集间隔查询]
                    if(empty($host_code_3)||empty($lable_code_3)) 
                        $data['message'] = "错误:请填写主机号/标签号!"; 
                    else 
                    {
                        $execRNum = $this->Users_model->add_sendComToHost($action,$host_code_3,$lable_code_3,""); 
                        $data['message']="[标签采集间隔查询]指令已下发，请[刷新]关注主机应答记录!";
                    }
                }
                if($action==4) 
                {
                    //[标签采集间隔设置]
                    if(empty($host_code_4)||empty($lable_code_4)||empty($ticklong_4)) 
                        $data['message'] = "错误:请填写主机号/标签号/时间间隔!"; 
                    else 
                    {
                        $execRNum = $this->Users_model->add_sendComToHost($action,$host_code_4,$lable_code_4,$ticklong_4); 
                        $data['message']="[标签采集间隔设置]指令已下发，请[刷新]关注主机应答记录!";
                    }
                } 
            }
            //主机应答刷新
            if(!empty($user_id))
            {  
                //$table = 'zcomrcv';
                //$field = 'rcv_id';
                //$value = $user_id;
                //$query = $this->Users_model->show_where($table, $field, $value, 1, 0);
                //if ($query->num_rows() > 0) {
                //    $row = $query->row();
                //    $data['row'] = $row;
                //}
    
                //show rcv log data
                $session_data = $this->session->all_userdata();
                if ($session_data['login_user_type'] == 'user') {
                    $sql = 'count(*) as num';
                    $table = 'zcomrcv z';
                    $join_table = array(
                        'hosts h',
                        'user_to_host u2h'
                    );
                    $join_field = array(
                        'z.rcv_host_code = h.host_code',
                        'h.host_id = u2h.host_id'
                    );
                    $join_type = array(
                        'inner join',
                        'inner join'
                    );
                    $where = array(
                        'key' => 'u2h.user_id',
                        'value' => $user_id
                    );
                    ////$order = FALSE;
                    ////$query = $this->Users_model->show_join($table, $sql, $join_table, $join_field, $join_type, $where);
                    ////$row = $query->row();
    
                    $sql = 'z.*';
                    $order = array(
                        'field' => 'z.rcv_id',
                        'type' => 'desc'
                    );
                    $query2 = $this->Users_model->show_join($table, $sql, $join_table, $join_field, $join_type, $where, $order, 20, 0);
                    ////echo $this->db->last_query();
                    $data['query2'] = $query2;
                }
            }
            $this->load->view('header', $data);
            $this->load->view('setparm', $data);
            $this->load->view('footer', $data);
        } else {
            $data['title'] = '登录';
            $this->load->view('index_view', $data);
        }
    }
    
    //我的空气净化器设定
    function set_jinghua()
    {
        $user_id = NULL;
        if ($this->session->userdata('is_login')) {
            $data['title'] = '我的空气净化器';
            $data['login_user'] = $this->session->userdata('login_user');
            $data['login_alias'] = $this->session->userdata('login_alias');
            $data['login_user_id'] = $this->session->userdata('login_user_id');
            $data['login_user_type'] = $this->session->userdata('login_user_type');
            $data['right_tpl'] = '???';
            $user_id = $data['login_user_id'];//zxg
            //获取命令代号
            $submit1_run = $this->input->post('Submit1');
            $submit2_run = $this->input->post('Submit2');
           // $submit3_run = $this->input->post('Submit3');
           // $submit4_run = $this->input->post('Submit4');
            $host_code_1 = $this->input->post('host_code_1');
            $host_code_2 = $this->input->post('host_code_2');
            $lable_code_3 = $this->input->post('lable_code_3');
            $switch_code_2 = $this->input->post('switch_code_2');
            $speed_code_2 = $this->input->post('speed_code_2');
            $timer_code_2 = $this->input->post('timer_code_2');
            $mode_code_2 = $this->input->post('mode_code_2');
            $ticklong_4 = $this->input->post('ticklong_4');
            $action = -1;
            if($submit1_run=="指令下发")
                $action=1;
            if($submit2_run=="控制指令下发")
                $action=101;
            /* if($submit3_run=="指令下发")
                $action=3;
            if($submit4_run=="指令下发")
                $action=4; */
    
            //指令下发
            if(!empty($user_id))
            {
                //提示:$data['message'] = $action.'...';
                if($action==1)
                {
                    //[净化器参数查询]
                    if(empty($host_code_1))
                        $data['message'] = "错误:请填写主机号!";
                    else
                    {
                        $execRNum = $this->Users_model->add_sendComToHost($action,$host_code_1,"","");
                        $data['message']="[空气净化器参数查询]控制指令已下发，请[刷新]关注主机应答记录!";
                    }
                }
                if($action==101)
                {
                    //[空气净化器参数设置]
                    //if(empty($host_code_2)||empty($lable_code_3)||empty($switch_code_2)||empty($speed_code_2)||empty($timer_code_2)||empty($mode_code_2))
                    if(empty($host_code_2)||empty($lable_code_3)||empty($switch_code_2))
                        $data['message'] = "错误:请填写主机号/标签号/开关/转速/定时/模式!";
                    else
                    {
                        //$abcdee="开关:".$switch_code_2 .";转速:".$speed_code_2.";定时:".$timer_code_2.";模式:".$mode_code_2;
                        //$execRNum = $this->Users_model->add_sendComToHost2($action,$host_code_2,$lable_code_3,$abcdee);
                        $execRNum = $this->Users_model->add_sendComToHost($action,$host_code_2,$lable_code_3,$switch_code_2);
                        $data['message']="[空气净化器]控制指令已下发，请[刷新]关注空气净化器状态列表!";
                    }
                  
                }
              
            }
            //主机应答刷新
            if(!empty($user_id))
            {
                //$table = 'zcomrcv';
                //$field = 'rcv_id';
                //$value = $user_id;
                //$query = $this->Users_model->show_where($table, $field, $value, 1, 0);
                //if ($query->num_rows() > 0) {
                //    $row = $query->row();
                //    $data['row'] = $row;
                //}
    
                //show rcv log data
                $session_data = $this->session->all_userdata();
                if ($session_data['login_user_type'] == 'user') {
                    $sql = 'count(*) as num';
                    $table = 'view_purifier_mustkroom p';
                    //$table = 'zcomrcv z';
                    $join_table = array(
                        'hosts h',
                        'user_to_host u2h'
                    );
                    $join_field = array(
                        'p.host_code = h.host_code',
                        'h.host_id = u2h.host_id'
                    );
                   // $join_field = array(
                    //    'z.rcv_host_code = h.host_code',
                    //    'h.host_id = u2h.host_id'
                  //  );
                    $join_type = array(
                        'inner join',
                        'inner join'
                    );
                    $where = array(
                        'key' => 'u2h.user_id',
                        'value' => $user_id
                    );
                    ////$order = FALSE;
                    ////$query = $this->Users_model->show_join($table, $sql, $join_table, $join_field, $join_type, $where);
                    ////$row = $query->row();
    
                    $sql = 'p.*';
                    $order = array(
                        'field' => 'p.id',
                       'type' => 'desc'
                    );
                  //  $sql = 'z.*';
                   // $order = array(
                   //     'field' => 'z.rcv_id',
                   //     'type' => 'desc'
                  // );
                    $query2 = $this->Users_model->show_join($table, $sql, $join_table, $join_field, $join_type, $where, $order, 200, 0);
                    ////echo $this->db->last_query();
                    $data['query2'] = $query2;
                }
            }
            $this->load->view('header', $data);
            $this->load->view('set_jinghua', $data);
            $this->load->view('footer', $data);
        } else {
            $data['title'] = '登录';
            $this->load->view('index_view', $data);
        }
    }
    
    //管理员功能
    function logs($keyword = NULL, $page = 0)
    {
        if ($this->session->userdata('is_login')) {
            $data['title'] = '网站登录日志';
            $data['login_user'] = $this->session->userdata('login_user');
            $data['login_alias'] = $this->session->userdata('login_alias');
            $data['login_user_type'] = $this->session->userdata('login_user_type');
            $data['login_user_id'] = $this->session->userdata('login_user_id');
            $data['right_tpl'] = 'logs';

            $keyword = base64_decode($keyword);
            if(empty($keyword)) {
              $keyword = trim($this->input->post('keyword'));              
            }
            if(empty($keyword)) {
              $keyword = 'default';
            }
            // get setting value
            $table = 'loginlogs';
            if ($data['login_user_type'] == 'admin') {
                $per_page = 20;
                $sql = 'count(*) as num';
                $table = 'loginlogs l';
                $join_table = array(
                    'users u'
                );
                $join_field = array(
                    'l.user_id = u.user_id'
                );
                $join_type = array(
                    'INNER'
                );
                if($keyword != 'default') {
                  $where = array(
                    'key' => 'u.alias like',
                    'value' => '%' . $keyword . '%'
                  );                  
                } else {
                  $where = FALSE;
                }
                $query = $this->Users_model->show_join($table, $sql, $join_table, $join_field, $join_type, $where);
                $row = $query->row(); 
                $total_rows = $row->num;
                //echo $this->db->last_query();


                $config['total_rows'] = $total_rows;
                $config['per_page'] = $per_page;
                $config['num_links'] = 10;
                $config['first_link'] = '首页';
                $config['last_link'] = '尾页';
                $config['use_page_numbers'] = TRUE;
                $config['uri_segment'] = 4;
                $config['base_url'] = site_url('/member/logs/' . base64_encode($keyword) . '/');
                $this->pagination->initialize($config);
                $data['total_rows'] = $config['total_rows'];
                $data['pagination'] = $this->pagination->create_links();
                $current_page = ceil($page / $per_page) + 1;
                $data['current_page'] = $current_page;
                
                $sql = 'l.*, u.alias,u.loginname';
                $table = 'loginlogs l';
                $join_table = array(
                    'users u'
                );
                $join_field = array(
                    'l.user_id = u.user_id'
                );
                $join_type = array(
                    'INNER'
                );                
                
                $order = array(
                    'field' => 'l.log_id',
                    'type' => 'desc'
                );
                if (is_numeric($page)) {
                    // this time, $type is page number
                    $query = $this->Users_model->show_join($table, $sql, $join_table, $join_field, $join_type, $where, $order, $per_page, $page);
                } else {
                    $query = $this->Users_model->show_join($table, $sql, $join_table, $join_field, $join_type, $where, $order, $per_page, 0);
                }
                //echo $this->db->last_query();
            } else {
                $field = 'loginname';
                $value = $data['login_user'];
                $query = $this->Users_model->show_where($table, $field, $value, 10, 0);
            }
            
            $data['query'] = $query;
            
            $this->load->view('header', $data);
            $this->load->view('logs', $data);
            $this->load->view('footer', $data);
        } else {
            $data['title'] = '登录';
            $this->load->view('index_view', $data);
        }
    }

    function bjinfo($keyword = NULL , $keyword2 = NULL , $page = 0)
    {
        if ($this->session->userdata('is_login')) {
            $data['title'] = '我的警情';
            $data['login_user'] = $this->session->userdata('login_user');
            $data['login_alias'] = $this->session->userdata('login_alias');
            $data['login_user_type'] = $this->session->userdata('login_user_type');
            $data['login_user_id'] = $this->session->userdata('login_user_id');
            $data['right_tpl'] = 'view_zbjlogs_mustalias';
    
            //where 组装
            $where = FALSE;
            //--
            $keyword = base64_decode($keyword);
            if(empty($keyword)) {
                $keyword = trim($this->input->post('keyword'));
            }
            if(empty($keyword) || $keyword === "标签编号") {
                $keyword = 'default';
            }
            if($keyword != 'default') {
                $where = array(
                     'l.label_code like' => '%' . $keyword . '%'
                ); 
            } 
            //-- 
            $keyword2 = base64_decode($keyword2);
            if(empty($keyword2)) {
                $keyword2 = trim($this->input->post('keyword2'));
            }
            if(empty($keyword2) || $keyword2 === "主机编号") {
                $keyword2 = 'default';
            }
            if($keyword2 != 'default') {
                $add_where = array(
                     'l.host_code like' => '%' . $keyword2 . '%'
                ); 
                if($where !== FALSE)
                    $where = array_merge($where,$add_where);
                else
                    $where = $add_where;
            } 
            //--
            if($data['login_user_type'] == 'admin')
            {
                //null
            }
            else if($data['login_user_type'] == 'user')
            {
               $add_where = array(
                     'u2h.user_id' => $data['login_user_id']."" ,  //第二个条件用于权限筛选  //$session_data['login_user_id'] 
               );
               if($where !== FALSE)
                   $where = array_merge($where,$add_where);
               else
                   $where = $add_where;
            }
              
            // get setting value
            $table = 'view_zbjlogs_mustalias'; 
            if ($data['login_user_type'] == 'admin' || $data['login_user_type'] == 'user') //zxg
            {
                $per_page = 20;
                $sql = 'count(*) as num';
                $table = 'view_zbjlogs_mustalias l'; 
                $join_table = array(
                    'hosts h',
                    'user_to_host u2h'
                );
                $join_field = array(
                    'l.host_code = h.host_code',
                    'h.host_id = u2h.host_id'
                );
                $join_type = array(
                    'left join',
                    'left join'
                );

                $query = $this->Users_model->show_join_zxg($table, $sql, $join_table, $join_field, $join_type, $where);
                $row = $query->row();
                $total_rows = $row->num;
                //echo $this->db->last_query();
     
                $config['total_rows'] = $total_rows;
                $config['per_page'] = $per_page;
                $config['num_links'] = 10;
                $config['first_link'] = '首页';
                $config['last_link'] = '尾页';
                $config['use_page_numbers'] = TRUE;
                $config['uri_segment'] = 4;
                $config['base_url'] = site_url('/member/bjinfo/' . base64_encode($keyword) . '/' . base64_encode($keyword2) .'/');  //zxg 改为两个参数
                $this->pagination->initialize($config);
                $data['total_rows'] = $config['total_rows'];
                $data['pagination'] = $this->pagination->create_links();
                $current_page = ceil($page / $per_page) + 1;
                $data['current_page'] = $current_page; //zxg 页面对的
    
                if(1==1)
                {
                    $sql = 'l.*';
                    $table = 'view_zbjlogs_mustalias l'; 
                    $join_table = array(
                        'hosts h',
                        'user_to_host u2h'
                    );
                    $join_field = array(
                        'l.host_code = h.host_code',
                        'h.host_id = u2h.host_id'
                    );
                    $join_type = array(
                        'left join',
                        'left join'
                    );
    
                    $order = array(
                        'field' => 'l.log_id',
                        'type' => 'desc'
                    );
                    if (is_numeric($page)) {
                        // this time, $type is page number
                        $query = $this->Users_model->show_join_zxg($table, $sql, $join_table, $join_field, $join_type, $where, $order, $per_page, $page);
                    } else {
                        $query = $this->Users_model->show_join_zxg($table, $sql, $join_table, $join_field, $join_type, $where, $order, $per_page, 0);
                    }
                    //echo $this->db->last_query();
                }
            }
    
    
            /* zxg 不再区分是普通用户还是管理员
             * else {
             $field = 'host_code';//'host_code';
             $value = $data['login_user']; //'1';//$data['login_user'];
             $query = $this->Users_model->show_where($table, $field, $value, 10, 0);
             } */
    
            $data['query'] = $query;
    
            $this->load->view('header', $data);
            $this->load->view('bjinfo', $data);
            $this->load->view('footer', $data);
        } else {
            $data['title'] = '登录';
            $this->load->view('index_view', $data);
        }
    }
    
    function bjSMSSend($keyword = NULL, $page = 0)
    {
        if ($this->session->userdata('is_login')) {
            $data['title'] = '我的报警短信下发日志';
            $data['login_user'] = $this->session->userdata('login_user');
            $data['login_alias'] = $this->session->userdata('login_alias');
            $data['login_user_type'] = $this->session->userdata('login_user_type');
            $data['login_user_id'] = $this->session->userdata('login_user_id');
            $data['right_tpl'] = 'zbjsendlogs';
    
            //where 组装
            $where = FALSE;
            $keyword = base64_decode($keyword);
            if(empty($keyword)) {
                $keyword = trim($this->input->post('keyword'));
            }
            if(empty($keyword) || $keyword === "标签编号") {
                $keyword = 'default';
            }
            if($keyword != 'default') {
                $where = array(
                     'l.label_code like' => '%' . $keyword . '%'
                ); 
            } 
            if($data['login_user_type'] == 'admin')
            {
                //null
            }
            else if($data['login_user_type'] == 'user')
            {
               $add_where = array(
                     'u2h.user_id' => $data['login_user_id']."" ,  //第二个条件用于权限筛选  //$session_data['login_user_id'] 
               );
               if($where !== FALSE)
                   $where = array_merge($where,$add_where);
               else
                   $where = $add_where;
            }
              
            // get setting value
            $table = 'view_zbjsendlogs_mustalias';
            if ($data['login_user_type'] == 'admin' || $data['login_user_type'] == 'user') //zxg
            {
                $per_page = 20;
                $sql = 'count(*) as num';
                $table = 'view_zbjsendlogs_mustalias l';
                $join_table = array(
                    'hosts h',
                    'user_to_host u2h'
                );
                $join_field = array(
                    'l.host_code = h.host_code',
                    'h.host_id = u2h.host_id'
                );
                $join_type = array(
                    'left join',
                    'left join'
                );
                $query = $this->Users_model->show_join_zxg($table, $sql, $join_table, $join_field, $join_type, $where);
                $row = $query->row();
                $total_rows = $row->num;
                //echo $this->db->last_query();
     
                $config['total_rows'] = $total_rows;
                $config['per_page'] = $per_page;
                $config['num_links'] = 10;
                $config['first_link'] = '首页';
                $config['last_link'] = '尾页';
                $config['use_page_numbers'] = TRUE;
                $config['uri_segment'] = 4;
                $config['base_url'] = site_url('/member/bjSMSSend/' . base64_encode($keyword) . '/');
                $this->pagination->initialize($config);
                $data['total_rows'] = $config['total_rows'];
                $data['pagination'] = $this->pagination->create_links();
                $current_page = ceil($page / $per_page) + 1;
                $data['current_page'] = $current_page;
    
                if(1==1)
                {
                    $sql = 'l.*';
                    $table = 'view_zbjsendlogs_mustalias l';
                    $join_table = array(
                        'hosts h',
                        'user_to_host u2h'
                    );
                    $join_field = array(
                        'l.host_code = h.host_code',
                        'h.host_id = u2h.host_id'
                    );
                    $join_type = array(
                        'left join',
                        'left join'
                    );
    
                    $order = array(
                        'field' => 'l.log_id',
                        'type' => 'desc'
                    );
                    if (is_numeric($page)) {
                        // this time, $type is page number
                        $query = $this->Users_model->show_join_zxg($table, $sql, $join_table, $join_field, $join_type, $where, $order, $per_page, $page);
                    } else {
                        $query = $this->Users_model->show_join_zxg($table, $sql, $join_table, $join_field, $join_type, $where, $order, $per_page, 0);
                    }
                    //echo $this->db->last_query();
                }
            }
    
    
            /* zxg 不再区分是普通用户还是管理员
             * else {
             $field = 'host_code';//'host_code';
             $value = $data['login_user']; //'1';//$data['login_user'];
             $query = $this->Users_model->show_where($table, $field, $value, 10, 0);
             } */
    
            $data['query'] = $query;
    
            $this->load->view('header', $data);
            $this->load->view('bjSMSSend', $data);
            $this->load->view('footer', $data);
        } else {
            $data['title'] = '登录';
            $this->load->view('index_view', $data);
        }
    }
        
    function bjinfo_old_废($keyword = NULL, $page = 0)
    {
        if ($this->session->userdata('is_login')) {
            $data['title'] = '我的警情';
            $data['login_user'] = $this->session->userdata('login_user');
            $data['login_alias'] = $this->session->userdata('login_alias');
            $data['login_user_type'] = $this->session->userdata('login_user_type');
            $data['login_user_id'] = $this->session->userdata('login_user_id');
            $data['right_tpl'] = 'zbjlogs';
    
            $keyword = base64_decode($keyword);
            if(empty($keyword)) {
                $keyword = trim($this->input->post('keyword'));
            }
            if(empty($keyword)) {
                $keyword = 'default';
            }
            // get setting value
            $table = 'zbjlogs';
            if ($data['login_user_type'] == 'admin' || $data['login_user_type'] == 'user') //zxg
            {
                $per_page = 20;
                $sql = 'count(*) as num';
                $table = 'zbjlogs l';
                $join_table = array(
                    'labels u'
                );
                $join_field = array(
                    'l.host_code = u.host_code',
                    'l.label_code = u.label_code'
                );
                $join_type = array(
                    'left join'
                );
                if($keyword != 'default') {
                    $where = array(
                        'key' => 'l.label_code like',
                        'value' => '%' . $keyword . '%'
                    );
                } else {
                    $where = FALSE;
                }
                $query = $this->Users_model->show_join($table, $sql, $join_table, $join_field, $join_type, $where);
                $row = $query->row();
                $total_rows = $row->num;
                //echo $this->db->last_query();
    
    
                $config['total_rows'] = $total_rows;
                $config['per_page'] = $per_page;
                $config['num_links'] = 10;
                $config['first_link'] = '首页';
                $config['last_link'] = '尾页';
                $config['use_page_numbers'] = TRUE;
                $config['uri_segment'] = 4;
                $config['base_url'] = site_url('/member/bjinfo/' . base64_encode($keyword) . '/');
                $this->pagination->initialize($config);
                $data['total_rows'] = $config['total_rows'];
                $data['pagination'] = $this->pagination->create_links();
                $current_page = ceil($page / $per_page) + 1;
                $data['current_page'] = $current_page;
    
                if(1==1)
                {
                    $sql = 'l.*, u.label_alias';
                    $table = 'zbjlogs l';
                    $join_table = array(
                        'labels u'
                    );
                    $join_field = array(
                        'l.host_code = u.host_code',
                        'l.label_code = u.label_code'
                    );
                    $join_type = array(
                        'left join'
                    );
        
                    $order = array(
                        'field' => 'l.log_id',
                        'type' => 'desc'
                    );
                    if (is_numeric($page)) {
                        // this time, $type is page number
                        $query = $this->Users_model->show_join($table, $sql, $join_table, $join_field, $join_type, $where, $order, $per_page, $page);
                    } else {
                        $query = $this->Users_model->show_join($table, $sql, $join_table, $join_field, $join_type, $where, $order, $per_page, 0);
                    }
                    //echo $this->db->last_query();  
                }
            }
            
            
            /* zxg 不再区分是普通用户还是管理员
             * else {
                $field = 'host_code';//'host_code';
                $value = $data['login_user']; //'1';//$data['login_user'];
                $query = $this->Users_model->show_where($table, $field, $value, 20, 0);
            } */
    
            $data['query'] = $query;
    
            $this->load->view('header', $data);
            $this->load->view('bjinfo', $data);
            $this->load->view('footer', $data);
        } else {
            $data['title'] = '登录';
            $this->load->view('index_view', $data);
        }
    }  
    
    function user_host($type = 'default', $user_id = NULL)
    {
        if ($this->session->userdata('is_login')) {
            $data['title'] = $this->session->userdata('login_alias') . '下属主机列表';
            $data['login_user'] = $this->session->userdata('login_user');
            $data['login_alias'] = $this->session->userdata('login_alias');
            $data['login_user_id'] = $this->session->userdata('login_user_id');
            $data['login_user_type'] = $this->session->userdata('login_user_type');
            $data['right_tpl'] = 'user_host';
            $data['user_id'] = $user_id;
            // get setting value
            if ($type == 'add') {
                $checkbox = $this->input->post('check2');
                if (count($checkbox) > 0 && $checkbox[0] > 0) {
                    // check value, del this user
                    foreach ($checkbox as $key => $value) {
                        if ($value > 0) {
                            $table = 'user_to_host';
                            $add_data = array();
                            $add_data = array(
                                'user_id' => $user_id,
                                'host_id' => $value
                            );
                            if ($this->Users_model->check_exist($table, 'user_id', $user_id . "' AND host_id = '" . $value)) {
                                $data['message'] = '对应关系 ' . $user_id . '&&' . $value . ' 已经存在';
                            } else {
                                if ($this->Users_model->add($table, $add_data)) {
                                    $data['message'] = '用户主机对应关添加成功';
                                } else {
                                    $data['message'] = '用户主机对应关添加失败';
                                }
                            }
                        }
                    }
                }
                redirect('/member/user_host/default/' . $user_id);
                exit();
            } elseif ($type == 'del') {
                // $data['user_id'] = $this->input->post('user_id');
                $checkbox = $this->input->post('check');
                if (count($checkbox) > 0 && $checkbox[0] > 0) {
                    // check value, del this user
                    foreach ($checkbox as $key => $value) {
                        if ($value > 0) {
                            $table = 'user_to_host';
                            if ($this->Users_model->delete($table, 'id', $value)) {
                                $data['message'] = '用户主机对应关系删除成功';
                            } else {
                                $data['message'] = '用户主机对应关系删除失败';
                            }
                        }
                    }
                }
                redirect('/member/user_host/default/' . $user_id);
                exit();
            } else {
                $sql = 'u2h.*, h.host_code, h.host_alias';
                $table = 'user_to_host u2h';
                $join_table = array(
                    'hosts h'
                );
                $join_field = array(
                    'u2h.host_id = h.host_id'
                );
                $join_type = array(
                    'inner join'
                );
                $where = array(
                    'key' => 'u2h.user_id',
                    'value' => $user_id
                );
                $order = array(
                    'field' => 'u2h.id',
                    'type' => 'desc'
                );
                $query = $this->Users_model->show_join($table, $sql, $join_table, $join_field, $join_type, $where, $order, 99, 0);
                $data['query'] = $query;
                
                $table = 'hosts';
                $query2 = $this->Users_model->show($table);
                // echo $this->db->last_query();
                $data['query2'] = $query2;
                
                $this->load->view('header', $data);
                $this->load->view('user_host', $data);
                $this->load->view('footer', $data);
            }
        } else {
            $data['title'] = '登录';
            $this->load->view('index_view', $data);
        }
    }

    //管理员功能---普通用户的主机别名修改貌似也用的这个
    function hosts($type = 'default', $host_id = NULL, $keyword = NULL , $keyword2 = NULL)
    {
        if ($this->session->userdata('is_login')) {
            $data['title'] = '主机信息维护';
            $data['login_user'] = $this->session->userdata('login_user');
            $data['login_alias'] = $this->session->userdata('login_alias');
            $data['login_user_id'] = $this->session->userdata('login_user_id');
            $data['login_user_type'] = $this->session->userdata('login_user_type');
            $data['right_tpl'] = 'hosts';
            
            // get setting value
            if ($type == 'add') {
                $this->form_validation->set_rules('host_code', '主机编号', 'required|min_length[1]|max_length[20]');
                $this->form_validation->set_rules('host_alias', '主机别名', 'required|min_length[1]|max_length[20]');
                if (! $this->form_validation->run()) {
                    //
                } else {
                    // add value
                    $table = 'hosts';
                    $add_data = array();
                    $add_data = array(
                        'host_code' => trim($this->input->post('host_code')),
                        'host_alias' => trim($this->input->post('host_alias')),
                        'is_online' => trim($this->input->post('is_online')),
                        'params' => trim($this->input->post('params')),
                        'note' => trim($this->input->post('note')),
                        'add_time' => date('Y-m-d H:i:s')
                    );
                    if ($this->Users_model->check_exist($table, 'host_code', trim($this->input->post('host_code')))) {
                        $data['message'] = '主机编码 ' . $this->input->post('host_code') . ' 已经存在';
                    } else {
                        if ($this->Users_model->add($table, $add_data)) {
                            $data['message'] = '主机添加成功';
                            redirect('/member/hosts');
                            exit();
                        } else {
                            $data['message'] = '主机添加失败';
                        }
                    }
                }
                $this->load->view('header', $data);
                $this->load->view('add_host', $data);
                $this->load->view('footer', $data);
            } elseif ($type == 'delete') {
                $checkbox = $this->input->post('check');
                if (count($checkbox) > 0 && $checkbox[0] > 0) {
                    // check value, del this user
                    foreach ($checkbox as $key => $value) {
                        if ($value > 0) {
                            $table = 'hosts';
                            if ($this->Users_model->delete($table, 'host_id', $value)) {
                                $data['message'] = '主机删除成功';
                            } else {
                                $data['message'] = '主机删除失败';
                            }
                        }
                    }
                }
                redirect('/member/hosts');
                exit();
            } elseif ($type == 'edit') {
                if ($host_id < 0) {
                    redirect('/member/hosts');
                    exit();
                }
                $sql = 'h.*';
                $table = 'hosts h';
                $join_table = array();
                $join_field = array();
                $join_type = array();
                $where = array(
                    'key' => 'h.host_id',
                    'value' => $host_id
                );
                $order = FALSE;
                $query = $this->Users_model->show_join($table, $sql, $join_table, $join_field, $join_type, $where, $order);
                if ($query->num_rows() > 0) {
                    $row = $query->row();
                    $data['row'] = $row;
                }
                $this->load->view('header', $data);
                $this->load->view('add_host', $data);
                $this->load->view('footer', $data);
            } elseif ($type == 'update') {
                $this->form_validation->set_rules('host_code', '主机编号', 'required|min_length[1]|max_length[20]');
                $this->form_validation->set_rules('host_alias', '主机别名', 'required|min_length[1]|max_length[20]');
                if (! $this->form_validation->run()) {
                    $sql = 'h.*';
                    $table = 'hosts h';
                    $join_table = array();
                    $join_field = array();
                    $join_type = array();
                    $where = array(
                        'key' => 'h.host_id',
                        'value' => $host_id
                    );
                    $order = FALSE;
                    $query = $this->Users_model->show_join($table, $sql, $join_table, $join_field, $where, $order);
                    if ($query->num_rows() > 0) {
                        $row = $query->row();
                        $data['row'] = $row;
                    }
                    $this->load->view('header', $data);
                    $this->load->view('add_host', $data);
                    $this->load->view('footer', $data);
                } else {
                    $table = 'hosts';
                    $condition['key'] = 'host_id';
                    $condition['val'] = $host_id;
                    if ($this->input->post('host_code') != $this->input->post('host_code2')) {
                        if ($this->Users_model->check_exist($table, 'host_code', trim($this->input->post('host_code')))) {
                            $data['message'] = '主机编码 ' . $this->input->post('host_code') . ' 已经存在';
                        } else {
                            $update_data = array(
                                'host_code' => trim($this->input->post('host_code')),
                                'host_alias' => trim($this->input->post('host_alias')),
                                'is_online' => trim($this->input->post('is_online')),
                                'params' => trim($this->input->post('params')),
                                'note' => trim($this->input->post('note')),
                                'add_time' => trim($this->input->post('add_time'))
                            );
                            if ($this->Users_model->update($table, $condition, $update_data)) {
                                $data['message'] = '主机修改成功';
                                $table = 'labels';
                                $update_data = array();
                                $condition = array();
                                $condition['key'] = 'host_code';
                                $condition['val'] = $this->input->post('host_code2');
                                $update_data = array(
                                    'host_code' => trim($this->input->post('host_code'))
                                );
                                $this->Users_model->update($table, $condition, $update_data);
                            } else {
                                $data['message'] = '主机记录修改失败';
                            }
                        }
                    } else {
                        $update_data = array(
                            'host_alias' => trim($this->input->post('host_alias')),
                            'is_online' => trim($this->input->post('is_online')),
                            'params' => trim($this->input->post('params')),
                            'note' => trim($this->input->post('note')),
                            'add_time' => trim($this->input->post('add_time'))
                        );
                        if ($this->Users_model->update($table, $condition, $update_data)) {
                            $data['message'] = '主机修改成功';
                        } else {
                            $data['message'] = '主机记录修改失败';
                        }
                    }
                    redirect('/member/hosts');
                    exit();
                }
            } else {   
                
                //zxg 查询和删除都进此
                
                //这个应该是查询  包括管理员 和 普通用户
                $session_data = $this->session->all_userdata();
                if ($session_data['login_user_type'] == 'admin') {
                    $sql = 'h.*';
                    $table = 'hosts h';
                    $join_table = array();
                    $join_field = array();
                    $join_type = array();
                    ////$where = FALSE;
                    
                    //where 组装
                    $where = FALSE;
                    //--
                    $keyword = base64_decode($keyword);
                    if(empty($keyword)) {
                        $keyword = trim($this->input->post('keyword'));
                    }
                    if(empty($keyword) || $keyword === "主机别名") {
                        $keyword = 'default';
                    }
                    if($keyword != 'default') {
                        $where = array(
                            'h.host_alias like' => '%' . $keyword . '%'
                        );
                    }
                    //--
                    $keyword2 = base64_decode($keyword2);
                    if(empty($keyword2)) {
                        $keyword2 = trim($this->input->post('keyword2'));
                    }
                    if(empty($keyword2) || $keyword2 === "主机编号") {
                        $keyword2 = 'default';
                    }
                    if($keyword2 != 'default') {
                        $add_where = array(
                            'h.host_code like' => '%' . $keyword2 . '%'
                        );
                        if($where !== FALSE)
                            $where = array_merge($where,$add_where);
                        else
                            $where = $add_where;
                    }
                    //END组装------------------------------------------------------

                    //获取命令代号
                    $action = -1; //-1首次进入页面
                    $submit1_run = $this->input->post('Submit_chaxun');
                    $submit2_run = $this->input->post('Submit_del');
                    if($submit1_run=="刷新")
                        $action=1;
                    else if($submit2_run=="删除所选")
                        $action=2;
                    
                    //zxg删除补丁拦截
                    if($action == 2)
                    {
                        $checkbox = $this->input->post('check');
                        if (count($checkbox) > 0 && $checkbox[0] > 0) {
                            // check value, del this user
                            foreach ($checkbox as $key => $value) {
                                if ($value > 0) {
                                    $table_del = 'hosts';
                                    if ($this->Users_model->delete($table_del, 'host_id', $value)) {
                                        $data['message'] = '主机删除成功';
                                    } else {
                                        $data['message'] = '主机删除失败';
                                    }
                                }
                            }
                        }
                    }
                    
                    $order = array(
                        'field' => 'h.host_id',
                        'type' => 'desc'
                    );
                    $per_page = 20;
                    $config['total_rows'] = $this->db->count_all('hosts');
                } else {
                    $sql = 'count(*) as num';
                    $table = 'hosts h';
                    $join_table = array(
                        'user_to_host u2h'
                    );
                    $join_field = array(
                        'h.host_id = u2h.host_id'
                    );
                    $join_type = array(
                        'inner join'
                    );
                    //$where = array(
                    //    'key' => 'u2h.user_id',
                    //    'value' => $session_data['login_user_id']
                    //);
                    $where = array(
                        'u2h.user_id =' => $session_data['login_user_id']
                    );
                    $order = FALSE;
                    $query = $this->Users_model->show_join_zxg($table, $sql, $join_table, $join_field, $join_type, $where);
                    $row = $query->row();
                    $config['total_rows'] = $row->num;
                    
                    $sql = 'h.*';
                    $order = array(
                        'field' => 'h.host_id',
                        'type' => 'desc'
                    );
                    $per_page = 20;
                }
                $config['per_page'] = $per_page;
                $config['num_links'] = 10;
                $config['first_link'] = '首页';
                $config['last_link'] = '尾页';
                $config['base_url'] = site_url('/member/hosts/');
                $this->pagination->initialize($config);
                $data['total_rows'] = $config['total_rows'];
                $data['pagination'] = $this->pagination->create_links();
                if (is_numeric($type)) {
                    // this time, $type is page number
                    $query = $this->Users_model->show_join_zxg($table, $sql, $join_table, $join_field, $join_type, $where, $order, $per_page, $type);
                } else {
                    $query = $this->Users_model->show_join_zxg($table, $sql, $join_table, $join_field, $join_type, $where, $order, $per_page, 0);
                }
                // echo $this->db->last_query();
                $data['query'] = $query;
                $this->load->view('header', $data);
                $this->load->view('hosts', $data);
                $this->load->view('footer', $data);
            }
        } else {
            $data['title'] = '登录';
            $this->load->view('index_view', $data);
        }
    }

    //管理员功能  ---普通用户的标签别名修改貌似也用的这个
    function labels($type = 'default', $label_id = NULL, $keyword = NULL , $keyword2 = NULL)
    {
        if ($this->session->userdata('is_login')) {
            $data['title'] = '标签信息维护';
            $data['login_user'] = $this->session->userdata('login_user');
            $data['login_user_id'] = $this->session->userdata('login_user_id');
            $data['login_alias'] = $this->session->userdata('login_alias');
            $data['login_user_type'] = $this->session->userdata('login_user_type');
            $data['right_tpl'] = 'labels';
            $table = 'hosts';
            $query2 = $this->Users_model->show($table);
            $data['query2'] = $query2;
            
            // get setting value
            if ($type == 'add') {
                $this->form_validation->set_rules('host_code', '主机编码', 'required|min_length[1]|max_length[20]');
                $this->form_validation->set_rules('label_code', '标签编码', 'required|min_length[1]|max_length[20]');
                $this->form_validation->set_rules('label_alias', '标签别名', 'required|min_length[1]|max_length[20]');
                if (! $this->form_validation->run()) {
                    //
                } else {
                    // add value
                    $table = 'labels';
                    $add_data = array();
                    $add_data = array(
                        'host_code' => trim($this->input->post('host_code')),
                        'label_code' => trim($this->input->post('label_code')),
                        'label_alias' => trim($this->input->post('label_alias')),
                        'label_category' => trim($this->input->post('label_category')),
                        'label_desc' => trim($this->input->post('label_desc')),
                        'label_param' => trim($this->input->post('label_param')),
                        'note' => trim($this->input->post('note')),
                        'add_time' => date('Y-m-d H:i:s')
                    );
                    if ($this->Users_model->check_exist($table, 'host_code', trim($this->input->post('host_code')) . "' AND label_code = '" . trim($this->input->post('label_code')))) {
                        $data['message'] = '采集器编码' . $this->input->post('label_code') . ' 已经存在';
                    } else {
                        if ($this->Users_model->add($table, $add_data)) {
                            $data['message'] = '采集器添加成功';
                            redirect('/member/labels');
                            exit();
                        } else {
                            $data['message'] = '采集器添加失败';
                        }
                    }
                }
                $this->load->view('header', $data);
                $this->load->view('add_label', $data);
                $this->load->view('footer', $data);
            } elseif ($type == 'delete') {
                $checkbox = $this->input->post('check');
                if (count($checkbox) > 0 && $checkbox[0] > 0) {
                    // check value, del this user
                    foreach ($checkbox as $key => $value) {
                        if ($value > 0) {
                            $table = 'labels';
                            if ($this->Users_model->delete($table, 'label_id', $value)) {
                                $data['message'] = '标签删除成功';
                            } else {
                                $data['message'] = '标签删除失败';
                            }
                        }
                    }
                }
                redirect('/member/labels');
               exit();
            } elseif ($type == 'edit') {
                if ($label_id < 0) {
                    redirect('/member/labels');
                   exit();
                }
                $sql = 'l.*';
                $table = 'labels l';
                $join_table = array();
                $join_field = array();
                $join_type = array();
                $where = array(
                    'key' => 'l.label_id',
                    'value' => $label_id
                );
                $order = FALSE;
                $query = $this->Users_model->show_join($table, $sql, $join_table, $join_field, $join_type, $where, $order);
                if ($query->num_rows() > 0) {
                    $row = $query->row();
                    $data['row'] = $row;
                }
                $this->load->view('header', $data);
                $this->load->view('add_label', $data);
                $this->load->view('footer', $data);
            } elseif ($type == 'update') {
                $this->form_validation->set_rules('host_code', '主机编码', 'required|min_length[1]|max_length[20]');
                $this->form_validation->set_rules('label_code', '标签编码', 'required|min_length[1]|max_length[20]');
                $this->form_validation->set_rules('label_alias', '标签别名', 'required|min_length[1]|max_length[20]');
                if (! $this->form_validation->run()) {
                    //
                } else {
                    $table = 'labels';
                    $condition['key'] = 'label_id';
                    $condition['val'] = $label_id;
                    if ($this->input->post('host_code') == $this->input->post('host_code2') && $this->input->post('label_code') == $this->input->post('label_code2')) {
                        $update_data = array(
                            'label_alias' => trim($this->input->post('label_alias')),
                            'label_category' => trim($this->input->post('label_category')),
                            'label_desc' => trim($this->input->post('label_desc')),
                            'label_param' => trim($this->input->post('label_param')),
                            'note' => trim($this->input->post('note')),
                            'add_time' => trim($this->input->post('add_time'))
                        );
                        if ($this->Users_model->update($table, $condition, $update_data)) {
                            $data['message'] = '采集器修改成功.';
                        } else {
                            $data['message'] = '采集器修改失败.';
                        }
                    } else {
                        if ($this->Users_model->check_exist($table, 'host_code', trim($this->input->post('host_code')) . "' AND label_code = '" . trim($this->input->post('label_code')))) {
                            $data['message'] = '采集器编码' . $this->input->post('label_code') . ' 已经存在';
                        } else {
                            $update_data = array(
                                'host_code' => trim($this->input->post('host_code')),
                                'label_code' => trim($this->input->post('label_code')),
                                'label_alias' => trim($this->input->post('label_alias')),
                                'label_category' => trim($this->input->post('label_category')),
                                'label_desc' => trim($this->input->post('label_desc')),
                                'label_param' => trim($this->input->post('label_param')),
                                'note' => trim($this->input->post('note')),
                                'add_time' => date('Y-m-d H:i:s')
                            );
                            if ($this->Users_model->update($table, $condition, $update_data)) {
                                $data['message'] = '采集器修改成功..';
                            } else {
                                $data['message'] = '采集器修改失败..';
                            }
                        }
                    }
                }
                $sql = 'l.*';
                $table = 'labels l';
                $join_table = array();
                $join_field = array();
                $join_type = array();
                $where = array(
                    'key' => 'l.label_id',
                    'value' => $label_id
                );
                $order = FALSE;
                $query = $this->Users_model->show_join($table, $sql, $join_table, $join_field, $join_type, $where, $order);
                // echo $this->db->last_query();
                if ($query->num_rows() > 0) {
                    $row = $query->row();
                    $data['row'] = $row;
                }
                //$this->load->view('header', $data);
                //$this->load->view('add_label', $data);
                //$this->load->view('footer', $data);
                redirect('/member/labels');
            } else {  
                
                //zxg 查询和删除应该都在这儿
                
                //应该是管理员 和 普通用户 的标签查询
                $session_data = $this->session->all_userdata();
                if ($session_data['login_user_type'] == 'admin') {
                    $sql = 'l.*';
                    $table = 'labels l';
                    $join_table = array();
                    $join_field = array();
                    $join_type = array();
                    ////$where = FALSE;

                    //where 组装
                    $where = FALSE;
                    //--
                    $keyword = base64_decode($keyword);
                    if(empty($keyword)) {
                        $keyword = trim($this->input->post('keyword'));
                    }
                    if(empty($keyword) || $keyword === "标签编号") {
                        $keyword = 'default';
                    }
                    if($keyword != 'default') {
                        $where = array(
                            'l.label_code like' => '%' . $keyword . '%'
                        );
                    }
                    //--
                    $keyword2 = base64_decode($keyword2);
                    if(empty($keyword2)) {
                        $keyword2 = trim($this->input->post('keyword2'));
                    }
                    if(empty($keyword2) || $keyword2 === "归属主机编号") {
                        $keyword2 = 'default';
                    }
                    if($keyword2 != 'default') {
                        $add_where = array(
                            'l.host_code like' => '%' . $keyword2 . '%'
                        );
                        if($where !== FALSE)
                            $where = array_merge($where,$add_where);
                        else
                            $where = $add_where;
                    }
                    //--
                    if($where === FALSE)
                    { 
                        /* $where = array(
                            'l.host_code like' => '%nullnullnullnull%'  //整个肯定差不到的条件zxg
                        ); */
                    }
                    //END组装------------------------------------------------------
                    //获取命令代号
                    $action = -1; //-1首次进入页面
                    $submit1_run = $this->input->post('Submit_chaxun');
                    $submit2_run = $this->input->post('Submit_del');
                    if($submit1_run=="刷新")
                        $action=1;
                    else if($submit2_run=="删除所选")
                        $action=2;
                    
                    //zxg删除补丁拦截
                    if($action == 2)
                    {
                        $checkbox = $this->input->post('check');
                        if (count($checkbox) > 0 && $checkbox[0] > 0) {
                            // check value, del this user
                            foreach ($checkbox as $key => $value) {
                                if ($value > 0) {
                                    $table_del = 'labels';
                                    if ($this->Users_model->delete($table_del, 'label_id', $value)) {
                                        $data['message'] = '标签删除成功';
                                    } else {
                                        $data['message'] = '标签删除失败';
                                    }
                                }
                            }
                        }
                    }                    
                    
                    
                    $order = array(
                        'field' => 'l.host_code,l.label_id',
                        'type' => 'desc'
                    );
                    $per_page = 200;
                    $config['total_rows'] = $this->db->count_all('labels');
                } else {
                    $sql = 'count(*) as num';
                    $table = 'labels l';
                    $join_table = array(
                        'hosts h',
                        'user_to_host u2h'
                    );
                    $join_field = array(
                        'l.host_code = h.host_code',
                        'h.host_id = u2h.host_id'
                    );
                    $join_type = array(
                        'inner join',
                        'inner join'
                    );
                    //$where = array(
                    //    'key' => 'u2h.user_id',
                    //    'value' => $session_data['login_user_id']
                    //);
                    $where = array(
                        'u2h.user_id =' => $session_data['login_user_id']
                    );
                    $order = FALSE;
                    $query = $this->Users_model->show_join_zxg($table, $sql, $join_table, $join_field, $join_type, $where);
                    $row = $query->row();
                    $config['total_rows'] = $row->num;
                    
                    $sql = 'l.*';
                    $order = array(
                        'field' => 'l.host_code,l.label_id',
                        'type' => 'desc'
                    );
                    $per_page = 200;
                }
                
                $config['per_page'] = $per_page;
                $config['num_links'] = 10;
                $config['first_link'] = '首页';
                $config['last_link'] = '尾页';
                $config['base_url'] = site_url('/member/labels/');
                $this->pagination->initialize($config);
                $data['total_rows'] = $config['total_rows'];
                $data['pagination'] = $this->pagination->create_links();
                if (is_numeric($type)) {
                    // this time, $type is page number
                    $query = $this->Users_model->show_join_zxg($table, $sql, $join_table, $join_field, $join_type, $where, $order, $per_page, $type);
                } else {
                    $query = $this->Users_model->show_join_zxg($table, $sql, $join_table, $join_field, $join_type, $where, $order, $per_page, 0);
                }
                // echo $this->db->last_query();
                $data['query'] = $query;
                $this->load->view('header', $data);
                $this->load->view('labels', $data);
                $this->load->view('footer', $data);
            }
        } else {
            $data['title'] = '登录';
            $this->load->view('index_view', $data);
        }
    }

    //用户 和 管理员 功能
    function rules($type = 'default', $rule_id = NULL, $keyword = NULL , $keyword2 = NULL)
    {
        if ($this->session->userdata('is_login')) {
            $data['title'] = '我的报警规则设定';
            $data['login_user'] = $this->session->userdata('login_user');
            $data['login_alias'] = $this->session->userdata('login_alias');
            $data['login_user_id'] = $this->session->userdata('login_user_id');
            $data['login_user_type'] = $this->session->userdata('login_user_type');
            $data['right_tpl'] = 'labels';
            $table = 'hosts';
            $query2 = $this->Users_model->show($table);
            $data['query2'] = $query2;
            
            // get setting value
            if ($type == 'add') {
                $this->form_validation->set_rules('host_code', '主机编码', 'required|min_length[1]|max_length[20]');
                $this->form_validation->set_rules('label_code', '标签编码', 'required|min_length[1]|max_length[20]');
                if (! $this->form_validation->run()) {
                    //
                } else {
                    // add value
                    $table = 'rules';
                    $add_data = array();
                    $add_data = array(
                        'host_code' => trim($this->input->post('host_code')),
                        'label_code' => trim($this->input->post('label_code')),
                        'alert_1' => trim($this->input->post('alert_1')),
                        'min_1' => floatval($this->input->post('min_1')),
                        'max_1' => floatval($this->input->post('max_1')),
                        'alert_2' => trim($this->input->post('alert_2')),
                        'min_2' => floatval($this->input->post('min_2')),
                        'max_2' => floatval($this->input->post('max_2')),
                        'alert_3' => trim($this->input->post('alert_3')),
                        'min_3' => floatval($this->input->post('min_3')),
                        'max_3' => floatval($this->input->post('max_3')),
                        'alert_4' => trim($this->input->post('alert_4')),
                        'min_4' => floatval($this->input->post('min_4')),
                        'max_4' => floatval($this->input->post('max_4')),
                        'alert_5' => trim($this->input->post('alert_5')),
                        'min_5' => floatval($this->input->post('min_5')),
                        'max_5' => floatval($this->input->post('max_5')),
                        'alert_6' => trim($this->input->post('alert_6')),
                        'min_6' => floatval($this->input->post('min_6')),
                        'max_6' => floatval($this->input->post('max_6')),
                        'alert_7' => trim($this->input->post('alert_7')),
                        'min_7' => floatval($this->input->post('min_7')),
                        'max_7' => floatval($this->input->post('max_7')),
                        'alert_8' => trim($this->input->post('alert_8')),
                        'min_8' => floatval($this->input->post('min_8')),
                        'max_8' => floatval($this->input->post('max_8')),
                        'is_active' => trim($this->input->post('is_active')),
                        'active_timeout' => intval($this->input->post('active_timeout')),
                        'add_time' => date('Y-m-d H:i:s')
                    );
                    if ($this->Users_model->check_exist($table, 'host_code', trim($this->input->post('host_code')) . "' AND label_code = '" . trim($this->input->post('label_code')))) {
                        $data['message'] = '采集器编码' . $this->input->post('label_code') . ' 已经存在';
                    } else {
                        if ($this->Users_model->add($table, $add_data)) {
                            $data['message'] = '报警规则添加成功';
                            redirect('/member/rules');
                            exit();
                        } else {
                            $data['message'] = '报警规则添加失败';
                        }
                    }
                }
                $this->load->view('header', $data);
                $this->load->view('add_rule', $data);
                $this->load->view('footer', $data);
            } elseif ($type == 'delete') {
                $checkbox = $this->input->post('check');
                if (count($checkbox) > 0 && $checkbox[0] > 0) {
                    // check value, del this user
                    foreach ($checkbox as $key => $value) {
                        if ($value > 0) {
                            $table = 'rules';
                            if ($this->Users_model->delete($table, 'rule_id', $value)) {
                                $data['message'] = '报警规则删除成功';
                            } else {
                                $data['message'] = '报警规则删除失败';
                            }
                        }
                    }
                }
                redirect('/member/rules');
                exit();
            } elseif ($type == 'edit') { 
                if ($rule_id < 0) {
                    redirect('/member/rules');
                    exit();
                }
                $sql = 'r.*';
                $table = 'view_rules_mustalias r';
                $join_table = array();
                $join_field = array();
                $join_type = array();
                $where = array(
                    'key' => 'r.rule_id',
                    'value' => $rule_id
                );
                $order = FALSE;
                $query = $this->Users_model->show_join($table, $sql, $join_table, $join_field, $join_type, $where, $order);
                if ($query->num_rows() > 0) {
                    $row = $query->row();
                    $data['row'] = $row;
                }
                $this->load->view('header', $data);
                $this->load->view('add_rule', $data);
                $this->load->view('footer', $data);  
            } elseif ($type == 'update') {
                $this->form_validation->set_rules('host_code', '主机编码', 'required|min_length[1]|max_length[20]');
                $this->form_validation->set_rules('label_code', '采集器编码', 'required|min_length[1]|max_length[20]');
                if (! $this->form_validation->run()) {
                    //
                } else {
                    $table = 'rules';
                    $condition['key'] = 'rule_id';
                    $condition['val'] = $rule_id;
                    if ($this->input->post('host_code') == $this->input->post('host_code2') && $this->input->post('label_code') == $this->input->post('label_code2')) {
                        $update_data = array(
                            'alert_1' => trim($this->input->post('alert_1')),
                            'min_1' => floatval($this->input->post('min_1')),
                            'max_1' => floatval($this->input->post('max_1')),
                            'alert_2' => trim($this->input->post('alert_2')),
                            'min_2' => floatval($this->input->post('min_2')),
                            'max_2' => floatval($this->input->post('max_2')),
                            'alert_3' => trim($this->input->post('alert_3')),
                            'min_3' => floatval($this->input->post('min_3')),
                            'max_3' => floatval($this->input->post('max_3')),
                            'alert_4' => trim($this->input->post('alert_4')),
                            'min_4' => floatval($this->input->post('min_4')),
                            'max_4' => floatval($this->input->post('max_4')),
                            'alert_5' => trim($this->input->post('alert_5')),
                            'min_5' => floatval($this->input->post('min_5')),
                            'max_5' => floatval($this->input->post('max_5')),
                            'alert_6' => trim($this->input->post('alert_6')),
                            'min_6' => floatval($this->input->post('min_6')),
                            'max_6' => floatval($this->input->post('max_6')),
                            'alert_7' => trim($this->input->post('alert_7')),
                            'min_7' => floatval($this->input->post('min_7')),
                            'max_7' => floatval($this->input->post('max_7')),
                            'alert_8' => trim($this->input->post('alert_8')),
                            'min_8' => floatval($this->input->post('min_8')),
                            'max_8' => floatval($this->input->post('max_8')),
                            'is_active' => trim($this->input->post('is_active')),
                            'active_timeout' => intval($this->input->post('active_timeout')),
                            'add_time' => trim($this->input->post('add_time'))
                        );
                        if ($this->Users_model->update($table, $condition, $update_data)) {
                            $data['message'] = '报警规则修改成功...';
                        } else {
                            $data['message'] = '报警规则修改失败...';
                        }
                    } else {
                        //zxg  以下是什么意思  不明白 先注释了
                        /* 
                        if ($this->Users_model->check_exist($table, 'host_code', trim($this->input->post('host_code')) . "' AND label_code = '" . trim($this->input->post('label_code')))) {
                            $data['message'] = '采集器编码' . $this->input->post('label_code') . ' 已经存在';
                        } else {
                            $update_data = array(
                                'host_code' => trim($this->input->post('host_code')),
                                'label_code' => trim($this->input->post('label_code')),
                                'alert_1' => trim($this->input->post('alert_1')),
                                'min_1' => floatval($this->input->post('min_1')),
                                'max_1' => floatval($this->input->post('max_1')),
                                'alert_2' => trim($this->input->post('alert_2')),
                                'min_2' => floatval($this->input->post('min_2')),
                                'max_2' => floatval($this->input->post('max_2')),
                                'alert_3' => trim($this->input->post('alert_3')),
                                'min_3' => floatval($this->input->post('min_3')),
                                'max_3' => floatval($this->input->post('max_3')),
                                'alert_4' => trim($this->input->post('alert_4')),
                                'min_4' => floatval($this->input->post('min_4')),
                                'max_4' => floatval($this->input->post('max_4')),
                                'alert_5' => trim($this->input->post('alert_5')),
                                'min_5' => floatval($this->input->post('min_5')),
                                'max_5' => floatval($this->input->post('max_5')),
                                'alert_6' => trim($this->input->post('alert_6')),
                                'min_6' => floatval($this->input->post('min_6')),
                                'max_6' => floatval($this->input->post('max_6')),
                                'alert_7' => trim($this->input->post('alert_7')),
                                'min_7' => floatval($this->input->post('min_7')),
                                'max_7' => floatval($this->input->post('max_7')),
                                'alert_8' => trim($this->input->post('alert_8')),
                                'min_8' => floatval($this->input->post('min_8')),
                                'max_8' => floatval($this->input->post('max_8')),
                                'is_active' => trim($this->input->post('is_active')),
                                'active_timeout' => intval($this->input->post('active_timeout')),
                                'add_time' => trim($this->input->post('add_time'))
                            );
                            if ($this->Users_model->update($table, $condition, $update_data)) {
                                $data['message'] = '报警规则修改成功....';
                            } else {
                                $data['message'] = '报警规则修改失败....';
                            }
                        } 
                        */
                    }
                }
                $sql = 'r.*';
                $table = 'view_rules_mustalias r';
                $join_table = array();
                $join_field = array();
                $join_type = array();
                $where = array(
                    'key' => 'r.rule_id',
                    'value' => $rule_id
                );
                $order = FALSE;
                $query = $this->Users_model->show_join($table, $sql, $join_table, $join_field, $join_type, $where, $order);
                // echo $this->db->last_query();
                if ($query->num_rows() > 0) {
                    $row = $query->row();
                    $data['row'] = $row;
                }
                $this->load->view('header', $data);
                $this->load->view('add_rule', $data);
                $this->load->view('footer', $data);
            } else { //应该是管理员 和 普通用户 的查询
                $session_data = $this->session->all_userdata();
                if ($session_data['login_user_type'] == 'admin') {
                    $sql = 'r.*';
                    $table = 'view_rules_mustalias r';
                    $join_table = array();
                    $join_field = array();
                    $join_type = array();
                    ////$where = FALSE;
                    
                    //where 组装
                    $where = FALSE;
                    //--
                    $keyword = base64_decode($keyword);
                    if(empty($keyword)) {
                        $keyword = trim($this->input->post('keyword'));
                    }
                    if(empty($keyword) || $keyword === "标签编号") {
                        $keyword = 'default';
                    }
                    if($keyword != 'default') {
                        $where = array(
                            'r.label_code like' => '%' . $keyword . '%'
                        );
                    }
                    //--
                    $keyword2 = base64_decode($keyword2);
                    if(empty($keyword2)) {
                        $keyword2 = trim($this->input->post('keyword2'));
                    }
                    if(empty($keyword2) || $keyword2 === "主机编号") {
                        $keyword2 = 'default';
                    }
                    if($keyword2 != 'default') {
                        $add_where = array(
                            'r.host_code like' => '%' . $keyword2 . '%'
                        );
                        if($where !== FALSE)
                            $where = array_merge($where,$add_where);
                        else
                            $where = $add_where;
                    }
                    //END组装------------------------------------------------------
                    
                    
                    $order = array(
                        'field' => 'r.host_code,r.rule_id',
                        'type' => 'desc'
                    );
                    $per_page = 20;
                    $config['total_rows'] = $this->db->count_all('rules');
                } else {
                    $sql = 'count(*) as num';
                    $table = 'view_rules_mustalias r';
                    $join_table = array(
                        'hosts h',
                        'user_to_host u2h'
                    );
                    $join_field = array(
                        'r.host_code = h.host_code',
                        'h.host_id = u2h.host_id'
                    );
                    $join_type = array(
                        'inner join',
                        'inner join'
                    );
                    $where = array(
                        'key' => 'u2h.user_id',
                        'value' => $session_data['login_user_id']
                    );
                    $order = FALSE;
                    $query = $this->Users_model->show_join($table, $sql, $join_table, $join_field, $join_type, $where);
                    $row = $query->row();
                    $config['total_rows'] = $row->num;
                    
                    $sql = 'r.*';
                    $order = array(
                        'field' => 'r.host_code,r.rule_id',
                        'type' => 'desc'
                    );
                    $per_page = 20;
                }
                $config['per_page'] = $per_page;
                $config['num_links'] = 10;
                $config['first_link'] = '首页';
                $config['last_link'] = '尾页';
                $config['base_url'] = site_url('/member/rules/');
                $this->pagination->initialize($config);
                $data['total_rows'] = $config['total_rows'];
                $data['pagination'] = $this->pagination->create_links();
                if (is_numeric($type)) {
                    // this time, $type is page number
                    $query = $this->Users_model->show_join_zxg($table, $sql, $join_table, $join_field, $join_type, $where, $order, $per_page, $type);
                } else {
                    $query = $this->Users_model->show_join_zxg($table, $sql, $join_table, $join_field, $join_type, $where, $order, $per_page, 0);
                }
                // echo $this->db->last_query();
                $data['query'] = $query;
                $this->load->view('header', $data);
                $this->load->view('rules', $data);
                $this->load->view('footer', $data);
            }
        } else {
            $data['title'] = '登录';
            $this->load->view('index_view', $data);
        }
    }

    //普通用户登陆后界面
    function devices($type = 'default')
    {
        if ($this->session->userdata('is_login')) {
            $data['title'] = '我的设备';
            $data['login_user'] = $this->session->userdata('login_user');
            $data['login_alias'] = $this->session->userdata('login_alias');
            $data['login_user_id'] = $this->session->userdata('login_user_id');
            $data['login_user_type'] = $this->session->userdata('login_user_type');
            $data['login_status'] = $this->session->userdata('login_user_status');//zxg 1冻结 0正常 
            $data['right_tpl'] = 'hosts';
            $session_data = $this->session->all_userdata();
            
            // get setting value
            if ($type == 'json') {   //json还不知道哪进入 zxg[标记]
                if ($session_data['login_user_type'] != 'admin') {
                    $sql = 'h.*';
                    $table = 'hosts h';
                    $join_table = array(
                        'user_to_host u2h'
                    );
                    $join_field = array(
                        'h.host_id = u2h.host_id'
                    );
                    $join_type = array(
                        'inner join'
                    );
                    $where = array(
                        'key' => 'u2h.user_id',
                        'value' => $session_data['login_user_id']
                    );
                    $order = array(
                        'field' => 'h.host_id',
                        'type' => 'asc'
                    );
                    $per_page = 100;
                }
                $query = $this->Users_model->show_join($table, $sql, $join_table, $join_field, $join_type, $where, $order, $per_page, 0);
                $devices = array();
                foreach ($query->result() as $row) {
                    $tmp = array();
                    $tmp['id'] = - 1;
                    $tmp['host_code']=$row->host_code;
                    $tmp['host_alias']=$row->host_alias;
                    if ($row->is_online == 1) {
                        $desc = '(在线)';
                    } else {
                        $desc = '(离线)';
                    }
                    if (! empty($row->host_alias)) {
                        $tmp['text'] = $row->host_alias . '_' . $row->host_code . $desc;
                    } else {
                        $tmp['text'] = $row->host_code . $desc;
                    }
                    // get labels
                    $sql = 'l.*';
                    $table = 'labels l';
                    $join_table = array(
                        'hosts h'
                    );
                    $join_field = array(
                        'h.host_code = l.host_code'
                    );
                    $join_type = array(
                        'inner join'
                    );
                    $where = array(
                        'key' => 'l.host_code',
                        'value' => $row->host_code
                    );
                    $order = array(
                        'field' => 'l.label_code',
                        'type' => 'asc'
                    );
                    $per_page = 100;
                    $query2 = $this->Users_model->show_join($table, $sql, $join_table, $join_field, $join_type, $where, $order, $per_page, 0);
                    $children = array();
                    foreach ($query2->result() as $row2) {
                        $tmp2 = array();
                        $tmp2['id'] = $row2->label_id;
                        $tmp2['label_category'] = $row2->label_category;
                        if (! empty($row2->label_alias)) {
                            $tmp2['text'] = $row2->label_alias;
                        } else {
                            $tmp2['text'] = $row2->label_code;
                        }
                        $children[] = $tmp2;
                    }
                    $tmp['children'] = $children;
                    $devices[] = $tmp;
                }
                echo json_encode($devices);
            } else if ($type == 'chart') {
                try{
                    $begin_date = trim($this->input->post('begin_date'));
                    $end_date = trim($this->input->post('end_date'));
                    /* zxg
                      if($begin_date == $end_date && ! empty($begin_date)) {
                      $begin_date = $begin_date . ' 00:00:00';
                      $end_date = $end_date . ' 23:59:59';
                    } */
                    $start_date = strtotime($begin_date);
                    $end_date = strtotime($end_date);
                    $labels = trim($this->input->post('labels'));
                    $show_type = $this->input->post('show_type') == 'true' ? 1 : 0;
                    $order_type = $this->input->post('order_type') == 'true' ? 'desc' : 'asc';
                    $alert_1 = $this->input->post('alert_1') == 'true' ? 1 : 0;
                    $alert_2 = $this->input->post('alert_2') == 'true' ? 1 : 0;
                    $alert_3 = $this->input->post('alert_3') == 'true' ? 1 : 0;
                    $alert_4 = $this->input->post('alert_4') == 'true' ? 1 : 0;
                    $alert_5 = $this->input->post('alert_5') == 'true' ? 1 : 0;
                    $alert_6 = $this->input->post('alert_6') == 'true' ? 1 : 0;
                    $alert_7 = $this->input->post('alert_7') == 'true' ? 1 : 0;
                    $alert_8 = $this->input->post('alert_8') == 'true' ? 1 : 0;
                    if (1 == $show_type) {
                        $total_display = 30;
                    } else {
                        $total_display = 200;
                    }
                    $labels = preg_replace('/,{2,}/', ',', $labels);
                    if (substr($labels, 0, 1) == ',') {
                        $labels = substr($labels, 1);
                    }
                    if (! empty($labels)) {
                        $sql = 'SELECT l.*,h.host_alias FROM hosts h INNER JOIN labels l ON l.host_code = h.host_code WHERE l.label_id in (' . $labels . ')';
                        $query = $this->Users_model->query($sql);
                        $devices = array();
                        $y_text = array();
                        $colors = array(
                            'value1' => '#ff0000',
                            'value2' => '#0000FF',
                            'value3' => '#FFFF00',
                            'value4' => '#00FF00',
                            'value5' => '#00FFFF',
                            'value6' => '#FF7F00',
                            'value7' => '#871F78',
                            'value8' => '#cccccc'
                        );
                        foreach ($query->result() as $row) {
                            $host_code = strtolower($row->host_code);
                            $label_code = strtolower($row->label_code);
                            $table = 'zhistorydata_' . $host_code . '_' . $label_code;
                            $sql = 'SELECT * FROM ' . $table;
                            if (! empty($start_date) && ! empty($end_date) && $end_date >= $start_date) {
                                $sql .= ' WHERE UNIX_TIMESTAMP(label_time) BETWEEN ' . $start_date . ' AND ' . $end_date;
                            }
                            // $sql .= ' ORDER BY UNIX_TIMESTAMP(add_time) ' . $order_type;
                            //zxg $sql .= ' LIMIT 200';
                            $sql .= ' order by z_id asc '; //zxg 
                            $query2 = $this->Users_model->query($sql);
                            //echo $this->db->last_query();
                            $device = array();
                            $code = $host_code . '_' . $label_code . '_value1';
                            $$code = array();
                            $code = $host_code . '_' . $label_code . '_value2';
                            $$code = array();
                            $code = $host_code . '_' . $label_code . '_value3';
                            $$code = array();
                            $code = $host_code . '_' . $label_code . '_value4';
                            $$code = array();
                            $code = $host_code . '_' . $label_code . '_value5';
                            $$code = array();
                            $code = $host_code . '_' . $label_code . '_value6';
                            $$code = array();
                            $code = $host_code . '_' . $label_code . '_value7';
                            $$code = array();
                            $code = $host_code . '_' . $label_code . '_value8';
                            $$code = array();
                            $x_text = array();
                            $num = $query2->num_rows();
                            if ($num < $total_display && $num > 0) {
                                $total_display = $num;
                            }
                            // echo 'num='.$num . '<br>';
                            // echo 'total='.$total_display . '<br>';
                            $i = 0;
                            $j = 0;
                            $step = floor($num / $total_display);
                            $rowslength=$num;//zxg
                            foreach ($query2->result() as $row2) {
                                if ($i == $j  || $i == $rowslength-1) {
                                    if ($alert_1 == 1) {
                                        $code = $host_code . '_' . $label_code . '_value1';
                                        array_push($$code, (float) $row2->value_1);
                                    }
                                    if ($alert_2 == 1) {
                                        $code = $host_code . '_' . $label_code . '_value2';
                                        array_push($$code, (float) $row2->value_2);
                                    }
                                    if ($alert_3 == 1) {
                                        $code = $host_code . '_' . $label_code . '_value3';
                                        array_push($$code, (float) $row2->value_3);
                                    }
                                    if ($alert_4 == 1) {
                                        $code = $host_code . '_' . $label_code . '_value4';
                                        array_push($$code, (float) $row2->value_4);
                                    }
                                    if ($alert_5 == 1) {
                                        $code = $host_code . '_' . $label_code . '_value5';
                                        array_push($$code, (float) $row2->value_5);
                                    }
                                    if ($alert_6 == 1) {
                                        $code = $host_code . '_' . $label_code . '_value6';
                                        array_push($$code, (float) $row2->value_6);
                                    }
                                    if ($alert_7 == 1) {
                                        $code = $host_code . '_' . $label_code . '_value7';
                                        array_push($$code, (float) $row2->value_7);
                                    }
                                    if ($alert_8 == 1) {
                                        $code = $host_code . '_' . $label_code . '_value8';
                                        array_push($$code, (float) $row2->value_8);
                                    }
                                    array_push($x_text, date('Y-m-d H:i:s', strtotime($row2->label_time)));
                                    // echo 'i='.$i . '<br>';
                                    // echo 'j='.$j . '<br>';
                                    $j += $step;
                                }
                                // end loop
                                $i ++;
                            }
                            //$pre_text = $row->host_code . '_' . $row->label_alias;
                            $pre_text = '';
                            if ($alert_1 == 1) {
                                $code = $host_code . '_' . $label_code . '_value1';
                                array_unshift($$code, $colors['value1']);
                                $devices[] = $$code;
                                array_push($y_text, $pre_text . '温度       ');
                            }
                            if ($alert_2 == 1) {
                                $code = $host_code . '_' . $label_code . '_value2';
                                array_unshift($$code, $colors['value2']);
                                $devices[] = $$code;
                                array_push($y_text, $pre_text . '湿度       ');
                            }
                            if ($alert_3 == 1) {
                                $code = $host_code . '_' . $label_code . '_value3';
                                array_unshift($$code, $colors['value3']);
                                $devices[] = $$code;
                                array_push($y_text, $pre_text . '电压       ');
                            }
                            if ($alert_4 == 1) {
                                $code = $host_code . '_' . $label_code . '_value4';
                                array_unshift($$code, $colors['value4']);
                                $devices[] = $$code;
                                array_push($y_text, $pre_text . '颗粒物PM2.5  ');
                            }
                            if ($alert_5 == 1) {
                                $code = $host_code . '_' . $label_code . '_value5';
                                array_unshift($$code, $colors['value5']);
                                $devices[] = $$code;
                                array_push($y_text, $pre_text . '颗粒物PM10  ');
                            }
                            if ($alert_6 == 1) {
                                $code = $host_code . '_' . $label_code . '_value6';
                                array_unshift($$code, $colors['value6']);
                                $devices[] = $$code;
                                array_push($y_text, $pre_text . '负氧离子数量  ');
                            }
                            if ($alert_7 == 1) {
                                $code = $host_code . '_' . $label_code . '_value7';
                                array_unshift($$code, $colors['value7']);
                                $devices[] = $$code;
                                array_push($y_text, $pre_text . '有机污染物(甲醛)  ');
                            }
                            if ($alert_8 == 1) {
                                $code = $host_code . '_' . $label_code . '_value8';
                                array_unshift($$code, $colors['value8']);
                                $devices[] = $$code;
                                array_push($y_text, $pre_text . '空气质量等级  ');
                            }
                            // end label loop
                        }
                    }
                    // echo '<pre>';
                    // print_r($devices);
                    // echo '</pre>';
                    $results = array();
                    $results['data'] = $devices;
                    $results['x_text'] = $x_text;
                    $results['y_text'] = $y_text;
                    $results['total'] = count($devices);
                    echo json_encode($results);
                }
                catch(Exception $e)
                { 
                    //??补救措施？
                    //print $e->getMessage();
                    //exit();
                }
            } elseif ($type == 'datagrid') {
                try {
                    $param = $this->uri->segment(4);
                    parse_str($param);
                    /* if($begin_date == $end_date && ! empty($begin_date)) {
                      $begin_date = $begin_date . ' 00:00:00';
                      $end_date = $end_date . ' 23:59:59';
                    } */
                    $start_date = strtotime($begin_date);
                    $end_date = strtotime($end_date);
                    $show_type = $show_type == 'true' ? 1 : 0;
                    $order_type = $order_type == 'true' ? 'desc' : 'asc';
                    $alert_1 = $alert_1 == 'true' ? 1 : 0;
                    $alert_2 = $alert_2 == 'true' ? 1 : 0;
                    $alert_3 = $alert_3 == 'true' ? 1 : 0;
                    $alert_4 = $alert_4 == 'true' ? 1 : 0;
                    $alert_5 = $alert_5 == 'true' ? 1 : 0;
                    $alert_6 = $alert_6 == 'true' ? 1 : 0;
                    $alert_7 = $alert_7 == 'true' ? 1 : 0;
                    $alert_8 = $alert_8 == 'true' ? 1 : 0;
                    $rows = 10;
                    $page = (int)$this->uri->segment(5);
                    if($page < 1) {
                      $page = 0;
                    }
                    $array = array();
                    if (1 == $show_type) {
                        $total_display = 30;
                    } else {
                        $total_display = 200;
                    }
                    
                    if (substr($labels, 0, 1) == ',') {
                        $labels = substr($labels, 1);
                    }
                    $labels = explode(',', $labels);               
    
                    if ($session_data['login_user_id'] != 'admin') {
                        $query = $this->Users_model->where_in('labels', 'label_id', $labels);
                        $devices = array();
                        foreach ($query->result() as $row) {
                            $host_code = strtolower($row->host_code);
                            $label_code = strtolower($row->label_code);
                            $table = 'zhistorydata_' . $host_code . '_' . $label_code;
                            $sql = 'SELECT count(*) as total FROM ' . $table;
                            if (! empty($start_date) && ! empty($end_date) && $end_date >= $start_date) {
                                $sql .= ' WHERE UNIX_TIMESTAMP(label_time) BETWEEN ' . $start_date . ' AND ' . $end_date;
                            }

                           // $sql .= ' order by z_id asc '; //zxg
							 $sql .= ' order by z_id desc '; //mjn
                            
                            $query2 = $this->Users_model->query($sql);
                            $row = $query2->row(); 
                            $total = $row->total;
    
                            $config['total_rows'] = $total;
                            $config['per_page'] = $rows;
                            $config['num_links'] = 10;
                            $config['first_link'] = '首页';
                            $config['last_link'] = '尾页';
                            $config['use_page_numbers'] = TRUE;
                            $config['uri_segment'] = 5;
                            $config['base_url'] = site_url('/member/devices/datagrid/' . $param . '/');
                            $this->pagination->initialize($config);
                            $data['total_rows'] = $config['total_rows'];
                            $data['pagination'] = $this->pagination->create_links();
    
    
                            $sql = 'SELECT * FROM ' . $table;
                            if (! empty($start_date) && ! empty($end_date) && $end_date >= $start_date) {
                                $sql .= ' WHERE UNIX_TIMESTAMP(label_time) BETWEEN ' . $start_date . ' AND ' . $end_date;
                            }
                            // $sql .= ' ORDER BY UNIX_TIMESTAMP(add_time) ' . $order_type;
                             
                            //$sql .= ' order by z_id asc '; //zxg
                             $sql .= ' order by z_id desc '; //mjn
                            $sql .= ' LIMIT ' . $page  . ',' . $rows;
                            
                            $query2 = $this->Users_model->query($sql);
                            $num = $query2->num_rows();
                            if ($num < $total_display && $num > 0) {
                                $total_display = $num;
                            }
                            //echo $this->db->last_query();
                            $device = array();
                            $i = 1;
                            $j = 1;
                            $step = floor($num / $total_display);
                            foreach ($query2->result() as $row2) {
                                //if ($i == $j) {
                                    $device['z_id'] = $row2->z_id;
                                    $device['host_code'] = $row2->host_code;
                                    $device['label_code'] = $row2->label_code;
                                    $device['label_time'] = $row2->label_time;
                                    $device['add_time'] = $row2->add_time;
                                    $device['value_1'] = $row2->value_1;
                                    $device['value_2'] = $row2->value_2;
                                    $device['value_3'] = $row2->value_3;
                                    $device['value_4'] = $row2->value_4;
                                    $device['value_5'] = $row2->value_5;
                                    $device['value_6'] = $row2->value_6;
                                    $device['value_7'] = $row2->value_7;
                                    $device['value_8'] = $row2->value_8;
                                    $devices[] = $device;
                                    $j += $step;
                                //}
                                $i ++;
                            }
                        }
                    }
                    $results = array();
                    if(isset($total)) {
                      $results['total'] = $total;
                      $results['rows'] = $devices;
                    } else {
                      $results['total'] = 0;
                      $results['rows'] = $devices;
                    }                
                    //echo json_encode($results);
                    $data['results'] = $results;
                    $this->load->view('datagrid', $data);
                }
                catch(Exception $e)
                { 
                    //$this->load->view('datagrid', null);
                    //print $e->getMessage();
                    //exit();
                }
            } else if ($type == 'export') {
                $this->load->library('excel');
                $titles = array(
                    iconv("UTF-8", "gb2312", '主机编号'),
                    iconv("UTF-8", "gb2312", '标签编号'),
                    iconv("UTF-8", "gb2312", '(温度)'),
                    iconv("UTF-8", "gb2312", '(湿度)'),
                    iconv("UTF-8", "gb2312", '(电压)'),
                    iconv("UTF-8", "gb2312", '(颗粒物PM2.5)'),
                    iconv("UTF-8", "gb2312", '(颗粒物PM10)'),
                    iconv("UTF-8", "gb2312", '(负氧离子数量)'),
                    iconv("UTF-8", "gb2312", '(有机污染物(甲醛))'),
                    iconv("UTF-8", "gb2312", '(空气质量等级)'),
                    iconv("UTF-8", "gb2312", '采集时间')
                );
                
                $param = $this->uri->segment(4);
                parse_str($param);
                /* if($begin_date == $end_date && ! empty($begin_date)) {
                  $begin_date = $begin_date . ' 00:00:00';
                  $end_date = $end_date . ' 23:59:59';
                } */
                $start_date = strtotime($begin_date);
                $end_date = strtotime($end_date);
                $show_type = $show_type == 'true' ? 1 : 0;
                $order_type = $order_type == 'true' ? 'desc' : 'asc';
                $alert_1 = $alert_1 == 'true' ? 1 : 0;
                $alert_2 = $alert_2 == 'true' ? 1 : 0;
                $alert_3 = $alert_3 == 'true' ? 1 : 0;
                $alert_4 = $alert_4 == 'true' ? 1 : 0;
                $alert_5 = $alert_5 == 'true' ? 1 : 0;
                $alert_6 = $alert_6 == 'true' ? 1 : 0;
                $alert_7 = $alert_7 == 'true' ? 1 : 0;
                $alert_8 = $alert_8 == 'true' ? 1 : 0;
                $array = array();
                if (1 == $show_type) {
                    $total_display = 30;
                } else {
                    $total_display = 200;
                }
                
                if (substr($labels, 0, 1) == ',') {
                    $labels = substr($labels, 1);
                }
                $labels = explode(',', $labels);
                if ($session_data['login_user_id'] != 'admin') {
                    $query = $this->Users_model->where_in('labels', 'label_id', $labels);
                    $devices = array();
                    foreach ($query->result() as $row) {
                        $host_code = strtolower($row->host_code); 
                        $label_code = strtolower($row->label_code);
                       // $host_alias=strtolower($row->host_alias);
                       $label_alias=strtolower($row->label_alias);                    
                        $table = 'zhistorydata_' . $host_code . '_' . $label_code;
                        $sql = 'SELECT * FROM ' . $table;
                        if (! empty($start_date) && ! empty($end_date) && $end_date >= $start_date) {
                            $sql .= ' WHERE UNIX_TIMESTAMP(label_time) BETWEEN ' . $start_date . ' AND ' . $end_date;
                        }
                        // $sql .= ' ORDER BY UNIX_TIMESTAMP(add_time) ' . $order_type;
                        
                        $sql .= ' order by z_id asc '; //zxg
                        
                        $query2 = $this->Users_model->query($sql);
                        //echo $this->db->last_query();
                        $num = $query2->num_rows();
                        if ($num < $total_display && $num > 0) {
                            $total_display = $num;
                        }
                        // echo $this->db->last_query();
                        $device = array();
                        $i = 1;
                        $j = 1;
                        $step = floor($num / $total_display);
                        $device = array();
                        foreach ($query2->result() as $row2) {
                            //if ($i == $j) {
                                $device['host_code'] = $row2->host_code;
                                $device['label_code'] = $row2->label_code;
                                $device['value_1'] = $row2->value_1;
                                $device['value_2'] = $row2->value_2;
                                $device['value_3'] = $row2->value_3;
                                $device['value_4'] = $row2->value_4;
                                $device['value_5'] = $row2->value_5;
                                $device['value_6'] = $row2->value_6;
                                $device['value_7'] = $row2->value_7;
                                $device['value_8'] = $row2->value_8;
                                $device['label_time'] = $row2->add_time;
                                $devices[] = $device;
                                $j += $step;
                            //}
                            $i ++;
                        }
                    }
                }
                $array = $devices;
                
               // $this->excel->filename = $host_code . '_' . $label_code . '_' . date('Y-m-d_H:i:s'); //老版本
               $this->excel->filename =  $host_code . '_' . $label_code . '_' . $label_alias . '_' . date('Y-m-d_H:i:s');   //Mjn
                
                $this->excel->make_from_array($titles, $array);
            }   else if ($type == 'export2') {
                $this->load->library('excel');
                $titles = array(
                    iconv("UTF-8", "gb2312", '主机别名'),
                    iconv("UTF-8", "gb2312", '标签别名'),
                    iconv("UTF-8", "gb2312", '主机编号'),
                    iconv("UTF-8", "gb2312", '标签编号'),
                    iconv("UTF-8", "gb2312", '(温度)'),
                    iconv("UTF-8", "gb2312", '(湿度)'),
                    iconv("UTF-8", "gb2312", '(电压)'),
                    iconv("UTF-8", "gb2312", '(颗粒物PM2.5)'),
                    iconv("UTF-8", "gb2312", '(颗粒物PM10)'),
                    iconv("UTF-8", "gb2312", '(负氧离子数量)'),
                    iconv("UTF-8", "gb2312", '(有机污染物(甲醛))'),
                    iconv("UTF-8", "gb2312", '(空气质量等级)'),
                    iconv("UTF-8", "gb2312", '采集时间')
                );
                
                $param = $this->uri->segment(4);
                parse_str($param);
                /* if($begin_date == $end_date && ! empty($begin_date)) {
                  $begin_date = $begin_date . ' 00:00:00';
                  $end_date = $end_date . ' 23:59:59';
                } */
                $start_date = strtotime($begin_date);
                $end_date = strtotime($end_date);
              //  $url_host_alias=$host_alias;
                  $url_host_code=$host_code;
              //  $url_label_alias =UrlDecode($label_alias);
               // $label_alias=decodeURIComponent($url_label_alias);
                $show_type = $show_type == 'true' ? 1 : 0;
                $order_type = $order_type == 'true' ? 'desc' : 'asc';
                $alert_1 = $alert_1 == 'true' ? 1 : 0;
                $alert_2 = $alert_2 == 'true' ? 1 : 0;
                $alert_3 = $alert_3 == 'true' ? 1 : 0;
                $alert_4 = $alert_4 == 'true' ? 1 : 0;
                $alert_5 = $alert_5 == 'true' ? 1 : 0;
                $alert_6 = $alert_6 == 'true' ? 1 : 0;
                $alert_7 = $alert_7 == 'true' ? 1 : 0;
                $alert_8 = $alert_8 == 'true' ? 1 : 0;
                $array = array();
                if (1 == $show_type) {
                    $total_display = 30;
                } else {
                    $total_display = 200;
                }
                
               /*  if (substr($labels, 0, 1) == ',') {
                    $labels = substr($labels, 1);
                } */
                /* if (substr($hosts, 0, 1) == ',') {
                    $hosts = substr($hosts, 1);
                }  */
               // $labels = explode(',', $labels);
                //$hosts  = explode(',', $hosts);
                if ($session_data['login_user_id'] != 'admin') {
                    $sql2 = 'SELECT host_alias FROM hosts where host_code in  (' . $url_host_code . ')';
                    //$query = $this->Users_model->where_in('labels', 'label_id', $labels);
                    $hhhh = array();
                    $hhhh = explode(',', $url_host_code); 
                    $query = $this->Users_model->where_in('labels', 'host_code', $hhhh);
                    $query2 = $this->Users_model->query($sql2);
                    foreach ($query2->result() as $row3){
                        $host_alias=strtolower($row3->host_alias);
                    }
                    $devices = array();
                    foreach ($query->result() as $row) {
                        if($row->label_alias=='')
                            continue;
                        if($row->label_category=='00000000')
                            continue;
                        $host_code = strtolower($row->host_code); 
                        $label_code = strtolower($row->label_code);
                        //$host_alias=strtolower($row->host_alias);
                        $label_alias=strtolower($row->label_alias);                    
                        $table = 'zhistorydata_' . $host_code . '_' . $label_code;
                        $sql = 'SELECT * FROM ' . $table;
                        if (! empty($start_date) && ! empty($end_date) && $end_date >= $start_date) {
                            $sql .= ' WHERE UNIX_TIMESTAMP(label_time) BETWEEN ' . $start_date . ' AND ' . $end_date;
                        }
                        // $sql .= ' ORDER BY UNIX_TIMESTAMP(add_time) ' . $order_type;
                        
                        $sql .= ' order by z_id asc '; //zxg
                        
                        $query2 = $this->Users_model->query($sql);
                        //echo $this->db->last_query();
                        $num = $query2->num_rows();
                        if ($num < $total_display && $num > 0) {
                            $total_display = $num;
                        }
                        // echo $this->db->last_query();
                        $device = array();
                        $i = 1;
                        $j = 1;
                        $step = floor($num / $total_display);
                        $device = array();
                        foreach ($query2->result() as $row2) {
                            //if ($i == $j) {
                                $device['host_alias']=iconv("UTF-8", "gb2312", $host_alias);
                               // $device['host_alias'] = iconv("UTF-8", "gb2312", '\''.$url_host_alias);//'\''.$url_host_alias;//iconv("UTF-8", "gb2312", '采集时间')
                                $device['label_alias'] = iconv("UTF-8", "gb2312", '\''.$row->label_alias);//$url_label_alias;
                           
                                $device['host_code'] = $row2->host_code;
                                $device['label_code'] = $row2->label_code;
                                $device['value_1'] = $row2->value_1;
                                $device['value_2'] = $row2->value_2;
                                $device['value_3'] = $row2->value_3;
                                $device['value_4'] = $row2->value_4;
                                $device['value_5'] = $row2->value_5;
                                $device['value_6'] = $row2->value_6;
                                $device['value_7'] = $row2->value_7;
                                $device['value_8'] = $row2->value_8;
                                $device['label_time'] = $row2->add_time;
                                $devices[] = $device;
                                $j += $step;
                            //}
                            $i ++;
                        }
                    }
                }
                $array = $devices;
                
                //$this->excel->filename = $host_code . '_' . $label_code . '_' . date('Y-m-d_H:i:s'); //老版本
                $this->excel->filename =  $host_code . '_ALL_' . date('Y-m-d_H:i:s');   //Mjn
                
                $this->excel->make_from_array($titles, $array);
            }else {
            
                //[标记]普通用户登录后
                $sql = 'h.*';
                $table = 'hosts h';
                $join_table = array(
                    'user_to_host u2h'
                );
                $join_field = array(
                    'h.host_id = u2h.host_id'
                );
                $join_type = array(
                    'inner join'
                );
                $where = array(
                    'key' => 'u2h.user_id',
                    'value' => $session_data['login_user_id']
                );
                $order = array(
                    'field' => 'h.host_id',
                    'type' => 'asc'
                );
                $per_page = 100;
                $query = $this->Users_model->show_join($table, $sql, $join_table, $join_field, $join_type, $where, $order, $per_page, 0);
                
                //[标记]加载标签
                $devices = array();
                foreach ($query->result() as $row) {
                    $tmp = array();
                    $tmp['id'] = - 1;
                    $tmp['host_code']=$row->host_code;
                    $tmp['host_alias']=$row->host_alias;
                    if ($row->is_online == 1) {
                        $desc = '(在线)';
                    } else {
                        $desc = '(离线)';
                    }
                    if (! empty($row->host_alias)) {
                        $tmp['text'] = $row->host_alias . '[' . $row->host_code . ']' . $desc;
                    } else {
                        $tmp['text'] = $row->host_code . $desc;
                    }
                    // get labels
                    $sql = 'l.*';
                    $table = 'labels l';
                    $join_table = array(
                        'hosts h'
                    );
                    $join_field = array(
                        'h.host_code = l.host_code'
                    );
                    $join_type = array(
                        'inner join'
                    );
                    $where = array(
                        'key' => 'l.host_code',
                        'value' => $row->host_code
                    );
                    $order = array(
                        'field' => 'l.label_code',
                        'type' => 'asc'
                    );
                    $per_page = 100;
                    $query2 = $this->Users_model->show_join($table, $sql, $join_table, $join_field, $join_type, $where, $order, $per_page, 0);
                    $children = array();
                    foreach ($query2->result() as $row2) {
                        $tmp2 = array();
                        $tmp2['id'] = $row2->label_id;
                        $tmp2['label_category'] = $row2->label_category;
                        if (! empty($row2->label_alias)) {
                            //$tmp2['text'] = $row2->label_alias . '[' . $row2->label_code . ']';//无备注
                            $tmp2['text'] = $row2->label_alias . '[' . $row2->label_code . ']' . '_' ;//16/02/01/mjn
                        } else {
                            $tmp2['text'] = $row2->label_code;
                        }
                        $children[] = $tmp2;
                    }
                    $tmp['children'] = $children;
                    $devices[] = $tmp;
                }
                
                //[标记]显示页面
                // echo $this->db->last_query();
                // print_r($devices);
                $data['devices'] = $devices;
                $this->load->view('header', $data);
                $this->load->view('devices', $data); //显示views/devices.php
                $this->load->view('footer', $data);
            }
        } else {
            $data['title'] = '登录';
            $this->load->view('index_view', $data);
        }
    }

    //温湿度实时数据界面
    function devices_now($type = 'default')
    {
        if ($this->session->userdata('is_login')) {
            $data['title'] = '实时数据';
            $data['login_user'] = $this->session->userdata('login_user');
            $data['login_alias'] = $this->session->userdata('login_alias');
            $data['login_user_id'] = $this->session->userdata('login_user_id');
            $data['login_user_type'] = $this->session->userdata('login_user_type');
            $data['login_status'] = $this->session->userdata('login_user_status');//zxg 1冻结 0正常
            $data['right_tpl'] = 'hosts';
            $session_data = $this->session->all_userdata();
    
            // get setting value
            if ($type == 'json') {   //json还不知道哪进入 zxg[标记]
                if ($session_data['login_user_type'] != 'admin') {
                    $sql = 'h.*';
                    $table = 'hosts h';
                    $join_table = array(
                        'user_to_host u2h'
                    );
                    $join_field = array(
                        'h.host_id = u2h.host_id'
                    );
                    $join_type = array(
                        'inner join'
                    );
                    $where = array(
                        'key' => 'u2h.user_id',
                        'value' => $session_data['login_user_id']
                    );
                    $order = array(
                        'field' => 'h.host_id',
                        'type' => 'asc'
                    );
                    $per_page = 100;
                }
                $query = $this->Users_model->show_join($table, $sql, $join_table, $join_field, $join_type, $where, $order, $per_page, 0);
                $devices = array();
                foreach ($query->result() as $row) {
                    $tmp = array();
                    $tmp['id'] = - 1;
                    $tmp['host_code']=$row->host_code;
                    $tmp['host_alias']=$row->host_alias;
                    if ($row->is_online == 1) {
                        $desc = '(在线)';
                    } else {
                        $desc = '(离线)';
                    }
                    if (! empty($row->host_alias)) {
                        $tmp['text']  = $row->host_alias . '_' . $row->host_code . $desc;
                        $tmp['textA'] = $row->host_alias . '(主机号' . $row->host_code . ') ' . $desc;  
                    } else {
                        $tmp['text']  = $row->host_code . $desc;
                        $tmp['textA'] = $row->host_code . '(主机号' . $row->host_code . ') ' . $desc;
                    }
                    // get labels
                    $sql = 'l.*';
                    $table = 'labels l';
                    $join_table = array(
                        'hosts h'
                    );
                    $join_field = array(
                        'h.host_code = l.host_code'
                    );
                    $join_type = array(
                        'inner join'
                    );
                    $where = array(
                        'key' => 'l.host_code',
                        'value' => $row->host_code
                    );
                    $order = array(
                        'field' => 'l.label_code',
                        'type' => 'asc'
                    );
                    $per_page = 100;
                    $query2 = $this->Users_model->show_join($table, $sql, $join_table, $join_field, $join_type, $where, $order, $per_page, 0);
                    $children = array();
                    foreach ($query2->result() as $row2) {
                        $tmp2 = array();
                        $tmp2['id'] = $row2->label_id;
                        $tmp2['label_category'] = $row2->label_category;
                        if (! empty($row2->label_alias)) {
                            $tmp2['text'] = $row2->label_alias;
                        } else {
                            $tmp2['text'] = $row2->label_code;
                        }
                        $children[] = $tmp2;
                    }
                    $tmp['children'] = $children;
                    $devices[] = $tmp;
                }
                echo json_encode($devices);
            }     
            else { 
                //[标记]普通用户登录后
                $sql = 'h.*';
                $table = 'hosts h';
                $join_table = array(
                    'user_to_host u2h'
                );
                $join_field = array(
                    'h.host_id = u2h.host_id'
                );
                $join_type = array(
                    'inner join'
                );
                $where = array(
                    'key' => 'u2h.user_id',
                    'value' => $session_data['login_user_id']
                );
                $order = array(
                    'field' => 'h.host_id',
                    'type' => 'asc'
                );
                $per_page = 100;
                $query = $this->Users_model->show_join($table, $sql, $join_table, $join_field, $join_type, $where, $order, $per_page, 0);
    
                //[标记]加载标签
                $devices = array();
                foreach ($query->result() as $row) {
                    $tmp = array();
                    $tmp['id'] = - 1;
                    $tmp['host_code']=$row->host_code;
                    $tmp['host_alias']=$row->host_alias;
                    if ($row->is_online == 1) {
                        $desc = '(在线)';
                    } else {
                        $desc = '(离线)';
                    }
                    if ($row->is_online == 1) {
                        $descOther = '[在线]';
                        $tmp['onlineNum'] = '1';
                    } else {
                        $descOther = '[离线]';
                        $tmp['onlineNum'] = '0';
                    }
                    if (! empty($row->host_alias)) {
                        $tmp['text'] = $row->host_alias . '[' . $row->host_code . ']' . $desc;
                        $tmp['textA'] = $row->host_alias . '（主机号' . $row->host_code . '） ' . $descOther; 
                    } else {
                        $tmp['text'] = $row->host_code . $desc;
                        $tmp['textA'] = $row->host_code . '（主机号' . $row->host_code . '） ' . $descOther;
                    }
                    // get labels
                    $sql = 'l.*';
                    $table = 'labels l';
                    $join_table = array(
                        'hosts h'
                    );
                    $join_field = array(
                        'h.host_code = l.host_code'
                    );
                    $join_type = array(
                        'inner join'
                    );
                    $where = array(
                        'key' => 'l.host_code',
                        'value' => $row->host_code
                    );
                    $order = array(
                        'field' => 'l.label_code',
                        'type' => 'asc'
                    );
                    $per_page = 100;
                    $query2 = $this->Users_model->show_join($table, $sql, $join_table, $join_field, $join_type, $where, $order, $per_page, 0);
                    $children = array();
                    foreach ($query2->result() as $row2) {
                        $tmp2 = array();
                        $tmp2['id'] = $row2->label_id;
                        $tmp2['label_category'] = $row2->label_category;
                        if (! empty($row2->label_alias)) {
                            //$tmp2['text'] = $row2->label_alias . '[' . $row2->label_code . ']';//无备注
                            $tmp2['text'] = $row2->label_alias . '[' . $row2->label_code . ']' . '_' ;//16/02/01/mjn
                            $tmp2['textA'] = $row2->label_alias . '(' . $row2->label_code . ')'; 
                        } else {
                            $tmp2['text'] = $row2->label_code;
                            $tmp2['textA'] = $row2->label_alias . '(' . $row2->label_code . ')';
                        } 
                        //=============获取标签最新温度值==============
                        $CurrWDVal = '';   //当前温度值
                        $CurrSDVal = '';   //当前湿度值
                        $CurrDYVal = '';   //当前电压值
                        $CurrWDTime = '';  //对应采集时间
                        $CurrWDIsBj = '';  //是否温度报警
                        ////error_reporting(0);
                        if($row2->label_category != '000000000')  //8个0是净化器
                        { 
                            $FF_host_code = strtolower($row2->host_code);
                            $FF_label_code = strtolower($row2->label_code);
                            $FF_table = 'zhistorydata_' . $FF_host_code . '_' . $FF_label_code;
                            $FF_sql = 'SELECT count(*) as total FROM ' . $FF_table;
                            ////if (! empty($start_date) && ! empty($end_date) && $end_date >= $start_date) {
                            ////    $sql .= ' WHERE UNIX_TIMESTAMP(label_time) BETWEEN ' . $start_date . ' AND ' . $end_date;
                            ////}
                             
                            $FF_sql .= ' order by z_id desc '; //mjn
                            
                            //不查了 $FF_query2 = $this->Users_model->query($FF_sql);
                            //不查了 $FF_row2 = $FF_query2->row();
                            //不查了 $FF_total = $FF_row2->total;
                             
                            $FF_sql = 'SELECT * FROM ' . $FF_table;
                            ////if (! empty($start_date) && ! empty($end_date) && $end_date >= $start_date) {
                            ////    $sql .= ' WHERE UNIX_TIMESTAMP(label_time) BETWEEN ' . $start_date . ' AND ' . $end_date;
                            ////}
                            ////// $sql .= ' ORDER BY UNIX_TIMESTAMP(add_time) ' . $order_type;
                              
                            $FF_sql .= ' order by z_id desc ';  
                            $FF_sql .= ' LIMIT 0,1 ';
                            
                            $FF_query2 = $this->Users_model->query($FF_sql);
                            $FF_num = $FF_query2->num_rows();
                            if ($FF_num > 0) 
                            {  
                                ////$FF_device = array(); 
                                foreach ($FF_query2->result() as $FF_row2) { 
                                    $FF_device['z_id'] = $FF_row2->z_id;
                                    $FF_device['host_code'] = $FF_row2->host_code;
                                    $FF_device['label_code'] = $FF_row2->label_code;
                                    $FF_device['label_time'] = $FF_row2->label_time;
                                    $FF_device['add_time'] = $FF_row2->add_time;
                                    $FF_device['value_1'] = $FF_row2->value_1;
                                    $FF_device['value_2'] = $FF_row2->value_2;
                                    $FF_device['value_3'] = $FF_row2->value_3;
                                    $FF_device['value_4'] = $FF_row2->value_4;
                                    $FF_device['value_5'] = $FF_row2->value_5;
                                    $FF_device['value_6'] = $FF_row2->value_6;
                                    $FF_device['value_7'] = $FF_row2->value_7;
                                    $FF_device['value_8'] = $FF_row2->value_8;
                                    $FF_device['bj_1'] = $FF_row2->bj_1;
                                    ////$FF_devices[] = $FF_device; 
                                    $CurrWDVal = strval(floatval($FF_device['value_1'])) . '℃';   //当前温度值
                                    //当前湿度值
                                    if(1==1)
                                    {
                                        $CurrSDVal = strval(floatval($FF_device['value_2'])) . '%RH';   
                                        $CurrSDVal = "<span class=\"in_div_txt_shidu\">".$CurrSDVal."</span>";
                                    }
                                    //当前电压值
                                    if(floatval($FF_device['value_3']) < 2.1) //以前是3.0 长波改'低电';
                                        $CurrDYVal = '&nbsp;&nbsp;<span style="color:red">低电</span>'; //'低电';
										
										
                                    else 
                                    $CurrDYVal = '&nbsp;&nbsp;<span style="color:green">正常</span>'; //'正常';
									//var_dump(floatval($FF_device['value_3']));
                                    $CurrWDTime = $FF_device['label_time'];  //对应采集时间
                                    $CurrWDIsBj = $FF_device['bj_1'];  //是否温度报警
                                }    
                            }                        
                        }
                        ////catch(Exception $e)  不拦截
                        ////{}
                        $tmp2['CurrWDVal'] = $CurrWDVal;
                        $tmp2['CurrSDVal'] = $CurrSDVal;
                        $tmp2['CurrDYVal'] = $CurrDYVal;
                        $tmp2['CurrWDTime'] = $CurrWDTime;
                        $tmp2['CurrWDIsBj'] = $CurrWDIsBj; 
                        //==========================================
                        $children[] = $tmp2;
                    }
                    $tmp['children'] = $children;
                    $devices[] = $tmp;
                }
    
                //[标记]显示页面
                // echo $this->db->last_query();
                // print_r($devices);
                $data['devices'] = $devices;
                $this->load->view('header', $data);
                $this->load->view('devices_now', $data); //显示views/devices.php
                $this->load->view('footer', $data);
            }
        } else {
            $data['title'] = '登录';
            $this->load->view('index_view', $data);
        }
    } 
    
    //获取表数据 (要求@sql第一个单词是SELECT)
    function GetTableDataJson($type = 'default')
    {
        if ($type == 'select')
        {
            try{
                $sql = trim($this->input->post('sql'));
                ////$sql = 'SELECT * FROM hosts';
                
                $sqlheadstr = substr($sql,0,6);
                $sqlheadstr = strtolower($sqlheadstr);
                if($sqlheadstr != 'select')
                {
                    $results = array();
                    $results['ErrMsg'] = "errCode:303";
                    echo json_encode($results);
                    return;
                }
                    
                $query = $this->Users_model->query($sql);
                 
                //返回结果
                if(!empty($query) && $query->num_rows()>0)
                {
                    $data = array();
                    foreach ($query->result() as $row)
                    {
                        //array_push($data, $row->host_code);
                        array_push($data, $row);
                    }
                    
                    $results = array();
                    $results['row'] = $data; 
                    ////$results['total'] = $query->num_rows();
                    echo json_encode($results);
                    return;
                }
                else
                {
                    $results = array();
                    $results['ErrMsg'] = "errCode:505";
                    echo json_encode($results);
                    return;
                }
            }
            catch(Exception $e)
            { 
                $results = array();
                $results['ErrMsg'] = "errCode:909";
                echo json_encode($results);
                return;
            }
        }
    }
    
    //获取折线数据
    function GetCharDataJson($type = 'default')
    {  
        //zxg 测试ok
        /* 
        $data_rowobj_List = array();
        
        if(1==1)
        {
            $colorStr = "#ff0000";
            $indata = array();
            array_push($indata,17);
            array_push($indata,1);
            array_push($indata,12);
            array_push($indata,133); 

            $data_rowobj['color'] = $colorStr;
            $data_rowobj['indata'] = $indata;
            array_push($data_rowobj_List,$data_rowobj);
        }  
        if(1==1)
        {
            $colorStr = "#ff0000";
            $indata = array();
            array_push($indata,17111);
            array_push($indata,1111);
            array_push($indata,11112);
            array_push($indata,131113); 

            $data_rowobj['color'] = $colorStr;
            $data_rowobj['indata'] = $indata;
            array_push($data_rowobj_List,$data_rowobj);
        } 
        $results = array();
        $results['data'] = $data_rowobj_List;
        echo json_encode($results);
        return; */
        
         if ($type == 'chart') 
         {
            try{
                $begin_date = trim($this->input->post('begin_date'));
                $end_date = trim($this->input->post('end_date'));
                /* zxg
                 if($begin_date == $end_date && ! empty($begin_date)) {
                 $begin_date = $begin_date . ' 00:00:00';
                 $end_date = $end_date . ' 23:59:59';
                } */
                $start_date = strtotime($begin_date);
                $end_date = strtotime($end_date);
                $labels = trim($this->input->post('labels'));
                $show_type = $this->input->post('show_type') == 'true' ? 1 : 0;
                $order_type = $this->input->post('order_type') == 'true' ? 'desc' : 'asc';
                $alert_1 = $this->input->post('alert_1') == 'true' ? 1 : 0;
                $alert_2 = $this->input->post('alert_2') == 'true' ? 1 : 0;
                $alert_3 = $this->input->post('alert_3') == 'true' ? 1 : 0;
                $alert_4 = $this->input->post('alert_4') == 'true' ? 1 : 0;
                $alert_5 = $this->input->post('alert_5') == 'true' ? 1 : 0;
                $alert_6 = $this->input->post('alert_6') == 'true' ? 1 : 0;
                $alert_7 = $this->input->post('alert_7') == 'true' ? 1 : 0;
                $alert_8 = $this->input->post('alert_8') == 'true' ? 1 : 0;
                if (1 == $show_type) {
                    $total_display = 30;
                } else {
                    $total_display = 200;
                }
                $labels = preg_replace('/,{2,}/', ',', $labels);
                if (substr($labels, 0, 1) == ',') {
                    $labels = substr($labels, 1);
                }

                if (! empty($labels)) {

                    $sql = 'SELECT l.*,h.host_alias FROM hosts h INNER JOIN labels l ON l.host_code = h.host_code WHERE l.label_id in (' . $labels . ')';
                    $query = $this->Users_model->query($sql);
                    $devices = array();
                    $y_text = array();
                    $colors = array(
                        'value1' => '#ff0000',
                        'value2' => '#0000FF',
                        'value3' => '#FFFF00',
                        'value4' => '#00FF00',
                        'value5' => '#00FFFF',
                        'value6' => '#FF7F00',
                        'value7' => '#871F78',
                        'value8' => '#cccccc'
                    );
                    
                    //zxg
                    $data_rowobj_List = array();  
                    if(1==1)
                    {
                  /*       $colorStr = "#ff0000";
                        $indata = array();
                        array_push($indata,17);
                        array_push($indata,1);
                        array_push($indata,12);
                        array_push($indata,133); 
            
                        $data_rowobj['color'] = $colorStr;
                        $data_rowobj['indata'] = $indata;
                        array_push($data_rowobj_List,$data_rowobj); */
                    }  
 
                    foreach ($query->result() as $row) {
 
                        //zxg new
                        $colorStr1 = "";
                        $indata1 = array();
                        $colorStr2 = "";
                        $indata2 = array();
                        $colorStr3 = "";
                        $indata3 = array();
                        $colorStr4 = "";
                        $indata4 = array();
                        $colorStr5 = "";
                        $indata5 = array();
                        $colorStr6 = "";
                        $indata6 = array();
                        $colorStr7 = "";
                        $indata7 = array();
                        $colorStr8 = "";
                        $indata8 = array();
                        
                        $host_code = strtolower($row->host_code);
                        $label_code = strtolower($row->label_code);
                        $table = 'zhistorydata_' . $host_code . '_' . $label_code;
                        $sql = 'SELECT * FROM ' . $table;
                        if (! empty($start_date) && ! empty($end_date) && $end_date >= $start_date) {
                            $sql .= ' WHERE UNIX_TIMESTAMP(label_time) BETWEEN ' . $start_date . ' AND ' . $end_date;
                        }
                        // $sql .= ' ORDER BY UNIX_TIMESTAMP(add_time) ' . $order_type;
                        //zxg $sql .= ' LIMIT 200';
                        $sql .= ' order by z_id asc '; //zxg
                        $query2 = $this->Users_model->query($sql);
                        //echo $this->db->last_query();
                        $device = array();
                        $code = $host_code . '_' . $label_code . '_value1';
                        $$code = array();
                        $code = $host_code . '_' . $label_code . '_value2';
                        $$code = array();
                        $code = $host_code . '_' . $label_code . '_value3';
                        $$code = array();
                        $code = $host_code . '_' . $label_code . '_value4';
                        $$code = array();
                        $code = $host_code . '_' . $label_code . '_value5';
                        $$code = array();
                        $code = $host_code . '_' . $label_code . '_value6';
                        $$code = array();
                        $code = $host_code . '_' . $label_code . '_value7';
                        $$code = array();
                        $code = $host_code . '_' . $label_code . '_value8';
                        $$code = array();
                        $x_text = array();
                        $num = $query2->num_rows();
                        if ($num < $total_display && $num > 0) {
                            $total_display = $num;
                        }
                        // echo 'num='.$num . '<br>';
                        // echo 'total='.$total_display . '<br>';
                        $i = 0;
                        $j = 0;
                        $step = floor($num / $total_display);
                        $rowslength=$num;//zxg
                        foreach ($query2->result() as $row2) {
                            if ($i == $j  || $i == $rowslength-1) {
                                if ($alert_1 == 1) {
                                    $code = $host_code . '_' . $label_code . '_value1';
                                    array_push($$code, (float) $row2->value_1);
                                    
                                    //zxg
                                    array_push($indata1,(float) $row2->value_1);
                                }
                                if ($alert_2 == 1) {
                                    $code = $host_code . '_' . $label_code . '_value2';
                                    array_push($$code, (float) $row2->value_2);

                                    //zxg
                                    array_push($indata2,(float) $row2->value_2);
                                }
                                if ($alert_3 == 1) {
                                    $code = $host_code . '_' . $label_code . '_value3';
                                    array_push($$code, (float) $row2->value_3);

                                    //zxg
                                    array_push($indata3,(float) $row2->value_3);
                                }
                                if ($alert_4 == 1) {
                                    $code = $host_code . '_' . $label_code . '_value4';
                                    array_push($$code, (float) $row2->value_4);

                                    //zxg
                                    array_push($indata4,(float) $row2->value_4);
                                }
                                if ($alert_5 == 1) {
                                    $code = $host_code . '_' . $label_code . '_value5';
                                    array_push($$code, (float) $row2->value_5);

                                    //zxg
                                    array_push($indata5,(float) $row2->value_5);
                                }
                                if ($alert_6 == 1) {
                                    $code = $host_code . '_' . $label_code . '_value6';
                                    array_push($$code, (float) $row2->value_6);

                                    //zxg
                                    array_push($indata6,(float) $row2->value_6);
                                }
                                if ($alert_7 == 1) {
                                    $code = $host_code . '_' . $label_code . '_value7';
                                    array_push($$code, (float) $row2->value_7);

                                    //zxg
                                    array_push($indata7,(float) $row2->value_7);
                                }
                                if ($alert_8 == 1) {
                                    $code = $host_code . '_' . $label_code . '_value8';
                                    array_push($$code, (float) $row2->value_8);

                                    //zxg
                                    array_push($indata8,(float) $row2->value_8);
                                }
                                array_push($x_text, date('Y-m-d H:i:s', strtotime($row2->label_time)));
                                // echo 'i='.$i . '<br>';
                                // echo 'j='.$j . '<br>';
                                $j += $step;
                            }
                            // end loop
                            $i ++;
                        }
                        //$pre_text = $row->host_code . '_' . $row->label_alias;
                        $pre_text = '';
                        if ($alert_1 == 1) {
                            $code = $host_code . '_' . $label_code . '_value1';
                            array_unshift($$code, $colors['value1']);
                            $devices[] = $$code;
                            array_push($y_text, $pre_text . '温度       ');
                            
                            //zxg
                            $colorStr1 = $colors['value1'];
                        }
                        if ($alert_2 == 1) {
                            $code = $host_code . '_' . $label_code . '_value2';
                            array_unshift($$code, $colors['value2']);
                            $devices[] = $$code;
                            array_push($y_text, $pre_text . '湿度       ');

                            //zxg
                            $colorStr2 = $colors['value2'];
                        }
                        if ($alert_3 == 1) {
                            $code = $host_code . '_' . $label_code . '_value3';
                            array_unshift($$code, $colors['value3']);
                            $devices[] = $$code;
                            array_push($y_text, $pre_text . '电压       ');

                            //zxg
                            $colorStr3 = $colors['value3'];
                        }
                        if ($alert_4 == 1) {
                            $code = $host_code . '_' . $label_code . '_value4';
                            array_unshift($$code, $colors['value4']);
                            $devices[] = $$code;
                            array_push($y_text, $pre_text . '颗粒物PM2.5  ');

                            //zxg
                            $colorStr4 = $colors['value4'];
                        }
                        if ($alert_5 == 1) {
                            $code = $host_code . '_' . $label_code . '_value5';
                            array_unshift($$code, $colors['value5']);
                            $devices[] = $$code;
                            array_push($y_text, $pre_text . '颗粒物PM10 ');

                            //zxg
                            $colorStr5 = $colors['value5'];
                        }
                        if ($alert_6 == 1) {
                            $code = $host_code . '_' . $label_code . '_value6';
                            array_unshift($$code, $colors['value6']);
                            $devices[] = $$code;
                            array_push($y_text, $pre_text . '负氧离子数量 ');

                            //zxg
                            $colorStr6 = $colors['value6'];
                        }
                        if ($alert_7 == 1) {
                            $code = $host_code . '_' . $label_code . '_value7';
                            array_unshift($$code, $colors['value7']);
                            $devices[] = $$code;
                            array_push($y_text, $pre_text . '有机污染物(甲醛) ');

                            //zxg
                            $colorStr7 = $colors['value7'];
                        }
                        if ($alert_8 == 1) {
                            $code = $host_code . '_' . $label_code . '_value8';
                            array_unshift($$code, $colors['value8']);
                            $devices[] = $$code;
                            array_push($y_text, $pre_text . '空气质量等级  ');

                            //zxg
                            $colorStr8 = $colors['value8'];
                        }
                        // end label loop
                         
                        //zxg 标签结果组合
                        if(1==1)
                        {
                            if ($alert_1 == 1) {
                                $data_rowobj['color'] = $colorStr1;
                                $data_rowobj['indata'] = $indata1;
                                array_push($data_rowobj_List,$data_rowobj);
                            }
                            if ($alert_2 == 1) {
                                $data_rowobj['color'] = $colorStr2;
                                $data_rowobj['indata'] = $indata2;
                                array_push($data_rowobj_List,$data_rowobj);
                            }
                            if ($alert_3 == 1) {
                                $data_rowobj['color'] = $colorStr3;
                                $data_rowobj['indata'] = $indata3;
                                array_push($data_rowobj_List,$data_rowobj);
                            }
                            if ($alert_4 == 1) {
                                $data_rowobj['color'] = $colorStr4;
                                $data_rowobj['indata'] = $indata4;
                                array_push($data_rowobj_List,$data_rowobj);
                            }
                            if ($alert_5 == 1) {
                                $data_rowobj['color'] = $colorStr5;
                                $data_rowobj['indata'] = $indata5;
                                array_push($data_rowobj_List,$data_rowobj);
                            }
                            if ($alert_6 == 1) {
                                $data_rowobj['color'] = $colorStr6;
                                $data_rowobj['indata'] = $indata6;
                                array_push($data_rowobj_List,$data_rowobj);
                            }
                            if ($alert_7 == 1) {
                                $data_rowobj['color'] = $colorStr7;
                                $data_rowobj['indata'] = $indata7;
                                array_push($data_rowobj_List,$data_rowobj);
                            }
                            if ($alert_8 == 1) {
                                $data_rowobj['color'] = $colorStr8;
                                $data_rowobj['indata'] = $indata8;
                                array_push($data_rowobj_List,$data_rowobj);
                            }
                        }
                    }
                     
                    //返回结果 
                    if($query->num_rows()>0)
                    {
                        $results = array();
                        ////$results['data'] = $devices;
                        $results['data'] = $data_rowobj_List;
                        $results['x_text'] = $x_text;
                        $results['y_text'] = $y_text;
                        $results['total'] = count($devices);
                        echo json_encode($results); 
                        return;
                    }
                    else
                    {
                        $results = array();
                        $results['ErrMsg'] = "errCode:505";
                        echo json_encode($results);
                        return;
                    }
                }
                else
                {
                    $results = array();
                    $results['ErrMsg'] = "errCode:707";
                    echo json_encode($results);
                    return;;
                }
                // echo '<pre>';
                // print_r($devices);
                // echo '</pre>'; 
            }
            catch(Exception $e)
            {
                //??补救措施？
                //print $e->getMessage();
                //exit(); 
                $results = array();
                $results['ErrMsg'] = "errCode:909";
                echo json_encode($results);
                return;
            }
        }     
    }
    
    //获取折线数据
    function GetCharDataJson_Yuanlai($type = 'default')
    {
        if ($type == 'chart')
        {
            try{
                $begin_date = trim($this->input->post('begin_date'));
                $end_date = trim($this->input->post('end_date'));
                /* zxg
                 if($begin_date == $end_date && ! empty($begin_date)) {
                 $begin_date = $begin_date . ' 00:00:00';
                 $end_date = $end_date . ' 23:59:59';
                } */
                $start_date = strtotime($begin_date);
                $end_date = strtotime($end_date);
                $labels = trim($this->input->post('labels'));
                $show_type = $this->input->post('show_type') == 'true' ? 1 : 0;
                $order_type = $this->input->post('order_type') == 'true' ? 'desc' : 'asc';
                $alert_1 = $this->input->post('alert_1') == 'true' ? 1 : 0;
                $alert_2 = $this->input->post('alert_2') == 'true' ? 1 : 0;
                $alert_3 = $this->input->post('alert_3') == 'true' ? 1 : 0;
                $alert_4 = $this->input->post('alert_4') == 'true' ? 1 : 0;
                $alert_5 = $this->input->post('alert_5') == 'true' ? 1 : 0;
                $alert_6 = $this->input->post('alert_6') == 'true' ? 1 : 0;
                $alert_7 = $this->input->post('alert_7') == 'true' ? 1 : 0;
                $alert_8 = $this->input->post('alert_8') == 'true' ? 1 : 0;
                if (1 == $show_type) {
                    $total_display = 30;
                } else {
                    $total_display = 200;
                }
                $labels = preg_replace('/,{2,}/', ',', $labels);
                if (substr($labels, 0, 1) == ',') {
                    $labels = substr($labels, 1);
                }
    
                if (! empty($labels)) {
    
                    $sql = 'SELECT l.*,h.host_alias FROM hosts h INNER JOIN labels l ON l.host_code = h.host_code WHERE l.label_id in (' . $labels . ')';
                    $query = $this->Users_model->query($sql);
                    $devices = array();
                    $y_text = array();
                    $colors = array(
                        'value1' => '#ff0000',
                        'value2' => '#0000FF',
                        'value3' => '#FFFF00',
                        'value4' => '#00FF00',
                        'value5' => '#00FFFF',
                        'value6' => '#FF7F00',
                        'value7' => '#871F78',
                        'value8' => '#cccccc'
                    );
    
                    foreach ($query->result() as $row) {
    
                        $host_code = strtolower($row->host_code);
                        $label_code = strtolower($row->label_code);
                        $table = 'zhistorydata_' . $host_code . '_' . $label_code;
                        $sql = 'SELECT * FROM ' . $table;
                        if (! empty($start_date) && ! empty($end_date) && $end_date >= $start_date) {
                            $sql .= ' WHERE UNIX_TIMESTAMP(label_time) BETWEEN ' . $start_date . ' AND ' . $end_date;
                        }
                        // $sql .= ' ORDER BY UNIX_TIMESTAMP(add_time) ' . $order_type;
                        //zxg $sql .= ' LIMIT 200';
                        $sql .= ' order by z_id asc '; //zxg
                        $query2 = $this->Users_model->query($sql);
                        //echo $this->db->last_query();
                        $device = array();
                        $code = $host_code . '_' . $label_code . '_value1';
                        $$code = array();
                        $code = $host_code . '_' . $label_code . '_value2';
                        $$code = array();
                        $code = $host_code . '_' . $label_code . '_value3';
                        $$code = array();
                        $code = $host_code . '_' . $label_code . '_value4';
                        $$code = array();
                        $code = $host_code . '_' . $label_code . '_value5';
                        $$code = array();
                        $code = $host_code . '_' . $label_code . '_value6';
                        $$code = array();
                        $code = $host_code . '_' . $label_code . '_value7';
                        $$code = array();
                        $code = $host_code . '_' . $label_code . '_value8';
                        $$code = array();
                        $x_text = array();
                        $num = $query2->num_rows();
                        if ($num < $total_display && $num > 0) {
                            $total_display = $num;
                        }
                        // echo 'num='.$num . '<br>';
                        // echo 'total='.$total_display . '<br>';
                        $i = 0;
                        $j = 0;
                        $step = floor($num / $total_display);
                        $rowslength=$num;//zxg
                        foreach ($query2->result() as $row2) {
                            if ($i == $j  || $i == $rowslength-1) {
                                if ($alert_1 == 1) {
                                    $code = $host_code . '_' . $label_code . '_value1';
                                    array_push($$code, (float) $row2->value_1);
                                }
                                if ($alert_2 == 1) {
                                    $code = $host_code . '_' . $label_code . '_value2';
                                    array_push($$code, (float) $row2->value_2);
                                }
                                if ($alert_3 == 1) {
                                    $code = $host_code . '_' . $label_code . '_value3';
                                    array_push($$code, (float) $row2->value_3);
                                }
                                if ($alert_4 == 1) {
                                    $code = $host_code . '_' . $label_code . '_value4';
                                    array_push($$code, (float) $row2->value_4);
                                }
                                if ($alert_5 == 1) {
                                    $code = $host_code . '_' . $label_code . '_value5';
                                    array_push($$code, (float) $row2->value_5);
                                }
                                if ($alert_6 == 1) {
                                    $code = $host_code . '_' . $label_code . '_value6';
                                    array_push($$code, (float) $row2->value_6);
                                }
                                if ($alert_7 == 1) {
                                    $code = $host_code . '_' . $label_code . '_value7';
                                    array_push($$code, (float) $row2->value_7);
                                }
                                if ($alert_8 == 1) {
                                    $code = $host_code . '_' . $label_code . '_value8';
                                    array_push($$code, (float) $row2->value_8);
                                }
                                array_push($x_text, date('Y-m-d H:i:s', strtotime($row2->label_time)));
                                // echo 'i='.$i . '<br>';
                                // echo 'j='.$j . '<br>';
                                $j += $step;
                            }
                            // end loop
                            $i ++;
                        }
                        //$pre_text = $row->host_code . '_' . $row->label_alias;
                        $pre_text = '';
                         if ($alert_1 == 1) {
                            $code = $host_code . '_' . $label_code . '_value1';
                            array_unshift($$code, $colors['value1']);
                            $devices[] = $$code;
                            array_push($y_text, $pre_text . '温度       ');
                        }
                        if ($alert_2 == 1) {
                            $code = $host_code . '_' . $label_code . '_value2';
                            array_unshift($$code, $colors['value2']);
                            $devices[] = $$code;
                            array_push($y_text, $pre_text . '湿度       ');
                        }
                        if ($alert_3 == 1) {
                            $code = $host_code . '_' . $label_code . '_value3';
                            array_unshift($$code, $colors['value3']);
                            $devices[] = $$code;
                            array_push($y_text, $pre_text . '电压       ');
                        }
                        if ($alert_4 == 1) {
                            $code = $host_code . '_' . $label_code . '_value4';
                            array_unshift($$code, $colors['value4']);
                            $devices[] = $$code;
                            array_push($y_text, $pre_text . '颗粒物PM2.5  ');
                        }
                        if ($alert_5 == 1) {
                            $code = $host_code . '_' . $label_code . '_value5';
                            array_unshift($$code, $colors['value5']);
                            $devices[] = $$code;
                            array_push($y_text, $pre_text . '颗粒物PM10   ');
                        }
                        if ($alert_6 == 1) {
                            $code = $host_code . '_' . $label_code . '_value6';
                            array_unshift($$code, $colors['value6']);
                            $devices[] = $$code;
                            array_push($y_text, $pre_text . '负氧离子数量  ');
                        }
                        if ($alert_7 == 1) {
                            $code = $host_code . '_' . $label_code . '_value7';
                            array_unshift($$code, $colors['value7']);
                            $devices[] = $$code;
                            array_push($y_text, $pre_text . '有机污染物(甲醛)  ');
                        }
                        if ($alert_8 == 1) {
                            $code = $host_code . '_' . $label_code . '_value8';
                            array_unshift($$code, $colors['value8']);
                            $devices[] = $$code;
                            array_push($y_text, $pre_text . '空气质量等级  ');
                        }
                        // end label loop
                    }
    
                    //返回结果
                    if($query->num_rows()>0)
                    {
                        $results = array();
                        $results['data'] = $devices;
                        $results['x_text'] = $x_text;
                        $results['y_text'] = $y_text;
                        $results['total'] = count($devices);
                        echo json_encode($results);
                        return;
                    }
                    else
                    {
                        $results = array();
                        $results['ErrMsg'] = "errCode:505";
                        echo json_encode($results);
                        return;
                    }
                }
                else
                {
                    $results = array();
                    $results['ErrMsg'] = "errCode:707";
                    echo json_encode($results);
                    return;;
                }
                // echo '<pre>';
                // print_r($devices);
                // echo '</pre>';
            }
            catch(Exception $e)
            {
                //??补救措施？
                //print $e->getMessage();
                //exit();
                $results = array();
                $results['ErrMsg'] = "errCode:909";
                echo json_encode($results);
                return;
            }
        }
    }
}
