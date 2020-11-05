<script language="javascript"> 
function login(){
  var username = document.getElementByName("user");
  var password = document.getElementByName("pass");
  var conn = new ActiveXObject("ADODB.Connection"); 
  conn.Open("Provider=SQLOLEDB.1; Data Source=swen90016; User ID=swen90016" 
  +"; Password=swen90016; Initial Catalog=MyBulletin"); 
  var rs = new ActiveXObject("ADODB.Recordset"); 
  var sql="select user_id from user_info where username"; 
  rs.open(sql, conn);
  alert(rs(0));//取出第一个来
  rs.close();  
  rs = null;  
  conn.close();  
  conn = null; 
}
  
</script> 