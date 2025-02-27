<?php
// Prevent direct access to the file.
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/*
 * Usando 'fafar_cf7crud_before_create' para enviar as 
 * reservas de auditório para a intranet.
*/
add_action( 'fafar_cf7crud_before_create', 'site_fafar_before_send_mail_handler', 10, 2 );

function site_fafar_before_send_mail_handler( $form_data, $contact_form ) {

    if( ! isset( $form_data['object_name'] ) ) return $form_data;

    if ( $form_data['object_name'] !== 'auditorium_reservation' ) return $form_data;

    error_log( print_r( '=============================>' , true ) );
    error_log( print_r( $form_data['data'] , true ) );

    $args = array(
        'method' => 'POST',
        'headers' => array( 'Content-Type' => 'application/json; charset=utf-8' ),
        'body'   => $form_data['data'],
    );

    $response = wp_remote_request( 'http://container_intranet/wp-json/intranet/v1/submissions/auditorium/reservation/', $args );

    if (is_wp_error($response)) {
        error_log( print_r( 'Request failed: ' . $response->get_error_message(), true ) );

        return array( 'far_prevent_submit' => true );
    }
    
    $body = wp_remote_retrieve_body($response);

    error_log( print_r( 'Response: ' . $body, true ) );

    return array( 'far_prevent_submit' => true );//return $form_data;

}