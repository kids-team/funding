import { __ } from '@wordpress/i18n'; 
import { useBlockProps } from '@wordpress/block-editor';
import { Icon, TextControl } from '@wordpress/components';
import icon from './icon';

import './style.scss';

const TransferEdit = ({ attributes, setAttributes }) => {

	const {
		eid
	} = attributes;

	const blockProps = useBlockProps({
		className: "funding-transfer"
	});

	return (
		<div { ...blockProps }>
			<div className="components-placeholder is-large">
                <div className="components-placeholder__label">
                    {__("Altruja Donations", "funding")}</div>

					<TextControl 
						label={__("Donator URL part")}
						value={eid}
						onChange={(value) => {setAttributes({eid: value})}}
					/>
            </div>
		</div>
	)
}

export default TransferEdit
