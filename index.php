<?php

$fileListEmoji = __DIR__ . '/db/list_marks.json';
if (!file_exists($fileListEmoji)) {
    throw new RuntimeException(sprintf('File %s is not exist', $fileListEmoji));
}

$listEmoji = json_decode(file_get_contents($fileListEmoji), true);

$content = $_POST['content'];

//getting random list mark
$currentListMark = $listEmoji['ul'][random_int(0, count($listEmoji['ul']))];

//replace html list items with emoji
$result = preg_replace('~(<ul>[\n\r]*)~ium', '', $content);//"\n","\r"
$result = preg_replace('~([\n\r]*<\/ul>)~ium', '', $result);//"\n","\r"
$result = preg_replace('~<li>(.*)<\/li>~ium', $currentListMark . ' $1', $result);//"\n","\r"

//replace list items(no html) with emoji
$matches = null;
//var_dump(preg_match_all('~\r\n[a-zA-Zа-яА-Я]+~ium', $result, $matches));
//var_dump($matches);
if (preg_match('~\r\n[a-zA-Zа-яА-Я]+~ium', $result, $matches)) {
}
$result = preg_replace('~\r\n\r\n\r\n(.*)\r\n~im', '<ul><li>$1</li>', $result);
//$result = preg_replace('~\n~im', '@', $result);
//$result = preg_replace('~\r~im', '%', $result);


?>
    <link rel="stylesheet" href="public/style.css">
    <script src="public/me.js"></script>
    <script src="public/editor.js"></script>
    <form action="/" method="post">
        <label for="content">Text</label>
        <div class="editor"><textarea class="left" name="content" id="content"><?= $content ?></textarea><div class="right"><pre></pre></div></div>
        <button type="submit">Reformat</button>
        <textarea name="result" id="result" cols="30" rows="10"><?= $result ?></textarea>
    </form>


    <script>
        window.addEventListener("load", function () {
            var markDownEl = document.querySelector(".editor > .right > pre");
            new MediumEditor(".editor > .left", {
                toolbar: {
                    buttons: ['bold', 'italic', 'underline', 'anchor', 'h2', 'h3', "unorderedlist", "orderedlist"]
                },
                extensions: {
                    markdown: new MeMarkdown(function (md) {
                        markDownEl.textContent = md;
                    })
                }
            });
        });
    </script>
<?php
function console_log($data)
{
    echo '<script>';
    echo 'console.log(' . json_encode($data) . ')';
    echo '</script>';
}