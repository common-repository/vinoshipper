/**
 * Vinoshipper Injector for WordPress: Core Resources
 *
 * @package
 */

import { createReduxStore, register } from '@wordpress/data';
import { updateCategory } from '@wordpress/blocks';
import apiFetch from '@wordpress/api-fetch';

/**
 * Lets webpack process CSS, SASS or SCSS files referenced in JavaScript files.
 * Those files can contain any CSS code that gets applied to the editor.
 *
 * @see https://www.npmjs.com/package/@wordpress/scripts#using-css
 */
import './index.scss';

class VsInjectorCore {
	constructor() {
		this.vs_injector_init();
		this.initProductsStore();
		this.initClubsStore();
	}

	initProductsStore() {
		const initialState = {
			producer: null,
			products: [],
			productsOptions: [],
			states: [],
		};

		const actions = {
			setProducts: ( productList ) => ( {
				type: 'SET_PRODUCTS',
				payload: productList,
			} ),
			setAll: ( endpointUpdate ) => ( {
				type: 'SET_ALL',
				payload: endpointUpdate,
			} ),
		};

		const vsInjectorProductItemStore = createReduxStore(
			'vsInjectorProductItemStore',
			{
				initialState,
				actions,
				reducer( state = initialState, action ) {
					if ( action.type === 'SET_PRODUCTS' ) {
						return { ...state, products: action.payload };
					}
					if ( action.type === 'SET_ALL' ) {
						const productsAsOptions = action.payload.products.map(
							( product ) => {
								const element = document.createElement( 'div' );
								element.innerHTML = product.displayName;
								return {
									label: element.textContent,
									value: product.id,
									disabled: false,
								};
							}
						);
						productsAsOptions.unshift( {
							label: 'Select a Product',
							value: null,
							disabled: false,
						} );
						return {
							...state,
							producer: action.payload.producer,
							products: action.payload.products,
							states: action.payload.states,
							productsOptions: productsAsOptions,
						};
					}
					return state;
				},
				selectors: {
					getProducer( state ) {
						return state.producer;
					},
					getProducts( state ) {
						return state.products;
					},
					getProductsOptions( state ) {
						return state.productsOptions;
					},
					getStates( state ) {
						return state.states;
					},
					getAll( state ) {
						return state;
					},
					getProductById( state, productId ) {
						const productIndex = state.products.findIndex(
							( a ) => a.id === productId
						);
						if ( productIndex >= 0 ) {
							return state.products[ productIndex ];
						}
						return null;
					},
				},
				resolvers: {
					async getAll() {
						return await apiFetch( {
							method: 'GET',
							path: '/vinoshipper-injector/v1/products',
						} )
							.then( ( results ) => {
								return actions.setAll( results );
							} )
							.catch( ( errors ) => {
								return Promise.reject( errors );
							} );
					},
				},
			}
		);
		register( vsInjectorProductItemStore );
	}

	initClubsStore() {
		const initialState = {
			availableClubs: [],
			clubsAsOptions: [],
		};

		const actions = {
			setAll: ( endpointUpdate ) => ( {
				type: 'SET_ALL',
				payload: endpointUpdate,
			} ),
		};

		const vsInjectorClubsStore = createReduxStore( 'vsInjectorClubsStore', {
			initialState,
			actions,
			reducer( state = initialState, action ) {
				if ( action.type === 'SET_ALL' ) {
					return {
						...state,
						availableClubs: action.payload.availableClubs,
					};
				}
				return state;
			},
			selectors: {
				getAll( state ) {
					return state;
				},
			},
			resolvers: {
				async getAll() {
					return await apiFetch( {
						method: 'GET',
						path: '/vinoshipper-injector/v1/clubs',
					} )
						.then( ( results ) => {
							return actions.setAll( results );
						} )
						.catch( ( errors ) => {
							return Promise.reject( errors );
						} );
				},
			},
		} );
		register( vsInjectorClubsStore );
	}

	vs_generate_logo() {
		return (
			<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1024 1024">
				<defs>
					<style>
						{
							'.vs-logo-gradient{fill:url(#vs-logo-linear-gradient);}'
						}
					</style>
					<linearGradient
						id="vs-logo-linear-gradient"
						x1="412.34"
						y1="681.61"
						x2="678.24"
						y2="458.49"
						gradientUnits="userSpaceOnUse"
					>
						<stop offset="0" stopColor="#fff" stopOpacity="0.35" />
						<stop
							offset="0.08"
							stopColor="#fff"
							stopOpacity="0.46"
						/>
						<stop
							offset="0.21"
							stopColor="#fff"
							stopOpacity="0.63"
						/>
						<stop
							offset="0.34"
							stopColor="#fff"
							stopOpacity="0.76"
						/>
						<stop
							offset="0.47"
							stopColor="#fff"
							stopOpacity="0.87"
						/>
						<stop
							offset="0.6"
							stopColor="#fff"
							stopOpacity="0.94"
						/>
						<stop
							offset="0.74"
							stopColor="#fff"
							stopOpacity="0.99"
						/>
						<stop offset="0.89" stopColor="#fff" />
					</linearGradient>
				</defs>
				<circle fill="#8031a7" cx="512" cy="512" r="512" />
				<path
					fill="#ffffff"
					d="M512.58,651.42,405.75,246A40,40,0,0,0,367,216.16H323.74a40.05,40.05,0,0,0-38.67,50.5L424.21,781.25a67.22,67.22,0,0,0,64.88,49.67h47a67.15,67.15,0,0,0,53.5-26.61C553.43,803,534.37,734.91,512.58,651.42Z"
				/>
				<path
					className="vs-logo-gradient"
					d="M701.44,216.16H658.15A40.05,40.05,0,0,0,619.42,246L512.58,651.42c21.79,83.49,40.85,151.57,77,152.89A68.32,68.32,0,0,0,601,781.25L740.1,266.66A40.05,40.05,0,0,0,701.44,216.16Z"
				/>
			</svg>
		);
	}

	vs_injector_init() {
		updateCategory( 'vinoshipper', { icon: this.vs_generate_logo() } );
	}
}

const instance = new VsInjectorCore();
export { instance as VsInjectorCore };
