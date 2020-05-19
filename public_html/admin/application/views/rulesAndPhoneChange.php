<table width="100%" cellpadding="0" cellspacing="0" border="0">
  <tr>
    <td class="left" valign="top">
        <?php 
            if(isset($login_user_type)) { 
              if($login_user_type == 'admin') {
                $this->load->view ( 'left_admin'); 
              } else {
                $this->load->view ( 'left_user'); 
              }
            }
        ?>
    </td>
    <td valign="top">
    <?php echo form_open('member/rulesAndPhoneChange'); ?>
    
    <!-- begin -->
    <table id='tb_smsbjset' class="tableBorder" cellpadding="0" cellspacing="0">
    <caption>短信报警设置</caption>
        <tr>
          <td width="20%" align="right">是否开启短信报警:</td>
          <td width="80%" style="PADDING-LEFT:10px">
          <select name="message_alert">
              <?php
                  if (isset($row->message_alert)) {
                      if ($row->message_alert == 1) {
                        echo '<option value="1">是</option>';
                        echo '<option value="0">否</option>';
                      } else {
                        echo '<option value="0">否</option>';
                        echo '<option value="1">是</option>';
                      }
                  }
              ?>
              <!--<option value="1">是</option>
              <option value="0">否</option> -->
          </select>
        </tr>
        <tr>
          <td width="20%" align="right">报警手机号1:</td>
          <td width="80%" style="PADDING-LEFT:10px"><input  name="alert_phone1" type="text" size="33" value="<?php if (isset($row->alert_phone1)) echo $row->alert_phone1;?>" ></td>
        </tr>
        <tr>
          <td width="20%" align="right">报警手机号2:</td>
          <td width="80%" style="PADDING-LEFT:10px"><input  name="alert_phone2" type="text" size="33" value="<?php if (isset($row->alert_phone2)) echo $row->alert_phone2;?>" ></td>
        </tr>
        <tr>
          <td width="20%" align="right">报警手机号3:</td>
          <td width="80%" style="PADDING-LEFT:10px"><input  name="alert_phone3" type="text" size="33" value="<?php if (isset($row->alert_phone3)) echo $row->alert_phone3;?>" ></td>
        </tr>
        <tr>
          <td width="20%" align="right">短信报警监听内容:</td>
          <td width="80%" style="PADDING-LEFT:10px">
              <?php 
                  $arr_alert = array('温度','湿度','电压','断网');
                  if (isset($row->alert_content) && ! empty($row->alert_content)) {
                    $content = str_split($row->alert_content);
                    for($i=0; $i<count($arr_alert); $i++) {
                      if((int)$content[$i] == 1) {
                        echo '<input type="checkbox" name="alert_' . $i . '" checked="checked"  />' . $arr_alert[$i];
                      } else {
                        echo '<input type="checkbox" name="alert_' . $i . '" />' . $arr_alert[$i];
                      }
                    }
                  } else {
                    for($i=0; $i<count($arr_alert); $i++) {
                      echo '<input type="checkbox" name="alert_' . $i . '"  />' . $arr_alert[$i];
                    }            
                  }
              ?>          
          </td>
        </tr>                
        <tr>
          <td height="30"></td>
          <td height="30" style="PADDING-LEFT: 10px"><input type="submit" name="submit_qr" value="确认"  onclick="document.getElementById('action_xiaoma').value = '2';" >
          <?php if(isset($message)) echo $message ?></td>
        </tr>
    </table>
    <?php 
        if(strstr($login_user,"guest")==true)
        {
            //hide 
            echo '<script type="text/javascript"> var tb=document.getElementById("tb_smsbjset");tb.style.display=\'none\'; </script> ';
        }
        else
        {
            //show 
        } 
     ?> 
    <!-- end -->  
      
      
      <input type="hidden" id="action_xiaoma" name="action_xiaoma" value="" /> 
      
      <table class="tableBorder" cellpadding="0" cellspacing="0">
        <caption>报警规则设置
         &nbsp;&nbsp;&nbsp; 
             <input type="text" id="keyword2" name="keyword2" value="主机编号" size="20" onblur="search2(0)" onfocus="search2(1)" />
             <input type="text" id="keyword" name="keyword" value="标签编号" size="20" onblur="search(0)" onfocus="search(1)" /> 
             <input type="submit" value="查询" name="submit_cx" onclick="document.getElementById('action_xiaoma').value = '1';" />
        </caption>

      
        <thead>
          <th>主机编号</th>
          <th>采集器编号</th>
		  <th>主机别名</th>
		  <th>标签别名</th>
          <th>一路报警</th>
          <th>温度下限</th>
          <th>温度上限</th>
          <th>二路报警</th>
          <th>湿度下限</th>
          <th>湿度上限</th>
          <th>三路报警</th>
          <th>电压下限</th>
          <th>电压上限</th>
         <!--  <th>四路报警</th>
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
          <th>空气质量等级上限</th> -->
          <th>断网报警</th>
          <th>断网时长</th>
          <th>入库时间</th>
        </thead>
        <?php if (isset($query2)) :?>
        <?php foreach ($query2->result() as $row) :?>
        <tr id="tb_sj">
          <td align="center"  class="red"><?php 
          if(strstr($login_user,"guest")==true)
          {
              echo $row->host_code;
          }
              else
              {  
              echo anchor('member/rules/edit/' . $row->rule_id, $row->host_code);
              }?></td>             
          <td align="center"  class="red"><?php
          if(strstr($login_user,"guest")==true)
          {
              echo $row->label_code;
          }
          else
          {
          echo anchor('member/rules/edit/' . $row->rule_id, $row->label_code);
          }?></td>
		  <td align="center"  ><?php echo $row->host_code_alias;?></td> 
	      <td align="center"  ><?php echo $row->label_code_alias;?></td>          
          <td align="center"><?php if ($row->alert_1 == 1) echo '是'; else echo '否'?></td>
          <td align="center"><?php echo $row->min_1;?></td>
          <td align="center"><?php echo $row->max_1;?></td>
          <td align="center"><?php if ($row->alert_2 == 1) echo '是'; else echo '否'?></td>
          <td align="center"><?php echo $row->min_2;?></td>
          <td align="center"><?php echo $row->max_2;?></td>
          <td align="center"><?php if ($row->alert_3 == 1) echo '是'; else echo '否'?></td>
          <td align="center"><?php echo $row->min_3;?></td>
          <td align="center"><?php echo $row->max_3;?></td>
          <!-- <td align="center"><?php if ($row->alert_4 == 1) echo '是'; else echo '否'?></td>
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
          <td align="center"><?php echo $row->max_8;?></td> -->
          <td align="center"><?php if ($row->is_active == 1) echo '是'; else echo '否'?></td>
          <td align="center"><?php echo $row->active_timeout;?></td>
          <td align="center"><?php echo $row->add_time;?></td>        
        </tr>
        <?php endforeach;?>
        <?php endif;?>
		<tr bgcolor="#E6EEF7" onMouseOver="this.style.backgroundColor='#CADBF1'" onMouseOut="this.style.backgroundColor='#E6EEF7'">
          <td align="center"></td>
          <td align="center" colspan="12">      
      </td>
      <td align="center"></td>
        </tr>
      </table>
    <?php echo form_close() ?>
    
      <div style="text-align:center;"><?php if (isset($total_rows) && ! empty($total_rows)) echo '总记录数：' . $total_rows;?><?php if (isset($pagination) && ! empty($pagination)) echo '， 第' . $current_page . '页， 分页：' . $pagination;?></div>
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