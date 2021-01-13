<div class="container-fluid">
	<?php json_encode($this->palabras) ?>
	<div class="row">
		<div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
			<select multiple="multiple" id="my-select" name="my-select[]">
				<?php foreach ($this->palabras as $index => $palabra) { ?>
					<option value="<?= $palabra ?>"><?= $palabra ?></option>
				<?php } ?>
			</select>
		</div>
		<div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
			
		</div>
	</div>
</div>

<script type="text/javascript">
    $(document).ready(function(){
		$('#my-select').multiSelect();
    });
</script>