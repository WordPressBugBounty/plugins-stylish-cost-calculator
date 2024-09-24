<?php

namespace DF_SCC\Admin\Views;

class UninstallSurveyModal {

    public function __construct() {
        add_action( 'admin_footer', [ $this, 'render' ] );
    }

    public function render() {
        global $pagenow;

        if ( $pagenow !== 'plugins.php' ) {
            return;
        }
        $kses_defaults = wp_kses_allowed_html( 'post' );

        $svg_args = [
            'svg'   => [
                'class'           => true,
                'aria-hidden'     => true,
                'aria-labelledby' => true,
                'role'            => true,
                'xmlns'           => true,
                'width'           => true,
                'height'          => true,
                'viewbox'         => true, // <= Must be lower case!
            ],
            'g'     => [ 'fill' => true ],
            'title' => [ 'title' => true ],
            'path'  => [
                'd'               => true,
                'fill'            => true,
            ],
        ];

        $allowed_tags = array_merge( $kses_defaults, $svg_args );
        $this->deactivation_modal_styles();
        $reasons = $this->get_uninstall_reasons();
        global $current_user;
        $df_scc_user_name = ! empty( $current_user->display_name ) ? $current_user->display_name : $current_user->user_login;
        ?>
			<div class="wd-dr-modal" id="scc-wd-dr-modal">
				<div class="wd-dr-modal-wrap">
					<div class="wd-dr-modal-header">
						<h3><?php echo  'Goodbyes are always hard. If you have a moment, please let us know how we can improve.'; ?></h3>
					</div>
					<div class="wd-dr-modal-body">
						<ul class="wd-de-reasons">
						<?php foreach ( $reasons as $reason ) { ?>
								<li data-placeholder="<?php echo esc_attr( $reason['placeholder'] ); ?>">
									<label>
										<input type="radio" name="selected-reason" value="<?php echo esc_attr( $reason['id'] ); ?>">
										<div class="wd-de-reason-icon"><?php echo wp_kses( $reason['icon'], $allowed_tags ); ?></div>
										<div class="wd-de-reason-text"><?php echo wp_kses( $reason['text'], $allowed_tags ); ?></div>
									</label>
								</li>
							<?php } ?>
						</ul>
						<div class="wd-dr-modal-reason-input"><textarea></textarea></div>
						<p class="wd-dr-modal-reasons-bottom">
						</p>
					</div>
					<div class="wd-dr-modal-footer">
						<a href="#" class="dont-bother-me wd-dr-button-secondary"><?php echo 'Skip & Deactivate'; ?></a>
						<button class="wd-dr-button-secondary wd-dr-cancel-modal"><?php echo 'Cancel'; ?></button>
						<button class="wd-dr-submit-modal scc-submit-feedback"><?php echo 'Submit & Deactivate'; ?></button>
					</div>
				</div>
			</div>
			<!-- user survey modal, initiates if the editing page has been used more than 9 times -->
			<div class="modal df-scc-modal fade in" id="user-scc-sv" style="padding-right: 0px; display: none;" role="dialog">
				<div class="df-scc-euiOverlayMask df-scc-euiOverlayMask--aboveHeader">
					<div class="df-scc-euiModal df-scc-euiModal--maxWidth-default df-scc-euiModal--confirmation">
						<button class="df-scc-euiButtonIcon df-scc-euiButtonIcon--text df-scc-euiModal__closeIcon" type="button" data-dismiss="modal" aria-label="Closes this modal window">
							<svg width="16" height="16" viewBox="0 0 16 16" xmlns="http://www.w3.org/2000/svg" class="df-scc-euiIcon df-scc-euiIcon--medium df-scc-euiButtonIcon__icon" focusable="false" role="img" aria-hidden="true">
								<path d="M7.293 8L3.146 3.854a.5.5 0 11.708-.708L8 7.293l4.146-4.147a.5.5 0 01.708.708L8.707 8l4.147 4.146a.5.5 0 01-.708.708L8 8.707l-4.146 4.147a.5.5 0 01-.708-.708L7.293 8z"></path>
							</svg>
						</button>
						<div class="df-scc-euiModal__flex">
							<div class="step1-wrapper">
								<div class="df-scc-euiModalHeader d-block pb-0">
									<div class="progress w-25">
										<div class="progress-bar bg-info" role="progressbar" style="width: 50%" aria-valuenow="20" aria-valuemin="0" aria-valuemax="100"></div>
										<div class="progress-bar bg-secondary" role="progressbar" style="width: 50%" aria-valuenow="20" aria-valuemin="0" aria-valuemax="100"></div>
									</div>
									<div class="df-scc-euiModalHeader__title pt-2">Rate your experience with our product...</div>
								</div>
								<div class="df-scc-euiModalBody">
									<div class="df-scc-euiModalBody__overflow d-flex align-items-center pb-0">
											<ul class="pagination pagination-lg me-3 mb-0 ratings-picker">
												<li class="page-item me-2">
													<span class="page-link text-dark" role="button">1</span>
												</li>
												<li class="page-item me-2"><span class="page-link text-dark" role="button">2</span></li>
												<li class="page-item me-2"><span class="page-link text-dark" role="button">3</span></li>
												<li class="page-item me-2"><span class="page-link text-dark" role="button">4</span></li>
												<li class="page-item"><span class="page-link text-dark" role="button">5</span></li>
											</ul>
											<p><i style="vertical-align: middle;" class="material-icons-outlined text-info">
												<svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 0 24 24" width="24px" fill="#75FBFD"><path d="M0 0h24v24H0z" fill="none"/><path d="M0 0h24v24H0z" fill="none"/><path d="M12 17.27 18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z"/></svg>
											</i>&nbsp;<span>Stars</span></p>
									</div>
								</div>
								<div class="df-scc-euiModalFooter">
									<a href="#" class="dont-bother-me wd-dr-button-secondary"><?php echo 'Skip & Deactivate'; ?></a>
								</div>
							</div>
							<div class="step2-wrapper d-none">
								<div class="df-scc-euiModalHeader d-block pb-0">
									<div class="progress w-25">
										<div class="progress-bar bg-secondary" role="progressbar" style="width: 50%" aria-valuenow="20" aria-valuemin="0" aria-valuemax="100"></div>
										<div class="progress-bar bg-info" role="progressbar" style="width: 50%" aria-valuenow="20" aria-valuemin="0" aria-valuemax="100"></div>
									</div>
									<div class="pt-2 d-flex align-items-center justify-content-between">
										<div class="df-scc-euiModalHeader__title">Anything that can be improved?</div>
										<p><i style="vertical-align: middle;" class="material-icons-outlined text-info">
											<svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 0 24 24" width="24px" fill="#75FBFD"><path d="M0 0h24v24H0z" fill="none"/><path d="M0 0h24v24H0z" fill="none"/><path d="M12 17.27 18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z"/></svg>
										</i>&nbsp;<span class="rating-chosen">5</span></p>
									</div>
								</div>
								<div class="df-scc-euiModalBody">
									<div class="df-scc-euiModalBody__overflow d-block align-items-center pb-0">
										<div class="form-group">
											<textarea id="comments-text-input" class="form-control h-auto" placeholder="Your feedback (optional)" rows="4"></textarea>
										</div>
										<div class="form-group" id="survey-username-input-wrapper">
											<label for="feedback-username-input">Your name (optional)</label>
											<input id="feedback-username-input" class="form-control" value="<?php echo esc_attr( $df_scc_user_name ); ?>" >
										</div>
										<div class="form-group" id="survey-email-input-wrapper">
											<label for="feedback-email-input">Your email address (optional)</label>
											<input id="feedback-email-input" class="form-control" value="<?php echo esc_attr( get_option( 'df_scc_emailsender', get_option( 'admin_email' ) ) ); ?>" >
										</div>
										<div class="scc-form-checkbox">
											<label class="scc-accordion_switch_button align-bottom" for="feedback-opt-in">
												<input checked type="checkbox" id="feedback-opt-in">
												<span class="scc-accordion_toggle_button round"></span>
											</label>
											<span><label role="button" for="feedback-opt-in" class="lblExtraSettingsEditCalc">I don't mind receiving a reply by email.</label>
											</span>
										</div>
										<div class="">
											<button id="comments-submit-btn" class="wd-dr-submit-modal">Submit &amp; Deactivate</button>
										</div>
									</div>
								</div>
								<div class="df-scc-euiModalFooter"></div>
							</div>
							<div class="step3-wrapper d-none">
								<div class="df-scc-euiModalHeader d-block mb-0">
									<div class="df-scc-euiModalHeader__title">
										<i style="vertical-align: sub;" class="material-icons-outlined bg-info rounded">
											<svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 0 24 24" width="24px" fill="#75FBFD"><path d="M0 0h24v24H0z" fill="none"/><path d="M9 16.2L4.8 12l-1.4 1.4L9 19 21 7l-1.4-1.4L9 16.2z"/></svg>
										</i>
										<span>Thanks for the feedback! The plugin is being deactivated.</span>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div><!-- /.modal -->
			<script type="text/javascript">
				(function($) {
					$(function() {
						const interactedWithFeedbackModal = <?php echo ( get_option( 'df-scc_feedback_invoke', null ) === 'disabled' ) ? 'true' : 'false'; ?>;
						const setupSurveyModal = (modal, deactivateLink) => {
							const firstStep = modal.querySelector('.step1-wrapper');
							const secondStep = modal.querySelector('.step2-wrapper');
							const thirdStep = modal.querySelector('.step3-wrapper');
							const closeBtn = modal.querySelector('[data-dismiss="modal"]');
							const emailInput = modal.querySelector('#feedback-email-input');
							const usernameInput = modal.querySelector( '#feedback-username-input' );
							const checkboxOptIn = modal.querySelector('#feedback-opt-in');
							const searchParams = new URLSearchParams(window.location.search);
							const dontBotherMe = modal.querySelector('.dont-bother-me');

							modal.classList.remove('d-none', 'fade');
							modal.style.display = 'block';

							dontBotherMe.addEventListener('click', evt => {
								modal.classList.add('d-none');
								modal.classList.add('fade');
								modal.style.display = 'none';
								window.location.href = deactivateLink;
							});

							const responseData = {
								rating: 0,
								text: '',
								email: emailInput.value,
								username: usernameInput.value,
								optedForEmail: checkboxOptIn.checked,
							}

							const ratingChosenText = modal.querySelector('.rating-chosen');

							const commentInput = modal.querySelector('#comments-text-input');
							const commentSubmitBtn = modal.querySelector('#comments-submit-btn');

							const ratingsPicker = modal.querySelector('.ratings-picker');
							ratingsPicker.querySelectorAll('li').forEach((li, index) => {
								li.addEventListener('click', evt => {
								firstStep.classList.add('d-none');
								secondStep.classList.remove('d-none');
								ratingChosenText.textContent = index + 1;
								responseData.rating = index + 1;
								});
							});

							commentInput.addEventListener('input', evt => {
								responseData.text = evt.target.value;
							});

							closeBtn.addEventListener('click', evt => {
								modal.classList.add('d-none');
								modal.classList.add('fade');
								modal.style.display = 'none';
							});

							emailInput.addEventListener('input', evt => {
								responseData.email = evt.target.value;
							});
						
							checkboxOptIn.addEventListener('change', evt => {
								responseData.optedForEmail = evt.target.checked;
								document.querySelector( '#survey-email-input-wrapper' ).classList.toggle( 'd-none' );
								document.querySelector( '#survey-username-input-wrapper' ).classList.toggle( 'd-none' );
								if (!evt.target.checked) {
									delete responseData.email;
									delete responseData.username;
								}
							});
							commentSubmitBtn.addEventListener('click', evt => {
								jQuery.ajax({
								url: '<?php echo esc_url_raw( add_query_arg( [ 'action' => 'scc_feedback_manage', '_wpnonce' => wp_create_nonce( 'uninstall-df-scc-calculator-page' ), 'source' => 'plugins_page' ], admin_url( 'admin-ajax.php' ) ) ); ?>',
								type: "POST",
								contentType: "application/json; charset=utf-8",
								dataType: "json",
								data: JSON.stringify(responseData),
								beforeSend: function () {
									commentSubmitBtn.disabled = true;
									commentSubmitBtn.textContent = 'Submitting...';
								},
								complete: function (data) {
									secondStep.classList.add('d-none');
									thirdStep.classList.remove('d-none');
									closeBtn.classList.add('d-none');
									setTimeout(() => {
										document.querySelector('#user-scc-sv').classList.remove('d-block');
										document.querySelector('#user-scc-sv').classList.add('fade', 'd-none');
										window.location.href = deactivateLink;
									}, 3000);
								}
								})
							});
						}
						var modal = $( '#scc-wd-dr-modal' );
						var deactivateLink = '';
						// Open modal
						$( '#the-list' ).on('click', '#deactivate-stylish-cost-calculator', function(e) {
							e.preventDefault();
							if ( interactedWithFeedbackModal ) {
								modal.addClass('modal-active');
								deactivateLink = $(this).attr('href');
								modal.find('a.dont-bother-me').attr('href', deactivateLink).css('float', 'left');
							} else {
								deactivateLink = $(this).attr('href');
								setupSurveyModal(document.getElementById('user-scc-sv'), deactivateLink);
							}
						});
						// Close modal; Cancel
						modal.on('click', 'button.wd-dr-cancel-modal', function(e) {
							e.preventDefault();
							modal.removeClass('modal-active');
						});
						// Reason change
						modal.on('click', 'input[type="radio"]', function () {
							var parent = $(this).parents('li');
							var isCustomReason = parent.data('customreason');
							var inputValue = $(this).val();
							if ( isCustomReason ) {
								$('ul.wd-de-reasons.wd-de-others-reasons li').removeClass('wd-de-reason-selected');
							} else {
								$('ul.wd-de-reasons li').removeClass('wd-de-reason-selected');
								if ( "other" != inputValue ) {
									$('ul.wd-de-reasons.wd-de-others-reasons').css('display', 'none');
								}
							}
							// Show if has custom reasons
							if ( "other" == inputValue ) {
								$('ul.wd-de-reasons.wd-de-others-reasons').css('display', 'flex');
							}
							parent.addClass('wd-de-reason-selected');
							inputValue != 1 ? $('.wd-dr-modal-reason-input').show() : $('.wd-dr-modal-reason-input').hide();
							$('.wd-dr-modal-reason-input textarea').attr('placeholder', parent.data('placeholder')).focus();
						});
						// Submit response
						modal.on('click', 'button.wd-dr-submit-modal.scc-submit-feedback', function(e) {
							e.preventDefault();
							var button = $(this);
							if ( button.hasClass('disabled') ) {
								return;
							}
							var $radio = $( 'input[type="radio"]:checked', modal );
							var $input = $('.wd-dr-modal-reason-input textarea');
							$.ajax({
								url: '<?php echo esc_url_raw( add_query_arg( [ 'action' => 'df_scc_uninstall_survey', 'nonce' => wp_create_nonce( 'uninstall-df-scc-calculator-page' ) ], admin_url( 'admin-ajax.php' ) ) ); ?>',
								type: 'POST',
                                contentType: 'application/json',
								data: JSON.stringify({
									answer: ( 0 !== $radio.length ) ? $radio.val() : '',
									comment: ( 0 !== $input.length ) ? $input.val().trim() : '',
									site: window.location.origin
								}),
								beforeSend: function() {
									button.addClass('disabled');
									button.text('Processing...');
								},
								complete: function() {
									window.location.href = deactivateLink;
								}
							});
						});
					});
				}(jQuery));
			</script>
            ?>
            <?php
    }

