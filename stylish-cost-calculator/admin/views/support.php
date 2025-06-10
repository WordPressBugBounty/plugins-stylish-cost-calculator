<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class SCCSupportPage {
	private $scc_icons;
    public function __construct() {
        //add_action('admin_menu', [$this, 'add_admin_menu']);
		$this->scc_icons = require SCC_DIR . '/assets/scc_icons/icon_rsrc.php';
        add_action('admin_enqueue_scripts', [$this, 'enqueue_scripts']);
        include_once(SCC_DIR . '/admin/controllers/formController.php');
        $this->render_support_page();
    }

    public function add_admin_menu() {
        add_submenu_page('scc-tabs', 'Support', 'Support', 'manage_options', 'scc-support', [$this, 'render_support_page']);
    }

    public function enqueue_scripts() {
        wp_enqueue_style('scc-admin-style');
        wp_enqueue_script('scc-admin-script');
    }

    public function render_support_page() {
        $system_info = $this->get_system_info();
        $license_status = $this->get_license_status();
        $license_key = $this->get_license_key();
        $site_url = get_site_url();
        $form_controller = new formController();
        $calculators = $form_controller->read();
        ?>
        <div class="scc-support-wrap">
            <!-- Tab Navigation -->
            <div class="scc-tab-navigation">
                <button class="scc-tab-button active" data-tab="support-form">
                    <span class="scc-icn-wrapper"><?php echo scc_get_kses_extended_ruleset( $this->scc_icons['help-circle'] ); ?></span>
                    Support Request
                </button>
                <button class="scc-tab-button" data-tab="help-tutorials">
                    <span class="scc-icn-wrapper"><?php echo scc_get_kses_extended_ruleset( $this->scc_icons['book-open'] ); ?></span>
                    Help & Tutorials
                </button>
                <button class="scc-tab-button" data-tab="diagnostics">
                    <span class="scc-icn-wrapper"><?php echo scc_get_kses_extended_ruleset( $this->scc_icons['settings'] ); ?></span>
                    Diagnostics & System Info
                </button>
            </div>

            <!-- Tab Content -->
            <div class="scc-tab-content">
                <!-- Support Form Tab -->
                <div id="support-form" class="scc-tab-panel active">
                    <form id="scc-support-form" method="post" action="">
                        <?php wp_nonce_field('scc_support_submit', 'scc_support_nonce'); ?>

                        <!-- Unified User & Issue Section -->
                        <div class="scc-form-section scc-card">
							<h2 class="scc-section-title">Support Request</h2>
							<p>Please fill out the form below to open a ticket. We will get back to you as soon as possible.</p>
                            <div class="scc-form-row">
                                <div class="scc-form-field">
                                    <label for="admin_name">Admin Name <span class="scc-required">*</span></label>
                                    <input type="text" class="scc-input" id="admin_name" name="admin_name" value="<?php echo esc_attr(wp_get_current_user()->display_name); ?>" required>
                                </div>
                                <div class="scc-form-field">
                                    <label for="admin_email">Email Address <span class="scc-required">*</span></label>
                                    <input type="email" class="scc-input" id="admin_email" name="admin_email" value="<?php echo esc_attr(wp_get_current_user()->user_email); ?>" required>
                                </div>
                            </div>
                            <div class="scc-form-row">
                                <div class="scc-form-field">
                                    <label for="issue_category">Issue Category <span class="scc-required">*</span></label>
                                    <select id="issue_category" name="issue_category" class="scc-input" style="width: 100%;min-width: 100%;" required>
                                        <option value="">Select Category</option>
                                        <option value="setup">Setup Issues</option>
                                        <option value="payment">Payment Gateway Problems</option>
                                        <option value="frontend">Frontend Display Issues</option>
                                        <option value="quote">Quote Generation Issues</option>
                                        <option value="license">License Problems</option>
                                        <option value="other">Other</option>
                                    </select>
                                </div>
                                <div class="scc-form-field">
                                    <label for="calculator_id">Calculator <span class="scc-required">*</span></label>
                                    <select id="calculator_id" name="calculator_id" class="scc-input" style="width: 100%;min-width: 100%;" required>
                                        <option value="">Select Calculator</option>
                                        <?php foreach ($calculators as $calculator): ?>
                                            <option value="<?php echo esc_attr($calculator->id); ?>">
                                                <?php echo esc_html($calculator->formname); ?> (ID: <?php echo esc_html($calculator->id); ?>)
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                            <div class="scc-form-row">
                                <div class="scc-form-field full-width">
                                    <label for="issue_subject">Issue Subject <span class="scc-required">*</span></label>
                                    <input type="text" class="scc-input" id="issue_subject" name="issue_subject" required>
                                </div>
                            </div>
                            <div class="scc-form-row">
                                <div class="scc-form-field full-width">
                                    <label for="issue_description">Issue Description <span class="scc-required">*</span></label>
                                    <textarea id="issue_description" name="issue_description" class="scc-input" rows="6" required></textarea>
                                    <div class="word-counter">Words: <span id="word_count">0</span></div>
                                </div>
                            </div>
                            <div class="scc-form-row">
                                <div class="scc-form-field full-width">
                                    <label for="scc-attachments">Attachments (screenshots, logs, etc.)</label>
                                    <input type="file" id="scc-attachments" name="scc_attachments[]" class="scc-input scc-file-input" multiple accept="image/*,application/pdf,.txt,.log,.zip">
                                    <small class="scc-file-help">You can attach images, PDFs, logs, or ZIP files. Max 5 files, 10MB each.</small>
                                </div>
                            </div>
                        </div>

                        <!-- Collapsible Site Information Section -->
                        <div class="scc-form-section scc-card scc-collapsed">
                            <button type="button" class="scc-collapse-toggle" data-target="#scc-site-info">Site Information <span class="scc-collapse-arrow">▼</span></button>
                            <div id="scc-site-info" class="scc-collapsible-content">
                                <div class="scc-form-row">
                                    <div class="scc-form-field">
                                        <label for="site_url">Site URL</label>
                                        <input type="url" class="scc-input" id="site_url" name="site_url" value="<?php echo esc_url($site_url); ?>" readonly>
                                    </div>
                                </div>
                                <div class="scc-form-row">
                                    <div class="scc-form-field">
                                        <label for="license_status">License Status</label>
                                        <input type="text" class="scc-input" id="license_status" name="license_status" value="<?php echo esc_attr($license_status); ?>" readonly>
                                        <input type="hidden" id="license_key" name="license_key" value="<?php echo esc_attr(get_option('df-scc-key-in-use', '')); ?>">
                                    </div>
                                </div>
                                <div class="scc-form-row">
                                    <div class="scc-form-field full-width">
                                        <label for="system_info">System Details</label>
                                        <textarea id="system_info" name="system_info" class="scc-input" rows="8" readonly><?php echo esc_textarea($system_info); ?></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="scc-form-actions">
                            <button type="submit" class="btn scc-btn-primary" style="color:white;">
                                <span class="scc-icn-wrapper"><?php echo scc_get_kses_extended_ruleset( $this->scc_icons['send'] ); ?></span>
                            Submit Support Request</button>
                        </div>
                    </form>
                </div>

                <!-- Help & Tutorials Tab -->
                <div id="help-tutorials" class="scc-tab-panel">
                    <div class="scc-help-tutorials-container">
                        <div class="scc-help-tutorials-header">
                            <h3>Help & Tutorials</h3>
                            <p>Access helpful resources, tutorials, and support options to get the most out of Stylish Cost Calculator.</p>
                        </div>
                        <div class="row m-0 mt-3">
                            <div style="">
                                <div class="col-md-12 col-xs-12 custom-btn-scc-top" style="min-height:500px;">
                                    <a class="scc-help-link-button" href="https://stylishcostcalculator.com/templates/" target="_blank">
                                        <span class="scc-icn-wrapper"><?php echo scc_get_kses_extended_ruleset( $this->scc_icons['monitor'] ); ?></span>
                                        Live Demos
                                    </a>
                                    <a class="scc-help-link-button" href="<?php echo esc_url( SCC_HELPDESK_LINKS['helpdesk-video-tutorials'] ); ?>" target="_blank">
                                        <span class="scc-icn-wrapper"><?php echo scc_get_kses_extended_ruleset( $this->scc_icons['play-circle'] ); ?></span>
                                        Video Tutorials
                                    </a>
                                    <a class="scc-help-link-button" href="<?php echo esc_url( SCC_HELPDESK_LINKS['helpdesk-home'] ); ?>" target="_blank">
                                        <span class="scc-icn-wrapper"><?php echo scc_get_kses_extended_ruleset( $this->scc_icons['book-open'] ); ?></span>
                                        User Guides
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Diagnostics Tab -->
                <div id="diagnostics" class="scc-tab-panel">
                    <div class="scc-diagnostics-container">
                        <div class="scc-diagnostics-header">
                            <h3>System Diagnostics</h3>
                            <p>This information helps us understand your server environment and identify potential issues.</p>
                        </div>
                        <div id="scc-diagnostics-content">
                            <?php $this->render_diagnostics(); ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <style>
            .scc-support-wrap {
                padding: 32px 0 0 0;
                max-width: 1000px;
                margin: 0 auto;
            }
            .scc-support-title {
                font-family: 'Poppins', Arial, sans-serif;
                font-size: 2rem;
                color: black;
                margin-bottom: 24px;
                font-weight: 700;
            }
            
            /* Tab Navigation */
            .scc-tab-navigation {
                display: flex;
                border-bottom: 2px solid #e3e6f0;
                margin-bottom: 24px;
                gap: 4px;
            }
            .scc-tab-button {
                background: #f8f9fc;
                border: 1.5px solid #e3e6f0;
                border-bottom: none;
                padding: 12px 24px;
                font-family: 'Poppins', Arial, sans-serif;
                font-size: 14px;
                font-weight: 500;
                color: #666;
                cursor: pointer;
                border-radius: 10px 10px 0 0;
                transition: all 0.2s;
                display: flex;
                align-items: center;
                gap: 8px;
            }
            .scc-tab-button:hover {
                background: #fff;
                color: #4f46e5;
            }
            .scc-tab-button.active {
                background: #fff;
                color: #4f46e5;
                border-color: #4f46e5;
                font-weight: 600;
                position: relative;
            }
            .scc-tab-button.active::after {
                content: '';
                position: absolute;
                bottom: -2px;
                left: 0;
                right: 0;
                height: 2px;
                background: #fff;
            }
            
            /* Tab Content */
            .scc-tab-content {
                position: relative;
            }
            .scc-tab-panel {
                display: none;
            }
            .scc-tab-panel.active {
                display: block;
            }
            
            /* Diagnostics Styles */
            .scc-diagnostics-container {
                background: #fff;
                border-radius: 18px;
                box-shadow: 0 2px 12px 0 rgba(26,35,126,0.07);
                border: 1.5px solid #e3e6f0;
                padding: 28px;
            }
            .scc-diagnostics-header h3 {
                font-size: 1.3rem;
                color: black;
                margin-bottom: 8px;
                font-weight: 600;
            }
            .scc-diagnostics-header p {
                color: #666;
                margin-bottom: 24px;
            }
            
            /* Help & Tutorials Styles */
            .scc-help-tutorials-container {
                background: #fff;
                border-radius: 18px;
                box-shadow: 0 2px 12px 0 rgba(26,35,126,0.07);
                border: 1.5px solid #e3e6f0;
                padding: 28px;
            }
            .scc-help-tutorials-header h3 {
                font-size: 1.3rem;
                color: black;
                margin-bottom: 8px;
                font-weight: 600;
            }
            .scc-help-tutorials-header p {
                color: #666;
                margin-bottom: 24px;
            }
            .custom-btn-scc-top {
                display: flex;
                flex-direction: column;
                gap: 20px;
                align-items: center;
                justify-content: center;
                padding: 40px 20px;
            }
            .scc-help-link-button {
                display: inline-block;
                background: var(--scc-color-primary);
                color: white !important;
                padding: 16px 32px;
                border-radius: 12px;
                text-decoration: none !important;
                font-family: 'Poppins', Arial, sans-serif;
                font-weight: 600;
                font-size: 16px;
                transition: all 0.3s ease;
                border: none;
                cursor: pointer;
                min-width: 200px;
                text-align: center;
                display: flex;
                align-items: center;
                justify-content: center;
                gap: 10px;
                min-width: 300px;
            }
            .scc-help-link-button:hover {
                transform: translateY(-2px);
                color: white !important;
                text-decoration: none !important;
            }
            .scc-help-link-button:active {
                transform: translateY(0);
            }
            .scc-help-link-button .scc-icn-wrapper {
                width: 20px;
                height: 20px;
                flex-shrink: 0;
            }
            .scc-help-link-button .scc-icn-wrapper svg {
                width: 100%;
                height: 100%;
            }
            
            /* Diagnostic content styles */
            #scc-diagnostics-content #border1 {
                border: 2px solid #e3e6f0;
                border-radius: 10px;
                background-color: #fff;
                padding: 15px;
                margin-left: 0;
                margin-bottom: 15px;
                box-shadow: 0 2px 8px 0 rgba(26,35,126,0.05);
                width: auto;
                max-width: none;
                display: flex;
                flex-wrap: wrap;
                gap: 10px;
            }
            #scc-diagnostics-content .dot {
                height: 16px;
                width: 16px;
                border-radius: 50%;
                display: inline-block;
                margin-left: 0;
                margin-right: 10px;
                flex-shrink: 0;
            }
            #scc-diagnostics-content .dot.green_dot {
                background-color: #22c55e;
            }
            #scc-diagnostics-content .dot.red_dot {
                background-color: #ef4444;
            }
            #scc-diagnostics-content .dot.orange_dot {
                background-color: #f97316;
            }
            #scc-diagnostics-content .yellow_dot {
                height: 16px;
                width: 16px;
                background-color: #eab308;
                border-radius: 50%;
                display: inline-block;
                margin-left: 0;
                margin-right: 10px;
                flex-shrink: 0;
            }
            #scc-diagnostics-content .gray_dot {
                height: 16px;
                width: 16px;
                background-color: #6b7280;
                border-radius: 50%;
                display: inline-block;
                margin-left: 0;
                margin-right: 10px;
                flex-shrink: 0;
            }
            #scc-diagnostics-content .scc-log-window {
                max-width: 100%;
                padding: 20px;
                border: 2px solid #e3e6f0;
                border-radius: 10px;
                background-color: #f8f9fc;
                margin-bottom: 15px;
                max-height: 400px;
                margin-left: 0;
                overflow-y: auto;
                font-family: 'Courier New', monospace;
                font-size: 13px;
                line-height: 1.4;
            }
            #scc-diagnostics-content .debug-items-wrapper {
                max-width: 100%;
                display: grid;
                grid-template-columns: 1fr 1fr;
                gap: 15px;
            }
            #scc-diagnostics-content .scc_title_bar {
                background: #4f46e5;
                color: white;
                padding: 12px 20px;
                border-radius: 8px;
                font-weight: 600;
                margin: 20px 0 10px 0;
                font-size: 1.1rem;
            }
            
            .scc-card {
                background: #fff;
                border-radius: 18px;
                box-shadow: 0 2px 12px 0 rgba(26,35,126,0.07);
                border: 1.5px solid #e3e6f0;
                margin-bottom: 24px;
                padding: 28px 28px 18px 28px;
            }
            .scc-section-title {
                font-size: 1.2rem;
                color: black;
                margin-bottom: 18px;
                font-weight: 600;
            }
            .scc-form-row {
                display: flex;
                gap: 20px;
                margin-bottom: 18px;
                flex-wrap: wrap;
            }
            .scc-form-field {
                flex: 1;
                min-width: 180px;
            }
            .scc-form-field.full-width {
                width: 100%;
                flex: 100%;
            }
            .scc-form-field label {
                display: block;
                margin-bottom: 6px;
                font-weight: 500;
                color: black;
            }
            .scc-input {
                width: 100%;
                padding: 10px 14px;
                border: 1.5px solid #e3e6f0;
                border-radius: 10px;
                font-size: 1rem;
                background: #f8f9fc;
                color: #222;
                transition: border-color 0.2s;
                font-family: 'Poppins', Arial, sans-serif;
				font-weight: 400;
            }
            .scc-input:focus {
                border-color: #4f46e5;
                outline: none;
                background: #fff;
            }

            .word-counter {
                margin-top: 5px;
                font-size: 12px;
                color: #666;
            }
            .scc-form-actions {
                margin-top: 20px;
                text-align: right;
            }
            .scc-collapse-toggle {
                background: none;
                border: none;
                color: var(--scc-color-primary);
                font-size: 1.1rem;
                font-weight: 600;
                cursor: pointer;
                margin-bottom: 10px;
                display: flex;
                align-items: center;
                gap: 8px;
                padding: 0;
            }
            .scc-collapse-arrow {
                font-size: 1.2em;
                transition: transform 0.2s;
            }
            .scc-collapsible-content {
                display: block;
                margin-top: 10px;
            }
            .scc-collapsed .scc-collapsible-content {
                display: none;
            }
            .scc-collapsed .scc-collapse-arrow {
                transform: rotate(-90deg);
            }
            .scc-file-input {
                background: #f8f9fc;
                border: 1.5px solid #e3e6f0;
                border-radius: 10px;
                padding: 10px 14px !important;
                font-size: 1rem;
                color: #222;
                margin-top: 4px;
            }
            .scc-file-help {
                font-size: 12px;
                color: #666;
                margin-top: 2px;
                display: block;
            }
            .scc-required {
                color: #e53935;
                font-weight: bold;
                margin-left: 2px;
            }
            .scc-input.scc-error {
                border-color: #e53935 !important;
                background: #fff0f0;
            }

            /* Responsive design for diagnostic cards */
            @media (max-width: 768px) {
                #scc-diagnostics-content .debug-items-wrapper {
                    grid-template-columns: 1fr;
                }
            }
        </style>

        <script>
        jQuery(document).ready(function($) {
            // Tab functionality
            $('.scc-tab-button').on('click', function() {
                var targetTab = $(this).data('tab');
                
                // Update active tab button
                $('.scc-tab-button').removeClass('active');
                $(this).addClass('active');
                
                // Update active tab panel
                $('.scc-tab-panel').removeClass('active');
                $('#' + targetTab).addClass('active');
            });

            // Word counter
            $('#issue_description').on('input', function() {
                var words = $(this).val().trim().split(/\s+/).filter(Boolean).length;
                $('#word_count').text(words);
            });

            // Collapse Site Information by default
            $('.scc-form-section.scc-card.scc-collapsed .scc-collapsible-content').hide();
            $('.scc-form-section.scc-card.scc-collapsed .scc-collapse-arrow').css('transform', 'rotate(-90deg)');
            // Collapsible section
            $('.scc-collapse-toggle').on('click', function() {
                var $section = $(this).closest('.scc-form-section');
                $section.toggleClass('scc-collapsed');
                $section.find('.scc-collapsible-content').slideToggle(200);
                var $arrow = $(this).find('.scc-collapse-arrow');
                $arrow.css('transform', $section.hasClass('scc-collapsed') ? 'rotate(-90deg)' : 'rotate(0deg)');
            });

            // Form submission
            $('#scc-support-form').on('submit', function(e) {
                e.preventDefault();
                var hasError = false;
                
                // Disable submit button and show loader
                var $submitBtn = $(this).find('button[type="submit"]');
                var originalBtnText = $submitBtn.html();
                $submitBtn.prop('disabled', true).html('<span class="scc-spinner"></span> Sending...');
                
                var formData = new FormData(this);
                formData.append('action', 'scc_submit_support_request');
                $.ajax({
                    url: ajaxurl,
                    type: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(response) {
                        // Reset button state
                        $submitBtn.prop('disabled', false).html(originalBtnText);
                        
                        if (response.success) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Support Request Sent!',
                                text: 'Thank you for contacting us. Our support team will get back to you soon.',
                                confirmButtonColor: '#4f46e5',
                                customClass: { popup: 'scc-swal-popup' }
                            });
                            $('#scc-support-form')[0].reset();
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Submission Failed',
                                text: response.data && response.data.message ? response.data.message : 'There was an error submitting your request. Please try again.',
                                confirmButtonColor: '#4f46e5',
                                customClass: { popup: 'scc-swal-popup' }
                            });
                        }
                    },
                    error: function() {
                        // Reset button state
                        $submitBtn.prop('disabled', false).html(originalBtnText);
                        
                        Swal.fire({
                            icon: 'error',
                            title: 'Submission Failed',
                            text: 'There was an error submitting your request. Please try again.',
                            confirmButtonColor: '#4f46e5',
                            customClass: { popup: 'scc-swal-popup' }
                        });
                    }
                });
            });
        });
        </script>
        <?php
    }

    private function get_system_info() {
        global $wpdb;
        $info = array(
            'WordPress Version' => get_bloginfo('version'),
            'PHP Version' => PHP_VERSION,
            'Server Software' => $_SERVER['SERVER_SOFTWARE'],
            'MySQL Version' => $wpdb->get_var("SELECT VERSION()"),
            'Memory Limit' => ini_get('memory_limit'),
            'Max Upload Size' => ini_get('upload_max_filesize'),
            'Max Post Size' => ini_get('post_max_size'),
            'Max Execution Time' => ini_get('max_execution_time'),
            'Active Theme' => wp_get_theme()->get('Name') . ' ' . wp_get_theme()->get('Version'),
            'Active Plugins' => $this->get_active_plugins()
        );
        $output = '';
        foreach ($info as $key => $value) {
            $output .= $key . ': ' . $value . "\n";
        }
        return $output;
    }

    private function get_active_plugins() {
        $active_plugins = get_option('active_plugins');
        $plugin_info = array();
        foreach ($active_plugins as $plugin) {
            $plugin_data = get_plugin_data(WP_PLUGIN_DIR . '/' . $plugin);
            $plugin_info[] = $plugin_data['Name'] . ' ' . $plugin_data['Version'];
        }
        return implode("\n", $plugin_info);
    }

    private function get_license_status() {
        $license_key = get_option('df-scc-key-in-use', '');
        
        if (empty($license_key)) {
            return 'Inactive';
        }

        // Prepare API request to EDD
        $url = 'https://members.stylishcostcalculator.com';
        $headers = [
            'user-agent' => 'SCC_Updater/' . home_url() . ';',
            'Accept' => 'application/json',
        ];

        $api_params = [
            'edd_action' => 'check_license',
            'license' => $license_key,
            'item_id' => defined('SCC_EDD_ITEM_ID') ? SCC_EDD_ITEM_ID : '',
            'url' => trailingslashit(home_url()),
        ];

        // Send request
        $response = wp_remote_get(
            add_query_arg($api_params, $url),
            [
                'timeout' => 20,
                'headers' => $headers,
            ]
        );

        if (is_wp_error($response)) {
            return 'Error';
        }

        $license_data = json_decode(wp_remote_retrieve_body($response));
        
        if (isset($license_data->license) && $license_data->license === 'valid') {
            return 'Active';
        }
        
        return 'Inactive';
    }

    private function get_license_key() {
        $license_key = get_option('df-scc-key-in-use', '');
        
        if (!empty($license_key)) {
            // Mask the license key for security
            return substr($license_key, 0, 4) . str_repeat('X', strlen($license_key) - 8) . substr($license_key, -4);
        }
        
        return '';
    }

    public function render_diagnostics() {
        global $wp_version, $wpdb;
        
        // Initialize diagnostic variables
        $results = [];
        $results['curl_available'] = in_array('curl', get_loaded_extensions());
        $test_char = defined('DB_CHARSET') ? DB_CHARSET : 'utf8';
        $postMaxAmt = (int)(str_replace('M', '', ini_get('post_max_size')));
        $php_version = phpversion();
        
        // Check mod_security
        ob_start();
        phpinfo(INFO_MODULES);
        $contents = ob_get_clean();
        $moduleAvailable = strpos($contents, 'mod_security') !== false;
        
        $plugin_conflicted = 0;
        $site_url = function_exists('site_url') ? site_url() : home_url();
        
        // Helper function for URL
        $get_current_url = function() {
            $protocol = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
            return $protocol . '://' . $_SERVER['HTTP_HOST'];
        };
        
        ?>
        <p style="padding-bottom: 20px; font-size: 1em;">Please action any items in red by emailing your admin or hosting company support. Don't worry about orange or yellow items.</p>
        
        <div class="debug-items-wrapper">
            <?php
            // Domain URL and Active Theme
            echo '<div id="border1">';
            echo '<span style="font-size:1em;font-weight:bold;">Domain URL:</span> ' . esc_html($get_current_url());
            echo '<br><span style="font-size:1em;font-weight:bold;">Active Theme:</span> ';
            if (function_exists('wp_get_theme')) {
                echo esc_html(wp_get_theme()->get('Name') . ' ' . wp_get_theme()->get('Version'));
            } else {
                echo 'Unknown';
            }
            echo '</div>';
            
            // SCC Version
            if (file_exists(SCC_DIR . '/stylish-cost-calculator-premium.php')) {
                if (function_exists('get_plugin_data')) {
                    $plugin_data = get_plugin_data(SCC_DIR . '/stylish-cost-calculator-premium.php');
                    if (isset($plugin_data['Version'])) {
                        echo '<div id="border1"><span class="dot green_dot"></span>';
                        echo '<span style="font-size:1em;font-weight:bold;">SCC Version:</span> ';
                        echo esc_html($plugin_data['Version']) . '</div>';
                    }
                }
            }
            
            // MySQL Version
            if ($wpdb) {
                $mysqlVersion = $wpdb->db_version();
                if ($mysqlVersion) {
                    echo '<div id="border1"><span class="dot green_dot"></span>';
                    echo '<span style="font-size:1em;font-weight:bold;">MySQL Version:</span> ';
                    echo esc_html($mysqlVersion) . '</div>';
                }
            }
            
            // CURL Library
            $curl_present = in_array('curl', get_loaded_extensions()) ? 'Yes' : 'No';
            echo '<div id="border1"><span class="dot green_dot"></span>';
            echo '<span style="font-size:1em;font-weight:bold;">CURL Library Present:</span> ';
            echo esc_html($curl_present) . '</div>';
            
            // PHP GD Extension
            if (in_array('gd', get_loaded_extensions())) {
                echo '<div id="border1"><span class="dot green_dot"></span>';
                echo '<span style="font-size:1em;font-weight:bold;">PHP GD extension:</span> available</div>';
            } else {
                echo '<div id="border1"><span class="yellow_dot"></span>';
                echo '<span style="font-size:1em;font-weight:bold;">PHP GD extension:</span> missing</div>';
            }
            
            // Security Plugins Detection
            $this->detect_and_display_security_plugins();
            
            // Cache/JS Optimizer Plugins Detection
            $this->detect_and_display_cache_plugins();
            
            // Database Checks
            $this->check_database_environment();
            
            // PHP Version Check
            if (version_compare($php_version, '7.2', '>=')) {
                echo '<div id="border1"><span class="dot green_dot"></span>';
                echo '<span style="font-size:1em;font-weight:bold;">PHP Version:</span> ';
                echo esc_html($php_version) . '</div>';
            } else {
                echo '<div id="border1"><span class="red_dot"></span>';
                echo '<span style="font-size:1em;font-weight:bold;">PHP Version:</span> ';
                echo esc_html($php_version) . '<br><br>';
                echo 'Change your PHP level in your cPanel, or ask your hosting company to do so.</div>';
            }
            
            // WordPress Version Check
            if (version_compare($wp_version, '5.6', '>=')) {
                echo '<div id="border1"><span class="dot green_dot"></span>';
                echo '<span style="font-size:1em;font-weight:bold;">WordPress Version:</span> ';
                echo esc_html($wp_version) . '</div>';
            } else {
                echo '<div id="border1"><span class="red_dot"></span>';
                echo '<span style="font-size:1em;font-weight:bold;">WordPress Version:</span> ';
                echo esc_html($wp_version) . '<br><br>';
                echo 'Your WordPress core version is really outdated. Please backup, then upgrade.</div>';
            }
            
            // MOD Security Check
            if (!$moduleAvailable) {
                echo '<div id="border1"><span class="dot green_dot"></span>';
                echo '<span style="font-size:1em;font-weight:bold;">MOD Security:</span> Off</div>';
            } else {
                echo '<div id="border1"><span class="red_dot"></span>';
                echo '<span style="font-size:1em;font-weight:bold;">MOD Security:</span> On<br>';
                echo 'Please contact your hosting provider to disable MOD Security.</div>';
            }
            
            // SMTP Plugin Check
            $this->check_smtp_plugins();
            
            // Plugin Conflicts Check
            $this->check_plugin_conflicts();
            
            // Charset Check
            if ($test_char == 'utf8mb4') {
                echo '<div id="border1"><span class="dot green_dot"></span>';
                echo '<span style="font-size:1em;font-weight:bold;">Charset:</span> ';
                echo esc_html($test_char) . '</div>';
            } elseif ($test_char == 'utf8') {
                echo '<div id="border1"><span class="yellow_dot"></span>';
                echo '<span style="font-size:1em;font-weight:bold;">Charset:</span> ';
                echo esc_html($test_char) . '<br><br>';
                echo 'Suggestion: You should edit the DB_CHARSET variable in your wp_config.php file to utf8mb4</div>';
            } else {
                echo '<div id="border1"><span class="dot orange_dot"></span>';
                echo '<span style="font-size:1em;font-weight:bold;">Charset:</span> ';
                echo esc_html($test_char) . '<br><br>';
                echo 'Warning: You should edit the DB_CHARSET variable in your wp_config.php file to utf8mb4</div>';
            }
            
            // Post Max Size Check
            if ($postMaxAmt > 20) {
                echo '<div id="border1"><span class="dot green_dot"></span>';
                echo '<span style="font-size:1em;font-weight:bold;">Maximum Allowed Post Data:</span> ';
                echo $postMaxAmt . 'M</div>';
            } else {
                echo '<div id="border1"><span class="yellow_dot"></span>';
                echo '<span style="font-size:1em;font-weight:bold;">Maximum Allowed Post Data:</span> ';
                echo $postMaxAmt . 'M<br><br>';
                echo "Warning: You should increase 'post_max_size' to some value greater than <b>20M</b>.</div>";
            }
            
            // License Status Check
            $license_status = $this->get_license_status();
            $license_color = ($license_status === 'Active') ? 'dot green_dot' : 'yellow_dot';
            echo '<div id="border1"><span class="' . $license_color . '"></span>';
            echo '<span style="font-size:1em;font-weight:bold;">License Status:</span> ';
            echo esc_html($license_status) . '</div>';
            
            // Commit Meta (if available)
            $this->display_commit_meta();
            ?>
        </div>
        
        <br>
        <p style="padding-bottom:20px;font-size: 1em">
            If you use CloudFlare, please make sure you have 
            <a target="_blank" href="https://developers.cloudflare.com/speed/optimization/content/rocket-loader/enable/">disabled Rocket Loader</a>.
        </p>
        
        <?php
        // Email Quote Log
        echo '<div class="scc_title_bar">Email Quote Log</div>';
        echo '<p style="font-size: 1em; margin-bottom: 10px;">This window shows the last 50 emails sent through the Email Quote Form.</p>';
        $this->display_logs('email-quote');
        
        // Update Services Log
        echo '<div class="scc_title_bar">Update Services Log</div>';
        echo '<p style="font-size: 1em; margin-bottom: 10px;">This window shows the last 50 logs from the Update Services.</p>';
        $this->display_logs('update-services');
    }
    
    private function detect_and_display_security_plugins() {
        $security_plugins = [
            'wordfence/wordfence.php' => 'WordFence - Please add Stylish Cost Calculator to exclusion list',
            'defender-security/wp-defender.php' => 'Defender Plugin - Please add Stylish Cost Calculator to exclusion list',
            'better-wp-security/better-wp-security.php' => 'iThemes Security - Add SCC to exclusion list if you have issues',
            'sucuri-scanner/sucuri.php' => 'Sucuri Plugin - Add SCC to exclusion list if you have issues',
            'all-in-one-wp-security-and-firewall/wp-security.php' => 'All In One WP Security - Add SCC to exclusion list if you have issues',
            'jetpack/jetpack.php' => 'Jetpack - Add SCC to exclusion list if you have frontend issues',
            'bulletproof-security/bulletproof-security.php' => 'Bulletproof Security - Add SCC to exclusion list',
            'security-ninja/security-ninja.php' => 'Security Ninja - Add SCC to exclusion list'
        ];
        
        foreach ($security_plugins as $plugin_path => $message) {
            if (function_exists('is_plugin_active') && is_plugin_active($plugin_path)) {
                echo '<div id="border1"><span class="dot orange_dot"></span>';
                echo '<span style="font-size:1em;font-weight:bold;">Security Plugin:</span> active<br><br>';
                echo esc_html($message) . '<br>If you are not experiencing a problem, ignore this message.</div>';
            }
        }
    }
    
    private function detect_and_display_cache_plugins() {
        $cache_plugins = [
            'wp-fastest-cache/wpFastestCache.php' => 'WP Fastest Cache',
            'autoptimize/autoptimize.php' => 'Autoptimize',
            'w3-total-cache/w3-total-cache.php' => 'W3 Total Cache',
            'wp-rocket/wp-rocket.php' => 'WP Rocket',
            'flying-press/flying-press.php' => 'FlyingPress',
            'perfmatters/perfmatters.php' => 'Perfmatters',
            'cache-enabler/cache-enabler.php' => 'Cache Enabler',
            'wp-super-cache/wp-cache.php' => 'WP Super Cache',
            'wp-super-minify/wp-super-minify.php' => 'WP Super Minify',
            'wp-smushit/wp-smush.php' => 'WP Smush',
            'rocket-lazy-load/rocket-lazy-load.php' => 'Rocket Lazy Load',
            'litespeed-cache/litespeed-cache.php' => 'LiteSpeed Cache',
            'sg-cachepress/sg-cachepress.php' => 'SiteGround Speed Optimizer'
        ];
        
        foreach ($cache_plugins as $plugin_path => $plugin_name) {
            if (function_exists('is_plugin_active') && is_plugin_active($plugin_path)) {
                echo '<div id="border1"><span class="yellow_dot"></span>';
                echo '<span style="font-size:1em;font-weight:bold;">Cache Plugin:</span> active<br><br>';
                echo 'You are using ' . esc_html($plugin_name) . '. If you have issues, please add Stylish Cost Calculator to the exclusion list.</div>';
            }
        }
    }
    
    private function check_database_environment() {
        global $wpdb;
        
        // Check for strict mode
        if ($wpdb) {
            $result = $wpdb->get_results('SELECT @@sql_mode AS sql_mode');
            if (!empty($result)) {
                $sql_mode = explode(',', $result[0]->sql_mode);
                if (in_array('STRICT_TRANS_TABLES', $sql_mode)) {
                    echo '<div id="border1"><span class="gray_dot"></span>';
                    echo '<span style="font-size:1em;font-weight:bold;">Database Strict Mode:</span> Enabled<br><br>';
                    echo 'You have strict transactional tables mode enabled in your MySQL software. Please disable it for best results.</div>';
                }
            }
        }
        
        // Check for missing tables
        if (defined('DF_SCC_TABLES') && $wpdb) {
            $tables = $wpdb->get_results('SHOW TABLES', ARRAY_A);
            $prefix = $wpdb->prefix;
            $existing_tables = array_map(function($table) use ($prefix) {
                $table_full_name = array_values($table)[0];
                return str_replace($prefix, '', $table_full_name);
            }, $tables);
            
            $required_tables = DF_SCC_TABLES;
            $missing_tables = array_diff($required_tables, $existing_tables);
            
            if (count($missing_tables) > 0) {
                echo '<div id="border1"><span class="red_dot"></span>';
                echo '<span style="font-size:1em;font-weight:bold;">Database Tables:</span> Missing<br><br>';
                echo 'The following tables are missing: ' . implode(', ', $missing_tables) . '. Please contact support to resolve this issue.</div>';
            }
        }
    }
    
    private function check_smtp_plugins() {
        if (function_exists('is_plugin_active')) {
            $smtp_active = is_plugin_active('wp-mail-smtp/wp_mail_smtp.php') || 
                          is_plugin_active('wp-mail-smtp-pro/wp_mail_smtp.php');
            
            if (!$smtp_active) {
                echo '<div id="border1"><span class="yellow_dot"></span>';
                echo '<span style="font-size:1em;font-weight:bold;">Email Delivery:</span><br><br>';
                echo 'Right now your site might be set up to send emails via PHP, this is not ideal and can cause your email quote PDF forms to be sent to your user\'s spam folder. We recommend installing the ';
                echo '<a href="https://en-ca.wordpress.org/plugins/wp-mail-smtp/" target="_blank">WP Mail SMTP plugin</a>.</div>';
            }
        }
    }
    
    private function check_plugin_conflicts() {
        $conflicting_plugins = [
            'siteorigin-panels/siteorigin-panels.php' => 'Page Builder by SiteOrigin',
            'beaver-builder-lite-version/fl-builder.php' => 'WordPress Page Builder – Beaver Builder',
            'wp-mail-logging/wp-mail-logging.php' => 'WP Mail Logging - May cause outgoing emails not to work',
            'enable-jquery-migrate-helper/enable-jquery-migrate-helper.php' => 'jQuery Migrate Helper - May cause frontend javascript not to work'
        ];
        
        $conflicts_found = 0;
        foreach ($conflicting_plugins as $plugin_path => $plugin_name) {
            if (function_exists('is_plugin_active') && is_plugin_active($plugin_path)) {
                $conflicts_found++;
                $color_class = (strpos($plugin_name, 'WP Mail Logging') !== false || strpos($plugin_name, 'jQuery') !== false) ? 'red_dot' : 'yellow_dot';
                
                echo '<div id="border1"><span class="' . $color_class . '"></span>';
                echo '<span style="font-size:1em;font-weight:bold;">Potential Conflict:</span> ';
                echo esc_html($plugin_name) . '<br><br>';
                
                if (strpos($plugin_name, 'Page Builder') !== false) {
                    echo 'Warning: You are using a page builder. Please make sure you are using the correct container size and widget to add our shortcode to.';
                } else {
                    echo 'Warning: This plugin may cause issues with Stylish Cost Calculator functionality.';
                }
                echo '</div>';
            }
        }
        
        if ($conflicts_found == 0) {
            echo '<div id="border1"><span class="dot green_dot"></span>';
            echo '<span style="font-size:1em;font-weight:bold;">Conflicted Plugins:</span> None</div>';
        }
    }
    
    private function display_commit_meta() {
        if (file_exists(SCC_DIR . '/commit_meta.json')) {
            $commit_meta = file_get_contents(SCC_DIR . '/commit_meta.json');
            $commit_meta = json_decode($commit_meta, true);
            
            if ($commit_meta) {
                $commit_hash = isset($commit_meta['commit_hash']) ? $commit_meta['commit_hash'] : '';
                $commit_time = isset($commit_meta['timestamp']) ? $commit_meta['timestamp'] : '';
                
                if ($commit_time) {
                    $date = new DateTime($commit_time, new DateTimeZone('Canada/Eastern'));
                    $commit_time = $date->format('d M Y H:i:s');
                }
                
                $gh_tree = 'https://github.com/DesignMike/stylish-cost-calculator-premium/tree/' . $commit_hash;
                
                echo '<div id="border1"><span class="dot green_dot"></span>';
                echo '<span style="font-size:1em;font-weight:bold;">Commit Meta:</span><br><br>';
                echo '<p>Commit Hash: <a target="_blank" href="' . esc_url($gh_tree) . '">' . esc_html($commit_hash) . '</a></p>';
                echo '<p>Commit Time: ' . esc_html($commit_time) . '</p>';
                echo '</div>';
            }
        }
    }
    
    private function display_logs($log_type) {
        if (function_exists('wp_upload_dir')) {
            $log_file = wp_upload_dir()['basedir'] . '/scc-logs/scc-' . $log_type . '.log';
            
            echo '<pre class="scc-log-window">';
            if (file_exists($log_file)) {
                $log_content = file_get_contents($log_file);
                $log_lines = explode("\n", $log_content);
                
                foreach ($log_lines as $line) {
                    if (strpos($line, 'ERROR') === 0) {
                        echo '<span style="color: red;">' . esc_html($line) . '</span><br>';
                    } elseif (strpos($line, 'SENT') === 0) {
                        echo '<span style="color: green;">' . esc_html($line) . '</span><br>';
                    } elseif (strpos($line, 'WARNING') === 0) {
                        echo '<span style="color: orange;">' . esc_html($line) . '</span><br>';
                    } else {
                        echo esc_html($line) . '<br>';
                    }
                }
            } else {
                echo 'No log file found.';
            }
            echo '</pre>';
        }
    }
}

new SCCSupportPage();

