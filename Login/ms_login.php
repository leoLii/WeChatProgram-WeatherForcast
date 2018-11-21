
<?php
//从test数据库中提取数据
function judge($name, $password){

require_once ("mysql_connect.php");
$sql = "select * from user where name = '$name'";
$result = mysql_query($sql);
$row = mysql_fetch_array($result);
if ($row){
  if ($row['password'] == $password){
    return 2;
  }
  else{
    return 1;
  }
}
else {
  return 0;
}

  
}
