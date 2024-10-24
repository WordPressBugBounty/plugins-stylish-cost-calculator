<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}
$isSCCFreeVersion = defined( 'STYLISH_COST_CALCULATOR_VERSION' );
require_once SCC_DIR . '/lib/wp-google-fonts/google-fonts.php';

if ( ! $f1->translation ) {
    $translateArray = '[{"key":"Total","lang":"en","translation":""},{"key":"Description","lang":"en","translation":""},{"key":"Unit Price","lang":"en","translation":""},{"key":"Quantity","lang":"en","translation":""},{"key":"Price","lang":"en","translation":""},{"key":"SEND","lang":"en","translation":""},{"key":"Total Price","lang":"en","translation":""},{"key":"Summary","lang":"en","translation":""},{"key":"Email Quote","lang":"en","translation":""},{"key":"Email Quote Form","lang":"en","translation":""},{"key":"Prove that you are not a robot","lang":"en","translation":""},{"key":"Please wait...","lang":"en","translation":""},{"key":"Submit","lang":"en","translation":""},{"key":"Cancel","lang":"en","translation":""},{"key":"Email Confirmation","lang":"en","translation":""},{"key":"Thank you! We sent your quote to","lang":"en","translation":""},{"key":"Remember to check your spam folder.","lang":"en","translation":""},{"key":"Detailed List","lang":"en","translation":""},{"key":"Choose an option...","lang":"en","translation":""},{"key":"Search an option...","lang":"en","translation":""},{"key":"Your Name","lang":"en","translation":""},{"key":"Your Email","lang":"en","translation":""},{"key":"Your Phone","lang":"en","translation":""},{"key":"Your Phone (Optional)","lang":"en","translation":""},{"key":"Coupon Code","lang":"en","translation":""},{"key":"Please choose an option","lang":"en","translation":""},{"key":"Enter your coupon code","lang":"en","translation":""},{"key":"This code is not valid","lang":"en","translation":""},{"key":"Discount percentage","lang":"en","translation":""},{"key":"Your discount has been applied correctly","lang":"en","translation":""},{"key":"Your discount has not been applied because the total price has to be between","lang":"en","translation":""},{"key":"The total price must be a minimum of","lang":"en","translation":""},{"key":"TAX","lang":"en","translation":""},{"key":"Invoice ID","lang":"en","translation":""}]';
} else {
    $translateArray = $f1->translation;
}
$translateArray = json_decode( stripslashes( $translateArray ) );

?>
<div id="calc-editor-wrapper" class="row">
	<div class="col-12">
		<!-- Calculator Title User Input-->
		<div class="row">
		<input type="text" id="id_form_input" value="<?php echo intval( $f1->id ); ?>" hidden>
		<div class="col-lg-6 col-md-6 col-xs-8 ms-2 my-4 scc-edit-calculator-name" style="display:inherit">
			<div class="col-lg-8 col-md-8 col-xs-8 d-inline-block" style="min-width: 600px; margin-right: 10px;">
				<input type="text" class="input_pad" id="costcalculatorname" placeholder="Enter the name of this calculator" value="<?php echo esc_attr( $f1->formname ); ?>" />
			</div>
			<!---SAVE BUTTON-->
			<div class="col-lg-2 col-md-2 col-xs-2 d-inline-block text-end scc-embed-wrapper position-relative ps-1" data-setting-tooltip-type="" data-bs-original-title="">
				<button class="btn m-0 d-flex align-items-center scc-top-embed-button" onclick="sccBackendUtils.toggleEmbedToPagePanel(this);" ><?php echo scc_get_kses_extended_ruleset( $this->scc_icons['code'] ); ?> <span class="ms-2">Embed</span></button>
				<div id="df_scc_tabembed_" class="scc-embed-tip-container scc-hidden">
					<button class="scc-embed-close-button" onclick="sccBackendUtils.toggleEmbedToPagePanel(this);">
						<?php echo scc_get_kses_extended_ruleset( $this->scc_icons['close'] ); ?>
					</button>
					<div class="scc-embed-tip-wrapper">
						<h3 class="scc-embed-tips-title">Embed to Page</h3>
						<div class="scc-embed-field-container mt-3">
							<h3 class="scc-embed-field-label">Calculator Form <a href="<?php echo esc_attr( SCC_HELPDESK_LINKS['troubleshoot-embedding-to-webpage'] ); ?>" target="_blank"><i class="material-icons-outlined">help_outline</i></a></h3>
							<div class="position-relative">
								<div class="scc-embed-field">[scc_calculator type='text' idvalue='<?php echo intval( $f1->id ); ?>']</div>
								<button class="scc-copy-embed-button" onclick="sccBackendUtils.copyEmbedsToClipboard(this);" ><?php echo scc_get_kses_extended_ruleset( $this->scc_icons['copy'] ); ?></button>
								<span class="scc-ai-copy-message-confirmation scc-hidden" >Copied!</span>
							</div>
						</div>
						<div class="scc-embed-field-container">
							<h3 class="scc-embed-field-label">Custom Calculator Total <a href="<?php echo esc_attr( SCC_HELPDESK_LINKS['feature-custom-totals'] ); ?>" target="_blank"><i class="material-icons-outlined">help_outline</i></a></h3>
							<div class="position-relative">
								<div class="scc-embed-field">[scc_calculator-total type='text' idvalue='<?php echo intval( $f1->id ); ?>']</div>
								<button class="scc-copy-embed-button" onclick="sccBackendUtils.copyEmbedsToClipboard(this);" ><?php echo scc_get_kses_extended_ruleset( $this->scc_icons['copy'] ); ?></button>
								<span class="scc-ai-copy-message-confirmation scc-hidden" >Copied!</span>
							</div>
						</div>
						<div class="scc-embed-field-container">
							<h3 class="scc-embed-field-label">Floating Itemized List <a href="<?php echo esc_attr( SCC_HELPDESK_LINKS['feature-floating-itemized-list'] ); ?>" target="_blank"><i class="material-icons-outlined">help_outline</i></a></h3>
							<div class="position-relative">
								<div class="scc-embed-field">[scc_calculator-detail type='text' idvalue='<?php echo intval( $f1->id ); ?>']</div>
								<button class="scc-copy-embed-button" onclick="sccBackendUtils.copyEmbedsToClipboard(this);" ><?php echo scc_get_kses_extended_ruleset( $this->scc_icons['copy'] ); ?></button>
								<span class="scc-ai-copy-message-confirmation scc-hidden" >Copied!</span>
							</div>
						</div>
						<hr>
						<div class="scc-embed-tip-footer d-flex">
							<i class="material-icons-outlined ">help_outline</i> <span>Copy and paste this shortcode <a href="<?php echo esc_attr( SCC_HELPDESK_LINKS['troubleshoot-embedding-to-webpage'] ); ?>" target="_blank"><b>properly</b></a> into a code, text, shortcode, or shortblock widget within your page builder. Do not use the visual text box.</span>
						</div>
					</div>
				</div>

			</div>
			<div class="col-lg-2 col-md-2 col-xs-2 text-end scc-save-btn-cont" data-setting-tooltip-type="" data-bs-original-title="">
				<button class="btn btn-primary m-0  d-inline-flex align-items-center align-items-center  scc-top-save-btn" onClick="saveDataFields()">
					<i class="scc-btn-spinner scc-save-btn-spinner scc-d-none"></i> Save
				</button>
			</div>
		</div>
		<!--END SAVE BUTTON-->
		</div>
		<!--END-->

		<!---EMBED BUTTON-->
	</div>
</div>

<div class="modal fade" id="settingsModal" tabindex="-1" aria-labelledby="settingsModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable scc-modal-fullscreen">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="settingsModalLabel">Font Settings</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
		<div id="dfscc_tab_font_settings_">
		<div class="row ms-4 mb-5" id="myDIV">
			<!-- START OF FONT SETTINGS AND COLORS -->
			<div class="col-12 col-md-3 col-lg-3 addedFieldsStyle font-settings-container scc-title-total-font-settings" style="min-height: 270px;margin-top:30px;margin-right:20px;box-shadow: 0px 0px 8px 1px rgba(163,163,163,0.27);">
				<div class="font-settings-title-head">
					Title & Total Font Settings <i class="material-icons-outlined more-settings-info text-white" title="Choose the font settings for the section titles and the total price, in your calculator form." style="margin-right:5px">help_outline</i>
				</div><br>
				<div style="margin-top:20px;" class="col-md-12 clearfix use-premium-tooltip">
					<label class="scc_label col-xs-8 col-md-6">Font Size</label>
					<select name="titlepricefontsize" id="titlepricefontsize" style="box-shadow: 1px 1px 1px #999; border: 0 none;" class="col-xs-4 col-md-6" disabled>
						<option class="form-control servicepricefontsize" value="0">Size</option>
						<?php
                        for ( $n = 10; $n <= 70; $n++ ) {
                            if ( $n . 'px' == $f1->titleFontSize ) {
                                $select_ser = 'selected';
                            } else {
                                $select_ser = '';
                            }
                            ?>
							<option class="form-control servicepricefontsize" value="<?php echo intval( $n ); ?>px" <?php echo esc_attr( $select_ser ); ?>><?php echo intval( $n ); ?>px</option>
							<?php
                        }
?>
					</select>
				</div>
				<div style="margin-top:20px;" class="col-md-12 clearfix use-premium-tooltip">
					<label class="scc_label col-xs-8 col-md-6">Title Font Type</label>
					<select id="titlescc_fonttype" style="box-shadow: 1px 1px 1px #999; border: 0 none;" class="col-md-6 col-xs-4" disabled>
						<?php
$allfonts  = json_decode( $scc_googlefonts_var->gf_get_local_fonts() );
$fontIndex = 0;

foreach ( $allfonts->items as $allfont ) {
    ?>
							<option <?php selected( $f1->titleFontType, $fontIndex ); ?> value="<?php echo intval( $fontIndex ); ?>"><?php echo esc_attr( $allfont->family ); ?></option>
							<?php
    $fontIndex++;
}
?>
					</select>
				</div>
				<div  style="margin-top:20px;" class="col-md-12 clearfix use-premium-tooltip">
					<label class="scc_label col-xs-8 col-md-6">Title Font Weight 
					<i class="material-icons-outlined with-tooltip" data-setting-tooltip-type="title-font-weight-tt" data-bs-original-title="" title="" style="margin-right:5px">help_outline</i>
					</label>
					<select id="titlescc_fonttype_variant" class="col-xs-4 col-md-6" style="box-shadow: 1px 1px 1px #999; border: 0 none;" disabled>
					  <?php
$allfonts  = json_decode( $scc_googlefonts_var->gf_get_local_fonts() );
$fontIndex = 0;

foreach ( $allfonts->items as $allfont ) {
    ?>
							<option <?php selected( $f1->titleFontType, $fontIndex ); ?> value="<?php echo intval( $fontIndex ); ?>"> <?php echo esc_attr( $allfont->family ); ?></option>
							<?php
    $fontIndex++;
}
?>
					</select>
				</div>
				<div style="margin-top:20px;margin-bottom:20px;" class="col-md-12 clearfix use-premium-tooltip">
					<label class="scc_label col-xs-8 col-md-6" style="margin-bottom:5px;margin-bottom:5px;">Font Color</label>
					<div class="wp-picker-container use-premium-tooltip"><button type="button" class="button wp-color-result " aria-expanded="false" style="background-color: rgb(0, 0, 0);" disabled="disabled" ><span class="wp-color-result-text">Select Color</span></button><span class="wp-picker-input-wrap hidden"><label><span class="screen-reader-text">Color value</span><input type="text" class="color-picker col-md-4 wp-color-picker" id="titlecolorPicker" value="#000"></label><input type="button" class="button button-small wp-picker-clear tooltipadmin-top" value="Clear" aria-label="Clear color" disabled="disabled"></span><div class="wp-picker-holder"><div class="iris-picker iris-border" style="display: none; width: 255px; height: 202.125px; padding-bottom: 23.2209px;"><div class="iris-picker-inner"><div class="iris-square" style="width: 182.125px; height: 182.125px;"><a class="iris-square-value ui-draggable ui-draggable-handle" href="#" style="left: 0px; top: 182.125px;"><span class="iris-square-handle ui-slider-handle"></span></a><div class="iris-square-inner iris-square-horiz" style="background-image: -webkit-linear-gradient(left, rgb(255, 255, 255), rgb(255, 255, 255), rgb(255, 255, 255), rgb(255, 255, 255), rgb(255, 255, 255), rgb(255, 255, 255), rgb(255, 255, 255), rgb(255, 255, 255), rgb(255, 255, 255), rgb(255, 255, 255), rgb(255, 255, 255), rgb(255, 255, 255), rgb(255, 255, 255));"></div><div class="iris-square-inner iris-square-vert" style="background-image: -webkit-linear-gradient(top, rgba(0, 0, 0, 0), rgb(0, 0, 0));"></div></div><div class="iris-slider iris-strip" style="height: 205.346px; width: 28.2px; background-image: -webkit-linear-gradient(top, rgb(0, 0, 0), rgb(0, 0, 0));"><div class="iris-slider-offset ui-slider ui-corner-all ui-slider-vertical ui-widget ui-widget-content"><span tabindex="0" class="ui-slider-handle ui-corner-all ui-state-default" style="bottom: 0%;"></span></div></div></div><div class="iris-palette-container"><a class="iris-palette" tabindex="0" style="background-color: rgb(0, 0, 0); height: 19.5784px; width: 19.5784px; margin-left: 0px;"></a><a class="iris-palette" tabindex="0" style="background-color: rgb(255, 255, 255); height: 19.5784px; width: 19.5784px; margin-left: 3.6425px;"></a><a class="iris-palette" tabindex="0" style="background-color: rgb(221, 51, 51); height: 19.5784px; width: 19.5784px; margin-left: 3.6425px;"></a><a class="iris-palette" tabindex="0" style="background-color: rgb(221, 153, 51); height: 19.5784px; width: 19.5784px; margin-left: 3.6425px;"></a><a class="iris-palette" tabindex="0" style="background-color: rgb(238, 238, 34); height: 19.5784px; width: 19.5784px; margin-left: 3.6425px;"></a><a class="iris-palette" tabindex="0" style="background-color: rgb(129, 215, 66); height: 19.5784px; width: 19.5784px; margin-left: 3.6425px;"></a><a class="iris-palette" tabindex="0" style="background-color: rgb(30, 115, 190); height: 19.5784px; width: 19.5784px; margin-left: 3.6425px;"></a><a class="iris-palette" tabindex="0" style="background-color: rgb(130, 36, 227); height: 19.5784px; width: 19.5784px; margin-left: 3.6425px;"></a></div></div></div></div>
				</div>
			</div>
			<!--- END OF TITLE FONT STTINGS --->
			<!-- OLD SERVICE FONT --->
			<div class="col-12 col-md-3 col-lg-3 addedFieldsStyle font-settings-container scc-element-font-settings" style="min-height: 270px;margin-top:30px;margin-right:20px;box-shadow: 0px 0px 8px 1px rgba(163,163,163,0.27);">
				<div class="font-settings-title-head">
					Element Font Settings <i class="material-icons-outlined more-settings-info text-white" title="Choose the font settings for the element titles in your calculator form. For example, the title of your dropdown menu." style="margin-right:5px">help_outline</i>
				</div><br>
				<div style="margin-top:20px;" class="col-md-12 clearfix use-premium-tooltip">
					<label class="scc_label col-xs-8 col-md-6">Font Size</label>
					<select name="servicepricefontsize" id="servicepricefontsize" style="box-shadow: 1px 1px 1px #999; border: 0 none;" class="col-md-6 col-xs-4" disabled>
						<option class="form-control servicepricefontsize" value="0">Size</option>
						<?php
for ( $n = 8; $n <= 40; $n++ ) {
    if ( $n . 'px' == $f1->ServicefontSize ) {
        $select_ser = 'selected=selected';
    } else {
        $select_ser = '';
    }
    ?>
							<option class="form-control servicepricefontsize" value="<?php echo intval( $n ); ?>px" <?php echo esc_attr( $select_ser ); ?>><?php echo intval( $n ); ?>px</option>
							<?php
}
?>
					</select>
				</div>
				<div style="margin-top:20px;" class="col-md-12 clearfix use-premium-tooltip">
					<label class="scc_label col-xs-8 col-md-6">Font Type</label>
					<?php
                    $allfonts = json_decode( $scc_googlefonts_var->gf_get_local_fonts() );
?>
					<select id='scc_fonttype' class="col-xs-4 col-md-6" style="box-shadow: 1px 1px 1px #999; border: 0 none;" disabled>
						<?php
    $fontIndex = 0;

