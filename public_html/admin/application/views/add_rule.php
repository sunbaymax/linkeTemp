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
    <?php if (isset($row->rule_id)) {echo form_open('member/rules/update/' . $row->rule_id); } else { echo form_open('member/rules/add');} ?>
    <table class="tableBorder" cellpadding="0" cellspacing="0">
        <caption>报警规则管理</caption>
        <tr>
          <td width="20%" align="right">主机编码:</td>
          <td width="80%" style="PADDING-LEFT: 10px">
        <?php
		if (isset ( $login_user_type )) {
			if (false){   //zxg 不管是谁登陆 前两项就是不能更改！   $login_user_type == 'admin') {
				if (isset ( $query2 )) :
					?>
                          <select name="host_code">
                          <?php foreach ($query2->result() as $row2) :?>
                                <?php if ($row->host_code == $row2->host_code) {?>
                                <option value="<?php echo $row2->host_code;?>" selected="selected"><?php echo $row2->host_code;?></option>
                                <?php } else { ?>
                                <option value="<?php echo $row2->host_code;?>"><?php echo $row2->host_code;?></option>
                                <?php } ?>
                          <?php endforeach;?>
                          </select>          
        		<?php endif;
    		} 
    		else {
    		?>
                <?php if (isset($row->host_code)) echo $row->host_code;?>
                <input name="host_code" type="hidden" value="<?php if (isset($row->host_code)) echo $row->host_code;?>" >       
            <?php
			}
		}
		?>                      

		<?php echo form_error('host_code');?></td>
        </tr>
        <tr>
          <td width="12%" align="right">采集器编号:</td>
          <td width="88%" style="PADDING-LEFT: 10px">
    		  <?php if(isset($login_user_type)) { 
                if(false){   //zxg 不管是谁登陆 前两项就是不能更改！  $login_user_type == 'admin') { ?>
                    <input name="label_code" type="text" size="33" value="<?php if (isset($row->label_code)) echo $row->label_code;?>"><?php echo form_error('label_code');?>            
                <?php              
                } else { ?>
                <?php if (isset($row->label_code)) echo $row->label_code;?>
                    <input name="label_code" type="hidden" value="<?php if (isset($row->label_code)) echo $row->label_code;?>">            
    			<?php               
                }
              }
              ?>
		  </td>
        </tr>
        <tr>
          <td width="12%" align="right">一路报警是否启用:</td>
          <td width="88%" style="PADDING-LEFT:10px">
          <select name="alert_1">
            <?php
            if (isset($row->alert_1)) {
                if ($row->alert_1 == 1) {
                  echo '<option value="' . $row->alert_1 . '">是</option>';
                } else {
                  echo '<option value="' . $row->alert_1 . '">否</option>';
                } 
            }
            ?>
            <option value="1">是</option>
            <option value="0">否</option>
          </select>
          </td>
        </tr>
        <tr>
          <td width="12%" align="right">一路数据(温度)下限:</td>
          <td width="88%" style="PADDING-LEFT:10px"><input  name="min_1" type="text" size="33" value="<?php if (isset($row->min_1)) echo $row->min_1;?>"><td>
        </tr>
        <tr>
          <td width="12%" align="right">一路数据(温度)上限:</td>
          <td width="88%" style="PADDING-LEFT:10px"><input  name="max_1" type="text" size="33" value="<?php if (isset($row->max_1)) echo $row->max_1;?>" ></td>
        </tr>
        <tr>
          <td width="12%" align="right">二路报警是否启用:</td>
          <td width="88%" style="PADDING-LEFT:10px">
          <select name="alert_2">
            <?php
            if (isset($row->alert_2)) {
                if ($row->alert_2 == 1) {
                  echo '<option value="' . $row->alert_2 . '">是</option>';
                } else {
                  echo '<option value="' . $row->alert_2 . '">否</option>';
                }
                
            }
            ?>
            <option value="1">是</option>
            <option value="0">否</option>
          </select>
          </td>
        </tr>
        <tr>
          <td width="12%" align="right">二路数据(湿度)下限:</td>
          <td width="88%" style="PADDING-LEFT:10px"><input  name="min_2" type="text" size="33" value="<?php if (isset($row->min_2)) echo $row->min_2;?>"><td>
        </tr>
        <tr>
          <td width="12%" align="right">二路数据(湿度)上限:</td>
          <td width="88%" style="PADDING-LEFT:10px"><input  name="max_2" type="text" size="33" value="<?php if (isset($row->max_2)) echo $row->max_2;?>" ></td>
        </tr>
        <tr>
          <td width="12%" align="right">三路报警是否启用:</td>
          <td width="88%" style="PADDING-LEFT:10px">
          <select name="alert_3">
            <?php
            if (isset($row->alert_3)) {
                if ($row->alert_3 == 1) {
                  echo '<option value="' . $row->alert_3 . '">是</option>';
                } else {
                  echo '<option value="' . $row->alert_3 . '">否</option>';
                }
                
            }
            ?>
            <option value="1">是</option>
            <option value="0">否</option>
          </select>
          </td>
        </tr>
        <tr>
          <td width="12%" align="right">三路数据(电压)下限:</td>
          <td width="88%" style="PADDING-LEFT:10px"><input  name="min_3" type="text" size="33" value="<?php if (isset($row->min_3)) echo $row->min_3;?>"><td>
        </tr>
        <tr>
          <td width="12%" align="right">三路数据(电压)上限:</td>
          <td width="88%" style="PADDING-LEFT:10px"><input  name="max_3" type="text" size="33" value="<?php if (isset($row->max_3)) echo $row->max_3;?>" ></td>
        </tr>
        <tr>
          <td width="12%" align="right">四路报警是否启用:</td>
          <td width="88%" style="PADDING-LEFT:10px">
          <select name="alert_4">
            <?php
            if (isset($row->alert_4)) {
                if ($row->alert_4 == 1) {
                  echo '<option value="' . $row->alert_4 . '">是</option>';
                } else {
                  echo '<option value="' . $row->alert_4 . '">否</option>';
                }
                
            }
            ?>
            <option value="1">是</option>
            <option value="0">否</option>
          </select>
          </td>
        </tr>
        <tr>
           <td width="12%" align="right">四路数据(颗粒物PM2.5)下限:</td>
          <td width="88%" style="PADDING-LEFT:10px"><input  name="min_4" type="text" size="33" value="<?php if (isset($row->min_4)) echo $row->min_4;?>"><td>
        </tr>
        <tr>
          <td width="12%" align="right">四路数据(颗粒物PM2.5)上限:</td>
          <td width="88%" style="PADDING-LEFT:10px"><input  name="max_4" type="text" size="33" value="<?php if (isset($row->max_4)) echo $row->max_4;?>" ></td>
        </tr>
        <tr>
          <td width="12%" align="right">五路报警是否启用:</td>
          <td width="88%" style="PADDING-LEFT:10px">
          <select name="alert_5">
            <?php
            if (isset($row->alert_5)) {
                if ($row->alert_5 == 1) {
                  echo '<option value="' . $row->alert_5 . '">是</option>';
                } else {
                  echo '<option value="' . $row->alert_5 . '">否</option>';
                }
                
            }
            ?>
            <option value="1">是</option>
            <option value="0">否</option>
          </select>
          </td>
        </tr>
        <tr>
          <td width="12%" align="right">五路数据(颗粒物PM10)下限:</td>
          <td width="88%" style="PADDING-LEFT:10px"><input  name="min_5" type="text" size="33" value="<?php if (isset($row->min_5)) echo $row->min_5;?>"><td>
        </tr>
        <tr>
          <td width="12%" align="right">五路数据(颗粒物PM10)上限:</td>
          <td width="88%" style="PADDING-LEFT:10px"><input  name="max_5" type="text" size="33" value="<?php if (isset($row->max_5)) echo $row->max_5;?>" ></td>
        </tr>
        <tr>
          <td width="12%" align="right">六路报警是否启用:</td>
          <td width="88%" style="PADDING-LEFT:10px">
          <select name="alert_6">
            <?php
            if (isset($row->alert_6)) {
                if ($row->alert_6 == 1) {
                  echo '<option value="' . $row->alert_6 . '">是</option>';
                } else {
                  echo '<option value="' . $row->alert_6 . '">否</option>';
                }
                
            }
            ?>
            <option value="1">是</option>
            <option value="0">否</option>
          </select>
          </td>
        </tr>
        <tr>
          <td width="12%" align="right">六路数据(负氧离子数量)下限:</td>
          <td width="88%" style="PADDING-LEFT:10px"><input  name="min_6" type="text" size="33" value="<?php if (isset($row->min_6)) echo $row->min_6;?>"><td>
        </tr>
        <tr>
          <td width="12%" align="right">六路数据(负氧离子数量)上限:</td>
          <td width="88%" style="PADDING-LEFT:10px"><input  name="max_6" type="text" size="33" value="<?php if (isset($row->max_6)) echo $row->max_6;?>" ></td>
        </tr>
        <tr>
          <td width="12%" align="right">七路报警是否启用:</td>
          <td width="88%" style="PADDING-LEFT:10px">
          <select name="alert_7">
            <?php
            if (isset($row->alert_7)) {
                if ($row->alert_7 == 1) {
                  echo '<option value="' . $row->alert_7 . '">是</option>';
                } else {
                  echo '<option value="' . $row->alert_7 . '">否</option>';
                }
                
            }
            ?>
            <option value="1">是</option>
            <option value="0">否</option>
          </select>
          </td>
        </tr>
        <tr>
         <td width="12%" align="right">七路数据(有机污染物(甲醛))下限:</td>
          <td width="88%" style="PADDING-LEFT:10px"><input  name="min_7" type="text" size="33" value="<?php if (isset($row->min_7)) echo $row->min_7;?>"><td>
        </tr>
        <tr>
          <td width="12%" align="right">七路数据(有机污染物(甲醛))上限:</td>
          <td width="88%" style="PADDING-LEFT:10px"><input  name="max_7" type="text" size="33" value="<?php if (isset($row->max_7)) echo $row->max_7;?>" ></td>
        </tr>
        <tr>
          <td width="12%" align="right">八路报警是否启用:</td>
          <td width="88%" style="PADDING-LEFT:10px">
          <select name="alert_8">
            <?php
            if (isset($row->alert_8)) {
                if ($row->alert_8 == 1) {
                  echo '<option value="' . $row->alert_8 . '">是</option>';
                } else {
                  echo '<option value="' . $row->alert_8 . '">否</option>';
                }
                
            }
            ?>
            <option value="1">是</option>
            <option value="0">否</option>
          </select>
          </td>
        </tr>
        <tr>
          <td width="12%" align="right">八路数据(空气质量等级)下限:</td>
          <td width="88%" style="PADDING-LEFT:10px"><input  name="min_8" type="text" size="33" value="<?php if (isset($row->min_8)) echo $row->min_8;?>"><td>
        </tr>
        <tr>
          <td width="12%" align="right">八路数据(空气质量等级)上限:</td>
          <td width="88%" style="PADDING-LEFT:10px"><input  name="max_8" type="text" size="33" value="<?php if (isset($row->max_8)) echo $row->max_8;?>" ></td>
        </tr>
        <tr>
          <td width="12%" align="right">标签断网检测报警是否启用:</td>
          <td width="88%" style="PADDING-LEFT:10px">
          <select name="is_active">
            <?php
            if (isset($row->is_active)) {
                if ($row->is_active == 1) {
                  echo '<option value="' . $row->is_active . '">是</option>';
                } else {
                  echo '<option value="' . $row->is_active . '">否</option>';
                }
                
            }
            ?>
            <option value="1">是</option>
            <option value="0">否</option>
          </select>
          </td>
        </tr>
        <tr>
          <td width="12%" align="right">标签断网检测报警:</td>
          <td width="88%" style="PADDING-LEFT:10px"><input  name="active_timeout" type="text" size="33" value="<?php if (isset($row->active_timeout)) echo $row->active_timeout;?>"> 标签多少秒没有传数据视为断网<td>
        </tr>                    
        <tr>
          <td width="12%" align="right">入库时间:</td>
          <td width="88%" style="PADDING-LEFT:10px"><input  name="add_time" type="text" size="33" value="<?php if (isset($row->add_time)) echo $row->add_time; ?>" ></td>
        </tr>
        <tr>
          <td height="30"></td>
          <td height="30" style="PADDING-LEFT: 10px"><input type="submit" name="Submit" value="确认"  > <?php if(isset($message)) echo $message ?></td>
        </tr>
      </table>
      <input type="hidden" name="host_code2" size="33" value="<?php if (isset($row->host_code)) echo $row->host_code;?>" >
      <input type="hidden" name="label_code2" size="33" value="<?php if (isset($row->label_code)) echo $row->label_code;?>" >
      <?php echo form_close() ?>
      </td>
  </tr>
</table>
