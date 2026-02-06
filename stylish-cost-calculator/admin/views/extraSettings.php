<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}
$isSCCFreeVersion = defined( 'STYLISH_COST_CALCULATOR_VERSION' );
global $current_user;
$df_scc_user_name = ! empty( $current_user->display_name ) ? $current_user->display_name : $current_user->user_login;
?>
<!-- FOR LATER -->
 <!-- Payments modal -->
<div class="scc-dashboard-modal modal fade" id="paymentSettingsModal" tabindex="-1" aria-labelledby="paymentSettingsModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable ">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="wordingsModalLabel">Payment Settings</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body pb-4">

	  		<!-- Start Payment processing section -->
		<div class="editing-action-cards action-payment scc-payment-settings mb-0 py-0">
			<div class="card-action-btns mx-3 has-checkmark
			<?php
?>
			"
>
			<div class="d-flex mb-3 scc-payment-methods">
				<button class="btn btn-cards me-3 <?php echo $isPayPalEnabled ? 'active' : ''; ?>" onclick="doPaypalSetupModal(<?php echo intval( $f1->id ); ?>)" data-setting-tooltip-type="payment-option-paypal-tt" data-bs-original-title="" title=""><span class="material-icons">done</span>Paypal</button>
				<button class="btn btn-cards me-3 <?php echo $isStripeEnabled ? 'active' : ''; ?>" onclick="<?php echo $isStripeSetupDone ? 'toggleStripe(this)' : 'stripeOptionsModal(this)'; ?>" data-setting-tooltip-type="payment-option-stripe-tt"  data-bs-original-title="" title=""  <?php echo $isStripeSetupDone ? esc_attr( $stripeDataAttr ) : ''; ?>><span class="material-icons">done</span><span>Stripe</span></button>
				<button class="btn btn-cards me-3 
								 <?php
                        if ( ! $isSCCFreeVersion ) {
                            if ( ! $isWoocommerceActive ) {
                                echo 'disabled tooltipadmin-right';
                            }
                        }

                        if ( $isWoocommerceCheckoutEnabled ) {
                            echo 'active';
                        }

?>
												" 
												<?php
            if ( ! $isSCCFreeVersion ) {
                if ( ! $isWoocommerceActive ) {
                    echo "data-tooltip='Please enable woocommerce'";
                }
            }
?>
												 data-setting-tooltip-type="payment-option-woocommerce-tt"  data-bs-original-title="" title=""><span class="material-icons">done</span>Woocommerce</button>
			</div>
	 
												 <div class="scc-form-checkbox	scc-email-quote-before-checkout" style="margin: 10px 0 0 0" >
				<label class="scc-accordion_switch_button" for="force-email-quote">
					<input 
					<?php
                    if ( $isSCCFreeVersion ) {
                        echo 'disabled';
                    }
?>
					 type="checkbox" id="force-email-quote" <?php echo $isForceQuoteFormEnabled ? 'checked' : ''; ?> onchange="setForceQuoteFormStatus(this, event)">
					<span class="scc-accordion_toggle_button round"></span>
				</label>
				<span><label for="force-email-quote" class="lblExtraSettingsEditCalc" data-setting-tooltip-type="force-email-form-before-checkout-tt" data-bs-original-title="" title="">Force Email Form before Checkout
						<i class="material-icons-outlined with-tooltip"  style="margin-right:5px">help_outline</i>
					</label>						
				</span>
			</div>
			</div>

		</div><!-- End Payment processing section -->



	  </div>
    </div>
  </div>
</div>



<!-- Form builder modal -->
<div class="scc-dashboard-modal modal fade" id="formBuilderModal" tabindex="-1" aria-labelledby="formBuilderModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable ">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="wordingsModalLabel">Email Quote | Form Builder</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body pb-4">

		<!-- QUOTE FORM SECTION -->
		<div class="editing-action-cards action-quoteform scc-quote-form-settings mb-0 py-0">
			<div class="card-action-btns mx-3 mb-3
			<?php
            if ( $isSCCFreeVersion ) {
                echo 'disabled use-tooltip-child-nodes';
            }