foreach ( $allfonts->items as $allfont ) {
    $selected = ( $fontIndex == $f1->fontType ) ? 'selected' : '';
    ?>
							<option <?php echo esc_html( $selected ); ?> value="<?php echo esc_html( $fontIndex ); ?>"><?php echo esc_html( $allfont->family ); ?></option>
							<?php
    $fontIndex++;
}
?>
					</select>
				</div>
				<div style="margin-top:20px;" class="col-md-12 clearfix use-premium-tooltip">
					<label class="scc_label col-xs-8 col-md-6">Font Weight 
					<i class="material-icons-outlined with-tooltip" data-setting-tooltip-type="font-weight-tt" data-bs-original-title="" title="" style="margin-right:5px">help_outline</i>
					</label>
					<select id='scc_fonttype_variant' class="col-xs-4 col-md-6" style="box-shadow: 1px 1px 1px #999; border: 0 none;" disabled>
					</select>
				</div>
				<div style="margin-top:20px;margin-bottom:20px;" class="col-md-12 clearfix use-premium-tooltip">
					<label class="scc_label col-xs-8 col-md-6">Font Color</label>
					<div class="wp-picker-container use-premium-tooltip"><button type="button" class="button wp-color-result " aria-expanded="false" style="background-color: rgb(0, 0, 0);" disabled="disabled" ><span class="wp-color-result-text">Select Color</span></button><span class="wp-picker-input-wrap hidden"><label><span class="screen-reader-text">Color value</span><input type="text" class="color-picker col-md-4 wp-color-picker" id="titlecolorPicker" value="#000"></label><input type="button" class="button button-small wp-picker-clear tooltipadmin-top" value="Clear" aria-label="Clear color" disabled="disabled"></span><div class="wp-picker-holder"><div class="iris-picker iris-border" style="display: none; width: 255px; height: 202.125px; padding-bottom: 23.2209px;"><div class="iris-picker-inner"><div class="iris-square" style="width: 182.125px; height: 182.125px;"><a class="iris-square-value ui-draggable ui-draggable-handle" href="#" style="left: 0px; top: 182.125px;"><span class="iris-square-handle ui-slider-handle"></span></a><div class="iris-square-inner iris-square-horiz" style="background-image: -webkit-linear-gradient(left, rgb(255, 255, 255), rgb(255, 255, 255), rgb(255, 255, 255), rgb(255, 255, 255), rgb(255, 255, 255), rgb(255, 255, 255), rgb(255, 255, 255), rgb(255, 255, 255), rgb(255, 255, 255), rgb(255, 255, 255), rgb(255, 255, 255), rgb(255, 255, 255), rgb(255, 255, 255));"></div><div class="iris-square-inner iris-square-vert" style="background-image: -webkit-linear-gradient(top, rgba(0, 0, 0, 0), rgb(0, 0, 0));"></div></div><div class="iris-slider iris-strip" style="height: 205.346px; width: 28.2px; background-image: -webkit-linear-gradient(top, rgb(0, 0, 0), rgb(0, 0, 0));"><div class="iris-slider-offset ui-slider ui-corner-all ui-slider-vertical ui-widget ui-widget-content"><span tabindex="0" class="ui-slider-handle ui-corner-all ui-state-default" style="bottom: 0%;"></span></div></div></div><div class="iris-palette-container"><a class="iris-palette" tabindex="0" style="background-color: rgb(0, 0, 0); height: 19.5784px; width: 19.5784px; margin-left: 0px;"></a><a class="iris-palette" tabindex="0" style="background-color: rgb(255, 255, 255); height: 19.5784px; width: 19.5784px; margin-left: 3.6425px;"></a><a class="iris-palette" tabindex="0" style="background-color: rgb(221, 51, 51); height: 19.5784px; width: 19.5784px; margin-left: 3.6425px;"></a><a class="iris-palette" tabindex="0" style="background-color: rgb(221, 153, 51); height: 19.5784px; width: 19.5784px; margin-left: 3.6425px;"></a><a class="iris-palette" tabindex="0" style="background-color: rgb(238, 238, 34); height: 19.5784px; width: 19.5784px; margin-left: 3.6425px;"></a><a class="iris-palette" tabindex="0" style="background-color: rgb(129, 215, 66); height: 19.5784px; width: 19.5784px; margin-left: 3.6425px;"></a><a class="iris-palette" tabindex="0" style="background-color: rgb(30, 115, 190); height: 19.5784px; width: 19.5784px; margin-left: 3.6425px;"></a><a class="iris-palette" tabindex="0" style="background-color: rgb(130, 36, 227); height: 19.5784px; width: 19.5784px; margin-left: 3.6425px;"></a></div></div></div></div>
				</div>
			</div>
			<!-- END OF SERVICE FONT SETTING -->
			<div class="col-12 col-md-3 col-lg-3 addedFieldsStyle font-settings-container scc-object-settings" style="min-height: 270px;margin-top:30px;box-shadow: 0px 0px 8px 1px rgba(163,163,163,0.27);">
				<div class="font-settings-title-head">
					Object Settings <i class="material-icons-outlined more-settings-info text-white" title="Choose the color for the objects in your calculator form. For example, the total bar style, slider handle bar, drodpown menu border, etc." style="margin-right:5px">help_outline</i>
				</div><br>
				<div style="margin-top:20px;" class="col-md-12 clearfix use-premium-tooltip">
					<label class="scc_label col-xs-8 col-md-6">Object Size</label>
					<select name="servicepricefontsize" id="objectservicepricefontsize" class="col-xs-4 col-md-6" style="box-shadow: 1px 1px 1px #999; border: 0 none;" disabled>
						<option class="form-control servicepricefontsize" value="0">Size</option>
						<?php
for ( $n = 1; $n <= 100; $n++ ) {
    if ( $n . 'px' == $f1->objectSize ) {
        $select_ser = 'selected';
    } else {
        $select_ser = '';
    }
    ?>
							<option class="form-control servicepricefontsize" value="<?php echo intval( $n ); ?>px" <?php echo esc_attr( $select_ser ); ?>><?php echo intval( $n ); ?>px</option>
							<?php
}
?>
					</select>
				</div>
				<div style="margin-top:20px;margin-bottom:20px;" class="col-md-12 clearfix use-premium-tooltip">
					<label class="scc_label col-xs-8 col-md-6">Object Color</label>
					<div class="wp-picker-container use-premium-tooltip"><button type="button" class="button wp-color-result " aria-expanded="false" style="background-color: rgb(0, 0, 0);" disabled="disabled" ><span class="wp-color-result-text">Select Color</span></button><span class="wp-picker-input-wrap hidden"><label><span class="screen-reader-text">Color value</span><input type="text" class="color-picker col-md-4 wp-color-picker" id="titlecolorPicker" value="#000"></label><input type="button" class="button button-small wp-picker-clear tooltipadmin-top" value="Clear" aria-label="Clear color" disabled="disabled"></span><div class="wp-picker-holder"><div class="iris-picker iris-border" style="display: none; width: 255px; height: 202.125px; padding-bottom: 23.2209px;"><div class="iris-picker-inner"><div class="iris-square" style="width: 182.125px; height: 182.125px;"><a class="iris-square-value ui-draggable ui-draggable-handle" href="#" style="left: 0px; top: 182.125px;"><span class="iris-square-handle ui-slider-handle"></span></a><div class="iris-square-inner iris-square-horiz" style="background-image: -webkit-linear-gradient(left, rgb(255, 255, 255), rgb(255, 255, 255), rgb(255, 255, 255), rgb(255, 255, 255), rgb(255, 255, 255), rgb(255, 255, 255), rgb(255, 255, 255), rgb(255, 255, 255), rgb(255, 255, 255), rgb(255, 255, 255), rgb(255, 255, 255), rgb(255, 255, 255), rgb(255, 255, 255));"></div><div class="iris-square-inner iris-square-vert" style="background-image: -webkit-linear-gradient(top, rgba(0, 0, 0, 0), rgb(0, 0, 0));"></div></div><div class="iris-slider iris-strip" style="height: 205.346px; width: 28.2px; background-image: -webkit-linear-gradient(top, rgb(0, 0, 0), rgb(0, 0, 0));"><div class="iris-slider-offset ui-slider ui-corner-all ui-slider-vertical ui-widget ui-widget-content"><span tabindex="0" class="ui-slider-handle ui-corner-all ui-state-default" style="bottom: 0%;"></span></div></div></div><div class="iris-palette-container"><a class="iris-palette" tabindex="0" style="background-color: rgb(0, 0, 0); height: 19.5784px; width: 19.5784px; margin-left: 0px;"></a><a class="iris-palette" tabindex="0" style="background-color: rgb(255, 255, 255); height: 19.5784px; width: 19.5784px; margin-left: 3.6425px;"></a><a class="iris-palette" tabindex="0" style="background-color: rgb(221, 51, 51); height: 19.5784px; width: 19.5784px; margin-left: 3.6425px;"></a><a class="iris-palette" tabindex="0" style="background-color: rgb(221, 153, 51); height: 19.5784px; width: 19.5784px; margin-left: 3.6425px;"></a><a class="iris-palette" tabindex="0" style="background-color: rgb(238, 238, 34); height: 19.5784px; width: 19.5784px; margin-left: 3.6425px;"></a><a class="iris-palette" tabindex="0" style="background-color: rgb(129, 215, 66); height: 19.5784px; width: 19.5784px; margin-left: 3.6425px;"></a><a class="iris-palette" tabindex="0" style="background-color: rgb(30, 115, 190); height: 19.5784px; width: 19.5784px; margin-left: 3.6425px;"></a><a class="iris-palette" tabindex="0" style="background-color: rgb(130, 36, 227); height: 19.5784px; width: 19.5784px; margin-left: 3.6425px;"></a></div></div></div></div>
				</div>
			</div>
		</div>
	</div>
	</div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary settings-modal-save-btn" onclick="saveDataFields()">
			<span class="btn-text">Save changes</span>
			<span class="btn-text-alt">Loading</span>
			<i class="fas fa-spinner btn-spinner fa-spin"></i>
		</button>
      </div>
    </div>
  </div>
