<?php

class Stylish_Cost_Calculator_Edit_Page {

    private $calc_id;
    protected $is_from_ajax;
    protected $df_scc_form_currency;
    protected $is_woocommerce_enabled;
    public $woo_commerce_products;
	private $scc_icons;

    public function __construct( $calc_id = false, $is_from_ajax = false, $is_woocommerce_enabled = false ) {
        $this->calc_id                = $calc_id;
        $this->is_from_ajax           = $is_from_ajax;
        $this->df_scc_form_currency   = get_option( 'df_scc_currency', 'USD' );
        $this->is_woocommerce_enabled = false;
		$this->scc_icons = require SCC_DIR . '/assets/scc_icons/icon_rsrc.php';
    }

    public function renderAdvancedOptions( $el ) { 
        $defaults = [
            'orden'              => 0,
            'titleElement'       => 'Title',
            'type'               => '',
            'subsection_id'      => 0,
            'value1'             => null,
            'value4'             => null,
            'value2'             => '',
            'value3'             => '0',
            'mandatory'          => 0,
            'showPriceHint'      => 0,
            'titleColumnDesktop' => '4',
            'titleColumnMobile'  => '12',
            'displayFrontend'    => 1,
            'displayDetailList'  => isset( $el->type ) && $el->type === 'texthtml' ? 3 : 1,
            'showTitlePdf'       => 0,
            'showInputBoxSlider' => 0,
        ];
        // value3 will represent if symbol before the number be shown. By default, it should be of value '1'
        // here, he handle cases where value3 is empty
        if ( strlen( $el->value3 ) == 0 && ( $el->type == 'custom math' ) ) {
            $el->value3 = '1';
        }
        $el = (object) wp_parse_args( $el, $defaults );
        ob_start();
        ?>
		<div class="scc-content" style="display: none;">
			<div class="scc-transition px-0 advanced-option-wrapper">
				<?php if ( $el->type == 'custom math' ) { ?>
						<p>
							<label class="scc-accordion_switch_button">
								<input onchange="changeDisplayFrontend(this)" class="scc_mandatory_dropdown" type="checkbox" 
								<?php
                                if ( $el->displayFrontend == '1' ) {
                                    echo 'checked';
                                }
				    ?>
								>
								<span class="scc-accordion_toggle_button round"></span>
							</label>
							<span>Display on Frontend Form</span>
							</p>
						<p>
							<label class="scc-accordion_switch_button">
								<input onchange="changeDisplayDetail(this)" class="scc_mandatory_dropdown" type="checkbox" 
								<?php
				    if ( $el->displayDetailList == '1' ) {
				        echo 'checked';
				    }
				    ?>
								>
								<span class="scc-accordion_toggle_button round"></span>
							</label>
							<span>Display on Detailed List</span>
				</p>
						<p>
							<label class="scc-accordion_switch_button">
								<input onchange="changeCalculationSymbol(this)" class="scc_mandatory_dropdown" type="checkbox" 
								<?php
				    if ( $el->value3 == '1' ) {
				        echo 'checked';
				    }
				    ?>
								>
								<span class="scc-accordion_toggle_button round"></span>
							</label>
							<span>Show Calculation Symbol</span>
				</p>
					</div>
				</div>
					<?php
				        $html = ob_get_clean();

				    return $html;
				}
        ?>
					<?php if ( $el->value1 != 8 ) { ?>
					<!-- Show only for image button element -->
					<div class="image-button-border" style="
						<?php
                if ( $el->value1 != 8 ) {
                    echo 'display:none';
                }
					    ?>
					">
						<label class="scc-accordion_switch_button">
							<input onchange="changeValue4(this)" class="scc_show_border" name="scc_mandatory_dropdown" type="checkbox" 
							<?php
					        if ( $el->value4 == 'true' ) {
					            echo 'checked';
					        }
					    ?>
							>
							<span class="scc-accordion_toggle_button round"></span>
						</label>
						<span>Image Border</span>
					</div>
					<!-- Show only for image button element -->
					<?php } ?>
					<?php if ( $el->value1 == 8 ) { ?>
						<div class="image-button-border">
							<label class="scc-accordion_switch_button">
								<input onchange="changeValue4(this)" class="scc_show_border" name="scc_mandatory_dropdown" type="checkbox" 
								<?php
					        if ( $el->value4 == 'true' ) {
					            echo 'checked';
					        }
					    ?>
								>
								<span class="scc-accordion_toggle_button round"></span>
							</label>
							<span>Image Border</span>
					</div>
						<div class="buttonsqtn">
							<label class="scc-accordion_switch_button">
								<input onchange="changeValue3__(this)" class="scc_mandatory_dropdown" name="scc_mandatory_dropdown" type="checkbox" 
								<?php
					    if ( $el->value3 == 'true' ) {
					        echo 'checked';
					    }
					    ?>
								>
								<span class="scc-accordion_toggle_button round"></span>
							</label><span><span class="scc-adv-opt-lbl"> Show buttons to add quantity</span></span>
					</div>
					<?php } ?>
					<?php if ( $el->type !== 'texthtml' ) { ?>
					<p class="scc-advanced-option-cont">
						<label class="scc-accordion_switch_button">
							<input onchange="changeMandatoryElement(this)" class="scc_mandatory_dropdown" name="scc_mandatory_dropdown" type="checkbox" 
							<?php
					        if ( $el->mandatory == '1' ) {
					            echo 'checked';
					        }
					    ?>
							>
							<span class="scc-accordion_toggle_button round"></span>
						</label>
						<span>
							<span class="scc-adv-opt-lbl" >Mandatory</span>
							<i
							data-element-tooltip-type="mandatory-elements-tt"
							class="material-icons-outlined more-settings-info"
							style="margin-right:5px">
							<span class="scc-icn-wrapper"><?php echo scc_get_kses_extended_ruleset( $this->scc_icons['help-circle'] ); ?></span>
						</i>
						</span>
					</p>
					
						<?php
					}

                    if ( in_array( $el->type, [ 'quantity box' ] ) ) {
                        ?>
						<div class="scc-accordion-tooltip px-0" style="width: 100%; text-align:left;">
							<div class="row gx-2">
								<div class="col-md-6 input-field">
									<input onchange="changeValue3(this)" onkeyup="changeValue3(this)" id="<?php echo esc_attr( 'scc_title_column_dskp-' . $el->id ); ?>" class="scc_title_column_dskp" name="scc_title_column_dskp" type="number" value="<?php echo intval( $el->value3 ); ?>">
									<label <?php echo "class='active'"; ?> for="<?php echo esc_attr( 'scc_title_column_dskp-' . $el->id ); ?>">Max Value</label>
								</div>
								<div class="col-md-6 input-field">
									<input onchange="changeValue4(this)" onkeyup="changeValue4(this)" id="<?php echo esc_attr( 'scc_title_column_mobl-' . $el->id ); ?>" class="scc_title_column_mobl" name="scc_title_column_mobl" type="number" value="<?php echo intval( $el->value4 ); ?>">
									<label <?php echo "class='active'"; ?> for="<?php echo esc_attr( 'scc_title_column_mobl-' . $el->id ); ?>">Min Value</label>
								</div>
							</div>
						</div>
						<?php
                    }
        ?>
				<?php if ( in_array( $el->type, [ 'checkbox', 'slider' ] ) ) { ?>
				<p class="scc-advanced-option-cont">
					<label class="scc-accordion_switch_button">
						<input onchange="changeShowPriceHintElement(this)" class="scc_mandatory_dropdown" name="scc_mandatory_dropdown" type="checkbox" disabled>
						<span class="scc-accordion_toggle_button round"></span>
					</label>
					<span><span class="scc-adv-opt-lbl">Show Price Hint</span>
					<i
						data-element-tooltip-type="enable-price-hint-bubble-tt"
						class="material-icons-outlined more-settings-info"
						style="margin-right:5px">
						<span class="scc-icn-wrapper"><?php echo scc_get_kses_extended_ruleset( $this->scc_icons['help-circle'] ); ?></span>
					</i>
					</span>
				<p>
				<?php } ?>
				<?php if ( in_array( $el->type, [ 'quantity box' ] ) ) { ?>
					<p class="scc-advanced-option-cont">
						<label class="scc-accordion_switch_button">
							<input onchange="sccBackendUtils.changeNumberInputCommaFormat(this)" class="scc_mandatory_dropdown"
								name="scc_mandatory_dropdown" type="checkbox" <?php
                            if ( isset( $el->value5 ) && 2 == $el->value5 ) {
                                echo 'checked';
                            }
				    ?>>
							<span class="scc-accordion_toggle_button round"></span>
						</label>
						<span>
							<span class="scc-adv-opt-lbl use-tooltip">Enable commas </span>		
							<i
								data-element-tooltip-type="qnt-input-comma-number"
								class="material-icons-outlined more-settings-info"
								style="margin-right:5px">
								<span class="scc-icn-wrapper"><?php echo scc_get_kses_extended_ruleset( $this->scc_icons['help-circle'] ); ?></span>
							</i>
						</span>
					</p>
				<?php } ?>
				<?php if ( in_array( $el->type, [ 'slider', 'texthtml', 'date' ] ) ) { ?>
					 
				<p class="scc-advanced-option-cont">
					<label class="scc-accordion_switch_button">
						<input onchange="toggleSliderDisplayinDetail(this)" name="scc_hide_slider_on_detailed_view" type="checkbox" 
						<?php
				        if ( $el->displayDetailList != '3' ) {
				            echo 'checked';
				        }
				    ?>
						>
						<span class="scc-accordion_toggle_button round"></span>
					</label>
					<span><span class="scc-adv-opt-lbl">Show on Detailed List</span>
				    <i
						data-element-tooltip-type="display-on-detailed-list-pdf-tt"
						class="material-icons-outlined more-settings-info"
						style="margin-right:5px">
						<span class="scc-icn-wrapper"><?php echo scc_get_kses_extended_ruleset( $this->scc_icons['help-circle'] ); ?></span>
					</i>

					</span>
				</p>
				<?php } ?>
				<?php if ( in_array( $el->type, [ 'slider' ] ) ) { ?>
				<p class="scc-advanced-option-cont">
					<label class="scc-accordion_switch_button">
						<input onchange="toggleSliderInputBoxShowHide(this)" name="scc_show_inputbox_slider" type="checkbox" 
						<?php
				    if ( $el->showInputBoxSlider != '0' ) {
				        echo 'checked';
				    }
				    ?>
						>
						<span class="scc-accordion_toggle_button round"></span>
					</label>
					<span><span class="scc-adv-opt-lbl">Add Input Box To Slider</span>
					<i
						data-element-tooltip-type="append-quantity-input-box-tt"
						class="material-icons-outlined more-settings-info"
						style="margin-right:5px">
						<span class="scc-icn-wrapper"><?php echo scc_get_kses_extended_ruleset( $this->scc_icons['help-circle'] ); ?></span>
					</i>
				    </span>
				</p>
				<p class="row gx-2 mt-2 scc-advanced-option-cont">
					<div class="col-md-6 input-field">
						<input id="<?php echo esc_attr( 'slider-starting-value-' . $el->id ); ?>" type="number" onchange="changeValue3(this, true);" onkeyup="changeValue3(this)" value="<?php echo esc_attr( $el->value3 ); ?>" style="margin-bottom: 0px;">
						<label for="<?php echo esc_attr( 'slider-starting-value-' . $el->id ); ?>" class="active form-label fw-bold">Starting value</label>
					</div>
					<div class="col-md-6 input-field">
						<input id="<?php echo esc_attr( 'slider-steps-value-' . $el->id ); ?>" type="number" onchange="changeValue2(this)" onkeyup="changeValue2(this)" value="<?php echo esc_attr( $el->value2 ); ?>" style="margin-bottom: 0px;">
						<label for="<?php echo esc_attr( 'slider-steps-value-' . $el->id ); ?>" class="active form-label fw-bold">Slider steps</label>
					</div>
				</p>
				<p class="d-none slider-start-value-warning" style="color: red;">The starting value cannot be smaller than the base from value.</p>
			   <?php } ?>
				<?php if ( in_array( $el->type, [ 'math', 'Dropdown Menu' ] ) ) { ?>
				<p class="scc-advanced-option-cont">
					<label class="scc-accordion_switch_button">
						<input onchange="changeShowTitlePdf(this)" class="scc_mandatory_dropdown" name="scc_mandatory_dropdown" type="checkbox" 
						<?php
				    if ( $el->showTitlePdf == '1' ) {
				        echo 'checked';
				    }
				    ?>
						>
						<span class="scc-accordion_toggle_button round"></span>
					</label>
					<span class="scc-adv-opt-lbl">Show Title on Detailed List 
				    <i
						data-element-tooltip-type="show-title-on-detailed-list-tt"
						class="material-icons-outlined more-settings-info"
						style="margin-right:5px">
						<span class="scc-icn-wrapper"><?php echo scc_get_kses_extended_ruleset( $this->scc_icons['help-circle'] ); ?></span>
					</i>
					</span>
				</p>
					<?php
				}

					if ( in_array( $el->type, [ 'date' ] ) ) {
					if ( $el->type == 'date') {
						$value6_default  = 'date-picker-element';
					}
                $value6_default  = DF_SCC_ELEMENT_DEFAULT_VALUES[$value6_default]['advanced']['value6'];
                $scc_date_config = wp_parse_args(
                    json_decode( wp_unslash( !empty( $el->value6 ) ? $el->value6 : '' ), true ),
                    $value6_default
                );
                $hours_12           = range( 1, 12 );
                $hours_24           = range( 0, 23 );
                $numbers_0_to_55    = range( 0, 55, 5 );
                $show_time_options  = isset( $scc_date_config['enable_time_picker'] ) ? boolval( $scc_date_config['enable_time_picker'] ) : false;
                $show_12h_options   = boolval( $scc_date_config['limit_hours'] ) && $scc_date_config['time_format'] === '12h' && $show_time_options;
                $show_24h_options   = boolval( $scc_date_config['limit_hours'] ) && $scc_date_config['time_format'] === '24h' && $show_time_options;
                ?>
				<p>
					<label class="scc-accordion_switch_button">
						<input
							data-element-id="<?php echo intval( $el->id ); ?>"
							data-value6-key="disable_past_days"
							<?php echo boolval( $scc_date_config['disable_past_days'] ) ? 'checked' : ''; ?>
							type="checkbox">
						<span class="scc-accordion_toggle_button round"></span>
					</label>
					<span class="scc-adv-opt-lbl">Disable Past Days
						<i 
							class="material-icons-outlined more-settings-info"
							data-element-tooltip-type="display-past-days-tt"
							style="margin-right:5px">
							<span class="scc-icn-wrapper"><?php echo scc_get_kses_extended_ruleset( $this->scc_icons['help-circle'] ); ?></span>
						</i>
					</span>
				</p>
				<p>
					<label class="scc-accordion_switch_button">
						<input
							data-element-id="<?php echo intval( $el->id ); ?>"
							data-value6-key="disable_today_date"
							<?php echo boolval( $scc_date_config['disable_today_date'] ) ? 'checked' : ''; ?>
							type="checkbox"
							class="scc-disable-today-date"
							>
						<span class="scc-accordion_toggle_button round"></span>
					</label>
					<span class="scc-adv-opt-lbl">Disable Today's Date
						<i 
							class="material-icons-outlined more-settings-info"
							data-element-tooltip-type="date-picker-disable-today-date-tt"
							style="margin-right:5px">
							<span class="scc-icn-wrapper"><?php echo scc_get_kses_extended_ruleset( $this->scc_icons['help-circle'] ); ?></span>
						</i>
					</span>
				</p>
				<p>
					<label class="scc-accordion_switch_button">
						<input
							data-element-id="<?php echo intval( $el->id ); ?>"
							<?php echo boolval( $scc_date_config['enable_limit_days'] ) ? 'checked' : ''; ?>
							data-value6-key="enable_limit_days"
							type="checkbox">
						<span class="scc-accordion_toggle_button round"></span>
					</label>
					<span class="scc-adv-opt-lbl"
						data-bs-original-title="">Limit Days
						<i
							data-element-tooltip-type="limit-days-tt"
							class="material-icons-outlined more-settings-info"
							style="margin-right:5px">
							<span class="scc-icn-wrapper"><?php echo scc_get_kses_extended_ruleset( $this->scc_icons['help-circle'] ); ?></span>
						</i>
					</span>
				</p>
				<div class="scc-days-wrapper p-3 <?php echo boolval( $scc_date_config['enable_limit_days'] ) ? '' : 'd-none'; ?>">
					<p class="scc-days-wrapper-lead">Choose days to exclude</p>
					<div class="days-select" data-value6-key="limit_days" data-value6-type="array-checkboxes">
						<?php foreach ( [ 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun' ] as $value ) {
						    $is_checked = in_array( $value, $scc_date_config['limit_days'] ) ? 'checked' : '';
						    echo "<label class='day'><input data-element-id=" . intval( $el->id ) . ' ' . $is_checked . " type='checkbox' value='$value'><span>$value</span></label>";
						} ?>
					</div>
				</div>
				<hr>
			<div class="scc-accordion-tooltip" style="text-align:left; width:100%">
				<div class="row gx-2 scc-edit-input-option-wrapper">
					<div class="col-md-4" style="margin-bottom: 1rem">
						<label class="use-tooltip fw-bold" title="Select the minimum date in which it is allowed to choose dates"
						style="font-size:14px; transform:scale(0.8)">Min date </label>
						<input placeholder="yyyy-mm-dd" type="text" data-date-structure data-picker-field="min-date" class="input_pad inputoption_2 scc-datepicker-editor" 
						style="text-align:center;height:35px; min-width:158px;" placeholder=""
						value="<?php echo esc_attr( $scc_date_config['min_date'] ); ?>" <?php echo esc_attr( $scc_date_config['min_date'] === 'today' ? 'data-today-enabled=true' : '' ); ?>>
					</div>
					<div class="col-md-4" style="margin-bottom: 1rem">
						<label class="use-tooltip fw-bold" title="Select the maximum date in which it is allowed to choose dates"
						style="font-size:14px; transform:scale(0.8)">Max date </label>
						<input placeholder="yyyy-mm-dd" type="text" data-date-structure data-picker-field="max-date" class="input_pad inputoption_2 scc-datepicker-editor" 
						style="text-align:center;height:35px; min-width:158px;" placeholder=""
						value="<?php echo esc_attr( $scc_date_config['max_date'] ); ?>" <?php echo esc_attr( $scc_date_config['max_date'] === 'today' ? 'data-today-enabled=true' : '' ); ?>>
					</div>
					<div class="col-md-4" style="margin-bottom: 1rem">
					<label class="use-tooltip fw-bold" title="Choose the default date for the date picker"
						style="font-size:14px; transform:scale(0.8)">Default date </label>
						<input placeholder="yyyy-mm-dd" type="text" data-picker-field="default-date" class="input_pad inputoption_2 scc-datepicker-editor" 
						style="text-align:center;height:35px; min-width:158px;" placeholder=""
						value="<?php echo esc_attr( $el->value2 ); ?>" <?php echo esc_attr( $el->value2 === 'today' ? 'data-today-enabled=true' : '' ); ?>>
					</div>
					
				</div>
				<div class="row gx-2 scc-edit-input-option-wrapper">
					<div class="col-md-12" style="margin-bottom: 1rem">
						<label class=" use-tooltip fw-bold" title="Manually choose the dates you want to disable so users can't select them"
						style="font-size:14px; transform:scale(0.8)">Disabled dates </label>
						<input placeholder="yyyy-mm-dd" type="text" data-picker-field="disabled-date" class="input_pad inputoption_2 scc-datepicker-editor" 
						style="text-align:center;height:35px; min-width:158px;" placeholder=""
						value="<?php echo esc_attr( $scc_date_config['disabled_date'] ); ?>">
					</div>
				</div>
			</div>
			<hr>
			<p>
					<label class="scc-accordion_switch_button">
						<input
							data-element-id="<?php echo intval( $el->id ); ?>"
							<?php echo $show_time_options ? 'checked' : ''; ?>
							data-value6-key="enable_time_picker"
							type="checkbox">
						<span class="scc-accordion_toggle_button round"></span>
					</label>
					<span class="scc-adv-opt-lbl"
						data-bs-original-title="">Enable Time Picker
						<i 
							data-element-tooltip-type="enable-time-picker-tt"
							class="material-icons-outlined more-settings-info"
							style="margin-right:5px">
							<span class="scc-icn-wrapper"><?php echo scc_get_kses_extended_ruleset( $this->scc_icons['help-circle'] ); ?></span>
						</i>
					</span>
				</p>
				<p class="limit-hours <?php echo $show_time_options ? '' : 'd-none'; ?>">
					<label class="scc-accordion_switch_button">
						<input
							data-element-id="<?php echo intval( $el->id ); ?>"
							<?php echo boolval( $scc_date_config['limit_hours'] ) ? 'checked' : ''; ?>
							data-value6-key="limit_hours"
							type="checkbox">
						<span class="scc-accordion_toggle_button round"></span>
					</label>
					<span class="scc-adv-opt-lbl"
						data-bs-original-title="">Limit Hours
						<i
							class="material-icons-outlined more-settings-info"
							data-element-tooltip-type="limit-hours-tt"
							style="margin-right:5px">
							<span class="scc-icn-wrapper"><?php echo scc_get_kses_extended_ruleset( $this->scc_icons['help-circle'] ); ?></span>
						</i>
					</span>
				</p>
				<div class="hours-wrapper-12 start <?php echo $show_12h_options ? '' : 'd-none'; ?> ">
					<div class="row gx-2 scc-edit-input-option-wrapper hours-select">
						<div class="col-md-4">
							<label class="use-tooltip fw-bold" style="font-size:14px; transform:scale(0.8)" title="Select the minimum time in which it is allowed to choose from">Start time</label>
							<select data-value6-key="limit_hours_start_12h_hour">
								<?php foreach ( $hours_12 as $value ) { ?>
									<option <?php selected( $scc_date_config['limit_hours_start_12h_hour'], $value ); ?>><?php echo $value; ?></option>
								<?php } ?>
							</select>
						</div>
						<div class="col-md-4">
							<label class="use-tooltip fw-bold fade" style="font-size:14px; transform:scale(0.8)">1</label>
							<select data-value6-key="limit_hours_start_12h_minutes">
								<?php foreach ( $numbers_0_to_55 as $value ) { ?>
									<option <?php selected( $scc_date_config['limit_hours_start_12h_minutes'], $value ); ?>><?php echo $value; ?></option>
								<?php } ?>
							</select>
						</div>
						<div class="col-md-4">
							<label class="use-tooltip fw-bold fade" style="font-size:14px; transform:scale(0.8)">1</label>
							<select data-value6-key="limit_hours_start_am_pm">
								<option <?php selected( $scc_date_config['limit_hours_start_am_pm'], 'AM' ); ?> value="AM">AM</option>
								<option <?php selected( $scc_date_config['limit_hours_start_am_pm'], 'PM' ); ?> value="PM">PM</option>
							</select>
						</div>
					</div>
				</div>
				<div class="hours-wrapper-12 end <?php echo $show_12h_options ? '' : 'd-none'; ?>">
					<div class="row gx-2 scc-edit-input-option-wrapper hours-select">
						<div class="col-md-4">
							<label class="use-tooltip fw-bold" style="font-size:14px; transform:scale(0.8)" title="Select the minimum time in which it is allowed to choose from">End time</label>
							<select data-value6-key="limit_hours_end_12h_hour">
								<?php foreach ( $hours_12 as $value ) { ?>
									<option <?php selected( $scc_date_config['limit_hours_end_12h_hour'], $value ); ?>><?php echo $value; ?></option>
								<?php } ?>
							</select>
						</div>
						<div class="col-md-4">
							<label class="use-tooltip fw-bold fade" style="font-size:14px; transform:scale(0.8)">1</label>
							<select data-value6-key="limit_hours_end_12h_minutes">
								<?php foreach ( $numbers_0_to_55 as $value ) { ?>
									<option <?php selected( $scc_date_config['limit_hours_end_12h_minutes'], $value ); ?>><?php echo $value; ?></option>
								<?php } ?>
							</select>
						</div>
						<div class="col-md-4">
							<label class="use-tooltip fw-bold fade" style="font-size:14px; transform:scale(0.8)">1</label>
							<select data-value6-key="limit_hours_end_am_pm">
								<option <?php selected( $scc_date_config['limit_hours_end_am_pm'], 'AM' ); ?> value="AM">AM</option>
								<option <?php selected( $scc_date_config['limit_hours_end_am_pm'], 'PM' ); ?> value="PM">PM</option>
							</select>
						</div>
					</div>
				</div>
				<div class="hours-wrapper-24 start <?php echo $show_24h_options ? '' : 'd-none'; ?>">
					<div class="row gx-2 scc-edit-input-option-wrapper hours-select">
						<div class="col-md-4">
							<label class="use-tooltip fw-bold" style="font-size:14px; transform:scale(0.8)" title="Select the minimum time in which it is allowed to choose from">Start time</label>
							<select data-value6-key="limit_hours_start_24h_hour">
								<?php foreach ( $hours_24 as $value ) { ?>
									<option <?php selected( $scc_date_config['limit_hours_start_24h_hour'], $value ); ?>><?php echo $value; ?></option>
								<?php } ?>
							</select>
						</div>
						<div class="col-md-4">
							<label class="use-tooltip fw-bold fade" style="font-size:14px; transform:scale(0.8)">1</label>
							<select data-value6-key="limit_hours_start_24h_minutes">
								<?php foreach ( $numbers_0_to_55 as $value ) { ?>
									<option <?php selected( $scc_date_config['limit_hours_start_24h_minutes'], $value ); ?>><?php echo $value; ?></option>
								<?php } ?>
							</select>
						</div>
					</div>
				</div>
				<div class="hours-wrapper-24 end <?php echo $show_24h_options ? '' : 'd-none'; ?>">
					<div class="row gx-2 scc-edit-input-option-wrapper hours-select">
						<div class="col-md-4">
							<label class="use-tooltip fw-bold" style="font-size:14px; transform:scale(0.8)" title="Select the minimum time in which it is allowed to choose from">End time</label>
							<select data-value6-key="limit_hours_end_24h_hour">
								<?php foreach ( $hours_24 as $value ) { ?>
									<option <?php selected( $scc_date_config['limit_hours_end_24h_hour'], $value ); ?>><?php echo $value; ?></option>
								<?php } ?>
							</select>
						</div>
						<div class="col-md-4">
							<label class="use-tooltip fw-bold fade" style="font-size:14px; transform:scale(0.8)">1</label>
							<select data-value6-key="limit_hours_end_24h_minutes">
								<?php foreach ( $numbers_0_to_55 as $value ) { ?>
									<option <?php selected( $scc_date_config['limit_hours_end_24h_minutes'], $value ); ?>><?php echo $value; ?></option>
								<?php } ?>
							</select>
						</div>
					</div>
				</div>
				<div class="row gx-2 scc-edit-input-option-wrapper scc-datepicker-time-interval <?php echo $show_time_options ? '' : 'd-none'; ?>">
					<div class="col-md-6">
						<label class=" use-tooltip fw-bold"
						style="font-size:14px; transform:scale(0.8)">Time Interval</label>
						<select class="d-block" data-value6-key="time_interval">
							<option <?php selected( $scc_date_config['time_interval'], '15m' ); ?> value="15m">15 minutes</option>
							<option <?php selected( $scc_date_config['time_interval'], '30m' ); ?> value="30m">30 minutes</option>
							<option <?php selected( $scc_date_config['time_interval'], '60m' ); ?> value="60m">1 hour</option>
						</select>
					</div>
					<div class="col-md-6">
						<label class=" use-tooltip fw-bold"
						style="font-size:14px; transform:scale(0.8)">Time Format</label>
						<div class="d-block">
							<div class="btn-group scc-btn-group-rounded" data-value6-key="time_format">
								<div role="button" class="m-0 btn <?php echo $scc_date_config['time_format'] === '12h' ? 'scc-btn-brand active' : ''; ?>" data-value="12h">12H</div>
								<div role="button" class="m-0 btn <?php echo $scc_date_config['time_format'] === '24h' ? 'scc-btn-brand active' : ''; ?>" data-value="24h">24H</div>
							</div>
						</div>
					</div>
				</div>
				<hr>
			<?php
            }
		 

                if ( $el->type != 'checkbox'  ) {
                    ?>
					<div class="text-scc-col d-flex" style="font-size:13px;">
						<div class="col-md-12 input-field use-premium-tooltip">
							<input onchange="changeTooltipText(this)" onkeyup="changeTooltipText(this)" id="<?php echo esc_attr( 'scc_tooltip_input-' . $el->id ); ?>" class="scc_title_column_mobl" name="scc_title_column_mobl" type="text" value="<?php echo ( isset( $el->tooltiptext ) ) ? esc_attr( $el->tooltiptext ) : ''; ?>" disabled>
							<label class="form-label fw-bold use-tooltip <?php echo ( isset( $el->tooltiptext ) && strlen( $el->tooltiptext ) > 0 ) ? 'active' : ''; ?> " for="<?php echo esc_attr( 'scc_tooltip_input-' . $el->id ); ?>" title="On the frontend, display a tooltip icon and information next to element titles. Explain what this item is about while keeping the calculator form organized.">Tooltip</label>
						</div>
					</div>
					<?php
                }
        ?>
				<?php if ( $el->type !== 'texthtml'  ) { ?>
				<div class="scc-accordion-tooltip px-0" style="width: 100%; text-align:left;"><span style="text-align: left;display: block;font-size:16px;margin-bottom:10px;">Responsive Options 
			    <i
					data-element-tooltip-type="responsive-options-tt"
					class="material-icons-outlined more-settings-info"
					style="margin-right:5px">
					<span class="scc-icn-wrapper"><?php echo scc_get_kses_extended_ruleset( $this->scc_icons['help-circle'] ); ?></span>
				</i>
				</span>
					<div class="row gx-2 mt-2">
						<div class="col-md-6 input-field use-premium-tooltip">
							<input disabled onchange="changeColumnDesktop(this)" id="<?php echo esc_attr( 'scc_title_column_dskp_r-' . $el->id ); ?>" onkeyup="changeColumnDesktop(this)" class="scc_title_column_dskp" min="1" max="12" name="scc_title_column_dskp" type="number" value="<?php echo intval( $el->titleColumnDesktop ); ?>">
							<label class="active form-label fw-bold" for="<?php echo esc_attr( 'scc_title_column_dskp_r-' . $el->id ); ?>" title="Please enter a number between 1 and 12. 1 being the smallest and 12 being the largest, for your title. If you have a large title, we recommend between 6 and 12.">Title column (desktop)</label>
						</div>
						<div class="col-md-6 input-field use-premium-tooltip">
							<input disabled onchange="changeColumnMobile(this)" id="<?php echo esc_attr( 'scc_title_column_mobl_r-' . $el->id ); ?>" onkeyup="changeColumnMobile(this)" class="scc_title_column_mobl" min="1" max="12" name="scc_title_column_mobl" type="number" value="<?php echo intval( $el->titleColumnMobile ); ?>">
							<label class="active form-label fw-bold" for="<?php echo esc_attr( 'scc_title_column_mobl_r-' . $el->id ); ?>" title="Please enter a number between 1 and 12. 1 being the smallest and 12 being the largest, for your title. If you have a large title, we recommend between 6 and 12.">Title column (mobile)</label>
						</div>
					</div>
				</div>
				<?php } ?>
				<?php if ( in_array( $el->type, [ 'checkbox' ] ) ) { ?>
				<div class="scc-accordion-tooltip px-0" style="width: 100%; text-align:left;">
					<span style="text-align: left;display: block;font-weight:bold">Checkbox Columns</span>
					<div class="row gx-0">
						<select onchange="changeColumnsCheckbox(this)" name="" id="" style="width: 100%; min-width: 100%;">
							<option value="1" 
							<?php
                    if ( $el->value2 == '1' ) {
                        echo 'selected';
                    }
				    ?>
							 >One</option>
							<option value="2" 
							<?php
				    if ( $el->value2 == '2' ) {
				        echo 'selected';
				    }
				    ?>
							 >Two</option>
							<option value="3" 
							<?php
				    if ( $el->value2 == '3' ) {
				        echo 'selected';
				    }
				    ?>
							 >Three</option>
						</select>
					</div>
				</div>
				<?php } 

			?>

			</div>
		</div>
		<?php
        $html = ob_get_clean();

        return $html;
    }
    public function renderFileUploadSetupBody2( $el, $conditionsBySet ) {
        if ( $this->is_from_ajax ) {
            $el->value1 = 'default';
        }
        $defaults = [
            'orden'                         => 0,
            'titleElement'                  => 'Title',
            'type'                          => '',
            'subsection_id'                 => 0,
            'value1'                        => 'default',
            'value4'                        => null,
            'value3'                        => null,
            'value2'                        => '',
            'length'                        => '',
            'uniqueId'                      => '',
            'mandatory'                     => 0,
            'showTitlePdf'                  => 0,
            'titleColumnDesktop'            => '',
            'titleColumnMobile'             => '',
            'showPriceHint'                 => 0,
            'displayFrontend'               => 1,
            'displayDetailList'             => 1,
            'subsection_id'                 => 0,
            'element_woocomerce_product_id' => 0,
            'elementitems'                  => [
                (object) [
                    'id'                    => isset( $el->elementItem_id ) ? $el->elementItem_id : null,
                    'order'                 => '0',
                    'name'                  => 'Name',
                    'price'                 => '10',
                    'description'           => 'Description',
                    'value1'                => '',
                    'value2'                => '',
                    'value3'                => '',
                    'value4'                => '',
                    'uniqueId'              => 'ozkYrfNw9j',
                    'woocomerce_product_id' => '',
                    'opt_default'           => '0',
                    'element_id'            => '0',
                ],
            ],
            'conditions'                    => [ 1 => [] ],
        ];
        $el       = (object) meks_wp_parse_args( $el, $defaults );
        ?>
		<div class="scc-element-content" data-element-setup-type="<?php echo esc_attr( $el->type ); ?>" value="selectoption" style="
		<?php
        if ( ! $this->is_from_ajax ) {
            echo 'display:none;';
        }
        ?>
		 height:auto;">
			<div class="slider-setup-body">
													<!-- CONTENIDO DE CADA ELEMENTO -->
													<!-- ELEMENT -->
													<label class="form-label fw-bold">Title</label>
													<div class="input-group mb-3">
													<input type="text" class="input_pad inputoption_title" onkeyup="clickedTitleElement(this)" style="height:35px;width:100%;margin: 0;" placeholder="Title" value="<?php echo stripslashes( htmlentities( $el->titleElement ) ); ?>">
													</div>
													<div class="row g-3 edit-field" style="    margin-bottom: 1rem!important;">
														<div class="col" >
															<label class="form-label fw-bold">Placeholder text</label>
															<input onkeyup="changeValue2(this)" type="text" class="input_pad inputoption_placeholder" style="width:100%;max-width:100%;float:left;height:35px;" placeholder="Please choose a file" value="<?php echo esc_attr( $el->value2 ); ?>">
														</div>
													</div>
													<div class="row g-3 edit-field" style="    margin-bottom: 1rem!important;">
														<div class="col">
															<label class="form-label fw-bold" style="width: 100%;" >Allowed file types</label>
															<input onkeyup="changeValue3(this)" type="text" class="input_pad inputoption_filetypes" style="width:100%;max-width:100%;float:left;height:35px;" placeholder="png,pdf,jpeg,jpg" value="<?php echo esc_attr( $el->value3 ); ?>">
														</div>
													</div>
													<div class="row g-3 edit-field" style="    margin-bottom: 1rem!important;">
														<div class="col">
															<label class="form-label fw-bold" style="width: 100%;">Max file size (kbs)</label>
															<input onchange="changeValue4(this)" onkeyup="changeValue4(this)" type="number" class="input_pad inputoption_2" style="float:left;margin-right:10px;width:80px;height:35px" placeholder="10" min="0" value="<?php echo esc_attr( $el->value4 ); ?>">
														</div>
													</div>
													</div>
													<div class="scc-element-content" value="selectoption" style="display:none; height:auto">
													<div class="scc-new-accordion-container">
														<div class="styled-accordion">
															<div class="scc-title scc_accordion_advance" onclick="showAdvanceoptions(this)">
																<i class="material-icons">keyboard_arrow_right</i><span>Advanced Options</span>
															</div>
																<?php echo $this->renderAdvancedOptions( $el ); ?>
														</div>
														<div class="styled-accordion">
															<div class="scc-title scc_accordion_conditional ">
																<i class="material-icons">keyboard_arrow_right</i><span style="padding-right:20px;" data-element-tooltip-type="conditional-logic-tt" data-bs-original-title="" title="">Conditional Logic </span>
															</div>
															 <div class="scc-content" style="display: none;">
																<div class="scc-transition">
																	<?php
                                                                    // echo json_encode($conditionsBySet);
                                                                    foreach ( $conditionsBySet as $key => $conditionCollection ) {
                                                                        ?>
																		<?php if ( $key > 1 ) { ?>
																			<div style="margin: 10px 0px 10px -10px;">OR</div>
																		<?php } ?>
																		<div class="condition-container clearfix" data-condition-set=<?php echo intval( $key ); ?>>
																			<?php
                                                                            foreach ( $conditionCollection as $index => $condition ) {
                                                                                if ( ( $condition->op == 'eq' || $condition->op == 'ne' || $condition->op == 'any' ) && ! ( $condition->element_condition->type == 'slider' || $condition->element_condition->type == 'quantity box' ) ) {
                                                                                    ?>
																					<div class="row col-xs-12 col-md-12 conditional-selection" style="padding: 0px; margin-bottom: 5px;">
																						<input type="text" class="id_conditional_item" value="<?php echo intval( $condition->id ); ?>" hidden>
																						<div class="col-xs-1 col-md-1" style="padding:0px;height:35px;background: #DCF1FD;">
																							<span class="scc_label" style="text-align:right;padding-right:10px;margin-top:5px;"><?php echo $index >= 1 ? 'And' : 'Show if'; ?></span>
																						</div>
																						<div class="col-xs-11 col-md-11" style="padding:0px;">
																							<div class="conditional-selection-steps col-xs-12 col-md-12" style="padding:0px;">
																								<div class="item_conditionals">
																									<select class="first-conditional-step col-3" style="height: 35px;">
																										<option style="font-size: 10px" value="0">Select one</option>
																										<option value="<?php echo intval( $condition->condition_element_id ); ?>" data-type="<?php echo esc_attr( $condition->element_condition->type ); ?>" selected><?php echo esc_attr( $condition->element_condition->titleElement ); ?></option>
																									</select>
																									<select class="second-conditional-step col-3" style="height: 35px;">
																										<option value="0" style="font-size: 10px">Select one</option>
																										<option value="eq" 
																										<?php
                                                                                                        if ( $condition->op == 'eq' ) {
                                                                                                            echo 'selected';
                                                                                                        }
                                                                                    ?>
																										>Equal To</option>
																										<option value="ne" 
																										<?php
                                                                                    if ( $condition->op == 'ne' ) {
                                                                                        echo 'selected';
                                                                                    }
                                                                                    ?>
																										>Not Equal To</option>
																										<option value="any" 
																										<?php
                                                                                    if ( $condition->op == 'any' ) {
                                                                                        echo 'selected';
                                                                                    }
                                                                                    ?>
																										>Any</option>
																									</select>
																									<select class="third-conditional-step col-3" style="height: 35px;
																									<?php
                                                                                                    if ( $condition->op == 'any' ) {
                                                                                                        echo 'display:none';
                                                                                                    }
                                                                                    ?>
																									 ">
																										<option value="0" style="font-size: 10px">Select one</option>
																										<option value="<?php echo intval( $condition->elementitem_id ); ?>" selected><?php echo esc_attr( $condition->elementitem_name->name ); ?></option>
																									</select>
																									<input value="<?php echo esc_attr( $condition->value ); ?>" type="number" placeholder="Number" style="height: 35px;display:none" class="conditional-number-value col-3" min="0">
																									<div class="btn-group" style="margin-left: 10px;">
																										<button onclick="addConditionElement(this)" class="btn btn-cond-save">Save</button>
																										<button onclick="deleteCondition(this)" class="btn btn-danger">x</button>
																									</div>
																								</div>
																							</div>
																						</div>
																					</div>
																					<?php
                                                                                }

                                                                                if ( $condition->elementitem_id && ! $condition->condition_element_id ) {
                                                                                    ?>
																					<div class="row col-xs-12 col-md-12 conditional-selection" style="padding: 0px; margin-bottom: 5px;">
																						<input type="text" class="id_conditional_item" value="<?php echo intval( $condition->id ); ?>" hidden>
																						<div class="col-xs-1 col-md-1" style="padding:0px;height:35px;background: #DCF1FD;">
																							<span class="scc_label" style="text-align:right;padding-right:10px;margin-top:5px;"><?php echo $index >= 1 ? 'And' : 'Show if'; ?></span>
																						</div>
																						<div class="col-xs-11 col-md-11" style="padding:0px;">
																							<div class="conditional-selection-steps col-xs-12 col-md-12" style="padding:0px;">
																								<div class="item_conditionals">
																									<select class="first-conditional-step col-3" style="height: 35px;">
																										<option style="font-size: 10px" value="0">Select one</option>
																										<option value="<?php echo intval( $condition->elementitem_id ); ?>" data-type="checkbox" selected><?php echo esc_attr( $condition->elementitem_name->name ); ?></option>
																									</select>
																									<select class="second-conditional-step col-3" style="height: 35px;">
																										<option value="0" style="font-size: 10px">Select one</option>
																										<option value="chec" 
																										<?php
                                                                                                        if ( $condition->op == 'chec' ) {
                                                                                                            echo 'selected';
                                                                                                        }
                                                                                    ?>
																										>Checked</option>
																										<option value="unc" 
																										<?php
                                                                                    if ( $condition->op == 'unc' ) {
                                                                                        echo 'selected';
                                                                                    }
                                                                                    ?>
																										>Unchecked</option>
																									</select>
																									<select class="third-conditional-step col-3" style="height: 35px;display:none">
																										<option value="0" style="font-size: 10px">Select one</option>
																										<option value="<?php echo intval( $condition->elementitem_id ); ?>" selected><?php echo esc_attr( $condition->elementitem_name->name ); ?></option>
																									</select>
																									<input value="<?php echo esc_attr( $condition->value ); ?>" type="number" placeholder="Number" style="height: 35px;display:none" class="conditional-number-value col-3" min="0">
																									<div class="btn-group" style="margin-left: 10px;">
																										<button onclick="addConditionElement(this)" class="btn btn-cond-save">Save</button>
																										<button onclick="deleteCondition(this)" class="btn btn-danger">x</button>
																									</div>
																								</div>
																							</div>
																						</div>
																					</div>
																					<?php
                                                                                }

                                                                                if ( $condition->condition_element_id ) {
                                                                                    if ( $condition->element_condition->type == 'slider' || $condition->element_condition->type == 'quantity box' ) {
                                                                                        ?>
																						<div class="row col-xs-12 col-md-12 conditional-selection" style="padding: 0px; margin-bottom: 5px;">
																							<input type="text" class="id_conditional_item" value="<?php echo intval( $condition->id ); ?>" hidden>
																							<div class="col-xs-1 col-md-1" style="padding:0px;height:35px;background: #DCF1FD;">
																								<span class="scc_label" style="text-align:right;padding-right:10px;margin-top:5px;"><?php echo $index >= 1 ? 'And' : 'Show if'; ?></span>
																							</div>
																							<div class="col-xs-11 col-md-11" style="padding:0px;">
																								<div class="conditional-selection-steps col-xs-12 col-md-12" style="padding:0px;">
																									<div class="item_conditionals">
																										<select class="first-conditional-step col-3" style="height: 35px;">
																											<option style="font-size: 10px" value="0">Select one</option>
																											<option value="<?php echo intval( $condition->condition_element_id ); ?>" data-type="<?php echo esc_attr( $condition->element_condition->type ); ?>" selected><?php echo esc_attr( $condition->element_condition->titleElement ); ?></option>
																										</select>
																										<select class="second-conditional-step col-3" style="height: 35px;">
																											<option value="0" style="font-size: 10px">Select one</option>
																											<option value="eq" 
																											<?php
                                                                                                            if ( $condition->op == 'eq' ) {
                                                                                                                echo 'selected';
                                                                                                            }
                                                                                        ?>
																											>Equal To</option>
																											<option value="ne" 
																											<?php
                                                                                        if ( $condition->op == 'ne' ) {
                                                                                            echo 'selected';
                                                                                        }
                                                                                        ?>
																											>Not Equal To</option>
																											<option value="gr" 
																											<?php
                                                                                        if ( $condition->op == 'gr' ) {
                                                                                            echo 'selected';
                                                                                        }
                                                                                        ?>
																											>Greater than</option>
																											<option value="les" 
																											<?php
                                                                                        if ( $condition->op == 'les' ) {
                                                                                            echo 'selected';
                                                                                        }
                                                                                        ?>
																											>Less than</option>
																										</select>
																										<select class="third-conditional-step col-3" style="height: 35px;display:none">
																											<option value="0" style="font-size: 10px">Select one</option>
																											<option value="<?php echo intval( $condition->elementitem_id ); ?>" selected><?php echo esc_attr( $condition->elementitem_name->name ); ?></option>
																										</select>
																										<input value="<?php echo esc_attr( $condition->value ); ?>" type="number" placeholder="Number" style="height: 35px;" class="conditional-number-value col-3" min="0">
																										<div class="btn-group" style="margin-left: 10px;">
																											<button onclick="addConditionElement(this)" class="btn btn-cond-save">Save</button>
																											<button onclick="deleteCondition(this)" class="btn btn-danger">x</button>
																										</div>
																									</div>
																								</div>
																							</div>
																						</div>
																						<?php
                                                                                    }
                                                                                }
                                                                            }
                                                                        ?>
																			<div class="row col-xs-12 col-md-12 conditional-selection  
																			<?php
                                                                        if ( count( $conditionCollection ) ) {
                                                                            echo 'hidden';
                                                                        }
                                                                        ?>
																			" style="padding: 0px; margin-bottom: 5px;">
																				<div class="col-xs-1 col-md-1" style="padding:0px;height:35px;background: #DCF1FD;">
																					<span class="scc_label" style="text-align:right;padding-right:10px;margin-top:5px;"><?php echo empty( count( $el->conditions ) ) ? 'Show if' : 'And'; ?></span>
																				</div>
																				<div class="col-xs-11 col-md-11" style="padding:0px;">
																					<div class="conditional-selection-steps col-xs-12 col-md-12" style="padding:0px;">
																						<div class="item_conditionals">
																							<select class="first-conditional-step col-3" style="height: 35px;">
																								<option style="font-size: 10px" value="0">Select an element</option>
																							</select>
																							<select class="second-conditional-step col-3" style="height: 35px;display:none">
																								<option value="0" style="font-size: 10px">Select one</option>
																							</select>
																							<select class="third-conditional-step col-3" style="height: 35px;display:none">
																								<option value="0" style="font-size: 10px">Select one</option>
																							</select>
																							<input type="number" placeholder="Number" style="height: 35px;display:none" class="conditional-number-value col-3" min="0">
																							<div class="btn-group" style="margin-left: 10px;display:none">
																								<button onclick="addConditionElement(this)" class="btn btn-cond-save">Save</button>
																								<button onclick="deleteCondition(this)" class="btn btn-danger btn-delcondition" style="display: none;">x</button>
																							</div>
																						</div>
																					</div>
																				</div>
																			</div>
																			<button onclick="(($this) => {jQuery($this).prev().removeClass('hidden'); jQuery($this).addClass('hidden')})(this)" class="btn btn-addcondition cond-add-btn 
																			<?php
                                                                        if ( empty( count( $el->conditions ) ) ) {
                                                                            echo 'hidden';
                                                                        }
                                                                        ?>
																			">+ AND</button>
																		</div>
																	<?php } ?>
																	<div style="width: 28%">
																		<button class="btn btn-primary btn-cond-or <?php echo empty( count( $el->conditions ) ) ? 'hidden' : ''; ?>">+ OR</button>
																	</div>
																</div>
															 </div>
														</div>
													</div>
													</div>
													<!-- ADVANCE -->
												</div>
		<?php
        $html = ob_get_clean();

        return $html;
    }

	public function renderTextHtmlSetupBody2( $el, $conditionsBySet ) {
        if ( $this->is_from_ajax ) {
            $el->value1 = 'default';
        }
        $defaults    = [
            'orden'                         => 0,
            'titleElement'                  => 'Title',
            'type'                          => '',
            'subsection_id'                 => 0,
            'value1'                        => 'default',
            'value4'                        => null,
            'value3'                        => null,
            'value2'                        => '',
            'length'                        => '',
            'uniqueId'                      => '',
            'mandatory'                     => 0,
            'showTitlePdf'                  => 0,
            'titleColumnDesktop'            => '4',
            'titleColumnMobile'             => '12',
            'showPriceHint'                 => 0,
            'displayFrontend'               => 1,
            'displayDetailList'             => 1,
            'subsection_id'                 => 0,
            'element_woocomerce_product_id' => 0,
            'conditions'                    => [],
        ];
        $el          = (object) meks_wp_parse_args( $el, $defaults );
        $field_value = json_decode( wp_unslash( $el->value2 ) );

        if ( empty( $field_value ) ) {
            $field_value = $el->value2;
        }
        ob_start();
        ?>
		<div class="scc-element-content" data-element-setup-type="<?php echo esc_attr( $el->type ); ?>" value="selectoption" style="
			<?php
            if ( ! $this->is_from_ajax ) {
                echo 'display:none;';
            }
        ?>
			height:auto;">
			<div class="slider-setup-body">
				<!-- ELEMENT -->
				<label class="form-label fw-bold">Title</label>
				<div class="input-group mb-3">
					<input type="text" class="789 input_pad inputoption_title" onkeyup="clickedTitleElement(this)"
						style="height:35px;width:100%;" placeholder="Title"
						value="<?php echo esc_attr( $el->titleElement ); ?>">
				</div>
				<div class="row g-3 edit-field" style="    margin-bottom: 1rem!important;">
					<div class="col">
						<label class="form-label fw-bold">Raw Text (or HTML)</label>
						<textarea data-type="<?php echo $el->type; ?>" onkeyup="changeValue2(this)" rows="5" cols="33"
							class="input_pad inputoption_text"
							style="width: 100%;"><?php echo isset( $field_value->texthtml ) ? $field_value->texthtml : $field_value; ?></textarea>
					</div>
				</div>
				<div class="scc-texthtml-errors scc-hidden">
				<?php
                $msg   = 'There are HTML errors in your changes, fix them to save your changes correctly';
        $hasHtmlErrors = true;
        echo df_scc_render_alert( $hasHtmlErrors, $msg, 'mt-3' );
        ?>
				</div>
			</div>
			<div class="scc-element-content" value="selectoption" style="<?php
        if ( ! $this->is_from_ajax ) {
            echo 'display:none;';
        }
        ?> height:auto">
				<div class="scc-new-accordion-container">
					<div class="styled-accordion">
						<div class="scc-title scc_accordion_advance" onclick="showAdvanceoptions(this)">
							<i class="material-icons">keyboard_arrow_right</i>
							<span>Advanced Options</span>
						</div>
						<?php echo $this->renderAdvancedOptions( $el ); ?>
					</div>
					<div class="styled-accordion">
					<div class="scc-title scc_accordion_conditional "  >
						<i class="material-icons">keyboard_arrow_right</i><span style="padding-right:20px;" data-element-tooltip-type="conditional-logic-tt" data-bs-original-title="" title="">Conditional Logic </span>
					</div>
					<div class="scc-content" style="display: none;">
						<div class="scc-transition">
							<?php
                            // echo json_encode($conditionsBySet);
                            foreach ( $conditionsBySet as $key => $conditionCollection ) {
                                ?>
								<?php if ( $key > 1 ) { ?>
									<div style="margin: 10px 0px 10px -10px;">OR</div>
								<?php } ?>
								<div class="condition-container clearfix" data-condition-set=<?php echo intval( $key ); ?>>
								<?php
                                foreach ( $conditionCollection as $index => $condition ) {
                                    if ( ( $condition->op == 'eq' || $condition->op == 'ne' || $condition->op == 'any' ) && ! ( $condition->element_condition->type == 'slider' || $condition->element_condition->type == 'quantity box' ) ) {
                                        ?>
											<div class="row col-xs-12 col-md-12 conditional-selection" style="padding: 0px; margin-bottom: 5px;">
												<input type="text" class="id_conditional_item" value="<?php echo intval( $condition->id ); ?>" hidden>
												<div class="col-xs-1 col-md-1" style="padding:0px;height:35px;background: #DCF1FD;">
													<span class="scc_label" style="text-align:right;padding-right:10px;margin-top:5px;"><?php echo $index >= 1 ? 'And' : 'Show if'; ?></span>
												</div>
												<div class="col-xs-11 col-md-11" style="padding:0px;">
													<div class="conditional-selection-steps col-xs-12 col-md-12" style="padding:0px;">
														<div class="item_conditionals">
															<select  class="first-conditional-step col-3" style="height: 35px;">
																<option style="font-size: 10px" value="0">Select one</option>
																<option value="<?php echo intval( $condition->condition_element_id ); ?>" data-type="<?php echo esc_attr( $condition->element_condition->type ); ?>" selected><?php echo esc_attr( $condition->element_condition->titleElement ); ?></option>
															</select>
															<select  class="second-conditional-step col-3" style="height: 35px;">
																<option value="0" style="font-size: 10px">Select one</option>
																<option value="eq" 
																<?php
                                                                if ( $condition->op == 'eq' ) {
                                                                    echo 'selected';
                                                                }
                                        ?>
																	>Equal To</option>
																<option value="ne" 
																<?php
                                        if ( $condition->op == 'ne' ) {
                                            echo 'selected';
                                        }
                                        ?>
																	>Not Equal To</option>
																<option value="any" 
																<?php
                                        if ( $condition->op == 'any' ) {
                                            echo 'selected';
                                        }
                                        ?>
																	>Any</option>
															</select>
															<select  class="third-conditional-step col-3" style="height: 35px;
															<?php
                                                            if ( $condition->op == 'any' ) {
                                                                echo 'display:none';
                                                            }
                                        ?>
																 ">
																<option value="0" style="font-size: 10px">Select one</option>
																<option value="<?php echo intval( $condition->elementitem_id ); ?>" selected><?php echo esc_attr( $condition->elementitem_name->name ); ?></option>
															</select>
															<input value="<?php echo esc_attr( $condition->value ); ?>" type="number" placeholder="Number" style="height: 35px;display:none" class="conditional-number-value col-3" min="0">
															<div class="btn-group" style="margin-left: 10px;">
																<button onclick="addConditionElement(this)" class="btn btn-cond-save">Save</button>
																<button onclick="deleteCondition(this)" class="btn btn-danger">x</button>
															</div>
														</div>
													</div>
												</div>
											</div>
											<?php
                                    }

                                    if ( $condition->elementitem_id && ! $condition->condition_element_id ) {
                                        ?>
											<div class="row col-xs-12 col-md-12 conditional-selection" style="padding: 0px; margin-bottom: 5px;">
												<input type="text" class="id_conditional_item" value="<?php echo intval( $condition->id ); ?>" hidden>
												<div class="col-xs-1 col-md-1" style="padding:0px;height:35px;background: #DCF1FD;">
													<span class="scc_label" style="text-align:right;padding-right:10px;margin-top:5px;"><?php echo $index >= 1 ? 'And' : 'Show if'; ?></span>
												</div>
												<div class="col-xs-11 col-md-11" style="padding:0px;">
													<div class="conditional-selection-steps col-xs-12 col-md-12" style="padding:0px;">
														<div class="item_conditionals">
															<select class="first-conditional-step col-3" style="height: 35px;">
																<option style="font-size: 10px" value="0">Select one</option>
																<option value="<?php echo intval( $condition->elementitem_id ); ?>" data-type="checkbox" selected><?php echo esc_attr( $condition->elementitem_name->name ); ?></option>
															</select>
															<select class="second-conditional-step col-3" style="height: 35px;">
																<option value="0" style="font-size: 10px">Select one</option>
																<option value="chec" 
																<?php
                                                                if ( $condition->op == 'chec' ) {
                                                                    echo 'selected';
                                                                }
                                        ?>
																	>Checked</option>
																<option value="unc" 
																<?php
                                        if ( $condition->op == 'unc' ) {
                                            echo 'selected';
                                        }
                                        ?>
																	>Unchecked</option>
															</select>
															<select  class="third-conditional-step col-3" style="height: 35px;display:none">
																<option value="0" style="font-size: 10px">Select one</option>
																<option value="<?php echo intval( $condition->elementitem_id ); ?>" selected><?php echo esc_attr( $condition->elementitem_name->name ); ?></option>
															</select>
															<input value="<?php echo esc_attr( $condition->value ); ?>" type="number" placeholder="Number" style="height: 35px;display:none" class="conditional-number-value col-3" min="0">
															<div class="btn-group" style="margin-left: 10px;">
																<button onclick="addConditionElement(this)" class="btn btn-cond-save">Save</button>
																<button onclick="deleteCondition(this)" class="btn btn-danger">x</button>
															</div>
														</div>
													</div>
												</div>
											</div>
											<?php
                                    }

                                    if ( $condition->condition_element_id ) {
                                        if ( $condition->element_condition->type == 'slider' || $condition->element_condition->type == 'quantity box' ) {
                                            ?>
												<div class="row col-xs-12 col-md-12 conditional-selection" style="padding: 0px; margin-bottom: 5px;">
													<input type="text" class="id_conditional_item" value="<?php echo intval( $condition->id ); ?>" hidden>
													<div class="col-xs-1 col-md-1" style="padding:0px;height:35px;background: #DCF1FD;">
														<span class="scc_label" style="text-align:right;padding-right:10px;margin-top:5px;"><?php echo $index >= 1 ? 'And' : 'Show if'; ?></span>
													</div>
													<div class="col-xs-11 col-md-11" style="padding:0px;">
														<div class="conditional-selection-steps col-xs-12 col-md-12" style="padding:0px;">
															<div class="item_conditionals">
																<select class="first-conditional-step col-3" style="height: 35px;">
																	<option style="font-size: 10px" value="0">Select one</option>
																	<option value="<?php echo intval( $condition->condition_element_id ); ?>" data-type="<?php echo esc_attr( $condition->element_condition->type ); ?>" selected><?php echo esc_attr( $condition->element_condition->titleElement ); ?></option>
																</select>
																<select class="second-conditional-step col-3" style="height: 35px;">
																<option value="0" style="font-size: 10px">Select one</option>
																	<option value="eq" 
																	<?php
                                                                    if ( $condition->op == 'eq' ) {
                                                                        echo 'selected';
                                                                    }
                                            ?>
																		>Equal To</option>
																	<option value="ne" 
																	<?php
                                            if ( $condition->op == 'ne' ) {
                                                echo 'selected';
                                            }
                                            ?>
																		>Not Equal To</option>
																	<option value="gr" 
																	<?php
                                            if ( $condition->op == 'gr' ) {
                                                echo 'selected';
                                            }
                                            ?>
																		>Greater than</option>
																	<option value="les" 
																	<?php
                                            if ( $condition->op == 'les' ) {
                                                echo 'selected';
                                            }
                                            ?>
																		>Less than</option>
																</select>
																<select  class="third-conditional-step col-3" style="height: 35px;display:none">
																	<option value="0" style="font-size: 10px">Select one</option>
																	<option value="<?php echo intval( $condition->elementitem_id ); ?>" selected><?php echo esc_attr( $condition->elementitem_name->name ); ?></option>
																</select>
																<input value="<?php echo esc_attr( $condition->value ); ?>" type="number" placeholder="Number" style="height: 35px;" class="conditional-number-value col-3" min="0">
																<div class="btn-group" style="margin-left: 10px;">
																	<button onclick="addConditionElement(this)" class="btn btn-cond-save">Save</button>
																	<button onclick="deleteCondition(this)" class="btn btn-danger">x</button>
																</div>
															</div>
														</div>
													</div>
												</div>
												<?php
                                        }
                                    }
                                }
                                ?>
									<div class="row col-xs-12 col-md-12 conditional-selection  
									<?php
                                    if ( count( $conditionCollection ) ) {
                                        echo 'hidden';
                                    }
                                ?>
										" style="padding: 0px; margin-bottom: 5px;">
										<div class="col-xs-1 col-md-1" style="padding:0px;height:35px;background: #DCF1FD;">
											<span class="scc_label" style="text-align:right;padding-right:10px;margin-top:5px;"><?php echo empty( count( $el->conditions ) ) ? 'Show if' : 'And'; ?></span>
										</div>
										<div class="col-xs-11 col-md-11" style="padding:0px;">
											<div class="conditional-selection-steps col-xs-12 col-md-12" style="padding:0px;">
												<div class="item_conditionals">
													<select class="first-conditional-step col-3" style="height: 35px;">
														<option style="font-size: 10px" value="0">Select an element</option>
													</select>
													<select class="second-conditional-step col-3" style="height: 35px;display:none">
														<option value="0" style="font-size: 10px">Select one</option>
													</select>
													<select class="third-conditional-step col-3" style="height: 35px;display:none">
														<option value="0" style="font-size: 10px">Select one</option>
													</select>
													<input type="number" placeholder="Number" style="height: 35px;display:none" class="conditional-number-value col-3" min="0">
													<div class="btn-group" style="margin-left: 10px;display:none">
														<button onclick="addConditionElement(this)" class="btn btn-cond-save">Save</button>
														<button onclick="deleteCondition(this)" class="btn btn-danger btn-delcondition" style="display: none;">x</button>
													</div>
												</div>
											</div>
										</div>
									</div>
									<button onclick="(($this) => {jQuery($this).prev().removeClass('hidden'); jQuery($this).addClass('hidden')})(this)" class="btn btn-addcondition cond-add-btn 
									<?php
                                if ( empty( count( $el->conditions ) ) ) {
                                    echo 'hidden';
                                }
                                ?>
										">+ AND</button>
								</div>
							<?php } ?>
							<div style="width: 28%">
								<button class="btn btn-primary btn-cond-or <?php echo empty( count( $el->conditions ) ) ? 'hidden' : ''; ?>">+ OR</button>
							</div>
						</div>
					</div>
				</div>
				</div>
			</div>

		</div>
		<?php
        $html = ob_get_clean();

        return $html;
    }

    public function renderCheckboxSetupBody( $el, $conditionsBySet ) {
        $defaults = [
            'orden'                         => 0,
            'titleElement'                  => 'Title',
            'type'                          => '',
            'subsection_id'                 => 0,
            'value1'                        => 'default',
            'value4'                        => null,
            'value3'                        => null,
            'value2'                        => '',
            'length'                        => '',
            'uniqueId'                      => '',
            'mandatory'                     => 0,
            'showTitlePdf'                  => 0,
            'titleColumnDesktop'            => '4',
            'titleColumnMobile'             => '12',
            'showPriceHint'                 => 0,
            'displayFrontend'               => 1,
            'displayDetailList'             => 1,
            'subsection_id'                 => 0,
            'element_woocomerce_product_id' => 0,
            'elementitems'                  => [
                (object) [
                    'id'                    => isset( $el->elementItem_id ) ? $el->elementItem_id : null,
                    'order'                 => '0',
                    'name'                  => 'Name',
                    'price'                 => '10',
                    'description'           => 'Description',
                    'value1'                => '',
                    'value2'                => '',
                    'value3'                => '',
                    'value4'                => '',
                    'uniqueId'              => 'ozkYrfNw9j',
                    'woocomerce_product_id' => '',
                    'opt_default'           => '0',
                    'element_id'            => '0',
                ],
            ],
            'conditions'                    => [],
        ];
        $el       = (object) meks_wp_parse_args( $el, $defaults );
        ob_start();
        ?>
	<div class="scc-element-content checkbox-content" data-element-setup-type="<?php echo esc_attr( $el->type ); ?>" value="selectoption" style="
		<?php
        if ( ! $this->is_from_ajax ) {
            echo 'display:none;';
        }
        ?>
	 height:auto">

		<!-- Simple Buttons - ELEMENT -->
		<div class="slider-setup-body">
		<label class="form-label fw-bold" title="For checkboxes, this will not appear on the frontend. Its for internal references only.">Title (Internal reference only)</label>
			<div class="input-group mb-3">
			<input type="text" class="form-control" onkeyup="clickedTitleElement(this)" value="<?php echo esc_attr( wp_unslash( $el->titleElement ) ); ?>">
		</div>
		<div class="row g-3 edit-field scc-quantity-input">
			<div class="col col-md-4">
				<label class="form-label fw-bold" style="align-items: center;
    display: flex;">Input Box Style 
					<i class="material-icons-outlined with-tooltip" style="margin-left:3px;" data-element-tooltip-type="checkbox-styles-tt" title="" data-bs-original-title="">help_outline</i>
				</label>
					<select onchange="changeValue1(this)" class="fieldFormat" style="width:100%;max-width:100%;height:35px;border-color:#f8f9ff;">
					<option value="6" 
					<?php
                    if ( $el->value1 == '6' ) {
                        echo 'selected';
                    }
        ?>
										>Simple Buttons (Inline)</option>
					<option value="1" 
					<?php
        if ( $el->value1 == '1' ) {
            echo 'selected';
        }
        ?>
										>Circle Checkbox</option>
					<option value="5" 
					<?php
        if ( $el->value1 == '5' ) {
            echo 'selected';
        }
        ?>
										>Circle Checkbox (Animated)</option>
					<option value="2" 
					<?php
        if ( $el->value1 == '2' ) {
            echo 'selected';
        }
        ?>
										>Square Checkbox (Animated)</option>
					<option value="3" 
					<?php
        if ( $el->value1 == '3' ) {
            echo 'selected';
        }
        ?>
										>Rectangle Toggle Switch</option>
					<option value="4" 
					<?php
        if ( $el->value1 == '4' ) {
            echo 'selected';
        }
        ?>
										>Rounded Toggle Switch </option>
					<option value="7" 
					<?php
        if ( $el->value1 == '7' ) {
            echo 'selected';
        }
        ?>
										>Radio (Single Choice)</option>
					<option value="" disabled>Multi-Items Radio Switch (Premium only)</option>
					<option value="" disabled>Image Buttons (Premium only)</option>
				</select>
														</div>
									</div>
		<!-- Image Button & Checkboxes & Simple Buttons Elements - (Pulled from DB) -->
		<div class="selectoption_2 col-xs-12 col-md-12" style="margin-top:20px;">
			<?php
            foreach ( $el->elementitems as $key => $elit ) {
                $count = $key + 1;
                echo $this->checkbox_setup_checkbox_item( $count, $elit, $el->value1 == 8 );
            }
        ?>
		</div>
		<div style="margin-top:5px;"><a onclick="addCheckboxItems(this)" data-type="<?php echo ( $el->value1 == 8 ) ? 'image-button' : 'otro'; ?>" class="crossnadd" style="margin-top:5px;margin-bottom:20px;">+ Item </a>
		</div>
		</div>
		</div>
		<div class="scc-element-content" value="selectoption" style="
		<?php
        if ( ! $this->is_from_ajax ) {
            echo 'display:none;';
        }
        ?>
		 height:auto">
		<div class="scc-new-accordion-container">
			<div class="styled-accordion">
				<div class="scc-title scc_accordion_advance" onclick="showAdvanceoptions(this)">
					<i class="material-icons">keyboard_arrow_right</i><span>Advanced Options</span>
				</div>
				<?php echo $this->renderAdvancedOptions( $el ); ?>
			</div>
			<div class="styled-accordion">
				<div class="scc-title scc_accordion_conditional ">
					<i class="material-icons">keyboard_arrow_right</i><span style="padding-right:20px;" data-element-tooltip-type="conditional-logic-tt" data-bs-original-title="" title="">Conditional Logic </span>
				</div>
				<div class="scc-content" style="display: none;">
					<div class="scc-transition">
						<?php
                        // echo json_encode($conditionsBySet);
                        foreach ( $conditionsBySet as $key => $conditionCollection ) {
                            ?>
							<?php if ( $key > 1 ) { ?>
								<div style="margin: 10px 0px 10px -10px;">OR</div>
							<?php } ?>
							<div class="condition-container clearfix" data-condition-set=<?php echo intval( $key ); ?>>
								<?php
                                foreach ( $conditionCollection as $index => $condition ) {
                                    if ( ( $condition->op == 'eq' || $condition->op == 'ne' || $condition->op == 'any' ) && ! ( $condition->element_condition->type == 'slider' || $condition->element_condition->type == 'quantity box' ) ) {
                                        ?>
										<div class="row col-xs-12 col-md-12 conditional-selection" style="padding: 0px; margin-bottom: 5px;">
											<input type="text" class="id_conditional_item" value="<?php echo intval( $condition->id ); ?>" hidden>
											<div class="col-xs-1 col-md-1" style="padding:0px;height:35px;background: #DCF1FD;">
												<span class="scc_label" style="text-align:right;padding-right:10px;margin-top:5px;"><?php echo $index >= 1 ? 'And' : 'Show if'; ?></span>
											</div>
											<div class="col-xs-11 col-md-11" style="padding:0px;">
												<div class="conditional-selection-steps col-xs-12 col-md-12" style="padding:0px;">
													<div class="item_conditionals">
														<select class="first-conditional-step col-3" style="height: 35px;">
															<option style="font-size: 10px" value="0">Select one</option>
															<option value="<?php echo intval( $condition->condition_element_id ); ?>" data-type="<?php echo esc_attr( $condition->element_condition->type ); ?>" selected><?php echo esc_attr( $condition->element_condition->titleElement ); ?></option>
														</select>
														<select class="second-conditional-step col-3" style="height: 35px;">
															<option value="0" style="font-size: 10px">Select one</option>
															<option value="eq" 
															<?php
                                                            if ( $condition->op == 'eq' ) {
                                                                echo 'selected';
                                                            }
                                        ?>
																				>Equal To</option>
															<option value="ne" 
															<?php
                                        if ( $condition->op == 'ne' ) {
                                            echo 'selected';
                                        }
                                        ?>
																				>Not Equal To</option>
															<option value="any" 
															<?php
                                        if ( $condition->op == 'any' ) {
                                            echo 'selected';
                                        }
                                        ?>
																				>Any</option>
														</select>
														<select class="third-conditional-step col-3" style="height: 35px;
																										<?php
                                                                                    if ( $condition->op == 'any' ) {
                                                                                        echo 'display:none';
                                                                                    }
                                        ?>
																										">
															<option value="0" style="font-size: 10px">Select one</option>
															<option value="<?php echo intval( $condition->elementitem_id ); ?>" selected><?php echo esc_attr( $condition->elementitem_name->name ); ?></option>
														</select>
														<input value="<?php echo esc_attr( $condition->value ); ?>" type="number" placeholder="Number" style="height: 35px;display:none" class="conditional-number-value col-3" min="0">
														<div class="btn-group" style="margin-left: 10px;">
															<button onclick="addConditionElement(this)" class="btn btn-cond-save">Save</button>
															<button onclick="deleteCondition(this)" class="btn btn-danger">x</button>
														</div>
													</div>
												</div>
											</div>
										</div>
										<?php
                                    }

                                    if ( $condition->elementitem_id && ! $condition->condition_element_id ) {
                                        ?>
										<div class="row col-xs-12 col-md-12 conditional-selection" style="padding: 0px; margin-bottom: 5px;">
											<input type="text" class="id_conditional_item" value="<?php echo intval( $condition->id ); ?>" hidden>
											<div class="col-xs-1 col-md-1" style="padding:0px;height:35px;background: #DCF1FD;">
												<span class="scc_label" style="text-align:right;padding-right:10px;margin-top:5px;"><?php echo $index >= 1 ? 'And' : 'Show if'; ?></span>
											</div>
											<div class="col-xs-11 col-md-11" style="padding:0px;">
												<div class="conditional-selection-steps col-xs-12 col-md-12" style="padding:0px;">
													<div class="item_conditionals">
														<select class="first-conditional-step col-3" style="height: 35px;">
															<option style="font-size: 10px" value="0">Select one</option>
															<option value="<?php echo intval( $condition->elementitem_id ); ?>" data-type="checkbox" selected><?php echo esc_attr( $condition->elementitem_name->name ); ?></option>
														</select>
														<select class="second-conditional-step col-3" style="height: 35px;">
															<option value="0" style="font-size: 10px">Select one</option>
															<option value="chec" 
															<?php
                                                            if ( $condition->op == 'chec' ) {
                                                                echo 'selected';
                                                            }
                                        ?>
																					>Checked</option>
															<option value="unc" 
															<?php
                                        if ( $condition->op == 'unc' ) {
                                            echo 'selected';
                                        }
                                        ?>
																				>Unchecked</option>
														</select>
														<select class="third-conditional-step col-3" style="height: 35px;display:none">
															<option value="0" style="font-size: 10px">Select one</option>
															<option value="<?php echo intval( $condition->elementitem_id ); ?>" selected><?php echo esc_attr( $condition->elementitem_name->name ); ?></option>
														</select>
														<input value="<?php echo esc_attr( $condition->value ); ?>" type="number" placeholder="Number" style="height: 35px;display:none" class="conditional-number-value col-3" min="0">
														<div class="btn-group" style="margin-left: 10px;">
															<button onclick="addConditionElement(this)" class="btn btn-cond-save">Save</button>
															<button onclick="deleteCondition(this)" class="btn btn-danger">x</button>
														</div>
													</div>
												</div>
											</div>
										</div>
										<?php
                                    }

                                    if ( $condition->condition_element_id ) {
                                        if ( $condition->element_condition->type == 'slider' || $condition->element_condition->type == 'quantity box' ) {
                                            ?>
											<div class="row col-xs-12 col-md-12 conditional-selection" style="padding: 0px; margin-bottom: 5px;">
												<input type="text" class="id_conditional_item" value="<?php echo intval( $condition->id ); ?>" hidden>
												<div class="col-xs-1 col-md-1" style="padding:0px;height:35px;background: #DCF1FD;">
													<span class="scc_label" style="text-align:right;padding-right:10px;margin-top:5px;"><?php echo $index >= 1 ? 'And' : 'Show if'; ?></span>
												</div>
												<div class="col-xs-11 col-md-11" style="padding:0px;">
													<div class="conditional-selection-steps col-xs-12 col-md-12" style="padding:0px;">
														<div class="item_conditionals">
															<select class="first-conditional-step col-3" style="height: 35px;">
																<option style="font-size: 10px" value="0">Select one</option>
																<option value="<?php echo intval( $condition->condition_element_id ); ?>" data-type="<?php echo esc_attr( $condition->element_condition->type ); ?>" selected><?php echo esc_attr( $condition->element_condition->titleElement ); ?></option>
															</select>
															<select class="second-conditional-step col-3" style="height: 35px;">
																<option value="0" style="font-size: 10px">Select one</option>
																<option value="eq" 
																<?php
                                                                if ( $condition->op == 'eq' ) {
                                                                    echo 'selected';
                                                                }
                                            ?>
																					>Equal To</option>
																<option value="ne" 
																<?php
                                            if ( $condition->op == 'ne' ) {
                                                echo 'selected';
                                            }
                                            ?>
																					>Not Equal To</option>
																<option value="gr" 
																<?php
                                            if ( $condition->op == 'gr' ) {
                                                echo 'selected';
                                            }
                                            ?>
																					>Greater than</option>
																<option value="les" 
																<?php
                                            if ( $condition->op == 'les' ) {
                                                echo 'selected';
                                            }
                                            ?>
																					>Less than</option>
															</select>
															<select class="third-conditional-step col-3" style="height: 35px;display:none">
																<option value="0" style="font-size: 10px">Select one</option>
																<option value="<?php echo intval( $condition->elementitem_id ); ?>" selected><?php echo esc_attr( $condition->elementitem_name->name ); ?></option>
															</select>
															<input value="<?php echo esc_attr( $condition->value ); ?>" type="number" placeholder="Number" style="height: 35px;" class="conditional-number-value col-3" min="0">
															<div class="btn-group" style="margin-left: 10px;">
																<button onclick="addConditionElement(this)" class="btn btn-cond-save">Save</button>
																<button onclick="deleteCondition(this)" class="btn btn-danger">x</button>
															</div>
														</div>
													</div>
												</div>
											</div>
											<?php
                                        }
                                    }
                                }
                            ?>
								<div class="row col-xs-12 col-md-12 conditional-selection  
																				<?php
                                                                            if ( count( $conditionCollection ) ) {
                                                                                echo 'hidden';
                                                                            }
                            ?>
																				" style="padding: 0px; margin-bottom: 5px;">
									<div class="col-xs-1 col-md-1" style="padding:0px;height:35px;background: #DCF1FD;">
										<span class="scc_label" style="text-align:right;padding-right:10px;margin-top:5px;"><?php echo empty( count( $el->conditions ) ) ? 'Show if' : 'And'; ?></span>
									</div>
									<div class="col-xs-11 col-md-11" style="padding:0px;">
										<div class="conditional-selection-steps col-xs-12 col-md-12" style="padding:0px;">
											<div class="item_conditionals">
												<select class="first-conditional-step col-3" style="height: 35px;">
													<option style="font-size: 10px" value="0">Select an element</option>
												</select>
												<select class="second-conditional-step col-3" style="height: 35px;display:none">
													<option value="0" style="font-size: 10px">Select one</option>
												</select>
												<select class="third-conditional-step col-3" style="height: 35px;display:none">
													<option value="0" style="font-size: 10px">Select one</option>
												</select>
												<input type="number" placeholder="Number" style="height: 35px;display:none" class="conditional-number-value col-3" min="0">
												<div class="btn-group" style="margin-left: 10px;display:none">
													<button onclick="addConditionElement(this)" class="btn btn-cond-save">Save</button>
													<button onclick="deleteCondition(this)" class="btn btn-danger btn-delcondition" style="display: none;">x</button>
												</div>
											</div>
										</div>
									</div>
								</div>
								<button onclick="(($this) => {jQuery($this).prev().removeClass('hidden'); jQuery($this).addClass('hidden')})(this)" class="btn btn-addcondition cond-add-btn 
																				<?php
                            if ( empty( count( $el->conditions ) ) ) {
                                echo 'hidden';
                            }
                            ?>
																				">+ AND</button>
							</div>
						<?php } ?>
						<div style="width: 28%">
							<button class="btn btn-primary btn-cond-or <?php echo empty( count( $el->conditions ) ) ? 'hidden' : ''; ?>">+ OR</button>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
		<?php
        $html = ob_get_clean();

        return $html;
    }
    public function checkbox_setup_checkbox_item( $count, $elit, $is_image_checkbox ) {
        $defaults = [
            'id'                    => null,
            'order'                 => '0',
            'name'                  => 'Name',
            'price'                 => '10',
            'description'           => 'Description',
            'value1'                => '',
            'value2'                => '',
            'value3'                => '',
            'value4'                => '',
            'uniqueId'              => 'ozkYrfNw9j',
            'woocomerce_product_id' => '',
            'opt_default'           => '0',
        ];
        $elit     = (object) wp_parse_args( $elit, $defaults );
        ob_start();
        ?>
		<div class="row m-0 selopt3 col-md-12 col-xs-12" style="margin-bottom:5px;padding:0px;">
					<div class="row" style="margin:0; padding: 0;">
						<div class="row p-0 m-0 mt-2 col-md-12 col-xs-12">
							<input class="666 swichoptionitem_id" type="text" value="<?php echo intval( $elit->id ); ?>" hidden>
							<div class="scc-input 123 el_1 col-xs-1 col-md-1 
																			<?php
                                                                            if ( $elit->opt_default == '1' ) {
                                                                                echo 'is-set-default';
                                                                            }
        ?>
																			" id="dropdownOpt" style="padding:0px;">
								<label class="scc-elm-num-lbl"><?php echo intval( $count ); ?></label>
							</div>
							<div class="col-md-6 col-xs-6 el_2" style="padding: 0px 5px 0px 1px;">
								<input type="text" onkeyup="changeNameElementItem(this)" class="input_pad inputoption scc-input" style="width:100%;" value="<?php echo stripslashes( wp_kses( $elit->name, SCC_ALLOWTAGS ) ); ?>" placeholder="Product or service name">
							</div>
							<div class="col-md-4 d-flex scc-input-icon scc-input" style="padding:0px;">
								<span class="input-group-text"><?php echo df_scc_get_currency_symbol_by_currency_code( $this->df_scc_form_currency ); ?></span>
								<input type="number" onchange="changePriceElementItem(this)" onkeyup="changePriceElementItem(this)" class="input_pad inputoption_2" style="width:100%;text-align:center;height:35px;" placeholder="Price" value="<?php echo floatval( $elit->price ); ?>">
							</div>
							<div class="col-md-1 col-xs-1" style="padding-left: 0;">
								<button onclick="removeSwitchOptionDropdown(this)" class="deleteBackendElmnt"><i class="fa fa-trash"></i></button>
							</div>
						</div>
						<!-- < -->
					</div>
				</div>
		<?php
        $html = ob_get_clean();

        return $html;
    }
    public function renderCommentBoxSetupBody2( $el, $conditionsBySet ) {
        if ( $this->is_from_ajax ) {
            $el->value1 = 'default';
        }
        $defaults = [
            'orden'                         => 0,
            'titleElement'                  => 'Title',
            'type'                          => '',
            'subsection_id'                 => 0,
            'value1'                        => 'default',
            'value4'                        => null,
            'value3'                        => null,
            'value2'                        => '',
            'length'                        => '',
            'uniqueId'                      => '',
            'mandatory'                     => 0,
            'showTitlePdf'                  => 0,
            'titleColumnDesktop'            => '4',
            'titleColumnMobile'             => '12',
            'showPriceHint'                 => 0,
            'displayFrontend'               => 1,
            'displayDetailList'             => 1,
            'subsection_id'                 => 0,
            'element_woocomerce_product_id' => 0,
            'elementitems'                  => [
                (object) [
                    'id'                    => isset( $el->elementItem_id ) ? $el->elementItem_id : null,
                    'order'                 => '0',
                    'name'                  => 'Name',
                    'price'                 => '10',
                    'description'           => 'Description',
                    'value1'                => '',
                    'value2'                => '',
                    'value3'                => '',
                    'value4'                => '',
                    'uniqueId'              => 'ozkYrfNw9j',
                    'woocomerce_product_id' => '',
                    'opt_default'           => '0',
                    'element_id'            => '0',
                ],
            ],
            'conditions'                    => [ 1 => [] ],
        ];
        $el       = (object) meks_wp_parse_args( $el, $defaults );
        ob_start();
        ?>
		<div class="scc-element-content" data-element-setup-type="<?php echo esc_attr( $el->type ); ?>" value="selectoption" style="
		<?php
        if ( ! $this->is_from_ajax ) {
            echo 'display:none;';
        }
        ?>
		 height:auto;">
			<div class="slider-setup-body" style="border:0px none!">
													<!-- CONTENIDO DE CADA ELEMENTO -->
													<!-- ELEMENT -->
													<label class="form-label fw-bold">Title</label>
													<div class="input-group mb-3">
													<input type="text" class="input_pad inputoption_title" onkeyup="clickedTitleElement(this)" style="height:35px;width:100%;" placeholder="Title" value="<?php echo stripslashes( htmlentities( $el->titleElement ) ); ?>">
													</div>
													<div class="row g-3 edit-field">
														<div class="col">
															<label class="form-label fw-bold use-tooltip" title="Define the size of the comment input box height" style="width: 100%;">Height</label>
															<input onkeyup="changeValue2(this)" onchange="changeValue2(this)" type="number" class="input_pad inputoption_2" style="text-align:center;width:80px;height:35px" placeholder="3" value="<?php echo esc_attr( $el->value2 ); ?>">
													</div>
													</div>
													<div class="row m-0 mt-2 col-xs-12 col-md-12" style="padding: 0;">
													<label class="form-label fw-bold" style="width: 100%;padding: 0;">Placeholder</label>
															<div class="col-xs-12 col-md-12" style="padding:0px;">
																<textarea onkeyup="changeValue3(this)" class="input_pad inputoption_desc" style="width:100%;max-width: 100%;" rows="4" placeholder=""><?php echo esc_attr( $el->value3 ); ?></textarea>
															</div>
														</div>
														</div>
													<div class="scc-element-content" value="selectoption" style="display:none; height:auto">
													<div class="scc-new-accordion-container">
														<div class="styled-accordion">
															<div class="scc-title scc_accordion_advance" onclick="showAdvanceoptions(this)">
																<i class="material-icons">keyboard_arrow_right</i><span>Advanced Options</span>
															</div>
																<?php echo $this->renderAdvancedOptions( $el ); ?>
														</div>
														<div class="styled-accordion">
															<div class="scc-title scc_accordion_conditional ">
																<i class="material-icons">keyboard_arrow_right</i><span style="padding-right:20px;" data-element-tooltip-type="conditional-logic-tt" data-bs-original-title="" title="">Conditional Logic </span>
															</div>
															<?php echo $this->renderAdvancedOptions( $el ); ?>
														</div>
													   </div>
													</div>
													<!-- ADVANCE -->
												</div>
		<?php
        $html = ob_get_clean();

        return $html;
    }
	public function get_element_type_v2( $type ) {
        return SCC_ELEMENT_TYPES[ $type ];
    }
	public function renderDate( $el, $conditionsBySet ) {
        $defaults = [
            'orden'                         => '0',
            'titleElement'                  => 'Title',
            'type'                          => 'date',
            'value1'                        => 'single_date',
            'value2'                        => '',
            'value3'                        => '',
            'value4'                        => '',
            'value5'                        => '1',
            'length'                        => '12asd',
            'uniqueId'                      => '',
            'mandatory'                     => '0',
            'showTitlePdf'                  => '0',
            'titleColumnDesktop'            => '4',
            'titleColumnMobile'             => '12',
            'showPriceHint'                 => '0',
            'displayFrontend'               => '0',
            'displayDetailList'             => '0',
            'showInputBoxSlider'            => '0',
            'showSavingsSlider'             => '0',
            'subsection_id'                 => '0',
            'element_woocomerce_product_id' => null,
            'tooltiptext'                   => null,
            'conditions'                    => [],
            'elementitems'                  => [],
        ];
        $el              = (object) meks_wp_parse_args( $el, $defaults );

        if ( !isset( $el->type_v2 ) ) {
            $el->type_v2 = $this->get_element_type_v2( $el->type );
        }
        $value6_default  = DF_SCC_ELEMENT_DEFAULT_VALUES[$el->type_v2]['advanced']['value6'];
        $scc_date_config = wp_parse_args(
            json_decode( wp_unslash( !empty( $el->value6 ) ? $el->value6 : '' ), true ),
            $value6_default
        );
        ob_start();
        ?>
	<div class="scc-element-content" data-element-setup-type="date" value="selectoption" style="
		<?php
        if ( ! $this->is_from_ajax ) {
            echo 'display:none;';
        }
		?>
		 height:auto;">
		<div class="slider-setup-body date-setup-body" style="border:0px none!">
			<!-- CONTENIDO DE CADA ELEMENTO -->
			<!-- ELEMENT -->
			<label class="form-label fw-bold">Title</label>
			<?php 
				echo $this->renderElementTitle( $el ); ?>
			<div class="col-12 mb-3 edit-field" style=" width: 100%;">
				<div class="col scc-input-icon col-md-4 scc-pm-0">
					<label class="form-label fw-bold">Date Type</label> <i style="margin-top:-10px;" class="material-icons-outlined v-align-middle" data-element-tooltip-type="date-picker-types-tt">
					 <span
							class="scc-icn-wrapper"><?php echo scc_get_kses_extended_ruleset( $this->scc_icons['help-circle'] ); ?></span>
					</i>
					<select data-date-structure class="form-select w-100" onchange="changeValue1(this)">
						<option value="single_date" <?php selected( $el->value1, 'single_date' ); ?>>Single Date Picker</option>
						<option value="date_range" <?php selected( $el->value1, 'date_range' ); ?>>Date Range</option>
					</select>
				</div>
			</div>
			<div class="col-12 mb-3 pricing-mode-dd edit-field <?php echo ( $el->value1 !== 'date_range' ) ? 'scc-d-none' : ''; ?>" style=" width: 100%;">
				<div class="col scc-input-icon col-md-4 scc-pm-0">
					<label class="form-label fw-bold">Pricing Mode</label> <i style="margin-top:-10px;" class="material-icons-outlined v-align-middle" data-element-tooltip-type="date-picker-pricing-mode-tt">
						<span
							class="scc-icn-wrapper"><?php echo scc_get_kses_extended_ruleset( $this->scc_icons['help-circle'] ); ?></span>
					</i>
					<?php
                    $scc_date_range_pricing_structure = isset( $scc_date_config['date_range_pricing_structure'] ) && !empty( $scc_date_config['date_range_pricing_structure'] )
                        ? $scc_date_config['date_range_pricing_structure']
                        : 'unit_price_only';
        ?>
					<input data-date-structure class="form-select w-100 pricing-structure-dd scc-datepicker-config" value="<?php echo esc_attr( $scc_date_range_pricing_structure ); ?>" onchange="changeValue6(this)">
				</div>
			</div>

			<!--WooCommerce-->
			<?php if ( isset( $this->woo_commerce_products ) ) { ?>
			<div class="row mb-3 edit-field">
				<div class="text-scc-col d-flex">
					<div class="col-md-12" style="margin-top:10px;padding:0px;">
						<div class="scc-col-xs-12 scc-col-md-12" style="padding:0px;background: #f8f9ff;height: 35px;">
							<img class="scc-woo-logo"
								src="<?php echo esc_url_raw( SCC_ASSETS_URL . '/images/logo-woocommerce.svg' ); ?>"
								title="Pick an item from your WooCommerce products to link to.">
							<i style="margin-top:-10px;" class="material-icons-outlined v-align-middle" data-element-tooltip-type="woocommerce-attach-product">
							<span
								class="scc-icn-wrapper"><?php echo scc_get_kses_extended_ruleset( $this->scc_icons['help-circle'] ); ?></span>
							</i>
						</div>
						<div class="woo-product-dd scc-col-xs-12 scc-col-md-12" style="padding:0px;">
							<select class="scc_woo_commerce_product_id scc-woo-commerce-product-selector"
								data-selected-value=<?php echo intval( $el->element_woocomerce_product_id ); ?>
								data-target="elements_added"
								onchange="attachProductId(this, <?php echo intval( $el->id ); ?>, '<?php echo esc_attr( $el->type ); ?>')">
								<option style="font-size: 10px" value=0>Select a product..</option>
								<?php echo $this->render_woocommerce_product_options( $el->element_woocomerce_product_id ); ?>
							</select>
						</div>
					</div>
				</div>
			</div>
			<?php } ?>

			<div class="mb-3 edit-field scc-price-per-date scc-pm-0 <?php echo ( $el->value1 == 'date_range' && $scc_date_config['date_range_pricing_structure'] !== 'quantity_mod' ) ? '' : 'scc-d-none'; ?>">
				<div class="col scc-input-icon col-md-4 scc-pm-0">
					<label class="form-label fw-bold" style="width: 100%;">Cost (per day)</label>
					<div class="scc-flex-inline">
						<span class="input-group-text"
							style="float: left;"><?php echo df_scc_get_currency_symbol_by_currency_code( $this->df_scc_form_currency ); ?></span>
						<input onkeyup="changeValue4(this)" onchange="changeValue4(this),checkBannerNoticeWithDebounce( true )" type="number"
							class="input_pad check-zero-amount-input inputoption_2" data-currency-input=1 style="text-align:center;width:92% !important;height:35px;margin-right:0 !important"
							placeholder="Price" value="<?php echo esc_attr( $el->value4 ); ?>" style="margin: 0;">
					</div>
				</div>
			</div>

		</div>
		<div class="scc-element-content" value="selectoption" style="<?php
        if ( ! $this->is_from_ajax ) {
            echo 'display:none;';
        }
        ?> height:auto">
			<div class="scc-new-accordion-container">
				<div class="styled-accordion">
					<div class="scc-title scc_accordion_advance" onclick="showAdvanceDateoptions(this)">
						<i class="material-icons"> 
							<span
								class="scc-icn-wrapper"><?php echo scc_get_kses_extended_ruleset( $this->scc_icons['chevron-right'] ); ?></span>
						</i>
						<span>Advanced Options</span>
					</div>
					<?php echo $this->renderAdvancedOptions( $el ); ?>
				</div>
                <div class="styled-accordion">
					<div class="scc-title scc_accordion_conditional ">
							<i class="material-icons">keyboard_arrow_right</i>
							<span style="padding-right:20px;" data-element-tooltip-type="conditional-logic-tt" data-bs-original-title="" title="">Conditional Logic </span>
					</div>
				</div>
			</div>
		</div>
		<!-- ADVANCE -->
		<?php // echo $this->scc_render_element_saving( $el ); ?>
	</div>
	<?php
        $html = ob_get_clean();

        return $html;
    }

	//this function render the element title input
    public function renderElementTitle( $el ) {
        ob_start();
        ?>
		<div class="input-group d-inline-flex scc-input-icon scc-title-icon mb-3">
			<?php echo $this->renderTitleIconButton( $el, 'element' ); ?>
			<input type="text"
				   name="scc-element-title-field"
				   class="scc-element-title-field" 
			       onkeyup="clickedTitleElement(this)"
				   placeholder="Title"
				   value="<?php echo stripslashes( htmlentities( $el->titleElement ) ); ?>"
			>
		</div>
		<?php
        $html = ob_get_clean();

        return $html;
    }

	//render title icon function. el is the element object and elementType can be 'element' or 'element-item'
    public function renderTitleIconButton( $el, $elementType ) {
		$sccIconConfig = wp_parse_args(
            json_decode( wp_unslash( !empty( $el->titleIconConfigArray ) ? $el->titleIconConfigArray : '' ), true ),
            [
                'type'       => 'icon-font',
                'icon_html'	 => '',
                'icon_class' => 'material-icons',
                'icon_text'	 => 'wallpaper',
                'image_icon' => '',
                'position'   => '',
                'width'      => '',
            ]
        );
        ob_start();
		?>
		<div class="scc-icon-picker" data-type="<?php echo esc_attr( $sccIconConfig['type'] ); ?>" data-position="<?php echo esc_attr( $sccIconConfig['position'] ); ?>" data-width="<?php echo esc_attr( $sccIconConfig['width'] ); ?>">
			<input type="hidden" name="icon" value="">
			<button onclick="sccShowTitleIconOptions(this, '<?php echo esc_attr( $elementType ); ?>')" class="input-group-text scc-icon-picker-button" style="height: 45px;border-radius: 6px 0px 0px 6px">
				<?php if ( $sccIconConfig['type'] === 'img' && !empty( $sccIconConfig['image_icon'] ) ) { ?>
				<img class="scc-selected-icon scc-image-icon" src="<?php echo $sccIconConfig['image_icon']; ?>" style="width:22px" alt="">
				<span class="scc-selected-icon  scc-font-icon" style="display:none;">
					<i class="<?php echo esc_attr( $sccIconConfig['icon_class'] ); ?>" ><?php echo esc_attr( $sccIconConfig['icon_text'] ); ?></i>
				</span>
				<?php } else { ?>
				<img class="scc-selected-icon scc-image-icon" src="" style="width:22px; display:none" alt="">
				<span class="scc-selected-icon  scc-font-icon">
					<i class="<?php echo esc_attr( $sccIconConfig['icon_class'] ); ?>" ><?php echo esc_attr( $sccIconConfig['icon_text'] ); ?></i>
				</span>	
				<?php } ?>
			</button>

			<div class="scc-icon-picker-menu">
				<div class="scc-icon-picker-search">
					<input class="scc-search-input" type="text" placeholder="Search icons...">
					<button class="btn" style="float:right;width:10%;box-shadow: none;" onclick="removeElementTitleIcon(this, '<?php echo esc_attr( $elementType ); ?>')">
						<span class="scc-icn-wrapper" style="margin-right:4px;"><i class="material-icons-outlined scc-conditional-delete-button">delete</i></span>
					</button>
				</div>
				
				<div class="scc-icon-picker-filters">
					<button class="scc-icon-picker-filter active" data-filter="scc-fontawesome">Font Awesome</button>
					<button class="scc-icon-picker-filter" data-filter="scc-material-icon">Material Icons</button>
					<button class="scc-icon-picker-filter show-all" data-filter="scc-all">Show all</button>
					<button id="scc-upload-el-<?php echo $el->id; ?>" data-element-id="<?php echo $el->id; ?>" onclick="uploadElementTitleIcon(this,'<?php echo esc_attr( $elementType ); ?>')" class="scc-icon-picker-upload-button d-inline-flex align-items-center" style="border-radius:6px">
								<span class="scc-icn-wrapper" style="margin-right:4px;"><?php echo scc_get_kses_extended_ruleset( $this->scc_icons['upload-cloud'] ); ?></span>	
							 Upload </button>
					<button style="background:white; border:none;">
						<i class="material-icons-outlined v-align-middle" data-element-tooltip-type="upload-icon-tt">
						<span class="scc-icn-wrapper"><?php echo scc_get_kses_extended_ruleset( $this->scc_icons['help-circle'] ); ?></span>
					</button>
							 
				</i>
				</div>
				<ul class="scc-icon-list">

				</ul>
				<div class="text-center">
					<span class="scc-loading-msg" style="display:none; padding-top:5px;">
						<i class="scc-btn-spinner scc-save-btn-spinner"></i> Loading...
					</span>
				</div>
			</div>
		</div>
		<?php
        $html = ob_get_clean();

        return $html;
    }

	public function renderConditionalLogic( $el, $conditionsBySet ) {  
		ob_start();
        ?>
		<div class="scc-content" style="display: none;">
		<div class="scc-transition scc-hidden">
			<?php
                                                    foreach ( $conditionsBySet as $key => $conditionCollection ) {
                                                        ?>
			<?php if ( $key > 1 ) { ?>
			<div class="scc-or-label-cond">OR</div>
			<?php } ?>
			<div class="condition-container clearfix" data-condition-set=<?php echo intval( $key ); ?>>
				<?php
                                                            foreach ( $conditionCollection as $index => $condition ) {
                                                                $is_checkbox_condition         = $condition->elementitem_id && ! $condition->condition_element_id;
                                                                $is_checkbox_with_qtn_selector = $is_checkbox_condition && !in_array( $condition->op, [ 'chec', 'unc' ] );
                                                                $comparisons                   = (
                                                                    (
                                                                        $condition->op === 'eq' ||
                                                                        $condition->op === 'ne' ||
                                                                        $condition->op === 'any'
                                                                    ) &&
                                                                    ( isset( $condition->element_condition ) ) &&
                                                                    ! (
                                                                        $condition->element_condition->type === 'slider' ||
                                                                        $condition->element_condition->type === 'distance' ||
                                                                        $condition->element_condition->type === 'quantity box' ||
                                                                        $condition->element_condition->type === 'calctotal' ||
                                                                        $condition->element_condition->type === 'date'
                                                                    )
                                                                );

                                                                if ( $comparisons ) {
                                                                    echo $this->get_conditional_logic_second_step( $condition, $index );
                                                                }
                                                                ?>
				
				<?php

                                                                if ( $is_checkbox_with_qtn_selector ) {
                                                                    echo $this->get_conditional_logic_second_step( $condition, $index );
                                                                }
                                                                ?>

																<?php

                                                                if ( $is_checkbox_condition && ! $is_checkbox_with_qtn_selector ) {
                                                                    echo $this->get_conditional_logic_second_step( $condition, $index );
                                                                }
                                                                ?>

				<?php

                                                                if ( $condition->condition_element_id || isset( $condition->is_total_cond ) && $condition->is_total_cond ) {
                                                                    if ( in_array( $condition->element_condition->type, [ 'slider', 'quantity box', 'date', 'distance', 'calctotal' ] ) ) {
                                                                        ?>
																		<!-- Cond 1 -->
				<div class="row col-xs-12 col-md-12 conditional-selection"
					style="padding: 0px; margin-bottom: 5px;">
					<input type="text" class="id_conditional_item"
						value="<?php echo intval( $condition->id ); ?>" hidden>
					<div class="col-xs-2 col-md-2" style="padding:0px;background: #DCF1FD;max-width:70px">
						<span class="scc_label"
							style="text-align:center;padding-right:10px;padding-left:5px;margin-top:5px;"><?php echo $index >= 1 ? 'And' : 'Show if'; ?></span>
					</div>
					<div class="col-xs-10 col-md-10" style="padding:0px;">
						<div class="conditional-selection-steps col-xs-12 col-md-12"
							style="padding:0px;">
							<div class="item_conditionals" data-value='{"cond":"<?php echo esc_attr( $condition->id ); ?>","operator": "<?php echo esc_attr( $condition->op ); ?>","itemId":"<?php echo intval( $condition->elementitem_id ); ?>", "value": "<?php echo esc_attr( $condition->value ); ?>"}'>
								<input type="hidden" value="<?php echo intval( ( $condition->condition_element_id === null ) ? $condition->elementitem_id : $condition->condition_element_id  ); ?>" class="first-conditional-step scc-ts-search">
								<input type="hidden" value="<?php echo esc_attr( $condition->op ); ?>" class="second-conditional-step">
								<input type="hidden" value="<?php echo intval( $condition->elementitem_id ); ?>" class="third-conditional-step">

								<?php
                                $scc_is_date_type                                               = $condition->element_condition->type == 'date';
                                                                        $scc_condition_operator = 'quantity';

                                                                        /* if( $condition->op == 'eq' || $condition->op == 'ne' || $condition->op == 'any' ){
                                                                            $scc_condition_operator = 'quantity';
                                                                        }elseif */
                                                                        $scc_number_class = $scc_is_date_type ? 'conditional-number-value scc-d-none' : 'conditional-number-value';
                                                                        $scc_date_class   = $scc_is_date_type ? 'scc-conditional-date-value' : 'scc-conditional-date-value scc-d-none';
                                                                        $scc_date_value   = $this->scc_is_valid_date( $condition->value ) ? $condition->value : '';
                                                                        ?>
								<input value="<?php echo esc_attr( $condition->value ); ?>" type="number" placeholder="Number" class="<?php echo $scc_number_class; ?>" min="0">
								<input value="<?php echo esc_attr( $scc_date_value ); ?>" type="date" class="<?php echo $scc_date_class; ?>">

								<div class="btn-group scc-cond-btn-group" style="margin-left: 10px;">
									<button onclick="addConditionElement(this)"
										class="btn btn-cond-saved">Saved</button>
									<button onclick="deleteCondition(this)"
										class="btn btn-transparent"><i class="material-icons-outlined scc-conditional-delete-button">delete</i></button>
								</div>
							</div>
						</div>
					</div>
				</div>
				<?php
                                                                    }
                                                                }
                                                            }
                                                        ?>
				<div class="row col-xs-12 col-md-12 conditional-selection  
															<?php
                                                        if ( count( $conditionCollection ) ) {
                                                            echo 'hidden';
                                                        }
                                                        ?>
															" style="padding: 0px; margin-bottom: 5px;">
					<div class="col-xs-2 col-md-2" style="padding:0px;background: #DCF1FD;max-width:70px;">
						<span class="scc_label"
							style="text-align:center;padding-right:10px;padding-left:5px;margin-top:5px;"><?php echo empty( count( $el->conditions ) ) ? 'Show if' : 'And'; ?></span>
					</div>
					<div class="col-xs-10 col-md-10" style="padding:0px;">
						<div class="conditional-selection-steps col-xs-12 col-md-12"
							style="padding:0px;">
							<div class="item_conditionals">
								<input type="hidden" class="first-conditional-step scc-ts-search">
								<input type="hidden" class="second-conditional-step">
								<input type="hidden" class="third-conditional-step">
								<input type="number" placeholder="Number"
									class="conditional-number-value scc-d-none" min="0">
								<input type="date" class="scc-conditional-date-value scc-d-none">
								<div class="btn-group scc-cond-btn-group scc-d-none" style="margin-left: 10px;">
									<button onclick="addConditionElement(this)"
										class="btn btn-cond-save">Save</button>
									<button onclick="deleteCondition(this)"
										class="btn btn-transparent btn-delcondition"
										style="display: none;"><i class="material-icons-outlined scc-conditional-delete-button">delete</i></button>
								</div>
							</div>
						</div>
					</div>
				</div>
				<button
					onclick="(($this) => {jQuery($this).prev().removeClass('hidden'); jQuery($this).addClass('hidden')})(this)"
					class="btn btn-addcondition cond-add-btn 
															<?php
                                                        if ( empty( count( $el->conditions ) ) ) {
                                                            echo 'hidden';
                                                        }
                                                        ?>
															">+ AND</button>
			</div>
			<?php } ?>
			<div style="width: 28%">
				<button
					class="btn btn-primary btn-cond-or <?php echo empty( count( $el->conditions ) ) ? 'hidden' : ''; ?>">+
					OR</button>
			</div>
		</div>
		<div class="scc-refresh-new-elements scc-d-none">
			<?php
                $msg  = 'New elements have been added, <a href="#" class="scc-page-refresh" onclick="event.preventDefault();location.reload()">refresh</a> to update the conditional logic.';
        $cond         = true;
        echo df_scc_render_alert( $cond, $msg, 'mt-3' );
        ?>
		</div>
		
	</div>
	<?php
        $html = ob_get_clean();

        return $html;
    }

    public function renderQuantityBoxSetupBody2( $el, $conditionsBySet ) {
        if ( $this->is_from_ajax ) {
            $el->value1 = 'default';
        }
        $defaults = [
            'orden'                         => 0,
            'titleElement'                  => 'Title',
            'type'                          => '',
            'subsection_id'                 => 0,
            'value1'                        => 'default',
            'value4'                        => null,
            'value3'                        => null,
            'value2'                        => '',
            'length'                        => '',
            'uniqueId'                      => '',
            'mandatory'                     => 0,
            'showTitlePdf'                  => 0,
            'titleColumnDesktop'            => '4',
            'titleColumnMobile'             => '12',
            'showPriceHint'                 => 0,
            'displayFrontend'               => 1,
            'displayDetailList'             => 1,
            'subsection_id'                 => 0,
            'element_woocomerce_product_id' => 0,
            'elementitems'                  => [
                (object) [
                    'id'                    => isset( $el->elementItem_id ) ? $el->elementItem_id : null,
                    'order'                 => '0',
                    'name'                  => 'Name',
                    'price'                 => '10',
                    'description'           => 'Description',
                    'value1'                => '',
                    'value2'                => '',
                    'value3'                => '',
                    'value4'                => '',
                    'uniqueId'              => 'ozkYrfNw9j',
                    'woocomerce_product_id' => '',
                    'opt_default'           => '0',
                    'element_id'            => '0',
                ],
            ],
            'conditions'                    => [ 1 => [] ],
        ];
        $el       = (object) meks_wp_parse_args( $el, $defaults );
        ob_start();
        ?>
		<div class="scc-element-content" data-element-setup-type="<?php echo esc_attr( $el->type ); ?>" value="selectoption" style="
		<?php
        if ( ! $this->is_from_ajax ) {
            echo 'display:none;';
        }
        ?>
		 height:auto;">
			<div class="slider-setup-body">
													<!-- CONTENIDO DE CADA ELEMENTO -->
													<!-- ELEMENT -->
													<label class="form-label fw-bold">Title</label>
													<div class="input-group mb-3">
														<input type="text" class="form-control" onkeyup="clickedTitleElement(this)" value="<?php echo stripslashes( htmlentities( $el->titleElement ) ); ?>">
													</div>
													<div class="row g-3 edit-field scc-quantity-input">
														<div class="col col-md-4">
															<label class="form-label fw-bold">Input Box Style</label>
															 <select onchange="changeValue1(this)" type="select-one" name="quantity-input--style-selection" style="width:100%;max-width:100%;float:left;height:35px;">'
															<option value="default" selected="">Default</option>
															<option value="compact">Compact</option>'
															</select>'
														</div>
													
													<div class="row g-3 edit-field scc-quantity-input" style="margin-top: 3%;">
														<!-- <div class="col">
															<label class="form-label fw-bold" style="width: 100%;">Price</label>
																<input onkeyup="changeValue2(this)" onchange="changeValue2(this)" type="number" class="input_pad inputoption_2" style="text-align:center;width:80px;height:35px" placeholder="Price" value="<?php echo esc_attr( $el->value2 ); ?>">
														</div> -->
														<div class="col-md-4 d-flex scc-input-icon scc-input">
															<span class="input-group-text"><?php echo df_scc_get_currency_symbol_by_currency_code( $this->df_scc_form_currency ); ?></span>
															<input type="number" onchange="changeValue2(this)" onkeyup="changeValue2(this)" class="ssc-margin-0 input_pad inputoption_2" style="width:100%;text-align:center;height:35px;" placeholder="Price" value="<?php echo isset($el->price)?floatval( $el->price ): ''; ?>">
														</div>
													</div>
													</div>
													</div>
													<div class="scc-element-content" value="selectoption" style="display:none; height:auto">
													<div class="scc-new-accordion-container">
														<div class="styled-accordion">
															<div class="scc-title scc_accordion_advance" onclick="showAdvanceoptions(this)">
																<i class="material-icons">keyboard_arrow_right</i><span>Advanced Options</span>
															</div>
																<?php echo $this->renderAdvancedOptions( $el ); ?>
														</div>
														<div class="styled-accordion">
															<div class="scc-title scc_accordion_conditional ">
																<i class="material-icons">keyboard_arrow_right</i><span style="padding-right:20px;" data-element-tooltip-type="conditional-logic-tt" data-bs-original-title="" title="">Conditional Logic </span>
															</div>
															 <div class="scc-content" style="display: none;">
																<div class="scc-transition">
																	<?php
                                                                    foreach ( $conditionsBySet as $key => $conditionCollection ) {
                                                                        ?>
																		<?php if ( $key > 1 ) { ?>
																			<div style="margin: 10px 0px 10px -10px;">OR</div>
																		<?php } ?>
																		<div class="condition-container clearfix" data-condition-set=<?php echo intval( $key ); ?>>
																			<?php
                                                                            foreach ( $conditionCollection as $index => $condition ) {
                                                                                if ( ( $condition->op == 'eq' || $condition->op == 'ne' || $condition->op == 'any' ) && ! ( $condition->element_condition->type == 'slider' || $condition->element_condition->type == 'quantity box' ) ) {
                                                                                    ?>
																					<div class="row col-xs-12 col-md-12 conditional-selection" style="padding: 0px; margin-bottom: 5px;">
																						<input type="text" class="id_conditional_item" value="<?php echo intval( $condition->id ); ?>" hidden>
																						<div class="col-xs-1 col-md-1" style="padding:0px;height:35px;background: #DCF1FD;">
																							<span class="scc_label" style="text-align:right;padding-right:10px;margin-top:5px;"><?php echo $index >= 1 ? 'And' : 'Show if'; ?></span>
																						</div>
																						<div class="col-xs-11 col-md-11" style="padding:0px;">
																							<div class="conditional-selection-steps col-xs-12 col-md-12" style="padding:0px;">
																								<div class="item_conditionals">
																									<select class="first-conditional-step col-3" style="height: 35px;">
																										<option style="font-size: 10px" value="0">Select one</option>
																										<option value="<?php echo intval( $condition->condition_element_id ); ?>" data-type="<?php echo esc_attr( $condition->element_condition->type ); ?>" selected><?php echo esc_attr( $condition->element_condition->titleElement ); ?></option>
																									</select>
																									<select class="second-conditional-step col-3" style="height: 35px;">
																										<option value="0" style="font-size: 10px">Select one</option>
																										<option value="eq" 
																										<?php
                                                                                                        if ( $condition->op == 'eq' ) {
                                                                                                            echo 'selected';
                                                                                                        }
                                                                                    ?>
																										>Equal To</option>
																										<option value="ne" 
																										<?php
                                                                                    if ( $condition->op == 'ne' ) {
                                                                                        echo 'selected';
                                                                                    }
                                                                                    ?>
																										>Not Equal To</option>
																										<option value="any" 
																										<?php
                                                                                    if ( $condition->op == 'any' ) {
                                                                                        echo 'selected';
                                                                                    }
                                                                                    ?>
																										>Any</option>
																									</select>
																									<select class="third-conditional-step col-3" style="height: 35px;
																									<?php
                                                                                                    if ( $condition->op == 'any' ) {
                                                                                                        echo 'display:none';
                                                                                                    }
                                                                                    ?>
																									 ">
																										<option value="0" style="font-size: 10px">Select one</option>
																										<option value="<?php echo intval( $condition->elementitem_id ); ?>" selected><?php echo esc_attr( $condition->elementitem_name->name ); ?></option>
																									</select>
																									<input value="<?php echo esc_attr( $condition->value ); ?>" type="number" placeholder="Number" style="height: 35px;display:none" class="conditional-number-value col-3" min="0">
																									<div class="btn-group" style="margin-left: 10px;">
																										<button onclick="addConditionElement(this)" class="btn btn-cond-save">Save</button>
																										<button onclick="deleteCondition(this)" class="btn btn-danger">x</button>
																									</div>
																								</div>
																							</div>
																						</div>
																					</div>
																					<?php
                                                                                }

                                                                                if ( $condition->elementitem_id && ! $condition->condition_element_id ) {
                                                                                    ?>
																					<div class="row col-xs-12 col-md-12 conditional-selection" style="padding: 0px; margin-bottom: 5px;">
																						<input type="text" class="id_conditional_item" value="<?php echo intval( $condition->id ); ?>" hidden>
																						<div class="col-xs-1 col-md-1" style="padding:0px;height:35px;background: #DCF1FD;">
																							<span class="scc_label" style="text-align:right;padding-right:10px;margin-top:5px;"><?php echo $index >= 1 ? 'And' : 'Show if'; ?></span>
																						</div>
																						<div class="col-xs-11 col-md-11" style="padding:0px;">
																							<div class="conditional-selection-steps col-xs-12 col-md-12" style="padding:0px;">
																								<div class="item_conditionals">
																									<select class="first-conditional-step col-3" style="height: 35px;">
																										<option style="font-size: 10px" value="0">Select one</option>
																										<option value="<?php echo intval( $condition->elementitem_id ); ?>" data-type="checkbox" selected><?php echo esc_attr( $condition->elementitem_name->name ); ?></option>
																									</select>
																									<select class="second-conditional-step col-3" style="height: 35px;">
																										<option value="0" style="font-size: 10px">Select one</option>
																										<option value="chec" 
																										<?php
                                                                                                        if ( $condition->op == 'chec' ) {
                                                                                                            echo 'selected';
                                                                                                        }
                                                                                    ?>
																										>Checked</option>
																										<option value="unc" 
																										<?php
                                                                                    if ( $condition->op == 'unc' ) {
                                                                                        echo 'selected';
                                                                                    }
                                                                                    ?>
																										>Unchecked</option>
																									</select>
																									<select class="third-conditional-step col-3" style="height: 35px;display:none">
																										<option value="0" style="font-size: 10px">Select one</option>
																										<option value="<?php echo intval( $condition->elementitem_id ); ?>" selected><?php echo esc_attr( $condition->elementitem_name->name ); ?></option>
																									</select>
																									<input value="<?php echo esc_attr( $condition->value ); ?>" type="number" placeholder="Number" style="height: 35px;display:none" class="conditional-number-value col-3" min="0">
																									<div class="btn-group" style="margin-left: 10px;">
																										<button onclick="addConditionElement(this)" class="btn btn-cond-save">Save</button>
																										<button onclick="deleteCondition(this)" class="btn btn-danger">x</button>
																									</div>
																								</div>
																							</div>
																						</div>
																					</div>
																					<?php
                                                                                }

                                                                                if ( $condition->condition_element_id ) {
                                                                                    if ( $condition->element_condition->type == 'slider' || $condition->element_condition->type == 'quantity box' ) {
                                                                                        ?>
																						<div class="row col-xs-12 col-md-12 conditional-selection" style="padding: 0px; margin-bottom: 5px;">
																							<input type="text" class="id_conditional_item" value="<?php echo intval( $condition->id ); ?>" hidden>
																							<div class="col-xs-1 col-md-1" style="padding:0px;height:35px;background: #DCF1FD;">
																								<span class="scc_label" style="text-align:right;padding-right:10px;margin-top:5px;"><?php echo $index >= 1 ? 'And' : 'Show if'; ?></span>
																							</div>
																							<div class="col-xs-11 col-md-11" style="padding:0px;">
																								<div class="conditional-selection-steps col-xs-12 col-md-12" style="padding:0px;">
																									<div class="item_conditionals">
																										<select class="first-conditional-step col-3" style="height: 35px;">
																											<option style="font-size: 10px" value="0">Select one</option>
																											<option value="<?php echo intval( $condition->condition_element_id ); ?>" data-type="<?php echo esc_attr( $condition->element_condition->type ); ?>" selected><?php echo esc_attr( $condition->element_condition->titleElement ); ?></option>
																										</select>
																										<select class="second-conditional-step col-3" style="height: 35px;">
																											<option value="0" style="font-size: 10px">Select one</option>
																											<option value="eq" 
																											<?php
                                                                                                            if ( $condition->op == 'eq' ) {
                                                                                                                echo 'selected';
                                                                                                            }
                                                                                        ?>
																											>Equal To</option>
																											<option value="ne" 
																											<?php
                                                                                        if ( $condition->op == 'ne' ) {
                                                                                            echo 'selected';
                                                                                        }
                                                                                        ?>
																											>Not Equal To</option>
																											<option value="gr" 
																											<?php
                                                                                        if ( $condition->op == 'gr' ) {
                                                                                            echo 'selected';
                                                                                        }
                                                                                        ?>
																											>Greater than</option>
																											<option value="les" 
																											<?php
                                                                                        if ( $condition->op == 'les' ) {
                                                                                            echo 'selected';
                                                                                        }
                                                                                        ?>
																											>Less than</option>
																										</select>
																										<select class="third-conditional-step col-3" style="height: 35px;display:none">
																											<option value="0" style="font-size: 10px">Select one</option>
																											<option value="<?php echo intval( $condition->elementitem_id ); ?>" selected><?php echo esc_attr( $condition->elementitem_name->name ); ?></option>
																										</select>
																										<input value="<?php echo esc_attr( $condition->value ); ?>" type="number" placeholder="Number" style="height: 35px;" class="conditional-number-value col-3" min="0">
																										<div class="btn-group" style="margin-left: 10px;">
																											<button onclick="addConditionElement(this)" class="btn btn-cond-save">Save</button>
																											<button onclick="deleteCondition(this)" class="btn btn-danger">x</button>
																										</div>
																									</div>
																								</div>
																							</div>
																						</div>
																						<?php
                                                                                    }
                                                                                }
                                                                            }
                                                                        ?>
																			<div class="row col-xs-12 col-md-12 conditional-selection  
																			<?php
                                                                        if ( count( $conditionCollection ) ) {
                                                                            echo 'hidden';
                                                                        }
                                                                        ?>
																			" style="padding: 0px; margin-bottom: 5px;">
																				<div class="col-xs-1 col-md-1" style="padding:0px;height:35px;background: #DCF1FD;">
																					<span class="scc_label" style="text-align:right;padding-right:10px;margin-top:5px;"><?php echo empty( count( $el->conditions ) ) ? 'Show if' : 'And'; ?></span>
																				</div>
																				<div class="col-xs-11 col-md-11" style="padding:0px;">
																					<div class="conditional-selection-steps col-xs-12 col-md-12" style="padding:0px;">
																						<div class="item_conditionals">
																							<select class="first-conditional-step col-3" style="height: 35px;">
																								<option style="font-size: 10px" value="0">Select an element</option>
																							</select>
																							<select class="second-conditional-step col-3" style="height: 35px;display:none">
																								<option value="0" style="font-size: 10px">Select one</option>
																							</select>
																							<select class="third-conditional-step col-3" style="height: 35px;display:none">
																								<option value="0" style="font-size: 10px">Select one</option>
																							</select>
																							<input type="number" placeholder="Number" style="height: 35px;display:none" class="conditional-number-value col-3" min="0">
																							<div class="btn-group" style="margin-left: 10px;display:none">
																								<button onclick="addConditionElement(this)" class="btn btn-cond-save">Save</button>
																								<button onclick="deleteCondition(this)" class="btn btn-danger btn-delcondition" style="display: none;">x</button>
																							</div>
																						</div>
																					</div>
																				</div>
																			</div>
																			<button onclick="(($this) => {jQuery($this).prev().removeClass('hidden'); jQuery($this).addClass('hidden')})(this)" class="btn btn-addcondition cond-add-btn 
																			<?php
                                                                        if ( empty( count( $el->conditions ) ) ) {
                                                                            echo 'hidden';
                                                                        }
                                                                        ?>
																			">+ AND</button>
																		</div>
																	<?php } ?>
																	<div style="width: 28%">
																		<button class="btn btn-primary btn-cond-or <?php echo empty( count( $el->conditions ) ) ? 'hidden' : ''; ?>">+ OR</button>
																	</div>
																</div>
															 </div>
														</div>
													</div>
													</div>
													<!-- ADVANCE -->
												</div>
		<?php
        $html = ob_get_clean();

        return $html;
    }
    public function renderSliderSetupBody2( $el, $conditionsBySet ) {
        if ( $this->is_from_ajax ) {
            $el->value1 = 'default';
        }
        $defaults = [
            'orden'              => 0,
            'titleElement'       => 'Title',
            'type'               => '',
            'subsection_id'      => 0,
            'value1'             => 'default',
            'value4'             => null,
            'value2'             => '',
            'mandatory'          => 0,
            'showPriceHint'      => 0,
            'titleColumnDesktop' => '4',
            'titleColumnMobile'  => '12',
            'displayFrontend'    => 1,
            'displayDetailList'  => 1,
            'showTitlePdf'       => 0,
            'elementitems'       => [
                (object) [
                    'id'                    => isset( $el->elementItem_id ) ? $el->elementItem_id : null,
                    'element_id'            => '0',
                    'opt_default'           => '0',
                    'woocomerce_product_id' => null,
                    'uniqueId'              => '8SKrlo73vP',
                    'value4'                => '',
                    'description'           => '',
                    'value1'                => '0',
                    'value3'                => '2',
                    'value2'                => '10',
                    'price'                 => '',
                ],
            ],
            'conditions'         => [],
            'showInputBoxSlider' => 00,
        ];
        $el                 = (object) wp_parse_args( $el, $defaults );
        $slider_price_title = '';
        switch ( $el->value1 ) {
            case 'default':
                $slider_price_title = 'Price Per Unit';
                break;

            case 'bulk':
                $slider_price_title = 'Price Per Unit';
                break;

            case 'sliding':
                $slider_price_title = 'Price For Range';
                break;

            case 'quantity_mod':
                $slider_price_title = '(not used for this mode)';
                break;
            default:
                // code...
                break;
        }
        ob_start();
        ?>
		<div class="scc-element-content" data-element-setup-type="slider" value="selectoption" style="
		<?php
        if ( ! $this->is_from_ajax ) {
            echo 'display:none;';
        }
        ?>
		 height:auto;">
			<div class="slider-setup-body">
													<!-- CONTENIDO DE CADA ELEMENTO -->
													<!-- ELEMENT -->
													<label class="form-label fw-bold">Title</label>
													<div class="input-group mb-3">
														<input data-element-title type="text" class="form-control" onkeyup="clickedTitleElement(this)" value="<?php echo stripslashes( htmlentities( $el->titleElement ) ); ?>">
													</div>
													<div class="col-12 mb-3 edit-field">
														<label class="form-label fw-bold">Pricing Structure</label>
														<i class="material-icons-outlined v-align-middle" data-element-tooltip-type="slider-type-<?php echo esc_attr( $el->value1 ); ?>">help_outline</i>
														<div class="col-md-4 pricing-structure">
															<select data-pricing-structure class="form-select w-100 pricing-structure-dd" onchange="changeValue1(this)">
																<option value="default" <?php selected( $el->value1, 'default' ); ?>>Unit Price + Quantity Multiplier</option>
																<option value="quantity_mod" <?php selected( $el->value1, 'quantity_mod' ); ?>>Quantity Multiplier Only</option>
																<option value="bulk" <?php selected( $el->value1, 'bulk' ); ?>>Bulk Pricing (Quantity-Based Pricing)</option>
																<option value="sliding" <?php selected( $el->value1, 'sliding' ); ?>>Sliding Scale (Quantity-Based Pricing)</option>
															</select>
														</div>
													</div>
													<div class="col-12 mb-3 edit-field" data-edit-field-type="wc_choices">
													</div>
													<div class="row g-3 price-slider-item-header ">
														<div class="col">
															<label class="form-label fw-bold">From</label>
														</div>
														<div class="col">
															<label class="form-label fw-bold">To</label>
														</div>
														<div class="col">
															<label class="form-label fw-bold"><?php echo $slider_price_title; ?></label>
														</div>
													</div>
														<?php
                                                        foreach ( $el->elementitems as $key => $item ) {
                                                            $hide_range = false;

                                                            if ( in_array( $el->value1, [ 'default', 'quantity_mod' ] ) && $key > 0 ) {
                                                                $hide_range = true;
                                                            }
                                                            ?>
															<div data-slider-range-setup class="row g-3 price-slider-item 
															<?php
                                                            if ( $hide_range ) {
                                                                echo 'd-none';
                                                            }
                                                            ?>
																">
																<input data-range-id="<?php echo intval( $item->id ); ?>" type="text" class="id_element_slider_item" value="<?php echo intval( $item->id ); ?>" hidden>
																<div class="col">
																	<input class="form-control scc-input" 
																	<?php
                                                                    if ( $key > 0 ) {
                                                                        echo 'disabled';
                                                                    }
                                                            ?>
																	type="number" min="0" value="<?php echo esc_attr( $item->value1 ); ?>">
																</div>
																<div class="col">
																	<input class="form-control scc-input" value="<?php echo esc_attr( $item->value2 ); ?>" type="number" min="1">
																</div>
																<div class="col d-inline-flex scc-input-icon">
																	<span class="input-group-text" style="height: fit-content;"><?php echo df_scc_get_currency_symbol_by_currency_code( $this->df_scc_form_currency ); ?></span>
																	<input class="form-control" type="number" min="0" 
																	<?php
                                                            if ( $key === 0 && $el->value1 == 'quantity_mod' ) {
                                                                echo 'disabled';
                                                            }
                                                            ?>
																	 value="<?php echo esc_attr( $item->value3 ); ?>">
																</div>
																<i onclick="deleteSliderItem(this)" class="material-icons-outlined range-close-btn" 
																<?php
                                                                if ( $key === 0 ) {
                                                                    echo 'disabled style="opacity: 0"';
                                                                }
                                                            ?>
																>close</i>
															</div>
														<?php } ?>
													<div class="text-start 
													<?php
                                                    if ( in_array( $el->value1, [ 'quantity_mod', 'default' ] ) || empty( $el->value1 ) ) {
                                                        echo 'd-none';
                                                    }
        ?>
													">
														<p href="#" onclick="addNewRangeSlider(this)" class="link-primary text-decoration-none" role="button">+ Price Range</p>
													</div>
													</div>
													<!-- ADVANCE -->
													<div class="scc-new-accordion-container">
														<div class="styled-accordion">
															<div class="scc-title scc_accordion_advance" onclick="showAdvanceoptions(this)">
																<i class="material-icons">keyboard_arrow_right</i><span>Advanced Options</span>
															</div>
															<?php echo $this->renderAdvancedOptions( $el ); ?>
														</div>
														<div class="styled-accordion">
															<div class="scc-title scc_accordion_conditional ">
																<i class="material-icons">keyboard_arrow_right</i><span style="padding-right:20px;" data-element-tooltip-type="conditional-logic-tt" data-bs-original-title="" title="">Conditional Logic </span>
															</div>
															<div class="scc-content" style="display: none;">
																<div class="scc-transition">
																	<?php
                        foreach ( $conditionsBySet as $key => $conditionCollection ) {
                            ?>
																		<?php if ( $key > 1 ) { ?>
																			<div style="margin: 10px 0px 10px -10px;">OR</div>
																		<?php } ?>
																		<div class="condition-container clearfix" data-condition-set=<?php echo intval( $key ); ?>>
																		   <?php
                                foreach ( $conditionCollection as $index => $condition ) {
                                    if ( ( $condition->op == 'eq' || $condition->op == 'ne' || $condition->op == 'any' ) && ! ( $condition->element_condition->type == 'slider' || $condition->element_condition->type == 'quantity box' ) ) {
                                        ?>
																					<div class="row col-xs-12 col-md-12 conditional-selection" style="padding: 0px; margin-bottom: 5px;">
																						<input type="text" class="id_conditional_item" value="<?php echo intval( $condition->id ); ?>" hidden>
																						<div class="col-xs-1 col-md-1" style="padding:0px;height:35px;background: #DCF1FD;">
																							<span class="scc_label" style="text-align:right;padding-right:10px;margin-top:5px;"><?php echo $index >= 1 ? 'And' : 'Show if'; ?></span>
																						</div>
																						<div class="col-xs-11 col-md-11" style="padding:0px;">
																							<div class="conditional-selection-steps col-xs-12 col-md-12" style="padding:0px;">
																								<div class="item_conditionals">
																									<select  class="first-conditional-step col-3" style="height: 35px;">
																										<option style="font-size: 10px" value="0">Select one</option>
																										<option value="<?php echo intval( $condition->condition_element_id ); ?>" data-type="<?php echo esc_attr( $condition->element_condition->type ); ?>" selected><?php echo esc_attr( $condition->element_condition->titleElement ); ?></option>
																									</select>
																									<select  class="second-conditional-step col-3" style="height: 35px;">
																										<option value="0" style="font-size: 10px">Select one</option>
																										<option value="eq" 
																										<?php
                                                            if ( $condition->op == 'eq' ) {
                                                                echo 'selected';
                                                            }
                                        ?>
																										>Equal To</option>
																										<option value="ne" 
																										<?php
                                        if ( $condition->op == 'ne' ) {
                                            echo 'selected';
                                        }
                                        ?>
																										>Not Equal To</option>
																										<option value="any" 
																										<?php
                                        if ( $condition->op == 'any' ) {
                                            echo 'selected';
                                        }
                                        ?>
																										>Any</option>
																									</select>
																									<select  class="third-conditional-step col-3" style="height: 35px;
																									<?php
                                                                                                    if ( $condition->op == 'any' ) {
                                                                                                        echo 'display:none';
                                                                                                    }
                                        ?>
																									 ">
																										<option value="0" style="font-size: 10px">Select one</option>
																										<option value="<?php echo intval( $condition->elementitem_id ); ?>" selected><?php echo esc_attr( $condition->elementitem_name->name ); ?></option>
																									</select>
																									<input value="<?php echo esc_attr( $condition->value ); ?>" type="number" placeholder="Number" style="height: 35px;display:none" class="conditional-number-value col-3" min="0">
																									<div class="btn-group" style="margin-left: 10px;">
																										<button onclick="addConditionElement(this)" class="btn btn-cond-save">Save</button>
																										<button onclick="deleteCondition(this)" class="btn btn-danger">x</button>
																									</div>
																								</div>
																							</div>
																						</div>
																					</div>
																					<?php
                                    }

                                    if ( $condition->elementitem_id && ! $condition->condition_element_id ) {
                                        ?>
																					<div class="row col-xs-12 col-md-12 conditional-selection" style="padding: 0px; margin-bottom: 5px;">
																						<input type="text" class="id_conditional_item" value="<?php echo intval( $condition->id ); ?>" hidden>
																						<div class="col-xs-1 col-md-1" style="padding:0px;height:35px;background: #DCF1FD;">
																							<span class="scc_label" style="text-align:right;padding-right:10px;margin-top:5px;"><?php echo $index >= 1 ? 'And' : 'Show if'; ?></span>
																						</div>
																						<div class="col-xs-11 col-md-11" style="padding:0px;">
																							<div class="conditional-selection-steps col-xs-12 col-md-12" style="padding:0px;">
																								<div class="item_conditionals">
																									<select class="first-conditional-step col-3" style="height: 35px;">
																										<option style="font-size: 10px" value="0">Select one</option>
																										<option value="<?php echo intval( $condition->elementitem_id ); ?>" data-type="checkbox" selected><?php echo esc_attr( $condition->elementitem_name->name ); ?></option>
																									</select>
																									<select class="second-conditional-step col-3" style="height: 35px;">
																										<option value="0" style="font-size: 10px">Select one</option>
																										<option value="chec" 
																										<?php
                                                            if ( $condition->op == 'chec' ) {
                                                                echo 'selected';
                                                            }
                                        ?>
																										>Checked</option>
																										<option value="unc" 
																										<?php
                                        if ( $condition->op == 'unc' ) {
                                            echo 'selected';
                                        }
                                        ?>
																										>Unchecked</option>
																									</select>
																									<select  class="third-conditional-step col-3" style="height: 35px;display:none">
																										<option value="0" style="font-size: 10px">Select one</option>
																										<option value="<?php echo intval( $condition->elementitem_id ); ?>" selected><?php echo esc_attr( $condition->elementitem_name->name ); ?></option>
																									</select>
																									<input value="<?php echo esc_attr( $condition->value ); ?>" type="number" placeholder="Number" style="height: 35px;display:none" class="conditional-number-value col-3" min="0">
																									<div class="btn-group" style="margin-left: 10px;">
																										<button onclick="addConditionElement(this)" class="btn btn-cond-save">Save</button>
																										<button onclick="deleteCondition(this)" class="btn btn-danger">x</button>
																									</div>
																								</div>
																							</div>
																						</div>
																					</div>
																					<?php
                                    }

                                    if ( $condition->condition_element_id ) {
                                        if ( $condition->element_condition->type == 'slider' || $condition->element_condition->type == 'quantity box' ) {
                                            ?>
																						<div class="row col-xs-12 col-md-12 conditional-selection" style="padding: 0px; margin-bottom: 5px;">
																							<input type="text" class="id_conditional_item" value="<?php echo intval( $condition->id ); ?>" hidden>
																							<div class="col-xs-1 col-md-1" style="padding:0px;height:35px;background: #DCF1FD;">
																								<span class="scc_label" style="text-align:right;padding-right:10px;margin-top:5px;"><?php echo $index >= 1 ? 'And' : 'Show if'; ?></span>
																							</div>
																							<div class="col-xs-11 col-md-11" style="padding:0px;">
																								<div class="conditional-selection-steps col-xs-12 col-md-12" style="padding:0px;">
																									<div class="item_conditionals">
																										<select class="first-conditional-step col-3" style="height: 35px;">
																											<option style="font-size: 10px" value="0">Select one</option>
																											<option value="<?php echo intval( $condition->condition_element_id ); ?>" data-type="<?php echo esc_attr( $condition->element_condition->type ); ?>" selected><?php echo esc_attr( $condition->element_condition->titleElement ); ?></option>
																										</select>
																										<select class="second-conditional-step col-3" style="height: 35px;">
																										<option value="0" style="font-size: 10px">Select one</option>
																											<option value="eq" 
																											<?php
                                                                if ( $condition->op == 'eq' ) {
                                                                    echo 'selected';
                                                                }
                                            ?>
																											>Equal To</option>
																											<option value="ne" 
																											<?php
                                            if ( $condition->op == 'ne' ) {
                                                echo 'selected';
                                            }
                                            ?>
																											>Not Equal To</option>
																											<option value="gr" 
																											<?php
                                            if ( $condition->op == 'gr' ) {
                                                echo 'selected';
                                            }
                                            ?>
																											>Greater than</option>
																											<option value="les" 
																											<?php
                                            if ( $condition->op == 'les' ) {
                                                echo 'selected';
                                            }
                                            ?>
																											>Less than</option>
																										</select>
																										<select  class="third-conditional-step col-3" style="height: 35px;display:none">
																											<option value="0" style="font-size: 10px">Select one</option>
																											<option value="<?php echo intval( $condition->elementitem_id ); ?>" selected><?php echo esc_attr( $condition->elementitem_name->name ); ?></option>
																										</select>
																										<input value="<?php echo esc_attr( $condition->value ); ?>" type="number" placeholder="Number" style="height: 35px;" class="conditional-number-value col-3" min="0">
																										<div class="btn-group" style="margin-left: 10px;">
																											<button onclick="addConditionElement(this)" class="btn btn-cond-save">Save</button>
																											<button onclick="deleteCondition(this)" class="btn btn-danger">x</button>
																										</div>
																									</div>
																								</div>
																							</div>
																						</div>
																						<?php
                                        }
                                    }
                                }
                            ?>
																			<div class="row col-xs-12 col-md-12 conditional-selection  
																			<?php
                            if ( count( $conditionCollection ) ) {
                                echo 'hidden';
                            }
                            ?>
																			" style="padding: 0px; margin-bottom: 5px;">
																				<div class="col-xs-1 col-md-1" style="padding:0px;height:35px;background: #DCF1FD;">
																					<span class="scc_label" style="text-align:right;padding-right:10px;margin-top:5px;"><?php echo empty( count( $el->conditions ) ) ? 'Show if' : 'And'; ?></span>
																				</div>
																				<div class="col-xs-11 col-md-11" style="padding:0px;">
																					<div class="conditional-selection-steps col-xs-12 col-md-12" style="padding:0px;">
																						<div class="item_conditionals">
																							<select class="first-conditional-step col-3" style="height: 35px;">
																								<option style="font-size: 10px" value="0">Select an element</option>
																							</select>
																							<select class="second-conditional-step col-3" style="height: 35px;display:none">
																								<option value="0" style="font-size: 10px">Select one</option>
																							</select>
																							<select class="third-conditional-step col-3" style="height: 35px;display:none">
																								<option value="0" style="font-size: 10px">Select one</option>
																							</select>
																							<input type="number" placeholder="Number" style="height: 35px;display:none" class="conditional-number-value col-3" min="0">
																							<div class="btn-group" style="margin-left: 10px;display:none">
																								<button onclick="addConditionElement(this)" class="btn btn-cond-save">Save</button>
																								<button onclick="deleteCondition(this)" class="btn btn-danger btn-delcondition" style="display: none;">x</button>
																							</div>
																						</div>
																					</div>
																				</div>
																			</div>
																			<button onclick="(($this) => {jQuery($this).prev().removeClass('hidden'); jQuery($this).addClass('hidden')})(this)" class="btn btn-addcondition cond-add-btn 
																			<?php
                            if ( empty( count( $el->conditions ) ) ) {
                                echo 'hidden';
                            }
                            ?>
																			">+ AND</button>
																		</div>
																	<?php } ?>
																	<div style="width: 28%">
																		<button class="btn btn-primary btn-cond-or <?php echo empty( count( $el->conditions ) ) ? 'hidden' : ''; ?>">+ OR</button>
																	</div>
																</div>
															</div>
														</div>
													</div>
												</div>
		<?php
        $html = ob_get_clean();

        return $html;
    }

    public function renderDropdownSetupBody( $el, $conditionsBySet, $woo_commerce_products = null ) {
        $defaults = [
            'orden'              => 0,
            'titleElement'       => 'Title',
            'type'               => '',
            'subsection_id'      => 0,
            'value1'             => 'default',
            'value4'             => null,
            'value2'             => '',
            'mandatory'          => 0,
            'showPriceHint'      => 0,
            'titleColumnDesktop' => '4',
            'titleColumnMobile'  => '12',
            'displayFrontend'    => 1,
            'displayDetailList'  => 1,
            'showTitlePdf'       => 0,
            'elementitems'       => [
                (object) [
                    'id'                    => isset( $el->elementItem_id ) ? $el->elementItem_id : null,
                    'order'                 => '0',
                    'name'                  => 'Name',
                    'price'                 => '10',
                    'description'           => 'Description',
                    'value1'                => '',
                    'value2'                => '',
                    'value3'                => '',
                    'value4'                => '',
                    'uniqueId'              => 'ozkYrfNw9j',
                    'woocomerce_product_id' => '',
                    'opt_default'           => '0',
                ],
            ],
            'conditions'         => [],
        ];
        $el       = (object) wp_parse_args( $el, $defaults );
        ob_start();
        ?>
		<div class="scc-element-content dropdown-content" data-element-setup-type="<?php echo esc_attr( $el->type ); ?>" value="selectoption" style="
		<?php
        if ( ! $this->is_from_ajax ) {
            echo 'display:none;';
        }
        ?>
			 height:auto">
			<!-- CONTENIDO DE CADA ELEMENTO -->
			<!-- ELEMENT -->
			<div class="slider-setup-body">
			<label class="form-label fw-bold">Title</label>
			<div class="input-group mb-3"><input type="text" onkeyup="clickedTitleElement(this)" style="height:35px;width:100%;" placeholder="Title" value="<?php echo stripslashes( htmlentities( $el->titleElement ) ); ?>"></div>
			<!-- Dropdown Menu Element - ELEMENTS INSIDE ELEMENTS -->
			<div class="col-titles">
				<div class="row g-3 dd-item-edit-field">
				<div class="col-md-7">
					<label class="form-label fw-bold">Items</label>
				</div>
				<div class="col-md-2">
					<label class="form-label fw-bold">Unit Price</label>
				</div>
				</div>
			</div>
			<div class="selectoption_2 col-xs-12 col-md-12">
				<?php foreach ( $el->elementitems as $key => $elit ) { ?>
					<?php echo true ? $this->element_setup_part_dropdown_item_beta( $key, $elit ) : $this->element_setup_part_dropdown_item( $key, $elit ); ?>
					<?php
				}
        ?>
			</div>
			<a onclick="addOptiontoSelect(this)" class="crossnadd" style="margin-top:5px;margin-bottom:20px;">+ Item </a>
				</div>
		</div>
		<div class="scc-element-content"  value="selectoption" style="
		<?php
        if ( ! $this->is_from_ajax ) {
            echo 'display:none;';
        }
        ?>
		 height:auto;">            <div class="scc-new-accordion-container">
				<div class="styled-accordion">
					<div class="scc-title scc_accordion_advance" onclick="showAdvanceoptions(this)">
						<i class="material-icons">keyboard_arrow_right</i><span>Advanced Options</span>
					</div>
					<?php echo $this->renderAdvancedOptions( $el ); ?>
				</div>
				<div class="styled-accordion">
					<div class="scc-title scc_accordion_conditional "  >
						<i class="material-icons">keyboard_arrow_right</i><span style="padding-right:20px;" data-element-tooltip-type="conditional-logic-tt" data-bs-original-title="" title="">Conditional Logic </span>
					</div>
					<div class="scc-content" style="display: none;">
						<div class="scc-transition">
							<?php
                            // echo json_encode($conditionsBySet);
                            foreach ( $conditionsBySet as $key => $conditionCollection ) {
                                ?>
								<?php if ( $key > 1 ) { ?>
									<div style="margin: 10px 0px 10px -10px;">OR</div>
								<?php } ?>
								<div class="condition-container clearfix" data-condition-set=<?php echo intval( $key ); ?>>
								<?php
                                foreach ( $conditionCollection as $index => $condition ) {
                                    if ( ( $condition->op == 'eq' || $condition->op == 'ne' || $condition->op == 'any' ) && ! ( $condition->element_condition->type == 'slider' || $condition->element_condition->type == 'quantity box' ) ) {
                                        ?>
											<div class="row col-xs-12 col-md-12 conditional-selection" style="padding: 0px; margin-bottom: 5px;">
												<input type="text" class="id_conditional_item" value="<?php echo intval( $condition->id ); ?>" hidden>
												<div class="col-xs-1 col-md-1" style="padding:0px;height:35px;background: #DCF1FD;">
													<span class="scc_label" style="text-align:right;padding-right:10px;margin-top:5px;"><?php echo $index >= 1 ? 'And' : 'Show if'; ?></span>
												</div>
												<div class="col-xs-11 col-md-11" style="padding:0px;">
													<div class="conditional-selection-steps col-xs-12 col-md-12" style="padding:0px;">
														<div class="item_conditionals">
															<select  class="first-conditional-step col-3" style="height: 35px;">
																<option style="font-size: 10px" value="0">Select one</option>
																<option value="<?php echo intval( $condition->condition_element_id ); ?>" data-type="<?php echo esc_attr( $condition->element_condition->type ); ?>" selected><?php echo esc_attr( $condition->element_condition->titleElement ); ?></option>
															</select>
															<select  class="second-conditional-step col-3" style="height: 35px;">
																<option value="0" style="font-size: 10px">Select one</option>
																<option value="eq" 
																<?php
                                                                if ( $condition->op == 'eq' ) {
                                                                    echo 'selected';
                                                                }
                                        ?>
																	>Equal To</option>
																<option value="ne" 
																<?php
                                        if ( $condition->op == 'ne' ) {
                                            echo 'selected';
                                        }
                                        ?>
																	>Not Equal To</option>
																<option value="any" 
																<?php
                                        if ( $condition->op == 'any' ) {
                                            echo 'selected';
                                        }
                                        ?>
																	>Any</option>
															</select>
															<select  class="third-conditional-step col-3" style="height: 35px;
															<?php
                                                            if ( $condition->op == 'any' ) {
                                                                echo 'display:none';
                                                            }
                                        ?>
																 ">
																<option value="0" style="font-size: 10px">Select one</option>
																<option value="<?php echo intval( $condition->elementitem_id ); ?>" selected><?php echo esc_attr( $condition->elementitem_name->name ); ?></option>
															</select>
															<input value="<?php echo esc_attr( $condition->value ); ?>" type="number" placeholder="Number" style="height: 35px;display:none" class="conditional-number-value col-3" min="0">
															<div class="btn-group" style="margin-left: 10px;">
																<button onclick="addConditionElement(this)" class="btn btn-cond-save">Save</button>
																<button onclick="deleteCondition(this)" class="btn btn-danger">x</button>
															</div>
														</div>
													</div>
												</div>
											</div>
											<?php
                                    }

                                    if ( $condition->elementitem_id && ! $condition->condition_element_id ) {
                                        ?>
											<div class="row col-xs-12 col-md-12 conditional-selection" style="padding: 0px; margin-bottom: 5px;">
												<input type="text" class="id_conditional_item" value="<?php echo intval( $condition->id ); ?>" hidden>
												<div class="col-xs-1 col-md-1" style="padding:0px;height:35px;background: #DCF1FD;">
													<span class="scc_label" style="text-align:right;padding-right:10px;margin-top:5px;"><?php echo $index >= 1 ? 'And' : 'Show if'; ?></span>
												</div>
												<div class="col-xs-11 col-md-11" style="padding:0px;">
													<div class="conditional-selection-steps col-xs-12 col-md-12" style="padding:0px;">
														<div class="item_conditionals">
															<select class="first-conditional-step col-3" style="height: 35px;">
																<option style="font-size: 10px" value="0">Select one</option>
																<option value="<?php echo intval( $condition->elementitem_id ); ?>" data-type="checkbox" selected><?php echo esc_attr( $condition->elementitem_name->name ); ?></option>
															</select>
															<select class="second-conditional-step col-3" style="height: 35px;">
																<option value="0" style="font-size: 10px">Select one</option>
																<option value="chec" 
																<?php
                                                                if ( $condition->op == 'chec' ) {
                                                                    echo 'selected';
                                                                }
                                        ?>
																	>Checked</option>
																<option value="unc" 
																<?php
                                        if ( $condition->op == 'unc' ) {
                                            echo 'selected';
                                        }
                                        ?>
																	>Unchecked</option>
															</select>
															<select  class="third-conditional-step col-3" style="height: 35px;display:none">
																<option value="0" style="font-size: 10px">Select one</option>
																<option value="<?php echo intval( $condition->elementitem_id ); ?>" selected><?php echo esc_attr( $condition->elementitem_name->name ); ?></option>
															</select>
															<input value="<?php echo esc_attr( $condition->value ); ?>" type="number" placeholder="Number" style="height: 35px;display:none" class="conditional-number-value col-3" min="0">
															<div class="btn-group" style="margin-left: 10px;">
																<button onclick="addConditionElement(this)" class="btn btn-cond-save">Save</button>
																<button onclick="deleteCondition(this)" class="btn btn-danger">x</button>
															</div>
														</div>
													</div>
												</div>
											</div>
											<?php
                                    }

                                    if ( $condition->condition_element_id ) {
                                        if ( $condition->element_condition->type == 'slider' || $condition->element_condition->type == 'quantity box' ) {
                                            ?>
												<div class="row col-xs-12 col-md-12 conditional-selection" style="padding: 0px; margin-bottom: 5px;">
													<input type="text" class="id_conditional_item" value="<?php echo intval( $condition->id ); ?>" hidden>
													<div class="col-xs-1 col-md-1" style="padding:0px;height:35px;background: #DCF1FD;">
														<span class="scc_label" style="text-align:right;padding-right:10px;margin-top:5px;"><?php echo $index >= 1 ? 'And' : 'Show if'; ?></span>
													</div>
													<div class="col-xs-11 col-md-11" style="padding:0px;">
														<div class="conditional-selection-steps col-xs-12 col-md-12" style="padding:0px;">
															<div class="item_conditionals">
																<select class="first-conditional-step col-3" style="height: 35px;">
																	<option style="font-size: 10px" value="0">Select one</option>
																	<option value="<?php echo intval( $condition->condition_element_id ); ?>" data-type="<?php echo esc_attr( $condition->element_condition->type ); ?>" selected><?php echo esc_attr( $condition->element_condition->titleElement ); ?></option>
																</select>
																<select class="second-conditional-step col-3" style="height: 35px;">
																<option value="0" style="font-size: 10px">Select one</option>
																	<option value="eq" 
																	<?php
                                                                    if ( $condition->op == 'eq' ) {
                                                                        echo 'selected';
                                                                    }
                                            ?>
																		>Equal To</option>
																	<option value="ne" 
																	<?php
                                            if ( $condition->op == 'ne' ) {
                                                echo 'selected';
                                            }
                                            ?>
																		>Not Equal To</option>
																	<option value="gr" 
																	<?php
                                            if ( $condition->op == 'gr' ) {
                                                echo 'selected';
                                            }
                                            ?>
																		>Greater than</option>
																	<option value="les" 
																	<?php
                                            if ( $condition->op == 'les' ) {
                                                echo 'selected';
                                            }
                                            ?>
																		>Less than</option>
																</select>
																<select  class="third-conditional-step col-3" style="height: 35px;display:none">
																	<option value="0" style="font-size: 10px">Select one</option>
																	<option value="<?php echo intval( $condition->elementitem_id ); ?>" selected><?php echo esc_attr( $condition->elementitem_name->name ); ?></option>
																</select>
																<input value="<?php echo esc_attr( $condition->value ); ?>" type="number" placeholder="Number" style="height: 35px;" class="conditional-number-value col-3" min="0">
																<div class="btn-group" style="margin-left: 10px;">
																	<button onclick="addConditionElement(this)" class="btn btn-cond-save">Save</button>
																	<button onclick="deleteCondition(this)" class="btn btn-danger">x</button>
																</div>
															</div>
														</div>
													</div>
												</div>
												<?php
                                        }
                                    }
                                }
                                ?>
									<div class="row col-xs-12 col-md-12 conditional-selection  
									<?php
                                    if ( count( $conditionCollection ) ) {
                                        echo 'hidden';
                                    }
                                ?>
										" style="padding: 0px; margin-bottom: 5px;">
										<div class="col-xs-1 col-md-1" style="padding:0px;height:35px;background: #DCF1FD;">
											<span class="scc_label" style="text-align:right;padding-right:10px;margin-top:5px;"><?php echo empty( count( $el->conditions ) ) ? 'Show if' : 'And'; ?></span>
										</div>
										<div class="col-xs-11 col-md-11" style="padding:0px;">
											<div class="conditional-selection-steps col-xs-12 col-md-12" style="padding:0px;">
												<div class="item_conditionals">
													<select class="first-conditional-step col-3" style="height: 35px;">
														<option style="font-size: 10px" value="0">Select an element</option>
													</select>
													<select class="second-conditional-step col-3" style="height: 35px;display:none">
														<option value="0" style="font-size: 10px">Select one</option>
													</select>
													<select class="third-conditional-step col-3" style="height: 35px;display:none">
														<option value="0" style="font-size: 10px">Select one</option>
													</select>
													<input type="number" placeholder="Number" style="height: 35px;display:none" class="conditional-number-value col-3" min="0">
													<div class="btn-group" style="margin-left: 10px;display:none">
														<button onclick="addConditionElement(this)" class="btn btn-cond-save">Save</button>
														<button onclick="deleteCondition(this)" class="btn btn-danger btn-delcondition" style="display: none;">x</button>
													</div>
												</div>
											</div>
										</div>
									</div>
									<button onclick="(($this) => {jQuery($this).prev().removeClass('hidden'); jQuery($this).addClass('hidden')})(this)" class="btn btn-addcondition cond-add-btn 
									<?php
                                if ( empty( count( $el->conditions ) ) ) {
                                    echo 'hidden';
                                }
                                ?>
										">+ AND</button>
								</div>
							<?php } ?>
							<div style="width: 28%">
								<button class="btn btn-primary btn-cond-or <?php echo empty( count( $el->conditions ) ) ? 'hidden' : ''; ?>">+ OR</button>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<?php
        $html = ob_get_clean();

        return $html;
    }

    public function element_setup_part_dropdown_item( $key, $elit ) {
        $defaults = [
            'id'                    => isset( $elit->elementItem_id ) ? $elit->elementItem_id : null,
            'order'                 => '0',
            'name'                  => 'Name',
            'price'                 => '10',
            'description'           => 'Description',
            'value1'                => '',
            'value2'                => '',
            'value3'                => '',
            'value4'                => '',
            'uniqueId'              => 'ozkYrfNw9j',
            'woocomerce_product_id' => '',
            'opt_default'           => '0',
        ];
        $elit     = (object) wp_parse_args( $elit, $defaults );

        if ( $this->is_from_ajax ) {
            $elit = (object) $elit;
            ob_start();
        }
        ?>
		<div class="row m-0 selopt3 col-md-12 col-xs-12" style="margin-top:10px;padding:0px">
			<div class="row p-0 m-0 col-md-11 col-xs-11">
				<input class="swichoptionitem_id" type="text" value="<?php echo intval( $elit->id ); ?>" hidden>
				<div class="col-xs-2 col-md-2 tooltipadmin-left 
				<?php
                if ( $elit->opt_default == '1' ) {
                    echo 'is-set-default';
                }
        ?>
					" onclick="setDefaultOption(this)" id="dropdownOpt" <?php echo ( $elit->opt_default == '1' ) ? ' data-selected="true"' : 'style="padding:0px;height:35px;" data-tooltip="Click to make this option default."'; ?>>
					<label class="" style="float: none;margin-top:10px;font-size:14px;font-weight: normal;"><?php echo intval( $key ) + 1; ?></label>
				</div>
				<div class="col-md-6 col-xs-6" style="padding: 0px 5px 0px 1px;">
				<input type="text" onkeyup="changeNameElementItem(this)" class="input_pad inputoption scc-input" style="width:100%;" value="<?php echo esc_html( $elit->name ); ?>" placeholder="Product or service name">
				</div>
				<div class="col-md-2 col-xs-2 d-flex scc-input-icon" style="padding:0px">
					<span class="input-group-text"><?php echo df_scc_get_currency_symbol_by_currency_code( $this->df_scc_form_currency ); ?></span>
					<input type="number" onchange="changePriceElementItem(this)" onkeyup="changePriceElementItem(this)" class="input_pad inputoption_2" style="width:100%;text-align:center;" placeholder="Price" value="<?php echo floatval( $elit->price ); ?>">
				</div>
				<div class="col-md-2 col-xs-0" style="padding:0px"></div>
				<span class="col-xs-6 col-md-2">
					<img class="scc-image-picker" style="height: 80px;width:80px" onclick="choseImageElementItem(this)" src="<?php echo empty( $elit->value1 ) ? esc_url( SCC_ASSETS_URL . '/images/image.png' ) : esc_url( $elit->value1 ); ?> " title="Pick an image. Please choose an image with a 1:1 aspect ratio for best results.">
					<span class="scc-dropdown-image-remove" onclick="removeDropdownImage(this)">x</span>
				</span>
				<div class="col-md-8 col-xs-8" style="padding:0px; padding-right:5px; margin-top:5px;">
				<textarea onkeyup="changeDescriptionElementItem(this)" class="input_pad inputoption_desc" style="width:100%;height:75px;" placeholder="Description"><?php echo esc_html( $elit->description ); ?></textarea>
				</div>
				<div class="col-md-1 col-xs-1" style="padding:0px;"></div>
			</div>
			<div class="col-md-1 col-xs-1" style="padding-left: 0;">
				<button onclick="removeSwitchOptionDropdown(this)" class="deleteBackendElmnt"><i class="fa fa-trash"></i></button>
			</div>
		</div>
		<?php
        if ( $this->is_from_ajax ) {
            $html = ob_get_clean();

            return $html;
        }
    }
    public function element_setup_part_dropdown_item_beta( $key, $elit ) {
        $defaults = [
            'id'                    => isset( $elit->elementItem_id ) ? $elit->elementItem_id : null,
            'order'                 => '0',
            'name'                  => 'Name',
            'price'                 => '10',
            'description'           => 'Description',
            'value1'                => '',
            'value2'                => '',
            'value3'                => '',
            'value4'                => '',
            'uniqueId'              => 'ozkYrfNw9j',
            'woocomerce_product_id' => '',
            'opt_default'           => '0',
        ];
        $elit     = (object) wp_parse_args( $elit, $defaults );

        if ( $this->is_from_ajax ) {
            $elit = (object) $elit;
            ob_start();
        }
        ?>
		<div class="dd-item-field-container" data-element-item-id="<?php echo intval( $elit->id ); ?>">
			<div class="row g-3 dd-item-edit-field">
				<div class="col-md-1">
					
				</div>
				<div class="col-md-6 scc-dd-title-column">
					<input type="text" class="form-control scc-input" onkeyup="changeNameElementItem(this, true)" value="<?php echo stripslashes( wp_kses( $elit->name, SCC_ALLOWTAGS ) ); ?>">
				</div>
				<div class="col-md-2 d-inline-flex scc-input-icon">
					<span class="input-group-text" style="height: fit-content;"><?php echo df_scc_get_currency_symbol_by_currency_code( $this->df_scc_form_currency ); ?></span>
					<input type="number" onchange="changePriceElementItem(this, true)" class="form-control" value="<?php echo floatval( $elit->price ); ?>">
				</div>
				<i onclick="removeSwitchOptionDropdown(this, true)" class="material-icons-outlined range-close-btn">close</i>
			</div>
			<div class="row g-3">
				<span class="col-xs-0 col-md-2 image_container scc-dd-image-container">
					<img class="scc-image-picker" style="height: 80px;width:80px" onclick="choseImageElementItem(this)" src="<?php echo ( $elit->value1 == null || $elit->value1 == '0' ) ? SCC_ASSETS_URL . '/images/image.png' : $elit->value1; ?>" title="Pick an image. Please choose an image with a 1:1 aspect ratio for best results.">
					<span class="scc-dropdown-image-remove" style="" onclick="removeDropdownImage(this, true)">x</span>
				</span>
				<div class="col-md-10 col-xs-6">
					<textarea onkeyup="changeDescriptionElementItem(this, true)" class="input_pad inputoption_desc" style="width:100%;height:75px;" placeholder="Description"><?php echo stripslashes( wp_kses( $elit->description, SCC_ALLOWTAGS ) ); ?></textarea>
				</div>
			</div>
			<div class="col-12 mb-3 edit-field" data-edit-field-type="wc_choices" style="width: 105%;">
														<?php if ( ! empty( $this->woo_commerce_products ) ) { ?>
															<label class="form-label fw-bold"><img class="scc-woo-logo" src="<?php echo esc_url_raw( SCC_ASSETS_URL . '/images/logo-woocommerce.svg' ); ?>" title="Pick an item from your WooCommerce products to link to."></label>
															<!--WooCommerce for Slider Element-->
															<select class="form-select w-100" data-target="elements_added" onchange="attachProductId(this, null, null, true)">
																<option style="font-size: 10px" value=0>Select a product..</option>
																<?php
                                                                foreach ( $this->woo_commerce_products as $product ) {
                                                                    ?>
																	<?php
                                                                    if ( $product->is_type( 'variable' ) ) {
                                                                        $available_variations = $product->get_available_variations();

                                                                        foreach ( $available_variations as $product_variable ) {
                                                                            $attributes = [];

                                                                            foreach ( $product_variable['attributes'] as $key => $value ) {
                                                                                $attributes[] = $product->get_name() . ': ' . $value;
                                                                            }
                                                                            ?>
																			<option value=<?php echo esc_html( $product_variable['variation_id'] ); ?> <?php echo selected( $product->get_id() == intval( $elit->woocomerce_product_id ) ); ?>><?php echo esc_html( implode( ' | ', $attributes ) ) . ' | Price: ' . get_woocommerce_currency_symbol() . '' . esc_html( $product_variable['display_regular_price'] ); ?></option>
																			<?php
                                                                        }
                                                                    } else {
                                                                        ?>
																		<option value=<?php echo esc_html( $product->get_id() ); ?> <?php echo selected( $product->get_id() == intval( $elit->woocomerce_product_id ) ); ?>><?php echo esc_html( $product->get_name() ) . ' | Price: ' . get_woocommerce_currency_symbol() . '' . esc_html( $product->get_price() ); ?></option>
																		<?php
                                                                    }
                                                                }
														    ?>
															</select>
															<i class="material-icons-outlined v-align-middle">info</i>
														<?php } ?>
													</div>
		</div>
		<?php
        if ( $this->is_from_ajax ) {
            $html = ob_get_clean();

            return $html;
        }
    }

    public function renderElementLoader() {
        ob_start(); ?>
			<span class="scc-saving-element-msg scc-visibility-hidden"></span>
		<?php
        $html = ob_get_clean();

        return $html;
    }

	public function scc_render_element_saving( $el ) {
        ob_start(); ?>
			<div class="scc-saving-element-section">
				<span class="scc-saving-element-msg scc-visibility-hidden me-3"></span>
			</div>
		<?php
        $html = ob_get_clean();

        return $html;
    }
}
