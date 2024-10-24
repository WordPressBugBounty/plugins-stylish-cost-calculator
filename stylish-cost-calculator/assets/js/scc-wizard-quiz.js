let svgCollection = [];

const modalLeads = {
	1: 'What are you <span style="color:#314af3;">selling</span>?',
	2: 'Choose one or <u>more</u> <span style="color:#314af3;">pricing structures</span>',
	3: 'Choose one or <u>more</u> <span style="color:#314af3;">use cases</span>',
	4: 'Choose one or <u>more</u> <span style="color:#314af3;">unique needs</span>',
	5: 'Choose one or <u>more</u> <span style="color:#314af3;">unique needs</span> (part 2)',
};

window.addEventListener( 'DOMContentLoaded', ( event ) => {
	// removing WordPress's forms.css file, for
	// document.getElementById('forms-css')?.remove();
	svgCollection = JSON.parse( document.getElementById( 'svgCollection' )?.textContent || '[]' );
	// parsing query parameters
	const urlParams = new URLSearchParams( window.location.search );
	if ( urlParams.has( 'open-wizard' ) ) {
		document.querySelector( '[data-btn-action="startSetupWizard"]' ).click();
	}

	const choicesData = JSON.parse( document.querySelector( '#choices-data' )?.textContent || '[]' );
	if ( window.location.search === '?page=scc-list-all-calculator-forms' ) {
		return;
	}
	window.choicesData = choicesData;
	const choicesBySteps = Object.keys( choicesData ).filter( ( z ) => z.startsWith( 'step' ) && z !== 'stepResult' && z !== 'step1' ).map( ( x ) => choicesData[ x ].map( ( q ) => q.key ) );
	window.choicesBySteps = choicesBySteps;
	const choicesByStepNames = {};
	window.choicesByStepNames = choicesByStepNames;
	choicesByStepNames[ 'Pricing Structure' ] = choicesBySteps[ 0 ];
	choicesByStepNames[ 'Use Cases' ] = choicesBySteps[ 1 ];
	choicesByStepNames[ 'Unique Needs' ] = [ ...choicesBySteps[ 2 ], ...choicesBySteps[ 3 ] ];
	const suggestionsByStep = {
		'Unique Needs': [],
		'Use Cases': [],
		'Pricing Structure': [],
	};
	window.suggestionsByStep = suggestionsByStep;
	const step2results = [ {
		choiceKey: 'straight-forward',
		feats: [],
		elements: [ 'dropdown-element', 'simple-buttons-element', 'slider-element' ],
	},
	{
		choiceKey: 'bulk-pricing',
		feats: [ 'use-cost-per-unit' ],
		elements: [ 'slider-with-bulk-or-sliding-pricing-element' ],
	},
	{
		choiceKey: 'mandatory-fees',
		feats: [ 'mandatory-fees' ],
		elements: [],
	},
	{
		choiceKey: 'need-complex-math',
		feats: [],
		elements: [ 'variable-math-element' ],
	},
	{
		choiceKey: 'need-to-apply-a-percentage',
		feats: [ 'need-to-apply-a-percentage' ],
		elements: [ 'custom-math-element' ],
	},
	{
		choiceKey: 'need-to-trigger-a-fee-or-discount',
		feats: [],
		elements: [ 'custom-math-with-cl-trigger-element' ],
	} ];
	window.step2results = step2results;
	const step3results = [ {
		choiceKey: 'lead-gen-user-enters-contact-to-see-final-price',
		feats: [ 'turn-off-detailed-list', 'turn-off-total-price' ],
		elements: [],
	},
	{
		choiceKey: 'send-email-quotes-pdf',
		feats: [
			'email-quote-primary-cta',
			'email-quote-custom-outgoing-message',
			'use-quote-management-screen',
			'use-live-currency-conversion',
		],
		elements: [
			'comment-box-element',
			'dropdown-element',
			'text-html-element',
			'slider-element',
		],
	},
	{
		choiceKey: 'lead-gen-user-can-email-total',
		feats: [ 'email-quote-primary-cta', 'email-quote-custom-outgoing-message' ],
		elements: [],
	},
	{
		choiceKey: 'e-comm',
		feats: [ 'woocommerce', 'stripe', 'paypal' ],
		elements: [ 'image-btn-w-qtn-sel-element' ],
	},
	{
		choiceKey: 'internal-tool',
		feats: [ 'internal-tool' ],
		elements: [],
	},
	{
		choiceKey: 'prod-config',
		feats: [],
		elements: [ 'slider-element' ],
	} ];
	window.step3results = step3results;
	const step4results = [
		{
			choiceKey: 'conditional-logic',
			feats: [ 'conditional-logic' ],
			elements: [],
		},
		{
			choiceKey: 'lead-gen-two-way-sms',
			feats: [ 'sms-feature' ],
			elements: [],
		},
		{
			choiceKey: 'multi-step',
			feats: [ 'activate-multiple-step', 'activate-accordion' ],
			elements: [],
		},
		{
			choiceKey: 'international-customers',
			feats: [ 'use-live-currency-conversion' ],
			elements: [],
		},
		{
			choiceKey: 'automation',
			feats: [ 'use-webhooks' ],
			elements: [],
		},
		{
			choiceKey: 'competitor-comparison',
			feats: [ 'use-custom-totals' ],
			elements: [],
		},
		{
			choiceKey: 'lead-management',
			feats: [ 'quotes-n-leads-dashboard' ],
			elements: [],
		},
		{
			choiceKey: 'stylish',
			feats: [ 'stylish' ],
			elements: [],
		},
		{
			choiceKey: 'coupons',
			feats: [ 'use-coupon-code-btn' ],
			elements: [],
		},
		{
			choiceKey: 'stats-n-conversion-tracking',
			feats: [ 'lead-source-analytics', 'form-conversion-analytics' ],
			elements: [],
		},
		{
			choiceKey: 'analytical-ai',
			feats: [ 'detailed-list' ],
			elements: [ 'slider-element' ],
		},
		{
			choiceKey: 'set-minimum-total',
			feats: [ 'use-minimum-total-feature' ],
			elements: [],
		},
		{
			choiceKey: 'shipping-rates-calculator',
			feats: [],
			elements: [ 'shipping-rates-calculator', 'distance-element' ],
		},
		{
			choiceKey: 'upsells-n-cross-sales',
			feats: [],
			elements: [ 'image-btn-w-qtn-sel-element', 'img-btn-element' ],
		},
		{
			choiceKey: 'add-clarity-credibility-reduce-friction',
			feats: [],
			elements: [ 'slider-element' ],
		},
		{
			choiceKey: 'file-uploads',
			feats: [],
			elements: [ 'file-upload-element' ],
		},
		{
			choiceKey: 'date-picker',
			feats: [],
			elements: [ 'date-picker-element' ],
		},
		{
			choiceKey: 'user-inputs',
			feats: [],
			elements: [ 'comment-box-element' ],
		},
		{
			choiceKey: 'conditional-messages-n-alerts',
			feats: [ 'conditional-logic' ],
			elements: [ 'html-box-w-cl-element' ],
		},
	];
	window.step4results = step4results;
	const choicesSuggestionMap = [ ...step2results, ...step3results, ...step4results ];
	window.choicesSuggestionMap = choicesSuggestionMap;

	window.quizAnswersStore = {};
	Object.keys( choicesData ).forEach( ( step ) => {
		quizAnswersStore[ step ] = {};
		choicesData[ step ].forEach( ( stepChoices ) => {
			if ( stepChoices.key == 'others' ) {
				quizAnswersStore[ step ][ stepChoices.key ] = '';
				return;
			}
			quizAnswersStore[ step ][ stepChoices.key ] = false;
		} );
	} );
} );

