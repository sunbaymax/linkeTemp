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
  <?php echo form_open('member/bjinfo') ?>
    <table class="tableBorder" cellpadding="0" cellspacing="0">
        <caption>我的警情            
            &nbsp;&nbsp;&nbsp; 
            <input type="text" id="keyword2" name="keyword2" value="主机编号" size="20" onblur="search2(0)" onfocus="search2(1)" />
            <input type="text" id="keyword" name="keyword" value="标签编号" size="20" onblur="search(0)" onfocus="search(1)" />
            <input type="submit" value="查询" name="submit" />
        </caption>
        <thead>
          <th>ID</th>
          <th>主机编号</th>
		  <th>主机别名</th>
          <th>标签编号</th>
		  <th>标签别名</th>
          <th>报警类型</th>
          <th>报警信息</th>
          <th>报警时间</th>
        </thead>
        <?php if (isset($query)) :?>
        <?php foreach ($query->result() as $row) :?>
        <tr bgcolor="#E6EEF7" onMouseOver="this.style.backgroundColor='#CADBF1'" onMouseOut="this.style.backgroundColor='#E6EEF7'">
          <td align="center"><?php echo $row->log_id;?></td>
          <td align="center"><?php echo $row->host_code;?></td>
		  <td align="center"><?php echo $row->host_code_alias;?></td>
          <td align="center"><?php echo $row->label_code;?></td>
		  <td align="center"><?php echo $row->label_code_alias;?></td>
          <td align="center"><?php echo $row->bj_Type;?></td>
          <td align="center"><?php echo $row->bj_Info;?></td>
          <td align="center"><?php echo $row->bj_Time;?></td>
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
		if(key.value=="" || key.value=="标签编号")
		{
			key.value="标签编号";
		}
		if(isSpaceStr(key.value) && key.value!="标签编号")
		{
			key.value="标签编号";
		}
	}
	else
	{
		if(key.value=="" || key.value=="标签编号")
		{
			key.value="";
		}
	}
}
function search2(id)
{
	key=document.getElementById("keyword2");
	if(id==0)
	{
		if(key.value=="" || key.value=="主机编号")
		{
			key.value="主机编号";
		}
		if(isSpaceStr(key.value) && key.value!="主机编号")
		{
			key.value="主机编号";
		}
	}
	else
	{
		if(key.value=="" || key.value=="主机编号")
		{
			key.value="";
		}
	}
}
</script>
