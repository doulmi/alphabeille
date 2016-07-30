<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/6/27
 * Time: 22:39
 */

namespace App;


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

    public static function str2Min($duration) {
        $tts = explode(':', $duration);
        $mm = $tts[0];
        $ss = $tts[1];

        return $mm + $ss / 60;
    }

    public static function parsePointLink($src) {
        $root = HtmlDomParser::str_get_html($src);

        $content = '<table><tbody>';

        $i = 0;

        foreach ($root->childNodes() as $childNode) {
            $tag = $childNode->tag;

            if (($tag == 'p' || $tag == 'blockquote') && !$childNode->find('img')) {
                $content .= '<tr class="align-top"><td class="width40"><a href="#' . $i . '" @click.stop.prevent="seekTo(' . $i . ')" class="seek-btn"></a></td><td>' . $childNode->outertext . '</td></tr>';
            } else {
                $content .= '<tr><td></td><td>' . $childNode->outertext . "</td></tr>";
            }
            $i++;
        }

        $content .= '</tbody></table>';

        return $content;
    }
}