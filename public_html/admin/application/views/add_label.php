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
    <?php if (isset($row->label_id)) {echo form_open('member/labels/update/' . $row->label_id); } else { echo form_open('member/labels/add');} ?>
    <table width="100%" border="0" cellpadding="2" cellspacing="5" class="table">
    <caption>标签</caption>
				<tr>
					<td width="12%" align="right">主机编码:</td>
					<td width="88%" style="PADDING-LEFT: 10px">
        <?php
		if (isset ( $login_user_type )) {
			if ($login_user_type == 'admin') {
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
		} else {
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
					<td width="12%" align="right">标签编号:</td>
					<td width="88%" style="PADDING-LEFT: 10px">
					<?php if(isset($login_user_type)) { 
            if($login_user_type == 'admin') { ?>
            <input name="label_code" type="text" <?php if (isset($row->label_id)) { echo 'readonly=\'readonly\''; } else { echo ''; };?>   size="33" value="<?php if (isset($row->label_code)) echo $row->label_code;?>"><?php echo form_error('label_code');?> 
            
            <?php if (isset($row->label_id)) { echo '*不能更改'; } else { echo ''; };?>       
            
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
					<td width="12%" align="right">标签别名:</td>
					<td width="88%" style="PADDING-LEFT: 10px"><input
						name="label_alias" type="text" size="33"
						value="<?php if (isset($row->label_alias)) echo $row->label_alias;?>"><?php echo form_error('label_alias');?></td>
				</tr>
				<tr>
					<td width="12%" align="right">标签功能类别:</td>
					<td width="88%" style="PADDING-LEFT: 10px"><input
						name="label_category" type="text" size="33"
						value="<?php if (isset($row->label_category)) echo $row->label_category;?>" <?php if($login_user_type != 'admin') { echo 'disabled="disabled"';}?>>
					
					<td>
				
				</tr>
				<tr>
					<td width="12%" align="right">标签位置描述:</td>
					<td width="88%" style="PADDING-LEFT: 10px"><input name="label_desc"
						type="text" size="33"
						value="<?php if (isset($row->label_desc)) echo $row->label_desc;?>"></td>
				</tr>
				<tr>
					<td width="12%" align="right">标签参数:</td>
					<td width="88%" style="PADDING-LEFT: 10px"><input
						name="label_param" type="text" size="33"
						value="<?php if (isset($row->label_param)) echo $row->label_param;?>" <?php if($login_user_type != 'admin') { echo 'disabled="disabled"';}?>></td>
				</tr>
				<tr>
					<td width="12%" align="right">入库时间:</td>
					<td width="88%" style="PADDING-LEFT: 10px"><input name="add_time"
						type="text" size="33"
						value="<?php if (isset($row->add_time)) echo $row->add_time; else echo date('Y-m-d H:i:s');?>" <?php if($login_user_type != 'admin') { echo 'disabled="disabled"';}?>></td>
				</tr>
				<tr>
					<td width="12%" align="right">备注:</td>
					<td width="88%" style="PADDING-LEFT: 10px"><textarea name="note"
							rows="5" cols="30"><?php if (isset($row->note)) echo $row->note;?></textarea></td>
				</tr>
				<tr>
					<td height="30"></td>
					<td height="30" style="PADDING-LEFT: 10px"><input type="submit"
						name="Submit" value="确认"> <?php if(isset($message)) echo $message ?></td>
				</tr>
			</table> <input type="hidden" name="host_code2" size="33"
			value="<?php if (isset($row->host_code)) echo $row->host_code;?>"> <input
			type="hidden" name="label_code2" size="33"
			value="<?php if (isset($row->label_code)) echo $row->label_code;?>">
      <?php if($login_user_type != 'admin') { ?>
      <input type="hidden" name="label_category" value="<?php if (isset($row->label_category)) echo $row->label_category;?>" >
      <input type="hidden" name="label_param" value="<?php if (isset($row->label_param)) echo $row->label_param;?>" >
      <input type="hidden" name="add_time" value="<?php if (isset($row->add_time)) echo $row->add_time;?>" >
      <?php } ?>
      <?php echo form_close()?>
      </td>
	</tr>
</table>
