var inter = "?"
jQuery( document ).ready( function ( $ ) {
	if( type_permalink=="plain" ) {
		inter = "&"	
	}
	$( '#mme-filtros .mme-moneda' ).on( 'input', function () { 
		this.value = this.value.replace(/[^0-9]/g,'');
	});
	jQuery( "#mme-filtros #mme-state" ).change( function( $e ) {
		var idedo = jQuery( '#mme-filtros #mme-state option:selected' ).attr( 'idedo' ) 
		var ajaxURL = ajax_var . url + inter + 'act=municipio&idEdo=' + idedo;
		$.ajax({
			url:		ajaxURL,
			type:		'get',
			dataType:	"json", 
			beforeSend:	function(){ 
				$( "#mme-filtros #mme-city" ).html( '<option value="">--Load....---</option>' ) 
			},
			success:	function ( response ) {
				var newarr = JSON.parse( response )   
				$( "#mme-filtros #mme-city" ).html( newarr.municipios )  
			}
		});
	});
	jQuery( "#mme-content-list-propiedades .button-propiedad" ).click( function( $e ) {
		var href				= jQuery( this ).attr( 'href' );
		window.location.href	= href; 
		return false;
	});
	jQuery( "#mme-filtros #mme-operation-type" ).change( function( $e ) {
		const headers = new Headers({
			'Content-Type':	'application/json',
			'X-WP-Nonce': 	ajax_var.nonce
		});
		var mun = jQuery( "#mme-filtros #mme-city" ).val()
		var edo = jQuery( "#mme-filtros #mme-state" ).val()
		var col = jQuery( "#mme-filtros #mme-neighborhood" ).val() 
		var pop = jQuery( "#mme-filtros #mme-operation-type" ).val() 
		operacion_propiedad( "mme-property-type", mun, edo, col, pop )
	});
	jQuery( "#mme-filtros #mme-neighborhood" ).change( function( $e ) {
		const headers = new Headers({
			'Content-Type':	'application/json',
			'X-WP-Nonce':	ajax_var . nonce
		});
		var mun = jQuery( "#mme-filtros #mme-city" ).val()
		var edo = jQuery( "#mme-filtros #mme-state" ).val()
		var col = jQuery( "#mme-filtros #mme-neighborhood" ).val()
		operacion_propiedad( "mme-operation-type", mun, edo, col )
		operacion_propiedad( "mme-property-type", mun, edo, col, "" )
	});
	jQuery( "#mme-filtros #mme-city" ).change(function( $e ) {
		var idestado    = jQuery( "#mme-filtros #mme-state" ).val() 
		var idmunicipio = jQuery( this ).val() 
		var ajaxURL = ajax_var . url + inter + 'act=colonia&idMun=' + idmunicipio;
		$.ajax({
			url:		ajaxURL,
			type:		'get',
			dataType:	"json",
			beforeSend: function(){ 
				$( "#mme-filtros #mme-neighborhood" ).html( '<option value="">--Load....---</option>' )  ;
			},
			success:  function (response){
				var newarr = JSON.parse( response )   
				$("#mme-filtros #mme-neighborhood").html( newarr.colonias )  
			}
		});
		operacion_propiedad( "mme-operation-type", idmunicipio, idestado, "" )
	});
	jQuery( "#mme-content-propiedades #form-data" ).submit(function() {
		var url_ajax_post = "/wp-json/remmdatapost/post";
		if( type_permalink == "plain" ) {
			url_ajax_post = "/index.php?rest_route=/remmdatapost/post";	
		}		
		var asunto = jQuery( "#subject" ).val();
		if( asunto=="" ){
			jQuery( "#mme-content-propiedades #respuesta" ).html( 'Sending message' );
			var formulario	= document.getElementById( "form-data" );
			var datos		= new FormData( formulario );
			fetch(url_ajax_post, {
				method: "POST",
				body: datos
			})
			.then( res => res.json() )
			.then( data => {
				data = JSON.parse( data )  
				jQuery( "#mme-content-propiedades #respuesta" ).html( data.html );
			})
			}else{
			jQuery( "#mme-filtros #nombre" ).val( "" );
			jQuery( "#mme-filtros #email" ).val( "" );
			jQuery( "#mme-filtros #telefono" ).val( "" );
			jQuery( "#mme-filtros #subject" ).val( "" );
		}
		return false;
	});
});
function operacion_propiedad( idSelect, idmunicipio, idestado, idcolonia, idoperacion ){
	jQuery( "#mme-filtros #"+idSelect ).html( '<option value="">--Load....---</option>' );
	var act = "tipo-operacion"
	if( idSelect == "mme-property-type" ){
		act = "tipo-propiedad"
	}
	var ajaxURL = ajax_var . url + inter + 'act=' + act + '&idMun=' + idmunicipio + '&idEdo=' + idestado
	if( idcolonia!="" )
	ajaxURL += '&idCol='+idcolonia
	if( idoperacion!="" )
	ajaxURL += '&idOp='+idoperacion
	jQuery.ajax({
		url:  ajaxURL,
		type: 'get',
		dataType: "json", 
		beforeSend: function(){ 
			jQuery( "#mme-filtros #"+idSelect ).html( '<option value="">--Load....---</option>' );
		},
		success:  function (response){
			var newarr = JSON.parse( response )   
			jQuery("#mme-filtros #"+idSelect).html( newarr.tipooperacion )
		}
	});
}
jQuery( function ( $ ) {
	$( '#tabs-nav a' ).on( 'click', function ( event ) {
		event.preventDefault();
		$( '.tab-active' ).removeClass( 'tab-active' );
		$( this ).parent().addClass( 'tab-active' );
		$( '#tabs-stage > div' ).hide();
		$( $( this ).attr( 'href' ) ).show();
	});
  $( '#tabs-nav a:first' ).trigger( 'click' ); // Default
});
