<?php

session_start();
error_reporting(E_ERROR);
include_once("../../sistema/motor/conexionSitio.php");
conectarSistema();

//DATOS DE LA EMPRESA
liberar_bd();
$selecDatosEmpresa = "CALL sp_sistema_select_datos_empresa();";
$datosEmpresa = consulta($selecDatosEmpresa);
$empresa = siguiente_registro($datosEmpresa);

//DATOS DE LA ORDEN
liberar_bd();
$selectDatosOrden = 'CALL sp_sistema_select_datos_orden_id(' . $_POST["idPoliza"] . ');';
$datosOrden = consulta($selectDatosOrden);
$ord = siguiente_registro($datosOrden);

//MENSAJE DEL PEDIDO
liberar_bd();
$selectMensajeOrden = 'CALL sp_sistema_select_mensaje_orden_id(' . $_POST["idOrdenEnvio"] . ');';
$mensajeOrden = consulta($selectMensajeOrden);
$men = siguiente_registro($mensajeOrden);

require_once("../../sistema/modulos/dashboard/attach_mailer/class.phpmailer.php");
$mail = new PHPMailer();
$mail->From = 'carlos.chavez@elevenmexico.com';
$mail->FromName = utf8_decode($empresa["razon"]);
$mail->Subject = utf8_decode(strtoupper("Pedido Grupo Reyma"));
$mail->Body = utf8_decode(strtoupper($men["txt"]));

$data = array();
$thedata = explode("&", $_POST['correos']);
$i = 0;
foreach ($thedata as $new) {
    $y = explode("=", $new);
    //DATOS DE LA FORMA DE CONTACTO
    liberar_bd();
    $selectDatosFormaContacto = '
select correo_agenda.id_correo_agenda id, nombre_agenda nombre,valor_correo_agenda correo from recordatorio 

INNER JOIN poliza on poliza.id_poliza=recordatorio.id_poliza
INNER JOIN agenda_cliente on agenda_cliente.id_cliente=poliza.id_cliente
INNER JOIN correo_agenda on correo_agenda.id_agenda = agenda_cliente.id_agenda
INNER JOIN agenda on agenda.id_agenda=agenda_cliente.id_agenda

where id_correo_agenda=(' . $y[1] . ') and id_recordatorio=(' . $_POST["idRecordatorio"] . ');';

    $datosFormaContacto = consulta($selectDatosFormaContacto);
    $forma = siguiente_registro($datosFormaContacto);
    $correo = strtolower($forma["correo"]);
    $nombre = strtoupper($forma["nombre"]);

    $mail->AddAddress($correo, utf8_encode($nombre));
}
$registro = select('SELECT archivo_poliza nombreArchivo FROM e11_segurosDev.poliza where id_poliza=
(select id_poliza from recordatorio where id_recordatorio=' . $_POST["idRecordatorio"] . ');');
$nombreArchivo = substr($registro["nombreArchivo"], 15);

$nombreFile = utf8_decode($nombreArchivo);
$my_path = "../../polizas/" . $registro["nombreArchivo"];
$mail->AddAttachment($my_path, $nombreFile);
//$mail->Send();
if(!$mail->send()) {
   echo 'Message could not be sent.';
   echo 'Mailer Error: ' . $mail->ErrorInfo;
}

//echo $selectMensajeOrden;
function select($consulta) {
    liberar_bd();
    $select = consulta($consulta);
    if ($select) {
        $registro = siguiente_registro($select);
    } else {
        return "ERROR AL EJECUTAR";
    }
    return $registro;
}

function moverArchivo($nombre, $carpeta) {
    $adjunto = $_FILES[$nombre];
    //GUARDAMOS EL ARCHIVO     
    if ($adjunto["name"] != "") {
        if ($adjunto["error"] == 0) {
            $pos = strrpos($adjunto["name"], '.');
            $ext = substr($adjunto["name"], $pos);
            $src = date("YmdHis") . $ext;
            $ruta = "../../" . $carpeta . "/" . $src;
            $mover = move_uploaded_file($adjunto["tmp_name"], $ruta);
            if (!$mover) {
                $error = "ERROR AL MOVER ARCHIVO";
            }
        } else {
            $src = $adjunto["error"];
        }
    } else {
        $src = '';
    }
    return $src;
}
