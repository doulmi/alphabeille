<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/6/27
 * Time: 22:39
 */

namespace App;


use Illuminate\Support\Facades\Redis;
use Sunra\PhpSimple\HtmlDomParser;

class Helper
{
    public static function duration2Hour($duration)
    {
        $tts = explode(':', $duration);
        $mm = $tts[0];
        $ss = $tts[1];

        return intval($mm) * 60 + intval($ss);
    }

    public static function str2Min($duration)
    {
        $tts = explode(':', $duration);
        $mm = $tts[0];
        $ss = $tts[1];

        return $mm + $ss / 60;
    }

    //convert 00:00:00.000 to second
    private static function toSecond($var)
    {
        $times = preg_split('/[:|,]/', $var);
        $second = intval($times[0]) * 60 * 60 + intval($times[1]) * 60 + intval($times[2]);
        $second .= '.' . $times[3];

        return floatval($second);
    }

    /**
     * 分析srt格式字幕，自动生成断点
     * @param $src
     * @return array
     */
    public static function parsePointLink($src)
    {
        $lines = preg_split('/\n|\r\n?/', $src);
        $state = 1;     //1: SRT_NUMBER; 2: SRT_TIME; 3: SRT_FR; 4: SRT_ZH; 5: SRT_BLANK
        $subs = [];
        $no = 0;
        $subTime = '';
        $subFr = '';
        $subZh = '';
        $fr = '';
        $zh = '';
        $points = '';
        foreach ($lines as $line) {
            switch ($state) {
                case 1:
                    if (trim($line) == '') {
                        break;
                    }
                    $no = intval($lines);
                    $state = 2;
                    break;
                case 2:
                    $subTime = $line;
                    $state = 3;
                    break;
                case 3:
                    if (trim($line) == '') {
                        $sub = new \stdClass();
                        $sub->no = $no;
                        if (str_contains($subTime, '-->')) {
                            $times = explode(' -->', $subTime);
                            if (count($times) == 2) {
                                list($sub->startTime, $sub->endTime) = $times;
                            } else {
                                $sub->startTime = $times[0];
                            }
                            $points .= self::toSecond($sub->startTime) . ',';
                        }
                        $sub->fr = $subFr;
                        $sub->zh = $subZh;
                        $fr .= $subFr . '||';
                        $zh .= $subZh . '||';
                        $subFr = '';
                        $subZh = '';
                        $state = 1;
                        $subs[] = $sub;
                    } else {
                        $accent = array('À', 'Â', 'Ä', 'È', 'É', 'Ê', 'Ë', 'Î', 'Ï', 'Ô', 'Œ', 'Ù', 'Û', 'Ü', 'Ÿ', 'â', 'ê', 'ô', 'û', 'î', 'ä', 'ë', 'ö', 'ü', 'ï', 'é', 'è', 'ç', 'à', 'œ', 'ù', 'ÿ', 'Ç', 'ç', '«', '»', '€');
                        $lineWithoutAccent = str_replace($accent, "", $line);
                        if (preg_match('/[\x7f-\xff]/', $lineWithoutAccent)) {
                            // 检测内容是否包含中文
                            $subZh = $subZh . $line;
                        } else {
                            $subFr = $subFr . $line;
                        }
                    }
            }
        }
        $frs = substr($fr, 0, strlen($fr) - 2);
        $frs = self::emberedWord($frs);
        return [$frs, substr($zh, 0, strlen($zh) - 2), substr($points, 0, strlen($points) - 1)];
    }

    public static function isChineseChar($char) {
        return preg_match('/[\x7f-\xff]/', $char) && !str_contains( 'ùûüÿ€àâæçéèêëïîôœÉÈÊËÀÂÎÏÔÙÛ《》', $char);
    }

    /**
     * 将单词用<span>围起来
     * @param $src
     * @return mixed|string
     */
    public static function emberedWord($src)
    {
        $in = false;
        $dest = '';
        $pattern = '/(\s|,|\.|-|;|:|《|》|\?|!|[|]|\(|\)|{|}|<|>|\')/';

        $len = mb_strlen($src,'utf8');
        for ($i = 0; $i < $len; $i++) {
            $char = mb_substr($src, $i, 1, 'utf-8');
            if ($in) { //in word
                if (preg_match($pattern, $char) || self::isChineseChar($char)) {
                    $dest .= '</span>' . $char;
                    $in = false;
                } else {
                    $dest .= $char;
                }
            } else {
                if (preg_match($pattern, $char) || self::isChineseChar($char)) {
                    $dest .= $char;
                } else {    //非标点、
                    $dest .= '<span>' . $char;
                    $in = true;
                }
            }
        }
        $dest = preg_replace('/<span>([A-Za-z]*)<\/span>\'/', '$1\'', $dest);
        $dest = preg_replace('/<span>(p|code|blockquote|\/p|\/code|\/blockquote|»|«|strong|\/strong|br|hr|h\d|\/h\d)<\/span>/', '$1', $dest);
        preg_match_all('/<<span>img.*<\/span>>/', $dest, $data);
        if($data[0]) {
            $img = preg_replace('/<\/?span>/', '', $data[0][0]);
            $dest = str_replace($data[0][0], $img, $dest);
        }
        return $dest;
    }


//    private static function getAllWords($src)
//    {
//        $words = str_word_count(str_replace('-', '', $src), 1, self::getFrAccent());
//        $count = count($words);
//        foreach ($words as $i => &$word) {
//            if (strlen($word) > 1) {
//                if ($word[1] == '\'') {
//                    $word = substr($word, 2);
//                }
//
//                if (!Redis::get('dict:fr:' . $word)) {
//                    $word = self::strtolowerFr($word);
//
//                }
//            }
//            echo $i . "/" . $count . "<br/>";
//        }
//    }

    private static function getFrAccent()
    {
        return 'ùûüÿ€àâæçéèêëïîôœÉÈÊËÀÂÎÏÔÙÛ《》';
    }

    private static function strtolowerFr(&$string)
    {
        //There's a bug when upper '€'
        $string = str_replace('€', '##_128', $string);

        $string = str_replace(
            array('É', 'È', 'Ê', 'Ë', 'À', 'Â', 'Î', 'Ï', 'Ô', 'Ù', 'Û'),
            array('é', 'è', 'ê', 'ë', 'à', 'â', 'î', 'ï', 'ô', 'ù', 'û'),
            $string
        );

        $codeA = ord('A');
        $codeZ = ord('Z');

        $len = strlen($string);

        for ($i = 0; $i < $len; $i++) {
            $code = ord($string[$i]);
            if ($code >= $codeA && $code <= $codeZ) {
                $string[$i] = chr($code + 32);
            }
        }

        $string = str_replace("##_128", "€", $string);
        return $string;
    }
//    public static function parsePointLink($src) {
//        $root = HtmlDomParser::str_get_html($src);
//
//        $content = '<table><tbody>';
//
//        $i = 0;
//
//        foreach ($root->childNodes() as $childNode) {
//            $tag = $childNode->tag;
//
//            if (($tag == 'p' || $tag == 'blockquote') && !$childNode->find('img')) {
//                $content .= '<tr class='align-top'><td class='width40'><a href='#' . $i . '' @click.stop.prevent='seekTo(' . $i . ')' class='seek-btn'></a></td><td>' . $childNode->outertext . '</td></tr>';
//            } else {
//                $content .= '<tr><td></td><td>' . $childNode->outertext . '</td></tr>';
//            }
//            $i++;
//            if ($tag == 'p') {
//
//            }
//        }
//
//        $content .= '</tbody></table>';
//
//        return $content;
//    }
}