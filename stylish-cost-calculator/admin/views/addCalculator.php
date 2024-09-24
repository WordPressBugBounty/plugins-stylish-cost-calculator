<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

// declaring the templates to load from json file

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
$icons_list = array_unique( $icons_list );

array_push( $icons_list, 'arrow-left' );

$icons_rsrc = [];

foreach ( $icons_list as $icon ) {
    if ( isset( $this->scc_icons[ $icon ] ) ) {
        $icons_rsrc[ $icon ] = scc_get_kses_extended_ruleset( $this->scc_icons[ $icon ] );
    }
}

wp_localize_script( 'scc-backend', 'pageAddCalculator', [ 'nonce' => wp_create_nonce( 'add-calculator-page' ) ] );
?>
<div id="add-new-page-wrapper" class="container-fluid my-5 mx-auto with-vh d-none">
	<div class="row">
		<div class="col page-section d-none" id="new-calc-creator-section">
			<div class="px-4 py-3 bg-white">
				<!-- Action buttons -->
				<div class="pb-4 d-flex scc-new-calculator-action-container">
					<button class="scc-new-calculator-action-btn" type="button" data-btn-action="backToHome"><span class="scc-icn-wrapper"><?php echo scc_get_kses_extended_ruleset( $this->scc_icons['chevron-left'] ); ?></span> Back</button>
				</div>
				<div class="head">
					<div class="text-muted text-uppercase">Option D</div>
					<strong>Start from scratch</strong>
				</div>
				<div class="body">
					<div class="input-group my-3">
						<input type="text" class="form-control" id="new-calc-name" placeholder="Calculator name">
					</div>
					<p>Create a new calculator from scratch with your own layout and style.</p>
					<p class="text-danger d-none">Please enter a name for the calculator</p>
				</div>
				<div class="action-btn">
					<button type="button" data-relative-field="new-calc-name" class="btn scc-btn-primary">Create calculator</button>
				</div>
			</div>
		</div>
	</div>
</div>

