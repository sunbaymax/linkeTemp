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
    <?php echo form_open('member/set_jinghua'); ?>
    <table class="tableBorder" cellpadding="0" cellspacing="0">
    <caption>空气净化器控制</caption>    
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
		<!--     <tr id="yc">
          <td width="20%" align="right">[空气净化器参数查询]</td>
          <td width="80%" style="PADDING-LEFT:10px">
            <input type="submit" name="Submit1" value="指令下发"  />
                                    主机号<input  name="host_code_1" type="text" size="1" value="<?php if (true) echo '';?>" /> 
          </td> 
        </tr>  -->
        <tr>
          <!-- <td width="20%" align="right">[空气净化器控制]</td>-->
          <td width="60%" style="PADDING-LEFT:10px" align="right">
                                    净化器所属主机号<input  name="host_code_2" type="text" size="1" value="<?php if (true) echo '';?>" /> &nbsp;
                                    模块地址（标签号）<input  name="lable_code_3" type="text" size="1" value="<?php if (true) echo '';?>" /> &nbsp;                                                                                              
          </td>
        </tr>
        <tr><td align="right">
        <input  name="switch_code_2" type="radio"  value="1" checked="checked"/>按下开关键                                                                  
        <input  name="switch_code_2" type="radio" value="2" />按下转速键
        <input  name="switch_code_2" type="radio" value="3" />按下定时键
        <input  name="switch_code_2" type="radio" value="4" />按下模式键
        </td></tr>
       <!--  <tr><td></td><td>转速<input  name="speed_code_2" type="radio" value="<?php if (true) echo '';?>" /> </td></tr>
        <tr><td></td><td>定时<input  name="timer_code_2" type="radio" value="<?php if (true) echo '';?>" /> </td></tr>
        <tr><td></td><td>模式<input  name="mode_code_2" type="radio" value="<?php if (true) echo '';?>" /> </td></tr>-->
        <tr>
          <td height="20" align="center" style="PADDING-LEFT:240px"> <input type="submit" name="Submit2" value="控制指令下发"  /></td>
          <td height="20" style="PADDING-LEFT: 10px;" > 
            <span style="font-color:red">
                <?php if(isset($message)) echo $message ?>
            </span>
          </td>
        </tr>
    </table>
      
      <input type="hidden" name="actionSend_del" id="actionSend_del" value="" />
      
      <table class="tableBorder" cellpadding="0" cellspacing="0">
        <caption>空气净化器状态列表          
            &nbsp;
            <input type="submit" value="刷新" name="submit_select" />
        </caption>
        <thead>
          <th>序号</th>
          <th>净化器所属主机号</th>
          <th>模块地址（标签号）</th>
          <th>净化器型号</th>
          <th>开关状态</th> 
          <th>转速</th>    
          <th>定时</th>    
          <th>工作模式</th>    
          <th>单位</th>  
          <th>房间</th>    
          <th>状态采集时间</th>    
        </thead>
        <?php if (isset($query2)) :?>
        <?php foreach ($query2->result() as $row) :?>
        <tr>
          <!-- <td align="center"  class="blue"><    ?php echo anchor('member/rules/edit/' . $row->rcv_id, $row->rcv_id);?></td>   -->  
          <td align="center" class="blue"><?php echo $row->id;?></td>   
          <td align="center" class="blue"><?php echo $row->host_code;?></td>
          <td align="center" class="blue"><?php echo $row->label_code;?></td>
          <td align="center" class="blue"><?php echo $row->XH;?></td>
          <td align="center" class="blue"><?php echo $row->openstate;?></td>   
          <td align="center" class="blue"><?php echo $row->speed;?></td>     
          <td align="center" class="blue"><?php echo $row->overtimer;?></td>     
          <td align="center" class="blue"><?php echo $row->workmodel;?></td>     
          <td align="center" class="blue"><?php echo $row->Kname;?></td>     
          <td align="center" class="blue"><?php echo $row->Kroom;?></td>     
          <td align="center" class="blue"><?php echo $row->hostlocalupdatetime;?></td>     
            
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
