/**
 * External dependencies
 */
import PropTypes from 'prop-types';

/**
 * WordPress dependencies
 */
const { serverSideRender: ServerSideRender } = wp;
import { sprintf, __ } from '@wordpress/i18n';
import { InspectorControls } from '@wordpress/block-editor';
import { PanelBody, ToggleControl } from '@wordpress/components';

const Edit = ({
	attributes: { content, toggleSwitch, amp_template_data },
	setAttributes,
}) => {
	return (
		<>
			<InspectorControls>
				<PanelBody title={__('Additional Statistics', 'block-scaffolding')}>
					<ToggleControl
						label={sprintf(
							__('%s', 'block-scaffolding'),
							'Display AMP template mode'
						)}
						checked={toggleSwitch}
						onChange={() => {
							setAttributes({ toggleSwitch: !toggleSwitch });

							wp.apiFetch({
								path: 'xwp/dynamic-block/amp_data/' + toggleSwitch,
							}).then((data) => {
								setAttributes({ amp_template_data: data });
							});
						}}
					></ToggleControl>
				</PanelBody>
			</InspectorControls>

			<>
				<ServerSideRender
					block={'xwp/dynamic-block'}
					attributes={{ content }}
					className='wp-block-name-dynamic-block'
				/>
			</>

			{!toggleSwitch ? (
				<div></div>
			) : (
				<>
					<ServerSideRender
						block={'xwp/dynamic-block'}
						attributes={{ amp_template_data }}
						className='wp-block-name-dynamic-block-template-data'
					/>
				</>
			)}
		</>
	);
};

Edit.propTypes = {
	attributes: PropTypes.shape({
		text: PropTypes.string,
		toggleSwitch: PropTypes.bool,
	}),
	setAttributes: PropTypes.func.isRequired,
};

export default Edit;
