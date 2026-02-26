// Initialize namespace if it doesn't exist
if (typeof window.SccModals === 'undefined') {
	window.SccModals = {};
}

// Template strings (inlined from HTML files)
const template = `    <div class="df-modal-overlay-mask">
        <div class="df-modal-container">
            <div class="df-modal-close-btn">
                <span data-node-type="modal-close-btn-icon" class="df-modal-close-btn-icon"></span>
            </div>
            <div class="df-modal-content">
                <div class="df-modal-header">
                    <div data-node-type="modal-info-icon" class="df-modal-info-icon">
                        <span class="df-modal-info-icon-symbol"></span>
                    </div>
                    <h2 data-node-type="modal-title" class="df-modal-title"></h2>
                </div>
                <div class="df-modal-body">
                    <div class="df-modal-content-wrapper">
                        <div data-node-type="modal-content" class="df-modal-content"></div>
                    </div>
                </div>
                <div class="df-modal-footer">
                    <button type="button" data-node-type="modal-negative-btn" class="df-btn df-btn-secondary">Close</button>
                    <button type="button" data-node-type="modal-positive-btn" class="df-btn df-btn-primary">Save changes</button>
                </div>
            </div>
        </div>
    </div>`;

const bannerTemplate = `    <div class="scc-template-banner">
        <div class="banner-content">
            <div class="banner-icon">
                <span class="scc-icn-wrapper">
                    <i class="material-icons">info</i>
                </span>
            </div>
            <div class="banner-message">
                <h4 data-node-type="banner-title" class="banner-title"></h4>
                <div class="scc-banner-line">
                    <div data-node-type="banner-message" class="banner-message-text"></div>
                    <div class="banner-actions" data-node-type="banner-actions">
                        <button type="button" data-node-type="banner-negative-btn" class="df-btn df-btn-secondary banner-action-btn">Close</button>
                        <button type="button" data-node-type="banner-positive-btn" class="df-btn df-btn-primary banner-action-btn">Save changes</button>
                    </div>
                </div>
            </div>
            <div class="banner-dismiss-menu" data-node-type="banner-dismiss-menu" style="display: none;">
                <div class="banner-dismiss-header">Remind me later</div>
                <button type="button" class="banner-dismiss-option" data-duration="1day">For 1 day</button>
                <button type="button" class="banner-dismiss-option" data-duration="1week">For 1 week</button>
                <button type="button" class="banner-dismiss-option" data-duration="forever">Don't remind me again</button>
            </div>
            <button type="button" data-node-type="banner-dismiss-btn" class="banner-dismiss-btn" title="Remind me later">
                <i class="material-icons">schedule</i>
            </button>
            <button type="button" data-node-type="banner-close" class="banner-close">
                <i class="material-icons">close</i>
            </button>
        </div>
    </div>`;

class StylishCostCalculatorModalView {
	constructor( modalConfig ) {
		this.modalConfig = modalConfig;
		this.template = this.getTemplate();
		this.bannerTemplate = this.getBannerTemplate();
		this.bannerMountNode = this.modalConfig.bannerMountNode || document.querySelector( '#notices_wrapper' );
	}

	init() {
		this.injectStyles();
		if ( ! this.modalConfig.bannerOnly ) {
			this.render();
		} else {
			this.sccMountBanner();
		}
	}

	async injectStyles() {
		// Create style element if it doesn't exist
		// await import( '../../scss/modals/_modal.scss' );
	}

