<?php if($type == 'rule') : ?>
<table width="100%" cellpadding="0" cellspacing="0" border="0">
  <tr>
    <td class="left" valign="top"><?php 
    if(isset($login_user_type)) { 
      if($login_user_type == 'admin') {
        $this->load->view ( 'left_admin'); 
      } else {
        $this->load->view ( 'left_user'); 
      }
    }
    ?></td>
    <td valign="top">
    <?php if (isset($row->user_id)) {echo form_open('member/add_user/' . $row->user_id); } else { echo form_open('member/add_user');} ?>
    <table class="tableBorder" cellpadding="0" cellspacing="0">
    <caption>短信报警设置</caption>
        <tr>
          <td width="20%" align="right">是否开启短信报警:</td>
          <td width="80%" style="PADDING-LEFT:10px">
          <select name="message_alert">
              <?php
              if (isset($row->message_alert)) {
                  if ($row->message_alert == 1) {
                    echo '<option value="' . $row->message_alert . '">是</option>';
                  } else {
                    echo '<option value="' . $row->message_alert . '">否</option>';
                  }
                  
              }
              ?>
              <option value="1">是</option>
              <option value="0">否</option>
            </select>
        </tr>
        <tr>
          <td width="20%" align="right">报警手机号1:</td>
          <td width="80%" style="PADDING-LEFT:10px"><input  name="alert_phone1" type="text" size="33" value="<?php if (isset($row->alert_phone1)) echo $row->alert_phone1;?>" ></td>
        </tr>
        <tr>
          <td width="20%" align="right">报警手机号2:</td>
          <td width="80%" style="PADDING-LEFT:10px"><input  name="alert_phone2" type="text" size="33" value="<?php if (isset($row->alert_phone2)) echo $row->alert_phone2;?>" ></td>
        </tr>
        <tr>
          <td width="20%" align="right">报警手机号3:</td>
          <td width="80%" style="PADDING-LEFT:10px"><input  name="alert_phone3" type="text" size="33" value="<?php if (isset($row->alert_phone3)) echo $row->alert_phone3;?>" ></td>
        </tr>
        <tr>
          <td width="20%" align="right">短信报警监听内容:</td>
          <td width="80%" style="PADDING-LEFT:10px">
          <?php 
          $arr_alert = array('温度','湿度','电压','颗粒物PM2.5','颗粒物PM10','负氧离子数量','有机污染物(甲醛)','空气质量等级','断网');
          if (isset($row->alert_content) && ! empty($row->alert_content)) {
            $content = str_split($row->alert_content);
            for($i=0; $i<9; $i++) {
              if((int)$content[$i] == 1) {
                echo '<input type="checkbox" name="alert_' . $i . '" checked="checked"  />' . $arr_alert[$i];
              } else {
                echo '<input type="checkbox" name="alert_' . $i . '" />' . $arr_alert[$i];
              }
            }
          } else {
            for($i=0; $i<9; $i++) {
              echo '<input type="checkbox" name="alert_' . $i . '"  />' . $arr_alert[$i];
            }            
          }
          ?>          
          </td>
        </tr>                
        <tr>
          <td height="30"></td>
          <td height="30" style="PADDING-LEFT: 10px"><input type="submit" name="Submit" value="确认"  > <?php if(isset($message)) echo $message ?></td>
        </tr>
      </table>
      <input type="hidden" name="action" value="<?php if (isset($row->user_id)) echo 'update'; else echo 'add';?>"  >
      <input type="hidden" name="type" value="rule"  >
      <?php echo form_close() ?>

      <?php echo form_open('member/rules/delete'); ?>
      <table class="tableBorder" cellpadding="0" cellspacing="0">
        <caption>报警规则设置</caption>
        <thead>
           <th>主机编号</th>
          <th>采集器编号</th>
          <th>一路报警</th>
          <th>温度下限</th>
          <th>温度上限</th>
          <th>二路报警</th>
          <th>湿度下限</th>
          <th>湿度上限</th>
          <th>三路报警</th>
          <th>电压下限</th>
          <th>电压上限</th>
          <th>四路报警</th>
          <th>颗粒物PM2.5下限</th>
          <th>颗粒物PM2.5上限</th>
          <th>五路报警</th>
          <th>颗粒物PM10下限</th>
          <th>颗粒物PM10上限</th>
          <th>六路报警</th>
          <th>负氧离子数量下限</th>
          <th>负氧离子数量上限</th>
          <th>七路报警</th>
          <th>有机污染物(甲醛)下限</th>
          <th>有机污染物(甲醛)上限</th>
          <th>八路报警</th>
          <th>空气质量等级下限</th>
          <th>空气质量等级上限</th>
          <th>断网报警</th>
          <th>断网时长</th>
          <th>入库时间</th>
          <th><?php if(isset($login_user_type)) { 
                          if($login_user_type == 'admin') {
          echo '操作';
          }
          }
          ?></thead>
        </thead>
        <?php if (isset($query2)) :?>
        <?php foreach ($query2->result() as $row) :?>
        <tr>
          <td align="center"  class="red"><?php echo anchor('member/rules/edit/' . $row->rule_id, $row->host_code);?></td>          
          <td align="center"  class="red"><?php echo anchor('member/rules/edit/' . $row->rule_id, $row->label_code);?></td>
          <td align="center"><?php if ($row->alert_1 == 1) echo '是'; else echo '否'?></td>
          <td align="center"><?php echo $row->min_1;?></td>
          <td align="center"><?php echo $row->max_1;?></td>
          <td align="center"><?php if ($row->alert_2 == 1) echo '是'; else echo '否'?></td>
          <td align="center"><?php echo $row->min_2;?></td>
          <td align="center"><?php echo $row->max_2;?></td>
          <td align="center"><?php if ($row->alert_3 == 1) echo '是'; else echo '否'?></td>
          <td align="center"><?php echo $row->min_3;?></td>
          <td align="center"><?php echo $row->max_3;?></td>
          <td align="center"><?php if ($row->alert_4 == 1) echo '是'; else echo '否'?></td>
          <td align="center"><?php echo $row->min_4;?></td>
          <td align="center"><?php echo $row->max_4;?></td>
          <td align="center"><?php if ($row->alert_5 == 1) echo '是'; else echo '否'?></td>
          <td align="center"><?php echo $row->min_5;?></td>
          <td align="center"><?php echo $row->max_5;?></td>
          <td align="center"><?php if ($row->alert_6 == 1) echo '是'; else echo '否'?></td>
          <td align="center"><?php echo $row->min_6;?></td>
          <td align="center"><?php echo $row->max_6;?></td>
          <td align="center"><?php if ($row->alert_7 == 1) echo '是'; else echo '否'?></td>
          <td align="center"><?php echo $row->min_7;?></td>
          <td align="center"><?php echo $row->max_7;?></td>
          <td align="center"><?php if ($row->alert_8 == 1) echo '是'; else echo '否'?></td>
          <td align="center"><?php echo $row->min_8?></td>
          <td align="center"><?php echo $row->max_8;?></td>
          <td align="center"><?php if ($row->is_active == 1) echo '是'; else echo '否'?></td>
          <td align="center"><?php echo $row->active_timeout;?></td>
          <td align="center"><?php echo $row->add_time;?></td>                  
          <td align="center">
          <?php if(isset($login_user_type)) { 
                          if($login_user_type == 'admin') {
          echo form_checkbox('check[]', $row->rule_id);
          }
          }
          ?></td>
        </tr>
        <?php endforeach;?>
        <?php endif;?>
      </table>
      <?php if(isset($login_user_type)) { 
        if($login_user_type == 'admin') { 
      ?>
      <div style="text-align:center;">全选 <input id="chkAll" type="checkbox" onclick="CheckAll(this.form)" value="chkAll" name="chkAll"> <input type="submit" name="Submit" value="删除所选" onclick="return confirm('是否确定？');"  />
       <?php if(isset($login_user_type)) { 
                          if($login_user_type == 'admin') {
                            echo anchor('member/rules/add', img('public/images/icons/add.png') . '添加');
                          }
                        }
                        ?>
      </div>
      <?php
        }
      }
      ?>      
      <div style="padding-left:20px;">点击下划线列数据处编辑</div>
      <?php echo form_close() ?>
      </td>
  </tr>
