<?
/**
* 指定文字数のランダムな文字列を返す
* セッションのkey用に使用
* 
* @access pulibc
* @param 型 $hoge (ここに引数の解説)
* @return 型 (ここに返却値の解説)
*/
function getRandStr($n = 10, $mode = 1){
	$strs = array('A', 'B', 'C', 'D', 'E', 'F', 'G', 
								'H', 'I', 'J', 'K', 'L', 'M', 'N', 
								'O', 'P', 'Q', 'R', 'S', 'T', 'U', 
								'V', 'W', 'X', 'Y', 'Z', 
								'a', 'b', 'c', 'd', 'e', 'f', 'g', 
								'h', 'i', 'j', 'k', 'l', 'm', 'n', 
								'o', 'p', 'q', 'r', 's', 't', 'u', 
								'v', 'w', 'x', 'y', 'z',
								'0','1','2','3','4','5','6','7','8','9');


	if($mode == 2){
		// アルファベットのみ
		$strs = array('A', 'B', 'C', 'D', 'E', 'F', 'G', 
									'H', 'I', 'J', 'K', 'L', 'M', 'N', 
									'O', 'P', 'Q', 'R', 'S', 'T', 'U', 
									'V', 'W', 'X', 'Y', 'Z', 
									'a', 'b', 'c', 'd', 'e', 'f', 'g', 
									'h', 'i', 'j', 'k', 'l', 'm', 'n', 
									'o', 'p', 'q', 'r', 's', 't', 'u', 
									'v', 'w', 'x', 'y', 'z',
									);
	}else if($mode == 3){
		//紛らわしい文字を使わない（O、l,i
		$strs = array('A', 'B', 'C', 'D', 'E', 'F', 'G', 
									'H', 'J', 'K', 'L', 'M', 'N', 
									'P', 'Q', 'R', 'S', 'T', 'U', 
									'V', 'W', 'X', 'Y', 'Z', 
									'a', 'b', 'c', 'd', 'e', 'f', 'g', 
									'h', 'j', 'k', 'm', 'n', 
									'p', 'q', 'r', 's', 't', 'u', 
									'v', 'w', 'x', 'y', 'z',
									'2','3','4','5','6','7','8','9');
	}

	$ret = "";
	for($i=0;$i<$n;$i++){
		$ret .= $strs[mt_rand(0, count($strs)-1)];
	}
	return $ret;
}

function rndStr($n = 10, $mode = 1){
	return getRandStr($n, $mode);
}

/*
		$image = @ImageCreateFromJPEG('mh3_monster07.jpg');
		$out = makeThumbnailImage($image, 300);
*/
function makeThumbnailImage($image, $pxw, $pxh = 0, $enclose = false){
	$width  = ImageSX($image); //元画像横幅（ピクセル）
	$height = ImageSY($image); //元画像縦幅（ピクセル）

	if(intval($pxh) <= 0){
		$pxh = $pxw;
	}

	if(($height < $pxh && $width < $pxw) && !$enclose){
		return $image;
	}

	//--------------------- 画像の縦幅・横幅を計算 Start
	if($pxw/$width<$pxh/$height){
		// 幅の縮小率のほうが大きい
			$rate = $pxw / $width;
			$new_width  = $width * $rate;
			$new_height = $height * $rate;
	}else{
		// 高さの縮小率の報が大きい
			$rate = $pxh / $height;
			$new_height = $height * $rate;
			$new_width  = $width * $rate;
	}
	//--------------------- 画像の縦幅・横幅を計算 End

	if($enclose){
		// 空の画像スペース作成
		$new_image = ImageCreateTrueColor($pxw, $pxh);

		$ofst_x = ($pxw-$new_width <= 0)?0:($pxw-$new_width)/2;
		$ofst_y = ($pxh-$new_height <= 0)?0:($pxh-$new_height)/2;

		// 背景を白で塗りつぶす
		$white = imagecolorallocate($new_image,255,255,255); 
		imagefilltoborder($new_image, 0, 0, $white, $white);

		// サンプリング
		ImageCopyResampled($new_image,$image,$ofst_x,$ofst_y,0,0,$new_width,$new_height,$width,$height);
	}else{
		// 空の画像スペース作成
		$new_image = ImageCreateTrueColor($new_width, $new_height);

		// サンプリング
		ImageCopyResampled($new_image,$image,0,0,0,0,$new_width,$new_height,$width,$height);

	}
	// こっちだときれいに縮小されない。。
	//ImageCopyResized($new_image,$image,0,0,0,0,$new_width,$new_height,$width,$height);

	return $new_image;
}

