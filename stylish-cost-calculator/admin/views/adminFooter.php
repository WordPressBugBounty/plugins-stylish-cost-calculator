<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}
use StylishCostCalculator\Admin\Views\SetupWizard;

$setup_wizard = new SetupWizard( $this->scc_icons );

add_action(
    'admin_footer',
    function () use ( $setup_wizard ) {
        require_once __DIR__ . '/modalTemplates.php';
        echo $setup_wizard->render_modal_placeholders();
        echo $setup_wizard->get_setup_wizard_templates();
        echo $setup_wizard->get_setup_wizard_styles();
    }
);
wp_localize_script( 'scc-backend', 'notificationsNonce', [ 'nonce' => wp_create_nonce( 'notifications-box' ) ] );

if ( get_current_screen()->base !== 'stylish-cost-calculator_page_scc-tabs' ) {
    do_action( 'scc_render_notices' );
}
?>
<?php if ( get_current_screen()->base === 'admin_page_scc_edit_items' && isset( $f1 ) ) {
    $choices_data = DF_SCC_QUIZ_CHOICES;
    $icons_list   = [];

    foreach ( $choices_data as $choices_collection ) {
        // $choices_data[$key]['choiceTitle'] = __( $value['choiceTitle'], 'smartcat-calculator' );
        foreach ( $choices_collection as $choice_props ) {
            if ( isset( $choice_props['icon'] ) && ! is_array( $choice_props['icon'] ) ) {
                array_push( $icons_list, $choice_props['icon'] );
            }

            if ( isset( $choice_props['icon'] ) && is_array( $choice_props['icon'] ) ) {
                foreach ( $choice_props['icon'] as $icon ) {
                    array_push( $icons_list, $icon );
                }
            }
        }
    }
    ?>
<script type="text/json" id="choices-data">
	<?php
        echo wp_json_encode( $choices_data );
    ?>
</script>
<?php } ?>
</div> <!--Closing Main Content Container-->

