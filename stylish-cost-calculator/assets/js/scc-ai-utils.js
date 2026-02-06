/************************************************
 * AI Features / AI functions
************************************************/

/**
 * PLUGIN VERSION FLAG
 * Set to false for FREE version, true for PREMIUM version
 * 
 * Migration instructions for FREE version:
 * 1. Set sccIsPro = false
 * 2. Remove/comment the import statements below (lines with 'import')
 * 3. Remove/comment the export statement at the end of the file
 * 4. Ensure 'marked' library is loaded globally via WordPress enqueue
 */
const sccIsPro = false;

// PREMIUM ONLY: Comment out these imports for FREE version
//import { marked } from 'marked';
//import stylishCostCalculatorModal from './modals/index';
let sccAiWizardOptionListener = null;
let sccAiWizardThread = '';
const sccAiDataSchema = [];

const sccAiUtils = {
	aiWizardMenu: null,
	quizRetakeButton: null,
	optimizerStartSetupWizard: null,
	aiWizardStatus: null,
	aiWizardLoaderMessages: [
		'Please wait, generating optimizations for your calculator...',
		'Analyzing your pricing structure...',
		'Gathering intelligent suggestions...',
		'Almost there! Processing AI recommendations...',
		'Creating personalized optimization insights...',
	],
	loaderMessageInterval: null,
	/*
	* AI Wizard Page States
	* ============================
	* scc-ai-wizard-suggest-element
	* scc-ai-wizard-setup-wizard
	* scc-ai-wizard-optimize-form
	* scc-ai-wizard-analytics-insights
	*/
	toggleAiWizardOverlay: function(show = true) {
		// Find or create the overlay
		let overlay = document.querySelector('.scc-ai-wizard-overlay');
		
		if (!overlay && show) {
			// Create the overlay if it doesn't exist
			overlay = document.createElement('div');
			overlay.className = 'scc-ai-wizard-overlay';
			
			// Create the icon element - using the wizard hat image like in the screenshot
			const wizardIcon = document.createElement('div');
			wizardIcon.style.width = '80px';
			wizardIcon.style.height = '80px';
			wizardIcon.style.marginBottom = '20px';
			wizardIcon.innerHTML = `
				<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" style="fill:#7b5dfa">
					<path d="M416 398.9c58.5-41.1 96-104.1 96-174.9C512 100.3 397.1 0 256 0 114.8 0 0 100.3 0 224c0 70.7 37.5 133.8 96 174.9V464c0 26.5 21.5 48 48 48h224c26.5 0 48-21.5 48-48v-65.1zM160 388v-35.6c47.1 13.2 96.7 13.2 144 0V388c0 4.4-3.6 8-8 8H168c-4.4 0-8-3.6-8-8zm8-280v64c0 4.4 3.6 8 8 8h32c4.4 0 8-3.6 8-8v-64c0-4.4-3.6-8-8-8h-32c-4.4 0-8 3.6-8 8zm88 272c0 8.8-7.2 16-16 16s-16-7.2-16-16v-64c0-8.8 7.2-16 16-16s16 7.2 16 16v64zm96-208v64c0 4.4 3.6 8 8 8h32c4.4 0 8-3.6 8-8v-64c0-4.4-3.6-8-8-8h-32c-4.4 0-8 3.6-8 8z" />
				</svg>
			`;
			
			// Create steps tracker
			const stepsContainer = document.createElement('div');
			stepsContainer.className = 'scc-ai-wizard-steps-container';
			stepsContainer.style.marginTop = '30px';
			stepsContainer.style.display = 'flex';
			stepsContainer.style.flexDirection = 'column';
			stepsContainer.style.alignItems = 'flex-start';
			
			// Add steps
			const steps = [
				{text: 'Processing your inputs...', done: true},
				{text: 'Applying calculation logic...', done: true},
				{text: 'Finalizing your calculator...', done: false}
			];
			
			steps.forEach(step => {
				const stepItem = document.createElement('div');
				stepItem.className = 'scc-ai-wizard-step-item';
				stepItem.style.display = 'flex';
				stepItem.style.alignItems = 'center';
				stepItem.style.margin = '5px 0';
				
				// Create icon for step
				const icon = document.createElement('span');
				icon.style.display = 'inline-flex';
				icon.style.alignItems = 'center';
				icon.style.justifyContent = 'center';
				icon.style.width = '24px';
				icon.style.height = '24px';
				icon.style.borderRadius = '50%';
				icon.style.marginRight = '10px';
				
				if (step.done) {
					icon.style.backgroundColor = '#5BB75B';
					icon.innerHTML = `
						<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="white" stroke-width="3" stroke-linecap="round" stroke-linejoin="round">
							<polyline points="20 6 9 17 4 12"></polyline>
						</svg>
					`;
				} else {
					icon.style.backgroundColor = '#6d6d6d';
					icon.innerHTML = `
						<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#ffffff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
							<circle cx="12" cy="12" r="10"></circle>
						</svg>
					`;
				}
				
				// Create text for step
				const text = document.createElement('span');
				text.textContent = step.text;
				text.style.fontSize = '16px';
				text.style.color = step.done ? 'white' : '#c7c7c7';
				
				stepItem.appendChild(icon);
				stepItem.appendChild(text);
				stepsContainer.appendChild(stepItem);
			});
			
			// Create message element
			const message = document.createElement('div');
			message.className = 'scc-ai-wizard-overlay-message';
			message.textContent = 'Almost There!';
			message.style.fontSize = '28px';
			message.style.fontWeight = 'bold';
			message.style.color = 'white';
			message.style.marginTop = '20px';
			message.style.marginBottom = '10px';
			
			// Create subtitle
			const subtitle = document.createElement('div');
			subtitle.textContent = 'Your calculator will be ready in just a moment';
			subtitle.style.color = '#f1f1f1';
			subtitle.style.fontSize = '16px';
			subtitle.style.marginBottom = '30px';
			
			// Create progress bar
			const progressContainer = document.createElement('div');
			progressContainer.style.width = '80%';
			progressContainer.style.maxWidth = '600px';
			progressContainer.style.height = '10px';
			progressContainer.style.backgroundColor = '#dddddd';
			progressContainer.style.borderRadius = '5px';
			progressContainer.style.overflow = 'hidden';
			progressContainer.style.marginBottom = '40px';
			
			const progressBar = document.createElement('div');
			progressBar.style.width = '50%';
			progressBar.style.height = '100%';
			progressBar.style.backgroundColor = '#7b5dfa';
			progressBar.style.borderRadius = '5px';
			// Add animation to progress bar
			progressBar.style.transition = 'width 3s ease-in-out';
			setTimeout(() => {
				progressBar.style.width = '100%';
			}, 100);
			
			progressContainer.appendChild(progressBar);
			
			// Append elements to overlay
			overlay.appendChild(wizardIcon);
			overlay.appendChild(message);
			overlay.appendChild(subtitle);
			overlay.appendChild(progressContainer);
			overlay.appendChild(stepsContainer);
			
			// Add overlay styles
			overlay.style.position = 'fixed';
			overlay.style.top = '0';
			overlay.style.left = '0';
			overlay.style.width = '100%';
			overlay.style.height = '100%';
			overlay.style.backgroundColor = 'rgba(0, 0, 0, 0.93)';
			overlay.style.backdropFilter = 'blur(8px)';
			overlay.style.display = 'flex';
			overlay.style.flexDirection = 'column';
			overlay.style.alignItems = 'center';
			overlay.style.justifyContent = 'center';
			overlay.style.zIndex = '99999';
			
			// Add animation to overlay
			overlay.style.opacity = '0';
			overlay.style.transition = 'opacity 0.3s ease';
			setTimeout(() => {
				overlay.style.opacity = '1';
			}, 10);
			
			// Append to body
			document.body.appendChild(overlay);
			
			// Update step status periodically
			let currentStep = 2;
			setTimeout(() => {
				const icons = stepsContainer.querySelectorAll('.scc-ai-wizard-step-item span:first-child');
				const texts = stepsContainer.querySelectorAll('.scc-ai-wizard-step-item span:last-child');
				
				if (icons[currentStep]) {
					icons[currentStep].style.backgroundColor = '#5BB75B';
					icons[currentStep].innerHTML = `
						<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="white" stroke-width="3" stroke-linecap="round" stroke-linejoin="round">
							<polyline points="20 6 9 17 4 12"></polyline>
						</svg>
					`;
				}
				
				if (texts[currentStep]) {
					texts[currentStep].style.color = 'white';
				}
			}, 4000);
			
		} else if (overlay && !show) {
			// Add fade-out animation
			overlay.style.opacity = '0';
			// Remove after animation completes
			setTimeout(() => {
				overlay.remove();
			}, 300);
		} else {
			console.log("Case not handled: overlay=", overlay, "show=", show);
		}
	},
	aiWizardInit: ( pageState = null ) => {
		const aiWizardButton = document.getElementById( 'scc-ai-wizard-button' );
		const aiWizardContainer = aiWizardButton?.closest( '.scc-ai-wizard-panel-container' );
		sccAiUtils.aiWizardMenu = aiWizardContainer?.querySelector( '.scc-ai-wizard-menu' );
		const aiWizardMenu = sccAiUtils.aiWizardMenu;
		const resetButton = document.getElementById( 'scc-ai-wizard-reset-btn' );
		const closeChatButton = document.getElementById( 'scc-ai-wizard-close-chat-btn' );
		const backToMenuButton = document.getElementById( 'scc-ai-wizard-back-btn' );
		const chatPanel = aiWizardMenu?.querySelector( '.scc-ai-wizard-chat' );
		sccAiUtils.quizRetakeButton = aiWizardMenu?.querySelector( '#scc-ai-wizard-retake' );
		const retakeText = sccAiUtils.quizRetakeButton?.querySelector( '.scc-ai-wizard-retake-text' );
		const quizRetakeButton = sccAiUtils.quizRetakeButton;
		if ( sccBackendStore.currentCalculatorSetupWizardData.__quizAnswersStore ) {
			retakeText.textContent = 'Redo Setup Wizard';
		} else {
			retakeText.textContent = 'Start Setup Wizard';
		}
		sccAiUtils.optimizerStartSetupWizard = aiWizardMenu?.querySelector( '#scc-ai-wizard-consider-setup' );
		const optimizerStartSetupWizard = sccAiUtils.optimizerStartSetupWizard;

		// Menu Button Ids
		const options = [
			'scc-ai-wizard-suggest-element',
			'scc-ai-wizard-setup-wizard',
			'scc-ai-wizard-optimize-form',
			'scc-ai-wizard-advanced-pricing-formula',
			'scc-ai-wizard-analytics-insights',
		];

		if ( aiWizardButton ) {
			options.forEach( ( optionId ) => {
				const optionElement = document.getElementById( optionId );
				const optionClickHandler = () => sccAiUtils.aiWizardOptionSelected( optionElement, aiWizardMenu );
				if ( ! optionElement.hasClickListener ) {
					optionElement.addEventListener( 'click', optionClickHandler );
					optionElement.hasClickListener = true;
				}
				if ( optionId === pageState ) {
					sccAiUtils.toggleAiWizardPanel( null );
					optionElement.click();
				}
			} );

			const resetClickHandler = () => sccAiUtils.resetChat( resetButton );
			if ( ! resetButton.hasClickListener ) {
				resetButton.addEventListener( 'click', resetClickHandler );
				resetButton.hasClickListener = true;
			}
			const backToMenuClickHandler = () => sccAiUtils.backToMenu( backToMenuButton, chatPanel );
			if ( ! backToMenuButton.hasClickListener ) {
				backToMenuButton.addEventListener( 'click', backToMenuClickHandler );
				backToMenuButton.hasClickListener = true;
			}

			const quizRetakeClickHandler = () => sccAiUtils.retakeSetupWizard();
			if ( ! quizRetakeButton.hasClickListener ) {
				quizRetakeButton.addEventListener( 'click', quizRetakeClickHandler );
				quizRetakeButton.hasClickListener = true;
			}

			const optimizerStartClickHandler = () => sccAiUtils.retakeSetupWizard();
			if ( ! optimizerStartSetupWizard.hasClickListener ) {
				optimizerStartSetupWizard.addEventListener( 'click', optimizerStartClickHandler );
				optimizerStartSetupWizard.hasClickListener = true;
			}

			[ aiWizardButton, closeChatButton ].forEach( ( button ) => {
				const buttonClickHandler = ( event ) => sccAiUtils.toggleAiWizardPanel( event );
				if ( ! button.hasClickListener ) {
					button.addEventListener( 'click', buttonClickHandler );
					button.hasClickListener = true;
				}
			} );
		}
	},
	aiWizardOptionSelected: ( button, menu ) => {
		const aiOptionType = button.getAttribute( 'data-option-type' );
		const chat = menu.querySelector( '.scc-ai-wizard-chat' );

		if ( aiOptionType === 'suggest-elements' ) {
			sccAiUtils.aiWizardStatus = 'scc-ai-wizard-suggest-element';
			const title = 'Intelligent Element Suggester';
			const customMessage = 'What are you trying to calculate?';
			sccAiUtils.enableInputsAiWizard( button, chat );
			if ( ! chat.querySelector( '.scc-ai-chat-bubble-wizard' ) ) {
				sccAiUtils.startAiWizardChat( menu, aiOptionType, title, customMessage );
			}
		}
		if ( aiOptionType === 'setup-wizard' ) {
			sccAiUtils.aiWizardStatus = 'scc-ai-wizard-setup-wizard';
			sccAiUtils.showSetupWizardTab( menu );
		}
		if ( aiOptionType === 'optimize-form' ) {
			sccAiUtils.aiWizardStatus = 'scc-ai-wizard-optimize-form';
			sccAiUtils.showFormOptimizerTab( menu );
		}
		if ( aiOptionType === 'advanced-pricing-formula' ) {
			sccAiUtils.aiWizardStatus = 'scc-ai-wizard-advanced-pricing-formula';
			sccAiUtils.showAdvancedPricingFormulaTab( menu );
		}
		if ( aiOptionType === 'analytics-insights' ) {
			sccAiUtils.aiWizardStatus = 'scc-ai-wizard-analytics-insights';
			sccAiUtils.showAnalyticsInsightsTab( menu );
		}
		if ( aiOptionType === 'open-support-chat' ) {
			sccAiUtils.openSupportChat();
			sccAiUtils.toggleAiWizardPanel( null );
		}
		if ( aiOptionType === 'open-intelligent-qa' ) {
			sccAiUtils.openIntelligentQuestionsAndAnswers();
			sccAiUtils.toggleAiWizardPanel( null );
		}
	},
	openSupportChat: () => {
		if ( window.$crisp ) {
		    $crisp.push( [ 'do', 'chat:open' ] );
		}
	},
	closeSupportChat: () => {
		if ( window.$crisp ) {
		    $crisp.push( [ 'do', 'chat:close' ] );
		}
	},
	openIntelligentQuestionsAndAnswers: () => {
		const button = document.querySelector( '#crisp-chatbox a[data-mode="search"]' );
		if ( button ) {
			button.click();
		}
	},
	startAiWizardChat( menu, aiOptionType, title = null, customStartingAiMessage = null ) {
		const inputs = menu.querySelector( '.scc-ai-assistant-inputs' );
		const textField = inputs.querySelector( '.scc-ai-assistant-text-field' );
		const sendButton = inputs.querySelector( '.scc-ai-assistant-send-btn' );
		const chat = menu.querySelector( '.scc-ai-wizard-chat' );
		const backButton = document.getElementById( 'scc-ai-wizard-back-btn' );
		const refreshButton = document.getElementById( 'scc-ai-wizard-reset-btn' );

		backButton.classList.remove( 'scc-hidden' );
		refreshButton.classList.remove( 'scc-hidden' );

		sendButton.setAttribute( 'data-option-type', aiOptionType );

		sccAiUtils.insertAiChatMessage( sendButton, null, aiOptionType, customStartingAiMessage );

		if ( sccAiWizardOptionListener ) {
			sendButton.removeEventListener( 'click', sccAiWizardOptionListener );
		}
		sccAiWizardOptionListener = () => sccAiUtils.aiWizardRequest( aiOptionType );
		sendButton.addEventListener( 'click', sccAiWizardOptionListener );

		//Adding keydown event listener to the prompt field
		const keydownHandler = function( event ) {
			if ( event.keyCode === 13 ) {
				event.preventDefault();
				sendButton.click();
			}
		};
		// Delete previous event listener
		textField.removeEventListener( 'keydown', keydownHandler );

		// Add current event listener
		textField.addEventListener( 'keydown', keydownHandler );
	},
	blockAIWizardCloseActions: (blockStatus = true) => {
		const menu = document.querySelector( '.scc-ai-wizard-menu' );
		const closeButton = menu.querySelector( '.scc-ai-close-btn' );
		const backButton = menu.querySelector('.scc-ai-back-btn');
		if ( blockStatus ) {
			if ( backButton ) {
				backButton.setAttribute( 'disabled', true );
			}
			if ( closeButton ) {
				closeButton.setAttribute( 'disabled', true );
			}
		} else {
			if ( backButton ) {
				backButton.removeAttribute( 'disabled' );
			}
			if ( closeButton ) {
				closeButton.removeAttribute( 'disabled' );
			}
		}
	},
	aiWizardRequest: ( optionType, regenerate = false ) => {
		const menu = document.querySelector( '.scc-ai-wizard-menu' );
		const inputs = menu?.querySelector( '.scc-ai-assistant-inputs' );
		const prompt = inputs?.querySelector( '.scc-ai-assistant-text-field' );
		const sendButton = inputs?.querySelector( '.scc-ai-assistant-send-btn' );
		const loader = menu.querySelector( '.scc-ai-wizard-loader-container' );
		const loaderSetupWizard = menu.querySelector( '.scc-setup-wizard-loader' );
		const setupWizardAlert = menu.querySelector( '.scc-ai-wizard-consider-start-setup' );
		const aiWizardMetadata = JSON.stringify( sccAiUtils.getCalculatorDataSchema() );

		let userPrompt = prompt?.value;

		let promptData = null;
		
		if ( optionType === 'optimize-form' ) {
			const wizardData = sccAiUtils.getSetupWizardData( 'industry-questions' );
			if ( wizardData ) {
				//const industry = wizardData[ 'industry-type' ];
				const businessName = wizardData[ 'business-name' ];
				const businessDescription = wizardData[ 'business-description' ];

				promptData = {
					businessDescription,
					businessName,
					calculatorArray: sccAiUtils.getCalculatorDataSchema(),
				};
				setupWizardAlert.classList.add( 'scc-hidden' );
			} else {
				promptData = {
					calculatorArray: sccAiUtils.getCalculatorDataSchema(),
				};
				setupWizardAlert.classList.remove( 'scc-hidden' );
			}

			userPrompt = JSON.stringify( promptData );
			loader.classList.remove( 'scc-hidden' );
			sccAiUtils.startLoaderMessageRotation(loader);
			sccAiUtils.blockAIWizardCloseActions( true );
			if ( regenerate === true ) {
				sccAiUtils.regenerateFormOptimizerResponse();
			}
		} else if ( optionType === 'setup-wizard' ) {
			const wizardData = sccAiUtils.getSetupWizardData();
			if ( wizardData ) {
				const setupWizardAccordion = menu.querySelector( '#scc-setup-wizard-accordion' );
				setupWizardAccordion.classList.remove( 'scc-hidden' );
				promptData = {
					quizData: wizardData,
				};
			}
			userPrompt = JSON.stringify( promptData );
			if ( regenerate === true ) {
				sccAiUtils.regenerateSetupWizardStepByStep();
			}
			sccAiUtils.startLoaderMessageRotation(loaderSetupWizard);
			sccAiUtils.blockAIWizardCloseActions( true );
		} else {
			userPrompt = prompt?.value;

			sccAiUtils.insertUserMessage( sendButton, prompt );
		}

		const calculatorId = sccAiUtils.getCalcId();
		const storedData = sccAiUtils.getAiWizardResponse( calculatorId, optionType );

		// Check if storedData exists and has valid ai_response
		let hasValidStoredData = false;
		if ( storedData && ! regenerate ) {
			try {
				const parsedStoredData = JSON.parse( storedData );
				hasValidStoredData = parsedStoredData?.ai_response?.ai_message && 
					parsedStoredData.ai_response.ai_message.trim() !== '';
			} catch ( error ) {
				console.error( 'Error parsing stored data:', error );
				hasValidStoredData = false;
			}
		}

		if ( hasValidStoredData ) {
			const jsonData = storedData;

			if ( optionType === 'optimize-form' || optionType === 'setup-wizard' ) {
				sccAiUtils.insertAiChatMessage( sendButton, jsonData, optionType );
			} else {
				sccAiUtils.insertAiChatMessage( sendButton, jsonData, optionType );
				sccAiUtils.enableInputsAiWizard( sendButton );
			}
			loader.classList.add( 'scc-hidden' );
			loaderSetupWizard.classList.add( 'scc-hidden' );
			sccAiUtils.stopLoaderMessageRotation();
		} else {
			// Ajax call - no valid stored data found

			const params = {
				action: 'scc_ai_wizard_request',
				nonce: pageEditCalculator.nonce,
				calculator_id: sccAiUtils.getCalcId(),
				prompt: userPrompt,
				type: optionType,
				thread: sccAiWizardThread,
				metadata: aiWizardMetadata,
			};
			const action = 'scc_ai_wizard_request';

			const formData = new FormData();
			formData.append( 'action', action );
			formData.append( 'request_data', JSON.stringify( params ) );

			const ajaxRoute = ajaxurl + '?action=' + action + '&nonce=' + pageEditCalculator.nonce;

			fetch( ajaxRoute, {
				method: 'POST',
				body: formData, // Send the formData
			} )
				.then( ( response ) => response.json() )
				.then( ( data ) => {
					const jsonData = JSON.stringify( data );

					if ( optionType === 'optimize-form' || optionType === 'setup-wizard' ) {
						// Save jsonData to localStorage
						sccAiUtils.saveAiWizardResponse( data );
						sccAiUtils.insertAiChatMessage( sendButton, jsonData, optionType );
					} else {
						sccAiUtils.insertAiChatMessage( sendButton, jsonData, optionType );
						sccAiUtils.enableInputsAiWizard( sendButton );
					}
					loader.classList.add( 'scc-hidden' );
					loaderSetupWizard.classList.add( 'scc-hidden' );
					sccAiUtils.stopLoaderMessageRotation();
					sccAiUtils.blockAIWizardCloseActions( false );
				} )
				.catch( ( error ) => {
					console.error( 'Error:', error );
					loader.classList.add( 'scc-hidden' );
					loaderSetupWizard.classList.add( 'scc-hidden' );
					sccAiUtils.stopLoaderMessageRotation();
					sccAiUtils.blockAIWizardCloseActions( false );
				} );
		}
	},
	regenerateSetupWizardStepByStep: () => {
		const responseContainer = document.querySelector( '.scc-ai-wizard-setup-accordion-body' );
		const loader = responseContainer.querySelector( '.scc-setup-wizard-loader' );
		const oldResponse = responseContainer.querySelector( '.scc-ai-chat-bubble-wizard' );
		loader.classList.remove( 'scc-hidden' );
		sccAiUtils.startLoaderMessageRotation(loader);
		sccAiUtils.blockAIWizardCloseActions( true );
		if ( oldResponse ) {
			oldResponse.remove();
		}
	},
	regenerateFormOptimizerResponse: () => {
		const responseContainer = document.querySelector( '.scc-ai-wizard-form-optimizer' );
		const loader = responseContainer.closest( '.scc-ai-wizard-menu-body' ).querySelector( '.scc-ai-wizard-loader-container' );
		const oldResponse = responseContainer.querySelector( '.scc-ai-chat-bubble-wizard' );
		loader.classList.remove( 'scc-hidden' );
		sccAiUtils.startLoaderMessageRotation(loader);
		sccAiUtils.blockAIWizardCloseActions( true );
		if ( oldResponse ) {
			oldResponse.remove();
		}
	},
	saveAiWizardResponse: ( data ) => {
		const storedData = JSON.parse( localStorage.getItem( 'aiWizardResponseData' ) || '[]' );
		const index = storedData.findIndex( ( item ) => item.calculator_id === data.calculator_id && item.type === data.type );

		if ( index !== -1 ) {
			// Replace the existing item
			storedData[ index ] = data;
		} else {
			// Add the new item
			storedData.push( data );
		}

		localStorage.setItem( 'aiWizardResponseData', JSON.stringify( storedData ) );
	},
	getAiWizardResponse: ( calculatorId, type ) => {
		const storedData = JSON.parse( localStorage.getItem( 'aiWizardResponseData' ) || '[]' );
		const aiData = storedData.find( ( item ) => item.calculator_id == calculatorId && item.type == type );
		return aiData ? JSON.stringify( aiData ) : null;
	},
	resetChat: ( $this ) => {
		sccAiWizardThread = '';
		const chat = $this.closest( '.scc-ai-assistant-chat' ) || $this.closest( '.scc-ai-wizard-menu' );
		const suggestions = chat.querySelectorAll( '.scc-ai-response-option' );
		const bubbles = chat.querySelectorAll( '.scc-ai-chat-bubble' );

		for ( let i = 1; i < bubbles.length; i++ ) {
			bubbles[ i ].remove();
		}
		// Insert the first bubble
	},
	backToMenu: ( button, chatPanel ) => {
		sccAiUtils.disableInputsAiWizard( button, chatPanel );
		const chat = button.closest( '.scc-ai-assistant-chat' ) || button.closest( '.scc-ai-wizard-menu' );
		const bubbles = chat.querySelectorAll( '.scc-ai-chat-bubble' );
		const backButton = document.getElementById( 'scc-ai-wizard-back-btn' );
		const refreshButton = document.getElementById( 'scc-ai-wizard-reset-btn' );
		backButton.classList.add( 'scc-hidden' );
		refreshButton.classList.add( 'scc-hidden' );

		for ( let i = 0; i < bubbles.length; i++ ) {
			bubbles[ i ].remove();
		}
	},
	retakeSetupWizard: () => {
		// initiate the setup wizard again
		sccAiUtils.toggleAiWizardPanel( null );
		startSetupWizard();
	},
	// This function handles the user message and inserts it into the chat
	insertUserMessage: ( $this, prompt ) => {
		const aiChatContainer = $this.closest( '.scc-ai-assistant-chat' ) || $this.closest( '.scc-ai-wizard-menu' );
		const userAvatar = aiChatContainer.getAttribute( 'data-user-avatar' );
		const aiChat = aiChatContainer.querySelector( '.scc-ai-chat' ) || aiChatContainer.querySelector( '.scc-ai-wizard-chat' );
		const userPrompt = prompt.value;
		const aiLoader = aiChatContainer.querySelector( '.scc-ai-response-loader' );
		const regenerateButton = aiChatContainer.querySelector( '.scc-ai-action-regenerate' );
		const message = `
            <div class="scc-ai-chat-bubble scc-ai-chat-bubble-user">
                
                <div class="scc-ai-chat-bubble-content">
				<div class="scc-ai-chat-bubble-avatar">
                    <img src="${ userAvatar }" alt="">
                </div>
                    <div class="scc-ai-chat-bubble-text mt-2">
                        <p>${ userPrompt }</p>
                    </div>
                </div>
            </div>
        `;

		aiChat.insertAdjacentHTML( 'beforeend', message );
		aiChat.scrollTop = aiChat.scrollHeight;
		if ( regenerateButton ) {
			regenerateButton.setAttribute( 'data-last-prompt', userPrompt );
		}
		prompt.value = '';
		prompt.setAttribute( 'disabled', true );
		$this.setAttribute( 'disabled', true );
		aiLoader.classList.remove( 'scc-hidden' );
		sccAiUtils.blockAIWizardCloseActions( true );
	},
	insertAISuggestionMessage: ( $this, text ) => {
		// Convert markdown to HTML (different method for FREE vs PREMIUM)
		text = sccIsPro ? marked( text ) : marked.parse( text );
		const message = `
                <div class="scc-ai-suggestion-message">
                    ${ text }
                </div>
        `;
		return message;
	},
	insertAiChatMessage: function( $this, aiResponse = null, optionType, customMessage = null ) {
		const aiChatContainer = $this.closest( '.scc-ai-assistant-chat' ) || $this.closest( '.scc-ai-wizard-menu' );
		const aiAvatar = aiChatContainer.getAttribute( 'data-ai-avatar' );
		const forceShowAiActions = optionType === 'setup-wizard' || optionType === 'optimize-form' ? true : false;
		//Ai Wizard Sections
		const aiChat = aiChatContainer.querySelector( '.scc-ai-wizard-chat' );
		const aiFormOptimizer = aiChatContainer.querySelector( '.scc-ai-wizard-form-optimizer' );
		const aiSetupWizard = aiChatContainer.querySelector( '.scc-ai-wizard-setup' );

		const creditIndicator = aiChatContainer?.querySelector( '.scc-ai-credit-count' );
		const requestType = optionType;
		let aiMessageText = '';
		const aiSuggestionText = '';
		let aiHideActionsClass = 'scc-hidden';
		let aiSmallAvatarVisibility = 'scc-hidden';
		let aiHideAiAvatar = '';
		let aiMessageClasses = 'scc-ai-chat-bubble-text';
		let aiAddElementsButton = '';

		if ( ! aiResponse ) {
			if ( customMessage ) {
				aiMessageText = customMessage;
			}
			if ( requestType === 'suggest-elements' ) {
				aiMessageText = 'What are you trying to calculate?';
			}
		} else {
			const aiResponseObject = JSON.parse( aiResponse );

			if ( aiResponseObject?.ai_response?.ai_message === null && aiResponseObject?.ai_response?.error ) {
				aiMessageText = aiResponseObject.ai_response.error;
				sccAiUtils.enableInputsAiWizard( $this );
				aiMessageClasses = 'scc-ai-chat-bubble-text scc-ai-chat-bubble-text-warning';
			} else {
				if ( optionType === 'suggest-elements' ) {
					sccAiWizardThread = aiResponseObject.ai_response.thread;
				}
				
				aiMessageText = aiResponseObject.ai_response.ai_message;

				if ( ( aiMessageText && aiMessageText.includes( '[[show_actions]]' ) ) || ( aiMessageText && forceShowAiActions ) ) {
					aiMessageText = aiMessageText.replace( '[[show_actions]]', '' );
					aiHideActionsClass = '';
					aiHideAiAvatar = 'scc-hidden';
					aiSmallAvatarVisibility = '';
					if ( optionType === 'suggest-elements') {
						aiAddElementsButton = '<button class="scc-ai-wizard-add-elements-btn btn btn-primary scc-ai-wizard-primary-button d-flex align-items-center me-2" onclick="window.showAiWizardOverlay(this, false);"><i class="scc-btn-spinner scc-save-btn-spinner scc-hidden ms-0"></i><span class="scc-ai-button-text"> + Add Elements with AI</span></button>';
					}
					if ( optionType === 'setup-wizard' ) {
						aiAddElementsButton = '<button class="scc-ai-wizard-add-elements-btn btn btn-primary scc-ai-wizard-primary-button d-flex align-items-center me-2" onclick="window.showAiWizardOverlay(this, true);"><i class="scc-btn-spinner scc-save-btn-spinner scc-hidden ms-0"></i><span class="scc-ai-button-text"> + Build Calculator with AI</span></button>';
					}
				}

				sccAiUtils.enableInputsAiWizard( aiChat );
				sccAiUtils.blockAIWizardCloseActions( false );
				sccAiUtils.checkAiCredits( 'edit-calculator-page' ).then( ( credits ) => {
					sccAiUtils.updateCreditsIndicator( credits, creditIndicator );
				} ).catch( ( error ) => {
					console.error( error );
				} );

				if ( aiResponseObject.ai_response.current_credits === 0 ) {
					aiMessageClasses = 'scc-ai-chat-bubble-text scc-ai-chat-bubble-text-warning';
				}
			}
		}
		try {
			const renderer = new marked.Renderer();
			renderer.link = function( href, title, text ) {
				const target = '_blank';
				const link = `<a href="${ href }" title="${ title || '' }" target="${ target }">${ text }</a>`;
				return link;
			};
			// Convert markdown to HTML (different method for FREE vs PREMIUM)
			aiMessageText = sccIsPro ? marked( aiMessageText, { renderer } ) : marked.parse( aiMessageText, { renderer } );
		} catch ( e ) {
			aiHideActionsClass = '';
			aiMessageText = 'An error occurred while processing the response. Please regenerate';
		}

		//aiSuggestionText = marked( aiSuggestionText );

		const message = `
            <div class="scc-ai-chat-bubble scc-ai-chat-bubble-wizard">

                <div class="scc-ai-chat-bubble-content position-relative">
					<div class="scc-ai-chat-bubble-avatar scc-ai-small-avatar">
						<img src="${ aiAvatar }" alt="">
					</div>
					<div class="scc-ai-message-actions ${ aiHideActionsClass }">
					
					<div class="scc-ai-copy-message-confirmation-container">
						${ aiAddElementsButton }
						<a id="scc-ai-chat-copy-button" class="scc-ai-chat-action-button material-icons-outlined me-2" onclick="sccAiUtils.copyAiResponseToClipboard(this);" title="Copy">copy</a>
						<a id="scc-ai-chat-regenerate-button" class="scc-ai-chat-action-button material-icons-outlined" onclick="sccAiUtils.aiWizardRequest('${ requestType }', true);" title="Regenerate">refresh</a>
						<span class="scc-ai-copy-message-confirmation scc-hidden" >Copied!</span>
					</div>
					</div>
					<div class="mt-3"></div>
					<div class="scc-ai-chat-avatar-divider ${ aiHideAiAvatar }"></div>

                    <div class="${ aiMessageClasses }">
						<div class="scc-ai-markdown-response">
							${ aiMessageText }
						</div>
                        ${ aiSuggestionText }
						<div class="scc-ai-wizard-add-elements-container">
							${ aiAddElementsButton }
						</div>
                    </div>
                </div>
            </div>
        `;

		if ( optionType === 'optimize-form' ) {
			aiFormOptimizer.insertAdjacentHTML( 'beforeend', message );
			aiFormOptimizer.scrollTop = aiFormOptimizer.scrollHeight;
		}
		if ( optionType === 'suggest-elements' ) {
			aiChat.insertAdjacentHTML( 'beforeend', message );
			aiChat.scrollTop = aiChat.scrollHeight;
		}
		if ( optionType === 'setup-wizard' ) {
			const accordionBody = aiSetupWizard.querySelector( '.scc-ai-wizard-setup-accordion-body' );
			accordionBody.insertAdjacentHTML( 'beforeend', message );
			//aiSetupWizard.scrollTop = aiSetupWizard.scrollHeight;
		}
	},
	addElementsWithAi: ( button, cleanCalculator = false ) => {
		
		if (typeof sccAiUtils.toggleAiWizardOverlay !== 'function') {
			console.error("toggleAiWizardOverlay is not a function");
			return;
		}
		
		try {
			sccAiUtils.toggleAiWizardOverlay(true);
		} catch (error) {
			console.error("Error while showing the overlay:", error);
		}
		
		// Get all buttons with the class and disable them
		const allButtons = document.querySelectorAll('.scc-ai-wizard-add-elements-btn');
		allButtons.forEach(btn => {
			btn.setAttribute('disabled', true);
			const btnSpinner = btn.querySelector('.scc-save-btn-spinner');
			if (btnSpinner) {
				btnSpinner.classList.remove('scc-hidden');
			}
		});
		
		// Define loading states
		const loadingStates = [
			'Preparing elements',
			'Configuring parameters',
			'Verifying elements'
		];
		let currentState = 0;
		
		// Create interval to cycle through states
		const stateInterval = setInterval(() => {
			allButtons.forEach(btn => {
				const btnText = btn.querySelector('.scc-ai-button-text') || btn.lastChild;
				btnText.textContent = ` ${loadingStates[currentState]}`;
			});
			
			// Also update the overlay subtitle
			const overlaySubtitle = document.querySelector('.scc-ai-wizard-overlay-message + div');
			if (overlaySubtitle) {
				overlaySubtitle.textContent = loadingStates[currentState];
			} else {
				console.warn("No se encontró el subtítulo del overlay");
			}
			
			currentState = (currentState + 1) % loadingStates.length;
		}, 2000);

		const container = button.closest('.scc-ai-chat-bubble-wizard');
		const aiResponse = container.querySelector('.scc-ai-markdown-response').innerText;

		const schema = sccAiUtils.getCalculatorDataSchema();
		const firstSection = schema.sections[0];
		const firstSectionId = firstSection.sectionId;
		const firstSubsectionId = firstSection.subsections[0].subsectionId;
		const params = {
			nonce: pageEditCalculator.nonce,
			calculator_id: sccAiUtils.getCalcId(),
			section_target_id: firstSectionId,
			clean_calculator: cleanCalculator,
			first_subsection_id: firstSubsectionId,
			ai_response: aiResponse,
		};
		const action = 'scc_ai_wizard_add_elements';
		const formData = new FormData();
		formData.append('request_data', JSON.stringify(params));

		const ajaxRoute = ajaxurl + '?action=' + action + '&nonce=' + pageEditCalculator.nonce;
		
		fetch(ajaxRoute, {
			method: 'POST',
			body: formData,
		})
			.then((response) => response.json())
			.then((data) => {
				clearInterval(stateInterval);
				if(cleanCalculator === true) {
					sccAiUtils.addCalculatorSettingsWithAi(button);
				} else {
					// Hide the overlay before reloading
					try {
						sccAiUtils.toggleAiWizardOverlay(false);
					} catch (error) {
						console.error("Error while hiding the overlay:", error);
					}
					location.reload();
				}
			})
			.catch((error) => {
				clearInterval(stateInterval);
				// Hide the overlay on error
				try {
					sccAiUtils.toggleAiWizardOverlay(false);
					console.log("Overlay hidden correctly after error");
				} catch (error) {
					console.error("Error while hiding the overlay:", error);
				}
				console.error('Error:', error);
			});
	},
	addCalculatorSettingsWithAi: ( button ) => {
		button.setAttribute( 'disabled', true );
		const spinner = button.querySelector( '.scc-save-btn-spinner' );
		spinner.classList.remove( 'scc-hidden' );
		const buttonText = button.querySelector('.scc-ai-button-text') || button.lastChild;
		buttonText.textContent = ' Updating Calculator Settings';
		
		// Update the overlay subtitle
		const overlaySubtitle = document.querySelector('.scc-ai-wizard-overlay-message + div');
		if (overlaySubtitle) {
			overlaySubtitle.textContent = 'Updating Calculator Settings';
		}

		const container = button.closest( '.scc-ai-chat-bubble-wizard' );
		const aiResponse = container.querySelector( '.scc-ai-markdown-response' ).innerText;

		const params = {
			nonce: pageEditCalculator.nonce,
			calculator_id: sccAiUtils.getCalcId(),
			ai_requested_settings: aiResponse,
		};
		const action = 'scc_ai_wizard_add_calculator_settings';
		const formData = new FormData();
		formData.append( 'request_data', JSON.stringify( params ) );

		const ajaxRoute = ajaxurl + '?action=' + action + '&nonce=' + pageEditCalculator.nonce;
		
		fetch( ajaxRoute, {
			method: 'POST',
			body: formData,
		})
			.then( ( response ) => response.json() )
			.then( ( data ) => {
				// Hide the overlay before reloading
				sccAiUtils.toggleAiWizardOverlay(false);
				location.reload();
			})
			.catch( ( error ) => {
				// Hide the overlay on error
				sccAiUtils.toggleAiWizardOverlay(false);
				console.error( 'Error:', error );
			});
	},
	copyAiResponseToClipboard: ( $this ) => {
		// Get the content of the div
		const divContent = $this.closest( '.scc-ai-chat-bubble-content' ).querySelector( '.scc-ai-chat-bubble-text' ).innerText;

		// Create a temporary textarea and copy the content
		const tempTextarea = document.createElement( 'textarea' );
		tempTextarea.value = divContent;
		document.body.appendChild( tempTextarea );
		tempTextarea.select();
		try {
			document.execCommand( 'copy' );

			const confirmation = $this.closest( '.scc-ai-copy-message-confirmation-container' ).querySelector( '.scc-ai-copy-message-confirmation' );
			confirmation.classList.remove( 'scc-hidden' );
			// Remove the confirmation message after 2 seconds
			setTimeout( () => {
				confirmation.classList.add( 'scc-hidden' );
			}, 2000 );
		} catch ( error ) {
			console.error( 'Error copying to clipboard: ', error );
		}
		document.body.removeChild( tempTextarea );
	},
	updateCreditsIndicator( credits, creditIndicator ) {
		const creditLoader = creditIndicator?.parentNode?.querySelector( '.scc-ai-credit-loader' );
		if ( creditLoader ) {
			creditLoader.classList.add( 'scc-hidden' );
		}

		creditIndicator.classList.remove( 'scc-hidden' );
		creditIndicator.querySelector( '.scc-ai-credit-count-total' ).textContent = credits.credits;
		const creditsParts = credits?.credits?.split( '/' );
		const numerator = creditsParts ? parseInt( creditsParts[ 0 ] ) : 0;
		const denominator = creditsParts ? parseInt( creditsParts[ 1 ] ) : 0;

		if ( numerator / denominator <= 0.50 ) {
			creditIndicator.querySelector( '.scc-ai-credit-count-circle-indicator' ).classList.add( 'scc-ai-count-orange' );
		}
		if ( numerator / denominator <= 0 ) {
			creditIndicator.querySelector( '.scc-ai-credit-count-circle-indicator' ).classList.add( 'scc-ai-count-red' );
		}
	},
	getCalcId: () => {
		const urlParams = new URLSearchParams( window.location.search );
		const calcId = urlParams.get( 'id_form' );
		return calcId;
	},
	disableInputsAiWizard: ( $this, chatPanel = null ) => {
		const chat = $this.closest( '.scc-ai-assistant-chat' ) || $this.closest( '.scc-ai-wizard-menu' );
		const prompt = chat.querySelector( '.scc-ai-assistant-text-field' );
		const sendButton = chat.querySelector( '.scc-ai-assistant-send-btn' );
		const aiLoader = chat.querySelector( '.scc-ai-response-loader' );
		const footer = chat.querySelector( '.scc-ai-wizard-menu-footer' );

		prompt.setAttribute( 'disabled', true );
		prompt.classList.add( 'scc-hidden' );
		sendButton.setAttribute( 'disabled', true );
		sendButton.classList.add( 'scc-hidden' );
		aiLoader.classList.add( 'scc-hidden' );

		if ( chatPanel ) {
			chatPanel.classList.add( 'scc-hidden' );
			chat.querySelector( '.scc-ai-wizard-menu-buttons' ).classList.remove( 'scc-hidden' );
			chat.querySelector( '.scc-ai-wizard-setup' ).classList.add( 'scc-hidden' );
			chat.querySelector( '.scc-ai-wizard-setup' ).classList.remove( 'scc-active-tab' );
			chat.querySelector( '.scc-ai-wizard-form-optimizer' ).classList.add( 'scc-hidden' );
			chat.querySelector( '.scc-ai-wizard-form-optimizer' ).classList.remove( 'scc-active-tab' );
			chat.querySelector( '.scc-ai-wizard-analytics-insights' ).classList.add( 'scc-hidden' );
			chat.querySelector( '.scc-ai-wizard-analytics-insights' ).classList.remove( 'scc-active-tab' );
			chat.querySelector( '.scc-ai-wizard-advanced-pricing-formula' ).classList.add( 'scc-hidden' );
			chat.querySelector( '.scc-ai-wizard-advanced-pricing-formula' ).classList.remove( 'scc-active-tab' );
			footer.classList.add( 'scc-hidden' );
		}
	},
	enableInputsAiWizard: ( $this, chatPanel = null ) => {
		const chat = $this.closest( '.scc-ai-assistant-chat' ) || $this.closest( '.scc-ai-wizard-menu' );
		const prompt = chat.querySelector( '.scc-ai-assistant-text-field' );
		const sendButton = chat.querySelector( '.scc-ai-assistant-send-btn' );
		const aiLoader = chat.querySelector( '.scc-ai-response-loader' );
		const footer = chat.querySelector( '.scc-ai-wizard-menu-footer' );

		prompt.removeAttribute( 'disabled' );
		prompt.classList.remove( 'scc-hidden' );
		sendButton.removeAttribute( 'disabled' );
		sendButton.classList.remove( 'scc-hidden' );
		aiLoader.classList.add( 'scc-hidden' );

		if ( chatPanel ) {
			chatPanel.classList.remove( 'scc-hidden' );
			chat.querySelector( '.scc-ai-wizard-menu-buttons' ).classList.add( 'scc-hidden' );
			footer.classList.remove( 'scc-hidden' );
		}
	},
	getSetupWizardData( type = null ) {
		const wizardQuizData = JSON.parse( localStorage.getItem( 'wizardQuizData' ) );
		const calcId = sccAiUtils.getCalcId();
		const setupWizardAlert = document.querySelector( '.scc-ai-wizard-consider-start-setup' );
		let response = null;
		if ( wizardQuizData ) {
			wizardQuizData.forEach( ( data ) => {
				if ( data.calcId == calcId ) {
					if ( type === 'industry-questions' ) {
						response = data?.__quizAnswersStore?.step1;
					}
					if ( type === 'quiz' || type === null ) {
						response = data?.__quizAnswersStore;
					}
				}
			} );
		} else if ( setupWizardAlert ) {
			setupWizardAlert.classList.remove( 'scc-hidden' );
		}
		return response;
	},
	showSetupWizardTab( menu ) {
		const menuButtons = menu.querySelector( '.scc-ai-wizard-menu-buttons' );
		const setupWizardTab = menu.querySelector( '.scc-ai-wizard-setup' );
		const backButton = document.getElementById( 'scc-ai-wizard-back-btn' );
		const inputs = menu.querySelector( '.scc-ai-assistant-inputs' );
		const setupWizardAccordion = menu.querySelector( '#scc-setup-wizard-accordion' );

		const aiLoader = menu.querySelector( '.scc-setup-wizard-loader' );
		aiLoader.classList.remove( 'scc-hidden' );
		backButton.classList.remove( 'scc-hidden' );
		menuButtons.classList.add( 'scc-hidden' );
		setupWizardTab.classList.remove( 'scc-hidden' );
		setupWizardTab.classList.add( 'scc-active-tab' );

		const wizardData = sccAiUtils.getSetupWizardData( 'industry-questions' );
		if ( wizardData ) {
			setupWizardAccordion.classList.remove( 'scc-hidden' );
			sccAiUtils.aiWizardRequest( 'setup-wizard' );
			// Rest of your code
		} else {
			setupWizardAccordion.classList.add( 'scc-hidden' );
		}
	},
	showFormOptimizerTab( menu ) {
		const menuButtons = menu.querySelector( '.scc-ai-wizard-menu-buttons' );
		const tab = menu.querySelector( '.scc-ai-wizard-form-optimizer' );
		const backButton = document.getElementById( 'scc-ai-wizard-back-btn' );
		backButton.classList.remove( 'scc-hidden' );

		menuButtons.classList.add( 'scc-hidden' );
		tab.classList.remove( 'scc-hidden' );
		tab.classList.add( 'scc-active-tab' );

		sccAiUtils.aiWizardRequest( 'optimize-form' );
	},
	showAdvancedPricingFormulaTab( menu ) {
		const menuButtons = menu.querySelector( '.scc-ai-wizard-menu-buttons' );
		const tab = menu.querySelector( '.scc-ai-wizard-advanced-pricing-formula' );
		const backButton = document.getElementById( 'scc-ai-wizard-back-btn' );
		backButton.classList.remove( 'scc-hidden' );

		menuButtons.classList.add( 'scc-hidden' );
		tab.classList.remove( 'scc-hidden' );
		tab.classList.add( 'scc-active-tab' );
		sccAiUtils.loadAdvancedPricingFormulaElements( menu );
	},
	showAnalyticsInsightsTab( menu ) {
		const menuButtons = menu.querySelector( '.scc-ai-wizard-menu-buttons' );
		const tab = menu.querySelector( '.scc-ai-wizard-analytics-insights' );
		const backButton = document.getElementById( 'scc-ai-wizard-back-btn' );
		backButton.classList.remove( 'scc-hidden' );

		menuButtons.classList.add( 'scc-hidden' );
		tab.classList.remove( 'scc-hidden' );
		tab.classList.add( 'scc-active-tab' );
	},
	loadAdvancedPricingFormulaElements: ( menu ) => {
		const buttonsContainer = menu.querySelector( '.scc-ai-wizard-advanced-pricing-formula-buttons' );
		const buttonsPanel = buttonsContainer.closest( '.scc-ai-wizard-menu-buttons' );
		const panelMessage = buttonsPanel.querySelector( '.scc-ai-wizard-init-message span' );
		const sccConfig = JSON.parse( document.getElementById( 'scc-data-schema' ).textContent );
		let vmathChecker = false;
		// Remove all previous buttons
		while ( buttonsContainer.firstChild ) {
			buttonsContainer.firstChild.remove();
		}

		sccConfig.flatMap( ( section ) => section.subsection )
			.flatMap( ( subsection ) => subsection.element )
			.filter( ( element ) => element.type === 'math' )
			.forEach( ( element ) => {
				vmathChecker = true;
				const button = document.createElement( 'button' );
				button.setAttribute( 'data-option-type', 'setup-wizard' );
				button.classList.add( 'btn', 'btn-alert', 'scc-ai-wizard-option' );
				button.textContent = element.titleElement;
				button.addEventListener( 'click', () => sccAiUtils.openAiWizardElement( element.id, 'vmath_request' ) );
				buttonsContainer.appendChild( button );
			} );

		if ( ! vmathChecker ) {
			panelMessage.textContent = 'No Advanced Pricing Formula elements available';
		} else {
			panelMessage.textContent = 'Select the element you want to edit';
		}
	},
	openAiWizardElement: ( elementId, requestType = null ) => {
		const element = document.querySelector( `input.input_id_element[value="${ elementId }"]` );
		const elementContainer = element?.closest( '.elements_added' );
		const aiButton = elementContainer?.querySelector( '.scc-ai-icon' );
		if ( aiButton ) {
			aiButton.click();
			if ( requestType === 'vmath_request' ) {
				setTimeout( () => {
					const vmathButton = elementContainer.querySelector( '.scc-ai-response-option[data-suggested-prompt="vmath_request"]' );
					sccResetAiAssistant( vmathButton );
					if ( vmathButton ) {
						vmathButton.click();
					}
				}, 0 );
			}
		}
		sccAiUtils.toggleAiWizardPanel( null );
	},
	toggleAiWizardPanel: ( event = null ) => {
		const panel = sccAiUtils.aiWizardMenu;
		if ( ! panel ) {
			return;
		}
		// Get the current panel
		if ( event ) {
			event.preventDefault();
		}

		if ( panel.classList.contains( 'scc-hidden' ) ) {
			panel.closest( '.scc-ai-wizard-panel-container' ).classList.add( 'scc-ai-wizard-overlap' );
			sccAiUtils.closeSupportChat();
			sccAiUtils.checkAiCredits( 'edit-calculator-page' ).then( ( credits ) => {
				const creditIndicator = panel?.querySelector( '.scc-ai-credit-count' );
				sccAiUtils.updateCreditsIndicator( credits, creditIndicator );
			} ).catch( ( error ) => {
				console.error( error );
			} );
		} else {
			panel.closest( '.scc-ai-wizard-panel-container' ).classList.remove( 'scc-ai-wizard-overlap' );
		}
		// Toggle the current panel
		if ( panel ) {
			panel.classList.toggle( 'scc-hidden' );
		}
	},
	getSiteInfoWithAi: async ( page = 'edit-calculator-page', siteURL = null ) => {
		let nonce = '';
		if( typeof pageEditCalculator !== 'undefined' && pageEditCalculator.nonce ) {
			nonce = pageEditCalculator.nonce;
		}else if( typeof pageAddCalculator !== 'undefined' && pageAddCalculator.nonce ) {
			nonce = pageAddCalculator.nonce;
		}
		const businessDescriptionLoader = document.querySelector( '.scc-ai-assisted-setup-wiz-business-description-loader' );
		businessDescriptionLoader.classList.remove( 'scc-hidden' );
		const params = new URLSearchParams( {
			action: 'scc_ai_get_site_info_with_ai',
			nonce: nonce,
			calculator_id: sccAiUtils.getCalcId(),
			page: page,
			siteURL: siteURL,
		} );
		const response = await fetch( `${ ajaxurl }?${ params }` );
		const data = await response.json();
		
		// Check if the response indicates an error
		if ( !data.success ) {
			// Extract error message from ai_raw_response if available
			let errorMessage = 'An error occurred. Please try again.';
			if ( data.data && data.data.ai_raw_response ) {
				errorMessage = data.data.ai_raw_response;
			} else if ( data.data && typeof data.data === 'string' ) {
				errorMessage = data.data;
			} else if ( data.data && data.data.error ) {
				errorMessage = data.data.error;
			}
			const error = new Error( errorMessage );
			error.responseData = data.data;
			throw error;
		}
		
		const dataResponse = data.data;
		
		// Check if the response data contains an error message in ai_raw_response
		// (e.g., when credits are insufficient but the request technically succeeded)
		if ( dataResponse && dataResponse.ai_raw_response ) {
			const aiResponse = dataResponse.ai_raw_response;
			// Check if it's an error message (contains keywords like "credit", "quota", "not enough", "sorry")
			if ( typeof aiResponse === 'string' && 
				( aiResponse.toLowerCase().includes( 'credit' ) || 
				  aiResponse.toLowerCase().includes( 'quota' ) || 
				  aiResponse.toLowerCase().includes( 'not enough' ) ||
				  aiResponse.toLowerCase().includes( 'sorry' ) ) ) {
				const error = new Error( aiResponse );
				error.responseData = dataResponse;
				throw error;
			}
		}

		return dataResponse;
	},
	forceCloseAiWizardPanel: () => {
		const panel = sccAiUtils.aiWizardMenu;
		if ( panel ) {
			panel.classList.add( 'scc-hidden' );
		}
	},
	checkAiCredits: async ( page ) => {
		let nonce = '';
		if ( page === 'edit-calculator-page' && typeof pageEditCalculator !== 'undefined' && pageEditCalculator.nonce ) {
			nonce = pageEditCalculator.nonce;
		}
		if ( page === 'view-quotes-page' && typeof pageViewQuotes !== 'undefined' && pageViewQuotes.nonce ) {
			nonce = pageViewQuotes.nonce;
		}
		if ( page === 'add-calculator-page' && typeof pageAddCalculator !== 'undefined' && pageAddCalculator.nonce ) {
			nonce = pageAddCalculator.nonce;
		}

		const params = new URLSearchParams( {
			action: 'scc_ai_check_credits',
			page_name: page,
			nonce,
		} );

		try {
			const response = await fetch( `${ ajaxurl }?${ params }`, {
				method: 'GET',
				headers: {
					'Content-Type': 'application/json',
				},
			} );

			const data = await response.json();

			return data;
		} catch ( error ) {
			console.error( 'Error:', error );
			return 0;
		}
	},
	updateCalculatorDataSchema: ( postSchemaUpdateCallback = null ) => {
		const schema = document.getElementById( 'scc-data-schema' );
		const params = {
			action: 'scc_update_calculator_data_schema',
			nonce: pageEditCalculator.nonce,
			calculator_id: sccAiUtils.getCalcId(),
		};
		const action = 'scc_update_calculator_data_schema';
		const formData = new FormData();
		formData.append( 'request_data', JSON.stringify( params ) );

		const ajaxRoute = ajaxurl + '?action=' + action + '&nonce=' + pageEditCalculator.nonce;

		fetch( ajaxRoute, {
			method: 'POST',
			body: formData, // Send the formData
		} )
			.then( ( response ) => response.json() )
			.then( ( data ) => {
				let sections = null;
				if ( data && data.schema ) {
					sections = JSON.stringify( data.schema );
				} else {
					console.error( 'Invalid response structure:', data );
				}
				schema.textContent = sections;
				const menu = document.querySelector( '.scc-ai-wizard-panel-container' );
				sccAiUtils.loadAdvancedPricingFormulaElements( menu );
				sccAiUtils.updateMultiplierGUI( data.schema );
				if ( postSchemaUpdateCallback ) {
					postSchemaUpdateCallback( data.schema );
				}
			} )
			.catch( ( error ) => {
				console.error( 'Error:', error );
			} );
	},
	getCalculatorDataSchema: () => {
		const calculatorId = sccAiUtils.getCalcId();
		const calculatorName = document.getElementById( 'costcalculatorname' )?.value;
		const schema = document.getElementById( 'scc-data-schema' );
		let data = [];
		if ( schema ) {
			data = JSON.parse( schema.textContent );
		}
		const sections = data.map( ( section ) => ( {
			sectionName: section.name,
			sectionId: section.id,
			sectionDescription: section.description,
			subsections: section.subsection.map( ( subsection ) => ( {
				subsectionId: subsection.id,
				elements: subsection.element.map( ( element ) => ( {
					elementName: element.titleElement,
					elementPrice: element.elementitems.length > 0 ? element.elementitems[ 0 ].price : null,
					elementType: element.type,
					elementDescription: element.elementitems.length > 0 ? element.elementitems[ 0 ].description : null,
					elementItems: element.elementitems.map( ( item ) => ( {
						itemName: item.name,
						itemPrice: item.price,
						itemDescription: item.description,
					} ) ),
				} ) ),
			} ) ),
		} ) );
		// Add calculatorId and calculatorName at the beginning of extractedData
		const result = {
			calculatorId,
			calculatorName,
			sections,
		};
		return result;
	},
	// this function is used to detect sliders, date or any multiplier element in the calculator data schema
	updateMultiplierGUI: (schema) => {
		if (!schema) {
			const sccDataSchema = document.getElementById('scc-data-schema');
			if (sccDataSchema) {
				schema = JSON.parse(sccDataSchema.textContent);
			} else {
				return;
			}
		}
		schema.forEach((section) => {
			section.subsection.forEach((subsection) => {
				let multiplier = false;
				const subsectionInput = document.querySelector('input.input_subsection_id[value="' + subsection.id + '"]');
				const subsectionArea = subsectionInput?.closest('.boardOption')?.querySelector('.subsection-area');
				const elementCount = subsection.element ? subsection.element?.length : 0;
				
				let countableElements = 0;
				let hasSlider = false;
	
				subsection.element.forEach((element) => {
					let elementIsMultiplier = false;
					let noCostElement = false;
					if (element.type === 'comment box' || element.type === 'signature box' || element.type === 'file upload' || element.type === 'texthtml') {
						noCostElement = true;
					} else {
						countableElements++;
					}
					if (element.type === 'slider') {
						elementIsMultiplier = true;
						multiplier = true;
						hasSlider = true;
					} else if (element.type === 'date') {
						if (element.value6 && element.value1 === 'date_range') {
							const escapedJsonString = element.value6;
							const unescapedJsonString = JSON.parse(`"${escapedJsonString}"`);
							const jsonObject = JSON.parse(unescapedJsonString);
							if (jsonObject.date_range_pricing_structure &&
								(jsonObject.date_range_pricing_structure === 'quantity_mod' ||
								jsonObject.date_range_pricing_structure === 'quantity_modifier_and_unit_price')
							) {
								elementIsMultiplier = true;
								multiplier = true;
							}
						}
					}
					const elementInput = subsectionArea.querySelector('input.input_id_element[value="' + element.id + '"]');
					const elementContainer = elementInput?.closest('.elements_added');
					const elementLinkLine = elementContainer?.querySelector('.scc-link-line');
	
					// Add last element connector class
					if (elementLinkLine && !elementIsMultiplier) {
						elementLinkLine.classList.remove('scc-invert-element-connector');
						elementLinkLine.classList.remove('scc-last-element-connector');
						if (multiplier) {
							elementLinkLine.classList.add('scc-invert-element-connector');
						}
					}
					// Add line if the element does not have a link line and is not a multiplier
					if (!elementLinkLine && !elementIsMultiplier) {
						if (elementContainer) {
							const connectorExtraClass = multiplier ? 'scc-invert-element-connector' : '';
							const html = `<div class="scc-line-hider"></div><div class="scc-element-connector-line scc-link-line ${connectorExtraClass}"></div>`;
							const htmlNoCost = `<div class="scc-line-hider"></div>`;
							if (!noCostElement) {
								elementContainer.insertAdjacentHTML('afterbegin', html);
							} else {
								elementContainer.insertAdjacentHTML('afterbegin', htmlNoCost);
							}
						}
					}
					// Add Line if the element is a multiplier and does not have a link line
					if (elementIsMultiplier && !elementLinkLine) {
						const editorContainer = document.querySelector('.scc-pane-container');
						const dataLinkIcon = editorContainer.getAttribute('data-link-icon');
						if (elementContainer) {
							const html = `<div class="scc-line-hider"></div>
										<div class="scc-multiplier-connector-line scc-link-line scc-hidden">
											<div class="scc-multiplier-connector-link" data-setting-tooltip-type="element-multiplier-tt" data-bs-original-title title>
												<span class="scc-icn-wrapper"><img src="${dataLinkIcon}"></span>
											</div>
										</div>`;
							elementContainer.insertAdjacentHTML('afterbegin', html);
							const tooltipNode = elementContainer.querySelector('.scc-multiplier-connector-link');
							applySettingTooltip(tooltipNode);
						}
					}
				});
	
				// Out of subsection loop
				subsectionArea.querySelectorAll('.scc-line-hider').forEach((line) => {
					line.classList.remove('scc-first-element-subsection');
					line.classList.remove('scc-last-element-subsection');
				});
	
				const elements = subsectionArea.querySelectorAll('.elements_added');
	
				if (elements.length > 0) {
					const noCostElementTypes = ['comment box', 'signature box', 'file upload', 'texthtml'];
	
					let firstCostElementIndex = 0;
					let lastCostElementIndex = elements.length - 1;
					const isNoCostElement = (elementType) => noCostElementTypes.includes(elementType);
					for (let i = 0; i < elements.length; i++) {
						const elementType = elements[i].querySelector('[data-element-setup-type]')?.getAttribute('data-element-setup-type');
						if (!isNoCostElement(elementType)) {
							firstCostElementIndex = i;
							break;
						}
					}
	
					for (let i = elements.length - 1; i >= 0; i--) {
						const elementType = elements[i].querySelector('[data-element-setup-type]')?.getAttribute('data-element-setup-type');
						if (!isNoCostElement(elementType)) {
							lastCostElementIndex = i;
							break;
						}
					}
	
					elements.forEach((element, index) => {
						const lineHider = element.querySelector('.scc-line-hider');
						const elementType = element.querySelector('[data-element-setup-type]')?.getAttribute('data-element-setup-type');
	
						if (isNoCostElement(elementType)) {
							if (index > firstCostElementIndex && index < lastCostElementIndex) {
								element.querySelectorAll('.scc-line-hider').forEach((hider) => hider.remove());
							} else {
								lineHider?.classList.add('scc-no-cost-element');
							}
						} else {
							if (index === firstCostElementIndex) {
								lineHider?.classList.add('scc-first-element-subsection');
							}
							if (index === lastCostElementIndex) {
								lineHider?.classList.add('scc-last-element-subsection');
							}
						}
	
						if (index < firstCostElementIndex) {
							lineHider?.classList.add('scc-first-element-subsection-no-cost');
						}
						if (index > lastCostElementIndex) {
							lineHider?.classList.add('scc-last-element-subsection-no-cost');
						}
					});
				}
				if (hasSlider && countableElements <= 1) {
					subsectionArea.querySelectorAll('.scc-link-line').forEach((line) => {
						line.classList.add('scc-hidden');
					});
				} else if (multiplier && elementCount > 1) {
					subsectionArea.querySelectorAll('.scc-link-line').forEach((line) => {
						line.classList.remove('scc-hidden');
					});
				} else if (!multiplier || elementCount <= 1) {
					subsectionArea.querySelectorAll('.scc-link-line').forEach((line) => {
						line.classList.add('scc-hidden');
					});
				}
			});
		});
	},
	switchMultiplierLines: ( element, multiplier ) => {
		const editorContainer = document.querySelector( '.scc-pane-container' );
		const dataLinkIcon = editorContainer.getAttribute( 'data-link-icon' );
		element.querySelectorAll( '.scc-link-line' ).forEach( ( line ) => {
			line.remove();
		} );
		// Create and append the new element based on the multiplier value
		const newLine = document.createElement( 'div' );
		if ( multiplier ) {
			newLine.className = 'scc-multiplier-connector-line scc-link-line scc-hidden';
			newLine.innerHTML = `
			   <div class="scc-multiplier-connector-link">
				   <span class="scc-icn-wrapper"><img src="${ dataLinkIcon }"></span>
			   </div>
		   `;
		} else {
			newLine.className = 'scc-element-connector-line scc-link-line scc-hidden';
		}
		element.appendChild( newLine );
	},
	openIntelligentElementSuggester: () => {
		const aiWizardMenu = document.querySelector( '.scc-ai-wizard-menu' );
		if ( aiWizardMenu.classList.contains( 'scc-hidden' ) ) {
			sccAiUtils.toggleAiWizardPanel( null );
		}

		const suggesterButton = document.getElementById( 'scc-ai-wizard-suggest-element' );
		if ( suggesterButton ) {
			suggesterButton.click();
		}
	},
	startLoaderMessageRotation: (loaderContainer) => {
		// Clear any existing interval
		sccAiUtils.stopLoaderMessageRotation();
		
		// Get the message element
		const messageElement = loaderContainer.querySelector('.scc-ai-wizard-loader-description');
		if (!messageElement) return;
		
		// Set initial message
		let currentIndex = 0;
		messageElement.textContent = sccAiUtils.aiWizardLoaderMessages[currentIndex];
		
		// Start rotation
		sccAiUtils.loaderMessageInterval = setInterval(() => {
			currentIndex = (currentIndex + 1) % sccAiUtils.aiWizardLoaderMessages.length;
			messageElement.textContent = sccAiUtils.aiWizardLoaderMessages[currentIndex];
		}, 4000);
	},
	stopLoaderMessageRotation: () => {
		if (sccAiUtils.loaderMessageInterval) {
			clearInterval(sccAiUtils.loaderMessageInterval);
			sccAiUtils.loaderMessageInterval = null;
		}
	},
};

