/* global ajaxurl, HealthCheck */
jQuery( document ).ready(function( $ ) {
    $( 'body' ).on( 'click', '.health-check-action', function() {
        var data = {
            'action': 'health-check-site-status-action',
            '_wpnonce': HealthCheck.nonce.site_status_action,
            'status_action': $( this ).data( 'health-check-action' )
        };

        $.post(
            ajaxurl,
            data,
            function() {

            }
        );
    });
} );