</div><!-- END OF MODAL -->
<div class="modal fade" id="settingsModal1" tabindex="-1" aria-labelledby="settingsModal1Label" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable scc-modal-fullscreen">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="settingsModal1Label">Calculator Settings</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
	  <div id="dfscc_tab_calculator_">
	<!--Settings Nav Bar-->
	<div class="scc-calculator-settings-filters d-none">
		<a href="#Tax-VAT-Settings"><button class="btn-calc-settings-bar">Tax / VAT Settings</button></a>
		<a href="#Currency-Settings"><button class="btn-calc-settings-bar">Currency Settings</button></a>
		<a href="#PDF-Settings"><button class="btn-calc-settings-bar">PDF Settings</button></a>
		<a href="#Email-Settings"><button class="btn-calc-settings-bar">Email Settings</button></a>
		<a href="#Webhook-Settings"><button class="btn-calc-settings-bar">Webhook Settings</button></a>
		<a href="#WooCommerce-Settings"><button class="btn-calc-settings-bar">WooCommerce Settings</button></a>
		<a href="#CTA-Buttons-Settings"><button class="btn-calc-settings-bar">CTA Buttons Settings</button></a>
		<a href="#Total-Price-Settings"><button class="btn-calc-settings-bar">Total Price Settings</button></a>
		<a href="#Detailed-List-PDF-Settings"><button class="btn-calc-settings-bar">Detailed List & PDF Settings</button></a>
	</div>
	<!--End Settings Nav Bar-->
	<div class="row" id="calc-settings-items">
			<!--Start Left Section-->
				<div class="clearfix more-settings-section col-md-12 scc-frontend-options-section">
					<div class="accordion mt-2" id="calc-settings-modal-accordion">
						<div class="accordion-item">
							<h2 class="accordion-header">
								<button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#calc-settings-modal-item" aria-expanded="true" aria-controls="calc-settings-modal-item">
									<div class="sccsubtitle scc_email_quote_label">
										<svg width="34" height="34" viewBox="0 0 34 34" fill="none" xmlns="http://www.w3.org/2000/svg">
											<path fill-rule="evenodd" clip-rule="evenodd" d="M23 10.25H11C10.4033 10.25 9.83097 10.4871 9.40901 10.909C8.98705 11.331 8.75 11.9033 8.75 12.5V13.25H25.25V12.5C25.25 11.9033 25.0129 11.331 24.591 10.909C24.169 10.4871 23.5967 10.25 23 10.25ZM8.75 21.5V15.5H11.75V23.75H11C10.4033 23.75 9.83097 23.5129 9.40901 23.091C8.98705 22.669 8.75 22.0967 8.75 21.5ZM14 23.75H23C23.5967 23.75 24.169 23.5129 24.591 23.091C25.0129 22.669 25.25 22.0967 25.25 21.5V15.5H14V23.75ZM11 8C9.80653 8 8.66193 8.47411 7.81802 9.31802C6.97411 10.1619 6.5 11.3065 6.5 12.5V21.5C6.5 22.6935 6.97411 23.8381 7.81802 24.682C8.66193 25.5259 9.80653 26 11 26H23C24.1935 26 25.3381 25.5259 26.182 24.682C27.0259 23.8381 27.5 22.6935 27.5 21.5V12.5C27.5 11.3065 27.0259 10.1619 26.182 9.31802C25.3381 8.47411 24.1935 8 23 8H11Z" fill="currentColor" />
										</svg>
										<span>FRONTEND</span> OPTIONS
									</div>
								</button>
							</h2>
							<div id="calc-settings-modal-item" class="accordion-collapse collapse" data-bs-parent="#calc-settings-modal-accordion">
								<div class="accordion-body">
									<div class="row col-md-12">
										<!-- START of frontend left section -->
										<div class="row col-xs-6 col-md-6 scc-setting-subsection-left-container">

											<!--START Of Entire Form Fields Section--->
											<div class="row col-xs-12 col-md-12 scc-setting-subsection scc-form-field-element-styles">
												<span class="scc-calc-settings-title">Form Fields &amp; Elements Style</span>
												<hr class="scc-calc-settings-hr">
												<div class="scc-vcenter">
													<div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
														<label id="label_element_style" class="scc-calc-settings-lbl">Elements Style Skin
															<i class="material-icons-outlined more-settings-info" data-setting-tooltip-type="element-style-skin-tt" data-bs-original-title="" title="" style="margin-right:5px">
																<span class="scc-icn-wrapper"><?php echo scc_get_kses_extended_ruleset($this->scc_icons['help-circle']); ?></span>
															</i>
														</label>
													</div>
													<div class="col-xs-6 col-md-6" style="padding:0;">
														<select onchange="changeFieldStyle(this)" name="form_field_style" class="form-control" style="height:40px;">
															<option class="form-control" value="style_1" <?php echo ($f1->elementSkin == 'style_1') ? 'selected' : ''; ?>>Style 1 - Inline</option>
															<option class="form-control" value="style_2" <?php echo ($f1->elementSkin == 'style_2') ? 'selected' : ''; ?>>Style 2 - Block</option>
														</select>
													</div>
												</div>

												<div class="col-md-12 scc-vcenter"
													<?php
													if ($f1->elementSkin == 'style_1') {
														echo 'style="display: none; margin-top: 10px;"';
													}
													?>>
													<div class="col-xs-12 col-sm-6 col-md-6 col-lg-6" style="padding-left:0">
														<label id="label_add_container" class="scc-calc-settings-lbl">Add container
															<i class="material-icons-outlined more-settings-info" data-setting-tooltip-type="add-container-tt" data-bs-original-title="" title="" style="margin-right:5px">
																<span class="scc-icn-wrapper"><?php echo scc_get_kses_extended_ruleset($this->scc_icons['help-circle']); ?></span>
															</i>
														</label>
													</div>
													<div class="col-xs-6 col-md-6" style="padding:0;">
														<!--- Style2 Box Shaddow Toggle Option -->
														<div id="toggle_boxshadow_style2" style="padding-top:1px;">
															<div class="switch-container">
																<label class="scc-switch">
																	<div class="scc-switch">
																		<input type="checkbox" name="toggle_boxshadow_style2" placeholder="" value="toggle_boxshadow_style2" id="toggle_boxshadow_style2" class="form-control"
																			<?php
																			if ($f1->addContainer == 'true') {
																				echo 'checked';
																			}
																			?>>
																		<span class="slider round"></span>
																	</div>
																</label>
															</div>
														</div>
													</div><!-- end of Style2 Box Shaddow Toggle Option  -->
												</div>
												<div class="row col-md-12 scc-cal-settings-row scc-vcenter">
													<div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
														<label id="label_add_container" class="scc-calc-settings-lbl">Add Container BG Shadow
															<i class="material-icons-outlined more-settings-info" data-setting-tooltip-type="add-bgboxshadow-container" data-bs-original-title="" title="" style="margin-right:5px">
																<span class="scc-icn-wrapper"><?php echo scc_get_kses_extended_ruleset($this->scc_icons['help-circle']); ?></span>
															</i>
														</label>
													</div>
													<div class="col-xs-6 col-md-6">
														<!--- Style2 Box Shaddow Toggle Option -->
														<div id="toggle_boxshadow_style2" style="padding-top:1px;">
															<div class="switch-container">
																<label class="scc-switch">
																	<div class="scc-switch use-premium-tooltip">
																		<input type="checkbox" disabled name="toggle_bgboxshadow_style2" placeholder="" value="toggle_boxshadow_style2" id="toggle_bgboxshadow_style2" class="form-control"
																			<?php
																			if (isset($f1->bgShadowContainer) && $f1->bgShadowContainer == 'true') {
																				echo 'checked';
																			}
																			?>>
																		<span class="slider round scc-disabled-premium-toggle"></span>
																	</div>
																</label>
															</div>
														</div>
													</div><!-- end of Style2 Box Shaddow Toggle Option  -->
												</div>

												<!-- start of calculator max width settings -->
												<div class="row col-md-12 scc-cal-settings-row scc-vcenter">
													<div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
														<label id="label_element_style" class="scc-calc-settings-lbl">Calculator Form - Container Max Width
															<i class="material-icons-outlined more-settings-info" data-setting-tooltip-type="calculator-max-with-tt" data-bs-original-title="" title="" style="margin-right:5px">
																<span class="scc-icn-wrapper"><?php echo scc_get_kses_extended_ruleset($this->scc_icons['help-circle']); ?></span>
															</i>
														</label>
													</div>
													<div class="col-xs-6 col-md-6">
														<input type="number" min="0" name="scc_wrapper_max_width" id="scc_wrapper_max_width" class="form-control" value="<?php echo esc_attr($f1->wrapper_max_width); ?>" style="max-width:100px;min-height: 40px;padding-right: 5px;border:2px solid #F0F0F0;" />
													</div>
												</div>
												<!-- end of calculator max width settings -->
												<div class="scc-cal-settings-row scc-vcenter">
													<div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
														<label id="label_element_style" class="scc-calc-settings-lbl">Accordion Style
															<i class="material-icons-outlined more-settings-info" data-setting-tooltip-type="accordion-style-tt" data-bs-original-title="" title="" style="margin-right:5px">
																<span class="scc-icn-wrapper"><?php echo scc_get_kses_extended_ruleset($this->scc_icons['help-circle']); ?></span>
															</i>
														</label>
													</div>
													<div class="col-xs-6 col-md-6 use-premium-tooltip" style="padding:0;">
														<select name="form_accordion_style" disabled class="form-control" style="height:40px;">
															<option class="form-control" value="style_1" <?php echo (isset($f1->accordionStyle) && $f1->accordionStyle == 'style_1') ? 'selected' : ''; ?>>Style 1 - Solid BG Color</option>
															<option class="form-control" value="style_2" <?php echo (isset($f1->accordionStyle) && $f1->accordionStyle == 'style_2') ? 'selected' : ''; ?>>Style 2 - Numbered & Minimal Style</option>
														</select>
													</div>
												</div>

											</div>
											<!--End Of Entire Form Fields Section--->

											<!-- Start Woocommerce add to cart behaviour -->
											<div class="row col-xs-12 col-md-12 scc-setting-subsection scc-woocommerce-settings">
												<span class="scc-calc-settings-title" id="WooCommerce-Settings">WooCommerce Settings</span>
												<hr class="scc-calc-settings-hr">
												<div class="col-xs-12 col-md-12" style="margin-top: 10px;">
													<div class="col-xs-12 col-md-12 scc-vcenter">
														<div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
															<label id="label_addtocart" class="scc-calc-settings-lbl">Add-To-Cart Redirection
																<i class="material-icons-outlined more-settings-info" data-setting-tooltip-type="add-to-cart-redirection-tt" data-bs-original-title="" title="" style="margin-right:5px">
																	<span class="scc-icn-wrapper"><?php echo scc_get_kses_extended_ruleset($this->scc_icons['help-circle']); ?></span>
																</i>
															</label>
														</div>
														<div class="col-xs-6 col-md-6" style="padding:0">
															<select name="scc_wc_cart_btn_action" class="form-control" style="height:40px; margin-bottom: 0;">
																<option class="form-control" value="open_checkout"
																	<?php
																	if ($f1->addtoCheckout == 'open_checkout') {
																		echo 'selected';
																	}
																	?>>Open Checkout Page</option>
																<option class="form-control" value="open_cart"
																	<?php
																	if ($f1->addtoCheckout == 'open_cart') {
																		echo 'selected';
																	}
																	?>>Open Cart Page</option>
															</select>
														</div>
													</div>
												</div>
											</div>
											<!-- End Woocommerce add to cart behaviour -->

											<!-- START User Action BIG SECTION -->
											<div class="row col-md-12 scc-cal-settings-row scc-setting-subsection scc-cta-button-calc-settings">
												<span class="scc-calc-settings-title" id="CTA-Buttons-Settings">CTA Buttons (User Action Buttons)</span>
												<hr class="scc-calc-settings-hr">
												<!-- Start User Action Button Style -->
												<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 scc-vcenter" style="margin-top:10px;">
													<div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
														<label id="label_buton_style" class="scc-calc-settings-lbl">Button Style</label>
														<i class="material-icons-outlined more-settings-info" data-setting-tooltip-type="button-style-visibility-tt" data-bs-original-title="" title="" style="margin-right:5px">
															<span class="scc-icn-wrapper"><?php echo scc_get_kses_extended_ruleset($this->scc_icons['help-circle']); ?></span>
														</i>
													</div>
													<div class="col-xs-6 col-md-6" style="padding:0">
														<select name="scc_user_action_btn_style" class="form-control" style="height:40px;" onchange="validateColorCompatibility()">
															<option class="form-control" value="1"
																<?php
																if ($f1->buttonStyle == '1') {
																	echo 'selected';
																}
																?>>Style 1 - Standard Rounded </option>
															<option class="form-control" value="2"
																<?php
																if ($f1->buttonStyle == '2') {
																	echo 'selected';
																}
																?>>Style 2 - Animated</option>
															<option class="form-control" disabled>Style 3 - Liquid Effect</option>
															<option class="form-control" disabled>Style 4 - Animated Borders</option>
														</select>
														<!-- Warning message -->
														<div class="alert alert-warning align-items-center mt-1 scc-err-btn-style
													<?php
													if ($f1->buttonStyle == '3' && $f1->ctaBtnColorPicker == '#000000' || $f1->buttonStyle == '3' && $f1->ctaBtnColorPicker == '#000') {
														echo 'd-flex';
													} else {
														echo 'd-none';
													}
													?>
													">
															<svg class="bi flex-shrink-0 me-3" width="24" height="24" role="img" aria-label="Warning:">
																<use xlink:href="#exclamation-triangle-fill" />
															</svg>
															<div>Style 3 doesn't work properly with black background</div>
														</div>
														<!-- End Warning message -->
													</div>
												</div>
												<!-- End User Action Button Style -->
												<!-- Start of Button styles -->
												<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 scc-vcenter" style=" display: none;">
													<select name="scc_btn_style" class="form-control scc_btn_style" style="font-size: 13px!important;height:40px;">
														<option class="form-control" value="0" selected="">Default</option>
														<option class="form-control" value="1">With Hover Animation Effect</option>
													</select>
												</div>

												<!-- Start Secondary CTA Button Select -->

												<?php
												$secondaryBtnsArray = [];

												if (isset($f1->secondaryCtaButtons) && $f1->secondaryCtaButtons) {
													$secondaryBtnsArray = json_decode(wp_unslash($f1->secondaryCtaButtons), true);
												}
												?>
												<!-- Start of Button styles -->
												<div class="row col-md-12 scc-cal-settings-row scc-vcenter">
													<!-- Start Send Quote Button -->
													<div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
														<label id="label_turnoffemai" class="scc-calc-settings-lbl">Show <b>Email Quote</b> button <i class="material-icons-outlined more-settings-info" data-setting-tooltip-type="show-email-quote-button-tt" data-bs-original-title="" title="" style="margin-right:5px">
																<span class="scc-icn-wrapper"><?php echo scc_get_kses_extended_ruleset($this->scc_icons['help-circle']); ?></span>
															</i></label>
													</div>
													<div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
														<label class="scc-switch">
															<div class="scc-switch">
																<input type="checkbox" name="scc_send_quote" placeholder="" value="turn_off_send_quote" id="scc_send_quote" class="form-control scc_send_quote"
																	<?php
																	if ($f1->turnoffemailquote !== 'true') {
																		echo 'checked';
																	}
																	?>>
																<span class="slider round"></span>
															</div>
														</label>
													</div>
												</div><!-- End of Send Quote Button -->
												<!-- Turn off View Detailed List button -->
												<div class="row col-md-12 scc-cal-settings-row scc-vcenter">
													<div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
														<label id="label_turnoffview" class="scc-calc-settings-lbl">Show <b>View Detailed List</b> button
															<i class="material-icons-outlined more-settings-info" data-setting-tooltip-type="show-view-detailed-list-button-tt" data-bs-original-title="" title="" style="margin-right:5px">
																<span class="scc-icn-wrapper"><?php echo scc_get_kses_extended_ruleset($this->scc_icons['help-circle']); ?></span>
															</i>
														</label>
													</div>
													<div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
														<label class="scc-switch">
															<div class="scc-switch">
																<input type="checkbox" name="scc_detailed_list" placeholder="" value="turn_off_viewed_detailed_list" id="scc_detailed_list" class="form-control scc_detailed_list"
																	<?php
																	if ($f1->turnviewdetails !== 'true') {
																		echo 'checked';
																	}
																	?>>
																<span class="slider round"></span>
															</div>
														</label>
													</div>
												</div><!-- End Turn off View Detailed List button -->
												<!-- Start Turn Off Coupon Discount -->
												<div class="row col-md-12 scc-cal-settings-row scc-vcenter">
													<div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
														<label id="label_turnoffcupon" class="scc-calc-settings-lbl">Show <b>Coupon</b> button
															<i class="material-icons-outlined more-settings-info" data-setting-tooltip-type="show-coupon-button-info-tt" data-bs-original-title="" title="" style="margin-right:5px">
																<span class="scc-icn-wrapper"><?php echo scc_get_kses_extended_ruleset($this->scc_icons['help-circle']); ?></span>
															</i>
														</label>
													</div>
													<div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
														<label class="scc-switch">
															<div class="scc-switch">
																<input type="checkbox" name="turn_off_coupon" placeholder="" value="turn_off_coupon" id="turn_off_coupon" class="form-control"
																	<?php
																	if ($f1->turnoffcoupon !== 'true') {
																		echo 'checked';
																	}
																	?>>
																<!-- <input type="checkbox" name="turn_off_coupon" placeholder="" value="turn_off_coupon" id="turn_off_coupon" checked class="form-control" disabled> -->
																<span class="slider round"></span>
															</div>
														</label>
													</div>
												</div><!-- End of Turn Off Coupon Display -->

												<!-- Start Turn Off CTA Button Icons -->
												<div class="row col-md-12 scc-cal-settings-row scc-vcenter">
													<div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
														<label id="label_turnoffcupon" class="scc-calc-settings-lbl">Show CTA Button <b>Icons</b>
															<i class="material-icons-outlined more-settings-info" data-setting-tooltip-type="show-cta-button-icons-tt" data-bs-original-title="" title="" style="margin-right:5px">
																<span class="scc-icn-wrapper"><?php echo scc_get_kses_extended_ruleset($this->scc_icons['help-circle']); ?></span>
															</i>
														</label>
													</div>
													<div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
														<label class="scc-switch">
															<div class="scc-switch use-premium-tooltip">
																<input type="checkbox" disabled name="turn_off_cta_btn_icons" placeholder="" value="turn_off_cta_btn_icons" id="turn_off_cta_btn_icons" class="form-control">
																<span class="slider round scc-disabled-premium-toggle"></span>
															</div>
														</label>
													</div>
												</div><!-- End of Turn Off CTA Button Icons -->

												<!-- Start of Multi-step Progress Indicator Style -->
												<div class="row col-md-12 scc-cal-settings-row scc-vcenter">
													<div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
														<label class="scc-calc-settings-lbl">Multi-step <b>Progress Indicator</b> Style
															<i class="material-icons-outlined more-settings-info" data-setting-tooltip-type="multi-step-progress-indicator-style-tt" data-bs-original-title="" title="" style="margin-right:5px">
																<span class="scc-icn-wrapper"><?php echo scc_get_kses_extended_ruleset($this->scc_icons['help-circle']); ?></span>
															</i>
														</label>
													</div>
													<div class="col-xs-6 col-sm-6 col-md-6 col-lg-6 use-premium-tooltip">
														<select disabled name="scc_progress_indicator_style" class="form-control">
															<option class="form-control" value="1" <?php selected($f1->progress_indicator_style, 1); ?>>Progress Bar</option>
															<option class="form-control" value="2" <?php selected($f1->progress_indicator_style, 2); ?>>Progress Dots With Title</option>
															<option class="form-control" value="3" <?php selected($f1->progress_indicator_style, 3); ?>>Progress Bar Steps With Title</option>
															<option class="form-control" value="4" <?php selected($f1->progress_indicator_style, 4); ?>>Progress Dots with Numerical Steps</option>
															<option class="form-control" value="0" <?php selected($f1->progress_indicator_style, 0); ?>>None</option>
														</select>
													</div>
												</div><!-- End of Multi-step Progress Indicator Style -->
												<!-- Start of Progress buttons Style -->
												<div class="row col-md-12 scc-cal-settings-row scc-vcenter">
													<div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
														<label class="scc-calc-settings-lbl">Multi-step <b>Progress Buttons</b> Style
															<i class="material-icons-outlined more-settings-info" data-setting-tooltip-type="multi-step-progress-buttons-style-tt" data-bs-original-title="" title="" style="margin-right:5px">
																<span class="scc-icn-wrapper"><?php echo scc_get_kses_extended_ruleset($this->scc_icons['help-circle']); ?></span>
															</i>
														</label>
													</div>
													<div class="col-xs-6 col-sm-6 col-md-6 col-lg-6 use-premium-tooltip">
														<select disabled name="scc_progress_buttons_style" class="form-control">
															<?php if (isset($f1->progress_buttons_style)) { ?>
																<option class="form-control" value="space-between" <?php selected($f1->progress_buttons_style, 'space-between'); ?>>Space Between Buttons</option>
																<option class="form-control" value="space-between-arrows" <?php selected($f1->progress_buttons_style, 'space-between-arrows'); ?>>Space Between Buttons ( Only arrows in the text )</option>
																<option class="form-control" value="aligned-right" <?php selected($f1->progress_buttons_style, 'aligned-right'); ?>>Aligned Right Buttons</option>
																<option class="form-control" value="aligned-right-arrows" <?php selected($f1->progress_buttons_style, 'aligned-right-arrows'); ?>>Aligned Right Buttons ( Only arrows in the text )</option>
															<?php } else {
															?>
																<option class="form-control" value="space-between">Space Between Buttons</option>
																<option class="form-control" value="space-between-arrows">Space Between Buttons ( Only arrows in the text )</option>
																<option class="form-control" value="aligned-right">Aligned Right Buttons</option>
																<option class="form-control" value="aligned-right-arrows">Aligned Right Buttons ( Only arrows in the text )</option>
															<?php
															} ?>
														</select>
													</div>
												</div><!-- End of Progress buttons Style -->

												<!-- Start of Custom payment buttons toggle -->
												<div class="row col-md-12 scc-cal-settings-row scc-vcenter">
													<div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
														<label for="enable_custom_payment_btns" class="scc-calc-settings-lbl">Customize <b>Payment Buttons</b> <i class="material-icons-outlined more-settings-info" data-setting-tooltip-type="show-customize-payment-options-tt" data-bs-original-title="" title="" style="margin-right:5px">
																<span class="scc-icn-wrapper"><?php echo scc_get_kses_extended_ruleset($this->scc_icons['help-circle']); ?></span>
															</i></label>
													</div>
													<div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
														<label class="scc-switch">
															<div class="scc-switch use-premium-tooltip">
																<input disabled type="checkbox" name="enable_custom_payment_btns" id="enable_custom_payment_btns" onchange="javascript:sccBackendUtils.handleSettingsToggle(this, 2)" class="form-control">
																<span class="slider round scc-disabled-premium-toggle"></span>
															</div>
														</label>
													</div>
												</div><!-- End of Custom payment buttons toggle -->
												<div class="row col-md-12 scc-cal-settings-row scc-vcenter">
													<!-- Start Payment Button Hover Effect Toggle Button -->
													<div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
														<label id="label_turnoffborder" class="scc-calc-settings-lbl">Show <b>Border</b> for Pay Btns
															<i class="material-icons-outlined more-settings-info" data-setting-tooltip-type="show-border-pay-buttons-tt" data-bs-original-title="" title="" style="margin-right:5px">
																<span class="scc-icn-wrapper"><?php echo scc_get_kses_extended_ruleset($this->scc_icons['help-circle']); ?></span>
															</i>
														</label>
													</div>
													<div class="col-xs-6 col-md-6">
														<label class="scc-switch">
															<div class="scc-switch">
																<input type="checkbox" name="paybuttonhovereffect" id="paybuttonhovereffect" class="form-control"
																	<?php
																	if ($f1->turnoffborder !== 'true') {
																		echo 'checked';
																	}
																	?>>
																<span class="slider round"></span>
															</div>
														</label>
													</div>
												</div><!-- End Payment Button Hover Effect Toggle Button -->
												<!-- Start of Paypal Custom Text -->
												<div class="row col-md-12 scc-cal-settings-row scc-vcenter 
												<?php
												if (isset($sccCustomButtonsArray) && $sccCustomButtonsArray['enable_custom_payment_btns'] != '1') {
													echo 'd-none';
												}
												?>
												">
													<div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
														<label for="invoice_starting_number" class="scc-calc-settings-lbl"><b>Paypal</b> Custom Text <i class="material-icons-outlined more-settings-info" data-setting-tooltip-type="customize-paypal-buttons-text-tt" data-bs-original-title="" title="" style="margin-right:5px">
																<span class="scc-icn-wrapper"><?php echo scc_get_kses_extended_ruleset($this->scc_icons['help-circle']); ?></span>
															</i></label>
													</div>
													<div class="col-xs-6 col-sm-6 col-md-6 col-lg-6 use-premium-tooltip">
														<div style="padding:0">
															<input type="text" disabled placeholder="Custom Paypal Text" name="scc_paypal_button_text" id="scc_paypal_button_text" class="form-control" value="" style="max-width:25rem;">
														</div>
													</div>
												</div><!-- End of Paypal Custom Text -->
												<!-- Start of Stripe Custom Text -->
												<div class="row col-md-12 scc-cal-settings-row scc-vcenter 
												<?php
												if (isset($sccCustomButtonsArray) && $sccCustomButtonsArray['enable_custom_payment_btns'] != '1') {
													echo 'd-none';
												}
												?>
												">
													<div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
														<label for="invoice_placement" class="scc-calc-settings-lbl"><b>Stripe</b> Custom text <i class="material-icons-outlined more-settings-info" data-setting-tooltip-type="customize-stripe-buttons-text-tt" data-bs-original-title="" title="" style="margin-right:5px">
																<span class="scc-icn-wrapper"><?php echo scc_get_kses_extended_ruleset($this->scc_icons['help-circle']); ?></span>
															</i></label>
													</div>
													<div class="col-xs-6 col-sm-6 col-md-6 col-lg-6 use-premium-tooltip">
														<div style="padding:0">
															<input type="text" disabled placeholder="Custom Stripe Text" name="scc_stripe_button_text" id="scc_stripe_button_text" class="form-control" value="<?php echo esc_attr((isset($sccCustomButtonsArray)) ? $sccCustomButtonsArray['stripe_text'] : ''); ?>" style="max-width:25rem;">
														</div>
													</div>
												</div><!-- End of  Stripe Custom Text -->
											</div>
											<!-- End of USER ACTIONS section close -->

											<!-- Start of Search Box settings -->
											<div class="row col-md-12 scc-cal-settings-row scc-setting-subsection scc-search-box-settings">
												<span class="scc-calc-settings-title">Search Box Settings</span>
												<hr class="scc-calc-settings-hr">
												<!-- Start User Action Button Style -->
												<div class="row col-md-12 scc-cal-settings-row scc-vcenter">
													<div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
														<label class="scc-calc-settings-lbl">Show <b>Search Box</b> <i class="material-icons-outlined more-settings-info" data-setting-tooltip-type="show-search-box-tt" data-bs-original-title="" title="" style="margin-right:5px">
																<span class="scc-icn-wrapper"><?php echo scc_get_kses_extended_ruleset($this->scc_icons['help-circle']); ?></span>
															</i></label>
													</div>
													<div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
														<label class="scc-switch">
															<div class="scc-switch">
																<input type="checkbox" name="scc_show_searchbar" class="form-control"
																	<?php
																	if ($f1->showSearchBar == 1) {
																		echo 'checked';
																	}
																	?>>
																<span class="slider round scc-disabled-premium-toggle"></span>
															</div>
														</label>
													</div>
												</div>
												<!-- End User Action Button Style -->
											</div>
											<!-- End of Search Box settings -->

										</div>
										<!-- END of frontend left section -->

										<!-- START of frontend right section -->
										<div class="row col-xs-6 col-md-6 scc-setting-subsection-left-container">

											<!-- START Total Price SECTION -->
											<div class="row col-md-12 scc-cal-settings-row scc-setting-subsection scc-total-price-settings">
												<span class="scc-calc-settings-title" id="Total-Price-Settings">Total Price Settings</span>
												<hr class="scc-calc-settings-hr">
												<!-- Start SCC Total Price Style View -->
												<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 scc-vcenter" style="margin-top:10px;">
													<div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
														<label id="label_barstyle" class="scc-calc-settings-lbl">Total Price Bar Style
															<i class="material-icons-outlined more-settings-info" data-setting-tooltip-type="bar-style-visibility-tt" data-bs-original-title="" title="" style="margin-right:5px">
																<span class="scc-icn-wrapper"><?php echo scc_get_kses_extended_ruleset($this->scc_icons['help-circle']); ?></span>
															</i>

														</label>
													</div>
													<div class="col-xs-6 col-sm-6 col-md-6 col-lg-6 use-premium-tooltip" style="padding:0">
														<select disabled name="scc_total_price_style_view" class="form-control scc_total_price_style_view" style="height:40px;">
															<option class="form-control" value="0">Choose a style</option>
															<option class="form-control" value="scc_tp_style1"
																<?php
																if ($f1->barstyle == 'scc_tp_style1') {
																	echo 'selected';
																}

																if (
																	$f1->barstyle !== 'scc_tp_style1' && $f1->barstyle !== 'scc_tp_style2' &&
																	$f1->barstyle !== 'scc_tp_style3' && $f1->barstyle !== 'scc_tp_style4'
																) {
																	echo 'selected';
																}
																?>>Style 1</option>
															<option class="form-control" value="scc_tp_style2"
																<?php
																if ($f1->barstyle == 'scc_tp_style2') {
																	echo 'selected';
																}
																?>>Style 2</option>
															<option class="form-control" value="scc_tp_style3"
																<?php
																if ($f1->barstyle == 'scc_tp_style3') {
																	echo 'selected';
																}
																?>>Style 3</option>
															<option class="form-control" value="scc_tp_style4"
																<?php
																if ($f1->barstyle == 'scc_tp_style4') {
																	echo 'selected';
																}
																?>>Style 4</option>
														</select>
													</div>
												</div><!-- End SCC Total Price Style View -->
												<div class="row col-md-12 scc-cal-settings-row scc-vcenter">
													<!--Start Turn on total bar price float -->
													<div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
														<label id="label_turnofffloating" class="scc-calc-settings-lbl">Display Sticky Total Price Bar
															<i class="material-icons-outlined more-settings-info" data-setting-tooltip-type="show-floating-total-bar-tt" data-bs-original-title="" title="" style="margin-right:5px">
																<span class="scc-icn-wrapper"><?php echo scc_get_kses_extended_ruleset($this->scc_icons['help-circle']); ?></span>
															</i>
														</label>
													</div>
													<div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
														<label class="scc-switch">
															<div class="scc-switch use-premium-tooltip">
																<input disabled type="checkbox" name="turn_on_total_price_float" placeholder="" value="turn_on_total_price_float" id="turn_on_total_price_float" class="form-control"
																	<?php
																	if ($f1->turnofffloating == 'true') {
																		echo 'checked';
																	}
																	?>>
																<span class="slider round scc-disabled-premium-toggle"></span>
															</div>
														</label>
													</div>
												</div><!-- End  Turn on total bar price float -->
												<div class="row col-md-12 scc-cal-settings-row scc-vcenter">
													<!--Start Turn on total bar price float -->
													<div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
														<label id="label_turnofffloating_mobile" class="scc-calc-settings-lbl">Show The Sticky Total Bar on Mobile Devices</label>
													</div>
													<div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
														<label class="scc-switch">
															<div class="scc-switch use-premium-tooltip">
																<input disabled type="checkbox" name="show_total_price_float_mobile" id="show_total_price_float_mobile" class="form-control"
																	<?php
																	if (isset($f1->show_total_price_float_mobile) && 1 === intval($f1->show_total_price_float_mobile)) {
																		echo 'checked';
																	}
																	?>>
																<span class="slider round scc-disabled-premium-toggle"></span>
															</div>
														</label>
													</div>
												</div><!-- End  Turn on total bar price float -->

												<div class="row col-md-12 scc-cal-settings-row scc-vcenter">
													<!--Start Show Unit Price -->
													<div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
														<label id="scc-label-show-unit-price" class="scc-calc-settings-lbl">Show <b>Cost Per Unit</b>
															<i class="material-icons-outlined more-settings-info" data-setting-tooltip-type="show-unit-price-tt" data-bs-original-title="" title="" style="margin-right:5px">
																<span class="scc-icn-wrapper"><?php echo scc_get_kses_extended_ruleset($this->scc_icons['help-circle']); ?></span>
															</i>
														</label>
													</div>
													<div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
														<label class="scc-switch">
															<div class="scc-switch use-premium-tooltip">
																<input disabled type="checkbox" name="scc_show_unit_price" onchange="javascript:sccBackendUtils.handleSettingsToggle(this, 1)" placeholder="" value="scc_show_unit_price" id="scc_show_unit_price" class="form-control"
																	<?php
																	if (isset($f1->showUnitPrice) && $f1->showUnitPrice == 'true') {
																		echo 'checked';
																	}
																	?>>
																<span class="slider round scc-disabled-premium-toggle"></span>
															</div>
														</label>
													</div>
												</div><!-- End Show Unit Price -->

												<!--Start Unit Price config - use counter element -->
												<div class="col-sm-12 col-md-12 col-lg-12 scc-cal-settings-row scc-vcenter 
											<?php
											if (isset($f1->showUnitPrice) && $f1->showUnitPrice == 'false') {
												echo 'd-none';
											}
											?>
											">
													<div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
														<label id="scc-label-show-unit-price" class="scc-calc-settings-lbl"><b>Cost Per Unit</b> Mode
															<i class="material-icons-outlined more-settings-info" data-setting-tooltip-type="cost-per-unit-mode-tt" data-bs-original-title="" title="" style="margin-right:5px">
																<span class="scc-icn-wrapper"><?php echo scc_get_kses_extended_ruleset($this->scc_icons['help-circle']); ?></span>
															</i>
														</label>
													</div>
													<div class="col-xs-6 col-sm-6 col-md-6 col-lg-6 use-premium-tooltip">
														<select disabled class="scc-unit-price-counter-ts scc-ts-search form-control" name="scc_unit_price_counter_element_id">
															<option style="font-size: 10px" value="0">Select an element</option>
															<?php if (isset($scc_unit_price_config_array)) { ?>
																<option
																	value="<?php echo intval($scc_unit_price_config_array['element_id']); ?>"
																	selected>
																	<?php echo intval($scc_unit_price_config_array['element_id']); ?>
																</option>
															<?php } else {
															?>
																<option
																	value="0"
																	selected>
																	Select an element
																<?php }
																?>
														</select>
													</div>
												</div><!-- End Unit Price config - use counter element -->

												<div class="row col-md-12 scc-cal-settings-row scc-vcenter">

												</div>
												<div class="row col-md-12 scc-cal-settings-row scc-vcenter">
													<!-- Start Total Price Toggle Button -->
													<div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
														<label id="label_removetotalprice" class="scc-calc-settings-lbl">Remove the <b>Total Price</b>
															<i class="material-icons-outlined more-settings-info" data-setting-tooltip-type="remove-total-price-info-tt" data-bs-original-title="" title="" style="margin-right:5px">
																<span class="scc-icn-wrapper"><?php echo scc_get_kses_extended_ruleset($this->scc_icons['help-circle']); ?></span>
															</i>
														</label>
													</div>
													<div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
														<label class="scc-switch">
															<div class="scc-switch">
																<input type="checkbox" name="scc_remove_total_price_frntd" placeholder="" value="scc_turn_off_total_price_view" id="scc_remove_total_price_frntd" class="form-control"
																	<?php
																	if ($f1->removeTotal == 'true') {
																		echo 'checked';
																	}
																	?>>
																<span class="slider round"></span>
															</div>
														</label>
													</div>
												</div><!-- End Total Price Toggle Button -->
												<div class="row col-md-12 scc-cal-settings-row scc-vcenter">
													<!-- Start Blur Total Price Toggle Button -->
													<div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
														<label id="label_removetotalprice" class="scc-calc-settings-lbl">Blur the <b>Total Price</b>
															<i class="material-icons-outlined more-settings-info" data-setting-tooltip-type="blur-total-price-tt" data-bs-original-title="" title="" style="margin-right:5px">
																<span class="scc-icn-wrapper"><?php echo scc_get_kses_extended_ruleset($this->scc_icons['help-circle']); ?></span>
															</i>
														</label>
													</div>
													<div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
														<label class="scc-switch">
															<div class="scc-switch">
																<input type="checkbox" disabled name="scc_blur_total_price" placeholder="" value="scc_blur_total_price" id="scc_blur_total_price" class="form-control"
																	<?php
																	if (isset($f1->blurTotalPrice) && $f1->blurTotalPrice == 'true') {
																		echo 'checked';
																	}
																	?>>
																<span class="slider round scc-disabled-premium-toggle"></span>
															</div>
														</label>
													</div>
												</div><!-- End Blur Total Price Toggle Button -->
												<div class="row col-md-12 scc-cal-settings-row scc-vcenter">
													<!-- Start Total Price Conditional Revelation -->
													<div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
														<label id="label_showtotalpricev" class="scc-calc-settings-lbl">Mandatory Minimum Total
															<i class="material-icons-outlined more-settings-info" data-setting-tooltip-type="minimum-total-price-tt" data-bs-original-title="" title="" style="margin-right:5px">
																<span class="scc-icn-wrapper"><?php echo scc_get_kses_extended_ruleset($this->scc_icons['help-circle']); ?></span>
															</i>
														</label>
													</div>
													<div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
														<div style="padding:0" class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
															<input type="number" min="0" name="scc_minimum_total_price" id="scc_minimum-total" oninput="javascript:(jQuery(this).val() > 0) ? jQuery('#scc_minimum-total-action').show() : jQuery('#scc_minimum-total-action').hide()" class="form-control has-related-field" value="<?php echo esc_attr($f1->minimumTotal); ?>" style="max-width:100px;min-height: 40px;padding-right: 5px; border:2px solid #F0F0F0;">
														</div>
													</div>
												</div><!-- End Total Price Conditional Revelation -->
												<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 scc-vcenter" id="scc_minimum-total-action" <?php echo ($f1->minimumTotal == '' || $f1->minimumTotal <= 0) ? 'style="display: none"' : ''; ?>>
													<!-- Start Total Price Conditional Revelation Placeholder -->
													<div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
														<label class="scc-calc-settings-lbl">Mandatory Minimum Total - Choose what happens
															<i class="material-icons-outlined more-settings-info" data-setting-tooltip-type="minimum-total-choose-tt" data-bs-original-title="" title="" style="margin-right:5px">
																<span class="scc-icn-wrapper"><?php echo scc_get_kses_extended_ruleset($this->scc_icons['help-circle']); ?></span>
															</i>
														</label>
													</div>
													<div class="col-xs-6 col-sm-6 col-md-6 col-lg-6" style="padding:0">
														<?php
														$minimum_total_warning_modes = [
															['key' => 'show-notice', 'label' => 'Show Notice Only'],
															['key' => 'notice-no-uab', 'label' => 'Show Notice & Conceal Buttons'],
															['key' => 'silent-hide', 'label' => 'Display Notice, Conceal Total & Disable User Actions'],
														];
														?>
														<select name="scc_minimum_total_placeholder" id="scc_minimum_total_placeholder" class="form-control" style="height:40px;">
															<?php foreach ($minimum_total_warning_modes as $warning_mode) {
															?>
																<option class="form-control" value="<?php echo $warning_mode['key']; ?>" <?php selected($warning_mode['key'] === $f1->minimumTotalChoose); ?>>
																	<?php echo $warning_mode['label']; ?>
																</option>
															<?php
															} ?>
														</select>
													</div>
												</div><!-- End Total Price Conditional Revelation Placeholder -->
												<!-- Start Total Price Range Settings -->
												<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 scc-vcenter" id="scc_price_range_total">
													<?php
													$price_range_total_settings_default = [
														'rangePercent' => '0',
													];
													$price_range_total_settings = wp_parse_args(json_decode(wp_unslash(!empty($f1->total_price_range_settings) ? $f1->total_price_range_settings : ''), true), $price_range_total_settings_default);
													?>
													<!-- Start Total Price Conditional Revelation Placeholder -->
													<div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
														<label class="scc-calc-settings-lbl">Total Price Range
															<i class="material-icons-outlined more-settings-info" data-setting-tooltip-type="total-price-range-settings-tt" style="margin-right:5px">
																<span class="scc-icn-wrapper"><?php echo scc_get_kses_extended_ruleset($this->scc_icons['help-circle']); ?></span>
															</i>
														</label>
													</div>
													<div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
														<div style="padding:0" class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
															<input type="number" min="0" name="total_price_range_settings_percent" disabled id="total_price_range_settings_percent" class="form-control d-inline" value="<?php echo esc_attr($price_range_total_settings['rangePercent']); ?>" style="max-width:100px;min-height: 40px;padding-right: 5px; border:2px solid #F0F0F0;">
															<span style="color:#b7c1fb;font-size:20px;font-weight:bold;display:inline-block;padding-left:5px;padding-top:5px;">%</span>
														</div>
													</div>
												</div><!-- End Total Price Range Settings -->
											</div><!-- End Total Price SECTION -->
											<div class="row col-md-12 scc-cal-settings-row scc-setting-subsection scc-total-price-settings">
												<span class="scc-calc-settings-title" id="Total-Price-Settings">Custom Style</span>
												<hr class="scc-calc-settings-hr">
												<!-- Start SCC Total Price Style View -->
												<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 scc-vcenter">
													<div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
														<label id="label_email_template" class="scc-calc-settings-lbl">Add Custom Styles <i class="material-icons-outlined more-settings-info me-1" data-setting-tooltip-type="custom-css-tt" data-bs-original-title="" title="">
																<span class="scc-icn-wrapper"><?php echo scc_get_kses_extended_ruleset($this->scc_icons['help-circle']); ?></span>
															</i>
															<span class="scc-text-secondary">(New)</span>
														</label>
													</div>
													<div class="col-xs-6 col-sm-6 col-md-6 col-lg-6 ">
														<i class="material-icons text-primary scc-custom-code-setup 
																			<?php
																			if ($isSCCFreeVersion) {
																				echo 'disabled';
																			}
																			?>
																			" role="button" data-event-type="custom-css" data-custom-code="<?php echo isset($scc_custom_css_config_array['css']) ? esc_attr($scc_custom_css_config_array['css']) : ''; ?>">
															<span class="scc-icn-wrapper"><?php echo scc_get_kses_extended_ruleset($this->scc_icons['edit']); ?></span>
														</i>
													</div>
												</div>
											</div>
											<!--End of Detailed List Section-->
										</div>
										<!-- END of frontend right section -->
									</div><!--End Of Inside Div-->
								</div>
							</div>
						</div>
					</div>
				</div> <!-- blue section close -->	
		<div class="row" style="padding-right:0px">
			<!--Start Left Section-->
			<div class="col-md-6" style="padding-right:0px">			
						<!-- Start Custom CSS Settings -->
			
						<!--Start PDF settings -->
						<div class="clearfix more-settings-section col-md-12 no-sub-section scc-pdf-settings-section">
							<div class="accordion mt-2" id="calc-settings-modal-accordion3">
								<div class="accordion-item">
									<h2 class="accordion-header">
										<button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#calc-settings-modal-item3" aria-expanded="true" aria-controls="calc-settings-modal-item">
											<div class="sccsubtitle scc_email_quote_label">
												<svg width="34" height="34" viewBox="0 0 34 34" fill="none" xmlns="http://www.w3.org/2000/svg">
													<path d="M19 7H11C10.4696 7 9.96086 7.21071 9.58579 7.58579C9.21071 7.96086 9 8.46957 9 9V25C9 25.5304 9.21071 26.0391 9.58579 26.4142C9.96086 26.7893 10.4696 27 11 27H23C23.5304 27 24.0391 26.7893 24.4142 26.4142C24.7893 26.0391 25 25.5304 25 25V13L19 7Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
													<path d="M19 7V13H25" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
													<path d="M21 18H13" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
													<path d="M21 22H13" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
													<path d="M15 14H14H13" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
												</svg>
												<span>DETAIL LIST & PDF</span> SETTINGS
											</div>
										</button>
									</h2>
									<div id="calc-settings-modal-item3" class="accordion-collapse collapse" data-bs-parent="#calc-settings-modal-accordion3">
										<div class="accordion-body">
											<div class="row col-md-12 scc-cal-settings-row">
												<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12" style="margin-left:10px">
													<!--Start of Any Global Settings Button-->
													<div class="scc-vcenter ms-2">
														<div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
															<label id="label_pdffont" class="scc-calc-settings-lbl">PDF Font Style <i class="material-icons-outlined more-settings-info me-1" data-setting-tooltip-type="pdf-font-style-tt" data-bs-original-title="" title="">
																	<span class="scc-icn-wrapper"><?php echo scc_get_kses_extended_ruleset($this->scc_icons['help-circle']); ?></span>
																</i></label>
														</div>
														<div class="col-xs-6 col-sm-6 col-md-6 col-lg-6" style="padding:0">
															<a href="/wp-admin/admin.php?page=scc-global-settings#scc_pdf_settings" target="_blank" class="scc-calc-settings-btn">In Global Settings</a>
														</div>
													</div>
													<div class="scc-vcenter ms-2">
														<div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
															<label id="label_pdfdate" class="scc-calc-settings-lbl">PDF Date Format <i class="material-icons-outlined more-settings-info me-1" data-setting-tooltip-type="pdf-date-format-tt" data-bs-original-title="" title="">
																	<span class="scc-icn-wrapper"><?php echo scc_get_kses_extended_ruleset($this->scc_icons['help-circle']); ?></span>
																</i></label>
														</div>
														<div class="col-xs-6 col-sm-6 col-md-6 col-lg-6" style="padding:0">
															<a href="/wp-admin/admin.php?page=scc-global-settings#scc_pdf_settings" target="_blank" class="scc-calc-settings-btn">In Global Settings</a>
														</div>
													</div>

													<!-- Start Detailed List Section-->
													<!-- Start Detailed List Title Toggle Button -->
													<div class="row col-md-12 scc-cal-settings-row scc-vcenter" style="margin-top:24px">
														<div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
															<label id="labe_removetitle" class="scc-calc-settings-lbl">Show <b>Title</b>
																<i class="material-icons-outlined more-settings-info" data-setting-tooltip-type="show-title-info-tt" data-bs-original-title="" title="" style="margin-right:5px">
																	<span class="scc-icn-wrapper"><?php echo scc_get_kses_extended_ruleset($this->scc_icons['help-circle']); ?></span>
																</i></label>
														</div>
														<div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
															<label class="scc-switch">
																<div class="scc-switch">
																	<input type="checkbox" name="scc_remove_detailed_list_title" placeholder="" value="scc_remove_detailed_list_title" id="scc_remove_detailed_list_title" class="form-control"
																		<?php
																		if ($f1->removeTitle !== 'true') {
																			echo 'checked';
																		}
																		?>>
																	<span class="slider round"></span>
																</div>
															</label>
														</div>
													</div><!-- End Detailed List Title Toggle Button -->
													<!--turn off unit price column -->
													<div class="row col-md-12 scc-cal-settings-row scc-vcenter">
														<div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
															<label id="label_turnoffprice" class="scc-calc-settings-lbl">Show <b>Unit Price</b> Column
																<i class="material-icons-outlined more-settings-info" data-setting-tooltip-type="show-unit-price-column-tt" data-bs-original-title="" title="" style="margin-right:5px">
																	<span class="scc-icn-wrapper"><?php echo scc_get_kses_extended_ruleset($this->scc_icons['help-circle']); ?></span>
																</i>
															</label>
														</div>
														<div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
															<label class="scc-switch">
																<div class="scc-switch">
																	<input type="checkbox" value="turn_off_save_icon" name="scc_no_unit_col" id="scc_no_unit_col" class="form-control"
																		<?php
																		if ($f1->turnoffUnit !== 'true') {
																			echo 'checked';
																		}
																		?>>
																	<span class="slider round"></span>
																</div>
															</label>
														</div>
													</div><!-- end of turn off unit price column -->
													<!--turn off qty column -->
													<div class="row col-md-12 scc-cal-settings-row scc-vcenter">
														<div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
															<label id="label_turnoffqty" class="scc-calc-settings-lbl">Show <b>Quantity</b> Column
																<i class="material-icons-outlined more-settings-info" data-setting-tooltip-type="show-quantity-column-tt" data-bs-original-title="" title="" style="margin-right:5px">
																	<span class="scc-icn-wrapper"><?php echo scc_get_kses_extended_ruleset($this->scc_icons['help-circle']); ?></span>
																</i>
															</label>
														</div>
														<div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
															<label class="scc-switch">
																<div class="scc-switch">
																	<input type="checkbox" value="turn_off_save_icon" name="scc_no_qty_col" id="scc_no_qty_col" class="form-control"
																		<?php
																		if ($f1->turnoffQty !== 'true') {
																			echo 'checked';
																		}
																		?>>
																	<span class="slider round"></span>
																</div>
															</label>
														</div>
													</div><!-- end of turn off qty column -->
													<!--turn off price column -->
													<div class="row col-md-12 scc-cal-settings-row scc-vcenter">
														<div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
															<label id="label_turnoffprice" class="scc-calc-settings-lbl">Show <b>Price</b> Column
																<i class="material-icons-outlined more-settings-info" data-setting-tooltip-type="show-price-column-tt" data-bs-original-title="" title="" style="margin-right:5px">
																	<span class="scc-icn-wrapper"><?php echo scc_get_kses_extended_ruleset($this->scc_icons['help-circle']); ?></span>
																</i>
															</label>
														</div>
														<div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
															<label class="scc-switch">
																<div class="scc-switch">
																	<input type="checkbox" disabled value="show_price_column" name="show_price_column" id="show_price_column" class="form-control"
																		<?php
																		if (isset($f1->show_price_column) && $f1->show_price_column) {
																			echo 'checked';
																		}
																		?>>
																	<span class="slider round scc-disabled-premium-toggle"></span>
																</div>
															</label>
														</div>
													</div><!-- end of turn off price column -->
													<div class="row col-md-12 scc-cal-settings-row scc-vcenter">
														<!--turn off save icon -->
														<div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
															<label id="label_turnoffsave" class="scc-calc-settings-lbl">Show <b>Save</b> Icon <i class="material-icons-outlined more-settings-info" data-setting-tooltip-type="show-save-icon-tt" data-bs-original-title="" title="" style="margin-right:5px">
																	<span class="scc-icn-wrapper"><?php echo scc_get_kses_extended_ruleset($this->scc_icons['help-circle']); ?></span>
																</i></label>
														</div>
														<div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
															<label class="scc-switch">
																<div class="scc-switch">
																	<input type="checkbox" name="scc_save_icon" placeholder="" value="turn_off_save_icon" id="scc_save_icon" class="form-control scc_save_icon"
																		<?php
																		if ($f1->turnoffSave !== 'true') {
																			echo 'checked';
																		}
																		?>>
																	<span class="slider round"></span>
																</div>
															</label>
														</div>
													</div><!-- end of turn off save icon -->
													<div class="row col-md-12 scc-cal-settings-row scc-vcenter">
														<!-- Start Turn Off Tax Display -->
														<div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
															<label id="label_turnofftax" class="scc-calc-settings-lbl">Show <b>Tax</b> Amount <i class="material-icons-outlined more-settings-info" data-setting-tooltip-type="show-tax-display-tt" data-bs-original-title="" title="" style="margin-right:5px">
																	<span class="scc-icn-wrapper"><?php echo scc_get_kses_extended_ruleset($this->scc_icons['help-circle']); ?></span>
																</i></label>
														</div>
														<div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
															<label class="scc-switch">
																<div class="scc-switch">
																	<input type="checkbox" name="turn_off_tax" placeholder="" value="turn_off_tax" id="turn_off_tax" class="form-control"
																		<?php
																		if ($f1->turnoffTax !== 'true') {
																			echo 'checked';
																		}
																		?>>
																	<span class="slider round"></span>
																</div>
															</label>
														</div>
													</div><!-- End of Turn Off Tax Display -->
													<!-- Start of invoice ID toggle -->
													<div class="row col-md-12 scc-cal-settings-row scc-vcenter">
														<div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
															<label for="show_invoice_number" class="scc-calc-settings-lbl">Show <b>Invoice Number</b> <i class="material-icons-outlined more-settings-info" data-setting-tooltip-type="show-invoice-number-tt" data-bs-original-title="" title="" style="margin-right:5px">
																	<span class="scc-icn-wrapper"><?php echo scc_get_kses_extended_ruleset($this->scc_icons['help-circle']); ?></span>
																</i></label>
														</div>
														<div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
															<label class="scc-switch">
																<div class="scc-switch">
																	<input type="checkbox" name="show_invoice_number" id="show_invoice_number" onchange="javascript:sccBackendUtils.handleSettingsToggleCheckbox(this)" class="form-control"
																		<?php
																		if (isset($scc_invoice_settings) && $scc_invoice_settings['enabled'] == 1) {
																			echo 'checked';
																		}
																		?>>
																	<span class="slider round"></span>
																</div>
															</label>
														</div>
													</div><!-- End of invoice ID toggle -->
													<!-- Start of invoice start number -->
													<div class="row col-md-12 scc-cal-settings-row scc-vcenter 
																						<?php
																						if (isset($scc_invoice_settings) && $scc_invoice_settings['enabled'] != 1) {
																							echo 'd-none';
																						}
																						?>
																						">
														<div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
															<label for="invoice_starting_number" class="scc-calc-settings-lbl"><b>Invoice Number</b> Starting Number <i class="material-icons-outlined more-settings-info" data-setting-tooltip-type="invoice-number-starting-tt" data-bs-original-title="" title="" style="margin-right:5px">
																	<span class="scc-icn-wrapper"><?php echo scc_get_kses_extended_ruleset($this->scc_icons['help-circle']); ?></span>
																</i></label>
														</div>
														<div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
															<div style="padding:0" class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
																<input type="number" disabled name="invoice_starting_number" id="invoice_starting_number" class="form-control" value="<?php echo isset($scc_invoice_settings) ? esc_attr($scc_invoice_settings['startingNumber']) : ''; ?>" style="max-width:70px;min-height: 40px;padding-right: 5px;border:2px solid #F0F0F0;">
															</div>
														</div>
													</div><!-- End of invoice start number -->
													<!-- Start of invoice placement -->
													<div class="row col-md-12 scc-cal-settings-row scc-vcenter 
																						<?php
																						if (isset($scc_invoice_settings) && $scc_invoice_settings['enabled'] != 1) {
																							echo 'd-none';
																						}
																						?>
																						">
														<div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
															<label for="invoice_placement" class="scc-calc-settings-lbl"><b>Invoice</b> Placement <i class="material-icons-outlined more-settings-info" data-setting-tooltip-type="invoice-placement-tt" data-bs-original-title="" title="" style="margin-right:5px">
																	<span class="scc-icn-wrapper"><?php echo scc_get_kses_extended_ruleset($this->scc_icons['help-circle']); ?></span>
																</i></label>
														</div>
														<div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">

															<?php if (isset($scc_invoice_settings)) { ?>
																<select name="invoice_placement" id="invoice_placement" class="form-control" style="height:40px;">
																	<option class="form-control" value="right" <?php selected($scc_invoice_settings['placement'], 'right'); ?>>Right</option>
																	<option class="form-control" value="left" <?php selected($scc_invoice_settings['placement'], 'left'); ?>>Left</option>
																</select>
															<?php } ?>
														</div>
													</div><!-- End of invoice placement -->


													<!-- Start Show User Details on Detailed List & PDF -->
													<div class="row col-md-12 scc-cal-settings-row scc-vcenter">
														<div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
															<label for="show_invoice_number" class="scc-calc-settings-lbl">Show <b>User Details</b> on Detailed List & PDF <i class="material-icons-outlined more-settings-info" data-setting-tooltip-type="show-user-details-detailed-list-pdf-tt" data-bs-original-title="" title="" style="margin-right:5px">
																	<span class="scc-icn-wrapper"><?php echo scc_get_kses_extended_ruleset($this->scc_icons['help-circle']); ?></span>
																</i></label>
														</div>
														<div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
															<label class="scc-switch">
																<div class="scc-switch">
																	<input type="checkbox" id="toggle-build-quote"
																		<?php
																		echo ($f1->ShowFormBuilderOnDetails != 'false') ? 'checked' : '';

																		if ($isSCCFreeVersion) {
																			echo 'disabled';
																		}
																		?>
																		onchange="toggleFormBuilderOnDetails(this)" class="form-control">
																	<span class="slider round scc-disabled-premium-toggle"></span>
																</div>
															</label>
														</div>
													</div>
													<!-- End Show User Details on Detailed List & PDF -->


													<div class="row col-md-12 scc-cal-settings-row scc-vcenter">
														<!--Start of Any Global Settings Button-->
														<div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
															<label id="label_footern" class="scc-calc-settings-lbl">Footer Notes <i class="material-icons-outlined more-settings-info" data-setting-tooltip-type="footer-notes-tt" data-bs-original-title="" title="" style="margin-right:5px">
																	<span class="scc-icn-wrapper"><?php echo scc_get_kses_extended_ruleset($this->scc_icons['help-circle']); ?></span>
																</i></label>
														</div>
														<div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
															<a href="/wp-admin/admin.php?page=scc-global-settings" target="_blank" class="scc-calc-settings-btn">In Global Settings</a>
														</div>
													</div>
													<!--End of Any Global Settings Button-->

												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div><!-- End Blue Section - PDF Options -->

						<!--End PDF settings -->
			<div class="clearfix more-settings-section col-md-12 scc-currency-tax-settings-section">
				<!-- Start Accordion -->
				<div class="accordion mt-2" id="calc-settings-modal-accordion2">
					<div class="accordion-item" >
			<h2 class="accordion-header">
			<button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#calc-settings-modal-item2" aria-expanded="true" aria-controls="calc-settings-modal-item">
				<div class="sccsubtitle scc_email_quote_label">
					<svg width="34" height="34" viewBox="0 0 34 34" fill="none" xmlns="http://www.w3.org/2000/svg">
						<path d="M17 6V28" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
						<path d="M22 10H14.5C13.5717 10 12.6815 10.3687 12.0251 11.0251C11.3687 11.6815 11 12.5717 11 13.5C11 14.4283 11.3687 15.3185 12.0251 15.9749C12.6815 16.6313 13.5717 17 14.5 17H19.5C20.4283 17 21.3185 17.3687 21.9749 18.0251C22.6313 18.6815 23 19.5717 23 20.5C23 21.4283 22.6313 22.3185 21.9749 22.9749C21.3185 23.6313 20.4283 24 19.5 24H11" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
					</svg>
					<span>CURRENCY &amp; TAX</span> SETTINGS
				</div>
			</button>
			</h2>
			<div id="calc-settings-modal-item2" class="accordion-collapse collapse" data-bs-parent="#calc-settings-modal-accordion2">
			<div class="accordion-body">
				<!-- Start TAX Percentage Input -->
				<div class="row col-md-12 scc-cal-settings-row">
					<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 ms-2">
						<span class="scc-calc-settings-title" id="Tax-VAT-Settings">Tax / VAT Settings</span>
						<hr class="scc-calc-settings-hr">
						<div class="scc-vcenter ms-2">
							<div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
								<label id="label_taxvat" class="scc-calc-settings-lbl">Tax/VAT</label>
								<i class="material-icons-outlined more-settings-info me-1" data-setting-tooltip-type="tax-vat-info-tt" data-bs-original-title="" title="">
									<span class="scc-icn-wrapper"><?php echo scc_get_kses_extended_ruleset( $this->scc_icons['help-circle'] ); ?></span>
								</i>
							</div>
							<div style="padding:0;display:flex;" class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
								<input type="number" min="1" max="99" name="scc_tax_amount" value="<?php echo esc_attr( $f1->taxVat ); ?>" id="scc_tax_amount" class="form-control" style="max-width:100px;min-height: 40px;padding-right: 5px;border:2px solid #F0F0F0;">
								<span style="color:#b7c1fb;font-size:20px;font-weight:bold;display:inline-block;padding-left:5px;padding-top:5px;">%</span>
							</div>
						</div>
						<div class="scc-vcenter ms-2">
							<div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
								<label id="label_show_taxvat" class="scc-calc-settings-lbl">Display Tax/VAT On Front End</label>
								<i class="material-icons-outlined more-settings-info me-1" data-setting-tooltip-type="show-tax-vat-before-total-tt" data-bs-original-title="" title="">
									<span class="scc-icn-wrapper"><?php echo scc_get_kses_extended_ruleset( $this->scc_icons['help-circle'] ); ?></span>
								</i>
							</div>
							<div style="padding:5px 0 0 0;display:flex;" class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
								<label class="scc-switch">
									<div class="scc-switch">
										<input type="checkbox" name="scc_show_taxvat" name="scc_show_taxvat" placeholder="" value="scc_show_taxvat" id="scc_show_taxvat" class="form-control" 
										<?php
                                        if ( $f1->showTaxBeforeTotal == 'true' ) {
                                            echo 'checked';
                                        }
