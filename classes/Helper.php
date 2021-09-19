<?php
namespace Emojify;

use Exception;
use RuntimeException;

class Helper
{
    public const REPLACE_TYPE_BEFORE = 'before';
    public const REPLACE_TYPE_AFTER = 'after';
    public const REPLACE_TYPE_INSTEAD = 'instead';
    public const REPLACE_TYPE_RANDOMLY = 'randomly';

    /**
     * @param $data
     */
    public static function consoleLog($data): void
    {
        echo '<script>';
        echo 'console.log(' . json_encode($data) . ')';
        echo '</script>';
    }

    /**
     * @return string
     */
    public static function getInitText(): string
    {
        return 'This article about my pets.

People ask me how many pets i have?
<ul>
<li>3 cats</li>
<li>2 dogs</li>
<li>1 fish</li>
</ul>

My biggest cat is  5 kg';
    }

    /**
     * @param  string  $content
     * @param  string  $replaceType
     *
     * @return array
     * @throws Exception
     */
    public static function emojifyText(string $content, string $replaceType): array
    {
        $emojiDbJson = dirname(__DIR__).'/db/emoji.json';
        if (!file_exists($emojiDbJson)) {
            throw new RuntimeException(sprintf('File %s is not exist', $emojiDbJson));
        }
        $emojiDb = json_decode(file_get_contents($emojiDbJson), true);

        $emojiKeywords        = [];
        $emojiDpPrettify      = [];
        $foundEmojiCategories = [];

        foreach ($emojiDb as $emojiCategoryName => $emojiCategory) {
            foreach ($emojiCategory as $emoji) {
                if (isset($emoji['active']) && $emoji['active'] === false) {
                    continue;
                }
                $emoji['category']             = $emojiCategoryName;
                $emojiDpPrettify[$emoji['no']] = $emoji;
                foreach ($emoji['keywords'] as $keyword) {
                    if (isset($emojiKeywords[$keyword])) {
                        $emojiKeywords[$keyword][] = $emoji['no'];
                    } else {
                        $emojiKeywords[$keyword] = [$emoji['no']];
                    }
                }
            }
        }

        $fileListEmoji = dirname(__DIR__).'/db/list_marks.json';
        if (!file_exists($fileListEmoji)) {
            throw new RuntimeException(sprintf('File %s is not exist', $fileListEmoji));
        }

        $listEmoji = json_decode(file_get_contents($fileListEmoji), true);

        $maxEmojiNumber = $_POST['max-emoji-number'] ?? 10;
        $addEmojiCount  = 0;

        $contentWithoutTags = strip_tags($content);
        $result             = $content;

        //search each keyword in line.
        foreach ($emojiKeywords as $keyword => $emojiIdList) {
            if ($addEmojiCount >= $maxEmojiNumber) {
                break;
            }
            if (strpos($contentWithoutTags, $keyword) !== false) {
                //if found keyword in line check what symbols around keyword and if it's ok try replace
                $emojiForReplace = $emojiDpPrettify[$emojiIdList[random_int(0, count($emojiIdList) - 1)]]['emoji'];
                $newResult       = $result;
                $matches         = null;

                $hhPattern = '~([\h\'"]'.$keyword.'[\h\'"]+)~ium';
                $pregRes   = preg_match($hhPattern, $newResult, $matches);
                if ($pregRes === 1) {
                    self::consoleLog('$hhPattern');
                    self::consoleLog($matches);
                    $newResult = preg_replace(
                        $hhPattern,
                        self::getReplaceTypeTemplate($replaceType, $emojiForReplace),
                        $newResult,
                        1
                    );
                    $addEmojiCount++;
                    if ($addEmojiCount >= $maxEmojiNumber) {
                        break;
                    }
                } elseif ($pregRes === false) {
                    throw new RuntimeException('Error with preg_match');
                }

                $vhPattern = '~[\v]('.$keyword.'[\h\'"])~ium';
                $pregRes   = preg_match($vhPattern, $newResult, $matches);
                if ($pregRes === 1) {
                    self::consoleLog('$vhPattern');
                    self::consoleLog($matches);
                    $newResult = preg_replace($vhPattern, '$1 '.$emojiForReplace." ", $newResult, 1);
                    $addEmojiCount++;
                    if ($addEmojiCount >= $maxEmojiNumber) {
                        break;
                    }
                } elseif ($pregRes === false) {
                    throw new RuntimeException('Error with preg_match');
                }

                $hvPattern = '~([\h\'"]'.$keyword.')\v~ium';
                $pregRes   = preg_match($hvPattern, $newResult, $matches);
                if ($pregRes === 1) {
                    self::consoleLog('$hvPattern');
                    self::consoleLog($matches);
                    $newResult = preg_replace($hvPattern, '$1 '.$emojiForReplace." ", $newResult, 1);
                    $addEmojiCount++;
                    if ($addEmojiCount >= $maxEmojiNumber) {
                        break;
                    }
                } elseif ($pregRes === false) {
                    throw new RuntimeException('Error with preg_match');
                }

                $vvPattern = '~\v('.$keyword.')\v~ium';
                $pregRes   = preg_match($vvPattern, $newResult, $matches);
                if ($pregRes === 1) {
                    self::consoleLog('$vvPattern');
                    self::consoleLog($matches);
                    $newResult = preg_replace($vvPattern, '$1 '.$emojiForReplace." ", $newResult, 1);
                    $addEmojiCount++;
                    if ($addEmojiCount >= $maxEmojiNumber) {
                        break;
                    }
                } elseif ($pregRes === false) {
                    throw new RuntimeException('Error with preg_match');
                }

                //keyword starts from very beggining and can finish any of space
                $startLineVhPattern = '~^('.$keyword.')[\v\h\'"]~ium';
                $pregRes            = preg_match($startLineVhPattern, $newResult, $matches);
                if ($pregRes === 1) {
                    self::consoleLog('$StartLineVhPattern');
                    self::consoleLog($matches);
                    $newResult = preg_replace($startLineVhPattern, '$1 '.$emojiForReplace." ", $newResult, 1);
                    $addEmojiCount++;
                    if ($addEmojiCount >= $maxEmojiNumber) {
                        break;
                    }
                } elseif ($pregRes === false) {
                    throw new RuntimeException('Error with preg_match');
                }


                //keyword stay at the very end and can start any of space
                $vhEndLinePattern = '~[\v\h\'"]('.$keyword.')$~ium';
                $pregRes          = preg_match($vhEndLinePattern, $newResult, $matches);
                if ($pregRes === 1) {
                    self::consoleLog('$vhEndLinePattern');
                    self::consoleLog($matches);
                    $newResult = preg_replace($vhEndLinePattern, '$0 '.$emojiForReplace." ", $newResult, 1);
                    $addEmojiCount++;
                    if ($addEmojiCount >= $maxEmojiNumber) {
                        break;
                    }
                } elseif ($pregRes === false) {
                    throw new RuntimeException('Error with preg_match');
                }

                //only keyword
                $onlyKeywordPattern = '~^('.$keyword.')$~ium';
                $pregRes          = preg_match($onlyKeywordPattern, $newResult, $matches);
                if ($pregRes === 1) {
                    self::consoleLog('$onlyKeywordPattern');
                    self::consoleLog($matches);
                    $newResult = preg_replace($onlyKeywordPattern, '$1 '.$emojiForReplace, $newResult, 1);
                    $addEmojiCount++;
                    if ($addEmojiCount >= $maxEmojiNumber) {
                        break;
                    }
                } elseif ($pregRes === false) {
                    throw new RuntimeException('Error with preg_match');
                }

                //if replacing is failed skip
                if ($newResult === null || $newResult === $contentWithoutTags) {
                    continue;
                }
                $result = $newResult;
                //save emoji category
                if (!in_array($emojiDpPrettify[$emojiIdList[0]]['category'], $foundEmojiCategories, true)) {
                    $foundEmojiCategories[] = $emojiDpPrettify[$emojiIdList[0]]['category'];
                }
            }
        }

        //getting random list mark
        $currentListMark = $listEmoji['ul'][random_int(0, count($listEmoji['ul']) - 1)];

        //replace html list items with emoji
        $result = preg_replace('~(<ul>[\n\r]*)~ium', '', $result);//"\n","\r"
        $result = preg_replace('~([\n\r]*<\/ul>)~ium', '', $result);//"\n","\r"
        if ($addEmojiCount < $maxEmojiNumber) {
            $result = preg_replace('~<li>(.*?)<\/li>~ium',
                                   $currentListMark . ' $1',
                                   $result,
                                   $maxEmojiNumber - $addEmojiCount);//"\n","\r"
        }

        //replace list items(no html) with emoji
        $matches = null;
//var_dump(preg_match_all('~\r\n[a-zA-Zа-яА-Я]+~ium', $result, $matches));
//var_dump($matches);
//if (preg_match('~\r\n[a-zA-Zа-яА-Я]+~ium', $result, $matches)) {
//}
//$result = preg_replace('~\r\n\r\n\r\n(.*)\r\n~im', '<ul><li>$1</li>', $result);
//$result = preg_replace('~\n~im', '@', $result);
//$result = preg_replace('~\r~im', '%', $result);
        return [
            $result,
            $foundEmojiCategories,
            $maxEmojiNumber,
        ];
    }

    /**
     * @return string[]
     */
    public static function hrefLangList(): array
    {
        return [
            'en' => 'https://www.text-emojify.com/en/',
            'ru' => 'https://www.text-emojify.com/ru/',
        ];
    }

    /**
     * @param string $replaceType
     * @param string $emojiForReplace
     *
     * @return string
     * @throws Exception
     */
    private static function getReplaceTypeTemplate(string $replaceType, string $emojiForReplace): string
    {
        $possibleTypes = [self::REPLACE_TYPE_BEFORE, self::REPLACE_TYPE_INSTEAD, self::REPLACE_TYPE_AFTER];
        if ($replaceType === self::REPLACE_TYPE_RANDOMLY) {
            $replaceType = $possibleTypes[random_int(0, count($possibleTypes) - 1)];
        }

        switch ($replaceType){
            case self::REPLACE_TYPE_BEFORE:
                return ' ' . $emojiForReplace . '$1 ';
            case self::REPLACE_TYPE_INSTEAD:
                return ' ' . $emojiForReplace . ' ';
            case self::REPLACE_TYPE_AFTER:
            default:
                return '$1 ' . $emojiForReplace . ' ';
        }
    }
}