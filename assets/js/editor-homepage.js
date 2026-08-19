( function ( wp, config ) {
	'use strict';

	var el = wp.element.createElement;
	var Fragment = wp.element.Fragment;
	var registerBlockType = wp.blocks.registerBlockType;
	var InspectorControls = wp.blockEditor.InspectorControls;
	var PanelBody = wp.components.PanelBody;
	var TextControl = wp.components.TextControl;
	var TextareaControl = wp.components.TextareaControl;
	var Notice = wp.components.Notice;
	var ServerSideRender = wp.serverSideRender;

	function fieldLabel( key ) {
		return key.replace( /_/g, ' ' ).replace( /\b\w/g, function ( letter ) { return letter.toUpperCase(); } );
	}

	function isLongField( key ) {
		return /(_intro|_text|_lead|_description|_note|_options|_success|_error|_stamp)$/.test( key );
	}

	function quickLink( href, label ) {
		return el( 'a', { className: 'components-button is-secondary', href: href }, label );
	}

	registerBlockType( 'feast/homepage', {
		apiVersion: 3,
		title: 'Feast visual homepage',
		description: 'The coded Feast homepage with safe, visual content controls.',
		icon: 'food',
		category: 'design',
		attributes: config.attributes,
		supports: { html: false, multiple: false, reusable: false },
		edit: function ( props ) {
			var panels = Object.keys( config.groups ).map( function ( groupTitle ) {
				var controls = config.groups[ groupTitle ].map( function ( key ) {
					var Control = isLongField( key ) ? TextareaControl : TextControl;
					return el( Control, {
						key: key,
						label: fieldLabel( key ),
						value: props.attributes[ key ] || '',
						onChange: function ( value ) {
							var update = {};
							update[ key ] = value;
							props.setAttributes( update );
						}
					} );
				} );
				return el( PanelBody, { key: groupTitle, title: groupTitle, initialOpen: false }, controls );
			} );

			return el( Fragment, null,
				el( InspectorControls, null,
					el( PanelBody, { title: 'Manage dynamic content', initialOpen: true },
						el( 'p', null, 'These open the collections that feed the live preview.' ),
						el( 'div', { className: 'feast-editor-links' },
							quickLink( config.links.hero, 'Hero offers' ),
							quickLink( config.links.packages, 'Catering packages' ),
							quickLink( config.links.menu, 'Menu dishes' ),
							quickLink( config.links.gallery, 'Gallery images' ),
							quickLink( config.links.copy, 'Other website copy' ),
							quickLink( config.links.design, 'Site-wide styling' )
						)
					),
					panels
				),
				el( 'div', { className: 'feast-homepage-editor-shell' },
					el( Notice, { status: 'info', isDismissible: false }, 'Select this block, then use the right sidebar to edit section text. Photography and repeating content stay connected to Feast CMS.' ),
					el( ServerSideRender, { block: 'feast/homepage', attributes: props.attributes, httpMethod: 'POST' } )
				)
			);
		},
		save: function () { return null; }
	} );
}( window.wp, window.feastHomepageEditor ) );
