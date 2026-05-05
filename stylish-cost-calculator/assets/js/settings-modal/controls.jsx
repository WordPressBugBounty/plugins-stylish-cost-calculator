import { createElement, useEffect, useRef, useState } from './elements';
import { EditIcon, GemIcon, HelpIcon, SettingsChevron } from './icons';
import {
	getControlLabel,
	getControlValue,
	getControlStateKey,
	getControlsForSubsection,
	getDatasetValue,
	getDisplayLabel,
	getGroupLabel,
	getHelpText,
	getSectionGroup,
	getSectionLabel,
	getSelectValue,
	getSubsectionDomId,
	getTextValue,
	invokeControlCallback,
	isCustomJsControl,
	isWebhookControl,
	normalizeText,
} from './utils';

const PREMIUM_PRICING_URL = 'https://stylishcostcalculator.com/pricing-plans/';
const PREMIUM_CONTROL_IDENTIFIERS = [
	'email_quote_recipients',
	'include_quote_form_data',
	'quote_form_show_pdf_name',
	'scc_no_qty_col',
	'scc_no_unit_col',
	'scc_remove_detailed_list_title',
	'scc_save_icon',
	'scc_send_quote',
	'scc_frontend_allow_currency_switching',
	'scc_show_searchbar',
	'scc_show_taxvat',
	'scc_tax_amount',
	'show_invoice_number',
	'show_price_column',
	'toggle_add_user_files_to_attachment',
	'turn_off_tax',
	'turn_off_coupon',
];

function getRowGroupKey( control ) {
	return [
		control.subsection || '',
		control.rowClasses || '',
		control.label || '',
		control.dataset?.target || '',
		control.dataset?.eventType || '',
		control.containerClasses || '',
	]
		.join( '::' );
}

function classListIncludes( value, className ) {
	return String( value || '' ).split( /\s+/ ).includes( className );
}

function isPremiumControl( control ) {
	if ( ! control ) {
		return false;
	}

	const controlIdentifiers = [
		control.id,
		control.name,
		control.key,
	].map( ( identifier ) => String( identifier || '' ) );
	const classSources = [
		control.classes,
		control.containerClasses,
		control.rowClasses,
	].join( ' ' );

	return Boolean( control.disabled ) ||
		controlIdentifiers.some( ( identifier ) => PREMIUM_CONTROL_IDENTIFIERS.includes( identifier ) ) ||
		classListIncludes( classSources, 'disabled' ) ||
		classListIncludes( classSources, 'use-premium-tooltip' ) ||
		classListIncludes( classSources, 'scc-disabled-premium-toggle' );
}

function openPremiumPricing() {
	window.open( PREMIUM_PRICING_URL, '_blank', 'noopener,noreferrer' );
}

function getPremiumIconElement( label ) {
	return (
		<button
			type="button"
			className="scc-settings-premium-trigger"
			onClick={ openPremiumPricing }
			aria-label={ `Upgrade to unlock ${ label }` }
			title="Upgrade to unlock this feature"
		>
			<GemIcon />
		</button>
	);
}

function getControlHelpTooltipType( control ) {
	return getDatasetValue( control, 'settingTooltipType' ) || '';
}

function getControlHelpElement( control ) {
	if ( ! getControlHelpTooltipType( control ) ) {
		return null;
	}

	return (
		<span
			className="scc-settings-help-trigger"
			role="button"
			tabIndex={ 0 }
			data-setting-tooltip-type={ getControlHelpTooltipType( control ) }
			aria-label={ `Help for ${ getControlLabel( control ) }` }
		>
			<HelpIcon />
		</span>
	);
}

