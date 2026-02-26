// Initialize namespace if it doesn't exist
if (typeof window.SccModals === 'undefined') {
	window.SccModals = {};
}

/**
 * Controller class that manages the modal's model and view components.
 * This class coordinates the interaction between the modal's data and its presentation.
 */
class StylishCostCalculatorModalController {
	/**
	 * Creates a new modal controller instance.
	 *
	 * @param {Object}   options                           - The configuration options for the modal
	 * @param {string}   options.context                   - The context in which the modal will be displayed
	 * @param {string}   options.title                     - The title text to be displayed in the modal header
	 * @param {string}   options.description               - The description text to be displayed in the modal body
	 * @param {string}   options.content                   - The main content to be displayed in the modal
	 * @param {boolean}  options.showBanner                - Whether to show the banner or not
	 * @param {boolean}  options.bannerOnly                - Whether to show only the banner or not
	 * @param {string}   options.affirmativeButtonText     - The text to be displayed on the affirmative/confirm button
	 * @param {string}   options.negativeButtonText        - The text to be displayed on the negative/cancel button
	 * @param {Function} options.affirmativeButtonCallback - The callback function to be executed when the affirmative button is clicked
	 * @param {Function} options.negativeButtonCallback    - The callback function to be executed when the negative button is clicked
	 * @param {string}   options.bannerMountNode           - The node to mount the banner on
	 */
	constructor( { context, title, description, content, showBanner, bannerOnly, bannerMountNode, affirmativeButtonText, negativeButtonText, affirmativeButtonCallback, negativeButtonCallback } ) {
		this.model = new window.SccModals.Model(
			context,
			title,
			description,
			showBanner,
			content,
			affirmativeButtonText,
			negativeButtonText,
			affirmativeButtonCallback,
			negativeButtonCallback,
			bannerOnly,
		);
		this.state = new window.SccModals.State( this );
	}

	/**
	 * Initializes the modal by setting up the model and creating the view.
	 * This method should be called after creating a new instance of the controller.
	 */
	init() {
		this.model.init();
		this.view = new window.SccModals.View( this.model.modalConfig );
		this.view.init();
	}
}

// Export to namespace
window.SccModals.Controller = StylishCostCalculatorModalController;
