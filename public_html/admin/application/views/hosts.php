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
      <?php echo form_open('member/hosts/showInfo'); ?>
      <table class="tableBorder" cellpadding="0" cellspacing="0">
        <caption>主机信息维护            
            &nbsp;&nbsp;&nbsp; 
        <?php 
        if(isset($login_user_type)) { 
          if($login_user_type == 'admin') { ?>
            <input type="text" id="keyword2" name="keyword2" value="主机编号" size="20" onblur="search2(0)" onfocus="search2(1)" />
            <input type="text" id="keyword" name="keyword" value="主机别名" size="20" onblur="search(0)" onfocus="search(1)" />
            <input type="submit" value="查询" name="submit_chaxun" />
        <?php 
          }
        } ?>
        </caption>
        <thead>
          <th>ID</th>
          <th>主机编号</th>
          <th>主机别名</th>
          <th>主机当前是否在线</th>
          <th>主机参数</th>
          <th>城市</th>
          <th>入库时间</th>
          <th><?php if(isset($login_user_type)) { 
                          if($login_user_type == 'admin') {
          echo '操作';
          }
          }
          ?></th>
        </tr>
        <?php if (isset($query)) :?>
        <?php foreach ($query->result() as $row) :?>
        <tr bgcolor="#E6EEF7" onMouseOver="this.style.backgroundColor='#CADBF1'" onMouseOut="this.style.backgroundColor='#E6EEF7'">
          <td align="center"><?php echo anchor('member/hosts/edit/' . $row->host_id, $row->host_id);?></a></td>
          <td align="center"  class="red"><?php echo anchor('member/hosts/edit/' . $row->host_id, $row->host_code);?></td>          
          <td align="center"  class="red"><?php echo anchor('member/hosts/edit/' . $row->host_id, $row->host_alias);?></td>
          <td align="center">
              <?php
              if (isset($row->is_online)) {
                  if ($row->is_online == 1) {
                    echo '是';
                  } else {
                    echo '否';
                  }
                  
              }
              ?>
          </td>
          <td align="center"><?php echo $row->params;?></td>
          <td align="center"><?php echo $row->note;?>&nbsp;</td>
          <td align="center"><?php echo $row->add_time;?></td>          
          <td align="center">
          <?php if(isset($login_user_type)) { 
                          if($login_user_type == 'admin') {
          echo form_checkbox('check[]', $row->host_id);
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
                            echo anchor('member/hosts/add', img('public/images/icons/add.png') . '添加');
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
		if(key.value=="" || key.value=="主机别名")
		{
			key.value="主机别名";
		}
		if(isSpaceStr(key.value) && key.value!="主机别名")
		{
			key.value="主机别名";
		}
	}
	else
	{
		if(key.value=="" || key.value=="主机别名")
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