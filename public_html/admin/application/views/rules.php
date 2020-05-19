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
      <?php echo form_open('member/rules/showInfo'); ?>
      <table class="tableBorder" cellpadding="0" cellspacing="0">
        <caption>报警规则管理            
            &nbsp;&nbsp;&nbsp; 
            <input type="text" id="keyword2" name="keyword2" value="主机编号" size="20" onblur="search2(0)" onfocus="search2(1)" />
            <input type="text" id="keyword" name="keyword" value="标签编号" size="20" onblur="search(0)" onfocus="search(1)" />
            <input type="submit" value="查询" name="submit" />
        </caption>
        <thead>
           <th>主机编号</th>
          <th>采集器编号</th>
          <th>一路报警</th>
          <th>温度下限</th>
          <th>温度上限</th>
          <th>二路报警</th>
          <th>湿度下限</th>
          <th>湿度上限</th>
          <th>三路报警</th>
          <th>电压下限</th>
          <th>电压上限</th>
          <th>四路报警</th>
          <th>颗粒物PM2.5下限</th>
          <th>颗粒物PM2.5上限</th>
          <th>五路报警</th>
          <th>颗粒物PM10下限</th>
          <th>颗粒物PM10上限</th>
          <th>六路报警</th>
          <th>负氧离子数量下限</th>
          <th>负氧离子数量上限</th>
          <th>七路报警</th>
          <th>有机污染物(甲醛)下限</th>
          <th>有机污染物(甲醛)上限</th>
          <th>八路报警</th>
          <th>空气质量等级下限</th>
          <th>空气质量等级上限</th>
          <th>断网报警</th>
          <th>断网时长</th>
          <th>入库时间</th>
          <th><?php if(isset($login_user_type)) { 
                          if($login_user_type == 'admin') {
          echo '操作';
          }
          }
          ?></thead>
        </thead>
        <?php if (isset($query)) :?>
        <?php foreach ($query->result() as $row) :?>
        <tr>
          <td align="center"  class="red"><?php echo anchor('member/rules/edit/' . $row->rule_id, $row->host_code);?></td>          
          <td align="center"  class="red"><?php echo anchor('member/rules/edit/' . $row->rule_id, $row->label_code);?></td>
          <td align="center"><?php if ($row->alert_1 == 1) echo '是'; else echo '否'?></td>
          <td align="center"><?php echo $row->min_1;?></td>
          <td align="center"><?php echo $row->max_1;?></td>
          <td align="center"><?php if ($row->alert_2 == 1) echo '是'; else echo '否'?></td>
          <td align="center"><?php echo $row->min_2;?></td>
          <td align="center"><?php echo $row->max_2;?></td>
          <td align="center"><?php if ($row->alert_3 == 1) echo '是'; else echo '否'?></td>
          <td align="center"><?php echo $row->min_3;?></td>
          <td align="center"><?php echo $row->max_3;?></td>
          <td align="center"><?php if ($row->alert_4 == 1) echo '是'; else echo '否'?></td>
          <td align="center"><?php echo $row->min_4;?></td>
          <td align="center"><?php echo $row->max_4;?></td>
          <td align="center"><?php if ($row->alert_5 == 1) echo '是'; else echo '否'?></td>
          <td align="center"><?php echo $row->min_5;?></td>
          <td align="center"><?php echo $row->max_5;?></td>
          <td align="center"><?php if ($row->alert_6 == 1) echo '是'; else echo '否'?></td>
          <td align="center"><?php echo $row->min_6;?></td>
          <td align="center"><?php echo $row->max_6;?></td>
          <td align="center"><?php if ($row->alert_7 == 1) echo '是'; else echo '否'?></td>
          <td align="center"><?php echo $row->min_7;?></td>
          <td align="center"><?php echo $row->max_7;?></td>
          <td align="center"><?php if ($row->alert_8 == 1) echo '是'; else echo '否'?></td>
          <td align="center"><?php echo $row->min_8?></td>
          <td align="center"><?php echo $row->max_8;?></td>
          <td align="center"><?php if ($row->is_active == 1) echo '是'; else echo '否'?></td>
          <td align="center"><?php echo $row->active_timeout;?></td>
          <td align="center"><?php echo $row->add_time;?></td>                  
          <td align="center">
          <?php if(isset($login_user_type)) { 
                          if($login_user_type == 'admin') {
          echo form_checkbox('check[]', $row->rule_id);
          }
          }
          ?></td>
        </tr>
        <?php endforeach;?>
        <?php endif;?>
      </table>
      <?php if(isset($login_user_type)) { 
        if($login_user_type == 'admin') { 
      ?>
      <div style="text-align:center;">全选 <input id="chkAll" type="checkbox" onclick="CheckAll(this.form)" value="chkAll" name="chkAll"> <input type="submit" name="Submit" value="删除所选" onclick="return confirm('是否确定？');"  />
       <?php if(isset($login_user_type)) { 
                          if($login_user_type == 'admin') {
                            echo anchor('member/rules/add', img('public/images/icons/add.png') . '添加');
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