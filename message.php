<?php
$data = json_decode(file_get_contents('php://input'),true);

$content = $data["content"];
$user_key = $data["user_key"];

$hostname = "localhost";
$user_id = "root";
$password = "password";
$dbname = "retreat";

$connect = mysql_connect($hostname, $user_id, $password);
mysql_select_db($dbname, $connect);
mysql_query("set names utf8");

$year = date("Y");
$month = date("m");
$day = date("d");

$query = "select * from kakao_index where hp_year = '$year' and hp_month = '$month' and hp_day = '$day'";
$result = mysql_query($query, $connect);
$n = mysql_num_rows($result);
if(!$n)
{
	mysql_query("insert into kakao_index (hp_year, hp_month, hp_day, hp_cnt) VALUES('$year','$month','$day','1')", $connect);
}
else
{
$query = "select * from kakao_index where hp_year = '$year' and hp_month = '$month' and hp_day = '$day'";
$result = mysql_query($query, $connect);
$cnt = mysql_result($result, 0, "hp_cnt");
$cnt = $cnt+1;
$query = "update kakao_index set hp_cnt = '$cnt' where hp_year = '$year' and hp_month = '$month' and hp_day = '$day'";
mysql_query($query, $connect);   // 날짜별 조회 횟수
}


if( $content == "모바일 바코드" ){
	
	$k="barcode";
	$query = "select * from kakao_index where hp_year = '$year' and hp_month = '$month' and hp_day = '$day'";
	$result = mysql_query($query, $connect);
	$cnt = mysql_result($result, 0, $k);
	$cnt = $cnt+1;
	$query = "update kakao_index set $k = '$cnt' where hp_year = '$year' and hp_month = '$month' and hp_day = '$day'";
	mysql_query($query, $connect);   // 버튼 별 조회 횟수

	$query = "SELECT * FROM barcode where user_key = '$user_key'";
	$result = mysql_query($query);
	if(mysql_num_rows($result) == 0) {
		$query = "insert into barcode VALUES ('$user_key', 0)";
		$result=mysql_query($query);

echo <<< EOD
		{
			"message": {
				"text": "링크를 통해 바코드를 등록해주시기 바랍니다. http://siteurl/kakao/register.html?user_key=$user_key"
			},
			"keyboard": {
				"type": "buttons",
			    "buttons" : ["모바일 바코드", "잔액 확인", "실시간영상", "타임테이블", "부흥회 자리표", "수련회 식단", "차량 메뉴", "간식카페 메뉴","분실물", "문의 연락처"]
			}
		}
EOD;
	}
	else {
		$row=mysql_fetch_array($result);
		
		$id = $row['id'];
		
		if($id == "0") {
echo <<< EOD
			{
				"message": {
					"text": " 아직 바코드 등록이 되지 않았습니다. 링크 통해 등록해주시기 바랍니다. http://siteurl/kakao/register.html?user_key=$user_key"
				},
				"keyboard": {
					"type": "buttons",
					"buttons" : ["모바일 바코드", "잔액 확인", "실시간영상", "타임테이블", "부흥회 자리표", "수련회 식단", "차량 메뉴", "간식카페 메뉴","분실물", "문의 연락처"]
				}
			}
EOD;
		}
		else {
			
echo <<< EOD
				{
					"message": {
						"photo": {
						  "url": "http://siteurl/kakao/barcode/barcode_image/$id.png",
						  "width": 500,
						  "height": 300
						}
					},
					"keyboard": {
						"type": "buttons",
						"buttons" : ["모바일 바코드", "잔액 확인", "실시간영상", "타임테이블", "부흥회 자리표", "수련회 식단", "차량 메뉴", "간식카페 메뉴","분실물", "문의 연락처"]
					}
				}
EOD;
		}
		
		
		
	}
	

}

