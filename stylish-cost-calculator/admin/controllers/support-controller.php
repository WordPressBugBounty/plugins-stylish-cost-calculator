<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class SCC_Support_Controller {
    public function __construct() {
        add_action('wp_ajax_scc_submit_support_request', [$this, 'handle_support_request']);
    }

    public function handle_support_request() {
        // Verify nonce
        if (!isset($_POST['scc_support_nonce']) || !wp_verify_nonce($_POST['scc_support_nonce'], 'scc_support_submit')) {
            wp_send_json_error(['message' => 'Security check failed']);
            return;
        }

        // Validate required fields
        $required_fields = ['issue_category', 'issue_subject', 'issue_description'];
        foreach ($required_fields as $field) {
            if (empty($_POST[$field])) {
                wp_send_json_error(['message' => 'Please fill in all required fields']);
                return;
            }
        }

        // Sanitize and prepare data
        $data = [
            'admin_name' => sanitize_text_field($_POST['admin_name']),
            'admin_email' => sanitize_email($_POST['admin_email']),
            'site_url' => esc_url_raw($_POST['site_url']),
            'license_status' => sanitize_text_field($_POST['license_status']),
            'license_key' => sanitize_text_field($_POST['license_key']),
            'issue_category' => sanitize_text_field($_POST['issue_category']),
            'calculator_id' => intval($_POST['calculator_id']),
            'issue_subject' => sanitize_text_field($_POST['issue_subject']),
            'issue_description' => sanitize_textarea_field($_POST['issue_description']),
            'system_info' => sanitize_textarea_field($_POST['system_info'])
        ];

        // Get calculator details if selected
        if (!empty($data['calculator_id'])) {
            include_once(SCC_DIR . '/admin/controllers/formController.php');
            $form_controller = new formController();
            $calculator = $form_controller->read($data['calculator_id']);
            if ($calculator) {
                $data['calculator_name'] = $calculator->formname;
                //$data['calculator_frontend_url'] = get_permalink($calculator->id);
                $data['calculator_backend_url'] = admin_url('admin.php?page=scc_edit_items&id_form=' . $calculator->id);
            }
        }

        // Handle file attachments
        $attachments = [];
        if (!empty($_FILES['scc_attachments']['name'][0])) {
            $allowed_types = ['image/jpeg','image/png','image/gif','application/pdf','text/plain','text/x-log','application/zip'];
            $max_files = 5;
            $max_size = 10 * 1024 * 1024; // 10MB
            $file_count = count($_FILES['scc_attachments']['name']);
            if ($file_count > $max_files) {
                wp_send_json_error(['message' => 'You can upload up to 5 files only.']);
                return;
            }
            for ($i = 0; $i < $file_count; $i++) {
                $file_type = $_FILES['scc_attachments']['type'][$i];
                $file_size = $_FILES['scc_attachments']['size'][$i];
                if (!in_array($file_type, $allowed_types)) {
                    wp_send_json_error(['message' => 'Invalid file type uploaded.']);
                    return;
                }
                if ($file_size > $max_size) {
                    wp_send_json_error(['message' => 'Each file must be less than 10MB.']);
                    return;
                }
                $tmp_name = $_FILES['scc_attachments']['tmp_name'][$i];
                $name = $_FILES['scc_attachments']['name'][$i];
                $upload = wp_upload_bits($name, null, file_get_contents($tmp_name));
                if ($upload['error']) {
                    wp_send_json_error(['message' => 'Error uploading file: ' . $upload['error']]);
                    return;
                }
                $attachments[] = $upload['file'];
            }
        }
        $data['attachments'] = $attachments;
        
        // Send email to support
        $this->send_support_email($data);

        wp_send_json_success(['message' => 'Support request submitted successfully']);
    }

    private function send_support_email($data) {
        $scc_logo = SCC_URL . '/assets/images/scc-logo.png';
        
        // Send detailed email to support team
        $to_support = 'contact@stylishcostcalculator.on.crisp.email';
        $subject_support = '[SCC Support] ' . $data['issue_subject'];
        $headers_support = [
            'From: ' . $data['admin_name'] . ' <' . $data['admin_email'] . '>',
            'Content-Type: text/html; charset=UTF-8'
        ];
        
        $message_support = '<div style="font-family:Poppins,Arial,sans-serif;max-width:650px;margin:0 auto;background:#f6f8fa;padding:32px 28px 28px 28px;border-radius:16px;border:1.5px solid #e3e6f0;box-shadow:0 2px 12px 0 rgba(26,35,126,0.07);">';
        $message_support .= '<div style="text-align:center;margin-bottom:24px;">'
            . '<img src="' . $scc_logo . '" alt="Stylish Cost Calculator Logo" style="width:160px;height:auto;margin-bottom:16px;"><br>'
            . '<span style="display:inline-block;color:#000;padding:8px 22px;border-radius:8px;font-size:1.5rem;font-weight:700;letter-spacing:1px;">Support Request</span>'
            . '</div>';
        $message_support .= '<h2 style="color:#222;font-size:1.3rem;margin-bottom:10px;border-bottom:1px solid #e3e6f0;padding-bottom:6px;">User Information</h2>';
        $message_support .= '<table style="width:100%;margin-bottom:18px;font-size:15px;border-collapse:collapse;">';
        $message_support .= '<tr><td style="font-weight:600;width:160px;padding:6px 0;color:#4f46e5;">Admin Name:</td><td style="padding:6px 0;">' . esc_html($data['admin_name']) . '</td></tr>';
        $message_support .= '<tr><td style="font-weight:600;padding:6px 0;color:#4f46e5;">Email:</td><td style="padding:6px 0;">' . esc_html($data['admin_email']) . '</td></tr>';
        $message_support .= '<tr><td style="font-weight:600;padding:6px 0;color:#4f46e5;">Site URL:</td><td style="padding:6px 0;">' . esc_html($data['site_url']) . '</td></tr>';
        $message_support .= '<tr><td style="font-weight:600;padding:6px 0;color:#4f46e5;">License Status:</td><td style="padding:6px 0;">' . esc_html($data['license_status']) . '</td></tr>';
        $message_support .= '<tr><td style="font-weight:600;padding:6px 0;color:#4f46e5;">License Key:</td><td style="padding:6px 0;">' . (isset($data['license_key']) ? esc_html($data['license_key']) : '-') . '</td></tr>';
        $message_support .= '</table>';
        $message_support .= '<h2 style="color:#222;font-size:1.3rem;margin-bottom:10px;border-bottom:1px solid #e3e6f0;padding-bottom:6px;">Issue Details</h2>';
        $message_support .= '<table style="width:100%;margin-bottom:18px;font-size:15px;border-collapse:collapse;">';
        $message_support .= '<tr><td style="font-weight:600;width:160px;padding:6px 0;color:#4f46e5;">Category:</td><td style="padding:6px 0;">' . esc_html($data['issue_category']) . '</td></tr>';
        if (!empty($data['calculator_name'])) {
            $message_support .= '<tr><td style="font-weight:600;padding:6px 0;color:#4f46e5;">Calculator:</td><td style="padding:6px 0;">' . esc_html($data['calculator_name']) . ' (ID: ' . esc_html($data['calculator_id']) . ')</td></tr>';
            $message_support .= '<tr><td style="font-weight:600;padding:6px 0;color:#4f46e5;">Backend URL:</td><td style="padding:6px 0;">' . esc_html($data['calculator_backend_url']) . '</td></tr>';
        }
        $message_support .= '<tr><td style="font-weight:600;padding:6px 0;color:#4f46e5;">Subject:</td><td style="padding:6px 0;">' . esc_html($data['issue_subject']) . '</td></tr>';
        $message_support .= '<tr><td style="font-weight:600;padding:6px 0;color:#4f46e5;vertical-align:top;">Description:</td><td style="padding:6px 0;white-space:pre-line;">' . nl2br(esc_html($data['issue_description'])) . '</td></tr>';
        $message_support .= '</table>';
        $message_support .= '<h2 style="color:#222;font-size:1.3rem;margin-bottom:10px;border-bottom:1px solid #e3e6f0;padding-bottom:6px;">System Information</h2>';
        $message_support .= '<pre style="background:#fff;border-radius:8px;padding:14px 12px;font-size:13px;border:1px solid #e3e6f0;overflow-x:auto;max-width:100%;margin-bottom:18px;">' . esc_html($data['system_info']) . '</pre>';
        if (!empty($data['attachments'])) {
            $message_support .= '<div style="margin-top:18px;"><b style="color:#4f46e5;">Attachments:</b><ul style="margin:8px 0 0 18px;padding:0;">';
            foreach ($data['attachments'] as $file) {
                $message_support .= '<li style="font-size:14px;color:#222;">' . esc_html(basename($file)) . '</li>';
            }
            $message_support .= '</ul></div>';
        }
        $message_support .= '<div style="margin-top:32px;text-align:center;color:#aaa;font-size:13px;">Stylish Cost Calculator &mdash; Support Email</div>';
        $message_support .= '</div>';
        
        // Send detailed email to support team
        wp_mail($to_support, $subject_support, $message_support, $headers_support, $data['attachments']);
        
        // Send simplified confirmation email to user
        $to_user = $data['admin_email'];
        $subject_user = 'Support Request Confirmation - ' . $data['issue_subject'];
        $headers_user = [
            'From: Stylish Cost Calculator Support <noreply@stylishcostcalculator.com>',
            'Content-Type: text/html; charset=UTF-8'
        ];
        
        $message_user = '<div style="font-family:Poppins,Arial,sans-serif;max-width:650px;margin:0 auto;background:#f6f8fa;padding:32px 28px 28px 28px;border-radius:16px;border:1.5px solid #e3e6f0;box-shadow:0 2px 12px 0 rgba(26,35,126,0.07);">';
        $message_user .= '<div style="text-align:center;margin-bottom:24px;">'
            . '<img src="' . $scc_logo . '" alt="Stylish Cost Calculator Logo" style="width:160px;height:auto;margin-bottom:16px;"><br>'
            . '<span style="display:inline-block;color:#000;padding:8px 22px;border-radius:8px;font-size:1.5rem;font-weight:700;letter-spacing:1px;">Support Request Received</span>'
            . '</div>';
        $message_user .= '<div style="background:#fff;border-radius:12px;padding:24px;margin-bottom:20px;border:1px solid #e3e6f0;">';
        $message_user .= '<h2 style="color:#222;font-size:1.4rem;margin-bottom:16px;margin-top:0;">Hello ' . esc_html($data['admin_name']) . ',</h2>';
        $message_user .= '<p style="color:#555;font-size:16px;line-height:1.6;margin-bottom:16px;">Thank you for contacting Stylish Cost Calculator support. We have received your support request and our team will review it shortly.</p>';
        $message_user .= '<h3 style="color:#222;font-size:1.2rem;margin-bottom:12px;border-bottom:1px solid #e3e6f0;padding-bottom:6px;">Your Request Details</h3>';
        $message_user .= '<table style="width:100%;margin-bottom:18px;font-size:15px;border-collapse:collapse;">';
        $message_user .= '<tr><td style="font-weight:600;width:140px;padding:8px 0;color:#4f46e5;">Subject:</td><td style="padding:8px 0;">' . esc_html($data['issue_subject']) . '</td></tr>';
        $message_user .= '<tr><td style="font-weight:600;padding:8px 0;color:#4f46e5;">Category:</td><td style="padding:8px 0;">' . esc_html($data['issue_category']) . '</td></tr>';
        if (!empty($data['calculator_name'])) {
            $message_user .= '<tr><td style="font-weight:600;padding:8px 0;color:#4f46e5;">Calculator:</td><td style="padding:8px 0;">' . esc_html($data['calculator_name']) . '</td></tr>';
        }
        $message_user .= '<tr><td style="font-weight:600;padding:8px 0;color:#4f46e5;vertical-align:top;">Description:</td><td style="padding:8px 0;white-space:pre-line;">' . nl2br(esc_html($data['issue_description'])) . '</td></tr>';
        $message_user .= '</table>';
        $message_user .= '</div>';
        $message_user .= '<div style="background:#f0f7ff;border-radius:12px;padding:20px;margin-bottom:20px;border-left:4px solid #4f46e5;">';
        $message_user .= '<h3 style="color:#222;font-size:1.1rem;margin-bottom:12px;margin-top:0;">What happens next?</h3>';
        $message_user .= '<ul style="color:#555;font-size:14px;line-height:1.6;margin:0;padding-left:20px;">';
        $message_user .= '<li>Our support team will review your request within 24 hours</li>';
        $message_user .= '<li>You will receive a response via email with next steps or a solution</li>';
        $message_user .= '<li>For urgent issues, please check our <a href="https://help.stylishcostcalculator.com/en/" style="color:#4f46e5;text-decoration:none;">documentation</a></li>';
        $message_user .= '</ul>';
        $message_user .= '</div>';
        $message_user .= '<div style="text-align:center;margin-top:24px;">';
        $message_user .= '<p style="color:#666;font-size:14px;margin:0;">Need immediate help? Visit our <a href="https://help.stylishcostcalculator.com/en/" style="color:#4f46e5;text-decoration:none;">Help Center</a></p>';
        $message_user .= '</div>';
        $message_user .= '<div style="margin-top:32px;text-align:center;color:#aaa;font-size:13px;">Stylish Cost Calculator &mdash; Support Team</div>';
        $message_user .= '</div>';
        
        // Send confirmation email to user (without attachments)
        wp_mail($to_user, $subject_user, $message_user, $headers_user);
    }
}

new SCC_Support_Controller();