<?php
include 'partials/welcome-page.php';
include 'partials/template-loader-page.php';
?>
<style>
	#adminmenumain, #wpfooter {
		display: none !important
	}
	#wpcontent {
		margin-left: 0 !important;
	}
	.action-btn {
		display: flex;
		flex-direction: column;
		width: 50%;
	}

	.backdropped-swal {
    	backdrop-filter: blur(5px);
	}

	.swal2-container.swal2-backdrop-show, .swal2-container.swal2-noanimation {
    	background: rgba(0, 0, 0, .85) !important;
	}

	#quiz-result-email-form .scc-wql-field-warnings input {
		border: 1px solid #dc3545 !important;
		border-radius: 0.25rem;
	}

	#quiz-result-email-form #wq_field_wrapper:not(.scc-wql-field-warnings) p.text-danger {
		display: none;
	}

	.vh-95 {
		height: 95vh;
	}

	.modal-head .text-muted {
		font-size: 16px;
	}

	#wq_optin_for_comm {
		margin: 0.25em 0 0 0.5em;
	}

	#wq-optin-checkbox-wrapper {
		box-shadow: unset;
		background-color: #fcfcfe;
		padding: 10px 0;
		border-radius: 5px;
	}

	.action-btn span.scc-icn-wrapper {
		color: var(--scc-color-primary);
		float: left;
	}

	.action-btn span.btn-text {
		color: var(--scc-color-primary);
		float: left;
	}

	.modal .card .scc-icn-wrapper {
		color: var(--scc-color-primary);
	}

	label.card {
		position: relative;
		user-select: none;
	}
	@media (max-width: 768px) {
		.action-btn {
			width: 80%;
		}
		label.card {
			padding: 0.7em 0.5em 1em;
		}
		label.card .btn-content{
			display: flex;
			flex-direction: column;
			align-items: center;
		}
	}

	a.card-help-icn:before {
		background-color: white;
		color: black;
		display: inline-block;
		border-radius: 50%;
		border: 1px solid grey;
		text-decoration: none;
		text-align: center;
		line-height: 18px;
		width: 18px;
		transition-duration: 0.4s;
		transform: scale(0);
	}

	a.card-help-icn {
		display: inline;
	}
	.has-help-article a.card-help-icn:before {
		border: 1px solid var(--bs-gray-dark);
		font-style: normal;
		transform: scale(1);
		content: "?";
	}

	a.card-help-icn:focus {
		box-shadow: unset;
		outline: unset;
		color: initial;
	}

	input:checked + label.card,
	input:checked + .btn-checkbox {
		background-color: var(--scc-wizard-quiz-card-color);
		box-shadow: 0px 0px 0 0px #fff, 0 0 0 0.25rem rgb(59 60 62 / 9%);
	}

	.modal .card {
		border: unset;
		box-shadow: 0px -1px 0 1px #fff, 0 0 0 0.25rem rgb(59 60 62 / 9%);
		/* padding: unset; */
	}

	.modal.fade .modal-dialog {
		max-width: 780px !important;
	}

	/**
	* font settings for modals
	 */
	.modal.fade .modal-title {
		font-size: 24px;
	}

	.choices-wrapper p {
		font-size: 12px;
	}

	.choices-wrapper p.mb-0,
	p.lead-text {
		font-size: 18px;
	}

	.choices-wrapper label.card p {
		font-size: 14px;
	}

	.choices-wrapper label.card p.result-card {
		display: inline;
	}

	.two-row-btn {
		min-height: 80px;
	}

	.single-row-btn {
		min-height: 40px;
	}

	.two-row-btn .btn-content,
	.single-row-btn .btn-content {
		margin: auto 0;
	}

	#quiz-result-email-form {
		padding: 1rem 1rem 1rem 1rem;
		background: var(--scc-color-primary);
		border-radius: 10px;
	}
	.scc-result-section-padding{
		padding-left: 5rem !important;
		padding-right: 5rem !important;
	}
	.scc-result-section-margin{
		margin-left: 5rem !important;
		margin-right: 5rem !important;
	}
	#welcome-section p,
	#welcome-section button {
		font-size: 16px;
	}
	#welcome-section .action-btn button {
		height: 55px;
	}

	.btn.scc-btn-primary:hover,
	.btn.scc-btn-primary:focus {
		color: #fff;
	}

	.modal input[type=checkbox]:not(.form-check-input), .modal input[type=radio] {
		margin: .25em 0 0 0;
	}
	.click-tip {
		color: var(--scc-color-primary);
	}

	.modal-back-nav {
		cursor: pointer;
		float: left;
	}

	.choices-wrapper .form-check {
		/* padding: 15px; */
		box-shadow: 0px 0px 10px #2b2b2b15;
		/* margin-top: 10px;
		margin-left: -5px;
    	margin-right: 10px;
		border-radius: 5px; */
	}

	.question-desc-text::first-letter {
		text-transform: capitalize;
	}


	@media (min-width: 1400px) {
		.container-fluid.my-5 {
			max-width: 40% !important;
		}
	}
	.scc-buttons-vertical{
		display: flex;
		flex-direction: column;
		justify-content: center;
	}
	
	.scc-buttons-vertical button, .scc-buttons-vertical a{
		margin-bottom: 10px;
	}
	#welcome-section h1{
		font-weight: bold;
		font-family: "Nunito";
	}
</style>
<script type="text/json" id="svgCollection">
<?php
echo wp_json_encode(
    $icons_rsrc
);
?>
</script>
<script type="text/json" id="choices-data">
	<?php
    echo wp_json_encode( $choices_data );
?>
</script>
<script type="text/json" id="telemetry-status">
	<?php
$opted_in_for_telemetry = get_option( 'scc_opted_in_for_telemetry', false );
echo wp_json_encode( [ 'usageTrackingAllowed' => $opted_in_for_telemetry ] );
?>
</script>

