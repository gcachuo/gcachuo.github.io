<?php

/**
 * Globales
 *
 * CONTIENE FUNCIONES GENERALES PARA TODO EL SISTEMA
 *
 * @package Motor
 *
 */

/**
 *
 * mensajeSistema
 *
 * Devuelve una alerta de JavaScript con el mensaje especificado desde el servidor.
 *
 * @param string $str Mensaje a mostrar.
 *
 * @return string Cadena HTML que ejecuta la alerta.
 *
 */
function utf8_convertir($value) {
    if (mb_detect_encoding(utf8_decode($value)) === 'UTF-8') {
        // Double encoded, or bad encoding
        $value = utf8_decode($value);
    }

    $value = utf8_encode($value);

    return $value;
}

function sistema_mensaje($tipo, $texto) {
    $resp = '<script>alert(\'' . $texto . '\');</script>';

    return $resp;
}

function sistema_codificaTexto($texto) {
    $resultado = "";
    if (preg_match("/^[\\x00-\\xFF]*$/u", $texto) === 1) {
        $resultado = ($texto);
    } else {
        $resultado = $texto;
    }

    return $resultado;
}

/**
 *
 * obtenerMes
 * 
 * Regresa el mes en texto utilizando el numero
 *
 * @param $valor numero del mes
 * @return string mes en texto
 */
function obtenerMes($valor) {
    switch ($valor) {
        case "01": $mes = "Enero";
            break;
        case "02": $mes = "Febrero";
            break;
        case "03": $mes = "Marzo";
            break;
        case "04": $mes = "Abril";
            break;
        case "05": $mes = "Mayo";
            break;
        case "06": $mes = "Junio";
            break;
        case "07": $mes = "Julio";
            break;
        case "08": $mes = "Agosto";
            break;
        case "09": $mes = "Septiembre";
            break;
        case "10": $mes = "Octubre";
            break;
        case "11": $mes = "Noviembre";
            break;
        case "12": $mes = "Diciembre";
            break;
    }
    return($mes);
}

/**
 *
 * paginar
 * 
 * Regresa los registros de la consulta enviada paginados.
 * @param string $sql Consulta SQL a paginar
 * @param int $paginacion numero de resultados a mostrar por pagina
 * @return string cadena con el HTML de la paginacion
 */
function paginar($sql, $paginacion = 20) {
    if (!isset($_POST['txtIndice'])) {
        $indice = 0;
    } else {
        $indice = $_POST['txtIndice'];
    }

    $paginas = '';

    liberar_bd();
    $res = consulta($sql);
    $semilla = $indice * $paginacion;
    $totalpaginas = cuenta_registros($res);
    $totalpaginas = $totalpaginas / $paginacion;
    $resuido = $totalpaginas - (int) $totalpaginas;
    $totalpaginas = (int) $totalpaginas;

    if ($resuido > 0) {
        $totalpaginas++;
    }

    $paginas = '	<div id="dompaginate" class="tab-pane active">
						<ul class="pagination">';
    if ($indice == 0) {
        $paginas.='<li class="disabled"><a href="javascript:;">Inicio</a></li>';
        $paginas.='<li class="disabled"><a href="javascript:;">Anterior</a></li>';
    } else {
        $paginas.='	<li><a href="javascript:;" onClick="document.frmSistema.txtIndice.value=\'' . (0) . '\';document.frmSistema.submit();">Inicio</a></li>';
        $paginas.='	<li><a href="javascript:;" onClick="document.frmSistema.txtIndice.value=\'' . ($indice - 1) . '\';document.frmSistema.submit();">Anterior</a></li>';
    }

    $inicio = 0;

    if ($indice < 10) {
        $inicio = 0;
        $fin = 10;
    } else {
        $inicio = $indice - 5;
        $fin = $indice + 5;
    }

    if ($fin > $totalpaginas) {
        $fin = $totalpaginas;
    }


    for ($j = $inicio; $j < $fin; $j++) {
        if ($indice == $j) {
            $paginas.='	<li class="active"><a href="javascript:;">' . ($j + 1) . '</a></li>';
        } else {
            $paginas.='	<li><a href="javascript:;" onClick="document.frmSistema.txtIndice.value=\'' . ($j) . '\';document.frmSistema.submit();">' . ($j + 1) . '</a></li>';
        }
    }


    /* $paginas.=' de '. $totalpaginas.'&nbsp;'; */
    if ($indice == ($totalpaginas - 1)) {
        $paginas.='<li class="disabled"><a href="javascript:;">Siguiente</a></li>';
        $paginas.='<li class="disabled"><a href="javascript:;">Final</a></li>';
    } else {
        $paginas.='	<li><a href="javascript:;" onClick="document.frmSistema.txtIndice.value=\'' . ($indice + 1) . '\';document.frmSistema.submit();">Siguiente</a></li>';
        $paginas.='	<li><a href="javascript:;" onClick="document.frmSistema.txtIndice.value=\'' . ($totalpaginas - 1) . '\';document.frmSistema.submit();">Final</a></li>';
    }

    if ($totalpaginas == 1 || $totalpaginas == 0) {
        $paginas = '	<div id="dompaginate" class="tab-pane active">
							<ul class="pagination">';
    }

    $paginas .= '		</ul>
						</div>';

    return array($semilla, $paginas, $paginacion);
}

