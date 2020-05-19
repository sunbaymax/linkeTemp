<link href="<?php echo base_url();?>public/css/style.css"	rel="stylesheet" type="text/css">
<link rel="stylesheet" type="text/css"	href="<?php echo base_url();?>public/css/themes/default/easyui.css">
<link rel="stylesheet" type="text/css"	href="<?php echo base_url();?>public/css/themes/icon.css">
<script type="text/javascript"	src="<?php echo base_url();?>public/js/jquery.min.js"></script>
<script type="text/javascript"	src="<?php echo base_url();?>public/js/jquery.easyui.min.js"></script>
<script type="text/javascript"	src="<?php echo base_url();?>public/js/easyui-lang-zh_CN.js"></script>
<script type="text/javascript"	src="<?php echo base_url();?>public/js/jscharts.js"></script>
<style type="text/css">
    .out_div {
        /*width: 1000px;*/
        overflow-x: auto;
        overflow-y: hidden;
        /*background-color: blue;*/
    } 
    .in_div_MarkA {
        width: 220px;
        height: 120px;
        float: left;
        margin: 12px;
        background-image: url('../../public/images/MarkA.png');
        background-repeat: no-repeat;
    }
    .in_div_MarkB {
        width: 220px;
        height: 120px;
        float: left;
        margin: 12px;
        background-image: url('../../public/images/MarkB.png');
        background-repeat: no-repeat;
    }
    .in_div_txt_wendu_green {
        line-height: 48px; 
        padding-left: 30px;
        font-size: 30px;
        font-family: 'Microsoft YaHei';
        font-weight: 800;
        color: #00c745; /* #00c745;  lightgreen;*/
    }
    .in_div_txt_wendu_red {
        line-height: 48px;
        padding-left: 30px;
        font-size: 30px;
        font-family: 'Microsoft YaHei';
        font-weight: 800;
        color: red;
    }
    .in_div_txt_wendu_gray {
        line-height: 48px;
        padding-left: 30px;
        font-size: 30px;
        font-family: 'Microsoft YaHei';
        font-weight: 800;
        color: gray;
    }
    .in_div_txt_time {
        line-height: 0px;
        padding-left: 0px;
        font-size: 14px;
        color: gray;
    }
    .in_div_txt_labeltxtA {
        line-height: 35px;
        padding-left: 0px;
        font-size: 16px;
        font-family: 'Microsoft YaHei';
        font-weight: 600;
        color: black;
    }
    .in_div_txt_labeltxtB {
        line-height: 35px;
        padding-left: 0px;
        font-size: 16px;
        font-family: 'Microsoft YaHei';
        font-weight: 600;
        color: gray;
    }
    .in_div_txt_shidu {
        line-height:  0px;
        padding-left: 35px;
        font-size: 12px;
        font-family: 'Microsoft YaHei';
        font-weight: 500;
        color: #0000AA;  
    }    
