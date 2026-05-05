import { createElement, createRoot } from './elements';
import { SETTINGS_MODAL_ID, SETTINGS_ROOT_ID } from './constants';
import { SettingsBootstrapFallback } from './controls';
import { SettingsApp } from './SettingsApp';
import { parseSettingsSchema } from './utils';

function initSettingsModalReact() {
	const modalNode = document.getElementById( SETTINGS_MODAL_ID );
	const mountNode = document.getElementById( SETTINGS_ROOT_ID );

	if ( ! modalNode || ! mountNode ) {
		return;
	}

	let reactRoot = null;

	const handleModalShown = () => {
		if ( reactRoot ) {
			return;
		}

		const schema = parseSettingsSchema();
		reactRoot = createRoot( mountNode );
		reactRoot.render( schema.sections.length ? <SettingsApp schema={ schema } /> : <SettingsBootstrapFallback /> );
	};

	const handleModalHidden = () => {
		if ( ! reactRoot ) {
			return;
		}

		reactRoot.unmount();
		reactRoot = null;
		mountNode.innerHTML = '';
	};

	modalNode.addEventListener( 'shown.bs.modal', handleModalShown );
	modalNode.addEventListener( 'hidden.bs.modal', handleModalHidden );
}

export { initSettingsModalReact };
