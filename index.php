<?php

/**
 * @todo
 * 1. Maybe I should remove all html tags first. And then search all keywords. To avoid problems with <>/
 * 2. If we have several list make them different
 * 3. Suggest emoji by emoji categoriy
 * 4. Add editor
 */
$emojiDbJson = __DIR__ . '/db/emoji.json';
if (!file_exists($emojiDbJson)) {
    throw new RuntimeException(sprintf('File %s is not exist', $emojiDbJson));
}
$emojiDb = json_decode(file_get_contents($emojiDbJson), true);

$emojiKeywords        = [];
$emojiDpPrettify      = [];
$foundEmojiCategories = [];

foreach ($emojiDb as $emojiCategoryName => $emojiCategory) {
    foreach ($emojiCategory as $emoji) {
        if ($emoji['active'] === false) {
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

$fileListEmoji = __DIR__ . '/db/list_marks.json';
if (!file_exists($fileListEmoji)) {
    throw new RuntimeException(sprintf('File %s is not exist', $fileListEmoji));
}

$listEmoji = json_decode(file_get_contents($fileListEmoji), true);

$fakeText = getInitText();

$content = $_POST['content'] ?? $fakeText;
$maxEmojiNumber = $_POST['max-emoji-number'] ?? -1;
$addEmojiCount = 0;

$contentWithoutTags = strip_tags($content);
$result = $content;
    //search each keyword in line.
    foreach ($emojiKeywords as $keyword => $emojiIdList) {
        if($addEmojiCount >= $maxEmojiNumber){
            break;
        }
        if (strpos($contentWithoutTags, $keyword) !== false) {
            //if found keyword in line check what symbols around keyword and if it's ok try replace
            $emojiForReplace = $emojiDpPrettify[$emojiIdList[random_int(0, count($emojiIdList) - 1)]]['emoji'];
            $newResult = preg_replace('~([\s\'"]' . $keyword . '[\s\'"]+)~ium',
                                      '$1 ' . $emojiForReplace . " ",
                                      $result, 1);
            //if replacing is failed skip
            if ($newResult === null || $newResult === $contentWithoutTags) {
                continue;
            }
            $result = $newResult;
            //save emoji category
            if (!in_array($emojiDpPrettify[$emojiIdList[0]]['category'], $foundEmojiCategories, true)) {
                $foundEmojiCategories[] = $emojiDpPrettify[$emojiIdList[0]]['category'];
            }
            $addEmojiCount++;
            continue;
        }
    }

//getting random list mark
$currentListMark = $listEmoji['ul'][random_int(0, count($listEmoji['ul']) - 1)];

//replace html list items with emoji
$result = preg_replace('~(<ul>[\n\r]*)~ium', '', $result);//"\n","\r"
$result = preg_replace('~([\n\r]*<\/ul>)~ium', '', $result);//"\n","\r"
if($addEmojiCount < $maxEmojiNumber){
    $result = preg_replace('~<li>(.*?)<\/li>~ium', $currentListMark . ' $1<br>', $result, $maxEmojiNumber-$addEmojiCount);//"\n","\r"
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
    <h1>Make your text more attractive</h1>
    <p>What can it do:</p>
    <ul>
        <li>Add leading emoji before each item of list</li>
        <li>Add emoji after words which has related emoji</li>
    </ul>
    <!--    <link rel="stylesheet" href="public/style.css">-->
    <!--    <script src="public/me.js"></script>-->
    <!--    <script src="public/editor.js"></script>-->
    <form action="/" method="post" enctype="application/x-www-form-urlencoded">
        <label for="content">Input your text</label>
        <div class="editor"><textarea class="left" name="content" id="content"><?= $content ?></textarea>
            <div class="right">
                <pre></pre>
            </div>
        </div>
        <label for="max-emoji-number">Add not more than </label><input id="max-emoji-number" type="number" name="max-emoji-number" value="<?=$maxEmojiNumber?>">
        <button type="submit">Reformat</button>
        <textarea name="result" id="result" cols="30" rows="10"><?= $result ?></textarea>
        <div><?= $result ?></div>
        <hr>
        <div><?= implode(', ', $foundEmojiCategories) ?></div>
    </form>


    <!--    <script>-->
    <!--        window.addEventListener("load", function () {-->
    <!--            var markDownEl = document.querySelector(".editor > .right > pre");-->
    <!--            new MediumEditor(".editor > .left", {-->
    <!--                toolbar: {-->
    <!--                    buttons: ['bold', 'italic', 'underline', 'anchor', 'h2', 'h3', "unorderedlist", "orderedlist"]-->
    <!--                },-->
    <!--                extensions: {-->
    <!--                    markdown: new MeMarkdown(function (md) {-->
    <!--                        markDownEl.textContent = md;-->
    <!--                    })-->
    <!--                }-->
    <!--            });-->
    <!--        });-->
    <!--    </script>-->
<?php
function console_log($data)
{
    echo '<script>';
    echo 'console.log(' . json_encode($data) . ')';
    echo '</script>';
}

function getInitText()
{
    return '<body class="post-template-default single single-post postid-36 single-format-standard">

<header class="site-header" role="banner">

	<div class="site-branding">
					<h1 class="site-title">
				<a href="https://wp-themes.com/hello-elementor/" title="Home" rel="home">
					Hello Elementor				</a>
			</h1>
			<p class="site-description">
				A plain-vanilla &amp; lightweight theme for Elementor page builder			</p>
			</div>

	</header>

<main class="site-main post-36 post type-post status-publish format-standard hentry category-uncategorized" role="main">
			<header class="page-header">
			<h1 class="entry-title">Elements</h1>		</header>
		<div class="page-content">
		<p><!-- Sample Content to Plugin to Template --></p>
<p>The purpose of this HTML is to help determine what default settings are with CSS and to make sure that all possible HTML Elements are included in this HTML so as to not miss any possible Elements when designing a site.</p>
<hr>
<h1>Heading 1</h1>
<h2>Heading 2</h2>
<h3>Heading 3</h3>
<h4>Heading 4</h4>
<h5>Heading 5</h5>
<h6>Heading 6</h6>
<p><small><a href="#wrapper">[top]</a></small></p>
<hr>
<h2 id="paragraph">Paragraph</h2>
<p> Morbi imperdiet augue quis tellus.</p>
<p>Lorem ipsum dolor sit amet, <em>emphasis</em> consectetuer adipiscing elit. Nullam dignissim convallis est. Quisque aliquam. Donec faucibus. Nunc iaculis suscipit dui. Nam sit amet sem. Aliquam libero nisi, imperdiet at, tincidunt nec, gravida vehicula, nisl. Praesent mattis, massa quis luctus fermentum, turpis mi volutpat justo, eu volutpat enim diam eget metus. Maecenas ornare tortor. Donec sed tellus eget sapien fringilla nonummy. Mauris a ante. Suspendisse quam sem, consequat at, commodo vitae, feugiat in, nunc. Morbi imperdiet augue quis tellus.</p>
<p><small><a href="#wrapper">[top]</a></small></p>
<hr>
<h2 id="list_types">List Types</h2>
<h3>Definition List</h3>
<dl>
<dt>Definition List Title</dt>
<dd>This is a definition list division.</dd>
</dl>
<h3>Ordered List</h3>
<ol>
<li>List Item 1</li>
<li>List Item 2</li>
<li>List Item 3</li>
</ol>
<h3>Unordered List</h3>
<ul>
<li>List Item 1</li>
<li>List Item 2</li>
<li>List Item 3</li>
</ul>
<p><small><a href="#wrapper">[top]</a></small></p>
<hr>
<h2 id="form_elements">Forms</h2>
<fieldset>
<legend>Legend</legend>
<p>Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Nullam dignissim convallis est. Quisque aliquam. Donec faucibus. Nunc iaculis suscipit dui. Nam sit amet sem. Aliquam libero nisi, imperdiet at, tincidunt nec, gravida vehicula, nisl. Praesent mattis, massa quis luctus fermentum, turpis mi volutpat justo, eu volutpat enim diam eget metus.</p>
<hr>
<h2 id="tables">Tables</h2>
<table cellspacing="0" cellpadding="0">
<tbody><tr>
<th>Table Header 1</th>
<th>Table Header 2</th>
<th>Table Header 3</th>
</tr>
</tbody></table>
<p><small><a href="#wrapper">[top]</a></small></p>
<hr>
<h2 id="misc">Misc Stuff – abbr, acronym, pre, code, sub, sup, etc.</h2>
<p>Lorem <sup>superscript</sup> dolor <sub>subscript</sub> amet, consectetuer adipiscing elit. Nullam dignissim convallis est. Quisque aliquam. <cite>cite</cite>. Nunc iaculis suscipit dui. Nam sit amet sem. Aliquam libero nisi, imperdiet at, tincidunt nec, gravida vehicula, nisl. Praesent mattis, massa quis luctus fermentum, turpis mi volutpat justo, eu volutpat enim diam eget metus. Maecenas ornare tortor. Donec sed tellus eget sapien fringilla nonummy. <acronym title="National Basketball Association">NBA</acronym> Mauris a ante. Suspendisse quam sem, consequat at, commodo vitae, feugiat in, nunc. Morbi imperdiet augue quis tellus.  <abbr title="Avenue">AVE</abbr></p>
<pre><p>
<acronym title="National Basketball Association">NBA</acronym> 
Mauris a ante. Suspendisse
 quam sem, consequat at, 
commodo vitae, feugiat in, 
nunc. Morbi imperdiet augue
 quis tellus.  
<abbr title="Avenue">AVE</abbr></p></pre>
<blockquote><p>
	“This stylesheet is going to help so freaking much.” <br>-Blockquote
</p></blockquote>
<p><small><a href="#wrapper">[top]</a></small><br>
<!-- End of Sample Content --></p>
		<div class="post-tags">
					</div>
			</div>

	<section id="comments" class="comments-area">

	


</section><!-- .comments-area -->
</main>

	<footer id="site-footer" class="site-footer" role="contentinfo">
	</footer>



</body>';
}