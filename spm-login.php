<?php
$con=mysqli_connect("127.0.0.1","swen90016","swen90016","swen90016"); //连接数据库，且定位到数据库web1
if(!$con){die("error:".mysqli_connect_error());} //如果连接失败就报错并且中断程序
$user=$_POST['user'];
$pass=$_POST['pass'];
if($user==null||$pass==null){
    echo "<script>alert('你不是管理员吧！')</script>";
    die("账号和密码不能为空!");
}function check_param($value=null) {  //过滤注入所用的字符，防止sql注入    
  $str = 'select|insert|and|or|update|delete|\'|\/\*|\*|\.\.\/|\.\/|union|into|load_file|outfile';    

  return true;
}
if(check_param($user)==true&&check_param($pass)==true){
  $sql='select * from user_info where username='."'{$user}'and password="."'$pass';";
  $res=mysqli_query($con,$sql);
  $row=$res->num_rows; //将获取到的用户名和密码拿到数据库里面去查找匹配
  if($row!=0)
  {
      //echo "<h1>登录成功，欢迎您&nbsp{$user}！</h1>";
      header("Location:beauty-care-platform");
  }
  else
  {
      echo "用户名或密码错误";
  }}
?>