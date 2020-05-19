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
            
            $this->form_validation->set_rules('old_password', '原密码','required|min_length[6]');
            $this->form_validation->set_rules('password', '新密码', 'required|min_length[6]');
            $this->form_validation->set_rules('password2', '确认密码', 'required|min_length[6]|matches[password]');
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

    function user($type = 'default', $id = NULL)
    {
        if ($this->session->userdata('is_login')) {
            $data['title'] = '用户管理';
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

    function add_user($user_id = NULL)
    {
        if ($this->session->userdata('is_login')) {
            $data['title'] = '用户管理';
            $data['login_user'] = $this->session->userdata('login_user');
            $data['login_alias'] = $this->session->userdata('login_alias');
            $data['login_user_id'] = $this->session->userdata('login_user_id');
            $data['login_user_type'] = $this->session->userdata('login_user_type');
            $data['right_tpl'] = 'add_user';
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
                      $table = 'rules r';
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
                      $table = 'rules r';
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
                        $data['message'] = '报警规则修改成功';
                    } else {
                        $data['message'] = '报警规则修改失败';
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
                        $table = 'rules r';
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
                        $table = 'rules r';
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

    function logs($keyword = NULL, $page = 0)
    {
        if ($this->session->userdata('is_login')) {
            $data['title'] = '用户登录记录';
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
                $query = $this->Users_model->show_join($table, $sql, $join_table, $join_field, $join_type, $where, $order, 10, 0);
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

    function hosts($type = 'default', $host_id = NULL)
    {
        if ($this->session->userdata('is_login')) {
            $data['title'] = '主机管理';
            $data['login_user'] = $this->session->userdata('login_user');
            $data['login_alias'] = $this->session->userdata('login_alias');
            $data['login_user_id'] = $this->session->userdata('login_user_id');
            $data['login_user_type'] = $this->session->userdata('login_user_type');
            $data['right_tpl'] = 'hosts';
            
            // get setting value
            if ($type == 'add') {
                $this->form_validation->set_rules('host_code', '主机编号', 'required|min_length[2]|max_length[20]');
                $this->form_validation->set_rules('host_alias', '主机别名', 'required|min_length[1]|max_length[10]');
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
                $this->form_validation->set_rules('host_code', '主机编号', 'required|min_length[2]|max_length[20]');
                $this->form_validation->set_rules('host_alias', '主机别名', 'required|min_length[1]|max_length[10]');
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
                $session_data = $this->session->all_userdata();
                if ($session_data['login_user_type'] == 'admin') {
                    $sql = 'h.*';
                    $table = 'hosts h';
                    $join_table = array();
                    $join_field = array();
                    $join_type = array();
                    $where = FALSE;
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
                    $where = array(
                        'key' => 'u2h.user_id',
                        'value' => $session_data['login_user_id']
                    );
                    $order = FALSE;
                    $query = $this->Users_model->show_join($table, $sql, $join_table, $join_field, $join_type, $where);
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
                    $query = $this->Users_model->show_join($table, $sql, $join_table, $join_field, $join_type, $where, $order, $per_page, $type);
                } else {
                    $query = $this->Users_model->show_join($table, $sql, $join_table, $join_field, $join_type, $where, $order, $per_page, 0);
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

    function labels($type = 'default', $label_id = NULL)
    {
        if ($this->session->userdata('is_login')) {
            $data['title'] = '采集器管理';
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
                $this->form_validation->set_rules('host_code', '主机编码', 'required|min_length[2]|max_length[20]');
                $this->form_validation->set_rules('label_code', '标签编码', 'required|min_length[2]|max_length[10]');
                $this->form_validation->set_rules('label_alias', '标签别名', 'required|min_length[2]|max_length[10]');
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
                $this->form_validation->set_rules('host_code', '主机编码', 'required|min_length[2]|max_length[20]');
                $this->form_validation->set_rules('label_code', '标签编码', 'required|min_length[2]|max_length[10]');
                $this->form_validation->set_rules('label_alias', '标签别名', 'required|min_length[2]|max_length[10]');
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
                            $data['message'] = '采集器修改成功';
                        } else {
                            $data['message'] = '采集器修改失败';
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
                                $data['message'] = '采集器修改成功';
                            } else {
                                $data['message'] = '采集器修改失败';
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
                $session_data = $this->session->all_userdata();
                if ($session_data['login_user_type'] == 'admin') {
                    $sql = 'l.*';
                    $table = 'labels l';
                    $join_table = array();
                    $join_field = array();
                    $join_type = array();
                    $where = FALSE;
                    $order = array(
                        'field' => 'l.host_code,l.label_id',
                        'type' => 'desc'
                    );
                    $per_page = 20;
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
                    $where = array(
                        'key' => 'u2h.user_id',
                        'value' => $session_data['login_user_id']
                    );
                    $order = FALSE;
                    $query = $this->Users_model->show_join($table, $sql, $join_table, $join_field, $join_type, $where);
                    $row = $query->row();
                    $config['total_rows'] = $row->num;
                    
                    $sql = 'l.*';
                    $order = array(
                        'field' => 'l.host_code,l.label_id',
                        'type' => 'desc'
                    );
                    $per_page = 20;
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
                    $query = $this->Users_model->show_join($table, $sql, $join_table, $join_field, $join_type, $where, $order, $per_page, $type);
                } else {
                    $query = $this->Users_model->show_join($table, $sql, $join_table, $join_field, $join_type, $where, $order, $per_page, 0);
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

    function rules($type = 'default', $rule_id = NULL)
    {
        if ($this->session->userdata('is_login')) {
            $data['title'] = '报警规则管理';
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
                $this->form_validation->set_rules('host_code', '主机编码', 'required|min_length[2]|max_length[20]');
                $this->form_validation->set_rules('label_code', '标签编码', 'required|min_length[2]|max_length[10]');
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
                $table = 'rules r';
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
                $this->form_validation->set_rules('host_code', '主机编码', 'required|min_length[2]|max_length[20]');
                $this->form_validation->set_rules('label_code', '采集器编码', 'required|min_length[2]|max_length[10]');
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
                            $data['message'] = '报警规则修改成功';
                        } else {
                            $data['message'] = '报警规则修改失败';
                        }
                    } else {
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
                                $data['message'] = '报警规则修改成功';
                            } else {
                                $data['message'] = '报警规则修改失败';
                            }
                        }
                    }
                }
                $sql = 'r.*';
                $table = 'rules r';
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
            } else {
                $session_data = $this->session->all_userdata();
                if ($session_data['login_user_type'] == 'admin') {
                    $sql = 'r.*';
                    $table = 'rules r';
                    $join_table = array();
                    $join_field = array();
                    $join_type = array();
                    $where = FALSE;
                    $order = array(
                        'field' => 'r.host_code,r.rule_id',
                        'type' => 'desc'
                    );
                    $per_page = 20;
                    $config['total_rows'] = $this->db->count_all('rules');
                } else {
                    $sql = 'count(*) as num';
                    $table = 'rules r';
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
                    $query = $this->Users_model->show_join($table, $sql, $join_table, $join_field, $join_type, $where, $order, $per_page, $type);
                } else {
                    $query = $this->Users_model->show_join($table, $sql, $join_table, $join_field, $join_type, $where, $order, $per_page, 0);
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

    function devices($type = 'default')
    {
        if ($this->session->userdata('is_login')) {
            $data['title'] = '主机管理';
            $data['login_user'] = $this->session->userdata('login_user');
            $data['login_alias'] = $this->session->userdata('login_alias');
            $data['login_user_id'] = $this->session->userdata('login_user_id');
            $data['login_user_type'] = $this->session->userdata('login_user_type');
            $data['right_tpl'] = 'hosts';
            $session_data = $this->session->all_userdata();
            
            // get setting value
            if ($type == 'json') {
                if ($session_data['login_user_id'] != 'admin') {
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
            } elseif ($type == 'chart') {
                $begin_date = trim($this->input->post('begin_date'));
                $end_date = trim($this->input->post('end_date'));
                if($begin_date == $end_date && ! empty($begin_date)) {
                  $begin_date = $begin_date . ' 00:00:00';
                  $end_date = $end_date . ' 23:59:59';
                }
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
                        'value2' => '#FF7F00',
                        'value3' => '#FFFF00',
                        'value4' => '#00FF00',
                        'value5' => '#00FFFF',
                        'value6' => '#0000FF',
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
                        $sql .= ' LIMIT 1000';
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
                        foreach ($query2->result() as $row2) {
                            if ($i == $j) {
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
                            array_push($y_text, $pre_text . '温度 ');
                        }
                        if ($alert_2 == 1) {
                            $code = $host_code . '_' . $label_code . '_value2';
                            array_unshift($$code, $colors['value2']);
                            $devices[] = $$code;
                            array_push($y_text, $pre_text . '湿度 ');
                        }
                        if ($alert_3 == 1) {
                            $code = $host_code . '_' . $label_code . '_value3';
                            array_unshift($$code, $colors['value3']);
                            $devices[] = $$code;
                            array_push($y_text, $pre_text . '电压 ');
                        }
                        if ($alert_4 == 1) {
                            $code = $host_code . '_' . $label_code . '_value4';
                            array_unshift($$code, $colors['value4']);
                            $devices[] = $$code;
                            array_push($y_text, $pre_text . '自定义 ');
                        }
                        if ($alert_5 == 1) {
                            $code = $host_code . '_' . $label_code . '_value5';
                            array_unshift($$code, $colors['value5']);
                            $devices[] = $$code;
                            array_push($y_text, $pre_text . '温度A ');
                        }
                        if ($alert_6 == 1) {
                            $code = $host_code . '_' . $label_code . '_value6';
                            array_unshift($$code, $colors['value6']);
                            $devices[] = $$code;
                            array_push($y_text, $pre_text . '温度B ');
                        }
                        if ($alert_7 == 1) {
                            $code = $host_code . '_' . $label_code . '_value7';
                            array_unshift($$code, $colors['value7']);
                            $devices[] = $$code;
                            array_push($y_text, $pre_text . '直流电压 ');
                        }
                        if ($alert_8 == 1) {
                            $code = $host_code . '_' . $label_code . '_value8';
                            array_unshift($$code, $colors['value8']);
                            $devices[] = $$code;
                            array_push($y_text, $pre_text . '交流电压 ');
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
            } elseif ($type == 'datagrid') {
                $begin_date = trim($this->input->post('begin_date'));
                $end_date = trim(strtolower($this->input->post('end_date')));
                if($begin_date == $end_date && ! empty($begin_date)) {
                  $begin_date = $begin_date . ' 00:00:00';
                  $end_date = $end_date . ' 23:59:59';
                }
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
                $page = $this->input->post('page') > 0 ? $this->input->post('page') : 1;
                $rows = $this->input->post('rows') > 0 ? $this->input->post('rows') : 20;
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
                        $sql = 'SELECT * FROM ' . $table;
                        if (! empty($start_date) && ! empty($end_date) && $end_date >= $start_date) {
                            $sql .= ' WHERE UNIX_TIMESTAMP(label_time) BETWEEN ' . $start_date . ' AND ' . $end_date;
                        }
                        // $sql .= ' ORDER BY UNIX_TIMESTAMP(add_time) ' . $order_type;
                        $sql .= ' LIMIT 1000';
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
                            if ($i == $j) {
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
                            }
                            $i ++;
                        }
                    }
                }
                $results = array();
                $results['total'] = count($devices);
                $results['rows'] = $devices;
                echo json_encode($results);
            } elseif ($type == 'export') {
                $this->load->library('excel');
                $titles = array(
                    iconv("UTF-8", "gb2312", '主机编号'),
                    iconv("UTF-8", "gb2312", '标签地址'),
                    iconv("UTF-8", "gb2312", '一路(温度)'),
                    iconv("UTF-8", "gb2312", '二路(湿度)'),
                    iconv("UTF-8", "gb2312", '三路(电压)'),
                    iconv("UTF-8", "gb2312", '四路(自定义)'),
                    iconv("UTF-8", "gb2312", '五路(温度A)'),
                    iconv("UTF-8", "gb2312", '六路(温度B)'),
                    iconv("UTF-8", "gb2312", '七路(直流电压)'),
                    iconv("UTF-8", "gb2312", '八路(交流电压)'),
                    iconv("UTF-8", "gb2312", '采集时间')
                );
                
                $param = $this->uri->segment(4);
                parse_str($param);
                if($begin_date == $end_date && ! empty($begin_date)) {
                  $begin_date = $begin_date . ' 00:00:00';
                  $end_date = $end_date . ' 23:59:59';
                }
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
                        $table = 'zhistorydata_' . $host_code . '_' . $label_code;
                        $sql = 'SELECT * FROM ' . $table;
                        if (! empty($start_date) && ! empty($end_date) && $end_date >= $start_date) {
                            $sql .= ' WHERE UNIX_TIMESTAMP(label_time) BETWEEN ' . $start_date . ' AND ' . $end_date;
                        }
                        // $sql .= ' ORDER BY UNIX_TIMESTAMP(add_time) ' . $order_type;
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
                            if ($i == $j) {
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
                            }
                            $i ++;
                        }
                    }
                }
                $array = $devices;
                
                $this->excel->filename = $host_code . '_' . $label_code . '_' . date('Y-m-d_H:i:s');
                $this->excel->make_from_array($titles, $array);
            } else {
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
                $devices = array();
                foreach ($query->result() as $row) {
                    $tmp = array();
                    $tmp['id'] = - 1;
                    if ($row->is_online == 1) {
                        $desc = '(在线)';
                    } else {
                        $desc = '(离线)';
                    }
                    if (! empty($row->host_alias)) {
                        $tmp['text'] = $row->host_alias . '[' . $row->host_code . ']';
                    } else {
                        $tmp['text'] = $row->host_code;
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
                            $tmp2['text'] = $row2->label_alias . '[' . $row2->label_code . ']';
                        } else {
                            $tmp2['text'] = $row2->label_code;
                        }
                        $children[] = $tmp2;
                    }
                    $tmp['children'] = $children;
                    $devices[] = $tmp;
                }
                // echo $this->db->last_query();
                // print_r($devices);
                $data['devices'] = $devices;
                $this->load->view('header', $data);
                $this->load->view('devices', $data);
                $this->load->view('footer', $data);
            }
        } else {
            $data['title'] = '登录';
            $this->load->view('index_view', $data);
        }
    }
}