?>
			">
				<div class="btns-container d-inline-block">
				<?php foreach ( $formFieldsArray as $fieldIndex => $fieldValue ) { ?>
					<?php
        $fieldKey   = array_keys( $fieldValue )[0];
				    $fieldProps = $fieldValue[ $fieldKey ];
				    ?>
					<button class="btn btn-cards disabled" data-btn-fieldtype="custom" data-field-key="<?php echo esc_attr( $fieldKey ); ?>">
						<span><?php echo esc_attr( $fieldProps['name'] ); ?></span>
						<i class="scc-icon-formbuilder material-icons" data-form-builder-action-type="edit">edit</i>
					</button>
				<?php } ?>
				</div>
				<button class="btn btn-cards btn-plus 
				<?php
                if ( $isSCCFreeVersion ) {
                    echo 'disabled';
                }
?>
				" data-btn-fieldtype="more-fields" onclick="doFormFieldsSetup(this, event, <?php echo $isSCCFreeVersion ? 'false' : 'true'; ?>)">
					<span class="material-icons">done</span>+
				</button>
				
				<div class="scc-form-checkbox" style="margin: 10px 0 0 0">
				<label class="scc-accordion_switch_button" for="toggle-build-quote">
					<input type="checkbox" id="toggle-build-quote" 
					<?php
    echo $ShowFormBuilderOnDetails ? 'checked' : '';

if ( $isSCCFreeVersion ) {
    echo 'disabled';
}
?>
					 onchange="toggleFormBuilderOnDetails(this)">
					<span class="scc-accordion_toggle_button round"></span>
				</label>
				<span><label for="toggle-build-quote" class="lblExtraSettingsEditCalc" data-setting-tooltip-type="require-acceptance-tt" data-bs-original-title="" title="">Require acceptance (GDPR/Terms & Conditions)
				<i class="material-icons-outlined with-tooltip"  style="margin-right:5px">help_outline</i></label>
				</span>
			</div>
			</div>
		</div>
		<!-- END FORM SECTION -->



	  </div>
    </div>
  </div>
</div>

<div class="row mt-2 scc-no-gutter">
</div>
<div id="yourNameModal" style="display:none">
	<h4 style="font-weight: bolder;">Add New Field</h4>
	<div class="form-group">
		<label for="" style="font-weight: normal;">Field Name</label>
		<input class="from-control" type="text" name="" style="width: 100%;">
	</div>
	<div class="form-group">
		<label for="" style="font-weight: normal;">Field Description</label>
		<input class="from-control" type="text" name="" style="width: 100%;">
	</div>
	<div class="form-group">
		<label for="" style="font-weight: normal;">Field Type</label>
		<select name="form-field-type" class="df-scc-eui-Select" aria-label="Use aria labels when no actual label is in use">
			<option value="0">Select A Type</option>
			<option value="date">Date</option>
			<option value="address">Address</option>
			<option value="phone">Phone</option>
			<option value="text" selected="">Text</option>
			<option value="email">Email</option>
		</select>
	</div>
	<div class="scc-form-checkbox">
		<input type="checkbox" name="is-mandatory"><label class="df-scc-euiFormLabel df-scc-euiFormRow__label" for="is-mandatory">Make Mandatory</label>
	</div>
	<div class="row">
		<div class="btn-group col-md-12 justify-content-end">
			<button class="btn " onclick="sssclose(this)">Cancel</button>
			<button class="btn " onclick="sssclose(this)" style="background-color: #006BB4;color:white">Save</button>
		</div>
	</div>
