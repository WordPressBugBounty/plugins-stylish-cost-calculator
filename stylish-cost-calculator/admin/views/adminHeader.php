<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}
$isSCCFreeVersion = defined( 'STYLISH_COST_CALCULATOR_VERSION' );
$couponFeatureUnlocked = (int) get_option( 'df_scc_licensed', 0 ) === 1;
$scc_icons        = require SCC_DIR . '/assets/scc_icons/icon_rsrc.php';
$scc_screen       = get_current_screen();
$current_user         = wp_get_current_user();
$scc_profile_name     = '';
$scc_profile_initials = '';

if ( $current_user instanceof WP_User ) {
	$scc_profile_name = ! empty( $current_user->display_name ) ? $current_user->display_name : $current_user->user_login;
}

if ( ! empty( $scc_profile_name ) ) {
	$scc_profile_parts = preg_split( '/[\s\-_]+/', trim( $scc_profile_name ) );

	if ( is_array( $scc_profile_parts ) ) {
		foreach ( $scc_profile_parts as $scc_profile_part ) {
			if ( '' === $scc_profile_part ) {
				continue;
			}

			$scc_profile_initials .= strtoupper( substr( $scc_profile_part, 0, 1 ) );

			if ( strlen( $scc_profile_initials ) >= 2 ) {
				break;
			}
		}
	}

	if ( '' === $scc_profile_initials ) {
		$scc_profile_initials = strtoupper( substr( preg_replace( '/[^A-Za-z0-9]/', '', $scc_profile_name ), 0, 2 ) );
	}
}

