<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}
$isSCCFreeVersion = defined( 'STYLISH_COST_CALCULATOR_VERSION' );
$scc_icons        = require SCC_DIR . '/assets/scc_icons/icon_rsrc.php';
$scc_screen       = get_current_screen();
?>
<style>
	.scc-smiling-loader * {
		border: 0;
		box-sizing: border-box;
		margin: 0;
		padding: 0;
	}
	.scc-background-transparent{
		background-color: transparent;
	}
	.scc-background-primary{
		background-color: #314af3;
	}
	.scc-main-header-container{
		border-radius: 10px;
		filter: drop-shadow(0px 0px 10px rgba(0, 0, 0, 0.11));
		display: inline-flex;
		z-index:1;
	}
	.scc-header-logo-col{
		border-top-left-radius: 9px;
		border-bottom-left-radius: 9px;
		display: inline-flex;
		position: relative;
		height: 110px;
		background: white;
	}
	.scc-header-ellipse{
		position: absolute;
		height: 100%;
		top: 0;
		right: -45px;
	}
	.scc-header-menu-col{
		display: inline-flex;
    	flex-direction: row;
    	flex: auto;
    	justify-content: space-between;
		height: 110px;
	}
	.scc-page-title{
		font-size: 28px;
    font-weight: 700;
		line-height: 1.3em;
	}
	.scc-page-desc{
		font-size: 20px;
		font-weight: 400;
	
	}
	.scc-page-name-and-desc{
		padding-left: 70px !important;
		color: white;
		display: flex;
		align-items: center;
	}
	.scc-header-menu-col .scc-top-nav-container .scc-icn-wrapper svg {
    	background-color: transparent;
	}
	.scc-custom-version-info{
		display: flex;
		align-items: center;
		z-index: 1;
	}
	.scc-smiling-loader:root {
		--hue: 223;
		--bg: hsl(var(--hue),90%,90%);
		--fg: hsl(var(--hue),90%,10%);
		--trans-dur: 0.3s;
		font-size: calc(16px + (20 - 16) * (100vw - 320px) / (1280 - 320));
	}
	#scc-editing-area-smiling-loading .smiley {
		position: absolute;
		height: auto;
		top: calc(50%);
		left: calc(50%);
		padding: 10px;
	}
	.scc-smiling-loader .smiley__eye1,
	.scc-smiling-loader .smiley__eye2,
	.scc-smiling-loader .smiley__mouth1,
	.scc-smiling-loader .smiley__mouth2 {
		animation: eye1 3s ease-in-out infinite;
	}
	.scc-smiling-loader .smiley__eye1,
	.scc-smiling-loader .smiley__eye2 {
		transform-origin: 64px 64px;
	}
	.scc-smiling-loader .smiley__eye2 {
		animation-name: eye2;
	}
	.scc-smiling-loader .smiley__mouth1 {
		animation-name: mouth1;
	}
	.scc-smiling-loader .smiley__mouth2 {
		animation-name: mouth2;
		visibility: hidden;
	}

	/* Animations */
	@keyframes eye1 {
		from {
			transform: rotate(-260deg) translate(0,-56px);
		}
		50%,
		60% {
			animation-timing-function: cubic-bezier(0.17,0,0.58,1);
			transform: rotate(-40deg) translate(0,-56px) scale(1);
		}
		to {
			transform: rotate(225deg) translate(0,-56px) scale(0.35);
		}
	}
	@keyframes eye2 {
		from {
			transform: rotate(-260deg) translate(0,-56px);
		}
		50% {
			transform: rotate(40deg) translate(0,-56px) rotate(-40deg) scale(1);
		}
		52.5% {
			transform: rotate(40deg) translate(0,-56px) rotate(-40deg) scale(1,0);
		}
		55%,
		70% {
			animation-timing-function: cubic-bezier(0,0,0.28,1);
			transform: rotate(40deg) translate(0,-56px) rotate(-40deg) scale(1);
		}
		to {
			transform: rotate(150deg) translate(0,-56px) scale(0.4);
		}
	}
	@keyframes eyeBlink {
		from,
		25%,
		75%,
		to {
			transform: scaleY(1);
		}
		50% {
			transform: scaleY(0);
		}
	}
	@keyframes mouth1 {
		from {
			animation-timing-function: ease-in;
			stroke-dasharray: 0 351.86;
			stroke-dashoffset: 0;
		}
		25% {
			animation-timing-function: ease-out;
			stroke-dasharray: 175.93 351.86;
			stroke-dashoffset: 0;
		}
		50% {
			animation-timing-function: steps(1,start);
			stroke-dasharray: 175.93 351.86;
			stroke-dashoffset: -175.93;
			visibility: visible;
		}
		75%,
		to {
			visibility: hidden;
		}
	}
	@keyframes mouth2 {
		from {
			animation-timing-function: steps(1,end);
			visibility: hidden;
		}
		50% {
			animation-timing-function: ease-in-out;
			visibility: visible;
			stroke-dashoffset: 0;
		}
		to {
			stroke-dashoffset: -351.86;
		}
	}

	div#scc-editing-area-smiling-loading {
        height: 100%;
        width: 0;
        position: fixed;
        z-index: 10;
        top: 0;
        left: 0;
        cursor: wait;
        background-color: rgba(27, 24, 24, 0.92);
        overflow-x: hidden;
        transition: 0.5s;
	}
	#scc-editing-area-loading {
		height: 100%;
		width: 0;
		position: fixed;
		z-index: 1;
		top: 0;
		left: 0;
		cursor: wait;
		background-color: rgba(27, 24, 24, 0.92);
		overflow-x: hidden;
		transition: 0.5s;
	}

	#scc-editing-area-loading .center {
		position: absolute;
		height: auto;
		width: 50%;
		top: calc(50% - 20%);
		left: calc(50% - 20%);
		padding: 10px;
	}

	#scc-editing-area-loading .sk-chase {
		width: 40px;
		height: 40px;
		left: calc(65% - 20%);
		position: relative;
		animation: scc-sk-chase 2.5s infinite linear both;
	}

	#scc-editing-area-loading .sk-chase-dot {
		width: 100%;
		height: 100%;
		position: absolute;
		left: 0;
		top: 0;
		animation: scc-sk-chase-dot 2.0s infinite ease-in-out both;
	}

	#scc-editing-area-loading .sk-chase-dot:before {
		content: '';
		display: block;
		width: 25%;
		height: 25%;
		background-color: #fff;
		border-radius: 100%;
		animation: scc-sk-chase-dot-before 2.0s infinite ease-in-out both;
	}

	#scc-editing-area-loading .sk-chase-dot:nth-child(1) {
		animation-delay: -1.1s;
	}

	#scc-editing-area-loading .sk-chase-dot:nth-child(2) {
		animation-delay: -1.0s;
	}

	#scc-editing-area-loading .sk-chase-dot:nth-child(3) {
		animation-delay: -0.9s;
	}

	#scc-editing-area-loading .sk-chase-dot:nth-child(4) {
		animation-delay: -0.8s;
	}

	#scc-editing-area-loading .sk-chase-dot:nth-child(5) {
		animation-delay: -0.7s;
	}

	#scc-editing-area-loading .sk-chase-dot:nth-child(6) {
		animation-delay: -0.6s;
	}

	#scc-editing-area-loading .sk-chase-dot:nth-child(1):before {
		animation-delay: -1.1s;
	}

	#scc-editing-area-loading .sk-chase-dot:nth-child(2):before {
		animation-delay: -1.0s;
	}

	#scc-editing-area-loading .sk-chase-dot:nth-child(3):before {
		animation-delay: -0.9s;
	}

	#scc-editing-area-loading .sk-chase-dot:nth-child(4):before {
		animation-delay: -0.8s;
	}

	#scc-editing-area-loading .sk-chase-dot:nth-child(5):before {
		animation-delay: -0.7s;
	}

	#scc-editing-area-loading .sk-chase-dot:nth-child(6):before {
		animation-delay: -0.6s;
	}
	.scc-icn-wrapper svg{
		height: 18px;
		width: 18px;
	}

	@keyframes scc-sk-chase {
		100% {
			transform: rotate(360deg);
		}
	}

	@keyframes scc-sk-chase-dot {

		80%,
		100% {
			transform: rotate(360deg);
		}
	}

	@keyframes scc-sk-chase-dot-before {
		50% {
			transform: scale(0.4);
		}

		100%,
		0% {
			transform: scale(1.0);
		}
	}
