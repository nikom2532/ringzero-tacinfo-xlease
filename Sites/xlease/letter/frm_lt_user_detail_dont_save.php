<?php
include("../config/config.php");
$idno = pg_escape_string($_GET['IDNO']);
$CusID = pg_escape_string($_GET['CusID']);
$CusState = pg_escape_string($_GET['CusState']);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html>
<head>
    <title>ส่งจดหมาย</title>
    <meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />
    <META HTTP-EQUIV="Pragma" CONTENT="no-cache">
    <link type="text/css" rel="stylesheet" href="act.css"></link>
    
    <link type="text/css" href="../jqueryui/css/ui-lightness/jquery-ui-1.8.2.custom.css" rel="stylesheet" />    
    <script type="text/javascript" src="../jqueryui/js/jquery-1.4.2.min.js"></script>
    <script type="text/javascript" src="../jqueryui/js/jquery-ui-1.8.2.custom.min.js"></script>
    
<script type="text/javascript">
function checkdata(){
	if(document.getElementById('type_send').value == "A"){
		if(document.getElementById('regis_back').value == ""){
			alert("กรุณากรอกเลขทะเบียน");
			document.getElementById('regis_back').focus();
			return false;
		}else{
			return true;
		}
	}
}
    var gFiles = 0;
    var summary;
    
    function addFile(){
        var li = document.createElement('div');
        li.setAttribute('id', 'file-' + gFiles);
        li.innerHTML = '<select name="typeletter[]" id="typeletter"><?php
$qry_type=pg_query("select \"auto_id\",\"type_name\" from letter.type_letter order by type_name asc");
while($res_type=pg_fetch_array($qry_type)){ 
    echo "<option value=\"$res_type[auto_id]\" >$res_type[type_name]</option>";
}?></select>&nbsp;<button onClick="removeFile(\'file-' + gFiles + '\')">ลบ</button>';
        document.getElementById('files-root').appendChild(li);
        gFiles++;
    }
    
    function removeFile(aId) {
        var obj = document.getElementById(aId);
        obj.parentNode.removeChild(obj);
    }
$(document).ready(function(){
	$("#type_send").change(function(){
        var src = $('#type_send option:selected').attr('value');
		document.frm.regis_back.value="";
        if ( src == "N" ){
            $("#regis_back").hide();
        }else if( src == "R" ){
            $("#regis_back").hide();
        }else if( src == "A" ){
			$("#regis_back").show();
        }else if( src == "E" ){
			$("#regis_back").show();
        }else{
           $("#regis_back").hide();
        }
    });
});

function add_address(){
	var txt_ads = document.getElementById("txt_ads").value;
	var idno = document.getElementById("idno").value;
	
	$.post("add_contact.php", {txt_ads: txt_ads,idno : idno
  	},
  	 function(data){
		var data = data;
		document.getElementById("contactnote").value = data;
  	 });		
}
function Clear_data() { 
		document.getElementById('coname').value="";
}
function popU(U,N,T) {
    newWindow = window.open(U, N, T);
}
</script>
    
</head>
<body>

<table width="100%" border="0" cellspacing="0" cellpadding="0" align="center">
    <tr>
        <td>

<div style="float:left"><input type="button" value="กลับ" onclick="window.location='frm_lt.php'"></div>
<div style="float:left"><input type="button" value="ส่งโดยบันทึกที่อยู่" onclick="window.location='frm_lt_user_detail.php?id=<?php echo $id;?>&CusID=<?php echo $CusID;?>&CusState=<?php echo $CusState;?>&idno=<?php echo $idno;?>'"></div>
<div style="float:left"><input type="button" value="ส่งโดยไม่บันทึก" onclick="window.location='frm_lt_user_detail_dont_save.php'"disabled></div>
<div style="float:right"><input type="button" value="  Close  " onclick="javascript:window.close();"></div>
<div style="clear:both; padding-bottom: 10px;"></div>

<fieldset><legend><B>ทำรายการส่งจดหมาย</B></legend>

<div class="ui-widget" align="left">

<?php

$qry_name=pg_query("select \"full_name\" from \"VSearchCus\"
WHERE \"CusID\"='$CusID'");
if($res_name=pg_fetch_array($qry_name)){
    list($name)=$res_name;
}else{
    exit;
}
?>