if( $content == "잔액 확인" ){
	
	$k="money";
	$query = "select * from kakao_index where hp_year = '$year' and hp_month = '$month' and hp_day = '$day'";
	$result = mysql_query($query, $connect);
	$cnt = mysql_result($result, 0, $k);
	$cnt = $cnt+1;
	$query = "update kakao_index set $k = '$cnt' where hp_year = '$year' and hp_month = '$month' and hp_day = '$day'";
	mysql_query($query, $connect);   // 버튼 별 조회 횟수
	
	$query = "SELECT * FROM barcode where user_key = '$user_key'";
	$result = mysql_query($query);

	if(mysql_num_rows($result) == 0) {
		$query = "insert into barcode VALUES ('$user_key', 0)";
		$result=mysql_query($query);
echo <<< EOD
		{
			"message": {
				"text": "링크를 통해 바코드를 등록해주시기 바랍니다. http://siteurl/kakao/register.html?user_key=$user_key"
			},
			"keyboard": {
				"type": "buttons",
				"buttons" : ["모바일 바코드", "잔액 확인", "실시간영상", "타임테이블", "부흥회 자리표", "수련회 식단", "차량 메뉴", "간식카페 메뉴","분실물", "문의 연락처"]
			}
		}
EOD;
	}
	else {
		$query = "SELECT * FROM barcode where user_key = '$user_key'";
		$row=mysql_fetch_array($result);
		$id = $row['id'];
		
		if($id == "0") {
echo <<< EOD
			{
				"message": {
					"text": " 아직 바코드 등록이 되지 않았습니다. 링크 통해 등록해주시기 바랍니다. http://siteurl/kakao/register.html?user_key=$user_key"
				},
				"keyboard": {
					"type": "buttons",
					"buttons" : ["모바일 바코드", "잔액 확인", "실시간영상", "타임테이블", "부흥회 자리표", "수련회 식단", "차량 메뉴", "간식카페 메뉴","분실물", "문의 연락처"]
			}
			}
EOD;
}
		else {
		$query = "SELECT * FROM personal where id = $id";
		$result = mysql_query($query);
		$row=mysql_fetch_array($result);
		$money = $row['money'];
		$gisu = $row['gisu'];	
		$name = $row['name'];
echo <<< EOD
	{
		"message": {
			"text": "$name($gisu) 님의 현재 잔액은 $money 원입니다"
		},
		"keyboard": {
			"type": "buttons",
			"buttons" : ["모바일 바코드", "잔액 확인", "실시간영상", "타임테이블", "부흥회 자리표", "수련회 식단", "차량 메뉴", "간식카페 메뉴","분실물", "문의 연락처"]
		}
	}
EOD;
		}
	}
}


if( $content == "타임테이블" ){

	$k="timetable";
	$query = "select * from kakao_index where hp_year = '$year' and hp_month = '$month' and hp_day = '$day'";
	$result = mysql_query($query, $connect);
	$cnt = mysql_result($result, 0, $k);
	$cnt = $cnt+1;
	$query = "update kakao_index set $k = '$cnt' where hp_year = '$year' and hp_month = '$month' and hp_day = '$day'";
	mysql_query($query, $connect);   // 버튼 별 조회 횟수

    echo  <<< EOD
	{    
	     "message": {
			"text": "메뉴를 선택해주세요 "
		},
		"keyboard": {
	    "type": "buttons",
		"buttons" : ["전체 타임테이블","프로그램 설명"]
		}
	}

EOD;
}
	
	if($content == "전체 타임테이블" ) {
	
     echo <<< EOD
	{
		"message": {
			"photo": {
				"url": "http://siteurl/kakao/table_18sum.jpg",
				"width": 480,
				"height": 680
			},
			"message_button": {
				"label": "타임테이블 크게보기",
				"url": "http://siteurl/kakao/table_18sum.jpg"
			}
		},
		"keyboard": {
			"type": "buttons",
			"buttons" : ["모바일 바코드", "잔액 확인", "실시간영상", "타임테이블", "부흥회 자리표", "수련회 식단", "차량 메뉴", "간식카페 메뉴","분실물", "문의 연락처"]
		}
	}
EOD;
}
 if( $content == "프로그램 설명" ) {
 
  echo <<<EOD
  {
       "message": {
	   "photo": {
	       "url": "http://siteurl/kakao/timetable_detail.png",
		   "width":960,
		   "height":720
		   },
		   "message_button": {
		   "label": "크게보기",
		   "url": "http://siteurl/kakao/timetable_detail.png"
		   }
		},
		"keyboard": {
		"type": "buttons",
		"buttons" : "buttons" : ["모바일 바코드", "잔액 확인", "실시간영상", "타임테이블", "부흥회 자리표", "수련회 식단", "차량 메뉴", "간식카페 메뉴","분실물", "문의 연락처"]
		}
	}
EOD;
}

