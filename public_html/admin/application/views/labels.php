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
      <?php echo form_open('member/labels/showInfo'); ?>
      <table class="tableBorder" cellpadding="0" cellspacing="0">
        <caption>标签信息维护            
            &nbsp;&nbsp;&nbsp; 
        <?php 
        if(isset($login_user_type)) { 
          if($login_user_type == 'admin') { ?>
            <input type="text" id="keyword2" name="keyword2" value="归属主机编号" size="20" onblur="search2(0)" onfocus="search2(1)" />
            <input type="text" id="keyword" name="keyword" value="标签编号" size="20" onblur="search(0)" onfocus="search(1)" />
            <input type="submit" value="查询" name="submit_chaxun" />
        <?php 
          }
        } ?>
        </caption>
        <thead>
          <th>ID</th>
          <th>归属主机编号</th>
          <th>标签编号</th>
          <th>标签别名</th>
          <th>标签功能类别</th>
          <th>标签位置描述</th>
          <th>标签参数</th>
          <th>备注</th>
          <th>入库时间</th>
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
          <td align="center"><?php echo $row->label_id;?></td>
          <td align="center"  class="red"><?php echo anchor('member/labels/edit/' . $row->label_id, $row->host_code);?></td>          
          <td align="center" class="red"><?php echo anchor('member/labels/edit/' . $row->label_id, $row->label_code);?></td>
          <td align="center"><?php echo $row->label_alias;?>&nbsp;</td>
          <td align="center"><?php echo $row->label_category;?>&nbsp;</td>
          <td align="center"><?php echo $row->label_desc;?>&nbsp;</td>
          <td align="center"><?php echo $row->label_param;?>&nbsp;</td>
          <td align="center"><?php echo $row->note;?>&nbsp;</td>
          <td align="center"><?php echo $row->add_time;?></td>                  
          <td align="center">
          <?php if(isset($login_user_type)) { 
                          if($login_user_type == 'admin') {
          echo form_checkbox('check[]', $row->label_id);
          }
          }
          ?>
          </td>
        </tr>
        <?php endforeach;?>
        <?php endif;?>
      </table>
      <?php if(isset($login_user_type)) { 
        if($login_user_type == 'admin') { 
      ?>
      <div style="text-align:center;">全选 <input id="chkAll" type="checkbox" onclick="CheckAll(this.form)" value="chkAll" name="chkAll"> 
      <input type="submit" name="Submit_del" value="删除所选" onclick="return confirm('是否确定？');"  />
       <?php if(isset($login_user_type)) { 
                          if($login_user_type == 'admin') {
                            echo anchor('member/labels/add', img('public/images/icons/add.png') . '添加');
                          }
                        }
                        ?>
      </div>
      <?php
        }
      }
      ?>
      <div style="text-align:center;">总记录数：<?php echo $total_rows;?><?php if(! empty($pagination)) {
        echo ', 分页：' . $pagination;
      }?></div>
      <div style="padding-left:20px;">点击下划线列数据处编辑</div>
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
		if(key.value=="" || key.value=="归属主机编号")
		{
			key.value="归属主机编号";
		}
		if(isSpaceStr(key.value) && key.value!="归属主机编号")
		{
			key.value="归属主机编号";
		}
	}
	else
	{
		if(key.value=="" || key.value=="归属主机编号")
		{
			key.value="";
		}
	}
}
</script>