function rmAllFile($dir) {
	if (!file_exists($dir)) {
		return;
	}
	if(!is_dir($dir)){
		@unlink($dir);
		return;
	}
	$dhandle = opendir($dir);
	if ($dhandle) {
		while (false !== ($fname = readdir($dhandle))) {
			if (is_dir( "{$dir}/{$fname}" )) {
				if (($fname != '.') && ($fname != '..')) {
					rmAllFile("$dir/$fname");
				}
			} else {
				@unlink("{$dir}/{$fname}");
			}
		}
		closedir($dhandle);
	}
	@rmdir($dir);
}

	function wl($dat,$fname = 'log.txt'){
		if(preg_match('/^>/',$fname)){
			$fname=substr($fname,1); $mode='a';
		}else{
			$mode='w';
		}
		$fp=fopen($fname,$mode);
		$ret=fwrite($fp,print_r($dat, true));
		fclose($fp);
		return $ret;
	}

/**
 * mb_convert_encoding()の拡張
 *
 * @param  mixed  $target       arrayかstring
 * @param  string $toEncoding   エンコード先
 * @param  string $fromEncoding エンコード元(default:null)
 * @return mixed  arrayが来たらarrayを、stringが来たらstringを
 */
function mbc($target, $toEncoding, $fromEncoding = null)
{
  if (is_array($target)) {
    foreach ($target as $key => $val) {
      if (is_null($fromEncoding)) {
        $fromEncoding = mb_detect_encoding($val);
      }
      $target[$key] = mbc($val,$toEncoding, $fromEncoding);
    }
  } else {
    if  (is_null($fromEncoding)) {
      $fromEncoding = mb_detect_encoding($target);
    }
    $target = mb_convert_encoding($target, $toEncoding, $fromEncoding);
  }
  return $target;
}

/**
 * 特殊文字変換
 * Enter description here ...
 * @param unknown_type $data : 変換する文字列
 */
function replace($data){
	
	// 特殊文字が入っているか？
	if((strstr($data,'&amp;')) || (strstr($data,'&quot;')) || (strstr($data,'&lt;')) || (strstr($data,'&gt;')) || (strstr($data,'<!--')) || (strstr($data,'-->')) || (strstr($data,'<?')) || (strstr($data,'?>'))){
		$data = str_replace('&amp;','&amp;amp;',$data);
		$data = str_replace('&quot;','&amp;quot;',$data);
		$data = str_replace('&#039;','&amp;#039;',$data);
		$data = str_replace('&lt;','&amp;lt;',$data);
		$data = str_replace('&gt;','&amp;gt;',$data);
		$data = str_replace('<!--','&lt;!--',$data);
		$data = str_replace('-->','--&gt;',$data);
		$data = str_replace('<?','&lt;?',$data);
		$data = str_replace('?>','?&gt;',$data);
	}
	return $data;
}


//ユーザ関数： 文字コードの変換（ array_map 用 ）
function _ame($data, $bfe = 'UTF-8', $afe = 'Shift_JIS') {
	return mb_convert_encoding ($data, $bfe, $afe );
}

function array_mb_encoding($data, $bfe = 'UTF-8', $afe = 'Shift_JIS') {
//配列の中身を変換： ユーザ関数
echo 1111;
echo var_dump($data);
$ret = array_map("_ame", $data, $bfe = 'UTF-8', $afe = 'Shift_JIS');

exit;

return $ret;
}


