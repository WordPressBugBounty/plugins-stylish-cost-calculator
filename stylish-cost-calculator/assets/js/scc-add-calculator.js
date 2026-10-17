function sscUploadSccBackup() {
	// showLoadingChanges()
	const files = jQuery( '#restoration-file' )[ 0 ].files[ 0 ];
	if ( ! files ) {
		return;
	}
	const reader = new FileReader();
	reader.readAsText( files, 'UTF-8' );
	reader.onload = function( ee ) {
		const json = JSON.parse( ee.target.result );
		const o = ( 'scc_form' in json );
		const fdata = new FormData();
		fdata.append( 'file', files );
		fdata.append( 'action', 'sccRestoreBackup' );
		fdata.append( 'nonce', pageAddCalculator.nonce );
		jQuery.ajax( {
			type: 'POST',
			url: ajaxurl + '?pageType=add_new',
			data: fdata,
			contentType: false,
			processData: false,
			success( data ) {
				if ( data.passed ) {
					showSweet( true, 'SCC restored successfully.' );
					window.location = '/admin.php?page=scc_edit_items' + '&id_form=' + data.newCalcId;
				} else {
					showSweet( false, data.msj );
				}
			},
		} );
	};
}
/**
 * *Creates new calculator with name
 * @param name_calculator
 */
function scc_create_new_calculator() {
	const name = jQuery( '#new-calc-name' ).val();
	if ( ! name ) {
		document.querySelector( '#new-calc-creator-section .text-danger' ).classList.remove( 'd-none' );
		setTimeout( () => {
			document.querySelector( '#new-calc-creator-section .text-danger' ).classList.add( 'd-none' );
		}, 5000 );
		return;
	}
	showLoadingChanges();

	if ( name ) {
		jQuery.ajax( {
			url: wp.ajax.settings.url,
			data: {
				action: 'sccCalculatorOp',
				op: 'add',
				calculator_name: name,
				nonce: pageAddCalculator.nonce,
			},
			success( data ) {
				const response = JSON.parse( data );
				if ( response.passed == true ) {
					window.location.href = window.location.pathname + '?page=scc_edit_items&id_form=' + response.data + '&new';
				} else {
					showSweet( false, 'An error occured, please try again' );
				}
			},
		} );
	}
}
/**
 * Creates a calculator form with the data returned by the quiz,
 * the caluclator will have the features and the elements selected by the results
 * @param results
 * @param _quizAnswersStore
 */
function scc_create_new_calculator_by_quiz_results( results, _quizAnswersStore ) {
	showWizardQuizCreationLoadingAlert();
	const wizardData = { ...results, ...{ __quizAnswersStore: _quizAnswersStore }, ...suggestionsByStep };
	const errorMessage = 'An error occurred creating the calculator. Please try again.';
	jQuery.ajax( {
		url: wp.ajax.settings.url + '?nonce=' + pageAddCalculator.nonce + '&action=sccCalculatorOp' + '&op=load_by_params' + '&calculator_name=' + 'New Stylish Calculator',
		contentType: 'application/json',
		method: 'POST',
		data: JSON.stringify( wizardData ),
		success: async ( data ) => {
			let response;
			try {
				response = typeof data === 'string' ? JSON.parse( data ) : data;
			} catch ( error ) {
				showSweet( false, errorMessage );
				return;
			}
			if ( ! response || response.passed !== true || ! response.data ) {
				showSweet( false, errorMessage );
				return;
			}

			const { data: calcId, quizAnswersRecordId } = response;
			try {
				await updateWizardQuizStorageData( { ...wizardData, quizAnswersRecordId }, calcId );
			} catch ( error ) {
				console.warn( 'SCC: Could not save the setup wizard recommendations in this browser.' );
			}
			window.location.href = window.location.pathname + '?page=scc_edit_items&id_form=' + calcId + '&new';
		},
		error: () => {
			showSweet( false, errorMessage );
		},
	} );
}

function showWizardQuizCreationLoadingAlert() {
	const modalShown = document.querySelector( '.modal.show' );
	if ( modalShown ) {
		bootstrap.Modal.getInstance( modalShown )?.hide();
	}
	Swal.fire( {
		title: 'Creating your calculator',
		html: 'Please wait while we build your calculator based on your answers.',
		showConfirmButton: false,
		allowOutsideClick: false,
		allowEscapeKey: false,
		willOpen: () => {
			Swal.showLoading();
		},
	} );
}

/**
 * *Creates calculator with template
 */

