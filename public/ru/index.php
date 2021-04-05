<?php
require_once dirname(__DIR__, 2).'/vendor/autoload.php';

use Emojify\Helper;
use Symfony\Component\Translation\Translator;


$fakeText = Helper::getInitText();
$content = $_POST['content'] ?? $fakeText;
list($result, $foundEmojiCategories, $maxEmojiNumber) = Helper::emojifyText($content);

$htmlLang = 'ru-RU';
$locale = 'ru_RU';
$lang = 'ru';
$translator = new Translator($locale);
$translator->addResource('array', [
    'main_page_title' => 'Добавить в текст эмодзи - ReEmoji',
    'main_page_description' => 'Делаем контент более привлекательным и читабильным с эмодзи',
    'main_page_keywords' => 'эмодзи, текст, контент, пост с эмодзи, контент с эмодзи',
    'What can it do:' => 'Что умеет делать сервис:',
    '❤️ Make your text more attractive' => '❤️ Сделаем контент более привлекательным',
    'Add leading emoji before each item of list' => 'Добавит эмодзи в начале каждого элемента списка',
    'Add emoji after words which has related emoji 💡' => 'Проанализирует слова и найдет и добавит(до, после или вместо) эмодзи близкие по значению слов 💡',
    'Settings' => 'Настройки',
    'Add not more than ' => 'Добавить не больше чем',
    'If you want limit amount of emoji' => 'Если вы хотите огранить кол-во эмодзи',
    'Reformat' => 'Преобразовать',
    'Input your text' => 'Введите ваш текст',
    'Result html' => 'Результат',
    'Found Emojies from categories' => 'Найденны эмодзи из категорий',
    'Who need this tool?' => 'Кому может быть полезен этот сервис?',
    'This service may be helpful for content creators. If you make:' => 'Этот сервис может быть полезен для контент мейкеров. Если вы создаете:',
    'Small posts for messengers like Whatsapp or Telegram' => 'Небольшие посты в мессенджерах как Whatsapp или Telegram',
    'Social media services like instagram, facebook, vk' => 'Посты в соц. сетях таких как instagram, facebook, vk',
    'Your purpose make it attractive, or you want people to read it' => 'Ваша цель сделать привлекательный текст, чтобы человек дочитал ваше сообщение, пробудить в нем любопытство или натолкнуть на идею.
    Эмодзи один из лучших способов украсить ваше сообщение, сделать его интригующим, более заметым и даже более читабельным.',
    'Service is for free ❤️' => 'Бесплатный сервис ❤️',
], $locale);
$translator->addLoader('array', new \Symfony\Component\Translation\Loader\ArrayLoader());

require_once dirname(__DIR__, 2).'/templates/layout.php';