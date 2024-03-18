$(document).ready(function() {
    $('#notice-offer-table').dataTable( {
      "paging":   false,
      initComplete: function () {
        var column =  this.api().column(0);
        var div = $('<div class="dataTables_length"></div>');                     
        var select = $('<select><option value="">-- Buscar por Campus --</option></select>')
                .appendTo( $(div).empty().append('<label>Campus: </label>') )
                .on( 'change', function () {
                    var val = $.fn.dataTable.util.escapeRegex(
                        $(this).val()
                    );

                    column
                        .search( val ? '^'+val+'$' : '', true, false )
                        .draw();
                } );
                column.data().unique().sort().each( function ( d, j ) {
                select.append( '<option value="'+d+'">'+d+'</option>' )
            } );            
        div.append(select);
        div.insertBefore('#notice-offer-table_filter');
    }, 
      "language": {
          "url": "/js/datatables.pt-br.json"
      }
    } );
} );