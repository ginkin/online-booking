 <?php
	$con=mysqli_connect("127.0.0.1","swen90016","swen90016","swen90016"); 
	if(!$con){die("error:".mysqli_connect_error());} 
	$user_id=$_POST['user_id'];

	$sql2="select * from user_info where user_id={$user_id}";
	$res=mysqli_query($con,$sql2);
	$row=$res->num_rows; 
	if($row!=0)
	{
		$record=$res->fetch_array();
		$address=$record[4];
		echo $address;
		//$data='{user_id:'.$user_id.',password:"'.$password.'",email:"'.$email.'",name:"'.$name.'",address:"'.$address.'",phone:"'.$phone.'",info:"'.$info.'",biller:"'.$biller.'",biller_email:"'.$biller_email.'"}';
		//  echo json_encode($data);
	  //header("Location:beauty-care-platform?username={$user_id}");
	}
	else
	{
	  echo "Wrong modification information!\nCheck your input";
	}
	
?>


