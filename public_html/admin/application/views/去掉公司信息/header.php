<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title><?php echo $title;?></title>
<link href="<?php echo base_url();?>public/css/style.css" rel="stylesheet" type="text/css">

</head>
<body>
<table width="100%" border="0" cellpadding="0" cellspacing="0">
  <tr>
    <td align="left" valign="top"><table width="100%"  border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td valign="top"><div class="headleft">
          <a href="../../../">
          <img src="<?php echo base_url();?>public/images/index_01.jpg" />
          </a></div>
          <div class="headlogin2">
          <b><img src="<?php echo base_url();?>public/images/icons/user.png" />
          <?php echo $login_alias;?></b> 
          <?php echo anchor('member/update_password/', '<b>【修改密码】</b>');?> 
          <a href="<?php echo base_url();?>index.php/login/logout">
          <b>【注销】</b></a></div><div class="headlogin">
          
          <!-- <a href="http://www.bjxwhl.com/"> -->
          <a href="#">
          
          <img src="<?php echo base_url();?>public/images/home.png" /></a>
          </div></td>
        </tr>
      </table></td>
  </tr>
</table>
