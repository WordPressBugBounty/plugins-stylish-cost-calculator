import toastTemplate from './template-toast.html';

export default class StylishCostCalculatorToast {
	constructor() {
		this.template = this.getToastTemplate();
		this.toast = null;
		this.toastContainer = null;
		this.autoHideTimeout = null;
	}

	init() {
		this.createToastContainer();
	}

	createToastContainer() {
		// Create toast container if it doesn't exist
		this.toastContainer = document.getElementById( 'scc-toast-container' );
		if ( ! this.toastContainer ) {
			this.toastContainer = document.createElement( 'div' );
			this.toastContainer.id = 'scc-toast-container';
			this.toastContainer.className = 'scc-toast-container';
			document.body.appendChild( this.toastContainer );
		}
	}

	show( toastConfig = {} ) {
		const defaultConfig = {
			title: '',
			message: '',
			type: 'info', // info, success, warning, error
			duration: 5000, // auto-hide after 5 seconds, 0 to disable
			showClose: true,
			showActions: false,
			negativeButtonText: 'Cancel',
			positiveButtonText: 'Confirm',
			negativeButtonCallback: null,
			positiveButtonCallback: null,
			closeCallback: null,
		};

		const config = { ...defaultConfig, ...toastConfig };

		// Create toast element
		this.toast = document.createElement( 'div' );
		this.toast.className = 'scc-toast-root';
		this.toast.innerHTML = this.template;

		// Set content
		this.sccUpdateToastContent( config );

		// Add to container
		this.toastContainer.appendChild( this.toast );

		// Add show animation
		requestAnimationFrame( () => {
			this.toast.classList.add( 'active' );
		} );

		// Auto-hide if duration is set
		if ( config.duration > 0 ) {
			this.autoHideTimeout = setTimeout( () => {
				this.hide();
			}, config.duration );
		}

		return this.toast;
	}

	sccUpdateToastContent( config ) {
		if ( ! this.toast ) {
			return;
		}

		// Set title and message
		const toastTitle = this.toast.querySelector( '[data-node-type="toast-title"]' );
		const toastMessage = this.toast.querySelector( '[data-node-type="toast-message-text"]' );
		const toastIcon = this.toast.querySelector( '[data-node-type="toast-icon"]' );
		const toastActions = this.toast.querySelector( '[data-node-type="toast-actions"]' );
		const toastNegativeBtn = this.toast.querySelector( '[data-node-type="toast-negative-btn"]' );
		const toastPositiveBtn = this.toast.querySelector( '[data-node-type="toast-positive-btn"]' );
		const toastClose = this.toast.querySelector( '[data-node-type="toast-close"]' );

		// Set title
		if ( toastTitle && config.title ) {
			toastTitle.textContent = config.title;
		}

		// Set message
		if ( toastMessage && config.message ) {
			toastMessage.innerHTML = config.message;
		}

		// Set icon based on type
		if ( toastIcon ) {
			const iconMap = {
				info: 'info',
				success: 'check_circle',
				warning: 'warning',
				error: 'error',
			};
			toastIcon.textContent = iconMap[ config.type ] || 'info';
		}

		// Add type class
		this.toast.classList.add( `toast-${ config.type }` );

		// Handle action buttons
		if ( toastActions ) {
			if ( config.showActions ) {
				toastActions.style.display = 'flex';

				// Handle negative button
				if ( toastNegativeBtn && config.negativeButtonText ) {
					// Clone node to remove all event listeners
					const newNegativeBtn = toastNegativeBtn.cloneNode( true );
					toastNegativeBtn.parentNode.replaceChild( newNegativeBtn, toastNegativeBtn );

					newNegativeBtn.textContent = config.negativeButtonText;
					newNegativeBtn.addEventListener( 'click', () => {
						if ( config.negativeButtonCallback ) {
							config.negativeButtonCallback();
						}
						this.hide();
					} );
				} else if ( toastNegativeBtn ) {
					toastNegativeBtn.style.display = 'none';
				}

				// Handle positive button
				if ( toastPositiveBtn && config.positiveButtonText ) {
					// Clone node to remove all event listeners
					const newPositiveBtn = toastPositiveBtn.cloneNode( true );
					toastPositiveBtn.parentNode.replaceChild( newPositiveBtn, toastPositiveBtn );

					newPositiveBtn.textContent = config.positiveButtonText;
					newPositiveBtn.addEventListener( 'click', () => {
						if ( config.positiveButtonCallback ) {
							config.positiveButtonCallback();
						}
						this.hide();
					} );
				} else if ( toastPositiveBtn ) {
					toastPositiveBtn.style.display = 'none';
				}
			} else {
				toastActions.style.display = 'none';
			}
		}

		// Handle close button
		if ( toastClose ) {
			// Clone node to remove all event listeners
			const newToastClose = toastClose.cloneNode( true );
			toastClose.parentNode.replaceChild( newToastClose, toastClose );

			if ( config.showClose === false ) {
				newToastClose.style.display = 'none';
			} else {
				newToastClose.addEventListener( 'click', () => {
					this.hide();
					if ( config.closeCallback ) {
						config.closeCallback();
					}
				} );
			}
		}
	}

	hide() {
		if ( this.toast ) {
			// Clear auto-hide timeout
			if ( this.autoHideTimeout ) {
				clearTimeout( this.autoHideTimeout );
				this.autoHideTimeout = null;
			}

			// Add hide animation
			this.toast.classList.remove( 'active' );

			// Remove after transition
			setTimeout( () => {
				if ( this.toast && this.toast.parentNode ) {
					this.toast.parentNode.removeChild( this.toast );
				}
				this.toast = null;
			}, 300 ); // Match transition duration from SCSS
		}
	}

	// Convenience methods for different toast types
	success( message, title = 'Success', options = {} ) {
		return this.show( {
			type: 'success',
			title,
			message,
			...options,
		} );
	}

	error( message, title = 'Error', options = {} ) {
		return this.show( {
			type: 'error',
			title,
			message,
			...options,
		} );
	}

	warning( message, title = 'Warning', options = {} ) {
		return this.show( {
			type: 'warning',
			title,
			message,
			...options,
		} );
	}

	info( message, title = 'Info', options = {} ) {
		return this.show( {
			type: 'info',
			title,
			message,
			...options,
		} );
	}

	getToastTemplate() {
		return toastTemplate;
	}
} 