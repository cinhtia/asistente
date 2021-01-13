<?php 
$asignaturas = $data->fromBody('asignaturas');
$pe = $data->fromBody('pe');
$institucion = $data->fromBody('institucion');
$page = $data->fromBody('page', 1);
?>

<?php if(count($asignaturas)>0){ ?>
    <table class="table table-bordered table striped table-hover">
        <colgroup>
            <col width="20%">
            <col width="70%">
            <col width="10%">
        </colgroup>
        <thead class="thead-dark">
            <tr>
                <th>No.</th>
                <th>Nombre</th>
                <th>Opciones</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach($asignaturas as $key => $asignaturasItem){ ?>
            
            <tr>
                <td><?= ($key+1) ?></td>
                <td><?= $asignaturasItem->nombre_asignatura ?></td>
                <td>
                    <div class="dropdown">
                      <button class="btn btn-sm btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <i class="fa fa-cog"></i>
                      </button>
                      <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButton">
                        <a href="#" class="dropdown-item btn_detalles_asignatura_pe" data-idasignatura="<?= $asignaturasItem->id_asignatura ?>"><i class="fa fa-eye"></i> Detalles </a>
                        <a class="dropdown-item btn_cgs_asignatura_pe" data-idasignatura="<?= $asignaturasItem->id_asignatura ?>" href="#"><i class="fa fa-list"></i> Competencias genéricas</a>
                        <a class="dropdown-item btn_cds_asignatura_pe" data-idasignatura="<?= $asignaturasItem->id_asignatura ?>" href="#"><i class="fa fa-list"></i> Competencias disciplinares</a>
                      </div>
                    </div>  
                </td>
            </tr>
    
            <?php } ?>
        </tbody>
    </table>    
<?php }else{ ?>
<div class="alert alert-info">Este plan de estudio no cuenta con asignaturas</div>
<?php } ?>
<script type="text/javascript">
    $(document).ready(function(){
        $('#modal_btn_primary').hide();
    });
</script>