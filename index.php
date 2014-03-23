<?php
//Работаем с УРЛом
if(strpos($_SERVER['REQUEST_URI'],'?')) $finish = strpos($_SERVER['REQUEST_URI'],'?');
	else $finish = strlen($_SERVER['REQUEST_URI']);
$_SERVER['REQUEST_URI'] = substr($_SERVER['REQUEST_URI'],0,$finish);
$URL = explode ('/',($_SERVER['REQUEST_URI']));

function doInclude($content) {
	for($j = 0; $j < 5; $j++) {
		preg_match_all('#\[\[(.*?)\*(\d+)\]\]#is', $content, $result);	
		if(!count($result)) break;
		foreach($result[0] as $k=>$el) {
			$content_inner = '';
			if(file_exists($result[1][$k])) {
				$tmp = file_get_contents($result[1][$k]);
				for($i = 0; $i < $result[2][$k]; $i++ ) $content_inner .= $tmp;
				$content = str_replace($el, $content_inner, $content);
			}
		}
	}
	return $content;
}

//Вывод страницы
if($URL[1]=='page') {
	$content = file_get_contents('./makeup/'.$URL[2]);
	$content = doInclude($content);

	echo $content;
} else { //Вывод списка страниц
	//Смотрим в директорию
	$dir = scandir('./makeup');

	//Выбираем htm|html файлы
	$htmls = array();
	foreach($dir as $file) {
		if(strpos($file,'.html') && strpos($file,'.htm')) {
			$htmls[] = $file;
		}

	}

	//Дергаем с каждого title 
	$titles = array();
	foreach($htmls as $i=>$file) {
		$content = file_get_contents('./makeup/'.$file);
		preg_match('#<title>(.*)<\/title>#u', $content, $result);
		$titles[$i] = $result[1];
	}

	//Формируем список
	$list = '';
	foreach($htmls as $i=>$file) {
		$list .= "<li><a href='/page/".$file."' target='_blank'>".$titles[$i]."</a></li>\r\n";

	}
	//Выводим в красивую форму 
	//$_SERVER['HTTP_HOST']
	$tpl = file_get_contents('./presentation/form.php','r');
	$tpl = str_replace('%PROJECT-NAME%', $_SERVER['HTTP_HOST'], $tpl);
	$tpl = str_replace('%LIST%', $list, $tpl);

	echo $tpl;
}
