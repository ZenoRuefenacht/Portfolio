<?php
session_start();
include 'config.php';

function check_auth( $roles = [] ) {
    if ( !isset( $_SESSION[ 'user_id' ] ) || !in_array( $_SESSION[ 'role' ], $roles ) ) {
        header( 'Location: ../auth/login.php' );
        exit();
    }
}
?>
