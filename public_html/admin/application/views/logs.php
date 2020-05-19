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
  <?php echo form_open('member/logs') ?>
    <table class="tableBorder" cellpadding="0" cellspacing="0">
        <caption>网站登录日志 <input type="text" id="keyword" name="keyword" value="用户名" size="20" onblur="search(0)" onfocus="search(1)" /><input type="submit" value="查询" name="submit" /></caption>
        <thead>
          <th>ID</th>
          <th>用户帐号</th>
          <th>用户名称</th>
          <th>登录IP</th>
          <th>时间</th>
        </thead>
        <?php if (isset($query)) :?>
        <?php foreach ($query->result() as $row) :?>
        <tr bgcolor="#E6EEF7" onMouseOver="this.style.backgroundColor='#CADBF1'" onMouseOut="this.style.backgroundColor='#E6EEF7'">
          <td align="center"><?php echo $row->log_id;?></td>
          <td align="center"><?php echo $row->loginname;?></td>
          <td align="center"><?php echo $row->alias;?></td>
          <td align="center"><?php echo $row->ip;?></td>
          <td align="center"><?php echo $row->login_time;?></td>
        </tr>
        <?php endforeach;?>
    <?php endif;?>
    <tr bgcolor="#E6EEF7" onMouseOver="this.style.backgroundColor='#CADBF1'" onMouseOut="this.style.backgroundColor='#E6EEF7'">
          <td align="center"></td>
          <td align="center" colspan="12">      
      <div style="text-align:center;"><?php if (isset($total_rows) && ! empty($total_rows)) echo '总记录数：' . $total_rows;?><?php if (isset($pagination) && ! empty($pagination)) echo '， 第' . $current_page . '页， 分页：' . $pagination;?></div>
      </td>
      <td align="center"></td>
        </tr>
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
<script>
function search(id)
{
	key=document.getElementById("keyword");
	if(id==0)
	{
		if(key.value=="" || key.value=="用户名")
		{
			key.value="用户名";
		}
		if(isSpaceStr(key.value) && key.value!="用户名")
		{
			key.value="用户名";
		}
	}
	else
	{
		if(key.value=="" || key.value=="用户名")
		{
			key.value="";
		}
	}
}
</script>