// function filterResultPageElementSuggestion(elements) {
// 	return elements;
// }
// https://dirask.com/posts/JavaScript-UUID-function-in-Vanilla-JS-1X9kgD
const UUIDv4 = new function() {
	const generateNumber = ( limit ) => {
		const value = limit * Math.random();
		return value | 0;
	};
	const generateX = () => {
		const value = generateNumber( 16 );
		return value.toString( 16 );
	};
	const generateXes = ( count ) => {
		let result = '';
		for ( let i = 0; i < count; ++i ) {
			result += generateX();
		}
		return result;
	};
	const generateVariant = () => {
		const value = generateNumber( 16 );
		const variant = ( value & 0x3 ) | 0x8;
		return variant.toString( 16 );
	};
	// UUID v4
	//
	//   varsion: M=4
	//   variant: N
	//   pattern: xxxxxxxx-xxxx-Mxxx-Nxxx-xxxxxxxxxxxx
	//
	this.generate = function() {
		const result = generateXes( 8 ) +
            '-' + generateXes( 4 ) +
            '-' + '4' + generateXes( 3 ) +
            '-' + generateVariant() + generateXes( 3 ) +
            '-' + generateXes( 12 );
		return result;
	};
};

function filterResultPageSuggestions( data ) {
	const chosenOptions = Object.values( quizAnswersStore )
		.map( ( quizStep ) => Object.entries( quizStep ) )
		.flat().filter( ( answerKV ) => answerKV[ 1 ] )
		.map( ( pickedChoice ) => pickedChoice[ 0 ] );
	const suggestionsAvailable = getFeaturesAndElementsByOptions( chosenOptions );

	const features = data.allFeatureSuggestions.filter( ( feature ) => suggestionsAvailable.features.includes( feature.key ) );
	const elements = data.allElementSuggestions.filter( ( element ) => suggestionsAvailable.elements.includes( element.key ) );

	// preparing the result modal data after the filtering

	data.choices = features;
	data.elementSuggestions = elements;
	data.elementsByChoice = suggestionsAvailable.elementsByChoice;
	data.featuresByChoice = suggestionsAvailable.featuresByChoice;

	return data;
	// return features.filter(feature => featsAvailable.includes(feature.key));
}