function buildControlRows( controls ) {
	const rows = [];
	const rowLookup = {};

	controls.forEach( ( control ) => {
		const rowKey = getRowGroupKey( control );
		if ( ! rowLookup[ rowKey ] ) {
			rowLookup[ rowKey ] = {
				key: rowKey,
				controls: [],
			};
			rows.push( rowLookup[ rowKey ] );
		}

		rowLookup[ rowKey ].controls.push( control );
	} );

	return rows.map( ( row ) => ( {
		...row,
		controls: row.controls.sort( ( left, right ) => left.sortOrder - right.sortOrder ),
	} ) );
}

function SettingsSingleSelect( { control, value, onChange, disabled } ) {
	const wrapperRef = useRef( null );
	const [ isOpen, setIsOpen ] = useState( false );
	const options = Array.isArray( control.options ) ? control.options : [];
	const selectedValue = getSelectValue( value );
	const selectedOption = options.find( ( option ) => String( option.value ) === selectedValue ) || options[ 0 ] || null;
	const selectedLabel = selectedOption ? selectedOption.label || selectedOption.value : control.placeholder || 'Select an option';

	useEffect( () => {
		if ( ! isOpen ) {
			return undefined;
		}

		const handlePointerDown = ( event ) => {
			if ( wrapperRef.current && ! wrapperRef.current.contains( event.target ) ) {
				setIsOpen( false );
			}
		};

		document.addEventListener( 'mousedown', handlePointerDown );

		return () => {
			document.removeEventListener( 'mousedown', handlePointerDown );
		};
	}, [ isOpen ] );

	const handleToggleOpen = () => {
		if ( disabled ) {
			return;
		}

		setIsOpen( ( currentValue ) => ! currentValue );
	};

	const handleSelectOption = ( optionValue ) => {
		if ( disabled ) {
			return;
		}

		onChange( String( optionValue ) );
		setIsOpen( false );
	};

	const handleTriggerKeyDown = ( event ) => {
		if ( disabled ) {
			return;
		}

		if ( event.key === 'Enter' || event.key === ' ' ) {
			event.preventDefault();
			handleToggleOpen();
			return;
		}

		if ( event.key === 'Escape' ) {
			setIsOpen( false );
			return;
		}

		if ( event.key === 'ArrowDown' || event.key === 'ArrowUp' ) {
			event.preventDefault();

			if ( ! options.length ) {
				return;
			}

			const enabledOptions = options.filter( ( option ) => ! option.disabled );
			if ( ! enabledOptions.length ) {
				return;
			}

			const currentIndex = enabledOptions.findIndex( ( option ) => String( option.value ) === selectedValue );
			const step = event.key === 'ArrowDown' ? 1 : -1;
			const fallbackIndex = step > 0 ? 0 : enabledOptions.length - 1;
			const nextIndex = currentIndex === -1 ? fallbackIndex : ( currentIndex + step + enabledOptions.length ) % enabledOptions.length;

			handleSelectOption( enabledOptions[ nextIndex ].value );
		}
	};

	return (
		<div className={ `scc-settings-select-shell scc-settings-custom-select-shell${ isOpen ? ' is-open' : '' }${ disabled ? ' is-disabled' : '' }` } ref={ wrapperRef }>
			<div
				className="scc-settings-custom-select-trigger"
				onClick={ handleToggleOpen }
				onKeyDown={ handleTriggerKeyDown }
				role="button"
				tabIndex={ disabled ? -1 : 0 }
				aria-expanded={ isOpen }
				aria-haspopup="listbox"
			>
				<span className={ `scc-settings-custom-select-value${ selectedOption ? '' : ' is-placeholder' }` }>
					{ selectedLabel }
				</span>
				<span className="scc-settings-select-icon" aria-hidden="true">
					<SettingsChevron isOpen={ isOpen } />
				</span>
			</div>
			{ isOpen ? (
				<div className="scc-settings-custom-select-dropdown">
					<div className="scc-settings-custom-select-options" role="listbox">
						{ options.length ? options.map( ( option ) => {
							const optionValue = String( option.value );
							const isSelected = optionValue === selectedValue;

							return (
								<button
									type="button"
									key={ `${ control.key }-${ option.value }-${ option.sortOrder }` }
									className={ `scc-settings-custom-select-option${ isSelected ? ' is-selected' : '' }` }
									onClick={ () => handleSelectOption( option.value ) }
									disabled={ option.disabled }
									role="option"
									aria-selected={ isSelected }
								>
									<span>{ option.label || option.value }</span>
								</button>
							);
						} ) : <div className="scc-settings-custom-select-empty">No options available.</div>}
					</div>
				</div>
			) : null}
		</div>
	);
}

