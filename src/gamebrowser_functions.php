<?php
/**
 * @filesource   gamebrowser_functions.php
 * @created      22.10.2018
 * @author       smiley <smiley@chillerlan.net>
 * @copyright    2018 smiley
 * @license      MIT
 */

namespace chillerlan\GameBrowser;

const GAMEBROWSER_INCLUDES = true;

function funname(string $name):string{
	$col = 'inherit';
	$str = '';
	$funname = '';
	$count = strlen($name);
	for($i=0; $i<$count; $i++){
		if($name[$i]==='^' && isset($name[$i+1]) && $name[$i+1]!='^'){
			if(!empty($str)){
				$str = htmlspecialchars($str, ENT_QUOTES);
				$str = ($str[0]==' ') ? ' '.substr($str, 1) : $str ;
				$funname.= '<span style="color:'.$col.';">'.$str.'</span>';
				$str = '';
			}
			$i++;
			if(ord($name[$i])==56){
				$name[$i] = 8;
			}
			else if(ord($name[$i])==57){
				$name[$i] = 9;
			}
			else if(ord($name[$i]) > 47 && ord($name[$i]) < 58){
				$name[$i] = ord($name[$i])%8;
			}
			else{
				$name[$i] = 7;
			}
			switch($name[$i]){
				case 0: $col = '#000000'; break;
				case 1: $col = '#FF0000'; break;
				case 2: $col = '#00FF00'; break;
				case 3: $col = '#FFFF00'; break;
				case 4: $col = '#0000FF'; break;
				case 5: $col = '#00FFFF'; break;
				case 6: $col = '#FF00FF'; break;
				case 7: $col = '#FFFFFF'; break;
				case 8: $col = '#FF8000'; break;
				case 9: $col = '#777777'; break;
			}
		}
		else{// Was not a control character
			$str.= (substr($name, $i, 1));// add the chr to the name
			if($name[$i]=='^'){
				$i++;
			}
		}
	}
	if(!empty($str)){// draw any leftover name
		$str = htmlspecialchars($str, ENT_QUOTES);
		$str = ($str[0]==' ') ? ' '.substr($str, 1) : $str ;
		$funname.= '<span style="color:'.$col.';">'.$str.'</span>';
	}
	return $funname;
}

function funping(string $ping):string{

	switch(true){
		case $ping < 40 : $col = '#00FF00'; break;
		case $ping < 70 : $col = '#007423'; break;
		case $ping < 120: $col = '#FFFF00'; break;
		case $ping < 190: $col = '#FF8000'; break;
		case $ping < 400: $col = '#FF00FF'; break;
		default:
			$col = '#FF0000';
	}

	return '<span style="color:'.$col.';">'.$ping.'</span>';
}

/*


		switch($info['gametype']){
			case 0 : $t='FFA'; break;
			case 1 : $t='1v1'; break;
			case 2 : $t='SP'; break;
			case 3 : $t='SYC-FFA'; break;
			case 4 : $t='LPS'; break;
			case 5 : $t='TDM'; break;
			case 6 : $t='CTL'; break;
			case 7 : $t='SYC-TP'; break;
			case 8 : $t='BB'; break;
		}
		$info['gametypename'] = $t;



function serverImageError($text1,$text2=null){
	global $config;
	$mappic = imageCreateFromJPEG('img/nopic_small.jpg');
	$black = imagecolorallocate($mappic, 0, 0, 0);
	ImageTTFText($mappic, 8, 0, 3, 10, $black, 'fonts/verdanab.ttf', $text1);
	ImageTTFText($mappic, 9, 0, 3, 20, $black, 'fonts/monofont.ttf', $text2);
	header('Content-type: image/png');
	imagePNG($mappic);
	imagedestroy($mappic);
}

function showServerImage($ip,$port,$pic){
	global $config;
	if(!$s_info=parseServerInfo($ip, $port)){
		serverImageError('no answer from:',$ip.':'.$port);
		return false;
	}
	$style=($config['style']!='default' && isset($config['styles'][$config['style']]['img']['server_images']) && is_array($config['styles'][$config['style']]['img']['server_images']) && count($config['styles'][$config['style']]['img']['server_images'])>0) ? $config['style'] : 'default' ;
	if(!$mappic = @imageCreateFromJPEG('styles/'.$style.'/img/mappics/'.strtolower($s_info['mapname']).'.jpg')){
		$mappic = imageCreateFromJPEG('img/nopic_small.jpg');
	}

	$rightpics = $config['styles'][$style]['img']['server_images'];
	$rightpic = ($pic>=0&&$pic<count($rightpics))?@imageCreateFromPNG('styles/'.$style.'/img/'.$rightpics[intval($pic)]):@imageCreateFromPNG('styles/'.$style.'/img/'.$rightpics[rand(0,(count($rightpics)-1))]);
	$bgpic = imageCreateFromPNG('styles/'.$style.'/img/wopheader02.png');
	$background = imageCreateTrueColor(570, 114);
	$yellow = imagecolorallocate($background, 255, 213, 0);
	$darkyellow = imagecolorallocate($background, 255, 175, 0);
	$black = imagecolorallocate($background, 0, 0, 0);
	$white = imagecolorallocate($background, 255, 255, 255);
	$lightblue = imagecolorallocate($background, 127, 175, 255);
	@imageCopyResampled($background, $bgpic, 0, 0, 0, 0, 175, 114, 20, 160);
	@imageCopyResampled($background, $mappic, 7, 7, 0, 0, 128, 100, 128, 100);
	@imageCopyResampled($background, $rightpic, 160, 0, 0, 0, 410, 114, 580, 160);
	imagefilledrectangle($background, 0, 0, 1, 114, $lightblue);

	$clients = (isset($s_info['clients'])) ? $s_info['clients'] : 0 ;
	$maxclients = (isset($s_info['sv_maxclients'])) ? $s_info['sv_maxclients'] : 0 ;
	$game = (isset($s_info['game'])) ? $s_info['game'] : 'wop' ;

	ImageTTFText($background, 10, 0, 140, 16, $white, 'fonts/Airbus_Spezial.ttf', strip_tags(funname($s_info['hostname'])));
	ImageTTFText($background, 9, 0, 142, 34, $black, 'fonts/verdanab.ttf', 'ip:port');
	ImageTTFText($background, 9, 0, 210, 34, $darkyellow, 'fonts/verdanab.ttf', $ip.':'.$port);
	ImageTTFText($background, 9, 0, 142, 50, $black, 'fonts/verdanab.ttf', 'Players');
	ImageTTFText($background, 9, 0, 210, 50, $darkyellow, 'fonts/verdanab.ttf', $clients.'/'.$maxclients);
	ImageTTFText($background, 9, 0, 142, 66, $black, 'fonts/verdanab.ttf', 'Mode');
	ImageTTFText($background, 9, 0, 210, 66, $darkyellow, 'fonts/verdanab.ttf', $game.' - '.$s_info['gametypename'].' ('.$s_info['gametype'].')');
	ImageTTFText($background, 9, 0, 142, 82, $black, 'fonts/verdanab.ttf', 'Map');
	ImageTTFText($background, 9, 0, 210, 82, $darkyellow, 'fonts/verdanab.ttf', $s_info['mapname']);
	ImageTTFText($background, 10, 0, 230, 105, $yellow, 'fonts/babykruffy.ttf', strtoupper('www.WorldOfPadman.com'));
	header('Content-type: image/png');
	imagePNG($background);
	imagedestroy($rightpic);
	imagedestroy($bgpic);
	imagedestroy($mappic);
	imagedestroy($background);
}
*/