const getFeaturesAndElementsByOptions = ( optionsChosen ) => {
	const features = [];
	const elements = [];
	const elementsByChoice = {};
	const featuresByChoice = {};
	const elementsByStep = {};
	const featuresByStep = {};
	optionsChosen.forEach( ( optKey, index ) => {
		if ( ! featuresByChoice[ optKey ] ) {
			featuresByChoice[ optKey ] = [];
		}
		if ( ! elementsByChoice[ optKey ] ) {
			elementsByChoice[ optKey ] = [];
		}
		const suggestionForChoice = choicesSuggestionMap.find( ( suggestion ) => suggestion.choiceKey == optKey );
		if ( suggestionForChoice ) {
			suggestionForChoice.feats.forEach( ( feature ) => {
				features.push( feature );
				const category = suggestionsByStep[ findCategory( optKey, choicesByStepNames ) ];
				if ( category ) {
					// push the element to category if not already present
					if ( ! category[ feature ] ) {
						category.push( feature );
					}
				}
				featuresByChoice[ optKey ].push( feature );
			} );
			suggestionForChoice.elements.forEach( ( element ) => {
				elements.push( element );
				const category = suggestionsByStep[ findCategory( optKey, choicesByStepNames ) ];
				if ( category ) {
					// push the element to category if not already present
					if ( ! category[ element ] ) {
						category.push( element );
					}
				}
				elementsByChoice[ optKey ].push( element );
			} );
		}
	} );

	// filtering out empty elements in elementsByChoice and featuresByChoice
	Object.keys( elementsByChoice ).forEach( ( choiceKey ) => {
		if ( elementsByChoice[ choiceKey ].length == 0 ) {
			delete elementsByChoice[ choiceKey ];
		}
	} );
	Object.keys( featuresByChoice ).forEach( ( choiceKey ) => {
		if ( featuresByChoice[ choiceKey ].length == 0 ) {
			delete featuresByChoice[ choiceKey ];
		}
	} );

	return { features, elements, elementsByChoice, featuresByChoice };
};

function findCategory( term, obj ) {
	for ( const key in obj ) {
		if ( obj[ key ].includes( term ) ) {
			return key;
		}
	}
	return null; // Return null if the term isn't found in any category
}


async function updateWizardQuizStorageData( data, newCalcId ) {
	const wizardData = JSON.parse( await localStorage.getItem( 'wizardQuizData' ) ) || [];
	// add current unix timestamp
	data.timestamp = Math.floor( Date.now() / 1000 );
	const evaluationConditions = [ ...choicesData.elementSuggestions, ...choicesData.stepResult ].map( ( z ) => {
		return { [ z.key ]: z.evaluateAsDoneConditions };
	} );
	wizardData.push( { ...data, ...{ calcId: newCalcId }, evaluationConditions } );
	await localStorage.setItem( 'wizardQuizData', JSON.stringify( wizardData ) );
}


function getChoicesByStep( stepNumber ) {
	if (typeof choicesData !== 'object' || choicesData === null) {
        return null;
    }
    const key = 'step' + stepNumber;
    if (!(key in choicesData)) {
        return null;
    }
    return choicesData[key];
}

