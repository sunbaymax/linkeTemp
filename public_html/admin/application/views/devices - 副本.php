<link href="<?php echo base_url();?>public/css/style.css" rel="stylesheet" type="text/css">
<link rel="stylesheet" type="text/css" href="<?php echo base_url();?>public/css/themes/default/easyui.css">
<link rel="stylesheet" type="text/css" href="<?php echo base_url();?>public/css/themes/icon.css">
<script type="text/javascript" src="<?php echo base_url();?>public/js/jquery.min.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>public/js/jquery.easyui.min.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>public/js/easyui-lang-zh_CN.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>public/js/jscharts.js"></script>
<table width="100%" cellpadding="0" cellspacing="0" border="0">
  <tr>
    <td class="left" valign="top">
      <div class="mnu">
        <div class="head"></div>   
        <ul>
          <li class="active"><?php echo img('public/images/icons/index_03.png');?>我的设备</li>
          <li class="children">
          <?php 
	  if (count($devices) > 0) {
      foreach ($devices as $key => $val) {
        echo '<div class="text">' . img('public/images/icons/log.png') . $val['text'] . '</div>';
        echo '<div class="child">';
        foreach ($val['children'] as $key2 => $val2) {
          if ($key == 0 && $key2 == 0) {
            echo '<input type="radio" checked="checked" value="' . $val2['id'] . '_' . $val2['label_category'] . '_' . $val['text'] . '_' . $val2['text'] . '" name="labels[]" onclick="show_data();">' . $val2['text'] . '<br />';
          } else {
            echo '<input type="radio" value="' . $val2['id'] . '_' . $val2['label_category'] . '_' . $val['text'] . '_' . $val2['text'] . '" name="labels[]" onclick="show_data();">' . $val2['text'] . '<br />';
          }
        }
        echo '</div>';
      }
    }
		?>
          </li>
          <li><?php echo img('public/images/icons/index_07.png') . anchor('member/rules', '我的警情');?></li>
          <li><?php echo img('public/images/icons/index_09.png') . anchor('member/add_user/rules_' . $this->session->userdata('login_user_id'), '我的报警规则设定');?></li>   
          <li><?php echo img('public/images/icons/index_12.png') . anchor('member/rules', '我的报警短信下发日志');?></li>
          <li><?php echo img('public/images/icons/index_15.png') . anchor('member/hosts', '我的设备参数设定');?></li>        
        </ul>
      </div>      
    </td>
    <td valign="top">
    <div class="attr_panel">显示数据：<input type="checkbox" id="alert_1" onclick="select_check('alert_1');" />
                  <span class="level_1">温度</span>
                  <input type="checkbox" id="alert_2" onclick="select_check('alert_2');" />
                  <span class="level_2">湿度</span>
                  <input type="checkbox" id="alert_3" onclick="select_check('alert_3');"  />
                  <span class="level_3">电压</span>
                  <input type="checkbox" id="alert_4" onclick="select_check('alert_4');" />
                  <span class="level_4">自定义</span>
                  <input type="checkbox" id="alert_5"  onclick="select_check('alert_5');" />
                  <span class="level_5">温度A</span>
                  <input type="checkbox" id="alert_6" onclick="select_check('alert_6');" />
                  <span class="level_6">温度B</span>
                  <input type="checkbox" id="alert_7" onclick="select_check('alert_7');" />
                  <span class="level_7">直流电压</span>
                  <input type="checkbox" id="alert_8"  onclick="select_check('alert_8');" />
                  <span class="level_8">交流电压</span>
                  <input type="hidden" id="order_type" name="order_type" value="0" />
                  <br /><br />                
                  显示方式：<input type="radio" id="show_type" name="show_type" value="0" checked="checked"   onclick="show_data();" />
                  智能显示
                  <input type="radio" id="show_type" name="show_type" value="1" onclick="{if(confirm('全部查询结果比较多,速度会比较慢，建议使用智能显示,确定要全部显示吗?')){show_data();return true;} else {return false;}}" />
                  全部显示&nbsp;&nbsp;&nbsp;&nbsp;  
                  时间范围：
                  <input class="easyui-datebox" id="begin_date" name="begin_date" value="<?php echo date('Y-m-d');?>"></input>
                  - 
                  <input class="easyui-datebox" data-options="buttons:buttons" id="end_date" name="end_date" value="<?php echo date('Y-m-d');?>"></input>                  
                  <input type="button" value="确定" onclick="show_data();" />
                  </div>
    <table border="0" cellpadding="10" cellspacing="10">     
                
        <tr>
          <td><div id="hostarea"><div class="left" id="labelarea"></div><div class="right"><?php echo anchor('member/hosts', '主机(别名)设置');echo anchor('member/labels', '标签(别名)设置');?></div></div></td>
        </tr>
        <tr>
          <td><div id="graph">数据加载...</div></td>
        </tr>
        <tr>
          <td><table id="detaildata" class="easyui-datagrid" title="数据列表" style="width:1000px;height:300px"
			data-options="url:'<?php echo site_url();?>/member/devices/datagrid',method:'post',rownumbers:true,pagination:true" idField="z_id">
		<thead>
			<tr>
				<th data-options="field:'host_code',width:70">主机编号</th>
				<th data-options="field:'label_code',width:70">标签地址</th>
				<th data-options="field:'value_1',width:80,align:'right'">一路(温度)</th>
				<th data-options="field:'value_2',width:80,align:'right'">二路(湿度)</th>
				<th data-options="field:'value_3',width:80,align:'center'">三路(电压)</th>
				<th data-options="field:'value_4',width:80,align:'center'">四路(自定义)</th>
				<th data-options="field:'value_5',width:80,align:'center'">五路(温度A)</th>
				<th data-options="field:'value_6',width:80,align:'center'">六路(温度B)</th>
				<th data-options="field:'value_7',width:100,align:'center'">七路(直流电压)</th>
				<th data-options="field:'value_8',width:100,align:'center'">八路(交流电压)</th>
				<th data-options="field:'label_time',width:135,align:'right'">采集时间</th>
			</tr>
		</thead>
	</table>
    <p><?php echo img('public/images/icons/excel.png');?><a href="javascript:void(0);" onclick="export_to_excel();">导出到EXCEL</a></p>
    </td>
  </tr>
