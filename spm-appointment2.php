 <?php
	$con=mysqli_connect("127.0.0.1","swen90016","swen90016","swen90016"); //连接数据库，且定位到数据库web1
	if(!$con){die("error:".mysqli_connect_error());} //如果连接失败就报错并且中断程序
	$user_id=$_POST['user_id'];
	$sql='SELECT * FROM appointment,user_info WHERE appointment.user_id=user_info.user_id AND user_info.user_id='."{$user_id}";
	$res=mysqli_query($con,$sql);
	$row=$res->num_rows; 
	$record=$res->fetch_all();

	$data='{';
	for ($i=0;$i<$row;$i++){
		$name=$record[$i][10];
		$phone=$record[$i][12];
		$email=$record[$i][8];
		$service=$record[$i][2];	
		$date=$record[$i][3];
		$time=$record[$i][4];
		$location=$record[$i][5];
		$message=$record[$i][6];
		$data=$data."{$i}:".'{name:"'.$name.'",phone:"'.$phone.'",service:"'.$service.'",email:"'.$email.'",date:"'.$date.'",time:"'.$time.'",location:"'.$location.'",message:"'.$message.'"},';
	}
	$data = substr($data,0,strlen($data)-1).'}';

	//$data='{service_id:'.$service_id.',service_name:"'.$service_name.'",charge:"'.$charge.'",name:"'.$name.'",address:"'.$address.'",phone:"'.$phone.'",info:"'.$info.'",biller:"'.$biller.'",biller_email:"'.$biller_email.'",user_type:"'.$user_type.'"}';
	echo json_encode($data);
	
	
?>