<form name="frm" action="frm_lt_user_detail_dont_save_add.php" method="post" style="margin:0">
<input type="hidden" name="adid" id="adid" value="<?php echo "$id"; ?>">
<input type="hidden" name="idno" id="idno" value="<?php echo "$idno"; ?>">
<input type="hidden" name="CusID" value="<?php echo "$CusID"; ?>">
<table width="100%" cellpadding="5" cellspacing="0" border="0" id="panel">
<tr>
    <td width="20%" align="right"><b>ชื่อ/สกุล :</b></td>
    <td width="80%"><?php echo "<span onclick=\"javascript:popU('../nw/manageCustomer/showdetail2.php?CusID=$CusID','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=1000,height=800')\" style=\"cursor: pointer;\" title=\"ข้อมูลลูกค้า\"><u>$name</u></span> (<span onclick=\"javascript:popU('../post/frm_viewcuspayment.php?idno_names=$idno&type=outstanding','$idno','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=1000,height=800')\" style=\"cursor: pointer;\" title=\"ดูตารางการชำระ\"><u>$idno</u></span>)"; ?></td>
</tr>
<tr>
    <td valign="top" align="right"><b>หมายเหตุ :</b></td>
	<?php
	$qury_cont = pg_query("select \"ContactNote\" from \"Fp_Note\" where \"IDNO\" = '$idno'");
	$num_cont = pg_num_rows($qury_cont);
	
	if($num_cont == 0){
		$contactnote="-----ยังไม่มีรายละเอียดสัญญา-----";
	}else{
		$result_cont = pg_fetch_array($qury_cont);
		$contactnote = $result_cont["ContactNote"];
	}
	?>
    <td><textarea rows="5" cols="80" name="contactnote" id="contactnote" readonly><?php echo $contactnote; ?></textarea></td>
</tr>
<tr>
<td align="right"><b>โอนสิทธิ์เข้าร่วมให้กับ :</b></td>
<td>
	<?php
		$qry_cusco=pg_query("select \"coname\" from letter.\"SendDetail\" where \"IDNO\"='$idno' order by \"auto_id\" DESC limit(1)");
		$res_cusco=pg_fetch_array($qry_cusco);
	?>
	<input type="text" name="coname" id="coname" value="<?php echo $res_cusco["coname"];?>" size="35"><input type="button" name="clear" value="ล้างข้อมูล" onclick="return Clear_data();">
</td>
</tr>
<tr valign="top">
    <td align="right"><b>ที่ส่งจดหมาย :</b></td>
    <td>
       <textarea name="txt_ads" id="txt_ads" rows="5" cols="80"></textarea>
    </td>
</tr>
<tr>
    <td valign="top"></td>
    <td align=""><div style="padding-left:380px;"><input type="button" name="btnsend" id="btnsend" value="**เพิ่มที่ส่งจดหมาย**" onclick="add_address()"></div></td>
</tr>
<tr>
	<td align="right"><b>ประเภทการส่งจดหมาย :</b></td>
	<td>
		<select name="type_send" id="type_send">
			<option value="N" >ส่งธรรมดา</option>
			<option value="R">ลงทะเบียน</option>
			<option value="A">ลงทะเบียนตอบรับ</option>
			<option value="E">EMS</option>
		</select>
		<input type="text" name="regis_back" id="regis_back" size="25" style="display:none;">
	</td>
</tr>
<tr valign="top">
    <td align="right"><b>เลือกรูปแบบจดหมาย :</b></td>
    <td>
<select name="typeletter[]" id="typeletter">
<?php 
$qry_type=pg_query("select \"auto_id\",\"type_name\" from letter.type_letter order by type_name asc");
while($res_type=pg_fetch_array($qry_type)){
    echo "<option value=\"$res_type[auto_id]\" >$res_type[type_name]</option>";
}
?>
</select>
<button type="button" onclick="addFile()">เพิ่มรายการ</button>
<div id="files-root" style="margin:0"></div>
    </td>
</tr>
<tr>
    <td></td>
    <td><input type="submit" value="บันทึก" onclick="return checkdata()"></td>
</tr>
</table>
</form>

</div>

 </fieldset>

        </td>
    </tr>
</table>

</body>
</html>