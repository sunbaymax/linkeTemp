<link href="<?php echo base_url();?>public/css/style.css" rel="stylesheet" type="text/css">
<?php if($results['total'] > 0) : ?>
<table id="datagrid" class="tableBorder" cellpadding="0" cellspacing="0">
    <caption>数据列表</caption>
		<thead>
			<tr>
				<th>ID</th>
				<th>主机编号</th>
				<th>标签编号</th>
				<th>温度(℃)</th>
				<th>湿度(%RH)</th>
				<th>电压(V)</th>
				<th>颗粒物PM2.5(ug/m³)</th>
				<th>颗粒物PM10(ug/m³)</th>
				<th>负氧离子数量(个)</th>
				<th>甲醛(mg/m³)</th>
				<th>空气质量等级</th>
				<th>采集时间</th>
			</tr>
		</thead>
    <tbody>
    <?php foreach($results['rows'] as $key=>$rows) : ?>     
    <tr>
       <td align="center"><?php echo $rows['z_id'];?></td>
       <td align="center"><?php echo $rows['host_code'];?></td>
       <td align="center"><?php echo $rows['label_code'];?></td>
       <td align="center"><?php echo $rows['value_1'];?>&nbsp;</td>
       <td align="center"><?php echo $rows['value_2'];?>&nbsp;</td>
       <td align="center"><?php echo $rows['value_3'];?>&nbsp;</td>
       <td align="center"><?php echo $rows['value_4'];?>&nbsp;</td>
       <td align="center"><?php echo $rows['value_5'];?>&nbsp;</td>
       <td align="center"><?php echo $rows['value_6'];?>&nbsp;</td>
       <td align="center"><?php echo $rows['value_7'];?>&nbsp;</td>
       <td align="center"><?php echo $rows['value_8'];?>&nbsp;</td>
       <td align="center"><?php echo $rows['label_time'];?></td>
    </tr>
    <?php endforeach;?>
    </tbody>
	</table>
  <div style="text-align:center;">总记录数：<?php echo $results['total'];?><?php if(! empty($pagination)) {
        echo ', 分页：' . $pagination;
      }?></div>
<?php else:?>
当前无数据，请选择正确的设备和日期。
<?php endif;?>