function getTemplateTypeByStep( stepNumber ) {
	if ( [ 'Result', 1 ].includes( stepNumber ) ) {
		return 'quiz-columned-card-choices-content';
	}
	return 'quiz-choices-content';
}

function buildChoicesContent( step ) {
	let templateData = {
		step,
	};
	if ( step !== 'Result' ) {
		templateData = {
			...templateData,
			choices: getChoicesByStep( step ),
		};
	}
	if ( step == 'Result' ) {
		// templateData.choices
		templateData = {
			...templateData,
			allFeatureSuggestions: getChoicesByStep( step ),
			allElementSuggestions: choicesData.elementSuggestions,
		};
	}
	return jQuery( wp.template( getTemplateTypeByStep( step ) )( templateData ) );
}

// Function to trigger 'change' event on checkbox input using vanilla JS
function triggerCheckboxChange( checkboxElement ) {
	const event = new Event( 'change' );
	checkboxElement.checked = true;
	checkboxElement.dispatchEvent( event );
}

const initiateIndustryChoices = () => {
	const industryChoicesNode = document.querySelector( '#industryTypeWrapper input' );
	if ( ! industryChoicesNode?.tomselect ) {
		new TomSelect( industryChoicesNode, {
			maxItems: 1,
			valueField: 'value',
			labelField: 'title',
			searchField: 'title',
			options: [
				'Web Services',
				'Business Services',
				'Domestic Services',
				'Construction & Maintenance',
				'Printing & Publishing',
				'Home Improvement',
				'Education',
				'Apparel',
				'Vehicle Parts & Services',
				'Health',
				'Software',
				'Visual Art & Design',
				'Travel',
				'Accounting & Auditing',
				'Yard & Patio',
				'Music & Audio',
				'Special Occasions',
				'Consumer Electronics',
				'Home Furnishings',
				'Gardening & Landscaping',
				'Energy & Utilities',
				'Restaurants',
				'Finance',
				'Entertainment Industry',
				'Fitness',
				'Online Communities',
				'Photography & Video Services',
				'Business Operations',
				'Social Issues & Advocacy',
				'Water Activities',
				'Education',
				'Home Swimming Pools',
				'Saunas & Spas',
				'Networking',
				'Food',
				'Legal',
				'Consumer Resources',
				'Gifts & Special Event Items',
				'Science',
				'Public Safety',
				'Blogging Resources & Services',
				'Beauty & Fitness',
				'Electronics & Electrical',
				'Business & Industrial',
				'Home Furnishings',
				'Credit & Lending',
				'Visual Art & Design',
				'Manufacturing',
				'Music & Audio',
				'Home Storage & Shelving',
			].map( ( industry ) => ( { title: industry, value: industry } ) ),
			create: false,
		} );
	}
};

