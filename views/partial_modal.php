<div class="modal fade" id="modal" tabindex="-1" role="dialog" style="overflow-y:scroll;">
  <div class="modal-dialog modal-lg" id="modal_modal_size" role="document">
    <div class="modal-content">
      <div class="modal-header" id="modal_modal_header">
        <h5 class="modal-title" id="modal_modal_title">Modal title</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div id="modal_alert"></div>
        <div id="modal_modal_body"></div>  
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-primary" id="modal_btn_primary">Guardar</button>
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="modal2" tabindex="-1" role="dialog" style="overflow-y:scroll;">
  <div class="modal-dialog modal-lg" id="modal_modal_size2" role="document">
    <div class="modal-content">
      <div class="modal-header" id="modal_modal_header2">
        <h5 class="modal-title" id="modal_modal_title2">Modal title</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div id="modal_alert2"></div>
        <div id="modal_modal_body2"></div>  
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-primary" id="modal_btn_primary2">Guardar</button>
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
      </div>
    </div>
  </div>
</div>

<script>
  $(document).ready(function(){
    $('#modal').on('hidden.bs.modal', function (e) {
      // reiniciamos las configuraciones del modal
      $('#modal_btn_primary').html('Guardar');
      //$('#modal_btn_primary').off('click');

      $('#modal_btn_primary').show();

      $('#modal_modal_body').html('');
      $('#modal_modal_title').html('');

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
      $('#modal_btn_primary2').html('Guardar');
      //$('#modal_btn_primary').off('click');

      $('#modal_btn_primary2').show();

      $('#modal_modal_body2').html('');
      $('#modal_modal_title2').html('');

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