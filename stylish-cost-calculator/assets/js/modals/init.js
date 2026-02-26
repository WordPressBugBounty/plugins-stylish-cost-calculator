// Initialize namespace if it doesn't exist
if (typeof window.SccModals === 'undefined') {
	window.SccModals = {};
}

/**
 * Creates and initializes a new modal instance using the StylishCostCalculatorModalController.
 *
 * @param {Object}   options                           - The configuration options for the modal
 * @param {string}   options.context                   - The context in which the modal will be displayed
 * @param {string}   options.title                     - The title text to be displayed in the modal header
 * @param {string}   options.description               - The description text to be displayed in the modal body
 * @param {string}   options.affirmativeButtonText     - The text to be displayed on the affirmative/confirm button
 * @param {string}   options.negativeButtonText        - The text to be displayed on the negative/cancel button
 * @param {Function} options.affirmativeButtonCallback - The callback function to be executed when the affirmative button is clicked
 * @param {Function} options.negativeButtonCallback    - The callback function to be executed when the negative button is clicked
 * @param {string}   options.content                   - The content text to be displayed in the modal body, html is allowed
 * @param {boolean}  options.showBanner                - Whether to show the banner or not
 * @param {boolean}  options.bannerOnly                - Whether to show only the banner or not
 * @return {StylishCostCalculatorModalController}   The initialized modal controller instance
 */
function createModal( { context, title, content, showBanner = false, bannerOnly = false, description, affirmativeButtonText, negativeButtonText, affirmativeButtonCallback, negativeButtonCallback, bannerMountNode } ) {
	// Determine bannerMountNode if not provided (default to notices_wrapper for banners)
	if ( ! bannerMountNode && showBanner ) {
		bannerMountNode = document.querySelector( '#notices_wrapper' );
	}

	// Determine banner type based on mount node
	let bannerType = 'general';
	if ( bannerMountNode ) {
		const mountNodeId = bannerMountNode.id || '';
		if ( mountNodeId === 'debug_messages_wrapper' || mountNodeId === 'cache_plugin_alert_wrapper' ) {
			bannerType = 'diag';
		}
	}

	// Construct bannerId in the format used by handleBannerDismiss: 'bannerType-bannerKey'
	// This must match the format used when dismissing: ${bannerType}-${bannerKey}
	const bannerId = `${ bannerType }-${ context }`;

	// Check if banner is dismissed in database
	// Use window.sccIsBannerDismissed directly to ensure we're using the global function
	const isDismissed = typeof window.sccIsBannerDismissed === 'function'
		? window.sccIsBannerDismissed( bannerId )
		: false;

	if ( isDismissed ) {
		// Debug: log when banner is dismissed
		if ( typeof console !== 'undefined' && console.debug ) {
			console.debug( `Banner ${ bannerId } is dismissed, skipping creation.` );
		}
		return;
	}

	if ( typeof sccBackendStore !== 'undefined' ) {
		const bannerNode = document.querySelector( `#scc-banner-${context}` );

		const closedBanner = sccBackendStore.closedBanners.find( ( q ) => q.id === context );
		if ( closedBanner || bannerNode ) { 
			return;
		}
	}
    const modal = new window.SccModals.Controller( {
		context,
		title,
		description,
		showBanner,
		bannerOnly,
		bannerMountNode,
		affirmativeButtonText,
		negativeButtonText,
		affirmativeButtonCallback,
		negativeButtonCallback,
		content,
	} );
	modal.init();
	return modal;
}

// Export to global scope so it can be called from non-module code
window.stylishCostCalculatorModal = createModal;

// Also export to namespace for consistency
window.SccModals.createModal = createModal;