function SettingsTextControl( { control, value, onChange, disabled, isTextarea = false } ) {
	const [ isFocused, setIsFocused ] = useState( false );
	const hasValue = Boolean( getTextValue( value ) );
	const shellClassName = `scc-settings-input-shell${ isTextarea ? ' scc-settings-textarea-shell' : '' }${ disabled ? ' is-disabled' : '' }${ isFocused ? ' is-focused' : '' }${ hasValue ? ' has-value' : '' }`;
	const commonProps = {
		className: isTextarea ? 'scc-settings-textarea scc-settings-input-field' : 'scc-settings-input scc-settings-input-field',
		value: getTextValue( value ),
		onChange: ( event ) => onChange( event.target.value ),
		placeholder: control.placeholder || '',
		disabled,
		onFocus: () => setIsFocused( true ),
		onBlur: () => setIsFocused( false ),
	};

	return (
		<div className={ shellClassName }>
			<div className="scc-settings-input-surface">
				{ isTextarea ? (
					<textarea { ...commonProps } />
				) : (
					<input
						{ ...commonProps }
						type={ [ 'number', 'email', 'url' ].includes( control.type ) ? control.type : 'text' }
					/>
				) }
			</div>
		</div>
	);
}

function SettingsMultiSelect( { control, value, onChange, disabled } ) {
	const wrapperRef = useRef( null );
	const inputRef = useRef( null );
	const [ isOpen, setIsOpen ] = useState( false );
	const [ searchQuery, setSearchQuery ] = useState( '' );
	const selectedValues = Array.isArray( value ) ? value.map( String ) : [];
	const options = Array.isArray( control.options ) ? control.options : [];
	const selectedOptions = options.filter( ( option ) => selectedValues.includes( String( option.value ) ) );
	const filteredOptions = options.filter( ( option ) => {
		if ( selectedValues.includes( String( option.value ) ) ) {
			return false;
		}

		if ( ! searchQuery.trim() ) {
			return true;
		}

		return normalizeText( option.label || option.value ).includes( normalizeText( searchQuery ) );
	} );
	const placeholder = control.dataset?.placeholder || control.placeholder || 'Select options';

	useEffect( () => {
		if ( ! isOpen ) {
			return undefined;
		}

		const handlePointerDown = ( event ) => {
			if ( wrapperRef.current && ! wrapperRef.current.contains( event.target ) ) {
				setIsOpen( false );
				setSearchQuery( '' );
			}
		};

		document.addEventListener( 'mousedown', handlePointerDown );

		return () => {
			document.removeEventListener( 'mousedown', handlePointerDown );
		};
	}, [ isOpen ] );

	useEffect( () => {
		if ( isOpen && inputRef.current ) {
			inputRef.current.focus();
		}
	}, [ isOpen ] );

	const handleToggleOpen = () => {
		if ( disabled ) {
			return;
		}

		setIsOpen( ( currentValue ) => ! currentValue );
		if ( isOpen ) {
			setSearchQuery( '' );
		}
	};

	const handleAddOption = ( optionValue ) => {
		if ( disabled ) {
			return;
		}

		onChange( [ ...selectedValues, String( optionValue ) ] );
		setSearchQuery( '' );
		if ( inputRef.current ) {
			inputRef.current.focus();
		}
	};

	const handleRemoveOption = ( optionValue ) => {
		if ( disabled ) {
			return;
		}

		onChange( selectedValues.filter( ( selectedValue ) => selectedValue !== String( optionValue ) ) );
	};

	return (
		<div className={ `scc-settings-select-shell scc-settings-multiselect-shell${ isOpen ? ' is-open' : '' }${ disabled ? ' is-disabled' : '' }` } ref={ wrapperRef }>
			<div
				className="scc-settings-multiselect-trigger"
				onClick={ handleToggleOpen }
				onKeyDown={ ( event ) => {
					if ( disabled ) {
						return;
					}

					if ( event.key === 'Enter' || event.key === ' ' ) {
						event.preventDefault();
						handleToggleOpen();
					}
				} }
				role="button"
				tabIndex={ disabled ? -1 : 0 }
				aria-expanded={ isOpen }
				aria-haspopup="listbox"
			>
				<div className="scc-settings-multiselect-values">
					{ selectedOptions.length ? selectedOptions.map( ( option ) => (
						<span className="scc-settings-multiselect-chip" key={ `${ control.key }-chip-${ option.value }` }>
							<span>{ option.label || option.value }</span>
							<button
								type="button"
								className="scc-settings-multiselect-chip-remove"
								onClick={ ( event ) => {
									event.stopPropagation();
									handleRemoveOption( option.value );
								} }
								aria-label={ `Remove ${ option.label || option.value }` }
								disabled={ disabled }
							>
								x
							</button>
						</span>
					) ) : <span className="scc-settings-multiselect-placeholder">{ placeholder }</span>}
				</div>
				<span className="scc-settings-select-icon scc-settings-multiselect-icon" aria-hidden="true">
					<SettingsChevron isOpen={ false } />
				</span>
			</div>
			{ isOpen ? (
				<div className="scc-settings-multiselect-dropdown">
					<div className="scc-settings-multiselect-options" role="listbox" aria-multiselectable="true">
						{ filteredOptions.length ? filteredOptions.map( ( option ) => (
							<button
								type="button"
								key={ `${ control.key }-${ option.value }-${ option.sortOrder }` }
								className="scc-settings-multiselect-option"
								onClick={ () => handleAddOption( option.value ) }
								disabled={ option.disabled }
							>
								<span>{ option.label || option.value }</span>
							</button>
						) ) : <div className="scc-settings-multiselect-empty">No matching options left.</div>}
					</div>
				</div>
			) : null}
		</div>
	);
}