function showModal( modalElementSelector, modalContentData, isFirstModal = false ) {
	const { currentStep } = modalContentData;
	const modalNode = jQuery( document.getElementById( modalElementSelector ) );
	const modalContent = jQuery( wp.template( 'quiz-modal-content' )( modalContentData ) );
	const choicesWrapper = modalContent.find( '.choices-wrapper' );
	// cleaning up previous content inside the modal body, if it was used earlier
	const modalExistingContent = modalNode[ 0 ]?.children;
	if ( modalExistingContent && modalExistingContent.length > 0 ) {
		[ ...modalExistingContent ].forEach( ( fragment ) => {
			fragment.remove();
		} );
	}
	try{
		const choicesContent = buildChoicesContent( currentStep );

		// registering tooltip for the modal contents
		choicesContent.find( '[title]' ).each( ( index, element ) => {
			const tooltip = new bootstrap.Tooltip( element );
		} );
		choicesWrapper.append( choicesContent );
		modalNode.append( modalContent );
		if ( isFirstModal ) {
			const cardChoices = modalNode.find( '.card' );
			cardChoices.attr( 'data-next-step', 2 );
			cardChoices.attr( 'data-max-steps', 5 );
			modalNode.find( '#scc-setup-wizard-first-step-next-btn' ).on( 'click', handleQuizBtnClick );
		}
		const modalActionBtn = modalNode.find( '.scc-setup-wizard-button' );
		const modalInputFields = modalNode.find( 'input:not([data-element-suggestion]):not(:text)' );
		const modalInputElementSuggestions = modalNode.find( 'input[data-element-suggestion]' );
		modalActionBtn.on( 'click', handleQuizBtnClick );
		modalInputFields.on( 'change', ( evt ) => {
			if ( currentStep === 1 && evt.target.type === 'checkbox' ) {
				const businessNameWrapper = modalContent.find( '#businessNameWrapper' )[ 0 ];
				const businessDescriptionWrapper = modalContent.find( '#businessDescriptionWrapper' )[ 0 ];
				//const industryTypeWrapper = modalContent.find( '#industryTypeWrapper' )[ 0 ];
				const selectedChoices = [ ...evt.target.closest( '.row' ).querySelectorAll( 'input:checked' ) ].filter( ( z ) => z !== evt.target );
				// deselecting the other choices
				selectedChoices.forEach( ( choice ) => {
					choice.checked = false;
					quizAnswersStore[ 'step' + currentStep ][ choice.name ] = false;
				} );
				if ( selectedChoices.length > 0 ) {
					quizAnswersStore[ 'step' + currentStep ][ evt.target.name ] = true;
					return;
				}
				if ( evt.target.checked ) {
					businessNameWrapper.classList.remove( 'd-none' );
				} else {
					businessNameWrapper.classList.add( 'd-none' );
					businessDescriptionWrapper.classList.add( 'd-none' );
					//industryTypeWrapper.classList.add( 'd-none' );
					document.querySelector( '#scc-setup-wizard-first-step-next-btn' ).classList.add( 'd-none' );
				}
			}
			updateQuizAnswersStore( evt, 'step' + currentStep );
		} );
		modalNode.find( 'input:text,  textarea' ).each( ( index, element ) => {
			element.addEventListener( 'input', ( evt ) => {
				updateQuizAnswersStore( evt, 'step' + currentStep );
			} );
		} );
		modalInputElementSuggestions.on( 'change', ( evt ) => {
			updateQuizAnswersStore( evt, 'elementSuggestions' );
		} );
		// If the 'modalInputElementSuggestions' variable has length, it is a final result modal
		// And we set all of the choices to checked state
		if ( modalInputElementSuggestions.length > 0 ) {
			modalInputElementSuggestions.each( ( index, element ) => {
				triggerCheckboxChange( element );
			} );
			modalInputFields.each( ( index, element ) => {
				triggerCheckboxChange( element );
			} );
		}
		const quizModal = bootstrap.Modal.getOrCreateInstance( modalNode.get( 0 ) );
		quizModal.show();
		if ( currentStep === 1 ) {
			//initiateIndustryChoices();
		}
	} catch ( error ) {
		//console.error( error );
	}

}

function send_setup_wizard_data_and_build( srcBtn, filteredFeaturesAndSuggestions ) {
	const _quizAnswersStore = Object.assign( {}, quizAnswersStore );
	// renaming stepResult to featureSuggestions
	_quizAnswersStore.featureSuggestions = _quizAnswersStore.stepResult;
	delete _quizAnswersStore.stepResult;
	document.querySelector( '#new-calc-name' ).value = 'New Stylish Calculator';
	scc_create_new_calculator_by_quiz_results( filteredFeaturesAndSuggestions, _quizAnswersStore );
}

