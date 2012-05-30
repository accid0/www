<?php $this->extend( 'table');?>
<script type="text/javascript">
$.editable.addInputType("multiselect", {
    element: function (settings, original) {
        var select = $('<select multiple="multiple"/>');
        
        if (settings.width != 'none') { select.width(settings.width); }
        if (settings.size) { select.attr('size', settings.size); }

        $(this).append(select);

        return (select);
    },
    buttons : function(settings, original) {
        var default_buttons = $.editable.types['defaults'].buttons;
        settings.submit = '<input type="button" value="Save" class="mws-button green small" />';
        settings.cancel = '<input type="button" value="Cancel" class="mws-button green small" />';
        default_buttons.apply(this, [settings, original]);
    },
    content: function (data, settings, original) {
        /* If it is string assume it is json. */
        if (String == data.constructor) {
            eval('var json = ' + data);
        } else {
            /* Otherwise assume it is a hash already. */
            var json = data;
        }
        for (var key in json) {
            if (!json.hasOwnProperty(key)) {
                continue;
            }
            if ('selected' == key) {
                continue;
            }
            var option = $('<option />').val(key).append(json[key]);
            $('select', this).append(option);
        }

        if ($(this).val() == json['selected'] ||
                            $(this).html() == $.trim(original.revert)) {
            $(this).attr('selected', 'selected');
        }

        /* Loop option again to set selected. IE needed this... */
        $('select', this).children().each(function () {
            if (json.selected) {
                var option = $(this);
                $.each(json.selected, function (index, value) {
                    if (option.val() == value) {
                        option.attr('selected', 'selected');
                    }
                });
            } else {
                if (original.revert.indexOf($(this).html()) != -1)
                    $(this).attr('selected', 'selected');
            }
        });
    },
    plugin : function(settings, original){
        if($.fn.chosen) {
        	$('select', this).chosen();
    	}
    }
});
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
      //bJQueryUI: true,
      "sPaginationType": "full_numbers",
      "bProcessing": true,
      "bServerSide": true,
      "sAjaxSource": "<?= $this->url( 'datatable');?>",
      "aoColumns": [
<?php foreach( $this->data as $key => $field):?>
        			{ sName: "<?= $field?>" },
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
      "aoColumns": [
        <?php foreach ( $this->data as $field):?>
        <?= $field->toJSON();?>,
        <?php endforeach;?>
      ],
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
      sAddDeleteToolbarSelector: ".dataTables_length",
      fnShowError : function ( msg, action ) {
        console.log( '[ERROR::' + action + '::' + msg + ']');
      },
      oDeleteParameters:{
          
      },
      fnOnDeleting: function( tr, id)
		{ 	
            var key = this.aoColumns[0].name;
            this.oDeleteParameters = {
                    serializeForm : '<?= $this->data->toJSON();?>'
            };
            this.oDeleteParameters[key] = id;
			return true;
		}
	});
  
	$("#formAddNewRow").validate({
		rules: {
			spinner: {
				required: true, 
				max: 5
			},
            Роли: {
                required: true
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