//ConvertUnit
function cnvUnit($int, $digit){
	if     ($int >= pow(1024, 4)){
		$int_t = round($int / pow(1024, 4), $digit);
		$int_t .= "T";
	}elseif($int >= pow(1024, 3)){
		$int_t = round($int / pow(1024, 3), $digit);
		$int_t .= "G";
	}elseif($int >= pow(1024, 2)){
		$int_t = round($int / pow(1024, 2), $digit);
		$int_t .= "M";
	}elseif($int >= 1024){
		$int_t = round($int / 1024, $digit);
		$int_t .= "K";
	}elseif($int < 1024){
		$int_t = round($int, $digit);
	}
	return $int_t;
}

function dig_array($arr, $key, $life = 5){
	if($life <= 0){ return false; }
	if(is_array($arr)){
		foreach($arr as $k=>$v){
			$ret = null;
			if(is_array($v)){
				$t = dig_array($v, $key, $l--);
				if($t){
					return $t;
				}				
			}else{
				if($k = $key){
					return $v;
				}
			}
		}
		return false;		
	}else{
		return false;
	}
	
}

function cpJpg($from_nm, $to_nm, $size=0){
	$image = ImageCreateFromJPEG($from_nm);
	$rs_image = rsImg($image, $size);
	ImageJPEG($rs_image, $to_nm, 100);
	Imagedestroy($rs_image);
}

function cpPng($from_nm, $to_nm, $size=0){
	$image = ImageCreateFromPNG($from_nm);
	$rs_image = rsImg($image, $size);	
	ImagePNG($rs_image, $to_nm);
	Imagedestroy($rs_image);
}

function cpGif($from_nm, $to_nm, $size=0){
	$image = ImageCreateFromGIF($from_nm);
	$rs_image = rsImg($image, $size);
	ImageGIF($rs_image, $to_nm);
	Imagedestroy($rs_image);
}


function cpImg($from_nm, $to_nm, $size=0,$type){
			
	if($type=='jpg'){ $type='jpeg'; }
	$image_from = 'imagecreatefrom'.$type;
	$image_create = 'image'.$type;
	$image = $image_from($from_nm);
	$rs_image = rsImg($image, $size);
	if($type=='jpeg'){
		$image_create($rs_image, $to_nm, 100);
	}else{
		$image_create($rs_image, $to_nm);
	}
	Imagedestroy($rs_image);
}


/**
* サムネイル画像の作成
*/
function rsImg($image, $pxw, $pxh = 0, $enclose = false){
	$width  = ImageSX($image);
	$height = ImageSY($image);

	if(intval($pxh) <= 0){
		$pxh = $pxw;
	}

	if(($height < $pxh && $width < $pxw) && !$enclose){
		return $image;
	}

	if($pxw/$width<$pxh/$height){
			$rate = $pxw / $width;
			$new_width  = $width * $rate;
			$new_height = $height * $rate;
	}else{
			$rate = $pxh / $height;
			$new_height = $height * $rate;
			$new_width  = $width * $rate;
	}

	if($enclose){
		$new_image = ImageCreateTrueColor($pxw, $pxh);

		$ofst_x = ($pxw-$new_width <= 0)?0:($pxw-$new_width)/2;
		$ofst_y = ($pxh-$new_height <= 0)?0:($pxh-$new_height)/2;

		$white = imagecolorallocate($new_image,255,255,255); 
		imagefilltoborder($new_image, 0, 0, $white, $white);

		ImageCopyResampled($new_image,$image,$ofst_x,$ofst_y,0,0,$new_width,$new_height,$width,$height);
	}else{
		$new_image = ImageCreateTrueColor($new_width, $new_height);

		ImageCopyResampled($new_image,$image,0,0,0,0,$new_width,$new_height,$width,$height);

	}
	return $new_image;
}

/*
function getFileType($file_nm){

	$fp=@fopen($file_nm, "r");
	if(!$fp){ return false; }
	$buffer=fread($fp, 32);
	fclose($fp);

	$type=false;
	if($buffer == ''){
		$er_mes='ファイルが見つかりません、またはファイルが空です。'; return false;
	}else if( 'GIF'  ==  substr( $buffer, 0, 3 ) ){ # GIF
		$type = 'gif';
	}else if( "\x89\x50\x4E\x47"  ==  substr( $buffer, 0, 4 ) ){ # PNG
		$type = 'png';
	}else if( "\xFF\xD8\xFF"  ==  substr( $buffer, 0, 3 ) ){ # JPG
		$type = 'jpg';
#	}else if( "\xD0\xCF\x11"  ==  substr( $buffer, 0, 3 ) ){ # xls
#		$type = 'xls';
	}
	return $type;

}
*/


