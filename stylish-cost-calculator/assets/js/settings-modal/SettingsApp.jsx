import { Fragment, createElement, useEffect, useRef, useState } from './elements';
import { BetaIcon, SettingsChevron } from './icons';
import { SubsectionPanel } from './controls';
import { SettingsStyles } from './styles';
import {
	buildGroupMap,
	buildSettingsModalState,
	getControlStateKey,
	getControlValue,
	getControlsList,
	getDisplayLabel,
	getSectionLabel,
	getSubsectionDomId,
	slugify,
} from './utils';

const SETTINGS_UI_STATE_KEY = 'sccSettingsModalUiState';

function SettingsApp( { schema } ) {
	const groups = buildGroupMap( schema );
	const allControls = getControlsList( schema.controls || {} );
	const contentRef = useRef( null );
	const persistedState = window[ SETTINGS_UI_STATE_KEY ] || {};
	const initialGroupName = persistedState.activeGroupName && groups.some( ( group ) => group.name === persistedState.activeGroupName )
		? persistedState.activeGroupName
		: groups[ 0 ]?.name || '';
	const initialActiveGroup = groups.find( ( group ) => group.name === initialGroupName ) || groups[ 0 ] || null;
	const initialSubsectionName = persistedState.activeSubsectionName &&
		initialActiveGroup?.sections?.some( ( section ) => getSectionLabel( section ) === persistedState.activeSubsectionName )
		? persistedState.activeSubsectionName
		: initialActiveGroup?.sections?.[ 0 ]
			? getSectionLabel( initialActiveGroup.sections[ 0 ] )
			: '';
	const [ activeGroupName, setActiveGroupName ] = useState( initialGroupName );
	const [ activeSubsectionName, setActiveSubsectionName ] = useState( initialSubsectionName );
	const previousActiveGroupNameRef = useRef( activeGroupName );
	const [ controlValues, setControlValues ] = useState( () => {
		const initialValues = {};

		allControls.forEach( ( control ) => {
			const controlStateKey = getControlStateKey( control );
			const hasPersistedValue = Object.prototype.hasOwnProperty.call( persistedState.controlValues || {}, controlStateKey ) &&
				persistedState.controlValues[ controlStateKey ] !== null;
			initialValues[ controlStateKey ] = hasPersistedValue
				? persistedState.controlValues[ controlStateKey ]
				: getControlValue( control );
		} );

		return initialValues;
	} );

	useEffect( () => {
		window.sccSettingsModalState = buildSettingsModalState( allControls, controlValues );
	}, [ allControls, controlValues ] );

	useEffect( () => () => {
		window.sccSettingsModalState = null;
	}, [] );

	useEffect( () => {
		window[ SETTINGS_UI_STATE_KEY ] = {
			activeGroupName,
			activeSubsectionName,
			controlValues,
		};
	}, [ activeGroupName, activeSubsectionName, controlValues ] );

	useEffect( () => {
		if ( ! groups.length ) {
			return;
		}

		const activeGroup = groups.find( ( group ) => group.name === activeGroupName ) || groups[ 0 ];
		const activeSections = activeGroup.sections || [];
		const hasActiveSubsection = activeSections.some( ( section ) => getSectionLabel( section ) === activeSubsectionName );

		if ( activeGroup.name !== activeGroupName ) {
			setActiveGroupName( activeGroup.name );
		}

		if ( ! hasActiveSubsection && activeSections[ 0 ] ) {
			setActiveSubsectionName( getSectionLabel( activeSections[ 0 ] ) );
		}
	}, [ activeGroupName, activeSubsectionName, groups ] );

	const activeGroup = groups.find( ( group ) => group.name === activeGroupName ) || groups[ 0 ] || null;
	const activeSections = activeGroup?.sections || [];

	useEffect( () => {
		if ( previousActiveGroupNameRef.current !== activeGroupName && contentRef.current ) {
			contentRef.current.scrollTo( {
				top: 0,
				behavior: 'auto',
			} );
		}

		previousActiveGroupNameRef.current = activeGroupName;
	}, [ activeGroupName ] );

	useEffect( () => {
		const contentNode = contentRef.current;
		if ( ! contentNode || typeof window.applySettingTooltip !== 'function' || ! window.bootstrap?.Tooltip ) {
			return undefined;
		}

		const tooltipNodes = Array.from( contentNode.querySelectorAll( '[data-setting-tooltip-type]' ) );
		tooltipNodes.forEach( ( node ) => {
			window.bootstrap.Tooltip.getInstance( node )?.dispose();
			window.applySettingTooltip( node );
		} );

		return () => {
			tooltipNodes.forEach( ( node ) => {
				window.bootstrap.Tooltip.getInstance( node )?.dispose();
			} );
		};
	}, [ activeGroupName ] );

	useEffect( () => {
		const contentNode = contentRef.current;
		if ( ! contentNode || ! activeSections.length ) {
			return undefined;
		}

		const updateActiveSubsectionFromScroll = () => {
			let closestSubsection = getSectionLabel( activeSections[ 0 ] );
			let closestDistance = Number.POSITIVE_INFINITY;

			activeSections.forEach( ( section ) => {
				const sectionNode = document.getElementById( getSubsectionDomId( section ) );
				if ( ! sectionNode ) {
					return;
				}

				const distance = Math.abs( sectionNode.offsetTop - contentNode.scrollTop - 16 );
				if ( distance < closestDistance ) {
					closestDistance = distance;
					closestSubsection = getSectionLabel( section );
				}
			} );

			if ( closestSubsection && closestSubsection !== activeSubsectionName ) {
				setActiveSubsectionName( closestSubsection );
			}
		};

		updateActiveSubsectionFromScroll();
		contentNode.addEventListener( 'scroll', updateActiveSubsectionFromScroll, { passive: true } );

		return () => {
			contentNode.removeEventListener( 'scroll', updateActiveSubsectionFromScroll );
		};
	}, [ activeSections, activeSubsectionName ] );

	const scrollToSubsection = ( subsectionName ) => {
		setActiveSubsectionName( subsectionName );

		window.requestAnimationFrame( () => {
			const contentNode = contentRef.current;
			const matchingSection = activeSections.find( ( section ) => getSectionLabel( section ) === subsectionName );
			const sectionNode = matchingSection ? document.getElementById( getSubsectionDomId( matchingSection ) ) : null;

			if ( contentNode && sectionNode ) {
				contentNode.scrollTo( {
					top: sectionNode.offsetTop - 16,
					behavior: 'smooth',
				} );
			}
		} );
	};

	return (
		<Fragment>
			<SettingsStyles />
			<div className="scc-settings-shell">
				<aside className="scc-settings-sidebar">
					<nav className="scc-settings-nav" aria-label="Settings groups">
						{ groups.map( ( group ) => {
							const isActiveGroup = group.name === activeGroupName;
							const hasMultipleSections = ( group.sections || [] ).length > 1;

							return (
								<div className="scc-settings-group" key={ group.key }>
									<button
										type="button"
										className={ `scc-settings-group-button${ isActiveGroup ? ' is-active' : '' }` }
										onClick={ () => {
											setActiveGroupName( group.name );
											if ( group.sections[ 0 ] ) {
												const sectionLabel = getSectionLabel( group.sections[ 0 ] );
												setActiveSubsectionName( sectionLabel );

												if ( ! hasMultipleSections && isActiveGroup ) {
													scrollToSubsection( sectionLabel );
												}
											}
										} }
									>
										<span className="scc-settings-group-label">
											<BetaIcon label={ group.label } />
											<span>{ group.label }</span>
										</span>
										{ hasMultipleSections ? <SettingsChevron isOpen={ isActiveGroup } /> : null }
									</button>
									{ isActiveGroup && hasMultipleSections ? (
										<div className="scc-settings-subnav">
											{ group.sections.map( ( section ) => {
												const sectionLabel = getSectionLabel( section );
												const displaySectionLabel = getDisplayLabel( sectionLabel );
												return (
													<button
														key={ `${ group.key }-${ slugify( sectionLabel ) }` }
														type="button"
														className={ `scc-settings-subnav-button${ activeSubsectionName === sectionLabel ? ' is-active' : '' }` }
														onClick={ () => {
															if ( ! isActiveGroup ) {
																setActiveGroupName( group.name );
															}
															scrollToSubsection( sectionLabel );
														} }
													>
														{ displaySectionLabel }
													</button>
												);
											} ) }
										</div>
									) : null}
								</div>
							);
						} ) }
					</nav>
				</aside>
				<section className="scc-settings-content" ref={ contentRef }>
					<div className="scc-settings-content-stack">
						{ activeSections.length ? activeSections.map( ( section ) => (
							<SubsectionPanel
								key={ getSectionLabel( section ) }
								section={ section }
								controls={ schema.controls || {} }
								controlValues={ controlValues }
								isActive={ activeSubsectionName === getSectionLabel( section ) }
								onControlChange={ ( controlStateKey, nextValue ) => {
									setControlValues( ( currentValues ) => ( {
										...currentValues,
										[ controlStateKey ]: nextValue,
									} ) );
								} }
							/>
						) ) : (
							<div className="scc-settings-empty">No settings sections were found in the schema.</div>
						) }
					</div>
				</section>
			</div>
		</Fragment>
	);
}

export { SettingsApp };