</style>
<div id="scc-editing-area-smiling-loading" class="scc-smiling-loader" style="width:100%">
	<svg role="img" aria-label="Mouth and eyes come from 9:00 and rotate clockwise into position, right eye blinks, then all parts rotate and merge into 3:00" class="smiley" viewBox="0 0 128 128" width="128px" height="128px">
		<defs>
			<clipPath id="smiley-eyes">
				<circle class="smiley__eye1" cx="64" cy="64" r="8" transform="rotate(-40,64,64) translate(0,-56)" />
				<circle class="smiley__eye2" cx="64" cy="64" r="8" transform="rotate(40,64,64) translate(0,-56)" />
			</clipPath>
			<linearGradient id="smiley-grad" x1="0" y1="0" x2="0" y2="1">
				<stop offset="0%" stop-color="#000" />
				<stop offset="100%" stop-color="#fff" />
			</linearGradient>
			<mask id="smiley-mask">
				<rect x="0" y="0" width="128" height="128" fill="url(#smiley-grad)" />
			</mask>
		</defs>
		<g stroke-linecap="round" stroke-width="12" stroke-dasharray="175.93 351.86">
			<g>
				<rect fill="hsl(193,90%,50%)" width="128" height="64" clip-path="url(#smiley-eyes)" />
				<g fill="none" stroke="hsl(193,90%,50%)">
					<circle class="smiley__mouth1" cx="64" cy="64" r="56" transform="rotate(180,64,64)" />
					<circle class="smiley__mouth2" cx="64" cy="64" r="56" transform="rotate(0,64,64)" />
				</g>
			</g>
			<g mask="url(#smiley-mask)">
				<rect fill="hsl(223,90%,50%)" width="128" height="64" clip-path="url(#smiley-eyes)" />
				<g fill="none" stroke="hsl(223,90%,50%)">
					<circle class="smiley__mouth1" cx="64" cy="64" r="56" transform="rotate(180,64,64)" />
					<circle class="smiley__mouth2" cx="64" cy="64" r="56" transform="rotate(0,64,64)" />
				</g>
			</g>
		</g>
	</svg>
