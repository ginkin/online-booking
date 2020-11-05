 <?php
	$con=mysqli_connect("127.0.0.1","swen90016","swen90016","swen90016"); //连接数据库，且定位到数据库web1
	if(!$con){die("error:".mysqli_connect_error());} //如果连接失败就报错并且中断程序
	$date=$_POST['date'];

	$sql='select * from appointment where date='."'{$date}'";
	$res=mysqli_query($con,$sql);
	$row=$res->num_rows; 
	$record=$res->fetch_all();

	$data='';
	for ($i=0;$i<$row;$i++){
		$time=$record[$i][4];
		$data=$data."{$time},";
	}
	$data = substr($data,0,strlen($data)-1);

	echo $data;
	
	
?>