if( $content == "오늘의 식단" ){

	$k="meal";
	$query = "select * from kakao_index where hp_year = '$year' and hp_month = '$month' and hp_day = '$day'";
	$result = mysql_query($query, $connect);
	$cnt = mysql_result($result, 0, $k);
	$cnt = $cnt+1;
	$query = "update kakao_index set $k = '$cnt' where hp_year = '$year' and hp_month = '$month' and hp_day = '$day'";
	mysql_query($query, $connect);   // 버튼 별 조회 횟수
	
	$time=date("Y-m-d H:i:s");
	$query="select * from retreat_info"; //날짜 info 찾아오는거
	$result=mysql_query($query);
	$day0=mysql_result($result,0,"day1");
	$day1=mysql_result($result,0,"day2");
	$day2=mysql_result($result,0,"day3");
	$day3=mysql_result($result,0,"day4");
	$time=date("Y-m-d");
	$lateday2=array(1,2,3,4);
	$lateday3=array("첫째 날 식단","둘째 날 식단","셋째 날 식단", "넷째 날 식단");
	$mealday=4;
	$mealday2="넷째 날 식단";
		for($i=0; $i<3; $i++)
		{
		$day_i=${"day".$i};
			if ($day_i==$time)
			{
				$mealday=$lateday2[$i];
				$mealday2=$lateday3[$i];
			}
		}
	if ($time<$day0){
		$mealday=1;
		$mealday2="첫째 날 식단";
	}
echo <<< EOD
	{
		"message": {
			"photo": {
				"url": "http://siteurl/kakao/meal$mealday.jpg",
				"width": 848,
				"height": 1200
			},
			"message_button": {
				"label": "$mealday2",
				"url": "http://siteurl/kakao/meal$mealday.jpg"
			}
		},
		"keyboard": {
			"type": "buttons",
			"buttons" : ["모바일 바코드", "잔액 확인", "실시간영상", "타임테이블", "오늘의 식단", "차량매뉴얼", "문의 연락처"]
		}
	}
EOD;
}

if( $content == "차량 메뉴" ) {


echo <<< EOD
	{
		"message": {
			"text": "메뉴를 선택해주세요."
		},
		"keyboard": {
			"type": "buttons",
			"buttons" : ["차량매뉴얼", "귀경 차량 신청", "차량 신청 확인"]
		}
	}
EOD;
}


				  
if( $content == "차량매뉴얼" ){

	$k="vehicle";
	$query = "select * from kakao_index where hp_year = '$year' and hp_month = '$month' and hp_day = '$day'";
	$result = mysql_query($query, $connect);
	$cnt = mysql_result($result, 0, $k);
	$cnt = $cnt+1;
	$query = "update kakao_index set $k = '$cnt' where hp_year = '$year' and hp_month = '$month' and hp_day = '$day'";
	mysql_query($query, $connect);   // 버튼 별 조회 횟수

echo <<< EOD
	{
		"message": {
			"text": "어디로 가시나요?(우와)"
		},
		"keyboard": {
			"type": "buttons",
			"buttons" : ["서울로","원주로"]
		}
	}
EOD;
}

if( $content == "서울로" ){
echo <<< EOD
	{
		"message": {
			"photo": {
				"url": "http://siteurl/kakao/vehicle_18summer_s.jpg",
				"width": 1240,
				"height": 1754
			},
			"message_button": {
				"label": "차량매뉴얼 크게보기",
				"url": "http://siteurl/kakao/vehicle_18summer_s.jpg"
			}
		},
		"keyboard": {
			"type": "buttons",
			"buttons" : ["모바일 바코드", "잔액 확인", "실시간영상", "타임테이블", "부흥회 자리표", "수련회 식단", "차량 메뉴", "간식카페 메뉴","분실물", "문의 연락처"]
		}
	}
EOD;
}

if( $content == "원주로" ){
echo <<< EOD
	{
		"message": {
			"photo": {
				"url": "http://siteurl/kakao/vehicle_18summer_w.jpg",
				"width": 1240,
				"height": 1754
			},
			"message_button": {
				"label": "차량매뉴얼 크게보기",
				"url": "http://siteurl/kakao/vehicle_18summer_w.jpg"
			}
		},
		"keyboard": {
			"type": "buttons",
			"buttons" : ["모바일 바코드", "잔액 확인", "실시간영상", "타임테이블", "부흥회 자리표", "수련회 식단", "차량 메뉴", "간식카페 메뉴","분실물", "문의 연락처"]
		}
	}
EOD;
}