function renderControlNode( { control, value, onChange } ) {
	const label = getControlLabel( control );

	if ( control.type === 'checkbox' ) {
		return (
			<button
				type="button"
				className={ `scc-settings-toggle${ value ? ' is-on' : '' }` }
				onClick={ () => onChange( ! value ) }
				role="switch"
				aria-checked={ Boolean( value ) }
				aria-label={ label }
				disabled={ control.disabled }
			>
				<span />
			</button>
		);
	}

	if ( control.type === 'select-one' ) {
		return (
			<SettingsSingleSelect
				control={ control }
				value={ value }
				onChange={ onChange }
				disabled={ control.disabled }
			/>
		);
	}

	if ( control.type === 'select-multiple' ) {
		return (
			<SettingsMultiSelect
				control={ control }
				value={ value }
				onChange={ onChange }
				disabled={ control.disabled }
			/>
		);
	}

	if ( control.type === 'action' || control.controlType === 'callback' ) {
		if ( isWebhookControl( control ) ) {
			const inputControl = {
				...control,
				type: 'url',
				placeholder: 'https://example.com/webhook',
			};
			const inputValue = value === null || value === undefined ? getControlValue( control ) : value;

			return (
				<SettingsTextControl
					control={ inputControl }
					value={ inputValue }
					onChange={ onChange }
					disabled={ control.disabled }
				/>
			);
		}

		return (
			<button
				type="button"
				className="scc-settings-action"
				onClick={ () => invokeControlCallback( control ) }
				disabled={ control.disabled }
				aria-label={ `Edit ${ label }` }
				title={ label }
			>
				<EditIcon />
			</button>
		);
	}

	if ( control.tagName === 'textarea' ) {
		return (
			<SettingsTextControl
				control={ control }
				value={ value }
				onChange={ onChange }
				disabled={ control.disabled }
				isTextarea
			/>
		);
	}

	return (
		<SettingsTextControl
			control={ control }
			value={ value }
			onChange={ onChange }
			disabled={ control.disabled }
		/>
	);
}

