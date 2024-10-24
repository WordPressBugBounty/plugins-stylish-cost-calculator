/************************************************
 * AI Features / AI functions
************************************************/

let sccAiWizardOptionListener = null;
let sccAiWizardThread = [];
const sccAiDataSchema = [];

const sccAiUtils = {
	aiWizardMenu: null,
	quizRetakeButton: null,
	optimizerStartSetupWizard: null,
	aiWizardStatus: null,
	alreadyLoaded: false,
	/*
	* AI Wizard Page States
	* ============================
	* scc-ai-wizard-suggest-element
	* scc-ai-wizard-setup-wizard
	* scc-ai-wizard-optimize-form
	* scc-ai-wizard-analytics-insights
	*/
	aiWizardInit: ( pageState = null ) => {
		if ( sccAiUtils.alreadyLoaded ) {
			return;
		}
		sccAiUtils.alreadyLoaded = true;
		const aiWizardButton = document.getElementById( 'scc-ai-wizard-button' );
		const aiWizardContainer = aiWizardButton?.closest( '.scc-ai-wizard-panel-container' );
		sccAiUtils.aiWizardMenu = aiWizardContainer?.querySelector( '.scc-ai-wizard-menu' );
		const aiWizardMenu = sccAiUtils.aiWizardMenu;
		const resetButton = document.getElementById( 'scc-ai-wizard-reset-btn' );
		const closeChatButton = document.getElementById( 'scc-ai-wizard-close-chat-btn' );
		const backToMenuButton = document.getElementById( 'scc-ai-wizard-back-btn' );
		const chatPanel = aiWizardMenu?.querySelector( '.scc-ai-wizard-chat' );
		sccAiUtils.quizRetakeButton = aiWizardMenu?.querySelector( '#scc-ai-wizard-retake' );
		const quizRetakeButton = sccAiUtils.quizRetakeButton;
		if ( typeof(sccBackendStore) !== 'undefined' && sccBackendStore.currentCalculatorSetupWizardData.__quizAnswersStore ) {
			if (quizRetakeButton) {
				quizRetakeButton.textContent = 'Redo Setup Wizard';
			}
		} else {
			if (quizRetakeButton) {
				quizRetakeButton.textContent = 'Start Setup Wizard';
			}
		}
		if ( typeof(sccBackendStore) !== 'undefined' && sccBackendStore.config.sections ) {
			sccAiUtils.updateMultiplierGUI(sccBackendStore.config.sections);
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
			'scc-ai-wizard-crisp-chat',
			'scc-ai-wizard-intelligent-qa',
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
			sccAiUtils.startAiWizardChat( menu, aiOptionType, title, customMessage );
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
	aiWizardRequest: ( optionType, regenerate = false ) => {
		const menu = document.querySelector( '.scc-ai-wizard-menu' );
		const inputs = menu.querySelector( '.scc-ai-assistant-inputs' );
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
		} else {
			userPrompt = prompt?.value;
			sccAiWizardThread.push( {
				role: 'user',
				content: userPrompt,
			} );
			sccAiUtils.insertUserMessage( sendButton, prompt );
		}

		const calculatorId = sccAiUtils.getCalcId();
		const storedData = sccAiUtils.getAiWizardResponse( calculatorId, optionType );

		if ( storedData && ! regenerate ) {
			const jsonData = storedData;

			if ( optionType === 'optimize-form' || optionType === 'setup-wizard' ) {
				sccAiUtils.insertAiChatMessage( sendButton, jsonData, optionType );
			} else {
				sccAiUtils.insertAiChatMessage( sendButton, jsonData, optionType );
				sccAiUtils.enableInputsAiWizard( sendButton );
			}
			loader.classList.add( 'scc-hidden' );
			loaderSetupWizard.classList.add( 'scc-hidden' );
		} else {
			// Ajax call

			const params = {
				action: 'scc_ai_wizard_request',
				nonce: pageEditCalculator.nonce,
				calculator_id: sccAiUtils.getCalcId(),
				prompt: userPrompt,
				type: optionType,
				thread: JSON.stringify( sccAiWizardThread ),
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
				} )
				.catch( ( error ) => {
					console.error( 'Error:', error );
				} );
		}
	},
	regenerateSetupWizardStepByStep: () => {
		const responseContainer = document.querySelector( '.scc-ai-wizard-setup-accordion-body' );
		const loader = responseContainer.querySelector( '.scc-setup-wizard-loader' );
		const oldResponse = responseContainer.querySelector( '.scc-ai-chat-bubble-wizard' );
		loader.classList.remove( 'scc-hidden' );
		if ( oldResponse ) {
			oldResponse.remove();
		}
	},
	regenerateFormOptimizerResponse: () => {
		const responseContainer = document.querySelector( '.scc-ai-wizard-form-optimizer' );
		const loader = responseContainer.closest( '.scc-ai-wizard-menu-body' ).querySelector( '.scc-ai-wizard-loader-container' );
		const oldResponse = responseContainer.querySelector( '.scc-ai-chat-bubble-wizard' );
		loader.classList.remove( 'scc-hidden' );
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
		sccAiWizardThread = [];
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
	},
	insertAISuggestionMessage: ( $this, text ) => {
		text = marked.parse( text );
		const message = `
                <div class="scc-ai-suggestion-message">
                    ${ text }
                </div>
        `;
		return message;
	},
	insertAiChatMessage( $this, aiResponse = null, optionType, customMessage = null ) {
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
					sccAiWizardThread.push( {
						role: 'assistant',
						content: aiResponseObject.ai_response.ai_raw_response,
					} );
				}
				aiMessageText = aiResponseObject.ai_response.ai_message;

				if ( ( aiMessageText && aiMessageText.includes( '[[show_actions]]' ) ) || ( aiMessageText && forceShowAiActions ) ) {
					aiMessageText = aiMessageText.replace( '[[show_actions]]', '' );
					aiHideActionsClass = '';
					aiHideAiAvatar = 'scc-hidden';
					aiSmallAvatarVisibility = '';
				}

				sccAiUtils.enableInputsAiWizard( aiChat );

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
			renderer.link = function(href, title, text) {
				const target = '_blank';
				const link = `<a href="${href}" title="${title || ''}" target="${target}">${text}</a>`;
				return link;
			};
			//aiMessageText = marked.parse( aiMessageText );
			aiMessageText = marked.parse(aiMessageText, { renderer });
			
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
						<a id="scc-ai-chat-regenerate-button" class="scc-ai-chat-action-button material-icons-outlined me-2" onclick="sccAiUtils.aiWizardRequest('${ requestType }', true);" title="Regenerate">refresh</a>
						<a id="scc-ai-chat-copy-button" class="scc-ai-chat-action-button material-icons-outlined" onclick="sccAiUtils.copyAiResponseToClipboard(this);" title="Copy">copy</a>
						<span class="scc-ai-copy-message-confirmation scc-hidden" >Copied!</span>
					</div>
					</div>
					<div class="mt-3"></div>
					<div class="scc-ai-chat-avatar-divider ${ aiHideAiAvatar }"></div>

                    <div class="${ aiMessageClasses }">
                        ${ aiMessageText }
                        ${ aiSuggestionText }
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
		return Number( calcId );
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
	forceCloseAiWizardPanel: () => {
		const panel = sccAiUtils.aiWizardMenu;
		if ( panel ) {
			panel.classList.add( 'scc-hidden' );
		}
	},
	checkAiCredits: async ( page ) => {
		let nonce = '';
		if ( page === 'edit-calculator-page' ) {
			nonce = pageEditCalculator.nonce;
		}
		if ( page === 'view-quotes-page' ) {
			nonce = pageViewQuotes.nonce;
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
			sectionDescription: section.description,
			elements: section.subsection.map( ( subsection ) =>
				subsection.element.map( ( element ) => ( {
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
			),
		} ) );
		// Add calculatorId and calculatorName at the beginning of extractedData
		const result = {
			calculatorId,
			calculatorName,
			sections,
		};
		return result;
	},
	updateCalculatorDataSchema: () => {
		//const schema = document.getElementById( 'scc-data-schema' );
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
				sccBackendStore.config.sections = sections;
				sccAiUtils.updateMultiplierGUI( data.schema );
			} )
			.catch( ( error ) => {
				console.error( 'Error:', error );
			} );
	},

	// this function is used to detect sliders, date or any multiplier element in the calculator data schema
	updateMultiplierGUI: ( schema ) => {
		if ( ! schema ) {
			return;
		}
		schema.forEach( ( section ) => {
			section.subsection.forEach( ( subsection ) => {
				let multiplier = false;
				const subsectionInput = document.querySelector( 'input.input_subsection_id[value="' + subsection.id + '"]' );
				const subsectionArea = subsectionInput?.closest( '.boardOption' )?.querySelector( '.subsection-area' );
				const elementCount = subsection.element ? subsection.element?.length : 0;
				subsection.element.forEach( ( element ) => {
					let elementIsMultiplier = false;
					let noCostElement = false;
					if ( element.type === 'comment box' || element.type === 'signature box' || element.type === 'file upload' || element.type === 'texthtml' ) {
						noCostElement = true;
					}
					if ( element.type === 'slider' ) {
						elementIsMultiplier = true;
						multiplier = true;
					} else if ( element.type === 'date' ) {
						if ( element.value6 && element.value1 === 'date_range' ) {
							const escapedJsonString = element.value6;
							const unescapedJsonString = JSON.parse( `"${ escapedJsonString }"` );
							const jsonObject = JSON.parse( unescapedJsonString );
							if ( jsonObject.date_range_pricing_structure &&
								( jsonObject.date_range_pricing_structure === 'quantity_mod' ||
								jsonObject.date_range_pricing_structure === 'quantity_modifier_and_unit_price' )
							) {
								elementIsMultiplier = true;
								multiplier = true;
							}
						}
					}
					const elementInput = subsectionArea.querySelector( 'input.input_id_element[value="' + element.id + '"]' );
					const elementContainer = elementInput?.closest( '.elements_added' );
					const elementLinkLine = elementContainer?.querySelector( '.scc-link-line' );

					// Add last element connector class
					if ( elementLinkLine && ! elementIsMultiplier ) {
						elementLinkLine.classList.remove( 'scc-invert-element-connector' );
						elementLinkLine.classList.remove( 'scc-last-element-connector' );
						if ( multiplier ) {
							elementLinkLine.classList.add( 'scc-invert-element-connector' );
						}
					}
					// Add line if the element does not have a link line and is not a multiplier
					if ( ! elementLinkLine && ! elementIsMultiplier ) {
						if ( elementContainer ) {
							const connectorExtraClass = multiplier ? 'scc-invert-element-connector' : '';
							const html = `<div class="scc-line-hider"></div><div class="scc-element-connector-line scc-link-line ${ connectorExtraClass }"></div>`;
							const htmlNoCost = `<div class="scc-line-hider"></div>`;
							if ( ! noCostElement ) {
								elementContainer.insertAdjacentHTML( 'afterbegin', html );
							} else {
								elementContainer.insertAdjacentHTML( 'afterbegin', htmlNoCost );
							}
						}
					}
					// Add Line if the element is a multiplier and does not have a link line
					if ( elementIsMultiplier && ! elementLinkLine ) {
						const editorContainer = document.querySelector( '.scc-pane-container' );
						const dataLinkIcon = editorContainer.getAttribute( 'data-link-icon' );
						if ( elementContainer ) {
							const html = `<div class="scc-line-hider"></div>
										<div class="scc-multiplier-connector-line scc-link-line scc-hidden">
											<div class="scc-multiplier-connector-link" data-setting-tooltip-type="element-multiplier-tt" data-bs-original-title title>
												<span class="scc-icn-wrapper"><img src="${ dataLinkIcon }"></span>
											</div>
										</div>`;
							elementContainer.insertAdjacentHTML( 'afterbegin', html );
							const tooltipNode = elementContainer.querySelector( '.scc-multiplier-connector-link' );
							applySettingTooltip( tooltipNode );
						}
					}
				} );

				// Out of subsection loop
				subsectionArea.querySelectorAll( '.scc-line-hider' ).forEach( ( line ) => {
					line.classList.remove( 'scc-first-element-subsection' );
					line.classList.remove( 'scc-last-element-subsection' );
				} );

				const elements = subsectionArea.querySelectorAll( '.elements_added' );

				if ( elements.length > 0 ) {
					const noCostElementTypes = [ 'comment box', 'signature box', 'file upload', 'texthtml' ];

					let firstCostElementIndex = 0;
					let lastCostElementIndex = elements.length - 1;
					const isNoCostElement = ( elementType ) => noCostElementTypes.includes( elementType );
					for ( let i = 0; i < elements.length; i++ ) {
						const elementType = elements[ i ].querySelector( '[data-element-setup-type]' )?.getAttribute( 'data-element-setup-type' );
						if ( ! isNoCostElement( elementType ) ) {
							firstCostElementIndex = i;
							break;
						}
					}

					for ( let i = elements.length - 1; i >= 0; i-- ) {
						const elementType = elements[ i ].querySelector( '[data-element-setup-type]' )?.getAttribute( 'data-element-setup-type' );
						if ( ! isNoCostElement( elementType ) ) {
							lastCostElementIndex = i;
							break;
						}
					}

					elements.forEach( ( element, index ) => {
						const lineHider = element.querySelector( '.scc-line-hider' );
						const elementType = element.querySelector( '[data-element-setup-type]' )?.getAttribute( 'data-element-setup-type' );

						if ( isNoCostElement( elementType ) ) {
							if ( index > firstCostElementIndex && index < lastCostElementIndex ) {
								element.querySelectorAll( '.scc-line-hider' ).forEach( ( hider ) => hider.remove() );
							} else {
								lineHider?.classList.add( 'scc-no-cost-element' );
							}
						} else {
							if ( index === firstCostElementIndex ) {
								lineHider?.classList.add( 'scc-first-element-subsection' );
							}
							if ( index === lastCostElementIndex ) {
								lineHider?.classList.add( 'scc-last-element-subsection' );
							}
						}

						if ( index < firstCostElementIndex ) {
							lineHider?.classList.add( 'scc-first-element-subsection-no-cost' );
						}
						if ( index > lastCostElementIndex ) {
							lineHider?.classList.add( 'scc-last-element-subsection-no-cost' );
						}
					} );
				}
				if ( multiplier && elementCount > 1 ) {
					subsectionArea.querySelectorAll( '.scc-link-line' ).forEach( ( line ) => {
						line.classList.remove( 'scc-hidden' );
					} );
				} else if ( ! multiplier || elementCount <= 1 ) {
					subsectionArea.querySelectorAll( '.scc-link-line' ).forEach( ( line ) => {
						line.classList.add( 'scc-hidden' );
					} );
				}
			} );
		} );
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
};

window.sccAiUtils = sccAiUtils;