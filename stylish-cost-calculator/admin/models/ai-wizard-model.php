<?php
if ( !defined( 'ABSPATH' ) ) {
    exit;
}
class SCCAiWizardModel {

    public function __construct() {
    }
    public function __destruct() {
    }
    private function render_ai_wizard_header( $scc_logo, $scc_icons ) {
        ob_start();
        ?>
        <div class="scc-ai-wizard-menu-header">
            <div class="scc-ai-wizard-back">
                <button id="scc-ai-wizard-back-btn" class="scc-ai-back-btn scc-hidden" title="Back">
                    <span class="scc-icn-wrapper">
                        <?php echo scc_get_kses_extended_ruleset( $scc_icons['chevron-left'] ); ?>
                    </span>
                </button>
            </div>
            <img src="<?php echo esc_url( $scc_logo ); ?>" alt="AI Wizard Logo">

            <div class="scc-ai-assistant-header-buttons">
                <div class="scc-ai-about-credits-tooltip" data-setting-tooltip-type="ai-wizard-credits-tt" data-bs-original-title="" title="">
                        <span class="scc-icn-wrapper">
                            <?php echo scc_get_kses_extended_ruleset( $scc_icons['help-circle'] ); ?>
                        </span>
                </div>
                <div class="scc-ai-credit-count scc-hidden">
                    <span class="scc-ai-credit-count-circle-indicator scc-ai-count-green"></span>
                    <span class="scc-ai-credit-count-total"></span>
                </div>
                <div>
                    <button id="scc-ai-wizard-reset-btn" class="scc-ai-reset-btn scc-hidden" title="Reset AI Chat">
                        <span class="scc-icn-wrapper">
                            <?php echo scc_get_kses_extended_ruleset( $scc_icons['refresh-ccw'] ); ?>
                        </span>
                    </button>
                    <button id="scc-ai-wizard-close-chat-btn" class="scc-ai-close-btn" title="Close AI Panel">
                        <span class="scc-icn-wrapper">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-x">
                                <path d="M18 6L6 18M6 6l12 12"></path>
                            </svg>
                        </span>
                    </button>
                </div>

            </div>
        </div>
    <?php
                return ob_get_clean();
    }
    private function render_ai_wizard_footer( $scc_icons ) {
        ob_start();
        ?>
        <div class="scc-ai-assistant-inputs">
            <div class="scc-ai-response-loader scc-hidden">
                <span>
                    <i class="scc-btn-spinner scc-save-btn-spinner"></i>
                </span>
            </div>
            <input type="text" class="scc-ai-assistant-text-field scc-hidden" placeholder="Type your question here" disabled>
            <button class="scc-ai-assistant-send-btn scc-hidden" data-request-type="" disabled>
                <span class="scc-icn-wrapper"><?php echo scc_get_kses_extended_ruleset( $scc_icons['send'] ); ?></span>
            </button>
        </div>
    <?php
            return ob_get_clean();
    }

