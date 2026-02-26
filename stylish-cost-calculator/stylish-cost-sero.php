<?php

class SccSero {

    private $str;
    private $a1;
    private $a2;

    public function __construct() {
        add_filter( 'cron_schedules', [ $this, 'add_three_hour_cron_schedule' ] );
    }

    public function add_three_hour_cron_schedule( $schedules ) {
        $schedules['scc_once_every_three_hours_schedule'] = [
            'interval' => 3 * HOUR_IN_SECONDS,
            'display'  => esc_html( 'Once every three hours' ),
        ];

        return $schedules;
    }

    public function init( $client ) {
        if ( $client ) {
            $version_data = $client->updater()->plugins_api_filter( null, 'plugin_information', (object) [ 'slug' => 'stylish-cost-calculator-premium' ] );

            if ( $version_data && version_compare( STYLISH_COST_CALCULATOR_PREMIUM_VERSION, $version_data->new_version, '<' ) ) {
                add_action( 'scc-edit-page', [ $this, 'update_notice' ] );
                add_action( 'admin_print_styles', [ $this, 'update_notice_admin_pages' ] );
            }
        }
    }

    public function get_args() {
        return [
            'type'        => 'submenu',
            'menu_title'  => 'License',
            'page_title'  => 'Stylish Cost Calculator Premium Settings',
            'menu_slug'   => 'stylish_cost_calculator_premium_settings',
            'parent_slug' => 'scc_edit_items',
        ];
    }

    public function getOpt() {
        $this->setA1( '716bcd9c-feeb-4028-b955-d323039bce07' );
        $this->setA2( 'Stylish Cost Calculator Premium' );
    }

    public function str( int $i ) {
        switch ( $i ) {
            case 0:
                return 'df_appsero_license';
                break;

            case 1:
                return 'df_scc_licensed';
                break;

            case 2:
                return 'df_scclk_opt';
                break;

            case 3:
                return 'df_scc_license_key';
                break;
        }
    }

    /**
     * Get the value of serverSts
     */
    public function getServerSts() {
        return $this->serverSts;
    }

    /**
     * Set the value of serverSts
     *
     * @return self
     */
    public function setServerSts( $serverSts ) {
        $this->serverSts = $serverSts;

        return $this;
    }

    /**
     * Get the value of str
     */
    public function getStr() {
        return $this->str;
    }

    /**
     * Set the value of str
     *
     * @return self
     */
    public function setStr( $str ) {
        $this->str = $str;

        return $this;
    }

    /**
     * Get the value of a1
     */
    public function getA1() {
        return $this->a1;
    }

    /**
     * Set the value of a1
     *
     * @return self
     */
    public function setA1( $a1 ) {
        $this->a1 = $a1;

        return $this;
    }

    /**
     * Get the value of a2
     */
    public function getA2() {
        return $this->a2;
    }

    /**
     * Set the value of a2
     *
     * @return self
     */
    public function setA2( $a2 ) {
        $this->a2 = $a2;

        return $this;
    }
}