</div>
<div id="addNewFieldModal" style="display:none" class="fade in" role="dialog">
	<div class="df-scc-euiOverlayMask df-scc-euiOverlayMask--aboveHeader">
		<div class="df-scc-euiModal df-scc-euiModal--maxWidth-default df-scc-euiModal--confirmation">
			<button class="df-scc-euiButtonIcon df-scc-euiButtonIcon--text df-scc-euiModal__closeIcon" type="button" data-dismiss="modal"><svg width="16" height="16" viewBox="0 0 16 16" xmlns="http://www.w3.org/2000/svg" class="df-scc-euiIcon df-scc-euiIcon--medium df-scc-euiButtonIcon__icon" focusable="false" role="img" aria-hidden="true">
					<path d="M7.293 8L3.146 3.854a.5.5 0 11.708-.708L8 7.293l4.146-4.147a.5.5 0 01.708.708L8.707 8l4.147 4.146a.5.5 0 01-.708.708L8 8.707l-4.146 4.147a.5.5 0 01-.708-.708L7.293 8z">
					</path>
				</svg></button>
			<form class="df-scc-euiModal__flex" onsubmit="addOrUpdateFormField(event, this)">
				<div class="df-scc-euiModalHeader">
					<div class="df-scc-euiModalHeader__title">Add New Field</div>
				</div>
				<div class="df-scc-euiModalBody">
					<div class="df-scc-euiModalBody__overflow">
						<div class="df-scc-euiText df-scc-euiText--medium">
							<div class="df-scc-euiFormRow">
								<div class="df-scc-euiFormRow__labelWrapper">
									<label class="trn df-scc-euiFormLabel df-scc-euiFormRow__label">Field Name</label>
								</div>
								<div class="df-scc-euiFormRow__fieldWrapper">
									<div class="df-scc-euiFormControlLayout">
										<div class="df-scc-euiFormControlLayout__childrenWrapper">
											<input type="text" name="field_name" class="df-scc-euiFieldText">
										</div>
									</div>
									<span class="text-danger" style="display: none; font-size: .75rem;">This field cannot be
										empty!</span>
								</div>
							</div>
							<div class="df-scc-euiFormRow">
								<div class="df-scc-euiFormRow__labelWrapper">
									<label class="trn df-scc-euiFormLabel df-scc-euiFormRow__label">Field
										Description</label>
								</div>
								<div class="df-scc-euiFormRow__fieldWrapper">
									<div class="df-scc-euiFormControlLayout">
										<div class="df-scc-euiFormControlLayout__childrenWrapper"><input type="text" name="field_description" class="df-scc-euiFieldText"></div>
									</div>
									<span class="text-danger" style="display: none; font-size: .75rem;">This field cannot be
										empty!</span>
								</div>
							</div>
							<div class="df-scc-euiFormRow">
								<div class="df-scc-euiFormRow__labelWrapper">
									<label class="trn df-scc-euiFormLabel df-scc-euiFormRow__label">Field Type</label>
								</div>
								<div class="df-scc-eui-FormControlLayout__childrenWrapper"><select name="form-field-type" class="df-scc-eui-Select" aria-label="Use aria labels when no actual label is in use">
										<option value="0">Select A Type</option>
										<option value="date">Date</option>
										<option value="address">Address</option>
										<option value="phone">Phone</option>
										<option value="text" selected="">Text</option>
										<option value="email">Email</option>
									</select>
									<div class="df-scc-eui-FormControlLayoutIcons df-scc-eui-FormControlLayoutIcons--right">
										<span class="df-scc-eui-FormControlLayoutCustomIcon"><svg width="16" height="16" viewBox="0 0 16 16" xmlns="http://www.w3.org/2000/svg" class="df-scc-eui-Icon df-scc-eui-Icon--medium df-scc-eui-FormControlLayoutCustomIcon__icon" focusable="false" role="img" aria-hidden="true">
												<path fill-rule="non-zero" d="M13.069 5.157L8.384 9.768a.546.546 0 01-.768 0L2.93 5.158a.552.552 0 00-.771 0 .53.53 0 000 .759l4.684 4.61c.641.631 1.672.63 2.312 0l4.684-4.61a.53.53 0 000-.76.552.552 0 00-.771 0z">
												</path>
											</svg></span>
									</div>
								</div>
								<span class="text-danger" style="display: none; font-size: .75rem;">Please choose a field
									type!</span>
							</div>
							<div class="scc-form-checkbox">
								<input type="checkbox" name="is-mandatory"><label class="df-scc-euiFormLabel df-scc-euiFormRow__label" for="is-mandatory">Make
									Mandatory</label>
							</div>
						</div>
						<p class="trn text-danger" style="display:none;">There has been an error. Try again</p>
					</div>
				</div>
				<div class="df-scc-euiModalFooter">
					<button class="df-scc-euiButtonEmpty df-scc-euiButtonEmpty--primary" type="button" data-dismiss="modal"><span class="df-scc-euiButtonContent df-scc-euiButtonEmpty__content"><span class="trn df-scc-euiButtonEmpty__text">Cancel</span></span>
					</button>
					<button class="df-scc-euiButton df-scc-euiButton--primary df-scc-euiButton--fill" type="submit">
						<span class="df-scc-euiButtonContent df-scc-euiButton__content" style="background-color:#006BB4;border-radius:3px">
							<span class="trn df-scc-euiButton__text">Add</span>
						</span>
					</button>
				</div>
			</form>
		</div>
	</div>