?>
										>
										<span class="slider round"></span>
									</div>
								</label>
							</div>
						</div>
					</div>
				</div>
				<!-- END TAX Percentage Input -->
				<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 ms-2">
						<span class="scc-calc-settings-title" id="Currency-Settings">Currency Settings</span>
						<hr class="scc-calc-settings-hr">
						<!-- Start of Currency Symbol or Letters -->
						<div class="scc-vcenter ms-2">
							<div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
								<label id="label_symbolpl" class="scc-calc-settings-lbl"> Symbol Placement Style <i class="material-icons-outlined more-settings-info me-1" data-setting-tooltip-type="symbol-placement-style-tt" data-bs-original-title="" title="">
									<span class="scc-icn-wrapper"><?php echo scc_get_kses_extended_ruleset( $this->scc_icons['help-circle'] ); ?></span>
								</i></label>
							</div>
							<div class="col-xs-6 col-sm-6 col-md-6 col-lg-6" style="padding:0">
								<select name="scc_currency_style" class="form-control scc_currency_style" style="font-size: 13px!important;height:40px;">
									<option class="form-control" value="0" 
									<?php
                                    if ( $f1->symbol == '0' ) {
                                        echo 'selected';
                                    }
?>
									>Currency Symbol (Example: $53)</option>
									<option class="form-control" value="1" 
									<?php
