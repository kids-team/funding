import { useBlockProps } from '@wordpress/block-editor';
import { Icon } from '@wordpress/components';
import { __ } from '@wordpress/i18n';
import icon from './icon';

const MollieEdit = (props) => {
	const blockProps = useBlockProps({
		className: 'funding-mollie',
	});

	return (
		<div {...blockProps}>
			<div className="components-placeholder is-large">
				<div className="components-placeholder__label">
					<span className="block-editor-block-icon has-colors">
						<Icon className="ctx-row-icon" icon={icon} />
					</span>
					{__('Mollie Donations', 'funding')}
				</div>
			</div>
		</div>
	);
};

export default MollieEdit;
