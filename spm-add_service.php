 <?php
	$con=mysqli_connect("127.0.0.1","swen90016","swen90016","swen90016"); //连接数据库，且定位到数据库web1
	if(!$con){die("error:".mysqli_connect_error());} //如果连接失败就报错并且中断程序
	$new_service=$_POST['new_service'];
	$charge=$_POST['charge'];

	$sql='insert into beauty_care_service (service_id, service_name, charge) values (NULL, '."'{$new_service}',{$charge})";
	$res=mysqli_query($con,$sql);
	echo $sql;
	
	
?>