if ( $f1->symbol == '1' ) {
    echo 'selected';
}
?>
									>Currency Letters (Example: 53 CAD)</option>
								</select>
							</div>
						</div>
						<!-- End of Currency Symbol or Letters -->
						<!-- Start of Price Rounding -->
						<div class="scc-vcenter ms-2">
							<div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
								<label id="label_symbolpl" class="scc-calc-settings-lbl"> Price Rounding <i class="material-icons-outlined more-settings-info me-1" data-setting-tooltip-type="price-rounding-tt" data-bs-original-title="" title="">
									<span class="scc-icn-wrapper"><?php echo scc_get_kses_extended_ruleset( $this->scc_icons['help-circle'] ); ?></span>
								</i></label>
							</div>
							<div class="col-xs-6 col-sm-6 col-md-6 col-lg-6" style="padding:0">
								<select name="scc_rounding_type" disabled class="form-control scc_rounding_type scc-setting-input">
									<option
										value="not_rounded"
										<?php
                                    if ( isset($scc_price_rounding_config_array) && $scc_price_rounding_config_array['rounding_type'] === 'not_rounded' ||
                                            ! empty( $scc_price_rounding_config_array['rounding_type'] ) ) {
                                        echo 'selected';
                                    }
?>
										selected>
										<?php echo _( 'Not rounded' ); ?>
									</option>
									<option
										value="nearest_tenth"
										<?php
if ( isset($scc_price_rounding_config_array) && $scc_price_rounding_config_array['rounding_type'] === 'nearest_tenth' ) {
    echo 'selected';
}
?>
										>
										<?php echo _( 'Nearest Tenth (e.g. $12.57 -> $12.6)' ); ?>
									</option>
									<option
										value="nearest_whole_number"
										<?php
if ( isset($scc_price_rounding_config_array) && $scc_price_rounding_config_array['rounding_type'] === 'nearest_whole_number' ) {
    echo 'selected';
}
?>
										>
										<?php echo _( 'Nearest Whole Number (e.g. $20.61 -> $21)' ); ?>
									</option>
									<option
										value="nearest_tens"
										<?php
if ( isset($scc_price_rounding_config_array) && $scc_price_rounding_config_array['rounding_type'] === 'nearest_tens' ) {
    echo 'selected';
}
?>
										>
										<?php echo _( 'Nearest Tens (e.g. $27.20 -> $30)' ); ?>
									</option>
									<option
										value="nearest_hundreds"
										<?php
