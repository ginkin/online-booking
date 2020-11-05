 <?php
	$con=mysqli_connect("127.0.0.1","swen90016","swen90016","swen90016"); //连接数据库，且定位到数据库web1
	if(!$con){die("error:".mysqli_connect_error());} //如果连接失败就报错并且中断程序
	$email=$_POST['email'];
	$email=str_replace("%40","@",$email);
	$pass=$_POST['pass'];
	if($email==null||$pass==null){
	    echo "<script>alert('Username/password can't be empty)</script>";
	    die("empty username/password");
	}
	
	$sql='select * from user_info where email='."'{$email}' and password="."'{$pass}';";
	$res=mysqli_query($con,$sql);
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

		  $data='{user_id:'.$user_id.',password:"'.$password.'",email:"'.$email.'",name:"'.$name.'",address:"'.$address.'",phone:"'.$phone.'",info:"'.$info.'",biller:"'.$biller.'",biller_email:"'.$biller_email.'",user_type:"'.$user_type.'"}';
		  echo json_encode($data);
		
	  /**echo $dom->saveHTML();
	  echo "yes";
	  $url="spm-info.php?user_id={$user_id}";
	  echo "<script type='text/javascript' language='javascript'>";
	echo "location.href='{$url}'";
	echo "</script>";**/
	  //header("spm-info.php?user_id={$user_id}");
	}
	else
	{
	  echo "Error username/password";
	}
	
?>


