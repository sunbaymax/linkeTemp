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
    <td valign="top"><?php $this->load->view ($right_tpl); ;?></td>
  </tr>
</table>