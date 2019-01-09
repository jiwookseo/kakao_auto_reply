<?php header("Content-Type:text/html;charset=utf-8");
$user_key = $_POST["user_key"];
$kyogu = $_POST["u_kyogu"];
$team = $_POST["u_team"];
$group = $_POST["u_group"];
$name = $_POST["u_name"];
$gisu = $_POST["u_gisu"];
$check = $_POST["u_check"];

if ($check==1){
	$kyogu=6;
	$team=1;
	$group=11;
}
else if ($check==2){
	$kyogu=7;
	$team=1;
	$group=11;
}
else {
	if($kyogu==4&&$team==14){
		$team=13;
	}
	$group=$team*10+$group;
}
//$phone = $_POST["u_phone"];

$hostname = "localhost";
$user_id = "root";
$password = "password";
$dbname = "retreat";

$connect = mysql_connect($hostname, $user_id, $password);
mysql_select_db($dbname, $connect);
mysql_query('set names utf8');

//$input11 = $_POST["input11"];


//$sql = "select * from personal where kyogu='$kyogu' and team='$team' and group='$group' and name='$user_name' and gisu='$gisu' and phone='$phone'";
//$sql = "SELECT * FROM  `personal` WHERE `kyogu` =$kyogu AND  `team` =$team and `gisu` = $gisu and `name` = `$name`";
//$sql = "SELECT * FROM `personal` WHERE `kyogu`=$kyogu AND `team`=$team AND `group`=$group AND `name`='$name'";
//&& name='$name'
$query = "SELECT * FROM `personal` WHERE `kyogu`=$kyogu && `group`=$group && `gisu`=$gisu && `name`='$name'";
$result = mysql_query($query);
$row=mysql_fetch_array($result);
$id = $row['id'];
//echo $id;


if(mysql_num_rows($result) == 1){
	$query = "select * from barcode where id='$id'";
	$result = mysql_query($query);
	
	if(mysql_num_rows($result) >= 1) {
		echo "누군가 이미 등록하였습니다. 관리자에게 문의해주세요!";
	}
	
	else {
		$query = "UPDATE barcode SET `id`=$id WHERE `user_key`='$user_key'";
		//echo $sql;
		$result = mysql_query($query);
		
		if($result) {
			
			require_once('barcode/class/BCGFontFile.php');
			require_once('barcode/class/BCGColor.php');
			require_once('barcode/class/BCGDrawing.php');

			require_once('barcode/class/BCGcode128.barcode.php');

			// The arguments are R, G, and B for color.
			$colorFront = new BCGColor(0, 0, 0);
			$colorBack = new BCGColor(255, 255, 255);

			$font = new BCGFontFile('barcode/font/Arial.ttf', 18);

			$code = new BCGcode128();
			$code->setScale(7); // Resolution
			$code->setThickness(30); // Thickness
			$code->setForegroundColor($colorFont); // Color of bars
			$code->setBackgroundColor($colorBack); // Color of spaces
			$code->setFont($font); // Font (or 0)
			$code->parse($id); // Text

			$drawing = new BCGDrawing("barcode/barcode_image/".$id.".png", $colorBack);
			$drawing->setBarcode($code);
			$drawing->draw();
			$drawing->finish(BCGDrawing::IMG_FORMAT_PNG);
			
			echo "정상적으로 등록되었습니다.";
		}
		else{
			echo "오류가 발생했습니다. 다시 실행해주시기 바랍니다.";
		}

	}
}


else if(mysql_num_rows($result) == 0) {
	echo "일치하는 정보가 없습니다. 다시 정확하게 입력해주십시오";
}

else if(mysql_num_rows($result) > 1) {
	echo "해당 정보와 일치하는 정보가 다수입니다. 관리자에게 문의해주세요!";
}




/*
$sql = "UPDATE barcode SET info='$input11' WHERE user_key='$user_key11'";
$result = $conn->query($sql);

//echo $input11;
//echo $user_key11;
//echo $sql;

if($result) {
	echo "완료";
	echo "<script> self.close(); </script> ";
}
else{
	echo "ㅜㅜ";
}
*/
?>