if ( isset($scc_price_rounding_config_array) && $scc_price_rounding_config_array['rounding_type'] === 'nearest_hundreds' ) {
    echo 'selected';
}
?>
										>
										<?php echo _( 'Nearest Hundreds (e.g. $165 -> $200)' ); ?>
									</option>
									<option
										value="nearest_thousands"
										<?php
if ( isset($scc_price_rounding_config_array) && $scc_price_rounding_config_array['rounding_type'] === 'nearest_thousands' ) {
    echo 'selected';
}
?>
										>
										<?php echo _( 'Nearest Thousands (e.g. $1741.89 -> $2000)' ); ?>
									</option>	
								</select>
							</div>
						</div>
						<!-- End of Price Rounding -->
						<!-- Start Currency label Toggle Button -->
						<div class="scc-vcenter ms-2">
							<div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
								<label id="label_removecurla" class="scc-calc-settings-lbl">Show Currency label </label>
								<i class="material-icons-outlined more-settings-info me-1" data-setting-tooltip-type="show-currency-label-tt" data-bs-original-title="" title="">
									<span class="scc-icn-wrapper"><?php echo scc_get_kses_extended_ruleset( $this->scc_icons['help-circle'] ); ?></span>
								</i>
							</div>
							<div class="col-xs-6 col-sm-6 col-md-6 col-lg-6" style="padding: 0;">
								<label class="scc-switch">
									<div class="scc-switch">
										<input type="checkbox" name="scc_remove_currency_label" placeholder="" value="scc_remove_currency_label" id="scc_remove_currency_label" class="form-control" 
										<?php
    if ( $f1->removeCurrency !== 'true' ) {
        echo 'checked';
    }
?>
										>
										<span class="slider round"></span>
									</div>
								</label>
							</div>
						</div>
						<div class="scc-vcenter ms-2">
							<div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
								<label id="label_removecurla" class="scc-calc-settings-lbl">Allow users to choose their currency</label>
								<i class="material-icons-outlined more-settings-info me-1" data-setting-tooltip-type="allow-users-choose-currency-tt" data-bs-original-title="" title="">
									<span class="scc-icn-wrapper"><?php echo scc_get_kses_extended_ruleset( $this->scc_icons['help-circle'] ); ?></span>
								</i>
							</div>
							<div class="col-xs-6 col-sm-6 col-md-6 col-lg-6" style="padding: 0;">
								<label class="scc-switch">
									<div class="scc-switch">
										<input type="checkbox" name="scc_frontend_allow_currency_switching" placeholder="" value="scc_frontend_allow_currency_switching" id="scc_frontend_allow_currency_switching" class="form-control" 
										<?php
if ( isset($allow_currency_switching) && $f1->allow_currency_switching ) {
    echo 'checked';
}
?>
										>
										<span class="slider round scc-disabled-premium-toggle"></span>
									</div>
								</label>
							</div>
						</div>
						<div class="scc-vcenter ms-2">
							<!--Start of Any Global Settings Button-->
							<div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
								<label id="label_curselec" class="scc-calc-settings-lbl">Currency Selector ($,€,£) <i class="material-icons-outlined more-settings-info me-1" data-setting-tooltip-type="currency-selector-tt" data-bs-original-title="" title="">
									<span class="scc-icn-wrapper"><?php echo scc_get_kses_extended_ruleset( $this->scc_icons['help-circle'] ); ?></span>
								</i></label>
							</div>
							<div class="col-xs-6 col-sm-6 col-md-6 col-lg-6" style="padding:0">
								<a href="/wp-admin/admin.php?page=scc-global-settings" target="_blank" class="scc-calc-settings-btn">In Global Settings</a>
							</div>
						</div>
						<!--End of Any Global Settings Button-->
						<!--Start of Any Global Settings Button-->
						<div class="scc-vcenter ms-2">
							<div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
								<label id="label_autocurr" class="scc-calc-settings-lbl">Auto Currency Conversion <i class="material-icons-outlined more-settings-info me-1" data-setting-tooltip-type="auto-currency-conversion-tt" data-bs-original-title="" title="">
									<span class="scc-icn-wrapper"><?php echo scc_get_kses_extended_ruleset( $this->scc_icons['help-circle'] ); ?></span>
								</i></label>
							</div>
							<div class="col-xs-6 col-sm-6 col-md-6 col-lg-6" style="padding:0">
								<a href="/wp-admin/admin.php?page=scc-global-settings" target="_blank" class="scc-calc-settings-btn">In Global Settings</a>
							</div>
						</div>
						<!--End of Any Global Settings Button-->
						<!--Start of Any Global Settings Button-->
						<div class="scc-vcenter ms-2">
							<div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
								<label id="label_currform" class="scc-calc-settings-lbl">Currency Format (dot/comma) <i class="material-icons-outlined more-settings-info me-1" data-setting-tooltip-type="currency-format-tt" data-bs-original-title="" title="">
									<span class="scc-icn-wrapper"><?php echo scc_get_kses_extended_ruleset( $this->scc_icons['help-circle'] ); ?></span>
								</i></label>
							</div>
							<div class="col-xs-6 col-sm-6 col-md-6 col-lg-6" style="padding:0">
								<a href="/wp-admin/admin.php?page=scc-global-settings" target="_blank" class="scc-calc-settings-btn">In Global Settings</a>
							</div>
						</div>
						<!--End of Any Global Settings Button-->
					</div>
			</div>
			</div>
			</div>
			</div><!-- End Accordion -->
				<div class="row col-md-12 scc-cal-settings-row">
					<!-- Start of END Currency Symbol or Letters -->

				</div><!-- End Currency label Toggle Button 2 -->
			</div><!-- End Blue Section - Currency & Tax-->
			
			<div class="clearfix more-settings-section col-md-12 no-sub-section scc-email-setting-section">
				<!-- Start Blue Section - Email Settings -->
				<div class="accordion mt-2" id="calc-settings-modal-accordion4">
					<div class="accordion-item" >
						<h2 class="accordion-header">
						<button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#calc-settings-modal-item4" aria-expanded="true" aria-controls="calc-settings-modal-item">
							<div class="sccsubtitle scc_email_quote_label">								
								<svg width="34" height="34" viewBox="0 0 34 34" fill="none" xmlns="http://www.w3.org/2000/svg">
									<g clip-path="url(#clip0_872_348)">
									<path fill-rule="evenodd" clip-rule="evenodd" d="M17.3327 8.16735C15.8606 8.16766 14.4103 8.52247 13.1044 9.20178C11.7984 9.8811 10.6753 10.8649 9.83 12.0701C8.98467 13.2752 8.44202 14.6662 8.24793 16.1254C8.05384 17.5846 8.21403 19.0691 8.71494 20.4533C9.21586 21.8375 10.0428 23.0807 11.1257 24.0778C12.2087 25.0748 13.5158 25.7964 14.9366 26.1815C16.3574 26.5666 17.85 26.6038 19.2883 26.2901C20.7265 25.9763 22.068 25.3208 23.1994 24.379C23.455 24.1762 23.7798 24.0814 24.1044 24.1148C24.429 24.1482 24.7277 24.3073 24.9366 24.558C25.1455 24.8087 25.2481 25.1311 25.2224 25.4564C25.1968 25.7818 25.045 26.0841 24.7994 26.299C22.7604 27.9973 20.2033 28.9498 17.5502 28.9993C14.897 29.0487 12.3063 28.1923 10.2054 26.5711C8.10453 24.95 6.61908 22.6611 5.99416 20.0821C5.36923 17.5031 5.64217 14.7882 6.76794 12.3852C7.89371 9.9822 9.80505 8.03479 12.1866 6.86428C14.5681 5.69378 17.2775 5.37013 19.8677 5.94672C22.4579 6.52332 24.7742 7.96572 26.4343 10.0359C28.0944 12.1061 28.9992 14.6804 28.9993 17.334C28.9994 18.1228 28.7755 18.8953 28.3538 19.5618C27.9321 20.2284 27.3298 20.7615 26.6171 21.0993C25.9043 21.4371 25.1104 21.5656 24.3274 21.47C23.5445 21.3743 22.8048 21.0584 22.1944 20.559C21.4209 21.7255 20.2538 22.575 18.9061 22.9525C17.5583 23.33 16.1198 23.2103 14.8529 22.6154C13.5861 22.0204 12.5754 20.9897 12.0052 19.7115C11.4351 18.4333 11.3436 16.9927 11.7474 15.6525C12.1511 14.3124 13.0233 13.1622 14.2046 12.4116C15.386 11.6611 16.7978 11.3604 18.1825 11.5643C19.5672 11.7682 20.8324 12.4631 21.7473 13.5223C22.6622 14.5816 23.1657 15.9344 23.166 17.334C23.166 17.776 23.3416 18.2 23.6542 18.5125C23.9667 18.8251 24.3907 19.0007 24.8327 19.0007C25.2747 19.0007 25.6986 18.8251 26.0112 18.5125C26.3238 18.2 26.4994 17.776 26.4994 17.334C26.4994 14.9029 25.5336 12.5713 23.8145 10.8522C22.0954 9.13313 19.7638 8.16735 17.3327 8.16735ZM20.666 17.334C20.666 16.45 20.3148 15.6021 19.6897 14.977C19.0646 14.3519 18.2167 14.0007 17.3327 14.0007C16.4486 14.0007 15.6008 14.3519 14.9757 14.977C14.3505 15.6021 13.9994 16.45 13.9994 17.334C13.9994 18.2181 14.3505 19.0659 14.9757 19.691C15.6008 20.3162 16.4486 20.6673 17.3327 20.6673C18.2167 20.6673 19.0646 20.3162 19.6897 19.691C20.3148 19.0659 20.666 18.2181 20.666 17.334Z" fill="currentColor"/>
									</g>
									<defs>
									<clipPath id="clip0_872_348">
									<rect width="24" height="24.0012" fill="white" transform="translate(5 5)"/>
									</clipPath>
									</defs>
								</svg>
								<span>EMAIL</span> SETTINGS
							</div>
						</button>
						</h2>
						<div id="calc-settings-modal-item4" class="accordion-collapse collapse" data-bs-parent="#calc-settings-modal-accordion4">
							<div class="accordion-body">
							<div class="scc-vcenter ms-2">
					<div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
						<label id="label_email_quote_recipients" class="scc-calc-settings-lbl">Email Quote Recipient(s) <i class="material-icons-outlined more-settings-info me-1" data-setting-tooltip-type="email-quote-recipient-tt" data-bs-original-title="" title="">
									<span class="scc-icn-wrapper"><?php echo scc_get_kses_extended_ruleset( $this->scc_icons['help-circle'] ); ?></span>
								</i></label>
					</div>
					<div class="col-xs-6 col-sm-6 col-md-6 col-lg-6" style="padding:0">
						<select name="email_quote_recipients" class="form-control" style="font-size: 13px!important;height:40px;">
							<option class="form-control" value="0" <?php selected( $f1->emailQuoteRecipients == 0 ); ?>>Admin Only</option>
							<option class="form-control" value="1" <?php selected( $f1->emailQuoteRecipients == 1 ); ?>>Admin and User</option>
							<option class="form-control" value="2" <?php selected( $f1->emailQuoteRecipients == 2 ); ?>>User Only</option>
						</select>
					</div>
				</div>
				<div class="scc-vcenter ms-2">
					<div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
						<label id="label_email_quote_recipients" class="scc-calc-settings-lbl"><span>Display PDF filename in the Quote Request Popup</span>&nbsp;
							<i class="material-icons-outlined more-settings-info" data-setting-tooltip-type="pdf-file-name-on-email-quote-form-tt">
								<span class="scc-icn-wrapper"><?php echo scc_get_kses_extended_ruleset( $this->scc_icons['help-circle'] ); ?></span>
							</i>
						</label>
					</div>
					<div class="col-xs-6 col-sm-6 col-md-6 col-lg-6 ">
						<label class="scc-switch">
							<div class="scc-switch">
								<input type="checkbox" data-target="quote-fillup" name="quote_form_show_pdf_name" 
								<?php
                                if ( isset($f1->quote_form_show_pdf_name) && ! empty( intval( $f1->quote_form_show_pdf_name ) ) ) {
                                    echo 'checked';
                                }
?>
								 id="quote_form_show_pdf_name" class="form-control webhook-setup">
								<span class="slider round scc-disabled-premium-toggle"></span>
							</div>
						</label>
					</div>
				</div>
				<div class="scc-vcenter ms-2">
					<div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
						<label id="label_email_quote_recipients" class="scc-calc-settings-lbl">Include User Details to the Admin's Email <i class="material-icons-outlined more-settings-info me-1" data-setting-tooltip-type="include-quote-submitted-data-tt" data-bs-original-title="" title="">
							<span class="scc-icn-wrapper"><?php echo scc_get_kses_extended_ruleset( $this->scc_icons['help-circle'] ); ?></span>
						</i>	
						</label>
					</div>
					<div class="col-xs-6 col-sm-6 col-md-6 col-lg-6 ">
						<label class="scc-switch">
							<div class="scc-switch">
								<input type="checkbox" data-target="quote-fillup" name="include_quote_form_data" 
								<?php
                                if ( isset($f1->include_quote_form_data) && ! empty( intval( $f1->include_quote_form_data ) ) ) {
                                    echo 'checked';
                                }
?>
								 id="include_quote_form_data" class="form-control webhook-setup">
								<span class="slider round scc-disabled-premium-toggle"></span>
							</div>
						</label>
					</div>
				</div>
				<div class="scc-vcenter ms-2">
					<div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
						<label id="label_add_user_files_to_attachment" class="scc-calc-settings-lbl">Add User Uploaded Files As Attachment(s) <i class="material-icons-outlined more-settings-info me-1" data-setting-tooltip-type="user-files-as-attachment-tt">
							<span class="scc-icn-wrapper"><?php echo scc_get_kses_extended_ruleset( $this->scc_icons['help-circle'] ); ?></span>
						</i>	
						</label>
					</div>
					<div class="col-xs-6 col-sm-6 col-md-6 col-lg-6 ">
						<label class="scc-switch">
							<div class="scc-switch">
								<input type="checkbox" data-target="quote-fillup" name="toggle_add_user_files_to_attachment" 
								<?php
if ( isset( $f1->add_user_files_to_email_attachment ) && intval( $f1->add_user_files_to_email_attachment ) ) {
    echo 'checked';
}
?>
								 id="toggle_add_user_files_to_attachment" class="form-control">
								<span class="slider round scc-disabled-premium-toggle"></span>
							</div>
						</label>
					</div>
				</div>
					<div class="scc-vcenter ms-2">
						<div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
							<label id="label_email_template" class="scc-calc-settings-lbl">Calculator Specific Email Template <i class="material-icons-outlined more-settings-info me-1" data-setting-tooltip-type="email-template-edit-tt">
								<span class="scc-icn-wrapper"><?php echo scc_get_kses_extended_ruleset( $this->scc_icons['help-circle'] ); ?></span>
							</i>
							<span class="scc-text-secondary">(New)</span>
							</label>
						</div>
						<div class="col-xs-6 col-sm-6 col-md-6 col-lg-6 ">
							<i class="material-icons text-primary 
							<?php
                            if ( $isSCCFreeVersion ) {
                                echo 'disabled';
                            }
?>
							" onclick="showEmailTemplateEditorModal()" role="button" data-event-type="detail-btn">
								<span class="scc-icn-wrapper"><?php echo scc_get_kses_extended_ruleset( $this->scc_icons['edit'] ); ?></span>
							</i>
						</div>
					</div>
					<div class="scc-vcenter ms-2">
					<div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
						<label id="post-quote-redirect-page" class="scc-calc-settings-lbl">Thank You Page Redirect <i class="material-icons-outlined more-settings-info me-1" data-setting-tooltip-type="post-quote-redirect-page-tt">
									<span class="scc-icn-wrapper"><?php echo scc_get_kses_extended_ruleset( $this->scc_icons['help-circle'] ); ?></span>
								</i></label>
					</div>
					<div class="col-xs-6 col-sm-6 col-md-6 col-lg-6 use-premium-tooltip" style="padding:0">
						<select disabled name="post-quote-redirect-page" class="form-control use-tom-select" style="font-size: 13px!important;height:40px;">
							<option class="form-control" value="0">Select A Page</option>
							
							<?php 
							if(isset($this->wp_available_posts)){
								foreach ( $this->wp_available_posts as $key => $value ) { ?>
									<option class="form-control" data-post-link="<?php echo esc_url( $value['link'] ); ?>" value="<?php echo $key; ?>" <?php selected( $key === intval( $f1->post_quote_redirect_page ) ); ?>><?php echo $value['title']; ?></option>
							<?php
								}
							}
