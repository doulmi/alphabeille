<?php

namespace App;


use App\Http\Controllers\WordController;
use Illuminate\Support\Facades\Redirect;

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

    //convert second to 00:00:00.000
    public static function reverseSecond($var)
    {
        $hour = intval($var / 60 / 60);
        $hour = $hour < 10 ? '0' . $hour : $hour;
        $min = intval($var / 60 - $hour * 60 * 60);
        $min = $min < 10 ? '0' . $min : $min;
        $sec = intval($var - $hour * 60 * 60 - $min * 60);
        $sec = $sec < 10 ? '0' . $sec : $sec;
        $micro = explode('.', $var);
        if (isset($micro[1])) {
            $micro = $micro[1];
            $micro = $micro < 10 ? '0' . $micro : $micro;
            $micro = $micro < 100 ? '0' . $micro : $micro;
        } else {
            $micro = '000';
        }
        return "$hour:$min:$sec,$micro";
    }

    /**
     * parseSubtitle的反过程，
     * 将一个subs数组转化为文本srt字幕
     * @param $subs
     */
    public static function composeSubtitle($subs)
    {
        $subStr = '';
        foreach ($subs as $i => $sub) {
            $subStr .= ($i + 1) . "\r\n";
            $subStr .= $sub->startTime . ' -->' . $sub->endTime . "\r\n";
            $subStr .= $sub->fr . "\r\n";
            $subStr .= $sub->zh . "\r\n";
            $subStr .= "\r\n";
        }
        return $subStr;
    }

    public static function extraFr($src)
    {
        $subs = self::parseSubtitle($src);
        $result = '';
        foreach ($subs as $sub) {
            $result .= $sub->fr . "\r\n\r\n";
        }
        return $result;
    }

    /**
     * 分析srt格式字幕，
     * @param $src
     * @return array
     */
    public static function parseSubtitle($src)
    {
        $lines = preg_split('/\n|\r\n?/', $src);
        $state = 1;     //1: SRT_NUMBER; 2: SRT_TIME; 3: SRT_FR; 4: SRT_ZH; 5: SRT_BLANK
        $subs = [];
        $no = 0;
        $subTime = '';
        $subFr = '';
        $subZh = '';
//        $fr = '';
//        $zh = '';
//        $points = '';
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
//                            $points .= self::toSecond($sub->startTime) . ',';
                        }
                        $sub->fr = $subFr;
                        $sub->zh = $subZh;
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
                            $subFr = $subFr . $line . ' ';
                        }
                    }
            }
        }
        return $subs;
