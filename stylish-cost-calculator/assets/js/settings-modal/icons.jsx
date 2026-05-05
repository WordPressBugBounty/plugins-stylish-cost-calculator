import { createElement } from './elements';

function SettingsNavIcon( { children, viewBox = '0 0 20 20' } ) {
	return (
		<svg className="scc-beta-icon" viewBox={ viewBox } fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
			{children}
		</svg>
	);
}

function BetaIcon( { label } ) {
	const normalizedLabel = String( label || '' ).toLowerCase();

	if ( normalizedLabel.includes( 'pdf' ) ) {
		return (
			<SettingsNavIcon>
				<path d="M6 3.8H11.5L15 7.3V16.2H6V3.8Z" stroke="currentColor" strokeWidth="1.6" strokeLinejoin="round" />
				<path d="M11.2 3.8V7.6H15" stroke="currentColor" strokeWidth="1.6" strokeLinejoin="round" />
				<path d="M8 10.2H13" stroke="currentColor" strokeWidth="1.6" strokeLinecap="round" />
				<path d="M8 13H13" stroke="currentColor" strokeWidth="1.6" strokeLinecap="round" />
			</SettingsNavIcon>
		);
	}

	if ( normalizedLabel.includes( 'sms' ) || normalizedLabel.includes( 'text message' ) ) {
		return (
			<SettingsNavIcon>
				<path d="M4 5.2H16V13.3H8.4L5.2 15.8V13.3H4V5.2Z" stroke="currentColor" strokeWidth="1.6" strokeLinejoin="round" />
				<path d="M7.2 9.2H12.8" stroke="currentColor" strokeWidth="1.6" strokeLinecap="round" />
			</SettingsNavIcon>
		);
	}

	switch ( label ) {
		case 'General':
			return (
				<SettingsNavIcon>
					<rect x="3.5" y="3.5" width="13" height="13" rx="2.5" stroke="currentColor" strokeWidth="1.6" />
					<path d="M6.5 7.5H13.5" stroke="currentColor" strokeWidth="1.6" strokeLinecap="round" />
				</SettingsNavIcon>
			);
		case 'Typography & Colors':
			return (
				<SettingsNavIcon>
					<path d="M10 4.2A5.8 5.8 0 1 0 15.8 10" stroke="currentColor" strokeWidth="1.6" strokeLinecap="round" />
					<circle cx="8" cy="8" r="1.1" fill="currentColor" />
					<circle cx="12.4" cy="6.8" r="1.1" fill="currentColor" />
					<circle cx="13.8" cy="11" r="1.1" fill="currentColor" />
				</SettingsNavIcon>
			);
		case 'Details List & PDF':
			return (
				<SettingsNavIcon>
					<path d="M6 3.8H11.5L15 7.3V16.2H6V3.8Z" stroke="currentColor" strokeWidth="1.6" strokeLinejoin="round" />
					<path d="M11.2 3.8V7.6H15" stroke="currentColor" strokeWidth="1.6" strokeLinejoin="round" />
					<path d="M8 10.2H13" stroke="currentColor" strokeWidth="1.6" strokeLinecap="round" />
					<path d="M8 13H13" stroke="currentColor" strokeWidth="1.6" strokeLinecap="round" />
				</SettingsNavIcon>
			);
		case 'Currency & Tax':
			return (
				<SettingsNavIcon>
					<path d="M10 3.5V16.5" stroke="currentColor" strokeWidth="1.6" strokeLinecap="round" />
					<path d="M13.6 6.6H8.4C7 6.6 5.9 7.6 5.9 8.8C5.9 10 7 11 8.4 11H11.6C13 11 14.1 12 14.1 13.2C14.1 14.4 13 15.4 11.6 15.4H6.4" stroke="currentColor" strokeWidth="1.6" strokeLinecap="round" strokeLinejoin="round" />
				</SettingsNavIcon>
			);
		case 'Email Settings':
			return (
				<SettingsNavIcon>
					<rect x="3.5" y="5.2" width="13" height="9.6" rx="2.2" stroke="currentColor" strokeWidth="1.6" />
					<path d="M5.4 7L10 10.5L14.6 7" stroke="currentColor" strokeWidth="1.6" strokeLinecap="round" strokeLinejoin="round" />
				</SettingsNavIcon>
			);
		case 'SMS Settings':
			return (
				<SettingsNavIcon>
					<path d="M4 5.2H16V13.3H8.4L5.2 15.8V13.3H4V5.2Z" stroke="currentColor" strokeWidth="1.6" strokeLinejoin="round" />
					<path d="M7.2 9.2H12.8" stroke="currentColor" strokeWidth="1.6" strokeLinecap="round" />
				</SettingsNavIcon>
			);
		case 'Webhook Events':
			return (
				<SettingsNavIcon>
					<circle cx="6" cy="6" r="1.7" stroke="currentColor" strokeWidth="1.5" />
					<circle cx="14" cy="10" r="1.7" stroke="currentColor" strokeWidth="1.5" />
					<circle cx="6" cy="14" r="1.7" stroke="currentColor" strokeWidth="1.5" />
					<path d="M7.5 7.1L12.5 9" stroke="currentColor" strokeWidth="1.5" strokeLinecap="round" />
					<path d="M7.5 12.9L12.5 11" stroke="currentColor" strokeWidth="1.5" strokeLinecap="round" />
				</SettingsNavIcon>
			);
		case 'Custom CSS':
			return (
				<SettingsNavIcon>
					<path d="M8 4.8C6.4 5.6 5.7 7.1 5.7 10C5.7 12.9 6.4 14.4 8 15.2" stroke="currentColor" strokeWidth="1.6" strokeLinecap="round" />
					<path d="M12 4.8C13.6 5.6 14.3 7.1 14.3 10C14.3 12.9 13.6 14.4 12 15.2" stroke="currentColor" strokeWidth="1.6" strokeLinecap="round" />
				</SettingsNavIcon>
			);
		default:
			return (
				<SettingsNavIcon>
					<circle cx="10" cy="10" r="1.8" fill="currentColor" />
				</SettingsNavIcon>
			);
	}
}

