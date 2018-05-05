<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 1/15/2018
 * Time: 9:54 AM
 */
namespace App\Controller\Component;

use Cake\Controller\Component;

class AppStringComponent extends Component
{
    /**
     * Convert string to UTF-8
     * @param $str
     * @return mixed|string
     */
    public static function _convertUTF8($str) {
        if (!self::_isUTF8($str)) {
            $encoding = 'UTF-8, Shift-JIS, EUC-JP, JIS, SJIS, JIS-ms, eucJP-win, SJIS-win, ISO-2022-JP, ISO-2022-JP-MS';
            $cur_encoding = mb_detect_encoding($str, 'auto');

            if (strpos($encoding,$cur_encoding) === false) {
                $encoding .= ', '.$cur_encoding;
            }

            $temp = mb_convert_encoding((string)$str, 'UTF-8', $encoding);

            return $temp;
        } else {
            return $str;
        }
    }

    /**
     * Function check encoding of tring is UTF-8
     * @param $str
     * @return bool
     */
    public static function _isUTF8($str) {
        return mb_detect_encoding((string)$str, 'UTF-8', true) == 'UTF-8';
    }

    /**
     * Convert string multibyte to one byte
     * @param $str
     * @return string
     */
    public static function _convertKataToZenKaKu($str) {
        $temp = $str;

        if (!self::_isUTF8($temp)) {
            $temp = self::_convertUTF8($temp);
        }

        $temp = mb_convert_kana($temp, "KVa");

        return $temp;
    }

    /**
     * @param $string
     * @return bool
     */
    public static function _isSingleByte($string) {
        $string = (string)$string;

        if (!self::_isUTF8($string)) {
            $string = self::_convertUTF8($string);
        }

        return strlen($string) === mb_strlen($string);
    }

    //Regular Expressions at http://www.localizingjapan.com/blog/2012/01/20/regular-expressions-for-japanese-text/
    //Hiragana
    public static function _isHiragana($string) {
        $temp = (string)$string;

        if (!self::_isUTF8($temp)) {
            $temp = self::_convertUTF8($temp);
        }

        return preg_match('/[\x{3041}-\x{3096}]/u', $temp) > 0;
    }

    //Regular Expressions at http://www.localizingjapan.com/blog/2012/01/20/regular-expressions-for-japanese-text/
    //Katakana (Full Width), Katakana and Punctuation (Half Width)
    public static function _isKatakana($string) {
        $temp = (string)$string;

        if (!self::_isUTF8($temp)) {
            $temp = self::_convertUTF8($temp);
        }

        return preg_match('/[\x{30A0}-\x{30FF}\x{FF61}-\x{FF9F}]/u', $temp) > 0;
    }

    //Regular Expressions at http://www.localizingjapan.com/blog/2012/01/20/regular-expressions-for-japanese-text/
    //Kanji
    public static function _isKanji($string) {
        $temp = (string)$string;

        if (!self::_isUTF8($temp)) {
            $temp = self::_convertUTF8($temp);
        }

        return preg_match('/[\x{3400}-\x{4DB5}\x{4E00}-\x{9FCB}\x{F900}-\x{FA6A}]/u', $temp) > 0;
    }
}