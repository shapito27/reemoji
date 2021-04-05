<?php
// 301 Moved Permanently
header("Location: /en/",TRUE,301);
die();
//require_once dirname(__DIR__).'/vendor/autoload.php';
//
//use Emojify\Helper;
//
//
//$fakeText = Helper::getInitText();
//
//$content = $_POST['content'] ?? $fakeText;
//
//list($result, $foundEmojiCategories, $maxEmojiNumber) = Helper::emojifyText($content);
////var_dump($result);
////die();
//
//use Symfony\Component\Translation\Translator;
//
//$translator = new Translator('fr_FR');
//$translator->addResource('array', [
//    'Hello World!' => 'Bonjour !',
//], 'fr_FR');
//
////echo $translator->trans('Hello World!'); // outputs « Bonjour ! »
//
////console_log($result);
//require_once dirname(__DIR__).'/templates/layout.php';