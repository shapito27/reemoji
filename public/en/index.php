<?php
require_once dirname(__DIR__, 2).'/vendor/autoload.php';

use Emojify\Helper;
use Symfony\Component\Translation\Translator;


$fakeText = Helper::getInitText();
$content = $_POST['content'] ?? $fakeText;
$replaceType = $_POST['replace-type'] ?? Helper::REPLACE_TYPE_AFTER;
list($result, $foundEmojiCategories, $maxEmojiNumber) = Helper::emojifyText($content, $replaceType);

$htmlLang = 'en-US';
$locale = 'en_US';
$lang = 'en';
$translator = new Translator($locale);
$translator->addResource('array', [
    'main_page_title' => 'Reformat text with emoji - ReEmoji',
    'main_page_description' => 'Add emoji to text and make it more attractive and readable',
    'main_page_keywords' => 'emoji, text, content, post with emoji, content with emoji',
    'What can it do:' => 'What can it do:',
    '❤️ Make your text more attractive' => '❤️ Make your text more attractive',
    'Add leading emoji before each item of list' => 'Add leading emoji before each item of list',
    'Add emoji after words which has related emoji 💡' => 'Add emoji after words which has related emoji 💡',
    'Settings' => 'Settings',
    'Add not more than ' => 'Add not more than',
    'If you want limit amount of emoji' => 'If you want limit amount of emoji',
    'Reformat' => 'Reformat',
    'Input your text' => 'Input your text',
    'Result html' => 'Result html',
    'Found Emojies from categories' => 'Found Emojies from categories',
    'Who need this tool?' => 'Who need this tool?',
    'This service may be helpful for content creators. If you make:' => 'This service may be helpful for content creators. If you make:',
    'Small posts for messengers like Whatsapp or Telegram' => 'Small posts for messengers like Whatsapp or Telegram',
    'Social media services like instagram, facebook, vk' => 'Social media services like instagram, facebook, vk',
    'Your purpose make it attractive, or you want people to read it' => 'Your purpose make it attractive, or you want people to read it',
    'Service is for free ❤️' => 'Service is for free ❤️',
], $locale);
$translator->addLoader('array', new \Symfony\Component\Translation\Loader\ArrayLoader());

require_once dirname(__DIR__, 2).'/templates/layout.php';