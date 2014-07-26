<?php
/*
// Filename: index.php
// Part of nutterrocker's AuthmeToMYSQL.
// Copyright (C) 2014 nutterrocker. Any rights allowed except change product name.
ระบบ Convert File Auths.db ไปยัง authme.sql
วิธีใช้งาน
1.นำไฟล์ไปไว้ใน appserv xampp หรือ http server ตัวอื่นๆ โดยสร้างโฟเดอร์ไว้ เช่น authmetosql
2.นำไฟล์ auths.db จากใน authme โฟเดอร์ของ plugin authme ไปไว้ในโฟเดอร์เดียวกับไฟล์นี้
3.ทำการรันไฟล์นี้โดยพิมพ์ localhost/ชื่อโฟเดอร์ที่เก็บ/index.php จากนั้นจะได้ไฟล์ authme.sql
*/
/*
Import ไฟล์ auths.db
*/
$sql = file('auths.db');
$output = "CREATE TABLE `authme` (
         `id` INTEGER AUTO_INCREMENT,
         `username` VARCHAR(255) NOT NULL,
         `password` VARCHAR(255) NOT NULL,
         `ip` VARCHAR(40) NOT NULL,
         `lastlogin` BIGINT,
         CONSTRAINT `table_const_prim` PRIMARY KEY (`id`));\n"; 
/* แปลงเป็นไฟล์ sql */
foreach($sql as $value){  //ระบบจะเช็คทีละบรรทัด
   $ex = explode(":",$value);  //ทำการ explode เครื่องหมาย : ของในบรรทัดนีเ
   foreach($ex as $id => $key) 
   {
		if($id != 4 && $id != 0 && $id <= 3) //เนื่องจากค่าที่เราได้จาก auths.db ในแต่ละบรรทัดนั้นมีทั้งหมด 8 ค่า แต่สิ่งสำคัญมีแค่ 4 ค่า ดังนั้นจึงต้องเช็คให้ได้แค่ 4 ค่า
		{
			$output .= ",'$key'";
		}
		else if($id == 0) //ถ้าเกิดวนลูปครั้งแรก ให้ทำการสร้าง Insert into ......................... ไว้
		{
			$output .= "INSERT INTO `authme` (`username`, `password`, `ip`, `lastlogin`) VALUES('$key'";
		}
		else
		{
			$output .= ");\n"; //เมื่อครบทั้ง 4 ค่าให้ออกจากลูป
			goto res;
		}
   }
   res :
}
$open = fopen("authme.sql", 'w'); //เปิดไฟล์
fwrite($open, $output); //สร้างไฟล์
echo "SUCCESS";