<?php
include("../../config/config.php");


$term = $_GET['term'];

$qry_name=pg_query("select \"CusID\",\"RadioID\",\"fullname\",\"carregis\" from \"VTacReceiveTemp\"
where (\"carregis\" like '%$term%' or \"CusID\" like '%$term%' )
and \"tacXlsRecID\" not in (select \"tacXlsRecID_Old\" from \"tacReceiveTemp_waitedit\" where \"statusApp\" in (2,3))
group by \"CusID\",\"RadioID\",\"fullname\",\"carregis\" order by \"carregis\"");

$numrows = pg_num_rows($qry_name);
while($res_name=pg_fetch_array($qry_name)){
	$CusID=trim($res_name["CusID"]); if(empty($CusID)) $CusID="ไม่พบข้อมูล";
	$RadioID=trim($res_name["RadioID"]); if(empty($RadioID)) $RadioID="ไม่พบรหัสวิทยุ";
    $fullname=trim($res_name["fullname"]);
	$CarRegis=trim($res_name["carregis"]);if(empty($CarRegis)) $CarRegis="ไม่พบทะเบียนรถ";
    
    $name = str_replace("'", "\'"," ".$CusID.""." / ".$fullname.""." / ".$RadioID.""." / ".$CarRegis);
	$display_name = preg_replace("/(" . $term . ")/i", "<b>$1</b>", "$name");

	
	$dt['value'] = $CusID;
    $dt['label'] = $display_name;
    $matches[] = $dt;
}

if($numrows==0){
    $matches[] = "ไม่พบข้อมูล";
}

$matches = array_slice($matches, 0, 100);
print json_encode($matches);
?>