<div class="scc-footer-container">
	<div class="scc-footer">
		<div class="scc-footer-left">
			<div class="scc-footer-logo">
				<a href="https://www.stylishcostcalculator.com/" class="scc-footer-logo-link" target="_blank">
					<img src="<?php echo esc_url(SCC_URL . 'assets/images/scc-logo.png'); ?>" alt="Stylish Cost Calculator">
				</a>
			</div>
		</div>
		
		<div class="scc-footer-center">
			<div>
				<span class="scc-footer-links-title">Helpful Links</span>
			</div>
			<ul class="scc-footer-links">
				<li>
					<a href="<?php echo esc_url( SCC_HELPDESK_LINKS['helpdesk-home'] ); ?>">						
						<i class="material-icons-outlined">book</i>
						<span>User Guides</span>
					</a>
				</li>
				<li><a href="<?php echo esc_url( SCC_HELPDESK_LINKS['helpdesk-video-tutorials'] ); ?>"><i class="material-icons-outlined">support</i>
				<span>Submit A Ticket</span></a></li>
				<li><a href="https://stylishcostcalculator.com/pricing-plans/">
					<i class="material-icons-outlined">diamond</i>
					<span>Pricing</span>
				</a></li>
			</ul>
		</div>
		<div class="scc-footer-center">
			<div>
				<span class="scc-footer-links-title">Follow Us</span>
			</div>
			<ul class="scc-footer-links">
				<li>
					<a href="https://www.facebook.com/Stylish-Cost-Calculator-WordPress-Plugin-354068492335430" target="_blank">						
						<i class="material-icons-outlined">facebook</i>
						<span>Facebook</span>
					</a>
				</li>
				<li><a href="https://www.youtube.com/c/StylishCostCalculator" target="_blank">						
					<i class="material-icons-outlined">
					<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 576 512"><path d="M549.655 124.083c-6.281-23.65-24.787-42.276-48.284-48.597C458.781 64 288 64 288 64S117.22 64 74.629 75.486c-23.497 6.322-42.003 24.947-48.284 48.597-11.412 42.867-11.412 132.305-11.412 132.305s0 89.438 11.412 132.305c6.281 23.65 24.787 41.5 48.284 47.821C117.22 448 288 448 288 448s170.78 0 213.371-11.486c23.497-6.321 42.003-24.171 48.284-47.821 11.412-42.867 11.412-132.305 11.412-132.305s0-89.438-11.412-132.305zm-317.51 213.508V175.185l142.739 81.205-142.739 81.201z"/></svg>
					</i>
					<span>YouTube</span>
				</a></li>
			</ul>
		</div>
		
		<div class="scc-footer-right">

		<?php if ( isset( $_REQUEST['id_form'] ) ) { ?>
					<ul class="scc-edit-nav-items">
						<li><a class="scc-nav-with-icons"
								href="<?php echo esc_url( admin_url( 'admin.php?page=scc-tabs' ) ); ?>"><i
									class="fas fa-plus"></i>Add New</a></li>
						<li><a class="scc-nav-with-icons"
								href="<?php echo esc_url( admin_url( 'admin.php?page=scc-list-all-calculator-forms' ) ); ?>"><i
									class="far fa-edit"></i>Edit Existing</a></li>
						<li class="dropdown">
							<a class="dropdown-toggle scc-nav-with-icons" data-bs-toggle="dropdown" role="button"
								aria-haspopup="true" aria-expanded="false"><i class="far fa-comment"></i>Feedback <span
									class="caret"></span></a>
							<ul class="dropdown-menu">
								<li><a target="_blank" href="https://stylishcostcalculator.com/how-can-we-be-better/">Send
										Feedback</a></li>
								<li><a target="_blank" href="https://stylishcostcalculator.com/poll/new-features/">Suggest
										Feature</a></li>
							</ul>
						</li>

						<li class="dropdown">
							<a class="dropdown-toggle scc-nav-with-icons" data-bs-toggle="dropdown" role="button"
								aria-haspopup="true" aria-expanded="false"><i class="far fa-life-ring"></i>Support <span
									class="caret"></span></a>
							<ul class="dropdown-menu">
								<li><a target="_blank"
										href="<?php echo esc_url(SCC_HELPDESK_LINKS['helpdesk-home']);?>">User Guides</a>
								</li>
								<li><a target="_blank"
										href="<?php echo esc_url(SCC_HELPDESK_LINKS['helpdesk-video-tutorials']);?>">Video
										Guides</a></li>
								<li><a target="_blank"
										href="<?php echo esc_url( admin_url( 'admin.php?page=scc-diagnostics' ) ); ?>">Diagnostic</a>
								</li>
								<li><a target="_blank"
										href="<?php echo esc_url(SCC_HELPDESK_LINKS['helpdesk-troubleshooting']);?>">Troubleshooting</a>
								</li>
								<li><a target="_blank" href="https://stylishcostcalculator.com/support/">Contact Support</a>
								</li>
								<li><a target="_blank" href="https://members.stylishcostcalculator.com/">Member's Portal</a>
								</li>
							</ul>
						</li>
					</ul>
				<?php } else { ?>
					<div class="scc-footer-right">
			
						<div class="scc-plugin-info">
							<div class="scc-plugin-info-text">
								<p class="scc-footer-links-title">Do you like this plugin? <span class="scc-emoji">😊</span> We have many more:</p>
							</div>
							<div class="scc-footer-plugin-logos-container">    
								<a href="https://stylishpricelist.com/" target="_blank" class="scc-footer-plugin-logos">
									<img src="<?php echo esc_url(SCC_URL . 'assets/images/spl-logo.webp'); ?>" alt="Stylish Price List" title="Stylish Price List">
								</a>
								<a href="https://seo-ai-audit-tool.designful.ca/" target="_blank" class="scc-footer-plugin-logos">
									<img src="<?php echo esc_url(SCC_URL . 'assets/images/saat-logo.png'); ?>" alt="SEO AI Audit Tool" title="SEO AI Audit Tool">
								</a>
								<a href="https://wordpress.org/plugins/smart-table-builder/" target="_blank" class="scc-footer-plugin-logos">
									<img src="<?php echo esc_url(SCC_URL . 'assets/images/stb-logo.png'); ?>" alt="Smart Table Builder" title="Smart Table Builder">
								</a>
								<a href="https://stylishcostcalculator.com" target="_blank" class="scc-footer-plugin-logos">
									<img src="<?php echo esc_url(SCC_URL . 'assets/images/scc-logo.png'); ?>" alt="Stylish Cost Calculator" title="Stylish Cost Calculator">
								</a>
							</div>
					</div>
					<?php } ?>



	</div>
