import { InspectorControls, useBlockProps } from '@wordpress/block-editor';
import { useSelect } from '@wordpress/data';
import {
	PanelBody,
	PanelRow,
	BaseControl,
	TextControl,
	SelectControl,
	RadioControl,
	CheckboxControl,
} from '@wordpress/components';
import './editor.scss';
import vsIcon from '../core/vinoshipper.svg';

/**
 * The edit function describes the structure of your block in the context of the
 * editor. This represents what the editor will render when the block is used.
 *
 * @param {any} root0
 * @param {any} root0.attributes
 * @param {any} root0.setAttributes
 * @see https://developer.wordpress.org/block-editor/reference-guides/block-api/block-edit-save/#edit
 *
 * @return {Element} Element to render.
 */
export default function Edit( { attributes, setAttributes } ) {
	const { allow, defaultClub, headline, clubsDisplayAll } = attributes;

	const targetAccountId =
		wp.data.select( 'core' ).getSite().vs_injector_account_id ?? null;

	const { producer, availableClubs } = useSelect( ( select ) => {
		return {
			...select( 'vsInjectorProductItemStore' ).getAll(),
			...select( 'vsInjectorClubsStore' ).getAll(),
		};
	} );

	function getClubOptionsSelected() {
		const defaultSelect = {
			label: 'No default club',
			value: null,
			disabled: false,
		};
		if ( availableClubs.length > 0 ) {
			const clubFiltered = availableClubs.map( ( club ) => {
				const element = document.createElement( 'div' );
				element.innerHTML = club.name;

				let disabled = false;
				if ( ! clubsDisplayAll ) {
					disabled = allow.includes( club.id ) ? false : true;
				}

				return {
					label: element.textContent,
					value: club.id,
					disabled,
				};
			} );
			return [ defaultSelect, ...clubFiltered ];
		}
		return [ defaultSelect ];
	}

	function getClubDetails( clubId ) {
		if ( availableClubs.length > 0 ) {
			const club = availableClubs.find( ( a ) => a.id === clubId );
			return club ?? null;
		}
		return null;
	}

	function getProducer() {
		if ( producer ) {
			return producer;
		}
		return {
			id: null,
			name: 'N/A',
			img: null,
		};
	}

	return (
		<div { ...useBlockProps() }>
			<InspectorControls>
				<PanelBody title="Club Settings">
					<PanelRow>
						<RadioControl
							label="Clubs to Display"
							selected={ clubsDisplayAll }
							options={ [
								{ label: 'All Available Clubs', value: true },
								{ label: 'Select Club(s)', value: false },
							] }
							onChange={ ( newValue ) => {
								if ( newValue === 'true' ) {
									setAttributes( {
										clubsDisplayAll: true,
										allow: [],
									} );
								} else {
									setAttributes( {
										clubsDisplayAll: false,
									} );
								}
							} }
							help={
								<div>
									Select all or certain club(s) associated to:
									<br />
									{ getProducer().name } (#
									{ getProducer().id })
								</div>
							}
						/>
					</PanelRow>
					{ ! clubsDisplayAll && (
						<PanelRow>
							<BaseControl
								label="Allowed Clubs"
								id="allowed-clubs-control"
							>
								{ availableClubs.map( ( club ) => (
									<CheckboxControl
										key={ club.id }
										label={ club.name }
										checked={
											allow.includes( club.id )
												? true
												: false
										}
										onChange={ ( newValue ) => {
											const tempValue = JSON.parse(
												JSON.stringify( allow )
											);
											// To prevent double IDs, always remove existing ID, even if we add it back in.
											const targetIndex =
												tempValue.findIndex(
													( clubFind ) => {
														return (
															clubFind === club.id
														);
													}
												);
											if ( targetIndex >= 0 ) {
												tempValue.splice( targetIndex );
											}
											if ( newValue ) {
												tempValue.push( club.id );
											}
											setAttributes( {
												allow: tempValue,
											} );
										} }
									/>
								) ) }
							</BaseControl>
						</PanelRow>
					) }
					<PanelRow>
						<SelectControl
							label="Default Selected Club"
							options={ getClubOptionsSelected() }
							value={ defaultClub }
							onChange={ ( newValue ) => {
								if ( newValue ) {
									setAttributes( {
										defaultClub: parseInt( newValue ),
									} );
								} else {
									setAttributes( { defaultClub: null } );
								}
							} }
							help={
								defaultClub
									? 'The form will pre-select this club when loading.'
									: 'The user must select a club. If only one club is available, the form will pre-select that club.'
							}
						/>
					</PanelRow>
				</PanelBody>
				<PanelBody title="Display" initialOpen={ false }>
					<fieldset>
						<TextControl
							label="Change Headline"
							help={
								'When defined, overwrites the headline of club signup.'
							}
							value={ headline }
							onChange={ ( newValue ) => {
								if ( newValue.trim() ) {
									setAttributes( { headline: newValue } );
								} else {
									setAttributes( { headline: null } );
								}
							} }
						/>
					</fieldset>
				</PanelBody>
			</InspectorControls>
			<div className="vs-injector-block-editor-content">
				<div className="vs-injector-block-club-registration">
					<div className="vs-injector-block-header">
						<img
							src={ vsIcon }
							className="vs-icon"
							alt="Vinoshipper"
						/>
						<h2>Club Registration</h2>
					</div>
					{ targetAccountId && (
						<div>
							<ul>
								{ headline && (
									<li>Headline: { `"${ headline }"` }</li>
								) }
								{ defaultClub && (
									<li>
										Default Club:{ ' ' }
										{ getClubDetails( defaultClub )?.name }
									</li>
								) }
								{ allow.length > 0 && (
									<li>
										Allowed Clubs:
										<ul>
											{ allow.map( ( clubId, idx ) => (
												<li key={ idx }>
													{ getClubDetails( clubId )
														?.name ?? clubId }
												</li>
											) ) }
										</ul>
									</li>
								) }
							</ul>
							<p>
								View page to see the fully rendered component.
							</p>
						</div>
					) }
					{ ! targetAccountId && (
						<div className="vs-injector-block-error">
							<h3>Vinoshipper Account ID: Missing</h3>
							<p>
								Please set your Vinoshipper Account ID in the
								Vinoshipper settings before using any
								Vinoshipper blocks.
							</p>
						</div>
					) }
				</div>
			</div>
		</div>
	);
}
