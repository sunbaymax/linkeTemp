<table cellpadding=0 cellspacing=0 width="100%">
  <tr>
    <td height=25 ><?php echo img('public/images/icons/house.png') . anchor('member', '网站管理首页');?></td>
  </tr>
  <?php if(isset($login_user_type)) { 
                          if($login_user_type != 'admin') {?>
  <tr>
    <td height=25 ><?php
                            echo img('public/images/icons/drive.png') . anchor('member/devices', '我的设备');
						?></td>
  </tr>
  <?php
                                                }
                        }
					  ?>
  <tr>
    <td height=25 ><?php echo img('public/images/icons/user.png') . anchor('member/user', '用户管理');?>
     </td>
  </tr>
  <tr>
    <td height=25 ><?php echo img('public/images/icons/host.png') . anchor('member/hosts', '主机管理');?>      
  </tr>
  <tr>
    <td height=25 ><?php echo img('public/images/icons/label.png') . anchor('member/labels', '采集器管理');?>     
  </tr>
  <tr>
    <td height=25 ><?php echo img('public/images/icons/rule.png') . anchor('member/rules', '报警规则管理');?>
     </td>
  </tr>
  <?php if(isset($login_user_type) && $login_user_type == 'admin') { ?>
  <tr>
    <td height=25 ><?php echo img('public/images/icons/log.png') . anchor('member/logs', '用户登录记录');?></td>
  </tr>
  <?php } ?>
</table>
