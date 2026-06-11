import { GROUP_LABELS, SETTINGS_SCHEMA_ID } from './constants';

export function parseSettingsSchema() {
	const schemaNode = document.getElementById( SETTINGS_SCHEMA_ID );
	if ( ! schemaNode ) {
		return { sections: [], controls: {} };
	}

	try {
		const parsed = JSON.parse( schemaNode.textContent || '{}' );
		return {
			sections: Array.isArray( parsed.sections ) ? parsed.sections : [],
			controls: parsed.controls || {},
		};
	} catch ( error ) {
		return { sections: [], controls: {} };
	}
}

export function normalizeText( value ) {
	return String( value || '' ).toLowerCase().replace( /\s+/g, ' ' ).trim();
}

export function slugify( value ) {
	return normalizeText( value ).replace( /[^a-z0-9]+/g, '-' ).replace( /(^-|-$)/g, '' );
}

export function getSectionLabel( section ) {
	return section?.subsection || section?.name || section?.label || '';
}

export function getDisplayLabel( value ) {
	const label = String( value || '' ).replace( /\s+/g, ' ' ).trim();

	if ( ! label ) {
		return '';
	}

	if ( GROUP_LABELS[ label ] ) {
		return GROUP_LABELS[ label ];
	}

	if ( GROUP_LABELS[ label.toUpperCase() ] ) {
		return GROUP_LABELS[ label.toUpperCase() ];
	}

	if ( label !== label.toUpperCase() ) {
		return label;
	}

	const acronyms = {
		api: 'API',
		css: 'CSS',
		gdpr: 'GDPR',
		html: 'HTML',
		id: 'ID',
		js: 'JS',
		pdf: 'PDF',
		sms: 'SMS',
		url: 'URL',
		vat: 'VAT',
	};

	return label.toLowerCase().replace( /\b[a-z0-9]+\b/g, ( word ) => (
		acronyms[ word ] || word.charAt( 0 ).toUpperCase() + word.slice( 1 )
	) );
}

export function getSectionGroup( section ) {
	return section?.group || section?.parentAccordion || 'General';
}

export function getControlSectionName( control ) {
	return control?.subsection || control?.section || '';
}

export function getControlsList( controls ) {
	return Object.values( controls )
		.flatMap( ( control ) => ( Array.isArray( control ) ? control : [ control ] ) )
		.filter( Boolean )
		.sort( ( left, right ) => left.sortOrder - right.sortOrder );
}

export function getControlStateKey( control ) {
	return `${ control.key || 'control' }::${ control.sortOrder || 0 }`;
}

export function getSubsectionDomId( section ) {
	return `scc-settings-subsection-${ slugify( getSectionLabel( section ) ) }`;
}

export function getControlLabel( control ) {
	return control.label || control.name || control.id || control.key || 'Untitled field';
}

export function getDatasetValue( control, key ) {
	if ( ! control?.dataset ) {
		return undefined;
	}

	if ( Object.prototype.hasOwnProperty.call( control.dataset, key ) ) {
		return control.dataset[ key ];
	}

	const snakeCaseKey = key.replace( /[A-Z]/g, ( match ) => `_${ match.toLowerCase() }` );
	return control.dataset[ snakeCaseKey ];
}

export function isWebhookControl( control ) {
	return String( control?.classes || '' ).split( /\s+/ ).includes( 'webhook-setup' );
}

export function isCustomJsControl( control ) {
	return String( control?.classes || '' ).split( /\s+/ ).includes( 'custom-js-setup' );
}

export function getWebhookSettingKey( control ) {
	const eventType = getDatasetValue( control, 'eventType' );

	if ( eventType === 'quote-fillup' ) {
		return 'scc_set_webhook_quote';
	}

	if ( eventType === 'detail-btn' ) {
		return 'scc_set_webhook_detail_view';
	}

	return '';
}

function getCustomJsSettingKey( control ) {
	if ( control?.id === 'scc_set_customJs_quote' || control?.name === 'scc_set_customJs_quote' ) {
		return 'scc_set_customJs_quote';
	}

	if ( control?.id === 'scc_set_customJs_detail_view' || control?.name === 'scc_set_customJs_detail_view' ) {
		return 'scc_set_customJs_detail_view';
	}

	return '';
}

function getWebhookEndpointFromConfig( webhookSettingKey ) {
	const webhookConfig = window.sccBackendStore?.config?.webhookConfig;
	if ( ! webhookSettingKey || ! Array.isArray( webhookConfig ) ) {
		return '';
	}

	const webhookSetting = webhookConfig.find( ( setting ) => Object.prototype.hasOwnProperty.call( setting, webhookSettingKey ) );
	return webhookSetting?.[ webhookSettingKey ]?.webhook || '';
}

function getCustomJsEnabledFromConfig( customJsSettingKey ) {
	const customJsConfig = window.sccBackendStore?.config?.customJsConfig;
	if ( ! customJsSettingKey || ! Array.isArray( customJsConfig ) ) {
		return false;
	}

	const customJsSetting = customJsConfig.find( ( setting ) => Object.prototype.hasOwnProperty.call( setting, customJsSettingKey ) );
	return Boolean( customJsSetting?.[ customJsSettingKey ]?.enabled );
}

