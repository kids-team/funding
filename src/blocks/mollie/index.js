/**
 * Internal dependencies
 */
 import edit from './edit';
 import icon from './icon';
 import metadata from './block.json';
 
 /**
  * Wordpress dependencies
  */
 const { withColors } = wp.blockEditor;
 
 
 import './style.scss';
 import './editor.scss';
 
 const { __ } = wp.i18n; 
 
 /**
  * Block constants
  */
 const { name, category, attributes, api_version } = metadata;
 
 const settings = {
	 title: __( 'Donate with Mollie', 'funding' ),
	 description: __( 'Shows a donation form with direct online payment', 'funding' ),
	 icon,
	 attributes,
	 apiVersion: api_version,
	 keywords: [
		 'funding',
		 __( 'funding', 'funding' ),
		 __( 'donation', 'funding' ),
	 ],

	 edit,
	 save: () => { return null; }
 };
 
 
 export { name, category, metadata, settings };