    public function get_ai_wizard_button( $calculator_id ) {
        if ( !is_int( $calculator_id ) || $calculator_id <= 0 ) {
            return; //Invalid calculator ID
        }
        $scc_icons          = require SCC_DIR . '/assets/scc_icons/icon_rsrc.php';
        $user_id            = get_current_user_id();
        $scc_logo           = SCC_URL . 'assets/images/scc-ai-wizard-logo.svg';
        $scc_ai_wizard_icon = SCC_URL . 'assets/images/scc-ai-wizard-icon.svg';
        $scc_ai_avatar      = SCC_URL . 'assets/images/ai-sparks.svg';
        $scc_user_avatar    = get_avatar_url( $user_id );

        //Feature titles
        $scc_suggest_element_title          = 'Intelligent Element Suggester';
        $scc_form_optimizer_title           = 'Calc Form Optimizer';
        $scc_setup_wizard_title             = 'AI-Powered Setup';
        $scc_advanced_pricing_formula_title = 'Advanced Pricing Formula Element Builder';
        $scc_analytic_insights_title        = 'AI-Powered Insights';
        ob_start();
        ?>
        <div class="scc-ai-wizard-panel-container scc-hidden">
            <div>
                <a id="scc-ai-wizard-button" class="scc-ai-wizard-button" href="#">
                    <img src="<?php echo esc_url( $scc_ai_wizard_icon ); ?>" alt="">
                </a>
            </div>
            <div class="scc-ai-wizard-menu scc-hidden" data-user-avatar="<?php echo esc_url( $scc_user_avatar ); ?>" data-ai-avatar="<?php echo esc_url( $scc_ai_wizard_icon ); ?>">
                <?php echo $this->render_ai_wizard_header( $scc_logo, $scc_icons ); ?>
                <div class="scc-ai-wizard-menu-body">
                    <div class="scc-ai-wizard-loader-container scc-hidden">
                        <div class="scc-ai-wizard-loader"></div>
                        <div class="scc-ai-wizard-loader-description">Please wait 5 seconds, generating optimizations for your calculator</div>
                    </div>
                    <div class="scc-ai-wizard-menu-buttons">
                        <div class="scc-ai-wizard-init-message">
                            <img src="<?php echo esc_url( $scc_ai_wizard_icon ); ?>" alt="AI Wizard">
                            <span>How can I help you today?</span>
                        </div>
                        <button id="scc-ai-wizard-setup-wizard" data-option-type="setup-wizard" class="btn btn-alert scc-ai-wizard-option" href="#"><span class="scc-icn-wrapper me-2"><?php echo scc_get_kses_extended_ruleset( $scc_icons['ai-algorithm'] ); ?></span><?php echo esc_html( $scc_setup_wizard_title ); ?></button>
                        <button id="scc-ai-wizard-suggest-element" data-option-type="suggest-elements" class="btn btn-alert scc-ai-wizard-option" href="#"><span class="scc-icn-wrapper me-1"><?php echo scc_get_kses_extended_ruleset( $scc_icons['icon-bulb'] ); ?></span><?php echo esc_html( $scc_suggest_element_title ); ?></button>
                        <button id="scc-ai-wizard-optimize-form" data-option-type="optimize-form" class="btn btn-alert scc-ai-wizard-option" href="#"><span class="scc-icn-wrapper me-2"><?php echo scc_get_kses_extended_ruleset( $scc_icons['icon-optimize'] ); ?></span><?php echo esc_html( $scc_form_optimizer_title ); ?></button>
                        <button id="scc-ai-wizard-advanced-pricing-formula" disabled data-option-type="advanced-pricing-formula" class="btn btn-alert scc-ai-wizard-option" href="#"><span class="scc-icn-wrapper me-2"><?php echo scc_get_kses_extended_ruleset( $scc_icons['icon-noun-math'] ); ?></span><?php echo esc_html( $scc_advanced_pricing_formula_title ); ?></button>
                        <button id="scc-ai-wizard-analytics-insights" disabled data-option-type="analytics-insights" class="btn btn-alert scc-ai-wizard-option" href="#"><span class="scc-icn-wrapper me-2"><?php echo scc_get_kses_extended_ruleset( $scc_icons['icon-analytics'] ); ?></span><?php echo esc_html( $scc_analytic_insights_title ); ?></button>
                        <button id="scc-ai-wizard-crisp-chat" data-option-type="open-support-chat" class="btn btn-alert scc-ai-wizard-option" href="#"><span class="scc-icn-wrapper me-2"><?php echo scc_get_kses_extended_ruleset( $scc_icons['message-square'] ); ?></span>Open Support Chat</button>
                        <button id="scc-ai-wizard-intelligent-qa" data-option-type="open-intelligent-qa" class="btn btn-alert scc-ai-wizard-option" href="#"><span class="scc-icn-wrapper me-2"><?php echo scc_get_kses_extended_ruleset( $scc_icons['help-circle'] ); ?></span>Intelligent Questions & Answers</button>
                    </div>
                    <div class="scc-ai-wizard-content-tab scc-ai-wizard-setup scc-hidden">
                        <div class="scc-ai-wizard-tab-title">
                            <?php echo esc_html( $scc_setup_wizard_title ); ?>
                        </div>
                        <div class="accordion" id="scc-setup-wizard-accordion">
                            <div class="accordion-item">
                                <h2 class="accordion-header" id="headingOne">
                                    <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="false" aria-controls="collapseOne">
                                        Step-by-Step Guide
                                    </button>
                                </h2>
                                <div id="collapseOne" class="accordion-collapse collapse show" aria-labelledby="headingOne" data-bs-parent="#scc-setup-wizard-accordion">
                                    <div class="accordion-body scc-ai-wizard-setup-accordion-body">
                                        <div class="scc-ai-wizard-loader-container scc-setup-wizard-loader scc-hidden">
                                            <div class="scc-ai-wizard-loader"></div>
                                            <div class="scc-ai-wizard-loader-description">Please wait 5 seconds, this is the most important info. Please don't close this while it's generating.</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="accordion-item">
                                <h2 class="accordion-header" id="headingTwo">
                                    <button class="accordion-button  collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTwo" aria-expanded="true" aria-controls="collapseTwo">
                                        Setup Wizard Check-List
                                    </button>
                                </h2>
                                <div id="collapseTwo" class="accordion-collapse collapse" aria-labelledby="headingTwo" data-bs-parent="#scc-setup-wizard-accordion">
                                    <div class="accordion-body">
                                    <div class="scc-ai-wizard-alert">
                                        <span>
                                            <i class="scc-icn-wrapper me-2"><?php echo scc_get_kses_extended_ruleset( $scc_icons['alert-circle'] ); ?></i>
                                        </span>
                                        <span>These are your <b>step-by-step instructions</b> that will become green as you create it. Please follow the instructions above to learn how to make these green.</span>
                                    </div>
                                        <div class="d-none" id="floating-wizard-placeholder"></div>
                                    </div>
                                </div>
                            </div>
                            
                        </div>
                        <div class="scc-ai-wizard-menu-buttons">
                            <button id="scc-ai-wizard-retake" class="btn btn-alert scc-ai-wizard-option" type="button">
                                   Redo Setup Wizard
                            </button>
                        </div>
                    </div>
                    <div class="scc-ai-wizard-content-tab scc-ai-wizard-chat scc-hidden">
                        <div class="scc-ai-wizard-tab-title">
                            <?php echo esc_html( $scc_suggest_element_title ); ?>
                        </div>

                    </div>
                    <div class="scc-ai-wizard-content-tab scc-ai-wizard-form-optimizer scc-hidden">
                        <div class="scc-ai-wizard-tab-title">
                            <?php echo esc_html( $scc_form_optimizer_title ); ?>
                        </div>
                        <div class="scc-ai-wizard-consider-start-setup scc-ai-wizard-alert scc-hidden">
                            <span>If you want to get better results based on your business, consider taking the <b><?php echo esc_html( $scc_setup_wizard_title ); ?></b></span>
                            <button id="scc-ai-wizard-consider-setup" class="btn btn-alert scc-ai-wizard-option mt-0" type="button">
                                Start Setup Wizard
                            </button>
                        </div>
                    </div>
                    <div class="scc-ai-wizard-content-tab scc-ai-wizard-advanced-pricing-formula scc-hidden">
                        <div class="scc-ai-wizard-tab-title">
                            <?php echo esc_html( $scc_advanced_pricing_formula_title ); ?>
                        </div>
                        <div class="scc-ai-wizard-menu-buttons">
                            <div class="scc-ai-wizard-init-message">
                                <span>Select the element you want to edit</span>
                            </div>
                            <div class="scc-ai-wizard-advanced-pricing-formula-buttons">
                                <button data-option-type="setup-wizard" class="btn btn-alert scc-ai-wizard-option">Advanced Pricing Formula 1</button>
                                <button data-option-type="setup-wizard" class="btn btn-alert scc-ai-wizard-option">Advanced Pricing Formula 2</button>
                            </div>
                        </div>

                    </div>
                    <div class="scc-ai-wizard-content-tab scc-ai-wizard-analytics-insights scc-hidden">
                        <div class="scc-ai-wizard-tab-title">
                            <?php echo esc_html( $scc_analytic_insights_title ); ?>
                        </div>
                        <div class="scc-ai-wizard-menu-buttons">
                            <div class="scc-ai-wizard-init-message">
                                <span>Get smart insights about your calculator quotes</span>
                            </div>
                            <a id="scc-ai-wizard-go-to-qms-button" class="btn btn-alert scc-ai-wizard-option" href="<?php echo esc_url_raw( admin_url( "admin.php?page=scc-quote-management-screen&id={$calculator_id}&advanced-options=true" ) ); ?>">Go to AI-Powered Insights</a>
                        </div>

                    </div>

                </div>
                <div class="scc-ai-wizard-menu-footer scc-hidden">
                    <?php echo $this->render_ai_wizard_footer( $scc_icons ); ?>
                </div>
            </div>
        </div>
    <?php
            return ob_get_clean();
    }