function RenderControlRow( { controls, controlValues, onControlChange } ) {
	const primaryControl = controls.find( ( control ) => control.controlType !== 'callback' ) || controls[ 0 ];
	const isPremiumRow = controls.some( isPremiumControl );
	const helpText = getHelpText( primaryControl );
	const labelSuffix = primaryControl.controlType === 'callback' && isWebhookControl( primaryControl )
		? ' (webhook URL)'
		: primaryControl.controlType === 'callback' && isCustomJsControl( primaryControl )
			? ' (custom code snippet)'
			: '';
	const label = `${ getControlLabel( primaryControl ) }${ labelSuffix }`;
	const helpElement = getControlHelpElement( primaryControl );

	return (
		<div className={ `scc-settings-row${ helpText ? ' has-help' : '' }` }>
			<div className="scc-settings-row-main">
				<h3 className="scc-settings-row-title">
					<span>{ label }</span>
					{ helpElement }
					{ isPremiumRow ? getPremiumIconElement( label ) : null }
				</h3>
				{ helpText ? <p className="scc-settings-row-help">{ helpText }</p> : null }
			</div>
			<div className="scc-settings-row-control">
				{ controls.map( ( control ) => {
					const controlStateKey = getControlStateKey( control );
					return (
						<div className="scc-settings-row-control-item" key={ controlStateKey }>
							{ renderControlNode( {
								control: isPremiumRow ? { ...control, disabled: true } : control,
								value: controlValues[ controlStateKey ],
								onChange: ( nextValue ) => onControlChange( controlStateKey, nextValue ),
							} ) }
						</div>
					);
				} ) }
			</div>
		</div>
	);
}

function SubsectionPanel( { section, controls, controlValues, onControlChange, isActive } ) {
	const subsectionName = getSectionLabel( section );
	const groupName = getGroupLabel( getSectionGroup( section ) );
	const displaySubsectionName = getDisplayLabel( subsectionName );
	const subsectionControls = getControlsForSubsection( controls, subsectionName );
	const controlRows = buildControlRows( subsectionControls );

	return (
		<section id={ getSubsectionDomId( section ) } className={ `scc-settings-section-card${ isActive ? ' is-active' : '' }` }>
			<header className="scc-settings-section-header">
				<div className="scc-settings-section-eyebrow">
					<span>{ groupName }</span>
					<span className="scc-settings-badge">{ section.fieldCount }</span>
				</div>
				<h2 className="scc-settings-section-title">{ displaySubsectionName }</h2>
				<p className="scc-settings-section-copy">
					{ `${ section.fieldCount } setting${ section.fieldCount === 1 ? '' : 's' } available in this subsection.` }
				</p>
			</header>
			<div className="scc-settings-section-body">
				{ controlRows.length ? controlRows.map( ( row ) => (
					<RenderControlRow
						key={ row.key }
						controls={ row.controls }
						controlValues={ controlValues }
						onControlChange={ onControlChange }
					/>
				) ) : <div className="scc-settings-empty">No visible controls were found for this subsection.</div>}
			</div>
		</section>
	);
}

function SettingsBootstrapFallback() {
	return (
		<div className="alert alert-warning mb-3" role="alert">
			<h5 className="mb-1">Settings UI unavailable</h5>
			<p className="mb-0">The modal schema could not be loaded, so the React settings shell was not rendered.</p>
		</div>
	);
}

export { SettingsBootstrapFallback, SubsectionPanel };
