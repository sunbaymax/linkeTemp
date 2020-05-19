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
  <?php echo form_open('member/user_host/del/' . $user_id) ?>
    <table class="tableBorder" width="90%" border="0" align="center" cellpadding="3" cellspacing="1" bgcolor="#FFFFFF">
        <tr>
          <td colspan="11" align="center" background="../images/admin_bg_1.gif"><b><font color="#5D82B1"><?php echo $title;?></font></b></td>
        </tr>
        <tr>
          <td  align="center" bgcolor="#CADBF1" class="wz01">ID</td>
          <td  align="center" bgcolor="#CADBF1" class="wz01">主机编号</td>
          <td  align="center" bgcolor="#CADBF1" class="wz01">主机别名</td>
          <td  align="center" bgcolor="#CADBF1" class="wz01">操作</td>
        </tr>
        <?php if (isset($query)) :?>
        <?php foreach ($query->result() as $row) :?>
        <tr bgcolor="#E6EEF7" onMouseOver="this.style.backgroundColor='#CADBF1'" onMouseOut="this.style.backgroundColor='#E6EEF7'">
          <td align="center"><?php echo $row->id;?></td>
          <td align="center"><?php echo $row->host_code;?></td>
          <td align="center"><?php echo $row->host_alias;?></td>
          <td align="center"><?php echo form_checkbox('check[]', $row->id);?></td>
        </tr>
        <?php endforeach;?>
		<?php endif;?>
    <tr bgcolor="#E6EEF7" onMouseOver="this.style.backgroundColor='#CADBF1'" onMouseOut="this.style.backgroundColor='#E6EEF7'">
          <td align="center"></td>
          <td align="center" colspan="2">全选 <input id="chkAll" type="checkbox" onclick="CheckAll(this.form)" value="chkAll" name="chkAll"> <input type="submit" name="Submit" value="删除所选" onclick="return confirm('是否确定？');" />  
      </td>
      <td align="center"></td>

        </tr>
      </table>      
      <?php echo form_close() ?>


      <?php echo form_open('member/user_host/add/' . $user_id) ?>
    <table class="tableBorder" width="90%" border="0" align="center" cellpadding="3" cellspacing="1" bgcolor="#FFFFFF">
        <tr>
          <td colspan="11" align="center" background="../images/admin_bg_1.gif"><b><font color="#5D82B1">主机列表</font></b></td>
        </tr>        
        
        <tr bgcolor="#E6EEF7" onMouseOver="this.style.backgroundColor='#CADBF1'" onMouseOut="this.style.backgroundColor='#E6EEF7'">
          <td align="center">
          <?php if (isset($query2)) :?>
          <?php foreach ($query2->result() as $row) :?>
            <?php echo form_checkbox('check2[]', $row->host_id) . $row->host_alias . '('. $row->host_code.')';?>
          <?php endforeach;?>
          <?php endif;?>
          <p>&nbsp;</p>
          <div style="text-align:center;"><input type="submit" name="Submit2" value="添加所选"  /></div>
    </td></tr>            
</table>
<?php echo form_close() ?>
      </td>
  </tr>
</table>



<script language=javascript>
  function CheckAll(form)
  {
    for (var i=0;i<form.elements.length;i++)
    {
    var e = form.elements[i];
    if (e.Name != "chkAll")
       e.checked = form.chkAll.checked;
    }
  }
</script>