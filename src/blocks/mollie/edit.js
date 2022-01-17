import { __ } from '@wordpress/i18n'; 
import { useBlockProps } from '@wordpress/block-editor';
import { Icon } from '@wordpress/components'
import ServerSideRender from '@wordpress/server-side-render';
import icon from './icon';

const MollieEdit = ({ attributes, setAttributes }) => {

	const { preview } = attributes;

	const blockProps = useBlockProps({
		className: "funding-mollie"
	});

	setAttributes({preview: false})

	return (
		<div { ...blockProps }>
			<div className="components-placeholder is-large">
                <div className="components-placeholder__label">
                    <span className="block-editor-block-icon has-colors">
					<Icon className="ctx-row-icon" icon={icon}/>
			
                    </span>{__("Mollie Donations", "funding")}</div>
                
				<ServerSideRender
					block="funding/mollie"
					attributes={ {
						preview: true,
					} }
				/>
            </div>
		</div>
	)
}

export default MollieEdit