</style>
<table width="100%" cellpadding="0" cellspacing="0" border="0">
	<tr>
		<td class="left" valign="top">
			<div class="mnu">
				<div class="head"></div>
				<ul>
				    <li><?php echo img('public/images/icons/house.png');?>温湿度实时数据</li> 
					<li><?php echo img('public/images/icons/index_03.png') . anchor('member/devices', '数据分析与导出');;?></li> 
            		<li><?php echo img('public/images/icons/drive.png')    . anchor('member/set_jinghua', '我的空气净化器');?></li>
					<li><?php echo img('public/images/icons/index_07.png') . anchor('member/bjinfo', '我的警情');?></li>
					<li><?php echo img('public/images/icons/index_09.png') . anchor('member/rulesAndPhoneChange', '我的报警规则设定');?></li>
					<li><?php echo img('public/images/icons/index_12.png') . anchor('member/bjSMSSend', '我的报警短信下发日志');?></li>
					<li><?php echo img('public/images/icons/index_15.png') . anchor('member/setparm', '主机参数设定');?></li>
			        <!-- class="active"--> 
				</ul>
				<!--200 150 标签位置< ?php echo img('public/images/eg1.png');?>--> 
			</div>
		</td>
		<td valign="top">
    		<!--以下是中间区域
            <div class="attr_panel" style="width: 1040px">   
                <div class="out_div">
                    <div class="text" style="font-size: 18px;font-family:'Microsoft YaHei';font-weight: 500;"> 丰台小太阳幼儿园（主机号10046） [测试]</div>
                    <!--width:1000px;-- >
                    <div class="in_div_MarkA">
                        <div style="background-color: blue;height:20px"></div>   
                        <span class="in_div_txt_wendu_green" style="background-color: red">99.9℃</span>
                        <div style="background-color: yellow">
                            <span class="in_div_txt_shidu">99.9%RH</span><span style="color:red">&nbsp;&nbsp;低电</span>
                        </div> 
                        <ul style="list-style-type:none">
                            <li class="in_div_txt_time">2017-06-01 12:12:12</li>
                            <li class="in_div_txt_labeltxtA">2#冰箱 509</li>
                        </ul>
                    </div>
                    <div class="in_div_MarkA">
                        <div style="background-color: ;height:20px"></div>
                        <span class="in_div_txt_wendu_red">24.5℃</span>
                        <div style="background-color: ">
                            <span class="in_div_txt_shidu">99.9%RH</span><span style="color:red">&nbsp;&nbsp;低电</span>
                        </div> 
                        <ul style="list-style-type:none">
                            <li class="in_div_txt_time">2017-06-01 12:12:12</li>
                            <li class="in_div_txt_labeltxtA">2#冰箱 509</li>
                        </ul>
                    </div>
                    <div class="in_div_MarkB">
                        <div style="background-color: ;height:20px"></div>
                        <span class="in_div_txt_wendu_gray">99.9℃</span>
                        <div style="background-color: ">
                            <span class="in_div_txt_shidu">99.9%RH</span><span style="color:red">&nbsp;&nbsp;低电</span>
                        </div> 
                        <ul style="list-style-type:none">
                            <li class="in_div_txt_time">2017-06-01 12:12:12</li>
                            <li class="in_div_txt_labeltxtB">2#冰箱 509</li>
                        </ul>
                    </div>  
                </div>
            </div>
            --> 
            <?php
            if (count($devices) > 0) 
            {
                foreach ($devices as $key => $val)   //循环主机
                { 
                   echo '<div class="attr_panel" style="width: 1040px"><div class="out_div">';  //P1-Begin-蓝色背景
                   ////echo '<div class="text" onclick="show_hidden('.$key.')" style="display:block;cursor: pointer;" onMouseOver="over_out('.$key.')"  >' . img('public/images/icons/log.png') . $val['text'] . '</div>';
                   echo '<div class="text" style="font-size: 18px;font-family:\'Microsoft YaHei\';font-weight: 500;">' . $val['textA'] . '</div>';  //P2-别名
                   // print($key);
                   
                   foreach ($val['children'] as $key2 => $val2)  //循环标签
                   { 
                       if(strstr($val2['text'],"[") && strstr($val2['text'],"]") && ($val2['label_category']!= '000000000'))  //8个0是净化器
                       {
                           //1th
                           if ($val['onlineNum'] == 1) 
        				       echo '<div class="in_div_MarkA">';  //P3-Begin-牌子
                           else 
                               echo '<div class="in_div_MarkB">';  //P3-Begin-牌子 
                           
                           //2th
                           ////echo '<input type="radio"  checked="checked"  value="' . $val2['id'] . '_' . $val2['label_category'] . '_' . $val['text'] . '_' . $val2['text'] .$val['host_code'].'_'.$val['host_alias'].  '" name="labels[]" onclick="show_data(true);">' . $val2['text'] . '<br />';
                           //判定数据是否过旧
                           $IsLabelOld = '';
                           if(1==1)
                           {
                               $nowtime = strtotime(date("Y-m-d H:i:s"));
                               ////$dateline = strtotime($nowtime); //标准时间转为时间戳
                               ////$nowtime = date('H:i:s',$dateline); //时间戳转为标准时间
                               $CompCurrWDTime = strtotime($val2['CurrWDTime']);
                               $CompCurrWDTime_Add1Hour = strtotime('+1 hour' , strtotime($val2['CurrWDTime']));
                               if($CompCurrWDTime_Add1Hour < $nowtime)
                                   $IsLabelOld = '1';
                               else
                                   $IsLabelOld = '0';
                           }
                           if($val['onlineNum'] == 1 && $IsLabelOld != '1')  //在线  并且 标签不是旧数据
                           { 
                               if($val2['CurrWDIsBj']== 1)  //报警
                               {
                                   echo "<div style=\"height:20px\"></div>";
                                   echo "<span class='in_div_txt_wendu_red'>" . $val2['CurrWDVal'] ."</span>";
                                   echo ""; 
                                   echo "<div>" . $val2['CurrSDVal'] . $val2['CurrDYVal'] . "</div>";
                                   echo "<ul style=\"list-style-type:none\">";
                                   echo "   <li class=\"in_div_txt_time\">" . $val2['CurrWDTime'] ."</li>";
                                   echo "   <li class=\"in_div_txt_labeltxtA\">" . $val2['textA'] ."</li>";
                                   echo "</ul>";
                               }
                               else 
                               {
                                   echo "<div style=\"height:20px\"></div>";
                                   echo "<span class='in_div_txt_wendu_green'>" . $val2['CurrWDVal'] ."</span>";
                                   echo "";
                                   echo "<div>" . $val2['CurrSDVal'] . $val2['CurrDYVal'] . "</div>";
                                   echo "<ul style=\"list-style-type:none\">";
                                   echo "   <li class=\"in_div_txt_time\">" . $val2['CurrWDTime'] ."</li>";
                                   echo "   <li class=\"in_div_txt_labeltxtA\">" . $val2['textA'] ."</li>";
                                   echo "</ul>";
                               }
                           }
                           else 
                           {
                               echo "<div style=\"height:20px\"></div>";
                               echo "<span class='in_div_txt_wendu_gray'>" . $val2['CurrWDVal'] ."</span>";
                               echo "";
                               echo "<div>" . $val2['CurrSDVal'] . $val2['CurrDYVal'] . "</div>";
                               echo "<ul style=\"list-style-type:none\">";
                               echo "   <li class=\"in_div_txt_time\">" . $val2['CurrWDTime'] ."</li>";
                               echo "   <li class=\"in_div_txt_labeltxtB\">" . $val2['textA'] ."</li>";
                               echo "</ul>";
                           }
                           //3th
                           echo '</div>'; //P3-End
                       }
                   } 
                   echo '</div></div>';  //P1-End
                }
            }
            ?> 
            
            <!-- 注释
			<div class="attr_panel">
				显示数据：<input type="checkbox" id="alert_1" checked="checked"
					onclick="select_check('alert_1');" /> <span class="level_1">温度</span>
				<input type="checkbox" id="alert_2"
					onclick="select_check('alert_2');" /> <span class="level_2">湿度</span>
				<input type="checkbox" id="alert_3"
					onclick="select_check('alert_3');" /> <span class="level_3">电压</span>
				<input type="checkbox" id="alert_4"
					onclick="select_check('alert_4');" /> <span class="level_4">颗粒物PM2.5</span>
				<input type="checkbox" id="alert_5"
					onclick="select_check('alert_5');" /> <span class="level_5">颗粒物PM10</span>
				<input type="checkbox" id="alert_6"
					onclick="select_check('alert_6');" /> <span class="level_6">负氧离子数量</span>
				<input type="checkbox" id="alert_7"
					onclick="select_check('alert_7');" /> <span class="level_7">有机污染物(甲醛)</span>
				<input type="checkbox" id="alert_8"
					onclick="select_check('alert_8');" /> <span class="level_8">空气质量等级</span>
				<input type="hidden" id="order_type" name="order_type" value="0" />
				<br /> <br /> 显示方式：<input type="radio" id="show_type"
					name="show_type" value="0" checked="checked" onclick="show_data();" />
				智能显示 <input type="radio" id="show_type" name="show_type" value="1"
					onclick="{if(confirm('全部查询结果比较多,速度会比较慢，建议使用智能显示,确定要全部显示吗?')){show_data();return true;} else {return false;}}" />
				全部显示&nbsp;&nbsp;&nbsp;&nbsp; 时间范围： <input class="easyui-datetimebox"
					id="begin_date" name="begin_date"
					value="< ?php echo date('Y-m-d 00:00:00');?>"></input> - <input
					class="easyui-datetimebox" data-options="buttons:buttons" id="end_date"
					name="end_date" value="< ?php echo date('Y-m-d 23:59:59');?>"></input> <input
					type="button" value="确定" onclick="show_data();" />
			</div>
			<table border="0" cellpadding="10" cellspacing="10">
				<tr>
					<td>
						<div id="hostarea">
							<div class="left" style="width: 600px;" id="labelarea"></div>
							<div class="right">
							 < ?php 
    							 if(strstr($login_user,"guest")==true)
    							 {
    							     //hide 就这样
    							 }
    							 else 
    							 {
    							     //show
    							     echo anchor('member/hosts', '主机(别名)设置');
    							     echo anchor('member/labels', '标签(别名)设置');
    							 }
							 ?>
							</div>
						</div>
					</td>
				</tr>
				<tr>
					<td><div id="graph">数据加载...</div></td>
				</tr>
				<tr>
					<td><iframe id="detaildata" width="1000" height="340"
							frameborder="0"
							src="< ?php echo site_url();?>/member/devices/datagrid"> </iframe>

						<p>< ?php echo img('public/images/icons/excel.png');?><a
								href="javascript:void(0);" onclick="export_to_excel();">导出到EXCEL</a>
						</p>
						<p>< ?php echo img('public/images/icons/excel.png');?><a
								href="javascript:void(0);" onclick="export_to_excel2();">导出该主机下全部标签到EXCEL</a>
						</p>
						</td>
				</tr>
			</table>
			-->
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
	var alias=document.getElementById("labelarea").checked;
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
			//arr_check[i] = data2[0];
            arr_check.push(data2[0]);  //zxg  
        }
    }
	var labels = arr_check.join();
	var params = 'begin_date=' + begin_date + '&alias='+ alias + '&end_date=' + end_date + '&show_type=' + show_type + '&order_type=' + order_type + 
	    '&alert_1=' + alert_1 + '&alert_2=' + alert_2 + '&alert_3=' + alert_3 + '&alert_4=' + alert_4 + '&alert_5=' + alert_5 + 
		'&alert_6=' + alert_6 + '&alert_7=' + alert_7 + '&alert_8=' + alert_8 + '&labels=' + labels;
	var url = '<?php echo site_url();?>/member/devices/export/' + params;
	window.location.href = url;
	return false;	
}
function export_to_excel2() {	
	var begin_date = $("#begin_date").datebox('getValue');
	var end_date = $("#end_date").datebox('getValue');
	//var date_type = $("#date_type").val();
	var alias=document.getElementById("labelarea").checked;
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
	var host ="";
	for(var i=0;i<nodes.length;i++)
    {
        if(nodes[i].checked)
        {
            var data2 = nodes[i].value.split("_");	
            host = data2[4];
            break;
        }
    }
	var arr_check2 = new Array(); 
	var arr_check4 = new Array(); 

	var host_alias='';
	var host_code='';
	//var labels_alias='2';
	for(var i=0;i<nodes.length;i++)
    {  
       var data3=nodes[i].value.split("_");
       if(data3[4] == host)
       {
           arr_check2.push(data3[0]);
           host_alias=data3[5];
           arr_check4.push(data3[3]);
           host_code=data3[4];
       }  
    }
	host_alias = encodeURIComponent(host_alias);
	//host_alias = escape(host_alias);
	
	var labels = arr_check2.join();
	var label_alias=arr_check4.join();
	label_alias = encodeURIComponent(label_alias);
	var params = 'begin_date=' + begin_date + '&alias='+ alias + '&end_date=' + end_date + '&show_type=' + show_type + '&order_type=' + order_type + 
	    '&alert_1=' + alert_1 + '&alert_2=' + alert_2 + '&alert_3=' + alert_3 + '&alert_4=' + alert_4 + '&alert_5=' + alert_5 + 
		'&alert_6=' + alert_6 + '&alert_7=' + alert_7 + '&alert_8=' + alert_8 + '&host_code=' + host_code; //'&labels=' + labels+
	var url = '<?php echo site_url();?>/member/devices/export2/' + params;
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

function show_data(IsUpdateShowType) {  //IsUpdateShowType 是否更新显示种类
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

          //arr_check[i] = data2[0];
          arr_check.push(data2[0]);  //zxg  
          
	      check_data = xor(check_data,data2[1]);
		  $('#labelarea').html('当前主机：' + data2[2] + '，当前标签：' + data2[3]);
        }
    }
	if (IsUpdateShowType===true && check_data != '') {
	  	change_check(check_data);
	}
    drawChart();
}