</div>
<!-- user survey modal, initiates if the editing page has been used more than 9 times -->
<div class="modal df-scc-modal fade in" id="user-scc-sv" style="padding-right: 0px; display: none;" role="dialog">
	<div class="df-scc-euiOverlayMask df-scc-euiOverlayMask--aboveHeader">
		<div class="df-scc-euiModal df-scc-euiModal--maxWidth-default df-scc-euiModal--confirmation">
			<button onclick="sccSkipFeedbackModal()" class="df-scc-euiButtonIcon df-scc-euiButtonIcon--text df-scc-euiModal__closeIcon" type="button" aria-label="Closes this modal window">
				<svg width="16" height="16" viewBox="0 0 16 16" xmlns="http://www.w3.org/2000/svg" class="df-scc-euiIcon df-scc-euiIcon--medium df-scc-euiButtonIcon__icon" focusable="false" role="img" aria-hidden="true">
					<path d="M7.293 8L3.146 3.854a.5.5 0 11.708-.708L8 7.293l4.146-4.147a.5.5 0 01.708.708L8.707 8l4.147 4.146a.5.5 0 01-.708.708L8 8.707l-4.146 4.147a.5.5 0 01-.708-.708L7.293 8z"></path>
				</svg>
			</button>
			<div class="df-scc-euiModal__flex">
				<div class="scc-survey-card-wrapper" data-survey-section>
					<div class="scc-survey-card" data-survey-card>
						<div class="scc-survey-heading">
							<h2>How's your experience?</h2>
							<p>Your feedback helps us build a better Stylish Cost Calculator.</p>
						</div>
						<?php
						$survey_ratings = array(
							array(
								'value' => 1,
								'emoji' => '😡',
								'label' => 'Very Unhappy',
								'color' => 'rgb(239, 68, 68)',
							),
							array(
								'value' => 2,
								'emoji' => '😕',
								'label' => 'Unhappy',
								'color' => 'rgb(251, 146, 60)',
							),
							array(
								'value' => 3,
								'emoji' => '😐',
								'label' => 'Okay',
								'color' => 'rgb(234, 179, 8)',
							),
							array(
								'value' => 4,
								'emoji' => '😊',
								'label' => 'Happy',
								'color' => 'rgb(74, 222, 128)',
							),
							array(
								'value' => 5,
								'emoji' => '😍',
								'label' => 'Love it',
								'color' => 'rgb(34, 197, 94)',
							),
						);
						?>
						<div class="scc-survey-ratings" data-survey-ratings data-survey-ratings-section>
							<?php foreach ( $survey_ratings as $rating ) : ?>
								<button class="scc-survey-rating-btn" type="button" data-survey-rating="<?php echo esc_attr( $rating['value'] ); ?>" data-survey-rating-color="<?php echo esc_attr( $rating['color'] ); ?>" title="<?php echo esc_attr( $rating['label'] ); ?>">
									<span class="scc-survey-rating-emoji"><?php echo esc_html( $rating['emoji'] ); ?></span>
									<span class="scc-survey-rating-label"><?php echo esc_html( $rating['label'] ); ?></span>
								</button>
							<?php endforeach; ?>
						</div>
						<div class="scc-survey-remind-wrapper" data-survey-ratings-remind>
							<button type="button" class="btn btn-link text-muted scc-survey-remind" onclick="sccSkipFeedbackModal()">Remind me later</button>
						</div>
						<div class="scc-survey-section d-none" data-survey-reasons-section>
							<div class="d-flex justify-content-between align-items-center mb-2">
								<h3 class="mb-0">What influenced your rating?</h3>
								<button type="button" class="btn btn-primary btn-sm d-none" data-survey-continue>Continue</button>
							</div>
							<div class="scc-survey-reasons" data-survey-reasons></div>
						</div>
						<div class="scc-survey-section d-none" data-survey-message-section>
							<label for="comments-text-input">Anything else we should know? (optional)</label>
							<textarea id="comments-text-input" class="form-control scc-survey-textarea" placeholder="Tell us more about your experience..." rows="4"></textarea>
						</div>
						<div class="scc-survey-section d-none" data-survey-contact-section>
							<div class="form-group" id="survey-username-input-wrapper">
								<label for="feedback-username-input">Your name (optional)</label>
								<input id="feedback-username-input" class="form-control" value="<?php echo esc_attr( $df_scc_user_name ); ?>">
							</div>
							<div class="form-group" id="survey-email-input-wrapper">
								<label for="feedback-email-input">Your email address (optional)</label>
								<input id="feedback-email-input" class="form-control" value="<?php echo esc_attr( get_option( 'df_scc_emailsender', get_option( 'admin_email' ) ) ); ?>">
							</div>
							<div class="scc-form-checkbox">
								<label class="scc-accordion_switch_button align-bottom" for="feedback-opt-in">
									<input checked type="checkbox" id="feedback-opt-in">
									<span class="scc-accordion_toggle_button round"></span>
								</label>
								<span><label role="button" for="feedback-opt-in" class="lblExtraSettingsEditCalc">I don't mind receiving a reply by email.</label></span>
							</div>
						</div>
						<div class="scc-survey-section d-none" data-survey-action-section>
							<button id="comments-submit-btn" class="btn btn-primary w-100" type="button" disabled>Submit feedback</button>
						</div>
					</div>
					<div class="scc-survey-success d-none" data-survey-success>
						<div class="scc-survey-success-icon">🎉</div>
						<h2>Thank you!</h2>
						<p>Your feedback helps us improve.</p>
					</div>
				</div>
			</div>
		</div>
	</div>
