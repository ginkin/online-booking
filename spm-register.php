 <?php
	$con=mysqli_connect("127.0.0.1","swen90016","swen90016","swen90016"); //连接数据库，且定位到数据库web1
	if(!$con){die("error:".mysqli_connect_error());} //如果连接失败就报错并且中断程序
	$email=$_POST['email'];
	$email=str_replace("%40","@",$email);
	$pass=$_POST['password'];
	$name=$_POST['name'];
	$address=$_POST['address'];
	$phone=$_POST['phone'];
	$info=$_POST['info'];
	if($email==null||$pass==null){
	    echo "<script>alert('Username/password can't be empty)</script>";
	    die("empty username/password");
	}
	
	$sql='insert into user_info (user_id, email, password, name, address, phone, info, biller, biller_email, user_type) values (NULL, '."'{$email}','{$pass}','{$name}','{$address}',{$phone},'{$info}',NULL,NULL,1)";
	$res=mysqli_query($con,$sql);
	echo $sql;
	
?>


