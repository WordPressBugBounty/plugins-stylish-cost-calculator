let svgCollection = [];

const modalLeads = {
	1: 'What are you <span style="color:#314af3;">selling</span>?',
	2: 'Choose one or <u>more</u> <span style="color:#314af3;">pricing structures</span>',
	3: 'Choose one or <u>more</u> <span style="color:#314af3;">use cases</span>',
	4: 'Choose one or <u>more</u> <span style="color:#314af3;">unique needs</span>',
	5: 'Choose one or <u>more</u> <span style="color:#314af3;">unique needs</span> (part 2)',
};

// Initialize choicesData early to prevent null reference errors
let choicesData = {};

// Function to safely initialize choicesData
const initializeChoicesData = () => {
	const choicesDataElement = document.querySelector('#choices-data');
	if (choicesDataElement && choicesDataElement.textContent) {
		try {
			choicesData = JSON.parse(choicesDataElement.textContent);
			window.choicesData = choicesData;
			return true;
		} catch (error) {
			console.error('SCC Wizard: Error parsing choices data:', error);
			choicesData = {};
		}
	}
	return false;
};

// Function to wait for choices-data element and initialize
const waitForChoicesData = (callback, maxAttempts = 10, attempt = 1) => {
	if (initializeChoicesData()) {
		callback();
		return;
	}
	
	if (attempt >= maxAttempts) {
		console.warn('SCC Wizard: choices-data element not found after', maxAttempts, 'attempts');
		return;
	}
	
	// Wait 100ms and try again
	setTimeout(() => {
		waitForChoicesData(callback, maxAttempts, attempt + 1);
	}, 100);
};

// Main initialization function
const initializeWizard = () => {
	// Early check - if no choices-data element exists at all, skip initialization
	if (!document.querySelector('#choices-data')) {
		return;
	}
	
	// Wait for choices data to be available and properly parsed
	waitForChoicesData(() => {
		// Continue with the rest of the initialization
		initializeWizardComponents();
	});
};

// Function to initialize wizard components after data is ready
const initializeWizardComponents = () => {
	const choicesData = window.choicesData;
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
	svgCollection = JSON.parse( document.getElementById( 'svgCollection' )?.textContent || '[]' );
	window.svgCollection = svgCollection;
}

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
	
	// Safely handle choicesData properties that might not exist
	const elementSuggestions = (choicesData && Array.isArray(choicesData.elementSuggestions)) ? choicesData.elementSuggestions : [];
	const stepResult = (choicesData && Array.isArray(choicesData.stepResult)) ? choicesData.stepResult : [];
	
	const evaluationConditions = [ ...elementSuggestions, ...stepResult ].map( ( z ) => {
		return { [ z.key ]: z.evaluateAsDoneConditions };
	} );
	wizardData.push( { ...data, ...{ calcId: newCalcId }, evaluationConditions } );
	await localStorage.setItem( 'wizardQuizData', JSON.stringify( wizardData ) );
}