function handleQuizBtnClick( evt ) {
	const { currentTarget: nextBtn } = evt;
	const currentStep = Number( nextBtn.getAttribute( 'data-next-step' ) );
	const finalStep = Number( nextBtn.getAttribute( 'data-max-steps' ) );
	const modalNode = nextBtn.closest( '.modal' );
	const modalInstance = bootstrap.Modal.getInstance( modalNode );
	const isFinalStep = currentStep == finalStep;
	if ( ! isNaN( currentStep ) ) {
		modalInstance.hide();
	}
	if ( currentStep == 0 ) {
		const resultAction = nextBtn.getAttribute( 'data-result-action' );
		const formEmailFields = document.querySelector( '#wq_field_wrapper input[type="email"]' );
		const isEmailOptInEnabled = ( resultAction === 'email' ) ? true : false;
		// check if the `formEmailFields` is visible
		if ( ( ! isElementInView( document.querySelector( '.modal.show .modal-body' ), formEmailFields ) ) && isEmailOptInEnabled ) {
			emailResultsFormScrollToView( true );
			return;
		}
		// if the `wq_your_name` and the `wq_your_email` fields are empty, show the error message
		if ( isEmailOptInEnabled && ( ! document.querySelector( '#wq_field_wrapper input[type="text"]' ).value || ! document.querySelector( '#wq_field_wrapper input[type="email"]' ).value ) ) {
			emailResultsFormScrollToView( true );
			document.querySelector( '#wq_field_wrapper' ).classList.add( 'scc-wql-field-warnings' );
			return;
		}
		const buildCalculatorActionBtn = nextBtn;
		let templateData = {
			step: 'Result',
		};
		templateData = {
			...templateData,
			allFeatureSuggestions: getChoicesByStep( 'Result' ),
			allElementSuggestions: choicesData.elementSuggestions,
		};
		const filteredFeaturesAndSuggestions = filterResultPageSuggestions( templateData );
		filteredFeaturesAndSuggestions.elementSuggestions.forEach( ( element ) => {
			quizAnswersStore.elementSuggestions[ element.key ] = true;
		} );
		filteredFeaturesAndSuggestions.choices.forEach( ( feature ) => {
			quizAnswersStore.stepResult[ feature.key ] = true;
		} );
		const resultsEmailFormData = {
			optin: isEmailOptInEnabled,
			email: document.querySelector( '#wq_field_wrapper input[type="email"]' ).value,
			name: document.querySelector( '#wq_field_wrapper input[type="text"]' ).value,
		};
		send_setup_wizard_data_and_build( buildCalculatorActionBtn, { elementsByChoice: filteredFeaturesAndSuggestions.elementsByChoice, featuresByChoice: filteredFeaturesAndSuggestions.featuresByChoice, resultsEmailFormData } );
		return;
	}
	if ( isNaN( currentStep ) && ( typeof ( isInsideEditingPage ) !== 'undefined' && isInsideEditingPage() ) ) {
		const { currentCalculatorSetupWizardData } = sccBackendStore;
		let templateData = {
			step: 'Result',
		};
		templateData = {
			...templateData,
			allFeatureSuggestions: getChoicesByStep( 'Result' ),
			allElementSuggestions: choicesData.elementSuggestions,
		};
		const filteredFeaturesAndSuggestions = filterResultPageSuggestions( templateData );
		filteredFeaturesAndSuggestions.elementSuggestions.forEach( ( element ) => {
			quizAnswersStore.elementSuggestions[ element.key ] = true;
		} );
		filteredFeaturesAndSuggestions.choices.forEach( ( feature ) => {
			if ( ! Boolean( quizAnswersStore.stepResult ) ) {
				return;
			}
			quizAnswersStore.stepResult[ feature.key ] = true;
		} );
		const _quizAnswersStore = Object.assign( {}, quizAnswersStore );
		// renaming stepResult to featureSuggestions
		_quizAnswersStore.featureSuggestions = _quizAnswersStore.stepResult;
		delete _quizAnswersStore.stepResult;
		const results = { elementsByChoice: filteredFeaturesAndSuggestions.elementsByChoice, featuresByChoice: filteredFeaturesAndSuggestions.featuresByChoice };
		const evaluationConditions = [ ...choicesData.elementSuggestions, ...choicesData.stepResult ].map( ( z ) => {
			return { [ z.key ]: z.evaluateAsDoneConditions };
		} );
		const wizardData = { ...results, ...{ __quizAnswersStore: _quizAnswersStore }, ...suggestionsByStep, evaluationConditions };
		Object.keys( wizardData ).forEach( ( prop ) => {
			currentCalculatorSetupWizardData[ prop ] = wizardData[ prop ];
		} );
		const setupWizard = document.querySelector( '#floating-wizard-placeholder' );
		const setupWizardTemplate = wp.template( 'scc-editing-page-sidebar-wizard' );
		const suggestionsObject = { suggestions: [ ...currentCalculatorSetupWizardData[ 'Pricing Structure' ], ...currentCalculatorSetupWizardData[ 'Unique Needs' ], ...currentCalculatorSetupWizardData[ 'Use Cases' ] ], suggestionsConfig: [ ...choicesData.stepResult, ...choicesData.elementSuggestions ] };
		let suggestionsPair = [ ...new Set( suggestionsObject.suggestions ) ].map( ( x ) => {
			const suggestion = suggestionsObject.suggestionsConfig.find( ( q ) => q.key === x );
			if ( ! suggestion || suggestion?.showSuggestion === false ) {
				return null;
			}
			return {
				title: suggestion.choiceTitle ? ( suggestion.instructionText || suggestion.choiceTitle ) : '',
				key: x,
				href: suggestion.helpLink,
				hideCheckbox: suggestion?.isDetectable === false,
			};
		} );
		suggestionsPair = suggestionsPair.filter( Boolean );
		const setupWizardHtml = setupWizardTemplate( suggestionsPair.filter( ( z ) => z.title !== '' ) );
		setupWizard.innerHTML = setupWizardHtml;
		setupWizard.classList.remove( 'd-none' );
		const toCheckConditions = new Set( currentCalculatorSetupWizardData.evaluationConditions.map( ( x ) => {
			return Object.values( x ).flat();
		} ).flat() );
		sccBackendStore.toCheckConditions = toCheckConditions;
		sccAiUtils.toggleAiWizardPanel( null, sccAiUtils.aiWizardMenu );
		sccAiUtils.quizRetakeButton.textContent = 'Redo Setup Wizard';
		sccBackendUtils.syncWizardSuggestionsState( true );
		sccBackendUtils.updateFeaturesAndElementsUsage( 'init', 'check' );
		modalInstance.hide();
		if ( ( sccAiUtils.aiWizardStatus && sccAiUtils.aiWizardStatus === 'scc-ai-wizard-setup-wizard' ) || ! sccAiUtils.aiWizardStatus ) {
			sccAiUtils.aiWizardRequest( 'setup-wizard', true );
		} if ( sccAiUtils.aiWizardStatus && sccAiUtils.aiWizardStatus === 'scc-ai-wizard-optimize-form' ) {
			sccAiUtils.aiWizardRequest( 'optimize-form', true );
		}
		return 0;
	}
	if ( isNaN( currentStep ) ) {
		const buildCalculatorActionBtn = nextBtn;
		let templateData = {
			step: 'Result',
		};
		templateData = {
			...templateData,
			allFeatureSuggestions: getChoicesByStep( 'Result' ),
			allElementSuggestions: choicesData.elementSuggestions,
		};
		const filteredFeaturesAndSuggestions = filterResultPageSuggestions( templateData );
		filteredFeaturesAndSuggestions.elementSuggestions.forEach( ( element ) => {
			quizAnswersStore.elementSuggestions[ element.key ] = true;
		} );
		filteredFeaturesAndSuggestions.choices.forEach( ( feature ) => {
			quizAnswersStore.stepResult[ feature.key ] = true;
		} );

		send_setup_wizard_data_and_build( buildCalculatorActionBtn, { elementsByChoice: filteredFeaturesAndSuggestions.elementsByChoice, featuresByChoice: filteredFeaturesAndSuggestions.featuresByChoice } );
		return;
	}
	/* if ( isNaN( currentStep ) ) {
		// currentStep is 'Result', thus was evaluated as NaN
		currentStep = nextBtn.getAttribute( 'data-next-step' );
		showModal( 'quizResult', {
			title: 'AI-Powered Setup: Final Step',
			subtitle: `For a seamless setup, get your instructions via <strong>Email</strong> to gide you as you build, or <strong>Download a PDF</strong> to keep a permanent guide on hand. Both provide clear, step-by-step directions to perfect your calculator form.`,
			modalLead: '',
			currentStep,
			actionBtnTitle: 'Send My Recommendations',
			quizNextStep: 0,
			isFinalStep: true,
		} );
		return;
	} */
	showModal( 'quizModal' + currentStep, {
		title: 'AI-Powered Setup',
		subtitle: `Step ${ currentStep } of 5`,
		modalLead: modalLeads[ currentStep ],
		currentStep,
		actionBtnTitle: isFinalStep ? 'Finish' : 'Next',
		quizNextStep: isFinalStep ? 'Result' : currentStep + 1,
		isFinalStep,
	} );
	return 0;
}


