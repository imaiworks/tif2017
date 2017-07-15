<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
<meta name="viewport" content="width=device-width,initial-scale=1">
<link rel="stylesheet" href="css/normalize.css" />
<link rel="stylesheet" href="css/app.css" />
<title>TIF2017/TimeTableEditor</title>
<script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
<script type="text/javascript" src="jquery.cookie.js"></script>
<script type="text/javascript">
$(document).ready(function(){
	$(".add").click( function(){
		$("."+$("#selectidollist").val()).css("display","block");
		$.cookie($("#selectidollist").val(), $("#selectidollist").val(), { expires: 365 });
	});
	$(".del").click( function(){
		$("."+$(this).attr("value")).css("display","none");
		$.removeCookie($(this).attr("value"));
	});

	$("#timetab").click( function(){
		$("#artisttabsec").css("display","none");
		$("#timetabsec").css("display","block");
		$("#maptabsec").css("display","none");
	});

	$("#artisttab").click( function(){
		$("#artisttabsec").css("display","block");
		$("#timetabsec").css("display","none");
		$("#maptabsec").css("display","none");
	});
	$("#maptab").click( function(){
		$("#artisttabsec").css("display","none");
		$("#timetabsec").css("display","none");
		$("#maptabsec").css("display","block");
	});
	
});
</script>
<h1>TIF2017/TimeTableEditor</h1>
<?php

$r=file_get_contents("http://www.idolfes.com/2017/timetable/time.json");


$tifdata=json_decode($r,true);

//print_r($tifdata);

foreach($tifdata as $key=>$data)
{
//	echo $key."\n";
	foreach($tifdata[$key] as $key2=>$data2)
	{
		$stagelist[$key2]=$key2;
		
		foreach($tifdata[$key][$key2] as $key3=>$data3)
		{
			
//			echo $key3."\n";
			$data3["day"]=$key;
			$data3["stage"]=$key2;
			$w_artistlist[$tifdata[$key][$key2][$key3]["artist"]][]=$data3;
			$artistlistname[$tifdata[$key][$key2][$key3]["artist"]]=$tifdata[$key][$key2][$key3]["artist"];
			
			$artisttimelist[$tifdata[$key][$key2][$key3]["artist"]."--".$key."-".$tifdata[$key][$key2][$key3]["start"]."-".$tifdata[$key][$key2][$key3]["end"]]=$key."\t".$tifdata[$key][$key2][$key3]["start"]."\t".$tifdata[$key][$key2][$key3]["end"]."\t".$key2."\t".$tifdata[$key][$key2][$key3]["artist"];;
			$artisttimelist_artistname[$tifdata[$key][$key2][$key3]["artist"]."--".$key."-".$tifdata[$key][$key2][$key3]["start"]."-".$tifdata[$key][$key2][$key3]["end"]]=$tifdata[$key][$key2][$key3]["artist"];
			
			
			if($data3["detail"]!="null")
			{
// 				echo "<pre>";
// 				print_r($data3);
// 				echo "</pre>";
				$detailarray=array();
				$w_detailarray=explode("<br>",$data3["detail"]);
				foreach($w_detailarray as $key4=>$data4)
				{
					$data4=str_replace(" ほか","",$data4);
					if($data4=="")
						$data4=$tifdata[$key][$key2][$key3]["artist"];
					
					$aname=$data4."(ｲﾍﾞﾝﾄ/ｼﾞｬﾝﾎﾞﾘｰ系)";
					if(strstr($data4,"ゲスト"))
						$aname=$data4;
					$w_artistlist[$aname][]=$data3;
					$artistlistname[$aname]=$aname;
					$artisttimelist[$aname."--".$key."-".$tifdata[$key][$key2][$key3]["start"]."-".$tifdata[$key][$key2][$key3]["end"]]=$key."\t".$tifdata[$key][$key2][$key3]["start"]."\t".$tifdata[$key][$key2][$key3]["end"]."\t".$key2."\t".$aname;;
					$artisttimelist_artistname[$aname."--".$key."-".$tifdata[$key][$key2][$key3]["start"]."-".$tifdata[$key][$key2][$key3]["end"]]=$tifdata[$key][$key2][$key3]["artist"];
					
					$eventname[$aname]=$tifdata[$key][$key2][$key3]["artist"];
				}
			}
			
			
			
		}
			
	}
}
foreach($w_artistlist as $key=>$data)
{
	$w_artistsort=array();
	foreach($w_artistlist[$key] as $key2=>$data2)
	{
		$w_artistsort[$key2]=$w_artistlist[$key][$key2]["day"]."-".$w_artistlist[$key][$key2]["start"]."-".$w_artistlist[$key][$key2]["end"];
	}
	asort($w_artistsort);
	foreach($w_artistsort as $key2=>$data2)
	{
		$artistlist[$key][]=$w_artistlist[$key][$key2];
	}
}

asort($artistlistname);
asort($artisttimelist);
?>
<script type="text/javascript">
$(document).ready(function(){
	$('#timetabsec > div').each(function(index, element){
		
		var a=$.cookie($(this).attr("class"));
		if (typeof a === "undefined") {
		}
		else
		{
			$("."+$(this).attr("class")).css("display","block");
		}
	});
});
</script>
<?php 

