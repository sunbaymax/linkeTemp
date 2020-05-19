<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>系统登录</title>
</head>
<style>
*{ margin:0; padding:0;}
body{ font-size:12px; line-height:150%; background-color:#FFFFFF; color:#000000;}
a{font-size:12px; color:#ff6600; text-decoration:none;}
a:link{ color:#ff6600;}
a:visited{ color:#ff6600;}
a:hover{ text-decoration:underline; color:#ff6600;}
a:active{ color:#000000;}
img{ border:none;}
ul,li{ list-style-type:none; padding:0; margin:0;}
.login{ border:1px #99D3FB solid; width:400px; height:190px; margin:100px auto 0 auto;}
.login_tit{  repeat-x; height:24px; border-bottom:1px #ffffff solid; line-height:24px; padding:2px 0 0 10px; font-size:13px; font-weight:bold; color:#077AC7;}
.login_form span{ display:block; height:20px; width:96%; margin:0 auto; padding-top:6px; border-bottom:1px #CCCCCC dashed;}
#wrongmsg{ color:#FF0000; text-align:center;}
.login_btntd{ padding-left:70px;}
.login_btn{border:1px #909090 solid;  repeat-x; width:auto; padding:2px 2px 0 2px;}
.copy_right{ margin:10px auto 0 auto; text-align:center;}
.inputs{ width:140px; height:16px;}
</style>


<body>
<div class="login">
    <?php if(isset($error)) echo $error ?>
	<div class="login_tit">登录&nbsp;&nbsp;</div>
	<div class="login_form">
		<?php echo form_open('login/log') ?>
		<table width="100%" border="0" cellpadding="3" cellspacing="10">
		  <tr>
			<td width="32%" align="right">帐号：</td>
			<td width="68%"><input type="text" name="loginname" class="inputs" /></td>
		  </tr>
		  <tr>
			<td align="right">密码：</td>
			<td><input type="password" name="password" class="inputs" /></td>
		  </tr> 		  
		  <tr>
			<td  class="login_btntd" style="padding-left:"></td>
			<td  class="login_btntd" align="left" ><input style="margin-left:-50px" type="submit" name="Submit" class="login_btn" value="登 录" /></td>
			
		  </tr>
		 
	  </table>
	  <?php echo form_close() ?>
	</div>	
</div>
</body>
</html>