if( $content == "귀경 차량 신청" ){

	$k="bus";
	$query = "select * from kakao_index where hp_year = '$year' and hp_month = '$month' and hp_day = '$day'";
	$result = mysql_query($query, $connect);
	$cnt = mysql_result($result, 0, $k);
	$cnt = $cnt+1;
	$query = "update kakao_index set $k = '$cnt' where hp_year = '$year' and hp_month = '$month' and hp_day = '$day'";
	mysql_query($query, $connect);   // 버튼 별 조회 횟수


/* echo <<< EOD
    {
	   "message": {
	       "text": "링크를 통해 차량을 신청해주세요. http://siteurl/kakao/bus_go.html?user_key=$user_key"
			},
			"keyboard": {
				"type": "buttons",
			    "buttons" : ["모바일 바코드", "잔액 확인", "실시간영상", "타임테이블", "부흥회 자리표", "수련회 식단", "차량 메뉴", "간식카페 메뉴","분실물", "문의 연락처"]
			}
	}
EOD;
}   */

    echo <<<EOD
	{ 
	"message": {
	 "text":  "신청이 마감되었습니다.\\n추가 신청은 차량분과장 번호로 연락주시면 감사하겠습니다(찡긋)"
	 },
	 "keyboard": {
	      "type": "buttons",
		  "buttons" : ["모바일 바코드", "잔액 확인", "실시간영상", "타임테이블", "부흥회 자리표", "수련회 식단", "차량 메뉴", "간식카페 메뉴","분실물", "문의 연락처"]
		  }
    }
EOD;
} 

if( $content == "차량 신청 확인") {
    
    $query = "SELECT * FROM barcode where user_key = '$user_key'";
	$result = mysql_query($query);

	if(mysql_num_rows($result) == 0) {
		$query = "insert into barcode VALUES ('$user_key', 0)";
		$result=mysql_query($query);
echo <<< EOD
		{
			"message": {
				"text": "링크를 통해 바코드를 먼저 등록해주시기 바랍니다. http://siteurl/kakao/register.html?user_key=$user_key"
			},
			"keyboard": {
				"type": "buttons",
				"buttons" : ["모바일 바코드", "잔액 확인", "실시간영상", "타임테이블", "부흥회 자리표", "수련회 식단", "차량 메뉴", "간식카페 메뉴","분실물", "문의 연락처"]
			}
		}
EOD;
	}
	else {
		$query = "SELECT * FROM barcode where user_key = '$user_key'";
		$row=mysql_fetch_array($result);
		$id = $row['id'];
		
		if($id == "0") {
echo <<< EOD
			{
				"message": {
					"text": " 아직 바코드 등록이 되지 않았습니다. 링크를 통해 먼저 등록해주시기 바랍니다. http://siteurl/kakao/register.html?user_key=$user_key"
				},
				"keyboard": {
					"type": "buttons",
					"buttons" : ["모바일 바코드", "잔액 확인", "실시간영상", "타임테이블", "부흥회 자리표", "수련회 식단", "차량 메뉴", "간식카페 메뉴","분실물", "문의 연락처"]
			}
		}
EOD;
}
		else {
		$query = "SELECT * FROM personal where id = $id";
		$result = mysql_query($query);
		$row=mysql_fetch_array($result);
		$bus[1] = $row['bus_go1'];
		$bus[2] = $row['bus_go2'];
		$bus[3] = $row['bus_go3'];
		$bus[4] = $row['bus_go4'];
		$bus[5] = $row['bus_go5'];
		
		
		$gisu = $row['gisu'];	
		$name = $row['name'];
		
		for($i=1; $i<=5; $i++) {
		
		   if($bus[$i] == 1) {
		   $bus_t[$i] = "신청" ; 
		   }
		   else {
		   $bus_t[$i] = "미신청";
		   }
		}
		
echo <<< EOD
	{
		"message": {
			"text": "$name($gisu) 님의 현재 귀경 차량신청 현황은 다음과 같습니다.\\n 13일(월) 밤 귀경(22:30): $bus_t[1]\\n 14일(화) 새벽 귀경(05:30): $bus_t[2] \\n 14일(화) 밤 귀경(22:30): $bus_t[3] \\n 15일(수) 밤 귀경(22:30): $bus_t[4] \\n 16일(목) 새벽 귀경(05:30): $bus_t[5]"
		},
		"keyboard": {
			"type": "buttons",
			"buttons" : ["모바일 바코드", "잔액 확인", "실시간영상", "타임테이블", "부흥회 자리표", "수련회 식단", "차량 메뉴", "간식카페 메뉴","분실물", "문의 연락처"]
		}
	}
EOD;
		}
	}
}

