 <?php
	$con=mysqli_connect("127.0.0.1","swen90016","swen90016","swen90016"); //连接数据库，且定位到数据库web1
	if(!$con){die("error:".mysqli_connect_error());} //如果连接失败就报错并且中断程序
	
	$sql='select * from beauty_care_service';
	$res=mysqli_query($con,$sql);
	$row=$res->num_rows; 
	$record=$res->fetch_all();

	$data='{';
	for ($i=0;$i<$row;$i++){
		$service_id=$record[$i][0];
		$service_name=$record[$i][1];
		$charge=$record[$i][2];
		$data=$data."{$i}:".'{service_id:'.$service_id.',service_name:"'.$service_name.'",charge:"'.$charge.'"},';
	}
	$data = substr($data,0,strlen($data)-1).'}';
	



	//$data='{service_id:'.$service_id.',service_name:"'.$service_name.'",charge:"'.$charge.'",name:"'.$name.'",address:"'.$address.'",phone:"'.$phone.'",info:"'.$info.'",biller:"'.$biller.'",biller_email:"'.$biller_email.'",user_type:"'.$user_type.'"}';
	echo json_encode($data);
	
	
?>