</div>
<script>
	jQuery(document).ready(function () {
		jQuery('#scc-editing-area-smiling-loading').remove()
	})
</script>
<?php if ( $scc_screen->base === 'admin_page_scc_edit_items' ) {
    do_action( 'scc_render_try_demo_notices' );
} ?>
<div class="row ms-0 align-items-center scc-background-transparent p-4 justify-content-center w-100">
	<div class="scc-background-primary scc-main-header-container align-items-center col-12 mx-auto px-0 w-100">
		<div class="scc-header-logo-col bg-white p-3">
			<img class="scc-header-ellipse" src="<?php
                    echo esc_url( SCC_URL . 'assets/images/scc-ellipse.svg' ); ?>
	" alt="">
			<div class="scc-custom-version-info align-middle">
				<a href="https://stylishcostcalculator.com/" class="scc-header">
					<img src="
						<?php
                        echo esc_url( SCC_URL . 'assets/images/scc-logo.png' );

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}
?>
									" class="img-responsive1" style="padding-bottom:20px;max-width: 200px" alt="Image">
				</a>
				<span class="scc_plug_ver">
					<?php
// $opt = get_option('df_scclk_opt');
                    if ( $isSCCFreeVersion ) {
                        echo 'Free';
                    } else {
                        echo 'Premium';
                    }
?>
				</span>
			</div>
		</div>
		<div class="scc-header-menu-col scc-navbar p-3">
		<div class="scc-page-name-and-desc">
				<div>
				<?php
                switch ( $scc_screen->base ) {
                    case 'stylish-cost-calculator_page_scc-quote-management-screen':
                        $quotes_from_id = 0;

                        if ( isset( $_GET['id'] ) ) {
                            $quotes_from_id = absint( $_GET['id'] );
                        }

                        echo '<div class="scc-page-title">Quotes & Leads Dashboard</div>';

                        if ( $quotes_from_id !== 0 ) {
                            global $wpdb;
                            $name = $wpdb->get_results( $wpdb->prepare( "SELECT formname FROM {$wpdb->prefix}df_scc_forms WHERE id = %d", $quotes_from_id ) );
                            echo '<div class="scc-page-desc">' . esc_html( $name[0]->formname ) . '</div>';
                        } else {
                            echo '<div class="scc-page-desc w-100">Quote Management Dashboard</div>';
                        }
                        break;

                    case 'stylish-cost-calculator_page_scc-global-settings':
                        echo '<div class="scc-page-title">Global Settings</div>';
                        break;

                    case 'stylish-cost-calculator_page_stylish_cost_calculator_license':
                        echo '<div class="scc-page-title">License Settings</div>';
                        break;

                    case 'stylish-cost-calculator_page_scc-license-help':
                        echo '<div class="scc-page-title">Member\'s Portal</div>';
                        break;

                    case 'stylish-cost-calculator_page_scc-coupons-management':
                        echo '<div class="scc-page-title">Coupon Generator & Editor (Premium Feature)</div>';
                        break;

                    case 'stylish-cost-calculator_page_scc-diagnostics':
                        echo '<div class="scc-page-title">Diagnostics</div>';
                        break;

                    case 'stylish-cost-calculator_page_scc-help':
                        echo '<div class="scc-page-title">Help & Tutorials</div>';
                        break;

                    case 'stylish-cost-calculator_page_scc-sms-quotes-dashboard':
                        echo '<div class="scc-page-title">SMS Dashboard</div>';
                        break;

                    case 'stylish-cost-calculator_page_scc-list-all-calculator-forms':
                        echo '<div class="scc-page-title">All Calculator Forms</div>';
                        break;
                }