function SettingsChevron( { isOpen } ) {
	return (
		<svg
			className={ `scc-settings-group-chevron${ isOpen ? ' is-open' : '' }` }
			viewBox="0 0 20 20"
			fill="none"
			xmlns="http://www.w3.org/2000/svg"
			aria-hidden="true"
		>
			<path
				d="M6.5 8.25L10 11.75L13.5 8.25"
				stroke="currentColor"
				strokeWidth="1.8"
				strokeLinecap="round"
				strokeLinejoin="round"
			/>
		</svg>
	);
}

function EditIcon() {
	return (
		<svg
			className="scc-settings-edit-icon"
			viewBox="0 0 20 20"
			fill="none"
			xmlns="http://www.w3.org/2000/svg"
			aria-hidden="true"
		>
			<path
				d="M11.8 4.1L15.9 8.2M5.2 14.8L8.4 14.1L15 7.5C15.6 6.9 15.6 5.9 15 5.3L14.7 5C14.1 4.4 13.1 4.4 12.5 5L5.9 11.6L5.2 14.8Z"
				stroke="currentColor"
				strokeWidth="1.8"
				strokeLinecap="round"
				strokeLinejoin="round"
			/>
		</svg>
	);
}

function HelpIcon() {
	return (
		<svg
			className="scc-settings-help-icon"
			viewBox="0 0 20 20"
			fill="none"
			xmlns="http://www.w3.org/2000/svg"
			aria-hidden="true"
		>
			<circle cx="10" cy="10" r="7.6" stroke="currentColor" strokeWidth="1.8" />
			<path d="M7.9 7.7C7.9 6.5 8.8 5.6 10 5.6C11.2 5.6 12.1 6.4 12.1 7.5C12.1 8.4 11.6 8.8 10.8 9.3C10.1 9.7 9.8 10 9.8 10.8" stroke="currentColor" strokeWidth="1.8" strokeLinecap="round" strokeLinejoin="round" />
			<circle cx="10" cy="13.8" r="0.8" fill="currentColor" />
		</svg>
	);
}

function GemIcon() {
	return (
		<svg
			className="scc-settings-gem-icon"
			viewBox="0 0 20 20"
			fill="none"
			xmlns="http://www.w3.org/2000/svg"
			aria-hidden="true"
		>
			<path
				d="M6.1 3.8H13.9L17 7.5L10 16.3L3 7.5L6.1 3.8Z"
				stroke="currentColor"
				strokeWidth="1.7"
				strokeLinejoin="round"
			/>
			<path
				d="M3.4 7.5H16.6M7 4L8.6 7.5L10 16M13 4L11.4 7.5L10 16"
				stroke="currentColor"
				strokeWidth="1.4"
				strokeLinecap="round"
				strokeLinejoin="round"
			/>
		</svg>
	);
}

export { BetaIcon, EditIcon, GemIcon, HelpIcon, SettingsChevron };