</table>
<script type="text/javascript">
function hide_logo() {
	$("[width=77]").hide();
}

function select_check(name) {	
	drawChart();
}

function export_to_excel() {	
	var begin_date = $("#begin_date").datebox('getValue');
	var end_date = $("#end_date").datebox('getValue');
	//var date_type = $("#date_type").val();
	var show_type = document.getElementById("show_type").checked;
	var order_type = document.getElementById("order_type").checked;  
	var alert_1 = document.getElementById("alert_1").checked;
	var alert_2 = document.getElementById("alert_2").checked;
	var alert_3 = document.getElementById("alert_3").checked;
	var alert_4 = document.getElementById("alert_4").checked;
	var alert_5 = document.getElementById("alert_5").checked;
	var alert_6 = document.getElementById("alert_6").checked;
	var alert_7 = document.getElementById("alert_7").checked;
	var alert_8 = document.getElementById("alert_8").checked;

	var nodes=document.getElementsByName("labels[]");
	var arr_check = new Array(); 
	for(var i=0;i<nodes.length;i++)
    {
        if(nodes[i].checked)
        {
            var data2 = nodes[i].value.split("_");	
			      arr_check[i] = data2[0];
        }
    }
	var labels = arr_check.join();
	var params = 'begin_date=' + begin_date + '&end_date=' + end_date + '&show_type=' + show_type + '&order_type=' + order_type + 
	    '&alert_1=' + alert_1 + '&alert_2=' + alert_2 + '&alert_3=' + alert_3 + '&alert_4=' + alert_4 + '&alert_5=' + alert_5 + 
		'&alert_6=' + alert_6 + '&alert_7=' + alert_7 + '&alert_8=' + alert_8 + '&labels=' + labels;
	var url = '<?php echo site_url();?>/member/devices/export/' + params;
	window.location.href = url;
	return false;
	
}

