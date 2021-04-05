<?php
/**
 * @var $htmlLang
 * @var $lang
 * @var $result
 * @var $foundEmojiCategories
 * @var $maxEmojiNumber
 * @var \Symfony\Component\Translation\Translator $translator
 *
 */

?>
<!DOCTYPE html>
<html lang="<?=$htmlLang?>">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= $translator->trans('main_page_title'); ?></title>
    <link rel="canonical" href="<?=\Emojify\Helper::hrefLangList()[$lang]; ?>"/>
    <?php
    foreach (\Emojify\Helper::hrefLangList() as $hrefLang => $hrefLink):?>
        <link hreflang="<?= $hrefLang;?>" href="<?= $hrefLink; ?>" rel="alternate">
    <?php
    endforeach;?>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <meta name="description" content="<?= $translator->trans('main_page_description'); ?>">
    <meta name="keywords" content="<?= $translator->trans('main_page_keywords'); ?>">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bulma@0.9.1/css/bulma.min.css">
    <link rel="apple-touch-icon" sizes="180x180" href="/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="/favicon-16x16.png">
    <link rel="manifest" href="/site.webmanifest">
    <!-- Global site tag (gtag.js) - Google Analytics -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=G-2SSGCSQHVF"></script>
    <script>
        window.dataLayer = window.dataLayer || [];

        function gtag() {
            dataLayer.push(arguments);
        }

        gtag('js', new Date());

        gtag('config', 'G-2SSGCSQHVF');
    </script>
    <style>
        .navbar-item {
            display: flex;
        }

        .navbar,
        .navbar-menu,
        .navbar-start,
        .navbar-end {
            align-items: stretch;
            display: flex;
            /*padding: 0;*/
        }

        .navbar-menu{
            flex-grow: 1;
            flex-shrink: 0;
        }
        .navbar-start{
            justify-content: flex-start;
            margin-right: auto;
        }
        .navbar-end{
            justify-content: flex-end;
            margin-left: auto;
        }
    </style>
</head>
<body>
<nav id="navbar" class="bd-navbar navbar has-shadow is-spaced">
    <div class="container">
        <div id="navMenuDocumentation" class="navbar-menu">
            <div class="navbar-end">
<!--                <a href="/en/" class="button is-link is-light">English</a>&nbsp;-->
<!--                <a class="button is-link is-light" href="/ru/">Русский</a>-->
            </div>
        </div>
    </div>
</nav>
<main>
<section class="section">
    <div class="container">
        <h1 class="title">
            <?= $translator->trans('❤️ Make your text more attractive'); ?>
        </h1>
        <div class="content is-medium">
            <p><?= $translator->trans('What can it do:'); ?></p>
            <ul>
                <li><?= $translator->trans('Add leading emoji before each item of list'); ?></li>
                <li><?= $translator->trans('Add emoji after words which has related emoji 💡'); ?></li>
            </ul>
            <form action="" method="post" enctype="application/x-www-form-urlencoded">
                <div class="field">
                    <h2><?= $translator->trans('Settings'); ?></h2>
                    <label for="max-emoji-number"><?= $translator->trans('Add not more than '); ?></label>
                    <div class="control">
                        <input class="input" id="max-emoji-number" type="number" name="max-emoji-number"
                               value="<?= $maxEmojiNumber ?>">
                        <p class="help"><?= $translator->trans('If you want limit amount of emoji'); ?></p>
                    </div>
                </div>
                <div class="field">
                    <div class="control buttons is-right">
                        <button class="button is-danger" type="submit"><?= $translator->trans('Reformat'); ?></button>
                    </div>
                </div>
                <div class="field">
                    <div class="columns">
                        <div class="column">
                            <h2><?= $translator->trans('Input your text'); ?></h2>
                            <div class="control">
                                <textarea class="textarea" name="content" rows="15"><?= $content ?></textarea>
                            </div>
                        </div>
                        <div class="column">
                            <h2><?= $translator->trans('Result html'); ?></h2>
                            <div class="control">
                        <textarea class="textarea" name="result" id="result" cols="30"
                                  rows="15"><?= $result ?></textarea>
                            </div>
                        </div>
                    </div>
                </div>

                <hr>
                <div><b><?= $translator->trans('Found Emojies from categories'); ?></b>: <?= implode(
                        ', ',
                        $foundEmojiCategories
                    ) ?></div>
            </form>

        </div>
        <div class="content">
            <h2><?= $translator->trans('Who need this tool?'); ?></h2>
            <p>
                <?= $translator->trans('This service may be helpful for content creators. If you make:'); ?>
            </p>
            <ul>
                <li><?= $translator->trans('Small posts for messengers like Whatsapp or Telegram'); ?></li>
                <li><?= $translator->trans('Social media services like instagram, facebook, vk'); ?></li>
            </ul>
            <p>
                <?= $translator->trans('Your purpose make it attractive, or you want people to read it'); ?>
            </p>
            <p>
                <?= $translator->trans('Service is for free ❤️'); ?>
            </p>
        </div>
    </div>
</section>
</main>
<footer class="footer">
    <div class="content has-text-centered">
        <p>
            <strong>ReEmoji</strong> 📒 <?= date('Y') ?></a>
        </p>
    </div>
</footer>
</body>
</html>
