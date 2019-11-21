$(document).ready(function(){
   
    var getdetails = function(id){
        return $.getJSON( "personas.php", { "id" : id });
    }
    
    
    $('[data-user]').click(function(e){
        
        e.preventDefault();
        
        $("#response-container").html("<p>Buscando...</p>");
        
        getdetails($(this).data('user'))
        .done( function( response ) {
           
            if( response.success ) {
                
                var output = "<h1>" + response.data.message + "</h1>";
                
                $.each(response.data.users, function( key, value ) {
                    output += "<h2>Detalles del usuario " + value['ID'] + "</h2>";
                    
                    $.each( value, function ( userkey, uservalue) {
                        output += '<ul>';
                        output += '<li>' + userkey + ': ' + uservalue + "</li>";
                        output += '</ul>';
                    });
                });
                
               
                $("#response-container").html(output);
                
                } else {
          
                $("#response-container").html('No ha habido suerte: ' + response.data.message);
            }
        })
        .fail(function( jqXHR, textStatus, errorThrown ) {
            $("#response-container").html("Algo ha fallado: " +  textStatus);
        });
    });
});        