function loadExample( element ) {
	const el = jQuery( element ).val();
	if ( el == 'null' ) {
		document.querySelector( '#template-loader .text-danger' ).classList.remove( 'd-none' );
		setTimeout( () => {
			document.querySelector( '#template-loader .text-danger' ).classList.add( 'd-none' );
		}, 5000 );
		return;
	}
	showLoadingChanges();
	jQuery.ajax( {
		url: ajaxurl,
		data: {
			action: 'sscLoadExample',
			el,
			nonce: pageAddCalculator.nonce,
		},
		success( data ) {
			if ( data.passed == true ) {
				window.location.href = window.location.pathname + '?page=scc_edit_items&id_form=' + data.data + '&new';
			} else {
				showSweet( false, 'An error occured, please try again' );
			}
		},
	} );
}

function sccGetOffset( el ) {
	const rect = el.getBoundingClientRect();
	return {
		left: rect.left + window.scrollX,
		top: rect.top + window.scrollY,
	};
}

document.querySelector( 'body' ).classList.remove( 'wp-core-ui' );

const welcomePageActionBtns = document.querySelectorAll( '[data-btn-action]' );
const welcomeSection = document.querySelector( '#welcome-section' );
const newCalcSection = document.querySelector( '#new-calc-creator-section' );
const templateSelectorSection = document.querySelector( '#template-selector-section' );
const restoreCalcSection = document.querySelector( '#restore-section' );
const templatePickerFragment = document.querySelector( '#template-loader-wrapper' );

const chooseATemplate = document.querySelector( '#choose-a-template' );
const chooseATemplateBtn = document.querySelectorAll( '[data-relative-field="choose-a-template"]' );
const newCalcName = document.querySelector( '#new-calc-name' );
const newCalcNameBtn = document.querySelector( '[data-relative-field="new-calc-name"]' );
const calcPreview = document.querySelector( '#calc-preview-wrapper' );
const calcPreviewImage = document.querySelector( '#calc-preview-wrapper img' );
const pageWrapper = document.querySelector( '#add-new-page-wrapper' );
const newCalcCreateBox = document.querySelector( '#new-calc-creator-section' );

calcPreviewImage?.addEventListener( 'click', ( evt ) => {
	chooseATemplateBtn.click();
} );

welcomePageActionBtns.forEach( ( btn ) => {
	btn.addEventListener( 'click', ( evt ) => {
		const actionType = btn.getAttribute( 'data-btn-action' );

		// Instead of eval, use a condition or switch statement
		if ( typeof window[ actionType ] === 'function' ) {
			window[ actionType ]();
		}
	} );
} );

function showNewCalcNameInput() {
	pageWrapper.classList.remove( 'd-none' );
	templateSelectorSection.classList.add( 'd-none' );
	welcomeSection.classList.add( 'd-none' );
	newCalcSection.classList.remove( 'd-none' );
}
function showTemplateSelector() {
	pageWrapper.classList.add( 'd-none' );
	welcomeSection.classList.add( 'd-none' );
	templateSelectorSection.classList.remove( 'd-none' );
}

function showRestorePrompt() {
	pageWrapper.classList.remove( 'd-none' );
	templateSelectorSection.classList.add( 'd-none' );
	welcomeSection.classList.add( 'd-none' );
	restoreCalcSection.classList.remove( 'd-none' );
}
function backToHome() {
	pageWrapper.classList.add( 'd-none' );
	welcomeSection.classList.remove( 'd-none' );
	//restoreCalcSection.classList.add( 'd-none' );
	newCalcSection.classList.add( 'd-none' );
	templateSelectorSection.classList.add( 'd-none' );
}

chooseATemplate?.addEventListener( 'change', ( evt ) => {
	const selectedValue = evt.target.value;
	if ( selectedValue !== 'null' ) {
		calcPreview.classList.remove( 'd-none' );
		pageWrapper.classList.remove( 'with-vh' );
		calcPreview.querySelector( 'img' ).setAttribute( 'src', previewImagesBaseUrl + '/' + chooseATemplate.querySelector( `[value="${ chooseATemplate.value }"]` ).getAttribute( 'data-preview-image' ) );
	} else {
		pageWrapper.classList.add( 'with-vh' );
		calcPreview.classList.add( 'd-none' );
	}
} );

newCalcName.addEventListener( 'keyup', ( evt ) => {
	const currentValue = evt.target.value;
} );

newCalcNameBtn.addEventListener( 'click', ( evt ) => {
	scc_create_new_calculator();
} );

chooseATemplateBtn?.forEach( ( btn ) => {
	btn.addEventListener( 'click', ( evt ) => {
		chooseATemplate.value = btn.getAttribute( 'data-template-id' );
		loadExample( chooseATemplate );
	} );
},
);

function showSweet( respuesta, message ) {
	if ( respuesta ) {
		Swal.fire( {
			toast: true,
			title: message,
			icon: 'success',
			showConfirmButton: false,
			timer: 3000,
			position: 'bottom-end',
		} );
	} else {
		Swal.fire( {
			toast: true,
			title: message,
			icon: 'error',
			showConfirmButton: false,
			timer: 3000,
			position: 'top-end',
			background: 'white',
		} );
	}
}