</div><!-- /.modal -->
<style id="scc-survey-styles">
	#user-scc-sv .scc-survey-card-wrapper {
		width: 100%;
	}
	#user-scc-sv .scc-survey-card {
		max-width: 640px;
		margin: 0 auto;
		padding: 24px 28px 32px;
		background: #fff;
		border-radius: 16px;
		box-shadow: 0 8px 24px rgba(15, 23, 42, 0.08);
	}
	#user-scc-sv .scc-survey-heading {
		text-align: center;
		margin-bottom: 24px;
	}
	#user-scc-sv .scc-survey-heading h2 {
		font-size: 24px;
		font-weight: 600;
		color: #111827;
		margin-bottom: 8px;
	}
	#user-scc-sv .scc-survey-heading p {
		color: #6b7280;
		margin: 0;
	}
	#user-scc-sv .scc-survey-ratings {
		display: flex;
		justify-content: center;
		flex-wrap: wrap;
		gap: 12px;
		margin-bottom: 24px;
	}
	#user-scc-sv .scc-survey-rating-btn {
		display: flex;
		flex-direction: column;
		align-items: center;
		justify-content: center;
		gap: 4px;
		border: 3px solid transparent;
		border-radius: 12px;
		padding: 12px 16px;
		background: #f9fafb;
		font-size: 14px;
		font-weight: 500;
		color: #374151;
		cursor: pointer;
		transition: all 0.2s ease;
		min-width: 96px;
	}
	#user-scc-sv .scc-survey-rating-btn:hover {
		background: #f3f4f6;
		transform: scale(1.05);
	}
	#user-scc-sv .scc-survey-rating-btn.is-selected {
		box-shadow: 0 6px 20px rgba(15, 23, 42, 0.1);
		transform: scale(1.08);
	}
	#user-scc-sv .scc-survey-rating-emoji {
		font-size: 32px;
		line-height: 1;
	}
	#user-scc-sv .scc-survey-section {
		margin-bottom: 24px;
	}
	#user-scc-sv [data-survey-continue] {
		padding: 6px 16px;
		border-radius: 999px;
		font-size: 13px;
	}
	#user-scc-sv .scc-survey-remind-wrapper {
		text-align: left;
		margin-top: -12px;
		margin-bottom: 16px;
	}
	#user-scc-sv .scc-survey-remind {
		font-size: 14px;
		text-decoration: none;
	}
	#user-scc-sv .scc-survey-remind:hover {
		text-decoration: underline;
	}
	#user-scc-sv .scc-survey-section h3 {
		font-size: 16px;
		font-weight: 600;
		color: #374151;
		margin-bottom: 12px;
	}
	#user-scc-sv .scc-survey-reasons {
		display: flex;
		flex-wrap: wrap;
		gap: 8px;
	}
	#user-scc-sv .scc-survey-reason-btn {
		border: 2px solid #e5e7eb;
		border-radius: 999px;
		padding: 6px 16px;
		background: #fff;
		color: #4b5563;
		font-size: 14px;
		cursor: pointer;
		transition: all 0.2s ease;
	}
	#user-scc-sv .scc-survey-reason-btn:hover {
		border-color: #6d28d9;
	}
	#user-scc-sv .scc-survey-reason-btn.is-selected {
		border-color: #6366f1;
		background: #eef2ff;
		color: #4c1d95;
		font-weight: 600;
	}
	#user-scc-sv .scc-survey-textarea {
		border: 2px solid #e5e7eb;
		border-radius: 8px;
		resize: vertical;
		min-height: 90px;
	}
	#user-scc-sv .scc-survey-textarea:focus {
		border-color: #6366f1;
		box-shadow: none;
	}
	#user-scc-sv .scc-survey-success {
		max-width: 480px;
		margin: 0 auto;
		padding: 48px 32px;
		text-align: center;
		background: #fff;
		border-radius: 16px;
		box-shadow: 0 8px 24px rgba(15, 23, 42, 0.08);
	}
	#user-scc-sv .scc-survey-success-icon {
		font-size: 60px;
		margin-bottom: 16px;
	}
	#user-scc-sv .scc-survey-success h2 {
		color: #22c55e;
		font-weight: 600;
		margin-bottom: 8px;
	}
	#user-scc-sv .scc-survey-success p {
		color: #6b7280;
		margin: 0;
	}