function change_check(data) {
	var show_type = document.getElementById("show_type").checked;
	if (data != '') {
		var data2 = data.split("");
		n = data2.length;
		for (var i = 0; i < n; i++) {
			if (show_type == true) {
				//zhi nen 
				if (data2[i] == 1 ) {
					document.getElementById('alert_' + (i+1)).checked = true;
				} else {
					document.getElementById('alert_' + (i+1)).checked = false;
				}
			} else {
				//show all
				//document.getElementById('alert_' + (i+1)).checked = true;
			}
		};				
	}
	
}

function xor(data1, data2) {
	if (data1 != '' && data2 != '') {
		var data11 = data1.split("");
		var data22 = data2.split("");
		result = '';
		n = data11.length;
		for (var i = 0; i < n; i++) {
			if (data11[i] + data22[i] > 0) {
				result += '1';
			} else {
				result += '0';
			}	
		};
		return result;			
	}
}

function show_data() {
	var begin_date = $("#begin_date").datebox('getValue');
	var end_date = $("#end_date").datebox('getValue');
	//var date_type = $("#date_type").val();
	var show_type = document.getElementById("show_type").checked;
	var order_type = document.getElementById("order_type").checked;
  if (begin_date == '' || end_date == '') {
    alert('日期不能为空');
    return false;
  }
  if (begin_date > end_date) {
    alert('开始日期不能大于结束日期');
    return false;
  }  
	var nodes=document.getElementsByName("labels[]");
	var arr_check = new Array(); 
	var check_data = '00000000';
	for(var i=0;i<nodes.length;i++)
    {
        if(nodes[i].checked)
        {
          var data2 = nodes[i].value.split("_");						
			    arr_check[i] = data2[0];
	        check_data = xor(check_data,data2[1]);
		      $('#labelarea').html('当前主机：' + data2[2] + '，当前标签：' + data2[3]);
        }
    }
	if (check_data != '') {
	  	change_check(check_data);
	}
  drawChart();
}

function drawChart() {
	var begin_date = $("#begin_date").datebox('getValue');
	var end_date = $("#end_date").datebox('getValue');
	var show_type = document.getElementById("show_type").checked;
	var order_type = document.getElementById("order_type").checked;  
	var alert_1 = document.getElementById("alert_1").checked;
	var alert_2 = document.getElementById("alert_2").checked;
	var alert_3 = document.getElementById("alert_3").checked;
	var alert_4 = document.getElementById("alert_4").checked;
	var alert_5 = document.getElementById("alert_5").checked;
	var alert_6 = document.getElementById("alert_6").checked;
	var alert_7 = document.getElementById("alert_7").checked;
	var alert_8 = document.getElementById("alert_8").checked;

	var nodes=document.getElementsByName("labels[]");
	var arr_check = new Array(); 
	for(var i=0;i<nodes.length;i++)
    {
        if(nodes[i].checked)
        {
            var data2 = nodes[i].value.split("_");	
			arr_check[i] = data2[0];
        }
    }	

  $.ajax({
          url: '<?php echo site_url();?>/member/devices/chart',
          type: "POST",
          dataType: "json",
          data: {
            begin_date: begin_date,
            end_date: end_date,
            show_type: show_type,
            order_type: order_type,
            alert_1: alert_1,
            alert_2: alert_2,
            alert_3: alert_3,
            alert_4: alert_4,
            alert_5: alert_5,
            alert_6: alert_6,
            alert_7: alert_7,
            alert_8: alert_8,
            labels: arr_check.join()
          },
          error: function() {
            //alert('数据不正确，请刷新重试。');
            return false;
          },
          success: function(data, status) {
            drawChartDetail(data);             
          }
        }); 
		
	//reload datagrid
	$('#detaildata').datagrid({  
    url:"<?php echo site_url();?>/member/devices/datagrid",
		queryParams:{
            begin_date: begin_date,
            end_date: end_date,
            show_type: show_type,
            order_type: order_type,
            alert_1: alert_1,
            alert_2: alert_2,
            alert_3: alert_3,
            alert_4: alert_4,
            alert_5: alert_5,
            alert_6: alert_6,
            alert_7: alert_7,
            alert_8: alert_8,
            labels: arr_check.join()}
		
    });
    $("#detaildata").datagrid('reload');   
  
}