?>
						</select>
					</div>
				</div>
							</div>
						</div>
					</div>
				</div>
			</div><!-- End Blue Section - Email Settings -->
			</div>
		<!--END Left Section-->
		<!--Start right Section-->
		<div class="col-md-6" style="padding-right:0px">
			<!-- Start Text Message (SMS) Settings -->
			<div class="clearfix more-settings-section col-md-12 no-sub-section scc-text-message-settings-section">
				<div class="accordion mt-2" id="calc-settings-modal-accordion5">
					<div class="accordion-item" >
						<h2 class="accordion-header">
						<button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#calc-settings-modal-item5" aria-expanded="true" aria-controls="calc-settings-modal-item">
							<div class="sccsubtitle scc_email_quote_label">								
								<svg width="34" height="34" viewBox="0 0 34 34" fill="none" xmlns="http://www.w3.org/2000/svg">
									<path d="M13.3 16.45C13.5975 16.45 13.847 16.3492 14.0486 16.1476C14.2502 15.946 14.3507 15.6968 14.35 15.4C14.3493 15.1032 14.2485 14.854 14.0476 14.6524C13.8467 14.4508 13.5975 14.35 13.3 14.35C13.0025 14.35 12.7533 14.4508 12.5524 14.6524C12.3515 14.854 12.2507 15.1032 12.25 15.4C12.2493 15.6968 12.3501 15.9463 12.5524 16.1486C12.7547 16.3509 13.0039 16.4514 13.3 16.45ZM17.5 16.45C17.7975 16.45 18.047 16.3492 18.2486 16.1476C18.4502 15.946 18.5507 15.6968 18.55 15.4C18.5493 15.1032 18.4485 14.854 18.2476 14.6524C18.0467 14.4508 17.7975 14.35 17.5 14.35C17.2025 14.35 16.9533 14.4508 16.7524 14.6524C16.5515 14.854 16.4507 15.1032 16.45 15.4C16.4493 15.6968 16.5501 15.9463 16.7524 16.1486C16.9547 16.3509 17.2039 16.4514 17.5 16.45ZM21.7 16.45C21.9975 16.45 22.247 16.3492 22.4486 16.1476C22.6502 15.946 22.7507 15.6968 22.75 15.4C22.7493 15.1032 22.6485 14.854 22.4476 14.6524C22.2467 14.4508 21.9975 14.35 21.7 14.35C21.4025 14.35 21.1533 14.4508 20.9524 14.6524C20.7515 14.854 20.6507 15.1032 20.65 15.4C20.6493 15.6968 20.7501 15.9463 20.9524 16.1486C21.1547 16.3509 21.4039 16.4514 21.7 16.45ZM7 28V9.1C7 8.5225 7.2058 8.0283 7.6174 7.6174C8.029 7.2065 8.5232 7.0007 9.1 7H25.9C26.4775 7 26.972 7.2058 27.3836 7.6174C27.7952 8.029 28.0007 8.5232 28 9.1V21.7C28 22.2775 27.7945 22.772 27.3836 23.1836C26.9727 23.5952 26.4782 23.8007 25.9 23.8H11.2L7 28ZM10.3075 21.7H25.9V9.1H9.1V22.8812L10.3075 21.7Z" fill="currentColor"/>
								</svg>
								<span>TEXT MESSAGE (SMS)</span> SETTINGS
							</div>
						</button>
						</h2>
						<div id="calc-settings-modal-item5" class="accordion-collapse collapse" data-bs-parent="#calc-settings-modal-accordion5">
							<div class="accordion-body">
								<div class="scc-vcenter" style="margin-left: 10px;">
								<div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
									<label id="label_email_template" class="scc-calc-settings-lbl">Calculator Specific Text Message Settings <i class="material-icons-outlined more-settings-info me-1">
										<span class="scc-icn-wrapper"><?php echo scc_get_kses_extended_ruleset( $this->scc_icons['help-circle'] ); ?></span>
									</i>
									<span class="scc-text-secondary">(New)</span>
									</label>
								</div>
								<div class="col-xs-6 col-sm-6 col-md-6 col-lg-6 ">
									<i class="material-icons text-primary 
									<?php
        if ( $isSCCFreeVersion ) {
            echo 'disabled';
        }
?>
									" onclick="showTextingSettingsEditorModal()" role="button" data-event-type="detail-btn">
										<span class="scc-icn-wrapper"><?php echo scc_get_kses_extended_ruleset( $this->scc_icons['edit'] ); ?></span>
									</i>
								</div>
							</div>
							</div>
						</div>
					</div>
				</div>
			</div>
			<!-- End Text Message (SMS) Settings -->
			<div class="clearfix more-settings-section col-md-12 no-sub-section scc-webhook-events-section" id="scc-event-actions">
				<!-- Start Blue Sec - Webhook Events -->
				<div class="accordion mt-2" id="calc-settings-modal-accordion6">
					<div class="accordion-item" >
						<h2 class="accordion-header">
						<button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#calc-settings-modal-item6" aria-expanded="true" aria-controls="calc-settings-modal-item">
							<div class="sccsubtitle scc_email_quote_label">								
								<svg width="34" height="34" viewBox="0 0 34 34" fill="none" xmlns="http://www.w3.org/2000/svg">
									<g clip-path="url(#clip0_872_356)">
									<path d="M14.8548 25.3025C13.1191 27.7634 9.73095 28.3816 7.28194 26.6697C4.84483 24.9578 4.27418 21.5458 5.98611 19.0611C6.46519 18.3664 7.09888 17.7922 7.83738 17.3838C8.57587 16.9753 9.39903 16.7438 10.2422 16.7072L10.3016 18.4073C9.21975 18.4905 8.17357 19.0492 7.49593 20.0241C6.3071 21.736 6.67564 24.0424 8.30434 25.1955C9.94494 26.3368 12.2394 25.8969 13.4282 24.1969C13.7968 23.6619 14.0108 23.0794 14.094 22.485V21.2843L20.7277 21.2367L20.8109 21.1059C21.441 20.0122 22.8082 19.6318 23.8781 20.25C24.1342 20.3998 24.3582 20.5986 24.5375 20.8351C24.7167 21.0715 24.8476 21.341 24.9226 21.628C24.9977 21.915 25.0155 22.214 24.975 22.508C24.9345 22.8019 24.8365 23.0849 24.6865 23.3409C24.0565 24.4228 22.6774 24.8032 21.6074 24.185C21.12 23.9116 20.7753 23.4717 20.6207 22.9724L15.7821 22.9962C15.639 23.8217 15.323 24.6077 14.8548 25.3025ZM23.5096 16.8142C26.5173 17.1828 28.6573 19.8814 28.2887 22.8416C27.9202 25.8137 25.1858 27.918 22.1781 27.5494C21.3429 27.4521 20.5411 27.1646 19.8342 26.7092C19.1273 26.2537 18.5342 25.6424 18.1004 24.9221L19.5745 24.0661C19.8795 24.5383 20.2847 24.9376 20.7614 25.2355C21.2381 25.5335 21.7745 25.7228 22.3326 25.79C24.4131 26.0396 26.2558 24.6249 26.5055 22.6395C26.7551 20.6542 25.281 18.8352 23.2243 18.5856C22.5823 18.5143 21.9641 18.5975 21.4053 18.7996L20.3948 19.3227L17.3276 13.6519H17.0661C16.4676 13.6345 15.9003 13.3809 15.4882 12.9465C15.0761 12.5121 14.8527 11.9323 14.8667 11.3337C14.9024 10.0973 15.9724 9.13433 17.2206 9.18188C18.4689 9.25321 19.4556 10.2637 19.42 11.5001C19.3962 12.0232 19.1941 12.4987 18.8731 12.8673L21.1319 17.0401C21.869 16.8023 22.6774 16.7191 23.5096 16.8142ZM12.2275 13.5806C11.0387 10.7868 12.2988 7.58884 15.0451 6.42378C17.8032 5.25871 20.9892 6.57833 22.1781 9.3721C22.8795 11.0008 22.7368 12.7841 21.9403 14.2107L20.4662 13.3547C20.9655 12.3917 21.0487 11.2148 20.5732 10.1092C19.7647 8.20703 17.6129 7.29163 15.7702 8.06437C13.9157 8.84901 13.0835 11.0365 13.8919 12.9386C14.2248 13.7232 14.7835 14.3296 15.4612 14.7338L15.9248 14.9834L12.2751 20.9157C12.3107 20.9752 12.3583 21.0465 12.3939 21.1416C12.9765 22.2234 12.5723 23.5906 11.4785 24.1731C10.3967 24.7557 9.02954 24.3277 8.43512 23.2102C7.85259 22.1046 8.25679 20.7374 9.35052 20.1549C9.81417 19.9052 10.3254 19.8458 10.8128 19.9528L13.559 15.4708C13.0003 14.9596 12.5247 14.3177 12.2275 13.5806Z" fill="currentColor"/>
									</g>
									<defs>
									<clipPath id="clip0_872_356">
									<rect width="24" height="22" fill="white" transform="translate(5 6)"/>
									</clipPath>
									</defs>
								</svg>
								<span>WEBHOOK</span> EVENTS TRIGGER
							</div>
						</button>
						</h2>
						<div id="calc-settings-modal-item6" class="accordion-collapse collapse" data-bs-parent="#calc-settings-modal-accordion6">
							<div class="accordion-body">
							<span class="scc-calc-settings-title ms-2">Raw Webhooks</span>
				<hr class="scc-calc-settings-hr">
				<div class="row col-md-12 scc-cal-settings-row scc-vcenter ms-2">
					<!-- Start of email quote webhook setup -->
					<div class="col-xs-12 col-sm-6 col-md-6 col-lg-6 
					<?php
                if ( $isSCCFreeVersion ) {
                    echo 'use-tooltip';
                }
?>
					" 
					<?php
if ( $isSCCFreeVersion ) {
    echo 'title="You need purchase Stylish Cost Calculator Premium"';
}
?>
>
						<label id="label_usercompl" class="scc-calc-settings-lbl">
							User completes an <b>Email Quote Form</b>
							<i class="material-icons-outlined more-settings-info" data-setting-tooltip-type="user-completes-email-quote-form-tt" data-bs-original-title="" title="">
							<span class="scc-icn-wrapper"><?php echo scc_get_kses_extended_ruleset( $this->scc_icons['help-circle'] ); ?></span>
						</i>
						</label>
					</div>
					<div class="col-xs-6 col-sm-6 col-md-6 col-lg-6 
					<?php
if ( $isSCCFreeVersion ) {
    echo 'use-tooltip';
}
?>
					" 
					<?php
if ( $isSCCFreeVersion ) {
    echo 'title="You need purchase Stylish Cost Calculator Premium"';
}
?>
>
						<label class="scc-switch">
							<div class="scc-switch">
								<input 
								<?php
            if ( $isSCCFreeVersion ) {
                echo 'disabled';
            }
?>
								 type="checkbox" data-target="quote-fillup" name="scc_set_webhook_quote" id="scc_set_webhook_quote" class="form-control webhook-setup" 
 <?php
    if ( $f1->userCompletes == 'true' ) {
        echo 'checked';
    }
?>
>
								<span class="slider round scc-disabled-premium-toggle"></span>
							</div>
						</label>
						<i class="material-icons webhook-setup 
						<?php
                    if ( $isSCCFreeVersion ) {
                        echo 'disabled';
                    }
?>
						" data-event-type="quote-fillup">
							<span class="scc-icn-wrapper"><?php echo scc_get_kses_extended_ruleset( $this->scc_icons['edit'] ); ?></span>
						</i>
					</div>
				</div><!-- End of email quote webhook setup -->
				<div class="row col-md-12 scc-cal-settings-row scc-vcenter ms-2">
					<!-- Start Toggle Btn for Detailed List Webhook -->
					<div class="col-xs-12 col-sm-6 col-md-6 col-lg-6 
					<?php
                    if ( $isSCCFreeVersion ) {
                        echo 'use-tooltip';
                    }
?>
					" 
					<?php
if ( $isSCCFreeVersion ) {
    echo 'title="You need purchase Stylish Cost Calculator Premium"';
}
?>
>
						<label id="label_userclicks" class="scc-calc-settings-lbl">
							User clicks the <b>Detailed List</b> button
							<i class="material-icons-outlined more-settings-info me-1" data-setting-tooltip-type="user-clicks-detailed-list-button-tt" data-bs-original-title="" title="">
							<span class="scc-icn-wrapper"><?php echo scc_get_kses_extended_ruleset( $this->scc_icons['help-circle'] ); ?></span>
						</i>
						</label>
					</div>
					<div class="col-xs-6 col-sm-6 col-md-6 col-lg-6 
					<?php
if ( $isSCCFreeVersion ) {
    echo 'use-tooltip';
}
?>
					" 
					<?php
if ( $isSCCFreeVersion ) {
    echo 'title="You need purchase Stylish Cost Calculator Premium"';
}
?>
>
						<label class="scc-switch">
							<div class="scc-switch">
								<input 
								<?php
            if ( $isSCCFreeVersion ) {
                echo 'disabled';
            }
?>
								 type="checkbox" data-target="detail-btn" name="scc_set_webhook_detail_view" id="scc_set_webhook_detail_view" class="form-control webhook-setup" 
								<?php
if ( $f1->userClicksf == 'true' ) {
    echo 'checked';
}
?>
>
								<span class="slider round scc-disabled-premium-toggle"></span>
							</div>
						</label>
						<i class="material-icons webhook-setup 
						<?php
                        if ( $isSCCFreeVersion ) {
                            echo 'disabled';
                        }
?>
						" data-event-type="detail-btn">
							<span class="scc-icn-wrapper"><?php echo scc_get_kses_extended_ruleset( $this->scc_icons['edit'] ); ?></span>
						</i>
					</div>
				</div><!-- End of email quote custom js setup -->
				<div class="scc-calc-settings-title" id="scc_custom_js_code" style="margin-top:20px;margin-left: 10px;">Trigger Custom Code</div>
				<hr class="scc-calc-settings-hr">
				<small style="margin-left:20px">What can you do? Redirect to thank you page, conversion tracking, pop-up messages, much more.</small>
				<!-- Start of email quote custom js setup -->
				<div class="row col-md-12 scc-cal-settings-row scc-vcenter ms-2">
					<div class="col-xs-12 col-sm-6 col-md-6 col-lg-6 
					<?php
                    if ( $isSCCFreeVersion ) {
                        echo 'use-tooltip';
                    }
?>
					" 
					<?php
if ( $isSCCFreeVersion ) {
    echo 'title="You need purchase Stylish Cost Calculator Premium"';
}
?>
>
						<label id="label_email_qot_btn_click" class="scc-calc-settings-lbl">
							User completes an <b>Email Quote Form</b>
							<i class="material-icons-outlined more-settings-info me-1" data-setting-tooltip-type="user-completes-email-quote-form-js-tt" data-bs-original-title="" title="">
							<span class="scc-icn-wrapper"><?php echo scc_get_kses_extended_ruleset( $this->scc_icons['help-circle'] ); ?></span>
						</i>
						</label>
					</div>
					<div class="col-xs-6 col-sm-6 col-md-6 col-lg-6 
					<?php
if ( $isSCCFreeVersion ) {
    echo 'use-tooltip';
}
?>
					" 
					<?php
if ( $isSCCFreeVersion ) {
    echo 'title="You need purchase Stylish Cost Calculator Premium"';
}
?>
>
						<label class="scc-switch">
							<div class="scc-switch">
								<input 
								<?php
            if ( $isSCCFreeVersion ) {
                echo 'disabled';
            }
?>
								 type="checkbox" data-target="quote-fillup" name="scc_set_customJs_quote" id="scc_set_customJs_quote" class="form-control custom-js-setup">
								<span class="slider round scc-disabled-premium-toggle"></span>
							</div>
						</label>
						<i class="material-icons custom-js-setup 
						<?php
                        if ( $isSCCFreeVersion ) {
                            echo 'disabled';
                        }
?>
						" data-event-type="quote-fillup">
							<span class="scc-icn-wrapper"><?php echo scc_get_kses_extended_ruleset( $this->scc_icons['edit'] ); ?></span>
						</i>
					</div>
				</div>
				<!-- Start Toggle Btn for Detailed List Custom JS -->
				<div class="row col-md-12 scc-cal-settings-row scc-vcenter ms-2">
					<div class="col-xs-12 col-sm-6 col-md-6 col-lg-6 
					<?php
                    if ( $isSCCFreeVersion ) {
                        echo 'use-tooltip';
                    }
?>
					" 
					<?php
if ( $isSCCFreeVersion ) {
    echo 'title="You need purchase Stylish Cost Calculator Premium"';
}
?>
>
						<label id="label_detailed_list_btn_click" class="scc-calc-settings-lbl">
							User clicks the <b>Detailed List</b> button
							<i class="material-icons-outlined more-settings-info me-1" data-setting-tooltip-type="user-clicks-detailed-list-button-js-tt" data-bs-original-title="" title="">
							<span class="scc-icn-wrapper"><?php echo scc_get_kses_extended_ruleset( $this->scc_icons['help-circle'] ); ?></span>
						</i>
						</label>
					</div>
					<div class="col-xs-6 col-sm-6 col-md-6 col-lg-6 
					<?php
if ( $isSCCFreeVersion ) {
    echo 'use-tooltip';
}
?>
					" 
					<?php
if ( $isSCCFreeVersion ) {
    echo 'title="You need purchase Stylish Cost Calculator Premium"';
}
?>
>
						<label class="scc-switch">
							<div class="scc-switch">
								<input 
								<?php
            if ( $isSCCFreeVersion ) {
                echo 'disabled';
            }
?>
								 type="checkbox" data-target="detail-btn" name="scc_set_customJs_detail_view" id="scc_set_customJs_detail_view" class="form-control custom-js-setup">
								<span class="slider round scc-disabled-premium-toggle"></span>
							</div>
						</label>
						<i class="material-icons custom-js-setup 
						<?php
                        if ( $isSCCFreeVersion ) {
                            echo 'disabled';
                        }
?>
						" data-event-type="detail-btn">
							<span class="scc-icn-wrapper"><?php echo scc_get_kses_extended_ruleset( $this->scc_icons['edit'] ); ?></span>
						</i>
					</div>
				</div><!-- End Toggle Btn for Detailed List Custom JS -->
							</div>
						</div>
					</div>
				</div>
			</div><!-- End Toggle Btn for Detailed List Webhook -->
			</div>
		<!--END right Section-->
		</div>
	</div><!-- End Blue Section - User Action Button Events -->
</div>
</div>
      <div class="modal-footer">
        <div role="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</div>
        <button type="button" class="btn btn-primary settings-modal-save-btn" onclick="saveDataFields()">
			<span class="btn-text">Save changes</span>
			<span class="btn-text-alt">Loading</span>
			<i class="fas fa-spinner btn-spinner fa-spin"></i>
		</button>
      </div>
    </div>
  </div>
</div><!-- END OF MODAL -->
<div class="modal fade" id="settingsModal2" tabindex="-1" aria-labelledby="settingsModal2Label" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable scc-modal-fullscreen">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="settingsModal2Label">Wordings</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
	<div id="dfscc_tab_translations_" class="my-4 py-2 px-4 col-xs-12 col-sm-10 col-md-8 col-lg-5 mx-auto" style="margin-left:35px;box-shadow: 1px 4px 10px #ddd;">

		<div class="row m-0">
			<div style="font-size:140%;" class="sccsubtitle scc_email_quote_label"><span style="font-weight:800;">LANGUAGE TRANSLATION</span> &amp; WORD CHANGES</div><br>
		</div>

		<!-- Start Local Translate style="display:none;" id="myDIV4"-->
		<div class="row">
			<div class="col-xs-4 col-md-4 col-sm-4 col-lg-5"><b>Frontend Word</b> </div>
			<div class="col-xs-4 col-md-4 col-sm-4 col-lg-5"><b>New Word / Translation</b> </div>
			<div class="col-md-1 "></div>
		</div>
		<?php
    //var_dump($translateArray[1]->translation);
    foreach ( $translateArray as $value ) {
        ?>
			<div class="row translation-row">
				<div class="col-xs-4 col-xs-4 col-md-4 col-sm-4 col-lg-5"><input type="text" value="<?php echo esc_attr( $value->key ); ?>" disabled=""></div>
				<div class="col-xs-4 col-xs-4 col-md-4 col-sm-4 col-lg-5"><input type="text" value="<?php echo esc_attr( $value->translation ); ?>"></div>
				<div class="col-md-1"><a href="javascript:void(0)" class="translate-del-btn material-icons" onclick="remove_rowTran(this)">close</a></div>
			</div>
		<?php } ?>

		<!-- End Local Translate -->
		<div class="col-lg-12 col-md-12 col-xs-12 scc-add-new-translate-button">
			<a href="javascript:void(0)" onclick="addNewTran(this)" class="crossnadd" style="font-size: 13px;">+ Add New Translate</a>
		</div>
	</div>
	</div>