    public function get_ai_wizard_element_panel( $type ) {
        $scc_icons       = require SCC_DIR . '/assets/scc_icons/icon_rsrc.php';
        $user_id         = get_current_user_id();
        $scc_logo        = SCC_URL . 'assets/images/scc-ai-wizard-logo.svg';
        $scc_ai_avatar   = SCC_URL . 'assets/images/ai-sparks.svg';
        $scc_ai_wizard_icon = SCC_URL . 'assets/images/scc-ai-wizard-icon.svg';
        $scc_user_avatar = get_avatar_url( $user_id );
        ob_start();
        ?>
        <div class="scc-element-ai-assistant-wrapper scc-hidden">
            <div class="scc-ai-assistant-chat" data-user-avatar="<?php echo esc_url( $scc_user_avatar ); ?>" data-ai-avatar="<?php echo esc_url( $scc_ai_wizard_icon ); ?>">
                <div class="scc-ai-assistant-header">
                    <img src="<?php echo esc_url( $scc_logo ); ?>" alt="AI Wizard Logo">
                    <div class="scc-ai-assistant-header-buttons">
                        <!-- <div class="scc-ai-credit-loader">
                            <span>
                                <i class="scc-btn-spinner scc-ai-count-loader"></i>
                            </span>
                        </div> -->
                        <div class="scc-ai-credit-count scc-hidden">
                            <span class="scc-ai-credit-count-circle-indicator scc-ai-count-green"></span>
                            <span class="scc-ai-credit-count-total"></span>
                        </div>
                        <div>
                            <button id="reset-btn" class="scc-ai-reset-btn" title="Reset AI Chat" onclick="sccResetAiAssistant(this)">
                                <span class="scc-icn-wrapper">
                                    <?php echo scc_get_kses_extended_ruleset( $scc_icons['refresh-ccw'] ); ?>
                                </span>
                            </button>
                            <button id="close-btn" class="scc-ai-close-btn" title="Close AI Panel" onclick="javascript:sccBackendUtils.toggleElementAiAssistant(this)">
                                <span class="scc-icn-wrapper">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-x">
                                        <path d="M18 6L6 18M6 6l12 12"></path>
                                    </svg>
                                </span>
                            </button>
                        </div>
                    </div>

                </div>
                <div class="scc-ai-assistant-thread">
                    <div class="scc-ai-init-frame"></div>
                    <div class="scc-ai-chat">
                        <div class="scc-ai-chat-bubble scc-ai-chat-bubble-wizard">
                            <div class="scc-ai-chat-bubble-avatar">
                                <img src="<?php echo $scc_ai_wizard_icon; ?>" alt="">
                            </div>
                            <div class="scc-ai-chat-bubble-content">
                                <div class="scc-ai-chat-bubble-text">
                                    <p>Hi! I am your AI Element Assistant, how can I help you?</p>
                                    <div class="scc-ai-response-option" onclick="sccChooseAiOption(this)" data-suggested-prompt="element_name">
                                        <span class="scc-icn-wrapper me-2"><?php echo scc_get_kses_extended_ruleset( $scc_icons['type'] ); ?></span>Help me to write a creative title
                                    </div>
                                    <?php if ( $type === 'variable-math' ) { ?>
                                        <div class="scc-ai-response-option" onclick="sccChooseAiOption(this)" data-suggested-prompt="vmath_request">
                                            <span class="scc-icn-wrapper me-2"><?php echo scc_get_kses_extended_ruleset( $scc_icons['divide-square'] ); ?></span>Advanced Pricing Formula Formula
                                        </div>
                                    <?php } ?>
                                </div>
                            </div>
                        </div>

                    </div>
                    <div class="scc-ai-actions scc-hidden">
                        <button class="btn scc-ai-action-btn scc-ai-action-regenerate" onclick=""><span class="scc-icn-wrapper me-1"><?php echo scc_get_kses_extended_ruleset( $scc_icons['refresh-cw'] ); ?></span> Regenerate</button>
                        <button class="btn scc-ai-action-btn scc-ai-action-discard" onclick=""><span class="scc-icn-wrapper me-1"><?php echo scc_get_kses_extended_ruleset( $scc_icons['x'] ); ?></span> Discard</button>
                        <button class="btn scc-ai-action-btn scc-ai-action-apply" onclick=""><span class="scc-icn-wrapper me-1"><?php echo scc_get_kses_extended_ruleset( $scc_icons['check'] ); ?></span> Apply</button>

                    </div>
                </div>
                <?php echo $this->render_ai_wizard_footer( $scc_icons ); ?>
            </div>
        </div>
<?php
            return ob_get_clean();
    }
}