    /**
     * Deactivation modal styles
     */
    private function deactivation_modal_styles() {
        ?>
			<style type="text/css">
				.form-group {
					margin-bottom: .7rem;
				}
				.d-none {
					display: none;
				}
				.d-block {
					display: block !important;
				}
				.scc-form-checkbox {
					margin: 0 0 1rem 0;
				}

				.d-flex {
					display: flex !important;
				}
				.align-items-center {
					align-items: center !important;
				}
				.pagination {
    display: inline-block;
    padding-left: 0;
    margin: 20px 0;
    border-radius: 4px;
}
.pagination>li {
    cursor: pointer;
}
.pagination>li {
    display: inline;
}

#user-scc-sv .pagination>li {
    cursor: pointer;
}

#user-scc-sv .pagination > li > span {
    color: #70bbb1;
}
.pagination-lg>li:first-child>a, .pagination-lg>li:first-child>span {
    border-top-left-radius: 6px;
    border-bottom-left-radius: 6px;
}
.pagination>li:first-child>a, .pagination>li:first-child>span {
    margin-left: 0;
    border-top-left-radius: 4px;
    border-bottom-left-radius: 4px;
}
.pagination-lg>li>a, .pagination-lg>li>span {
    padding: 10px 16px;
    font-size: 18px;
    line-height: 1.3333333;
}
.pagination>li>a, .pagination>li>span {
    position: relative;
    float: left;
    padding: 6px 12px;
    margin-left: -1px;
    line-height: 1.42857143;
    color: #337ab7;
    text-decoration: none;
    background-color: #fff;
    border: 1px solid #ddd;
}
.pagination-lg .page-item:first-child .page-link {
    border-top-left-radius: .3rem;
    border-bottom-left-radius: .3rem;
}

