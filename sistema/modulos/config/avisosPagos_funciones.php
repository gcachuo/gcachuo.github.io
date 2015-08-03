<?php

function avisosPagos_menuInicio() {
    $btnAlta = false;
    $btnEdita = false;
    $btnElimina = false;
    $btnPermisos = false;

    //PREMISOS DE ACCIONES
    liberar_bd();
    $selectPermisosAcciones = 'CALL sp_sistema_select_permisos_acciones_modulo(' . $_SESSION["idPerfil"] . ', ' . $_SESSION["mod"] . ');';
    $permisosAcciones = consulta($selectPermisosAcciones);
    while ($acciones = siguiente_registro($permisosAcciones)) {
        switch (utf8_encode($acciones["accion"])) {
            case 'Alta':
                //$btnAlta = true;
                break;
            case 'Editar':
                $btnEdita = true;
                break;
            case 'Permisos de tablero':
                $btnPermisos = true;
                break;
            case 'Eliminar':
                //$btnElimina = true;
                break;
        }
    }

    liberar_bd();
    $selectAvisos = 'CALL sp_sistema_select_lista_avisos();';
    $avisos = consulta($selectAvisos);

    $pagina = '
<div id="page-heading">
<ol class="breadcrumb">
   <li><a href="javascript:navegar_modulo(0);">Tablero</a></li>
   <li class="active">
      ' . $_SESSION["moduloPadreActual"] . '
   </li>
</ol>
<h1>' . $_SESSION["moduloPadreActual"] . '</h1>
<div class="options">
<div class="btn-toolbar">
<input type="hidden" id="idPerfil" name="idPerfil" value="" />
<input type="hidden" name="txtIndice" />';

    if ($btnAlta) {
        $pagina.= '	<a title="Nuevo Perfil" onclick="navegar(\'Nuevo\')" class="btn btn-warning" >Nuevo Perfil</a>';
    }

    $pagina.= '
</div>							
</div>										
</div>		
<div class="container">
<div class="row">
<div class="col-sm-12">
<div class="panel panel-danger">
<div class="panel-heading">
   <h4></h4>
   <div class="options">   
      <a href="javascript:;" class="panel-collapse"><i class="fa fa-chevron-down"></i></a>
   </div>
</div>
<div class="panel-body collapse in">
<div class="table-responsive">
<table cellpadding="0" cellspacing="0" border="0" class="table table-striped table-bordered" id="example">
<thead>
   <tr>
      <th>AVISO</th>
      <th>ACCIONES</th>
   </tr>
</thead>
<tbody>';

    while ($avi = siguiente_registro($avisos)) {
        $pagina.= '
<tr>
   <td name="tddescripcion">' .$avi["dias"]." ". $avi["descripcion"] . '</td>
   <td class="tdAcciones">';

        if ($btnEdita) {
            $pagina.= '	<a class="btn btn-default-alt btn-sm" href="#modalEditar" id="bootbox-demo-5" data-toggle="modal">
                <i title="Editar" style="cursor:pointer;" class="fa fa-pencil"></i>
                </a>';
        }
        if ($btnPermisos) {
            $pagina.= '	<a class="btn btn-default-alt btn-sm"  onClick="document.frmSistema.idPerfil.value=' . $avi["id"] . ';navegar(\'Permisos\');" >
                <i title="Permisos" style="cursor:pointer;" class="fa fa-cog"></i>
                </a>';
        }
        if ($btnElimina) {
            $pagina.= '	<a class="btn btn-default-alt btn-sm" onClick="if(confirm(\'Desea eliminar esta perfil\'))'
                    . '{document.frmSistema.idPerfil.value=' . $avi["id"] . ';navegar(\'Eliminar perfil\')};">
                <i title="Eliminar" style="cursor:pointer;" class="fa fa-trash-o"></i>
                </a>';
        }
        $pagina.= '
</td>
</tr>
</tbody>
</table>
</div>
</div>
</div>
</div>
</div>
</div>';
        ?>    
        <div class="modal fade" id="modalEditar" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">Editar Aviso</h4>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-sm-12">
                                <div class="form-horizontal">                                    
                                    <div class="form-group">
                                        <label for="cantidad_aviso" class="col-sm-4 control-label">Cantidad:</label>
                                        <div class="col-sm-8">
                                            <input type="number" min="1" max="365" class="form-control" id="cantidad_aviso" 
                                                   name="cantidad_aviso" maxlength="4" required pattern="[0-9]{1,4}"
                                                   value="<?php echo $avi["dias"]?>"/>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="descripcion_aviso" class="col-sm-4 control-label">Descripci&oacute;n:</label>
                                        <div class="col-sm-8">
                                            <input type="text" class="form-control" id="descripcion_aviso" name="descripcion_aviso" maxlength="100" required 
                                                   value="<?php echo $avi["descripcion"]?>"/>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">									
                        <i class="btn-danger btn" data-dismiss="modal">Cancelar</i>
                        <i class="btn-success btn" data-dismiss="modal" id="btnActualizar" onclick="editarAviso()">Guardar</i> 
                    </div>
                </div>
            </div>
        </div>
        <?php
        $_SESSION['pagina'] = $pagina;
        return $pagina;
    }
}

function avisosPagos_editar() {
    //parent.window.location.reload();
}
