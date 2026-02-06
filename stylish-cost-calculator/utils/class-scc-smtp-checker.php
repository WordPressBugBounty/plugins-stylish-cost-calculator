<?php

if ( !defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Centralized SMTP checker class
 * Provides SMTP plugin detection and configuration checking
 */
class SCC_SMTP_Checker {

    /**
     * Get comprehensive SMTP plugins list
     *
     * @return array List of SMTP plugins with their configuration check methods
     */
    public static function get_smtp_plugins_list() {
        return [
            // WP Mail SMTP (most popular)
            'wp-mail-smtp/wp_mail_smtp.php' => [
                'name'         => 'WP Mail SMTP',
                'pro'          => false,
                'config_check' => 'wp_mail_smtp',
            ],
            'wp-mail-smtp-pro/wp_mail_smtp.php' => [
                'name'         => 'WP Mail SMTP Pro',
                'pro'          => true,
                'config_check' => 'wp_mail_smtp',
            ],

            // Easy WP SMTP
            'easy-wp-smtp/easy-wp-smtp.php' => [
                'name'         => 'Easy WP SMTP',
                'pro'          => false,
                'config_check' => 'swpsmtp',
            ],

            // Post SMTP
            'post-smtp/postman-smtp.php' => [
                'name'         => 'Post SMTP',
                'pro'          => false,
                'config_check' => 'postman',
            ],

            // Mail Bank
            'mail-bank/mail-bank.php' => [
                'name'         => 'Mail Bank',
                'pro'          => false,
                'config_check' => 'mail_bank',
            ],

            // Fluent SMTP
            'fluent-smtp/fluent-smtp.php' => [
                'name'         => 'Fluent SMTP',
                'pro'          => false,
                'config_check' => 'fluent_smtp',
            ],

            // Gmail SMTP
            'gmail-smtp/main.php' => [
                'name'         => 'Gmail SMTP',
                'pro'          => false,
                'config_check' => 'gmail_smtp',
            ],

            // SendGrid
            'sendgrid-email-delivery-simplified/wpsendgrid.php' => [
                'name'         => 'SendGrid',
                'pro'          => false,
                'config_check' => 'sendgrid',
            ],

            // Mailgun
            'mailgun/mailgun.php' => [
                'name'         => 'Mailgun',
                'pro'          => false,
                'config_check' => 'mailgun',
            ],

            // Amazon SES
            'wp-amazon-ses/wp-amazon-ses.php' => [
                'name'         => 'WP Amazon SES',
                'pro'          => false,
                'config_check' => 'wp_amazon_ses',
            ],

            // SMTP Mailer
            'smtp-mailer/smtp-mailer.php' => [
                'name'         => 'SMTP Mailer',
                'pro'          => false,
                'config_check' => 'smtp_mailer',
            ],

            // WP SMTP
            'wp-smtp/wp-smtp.php' => [
                'name'         => 'WP SMTP',
                'pro'          => false,
                'config_check' => 'wp_smtp',
            ],

            // SMTP Mail
            'smtp-mail/smtp-mail.php' => [
                'name'         => 'SMTP Mail',
                'pro'          => false,
                'config_check' => 'smtp_mail',
            ],

            // WP Mail SMTP by MailPoet
            'mailpoet/mailpoet.php' => [
                'name'         => 'MailPoet (includes SMTP)',
                'pro'          => false,
                'config_check' => 'mailpoet',
            ],

            // WooCommerce SMTP
            'woocommerce-smtp/woocommerce-smtp.php' => [
                'name'         => 'WooCommerce SMTP',
                'pro'          => false,
                'config_check' => 'woocommerce_smtp',
            ],

            // WP Mail SMTP by WPForms
            'wpforms-lite/wpforms.php' => [
                'name'         => 'WPForms (includes SMTP)',
                'pro'          => false,
                'config_check' => 'wpforms',
            ],

            // Contact Form 7 SMTP
            'contact-form-7/wp-contact-form-7.php' => [
                'name'         => 'Contact Form 7 (with SMTP addon)',
                'pro'          => false,
                'config_check' => 'cf7_smtp',
            ],
        ];
    }

    /**
     * Check if an SMTP plugin is properly configured
     *
     * @param string $config_check The configuration check identifier
     *
     * @return bool True if the plugin is configured, false otherwise
     */
    public static function is_smtp_plugin_configured( $config_check ) {
        switch ( $config_check ) {
            case 'wp_mail_smtp':
                // Check WP Mail SMTP configuration
                $wp_mail_smtp_options = get_option( 'wp_mail_smtp', [] );

                return !empty( $wp_mail_smtp_options['mail']['mailer'] ) &&
                       $wp_mail_smtp_options['mail']['mailer'] !== 'mail';

            case 'swpsmtp':
                // Check Easy WP SMTP configuration
                $swpsmtp_options = get_option( 'swpsmtp_options', [] );

                return !empty( $swpsmtp_options['smtp_settings']['host'] ) &&
                       !empty( $swpsmtp_options['smtp_settings']['port'] );

            case 'postman':
                // Check Post SMTP configuration
                $postman_options = get_option( 'postman_options', [] );

                return !empty( $postman_options['transport_type'] ) &&
                       $postman_options['transport_type'] !== 'default';

            case 'mail_bank':
                // Check Mail Bank configuration
                $mail_bank_options = get_option( 'mail_bank_authentication', [] );

                return !empty( $mail_bank_options['hostname'] ) &&
                       !empty( $mail_bank_options['port'] );

            case 'fluent_smtp':
                // Check Fluent SMTP configuration
                $fluent_smtp_options = get_option( 'fluent_smtp_settings', [] );

                return !empty( $fluent_smtp_options['connections'] ) &&
                       is_array( $fluent_smtp_options['connections'] ) &&
                       count( $fluent_smtp_options['connections'] ) > 0;

            case 'gmail_smtp':
                // Check Gmail SMTP configuration
                $gmail_smtp_options = get_option( 'gmail_smtp_options', [] );

                return !empty( $gmail_smtp_options['smtp_host'] ) &&
                       !empty( $gmail_smtp_options['smtp_port'] );

            case 'sendgrid':
                // Check SendGrid configuration
                $sendgrid_options = get_option( 'sendgrid_settings', [] );

                return !empty( $sendgrid_options['api_key'] ) ||
                       ( !empty( $sendgrid_options['username'] ) && !empty( $sendgrid_options['password'] ) );

            case 'mailgun':
                // Check Mailgun configuration
                $mailgun_options = get_option( 'mailgun', [] );

                return !empty( $mailgun_options['apiKey'] ) && !empty( $mailgun_options['domain'] );

            case 'wp_amazon_ses':
                // Check Amazon SES configuration
                $amazon_ses_options = get_option( 'wp_amazon_ses_options', [] );

                return !empty( $amazon_ses_options['access_key_id'] ) &&
                       !empty( $amazon_ses_options['secret_access_key'] );

            case 'smtp_mailer':
                // Check SMTP Mailer configuration
                $smtp_mailer_options = get_option( 'smtp_mailer_options', [] );

                return !empty( $smtp_mailer_options['smtp_host'] ) &&
                       !empty( $smtp_mailer_options['smtp_port'] );

            case 'wp_smtp':
                // Check WP SMTP configuration
                $wp_smtp_options = get_option( 'wp_smtp_options', [] );

                return !empty( $wp_smtp_options['smtp_host'] ) &&
                       !empty( $wp_smtp_options['smtp_port'] );

            case 'smtp_mail':
                // Check SMTP Mail configuration
                $smtp_mail_options = get_option( 'smtp_mail_options', [] );

                return !empty( $smtp_mail_options['smtp_host'] ) &&
                       !empty( $smtp_mail_options['smtp_port'] );

            case 'mailpoet':
                // Check MailPoet configuration
                $mailpoet_options = get_option( 'mailpoet_settings', [] );

                return !empty( $mailpoet_options['mta']['method'] ) &&
                       $mailpoet_options['mta']['method'] !== 'PHPMail';

            case 'woocommerce_smtp':
                // Check WooCommerce SMTP configuration
                $woo_smtp_options = get_option( 'woocommerce_smtp_options', [] );

                return !empty( $woo_smtp_options['smtp_host'] ) &&
                       !empty( $woo_smtp_options['smtp_port'] );

            case 'wpforms':
                // Check WPForms SMTP configuration
                $wpforms_options = get_option( 'wpforms_settings', [] );

                return !empty( $wpforms_options['email']['smtp_host'] ) &&
                       !empty( $wpforms_options['email']['smtp_port'] );

            case 'cf7_smtp':
                // Check Contact Form 7 SMTP configuration
                $cf7_smtp_options = get_option( 'cf7_smtp_options', [] );

                return !empty( $cf7_smtp_options['smtp_host'] ) &&
                       !empty( $cf7_smtp_options['smtp_port'] );

            default:
                // For unknown plugins, assume they might be configured if they're active
                return true;
        }
    }

    /**
     * Get comprehensive SMTP status data
     *
     * @return array SMTP status data
     */
    public static function get_smtp_status_data() {
        if ( !function_exists( 'is_plugin_active' ) ) {
            return [
                'has_smtp' => false,
                'message'  => 'WordPress plugin functions not available',
                'plugins'  => [],
            ];
        }

        $smtp_plugins            = self::get_smtp_plugins_list();
        $active_smtp_plugins     = [];
        $configured_smtp_plugins = [];

        // Check for active SMTP plugins
        foreach ( $smtp_plugins as $plugin_path => $plugin_info ) {
            if ( is_plugin_active( $plugin_path ) ) {
                $active_smtp_plugins[] = $plugin_info;

                // Check if the plugin is properly configured
                if ( self::is_smtp_plugin_configured( $plugin_info['config_check'] ) ) {
                    $configured_smtp_plugins[] = $plugin_info;
                }
            }
        }

        // Determine status and message
        if ( empty( $active_smtp_plugins ) ) {
            return [
                'has_smtp'           => false,
                'message'            => 'No SMTP plugins detected. Your site might be set up to send emails via PHP, which is not ideal and can cause your email quote PDF forms to be sent to your user\'s spam folder.',
                'plugins'            => [],
                'configured_plugins' => [],
                'recommendation'     => 'We recommend installing the WP Mail SMTP plugin.',
                'help_url'           => SCC_HELPDESK_LINKS['troubleshoot-email-setup'] ?? '#',
            ];
        } else {
            $configured_count = count( $configured_smtp_plugins );
            $total_count      = count( $active_smtp_plugins );

            if ( $configured_count > 0 ) {
                return [
                    'has_smtp'           => true,
                    'is_configured'      => true,
                    'message'            => 'SMTP is configured and working properly.',
                    'plugins'            => $active_smtp_plugins,
                    'configured_plugins' => $configured_smtp_plugins,
                    'total_plugins'      => $total_count,
                    'configured_count'   => $configured_count,
                ];
            } else {
                return [
                    'has_smtp'           => true,
                    'is_configured'      => false,
                    'message'            => 'SMTP plugins are active but not properly configured.',
                    'plugins'            => $active_smtp_plugins,
                    'configured_plugins' => [],
                    'total_plugins'      => $total_count,
                    'configured_count'   => 0,
                    'help_url'           => SCC_HELPDESK_LINKS['troubleshoot-email-setup'] ?? '#',
                ];
            }
        }
    }
}
