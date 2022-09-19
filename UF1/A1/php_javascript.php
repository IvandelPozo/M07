<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">

<title>Pràctica hello world</title>

<script type="text/javascript">
var i=0;
var ii=1;	
function f_incrementa(){
   var iii=i+ii;
   i= ii;
   ii=iii;
   document.getElementById('incrementa').innerHTML += ', ' + iii;   
   return false;
};
</script>

<style type="text/css">
	
</style>

</head>

<body>
    <?php
    echo( "<h1>La meva sèrie fibonacci</h1>" );
    ?>
    <b id='incrementa'>0</b> </p> 
    <input type='button' onclick='f_incrementa()' value='Següent'/>	
<p>
Benvinguts al curs de PHP.	
</p>
</body>
</html>