	render() {
		// Create modal container
		this.modal = document.createElement( 'div' );
		this.modal.className = 'df-modal-root';
		this.modal.id = this.modalConfig.context;
		this.modal.innerHTML = this.template;
		const closeButton = this.modal.querySelector( '[data-node-type="modal-negative-btn"]' );
		const closeIcon = this.modal.querySelector( '[data-node-type="modal-close-btn-icon"]' );

		// Set custom title and content if provided
		if ( this.modalConfig.title ) {
			const titleElement = this.modal.querySelector( '[data-node-type="modal-title"]' );
			if ( titleElement ) {
				titleElement.innerHTML = this.modalConfig.title;
			}
		}

		if ( this.modalConfig.content ) {
			const contentElement = this.modal.querySelector( '[data-node-type="modal-content"]' );
			if ( contentElement ) {
				contentElement.innerHTML = this.modalConfig.content;
			}
		}

		// Set custom button text if provided
		if ( this.modalConfig.negativeButtonText ) {
			if ( closeButton ) {
				closeButton.addEventListener( 'click', () => {
					if ( this.modalConfig.negativeButtonCallback ) {
						this.modalConfig.negativeButtonCallback();
					}
					this.close();
				} );
				closeButton.textContent = this.modalConfig.negativeButtonText;
			}
		} else if ( closeButton ) {
			closeButton.style.display = 'none';
		}

		if ( closeIcon ) {
			closeIcon.addEventListener( 'click', () => {
				if ( this.modalConfig.negativeButtonCallback ) {
					this.modalConfig.negativeButtonCallback();
				}
				this.close();
			} );
		} else if ( closeIcon ) {
			closeIcon.style.display = 'none';
		}

		const infoIcon = this.modal.querySelector( '[data-node-type="modal-info-icon"]' );
		if ( infoIcon ) {
			infoIcon.innerHTML = window.SccModals.icons.dangerous;
		}

		if ( this.modalConfig.affirmativeButtonText ) {
			const saveButton = this.modal.querySelector( '[data-node-type="modal-positive-btn"]' );
			if ( saveButton ) {
				saveButton.addEventListener( 'click', () => {
					if ( this.modalConfig.affirmativeButtonCallback ) {
						this.modalConfig.affirmativeButtonCallback();
					}
					this.close();
				} );

				// Check if button text contains "remove" or "delete" to add trash icon
				const buttonText = this.modalConfig.affirmativeButtonText.toLowerCase();
				if ( buttonText.includes( 'remove' ) || buttonText.includes( 'delete' ) ) {
					saveButton.innerHTML = `${ window.SccModals.icons.trash } <span>${ this.modalConfig.affirmativeButtonText }</span>`;
				} else {
					saveButton.textContent = this.modalConfig.affirmativeButtonText;
				}
			}
		}

		// if modal with id exists, remove it
		if ( document.getElementById( this.modalConfig.id ) ) {
			document.getElementById( this.modalConfig.id ).remove();
		}

		// Mount banner if banner config is provided
		this.sccMountBanner();

		document.body.appendChild( this.modal );

		// Add active class to show modal
		requestAnimationFrame( () => {
			this.modal.classList.add( 'active' );
		} );
	}

	sccMountBanner() {
		if ( this.modalConfig.showBanner && this.bannerMountNode ) {
			const bannerId = `scc-banner-${ this.modalConfig.context }`;

			// Check if banner with same modal ID already exists
			const existingBanner = document.getElementById( bannerId );

			if ( existingBanner ) {
				// Update existing banner instead of creating new one
				this.banner = existingBanner;
				this.sccUpdateBannerContent();
			} else {
				// Create new banner container
				this.banner = document.createElement( 'div' );
				this.banner.className = 'scc-banner-root';
				this.banner.id = bannerId;
				this.banner.innerHTML = this.bannerTemplate;

				this.sccUpdateBannerContent();
				this.bannerMountNode.appendChild( this.banner );
				this.bannerMountNode.classList.remove( 'd-none' );
			}
		}
	}