//        return [$fr, substr($zh, 0, strlen($zh) - 2), substr($points, 0, strlen($points) - 1)];
    }

    /**
     * 分析srt格式字幕，自动生成断点
     * @param $src
     * @return array
     */
    public static function parsePointLink($src)
    {
        $subs = self::parseSubtitle($src);
        $fr = '';
        $zh = '';
        $points = '';

        foreach ($subs as $i => $sub) {
            if ($i != 0) {
                $fr .= '||';
                $zh .= '||';
                $points .= ',';
            }
            $fr .= $sub->fr;
            $zh .= $sub->zh;
            if (!isset($sub->startTime)) {
                var_dump($i . ':' . $sub->fr . "\n" . $sub->zh);
                echo "<a: href='" . Redirect::getUrlGenerator()->previous() . "'>Back</a>";
                exit;
            } else {
                $points .= self::toSecond($sub->startTime);
            }
        }
        $fr = self::emberedWord($fr);
        $zh = str_replace(',', '，', $zh);
        $zh = str_replace('.', '。', $zh);
        return [$fr, $zh, $points];
    }

    //找到对应时间点的字幕
    private static function findCorrespondSub($fr, $zhSubs)
    {
        $possibleSub = '';
        $max = 10000;
        $find = false;
        $startTime = self::toSecond($fr->startTime);
        $result = '';
        foreach ($zhSubs as $zh) {
            if (trim($zh->startTime) == trim($fr->startTime)) {
                $result = $zh;
                $find = true;
                break;
            } else {
                $limit = abs(self::toSecond($zh->startTime) - $startTime);
                if ($limit < $max) {
                    $possibleSub = $zh;
                    $max = $limit;
                }
            }
        }

        if ($find) {
            return $result->zh;
        } else {
            return $possibleSub->zh;
        }
    }

    /**将中文字幕和法文字幕合并成一个中法文字幕文件*/
    public static function merge($frSrc, $zhSrc)
    {
        $frSubs = self::parseSubtitle($frSrc);
        $zhSubs = self::parseSubtitle($zhSrc);

        $count = count($frSubs);
        $sameLines = $count != count($zhSubs);
        if ($sameLines) {
            for ($i = 0; $i < $count; $i++) {

                $frSubs[$i]->zh = self::findCorrespondSub($frSubs[$i], $zhSubs);
            }
        } else {
            //生成单一字幕
            for ($i = 0; $i < $count; $i++) {
                $frSubs[$i]->zh = $zhSubs[$i]->zh;
            }
        }
        return ($sameLines ? "same lines\n" : "not same lines\n") . self::composeSubtitle($frSubs);
    }

    public static function isChineseChar($char)
    {
        return preg_match('/[\x7f-\xff]/', $char) && !str_contains('ùûüÿ€àâæçÇéèêëïîôœÉÈÊËÀÂÎÏÔÙÛ《》', $char);
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
        $pattern = '/(\s|,|\.|;|:|《|》|\?|!|[|]|\(|\)|{|}|<|>|\/|\')/';
        $len = mb_strlen($src, 'utf8');
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
        $dest = self::filterExpression($dest);
        if ($data[0]) {
            $img = preg_replace('/<\/?span>/', '', $data[0][0]);
            $dest = str_replace($data[0][0], $img, $dest);
        }

        return $dest;
    }

    public static function filterExpression($src)
    {
        $list = [
            "([A|a]ujourd)'<span>hui<\/span>" => "$1'hui",
            "([D|d])'<span>abord<\/span>" => "$1'abord",
            "([D|d])'<span>accord<\/span>" => "$1'accord",
            "([C|c])'<span>est-à-dire<\/span>" => "$1'est-à-dire",
            "<span>([à|a|A])<\/span> <span>bientôt<\/span>" => "$1 bientôt",
            "<span>([à|a|A])<\/span> <span>cause<\/span> <span>de<\/span>" => "$1 cause de",
            "<span>([à|a|A])<\/span> <span>ce<\/span> <span>moment<\/span>" => "$1 ce moment",
            "<span>([à|a|A])<\/span> <span>charge<\/span> <span>de<\/span>" => "$1 charge de",
            "<span>([à|a|A])<\/span> <span>proportion<\/span> <span>de<\/span>" => "$1 proportion de",
            "<span>([à|a|A])<\/span> <span>condition<\/span> <span>([de|que])<\/span>" => "$1 condition $2",
            "<span>([à|a|A])<\/span> <span>peu<\/span> <span>près<\/span>" => "$1 peu près",
            "<span>([à|a|A])<\/span> <span>son<\/span> <span>tour<\/span>" => "$1 son tour",
            "<span>([à|a|A])<\/span> <span>côté<\/span> <span>de<\/span>" => "$1 côté de",
            "<span>([à|a|A])<\/span> <span>côté<\/span>" => "$1 côté",
            "<span>([à|a|A])<\/span> <span>demi<\/span>" => "$1 demi",
            "<span>([à|a|A])<\/span> <span>table<\/span>" => "$1 table",
            "<span>([à|a|A])<\/span> <span>font<\/span>" => "$1 font",
            "<span>([n|N]')<span>importe<\/span> <span>quoi<\/span>" => "$1impofte quoi",
            "<span>([à|a|A])<\/span> <span>point<\/span>" => "$1 point",
            "<span>([à|a|A])<\/span> <span>regret<\/span>" => "$1 regret",
            "<span>([à|a|A])<\/span> <span>part<\/span>" => "$1 part", "<span>([à|a|A])u<\/span> <span>début<\/span>" => "$1 début",
            "<span>([à|a|A])<\/span> l'<span>aise<\/span>" => "$1 l'aise",
            "<span>([à|a|A])<\/span> l'<span>avance<\/span>" => "$1 l'avance",
            "<span>([à|a|A])<\/span> l'<span>avenir<\/span>" => "$1 l'avenir",
            "<span>([à|a|A])<\/span> l'<span>époque<\/span>" => "$1 l'époque",
            "<span>([à|a|A])<\/span> l'<span>envers<\/span>" => "$1 l'envers",
            "<span>([i|I]l)<\/span> s'<span>agit<\/span> <span>de<\/span>" => "$1 s'agit de",
            "<span>([à|a|A])<\/span> <span>la<\/span> <span>fin<\/span>" => "$1 la fin",
            "<span>([à|a|A])<\/span> <span>longueur<\/span> <span>de<\/span>" => "$1 longueur de",
            "<span>([à|a|A])<\/span> <span>mesure<\/span> <span>que<\/span>" => "$1 mesure que",
            "<span>([à|a|A])<\/span> <span>mon<\/span> <span>avis<\/span>" => "$1 mon avis",
            "<span>([à|a|A])<\/span> <span>noter<\/span> <span>que<\/span>" => "$1 noter que",
            "<span>([à|a|A])<\/span> <span>partir<\/span> <span>de<\/span>" => "$1 partir de",
            "<span>([a|A])fin<\/span> <span>([de|que])<\/span>" => "$1fin $2",
            "<span>([a|A])u<\/span> <span>bord<\/span> <span>de<\/span>" => "$1u bord de",
            "<span>([a|A])u<\/span> <span>sein<\/span> <span>de<\/span>" => "$1u sein de",
            "<span>([a|A])u<\/span> <span>cours<\/span> <span>de<\/span>" => "$1u cours de",
            "<span>([a|A])u<\/span> <span>lieu<\/span> <span>de<\/span>" => "$1u cours de",
            "<span>([a|A])u<\/span> <span>point<\/span> <span>de<\/span> <span>vue<\/span>" => "$1u point de vue",
            "<span>([a|A])u<\/span> <span>bout<\/span>" => "$1u bout",
            "<span>([a|A])uprès<\/span> <span>de<\/span>" => "$1uprès de",
            "<span>([a|A])utour<\/span> <span>de<\/span>" => "$1utour de",
            "<span>([a|A])u<\/span> <span>revoir<\/span>" => "$1u revoir",
            "<span>([a|A])u<\/span> <span>moins<\/span>" => "$1u moins",
            "<span>([a|A])u<\/span> <span>hasard<\/span>" => "$1u hasard",
            "<span>([a|A])u<\/span> <span>dedans<\/span>" => "$1u dedans",
            "<span>([d|D])es<\/span> <span>que<\/span>" => "$1es que",
            "<span>([c|C|ç])a<\/span> <span>y<\/span> <span>est<\/span>" => "$1a y est",
            "<span>([a|A])u<\/span> <span>mesure<\/span> <span>et<\/span> <span>à<\/span>  <span>mesure<\/span>" => "$1u fur et à mesure",
        ];
        foreach ($list as $key => $value) {
            $src = preg_replace('/' . $key . '/ui', "<span>" . $value . "</span>", $src);
        }

        //au lieu de
        return $src;
    }


    /**
     * 删除字幕文件中的所有中文
     * @param $src
     * @return string
     */
    private static function removeAllChineseChars($src)
    {
        $lines = preg_split('/\n|\r\n?/', $src);
        $out = '';
        foreach ($lines as $i => $line) {
            $accent = array('À', 'Â', 'Ä', 'È', 'É', 'Ê', 'Ë', 'Î', 'Ï', 'Ô', 'Œ', 'Ù', 'Û', 'Ü', 'Ÿ', 'â', 'ê', 'ô', 'û', 'î', 'ä', 'ë', 'ö', 'ü', 'ï', 'é', 'è', 'ç', 'à', 'œ', 'ù', 'ÿ', 'Ç', 'ç', '«', '»', '€');
            $lineWithoutAccent = str_replace($accent, "", $line);
            if (!preg_match('/[\x7f-\xff]/', $lineWithoutAccent)) {
                if ($i != 0) {
                    $out .= "\n";
                }
                $out .= $line;
            }
        }
        return $out;
    }


    /**
     * 取得字幕中所有还不被字典所包含的词汇
     * @param $src
     * @return array
     */
    public static function getWordsNotInDict($src)
    {
        $src = self::removeAllChineseChars($src);
        $words = str_word_count(str_replace('-', ' ', $src), 1, self::getFrAccent());
        $out = [];
        foreach ($words as $word) {
            if (preg_match('/[a-zA-Z]*\'([a-zA-Z]*)/', $word, $data)) {
                $new = self::strtolowerFr($data[1]);
            } else {
                $new = self::strtolowerFr($word);
            }
            $out[] = $new;
        }

        $words = array_unique($out);
        $out = [];
        $controller = new WordController();
        foreach ($words as $word) {
//            $isInDict = Word::where('word', 'like', $word)->first();
            $result = $controller->getWord($word)[0];
            if (!$result) {
                $out[] = $word;
            }
        }
        return $out;
    }

    private static function getFrAccent()
    {
        return 'ùûüÿ€àâæçéèêëïîôœÉÈÊËÀÂÎÏÇÔÙÛ《》';
    }

    private static function strtolowerFr(&$string)
    {
        //There's a bug when upper '€'
        $string = str_replace('€', '##_128', $string);

        $string = str_replace(
            array('É', 'È', 'Ê', 'Ë', 'À', 'Â', 'Î', 'Ï', 'Ô', 'Ù', 'Û', 'Ç'),
            array('é', 'è', 'ê', 'ë', 'à', 'â', 'î', 'ï', 'ô', 'ù', 'û', 'ç'),
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

    public static function filterSpecialChars($content)
    {
        $content = str_replace('！', '!', $content);
        $content = str_replace('？', '?', $content);
        $content = str_replace('  ', ' ', $content);
        $content = str_replace('‘', '\'', $content);
        $content = str_replace('’', '\'', $content);
        $content = str_replace('“', '\'', $content);
        $content = str_replace('”', '\'', $content);
        $content = str_replace('"', '\'', $content);
        $content = str_replace('。', '.', $content);
        $content = str_replace('，', ',', $content);
        $content = str_replace('…', '...', $content);
        $content = str_replace('!', '.', $content);
        $content = str_replace('\n\n', '\n', $content);
        $content = str_replace(' ', ' ', $content);//特殊的空格,会被看做中文
        $content = str_replace('–', '-', $content);
        $content = str_replace('♪', '', $content);
        $content = str_replace('°C', 'degrés', $content);
        $content = str_replace('°', '', $content);
        return $content;
    }

    public static function isForeignIp($ip)
    {
        $url = "http://api.wipmania.com/" . $ip;
        $response = self::httpGet($url);
        return !($response == 'CN' || $response == 'XX');
    }

    /**
     * 取得对外api的结果
     * @param $url
     * @return mixed
     */
    public static function httpGet($url)
    {
        $curl = curl_init($url);
        curl_setopt($curl, CURLOPT_HEADER, 0); // 过滤HTTP头
        curl_setopt($curl, CURLOPT_TIMEOUT_MS, 20000);    //20秒钟没有取得，则使用上次的值
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);// 显示输出结果
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, true);//SSL证书认证
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 2);//严格认证
        $response = curl_exec($curl);

        $curl_errno = curl_errno($curl);

        curl_close($curl);

        if($curl_errno > 0) {
            return 'CN';
        } else {
            return $response;
        }
    }
}