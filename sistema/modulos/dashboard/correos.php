<?php

function cargarCorreos($idRecordatorio) {
    session_start();
    error_reporting(E_ERROR);
    include_once("../../sistema/motor/conexionSitio.php");
    conectarSistema();

//DATOS DEL PROVEEDOR
    liberar_bd();
    $selectDatosProveedor = 'CALL sp_sistema_select_datos_proveedor(' . $idProveedor . ');';
    $datosProveedor = consulta($selectDatosProveedor);
    $prov = siguiente_registro($datosProveedor);

//LISTA DE CORREOS DEL PROVEEDOR
    liberar_bd();
    $selectCorreosProveedor = 'CALL sp_sistema_select_correos_recordatorio(' . $idRecordatorio . ');';
    $correosProveedor = consulta($selectCorreosProveedor);
    foreach ($correosProveedor as $cor) {
        $listaCorreos.= '	
<tr>
    <td>
        <input type="checkbox" checked="checked" class="checkAll" name="correos[]" id="cor' . $cor["id"] . '" value="' . $cor["id"] . '"/>
    </td>
    <td style="text-align: center;">' . utf8_encode($cor["nombre"]) . '</td>
    <td class="frmCorreo">' . utf8_encode($cor["correo"]) . '</td>
</tr>';
    }


    $datosCorreos = '	
<div class="row">
    <div class="col-md-12">
        <div class="table-responsive">
            <table class="table table-condensed" name="correos">
                <input type="hidden" name="idRecordatorioEnvio" id="idRecordatorioEnvio" readonly value="' . $idRecordatorio . '"/>
                <thead>
                    <tr>
                        <th></th>
                        <th style="text-align: center;">NOMBRE</th>														
                        <th>CORREO</th>												
                    </tr>
                </thead>
                <tbody>
                    ' . $listaCorreos . '
                </tbody>
            </table>
        </div>
    </div>    
</div>';

    return $datosCorreos;
}
