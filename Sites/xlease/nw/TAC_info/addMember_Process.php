<?php
	session_start();
	include("config.php");
	$username=$_POST['tbxUsername'];
	$password=md5($_POST['tbxPassword']);
	$beforName=$_POST['lbxBeforeName'];
	$firstName=$_POST['tbxName'];
	$lastName=$_POST['tbxLastName'];
	$carCode=$_POST['tbxCarNumber'];
	$telNumber=$_POST['tbxTelephoneNumber'];
	$isAdmin=$_POST['lbxUserType'];
	//$disabled="n";
	$loginResult="";
	$loginStatus=0;
	$sql="SELECT * FROM \"TrMember\" WHERE \"Username\"='$username'";
	$dbquery=pg_query($sql);
	$rows=pg_num_rows($dbquery);
	if($rows==0)
	{
		$sql="insert into \"TrMember\"(\"Username\", \"Password\", \"isAdmin\", \"carregistrationnumber\", \"telephonenumber\", \"disabled\", \"beforName\", \"firstName\", \"lastName\") values('$username','$password','$isAdmin','$carCode','$telNumber','$disabled','$beforName','$firstName','$lastName')";
		$dbquery=pg_query($sql);
		$loginResult="เพิ่มสมาชิกเรียบร้อยแล้ว";
		$loginStatus=1;
	}
	else
	{
		$loginResult="Username นี้ถูกใช้งานแล้ว";
		$loginStatus=0;
	}
?>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>TR Member</title>
<link href="info.css" rel="stylesheet" type="text/css">
</head>

<body>
	<div align="center">
    	<div id="main">
        	<table width="800" border="0" cellpadding="0" cellspacing="0">
            	<tr>
                	<td height="190">
                    <iframe width="800" height="220" src="header.php" frameborder="0" name="iframe_header"></iframe>
                    </td>
           	  	</tr>
                <tr>
                	<td align="center" valign="top" id="content">
               	  <table border="0" cellpadding="0" cellspacing="0">
                            <tr>
                                <td height="400" align="center" valign="top" id="content">
                                    <div id="content_box">
                                        <table width="780" border="0" cellpadding="0" cellspacing="0">
                                            <tr>
                                                <td valign="middle" id="td_top_menu">Add Member :: เพิ่มสมาชิก</td>
                                            </tr>
                                            <tr>
                                                <td valign="middle" align="center" id="top_menu_border">
                                                    <table border="0" cellpadding="0" cellspacing="0" width="400">
                                                        <tr>
                                                            <td align="right" id="usernameLabel"></td>
                                                            <td></td>
                                                        </tr>
                                                        <tr>
                                                            <td align="center" id="lbWelcome">
                                                            <?php
																echo $loginResult;
																if($loginStatus==1)
																{
																	echo"<h2>กลับไปยังหน้าเพิ่มสมาชิก <a href=\"addMember.php\">คลิกที่นี่</a></h2>";
																}
																else
																{
																	echo "<h2>กลับไปยังหน้าเพิ่มสมาชิก <a href=\"javascript:history.back()\">คลิกที่นี่</a></h2>";
																}
															?>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td></td>
                                                            <td id="td_login_button"></td>
                                                        </tr>
                                                    </table>
                                                </td>
                                            </tr>
                                        </table>
                                    </div>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
                <tr>
                	<td height="30">
                    	<iframe width="800" height="30" frameborder="0" src="footer.php" scrolling="no"></iframe>
                    </td>
                </tr>
            </table>
        </div>
	</div>
</body>
</html>