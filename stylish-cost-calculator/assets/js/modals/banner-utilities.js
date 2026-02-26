// Initialize namespace if it doesn't exist
if (typeof window.SccModals === 'undefined') {
	window.SccModals = {};
}

/**
 * Banner utilities for managing dismissed banners with database
 */

 /**
  * Get all dismissed banners from database (via global function)
  * @return {Object} Object with banner IDs as keys and dismiss info as values
  */
function sccGetDismissedBanners() {
     // Use global function that reads from database (stored in memory)
     if ( typeof window.wpRestGetDismissedBanners === 'function' ) {
         return window.wpRestGetDismissedBanners();
     }
     return {};
 }

 /**
  * Check if a banner is currently dismissed (from database)
  * @param {string} bannerId - The ID of the banner to check
  * @return {boolean} True if banner is dismissed and not expired
  */
 function sccIsBannerDismissed( bannerId ) {
     // Use global function that checks database
     if ( typeof window.wpRestIsBannerDismissed === 'function' ) {
         return window.wpRestIsBannerDismissed( bannerId );
     }
     return false;
 }

 /**
  * Dismiss a banner for a specific duration (saves to database)
  * @param {string} bannerId - The ID of the banner to dismiss (e.g., 'context' or 'diag-key')
  * @param {string} duration - Duration: '1day', '1week', or 'forever'
  */
 function sccDismissBanner( bannerId, duration ) {
     // Determine banner type and key from bannerId
     // Format: 'diag-key' or just 'key' for general banners
     let bannerKey = bannerId;
     let bannerType = 'general';

     if ( bannerId.startsWith( 'diag-' ) ) {
         bannerKey = bannerId.replace( 'diag-', '' );
         bannerType = 'diag';
     }

     // Use global handleBannerDismiss function which saves to database
     if ( typeof window.handleBannerDismiss === 'function' ) {
         // Note: bannerElement is null because we're just saving, not removing from DOM
         window.handleBannerDismiss( bannerKey, duration, null, bannerType );
     } else {
         console.warn( 'handleBannerDismiss function not available' );
     }
 }

 /**
  * Remove a banner from dismissed list
  * @param {string} bannerId - The ID of the banner to remove
  */
 function sccRemoveDismissedBanner( bannerId ) {
     const dismissedBanners = sccGetDismissedBanners();
     delete dismissedBanners[ bannerId ];

     try {
         localStorage.setItem( STORAGE_KEY, JSON.stringify( dismissedBanners ) );
     } catch ( error ) {
         console.error( 'Error removing dismissed banner:', error );
     }
 }

 /**
  * Clear all dismissed banners (useful for debugging)
  */
 function sccClearAllDismissedBanners() {
     try {
         localStorage.removeItem( STORAGE_KEY );
     } catch ( error ) {
         console.error( 'Error clearing dismissed banners:', error );
     }
 }

 // Export to namespace
 window.SccModals.bannerUtilities = {
 	sccGetDismissedBanners,
 	sccIsBannerDismissed,
 	sccDismissBanner,
 	sccRemoveDismissedBanner,
 	sccClearAllDismissedBanners
 };