	sccUpdateBannerContent() {
		if ( ! this.banner ) {
			return;
		}

		// Set banner content with fallbacks to modalConfig
		const bannerTitle = this.banner.querySelector( '[data-node-type="banner-title"]' );
		const bannerMessage = this.banner.querySelector( '[data-node-type="banner-message"]' );
		const bannerClose = this.banner.querySelector( '[data-node-type="banner-close"]' );
		const bannerActions = this.banner.querySelector( '[data-node-type="banner-actions"]' );
		const bannerNegativeBtn = this.banner.querySelector( '[data-node-type="banner-negative-btn"]' );
		const bannerPositiveBtn = this.banner.querySelector( '[data-node-type="banner-positive-btn"]' );

		if ( bannerTitle ) {
			// Use banner.title, fallback to modalConfig.title
			const titleText = this.modalConfig.banner.title || this.modalConfig.title;
			if ( titleText ) {
				bannerTitle.innerHTML = titleText;
			}
		}

		if ( bannerMessage ) {
			// Use banner.message, fallback to modalConfig.content
			const messageText = this.modalConfig.banner.message || this.modalConfig.content;
			if ( messageText ) {
				bannerMessage.innerHTML = messageText;
			}

			// Move button-like elements authored inside the message into the actions container
				// This keeps visual alignment consistent and out of the message flow
				if ( bannerActions ) {
					const actionSelector = '.df-btn, .btn, .scc-btn, .banner-action-btn';
					const candidates = Array.from( bannerMessage.querySelectorAll( actionSelector ) );
					if ( candidates.length ) {
						// Deduplicate: move only top-level candidates (not ones nested inside others)
						const topLevelCandidates = candidates.filter( ( el ) => ! candidates.some( ( other ) => other !== el && other.contains( el ) ) );
						topLevelCandidates.forEach( ( el ) => {
							bannerActions.appendChild( el );
						} );
					}
				}
		}

		// Handle action buttons visibility and functionality
		const hasActionButtons = this.modalConfig.affirmativeButtonText || this.modalConfig.negativeButtonText;
		const hasMovedButtons = !!( bannerActions && bannerActions.querySelector( '.df-btn, .btn, .scc-btn, .banner-action-btn' ) );
		const showActionButtons = this.modalConfig.banner?.showActionButtons !== false && ( hasActionButtons || hasMovedButtons );

		if ( bannerActions ) {
			if ( showActionButtons ) {
				bannerActions.style.display = 'flex';

				// Handle negative button
				if ( bannerNegativeBtn && this.modalConfig.negativeButtonText ) {
					// Clone node to remove all event listeners
					const newNegativeBtn = bannerNegativeBtn.cloneNode( true );
					bannerNegativeBtn.parentNode.replaceChild( newNegativeBtn, bannerNegativeBtn );

					newNegativeBtn.textContent = this.modalConfig.negativeButtonText;
					newNegativeBtn.addEventListener( 'click', () => {
						if ( this.modalConfig.negativeButtonCallback ) {
							this.modalConfig.negativeButtonCallback();
						}
						this.sccCloseBanner();
					} );
				} else if ( bannerNegativeBtn ) {
					bannerNegativeBtn.style.display = 'none';
				}

				// Handle positive button
				if ( bannerPositiveBtn && this.modalConfig.affirmativeButtonText ) {
					// Clone node to remove all event listeners
					const newPositiveBtn = bannerPositiveBtn.cloneNode( true );
					bannerPositiveBtn.parentNode.replaceChild( newPositiveBtn, bannerPositiveBtn );

					// Check if button text contains "remove" or "delete" to add trash icon
					const buttonText = this.modalConfig.affirmativeButtonText.toLowerCase();
					if ( buttonText.includes( 'remove' ) || buttonText.includes( 'delete' ) ) {
						newPositiveBtn.innerHTML = `${ window.SccModals.icons.trash } <span>${ this.modalConfig.affirmativeButtonText }</span>`;
					} else {
						newPositiveBtn.textContent = this.modalConfig.affirmativeButtonText;
					}

					newPositiveBtn.addEventListener( 'click', () => {
						if ( this.modalConfig.affirmativeButtonCallback ) {
							this.modalConfig.affirmativeButtonCallback();
						}
						this.sccCloseBanner();
					} );
				} else if ( bannerPositiveBtn ) {
					bannerPositiveBtn.style.display = 'none';
				}

				// Ensure original action buttons are placed last (to the right)
				const negEl = this.banner.querySelector( '[data-node-type="banner-negative-btn"]' );
				const posEl = this.banner.querySelector( '[data-node-type="banner-positive-btn"]' );
				[ negEl, posEl ].forEach( ( el ) => {
					if ( el && el.style.display !== 'none' ) {
						bannerActions.appendChild( el );
					}
				} );
			} else {
				bannerActions.style.display = 'none';
			}
		}

		// Handle dismiss button (Remind me later)
		const bannerDismissBtn = this.banner.querySelector( '[data-node-type="banner-dismiss-btn"]' );
		const bannerDismissMenu = this.banner.querySelector( '[data-node-type="banner-dismiss-menu"]' );

		if ( bannerDismissBtn && bannerDismissMenu ) {
			// Clone nodes to remove all event listeners
			const newDismissBtn = bannerDismissBtn.cloneNode( true );
			const newDismissMenu = bannerDismissMenu.cloneNode( true );
			bannerDismissBtn.parentNode.replaceChild( newDismissBtn, bannerDismissBtn );
			bannerDismissMenu.parentNode.replaceChild( newDismissMenu, bannerDismissMenu );

			// Toggle menu on button click
			newDismissBtn.addEventListener( 'click', ( e ) => {
				e.stopPropagation();

				// Close all other dismiss menus first and remove their open class
				const allBanners = document.querySelectorAll( '.scc-template-banner' );
				const allDismissMenus = document.querySelectorAll( '[data-node-type="banner-dismiss-menu"]' );
				allDismissMenus.forEach( ( menu ) => {
					if ( menu !== newDismissMenu ) {
						menu.style.display = 'none';
					}
				} );
				allBanners.forEach( ( banner ) => {
					banner.classList.remove( 'scc-banner-menu-open' );
				} );

				// Toggle current menu
				const isVisible = newDismissMenu.style.display !== 'none';
				if ( isVisible ) {
					newDismissMenu.style.display = 'none';
					this.banner.classList.remove( 'scc-banner-menu-open' );
				} else {
					newDismissMenu.style.display = 'block';
					// Add class to increase z-index of current banner when menu is open
					this.banner.classList.add( 'scc-banner-menu-open' );
				}
			} );

			// Handle dismiss option clicks
			const dismissOptions = newDismissMenu.querySelectorAll( '.banner-dismiss-option' );
			dismissOptions.forEach( ( option ) => {
				option.addEventListener( 'click', ( e ) => {
					e.stopPropagation();
					const duration = option.getAttribute( 'data-duration' );

					// Determine banner type and key
					let bannerType = 'general';
					if ( this.bannerMountNode ) {
						const mountNodeId = this.bannerMountNode.id || '';
						if ( mountNodeId === 'debug_messages_wrapper' || mountNodeId === 'cache_plugin_alert_wrapper' ) {
							bannerType = 'diag';
						}
					}

					const bannerKey = this.modalConfig.banner?.bannerKey || this.modalConfig.context;
					const bannerTemplateElement = this.banner ? this.banner.querySelector( '.scc-template-banner' ) : null;

					// Dismiss the banner using database
					if ( typeof window.handleBannerDismiss === 'function' ) {
						const bannerRoot = this.banner;

						// Hide menu and remove open class first
						newDismissMenu.style.display = 'none';
						if ( bannerRoot ) {
							bannerRoot.classList.remove( 'scc-banner-menu-open' );
						}

						// Dismiss the banner (this will remove the element and save to database)
						window.handleBannerDismiss( bannerKey, duration, bannerTemplateElement || bannerRoot, bannerType );

						// Wait for handleBannerDismiss to remove the element, then clean up root container if needed
						setTimeout( () => {
							if ( bannerTemplateElement && bannerRoot && ! bannerRoot.querySelector( '.scc-template-banner' ) ) {
								// Template element was removed, now remove the root container
								bannerRoot.remove();
							} else if ( ! bannerTemplateElement && bannerRoot && bannerRoot.parentNode ) {
								// For banners without template structure, handleBannerDismiss should have removed it
								// But if it still exists, remove it here as fallback
								bannerRoot.remove();
							}

							// Clean up
							this.banner = null;
						}, 100 );
					} else {
						// Fallback: just close the banner
						newDismissMenu.style.display = 'none';
						if ( this.banner ) {
							this.banner.classList.remove( 'scc-banner-menu-open' );
						}
						this.sccCloseBanner();
					}
				} );
			} );

			// Close menu when clicking outside
			document.addEventListener( 'click', ( e ) => {
				if ( ! newDismissBtn.contains( e.target ) && ! newDismissMenu.contains( e.target ) ) {
					newDismissMenu.style.display = 'none';
					// Check if banner still exists before accessing classList
					if ( this.banner && this.banner.classList ) {
						this.banner.classList.remove( 'scc-banner-menu-open' );
					}
				}
			} );
		}

		// Handle close button - remove existing listeners first
		if ( bannerClose ) {
			// Clone node to remove all event listeners
			const newBannerClose = bannerClose.cloneNode( true );
			bannerClose.parentNode.replaceChild( newBannerClose, bannerClose );

			if ( this.modalConfig.banner?.showClose === false ) {
				newBannerClose.style.display = 'none';
			} else {
				newBannerClose.addEventListener( 'click', () => {
					// Use banner.closeCallback, fallback to negativeButtonCallback
					const closeCallback = this.modalConfig.banner?.closeCallback || this.modalConfig.negativeButtonCallback;
					if ( closeCallback ) {
						// If callback returns false, prevent closing
						const result = closeCallback();
						if ( result === false ) {
							return;
						}
					}

					// Show confirmation modal for notices_wrapper banners (same as debug_messages_wrapper)
					const sccCreateModal = window.stylishCostCalculatorModal || ( typeof stylishCostCalculatorModal !== 'undefined' ? stylishCostCalculatorModal : null );

					if ( sccCreateModal ) {
						// Get banner key from context or banner config
						const bannerKey = this.modalConfig.banner?.bannerKey || this.modalConfig.context;

						// Determine banner type based on mount node
						let bannerType = 'general';
						if ( this.bannerMountNode ) {
							const mountNodeId = this.bannerMountNode.id || '';
							if ( mountNodeId === 'debug_messages_wrapper' || mountNodeId === 'cache_plugin_alert_wrapper' ) {
								bannerType = 'diag';
							}
						}

						// Get the banner template element for handleBannerDismiss
						// Note: handleBannerDismiss will remove the element passed to it
						// We need to pass the template element, but then also remove the root container
						const bannerTemplateElement = this.banner ? this.banner.querySelector( '.scc-template-banner' ) : null;

						// Create a custom version that works with the modal system
						sccCreateModal( {
							context: 'banner-close-warning',
							title: 'Are you sure?',
							content: 'These warnings are important and help identify potential issues with your calculator.<br><br><strong>We recommend using "Remind me later" instead</strong> to temporarily hide this message until the issue is fixed.',
							affirmativeButtonText: 'Close anyway',
							negativeButtonText: 'Cancel',
							affirmativeButtonCallback: () => {
								// User confirmed - dismiss banner forever (same as "Remind me later" -> "Forever")
								const bannerRoot = this.banner;
								const self = this;
								const context = this.modalConfig.context;

								// All banners now use database via handleBannerDismiss
								if ( bannerKey && typeof window.handleBannerDismiss === 'function' ) {
									// Use handleBannerDismiss which saves to database
									window.handleBannerDismiss( bannerKey, 'forever', bannerTemplateElement || bannerRoot, bannerType );

									// Wait for the banner to be removed, then remove the root container if needed
									setTimeout( () => {
										if ( bannerRoot && bannerRoot.parentNode ) {
											// Check if template element was removed (for banners with template structure)
											if ( bannerTemplateElement && ! bannerRoot.querySelector( '.scc-template-banner' ) ) {
												bannerRoot.remove();
											} else if ( ! bannerTemplateElement ) {
												// For banners without template structure, remove root directly
												bannerRoot.remove();
											}
										}
										self.banner = null;
									}, 100 );
								} else {
									// Fallback: just close the banner
									this.sccCloseBanner();
								}
							},
							negativeButtonCallback: () => {
								// User cancelled, do nothing - banner stays visible
							},
						} );
					} else {
						// Fallback: close directly without confirmation
						this.sccCloseBanner();
					}
				} );
			}
		}
	}

	sccCloseBanner() {
		if ( this.banner ) {
			this.banner.remove();
			this.banner = null;
			if ( typeof sccBackendStore !== 'undefined' ) {
				const closedBanner = sccBackendStore.closedBanners.find( ( q ) => q.id === this.modalConfig.context );
				if ( closedBanner ) {
					closedBanner.closed = true;
				} else {
					sccBackendStore.closedBanners.push( { id: this.modalConfig.context, closed: true } );
				}
			}
		}
	}

	getTemplate() {
		return template;
	}

	getBannerTemplate() {
		return bannerTemplate;
	}

	// Method to close modal
	close() {
		this.modal.classList.remove( 'active' );

		// Remove elements after transition
		setTimeout( () => {
			this.modal.remove();
		}, 300 ); // Match transition duration from SCSS
	}
}

// Export to namespace
window.SccModals.View = StylishCostCalculatorModalView;