function handleBackNavigation( currentStep, backBtn ) {
	const isFinalStep = currentStep == 5;
	const isFirstModal = currentStep == 1;
	const templateId = isFirstModal ? 'quizModal' : 'quizModal' + currentStep;

	const modalNode = backBtn.closest( '.modal' );
	const modalInstance = bootstrap.Modal.getInstance( modalNode );
	modalInstance.hide();

	showModal( templateId, {
		title: 'AI-Powered Setup',
		subtitle: `Step ${ currentStep } of 5`,
		modalLead: modalLeads[ currentStep ],
		currentStep,
		actionBtnTitle: isFinalStep ? 'Finish' : 'Next',
		quizNextStep: isFinalStep ? 'Result' : currentStep + 1,
		isFinalStep,
	}, isFirstModal );
}


function updateQuizAnswersStore( evt, inputOriginStep ) {
	const { currentTarget: inputField } = evt;
	if ( inputField.name == 'others' ) {
		// revealing the input field to define the others
		const defineOthersInput = document.querySelector( `[name="${ inputOriginStep }-othersInput"]` );
		const defineOthersInputWrapper = defineOthersInput.closest( '.form-check' );
		defineOthersInputWrapper.classList.toggle( 'd-none' );
		defineOthersInputWrapper.value = '';
		// scroll to the input field
		defineOthersInput.scrollIntoView( { behavior: 'smooth' } );
		// adding cursor focus to the input field
		defineOthersInput.focus();
		// adding event listener to the input field
		if ( defineOthersInput.getAttribute( 'data-event-registered' ) == 'true' ) {
			return;
		}
		defineOthersInput.addEventListener( 'change', ( evt ) => {
			quizAnswersStore[ inputOriginStep ][ inputField.name ] = evt.currentTarget.value;
		} );
		defineOthersInput.setAttribute( 'data-event-registered', 'true' );

		return;
	}
	quizAnswersStore[ inputOriginStep ][ inputField.name ] = inputField.type === 'checkbox' ? inputField.checked : inputField.value;
	if ( inputOriginStep === 'step1' ) {
		if ( inputField.name === 'business-name' && inputField.value.length > 0 ) {
			document.querySelector( '#businessDescriptionWrapper' ).classList.remove( 'd-none' );
		}
		if ( inputField.name === 'business-name' && inputField.value.length === 0 ) {
			document.querySelector( '#businessDescriptionWrapper' ).classList.add( 'd-none' );
		}
		const bothFieldsFulfilled = quizAnswersStore[ inputOriginStep ][ 'business-name' ]?.length && quizAnswersStore[ inputOriginStep ][ 'business-description' ]?.length;
		if ( bothFieldsFulfilled ) {
			document.querySelector( '#scc-setup-wizard-first-step-next-btn' ).classList.remove( 'd-none' );
		} else {
			document.querySelector( '#scc-setup-wizard-first-step-next-btn' ).classList.add( 'd-none' );
		}
	}
}

