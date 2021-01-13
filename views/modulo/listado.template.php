<?= Helpers::paginacionHTML($this->page, $this->result['count'], $this->limit, "btn-paginacion", count($this->result['rows'])) ?>
<table class="table table-bordered table striped table-hover">
	<colgroup>
		<col width="40%">
		<col width="60%">
	</colgroup>
	<thead class="thead-dark">
		<tr>
			<th>Nombre</th>
			<th>Descripción</th>
		</tr>
	</thead>
	<tbody>
		<?php foreach($this->result['rows'] as $key => $item){ ?>
		
		<tr>
			<td><?= $item->nombre ?></td>
			<td><?= $item->descripcion ?></td>
		</tr>
		<?php } ?>
	</tbody>
</table>
<?= Helpers::paginacionHTML($this->page, $this->result['count'], $this->limit, "btn-paginacion") ?>