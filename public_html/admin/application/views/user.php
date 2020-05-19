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
  <?php echo form_open('member/user') ?>
    <table class="tableBorder" cellpadding="0" cellspacing="0">
        <caption>客户资料维护            
            &nbsp;&nbsp;&nbsp; 
            <input type="text" id="keyword2" name="keyword2" value="用户名称" size="20" onblur="search2(0)" onfocus="search2(1)" />
            <input type="text" id="keyword" name="keyword" value="登陆账号" size="20" onblur="search(0)" onfocus="search(1)" />
            <input type="checkbox" id="keyword3" name="keyword3" value=""  onclick="setValue()" />仅显示已冻结账号
            <input type="hidden" name="ffffffffffffffeichu" value=""  >
            <input type="submit" value="查询" name="submit_chaxun" />
        </caption>
        <thead>
          <th>ID</th>
          <th>登陆账号</th>
          <th>用户名称</th>
          <th>用户类别</th>
          <th>联系方式</th>
          <th>是否冻结账号</th>
          <th>是否开启短信报警</th>
          <th>报警手机号1</th>
          <th>报警手机号2</th>
          <th>报警手机号3</th>
          <th>短信报警监听内容</th>
          <th>备注</th>
          <th>时间</th>
          <th>用户拥有主机</th>
          <th><?php if(isset($login_user_type)) { 
                          if($login_user_type == 'admin') {
          echo '操作';
          }
          }
          ?></th>
        </thead>
        <?php if (isset($query)) :?>
        <?php foreach ($query->result() as $row) :?>
        <tr bgcolor="#E6EEF7" onMouseOver="this.style.backgroundColor='#CADBF1'" onMouseOut="this.style.backgroundColor='#E6EEF7'">
          <td align="center"><?php echo $row->user_id;?></td>
          <td align="center"  class="red"><?php echo anchor('member/add_user/' . $row->user_id, $row->loginname);?></td>
          <td align="center"  class="red"><?php echo anchor('member/add_user/' . $row->user_id, $row->alias);?></td>
          <td align="center"><?php echo $row->user_type;?></td>
          <td align="center"><?php echo $row->contact;?></td>
          <td align="center"><?php if ($row->status == 1) echo '是'; else echo '否'?></td>
          <td align="center"><?php if ($row->message_alert == 1) echo '是'; else echo '否'?></td>
          <td align="center"><?php echo $row->alert_phone1;?>&nbsp;</td>
          <td align="center"><?php echo $row->alert_phone2;?>&nbsp;</td>
          <td align="center"><?php echo $row->alert_phone3;?>&nbsp;</td>
          <td align="center"><?php echo $row->alert_content;?>&nbsp;</td>
          <td align="center"><?php echo $row->note;?>&nbsp;</td>
          <td align="center"><?php echo $row->add_time;?></td>
          <td align="center" class="red"><?php echo anchor('member/user_host/default/' . $row->user_id, '<u>(主机列表)</u>');?></td>
          <td align="center">
          <?php if(isset($login_user_type)) { 
                          if($login_user_type == 'admin') {
          echo form_checkbox('check[]', $row->user_id);
          }
          }
          ?>
          </td>
        </tr>
        <?php endforeach;?>
		<?php endif;?>
    <tr bgcolor="#E6EEF7" onMouseOver="this.style.backgroundColor='#CADBF1'" onMouseOut="this.style.backgroundColor='#E6EEF7'">
          <td align="center"></td>
          <td align="center" colspan="13">
          <?php if(isset($login_user_type)) { 
        if($login_user_type == 'admin') { 
      ?>
          全选 <input id="chkAll" type="checkbox" onclick="CheckAll(this.form)" value="chkAll" name="chkAll"> 
          <input type="submit" name="Submit_del" value="删除所选" onclick="return confirm('是否确定？');" /> <?php if(isset($login_user_type)) { 
  if($login_user_type == 'admin') {
    echo anchor('member/add_user', img('public/images/icons/add.png') . '添加');
  }
}
?>
      <input type="hidden" name="action" value="del"  >
      <?php
        }
      }
      ?>
      <div style="text-align:center;"><?php if (isset($total_rows) && ! empty($total_rows)) echo '总记录数：' . $total_rows;?><?php if (isset($pagination) && ! empty($pagination)) echo ', 分页：' . $pagination;?></div>
      <div style="padding-left:20px;">点击下划线列数据处编辑</div>
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
function setValue()
{
	try{
	cb=document.getElementById("keyword3");  
	cb.checked=true;
	cb.value=1;
	}catch(e)
	{}
}
</script>
<script>
function search(id)
{
	key=document.getElementById("keyword");
	if(id==0)
	{
		if(key.value=="" || key.value=="登陆账号")
		{
			key.value="登陆账号";
		}
		if(isSpaceStr(key.value) && key.value!="登陆账号")
		{
			key.value="登陆账号";
		}
	}
	else
	{
		if(key.value=="" || key.value=="登陆账号")
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
		if(key.value=="" || key.value=="用户名称")
		{
			key.value="用户名称";
		}
		if(isSpaceStr(key.value) && key.value!="用户名称")
		{
			key.value="用户名称";
		}
	}
	else
	{
		if(key.value=="" || key.value=="用户名称")
		{
			key.value="";
		}
	}
}
</script>
    