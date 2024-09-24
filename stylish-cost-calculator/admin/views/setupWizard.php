<?php

namespace StylishCostCalculator\Admin\Views;

class SetupWizard {

    public $scc_icons;
    private $icons_rsrc = [];
    public function __construct( $icons ) {
        $this->scc_icons = $icons;
        $choices_data    = DF_SCC_QUIZ_CHOICES;
        $icons_list      = [];

        foreach ( $choices_data as $choices_collection ) {
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

        foreach ( $icons_list as $icon ) {
            if ( isset( $this->scc_icons[ $icon ] ) ) {
                $this->icons_rsrc[ $icon ] = scc_get_kses_extended_ruleset( $this->scc_icons[ $icon ] );
            }
        }
    }

    public function render_modal_placeholders() {
        // return multiline html string
        ob_start();
        ?>
        <div class="modal fade quiz-modal" id="quizModal" aria-hidden="true" aria-labelledby="quizModalLabel" tabindex="-1">
        </div>
        <div class="modal fade quiz-modal" id="quizModal2" aria-hidden="true" aria-labelledby="quizModalLabel2" tabindex="-1">
        </div>
        <div class="modal fade quiz-modal" id="quizModal3" aria-hidden="true" aria-labelledby="quizModalLabel3" tabindex="-1">
        </div>
        <div class="modal fade quiz-modal" id="quizModal4" aria-hidden="true" aria-labelledby="quizModalLabel4" tabindex="-1">
        </div>
        <div class="modal fade quiz-modal" id="quizModal5" aria-hidden="true" aria-labelledby="quizModalLabel5" tabindex="-1">
        </div>
        <div class="modal fade quiz-modal" id="quizResult" aria-hidden="true" aria-labelledby="quizModalResult" tabindex="-1">
        </div>
		<?php
        return ob_get_clean();
    }

    public function get_setup_wizard_templates() {
        $opt_user_email = get_option( 'df_scc_emailsender', get_option( 'admin_email' ) );

        if ( empty( $user_email ) ) {
            $opt_user_email = get_option( 'admin_email' );
        }
        $logged_in_user     = wp_get_current_user();

        if ( empty( $opt_user_full_name ) ) {
            $opt_user_full_name = $logged_in_user->user_login;
        }
        $opt_user_full_name = apply_filters( 'scc_wq_user_full_name', $logged_in_user->display_name );
        $opt_user_email     = apply_filters( 'scc_wq_user_email', $opt_user_email );
        // return multiline html string
        ob_start();
        ?>
        <script type="text/html" id="tmpl-quiz-modal-content">
	<div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
		<div class="modal-content p-3 vh-95">
			<div class="px-3 pt-3 text-center modal-head">
				<# if (data.currentStep != 1) { #>
					<span class="scc-icn-wrapper modal-back-nav" data-dismiss="modal" onclick="handleBackNavigation({{ data.currentStep == 'Result' ? 4 : data.currentStep - 1 }}, this)">{{{svgCollection['arrow-left']}}}</span>
				<# } #>
				<h1 class="{{ data.currentStep === 'Result' ? 'mt-5 mb-4' : ''  }}">{{{data.title}}}</h1>
				<p class="text-muted <# if ( data.currentStep == 'Result' ) { #> scc-text-black <# } #> scc-result-section-padding">{{{data.subtitle}}}</p>
			</div>
			<div class="modal-body pt-0">
				<# if (data.modalLead && data.modalLead.length) { #>
				<p class="fw-bold lead-text">{{{data.modalLead}}}</p>
				<# } #>
				<# if (data?.showFormNameInput) { #>
					<div class="px-4 py-3 bg-white">
						<div class="head">
							<strong>Please enter a name for the calculator form</strong>
						</div>
						<div class="body">
							<div class="input-group my-3">
								<input type="text" autofocus class="form-control" id="calc-from-answes-name" placeholder="Calculator name">
							</div>
							<p>Create a new calculator from the answers you have given in the previous steps.</p>
							<p class="text-danger d-none" data-warning-for="calc-from-answes-name">The above field cannot be empty</p>
						</div>
						<div class="action-btn">
							<button type="button" data-relative-field="calc-from-answes-name" class="btn scc-btn-primary">Create calculator</button>
						</div>
					</div>
				<# } #>
				<# if ( ['Result', 1].includes( data.currentStep ) ) { #>
					<div class="choices-wrapper"></div>
				<# } else { #>
					<div class="choices-wrapper row row-cols-2"></div>
				<# } #>
			</div>
			<# if ( Boolean( data?.actionBtnTitle?.length ) ) { #>
				<# if ( ! ['Result', 1].includes( data.currentStep ) ) { #>
				<div class="modal-footer d-block">
					<div class="d-grid gap-2">
						<button class="btn scc-setup-wizard-button scc-btn-primary {{ data.currentStep === 1 ? 'd-none' : '' }}" data-max-steps="5" data-next-step={{ data.quizNextStep }} type="button">{{data.actionBtnTitle}}</button>
					</div>
				</div>
				<# } #>	
			<# } #>
		</div>
	</div>
</script>
<script type="text/html" id="tmpl-quiz-choices-content">
		<# data.choices.forEach( (choice, i) => {
			let currentKey = UUIDv4.generate();
			let answersStoreIndex = quizAnswersStore['step' + data.step][choice.key];
			let hasHelpLink = choice.helpLink && choice.helpLink !== '#';
		#>
			<div class="col d-flex flex-column g-3 user-select-none">	
                <input class="form-check-input col-sm-1" hidden {{ answersStoreIndex == true ? 'checked' : '' }} type="checkbox" name="{{ choice.key }}" id="key-{{ currentKey }}">
				<label class="card {{ choice.key !== 'others' ? 'has-help-article' : '' }} m-0 flex-sm-grow-1 justify-content-center" for="key-{{ currentKey }}">
					<div class="form-check-label col-sm-11 d-inline-block ps-1">
						<div class="question-title-wrapper">
							<i class="material-icons align-middle text-primary">{{ choice.icon }}</i>
							<strong>{{ choice.choiceTitle }}</strong>&nbsp;
							<# if (hasHelpLink) { #>
								<a href="{{{ choice.helpLink }}}" target="_blank" class="card-help-icn" title="Click to learn more"></a>
							<# } #>
						</div>
						<p class="pt-1 question-desc-text">{{ choice.choiceDescription }}</p>
					</div>
				</label>
			</div>
			<# if (choice.key == 'others') { #>
				<div class="form-check w-100 d-none">
					<input class="form-control form-control-sm mb-2 mt-2 w-100 others-input" type="text" name="step{{data.step}}-othersInput" id="othersInput-{{currentKey}}" placeholder="Please specify" required="">
					<label class="form-check-label" for="othersInput-{{currentKey}}">Others</label>
				</div>
			<# } #>
		<# }) #>
</script>
<script type="text/html" id="tmpl-quiz-columned-card-choices-content">
<# if (data.step == 'Result' && data.choices?.length) {  #>
	<p class="mb-0">Recommended <b>Features</b></p>
<# } #>
<div class="row row-cols-3 g-0 w-100">
	<# 
	if (data.step == 'Result') {
		data = filterResultPageSuggestions(data);
	}
	data.choices.forEach( (choice, i) => {
		let currentKey = UUIDv4.generate();
		let icon = typeof(choice.icon) == 'string' ? svgCollection[choice.icon] : choice.icon.map(z => svgCollection[z]).join('');
		let choiceCardClasses = [];
		if (choice.helpLink && choice.helpLink.length > 1) {
			choiceCardClasses.push('has-help-article');
		}
		let title = choice?.choiceSimpleTitle ? choice.choiceSimpleTitle : choice.choiceTitle;
		let hasHelpLink = choice.helpLink && choice.helpLink !== '#';
        if ( ! Boolean(quizAnswersStore['step' + data.step]) ) {
            return;
        }
	#>
	<div class="col d-flex flex-column g-3 {{ data.step === 'Result' ? 'd-none' : '' }}">
		<input type="checkbox" {{ quizAnswersStore['step' + data.step][choice.key] == true ? 'checked' : '' }} class="d-none" name="{{ choice.key }}" id={{currentKey}}>
		<label class="card {{ choiceCardClasses.join( ' ' ) }} two-row-btn text-center mt-0 py-0 flex-sm-grow-1" role="button" for={{currentKey}}>
			<span class="btn-content">
				<span class="scc-icn-wrapper">{{{ icon }}}</span>
				<p class="mb-0 mt-2 result-card me-1">{{ title }}</p>
				<# if ( hasHelpLink ) { #>
					<a href="{{{ choice.helpLink }}}" target="_blank" class="card-help-icn" title="click to learn more"></a>
				<# } #>
			</span>
		</label>
	</div>
	<# }) #>
  </div>
  <# if (data.step == '1') {
	const businessAndIndustyFieldsFilledUp = quizAnswersStore.step1?.['business-description']?.length && quizAnswersStore.step1?.['business-name']?.length;
	const conditionalHideClass = businessAndIndustyFieldsFilledUp ? '' : 'd-none';
  #>
	<div class="my-3 {{ conditionalHideClass }}" id="businessNameWrapper">
		<label for="businessName" class="form-label">What is your business name?</label>
		<input type="text" value="{{ quizAnswersStore.step1?.['business-name'] }}" name="business-name" class="form-control scc-setup-wizard-text-inputs" id="businessName">
	</div>
    <div class="my-3 {{ conditionalHideClass }}" id="businessDescriptionWrapper">
		<label for="businessDescription" class="form-label">Describe your business and pricing in 3 sentences</label>
		<textarea name="business-description" class="form-control scc-setup-wizard-text-inputs" id="scc-ai-assisted-setup-wiz-business-description" placeholder="Example: We offer professional sound and lighting services for events of all sizes. Our basic package starts at $500 and includes setup and operation for up to 5 hours. Additional services, such as custom lighting designs and extended hours, are available at competitive rates."
        oninput="sccAiAssistedSetupWizUpdateProgress()">{{ quizAnswersStore.step1?.['business-description'] }}</textarea>
        <div class="scc-ai-assisted-setup-wiz-progress-bar-container">
            <div class="scc-ai-assisted-setup-wiz-progress-bar" id="scc-ai-assisted-setup-wiz-progress-bar"></div>
        </div>
    </div>
	<!-- <div class="my-3 {{ conditionalHideClass }}" id="industryTypeWrapper">
		<label for="industryType" class="form-label">What is your industry?</label>
		<input type="text" value="{{ quizAnswersStore.step1?.['industry-type'] }}" name="industry-type" class="form-control" id="industryType">
	</div> -->
	<button type="submit"id="scc-setup-wizard-first-step-next-btn" data-max-steps="5" data-next-step="2" class="btn btn-primary w-100 {{ conditionalHideClass }}">Continue</button>
  <# } #>
  <# if (data.step == 'Result') { #>
	<# if (data.elementSuggestions?.length) { #>
  <div class="mt-3 d-none">
	<p class="mb-0">Recommended <b>Elements</b></p>
	<div class="row row-cols-3 g-0">
		<# data.elementSuggestions.forEach( (choice, i) => {
			let currentKey = UUIDv4.generate();
			let choiceCardClasses = [];
			let icon = typeof(choice.icon) == 'string' ? svgCollection[choice.icon] : choice.icon.map(z => svgCollection[z]).join('');
			if (choice.helpLink) {
				choiceCardClasses.push('has-help-article');
			}
			let title = choice?.choiceSimpleTitle ? choice.choiceSimpleTitle : choice.choiceTitle;
			let hasHelpLink = choice.helpLink && choice.helpLink !== '#';
            if ( ! Boolean(quizAnswersStore['step' + data.step]) ) {
                return;
            }
		#>
		<div class="col d-flex flex-column g-3">
			<input type="checkbox" {{ quizAnswersStore['step' + data.step][choice.key] == true ? 'checked' : '' }} data-element-suggestion="1" class="d-none" name="{{ choice.key }}" id={{currentKey}}>
			<label class="card {{ choiceCardClasses.join( ' ' ) }} single-row-btn text-center mt-0 py-0 flex-sm-grow-1" role="button" for={{currentKey}}>
				<span class="btn-content">
					<span class="scc-icn-wrapper">{{{ icon }}}</span>
					<p class="mb-0 mt-2 result-card me-1">{{ title }}</p>
					<# if ( hasHelpLink ) { #>
						<a href="{{{ choice.helpLink }}}" target="_blank" class="card-help-icn" title="Click to learn more"></a>
					<# } #>
				</span>
			</label>
		</div>
		<# }) #>
	</div>
  </div>
  <# } #>
  <form class="mt-4 scc-result-section-margin" id="quiz-result-email-form">
	<div id="wq_field_wrapper">
		<div class="mt-3">
			<label for="wq_your_name" class="form-label text-white">Your Name</label>
			<input type="text" class="form-control" value="<?php echo esc_attr( $opt_user_full_name ); ?>" id="wq_your_name">
		</div>
		<p class="m-0 text-danger">Please fill out the field above</p>
		<div class="mt-3">
			<label for="wq_your_email" class="form-label text-white">Your Email</label>
			<input type="email" class="form-control" value="<?php echo esc_attr( $opt_user_email ); ?>" id="wq_your_email">
		</div>
		<p class="m-0 text-danger">Please fill out the field above</p>

		<button class="btn scc-setup-wizard-button scc-backend-wizard-button mt-4 w-100" data-max-steps="5" data-next-step="0" data-result-action="email" type="button">	
			Email Setup Steps & Open Builder
			<div class="scc-liquid-shape"></div>
		</button>
  </div>
</form>
<div class="text-center pt-4 mt-4 mb-3 pb-4 scc-backend-wizard-text"><strong>Or</strong></div>
<div class="text-center"><a href="#" class="scc-text-black scc-setup-wizard-button scc-font-size-normal" data-max-steps="5" data-next-step="0" data-result-action="pdf" ><strong>Download Step-by-Step PDF & Open Builder</strong></a></div>
  <# } #>
</script>
        <?php
        return ob_get_clean();
    }

    public function get_setup_wizard_styles() {
        ob_start();
        ?>

        <style>
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

            .modal .card .scc-icn-wrapper {
                color: var(--scc-color-primary);
            }

            label.card {
                position: relative;
                user-select: none;
            }
            @media (max-width: 768px) {
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

            .scc-result-section-padding{
                padding-left: 5rem !important;
                padding-right: 5rem !important;
            }
            .scc-result-section-margin{
                margin-left: 5rem !important;
                margin-right: 5rem !important;
            }

            @media (min-width: 1400px) {
                .container-fluid.my-5 {
                    max-width: 40% !important;
                }
            }
        </style>
        <?php
        return ob_get_clean();
    }
    public function get_setup_wizard_scripts() {
        ob_start();
        ?>
        <script type="text/json" id="svgCollection">
        <?php
        echo wp_json_encode(
            $this->icons_rsrc
        );
        ?>
        </script>
        <?php
        return ob_get_clean();
    }
}
