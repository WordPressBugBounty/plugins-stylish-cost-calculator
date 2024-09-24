<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}
class SCC_Notifications {

    public $remote_notifications;
    private $scc_icons;
    private $type;
    public function __construct( $type, $data ) {
        $this->type                 = $type;
        $this->remote_notifications = $this->prepare_message( $data );
        $this->scc_icons            = require SCC_DIR . '/assets/scc_icons/icon_rsrc.php';
    }

    private function prepare_message( $data ) {
        $message = '';
        switch ( $this->type ) {
            case 'diag':
                $message = $this->process_diag_msg( $data );
                break;

            default:
                // code...
                break;
        }

        return $message;
    }
    public function process_diag_msg( $data ) {
        $notif = get_option( 'df_scc_notifications', [] );

        return $notif;
    }
    public function output() {
        $notifications = $this->remote_notifications;

        if ( empty( $notifications ) ) {
            return;
        }

        $notifications = $notifications['feed'];

        $notifications_html   = '';
        $current_class        = ' current';
        $content_allowed_tags = [
            'em'     => [],
            'strong' => [],
            'span'   => [
                'style' => [],
            ],
            'a'      => [
                'href'   => [],
                'target' => [],
                'rel'    => [],
            ],
        ];

        foreach ( $notifications as $notification ) {
            // Buttons HTML.
            $buttons_html = '';

            if ( ! empty( $notification['btns'] ) && is_array( $notification['btns'] ) ) {
                foreach ( $notification['btns'] as $btn_type => $btn ) {
                    $buttons_html .= sprintf(
                        '<a href="%1$s" class="btn btn-%2$s"%3$s>%4$s</a>',
                        ! empty( $btn['url'] ) ? esc_url( $btn['url'] ) : '',
                        $btn_type === 'main' ? 'primary' : 'secondary',
                        ! empty( $btn['target'] ) && $btn['target'] === '_blank' ? ' target="_blank" rel="noopener noreferrer"' : '',
                        ! empty( $btn['text'] ) ? sanitize_text_field( $btn['text'] ) : ''
                    );
                }
                $buttons_html = ! empty( $buttons_html ) ? '<div class="scc-notifications-buttons">' . $buttons_html . '</div>' : '';
            }

            // Notification HTML.
            $notifications_html .= sprintf(
                '<div class="scc-notifications-message%5$s" data-message-id="%4$s">
					<h3 class="card-title">%1$s</h3>
					<p class="card-text">%2$s</p>
					%3$s
				</div>',
                ! empty( $notification['title'] ) ? sanitize_text_field( $notification['title'] ) : '',
                ! empty( $notification['content'] ) ? wp_kses( $notification['content'], $content_allowed_tags ) : '',
                $buttons_html,
                ! empty( $notification['id'] ) ? esc_attr( sanitize_text_field( $notification['id'] ) ) : 0,
                $current_class
            );

            // Only first notification is current.
            $current_class = '';
        }

        if ( count( $notifications ) > 0 ) {
            ?>
		<div class="row px-3 mt-5 scc-no-gutter" style="">
			<div class="card p-0 scc-notifications-wrapper">
				<div class="card-header">
					Notifications
				</div>
				<div class="card-body" id="scc-notifications">
					<a class="scc-dismiss" title="Dismiss this message">
						<span class="scc-icn-wrapper"><?php echo scc_get_kses_extended_ruleset( $this->scc_icons['x-circle'] ); ?></span>
					</a>
					<div class="navigation">
						<?php if ( count( $notifications ) > 1 ) { ?>
						<a class="prev disabled">
							<span class="screen-reader-text">Previous message</span>
							<span aria-hidden="true">‹</span>
						</a>
						<a class="next">
							<span class="screen-reader-text">Next message"&gt;</span>
							<span aria-hidden="true">›</span>
						</a>
						<?php } ?>
					</div>
					<div class="notification-message-wrapper">
						<?php echo $notifications_html; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped?>
					</div>
				</div>
			</div>
		</div>
			<?php
        }
    }
    public function try_demo_banner() {
        ob_start();
        ?>
        <div id="premium-banner-promo" class="row ms-0 scc-background-transparent w-100 premium-banner">
            <div class="vstack p-3">
                <div class="header-wrapper d-flex">
                    <div class="message-container w-100">
                        <div class="text-center mx-auto">
                            <p class="cta d-inline fw-bold">Try The <span class="link-primary">Premium Version</span> Now. Access Our Demo Site In Seconds.</p>
                            <a href="https://stylishcostcalculator.com/test-drive-premium" target="_blank" class="btn btn-primary">
                                <span>Try The Calculator Builder</span>
                                <i class="scc-element-action-icon material-icons-outlined align-middle">arrow_forward</i>
                            </a>
                            <a class="ms-2" href="https://stylishcostcalculator.com/features/" target="_blank">
                                <span>Explore All Features</span>
                            </a>
                        </div>
                    </div>
                    <a class="scc-dismiss" title="Dismiss this message">
                        <button type="button" onclick="sccBackendUtils.skipPremiumDemoModal()" class="btn-close" aria-label="Close"></button>
                    </a>
                </div>
            </div>
        </div>
        <?php
        echo ob_get_clean(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
    }
}
