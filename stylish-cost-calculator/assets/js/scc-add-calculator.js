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
	showWizardQuizResultEmailLoadingAlert( results.resultsEmailFormData?.optin );
	const wizardData = { ...results, ...{ __quizAnswersStore: _quizAnswersStore }, ...suggestionsByStep };
	jQuery.ajax( {
		url: wp.ajax.settings.url + '?nonce=' + pageAddCalculator.nonce + '&action=sccCalculatorOp' + '&op=load_by_params' + '&calculator_name=' + 'New Stylish Calculator',
		// set payload type to json
		contentType: 'application/json',
		method: 'POST',
		data: JSON.stringify( wizardData ),
		success: async ( data ) => {
			const response = JSON.parse( data );
			if ( results.resultsEmailFormData?.optin ) {
				if ( response.data ) {
					const { data: calcId, quizAnswersRecordId } = response;
					await updateWizardQuizStorageData( { ...wizardData, quizAnswersRecordId }, calcId );
					window.location.href = window.location.pathname + '?page=scc_edit_items&id_form=' + calcId + '&new';
				} else {
					showSweet( false, 'An error occurred, please try again' );
				}
			} else if ( response.pdfData ) {
				const { data: calcId, quizAnswersRecordId, pdfData } = response;
				await updateWizardQuizStorageData( { ...wizardData, quizAnswersRecordId }, calcId );
				//downloadPDF( pdfData, calcId );
				window.location.href = window.location.pathname + '?page=scc_edit_items&id_form=' + calcId + '&new';
			} else {
				showSweet( false, 'An error occurred generating the PDF' );
			}
		},
	} );
}

const downloadPDF = ( data, calcId, type = 'application/pdf' ) => {
	let blob = null;
	const filename = 'your-tailored-setup-guide-' + calcId + '-sylish-cost-calculator';
	blob = b64toBlob( data, type );
	const blobURL = URL.createObjectURL( blob );
	const tempLink = document.createElement( 'a' );
	tempLink.href = blobURL;
	tempLink.download = filename;
	tempLink.click();
};
const printPreview = ( data, type = 'application/pdf' ) => {
	let blob = null;
	const filename = 'your-tailored-setup-guide-sylish-cost-calculator';
	blob = b64toBlob( data, type );
	const blobURL = URL.createObjectURL( blob );
	const theWindow = window.open( blobURL );
	const theDoc = theWindow.document;
	const theScript = document.createElement( 'script' );
	function injectThis() {
		window.print();
	}
	theScript.innerHTML = `window.onload = ${ injectThis.toString() };`;
	theDoc.body.appendChild( theScript );
};
const b64toBlob = ( content, contentType ) => {
	contentType = contentType || '';
	const sliceSize = 512;
	// method which converts base64 to binary
	const byteCharacters = window.atob( content );
	const byteArrays = [];
	for ( let offset = 0; offset < byteCharacters.length; offset += sliceSize ) {
		const slice = byteCharacters.slice( offset, offset + sliceSize );
		const byteNumbers = new Array( slice.length );
		for ( let i = 0; i < slice.length; i++ ) {
			byteNumbers[ i ] = slice.charCodeAt( i );
		}
		const byteArray = new Uint8Array( byteNumbers );
		byteArrays.push( byteArray );
	}
	const blob = new Blob( byteArrays, {
		type: contentType,
	} ); // statement which creates the blob
	return blob;
};
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

function isElementInView( scrollableDiv, targetElement ) {
	// Get dimensions and position for the scrollable div
	const divTop = scrollableDiv.scrollTop;
	const divBottom = divTop + scrollableDiv.clientHeight;

	// Get dimensions and position for the target element
	const elemTop = targetElement.offsetTop;
	const elemBottom = elemTop + targetElement.clientHeight;

	// Check if the element is fully within the view of the scrollable div
	return elemTop >= divTop && elemBottom <= divBottom;
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

function handleEmailOptInForWQ( $this ) {
	const shownModalActionBtn = document.querySelector( '.modal.show .scc-setup-wizard-button' );
	if ( $this.checked ) {
		document.querySelector( '#wq_field_wrapper' ).classList.remove( 'd-none' );
		shownModalActionBtn.textContent = 'Send My Recommendations';
	} else {
		document.querySelector( '#wq_field_wrapper' ).classList.add( 'd-none' );
		shownModalActionBtn.textContent = 'Build My Calculator';
	}
	emailResultsFormScrollToView();
}

function emailResultsFormScrollToView( showBorder = false ) {
	const modalVisibleContent = document.querySelector( '.modal.show .modal-body' );
	const formEmailFields = document.querySelector( '#wq_field_wrapper input[type="email"]' );
	modalVisibleContent.scrollTo( 0, sccGetOffset( formEmailFields ).top - 100 );
	if ( showBorder ) {
		formEmailFields.classList.add( 'scc-wql-field-warnings' );
		setTimeout( () => {
			formEmailFields.classList.remove( 'scc-wql-field-warnings' );
		}, 2000 );
	}
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

function showWizardQuizResultEmailLoadingAlert( emailOptIn = false ) {
    const modalShown = document.querySelector( '.modal.show' );
    // Hide the modal if it is shown
    if ( modalShown ) {
        const modalInstance = bootstrap.Modal.getInstance( modalShown );
        modalInstance.hide();
    }
    Swal.fire( {
        title: 'Success',
        width: 600,
        padding: '3em',
        showConfirmButton: false,
        html: `<div style="color: #716add;">${ emailOptIn ? 'We sent your email instructions.' : '' } <b>Please wait</b> while we build your calculator form based on your answers.</div>`,
        willOpen: () => {
            Swal.showLoading();
        },
        backdrop: `
          left top no-repeat rgba(0, 0, 0, .85)
          no-repeat
        `,
    } );
}