if( $content == "실시간영상" ){

	$k="live";
	$query = "select * from kakao_index where hp_year = '$year' and hp_month = '$month' and hp_day = '$day'";
	$result = mysql_query($query, $connect);
	$cnt = mysql_result($result, 0, $k);
	$cnt = $cnt+1;
	$query = "update kakao_index set $k = '$cnt' where hp_year = '$year' and hp_month = '$month' and hp_day = '$day'";
	mysql_query($query, $connect);   // 버튼 별 조회 횟수
	
echo <<< EOD
	{
		"message": {
			"photo": {
				"url": "http://siteurl/mobile/images/1.jpg",
				"width": 1200,
				"height": 297
			},
			"message_button": {
				"label": "실시간영상",
				"url": "https://www.youtube.com/watch?v=ebbLepnNTcc"
			}
		},
		"keyboard": {
			"type": "buttons",
			"buttons" : ["모바일 바코드", "잔액 확인", "실시간영상", "타임테이블", "부흥회 자리표", "수련회 식단", "차량 메뉴", "간식카페 메뉴","분실물", "문의 연락처"]
		}
	}
EOD;
}

if( $content == "간식카페 메뉴"){

    $k="snack";
	$query = "select * from kakao_index where hp_year = '$year' and hp_month = '$month' and hp_day = '$day'";
	$result = mysql_query($query, $connect);
	$cnt = mysql_result($result, 0, $k);
	$cnt = $cnt+1;
	$query = "update kakao_index set $k = '$cnt' where hp_year = '$year' and hp_month = '$month' and hp_day = '$day'";
	mysql_query($query, $connect);   // 버튼 별 조회 횟수
	
	
echo <<< EOD
    {
	        "message": {
			"text": "소시지 1500원\\n초코 시럽 아이스크림 1500원\\n뻥튀기 아이스크림 1500원\\n치즈케이크 2000원\\n아침세트메뉴 2000원\\n아메리카노 1000원\\n블루에이드 음료 1500원\\n아포카토 2000원"			
			},
			"keyboard": {
			    "type": "buttons",
			    "buttons" : ["모바일 바코드", "잔액 확인", "실시간영상", "타임테이블", "부흥회 자리표", "수련회 식단", "차량 메뉴", "간식카페 메뉴","분실물", "문의 연락처"]
		}
    }
EOD;
}




if( $content == "분실물" ) {

    $k="lost";
	$query = "select * from kakao_index where hp_year = '$year' and hp_month = '$month' and hp_day = '$day'";
	$result = mysql_query($query, $connect);
	$cnt = mysql_result($result, 0, $k);
	$cnt = $cnt+1;
	$query = "update kakao_index set $k = '$cnt' where hp_year = '$year' and hp_month = '$month' and hp_day = '$day'";
	mysql_query($query, $connect);   // 버튼 별 조회 횟수
	

echo <<< EOD
    {   
	     
		"message": {
			"photo": {
				"url": "http://siteurl/kakao/lost.jpg",
				"width": 1240,
				"height": 1754
			},
			"message_button": {
				"label": "분실물 크게보기",
				"url": "http://siteurl/kakao/lost.jpg"
			}
		   },
		"keyboard": {
			"type": "buttons",
			"buttons" : ["모바일 바코드", "잔액 확인", "실시간영상", "타임테이블", "부흥회 자리표", "수련회 식단", "차량 메뉴", "간식카페 메뉴","분실물", "문의 연락처"]
		}
	
	}
EOD;
}

 

if( $content == "문의 연락처" ){

	$k="phone";
	$query = "select * from kakao_index where hp_year = '$year' and hp_month = '$month' and hp_day = '$day'";
	$result = mysql_query($query, $connect);
	$cnt = mysql_result($result, 0, $k);
	$cnt = $cnt+1;
	$query = "update kakao_index set $k = '$cnt' where hp_year = '$year' and hp_month = '$month' and hp_day = '$day'";
	mysql_query($query, $connect);   // 버튼 별 조회 횟수


echo <<< EOD
	{
		"message": {
			"text": "연락처"
		},
		"keyboard": {
			"type": "buttons",
			"buttons" : ["모바일 바코드", "잔액 확인", "실시간영상", "타임테이블", "부흥회 자리표", "수련회 식단", "차량매뉴얼", "귀경 차량 신청", "간식카페 메뉴","분실물", "문의 연락처"]
		}
	}
EOD;
}

?>