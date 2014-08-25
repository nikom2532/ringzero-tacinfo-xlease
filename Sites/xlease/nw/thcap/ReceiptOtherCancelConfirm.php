<?php
set_time_limit(0);
session_start();
include("../../config/config.php");
$contractID=$_GET['contractID'];
$contractID2=$_GET['contractID'];
$receiptID=$_GET['receiptID'];
$statusshow=$_GET['statusshow'];
$cancelID=$_GET['cancelID'];


?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link type="text/css" rel="stylesheet" href="act.css"></link>
<link type="text/css" href="../../jqueryui/css/ui-lightness/jquery-ui-1.8.2.custom.css" rel="stylesheet" />    
<script type="text/javascript" src="../../jqueryui/js/jquery-1.4.2.min.js"></script>
<script type="text/javascript" src="../../jqueryui/js/jquery-ui-1.8.2.custom.min.js"></script>
<title>รายละเอียดการยกเลิก</title>
<script language="JavaScript" type="text/javascript">
function RefreshMe(){
    opener.location.reload(true);
    self.close();
}
function confirmapp(){
	if(confirm('ยืนยันการอนุมัติยกเลิกใบเสร็จ!!'))
	{ return true;	}
	else{return false;}
}
$(document).ready(function(){
	$("#buttonsave").click(function(){
		if($("#resultcancel").val()==""){
			alert('กรุณาระบุเหตุผลที่ยกเลิกใบเสร็จ');
			$('#resultcancel').select();
			return false;
		}else{
			if(confirm('คุณยืนยันที่จะยกเลิกใบเสร็จเหล่านี้!!')){
				$("#buttonsave").submit();
			}else{
				return false;
			}
		}
	});
});
</script> 
</head>
<body>

<form method="post" name="form1" action="process_receiptcancel.php">
<table width="100%" cellSpacing="1" cellPadding="3" border="0" bgcolor="#EAF9FF" align="center">
<?php