<div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary settings-modal-save-btn" onclick="saveDataFields()">
			<span class="btn-text">Save changes</span>
			<span class="btn-text-alt">Loading</span>
			<i class="fas fa-spinner btn-spinner fa-spin"></i>
		</button>
      </div>
    </div>
  </div>
</div><!-- END OF MODAL -->
</div>
<div id="Webhook1" style="display:none">
	<h4 style="font-weight: bolder;">Quote Fillup Webhook</h4>
	<div class="form-group">
		<label for="" style="font-weight: normal;">Webhook link</label>
		<input class="from-control" type="text" name="" style="width: 100%;">
	</div>
	<div class="row">
		<div class="btn-group col-md-12 justify-content-end">
			<button class="btn " onclick="sssclose(this)">Cancel</button>
			<button class="btn " onclick="sssclose(this)" style="background-color: #006BB4;color:white">Save</button>
		</div>
	</div>
</div>
<div id="Webhook2" style="display:none">
	<h4 style="font-weight: bolder;">Detailed View Button Webhook</h4>
	<div class="form-group">
		<label for="" style="font-weight: normal;">Webhook link</label>
		<input class="from-control" type="text" name="" style="width: 100%;">
	</div>
	<div class="row">
		<div class="btn-group col-md-12 justify-content-end">
			<button class="btn " onclick="sssclose(this)">Cancel</button>
			<button class="btn " onclick="sssclose(this)" style="background-color: #006BB4;color:white">Save</button>
		</div>
	</div>
</div>
<div class="modal fade" id="quote_lists_modal" tabindex="-1" aria-labelledby="settingsModalLabel" aria-hidden="true">
  <div class="modal-dialog">
	<div class="modal-content">
	  <div class="modal-header">
		<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
	  </div>
	  <div class="modal-body">
	  <h5 class="modal-title" id="settingsModalLabel">Screenshot for the Quotes Management Screen</h5>
	  <div style="padding-bottom:20px;padding-top:20px;text-align:center;font-size:20px;color:#484848;"><span>Below is a screenshot for the Quotes Managment feature that comes with Stylish Cost Calculator premium. <br>View and manage quotes generated by users who have used the "Email Quotes" feature.</span>
	  <br><br>
	  <a class=" btn btn-settings-bar buynow-btn" style="color: #000; padding:20px;" target="_blank" href="https://www.stylishcostcalculator.com">Buy Now</a>
	  <a class=" btn btn-settings-bar buynow-btn" style="color: #000; padding: 20px;" target="_blank" href="https://designful.freshdesk.com/en/support/solutions/articles/48001216303-leads-quotes-management-system-a-complete-guide">Learn More</a></div>
	  <img src="<?php echo esc_url( SCC_URL . 'assets/images/quotes.png' ); ?>" style="max-width:80%;">
	  </div>
	</div>
  </div>
</div>
<script>
	/**
	 * *Show tooltip on hover eye
	 */
	jQuery(document).ready(function() {
		jQuery('.material-icons-outlined.more-settings-info:not(.t-img), .material-icons.v-align-middle').tooltip({
			placement: 'right'
		})
		jQuery('.use-tooltip').tooltip({
			placement: 'right'});
		jQuery('.tooltipadmin-right[title]').tooltip({
			position: {
				my: "center bottom",
				at: "right top"
			}
		})
		//IMG tooltip
		jQuery('.material-icons-outlined.more-settings-info[data-toggle="tooltip"]').tooltip({
			placement: 'right',
			html: true,
			customClass:'tooltip-img'
		});
	})
	


	/**
	 * *Available settings for autoload
	 * !autoload jquery needs to be loaded
	 */
		// prepare the items for search
		let availableTagsCollection = jQuery('label.scc-calc-settings-lbl', '#calc-settings-items').map((i, e) => ({
		labelTitle: jQuery(e).clone().find('i' || ">*").remove().end().text().trim(),
		labelNode: e
	})).get();
	jQuery(document).ready(function($a) {
		if (jQuery.ui) {
			$a("#ssc-search-term").autocomplete({
				source: availableTagsCollection.map(e => e.labelTitle),
				select: function(e, ui) {
					scc_deplacement_script(availableTagsCollection.find(e => e.labelTitle == ui.item.value).labelNode);
					ui.item.value = "";
				},
			});
		} else {
			alert("no cargo jquery-ui no cargado");
		}
	});
	/**
	 * *Moves where the option is located
	 */
	function scc_deplacement_script(htmlNode) {
		jQuery([document.documentElement, document.body]).animate({
			scrollTop: jQuery(htmlNode).offset().top - 50
		}, 1000);
		const shownModalBody = document.querySelector('.modal.show .modal-body');
		const position = htmlNode.offsetTop;
		shownModalBody.scrollTo({
			top: position - 80,
			behavior: 'smooth'
		});
		var originalColor = jQuery(htmlNode).css("color");
		var originalBackground = jQuery(htmlNode).parent().css("background-color")
		var $el = jQuery(htmlNode);
		var $div = jQuery(htmlNode).parent();
		$el.css("color", "white");
		$el.css("font-weight", "bold")
		$div.css("padding", "5px");
		$div.css("border-radius", "5px");
		$div.css("background-color", "#FF9A00");

		setTimeout(function() {
			$el.css("color", originalColor);
			$div.css("padding", "")
			$el.css("font-weight", "normal");
			$div.css("background-color", originalBackground);
		}, 4000);
		//console.log(document.getElementById(labelId));
	};

	/***
	 * *Closes the thickbox shown in webhooks
	 */
	function sssclose() {
		event.preventDefault();
		jQuery("#TB_closeWindowButton").click()
	}

	function changeFieldStyle(element) {
		var value = jQuery(element).val()
		var add_element = jQuery(element).closest('.scc-vcenter').next().hide()
		if (value == "style_2") {
			add_element.show()
		} else {
			add_element.hide()
		}
	}

	/**
	 * *Save Translation in database
	 * !dont delete or change the first 2 rows of classes or divs
	 * $translateArray = '[ { "key": "Total", "lang": "en", "translation": "" }, { "key": "Description", "lang": "en", "translation": "" }, { "key": "Unit Price", "lang": "en", "translation": "" }, { "key": "Quantity", "lang": "en", "translation": "" }, { "key": "Price", "lang": "en", "translation": "" }, { "key": "SEND", "lang": "en", "translation": "" }, { "key": "Total Price", "lang": "en", "translation": "" }, { "key": "Summary", "lang": "en", "translation": "" }, { "key": "Email Quote", "lang": "en", "translation": "" }, { "key": "Email Quote Form", "lang": "en", "translation": "" }, { "key": "Prove that you are not a robot", "lang": "en", "translation": "" }, { "key": "Please wait...", "lang": "en", "translation": "" }, { "key": "Submit", "lang": "en", "translation": "" }, { "key": "Cancel", "lang": "en", "translation": "" }, { "key": "Email Confirmation", "lang": "en", "translation": "" }, { "key": "Thank you! We sent your quote to", "lang": "en", "translation": "" }, { "key": "Remember to check your spam folder.", "lang": "en", "translation": "" }, { "key": "Detailed List", "lang": "en", "translation": "" }, { "key": "Choose an option...", "lang": "en", "translation": "" }, { "key": "Your Name", "lang": "en", "translation": "" }, { "key": "Your Email", "lang": "en", "translation": "" }, { "key": "Your Phone", "lang": "en", "translation": "" }, { "key": "Your Phone (Optional)", "lang": "en", "translation": "" }, { "key": "Coupon Code", "lang": "en", "translation": "" }, { "key": "Please choose an option", "lang": "en", "translation": "" }, { "key": "Enter your coupon code", "lang": "en", "translation": "" }, { "key": "This code is not valid", "lang": "en", "translation": "" }, { "key": "Discount percentage", "lang": "en", "translation": "" }, { "key": "Your discount has been applied correctly", "lang": "en", "translation": "" }, { "key": "Your discount has not been applied because the total price has to be between", "lang": "en", "translation": "" }, { "key": "The total price must be a minimum of", "lang": "en", "translation": "" } ]';
	 * 
	 */

	function getTranslationData() {
		var arrayTr = []
		var con = jQuery("#dfscc_tab_translations_").find(".row").slice(2).each(function(e) {
			var c = {}
			var a
			var b
			var s = jQuery(this).find("input").each(function(e) {
				var empt
				if (e == 0) {
					var i = jQuery(this).val()
					a = i
				}
				if (e == 1) {
					var i2 = jQuery(this).val()
					b = i2
				}
			})
			if (a != "") {
				c["key"] = a
				c["lang"] = "en"
				c["translation"] = b
				arrayTr.push(c)
			}
			//validate if first is not empty to add
		})
		return JSON.stringify(arrayTr)
	}


	/**
	 * *Removes one row from dom
	 */
	function remove_rowTran(element) {
		jQuery(element).parent().parent().remove()
	}

	/**
	 * *add row to dom
	 */
	function addNewTran(element) {
		jQuery("#dfscc_tab_translations_ div:last").before(render_row())

		function render_row() {
			var el = '<div class="row translation-row">'
			el += '    <div class="col-xs-4 col-md-4 col-sm-4 col-lg-5"><input type="text" value=""></div>'
			el += '    <div class="col-xs-4 col-md-4 col-sm-4 col-lg-5"><input type="text" value=""></div>'
			el += '    <div class="col-md-1"><a href="javascript:void(0)" class="translate-del-btn material-icons" onclick="remove_rowTran(this)">cancel</a></div>'
			el += '</div>'
			return el
		}
	}

	// save fromname
	function saveDataFields() {
		showLoadingChanges()
		var strTrans = getTranslationData()
		// idform
		var id_form = jQuery("#id_form_input").val();
		// name of form
		var formname = jQuery("body").find("#costcalculatorname").val();

		var activeModal = document.querySelector('.modal.show');
		if ( ! activeModal ) {
			activeModal = document.querySelector('#settingsModal1');
		}

		// CALCULATOR SETTINGS
		var elementSkin = jQuery('select[name="form_field_style"]').val()
		var addContainer = jQuery('input[name="toggle_boxshadow_style2"]').is(":checked")
		// woocommerce
		// user action buttons
		var buttonStyle = jQuery('select[name="scc_user_action_btn_style"]').val()
		var turnviewdetails = !jQuery('input[name="scc_detailed_list"]').is(":checked")

		
		// total price settings
		var barstyle = jQuery('select[name="scc_total_price_style_view"]').val()
		var turnofffloating = jQuery('input[name="turn_on_total_price_float"]').is(":checked")
		// detailed list settings
		var removeTitle = !jQuery('input[name="scc_remove_detailed_list_title"]').is(":checked")
		var turnoffUnit = !jQuery('input[name="scc_no_unit_col"]').is(":checked")
		var turnoffQty = !jQuery('input[name="scc_no_qty_col"]').is(":checked")

		var turnoffSave = !jQuery('input[name="scc_save_icon"]').is(":checked")
		var turnoffTax = !jQuery('input[name="turn_off_tax"]').is(":checked")
		// currency & tax
		var symbol = jQuery('select[name="scc_currency_style"]').val()
		var removeCurrency = !jQuery('input[name="scc_remove_currency_label"]').is(":checked")
		// webhook
		var userCompletes = jQuery('input[name="scc_set_webhook_quote"]').is(":checked")
		var userClicksf = jQuery('input[name="scc_set_webhook_detail_view"]').is(":checked")
		var calcWrapperMaxWidth = jQuery('input[name="scc_wrapper_max_width"]').val()
		var turnoffQty = !jQuery('input[name="scc_no_qty_col"]').is(":checked")


		var json = {
			formname,
			elementSkin,
			addContainer,
			buttonStyle,
			turnviewdetails,
			barstyle,
			turnofffloating,
			removeTitle,
			turnoffUnit,
			turnoffQty,
			turnoffSave,
			turnoffTax,
			symbol,
			removeCurrency,
			userCompletes,
			userClicksf,
			calcWrapperMaxWidth
		}
		// Change form table settings names

		jQuery.ajax({
			url: ajaxurl,
			type:'POST',
			data: {
				action: 'sccSaveForm',
				id_form: id_form,
				data: json,
				translations: strTrans,
				nonce: pageEditCalculator.nonce
			},
			beforeSend: function() {
				if ( activeModal ) {
					activeModal.querySelector('.settings-modal-save-btn').setAttribute('disabled', true);
				}
			},
			success: function(data) {
				var datajson = JSON.parse(data)
				if (datajson.passed == true) {
					showSweet(true, "The changes have been saved.")
					disableInputT()
					if ( activeModal ) {
						const openedModalInstance = bootstrap.Modal.getInstance(activeModal);
						// Hide the modal
						openedModalInstance?.hide();
					}
				} else {
					showSweet(false, datajson.msj)
				}
			}
		}).always(function() {
			activeModal.querySelector('.settings-modal-save-btn').removeAttribute('disabled');
		})
		
		function disableInputT(){
			jQuery('#dfscc_tab_translations_ .row > div > input').each(function(i,e){
				if(Number.isInteger(i/2)){
					jQuery(this).attr('disabled','true')
				}
			})
			
			
		}
	}

	// color picker 
	jQuery(document).ready(function() {
		const tabfont = jQuery("#dfscc_tab_font_settings_");
		const tabcalculator = jQuery("#dfscc_tab_calculator_");
		const tabtrabs = jQuery("#dfscc_tab_translations_");
		const tabembed = jQuery("#df_scc_tabembed_");

		const b_font = jQuery("#btn_dfscc_tab_font_settings_");
		const b_calc = jQuery("#btn_dfscc_tab_calculator_");
		const b_tans = jQuery("#btn_dfscc_tab_translations_");
		const b_embe = jQuery("#btn_df_scc_tabembed_");

		function handleSettingsTabs(target, srcBtn) {
			let tabs = [tabembed, tabfont, tabcalculator, tabtrabs];
			let btns = [b_font, b_calc, b_tans, b_embe];
			let targetIndex = tabs.findIndex(e => e == target);
			let btnIndex = btns.findIndex(e => e == srcBtn);
			if (targetIndex >= 0 && btnIndex >= 0) {
				delete btns[btnIndex];
				delete tabs[targetIndex];
			}
			target.slideToggle({
				complete: function() {
					if (target.attr('id') == 'df_scc_tabembed_') return;
					if (target.is(':visible')) {
						srcBtn.find('.material-icons-outlined').text('expand_less')
					} else {
						srcBtn.find('.material-icons-outlined').text('expand_more')
					}
				}
			});
			tabs.forEach(e => {
				e.hide();
			});
			btns.forEach(e => e.find('.material-icons-outlined').text('expand_more'))
		}
		<?php if ( ! $isSCCFreeVersion ) { ?>
		jQuery('.color-picker').wpColorPicker();
		<?php } ?>
	});
	// fonts
	var gFonts = JSON.parse(<?php echo json_encode( $scc_googlefonts_var->gf_get_local_fonts() ); ?>);

	jQuery('#titlescc_fonttype').on('change', (event) => {
		let selectedFont = jQuery(event.currentTarget).val();
		let selectedFontVariant = gFonts.items[selectedFont].variants;
		let variantSelectionElement = document.getElementById('titlescc_fonttype_variant');
		variantSelectionElement.innerHTML = '';
		for (var i = 0; i < selectedFontVariant.length; i++) {
			var opt = selectedFontVariant[i];
			var el = document.createElement("option");
			el.textContent = opt;
			el.value = opt;
			variantSelectionElement.appendChild(el);
		}
		jQuery(variantSelectionElement).val('regular');
	}).trigger('change');
	jQuery('#scc_fonttype').change((event) => {
		let selectedFont = jQuery(event.currentTarget).val();
		let selectedFontVariant = gFonts.items[selectedFont].variants;
		let variantSelectionElement = document.getElementById('scc_fonttype_variant');
		variantSelectionElement.innerHTML = '';
		for (var i = 0; i < selectedFontVariant.length; i++) {
			var opt = selectedFontVariant[i];
			var el = document.createElement("option");
			el.textContent = opt;
			el.value = opt;
			variantSelectionElement.appendChild(el);
		}
		jQuery(variantSelectionElement).val('regular');
	}).trigger('change');
		jQuery('#scc_fonttype_variant').val('<?php echo esc_attr( $f1->fontWeight ); ?>');
		jQuery('#titlescc_fonttype_variant').val('<?php echo esc_attr( $f1->titleFontWeight ); ?>');
</script>
<style>
	#premium-icon {
		vertical-align: middle;
	}
	a.buynow-btn:hover,
	a.buynow-btn:focus {
		color: #fff !important;
	}
	#quote_lists_modal {
		margin-top: 30px;
		
	}
	#quote_lists_modal .modal-body h5{
		padding-top: 15px;
		text-align: center;
		font-size: 40px;
		font-weight: bold;
		color: #314af3;
	}
	#quote_lists_modal .modal-dialog{
		
		margin-left: 173px;
		max-width: 100%;
		margin-right: 1em;
	}

	#quote_lists_modal .modal-content{

	}

	#quote_lists_modal .modal-header{
		border-bottom: unset;
	}

	@media screen and (max-width:950px){
		#quote_lists_modal .modal-dialog{
		margin-left: 50px;
		}
	}

	@media screen and (max-width:782px) {
		#quote_lists_modal .modal-dialog{
		margin-left: 20px;
		}
	}

	.closemodal {
		float: right;
		border: 1px solid;
		border-radius: 5px;
		cursor: pointer;
		width: 20px;
		height: 20px;
		text-align: center;
	}
	#dfscc_tab_translations_ .row {
		padding-top: 10px;
	}

	#dfscc_tab_translations_ .row input {
		width: 100%;
	}

	#dfscc_tab_translations_ .row a {
		font-size: 15px;

	}

	#TB_window {
		border-radius: 8px !important;
	}

	#TB_title {
		background: none !important;
		border-bottom: none !important;
	}
</style>