?>
				</div>
				
			</div>
			<div class="scc-top-nav-container">
				<ul class="scc-edit-nav-items-2"></ul>
				<ul class="scc-edit-nav-items">
				<?php
    if ( ! empty( $f1 ) ) {
        ?>
						<li><a class="scc-nav-with-icons" onclick="event.preventDefault();" href="#" data-setting-tooltip-type="quote-screen-tt" data-bs-original-title="" title=""><i class="fas fa-list-ul"></i> View Quotes</a></li>
						<?php
    }
?>
				<?php if ( isset( $_REQUEST['page'] ) && $_REQUEST['page'] === 'scc_edit_items' && isset( $_REQUEST['id_form'] ) ) { ?>
					<li class="dropdown ">
						<a class="dropdown-toggle scc-nav-with-icons" href="#" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
							<span class="scc-icn-wrapper"><?php echo scc_get_kses_extended_ruleset( $scc_icons['navigation'] ); ?></span>
							Guided Tours <span class="caret"></span>
						</a>
						<ul class="dropdown-menu scc-multilevel-dropdown-menu">
							<li><a class="dropdown-item scc-calculator-tour-link" data-tour-type="editing-page" href="#"><span class="scc-icn-wrapper"><?php echo scc_get_kses_extended_ruleset( $scc_icons['navigation'] ); ?></span> Knowing the editing page</a></li>
							<li><a class="dropdown-item scc-calculator-tour-link" data-tour-type="font-settings" href="#"><span class="scc-icn-wrapper"><?php echo scc_get_kses_extended_ruleset( $scc_icons['navigation'] ); ?> Customizing Font Settings</a></li>
							<li><a class="dropdown-item scc-calculator-tour-link" data-tour-type="calculator-settings" href="#"><span class="scc-icn-wrapper"><?php echo scc_get_kses_extended_ruleset( $scc_icons['navigation'] ); ?> Customizing Calculator Settings</a></li>
							<li><a class="dropdown-item scc-calculator-tour-link" data-tour-type="wordings" href="#"><span class="scc-icn-wrapper"><?php echo scc_get_kses_extended_ruleset( $scc_icons['navigation'] ); ?> Customizing Wordings</a></li>
							<li><a class="dropdown-item scc-calculator-tour-link" data-tour-type="email-quote-settings" href="#"><span class="scc-icn-wrapper"><?php echo scc_get_kses_extended_ruleset( $scc_icons['navigation'] ); ?> Customizing Email Quote Form</a></li>
							<li><a class="dropdown-item scc-calculator-tour-link" data-tour-type="payment-options" href="#"><span class="scc-icn-wrapper"><?php echo scc_get_kses_extended_ruleset( $scc_icons['navigation'] ); ?> Setup Payment Options</a></li>
						</ul>
					</li>
					<?php } ?>
					<?php if ( $scc_screen->base === 'toplevel_page_scc-tabs' ) { ?>
					<li><a class="scc-nav-with-icons"
							href="<?php echo admin_url( 'admin.php?page=scc-list-all-calculator-forms' ); ?>">
							<span class="scc-icn-wrapper"><?php echo scc_get_kses_extended_ruleset( $scc_icons['edit'] ); ?></span>
							All Forms</a>
					</li>
					<li><a class="scc-nav-with-icons"
							href="<?php echo admin_url( 'admin.php?page=scc-global-settings' ); ?>">
							<span class="scc-icn-wrapper"><?php echo scc_get_kses_extended_ruleset( $scc_icons['settings'] ); ?></span>
							Global Settings</a>
					</li>
					<?php } ?>
					<li class="dropdown">
						<a class="dropdown-toggle scc-nav-with-icons" data-bs-toggle="dropdown" role="button"
							aria-haspopup="true" aria-expanded="false"><span class="scc-icn-wrapper <?php echo ( $scc_screen->base === 'toplevel_page_scc-tabs' ) ? 'scc-margin-menu' : ''; ?>"><?php echo scc_get_kses_extended_ruleset( $scc_icons['life-buoy'] ); ?></span>Support <span
								class="caret"></span></a>
						<ul class="dropdown-menu scc-multilevel-dropdown-menu">
							<li><a class="dropdown-item" target="_blank"
									href="<?php echo esc_url(SCC_HELPDESK_LINKS['helpdesk-home']);?>">User Guides</a>
							</li>
							<li><a class="dropdown-item" target="_blank"
									href="<?php echo esc_url(SCC_HELPDESK_LINKS['helpdesk-video-tutorials']);?>">Video
									Guides</a></li>
							<li><a class="dropdown-item" target="_blank"
									href="<?php echo esc_url( admin_url( 'admin.php?page=scc-diagnostics' ) ); ?>">Diagnostic</a>
							</li>
							<li><a class="dropdown-item" target="_blank"
									href="<?php echo esc_url(SCC_HELPDESK_LINKS['helpdesk-troubleshooting']);?>">Troubleshooting</a>
							</li>
							<li><a class="dropdown-item" target="_blank" href="https://stylishcostcalculator.com/support/">Contact Support</a>
							</li>
							<li><a class="dropdown-item" target="_blank" href="https://members.stylishcostcalculator.com/">Member's Portal</a>
							</li>
						</ul>
					</li>
				</ul>
				<?php if ( isset( $_REQUEST['id_form'] ) || $scc_screen->base === 'toplevel_page_scc-tabs'  ) { ?>
				<a class="text-decoration-none text-white" href="<?php echo admin_url(); ?>">
					<button class="btn btn-default py-2 me-3">
						<span class="scc-b-has-icon-left">WP Dashboard</span>
						<svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-corner-down-left"><polyline points="9 10 4 15 9 20"></polyline><path d="M20 4v7a4 4 0 0 1-4 4H4"></path></svg>
					</button>
				</a>
				<?php }

				if ( $scc_screen->base === 'stylish-cost-calculator_page_scc-list-all-calculator-forms' ) {
				    printf(
				        '<div class="row m-0"><div class="row m-0 col-md-12"><a class="text-decoration-none text-white" href="%s"><button class="btn btn-default py-2 me-3" ><span class="scc-b-has-icon-left">%s</span><span class="scc-icn-wrapper" id="ssc-nav-icon-wrapper"> %s</span></button></a></div></div>',
				        menu_page_url( 'scc-tabs', false ),
				        __( 'Add New Calculator', 'textdomain' ),
				        '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-plus"><path d="M12 5v14M5 12h14"></path></svg>'
				    );
				} ?>

			</div>
		</div>
	</div>
	</div>
	<div class="container-fluid col-12" id="settings-tabs-wrapper">
		<!--Main Content Container-->
		<div id="debug_messages_wrapper" class="d-none"></div>
		<div id="sg_optimizer_message_wrapper" class="d-none alert alert-danger" role="alert">
			<div class="diag-msg-container">
				<p>
					<span class="scc-icn-wrapper">
						<svg xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 -960 960 960" width="24"><path d="m40-120 440-760 440 760H40Zm138-80h604L480-720 178-200Zm302-40q17 0 28.5-11.5T520-280q0-17-11.5-28.5T480-320q-17 0-28.5 11.5T440-280q0 17 11.5 28.5T480-240Zm-40-120h80v-200h-80v200Zm40-100Z"/></svg>
					</span>
					<b>You're using SG Page Optimizer!</b>
				</p>
				<p class="mb-0">This plugin is known for heavy JS optimizations that interfere with the contact forms and calculator forms</p>
				<i class="material-icons diag-msg-close" onclick="javascript:skipSGOptimWarning(this)">close</i>
			</div>
		</div>
		<script>
			function showSettingsTab(type) {
				switch (type) {
					case "font":
						b_font.click()
						break
					case "translation":
						b_tans.click()
						break
					case "settings":
						b_calc.click()
						break
				}
			}

			/**
			 * *Handles download of backup
			 */

			function downloadBackup(isPremium) {
				return
			}

			let sccHelpdeskLinks = <?php echo json_encode(scc_get_helpdesk_link_list()); ?>;
		</script>
