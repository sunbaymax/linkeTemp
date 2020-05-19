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
    <td valign="top"><?php echo form_open('member/setting') ?>
      <table width="95%" height="0" border="0" align="left" cellpadding="0" cellspacing="1" bgcolor="#cccccc">
        <tr bgcolor="#ffffff" align="right">
          <td height="30" width="200">参与人数起始值：</td>
          <td align="left">&nbsp;&nbsp <input name="start_num" value="<?php if(isset($start_num)) echo $start_num;?>" type="text" size="20"><?php echo form_error('start_num');?></td>
        </tr>
        <tr bgcolor="#ffffff" align="right">
          <td height="30">抽奖开始时间：</td>
          <td align="left">&nbsp;&nbsp; <input name="start_date" value="<?php if(isset($start_date)) echo $start_date;?>" type="text" size="20"> (格式：2014-12-10 20:10:03) <?php echo form_error('start_date');?></td>
        </tr>
        <tr bgcolor="#ffffff" align="right">
          <td height="30">抽奖结束时间：</td>
          <td align="left">&nbsp;&nbsp;
            <input name="expire_date" value="<?php if(isset($expire_date)) echo $expire_date;?>"  type="text" size="20">
            (格式：2014-12-30 20:10:03) <?php echo form_error('expire_date');?></td>
        </tr>
        <tr bgcolor="#ffffff" align="right">
          <td height="30"></td>
          <td align="left">&nbsp;&nbsp;
            <input type="submit" name="Submit2" value="保存设置">
            <?php if(isset($message)) echo $message ?></td>
        </tr>
      </table>
      <input type="hidden" name="action" value="update" />
      <?php echo form_close() ?></td>
  </tr>
</table>
