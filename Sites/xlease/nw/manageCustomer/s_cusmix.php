<?php
include("../../config/config.php");
$term =  pg_escape_string($_GET['term']);

$term=strtr($term, "-", " "); //แปลงค่าที่คีย์ - ให้เป็นช่องว่าง
$term=ereg_replace('[[:space:]]+', '', trim($term)); //ตัดช่องว่างออก

$qry_name=pg_query("select 1 as type ,a.\"full_name\",a.\"CusID\",b.\"N_IDCARD\"  from \"VSearchCus\" a 
left join \"Fn\" b on a.\"CusID\"=b.\"CusID\" 
where replace(replace(a.\"full_name\",' ',''),'-','') like '%$term%' or replace(replace(b.\"N_IDCARD\",' ',''),'-','') like '%$term%' OR a.\"CusID\" like '%$term%'
union 
select 2 as type ,a.\"full_name\",a.\"CusID\",b.\"N_IDCARD\" from \"VSearchCusTemp\" a 
left join \"Customer_Temp\" b on a.\"CusID\"=b.\"CusID\" 
where 
((replace(replace(a.\"full_name\",' ',''),'-','') like '%$term%' or replace(replace(b.\"N_IDCARD\",' ',''),'-','') like '%$term%') 
OR a.\"CusID\" like '%$term%')
and substr(a.\"CusID\",1,2)='CT' and \"statusapp\" ='0'");

$numrows = pg_num_rows($qry_name);

while($res_name=pg_fetch_array($qry_name)){
    $name=$res_name["full_name"];
	$CusID = trim($res_name["CusID"]);
	$N_IDCARD = trim($res_name["N_IDCARD"]);
	if(substr($CusID,0,2)=='CT'){
		$status="<font color=red>(ไม่อนุมัติ)</font>";
	}else{
		$status="";
	}
	
    $dt['value'] = $CusID."#".$name."#".$N_IDCARD;
    $dt['label'] = "{$CusID}, {$name}, {$N_IDCARD} {$status}";
    $matches[] = $dt;
}

if($numrows==0){
    $matches[] = "ไม่พบข้อมูล";
}

$matches = array_slice($matches, 0, 100);
print json_encode($matches);
?>