//ตรวจสอบข้อมูลก่อนว่ารายการได้ถูกอนุมัติไปก่อนหน้านี้หรือยัง
$qrycheck=pg_query("select * from thcap_temp_receipt_cancel where \"receiptID\" = '$receiptID' and \"approveStatus\"='1'");
$numcheck=pg_num_rows($qrycheck);
if($numcheck==1){ //เท่ากับ 1 แสดงว่ามีการอนุมัติรายการแล้ว
	$stscheck=2;
	echo "<tr><td colspan=3 align=center>
	<h2>รายการนี้ได้ทำการอนุมัติหรือไม่อนุมัติไปก่อนหน้านี้แล้วค่ะ</h2><br>
	<input type=\"submit\" value=\"  ปิด  \" onclick=\"javascript:RefreshMe();\" /></td></tr>";
}else{
	$stscheck=1;
}
if($statusshow==1){
//ตรวจสอบว่ามีเลขที่สัญญานั้นรออนุมัติอยู่หรือไม่
		$qrycheck=pg_query("select \"typePayID\" from thcap_temp_receipt_cancel a
		left join \"thcap_temp_receipt_otherpay\" b on a.\"receiptID\"=b.\"receiptID\"
		where a.\"receiptID\" = '$receiptID' and \"approveStatus\"='2'");
		$numcheck=pg_num_rows($qrycheck);
		
		if($numcheck>0){ //กรณีมีรายการที่รออนุมัติ
			$stscheck=2;
			echo "<tr><td colspan=3 align=center>
			<h2>ไม่สามารถขอยกเลิกใบเสร็จได้ เนื่องจากมีใบเสร็จค่างวดที่รออนุมัีติอยู่</h2><br>
			<input type=\"submit\" value=\"  ปิด  \" onclick=\"javascript:RefreshMe();\" /></td></tr>";
		}
}
if($stscheck==1){
?>
<tr>
    <td align="center" colspan="3">
	<h2>
	<?php
	if($statusshow==1){
		echo "- ยืนยันการยกเลิกใบเสร็จค่าอื่นๆ -";
	}else{
		echo "อนุมัติการยกเลิกใบเสร็จ";
	}
	?>
	</h2>
	</td>
</tr>
<?php
if($statusshow==2){
?>
<tr><td align="right"><span onclick="window.close();" style="cursor:pointer;"><u>X ปิดหน้านี้</u></span></td></tr>
<?php
}
?>
<tr>
    <td height="25"><b>ใบเสร็จค่าอื่นๆที่ขอยกเลิก: <font color="red"><?php echo $receiptID; ?></font></b></td>
</tr>
<tr>
    <td height="25" bgcolor="#FFEFD5">
	<table width="100%" cellpadding="3" cellspacing="0" border="0" style="border-style: dashed; border-width: 1px; border-color:#8B7765; margin-bottom:3px"><tr><td>
	<?php 
	$showconfig="no";
	include "Channel_detail.php"; 
	?>
	</td></tr></table>
	</td>
</tr>
<?php
	//หาเหตุผลโดยการนำเลขที่ใบเสร็จไปค้นในตาราง  thcap_temp_receipt_cancel
	$qryresult=pg_query("SELECT result FROM thcap_temp_receipt_cancel where \"receiptID\"='$receiptID' and \"approveStatus\"='2'");
	$resresult=pg_fetch_array($qryresult);
	list($result)=$resresult;
?>
<tr bgcolor="#F5F5F5">
	<td colspan="5"><b>::เหตุผลที่ยกเลิก::</b><br><textarea name="resultcancel" id="resultcancel" cols="50" rows="5" <?php if($result!="") echo "readonly=true";?>><?php echo $result;?></textarea></td>
</tr>
<tr>
    <td height="25"><b><font color="red">* จะยกเลิกทุกรายการในใบเสร็จนี้</font></b></td>
</tr>
<?php
	if($statusshow==1){
?>
	<tr>
		<td align="center" bgcolor="#FFFFFF" height="50">
		<input type="hidden" name="contractID" value="<?php echo $contractID2;?>">
		<input type="hidden" name="receiptID" value="<?php echo $receiptID;?>">
		<input type="hidden" name="receiveDate" value="<?php echo $receiveDate;?>">
		<input type="hidden" name="method" value="request_other">
		<input type="submit" value="บันทึก" id="buttonsave">
		<input type="button" onclick="window.close();" value="ปิดหน้านี้">
		</td>
	</tr>
<?php
	}else{
?>
	<!--tr><td align="center" bgcolor="#FFFFFF" height="50">
		<input type="button" value="อนุมัติ" onclick="if(confirm('ยืนยันการอนุมัติยกเลิกใบเสร็จ!!')){location.href='process_receiptcancel.php?contractID=<?php echo $contractID;?>&receiptID=<?php echo $receiptID;?>&receiveDate=<?php echo $receiveDate;?>&cancelID=<?php echo $cancelID;?>&method=approve1'}">
		<input type="button" value="ไม่อนุมัติ" onclick="location.href='process_receiptcancel.php?contractID=<?php echo $contractID;?>&receiptID=<?php echo $receiptID;?>&receiveDate=<?php echo $receiveDate;?>&cancelID=<?php echo $cancelID;?>&method=approve0'">
	</td></tr-->
	<tr>
		<input type="hidden" name="contractID" id="contractID" value="<?php echo $contractID;?>">
		<input type="hidden" name="receiptID" id="receiptID" value="<?php echo $receiptID;?>">
		<input type="hidden" name="receiveDate" id="receiveDate" value="<?php echo $receiveDate;?>">
		<input type="hidden" name="cancelID" id="cancelID" value="<?php echo $cancelID;?>">	<td align="center" bgcolor="#FFFFFF" height="50">
		<input type="submit" name="rec_appv" value="อนุมัติ" onclick="return confirmapp();">
		<input type="submit" name="rec_unappv" value="ไม่อนุมัติ">
		<input type="button" value="ปิด" onClick="window.close();">
	</td></tr>	
<?php
	}
}
?> 
</table>
</form>
</body>
</html>