//let button = document.getElementById('scc-ai-wizard-retake');
//window.showAiWizardOverlay(button, false);

window.showAiWizardOverlay = function(button, cleanCalculator = false) {
    // Create the overlay directly without depending on sccAiUtils
    let overlay = document.querySelector('.scc-ai-wizard-overlay');
    
    if (!overlay) {
        // Create the overlay
        overlay = document.createElement('div');
        overlay.className = 'scc-ai-wizard-overlay';
        overlay.id = 'scc-ai-wizard-global-overlay';
        
        // Create the icon
        const wizardIcon = document.createElement('div');
        wizardIcon.style.width = '80px';
        wizardIcon.style.height = '80px';
        wizardIcon.style.marginBottom = '20px';
        wizardIcon.innerHTML = `
            <svg id="a" xmlns="http://www.w3.org/2000/svg" width="80" height="80" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 826 826"><defs><linearGradient id="b" x1="138.54" y1="13.67" x2="797.94" y2="627.4" gradientTransform="translate(0 826) scale(1 -1)" gradientUnits="userSpaceOnUse"><stop offset="0" stop-color="#2b41f0"/><stop offset="1" stop-color="#722aef"/></linearGradient></defs><path d="M279.75,758.61l-31.47,13.75c-5.52,2.4-10.23,6.24-13.64,11.09-1.32,1.83-2.44,3.79-3.35,5.89l-13.71,31.42c-.53,1.23-1.67,1.83-2.81,1.83s-2.27-.6-2.79-1.83l-13.28-30.89c-3.29-7.63-9.32-13.72-16.91-17.07l-32.06-14.19c-2.19-.96-2.41-3.8-.7-5.14.2-.18.43-.32.7-.42l31.51-13.77c7.59-3.31,13.67-9.37,16.98-16.98l13.74-31.48c.54-1.22,1.66-1.83,2.8-1.83s2.27.61,2.79,1.83l13.74,31.48c3.31,7.59,9.37,13.67,16.99,16.98l31.47,13.75c2.44,1.05,2.44,4.51,0,5.58Z" fill="#ffab02" stroke-width="0"/><path d="M148.11,493.37l12.38-28.64c2.98-6.91,8.45-12.47,15.32-15.48l28.39-12.5c2.19-.97,2.19-4.14,0-5.08l-28.39-12.5c-6.84-3.01-12.35-8.53-15.32-15.48l-12.38-28.67c-.96-2.21-4.1-2.21-5.03,0l-12.38,28.67c-2.97,6.91-8.45,12.47-15.32,15.48l-28.42,12.54c-2.19.97-2.19,4.11,0,5.08l28.9,12.92c6.84,3.04,12.28,8.6,15.22,15.54l12,28.12c.96,2.21,4.1,2.24,5.03,0h0Z" fill="#ffab02" stroke-width="0"/><path d="M556.02,334.02l36.83,16.26c8.75,3.85,15.64,10.92,19.39,19.58l15.28,35.48c1.24,2.87,5.18,2.87,6.44,0l15.73-36.13c3.85-8.75,10.8-15.73,19.5-19.5l36.13-15.73c2.76-1.24,2.76-5.18,0-6.44l-36.13-15.73c-8.75-3.86-15.73-10.8-19.5-19.5l-15.73-36.13c-1.24-2.76-5.18-2.76-6.44,0l-15.73,36.13c-3.85,8.75-10.8,15.73-19.5,19.5l-36.21,15.81c-2.76,1.24-2.76,5.18,0,6.44l-.08-.09.03.03v.02Z" fill="#ffab02" stroke-width="0"/><path d="M685.64,605.25l-172.82-1.39c-10.55,27.18-36.85,46.57-67.64,46.92-.49,0-.97,0-1.46,0h-65.2c-.29,0-.59,0-.88,0-31.12-.63-57.57-20.72-67.61-48.56h-167.96c-33.68,29.45-67.35,58.89-101.02,88.32l-.29.25c18.17,12.34,49.23,32.11,91.38,51.74,1.14-1.62,2.49-3.1,4.03-4.38,1.72-1.45,3.63-2.62,5.71-3.5l31.37-13.7c2.98-1.3,5.34-3.65,6.64-6.65l13.73-31.44c3.66-8.43,11.91-13.87,21.14-13.87s17.51,5.45,21.15,13.91l13.72,31.41c1.3,2.99,3.65,5.34,6.65,6.64l31.44,13.73c8.39,3.65,13.83,11.9,13.86,21.06.03,9.19-5.39,17.51-13.82,21.2l-24.81,10.83c41.47,9.66,87.54,16.2,137.64,17.04,1.77.03,3.55.05,5.34.07,2.96.02,5.93.02,8.92.01,1.49-.01,2.98-.02,4.47-.04,187.72-2.18,319.65-83.93,367.65-117.09l-101.33-82.51ZM483.12,488.16c-7.88-12.75-21.85-21.3-37.88-21.61-.34-.02-.68-.02-1.02-.02h-65.09c-16.39,0-30.77,8.64-38.8,21.63-4.32,6.96-6.81,15.19-6.81,23.98v65.2c0,.6.01,1.22.04,1.82.33,8.58,3.05,16.58,7.52,23.3,8.15,12.36,22.16,20.49,38.05,20.49h65.19c15.42,0,29.04-7.64,37.28-19.34,4.94-6.97,7.95-15.37,8.29-24.45.03-.6.05-1.22.05-1.82v-65.2c0-8.79-2.49-17.02-6.82-23.98ZM627.72,513.06c-.2-.01-.4-.01-.6-.01h-109.35v64.29c0,.62,0,1.22-.03,1.82h0s155.77.01,155.77.01v-19.73c0-25.42-20.44-46.07-45.79-46.38ZM305.68,577.34v-64.29h-106.91c-25.62,0-46.39,20.77-46.39,46.39v19.73h153.33c-.03-.6-.03-1.21-.03-1.83ZM560.67,4.03l-.41.29-290.79,207.19-55.22,276.65h0s95.45.01,95.45.01c9.96-28.76,37.33-49.47,69.43-49.47h65.19c32.11,0,59.46,20.71,69.43,49.46h0s91.2.01,91.2.01l-101.91-190.49,62.19-116.92-32.27-14.38c-7.1-3.16-10.3-11.49-7.13-18.59,2.32-5.23,7.42-8.32,12.78-8.36h.1c1.92,0,3.87.4,5.72,1.22l65.24,29.05,96.34,42.05.87.38L560.67,4.03Z" fill="url(#b)" fill-rule="evenodd" stroke-width="0"/>
        `;
        
        // Create steps tracker
        const stepsContainer = document.createElement('div');
        stepsContainer.className = 'scc-ai-wizard-steps-container';
        stepsContainer.style.marginTop = '30px';
        stepsContainer.style.display = 'flex';
        stepsContainer.style.flexDirection = 'column';
        stepsContainer.style.alignItems = 'flex-start';
        
        // Add steps
        const steps = [
            {text: 'Processing your inputs...', done: true},
            {text: 'Applying calculation elements...', done: true},
            {text: 'Finalizing your calculator...', done: false}
        ];
        
        steps.forEach(step => {
            const stepItem = document.createElement('div');
            stepItem.className = 'scc-ai-wizard-step-item';
            stepItem.style.display = 'flex';
            stepItem.style.alignItems = 'center';
            stepItem.style.margin = '5px 0';
            
            // Create icon for step
            const icon = document.createElement('span');
            icon.style.display = 'inline-flex';
            icon.style.alignItems = 'center';
            icon.style.justifyContent = 'center';
            icon.style.width = '24px';
            icon.style.height = '24px';
            icon.style.borderRadius = '50%';
            icon.style.marginRight = '10px';
            
            if (step.done) {
                icon.style.backgroundColor = '#5BB75B';
                icon.innerHTML = `
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="white" stroke-width="3" stroke-linecap="round" stroke-linejoin="round">
                        <polyline points="20 6 9 17 4 12"></polyline>
                    </svg>
                `;
            } else {
                icon.style.backgroundColor = '#6d6d6d';
                icon.innerHTML = `
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#ffffff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="12" cy="12" r="10"></circle>
                    </svg>
                `;
            }
            
            // Create text for step
            const text = document.createElement('span');
            text.textContent = step.text;
            text.style.fontSize = '16px';
            text.style.color = step.done ? 'black' : '#9999A0';
            
            stepItem.appendChild(icon);
            stepItem.appendChild(text);
            stepsContainer.appendChild(stepItem);
        });
        
        // Create message element
        const message = document.createElement('div');
        message.className = 'scc-ai-wizard-overlay-message';
        message.id = 'scc-ai-wizard-overlay-message';
        message.textContent = 'Almost There!';
        message.style.fontSize = '28px';
        message.style.fontWeight = 'bold';
        message.style.color = 'black';
        message.style.marginTop = '20px';
        message.style.marginBottom = '10px';
        
        // Create subtitle
        const subtitle = document.createElement('div');
        subtitle.id = 'scc-ai-wizard-overlay-subtitle';
        subtitle.textContent = 'Your calculator will be ready in just a moment';
        subtitle.style.color = '#9999A0';
        subtitle.style.fontSize = '16px';
        subtitle.style.marginBottom = '30px';
        
        // Create progress bar container
        const progressBarsWrapper = document.createElement('div');
        progressBarsWrapper.style.width = '80%';
        progressBarsWrapper.style.maxWidth = '600px';
        progressBarsWrapper.style.marginBottom = '40px';
        
        // Create main progress bar
        const progressContainer = document.createElement('div');
        progressContainer.style.width = '100%';
        progressContainer.style.height = '10px';
        progressContainer.style.backgroundColor = '#dddddd';
        progressContainer.style.borderRadius = '5px';
        progressContainer.style.overflow = 'hidden';
        progressContainer.style.marginBottom = '8px';
        
        const progressBar = document.createElement('div');
        progressBar.id = 'scc-ai-wizard-progress-bar';
        // Start with minimal width
        progressBar.style.width = '0%';
        progressBar.style.height = '100%';
        progressBar.style.backgroundColor = '#7b5dfa';
        progressBar.style.borderRadius = '5px';
        // Add animation to progress bar
        progressBar.style.transition = 'width 2s ease-in-out';
        
        progressContainer.appendChild(progressBar);
        
        // Create estimated time progress bar (secondary bar)
        const estimatedProgressContainer = document.createElement('div');
        estimatedProgressContainer.style.width = '100%';
        estimatedProgressContainer.style.height = '6px';
        estimatedProgressContainer.style.backgroundColor = '#e8e8e8';
        estimatedProgressContainer.style.borderRadius = '3px';
        estimatedProgressContainer.style.overflow = 'hidden';
        
        const estimatedProgressBar = document.createElement('div');
        estimatedProgressBar.id = 'scc-ai-wizard-estimated-progress-bar';
        estimatedProgressBar.style.width = '0%';
        estimatedProgressBar.style.height = '100%';
        estimatedProgressBar.style.backgroundColor = '#b4a3f5';
        estimatedProgressBar.style.borderRadius = '3px';
        estimatedProgressBar.style.transition = 'width 45s linear';
        
        estimatedProgressContainer.appendChild(estimatedProgressBar);
        
        // Append both progress bars to wrapper
        progressBarsWrapper.appendChild(progressContainer);
        progressBarsWrapper.appendChild(estimatedProgressContainer);
        
        // Append elements to overlay
        overlay.appendChild(wizardIcon);
        overlay.appendChild(message);
        overlay.appendChild(subtitle);
        overlay.appendChild(progressBarsWrapper);
        overlay.appendChild(stepsContainer);
        
        // Style the overlay
        overlay.style.position = 'fixed';
        overlay.style.top = '0';
        overlay.style.left = '0';
        overlay.style.width = '100%';
        overlay.style.height = '100%';
        overlay.style.backgroundColor = 'rgba(255, 255, 255, 0.93)';
        overlay.style.backdropFilter = 'blur(8px)';
        overlay.style.display = 'flex';
        overlay.style.flexDirection = 'column';
        overlay.style.alignItems = 'center';
        overlay.style.justifyContent = 'center';
        overlay.style.zIndex = '99999';
        
        // Add animation to overlay
        overlay.style.opacity = '0';
        overlay.style.transition = 'opacity 0.3s ease';
        
        // Append to body
        document.body.appendChild(overlay);
        
        setTimeout(() => {
            overlay.style.opacity = '1';
            
            // Animate main progress bar to 50% after overlay becomes visible
            const progressBar = document.getElementById('scc-ai-wizard-progress-bar');
            if (progressBar) {
                progressBar.style.width = '50%';
            }
            
            // Start estimated progress bar animation (45 seconds)
            const estimatedProgressBar = document.getElementById('scc-ai-wizard-estimated-progress-bar');
            if (estimatedProgressBar) {
                estimatedProgressBar.style.width = '100%';
            }
        }, 10);
    }
    
    // Define loading states for subtitle updates
    const loadingStates = [
        'Preparing elements',
        'Configuring parameters',
        'Verifying elements'
    ];
    let currentState = 0;
    
    // Interval to update messages
    const stateInterval = setInterval(() => {
        const subtitle = document.getElementById('scc-ai-wizard-overlay-subtitle');
        if (subtitle) {
            subtitle.textContent = loadingStates[currentState];
            console.log("Subtitle updated:", loadingStates[currentState]);
        }
        currentState = (currentState + 1) % loadingStates.length;
    }, 2000);
    
    // Disable all buttons with the class
    const allButtons = document.querySelectorAll('.scc-ai-wizard-add-elements-btn');
    allButtons.forEach(btn => {
        btn.setAttribute('disabled', true);
        const btnSpinner = btn.querySelector('.scc-save-btn-spinner');
        if (btnSpinner) {
            btnSpinner.classList.remove('scc-hidden');
        }
    });
    
    // Get AI response
    const container = button.closest('.scc-ai-chat-bubble-wizard');
    const aiResponse = container.querySelector('.scc-ai-markdown-response').innerText;
    
    // Prepare data for request
    const schema = sccAiUtils.getCalculatorDataSchema();
    const firstSection = schema.sections[0];
    const firstSectionId = firstSection.sectionId;
    
    // Check if subsections exist before accessing subsectionId
    if (!firstSection.subsections || firstSection.subsections.length === 0) {
        // Show error modal using df-modal-root system
        stylishCostCalculatorModal({
            context: 'ai-wizard-no-subsections',
            title: 'No Subsections Available',
            content: 'Please create at least one subsection in your calculator before using the AI wizard.',
            affirmativeButtonText: 'OK',
            affirmativeButtonCallback: () => {
                // Modal will be closed by the modal system
            }
        });
        
        // Re-enable buttons
        allButtons.forEach(btn => {
            btn.removeAttribute('disabled');
            const btnSpinner = btn.querySelector('.scc-save-btn-spinner');
            if (btnSpinner) {
                btnSpinner.classList.add('scc-hidden');
            }
        });
        
        // Clear interval
        clearInterval(stateInterval);
        
        // Hide overlay
        if (overlay) {
            overlay.style.display = 'none';
        }
        
        return;
    }
    
    const firstSubsectionId = firstSection.subsections[0].subsectionId;
    const params = {
        nonce: pageEditCalculator.nonce,
        calculator_id: sccAiUtils.getCalcId(),
        section_target_id: firstSectionId,
        clean_calculator: cleanCalculator,
        first_subsection_id: firstSubsectionId,
        ai_response: aiResponse,
    };
    const action = 'scc_ai_wizard_add_elements';
    const formData = new FormData();
    formData.append('request_data', JSON.stringify(params));
    
    const ajaxRoute = ajaxurl + '?action=' + action + '&nonce=' + pageEditCalculator.nonce;
    
    // Call endpoint
    fetch(ajaxRoute, {
        method: 'POST',
        body: formData,
    })
        .then((response) => response.json())
        .then((data) => {
            clearInterval(stateInterval);
            
            // Update the progress bar to 75% when elements are added
            const progressBar = document.getElementById('scc-ai-wizard-progress-bar');
            if (progressBar) {
                progressBar.style.width = '75%';
            }
            
            // Reset estimated progress bar and start 15-second animation
            const estimatedProgressBar = document.getElementById('scc-ai-wizard-estimated-progress-bar');
            if (estimatedProgressBar) {
                // Reset to 0% instantly
                estimatedProgressBar.style.transition = 'none';
                estimatedProgressBar.style.width = '0%';
                
                // Force reflow to ensure the reset happens
                estimatedProgressBar.offsetHeight;
                
                // Start new 15-second animation
                estimatedProgressBar.style.transition = 'width 20s linear';
                setTimeout(() => {
                    estimatedProgressBar.style.width = '100%';
                }, 10);
            }
            
            if(cleanCalculator === true) {
                // Update subtitle for next phase
                const subtitle = document.getElementById('scc-ai-wizard-overlay-subtitle');
                if (subtitle) {
                    subtitle.textContent = 'Updating Calculator Settings';
                }
                
                // Configure data for next request
                const params = {
                    nonce: pageEditCalculator.nonce,
                    calculator_id: sccAiUtils.getCalcId(),
                    ai_requested_settings: aiResponse,
                };
                const action = 'scc_ai_wizard_add_calculator_settings';
                const formData = new FormData();
                formData.append('request_data', JSON.stringify(params));
                
                const ajaxRoute = ajaxurl + '?action=' + action + '&nonce=' + pageEditCalculator.nonce;
                
                // Call endpoint to update settings
                fetch(ajaxRoute, {
                    method: 'POST',
                    body: formData,
                })
                    .then((response) => response.json())
                    .then((data) => {
                        // Update the progress bar to 100% when settings are complete
                        const progressBar = document.getElementById('scc-ai-wizard-progress-bar');
                        if (progressBar) {
                            progressBar.style.width = '100%';
                        }
                        
                        // Update the last step to completed after settings success
                        const stepsContainer = document.querySelector('.scc-ai-wizard-steps-container');
                        if (stepsContainer) {
                            const icons = stepsContainer.querySelectorAll('.scc-ai-wizard-step-item span:first-child');
                            const texts = stepsContainer.querySelectorAll('.scc-ai-wizard-step-item span:last-child');
                            
                            if (icons[2]) {
                                icons[2].style.backgroundColor = '#5BB75B';
                                icons[2].innerHTML = `
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="white" stroke-width="3" stroke-linecap="round" stroke-linejoin="round">
                                        <polyline points="20 6 9 17 4 12"></polyline>
                                    </svg>
                                `;
                            }
                            
                            if (texts[2]) {
                                texts[2].style.color = 'black';
                            }
                        }
                        
                        // Wait a bit to show the completed state before reloading
                        setTimeout(() => {
                            // Hide overlay before reloading
                            const overlay = document.getElementById('scc-ai-wizard-global-overlay');
                            if (overlay) {
                                overlay.style.opacity = '0';
                                setTimeout(() => {
                                    overlay.remove();
                                    location.reload();
                                }, 300);
                            } else {
                                location.reload();
                            }
                        }, 500);
                    })
                    .catch((error) => {
                        console.error('Error:', error);
                        // Hide overlay on error
                        const overlay = document.getElementById('scc-ai-wizard-global-overlay');
                        if (overlay) {
                            overlay.style.opacity = '0';
                            setTimeout(() => {
                                overlay.remove();
                            }, 300);
                        }
                    });
            } else {
                // Update progress bar to 100% when only adding elements (no settings)
                const progressBar = document.getElementById('scc-ai-wizard-progress-bar');
                if (progressBar) {
                    progressBar.style.width = '100%';
                }
                
                // Update the last step to completed
                const stepsContainer = document.querySelector('.scc-ai-wizard-steps-container');
                if (stepsContainer) {
                    const icons = stepsContainer.querySelectorAll('.scc-ai-wizard-step-item span:first-child');
                    const texts = stepsContainer.querySelectorAll('.scc-ai-wizard-step-item span:last-child');
                    
                    if (icons[2]) {
                        icons[2].style.backgroundColor = '#5BB75B';
                        icons[2].innerHTML = `
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="white" stroke-width="3" stroke-linecap="round" stroke-linejoin="round">
                                <polyline points="20 6 9 17 4 12"></polyline>
                            </svg>
                        `;
                    }
                    
                    if (texts[2]) {
                        texts[2].style.color = 'black';
                    }
                }
                
                // Wait a bit to show the completed state before reloading
                setTimeout(() => {
                    // Hide overlay before reloading
                    const overlay = document.getElementById('scc-ai-wizard-global-overlay');
                    if (overlay) {
                        overlay.style.opacity = '0';
                        setTimeout(() => {
                            overlay.remove();
                            location.reload();
                        }, 300);
                    } else {
                        location.reload();
                    }
                }, 500);
            }
        })
        .catch((error) => {
            clearInterval(stateInterval);
            console.error('Error:', error);
            // Hide overlay on error
            const overlay = document.getElementById('scc-ai-wizard-global-overlay');
            if (overlay) {
                overlay.style.opacity = '0';
                setTimeout(() => {
                    overlay.remove();
                }, 300);
            }
        });
};

window.sccAiUtils = sccAiUtils;
// PREMIUM ONLY: Remove/comment this export for FREE version
//export default sccAiUtils;