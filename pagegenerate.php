<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
<meta name="viewport" content="width=device-width,initial-scale=1">
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
	});

	$("#artisttab").click( function(){
		$("#artisttabsec").css("display","block");
		$("#timetabsec").css("display","none");
	});
});
</script>
TIF2017/TimeTableEditor<br>
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

echo "<select id='selectidollist'>";
foreach($artistlistname as $key=>$data)
{
	echo "<option value='".md5($data)."'>".$data."</option>\n";
}
echo "</select>";
echo "<button class='add'>アイドル/イベント表示追加</button>";

echo "<br>\n";
echo "<div style='background-color:#ffeeff;width:50%;float:left;' id='timetab'>";
echo "<div style='padding:15px;border: solid #008080;'>時間別</div>\n";
echo "</div>\n";

echo "<div>";
echo "<div style='background-color:#eeffff;width:50%;float:left;' id='artisttab'>";
echo "<div style='padding:15px;border: solid #008080;'>アイドル別</div>\n";
echo "</div>\n";

echo "<div style='clear:both;'></div>\n";


echo "</div>";


		
echo "<div id='timetabsec'  style='background-color:#ffeeff;'>";

// echo "<pre>";
// print_r($artisttimelist);
// echo "</pre>";
foreach($artisttimelist as $key=>$data)
{
	$arraydata=explode("\t",$data);
	
	echo "<div class='".md5($arraydata[4])."' style='display:none;border: solid #008080;'>";
	echo "<div style='float:left;'>".$arraydata[4]." (".count($artistlist[$arraydata[4]]).")</div> <div  style='float:left;' class='del' value='".md5($arraydata[4])."'>[x]</div><div style='clear:both;'></div>\n";
	echo "<div>".$arraydata[0]." ".substr($arraydata[1],0,2).":".substr($arraydata[1],2,2)."〜".substr($arraydata[2],0,2).":".substr($arraydata[2],2,2)." ".$arraydata[3]."</div>\n";
	echo "</div>\n";
}
echo "</div>\n";

echo "<div id='artisttabsec' style='background-color:#eeffff;display:none;'>";

foreach($artistlistname as $key=>$data)
{
	echo "<div class='".md5($key)."' style='display:none;border: solid #008080;'>";
	echo "<div style='float:left;'>".$key." (".count($artistlist[$key]).")</div> <div style='float:left;' class='del' value='".md5($key)."'>[x]</div><div style='clear:both;'></div>\n";
	foreach($artistlist[$key] as $key2=>$data2)
	{
		echo "<div>".$data2["day"]." ".substr($data2["start"],0,2).":".substr($data2["start"],2,2)."〜".substr($data2["end"],0,2).":".substr($data2["end"],2,2)." ".$data2["stage"]."</div>\n";
	}
	echo "</div>\n";
}
echo "</div>\n";
?>
アイドル/イベントを選択してボタンを押すと表示追加していきます<br>
[x]を押すと表示から消えます<br>
選択したアイドル/イベントはブラウザに保存されますので、次このサイトに同じスマホ・PCの同じブラウザで訪れたときは前回の情報が反映されます。<br>

アイドル/イベント単位での表示・非表示なので、時間ごとに表示・非表示はできません.ごめんなさい<br>
<br>

このタイムテーブルエディタを使って効率よくたくさんのアイドルを応援していきましょう！<br>
<br>
このサイトはTokyoIdolFestivalとは全く無関係です。<br>
このサイトについての内容や問題などはTokyoIdolFestivalには絶対にお問い合わせをしないでください。お願いします。<br>
このサイトのデータや内容に関しては必ずしも正しいものとは限りません。主催者発表の資料を必ず参照してください。このツールの製作者は一切の責任を負いかねます。ご理解の程よろしくお願いいたします。<br>


<script>
  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
  })(window,document,'script','https://www.google-analytics.com/analytics.js','ga');

  ga('create', 'UA-102511066-1', 'auto');
  ga('send', 'pageview');

</script>





