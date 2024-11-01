/**
 * Vinoshipper Injector for WordPress: Announcement, Edit
 *
 * @package
 */

import { useBlockProps, InspectorControls } from '@wordpress/block-editor';
import { PanelBody } from '@wordpress/components';
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
export default function Edit( {} ) {
	return (
		<div { ...useBlockProps() }>
			<InspectorControls>
				<PanelBody title="Settings" initialOpen={ true }>
					<fieldset>
						<p>
							Announcement will render only when the defined in{ ' ' }
							<a
								href="https://vinoshipper.com/ui/producer/products/announcement"
								target="_blank"
								rel="noreferrer"
							>
								&quot;Products -&gt; Announcement&quot;
							</a>{ ' ' }
							using your Vinoshipper Producer&apos;s Admin access.
						</p>
					</fieldset>
				</PanelBody>
			</InspectorControls>
			<div className="vs-injector-block-editor-content">
				<div className="vs-injector-block-announcement">
					<div className="vs-injector-block-header">
						<img
							src={ vsIcon }
							className="vs-icon"
							alt="Vinoshipper"
						/>
						<h2>Announcement</h2>
					</div>
					<p>View page to see the fully rendered component.</p>
					<p>
						Announcement will render <em>only</em> when defined in
						the Vinoshipper Producer Admin. See block settings for
						more details.
					</p>
				</div>
			</div>
		</div>
	);
}