/**
 * Gets remote client IP
 *
 * @return string Client IP address
 * @access public
 */
	function getClientIP($safe = true) {
		if (!$safe && env('HTTP_X_FORWARDED_FOR') != null) {
			$ipaddr = preg_replace('/(?:,.*)/', '', env('HTTP_X_FORWARDED_FOR'));
		} else {
			if (env('HTTP_CLIENT_IP') != null) {
				$ipaddr = env('HTTP_CLIENT_IP');
			} else {
				$ipaddr = env('REMOTE_ADDR');
			}
		}

		if (env('HTTP_CLIENTADDRESS') != null) {
			$tmpipaddr = env('HTTP_CLIENTADDRESS');

			if (!empty($tmpipaddr)) {
				$ipaddr = preg_replace('/(?:,.*)/', '', $tmpipaddr);
			}
		}
		return trim($ipaddr);
	}



function imgStamp($image,$stamp_file='sukashi.gif',$alpha=20, $resizeble = true){
	// 透かし画像を読み込みます

	$stamp = @imagecreatefromgif($stamp_file);
	/*if(!$stamp){
		$stamp = @imagecreatefrompng($stamp_file);
	}*/

	// 透かしを適用する画像サイズを取得
	$target_x = imagesx($image);
	$target_y = imagesy($image);

	$sx = imagesx($stamp);
	$sy = imagesy($stamp);
	//------------------------------------------------- 透かし画像の縮小 Start
	if($target_x > $sx){
		$new_width  = $sx;
	}
	else{
		$new_width  = $target_x;
	}
	if($target_y > $sy){
		$new_height  = $sy;
	}
	else{
		$new_height  = $target_y;
	}
	
	// 縮小した画像のサイズを決める。
	$rate1       = $new_width / $sx;		//縮小率
	$rate2       = $new_height / $sy;		//縮小率
	
	if($rate1 > $rate2){
		$rate = $rate2;
		$new_width = $rate2 * $sx;
	}
	else{
		$rate = $rate1;
		$new_height = $rate1 * $sy;
	}
	
	// 空の画像を作成する。
	$resize_stamp = ImageCreateTrueColor($new_width, $new_height);
	//$resize_stamp = imagecreate($new_width, $new_height);

	// Add By NSD 20091110
	// 透化色の調査
	for($sxi=0; $sxi<$sx; $sxi++) {
		for($syi=0; $syi<$sy; $syi++) {

			$rgb = imagecolorat($stamp, $sxi, $syi);
			$idx = imagecolorsforindex($stamp, $rgb);
			if($idx["alpha"] !== 0){
				$tp = $idx;
				$ts = $rgb;
				break;
			}
		}
		if(!isset($tp) || $tp!== null)break;
	}


	if(isset($tp) && is_array($tp)){
		// 透化GIF
		
		// パレットの背景を透明にする $tp
		$bg_color = imagecolorallocate($resize_stamp, $tp['red'], $tp['green'], $tp['blue']);
		
		// 画像で使用する色を透過度を指定して作成
		$bgcolor = imagecolorallocatealpha($resize_stamp,
																		$tp["red"],
																		$tp["green"],
																		$tp["blue"],
																		$tp["alpha"]);
		// 塗り潰す
		imagefill($resize_stamp, 0, 0, $bgcolor);
		
		// 透化する色を設定
		imagecolortransparent($resize_stamp, $bgcolor);
	}else{
		// 透化部分無し
	}
	
	// 背景を透明にします
	//	$bg_color = imagecolorallocate($resize_stamp, 0, 0, 0);
	//	imagecolortransparent($resize_stamp, $bg_color);
	
	// 画像を普通にリサイズコピーする場合。
	ImageCopyResized($resize_stamp,$stamp,0,0,0,0,$new_width,$new_height,$sx,$sy);
	// サンプリングしなおす場合。(縮小したときに画像の周りが黒くなっちゃう。。)
	//ImageCopyResampled($resize_stamp,$stamp,0,0,0,0,$new_width,$new_height,$sx,$sy);
	//------------------------------------------------- 透かし画像の縮小 End
	
	// スタンプの余白を設定し、スタンプ画像の幅と高さを取得します
	$marge_right  = ($target_x - $new_width)  / 2;
	$marge_bottom = ($target_y - $new_height) / 2;
	
	// 透かし画像の重ね合わせ
	if($resizeble){
		imagecopymerge($image, $resize_stamp, $target_x - $new_width - $marge_right, $target_y - $new_height - $marge_bottom, 0, 0, imagesx($resize_stamp), imagesy($resize_stamp), $alpha);
	}else{
		imagecopymerge($image, $stamp, 0, 0, 0, 0, imagesx($stamp), imagesy($stamp), $alpha);
	}
	
	// メモリの解放
	imagedestroy($stamp);
	imagedestroy($resize_stamp);
	
	return $image;
}

