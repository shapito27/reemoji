<?php
/**
 * @var $htmlLang
 * @var $lang
 * @var $result
 * @var $foundEmojiCategories
 * @var $maxEmojiNumber
 * @var $replaceType
 * @var \Symfony\Component\Translation\Translator $translator
 *
 */

use Emojify\Helper;

?>
<!DOCTYPE html>
<html lang="<?=$htmlLang?>">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= $translator->trans('main_page_title'); ?></title>
    <link rel="canonical" href="<?= Helper::hrefLangList()[$lang]; ?>"/>
    <?php
    foreach (Helper::hrefLangList() as $hrefLang => $hrefLink):?>
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
    <!-- Google Tag Manager -->
    <script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
                new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
            j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
            'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
        })(window,document,'script','dataLayer','GTM-5JBH4F6');</script>
    <!-- End Google Tag Manager -->
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
<!-- Google Tag Manager (noscript) -->
<noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-5JBH4F6"
                  height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
<!-- End Google Tag Manager (noscript) -->
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
                    <label>How to place emoji</label>
                    <div class="">
                        <label for="replace-type-before" class="radio">
                            <input class="radio" id="replace-type-before" type="radio" name="replace-type" value="before" <?= $replaceType === Helper::REPLACE_TYPE_BEFORE?'checked':''?>>
                            before keyword </label>&nbsp;&nbsp;&nbsp;

                        <label for="replace-type-after">
                            <input class="radio" id="replace-type-after" type="radio" name="replace-type" value="after" <?=$replaceType=== Helper::REPLACE_TYPE_AFTER?'checked':''?>>
                            after keyword </label>&nbsp;&nbsp;&nbsp;

                        <label for="replace-type-instead">
                            <input class="radio" id="replace-type-instead" type="radio" name="replace-type" value="instead" <?=$replaceType=== Helper::REPLACE_TYPE_INSTEAD?'checked':''?>>
                            instead keyword </label>&nbsp;&nbsp;&nbsp;

                        <label for="replace-type-randomly">
                            <input class="radio" id="replace-type-randomly" type="radio" name="replace-type" value="randomly" <?=$replaceType===Helper::REPLACE_TYPE_RANDOMLY?'checked':''?>>
                            randomly </label>&nbsp;&nbsp;&nbsp;

                        <!--                            <p class="help">If you want limit amount of emoji</p>-->
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
            <strong>ReEmoji</strong> 📒 <?= date('Y') ?></a> <a href="https://github.com/shapito27/reemoji" style="vertical-align: text-bottom;"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path d="M12 0c-6.626 0-12 5.373-12 12 0 5.302 3.438 9.8 8.207 11.387.599.111.793-.261.793-.577v-2.234c-3.338.726-4.033-1.416-4.033-1.416-.546-1.387-1.333-1.756-1.333-1.756-1.089-.745.083-.729.083-.729 1.205.084 1.839 1.237 1.839 1.237 1.07 1.834 2.807 1.304 3.492.997.107-.775.418-1.305.762-1.604-2.665-.305-5.467-1.334-5.467-5.931 0-1.311.469-2.381 1.236-3.221-.124-.303-.535-1.524.117-3.176 0 0 1.008-.322 3.301 1.23.957-.266 1.983-.399 3.003-.404 1.02.005 2.047.138 3.006.404 2.291-1.552 3.297-1.23 3.297-1.23.653 1.653.242 2.874.118 3.176.77.84 1.235 1.911 1.235 3.221 0 4.609-2.807 5.624-5.479 5.921.43.372.823 1.102.823 2.222v3.293c0 .319.192.694.801.576 4.765-1.589 8.199-6.086 8.199-11.386 0-6.627-5.373-12-12-12z"/></svg></a>
        </p>
    </div>
</footer>
</body>
</html>
