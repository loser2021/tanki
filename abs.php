<?php
echo $_SERVER['DOCUMENT_ROOT'];

?>
<br /><br />
<?php


/*$sum='999';
if($sum >= 1000){
$proc=$sum+$sum*1;
}else{
$proc=$sum+$sum*0.05;
}
echo $proc; */









$amountprice = 9;
$views = 7;
echo  $views/$amountprice*100;





?>
<br /><br /><br />
<?

 function Persent($var, $basa, $persent = true) {
  $d = $var/$basa; //—читаем какую долю $var составл¤ет от $basa
  if($persent) return round($d*100);
  return $d;
}

echo Persent(9,38);


?>
