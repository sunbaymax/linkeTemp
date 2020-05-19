<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>无线温湿度云平台</title>
<link href="css/reset.css" rel="stylesheet" type="text/css" />
<style type="text/css">
</style>
</head>

<body>
	<div class="top">
		<div class="topcenter">
			<h1 style="display: none">
				<a href="#">logo</a> 
			</h1>  
			<div class="topshuxian" style="display: none"> 
			</div>
			<div class="topwenzi" style="display: none">
			</div> 
			<table>
			<tr>
			<td>
    		<img src="admin/public/images/index_01.jpg" /> 
			</td>
			<td>
			<br />
			<a class="app" style="width:160px" href="http://www.bjxwhl.com/apk/HLink_TECH_v1.3_beta8.apk"> 点击下载：Android APP</a>      
			</td>
			</tr>
			</table>
			    
    			
			<div class="appdown" style="display: none">
    			<a href="http://www.bjxwhl.com/apk/HLink_TECH_v1.3_beta8.apk"> 点击下载：Android APP</a>
    	    </div>
			<div class="tophome" style="display: none"> 
				<a href="http://www.bjxwhl.com/"><img src="images/home.png" />返回公司首页</a>
			</div>
		</div>
	</div>
	<div class="clear"></div>
	<div class="bannerwrapper">
		<div class="banner"></div>
		<div class="loginbox"></div>
		<div class="Registration">
			<form action="admin/index.php/login/log" method="post"
				accept-charset="utf-8">
				<input type="text" name="loginname" id='loginname' placeholder="用户名" 
					title="用户名" class="inputname" onblur="search(0)"
					onfocus="search(1)" /><br /> <input type="password" name="password"
					id="password" placeholder="密码" title="密码" class="inputpassword"
					onblur="search2(0)" onfocus="search2(1)" /><br />
<?php
//zxg 设置时区补丁 在3个php.ini已经一次性改好了
//echo date_default_timezone_set('PRC').""; //设置中国时区
//echo date('Y-m-d H:i:s')."";//中国标准时间
$PRCTime1 = date('Y-m-d H');
date_default_timezone_set('PRC');
$PRCTime2 = date('Y-m-d H');
if($PRCTime1!=$PRCTime2)
{
    echo "注意：服务器时区设置错误!";
}
  
if(isset($_REQUEST) && isset($_REQUEST['error']))
{
    //$objtest = $_REQUEST['error'] ;
    if ($_REQUEST['error'] != null && $_REQUEST['error'] == 1) {
        echo '<div style="color:#f00;">用户名或密码错误</div>';
    }
    //zxg add
    if ($_REQUEST['error'] != null && $_REQUEST['error'] == 2) {
        echo '<div style="color:#f00;">账号已冻结，请联系客服续费！</div>';
    }
} 

?>   
   <br /> <input type="image" src="images/denglubox11111111_18.png" />
			</form>
		</div>
		<div class="denglubox"></div>
	</div>
	<div class="centerwrapper">
		<div class="centerbox">
			<div class="left">
				<div class="lefttop"></div>
				<div class="leftbottom">
					<ul class="firstul">
						<li><img src="images/index_23.jpg" /></li>
						<li><img src="images/index_25.jpg" /></li>
						<li class="thelastli"><img src="images/index_27.jpg" /></li>
					</ul>
					<ul class="secondul">
						<li><img src="images/index_28.png" /></li>
						<li><img src="images/index_30.png" /></li>
						<li class="thelastli"><img src="images/index_32.png" /></li>
					</ul>
					<ul class="thirdul">
						<li><img src="images/index_45.jpg" /></li>
						<li><img src="images/index_46.jpg" /></li>
						<li class="thelastli"><img src="images/index_47.jpg" /></li>
					</ul>
					<ul class="fouthul">
						<li><img src="images/index_38.png" /></li>
						<li><img src="images/index_41.png" /></li>
						<li class="thelastli"><img src="images/index_40.png" /></li>
					</ul>
				</div>
			</div>
			<div class="right">
				<div class="righttop"></div>
				<div class="rightbottom"></div>
			</div>
		</div>
	</div>
	<div class="bottomwrapper">
		<div class="bottomcenter">
			<a href="http://www.bjxwhl.com">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Copyrights 2015
				@ 北京林克物联科技有限公司  &nbsp;&nbsp;  京ICP备15010521号</a>
		</div>
	</div>

	<script>
function search(id)
{
// 	key=document.getElementById("loginname");
// 	if(id==0)
// 	{
// 		if(key.value=="" || key.value=="用户名")
// 		{
// 			key.value="用户名";
// 		}
// 		if(isSpaceStr(key.value) && key.value!="用户名")
// 		{
// 			key.value="用户名";
// 		}
// 	}
// 	else
// 	{
// 		if(key.value=="" || key.value=="用户名")
// 		{
// 			key.value="";
// 		}
// 	}
}
function search2(id)
{
// 	key=document.getElementById("password");
// 	if(id==0)
// 	{
// 		if(key.value=="" || key.value=="密码")
// 		{
// 			key.value="密码";
// 		}
// 		if(isSpaceStr(key.value) && key.value!="密码")
// 		{
// 			key.value="密码";
// 		}
// 	}
// 	else
// 	{
// 		if(key.value=="" || key.value=="密码")
// 		{
// 			key.value="";
// 		}
// 	}
}
</script>


</body>
</html>
