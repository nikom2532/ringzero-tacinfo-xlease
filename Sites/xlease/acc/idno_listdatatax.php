<?php
 session_start();
 include("../config/config.php");
 $id_no=pg_escape_string($_GET["names"]);
    
  $sql_select=pg_query("select * from  \"VCarregistemp\" 
	where (\"IDNO\" like '%$id_no%') OR (\"C_REGIS\" like '%$id_no%') OR (\"C_CARNUM\" like '%$id_no%') OR  (\"full_name\" like '%$id_no%') LIMIT(15)  ");
	
    $nrows=pg_num_rows($sql_select);
	
	if($nrows==0)
	{
	  echo "ไม่พบข้อมูล ".",";
	}
	else
	{	
     while ($result=pg_fetch_array($sql_select))
     {	
	 	  
	  echo trim($result[IDNO]).":"." ทะเบียน ".trim($result[C_REGIS])." ชื่อ ".trim($result[full_name])." เลขตัวถัง ".trim($result[C_CARNUM]).",";  
     }
   }
	
?>