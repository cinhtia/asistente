<?php
$cgs = $data->fromBody('cgs');
?>
<div class="container">
	<?= Helpers::paginacionHTML($page, $total, $count, "btn-paginacion-cgpe", count($cgs)) ?>
	<table class="table table-bordered table striped table-hover">
		<colgroup>
			<col width="10%">
			<col width="90%">
		</colgroup>
		<thead class="thead-dark">
			<tr>
				<th>No</th>
				<th>Competencia genérica</th>
			</tr>
		</thead>
		<tbody>
			<?php foreach($cgs as $key => $cgsItem){ ?>
			
			<tr>
				<td><?= ($key+1) ?></td>
				<td><?= $cgsItem->descripcion_cg ?></td>
			</tr>
	
			<?php } ?>
		</tbody>
	</table>
	<?= Helpers::paginacionHTML($page, $total, $count, "btn-paginacion-cgpe") ?>
</div>

<script type="text/javascript">
    $(document).ready(function(){
    	$('.btn_paginacion_cgpe').off('click').on('click', function (e){
    	    e.preventDefault();
    	    	
    	    


    	});
    });
</script>