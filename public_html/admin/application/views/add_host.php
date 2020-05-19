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
    <?php if (isset($row->host_id)) {echo form_open('member/hosts/update/' . $row->host_id); } else { echo form_open('member/hosts/add');} ?>
    <table width="100%" border="0" cellpadding="2" cellspacing="5" class="table">
    <caption>主机</caption>
        <tr>
          <td width="12%" align="right">主机编码:</td>
          <td width="88%" style="PADDING-LEFT:10px">
          <?php if(isset($login_user_type)) { 
            if($login_user_type == 'admin') { ?>
            <input name="host_code" type="text" size="33" <?php if (isset($row->host_id)) { echo 'readonly=\'readonly\''; } else { echo ''; };?>  value="<?php if (isset($row->host_code)) echo $row->host_code;?>" > 
            <?php if (isset($row->host_id)) { echo '*不能更改'; } else { echo ''; };?>       
            <?php              
            } else { ?>
            <?php if (isset($row->host_code)) echo $row->host_code;?>
            <input name="host_code" type="hidden" value="<?php if (isset($row->host_code)) echo $row->host_code;?>" >            
			<?php               
            }
          }
          ?>     
          <?php echo form_error('host_code');?></td>
        </tr>
        <tr>
          <td width="12%" align="right">主机别名:</td>
          <td width="88%" style="PADDING-LEFT:10px"><input  name="host_alias" type="text" size="33" value="<?php if (isset($row->host_alias)) echo $row->host_alias;?>" ><?php echo form_error('host_alias');?></td>
        </tr>
        <tr>
          <td width="12%" align="right">主机当前是否在线:</td>
          <td width="88%" style="PADDING-LEFT:10px">          
          <select name="is_online"  <?php if (isset($row->host_id)) { echo 'readonly=\'readonly\''; } else { echo ''; };?>  <?php if($login_user_type != 'admin') { echo 'disabled="disabled"';}?>>
              <?php
              if (isset($row->is_online)) {
                  if ($row->is_online == 1) {
                    echo '<option value="' . $row->is_online . '">是</option>';
                  } else {
                    echo '<option value="' . $row->is_online . '">否</option>';
                  }
                  
              }
              ?>
              <!-- <option value="1">是</option>
              <option value="0">否</option> -->
              <?php if (isset($row->host_id)) { echo ''; } else { echo '<option value="1">是</option><option value="0">否</option>'; };?>
            </select>
            <?php if (isset($row->host_id)) { echo '*不能更改'; } else { echo ''; };?>
          </td>
        </tr>
        <tr>
          <td width="12%" align="right">主机参数:</td>
          <td width="88%" style="PADDING-LEFT:10px"><input  name="params" type="text" size="33" value="<?php if (isset($row->params)) echo $row->params;?>"  <?php if($login_user_type != 'admin') { echo 'disabled="disabled"';}?>></td>
        </tr>        
        <tr>
          <td width="12%" align="right">入库时间:</td>
          <td width="88%" style="PADDING-LEFT:10px"><input  name="add_time" type="text" size="33" value="<?php if (isset($row->add_time)) echo $row->add_time; else echo date('Y-m-d H:i:s'); ?>"  <?php if($login_user_type != 'admin') { echo 'disabled="disabled"';}?>></td>
        </tr>        
        <tr>
          <td width="12%" align="right">城市:</td>
          <td width="88%" style="PADDING-LEFT:10px"><input  name="note" type="text" size="33" placeholder="标准城市名称（城市名不允许带“市”字）" value="<?php if (isset($row->note)) echo $row->note;?>"  <?php if($login_user_type != 'admin') { echo 'disabled="disabled"';}?>>
         <!--  <textarea  name="note" rows="5" cols="30"><?php if (isset($row->note)) echo $row->note;?></textarea> -->
          </td>
        </tr>        
        <tr>
          <td height="30"></td>
          <td height="30" style="PADDING-LEFT: 10px"><input type="submit" name="Submit" value="确认"  > <?php if(isset($message)) echo $message ?></td>
        </tr>
      </table>
      <input type="hidden" name="host_code2" size="33" value="<?php if (isset($row->host_code)) echo $row->host_code;?>" >
      <?php if($login_user_type != 'admin') { ?>
      <input type="hidden" name="is_online" value="<?php if (isset($row->is_online)) echo $row->is_online;?>" >
      <input type="hidden" name="params" value="<?php if (isset($row->params)) echo $row->params;?>" >
      <input type="hidden" name="add_time" value="<?php if (isset($row->add_time)) echo $row->add_time;?>" >
      <?php
      }
      ?>
      <?php echo form_close() ?>
      </td>
  </tr>
</table>