function normalize_date($dateNormalize) {
    $dateNormalize = date("d-m-Y", strtotime($dateNormalize));
    return $dateNormalize;
}

    function normalize_date2($dateNormalize2) {
    $dateNormalize2 = date("Y-m-d", strtotime($dateNormalize2));
    return $dateNormalize2;
}

function normalize_date_complete($dateNormalize) {
    $dateNormalize = date("d-m-Y H:m:s", strtotime($dateNormalize));
    return $dateNormalize;
}

function normalize_date_complete2($dateNormalize) {
    $dateNormalize = date("Y-m-d H:m:s", strtotime($dateNormalize));
    return $dateNormalize;
}

function normalize_time($timeNormalize) {
    $timeNormalize = date("H:i", strtotime($timeNormalize));
    return $timeNormalize;
}

//GEOLOCALIZADOR
function mqw_iplocation_func($ip) {
    $default = 'L&eacute;on';

    if (!is_string($ip) || strlen($ip) < 1 || $ip == '127.0.0.1' || $ip == 'localhost')
        $ip = '8.8.8.8';

    $curlopt_useragent = 'Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.9.2) Gecko/20100115 Firefox/3.6 (.NET CLR 3.5.30729)';

    $url = 'http://ipinfodb.com/ip_locator.php?ip=' . urlencode($ip);
    $ch = curl_init();

    $curl_opt = array(
        CURLOPT_FOLLOWLOCATION => 1,
        CURLOPT_HEADER => 0,
        CURLOPT_RETURNTRANSFER => 1,
        CURLOPT_USERAGENT => $curlopt_useragent,
        CURLOPT_URL => $url,
        CURLOPT_TIMEOUT => 1,
        CURLOPT_REFERER => 'http://' . $_SERVER['HTTP_HOST'],
    );

    curl_setopt_array($ch, $curl_opt);

    $content = curl_exec($ch);

    if (!is_null($curl_info)) {
        $curl_info = curl_getinfo($ch);
    }

    curl_close($ch);

    if (preg_match('{<li>City : ([^<]*)</li>}i', $content, $regs)) {
        $city = $regs[1];
    }
    if (preg_match('{<li>Latitude : ([^<]*)</li>}i', $content, $regs)) {
        $latitud = $regs[1];
    }
    if (preg_match('{<li>Longitude : ([^<]*)</li>}i', $content, $regs)) {
        $longitud = $regs[1];
    }
    /* if ( preg_match('{<li>State/Province : ([^<]*)</li>}i', $content, $regs) )  
      {
      $state = $regs[1];
      }
      if ( preg_match('{<li>Country : ([^<]*)</li>}i', $content, $regs) )
      {
      $country = $regs[1];
      } */

    if ($city != '') {
        $location = $city . ',' . $latitud . ',' . $longitud;
        return $location;
    } else {
        return $default;
    }
}

