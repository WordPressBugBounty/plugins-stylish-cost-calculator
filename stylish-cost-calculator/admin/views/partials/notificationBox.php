<?php

add_action(
    'scc_render_notices',
    function ( $d ) {
        $notifications = new SCC_Notifications( 'diag', null );
        $notifications->output();
    }
);
add_action(
    'scc_render_try_demo_notices',
    function ( $d ) {
        $invokation         = intval( get_option( 'scc-skip-premium-demo-modal', 0 ) );
        $currnet_save_count = intval( get_option( 'df-scc-save-count', 0 ) );

        if ( $currnet_save_count >= $invokation ) {
            $notifications = new SCC_Notifications( 'try_demo', null );
            $notifications->try_demo_banner();
        }
    }
);
