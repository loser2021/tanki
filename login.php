
<?PHP

# Счетчик
function TimerSet(){
	list($seconds, $microSeconds) = explode(' ', microtime());
	return $seconds + (float) $microSeconds;
}

$_timer_a = TimerSet();


# Старт сессии
@session_start();

# Старт буфера
@ob_start();

# Константа для Include
define("CONST_RUFUS", true);

# Автоподгрузка классов
function __autoload($name){ include("classes/_class.".$name.".php");}

# Класс конфига 
$config = new config;

# Функции
$func = new func;

# Установка REFERER
include("inc/_set_referer.php");

# База данных
$db = new db($config->HostDB, $config->UserDB, $config->PassDB, $config->BaseDB);

$life_time = new life_time($db);
$life_time->CheckTime();

include 'pages/_login.php';

# Заносим контент в переменную
$content = ob_get_contents();

# Очищаем буфер
ob_end_clean();

# Заменяем данные
$content = str_replace("{!TITLE!}",$_OPTIMIZATION["title"],$content);
$content = str_replace('{!DESCRIPTION!}',$_OPTIMIZATION["description"],$content);
$content = str_replace('{!KEYWORDS!}',$_OPTIMIZATION["keywords"],$content);
$content = str_replace('{!GEN_PAGE!}', sprintf("%.5f", (TimerSet() - $_timer_a)) ,$content);
// Выводим контент
echo $content;
?>
