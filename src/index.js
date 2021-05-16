/**
 * WordPress dependencies
 */
const { __ } = wp.i18n;
const { registerBlockType } = wp.blocks;

/**
 * Internal dependencies
 */
import Edit from './edit';

registerBlockType('xwp/dynamic-block', {
	title: __('Dynamic Block', 'block-scaffolding'),
	icon: 'smiley',
	category: 'common',
	attributes: {
		content: {
			type: 'string',
			default: true,
		},
		toggleSwitch: {
			type: 'boolean',
			default: false,
		},
		amp_template_data: {
			type: 'string',
			default: false,
		},
	},
	edit: Edit,
	save() {
		return null;
	},
});