echo "<select id='selectidollist' style='margin-bottom: 0px;'>";
foreach($artistlistname as $key=>$data)
{
	echo "<option value='".md5($data)."'>".$data."</option>\n";
}
echo "</select>";
echo "<button class='add' style='padding-top: 8px;padding-bottom: 8px;'>アイドル/イベント表示追加</button>";

echo "<br>\n";
?>
<div style="display:table">
<button class="button radius highlight" id='timetab'   style='float:left;margin-bottom: 0px;padding-left: 10px;padding-right: 10px;margin-right: 2px;background-color:#ffeeff;overflow:hidden;'>時間別</button>
<button class="button radius" id='artisttab' style='float:left;margin-bottom: 0px;padding-left: 10px;padding-right: 10px;overflow:hidden;margin-right: 2px;'>アイドル・イベント別</button>
<button class="button radius warning" id='maptab' style='float:left;margin-bottom: 0px;padding-left: 10px;padding-right: 10px;overflow:hidden;margin-right: 2px;'>地図</button>
</div>
<?php 




		
echo "<div id='timetabsec'  style='background-color:#ffeeff;'>";

// echo "<pre>";
// print_r($artisttimelist);
// echo "</pre>";
foreach($artisttimelist as $key=>$data)
{
	$arraydata=explode("\t",$data);
	
	echo "<div class='".md5($arraydata[4])."' style='display:none;border: solid 2px #ec99f1;'>";
	echo "<div style='float:left;'><h4 style='margin-top: 1px;margin-bottom: 0px;'>".$arraydata[4]." (".count($artistlist[$arraydata[4]]).")</h4></div> <div  style='float:left;' class='del ' value='".md5($arraydata[4])."'><span class='round alert label'>x</span></div><div style='clear:both;'></div>\n";
	if(isset($eventname[$arraydata[4]]))
		echo "<h4 style='margin-top: 1px;margin-bottom: 0px;' >".$eventname[$arraydata[4]]."</h4>";
		
	echo "<div><h5  style='margin-bottom: 0px;' >".$arraydata[0]." ".substr($arraydata[1],0,2).":".substr($arraydata[1],2,2)."〜".substr($arraydata[2],0,2).":".substr($arraydata[2],2,2)." ".$arraydata[3]."</h5>";
	echo "</div>\n";
	echo "</div>\n";
}
echo "</div>\n";

echo "<div id='artisttabsec' style='background-color:#eeffff;display:none;'>";

foreach($artistlistname as $key=>$data)
{
	echo "<div class='".md5($key)."' style='display:none;border: solid 2px #99ecf1;'>";
	echo "<div style='float:left;'><h4 style='margin-top: 1px;margin-bottom: 0px;'>".$key." (".count($artistlist[$key]).")</h4></div> <div style='float:left;' class='del active' value='".md5($key)."'><span class='round alert label'>x</span><dd></div><div style='clear:both;'></div>\n";
	foreach($artistlist[$key] as $key2=>$data2)
	{
		if(isset($eventname[$arraydata[4]]))
			echo "<h4 style='margin-top: 1px;margin-bottom: 0px;' >".$eventname[$arraydata[4]]."</h4>";
			
		echo "<div><h5  style='margin-bottom: 0px;' >".$data2["day"]." ".substr($data2["start"],0,2).":".substr($data2["start"],2,2)."〜".substr($data2["end"],0,2).":".substr($data2["end"],2,2)." ".$data2["stage"]."</h5></div>\n";
	}
	echo "</div>\n";
}
echo "</div>\n";
?>
<div id='maptabsec' style='background-color:#eeffff;display:none;'>
<img src=http://www.idolfes.com/2017/images/map.jpg><br>
<a target='_blank' href='http://www.idolfes.com/2017/'><h5>TIF2017 公式サイト</h5></a>
</div>

<br><br>
<blockquote>
アイドル/イベントを選択してボタンを押すと表示追加していきます<br>
<span class='round alert label'>x</span>を押すと表示から消えます<br>
選択したアイドル/イベントはブラウザに保存されますので、次このサイトに同じスマホ・PCの同じブラウザで訪れたときは前回の情報が反映されます。<br>

アイドル/イベント単位での表示・非表示なので、時間ごとに表示・非表示はできません.ごめんなさい<br>

<hr>
タイムテーブルエディタを使って効率よくたくさんのアイドルを応援していきましょう！<br>
<hr>
<br>
このサイトはTokyoIdolFestivalとは全く無関係です。<br>
このサイトについての内容や問題などはTokyoIdolFestivalには絶対にお問い合わせをしないでください。お願いします。<br>
このサイトのデータや内容に関しては必ずしも正しいものとは限りません。主催者発表の資料を必ず参照してください。このツールの製作者は一切の責任を負いかねます。ご理解の程よろしくお願いいたします。<br>

</blockquote>
<script>
  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
  })(window,document,'script','https://www.google-analytics.com/analytics.js','ga');

  ga('create', 'UA-102511066-1', 'auto');
  ga('send', 'pageview');

</script>





