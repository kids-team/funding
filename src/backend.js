/**
 * Wordpress dependencies.
 */
 import { registerBlockType } from '@wordpress/blocks';

 /**
  * Blocks dependencies.
  */

 import * as mollie from './blocks/mollie';
 import * as transfer from './blocks/transfer';
  
 
 const registerBlock = ( block ) => {
	 if ( ! block ) {
		 return;
	 }
 
	 const { name, category, settings } = block;
 
	 registerBlockType( name, {
		 category: category,
		 ...settings,
	 } );
 };
 
 export const registerBlocks = () => {
	 [
		 mollie,
		 transfer
	 ].forEach( registerBlock );
 };
 
 registerBlocks();