function getChoicesByStep( stepNumber ) {
	// Ensure choicesData is available and properly initialized
	if (typeof choicesData !== 'object' || choicesData === null) {
        console.warn('SCC Wizard: choicesData is not properly initialized:', choicesData);
        return [];
    }
    
    const key = 'step' + stepNumber;
    if (!(key in choicesData)) {
        console.warn('SCC Wizard: Step data not found for key:', key, 'Available keys:', Object.keys(choicesData));
        return [];
    }
    
    const stepData = choicesData[key];
    if (!Array.isArray(stepData)) {
        console.warn('SCC Wizard: Step data is not an array for key:', key, 'Data:', stepData);
        return [];
    }
    
    return stepData;
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
		const choices = getChoicesByStep( step );
		templateData = {
			...templateData,
			choices: choices || [], // Ensure choices is always an array
		};
	}
	if ( step == 'Result' ) {
		// templateData.choices
		const allFeatureSuggestions = getChoicesByStep( step );
		const elementSuggestions = (choicesData && Array.isArray(choicesData.elementSuggestions)) ? choicesData.elementSuggestions : [];
		templateData = {
			...templateData,
			allFeatureSuggestions: allFeatureSuggestions || [], // Ensure it's always an array
			allElementSuggestions: elementSuggestions, // Ensure it's always an array
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
	const choicesContent = buildChoicesContent( currentStep );
	// registering tooltip for the modal contents
	choicesContent.find( '[title]' ).each( ( index, element ) => {
		const tooltip = new bootstrap.Tooltip( element );
	} );
	choicesWrapper.append( choicesContent );
	modalNode.append( modalContent );

	if ( currentStep === 1 ) {
		// Pre-fill Website URL
		const websiteUrlInput = modalNode.find('#websiteUrl')[0];
		if (websiteUrlInput && !websiteUrlInput.value && typeof quizAnswersStore !== 'undefined' && quizAnswersStore.step1 && !quizAnswersStore.step1['website-url']) {
			websiteUrlInput.value = window.location.origin;
			quizAnswersStore.step1['website-url'] = window.location.origin;
			// Also update the store immediately
			updateQuizAnswersStore({ currentTarget: websiteUrlInput }, 'step1');
		}

		// Pre-fill Business Name from Domain
		const businessNameInput = modalNode.find('#businessName')[0];
		if (businessNameInput && !businessNameInput.value && typeof quizAnswersStore !== 'undefined' && quizAnswersStore.step1 && !quizAnswersStore.step1['business-name']) {
			let domain = window.location.hostname;
			// Remove www.
			domain = domain.replace(/^www\./, '');
			// Remove TLD (.com, .org, etc.)
			const parts = domain.split('.');
			if (parts.length > 1) {
				parts.pop(); // Remove the last part (TLD)
				domain = parts.join('.');
			}
			// Replace hyphens with spaces and capitalize
			let businessName = domain.replace(/-/g, ' ');
			businessName = businessName.charAt(0).toUpperCase() + businessName.slice(1);
			// Capitalize after spaces
			businessName = businessName.replace(/\s(.)/g, function(match, char) {
				return ' ' + char.toUpperCase();
			});

			businessNameInput.value = businessName;
			quizAnswersStore.step1['business-name'] = businessName;
			// Also update the store immediately
			updateQuizAnswersStore({ currentTarget: businessNameInput }, 'step1');
		}

		// Initial check for AI button visibility after pre-filling
		updateQuizAnswersStore({ currentTarget: businessNameInput }, 'step1'); 
	}

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
			const websiteUrlWrapper = modalContent.find( '#websiteUrlWrapper' )[ 0 ];
			const aiRetrievalWrapper = modalContent.find( '#aiRetrievalWrapper' )[ 0 ];
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
			}
			if ( evt.target.checked ) {
				businessNameWrapper.classList.remove( 'd-none' );
				websiteUrlWrapper.classList.remove( 'd-none' );
				aiRetrievalWrapper.classList.remove( 'd-none' );
			} else {
				businessNameWrapper.classList.add( 'd-none' );
				websiteUrlWrapper.classList.add( 'd-none' );
				aiRetrievalWrapper.classList.add( 'd-none' );
				businessDescriptionWrapper.classList.add( 'd-none' );
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
		quizModal._element.addEventListener( 'hidden.bs.modal', () => {
			const modalsActive = document.querySelectorAll( '.quiz-modal.show' ).length;
			// reset the quizAnswersStore
			if ( modalsActive === 0 ) {
				Object.keys( quizAnswersStore.step1 ).forEach( ( key ) => {
					if (typeof(quizAnswersStore.step1[ key ]) === 'boolean') {
						quizAnswersStore.step1[ key ] = false;
					} else {
						quizAnswersStore.step1[ key ] = '';
					}
				} );
			}
		} );
		//initiateIndustryChoices();
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
			allElementSuggestions: (choicesData && Array.isArray(choicesData.elementSuggestions)) ? choicesData.elementSuggestions : [],
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
			allElementSuggestions: (choicesData && Array.isArray(choicesData.elementSuggestions)) ? choicesData.elementSuggestions : [],
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
		
		// Safely handle choicesData properties that might not exist
		const elementSuggestions = (choicesData && Array.isArray(choicesData.elementSuggestions)) ? choicesData.elementSuggestions : [];
		const stepResult = (choicesData && Array.isArray(choicesData.stepResult)) ? choicesData.stepResult : [];
		
		const evaluationConditions = [ ...elementSuggestions, ...stepResult ].map( ( z ) => {
			return { [ z.key ]: z.evaluateAsDoneConditions };
		} );
		const wizardData = { ...results, ...{ __quizAnswersStore: _quizAnswersStore }, ...suggestionsByStep, evaluationConditions };
		Object.keys( wizardData ).forEach( ( prop ) => {
			currentCalculatorSetupWizardData[ prop ] = wizardData[ prop ];
		} );
		const setupWizard = document.querySelector( '#floating-wizard-placeholder' );
		const setupWizardTemplate = wp.template( 'scc-editing-page-sidebar-wizard' );
		const suggestionsObject = { 
			suggestions: [ ...currentCalculatorSetupWizardData[ 'Pricing Structure' ], ...currentCalculatorSetupWizardData[ 'Unique Needs' ], ...currentCalculatorSetupWizardData[ 'Use Cases' ] ], 
			suggestionsConfig: [ ...stepResult, ...elementSuggestions ] 
		};
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
			allElementSuggestions: (choicesData && Array.isArray(choicesData.elementSuggestions)) ? choicesData.elementSuggestions : [],
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
		// Show/Hide AI Retrieval button based on Name and URL having values
		const businessNameHasValue = quizAnswersStore[ inputOriginStep ][ 'business-name' ]?.length > 0;
		const websiteUrlHasValue = quizAnswersStore[ inputOriginStep ][ 'website-url' ]?.length > 0;
		const aiRetrievalWrapper = document.querySelector( '#aiRetrievalWrapper' );
		const descriptionWrapper = document.querySelector( '#businessDescriptionWrapper' ); // Keep description hidden initially

		if(businessNameHasValue && websiteUrlHasValue) {
			aiRetrievalWrapper.classList.remove( 'd-none' );
		} else {
			aiRetrievalWrapper.classList.add( 'd-none' );
			descriptionWrapper.classList.add( 'd-none' ); // Hide description if name/url cleared
		}

		// Show/Hide Continue button based on all three fields having values
		const allFieldsFulfilled = businessNameHasValue &&
			websiteUrlHasValue &&
			quizAnswersStore[ inputOriginStep ][ 'business-description' ]?.length > 0;

		if ( allFieldsFulfilled ) {
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
	const isFinalStep = false;
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
	sccDisplayBusinessInfoMessage();
}

function sccAiAssistedSetupWizUpdateProgress() {
	const textarea = document.getElementById('scc-ai-assisted-setup-wiz-business-description');
	const preview = document.getElementById('scc-ai-assisted-setup-wiz-business-description-preview');
	const progressBar = document.getElementById('scc-ai-assisted-setup-wiz-progress-bar');
	if (!textarea || !progressBar || !preview) {
		return;
	}

	const textLength = textarea.value.trim().length;
	const minLength = 100; // Minimum required length
	const progress = Math.min((textLength / minLength) * 100, 100);
	progressBar.style.width = progress + '%';

	// Update color based on progress
	if (progress < 33) {
		progressBar.style.backgroundColor = '#dc3545'; // Red
	} else if (progress < 66) {
		progressBar.style.backgroundColor = '#ffc107'; // Yellow
	} else {
		progressBar.style.backgroundColor = '#198754'; // Green
	}

	// Render Markdown preview only if the preview tab is active or becomes active
    const previewTabPane = document.getElementById('preview-tab-pane');
    const isPreviewActive = previewTabPane && previewTabPane.classList.contains('show');

	// Free version uses marked as an object while pro uses a function
    if (isPreviewActive && typeof marked === 'object') {
        try {
            preview.innerHTML = marked.parse(textarea.value);
        } catch (error) {
            console.error("Error parsing Markdown:", error);
            preview.textContent = textarea.value; // Fallback to plain text on error
        }
    } else if (isPreviewActive) { // Only show error if preview tab is active
		console.error("marked.js library not loaded or imported correctly.");
		preview.textContent = textarea.value; // Use textContent for plain text fallback
	}

	// Enable/disable continue button based on length
	const continueButton = document.getElementById('scc-setup-wizard-first-step-next-btn');
	if (continueButton) {
		if (textLength >= minLength) {
			continueButton.classList.remove('d-none');
		} else {
			continueButton.classList.add('d-none');
		}
	}
}
window.sccAiAssistedSetupWizUpdateProgress = sccAiAssistedSetupWizUpdateProgress;

// Add event listener for when the preview tab is shown
document.addEventListener('shown.bs.tab', function (event) {
    if (event.target.id === 'preview-tab') {
        sccAiAssistedSetupWizUpdateProgress(); // Re-render Markdown when preview tab is shown
    }
});
function sccRegenerateDescriptionFromUrl() {
	const websiteUrl = document.getElementById('websiteUrl').value;
	const businessName = document.getElementById('businessName').value;
	const descriptionTextarea = document.getElementById('scc-ai-assisted-setup-wiz-business-description');
	const loader = document.querySelector('.scc-ai-assisted-setup-wiz-business-description-loader');
	const regenerateBtn = document.getElementById('scc-regenerate-description-btn');

	if (!websiteUrl || !businessName) {
		alert('Please enter both business name and website URL first');
		return;
	}

	// Disable the button and show loader
	regenerateBtn.disabled = true;
	loader.classList.remove('scc-hidden');

	// Use the existing getSiteInfoWithAi function with the current page slug
	sccAiUtils.getSiteInfoWithAi( 'edit-calculator-page', websiteUrl )
		.then(response => {
			if (response && response.description) {
				descriptionTextarea.value = response.description;
				quizAnswersStore.step1['business-description'] = response.description;
				sccAiAssistedSetupWizUpdateProgress();
			} else {
				alert('Failed to generate description. Please try again.');
			}
		})
		.catch(error => {
			console.error('Error generating description:', error);
			alert('An error occurred. Please try again.');
		})
		.finally(() => {
			// Re-enable the button and hide loader
			regenerateBtn.disabled = false;
			loader.classList.add('scc-hidden');
		});
}

window.sccRegenerateDescriptionFromUrl = sccRegenerateDescriptionFromUrl;

function sccRetrieveBusinessDetails() {
	const websiteUrl = document.getElementById('websiteUrl').value;
	const businessName = document.getElementById('businessName').value;
	const descriptionWrapper = document.getElementById('businessDescriptionWrapper');
	const retrieveBtn = document.getElementById('scc-retrieve-business-details-btn');
	const loader = descriptionWrapper.querySelector('.scc-ai-assisted-setup-wiz-business-description-loader'); // Get loader inside description wrapper

	if (!websiteUrl || !businessName) {
		alert('Please enter both business name and website URL first');
		return;
	}

	// Disable the button and show loader
	retrieveBtn.disabled = true;
	loader.classList.remove('scc-hidden');
	descriptionWrapper.classList.remove('d-none'); // Show the description wrapper immediately

	// Use the existing getSiteInfoWithAi function
	sccAiUtils.getSiteInfoWithAi( 'edit-calculator-page', websiteUrl )
		.then(response => {
			// The response structure from getSiteInfoWithAi needs to be checked
			// Assuming response has a structure like { data: { ai_message: "description" } }
			const description = response?.ai_message;
			// Clean up the description string
			let formattedText = description
				.replace(/&lt;/g, '<')
				.replace(/&gt;/g, '>')
				.replace(/&amp;/g, '&')
				.replace(/&quot;/g, '"')
				.replace(/&#039;/g, "'")
				.replace(/<br\s*\/?>/g, '\n') // Convert <br> tags to newlines
				.replace(/<\/p>/g, '\n') // Convert </p> tags to newlines
				.replace(/<[^>]*>/g, '') // Remove any remaining HTML tags
				.replace(/\[([^\]]+)\]\(([^)]+)\)/g, '$1'); // Remove markdown links

			if (description) {
				// Update the description textarea
				const descriptionTextarea = document.getElementById('scc-ai-assisted-setup-wiz-business-description');
				descriptionTextarea.value = formattedText;
				quizAnswersStore.step1['business-description'] = formattedText;
				sccAiAssistedSetupWizUpdateProgress(); // Update progress bar
				updateQuizAnswersStore({ currentTarget: descriptionTextarea }, 'step1'); // Trigger check for continue button
			} else {
				alert('Failed to retrieve business details. Please try again.');
				descriptionWrapper.classList.add('d-none'); // Hide wrapper if failed
			}
		})
		.catch(error => {
			console.error('Error retrieving business details:', error);
			alert('An error occurred. Please try again.');
			descriptionWrapper.classList.add('d-none'); // Hide wrapper if failed
		})
		.finally(() => {
			// Re-enable the button and hide loader
			retrieveBtn.disabled = false;
			loader.classList.add('scc-hidden');
		});
}

window.sccRetrieveBusinessDetails = sccRetrieveBusinessDetails;

function sccDisplayBusinessInfoMessage() {

	const requiredUrl = 'scc-premium-demo.com';
	console.log(window.location.href);
    // Check if we're NOT on the required URL
    if (!window.location.href.includes(requiredUrl)) {
        return; // Exit the function if the URL doesn't match
    }
    // --- Find the Target Element to Insert After ---
    setTimeout(() => {
        const targetWrapper = document.querySelector('.scc-product-choices-wrapper');

        // Only proceed if the target wrapper exists
        if (targetWrapper) {

            // --- Check if message already exists (to avoid duplicates if startSetupWizard is called again) ---
            if (document.getElementById('scc-inline-business-info-message')) {
                // Optionally make it visible again if it was hidden
                document.getElementById('scc-inline-business-info-message').style.display = 'block';
                return; // Don't create another one
            }

            // --- Create the Info Message Element ---
            const infoMessageDiv = document.createElement('div');
            infoMessageDiv.id = 'scc-inline-business-info-message'; // Unique ID
            // Basic styling for the message - adjust as needed
            infoMessageDiv.style.padding = '10px';
            infoMessageDiv.style.marginTop = '15px'; // Add some space above
            infoMessageDiv.style.backgroundColor = '#e7f3fe'; // Light blue background
            infoMessageDiv.style.color = '#0a58ca'; // Darker blue text
            infoMessageDiv.style.border = '1px solid #b6d4fe'; // Blue border
            infoMessageDiv.style.borderRadius = '4px';
            infoMessageDiv.style.fontSize = '14px';
            infoMessageDiv.style.fontFamily = 'sans-serif';
            infoMessageDiv.style.display = 'block'; // Initially visible

            // --- Create Message Content ---
            infoMessageDiv.textContent = 'Tip: Please enter your current business name and your website URL in the fields below to help us personalize your calculator.';

            // --- Insert the Message After the Target Wrapper ---
            targetWrapper.insertAdjacentElement('afterend', infoMessageDiv);

            // --- Find the Button to Trigger Hiding ---
            const retrieveButton = document.getElementById('scc-retrieve-business-details-btn');

            if (retrieveButton) {
                // Make sure we don't add the listener multiple times
                retrieveButton.removeEventListener('click', sccHideInfoMessageOnClick); // Remove previous if any
                retrieveButton.addEventListener('click', sccHideInfoMessageOnClick);
            } else {
                //console.warn('SCC Info Script: Button with ID "scc-retrieve-business-details-btn" not found on wizard start. The info message will not auto-hide.');
            }

        } else {
            //console.warn('SCC Info Script: Element with class "scc-product-choices-wrapper" not found on wizard start. Cannot display the info message.');
        }
    }, 100); // Small delay (100ms) to allow template rendering, adjust if needed
}
window.sccDisplayBusinessInfoMessage = sccDisplayBusinessInfoMessage;
// Helper function to avoid listener duplication if button is recreated
function sccHideInfoMessageOnClick() {
    const messageDiv = document.getElementById('scc-inline-business-info-message');
    if (messageDiv) {
        messageDiv.style.display = 'none';
    }
}
window.sccHideInfoMessageOnClick = sccHideInfoMessageOnClick;

// Add DOMContentLoaded event listener
window.addEventListener( 'DOMContentLoaded', ( event ) => {
	// removing WordPress's forms.css file, for
	// document.getElementById('forms-css')?.remove();
	
	// Check if we should skip initialization for certain pages
	if ( window.location.search === '?page=scc-list-all-calculator-forms' ) {
		return;
	}
	
	// Initialize the wizard
	initializeWizard();
	
	// Handle open-wizard parameter after initialization
	const urlParams = new URLSearchParams( window.location.search );
	if ( urlParams.has( 'open-wizard' ) ) {
		// Wait a bit for the page to be fully loaded before clicking
		setTimeout(() => {
			const wizardBtn = document.querySelector( '[data-btn-action="startSetupWizard"]' );
			if (wizardBtn) {
				wizardBtn.click();
			}
		}, 500);
	}
} );