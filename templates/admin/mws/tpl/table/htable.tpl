<?php $this->extend( 'table');?>
<script type="text/javascript">

$(document).ready(function() {
	var opts = {
			's1': {decimals:2},
			's2': {stepping: 0.25},
			's3': {currency: '$'}, 
			's4': {decimals:2}
		};
    for (var n in opts)
		$("#"+n).spinner();
	var oTable = $('.mws-datatable-fn').dataTable( {
      /*bJQueryUI: true,*/
      "sPaginationType": "full_numbers",
      "bProcessing": true,
      "bServerSide": true,
      "sAjaxSource": "<?= $this->url( 'datatable');?>",
      "aoColumns": [
<?php foreach( $this->data as $key => $field):?>
        			{ "sName": "<?= $field?>" },
<?php endforeach;?>
        		],
      "fnServerData": function ( sSource, aoData, fnCallback ) {
          /* Add some extra data to the sender */
          aoData.push( { 
              "name": "graph", 
              "value" : "<?=$this->data->getGraph();?>" 
          } );
          aoData.push( { 
              "name": "table", 
              "value" : "<?= $this->data->getTable();?>" 
          } );
          $.ajax( {
          "dataType": 'json', 
          "type": "POST", 
          "url": sSource, 
          "data": aoData, 
          "success": fnCallback
        });
      }
    } );
	oTable . makeEditable({
      sUpdateURL: "<?= $this->url( 'refresh');?>",
      sAddURL: "<?= $this->url( 'add');?>",
      sDeleteURL: "<?= $this->url('delete');?>",
      oAddNewRowButtonOptions: {	
        label: "Добавить...",
        icons: {primary:'mws-ic-16 ic-add'} 
      },
      oDeleteRowButtonOptions: {
        label: "Удалить...", 
      icons: {primary:'mws-ic-16 ic-trash red'}
      },
      oAddNewRowFormOptions: { 	
        title: 'Добавить строку в таблицу',
	    show: "blind",
		hide: "explode",
        modal: true, 
        width: "640"
      },
      sAddDeleteToolbarSelector: ".dataTables_length"	
	});
	$("#formAddNewRow").validate({
		rules: {
			spinner: {
				required: true, 
				max: 5
			}
		}, 
		invalidHandler: function(form, validator) {
			var errors = validator.numberOfInvalids();
			if (errors) {
				var message = errors == 1
				? 'Вы пропустили 1 поле. Оно выделено цветом.'
				: 'Вы пропустили ' + errors + ' полей. Они выделены цветом.';
				$("#mws-validate-error").html(message).show();
			} else {
				$("#mws-validate-error").hide();
      		}
		}
	});
} );
</script>