//RECORTADOR DE PALABRAS
function recortar_texto($texto, $limite = 100) {
    $texto = trim($texto);
    $texto = strip_tags($texto);
    $tamano = strlen($texto);
    $resultado = '';
    if ($tamano <= $limite) {
        return $texto;
    } else {
        $texto = substr($texto, 0, $limite);
        $palabras = explode(' ', $texto);
        $resultado = implode(' ', $palabras);
        $resultado .= '...';
    }
    return $resultado;
}

//DAR FORMATO DE MONEDA
function format_moneda($simbolo, $cantidad) {
    $formatMoneda = $simbolo . number_format($cantidad, 2);
    return $formatMoneda;
}

//Simple mail function with HTML header
function sendmail($from, $subject, $message, $to) {
    $headers = "MIME-Version: 1.0" . "\r\n";
    $headers .= "Content-type:text/html;charset=iso-8859-1" . "\r\n";
    $headers .= 'From: ' . $from . "\r\n";
    $result = mail($to, $subject, $message, $headers);
    if ($result)
        return 1;
    else
        return 0;
}

//Mail whith adjuntos
function mail_attachment($filename, $path, $mailto, $from_mail, $from_name, $replyto, $subject, $message, $consulta) {
    $true = true;
    $false = false;
    $file = $path . $filename;
    $file_size = filesize($file);
    $handle = fopen($file, "r");
    $content = fread($handle, $file_size);
    fclose($handle);
    $content = chunk_split(base64_encode($content));
    $uid = md5(uniqid(time()));
    $name = basename($file);
    $header = "From: " . $from_name . " <" . $from_mail . ">\r\n";
    $header .= "Reply-To: " . $replyto . "\r\n";
    $header .= "MIME-Version: 1.0\r\n";
    $header .= "Content-Type: multipart/mixed; boundary=\"" . $uid . "\"\r\n\r\n";
    $header .= "This is a multi-part message in MIME format.\r\n";
    $header .= "--" . $uid . "\r\n";
    $header .= "Content-type:text/plain; charset=iso-8859-1\r\n";
    $header .= "Content-Transfer-Encoding: 7bit\r\n\r\n";
    $header .= $message . "\r\n\r\n";
    $header .= "--" . $uid . "\r\n";
    $header .= "Content-Type: application/octet-stream; name=\"" . $filename . "\"\r\n"; // use different content types here
    $header .= "Content-Transfer-Encoding: base64\r\n";
    $header .= "Content-Disposition: attachment; filename=\"" . $filename . "\"\r\n\r\n";
    $header .= $content . "\r\n\r\n";
    $header .= "--" . $uid . "--";
    if (mail($mailto, $subject, "", $header)) {
        return $true;
    } else {
        return $false;
    }
}

function convertMayus($variable) {
    $variable = strtr(strtoupper($variable), "àèìòùáéíóúçñäëïöü", "ÀÈÌÒÙÁÉÍÓÚÇÑÄËÏÖÜ");
    return $variable;
}

function multiplo3($num) {
    if (($num % 3) == 0)
        $retorno = 1;
    else
        $retorno = 0;
}

function insertSlashes($cadena) {
    $cadena = (addslashes($cadena));
    return $cadena;
}

function selectSlashes($cadena) {
    $cadena = utf8_convertir(stripslashes($cadena));
    return $cadena;
}

function getUltimoDiaMes($elAnio, $elMes) {
    return date("d", (mktime(0, 0, 0, $elMes + 1, 1, $elAnio) - 1));
}

?>