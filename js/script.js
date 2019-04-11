jQuery(document).ready( function($) {
	var $timestampdiv = $('#duetimestampdiv');
		
	// Edit publish time click.
	$timestampdiv.siblings('a.edit-timestamp').click( function( event ) {
		if ( $timestampdiv.is( ':hidden' ) ) {
			$timestampdiv.slideDown( 'fast', function() {
				$( '#ce_to_do_day').focus();
			} );
			$(this).hide();
		}
		event.preventDefault();
	});

	$timestampdiv.find('.clear-timestamp').click( function( event ) {
		$timestampdiv.slideUp('fast').siblings('a.edit-timestamp').show().focus();
		$("#ce_to_do_month").val('');
		$('#ce_to_do_day').val(''); 
		$('#ce_to_do_year').val(''); 
		$('#hidden_ce_to_do_clear').val('1'); 
		event.preventDefault();
	});

	// Cancel editing the publish time and hide the settings.
	$timestampdiv.find('.cancel-timestamp').click( function( event ) {
		$timestampdiv.slideUp('fast').siblings('a.edit-timestamp').show().focus();
		$('#ce_to_do_month').val($('#hidden_ce_to_do_month').val());
		$('#ce_to_do_day').val($('#hidden_ce_to_do_day').val());
		$('#ce_to_do_year').val($('#hidden_ce_to_do_year').val());
		$('#hidden_ce_to_do_clear').val('0'); 
		event.preventDefault();
	});

	// Save the changed timestamp.
	$timestampdiv.find('.save-timestamp').click( function( event ) { // crazyhorse - multiple ok cancels
		$timestampdiv.slideUp('fast');
		$timestampdiv.siblings('a.edit-timestamp').show().focus();
		event.preventDefault();
	});
});