if ( '' === $scc_profile_initials ) {
	$scc_profile_initials = 'AD';
}
?>
<style>
	.scc-smiling-loader * {
		border: 0;
		box-sizing: border-box;
		margin: 0;
		padding: 0;
	}
	.scc-background-transparent{
		background-color: transparent;
	}
	.scc-background-primary{
		background-color: #314af3;
	}
	.scc-background-primary-dark{
		background-color: #15216C;
	}
	.scc-main-header-container{
		border-radius: 10px;
		filter: drop-shadow(0px 0px 10px rgba(0, 0, 0, 0.11));
		display: inline-flex;
		z-index:2;
		width: 100%;
		padding-right: 0 !important;
    	padding-left: 0 !important;
		margin-right: auto !important;
    	margin-left: auto !important;
		align-items: center !important;
	}
	.scc-header-logo-col{
		border-top-left-radius: 9px;
		border-bottom-left-radius: 9px;
		display: inline-flex;
		position: relative;
		height: 110px;
		width:40%;
	}
	.scc-header-ellipse{
		position: absolute;
		height: 100%;
		top: 0;
		right: -45px;
	}
	.scc-header-menu-col{
		display: inline-flex;
    	flex-direction: row;
    	flex: auto;
    	justify-content: space-between;
		height: 110px;
	}
	.scc-page-title{
		font-size: 28px;
    font-weight: 700;
		line-height: 1.3em;
	}
	.scc-page-desc{
		font-size: 20px;
		font-weight: 400;
	
	}
	.scc-page-name-and-desc{
		padding-left: 30px !important;
		color: white;
		display: flex;
		align-items: center;
	}
	.scc-header-menu-col .scc-top-nav-container .scc-icn-wrapper svg {
    	background-color: transparent;
	}

	.scc-top-nav-container .scc-menu-dropdown-content .scc-icn-wrapper svg{
		width: 18px;
		height: 18px;
	}
	.scc-custom-version-info{
		display: flex;
		align-items: center;
		z-index: 1;
	}
	.dropdown-toggle.scc-minimal-header-icon::after{
		display: none;
	}
	.scc-header-logo-white{
		background-color: white;
		border-radius: 8px;
		padding: 5px;
	}
	.scc-header-logo-white img{
		object-fit: contain;
	}
	.scc-smiling-loader:root {
		--hue: 223;
		--bg: hsl(var(--hue),90%,90%);
		--fg: hsl(var(--hue),90%,10%);
		--trans-dur: 0.3s;
		font-size: calc(16px + (20 - 16) * (100vw - 320px) / (1280 - 320));
	}
	#scc-editing-area-smiling-loading .smiley {
		position: absolute;
		height: auto;
		top: calc(50%);
		left: calc(50%);
		padding: 10px;
	}
	.scc-smiling-loader .smiley__eye1,
	.scc-smiling-loader .smiley__eye2,
	.scc-smiling-loader .smiley__mouth1,
	.scc-smiling-loader .smiley__mouth2 {
		animation: eye1 3s ease-in-out infinite;
	}
	.scc-smiling-loader .smiley__eye1,
	.scc-smiling-loader .smiley__eye2 {
		transform-origin: 64px 64px;
	}
	.scc-smiling-loader .smiley__eye2 {
		animation-name: eye2;
	}
	.scc-smiling-loader .smiley__mouth1 {
		animation-name: mouth1;
	}
	.scc-smiling-loader .smiley__mouth2 {
		animation-name: mouth2;
		visibility: hidden;
	}

	/* Animations */
	@keyframes eye1 {
		from {
			transform: rotate(-260deg) translate(0,-56px);
		}
		50%,
		60% {
			animation-timing-function: cubic-bezier(0.17,0,0.58,1);
			transform: rotate(-40deg) translate(0,-56px) scale(1);
		}
		to {
			transform: rotate(225deg) translate(0,-56px) scale(0.35);
		}
	}
	@keyframes eye2 {
		from {
			transform: rotate(-260deg) translate(0,-56px);
		}
		50% {
			transform: rotate(40deg) translate(0,-56px) rotate(-40deg) scale(1);
		}
		52.5% {
			transform: rotate(40deg) translate(0,-56px) rotate(-40deg) scale(1,0);
		}
		55%,
		70% {
			animation-timing-function: cubic-bezier(0,0,0.28,1);
			transform: rotate(40deg) translate(0,-56px) rotate(-40deg) scale(1);
		}
		to {
			transform: rotate(150deg) translate(0,-56px) scale(0.4);
		}
	}
	@keyframes eyeBlink {
		from,
		25%,
		75%,
		to {
			transform: scaleY(1);
		}
		50% {
			transform: scaleY(0);
		}
	}
	@keyframes mouth1 {
		from {
			animation-timing-function: ease-in;
			stroke-dasharray: 0 351.86;
			stroke-dashoffset: 0;
		}
		25% {
			animation-timing-function: ease-out;
			stroke-dasharray: 175.93 351.86;
			stroke-dashoffset: 0;
		}
		50% {
			animation-timing-function: steps(1,start);
			stroke-dasharray: 175.93 351.86;
			stroke-dashoffset: -175.93;
			visibility: visible;
		}
		75%,
		to {
			visibility: hidden;
		}
	}
	@keyframes mouth2 {
		from {
			animation-timing-function: steps(1,end);
			visibility: hidden;
		}
		50% {
			animation-timing-function: ease-in-out;
			visibility: visible;
			stroke-dashoffset: 0;
		}
		to {
			stroke-dashoffset: -351.86;
		}
	}

	div#scc-editing-area-smiling-loading {
        height: 100%;
        width: 0;
        position: fixed;
        z-index: 10;
        top: 0;
        left: 0;
        cursor: wait;
        background-color: rgba(27, 24, 24, 0.92);
        overflow-x: hidden;
        transition: 0.5s;
	}
	#scc-editing-area-loading {
		height: 100%;
		width: 0;
		position: fixed;
		z-index: 1;
		top: 0;
		left: 0;
		cursor: wait;
		background-color: rgba(27, 24, 24, 0.92);
		overflow-x: hidden;
		transition: 0.5s;
	}

	#scc-editing-area-loading .center {
		position: absolute;
		height: auto;
		width: 50%;
		top: calc(50% - 20%);
		left: calc(50% - 20%);
		padding: 10px;
	}

	#scc-editing-area-loading .sk-chase {
		width: 40px;
		height: 40px;
		left: calc(65% - 20%);
		position: relative;
		animation: scc-sk-chase 2.5s infinite linear both;
	}

	#scc-editing-area-loading .sk-chase-dot {
		width: 100%;
		height: 100%;
		position: absolute;
		left: 0;
		top: 0;
		animation: scc-sk-chase-dot 2.0s infinite ease-in-out both;
	}

	#scc-editing-area-loading .sk-chase-dot:before {
		content: '';
		display: block;
		width: 25%;
		height: 25%;
		background-color: #fff;
		border-radius: 100%;
		animation: scc-sk-chase-dot-before 2.0s infinite ease-in-out both;
	}

	#scc-editing-area-loading .sk-chase-dot:nth-child(1) {
		animation-delay: -1.1s;
	}

	#scc-editing-area-loading .sk-chase-dot:nth-child(2) {
		animation-delay: -1.0s;
	}

	#scc-editing-area-loading .sk-chase-dot:nth-child(3) {
		animation-delay: -0.9s;
	}

	#scc-editing-area-loading .sk-chase-dot:nth-child(4) {
		animation-delay: -0.8s;
	}

	#scc-editing-area-loading .sk-chase-dot:nth-child(5) {
		animation-delay: -0.7s;
	}

	#scc-editing-area-loading .sk-chase-dot:nth-child(6) {
		animation-delay: -0.6s;
	}

	#scc-editing-area-loading .sk-chase-dot:nth-child(1):before {
		animation-delay: -1.1s;
	}

	#scc-editing-area-loading .sk-chase-dot:nth-child(2):before {
		animation-delay: -1.0s;
	}

	#scc-editing-area-loading .sk-chase-dot:nth-child(3):before {
		animation-delay: -0.9s;
	}

	#scc-editing-area-loading .sk-chase-dot:nth-child(4):before {
		animation-delay: -0.8s;
	}

	#scc-editing-area-loading .sk-chase-dot:nth-child(5):before {
		animation-delay: -0.7s;
	}

	#scc-editing-area-loading .sk-chase-dot:nth-child(6):before {
		animation-delay: -0.6s;
	}
	.scc-icn-wrapper svg{
		height: 18px;
		width: 18px;
	}

  .scc-calculator-name-container{
    display: flex !important;
    align-items: center;
  }
  .scc-calculator-name-container svg{
    width: 20px;
    height: 20px;
    color: white;
  }

  .scc-icn-wrapper svg{
	height: 18px;
	width: 18px;
  }

	@keyframes scc-sk-chase {
		100% {
			transform: rotate(360deg);
		}
	}

	@keyframes scc-sk-chase-dot {

		80%,
		100% {
			transform: rotate(360deg);
		}
	}

	@keyframes scc-sk-chase-dot-before {
		50% {
			transform: scale(0.4);
		}

		100%,
		0% {
			transform: scale(1.0);
		}
	}

	:root {
		--scc-primary-blue: #0E1726; /* Dark navy blue from screenshot */
		--scc-secondary-bg: #FFFFFF;
		--scc-text-light: #FFFFFF;
		--scc-text-muted: #94A3B8;
		--scc-border-color: #E2E8F0;
	}
	
	.scc-primary-navbar {
		background-color: var(--scc-primary-blue);
		color: var(--scc-text-light);
		display: flex;
		align-items: center;
		justify-content: space-between;
		padding: 0 24px;
		height: 60px;
		width: 100%;
		
	}
	
	.scc-primary-nav-left{
		gap: 30px;
	}

	.scc-primary-nav-left, .scc-primary-nav-right {
		display: flex;
		align-items: center;
		height: 100%;
	}
	
	.scc-primary-nav-links {
		display: flex;
		list-style: none;
		margin: 0;
		padding: 0;
		height: 100%;
		align-items: center;
		gap: 20px;
	}
	
	.scc-primary-nav-links li {
		height: 100%;
		display: flex;
		align-items: center;
		margin: 0;
	}
	
	.scc-primary-nav-links .scc-nav-link {
		position: relative;
		color: #FFFFFF;
		text-decoration: none;
		display: flex;
		align-items: center;
		padding: 0 16px;
		height: 100%;
		font-weight: 500;
		font-size: 14px;
		gap: 8px;
		transition: color 0.2s ease, background-color 0.2s ease, transform 0.2s ease, opacity 0.2s ease;
	}

	.scc-primary-nav-links .scc-nav-link::after {
		content: "";
		position: absolute;
		left: 16px;
		right: 16px;
		bottom: 0;
		height: 3px;
		border-radius: 999px;
		background: var(--scc-color-primary, #314AF3);
		box-shadow: 0 0 12px rgba(49, 74, 243, 0.45);
		opacity: 0;
		transform: scaleX(0.7);
		transform-origin: center;
		transition: opacity 0.2s ease, transform 0.2s ease;
	}
	
	.scc-primary-nav-links .scc-nav-link:hover,
	.scc-primary-nav-links .scc-nav-link.active {
		color: #FFFFFF;
		background-color: rgba(255, 255, 255, 0.03);
		opacity: 0.96;
		transform: translateY(-0.5px);
	}

	.scc-primary-nav-links .scc-nav-link:hover::after,
	.scc-primary-nav-links .scc-nav-link.active::after {
		opacity: 1;
		transform: scaleX(1);
	}

	.scc-primary-nav-links .scc-nav-link.scc-nav-link-disabled {
		color: #FFFFFF;
		opacity: 0.7;
		cursor: not-allowed;
	}

	.scc-primary-nav-links .scc-nav-link.scc-nav-link-disabled::after {
		display: none;
	}

	.scc-primary-nav-links .scc-nav-link.scc-nav-link-disabled:hover,
	.scc-primary-nav-links .scc-nav-link.scc-nav-link-disabled:focus {
		color: #FFFFFF;
		opacity: 0.7;
	}
	
	.scc-primary-nav-links .scc-icn-wrapper svg {
		width: 18px;
		height: 18px;
		opacity: 0.8;
	}
	
		/* + NEW Badge */
	.scc-new-btn-badge {
		display: inline-flex;
		align-items: center;
		justify-content: center;
		gap: 6px;
		min-height: 32px;
		padding: 0 12px;
		background: rgba(49, 74, 243, 0.14);
		background: color-mix(in srgb, var(--scc-color-primary, #314AF3) 16%, transparent);
		border: 1px solid rgba(122, 131, 245, 0.45);
		border: 1px solid color-mix(in srgb, var(--scc-color-primary, #314AF3) 42%, white 18%);
		box-shadow: inset 0 1px 0 rgba(255, 255, 255, 0.08);
		color: #FFF !important;
		font-size: 11px;
		font-weight: 600;
		letter-spacing: 0.06em;
		border-radius: 8px;
		margin-left: 5px;
		line-height: 1;
		text-transform: uppercase;
		transition: background-color 0.2s ease, border-color 0.2s ease, color 0.2s ease, transform 0.2s ease, opacity 0.2s ease;
	}
	.scc-new-btn-badge:hover {
		background: rgba(49, 74, 243, 0.18);
		background: color-mix(in srgb, var(--scc-color-primary, #314AF3) 18%, transparent);
		border-color: rgba(122, 131, 245, 0.5);
		border-color: color-mix(in srgb, var(--scc-color-primary, #314AF3) 48%, white 18%);
		color: #FFF !important;
		opacity: 0.96;
		transform: translateY(-0.5px);
	}
	.scc-new-btn-badge svg {
		width: 14px;
		height: 14px;
	}

	.scc-profile-nav-item {
		display: flex;
		align-items: center;
	}

	.scc-profile-button {
		display: inline-flex;
		align-items: center;
		justify-content: center;
		width: 36px;
		height: 36px;
		padding: 0;
		border-radius: 999px;
		border: 1px solid #273ccd;
		border: 1px solid color-mix(in srgb, var(--scc-color-primary, #314AF3) 88%, black 12%);
		background: var(--scc-color-primary, #314AF3);
		box-shadow: 0 8px 20px rgba(49, 74, 243, 0.22);
		color: #FFFFFF;
		font-size: 12px;
		font-weight: 700;
		letter-spacing: 0.02em;
		line-height: 1;
		text-align: center;
		text-decoration: none;
		transition: transform 0.2s ease, box-shadow 0.2s ease;
	}

	.scc-profile-button:hover,
	.scc-profile-button:focus {
		color: #FFFFFF;
		transform: translateY(-1px);
		background: var(--scc-color-primary-dark, var(--scc-color-primary, #314AF3));
		box-shadow: 0 10px 24px rgba(49, 74, 243, 0.28);
	}
	
	/* Secondary Navbar */
	.scc-secondary-navbar {
		background-color: var(--scc-secondary-bg);
		border-bottom: 1px solid var(--scc-border-color);
		display: flex;
		align-items: center;
		justify-content: space-between;
		padding: 0 24px;
		height: 64px;
		width: 100%;
		border-radius: 0 0 8px 8px;
	}
	
	.scc-secondary-nav-left, .scc-secondary-nav-right {
		display: flex;
		align-items: center;
		height: 100%;
		gap: 12px;
	}
	
	.scc-secondary-nav-left a {
		text-decoration: none;
		color: #64748B;
		font-size: 14px;
		font-weight: 500;
		display: flex;
		align-items: center;
		gap: 6px;
	}
	
	.scc-secondary-nav-left a:hover {
		color: #0F172A;
	}
	
	.scc-breadcrumb-separator {
		color: #CBD5E1;
		font-size: 14px;
	}
	
	.scc-calc-name-input-wrapper {
		display: flex;
		align-items: center;
		gap: 8px;
	}

	.scc-calc-name-edit-icon {
		display: inline-flex;
		align-items: center;
		color: #64748B;
	}

	.scc-calc-name-edit-icon svg {
		width: 16px;
		height: 16px;
	}
	
	.scc-calc-name-input-wrapper input[type="text"] {
		border: none;
		background: transparent;
		font-size: 16px;
		font-weight: 600;
		color: #0F172A;
		padding: 0;
		box-shadow: none;
		width: 350px; /* Wide input */
	}
	
	.scc-calc-name-input-wrapper input[type="text"]:focus {
		outline: none;
		border-bottom: 2px solid #2563EB;
		border-radius: 0;
	}
	
	.scc-draft-badge {
		background: #F1F5F9;
		color: #64748B;
		font-size: 11px;
		font-weight: 600;
		padding: 4px 10px;
		border-radius: 4px;
		letter-spacing: 0.5px;
		border: 1px solid #E2E8F0;
	}
	
	/* Action Buttons */
	.scc-secondary-nav-right .btn-action {
		height: 35px;
		line-height: normal;
		background: #FFFFFF;
		border: 1px solid var(--scc-border-color);
		color: #475569;
		font-size: 14px;
		font-weight: 500;
		padding: 0 16px;
		border-radius: 6px;
		display: flex;
		align-items: center;
		gap: 6px;
		transition: all 0.2s;
	}
	
	.scc-secondary-nav-right .btn-action:hover {
		background: #F8FAFC;
		color: #0F172A;
	}
	
	.scc-secondary-nav-right .btn-action.scc-btn-green {
		background: var(--scc-color-green);
		border-color: var(--scc-color-green);
		color: #FFFFFF;
	}
	
	.scc-secondary-nav-right .btn-action.scc-btn-green:hover {
		background: #059669;
		border-color: #059669;
	}


</style>
<div id="scc-editing-area-smiling-loading" class="scc-smiling-loader" style="width:100%">
	<svg role="img" aria-label="Mouth and eyes come from 9:00 and rotate clockwise into position, right eye blinks, then all parts rotate and merge into 3:00" class="smiley" viewBox="0 0 128 128" width="128px" height="128px">
		<defs>
			<clipPath id="smiley-eyes">
				<circle class="smiley__eye1" cx="64" cy="64" r="8" transform="rotate(-40,64,64) translate(0,-56)" />
				<circle class="smiley__eye2" cx="64" cy="64" r="8" transform="rotate(40,64,64) translate(0,-56)" />
			</clipPath>
			<linearGradient id="smiley-grad" x1="0" y1="0" x2="0" y2="1">
				<stop offset="0%" stop-color="#000" />
				<stop offset="100%" stop-color="#fff" />
			</linearGradient>
			<mask id="smiley-mask">
				<rect x="0" y="0" width="128" height="128" fill="url(#smiley-grad)" />
			</mask>
		</defs>
		<g stroke-linecap="round" stroke-width="12" stroke-dasharray="175.93 351.86">
			<g>
				<rect fill="hsl(193,90%,50%)" width="128" height="64" clip-path="url(#smiley-eyes)" />
				<g fill="none" stroke="hsl(193,90%,50%)">
					<circle class="smiley__mouth1" cx="64" cy="64" r="56" transform="rotate(180,64,64)" />
					<circle class="smiley__mouth2" cx="64" cy="64" r="56" transform="rotate(0,64,64)" />
				</g>
			</g>
			<g mask="url(#smiley-mask)">
				<rect fill="hsl(223,90%,50%)" width="128" height="64" clip-path="url(#smiley-eyes)" />
				<g fill="none" stroke="hsl(223,90%,50%)">
					<circle class="smiley__mouth1" cx="64" cy="64" r="56" transform="rotate(180,64,64)" />
					<circle class="smiley__mouth2" cx="64" cy="64" r="56" transform="rotate(0,64,64)" />
				</g>
			</g>
		</g>
	</svg>
</div>
<script>
	jQuery(document).ready(function () {
		jQuery('#scc-editing-area-smiling-loading').remove()
		
		// Keep the WordPress menu open on SCC admin pages.
		sccForceWordPressMenuOpen();

		// Set initial icon based on menu state with a small delay to ensure DOM is ready
		setTimeout(function() {
			sccUpdateMenuToggleIcon();
		}, 100);

		if ( window.jQuery ) {
			jQuery( document ).on( 'wp-collapse-menu', function() {
				sccForceWordPressMenuOpen();
				sccUpdateMenuToggleIcon();
			} );
		}

		if ( typeof MutationObserver !== 'undefined' && document.body ) {
			const observer = new MutationObserver( function( mutations ) {
				for ( const mutation of mutations ) {
					if ( mutation.type === 'attributes' && mutation.attributeName === 'class' ) {
						sccForceWordPressMenuOpen();
						sccUpdateMenuToggleIcon();
						break;
					}
				}
			} );
			observer.observe( document.body, { attributes: true, attributeFilter: [ 'class' ] } );
		}
	})

	function sccForceWordPressMenuOpen() {
		const body = document.body;

		if ( ! body ) {
			return;
		}

		if ( body.classList.contains( 'folded' ) || body.classList.contains( 'auto-fold' ) ) {
			body.classList.remove( 'folded' );
			body.classList.remove( 'auto-fold' );
		}
	}
	
	// Update the menu toggle icon based on current state
	function sccUpdateMenuToggleIcon() {
		const body = document.body;
		const isCurrentlyFolded = body.classList.contains( 'folded' ) || body.classList.contains( 'auto-fold' );
		const iconWrapper = document.getElementById('scc-menu-toggle-icon');
		
		if (iconWrapper) {
			// When folded (collapsed), show > (chevron-right) to indicate it will expand
			// When not folded (expanded), show < (chevron-left) to indicate it will collapse
			const chevronRight = '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-chevron-right"><path d="M9 18l6-6-6-6"/></svg>';
			const chevronLeft = '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-chevron-left"><path d="M15 18l-6-6 6-6"/></svg>';
			
			iconWrapper.innerHTML = isCurrentlyFolded ? chevronRight : chevronLeft;

			const toggleButton = iconWrapper.closest( '.scc-btn-header-back' );
			if ( toggleButton ) {
				toggleButton.setAttribute( 'aria-expanded', isCurrentlyFolded ? 'false' : 'true' );
			}
		}
	}
	
	// Toggle WordPress admin menu
	function sccToggleWordPressMenu(event) {
		event.preventDefault();

		sccForceWordPressMenuOpen();

		// Update icon after toggle
		setTimeout(function() {
			sccUpdateMenuToggleIcon();
		}, 50);
	}
</script>
<?php if ( $scc_screen->base === 'admin_page_scc_edit_items' ) {
    do_action( 'scc_render_try_demo_notices' );
} ?>
<div class="row ms-0 align-items-center scc-background-transparent justify-content-center w-100 flex-column" style="gap: 0;">
	<!-- PRIMARY NAVBAR -->
	<div class="scc-primary-navbar col-12 mx-auto w-100">
		<div class="scc-primary-nav-left">
			<a href="https://stylishcostcalculator.com/" class="scc-header-logo-white" target="_blank" rel="noopener noreferrer" style="background-color: #ffffff; border-radius: 8px; padding: 5px; display: flex; align-items: center; justify-content: center; width: 40px; height: 40px;">
				<img src="<?php echo esc_url( SCC_URL . 'assets/images/scc-icon.png' ); ?>" alt="Stylish Cost Calculator Logo" style="max-width: 100%; max-height: 100%; object-fit: contain;">
				<?php if ( $isSCCFreeVersion ) { ?>
					<span class="scc-free-badge-header">FREE</span>
				<?php } ?>
			</a>
			
			<ul class="scc-primary-nav-links" style="margin-left: 10px;">
				<?php if ( $scc_screen->base === 'stylish-cost-calculator_page_scc-list-all-calculator-forms' || true ) { ?>
				<li>
					<a href="<?php echo esc_url( menu_page_url( 'scc-tabs', false ) ); ?>" class="scc-new-btn-badge text-decoration-none mt-0 ms-0" style="margin-left: -5px !important; margin-top: 0 !important; cursor: pointer;">
						<span class="scc-icn-wrapper"><?php echo scc_get_kses_extended_ruleset( $scc_icons['plus'] ?? $scc_icons['plus-circle'] ); ?></span>	
						NEW
					</a>
				</li>
				<?php } ?>
				<li>
					<a href="<?php echo esc_url( admin_url( 'admin.php?page=scc-list-all-calculator-forms' ) ); ?>" class="scc-nav-link <?php echo (isset($_REQUEST['page']) && $_REQUEST['page'] === 'scc-list-all-calculator-forms') ? 'active' : ''; ?>">
						<span class="scc-icn-wrapper"><?php echo scc_get_kses_extended_ruleset( $scc_icons['folder'] ?? $scc_icons['list'] ); ?></span>
						Calculators
					</a>
				</li>
				<?php 
					$quotes_url = admin_url( 'admin.php?page=scc-quote-management-screen' );
					if ( isset( $f1 ) && isset( $f1->id ) ) {
						$quotes_url = admin_url( "admin.php?page=scc-quote-management-screen&id={$f1->id}" );
					}
				?>
				<li>
					<a href="javascript:void()" data-setting-tooltip-type="quote-screen-tt" class="scc-nav-link scc-nav-link-disabled use-tooltip use-premium-tooltip <?php echo (isset($_REQUEST['page']) && $_REQUEST['page'] === 'scc-quote-management-screen') ? 'active' : ''; ?>" onclick="event.preventDefault();" aria-disabled="true">
						<span class="scc-icn-wrapper"><?php echo scc_get_kses_extended_ruleset( $scc_icons['file-text'] ); ?></span>
						Orders
					</a>
				</li>
				<li>
					<a href="javascript:void()" data-setting-tooltip-type="sms-dashboard-tt" class="scc-nav-link scc-nav-link-disabled use-tooltip use-premium-tooltip" onclick="event.preventDefault();" aria-disabled="true">
						<span class="scc-icn-wrapper"><?php echo scc_get_kses_extended_ruleset( $scc_icons['message-square'] ?? $scc_icons['message-circle'] ); ?></span>
						SMS Dashboard
					</a>
				</li>
				<li>
					<a href="<?php echo esc_url( admin_url( 'admin.php?page=scc-global-settings' ) ); ?>" class="scc-nav-link <?php echo (isset($_REQUEST['page']) && $_REQUEST['page'] === 'scc-global-settings') ? 'active' : ''; ?>">
						<span class="scc-icn-wrapper"><?php echo scc_get_kses_extended_ruleset( $scc_icons['settings'] ); ?></span>
						Global Settings
					</a>
				</li>
			</ul>
		</div>

		<div class="scc-primary-nav-right">
			<ul class="scc-primary-nav-links">
				<li class="dropdown">
					<a href="#" class="scc-nav-link dropdown-toggle scc-minimal-header-icon" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
						<span class="scc-icn-wrapper"><?php echo scc_get_kses_extended_ruleset( $scc_icons['help-circle'] ); ?></span>
						Help
					</a>
					<ul class="dropdown-menu scc-multilevel-dropdown-menu scc-backup-dropdown-menu dropdown-menu-end">
						<li><a class="dropdown-item" target="_blank" href="<?php echo esc_url( SCC_HELPDESK_LINKS['helpdesk-home'] ); ?>">
							<span class="scc-icn-wrapper me-2"><?php echo scc_get_kses_extended_ruleset( $scc_icons['book-open'] ); ?></span>
							User Guides</a></li>
						<li><a class="dropdown-item" target="_blank" href="<?php echo esc_url( SCC_HELPDESK_LINKS['helpdesk-video-tutorials'] ); ?>">
							<span class="scc-icn-wrapper me-2"><?php echo scc_get_kses_extended_ruleset( $scc_icons['video'] ); ?></span>
							Video Guides</a></li>
						<li><a class="dropdown-item" target="_blank" href="<?php echo admin_url( 'admin.php?page=scc-diagnostics' ); ?>">
							<span class="scc-icn-wrapper me-2"><?php echo scc_get_kses_extended_ruleset( $scc_icons['activity'] ); ?></span>
							Diagnostic</a></li>
						<li><a class="dropdown-item" target="_blank" href="<?php echo esc_url( SCC_HELPDESK_LINKS['helpdesk-troubleshooting'] ); ?>">
							<span class="scc-icn-wrapper me-2"><?php echo scc_get_kses_extended_ruleset( $scc_icons['tool'] ); ?></span>
							Troubleshooting</a></li>
						<li><a class="dropdown-item" target="_blank" href="https://stylishcostcalculator.com/support/">
							<span class="scc-icn-wrapper me-2"><?php echo scc_get_kses_extended_ruleset( $scc_icons['life-buoy'] ); ?></span>
							Contact Support</a></li>
					</ul>
				</li>
				<li class="scc-profile-nav-item">
					<a href="https://members.stylishcostcalculator.com/" class="scc-profile-button" aria-label="Open Members Portal" title="<?php echo esc_attr( $scc_profile_name ? $scc_profile_name : 'Members Portal' ); ?>" target="_blank" rel="noopener noreferrer">
						<?php echo esc_html( $scc_profile_initials ); ?>
					</a>
				</li>
			</ul>
		</div>
	</div>

	<?php if ( ! ( isset( $_REQUEST['page'] ) && $_REQUEST['page'] === 'scc_edit_items' ) ) { ?>
	<div class="scc-non-editor-page-header p-4" style="background:#f8fafc; border-bottom:1px solid #e2e8f0; width:100%;">
		<div class="scc-page-name-and-desc text-dark">
			<?php
			$scc_screen = get_current_screen();
			switch ( $scc_screen->base ) {
				case 'stylish-cost-calculator_page_scc-quote-management-screen':
					$quotes_from_id = 0;
					if ( isset( $_GET['id'] ) ) {
						$quotes_from_id = absint( $_GET['id'] );
					}
					echo '<h1 class="scc-page-title text-dark m-0 fs-3">Quotes & Leads Dashboard</h1>';
					if ( $quotes_from_id !== 0 ) {
						global $wpdb;
						$name = $wpdb->get_results( $wpdb->prepare( "SELECT formname FROM {$wpdb->prefix}df_scc_forms WHERE id = %d", $quotes_from_id ) );
						if ( !empty( $name ) ) {
							echo '<div id="calculator-name" class="scc-page-desc text-secondary ms-3 ps-3 mt-1">' . esc_html( $name[0]->formname ) . '</div>';
						}
					}
					break;
				case 'stylish-cost-calculator_page_scc-global-settings':
					echo '<h1 class="scc-page-title text-dark m-0 fs-3">Global Settings</h1>';
					break;
				case 'stylish-cost-calculator_page_stylish_cost_calculator_license':
					echo '<h1 class="scc-page-title text-dark m-0 fs-3">License & Members Settings</h1>';
					break;
				case 'stylish-cost-calculator_page_scc-license-help':
					echo '<h1 class="scc-page-title text-dark m-0 fs-3">Member\'s Portal</h1>';
					break;
				case 'stylish-cost-calculator_page_scc-coupons-management':
				case 'admin_page_scc-coupons-management':
					echo '<h1 class="scc-page-title text-dark m-0 fs-3">Coupon Generator & Editor</h1>';
					break;
				case 'stylish-cost-calculator_page_scc-diagnostics':
					echo '<h1 class="scc-page-title text-dark m-0 fs-3">Diagnostics</h1>';
					break;
				case 'stylish-cost-calculator_page_scc-sms-quotes-dashboard':
					echo '<h1 class="scc-page-title text-dark m-0 fs-3">SMS Dashboard</h1>';
					break;
				case 'stylish-cost-calculator_page_scc-list-all-calculator-forms':
					echo '<div class="d-flex justify-content-between align-items-center w-100">';
					echo '<h1 class="scc-page-title text-dark m-0 fs-3">All Calculator Forms</h1>';
					printf(
						'<a class="text-decoration-none" href="%s"><button class="btn scc-btn-green py-2 d-flex align-items-center text-white gap-2"><span class="scc-icn-wrapper"> %s</span> %s</button></a>',
						menu_page_url( 'scc-tabs', false ),
						'<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-plus"><path d="M12 5v14M5 12h14"></path></svg>',
						__( 'Add New Calculator', 'textdomain' )
					);
					echo '</div>';
					break;
				case 'stylish-cost-calculator_page_scc-support':
					echo '<h1 class="scc-page-title text-dark m-0 fs-3">Support & Help</h1>';
					break;
			}
			?>
		</div>
	</div>
	<?php } ?>

	<?php if ( isset( $_REQUEST['page'] ) && $_REQUEST['page'] === 'scc_edit_items' && isset( $f1 ) ) { ?>
	<!-- SECONDARY NAVBAR (Edit Page Only) -->
	<div class="scc-secondary-navbar col-12 mx-auto w-100">
		<div class="scc-secondary-nav-left">
			<a href="<?php echo esc_url( admin_url( 'admin.php?page=scc-list-all-calculator-forms' ) ); ?>">
				Calculators
			</a>
			<span class="scc-breadcrumb-separator">></span>
			<div class="scc-calc-name-input-wrapper">
				<input type="text" id="id_form_input" value="<?php echo intval( $f1->id ); ?>" hidden>
				<span class="scc-calc-name-edit-icon" aria-hidden="true"><?php echo scc_get_kses_extended_ruleset( $scc_icons['edit-3'] ?? $scc_icons['pen-tool'] ); ?></span>
				<input type="text" id="costcalculatorname" placeholder="Enter the name of this calculator" value="<?php echo esc_attr( wp_unslash( $f1->formname ) ); ?>" />
			</div>
		</div>

		<div class="scc-secondary-nav-right">
			<a id="downloadAnchorElem" class="scc-hidden"></a>
			
			<div class="use-premium-tooltip" style="display:inline-block; cursor:not-allowed;">
				<button class="btn-action" type="button" style="opacity: 0.5; pointer-events: none; box-shadow:none; margin: 0;">
					<span class="scc-icn-wrapper"><?php echo scc_get_kses_extended_ruleset( $scc_icons['archive'] ?? $scc_icons['upload-cloud'] ?? '' ); ?></span> Backup & Restore
				</button>
			</div>

			<div class="scc-embed-wrapper" style="position:relative;">
				<button class="btn-action" onclick="sccBackendUtils.toggleEmbedToPagePanel(this);">
					<span class="scc-icn-wrapper"><?php echo scc_get_kses_extended_ruleset( $scc_icons['code'] ); ?></span> Embed
				</button>
				<div id="df_scc_tabembed_" class="scc-embed-tip-container scc-hidden">
					<button class="scc-embed-close-button pull-right" onclick="sccBackendUtils.toggleEmbedToPagePanel(document.querySelector('.scc-embed-wrapper button'));" style="background:transparent;border:none;">
						<?php echo scc_get_kses_extended_ruleset( $scc_icons['close'] ); ?>
					</button>
					<div class="scc-embed-tip-wrapper">
						<h3 class="scc-embed-tips-title mb-3 fs-5">Embed to Page</h3>
						<div class="scc-embed-field-container mb-3">
							<h3 class="scc-embed-field-label fs-6">Calculator Form <a href="<?php echo esc_attr( SCC_HELPDESK_LINKS['troubleshoot-embedding-to-webpage'] ); ?>" target="_blank"><i class="material-icons-outlined" style="font-size:16px;">help_outline</i></a></h3>
							<div class="position-relative">
								<div class="scc-embed-field" >[scc_calculator type='text' idvalue='<?php echo intval( $f1->id ); ?>']</div>
								<button class="scc-copy-embed-button" onclick="sccBackendUtils.copyEmbedsToClipboard(this);" ><?php echo scc_get_kses_extended_ruleset( $scc_icons['copy'] ); ?></button>
								<span class="scc-ai-copy-message-confirmation scc-hidden" >Copied!</span>
							</div>
						</div>
						<div class="scc-embed-field-container mb-3">
							<h3 class="scc-embed-field-label fs-6">Custom Calculator Total <a href="<?php echo esc_attr( SCC_HELPDESK_LINKS['feature-custom-totals'] ); ?>" target="_blank"><i class="material-icons-outlined" style="font-size:16px;">help_outline</i></a></h3>
							<div class="position-relative">
								<div class="scc-embed-field" >[scc_calculator-total type='text' idvalue='<?php echo intval( $f1->id ); ?>']</div>
								<button class="scc-copy-embed-button" onclick="sccBackendUtils.copyEmbedsToClipboard(this);" ><?php echo scc_get_kses_extended_ruleset( $scc_icons['copy'] ); ?></button>
								<span class="scc-ai-copy-message-confirmation scc-hidden" >Copied!</span>
							</div>
						</div>
						<div class="scc-embed-field-container mb-3">
							<h3 class="scc-embed-field-label fs-6">Floating Itemized List <a href="<?php echo esc_attr( SCC_HELPDESK_LINKS['feature-floating-itemized-list'] ); ?>" target="_blank"><i class="material-icons-outlined" style="font-size:16px;">help_outline</i></a></h3>
							<div class="position-relative">
								<div class="scc-embed-field" >[scc_calculator-detail type='text' idvalue='<?php echo intval( $f1->id ); ?>']</div>
								<button class="scc-copy-embed-button" onclick="sccBackendUtils.copyEmbedsToClipboard(this);" ><?php echo scc_get_kses_extended_ruleset( $scc_icons['copy'] ); ?></button>
								<span class="scc-ai-copy-message-confirmation scc-hidden" >Copied!</span>
							</div>
						</div>
						<hr class="my-3">
						<div class="scc-embed-tip-footer d-flex align-items-start text-muted" style="font-size:13px;">
							<i class="material-icons-outlined me-2" style="font-size:18px;">help_outline</i> <span>Copy and paste this shortcode <a href="<?php echo esc_attr( SCC_HELPDESK_LINKS['troubleshoot-embedding-to-webpage'] ); ?>" target="_blank"><b>properly</b></a> into a code, text, shortcode, or shortblock widget within your page builder. Do not use the visual text box.</span>
						</div>
					</div>
				</div>
			</div>

			<div class="dropdown scc-menu-dropdown">
				<button id="scc-calculator-settings-menu-button" class="btn-action dropdown-toggle" onclick="sccToggleMenuDropdown( this, event )" style="box-shadow:none;">
					<span class="scc-icn-wrapper"><?php echo scc_get_kses_extended_ruleset( $scc_icons['settings'] ); ?></span>
					Calc Settings
				</button>
				<div class="scc-menu-dropdown-content scc-hidden">
					<a class="scc-font-settings-dropdown" href="#" data-bs-toggle="modal" data-bs-target="#settingsModal" onclick="sccToggleMenuDropdown( this )" ><span class="scc-icn-wrapper">
						<?php echo scc_get_kses_extended_ruleset( $scc_icons['edit-3'] ); ?>
					</span> Font Settings</a>
					<hr>
					<a class="scc-calculator-settings-dropdown" href="#" data-bs-toggle="modal" data-bs-target="#settingsModal1" onclick="sccToggleMenuDropdown( this )" ><span class="scc-icn-wrapper">
						<?php echo scc_get_kses_extended_ruleset( $scc_icons['tool'] ); ?>
					</span> Calculator Settings</a>
					<hr>
					<a class="scc-calculator-settings-dropdown" href="#" data-bs-toggle="modal" data-bs-target="#formBuilderModal" onclick="sccToggleMenuDropdown( this )" ><span class="scc-icn-wrapper">
						<?php echo scc_get_kses_extended_ruleset( $scc_icons['mail'] ); ?>
					</span> Form Builder</a>
					<hr>
					<a class="scc-calculator-settings-dropdown" href="#" data-bs-toggle="modal" data-bs-target="#paymentSettingsModal" onclick="sccToggleMenuDropdown( this )" ><span class="scc-icn-wrapper">
						<?php echo scc_get_kses_extended_ruleset( $scc_icons['dollar-sign'] ); ?>
					</span> Payment Settings</a>
					<hr>
					<a class="scc-wordings-settings-dropdown" href="#" data-bs-toggle="modal" data-bs-target="#settingsModal2" onclick="sccToggleMenuDropdown( this )" ><span class="scc-icn-wrapper">
						<?php echo scc_get_kses_extended_ruleset( $scc_icons['file-text'] ); ?>
					</span> Wordings</a>
					<hr>
					<?php if ( $couponFeatureUnlocked ) { ?>
						<a class="scc-coupon-codes-dropdown <?php echo ( isset( $_REQUEST['page'] ) && $_REQUEST['page'] === 'scc-coupons-management' ) ? 'active' : ''; ?>" href="<?php echo esc_url( admin_url( 'admin.php?page=scc-coupons-management' ) ); ?>"><span class="scc-icn-wrapper">
							<?php echo scc_get_kses_extended_ruleset( $scc_icons['percent'] ); ?>
						</span> Coupon Codes</a>
					<?php } else { ?>
						<a href="javascript:void()" class="scc-coupon-codes-dropdown scc-nav-link scc-nav-link-disabled use-premium-tooltip scc-premium-badge <?php echo ( isset( $_REQUEST['page'] ) && $_REQUEST['page'] === 'scc-coupons-management' ) ? 'active' : ''; ?>" onclick="event.preventDefault();" aria-disabled="true"><span class="scc-icn-wrapper">
							<?php echo scc_get_kses_extended_ruleset( $scc_icons['percent'] ); ?>
						</span> Coupon Codes</a>
					<?php } ?>
					<hr>
					<a class="scc-global-settings-dropdown" href="<?php echo esc_url( admin_url( 'admin.php?page=scc-global-settings' ) ); ?>"><span class="scc-icn-wrapper">
						<?php echo scc_get_kses_extended_ruleset( $scc_icons['settings'] ); ?>
					</span> Global Settings</a>
				</div>
			</div>

			<button class="btn scc-btn-green btn-action text-white scc-top-save-btn" onClick="saveDataFields()">
				<i class="scc-btn-spinner scc-save-btn-spinner scc-d-none ms-0"></i>Save
			</button>
		</div>
	</div>
	<?php } ?>

	<?php do_action( 'scc-edit-page' ); ?>
</div>
	<div class="container-fluid col-12" id="settings-tabs-wrapper">
		<!--Main Content Container-->
		<div id="debug_messages_wrapper" class="d-none"></div>
		<div id="notices_wrapper" class="d-none"></div>
		<div id="sg_optimizer_message_wrapper" class="d-none alert alert-danger" role="alert">
			<div class="diag-msg-container">
				<p>
					<span class="scc-icn-wrapper">
						<svg xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 -960 960 960" width="24"><path d="m40-120 440-760 440 760H40Zm138-80h604L480-720 178-200Zm302-40q17 0 28.5-11.5T520-280q0-17-11.5-28.5T480-320q-17 0-28.5 11.5T440-280q0 17 11.5 28.5T480-240Zm-40-120h80v-200h-80v200Zm40-100Z"/></svg>
					</span>
					<b>You're using SG Page Optimizer!</b>
				</p>
				<p class="mb-0">This plugin is known for heavy JS optimizations that interfere with the contact forms and calculator forms</p>
				<i class="material-icons diag-msg-close" onclick="javascript:skipSGOptimWarning(this)">close</i>
			</div>
		</div>
		<script>
			function showSettingsTab(type) {
				switch (type) {
					case "font":
						b_font.click()
						break
					case "translation":
						b_tans.click()
						break
					case "settings":
						b_calc.click()
						break
				}
			}

			/**
			 * *Handles download of backup
			 */

			function downloadBackup(isPremium) {
				return
			}

			let sccHelpdeskLinks = <?php echo json_encode(scc_get_helpdesk_link_list()); ?>;
		</script>