function sm_esq($s) { 
	$s=str_replace("//","/",$s);
	$s=str_replace("\\\\","\\",$s);
	$s=str_replace("\\","/",$s);
	return $s;

}


/**
 * 期間が入力なしのとき
 * Enter description here ...
 */
function getDateHantoshiNone(){

	$date = array();
	
	$end_year = date('Y');
	$end_month = date('m');	
	$end_day = date('d');
		
	$timestamp = mktime(0, 0, 0, date($end_month), date($end_day), date($end_year));								
	$start_date=date("Y/m/d",strtotime("-6 month" ,strtotime(date('Ymd',$timestamp))));
	$date[] = $start_date;
	$date[] = date('Y/m/d');
	return $date;	

}



function getDateHantoshimae($end_year=null,$end_month=null,$end_day=null){
		
	if(!$end_year){
		$end_year = date('Y');
	}
	if(!$end_month){
		$end_month = date('m');	
	}
	if(!$end_day){
		$end_day = date('d');
	}
	
	
	$timestamp = mktime(0, 0, 0, date($end_month), date($end_day), date($end_year));								
	$start_date=date("Y/m/d",strtotime("-6 month" ,strtotime(date('Ymd',$timestamp))));
	return $start_date;
}



function getDateHantoshigo($start_year=null,$start_month=null,$start_day=null){
		
	if(!$start_year){
		$start_year = date('Y');
	}
	if(!$start_month){
		$start_month = date('m');	
	}
	if(!$start_day){
		$start_day = date('d');
	}
	
	$timestamp = mktime(0, 0, 0, date($start_month), date($start_day), date($start_year));								
	$end_date=date("Y/m/d",strtotime("+6 month" ,strtotime(date('Ymd',$timestamp))));
	return $end_date;
}



/**
 * 日付の差を求める
 * Enter description here ...
 * @param $year1
 * @param $month1
 * @param $day1
 * @param $year2
 * @param $month2
 * @param $day2
 */
function compareDate($year1, $month1, $day1, $year2, $month2, $day2) {
	$dt1 = mktime(0, 0, 0, $month1, $day1, $year1);
 	$dt2 = mktime(0, 0, 0, $month2, $day2, $year2);
	$diff = $dt1 - $dt2;
	$diffDay = $diff / 86400;//1日は86400秒
    return $diffDay;
}


/**
 * 年月日と加算月からnヶ月後、nヶ月前の日付を求める
 * $year 年
 * $month 月
 * $day 日
 * $addMonths 加算月。マイナス指定でnヶ月前も設定可能
 */
function computeMonth($year, $month, $day, $addMonths) {

	if(!$day){
		$day = date('d');
	}
	
	$month += $addMonths;
    $endDay = getMonthEndDay($year, $month);//ここで、前述した月末日を求める関数を使用します
    if($day > $endDay) $day = $endDay;
    $dt = mktime(0, 0, 0, $month, $day, $year);//正規化
    return date("Y-m-d", $dt);
}



/**
 * 年月を指定して月末日を求める関数
 * $year 年
 * $month 月
 */
