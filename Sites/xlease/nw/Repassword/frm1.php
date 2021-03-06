<?php 
session_start();
$cur_yr = date("Y");
$start_year = $cur_yr-60;
$end_year = $cur_yr; 

?>


<link type="text/css" href="../../jqueryui/css/ui-lightness/jquery-ui-1.8.2.custom.css" rel="stylesheet" /> 
<script type="text/javascript" src="../../jqueryui/js/jquery-1.4.2.min.js"></script>
<script type="text/javascript" src="../../jqueryui/js/jquery-ui-1.8.2.custom.min.js"></script>
<script type="text/javascript">

$(function(){
	var start_yr = '<?php echo $start_year; ?>'; 
	var end_yr = '<?php echo $end_year; ?>'; 
	var dateBefore=null;
	$("#BDate").datepicker({
		inline:true,
		dateFormat: 'dd-mm-yy',
		showOn: 'button',
		yearRange: start_yr +':'+ end_yr ,
		buttonImage: 'images/calendar.gif',
		buttonImageOnly: true,
		dayNamesMin: ['อา', 'จ', 'อ', 'พ', 'พฤ', 'ศ', 'ส'], 
		monthNamesShort: ['มกราคม','กุมภาพันธ์','มีนาคม','เมษายน','พฤษภาคม','มิถุนายน','กรกฎาคม','สิงหาคม','กันยายน','ตุลาคม','พฤศจิกายน','ธันวาคม'],
		changeMonth: true,
		changeYear: true ,
		beforeShow:function(){
			if($(this).val()!=""){
				var arrayDate=$(this).val().split("-");		
				arrayDate[2]=parseInt(arrayDate[2])-543;
				$(this).val(arrayDate[0]+"-"+arrayDate[1]+"-"+arrayDate[2]);
			}
			setTimeout(function(){
				$.each($(".ui-datepicker-year option"),function(j,k){
					var textYear=parseInt($(".ui-datepicker-year option").eq(j).val())+543;
					$(".ui-datepicker-year option").eq(j).text(textYear);
				});				
			},50);

		},
		onChangeMonthYear: function(){
			setTimeout(function(){
				$.each($(".ui-datepicker-year option"),function(j,k){
					var textYear=parseInt($(".ui-datepicker-year option").eq(j).val())+543;
					$(".ui-datepicker-year option").eq(j).text(textYear);
				});				
			},50);		
		},
		onClose:function(){
			if($(this).val()!="" && $(this).val()==dateBefore){			
				var arrayDate=dateBefore.split("-");
				arrayDate[2]=parseInt(arrayDate[2])+543;
				$(this).val(arrayDate[0]+"-"+arrayDate[1]+"-"+arrayDate[2]);	
			}		
		},
		onSelect: function(dateText, inst){ 
			dateBefore=$(this).val();
			var arrayDate=dateText.split("-");
			arrayDate[2]=parseInt(arrayDate[2])+543;
			$(this).val(arrayDate[0]+"-"+arrayDate[1]+"-"+arrayDate[2]);
		}
		
		

	});
	
	// ยกเลิกการแปลงวันทีี่อัตโนมัติ
	/*$('#BDate').change(function(){
    		$('#BDate').datepicker('setDate', $(this).val());
		});
	*/
});

	
function check_num(e)
{
    var key;
    if(window.event){
        key = window.event.keyCode; // IE
if (key > 57)
      window.event.returnValue = false;
    }else{
        key = e.which; // Firefox       
if (key > 57)
      key = e.preventDefault();
  }
} 
function check_Input_Date(data_In){
	
	var str = data_In;  
	var Date_split = str.split("-");
	var chk = 0; 
	if(Date_split.length!= 3){
		chk++;
	}else{
	
		var dtYear = parseInt(Date_split[2]);   
		var dtMonth = parseInt(Date_split[1]); 
		var dtDay = parseInt(Date_split[0]); 
		
		if(isNaN(dtYear) == true){
			chk++;
		}
		if(isNaN(dtMonth) == true){
			chk++;
		}
		if(isNaN(dtDay) == true){
			chk++;
		}
			
		if (dtMonth < 1 || dtMonth > 12){
			chk++;
		}else if (dtDay < 1 || dtDay> 31) {
			chk++;
		}else if ((dtMonth==4 || dtMonth==6 || dtMonth==9 || dtMonth==11) && dtDay ==31) {
			chk++;
		} else if (dtMonth == 2) {
			var isleap = (dtYear % 4 == 0 && (dtYear % 100 != 0 || dtYear % 400 == 0));
			if (dtDay> 29 || (dtDay ==29 && !isleap)) 
            chk++;
		}
	}
    
     
	if(chk>0){
		return 0;
	}else{
		return 1;
	}
	
	
	
}// End Of function check_Input_Date(data_In)
	