</div>


<style type="text/css">
	.scc-new .clearfix {
		clear: both;
		display: block;
		/* width: 100%; */
	}

	.scc-new .clearfix a img {
		max-width: 130px;
	}

	img.img-responsive-scc {
		max-width: 100%;
		height: auto;
	}

	.foot-img-li .col-md-5 .scc-footer,
	.foot-img-li .col-md-5 ul.foot-li,
	.foot-img-li .col-md-5 ul.foot-li li {
		display: inline-block;
	}

	.foot-img-li .scc-footer {
		width: 100%;
	}

	.foot-img-li .scc-footer {
		width: 100%;
	}

	.foot-img-li {
		margin-top: 100px;
	}

	ul.foot-li {
		width: 100%;
		float: left;
	}

	ul.foot-li li a {
		list-style-type: none;
		text-decoration: none;
		padding: 6px;
		font-size: 12px;
		color: #314af3;
	}

	ul.foot-li li {
		width: 30%;
		float: left;
	}

	ul.foot-li li:last-child:after {
		display: none;
	}

	.design,
	.design-2,
	.design-3 {
		position: relative;
	}

	.foot-img-li .design:after,
	.design-2:after,
	.design-3:after {
		content: "";
		width: 2px;
		height: 65px;
		background-color: #9c9c9c;
		position: absolute;
		right: 3px;
		top: 0;
	}

	p.foot-social i.fa {
		padding-top: -20px;
		width: 35px;
		font-size: 20px;
		color: #314af3;
	}

	.foot-text-img p span img {
		width: 100%;
	}

	.foot-text-img p span {
		display: inline-block;
		width: 100%;
		float: left;
	}

	.foot-url {
		text-align: center;
	}

	.foot-url p.col-me {
		color: #314af3;
	}

	.foot-url p {
		margin: 2px;
	}

	.foot-text-img a.plugin_text {
		font-size: 15px;
	}

	.foot-text-img p span img {
		width: 100px;
	}

	.price_wrapper {
		border-top: 1px solid #dcdcdc;
		margin-top: 50px;
		width: 100%;
		max-width: 98%;
	}

	.url-foot:after {
		content: "";
		width: 1px;
		height: 100px;
		background-color: #000;
		position: absolute;
		top: 0px;
	}

	.foot-text-img p {
		width: 100%;
		float: left;
		margin: 3px 0px;
		padding-left: 15px;
	}

	.foot-img-li .foot-li {
		margin-top: 12px;
	}

	@media screen and (max-width:768px) {

		.foot-img-li .col-md-1,
		.foot-img-li .design,
		.foot-img-li .design-2,
		.foot-img-li .design-3,
		.foot-img-li .col-md-3 {
			width: 100%;
			float: left;
		}

		.foot-img-li .scc-footer {
			width: 100%;
			float: left;
			max-width: 200px;
		}

		.foot-url {
			text-align: left;
			margin: 15px 0px;
		}

		.foot-text-img {
			width: 100%;
			float: left;
			margin-bottom: 16px;
		}

		.foot-text-img p {
			padding-left: 0px;
		}

		.foot-img-li .design:after,
		.design-2:after,
		.design-3:after {
			display: none;
		}
	}

	@media screen and (max-width:366px) {
		ul.foot-li li {
			width: 100%;
			float: left;
		}

		ul.foot-li li:after {
			display: none;
		}
	}

	.scc-separator {
		margin-top: 20px;
	}
</style>