function getMonthEndDay($year, $month) {
    //mktime関数で日付を0にすると前月の末日を指定したことになります
    //$month + 1 をしていますが、結果13月のような値になっても自動で補正されます
    $dt = mktime(0, 0, 0, $month + 1, 0, $year);
    return date("d", $dt);
}


function au($str){
/*    preg_match_all("/(http:\/\/[\w\/\@\$()!?&%#:;.,~'=*+-]+)/i",strip_tags($str), $array_url);
    for($i=0;$i<count($array_url[1]);++$i){
        $linkhtml="<a href=\"$array_url[1][$i]\">$array_url[1][$i]</a>";
        $str=str_replace($array_url[1][$i],"<a href=\"".$array_url[1][$i]."\">".$array_url[1][$i]."</a>",$str);
    }
    return $str;*/
	$ret = preg_replace_callback("/((http|https):\/\/[\w\/\@\$()!?&%#:;.,~'=*+-]+)/i", 'cau', $str);
	return $ret;

}

function cau($url){
return sprintf('<a href="%s" target="_blank">%s</a>',$url[0], $url[0]);;
/*
	$tag = $val[1];
	$atts = preg_split('#\s#',$val[2]);
	$allow_attr = array('href','color','font-size','target');
	$att = '';
	foreach($atts as $tmp_att){
		list($att_name, $att_val) = split('=',trim($tmp_att));
		if(in_array($att_name, $allow_attr)){
			$att .= sprintf(' %s=%s', $att_name,str_replace('&quot;', '"',$att_val));
		}
	}
	$txt = $val[3];
	$ret = sprintf('<%s%s>%s</%s>',$tag,$att,$txt,$tag);
	return $ret;*/
}


/**
* 
* ● 曜日を取得する
* 
* @param int $w    $datetime->format('w')
* 
* @return string 曜日
*
*/
function get_week_day($w) {
	$week_str_list = array('日', '月', '火', '水', '木', '金', '土');
	return $week_str_list[ $w ];
}

/**
* 
* ● 利用者IDを8桁0詰にする
* 
* @param int $user_id   利用者ID
* 
* @return string 8桁0詰のID
*
*/
function get_user_id_zero($user_id) {
	return sprintf('%08d', $user_id);
}



/*
* ● POSTまたはGETでデータを送信し応答データを取得する
* 
* @param str $method POSTまたはGET
* @param str $url    送信先URL
* @param str $param  パラメータ
* 
* @return str 送信先URLからの応答データ
*
*/
function wbsRequest($method, $url, $params = array())
{

	$data = http_build_query($params);
	   $header = Array("Content-Type: application/x-www-form-urlencoded");
	//   $header = Array('User-Agent: Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1)');
	//$header = Array("Content-Type: image/jpeg");
	$options = array('http' => Array(
	    'method'  => $method,
	    'header'  => implode("\r\n", $header),
	));

	//ステータスをチェック / PHP5専用 get_headers()
	$respons = get_headers($url);
	if(preg_match("/(404|403|500)/",$respons['0'])){
	    return false;
	}

	if($method == 'GET') {
	    $url = ($data != '')?$url.'?'.$data:$url;
	}elseif($method == 'POST') {
	    $options['http']['content'] = $data;
	}
	$content = file_get_contents($url, false, stream_context_create($options));

	return $content;
}




/**
	* 
	* ● 0埋めの「時」リストを取得する
	* 
	* @return array 0～23の「時」リスト
	*
	*/
       function get_time_h_list() {
		$time_list = array();
		
		for($i = 0; $i <= 23; $i++){
			$h             = sprintf('%02d', $i);
			$time_list[$h] = $h;
		}
		
		return $time_list;
       }
	
	
	/**
	* 
	* ● 10間隔、0埋めの「分」リストを取得する
	* 
	* @return array 10間隔の「分」リスト
	*
	*/
       function get_time_i_list() {
		$time_list = array(
				'00' => '00',
				'10' => '10',
				'20' => '20',
				'30' => '30',
				'40' => '40',
				'50' => '50'
		);
		
		return $time_list;
       }
?>