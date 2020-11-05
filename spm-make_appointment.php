 <?php
	$con=mysqli_connect("127.0.0.1","swen90016","swen90016","swen90016"); //连接数据库，且定位到数据库web1
	if(!$con){die("error:".mysqli_connect_error());} //如果连接失败就报错并且中断程序
	$user_id=$_POST['user_id'];
	$services=$_POST['services'];
	$date=$_POST['date'];
	$time=$_POST['time'];
	$location=$_POST['location'];
	$message=$_POST['message'];

	
	$sql='insert into appointment (appointment_id, user_id, service_name, date, time, location, message) values (NULL, '."{$user_id},'{$services}','{$date}',{$time},'{$location}','{$message}')";
	$res=mysqli_query($con,$sql);
	echo $sql;
	
?>


