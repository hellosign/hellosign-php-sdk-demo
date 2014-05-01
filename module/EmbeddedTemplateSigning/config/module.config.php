<?php
return array(
    'controllers' => array(
        'invokables' => array(
            'EmbeddedTemplateSigning\Controller\EmbeddedTemplateSigning' => 'EmbeddedTemplateSigning\Controller\EmbeddedTemplateSigningController',
        ),
    ),
    'router' => array(
        'routes' => array(
            'embedded-template-signing' => array(
                'type'    => 'segment',
                'options' => array(
                    'route'    => '/embedded-template-signing[/:id]',
                    'constraints' => array(
                        'id'     => '[a-zA-Z][a-zA-Z0-9_-]*',
                    ),
                    'defaults' => array(
                        'controller' => 'EmbeddedTemplateSigning\Controller\EmbeddedTemplateSigning',
                        'action'     => 'index',
                    ),
                ),
            ),
        ),
    ),
    'view_manager' => array(
        'template_path_stack' => array(
            'embedded-template-signing' => __DIR__ . '/../view',
        ),
    ),
);
