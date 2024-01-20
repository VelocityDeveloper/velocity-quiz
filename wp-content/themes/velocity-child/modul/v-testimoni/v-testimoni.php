<?php

/**
 * @class vFLtestimonial
 */
class vFLtestimonial extends FLBuilderModule {

	/**
	 * @method __construct
	 */
	public function __construct() {
		parent::__construct(array(
			'name'          	=> __( 'Velocity Testimonial', 'fl-builder' ),
			'description'   	=> __( 'Display testimonials.', 'fl-builder' ),
			'category'      	=> __( 'Media', 'fl-builder' ),
			'editor_export' 	=> false,
			'partial_refresh'	=> true,
		));
	}
}

/**
 * Register the module and its form settings.
 */
FLBuilder::register_module('vFLtestimonial', array(
	'layout'        => array(
		'title'         => __( 'Testimonial ', 'fl-builder' ),
		'sections'      => array(
			'content'       => array(
				'title'         => __( 'Testimonial', 'fl-builder' ),
				'fields'        => array(
					't_columns'     => array(
						'type'         => 'form',
						'label'        => __('Testimonial Box', 'fl-builder'),
						'form'         => 't_column_form',
						'preview_text' => 'title',
						'multiple'     => true
					),
				),
			),
		),
	),
));

FLBuilder::register_settings_form('t_column_form', array(
	'title' => __( 'Add Testimonial', 'fl-builder' ),
	'tabs'  => array(
		'general'      => array(
			'title'         => __('General', 'fl-builder'),
			'sections'      => array(
				'title'       => array(
					'title'         => __( 'Title', 'fl-builder' ),
					'fields'        => array(
						'name'          => array(
							'type'          => 'text',
							'label'         => __('Name', 'fl-builder'),
						),
						'profession'          => array(
							'type'          => 'text',
							'label'         => __('Profession', 'fl-builder'),
						),
						'desc'          => array(
							'type'          => 'textarea',
							'label'         => __('Description', 'fl-builder'),
						),
					),
				),
			)
		),
	)
));
