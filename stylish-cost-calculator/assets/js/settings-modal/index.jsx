import { initSettingsModalReact } from './init.jsx';

if ( document.readyState === 'loading' ) {
	document.addEventListener( 'DOMContentLoaded', initSettingsModalReact );
} else {
	initSettingsModalReact();
}
