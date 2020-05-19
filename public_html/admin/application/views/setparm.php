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
    <?php echo form_open('member/setparm'); ?>
    <table class="tableBorder" cellpadding="0" cellspacing="0">
    <caption>参数设置指令下发</caption>    
        <tr>
          <td height="15" align="right"></td>
          <td height="15" style="PADDING-LEFT: 10px">  
          </td>
        </tr> 
		 <?php 
          if(strstr($login_user,"guest")==true)
        {
			echo'<script type="text/javascript"> var tb=document.getElementById("yc");tb.style.display=\'none\'; </script> ';
		}
		else{
	
		    }
		 ?>
		   <tr id="yc">
          <td width="20%" align="right">[主机参数查询]</td>
          <td width="80%" style="PADDING-LEFT:10px">
            <input type="submit" name="Submit1" value="指令下发"  />
                                    主机号<input  name="host_code_1" type="text" size="1" value="<?php if (true) echo '';?>" /> 
          </td> 
        </tr>
        <tr>
          <td width="20%" align="right">[服务器报警规则设置至主机]</td>
          <td width="80%" style="PADDING-LEFT:10px">
            <input type="submit" name="Submit2" value="指令下发"  disabled="disabled" />
                                    主机号<input  name="host_code_2" type="text" size="1" value="<?php if (true) echo '';?>" /> 
                                    提示：请先在"我的报警规则设定"设置主机标签的报警规则,本设置会克隆主机第一个标签的报警规则。
          </td>
        </tr>
        <!--<tr>
          <td width="20%" align="right">[标签采集间隔查询]</td>
          <td width="80%" style="PADDING-LEFT:10px">
            <input type="submit" name="Submit3" value="指令下发"  disabled="disabled" />
                                    主机号<input  name="host_code_3" type="text" size="1" value="<?php if (true) echo '';?>" />
                                    标签号<input  name="lable_code_3" type="text" size="1" value="<?php if (true) echo '';?>" />
                                    提示：此指令需要在标签上电30秒内进行。
          </td>
        </tr>
        <tr>
          <td width="20%" align="right">[标签采集间隔设置]</td>
          <td width="80%" style="PADDING-LEFT:10px">
            <input type="submit" name="Submit4" value="指令下发"  disabled="disabled" />
                                    主机号<input  name="host_code_4" type="text" size="1" value="<?php if (true) echo '';?>" />
                                    标签号<input  name="lable_code_4" type="text" size="1" value="<?php if (true) echo '';?>" />
                                    间隔<input  name="ticklong_4" type="text" size="1" value="<?php if (true) echo 600;?>" />(秒)
                                    提示：此指令需要在标签上电30秒内进行。
          </td>
        </tr>    -->
        <tr>
          <td height="20"></td>
          <td height="20" style="PADDING-LEFT: 10px;" > 
            <span style="font-color:red">
                <?php if(isset($message)) echo $message ?>
            </span>
          </td>
        </tr>
    </table>
      
      <input type="hidden" name="actionSend_del" id="actionSend_del" value="" />
      
      <table class="tableBorder" cellpadding="0" cellspacing="0">
        <caption>主机应答记录            
            &nbsp;
            <input type="submit" value="刷新" name="submit_select" />
        </caption>
        <thead>
          <th>序号</th>
          <th>主机编号</th>
          <th>标签编号</th>
          <th>应答信息</th> 
          <th>应答时间</th>    
        </thead>
        <?php if (isset($query2)) :?>
        <?php foreach ($query2->result() as $row) :?>
        <tr>
          <!-- <td align="center"  class="blue"><    ?php echo anchor('member/rules/edit/' . $row->rcv_id, $row->rcv_id);?></td>   -->  
          <td align="center" class="blue"><?php echo $row->rcv_id;?></td>   
          <td align="center" class="blue"><?php echo $row->rcv_host_code;?></td>
          <td align="center" class="blue"><?php echo $row->rcv_lable_code;?></td>
          <td align="center" class="blue"><?php echo $row->rcv_msg;?></td>
          <td align="center" class="blue"><?php echo $row->rcv_time;?></td>     
        </tr>
        <?php endforeach;?>
        <?php endif;?>
      </table>
      <?php echo form_close() ?>
      
     </td>
  </tr>
</table>
<script type="text/javascript">
function submit_del(actionNum)
{
/* 	废除 用名称判断
	try
	{
    	cb=document.getElementById("actionSend");   
    	cb.value=actionNum;
	}catch(e)
	{} 
	try
	{
    	cb=document.getElementById("host_code_1");   
    	cb.value=actionNum;
	}catch(e)
	{} 
	try
	{
    	cb=document.getElementById("Submit1");   
    	cb.value=actionNum;
	}catch(e)
	{}  */
}
</script>
