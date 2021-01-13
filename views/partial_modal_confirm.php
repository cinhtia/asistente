<div class="modal fade" id="modal_confirm" tabindex="-1" role="dialog">
  <div class="modal-dialog modal-lg" role="document" id="modal_confirm_size">
    <div class="modal-content">
      <div class="modal-header" id="modal_confirm_header">
        <h5 class="modal-title" id="modal_modal_confirm_title">Modal title</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body" id="modal_modal_confirm_body">
        
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-primary" id="modal_confirm_btn_primary">Aceptar</button>
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="modal_confirm2" tabindex="-1" role="dialog">
  <div class="modal-dialog modal-lg" role="document" id="modal_confirm_size2">
    <div class="modal-content">
      <div class="modal-header" id="modal_confirm_header2">
        <h5 class="modal-title" id="modal_modal_confirm_title2">Modal title</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body" id="modal_modal_confirm_body2">
        
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-primary" id="modal_confirm_btn_primary2">Aceptar</button>
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
      </div>
    </div>
  </div>
</div>

<script>
  $(document).ready(function(){
    $('#modal').on('hidden.bs.modal', function (e) {
      // reiniciamos las configuraciones del modal
      $('#modal_confirm_btn_primary').html('Aceptar');
      $('#modal_confirm_btn_primary').show();
      $('#modal_modal_confirm_body').html('');
      $('#modal_modal_confirm_title').html('');
      
      $('#modal_confirm_size').removeClass('modal-md');
      $('#modal_confirm_size').removeClass('modal-sm');
      $('#modal_confirm_size').removeClass('modal-xs');
      $('#modal_confirm_size').removeClass('modal-lg');

      $('#modal_confirm_header').removeClass('modal-header-danger');
      $('#modal_confirm_header').removeClass('modal-header-success');
      $('#modal_confirm_header').removeClass('modal-header-warning');
      $('#modal_confirm_header').removeClass('modal-header-info');

    });

    $('#modal2').on('hidden.bs.modal', function (e) {
      // reiniciamos las configuraciones del modal
      $('#modal_confirm_btn_primary2').html('Aceptar');
      $('#modal_confirm_btn_primary2').show();
      $('#modal_modal_confirm_body2').html('');
      $('#modal_modal_confirm_title2').html('');
      
      $('#modal_confirm_size2').removeClass('modal-md');
      $('#modal_confirm_size2').removeClass('modal-sm');
      $('#modal_confirm_size2').removeClass('modal-xs');
      $('#modal_confirm_size2').removeClass('modal-lg');

      $('#modal_confirm_header2').removeClass('modal-header-danger');
      $('#modal_confirm_header2').removeClass('modal-header-success');
      $('#modal_confirm_header2').removeClass('modal-header-warning');
      $('#modal_confirm_header2').removeClass('modal-header-info');

    });
  });
</script>