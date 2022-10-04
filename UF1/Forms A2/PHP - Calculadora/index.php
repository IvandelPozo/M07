<!DOCTYPE html>
<html lang="ca">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <link rel="stylesheet" type="text/css" href="style.css" />
    <title>Calculadora</title>
</head>
<body>
<?php

/** 
* Si la variable Valor está definida i és igual a ERROR o INFINIT, netejarà la pantalla a la següent escriptura.
*/

if (isset($_POST["Valor"]) && ($_POST["Valor"] == "ERROR" || $_POST["Valor"] == "INF")) {

    $_POST["Valor"] = "";
}

/** 
* Funció principal, s'encarrega de cridar funcions o fer operacions depenent els botons que es cliquen.
*
* @return int|float|string $numero Pot retornar números enters, números decimals, o textes d'error.
*/

function obtenirEntrada():int|float|string {

	$numero = implode($_POST);
	$digitFinal = end($_POST);
	
	switch ($digitFinal) {
        case 'x2':
            array_pop($_POST);
            $cadenaPantalla = implode($_POST);
            $calcul = calculs($cadenaPantalla);
            
            $numero = exponent($calcul);
            break;
            case 'SIN':
                $numero .= "(";
                break;
            case 'COS':
                    $numero .= "(";
                    break;
            case 'C':
                $numero = "";
                break;
            case '=':
				array_pop($_POST);
				$cadenaPantalla = implode($_POST);
               
                $numero = calculs($cadenaPantalla);
                break;
        }
	return $numero;
}

/** 
* Funció que s'encarrega de fer les operacions del string que rep. Té un RegExpression que evita que entrin codi i altres signes no vàlids.
* Crida altres funcions que fan que si el càlcul és decimal, mostra quatre decimals com a resultat dels números amb fracció decimal.
*
* @param string $operacio Rep en forma de cadena (string) el valor dels botons que es marquen per pantalla.
* @return int|float|string $resultat Pot retornar números enters, números decimals, o textes d'error.
*/

function calculs(string $operacio):int|float|string {

    try {

        if(preg_match('/^[0-9()+.\-*\(SIN)(COS)\/]+$/', $operacio)){

            $calcul = eval("return (".$operacio.");");
            $decimal = esDecimal($calcul);

            if ($decimal == 1) {
                $resultat = obtenirQuatreDecimals($calcul);
            } else {
                $resultat = $calcul;
            }
        } else {
            throw new NoRegex("ERROR");
        }    

    } catch (DivisionByZeroError $e) {

        $resultat = "INF";

    } catch (Throwable $e) {

        $resultat = "ERROR";

    }
    return $resultat;
}

/** 
* Funció que s'encarrega de comprovar si un número és o no, decimal.
*
* @param $num Rep el càlcul de la funció eval.
* @return int Retorna 1 si són decimals.
*/

function esDecimal($num):int
{
    return is_numeric( $num ) && floor($num) != $num;
}


/** 
* Funció que s'encarrega de comprovar si un número és o no, decimal.
*
* @param $num Rep el càlcul de la funció eval.
* @return int Retorna 1 si són decimals.
*/

function obtenirQuatreDecimals(float $float, int $significantDigits = 4):float
{
    $format = sprintf('%%.%df', $significantDigits + 1);

    return (float) substr(sprintf($format, $float), 0, -1);
}

/** 
* Funció que s'encarrega de fer l'exponent de 2.
*
* @param $num Rep el càlcul de la funció eval.
* @return int|float Retorna el càlcul en exponent de 2.
*/

function exponent($num):int|float {
    return $num *= $num;
}

?>
    <div class="container">
        <form action="index.php" method="post" name="calc" class="calculator">
            <input name="Valor" type="text" class="value" readonly value="<?php echo obtenirEntrada(); ?>" />
            <span class="num petites"><input name="op" type ="submit" value="x2"></span>
            <span class="num petites"><input name="op" type ="submit" value=""></span>
            <span class="num petites"><input name="op" type ="submit" value=""></span>
            <span class="num petites"><input name="op" type ="submit" value=""></span>
            <span class="num petites"><input name="op" type ="submit" value="("></span>
            <span class="num petites"><input name="op" type ="submit" value=")"></span>
            <span class="num petites"><input name="op" type ="submit" value="SIN"></span>
            <span class="num petites"><input name="op" type ="submit" value="COS"></span>
            <span class="num clear"><input name="op" type ="submit" value="C"></span>
            <span class="num"><input name="op" type ="submit" value="/"></span>
            <span class="num"><input name="op" type ="submit" value="*"></span>
            <span class="num"><input name="Numeros" type ="submit" value="7"></span>
            <span class="num"><input name="Numeros" type ="submit" value="8"></span>
            <span class="num"><input name="Numeros" type ="submit" value="9"></span>
            <span class="num"><input name="Numeros" type ="submit" value="-"></span>
            <span class="num"><input name="Numeros" type ="submit" value="4"></span>
            <span class="num"><input name="Numeros" type ="submit" value="5"></span>
            <span class="num"><input name="Numeros" type ="submit" value="6"></span>
            <span class="num plus"><input name="op" type ="submit" value="+"></span>
            <span class="num"><input name="Numeros" type ="submit" value="1"></span>
            <span class="num"><input name="Numeros" type ="submit" value="2"></span>
            <span class="num"><input name="Numeros" type ="submit" value="3"></span>
            <span class="num"><input name="Numeros" type ="submit" value="0"></span>
            <span class="num"><input name="Numeros" type ="submit" value="00"></span>
            <span class="num"><input name="Numeros" type ="submit" value="."></span>
            <span class="num equal"><input name="Enviar" type ="submit" value="="></span>
        </form>
    </div>
</body>
</html>