/**
 * Retrieves the translation of text.
 *
 * @see https://developer.wordpress.org/block-editor/reference-guides/packages/packages-i18n/
 */
import { __ } from '@wordpress/i18n';

/**
 * React hook that is used to mark the block wrapper element.
 * It provides all the necessary props like the class name.
 *
 * @see https://developer.wordpress.org/block-editor/reference-guides/packages/packages-block-editor/#useblockprops
 */
import {
	InspectorControls,
	InnerBlocks,
	useBlockProps,
} from '@wordpress/block-editor';
import { PanelBody, SelectControl, TextControl } from '@wordpress/components';
import { useSelect } from '@wordpress/data';
import { useEffect, useState } from '@wordpress/element';
import ServerSideRender from '@wordpress/server-side-render';
/**
 * Lets webpack process CSS, SASS or SCSS files referenced in JavaScript files.
 * Those files can contain any CSS code that gets applied to the editor.
 *
 * @see https://www.npmjs.com/package/@wordpress/scripts#using-css
 */
import './editor.scss';

/**
 * The edit function describes the structure of your block in the context of the
 * editor. This represents what the editor will render when the block is used.
 *
 * @see https://developer.wordpress.org/block-editor/reference-guides/block-api/block-edit-save/#edit
 *
 * @param {Object} root0
 * @param {Object} root0.attributes
 * @param {Function} root0.setAttributes
 */
export default function Edit( { attributes, setAttributes } ) {
	const blockProps = useBlockProps();

	const postTypes = useSelect(
		( select ) => select( 'core' ).getPostTypes( { per_page: -1 } ) || [],
		[]
	);
	const taxonomies = useSelect(
		( select ) => select( 'core' ).getTaxonomies() || [],
		[]
	);
	const [ statusOptions, setStatusOptions ] = useState( [] );

	useEffect( () => {
		if ( window.StellarQueryLoopBlockPostStatusOptions ) {
			setStatusOptions( window.StellarQueryLoopBlockPostStatusOptions );
		}
	}, [] );

	return (
		<>
			<InspectorControls>
				<PanelBody
					title={ __( 'Query Settings', 'stellar-query-loop-block' ) }
				>
					<SelectControl
						label="Post Type"
						value={ attributes.postType }
						options={ postTypes.map( ( type ) => ( {
							label: type.name,
							value: type.slug,
						} ) ) }
						onChange={ ( val ) =>
							setAttributes( { postType: val } )
						}
					/>

					{ statusOptions.length > 0 && (
						<fieldset>
							<legend>
								{ __(
									'Post Status',
									'stellar-query-loop-block'
								) }
							</legend>
							{ statusOptions.map( ( status ) => (
								<label key={ status.value }>
									<input
										type="checkbox"
										checked={ attributes.postStatus.includes(
											status.value
										) }
										onChange={ ( e ) => {
											const updated = [
												...attributes.postStatus,
											];
											if ( e.target.checked ) {
												updated.push( status.value );
											} else {
												const i = updated.indexOf(
													status.value
												);
												if ( i > -1 ) {
													updated.splice( i, 1 );
												}
											}
											setAttributes( {
												postStatus: updated,
											} );
										} }
									/>
									{ status.label }
								</label>
							) ) }
						</fieldset>
					) }

					<SelectControl
						label="Author Filter"
						value={ attributes.authorType }
						options={ [
							{ label: 'All Authors', value: 'all' },
							{ label: 'Current User', value: 'current' },
							{ label: 'Specific Author ID', value: 'specific' },
						] }
						onChange={ ( val ) =>
							setAttributes( { authorType: val } )
						}
					/>

					{ attributes.authorType === 'specific' && (
						<TextControl
							label="Author ID"
							value={ attributes.specificAuthor }
							onChange={ ( val ) =>
								setAttributes( { specificAuthor: val } )
							}
						/>
					) }

					<SelectControl
						label="Taxonomy"
						value={ attributes.taxonomy }
						options={ [ { label: 'None', value: '' } ].concat(
							taxonomies.map( ( t ) => ( {
								label: t.name,
								value: t.slug,
							} ) )
						) }
						onChange={ ( val ) =>
							setAttributes( { taxonomy: val } )
						}
					/>

					{ attributes.taxonomy && (
						<TextControl
							label="Taxonomy Term Slug"
							value={ attributes.taxonomyTerm }
							onChange={ ( val ) =>
								setAttributes( { taxonomyTerm: val } )
							}
						/>
					) }

					<TextControl
						label="Posts Per Page"
						value={ attributes.postsPerPage }
						onChange={ ( val ) =>
							setAttributes( { postsPerPage: val } )
						}
					/>

					<SelectControl
						label="Order By"
						value={ attributes.orderBy }
						options={ [
							{ label: 'Date', value: 'date' },
							{ label: 'Title', value: 'title' },
							{ label: 'Modified', value: 'modified' },
						] }
						onChange={ ( val ) =>
							setAttributes( { orderBy: val } )
						}
					/>

					<SelectControl
						label="Order"
						value={ attributes.order }
						options={ [
							{ label: 'Descending', value: 'DESC' },
							{ label: 'Ascending', value: 'ASC' },
						] }
						onChange={ ( val ) => setAttributes( { order: val } ) }
					/>
				</PanelBody>
			</InspectorControls>

			<div { ...blockProps }>
				<ServerSideRender
					block="stellar/query-loop-block"
					attributes={ attributes }
				/>
				<InnerBlocks />
			</div>
		</>
	);
}
