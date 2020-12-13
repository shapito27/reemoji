<?php

/**
 * @todo
 * 1. Maybe I should remove all html tags first. And then search all keywords. To avoid problems with <>/
 * 2. If we have several list make them different
 * 3. Suggest emoji by emoji categoriy
 * 4. Add editor
 */
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

$fakeText = getInitText();

$content        = $_POST['content'] ?? $fakeText;
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
            console_log('$hhPattern');
            console_log($matches);
            $newResult = preg_replace($hhPattern, '$1 '.$emojiForReplace." ", $newResult, 1);
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
            console_log('$vhPattern');
            console_log($matches);
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
            console_log('$hvPattern');
            console_log($matches);
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
            console_log('$vvPattern');
            console_log($matches);
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
            console_log('$StartLineVhPattern');
            console_log($matches);
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
            console_log('$vhEndLinePattern');
            console_log($matches);
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
            console_log('$onlyKeywordPattern');
            console_log($matches);
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


//console_log($result);

?>
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Reformat text with emoji - ReEmoji</title>
        <link rel="canonical" href="https://text-emojify.com" />
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
        <meta name="description" content="Add emoji to text and make it more attractive and readable">
        <meta name="keywords" content="emoji, text, content, post with emoji, content with emoji">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bulma@0.9.1/css/bulma.min.css">
        <link rel="apple-touch-icon" sizes="180x180" href="/apple-touch-icon.png">
        <link rel="icon" type="image/png" sizes="32x32" href="/favicon-32x32.png">
        <link rel="icon" type="image/png" sizes="16x16" href="/favicon-16x16.png">
        <link rel="manifest" href="/site.webmanifest">
    </head>
    <body>
    <section class="section">
        <div class="container">
            <h1 class="title">
                ❤️ Make your text more attractive
            </h1>
            <div class="content is-medium">
                <p>What can it do:</p>
                <ul>
                    <li>Add leading emoji before each item of list</li>
                    <li>Add emoji after words which has related emoji 💡</li>
                </ul>
                <!--    <link rel="stylesheet" href="public/style.css">-->
                <!--    <script src="public/me.js"></script>-->
                <!--    <script src="public/editor.js"></script>-->
                <form action="/" method="post" enctype="application/x-www-form-urlencoded">
                    <div class="field">
                        <h2>Input your text</h2>
                        <div class="control">
                            <textarea class="textarea" name="content" rows="15"><?= $content ?></textarea>
                        </div>
                    </div>
                    <div class="field">
                        <h2>Settings</h2>
                        <label for="max-emoji-number">Add not more than </label>
                        <div class="control">
                            <input class="input" id="max-emoji-number" type="number" name="max-emoji-number"
                                   value="<?= $maxEmojiNumber ?>">
                            <p class="help">If you want limit amount of emoji</p>
                        </div>
                    </div>
                    <div class="field">
                        <div class="control buttons is-right">
                            <button class="button is-danger" type="submit">Reformat</button>
                        </div>
                    </div>
                    <div class="field">
                        <div class="columns">
                            <div class="column">
                                <h3>Result html</h3>
                                <hr>
                                <div class="control">
                            <textarea class="textarea" name="result" id="result" cols="30"
                                      rows="15"><?= $result ?></textarea>
                                </div>
                            </div>
                            <div class="column">
                                <h3>Result text</h3>
                                <hr>
                                <pre><?= $result ?></pre>
                            </div>
                        </div>

                    </div>

                    <hr>
                    <div><b>Found Emojies from categories</b>: <?= implode(', ', $foundEmojiCategories) ?></div>
                </form>
            </div>
        </div>
    </section>
    <footer class="footer">
        <div class="content has-text-centered">
            <p>
                <strong>ReEmoji</strong> 📒 2020</a>.
            </p>
        </div>
    </footer>
    </body>
    </html>
<?php
function console_log($data)
{
    echo '<script>';
    echo 'console.log(' . json_encode($data) . ')';
    echo '</script>';
}

function getInitText()
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