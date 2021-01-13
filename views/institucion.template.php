<?php $institucion = $data->fromBody('institucion', []); ?>
<div class="container">
	<h4>Información de la institución</h4>
	<div class="table-responsive mt-25">
		<table class="table bordered striped">
			<?php foreach ($institucion as $elemento) { ?>
				<tr>
					<th class="text-right"><?= $elemento['titulo'] ?></th>
					<td><?= $elemento['valor'] ?></td>
				</tr>
			<?php } ?>
		</table>
	</div>
</div>