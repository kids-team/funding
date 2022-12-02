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
	 title: __( 'Donate with Altruja', 'funding' ),
	 description: __( 'Shows an iFrame with the Altruja donation form', 'funding' ),
	 icon,
	 attributes,
	 apiVersion: 2,
	 keywords: [
		 'funding',
		 __( 'funding', 'funding' ),
		 __( 'donation', 'funding' ),
	 ],

	 edit,
	 save: () => { return null; }
 };
 
 
 export { name, category, metadata, settings };