function checkList()
{
		
	if(document.getElementById("username").value=="")
	{
		alert('กรุณากรอก Username');
		return false;
	}
	if(document.getElementById("iden").value=="")
	{
		alert('กรุณากรอก รหัสประจำตัวประชาชน 13 หลัก');
		return false;
	}
	if(document.getElementById("BDate").value=="")
	{
		alert('กรุณากรอก วัน/เดือน/ปี เกิด ');
		return false;
	}
	else
	{ 
		var chk_date = document.getElementById("BDate").value;	
		var chk_ret = check_Input_Date(chk_date);	
		
		if(chk_ret){
			return true; 
		}else{
			alert("กรุณากรอก วันที่ ให้ถูกต้อง");
			return false;
		}	
    return true;
    }
}


</script>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />
    <title>Unlock username</title>

<style type="text/css">
BODY{
    font-family: tahoma;
    font-size: 14px;
    color: #585858;
    background-color: #C0C0C0;
    margin: 0 auto;
    padding-top: 20px;
}
H1{
    font-size: 16px;
    color: #585858;
    font-weight: bold;
    padding: 0px;
    margin: 0px;
}
H2{
    font-size: 20px;
    color: #888800;
    font-weight: bold;
    padding: 0px;
    margin: 0px;
}
INPUT {
    font-family: tahoma;
    font-size: 14px;
    font-weight: normal;
    /*color: #585858;
    background-color: #E0E0E0;*/
}
HR {
    border: 0;
    color: #ACACAC;
    background-color: #ACACAC;
    height: 1px;
}

.roundedcornr_box {
   background: #ffffff;
   width: 500px;
   margin: auto;
}
.roundedcornr_top div {
   background: url(../../img/roundedcornr_tl.png) no-repeat top left;
}
.roundedcornr_top {
   background: url(../../img/roundedcornr_tr.png) no-repeat top right;
}
.roundedcornr_bottom div {
   background: url(../../img/roundedcornr_bl.png) no-repeat bottom left;
}
.roundedcornr_bottom {
   background: url(../../img/roundedcornr_br.png) no-repeat bottom right;
}

.roundedcornr_top div, .roundedcornr_top, 
.roundedcornr_bottom div, .roundedcornr_bottom {
   width: 100%;
   height: 15px;
   font-size: 1px;
}
.roundedcornr_content {
    margin: 0 15px;
}

</style>

</head>
<body onload="document.form1.username.focus();">

<div class="roundedcornr_box">
   <div class="roundedcornr_top"><div></div></div>
      <div class="roundedcornr_content">

<h2>Unlock username</h2>
<hr/>
<div>
<FORM method="post" action="genpass.php" style="margin:0px" name="form1">
<TABLE width="400" cellspacing="0" cellpadding="3" border="0" align="center">
<TR>
    <TD align="right"><font size="2"><B>Username</B></font></TD>
    <TD><INPUT TYPE="text" autocomplete="off" NAME="username" id="username"></TD>
	<td><font color="gray" size="2">ex.Thaiace.cap</font></td>
</TR>

<TR>
    <TD align="right"><font size="2"><B>เลขประจำตัวประชาชน</B></font></TD>
    <TD><INPUT TYPE="text" autocomplete="off" NAME="iden" id="iden"></TD>
	<td><font color="gray" size="2">ex.xxxxxxxxxxxxx</font></td>
</TR>
<TR>
<tr>
<td align="right"><font size="2"><B>วัน/เดือน/ปี เกิด</B></font></td>
<td>
<input type="text" size="12" style="text-align:center;" id="BDate" name="BDate" value="" onchange="chkdate()"/>&nbsp </td>
	<td><font color="gray" size="2"> Ex. 24-09-2520(พ.ศ.)</font></td>
</tr>
</select>
    </TD>
</TR>
<tr>
<td></td>
<td colspan="2" align="left"><input type="submit" value="ขอรหัสผ่าน" style="width:100px; height:30px;" onClick="return checkList();">

<input type="button" value=" ปิด "  style="width:100px; height:30px;"  onclick="parent.location.href='../../index.php'"></td>
<td></td>
</tr>
</TABLE>
</FORM>
</div>

      </div>
   <div class="roundedcornr_bottom"><div></div></div>
</div>

</body>
</html>