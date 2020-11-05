 <?php
	$con=mysqli_connect("127.0.0.1","swen90016","swen90016","swen90016"); 
	if(!$con){die("error:".mysqli_connect_error());} 
	$user_id=$_POST['user_id'];
	$password=$_POST['password'];
	$email=$_POST['email'];
	$name=$_POST['name'];
	$address=$_POST['address'];
	$phone=$_POST['phone'];
	$info=$_POST['info'];
	$biller=$_POST['biller'];
	$biller_email=$_POST['biller_email'];
	$sql="update user_info set password='{$password}', email='{$email}', name='{$name}', address='{$address}', phone={$phone}, info='{$info}', biller='{$biller}', biller_email='{$biller_email}' where user_info.user_id={$user_id}";

	$res=mysqli_query($con,$sql);

	$sql2="select * from user_info where user_id={$user_id}";
	$res=mysqli_query($con,$sql2);
	$row=$res->num_rows; 
	if($row!=0)
	{
		$record=$res->fetch_array();
		$user_id=$record[0];
		$email=$record[1];
		$password=$record[2];
		$name=$record[3];
		$address=$record[4];
		$phone=$record[5];
		$info=$record[6];
		$biller=$record[7];
		$biller_email=$record[8];
		$user_type=$record[9];

		$data='{user_id:'.$user_id.',password:"'.$password.'",email:"'.$email.'",name:"'.$name.'",address:"'.$address.'",phone:"'.$phone.'",info:"'.$info.'",biller:"'.$biller.'",biller_email:"'.$biller_email.'"}';
		  echo json_encode($data);
	  //header("Location:beauty-care-platform?username={$user_id}");
	}
	else
	{
	  echo "Wrong modification information!\nCheck your input";
	}
	
?>


