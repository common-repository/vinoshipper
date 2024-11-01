import { InspectorControls, useBlockProps } from '@wordpress/block-editor';
import { useSelect } from '@wordpress/data';
import { PanelBody, ToggleControl, SelectControl } from '@wordpress/components';
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
	const { productId, accountId, cards, image, descForce } = attributes;

	let targetAccountId =
		wp.data.select( 'core' ).getSite().vs_injector_account_id ?? null;
	if ( accountId ) {
		targetAccountId = accountId;
	}

	const { producer, products, productsOptions } = useSelect( ( select ) => {
		return {
			...select( 'vsInjectorProductItemStore' ).getAll(),
		};
	} );

	function getProductOptions() {
		if ( productsOptions.length > 0 ) {
			return productsOptions;
		}
		const returnOptionsDefault = [
			{
				label: 'No Account ID Found',
				value: '0',
				disabled: true,
			},
		];
		if ( productId ) {
			returnOptionsDefault.push( {
				label: `Product ID #${ productId }`,
				value: productId,
				disabled: false,
			} );
		}
		return returnOptionsDefault;
	}

	function getCurrentProduct() {
		if ( productId ) {
			const returnProduct = products.find( ( product ) => {
				return product.id === productId;
			} );
			if ( returnProduct ) {
				return returnProduct;
			}
			return {
				id: productId,
				displayName: null,
			};
		}
		return {
			id: null,
			displayName: null,
		};
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
				<PanelBody title="Settings">
					<fieldset>
						<SelectControl
							label="Product"
							options={ getProductOptions() }
							value={ productId }
							onChange={ ( newValue ) => {
								if ( newValue ) {
									setAttributes( {
										productId: parseInt( newValue ),
									} );
								} else {
									setAttributes( { productId: null } );
								}
							} }
							help={
								<div>
									Products associated to:
									<br />
									{ getProducer().name } (#
									{ getProducer().id })
								</div>
							}
						/>
					</fieldset>
				</PanelBody>
				<PanelBody title="Display">
					<fieldset>
						<SelectControl
							label="Catalog Layout"
							options={ [
								{ label: 'List', value: 'list' },
								{ label: 'Cards', value: 'cards' },
							] }
							value={ cards }
							help={
								<div>
									See{ ' ' }
									<a
										href="https://developer.vinoshipper.com/docs/injector-product-catalog-layouts"
										target="_blank"
										rel="noreferrer"
									>
										Product Catalog -&gt; Layouts
									</a>{ ' ' }
									for more information.
								</div>
							}
							onChange={ ( newValue ) => {
								setAttributes( { cards: newValue } );
							} }
						/>
						<ToggleControl
							label="Display Product Image"
							help={
								image
									? 'Display the Products image.'
									: 'Do not display the Products image.'
							}
							checked={ image }
							onChange={ ( newValue ) => {
								setAttributes( { image: newValue } );
							} }
						/>
						<ToggleControl
							label="Force Description"
							help={
								descForce
									? 'Always show the full description of product and not render the "Show/Hide Description" actions, regardless of the layout and the width of the element.'
									: 'Render "Show/Hide Description", except when in list layout and the element is larger than 504px.'
							}
							checked={ descForce }
							onChange={ ( newValue ) => {
								setAttributes( { descForce: newValue } );
							} }
						/>
					</fieldset>
				</PanelBody>
			</InspectorControls>
			<div className="vs-injector-block-editor-content">
				<div className="vs-injector-block-product-item">
					<div className="vs-injector-block-header">
						<img
							src={ vsIcon }
							className="vs-icon"
							alt="Vinoshipper"
						/>
						<h2>Product Item</h2>
					</div>
					{ targetAccountId && (
						<div>
							<h3>
								{ getCurrentProduct().displayName && (
									<span
										dangerouslySetInnerHTML={ {
											__html: getCurrentProduct()
												.displayName,
										} }
									></span>
								) }
								{ ! getCurrentProduct().displayName &&
									getCurrentProduct().id && (
										<span>
											Product: #
											{ getCurrentProduct()?.id }
										</span>
									) }
								{ ! productId && (
									<strong>Warning: No Product Set!</strong>
								) }
							</h3>
							<ul>
								{ cards === 'cards' && (
									<li>Display in the Cards Layout.</li>
								) }
								{ cards === 'list' && (
									<li>Display in the List Layout.</li>
								) }
								{ ! image && (
									<li>Will not display the product image.</li>
								) }
								{ descForce && (
									<li>Always show full description.</li>
								) }
							</ul>
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