</style>
<!-- welcome message and introductory video modal -->
<div class="modal df-scc-modal fade in" id="scc-welcome-modal" style="padding-right: 0px; display: none;" role="dialog">
	<div class="df-scc-euiOverlayMask df-scc-euiOverlayMask--aboveHeader">
		<div class="df-scc-euiModal df-scc-euiModal--maxWidth-default df-scc-euiModal--confirmation w-100">
			<button class="df-scc-euiButtonIcon df-scc-euiButtonIcon--text df-scc-euiModal__closeIcon" type="button" data-dismiss="modal" aria-label="Closes this modal window">
				<svg width="16" height="16" viewBox="0 0 16 16" xmlns="http://www.w3.org/2000/svg" class="df-scc-euiIcon df-scc-euiIcon--medium df-scc-euiButtonIcon__icon" focusable="false" role="img" aria-hidden="true">
					<path d="M7.293 8L3.146 3.854a.5.5 0 11.708-.708L8 7.293l4.146-4.147a.5.5 0 01.708.708L8.707 8l4.147 4.146a.5.5 0 01-.708.708L8 8.707l-4.146 4.147a.5.5 0 01-.708-.708L7.293 8z"></path>
				</svg>
			</button>
			<div class="df-scc-euiModal__flex">
				<div class="step1-wrapper">
					<div class="df-scc-euiModalHeader d-block pb-0">
						<div class="df-scc-euiModalHeader__title pt-2">Welcome to Stylish Cost Calculator</div>
					</div>
					<div class="df-scc-euiModalBody">
						<div class="df-scc-euiModalBody__overflow d-flex align-items-center">
							<div class="scc-video-iframe-container"></div>
						</div>
					</div>
					<div class="df-scc-euiModalFooter">
						<button type="button" class="btn btn-primary bg-primary">Settings Explained <span class="scc-icn-wrapper"><?php echo scc_get_kses_extended_ruleset( $scc_icons['arrow-right'] ); ?></span></button>
					</div>
				</div>
			</div>
		</div>
	</div>
</div><!-- /.modal -->
<!-- placeholder for editing existing for field. This div will be populated by template rendering -->
<div id="editFieldModal" style="display:none" class="fade in" role="dialog"></div>
<div id="paypalSetupModal" style="display:none" class="fade in" role="dialog"></div>
<div id="stripe_opts_modal" style="display:none" class="fade in" role="dialog"></div>