export function getControlValue( control ) {
	if ( control.controlType === 'callback' && isWebhookControl( control ) ) {
		return getWebhookEndpointFromConfig( getWebhookSettingKey( control ) );
	}

	if ( control.type === 'checkbox' && isCustomJsControl( control ) ) {
		return getCustomJsEnabledFromConfig( getCustomJsSettingKey( control ) );
	}

	if ( control.type === 'checkbox' ) {
		return Boolean( control.checked );
	}

	if ( Array.isArray( control.selectedValue ) ) {
		return control.selectedValue;
	}

	if ( control.selectedValue !== null && control.selectedValue !== undefined ) {
		return control.selectedValue;
	}

	if ( control.value !== null && control.value !== undefined ) {
		return control.value;
	}

	if ( control.defaultValue !== null && control.defaultValue !== undefined ) {
		return control.defaultValue;
	}

	return control.type === 'select-multiple' ? [] : '';
}

export function getCurrentControlValue( control, controlValues ) {
	const controlStateKey = getControlStateKey( control );
	return Object.prototype.hasOwnProperty.call( controlValues, controlStateKey ) ? controlValues[ controlStateKey ] : getControlValue( control );
}

export function getGroupLabel( groupName ) {
	return getDisplayLabel( groupName );
}

export function buildGroupMap( schema ) {
	const groups = [];
	const seenGroups = {};

	( schema.sections || [] ).forEach( ( section ) => {
		const groupName = getSectionGroup( section );

		if ( ! seenGroups[ groupName ] ) {
			seenGroups[ groupName ] = {
				key: slugify( groupName ),
				name: groupName,
				label: getGroupLabel( groupName ),
				sections: [],
				sortOrder: section.sortOrder || 0,
			};
			groups.push( seenGroups[ groupName ] );
		}

		seenGroups[ groupName ].sections.push( section );
	} );

	return groups.sort( ( left, right ) => left.sortOrder - right.sortOrder );
}

export function buildSettingsModalState( controls, controlValues ) {
	const lookup = {};

	controls.forEach( ( control ) => {
		[ control.name, control.id, control.key ].filter( Boolean ).forEach( ( identifier ) => {
			if ( ! lookup[ identifier ] ) {
				lookup[ identifier ] = [];
			}

			lookup[ identifier ].push( control );
		} );
	} );

	const resolveControl = ( identifier ) => lookup[ identifier ]?.[ 0 ] || null;

	return {
		hasControl( identifier ) {
			return Boolean( resolveControl( identifier ) );
		},
		getControl( identifier ) {
			return resolveControl( identifier );
		},
		getValue( identifier, fallback = '' ) {
			const control = resolveControl( identifier );
			if ( ! control ) {
				return fallback;
			}

			const currentValue = getCurrentControlValue( control, controlValues );
			return currentValue === undefined ? fallback : currentValue;
		},
		isChecked( identifier, fallback = false ) {
			const control = resolveControl( identifier );
			if ( ! control ) {
				return fallback;
			}

			return Boolean( getCurrentControlValue( control, controlValues ) );
		},
	};
}

export function getControlsForSubsection( controls, subsectionName ) {
	return getControlsList( controls )
		.filter( ( control ) => getControlSectionName( control ) === subsectionName && control.type !== 'hidden' && control.visible !== false );
}

export function getSelectValue( value ) {
	if ( value === null || value === undefined ) {
		return '';
	}

	return String( value );
}

export function getTextValue( value ) {
	if ( value === null || value === undefined ) {
		return '';
	}

	return String( value );
}

export function getHelpText( control ) {
	if ( control.placeholder ) {
		return control.placeholder;
	}

	if ( control.dataset && control.dataset.placeholder ) {
		return control.dataset.placeholder;
	}

	return '';
}

export function invokeControlCallback( control ) {
	if ( ! control ) {
		return;
	}

	if ( control.callback === 'triggerLegacyControlClick' ) {
		const classNames = String( control.classes || '' )
			.split( /\s+/ )
			.filter( Boolean )
			.filter( ( className ) => className !== 'disabled' && className !== 'material-icons' );
		const eventType = getDatasetValue( control, 'eventType' );
		const selector = classNames.length ? `${ control.tagName || 'i' }.${ classNames.join( '.' ) }` : '';
		const candidates = selector ? Array.from( document.querySelectorAll( selector ) ) : [];
		const targetNode = candidates.find( ( node ) => {
			if ( ! eventType ) {
				return true;
			}

			return node.dataset?.eventType === eventType;
		} );

		if ( targetNode ) {
			targetNode.dispatchEvent(
				new MouseEvent( 'click', {
					bubbles: true,
					cancelable: true,
					view: window,
				} ),
			);
		}

		return;
	}

	if ( ! control.callback || typeof window[ control.callback ] !== 'function' ) {
		return;
	}

	window[ control.callback ]();
}