function drawChartDetail(data)
{
  var l = data.total;
  var data2 = data.data;
  var x_text = data.x_text;
  var y_text = data.y_text;
  var color_name = new Array('color1','color2','color3','color4','color5','color6','color7','color8','color9','color10','color11','color12','color13','color14','color15','color16','color17','color18','color19','color20','color21','color22','color23','color24','color25','color26','color27','color28','color29','color30','color31','color32');
  if (l > 0)
  {
    var myChart = new JSChart('graph', 'line'); 
	  var color_desc = '';   
    for (i=0; i<l; i++)
    {
      var n = data2[i].length;
      var json = '';
      var json_data = '[';
      var json_arr = new Array();
      for (j=1; j<=n-1; j++)
      {
        json = '[' + (j-1) + ',' + parseFloat(data2[i][j]) + ']';   
        json_arr.push(json);
        myChart.setTooltip([j-1,'时间：' + x_text[j-1]]);
        if (j == 2 || j == parseInt(n/2) || j == n-2)
        {
          myChart.setLabelX([j-1,x_text[j-1]]);
        }        
        color_desc = data2[i][0];
      }
      json_data += json_arr.join(',') + ']';
      //myChart.setLabelY([parseFloat(data2[i][1]),y_text[i]]);
      myChart.setDataArray(eval('(' + json_data + ')'), color_name[i]); 
      myChart.setLegendForLine(color_name[i],y_text[i]);
      myChart.setLineColor(color_desc, color_name[i]);
    }
    myChart.setLegendShow(true);
    myChart.setLegendPosition(950, 100);    
	  myChart.setLineWidth(1);
	  myChart.setLineSpeed(100);
	  myChart.setFlagRadius(2);
	  //myChart.setAxisValuesSuffixY('');
	  myChart.setGridOpacityY(0);
	  myChart.setLabelFontSizeX(6);
	  myChart.setLabelFontSizeY(6);
    myChart.setShowXValues(false);
    myChart.setAxisNameX('时间');
    myChart.setAxisNameY('数值');
    myChart.setTitlePosition('left');
    myChart.setTitle('');
    myChart.setAxisValuesFontSize(8);
    //myChart.setAxisValuesAngle(90);
    myChart.setAxisPaddingBottom(30);
    myChart.setAxisPaddingTop(10);
    myChart.setAxisPaddingLeft(50);
    myChart.setFontFamily("yahei");
    myChart.setAxisNameFontFamily("yahei");
    myChart.setTitleColor('#454545');
    myChart.setAxisValuesColor('#454545');
    myChart.setFlagColor('#9D16FC');
    myChart.setSize(1000, 300);
    myChart.draw();
    $("[width=77]").hide();
  }  
}

</script> 
<script type="text/javascript">
$(function () {  
  show_data();
  setInterval("hide_logo()",1000);
});
</script>
<script>
  var buttons = $.extend([], $.fn.datebox.defaults.buttons);
  buttons.splice(1, 0, {
    text: 'MyBtn',
    handler: function(target){
      alert('click MyBtn');
    }
  });
</script>