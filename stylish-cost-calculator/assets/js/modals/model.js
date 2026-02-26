// Initialize namespace if it doesn't exist
if (typeof window.SccModals === 'undefined') {
	window.SccModals = {};
}

/**
 * Modal model class that handles the data and configuration for the Stylish Cost Calculator modal.
 */
class StylishCostCalculatorModalModel {
	/**
	 * Creates a new modal model instance.
	 *
	 * @param {string}   context                   - The context in which the modal will be displayed
	 * @param {string}   title                     - The title text to be displayed in the modal header
	 * @param {string}   description               - The description text to be displayed in the modal body
	 * @param {boolean}  showBanner                - Whether to show the banner or not
	 * @param {string}   content                   - The main content to be displayed in the modal
	 * @param {string}   affirmativeButtonText     - The text to be displayed on the affirmative/confirm button
	 * @param {string}   negativeButtonText        - The text to be displayed on the negative/cancel button
	 * @param {Function} affirmativeButtonCallback - The callback function to be executed when the affirmative button is clicked
	 * @param {Function} negativeButtonCallback    - The callback function to be executed when the negative button is clicked
	 * @param {boolean}  bannerOnly                - Whether to show only the banner or not
	 */
	constructor( context,
		title,
		description,
		showBanner,
		content,
		affirmativeButtonText,
		negativeButtonText,
		affirmativeButtonCallback,
		negativeButtonCallback,
		bannerOnly ) {
		this.context = context;
		this.title = title;
		this.description = description;
		this.showBanner = showBanner;
		this.content = content;
		this.affirmativeButtonText = affirmativeButtonText;
		this.negativeButtonText = negativeButtonText;
		this.affirmativeButtonCallback = affirmativeButtonCallback;
		this.negativeButtonCallback = negativeButtonCallback;
		this.bannerMountNode = null;
		this.bannerOnly = bannerOnly;
	}

	/**
	 * Initializes the modal configuration object with all necessary properties.
	 * This method should be called after creating a new instance of the modal model.
	 */
	init() {  
		// create modal
		this.modalConfig = {
			title: this.title,
			description: this.description,
			context: this.context,
			content: this.content,
			showCancelButton: true,
			affirmativeButtonText: this.affirmativeButtonText,
			negativeButtonText: this.negativeButtonText,
			affirmativeButtonCallback: this.affirmativeButtonCallback,
			negativeButtonCallback: this.negativeButtonCallback,
			bannerMountNode: this.bannerMountNode,
			showBanner: this.showBanner,
			bannerOnly: this.bannerOnly,
			banner: {
				title: this.bannerTitle,
				message: this.bannerMessage,
				closeCallback: this.bannerCloseCallback,
			},
		};
		 
	}
}

// Export to namespace
window.SccModals.Model = StylishCostCalculatorModalModel;
