<?php
/*
保存shortcut.php访问即可保存桌面
*/
$title="新乡九天-新乡园长采购服务中心";
$Shortcut = "[InternetShortcut]
URL=http://info.bjxwhl.com:8000/upload/index.php?route=account/login
IDList=
[{000214A0-0000-0000-C000-000000000046}]
Prop3=19,2";
Header("Content-type: application/octet-stream");
header("Content-Disposition: attachment; filename=".$title.".url;");
echo $Shortcut;
?>