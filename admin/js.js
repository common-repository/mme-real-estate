function function_reset(){
	jQuery('#reset').val('reset');
	jQuery('#submit').click();
}

jQuery( document ).ready( function() {
	var precio = jQuery( ".moneda" ).val();
	if ( precio!="" && typeof(precio) !== "undefined" ){ 
		precio = precio.replace(/\D/g, "")
		.replace(/\B(?=(\d{3})+(?!\d)\.?)/g, ",");
	 	jQuery( ".moneda" ).val( precio );
	} 
	
	jQuery( document ).on( "focus", ".moneda", function ( event ) {
		jQuery( event.target ).select();
	});
	
	jQuery( document ).on( "keyup", ".moneda", function ( event ) {
		jQuery( event.target ).val( function ( index, value ) {
			return value.replace(/\D/g, "")
			.replace(/\B(?=(\d{3})+(?!\d)\.?)/g, ",");
		});			
	});
});