function showTemplateChoices() {
	welcomeSection.classList.add( 'd-none' );
	pageWrapper.classList.add( 'd-none' );
	templatePickerFragment.classList.remove( 'd-none' );
}

function startSetupWizard() {
	const currentStep = 1;
	isFinalStep = false;
	showModal( 'quizModal', {
		title: 'AI-Powered Setup',
		subtitle: `Step ${ currentStep } of 5`,
		modalLead: modalLeads[ currentStep ],
		currentStep,
		quizNextStep: isFinalStep ? 'Result' : currentStep + 1,
		isFinalStep,
	}, true );
	const wrapper = document.querySelector( '#wpwrap' );
	if ( wrapper ) {
		wrapper.classList.add( 'scc-p-relative' );
	}
	sccAiAssistedSetupWizUpdateProgress();
}

function sccAiAssistedSetupWizUpdateProgress() {
    const textarea = document.getElementById('scc-ai-assisted-setup-wiz-business-description');
    const progressBar = document.getElementById('scc-ai-assisted-setup-wiz-progress-bar');
    const nextButton = document.getElementById('scc-setup-wizard-first-step-next-btn');
    const charCount = textarea?.value?.length || 0;

    let progress = (charCount / 100) * 100;
    progress = Math.min(progress, 100);

    if (progressBar && progressBar.style) {
        progressBar.style.width = `${progress}%`;
    }

    if (progressBar && progressBar.classList && nextButton) {
        progressBar.classList.remove('scc-ai-count-red', 'scc-ai-count-orange', 'scc-ai-count-green');

        if (charCount < 33) {
            progressBar.classList.add('scc-ai-count-red');
            if (nextButton) {
                nextButton.disabled = true;
            }
        } else if (charCount >= 33 && charCount < 66) {
            progressBar.classList.add('scc-ai-count-orange');
            if (nextButton) {
                nextButton.disabled = false;
            }
        } else if (charCount >= 66) {
            progressBar.classList.add('scc-ai-count-green');
            if (nextButton) {
                nextButton.disabled = false;
            }
        }
    }
}