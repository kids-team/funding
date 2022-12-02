/**
 * Internal dependencies
 */
 import edit from './edit';
 import icon from './icon';
 import metadata from './block.json';
 
 /**
  * Wordpress dependencies
  */
 
 import './editor.scss';
 
 const { __ } = wp.i18n; 
 
 /**
  * Block constants
  */
 const { name, category, attributes, api_version } = metadata;
 
 const settings = {
	 title: __( 'Donate with Bank Transfer', 'funding' ),
	 description: __( 'Shows a donation form with a QR-Code for manual donation', 'funding' ),
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