</table>

<?php else : ?>

<table width="100%" cellpadding="0" cellspacing="0" border="0">
  <tr>
    <td class="left" valign="top"><?php 
    if(isset($login_user_type)) { 
      if($login_user_type == 'admin') {
        $this->load->view ( 'left_admin'); 
      } else {
        $this->load->view ( 'left_user'); 
      }
    }
    ?></td>
    <td valign="top">
    <?php if (isset($row->user_id)) {echo form_open('member/add_user/' . $row->user_id); } else { echo form_open('member/add_user');} ?>
    <table width="100%" border="0" cellpadding="2" cellspacing="5" class="table">
    <caption>客户信息</caption>
        <tr>
          <td width="20%" align="right">登陆账号:</td>
          <td width="80%" style="PADDING-LEFT:10px"><input  name="loginname" type="text" size="33" value="<?php if (isset($row->loginname)) echo $row->loginname;?>" ><?php echo form_error('loginname');?></td>
        </tr>
        <tr>
          <td width="20%" align="right">密码：</td>
          <td width="80%" style="PADDING-LEFT:10px"><input  name="password" type="password" size="33" value="" ><?php echo form_error('password');?>  <?php if (isset($row->user_id)) {echo '如果不改密码，直接留空'; }?> </td>
        </tr>
        <tr>
          <td width="20%" align="right">用户名称:</td>
          <td width="80%" style="PADDING-LEFT:10px"><input  name="alias" type="text" size="33" value="<?php if (isset($row->alias)) echo $row->alias;?>" ><?php echo form_error('alias');?></td>
        </tr>
        <tr>
          <td width="20%" align="right">用户类别:</td>
          <td width="80%" style="PADDING-LEFT:10px">
          <select name="user_type">
              <?php
              if (isset($row->user_type)) {
                  if ($row->user_type == 'admin') {
                    echo '<option value="' . $row->user_type . '">管理员</option>';
                  } else {
                    echo '<option value="' . $row->user_type . '">普通用户</option>';
                  }                  
              }
              ?>
              <option value="user">普通用户</option>
            </select>
          </td>
        </tr>
        <tr>
          <td width="20%" align="right">联系方式:</td>
          <td width="80%" style="PADDING-LEFT:10px"><input  name="contact" type="text" size="33" value="<?php if (isset($row->contact)) echo $row->contact;?>" ></td>
        </tr>
        <tr>
          <td width="20%" align="right">是否冻结账号:</td>
          <td width="80%" style="PADDING-LEFT:10px">
          <select name="status">
              <?php
              if (isset($row->status)) {
                  if ($row->status == 1) {
                    echo '<option value="' . $row->status . '">是</option>';
                  } else {
                    echo '<option value="' . $row->status . '">否</option>';
                  }
                  
              }
              ?>
              <option value="1">是</option>
              <option value="0">否</option>
            </select>
          </td>
        </tr>
        <tr>
          <td width="20%" align="right">是否开启短信报警:</td>
          <td width="80%" style="PADDING-LEFT:10px">
          <select name="message_alert">
              <?php
              if (isset($row->message_alert)) {
                  if ($row->message_alert == 1) {
                    echo '<option value="' . $row->message_alert . '">是</option>';
                  } else {
                    echo '<option value="' . $row->message_alert . '">否</option>';
                  }
                  
              }
              ?>
              <option value="1">是</option>
              <option value="0">否</option>
            </select>
        </tr>
        <tr>
          <td width="20%" align="right">报警手机号1:</td>
          <td width="80%" style="PADDING-LEFT:10px"><input  name="alert_phone1" type="text" size="33" value="<?php if (isset($row->alert_phone1)) echo $row->alert_phone1;?>" ></td>
        </tr>
        <tr>
          <td width="20%" align="right">报警手机号2:</td>
          <td width="80%" style="PADDING-LEFT:10px"><input  name="alert_phone2" type="text" size="33" value="<?php if (isset($row->alert_phone2)) echo $row->alert_phone2;?>" ></td>
        </tr>
        <tr>
          <td width="20%" align="right">报警手机号3:</td>
          <td width="80%" style="PADDING-LEFT:10px"><input  name="alert_phone3" type="text" size="33" value="<?php if (isset($row->alert_phone3)) echo $row->alert_phone3;?>" ></td>
        </tr>
        <tr>
          <td width="20%" align="right">短信报警监听内容:</td>
          <td width="80%" style="PADDING-LEFT:10px">
          <?php 
           $arr_alert = array('温度','湿度','电压','颗粒物PM2.5','颗粒物PM10','负氧离子数量','有机污染物(甲醛)','空气质量等级','断网');
          if (isset($row->alert_content) && ! empty($row->alert_content)) {
            $content = str_split($row->alert_content);
            for($i=0; $i<9; $i++) {
              if((int)$content[$i] == 1) {
                echo '<input type="checkbox" name="alert_' . $i . '" checked="checked"  />' . $arr_alert[$i];
              } else {
                echo '<input type="checkbox" name="alert_' . $i . '" />' . $arr_alert[$i];
              }
            }
          } else {
            for($i=0; $i<9; $i++) {
              echo '<input type="checkbox" name="alert_' . $i . '"  />' . $arr_alert[$i];
            }            
          }
          ?>          
          </td>
        </tr>
        <tr>
          <td width="20%" align="right">备注:</td>
          <td width="80%" style="PADDING-LEFT:10px"><textarea  name="note" rows="5" cols="30"><?php if (isset($row->note)) echo $row->note;?></textarea></td>
        </tr>        
        <tr>
          <td height="30"></td>
          <td height="30" style="PADDING-LEFT: 10px"><input type="submit" name="Submit" value="确认"  > <?php if(isset($message)) echo $message ?></td>
        </tr>
      </table>
      <input type="hidden" name="action" value="<?php if (isset($row->user_id)) echo 'update'; else echo 'add';?>"  >
      <input type="hidden" name="type" value="user"  >
      <input type="hidden" name="loginname2" size="33" value="<?php if (isset($row->loginname)) echo $row->loginname;?>" >
      <?php echo form_close() ?>
      </td>
  </tr>
</table>

<?php endif;?> 