function drawChart() {
	document.getElementById("graph").innerHTML = "数据加载...";//zxg
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
            //arr_check[i] = data2[0]; err
            arr_check.push(data2[0]);  //zxg    
        }
    }	
  var labels = arr_check.join();

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
            labels: labels
          },
          error: function() {
            //alert('数据不正确，请刷新重试。');
          	//zxg 数据查询失败 折线图隐藏
        	document.getElementById("graph").innerHTML = "数据加载失败!";
            return false;
          },
          success: function(data, status) {
        	  try
              {
      		    drawChartDetail(data); 
              }
              catch(e)
              {}               
          }
        }); 
    //change iframe src
    
    var params = 'begin_date=' + begin_date + '&end_date=' + end_date + '&show_type=' + show_type + '&order_type=' + order_type + 
	    '&alert_1=' + alert_1 + '&alert_2=' + alert_2 + '&alert_3=' + alert_3 + '&alert_4=' + alert_4 + '&alert_5=' + alert_5 + 
		'&alert_6=' + alert_6 + '&alert_7=' + alert_7 + '&alert_8=' + alert_8 + '&labels=' + labels;
	  var url = '<?php echo site_url();?>/member/devices/datagrid/' + params; 
    $('#detaildata').attr("src",url); 
}

function drawChartDetail(data)
{  
  var l = data.total;
  var data2 = data.data;
  var x_text = data.x_text;
  var y_text = data.y_text;
 
  if(x_text.length == 0)//说明x方向没有值
  {
	  document.getElementById("graph").innerHTML = "";//zxg
	  //$("[width=77]").hide();
	  return; //没有查到数据 返回
  }
  
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
        myChart.setTooltip([j-1,'时间：' + x_text[j-1] ]); //zxg 第二次会被第一次盖掉 以后再解决吧 + '<br />数值：' + parseFloat(data2[i][j])]);

        //设置下轴下标
        if (j == 1 || j == parseInt(n/2) || j == n-1)
        {
            //zxg 补空格
            var showTimeFm='';
            if(j==1)
            	showTimeFm='     '+x_text[j-1]+'';
            else if(j==parseInt(n/2))
            	showTimeFm=''+x_text[j-1]+'';
            else if(j==n-1)
            	showTimeFm=''+x_text[j-1]+'          ';
     	   myChart.setLabelX([j-1,showTimeFm]);
        }        
        color_desc = data2[i][0];
      }
      json_data += json_arr.join(',') + ']';
      //myChart.setLabelY([parseFloat(data2[i][1]),y_text[i]]);
      try
      {
          if(json_data !== '[]')//说明x方向有值
          {
              myChart.setDataArray(eval('(' + json_data + ')'), color_name[i]);  
              myChart.setLegendForLine(color_name[i],y_text[i]);
              myChart.setLineColor(color_desc, color_name[i]);
          }
      }
      catch(e)
      {}
    }
    myChart.setLegendShow(true);
    myChart.setLegendPosition(920, 100);    //图例坐标
    myChart.setLineWidth(1);
    myChart.setLineSpeed(100);
    myChart.setFlagRadius(2);
    //myChart.setAxisValuesSuffixY('');
    myChart.setGridOpacityY(0);
    myChart.setLabelFontSizeX(6);
    myChart.setLabelFontSizeY(6);
    myChart.setShowXValues(false);
    myChart.setAxisNameX('时间       ');
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
  show_data(true);
  setInterval("hide_logo()",1000);
});
</script>
<script type="text/javascript">
function show_hidden(index){
	try
	{
    	var div=document.getElementById("tr"+index);
    
        if(div.style.display=="block"){
            div.style.display='none';
        }else{
            div.style.display='block';
        }
	}
	catch(e)
	{
	}
};
</script>
<script type="text/javascript">
function over_out(index){
	try
	{
    	var div=document.getElementById("tr"+index);
    
        if(div.style.text-decoration="underline"){
            div.style.text-decoration='none';
        }else{
            div.style.text-decoration='underline';
        }
	}
	catch(e)
	{
	}
};
</script>
<script>
  var buttons = $.extend([], $.fn.datebox.defaults.buttons);
</script>