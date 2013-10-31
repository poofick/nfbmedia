<?php
error_reporting(E_ALL);
ini_set('display_errors', '1');
date_default_timezone_set('Europe/London');

require_once('autoload.php');

Registry::get_ini_file('config/config.ini');

$conferenceId = 21;

$souceFile = $conferenceId.'.flv';
$tempFile = $conferenceId.'_temp.mp4';
$destinationFile = $conferenceId.'.mp4';

$command = array();
$command['encodeSourceFile'] = 'ffmpeg -y -i '.$souceFile.' -vcodec libx264 -s 320x240 -r 25 -acodec libfaac -ar 8k -ab 32k -ac 2 -crf 18 '.$tempFile;
//ffmpeg -y -i 21.flv -vcodec libx264 -crf 18 -acodec libfaac -ar 8k -ab 32k -ac 2 -s 320x240 -r 25 21.mp4
$command['encodeTempFile'] = 'MP4Box -add '.$tempFile.' '.$destinationFile;

$command['removeSourceFile'] = 'rm -f '.$souceFile;
$command['removeTempFile'] = 'rm -f '.$tempFile;

$db = Registry::get('db');
$command['mysqlSuccess'] = 'mysql -h '.$db['development']['host'].' -u '.$db['development']['user'].' -p'.$db['development']['pass'].' -e \'UPDATE `conference` SET `video_converting_status`=1 WHERE `id`='.$conferenceId.'\'';
$command['mysqlFailed'] = 'mysql -h '.$db['development']['host'].' -u '.$db['development']['user'].' -p'.$db['development']['pass'].' -e \'UPDATE `conference` SET `video_converting_status`=2 WHERE `id`='.$conferenceId.'\'';

$command['toBackground'] = ' > /dev/null 2>/dev/null &';

//$output = shell_exec('(( ('.$command['encodeSourceFile'].' && '.$command['removeSourceFile'].') && ('.$command['encodeTempFile'].' && '.$command['removeTempFile'].') && ('.$command['mysqlSuccess'].') ) || ('.$command['mysqlFailed'].') ) '.$command['toBackground']);
$output = shell_exec('(('.$command['encodeSourceFile'].' && ('.$command['encodeTempFile'].' && '.$command['removeTempFile'].') && ('.$command['mysqlSuccess'].') ) || ('.$command['mysqlFailed'].') ) '.$command['toBackground']);
shell_exec($output);

//  ffmpeg -i test1.avi -i test2.avi -vcodec copy -acodec copy -vcodec copy -acodec copy test12.avi -newvideo -newaudio 