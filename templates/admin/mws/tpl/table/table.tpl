<div class="mws-panel grid_8">
  <div class="mws-panel-header">
    <span class="mws-i-24 i-table-1"><?= $this->lang[$this->data->getTable()]?></span>
  </div>
  <div class="mws-panel-body">
    <table class="mws-datatable-fn mws-table">
      <thead>
          <tr>
          <?php foreach ( $this->data as $field):?>
            <th ><?= $this->lang[(string)$field]?></th>
            <?php endforeach;?>
          </tr>
        </thead>
        <tbody>
          <tr>
            <td colspan="3" class="dataTables_empty"></td>
          </tr>
        </tbody>
    </table>
  </div>
</div>
<form id="formAddNewRow" class="mws-form" action="#">
<input type='hidden' name='serializeForm' value='<?= $this->form->toJSON()?>'/>
  <div id="mws-validate-error" class="mws-form-message error" style="display:none;">
  </div>
  <div class="mws-form-inline">
 <?php $this->extend( 'form');?>   
  </div>
</form>