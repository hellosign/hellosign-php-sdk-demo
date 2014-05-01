<?php
return array(
    'controllers' => array(
        'invokables' => array(
            'EmbeddedSigning\Controller\EmbeddedSigning' => 'EmbeddedSigning\Controller\EmbeddedSigningController',
        ),
    ),
    'router' => array(
        'routes' => array(
            'embedded-signing' => array(
                'type'    => 'segment',
                'options' => array(
                    'route'    => '/embedded-signing',
                    'defaults' => array(
                        'controller' => 'EmbeddedSigning\Controller\EmbeddedSigning',
                        'action'     => 'index',
                    ),
                ),
            ),
        ),
    ),
    'view_manager' => array(
        'template_path_stack' => array(
            'embedded-signing' => __DIR__ . '/../view',
        ),
    ),
);
