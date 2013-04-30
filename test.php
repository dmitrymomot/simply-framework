<?php

define('DOCROOT', realpath(dirname(__FILE__)).DIRECTORY_SEPARATOR);
require_once(DOCROOT.'simply/classes/Core/Autoload.php');

$test_array = array(
	'test_1' => 'test 1 - 1',
	'test_2' => 'test 2 - 1',
	'test_3' => 'test 3 - 1',
	'test_4' => 'test 4 - 1',
	'test_5' => 'test 5 - 1',

	'array' => array(
		'test_1' => 'test 1 - 2',
		'test_2' => 'test 2 - 2',
		'test_3' => 'test 3 - 2',
		'test_4' => 'test 4 - 2',
		'test_5' => 'test 5 - 2',

		'array' => array(
			'test_1' => 'test 1 - 3',
			'test_2' => 'test 2 - 3',
			'test_3' => 'test 3 - 3',
			'test_4' => 'test 4 - 3',
			'test_5' => 'test 5 - 3',
		),
	),
);
/**
 * Начало отсчета времени выполнения скрипта
 */
if ( ! defined('START_TIME'))
{
    define('START_TIME', microtime(TRUE));
}

/**
 * Начало отсчета потребляемой памяти скрипта
 */
if ( ! defined('START_MEMORY'))
{
    define('START_MEMORY', memory_get_usage());
}

$autoloader = new \Core\Autoload();
$autoloader->set_paths(array(DOCROOT.'test', DOCROOT.'simply'));
$autoloader->register();

// echo '<pre>';
// for ($i = 0; $i < 1000; $i++)
// {
	// var_dump(\Helpers\Date::adjust(3, 'pm'));
	// echo \Helpers\File::ext_by_path('fdsvgsdfv.png');
// }
// echo '</pre>';
	// echo \Helpers\File::ext_by_path('fdsvgsdfv.png');

//Устанавливаем заголовок RSS канала
// $info = array(
	// 'title' => 'Новости',
	// 'language' => 'ru',
	// 'description' => 'Новости от Mysite',
	// 'link' => 'http::/mysite.com/news/rss',
	// 'pubDate' => time()
// );
//
// $items = array();
//
// for ($i = 0; $i < 10; $i++)
// {
	// $items[] = array(
		// 'title' => 'Название',
		// 'link' => 'link',
		// 'guid' => 'guid',
		// 'description' => 'описание',
		// 'pubDate' => time(),
	// );
// }

//Перед выводом не забудем установить правильный хедер для xml
// header('Content-Type: text/xml');

//выводим нашу RSS ленту
// echo \Helpers\Feed::create($info, $items);




echo '<br/>< !-- time: ' . round(((microtime(TRUE) - START_TIME)*1000), 5) . ' ms; memory: ' . sprintf('%01.5f', ((memory_get_usage() - START_MEMORY) / 1024)) . ' KB -- >';
