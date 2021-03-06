<?php
include("../config/config.php");

$qr_contest = pg_query("select * from \"TAC_contest_types\"");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>ระบบลงทะเบียน :: TAC INFO e-Commerce Web Design Competition 2013</title>
<link href="../libralies/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
<link href="../libralies/bootstrap/css/bootstrap-responsive.min.css" rel="stylesheet" type="text/css" />
<link href="../css/flick/jquery-ui-1.9.0.custom.css" rel="stylesheet" type="text/css" />

<link href="css/main.css" rel="stylesheet" type="text/css" />

<script type="text/javascript" src="../scripts/jquery-1.9.0.js"></script>
<script type="text/javascript" src="../scripts/jquery-ui-1.10.0.custom.js"></script>
<script type="text/javascript" src="../libralies/bootstrap/js/bootstrap.min.js"></script>
<script type="text/javascript">
$(document).ready(function(){
	$('.show_content').fadeIn(1000);
});
</script>
</head>

<body>
<div class="show_content">
    <div align="center">
        <div class="container">
            <div class="top">
                <div class="logo"></div>
                <div class="head_title">
                    <!--<div class="step_logo"></div>-->
                </div>
            </div>
            <div class="middle">
                <!-- step 1 -->
                <div id="step1">
                    <div class="title">
                        <span class="inline_block"><h3>ข้อตกลงในการสมัครเข้าร่วมประกวดโครงการ .:</h3></span>
                        <span class="inline_block"><h3>TAC INFO e-Commerce Web Design Competition 2013 (TEWDC 2013)</h3></span>
                    </div>
                    <div class="content">
                        <div class="rule">
                            <p>
								1.	ข้อตกลงในการสมัครเข้าร่วมประกวดโครงการ (“ข้อตกลง”) เป็นข้อตกลงที่เกิดขึ้นระหว่าง บริษัท แทค อินโฟ จำกัด ผู้จัดการ โครงการ TACINFO e-Commerce Web Design Competition 2013 (TEWDC 2013) (“โครงการ”)  กับ ผู้สมัครที่สนใจเข้าร่วมประกวดโครงการนี้ (“ผู้สมัคร”) โดยผู้สมัครตกลงสมัครเข้าร่วมประกวดโครงการพัฒนาระบบ e-Commerce ในลักษณะ Web Application 
                            </p>
                            <p>
                                2.	ผู้จัดงานกำหนดรางวัลสำหรับผู้สมัคร เข้าร่วมโครงการ โดยมีรายละเอียดรางวัลดังต่อไปนี้
								<ul style="list-style:none;">
                                    <li>•	รางวัลที่ 1 : เงินสดมูลค่า 300,000 บาท จำนวน 1 รางวัล และ *สิทธิในการได้รับเงินทุนสำหรับ Startup Company มูลค่า 1,000,000 บาท และ *สิทธิในการได้รับทุนสำหรับอบรมต่างๆ เป็นจำนวนเงินอีกคนละ 10,000 บาท / คน</li>
                                    <li>•	รางวัลที่ 2 : เงินสดมูลค่า 50,000 บาท จำนวน 1 รางวัล</li>
                                    <li>•	รางวัลชมเชย : เงินสดมูลค่า 25,000 บาท จำนวน 1 รางวัล</li>
                                    <li>•	รางวัลพิเศษ: สำหรับคณะ/ภาควิชา  ของมหาวิทยาลัยที่ทีมผู้สมัครที่ได้ รางวัลที่ 1 สังกัดได้ จำนวน 100,000 บาท</li>
                                </ul>
                            </p>
                            <p>
                                3.	สิทธิในการได้รับเงินทุนสำหรับ Startup Company มูลค่า 1,000,000 บาท และ สิทธิในการได้รับทุนสำหรับอบรมต่างๆ เป็นจำนวนเงินอีกคนละ 10,000 บาท / คน เป็นสิทธิที่ผู้สมัครจะเลือกรับ หรือไม่เลือกรับก็ได้โดยผู้สมัครจะต้องเลือกรับหรือไม่รับ โดยไม่เกี่ยวข้องกับเงินรางวัลปกติที่จะได้รับโดยเงินทุน Startup Company หมายถึง เงินทุนที่บริษัทจะสนับสนุนในการตั้งกิจการบริษัทให้กับผลงานของผู้สมัครร่วมกับผู้จัดงาน โดยผู้สมัครจะได้รับสิทธิรวมถึงด้านการดูงาน การบริการจัดการ และทรัพยากรต่างๆ ร่วมกับบริษัทของผู้จัดงาน
                            </p>
                            <p>
                                4.	ผู้สมัครเข้าประกวดจะต้องมีสถานะกำลังศึกษาอยู่ในมหาวิทยาลัยไทยซึ่งได้รับการรับรองจากกระทรวงศึกษาธิการ ในระดับปริญญาตรี ชั้นปีที่ 3 หรือ ชั้นปีที่ 4 และมีอายุไม่เกิน 23 ปี จนถึงวันที่ประกาศผลการประกวดโครงการ
                            </p>
                            <p>
                                5.	ผู้สมัครเข้าประกวดโครงการจะต้องส่งผลงานเป็นทีม ๆ โดยแต่ละทีมสามารถสมาชิกสูงสุดได้ทีมละไม่เกิน 5 คน และผู้สมัครแต่ละรายบุคคลจะต้องสังกัดเพียงทีมใดทีมหนึ่งเท่านั้น
                            </p>
                            <p>
                                6.	ผู้สมัครเข้าประกวดโครงการแต่ละทีม สามารถส่งผลงานได้เพียง 1 โครงการต่อ 1 ทีมเท่านั้น 
                            </p>
                            <p>
                                7.	ผู้สมัครเข้าประกวดโครงการจะต้องกรอกรายละเอียดการสมัครของตนเองและของทีมให้ครบถ้วนถ้วนเรียบร้อยผ่านทางเว็บไซต์ http://tewdc2013.tacinfo.co.th/register พร้อมกับแนบเอกสารหลักฐานที่รับรองสำเนาถูกต้องดังต่อไปนี้ผ่านทางหน้าเว็บให้ครบถ้วนถูกต้องเรียบร้อยภายในวันที่ 18 กันยายน พ.ศ.2556
                                <ul style="list-style:none;">
                                    <li>7.1.	สำเนาบัตรประชาชน</li>
                                    <li>7.2.	สำเนาทะเบียนบ้าน</li>
                                    <li>7.3.	รูปถ่ายปัจจุบัน ที่ถ่ายมาแล้วไม่เกิน 1 ปี และสามารถระบุตัวตนได้ชัดเจน</li>
                                </ul>
                            </p>
                            <p>
                                8.	ผู้จัดงานจะประกาศผลทีมผู้สมัครที่มีสิทธิเข้าประกวดผ่านทางเว็บไซต์ http://tewdc2013.tacinfo.co.th/news ในวันที่ 20 กันยายน พ.ศ.2556 โดยดุลพินิจของผู้จัดงานถือเป็นสิทธิขาด
                            </p>
                            <p>
                                9.	ทีมผู้สมัครจะต้องเข้ารับฟังคำชี้แจงในการประกวดในวันที่ 22 กันยายน พ.ศ.2556 โดยดูสถานที่ได้จากเว็บไซต์ของโครงการ
                            </p>
                            <p>
								10.	ทีมผู้สมัครจะต้องออกแบบและพัฒนาผลงานที่จะส่งประกวดในโครงการโดยพัฒนาออกมาในรูปแบบ Web Application โดยผู้จัดงานไม่จำกัดภาษา (Programming Language) ที่ใช้ในการพัฒนาโดยเป็นผลงานที่พัฒนาขึ้นเองเท่านั้น ไม่ใช่ผลงานที่พัฒนาต่อยอดจากงาน Open Source แต่สามารถใช้ Library Open Source ได้ 
                            </p>
                            <p>
                                11.	ผลงานที่ส่งเข้าประกวดจะต้องไม่เคยวางขาย หรือแผยแพร่ที่ไหนหรือบนระบบใดๆ รวมถึงไม่เคยนำเข้าประกวดที่ใดๆ
                            </p>
                            <p>
                                12.	ในกรณีที่ผลงานของผู้สมัครมีการใช้ อุปกรณ์เครื่องมือ (Tools) หรือชุดพัฒนาเกม (SDK) หรือไลบรารี่ (Library) หรือมีการใช้งานกราฟฟิก ใดๆที่มีลิขสิทธิ์เป็นของบุคคลอื่น ทีมผู้สมัครจะต้องเป็นผู้ออกค่าใช้จ่ายทั้งหมดในการจัดซื้อ จัดหา รวมถึงเป็นผู้รับผิดชอบในกรณีที่มีข้อโต้แย้งใดๆ
                            </p>
                            <p>
                                13.	ทีมผู้สมัครจะต้องส่งผลงานตัวเว็บที่สมบูรณ์และใช้งานได้จริง พร้อมกับ คำสั่งในการเขียนโปรแกรม (Source Code) ทั้งหมด รวมถึงไลบราลี่ต่างๆ (ถ้ามี) และ อ้างอิงเครื่องในในการพัฒนาที่นำมาใช้ (ถ้ามี) โดยทั้งหมดจะถูกต้องตามกฎหมายลิขสิทธิ์ ให้กับคณะกรรมการผู้จัดงาน ภายในวันที่ 10 มกราคม พ.ศ.2557
                            </p>
                            <p>
                                14.	ทีมผู้สมัครจะต้องนำเสนอผลงานที่ทีมตนเองส่งเข้าประกวด แก่คณะกรรมการใน วันที่ 12 มกราคม พ.ศ.2557
                            </p>
							<p>
								15.	ผู้จัดงานขอสงวนสิทธิในการยกเลิกการประกวด กรณีที่มีผู้สมัครเข้าประกวด น้อยกว่า 4 ทีม โดยหากมีการยกเลิกโครงการผู้จัดงานจะแจ้งให้ทราบในวันที่ประกาศผลผู้สมัครที่มีสิทธิในการเข้าประกวด
							</p>
                            <p>
                                16.	กรรมสิทธิหรือลิขสิทธิ์ใดๆในตัวผลงาน รวมถึงเอกสารต่างๆที่เกี่ยวข้องกับผลงาน ที่ทีมผู้สมัครทุกทีมได้ส่งให้ผู้จัดงานพิจารณาให้ถือว่าเป็นของผู้จัดงานทั้งหมด
                            </p>
                            <p>
                                17.	ผู้จัดงานขอสงวนสิทธิในการตัดสิทธิในการรับพิจารณา หรือสมัครประกวด หรือยกเลิกการมอบรางวัลให้กับทีมใดๆ หรือริบรางวัลคืน หากภายหลังพบว่าทีมหรือผู้สมัครคนหนึ่งคนใดในทีมดังกล่าวมีคุณสมบัติไม่ครบถ้วนตามข้อตกลง หรือให้ข้อมูลใดๆอันเป็นเท็จ หรือกระทำผิดข้อหนึ่งข้อใดในข้อตกลงฉบับนี้ ไม่ว่ากรณีดังกล่าวนั้นผู้จัดงานจะตรวจสอบพบก่อนหรือภายหลังการมอบรางวัลไปแล้วก็ดี
                            </p>
                            <p>
                                18.	ผู้จัดงานขอสงวนสิทธิในการแก้ไขเปลี่ยนแปลง หลักเกณฑ์ และรางวัล โดยที่ไม่ต้องแจ้งให้ผู้สมัครทราบล่วงหน้า
                            </p>
							<p>
								19.	ผู้สมัครได้รับทราบเงื่อนไขทั้งหมดอย่างเข้าใจ และยินยอมที่จะปฏิบัติตามเงื่อนไขและข้อตกลงทั้งหมดในข้อตกลงฉบับนี้ รวมถึงข้อตกลงอื่นๆที่เกี่ยวข้องกับโครงการนี้ที่จะมีขึ้นในอนาคต ทุกประการ
							</p>
                        </div>
                        <div class="except">
                            <label>
                                <input type="checkbox" name="except" id="except" onchange="enable_nextstep();" />
                                <span>ฉันยอมรับข้อตกลงและเงื่อนไขในการสมัครเข้าร่วมโครงการทุกประการ</span>
                            </label>
                        </div>
                        <div class="submit align_right">
							 <input type="button" name="next_btn" id="next_btn" class="btn" value="ถัดไป" onclick="next_step(2);" disabled="disabled" />
                        </div>
                    </div>
                </div>
            </div>
            <!--<div class="bottom">
                <div class="bottom_content"></div>
                <div class="powered"></div>
            </div>-->
        </div>
    </div>
</div>
<script type="text/javascript" src="scripts/validate.js"></script>
</body>
</html>