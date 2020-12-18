<!doctype html>
<html lang="ru">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="initial-scale=1, maximum-scale=1, user-scalable=no" />
    <title>TanksMoney - Восстановление пароля</title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Arsenal:400,700|Ubuntu" />
    <link rel="stylesheet" href="/style/bootstrap.min.css" type="text/css" />
    <link rel="stylesheet" href="/style/pagestyle.css" type="text/css" />
    <link rel="stylesheet" href="/style/formstyle.css" type="text/css" />
    <link rel="shortcut icon" href="./img/favicon.ico">
    <link href="/style/sweet-alert.css" rel="stylesheet" type="text/css">
</head>

<body>
    <div class="container">
        <div class="content">
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

# База данных
$db = new db($config->HostDB, $config->UserDB, $config->PassDB, $config->BaseDB);


include 'pages/_recovery.php';

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

            <div class="clearfix"></div>
        </div>
    </div>
    <script src="js/jquery-3.1.1.min.js" type="text/javascript"></script>
    <script src="js/bootstrap.min.js" type="text/javascript"></script>
    <script src="/js/sweet-alert.min.js"></script>
	<!-- Yandex.Metrika counter --><!-- /Yandex.Metrika counter -->
</body>
</html>