.pagination-lg .page-link {
    padding: .75rem 1.5rem;
    font-size: 1.25rem;
}

.page-link:hover {
    z-index: 2;
    color: #0a58ca;
    background-color: #e9ecef;
    border-color: #dee2e6;
}


				.modal {
					position: fixed;
					top: 0;
					left: 0;
					z-index: 1055;
					display: none;
					width: 100%;
					height: 100%;
					overflow-x: hidden;
					overflow-y: auto;
					outline: 0;
				}
				.df-scc-euiOverlayMask {
					position: fixed;
					top: 0;
					left: 0;
					right: 0;
					bottom: 0;
					display: -webkit-flex;
					display: flex;
					-webkit-align-items: center;
					align-items: center;
					-webkit-justify-content: center;
					justify-content: center;
					padding-bottom: 10vh;
					background: rgba(27, 24, 24, 0.92);
					z-index: 8000;
					}

					.df-scc-euiModal--confirmation {
					min-width: 400px;
					}

					.df-scc-euiModal--maxWidth-default {
					max-width: 768px;
					}

					.df-scc-euiModal {
					border: 1px solid #D3DAE6;
					box-shadow: 0 40px 64px 0 rgba(65, 78, 101, 0.1), 0 24px 32px 0 rgba(65, 78, 101, 0.1), 0 16px 16px 0 rgba(65, 78, 101, 0.1), 0 8px 8px 0 rgba(65, 78, 101, 0.1), 0 4px 4px 0 rgba(65, 78, 101, 0.1), 0 2px 2px 0 rgba(65, 78, 101, 0.1);
					border-color: #c6cad1;
					border-top-color: #e3e4e8;
					border-bottom-color: #aaafba;
					display: -webkit-flex;
					display: flex;
					position: relative;
					background-color: #fff;
					border-radius: 4px;
					z-index: 8000;
					min-width: 400px;
					-webkit-animation: euiModal 350ms cubic-bezier(0.34, 1.61, 0.7, 1);
					animation: euiModal 350ms cubic-bezier(0.34, 1.61, 0.7, 1);
					}

					.df-scc-euiModal__closeIcon {
					background-color: rgba(255, 255, 255, 0.9);
					position: absolute;
					right: 4px;
					top: 4px;
					z-index: 3;
					}

					.df-scc-euiModal__closeIcon {
					background-color: rgba(255, 255, 255, 0.9);
					position: absolute;
					right: 4px;
					top: 4px;
					z-index: 3;
					}

					.df-scc-euiButtonIcon--text {
					color: #343741;
					}

					.df-scc-euiButtonIcon {
					display: inline-block;
					-webkit-appearance: none;
					-moz-appearance: none;
					appearance: none;
					cursor: pointer;
					height: 40px;
					line-height: 40px;
					text-align: center;
					white-space: nowrap;
					max-width: 100%;
					vertical-align: middle;
					font-family: "Inter UI", -apple-system, BlinkMacSystemFont, "Segoe UI", Helvetica, Arial, sans-serif, "Apple Color Emoji", "Segoe UI Emoji", "Segoe UI Symbol";
					font-weight: 400;
					letter-spacing: -.005em;
					-webkit-text-size-adjust: 100%;
					-ms-text-size-adjust: 100%;
					-webkit-font-kerning: normal;
					font-kerning: normal;
					font-size: 16px;
					font-size: 1rem;
					line-height: 1.5;
					text-decoration: none;
					border: solid 1px transparent;
					transition: all 250ms ease-in-out;
					border: none;
					background-color: transparent;
					box-shadow: none;
					height: auto;
					min-height: 24px;
					min-width: 24px;
					line-height: 0;
					padding: 4px;
					border-radius: 4px;
					}

					.df-scc-euiModal .df-scc-euiModal__flex {
					-webkit-flex: 1 1 auto;
					flex: 1 1 auto;
					display: -webkit-flex;
					display: flex;
					-webkit-flex-direction: column;
					flex-direction: column;
					max-height: 85vh;
					overflow: hidden;
					}

					.df-scc-euiModalHeader {
					display: -webkit-flex;
					display: flex;
					-webkit-justify-content: space-between;
					justify-content: space-between;
					-webkit-align-items: center;
					align-items: center;
					padding: 24px 40px 16px 24px;
					-webkit-flex-grow: 0;
					flex-grow: 0;
					-webkit-flex-shrink: 0;
					flex-shrink: 0;
					}

					.df-scc-euiModalHeader__title {
					/* color: #1a1c21; */
					font-size: 22px;
					line-height: 2.5rem;
					font-weight: 500;
					letter-spacing: -.04em;
					}

					.df-scc-euiModalBody {
					-webkit-flex-grow: 1;
					flex-grow: 1;
					overflow: hidden;
					display: -webkit-flex;
					display: flex;
					-webkit-flex-direction: column;
					flex-direction: column;
					}

					.df-scc-euiModalBody .df-scc-euiModalBody__overflow {
					scrollbar-width: thin;
					height: 100%;
					overflow-y: auto;
					-webkit-mask-image: linear-gradient(to bottom, rgba(255, 0, 0, 0.1) 0%, red 7.5px, red calc(100% - 7.5px), rgba(255, 0, 0, 0.1) 100%);
					mask-image: linear-gradient(to bottom, rgba(255, 0, 0, 0.1) 0%, red 7.5px, red calc(100% - 7.5px), rgba(255, 0, 0, 0.1) 100%);
					padding: 8px 24px 24px;
					}

					.df-scc-euiText {
					color: #343741;
					font-weight: 400;
					font-size: 16px;
					font-size: 1rem;
					line-height: 1.5;
					color: inherit;
					line-height: 1.5rem;
					}

					.df-scc-euiText p {
					margin-bottom: 1.5rem;
					font-size: inherit;
					}

					.df-scc-euiModalFooter {
					display: -webkit-flex;
					display: flex;
					-webkit-justify-content: flex-start;
					justify-content: flex-start;
					padding: 16px 24px 24px;
					-webkit-flex-grow: 0;
					flex-grow: 0;
					-webkit-flex-shrink: 0;
					flex-shrink: 0;
					}
					.step1-wrapper .df-scc-euiModalFooter {
						border-top: 2px solid #dbd9d9;
					}

				#user-scc-sv .df-scc-euiModal {
					width: 700px;
				}

				#user-scc-sv .pagination {
					display: inline;
				}

				#user-scc-sv .pagination > li > span {
					color: #1d2327;
				}

				#user-scc-sv .text-info {
					color: #70bbb1;
				}

				#user-scc-sv .align-items-center p {
					font-size: 16px;
					margin: 0 0 0 25px;
				}

				#user-scc-sv .pagination>li {
					cursor: pointer;
				}

				#user-scc-sv .df-scc-euiModalHeader__title {
					font-weight: 400;
				}

				#user-scc-sv label {
					font-weight: 400;
					font-size: 15px;
					line-height: 1.9;
				}
				textarea.form-control {
					min-height: calc(1.5em + .75rem + 2px);
				}
				.form-control:focus, .form-select:focus {
					border-color: #7A83F5 !important;
					outline: 0 !important;
					box-shadow: 0 0 0 0.8px #7A83F5 !important;
				}
				.form-control, .form-select {
					width: 100% !important;
					padding: 0.375rem 0.75rem !important;
					line-height: 1.5 !important;
					color: #212529 !important;
					border: 1px solid #BFC1FD !important;
					background-color: #f8f8ff !important;
					-webkit-appearance: none !important;
					-moz-appearance: none !important;
					border-radius: 0.25rem !important;
					transition: border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out !important;
				}
				.wd-dr-modal {
					position: fixed;
					z-index: 99999;
					top: 0;
					right: 0;
					bottom: 0;
					left: 0;
					background: rgba(0,0,0,0.5);
					display: none;
					box-sizing: border-box;
					overflow: scroll;
				}
				.wd-dr-modal * {
					box-sizing: border-box;
				}
				.wd-dr-modal.modal-active {
					display: block;
				}
				.wd-dr-modal-wrap {
					max-width: 870px;
					width: 100%;
					position: relative;
					margin: 10% auto;
					background: #fff;
				}
				.wd-dr-modal-header {
					border-bottom: 1px solid #E8E8E8;
					padding: 20px 20px 18px 20px;
				}
				.wd-dr-modal-header h3 {
					line-height: 1.8;
					margin: 0;
					color: #4A5568;
				}
				.wd-dr-modal-body {
					padding: 5px 20px 20px 20px;
				}
				.wd-dr-modal-body .reason-input {
					margin-top: 5px;
					margin-left: 20px;
				}
				.wd-dr-modal-footer {
					border-top: 1px solid #E8E8E8;
					padding: 20px;
					text-align: right;
				}
				.wd-dr-modal-reasons-bottom {
					margin: 0;
				}
				ul.wd-de-reasons {
					display: flex;
					margin: 0 -5px 0 -5px;
					padding: 15px 0 20px 0;
				}
				ul.wd-de-reasons.wd-de-others-reasons {
					padding-top: 0;
					display: none;
				}
				ul.wd-de-reasons li {
					padding: 0 5px;
					margin: 0;
					width: 14.26%;
				}
				ul.wd-de-reasons label {
					position: relative;
					border: 1px solid #E8E8E8;
					border-radius: 4px;
					display: block;
					text-align: center;
					height: 100%;
					padding: 15px 3px 8px 3px;
				}
				ul.wd-de-reasons label:after {
					width: 0;
					height: 0;
					border-left: 8px solid transparent;
					border-right: 8px solid transparent;
					border-top: 10px solid #3B86FF;
					position: absolute;
					left: 50%;
					top: 100%;
					margin-left: -8px;
				}
				ul.wd-de-reasons label input[type="radio"] {
					position: absolute;
					left: 0;
					right: 0;
					visibility: hidden;
				}
				.wd-de-reason-text {
					color: #4A5568;
					font-size: 13px;
				}
				.wd-de-reason-icon {
					margin-bottom: 7px;
				}
				ul.wd-de-reasons li.wd-de-reason-selected label {
					background-color: #3B86FF;
					border-color: #3B86FF;
				}
				li.wd-de-reason-selected .wd-de-reason-icon svg,
				li.wd-de-reason-selected .wd-de-reason-icon svg g {
					fill: #fff;
				}
				li.wd-de-reason-selected .wd-de-reason-text {
					color: #fff;
				}
				ul.wd-de-reasons li.wd-de-reason-selected label:after {
					content: "";
				}
				.wd-dr-modal-reason-input {
					margin-bottom: 15px;
					display: none;
				}
				.wd-dr-modal-reason-input textarea {
					background: #FAFAFA;
					border: 1px solid #287EB8;
					border-radius: 4px;
					width: 100%;
					height: 100px;
					color: #524242;
					font-size: 13px;
					line-height: 1.4;
					padding: 11px 15px;
					resize: none;
				}
				.wd-dr-modal-reason-input textarea:focus {
					outline: 0 none;
					box-shadow: 0 0 0;
				}
				.wd-dr-button-secondary, .wd-dr-button-secondary:hover {
					border: 1px solid #EBEBEB;
					border-radius: 3px;
					font-size: 13px;
					line-height: 1.5;
					color: #718096;
					padding: 5px 12px;
					cursor: pointer;
					background-color: transparent;
					text-decoration: none;
				}
				.wd-dr-submit-modal, .wd-dr-submit-modal:hover {
					border: 1px solid #3B86FF;
					background-color: #3B86FF;
					border-radius: 3px;
					font-size: 13px;
					line-height: 1.5;
					color: #fff;
					padding: 5px 12px;
					cursor: pointer;
					margin-left: 4px;
				}
			</style>
		<?php
    }

    /**
     * Plugin uninstall reasons
     *
     * @return array
     */
    private function get_uninstall_reasons() {
        $reasons = [
            [
                'id'          => '1',
                'text'        => "It's just a temp. deactivation",
                'placeholder' => '',
                'icon'        => '<svg xmlns="http://www.w3.org/2000/svg" width="23" height="23" viewBox="0 0 23 23"><g fill="none"><g fill="#3B86FF"><path d="M11.5 0C17.9 0 23 5.1 23 11.5 23 17.9 17.9 23 11.5 23 10.6 23 9.6 22.9 8.8 22.7L8.8 22.6C9.3 22.5 9.7 22.3 10 21.9 10.3 21.6 10.4 21.3 10.4 20.9 10.8 21 11.1 21 11.5 21 16.7 21 21 16.7 21 11.5 21 6.3 16.7 2 11.5 2 6.3 2 2 6.3 2 11.5 2 13 2.3 14.3 2.9 15.6 2.7 16 2.4 16.3 2.2 16.8L2.1 17.1 2.1 17.3C2 17.5 2 17.7 2 18 0.7 16.1 0 13.9 0 11.5 0 5.1 5.1 0 11.5 0ZM6 13.6C6 13.7 6.1 13.8 6.1 13.9 6.3 14.5 6.2 15.7 6.1 16.4 6.1 16.6 6 16.9 6 17.1 6 17.1 6.1 17.1 6.1 17.1 7.1 16.9 8.2 16 9.3 15.5 9.8 15.2 10.4 15 10.9 15 11.2 15 11.4 15 11.6 15.2 11.9 15.4 12.1 16 11.6 16.4 11.5 16.5 11.3 16.6 11.1 16.7 10.5 17 9.9 17.4 9.3 17.7 9 17.9 9 18.1 9.1 18.5 9.2 18.9 9.3 19.4 9.3 19.8 9.4 20.3 9.3 20.8 9 21.2 8.8 21.5 8.5 21.6 8.1 21.7 7.9 21.8 7.6 21.9 7.3 21.9L6.5 22C6.3 22 6 21.9 5.8 21.9 5 21.8 4.4 21.5 3.9 20.9 3.3 20.4 3.1 19.6 3 18.8L3 18.5C3 18.2 3 17.9 3.1 17.7L3.1 17.6C3.2 17.1 3.5 16.7 3.7 16.3 4 15.9 4.2 15.4 4.3 15 4.4 14.6 4.4 14.5 4.6 14.2 4.6 13.9 4.7 13.7 4.9 13.6 5.2 13.2 5.7 13.2 6 13.6ZM11.7 11.2C13.1 11.2 14.3 11.7 15.2 12.9 15.3 13 15.4 13.1 15.4 13.2 15.4 13.4 15.3 13.8 15.2 13.8 15 13.9 14.9 13.8 14.8 13.7 14.6 13.5 14.4 13.2 14.1 13.1 13.5 12.6 12.8 12.3 12 12.2 10.7 12.1 9.5 12.3 8.4 12.8 8.3 12.8 8.2 12.8 8.1 12.8 7.9 12.8 7.8 12.4 7.8 12.2 7.7 12.1 7.8 11.9 8 11.8 8.4 11.7 8.8 11.5 9.2 11.4 10 11.2 10.9 11.1 11.7 11.2ZM16.3 5.9C17.3 5.9 18 6.6 18 7.6 18 8.5 17.3 9.3 16.3 9.3 15.4 9.3 14.7 8.5 14.7 7.6 14.7 6.6 15.4 5.9 16.3 5.9ZM8.3 5C9.2 5 9.9 5.8 9.9 6.7 9.9 7.7 9.2 8.4 8.2 8.4 7.3 8.4 6.6 7.7 6.6 6.7 6.6 5.8 7.3 5 8.3 5Z"/></g></g></svg>',
            ],
            [
                'id'          => '2',
                'text'        => 'I want a free plugin',
                'placeholder' => ( 'Could you tell us a bit more?' ),
                'icon'        => '<svg xmlns="http://www.w3.org/2000/svg" width="23" height="23" viewBox="0 0 23 23"><g fill="none"><g fill="#3B86FF"><path d="M17.1 14L22.4 19.3C23.2 20.2 23.2 21.5 22.4 22.4 21.5 23.2 20.2 23.2 19.3 22.4L19.3 22.4 14 17.1C15.3 16.3 16.3 15.3 17.1 14L17.1 14ZM8.6 0C13.4 0 17.3 3.9 17.3 8.6 17.3 13.4 13.4 17.2 8.6 17.2 3.9 17.2 0 13.4 0 8.6 0 3.9 3.9 0 8.6 0ZM8.6 2.2C5.1 2.2 2.2 5.1 2.2 8.6 2.2 12.2 5.1 15.1 8.6 15.1 12.2 15.1 15.1 12.2 15.1 8.6 15.1 5.1 12.2 2.2 8.6 2.2ZM8.6 3.6L8.6 5C6.6 5 5 6.6 5 8.6L5 8.6 3.6 8.6C3.6 5.9 5.9 3.6 8.6 3.6L8.6 3.6Z"/></g></g></svg>',
            ],
            [
                'id'          => '3',
                'text'        => "I couldn't get it to work",
                'placeholder' => ( 'Could you tell us a bit more?' ),
                'icon'        => '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="17" viewBox="0 0 24 17"><g fill="none"><g fill="#3B86FF"><path d="M19.4 0C19.7 0.6 19.8 1.3 19.8 2 19.8 3.2 19.4 4.4 18.5 5.3 17.6 6.2 16.5 6.7 15.2 6.7 15.2 6.7 15.2 6.7 15.2 6.7 14 6.7 12.9 6.2 12 5.3 11.2 4.4 10.7 3.3 10.7 2 10.7 1.3 10.8 0.6 11.1 0L7.6 0 7 0 6.5 0 6.5 5.7C6.3 5.6 5.9 5.3 5.6 5.1 5 4.6 4.3 4.3 3.5 4.3 3.5 4.3 3.5 4.3 3.4 4.3 1.6 4.4 0 5.9 0 7.9 0 8.6 0.2 9.2 0.5 9.7 1.1 10.8 2.2 11.5 3.5 11.5 4.3 11.5 5 11.2 5.6 10.8 6 10.5 6.3 10.3 6.5 10.2L6.5 10.2 6.5 17 6.5 17 7 17 7.6 17 22.5 17C23.3 17 24 16.3 24 15.5L24 0 19.4 0Z"/></g></g></svg>',
            ],
            [
                'id'          => '4',
                'text'        => 'I found a better plugin',
                'placeholder' => ( 'Which plugin?' ),
                'icon'        => '<svg xmlns="http://www.w3.org/2000/svg" width="23" height="23" viewBox="0 0 23 23"><g fill="none"><g fill="#3B86FF"><path d="M11.5 0C17.9 0 23 5.1 23 11.5 23 17.9 17.9 23 11.5 23 10.6 23 9.6 22.9 8.8 22.7L8.8 22.6C9.3 22.5 9.7 22.3 10 21.9 10.3 21.6 10.4 21.3 10.4 20.9 10.8 21 11.1 21 11.5 21 16.7 21 21 16.7 21 11.5 21 6.3 16.7 2 11.5 2 6.3 2 2 6.3 2 11.5 2 13 2.3 14.3 2.9 15.6 2.7 16 2.4 16.3 2.2 16.8L2.1 17.1 2.1 17.3C2 17.5 2 17.7 2 18 0.7 16.1 0 13.9 0 11.5 0 5.1 5.1 0 11.5 0ZM6 13.6C6 13.7 6.1 13.8 6.1 13.9 6.3 14.5 6.2 15.7 6.1 16.4 6.1 16.6 6 16.9 6 17.1 6 17.1 6.1 17.1 6.1 17.1 7.1 16.9 8.2 16 9.3 15.5 9.8 15.2 10.4 15 10.9 15 11.2 15 11.4 15 11.6 15.2 11.9 15.4 12.1 16 11.6 16.4 11.5 16.5 11.3 16.6 11.1 16.7 10.5 17 9.9 17.4 9.3 17.7 9 17.9 9 18.1 9.1 18.5 9.2 18.9 9.3 19.4 9.3 19.8 9.4 20.3 9.3 20.8 9 21.2 8.8 21.5 8.5 21.6 8.1 21.7 7.9 21.8 7.6 21.9 7.3 21.9L6.5 22C6.3 22 6 21.9 5.8 21.9 5 21.8 4.4 21.5 3.9 20.9 3.3 20.4 3.1 19.6 3 18.8L3 18.5C3 18.2 3 17.9 3.1 17.7L3.1 17.6C3.2 17.1 3.5 16.7 3.7 16.3 4 15.9 4.2 15.4 4.3 15 4.4 14.6 4.4 14.5 4.6 14.2 4.6 13.9 4.7 13.7 4.9 13.6 5.2 13.2 5.7 13.2 6 13.6ZM11.7 11.2C13.1 11.2 14.3 11.7 15.2 12.9 15.3 13 15.4 13.1 15.4 13.2 15.4 13.4 15.3 13.8 15.2 13.8 15 13.9 14.9 13.8 14.8 13.7 14.6 13.5 14.4 13.2 14.1 13.1 13.5 12.6 12.8 12.3 12 12.2 10.7 12.1 9.5 12.3 8.4 12.8 8.3 12.8 8.2 12.8 8.1 12.8 7.9 12.8 7.8 12.4 7.8 12.2 7.7 12.1 7.8 11.9 8 11.8 8.4 11.7 8.8 11.5 9.2 11.4 10 11.2 10.9 11.1 11.7 11.2ZM16.3 5.9C17.3 5.9 18 6.6 18 7.6 18 8.5 17.3 9.3 16.3 9.3 15.4 9.3 14.7 8.5 14.7 7.6 14.7 6.6 15.4 5.9 16.3 5.9ZM8.3 5C9.2 5 9.9 5.8 9.9 6.7 9.9 7.7 9.2 8.4 8.2 8.4 7.3 8.4 6.6 7.7 6.6 6.7 6.6 5.8 7.3 5 8.3 5Z"/></g></g></svg>',
            ],
            [
                'id'          => '5',
                'text'        => "I don't need a price list anymore",
                'placeholder' => ( 'Could you tell us a bit more?' ),
                'icon'        => '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="17" viewBox="0 0 24 17"><g fill="none"><g fill="#3B86FF"><path d="M23.5 9C23.5 9 23.5 8.9 23.5 8.9 23.5 8.9 23.5 8.9 23.5 8.9 23.4 8.6 23.2 8.3 23 8 22.2 6.5 20.6 3.7 19.8 2.6 18.8 1.3 17.7 0 16.1 0 15.7 0 15.3 0.1 14.9 0.2 13.8 0.6 12.6 1.2 12.3 2.7L11.7 2.7C11.4 1.2 10.2 0.6 9.1 0.2 8.7 0.1 8.3 0 7.9 0 6.3 0 5.2 1.3 4.2 2.6 3.4 3.7 1.8 6.5 1 8 0.8 8.3 0.6 8.6 0.5 8.9 0.5 8.9 0.5 8.9 0.5 8.9 0.5 8.9 0.5 9 0.5 9 0.2 9.7 0 10.5 0 11.3 0 14.4 2.5 17 5.5 17 7.3 17 8.8 16.1 9.8 14.8L14.2 14.8C15.2 16.1 16.7 17 18.5 17 21.5 17 24 14.4 24 11.3 24 10.5 23.8 9.7 23.5 9ZM5.5 15C3.6 15 2 13.2 2 11 2 8.8 3.6 7 5.5 7 7.4 7 9 8.8 9 11 9 13.2 7.4 15 5.5 15ZM18.5 15C16.6 15 15 13.2 15 11 15 8.8 16.6 7 18.5 7 20.4 7 22 8.8 22 11 22 13.2 20.4 15 18.5 15Z"/></g></g></svg>',
            ],
            [
                'id'          => '6',
                'text'        => 'Other',
                'placeholder' => ( 'Could you tell us a bit more?' ),
                'icon'        => '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="23" viewBox="0 0 24 6"><g fill="none"><g fill="#3B86FF"><path d="M3 0C4.7 0 6 1.3 6 3 6 4.7 4.7 6 3 6 1.3 6 0 4.7 0 3 0 1.3 1.3 0 3 0ZM12 0C13.7 0 15 1.3 15 3 15 4.7 13.7 6 12 6 10.3 6 9 4.7 9 3 9 1.3 10.3 0 12 0ZM21 0C22.7 0 24 1.3 24 3 24 4.7 22.7 6 21 6 19.3 6 18 4.7 18 3 18 1.3 19.3 0 21 0Z"/></g></g></svg>',
            ],
        ];

        return $reasons;
    }
}

new UninstallSurveyModal();
