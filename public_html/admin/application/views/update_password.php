<table width="100%" cellpadding="5" cellspacing="5" border="0">
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
    <?php echo form_open('member/update_password'); ?>
    <table width="100%" border="0" cellpadding="2" cellspacing="5" class="table">
    <caption>修改密码</caption>
        <tr>
          <td width="12%" align="right">原密码:</td>
          <td width="88%" style="PADDING-LEFT:10px"><input  name="old_password" type="password" size="33" value="" ><?php echo form_error('old_password');?></td>
        </tr>
        <tr>
          <td width="12%" align="right">新密码：</td>
          <td width="88%" style="PADDING-LEFT:10px"><input  name="password" type="password" size="33" value="" ><?php echo form_error('password');?></td>
        </tr>
        <tr>
          <td width="12%" align="right">确认密码:</td>
          <td width="88%" style="PADDING-LEFT:10px"><input  name="password2" type="password" size="33" value="" ><?php echo form_error('password2');?></td>
        </tr>               
        <tr>
          <td height="30"></td>
          <td height="30" style="PADDING-LEFT: 10px"><input type="submit" name="Submit" value="确认"  > <?php if(isset($message)) echo $message ?></td>
        </tr>
      </table>
      <input type="hidden" name="action" value="update"  >
      <input type="hidden" name="user_id" size="33" value="<?php echo $login_user_id;?>" >
      <?php echo form_close() ?>
      </td>
  </tr>
</table>
 