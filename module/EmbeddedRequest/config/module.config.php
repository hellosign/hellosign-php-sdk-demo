<?php
return array(
    'controllers' => array(
        'invokables' => array(
            'EmbeddedRequest\Controller\EmbeddedRequest' => 'EmbeddedRequest\Controller\EmbeddedRequestController',
        ),
    ),
    'router' => array(
        'routes' => array(
            'embedded-request' => array(
                'type'    => 'segment',
                'options' => array(
                    'route'    => '/embedded-request',
                    'defaults' => array(
                        'controller' => 'EmbeddedRequest\Controller\EmbeddedRequest',
                        'action'     => 'index',
                    ),
                ),
            ),
        ),
    ),
    'view_manager' => array(
        'template_path_stack' => array(
            'embedded-request' => __DIR__ . '/../view',
        ),
    ),
);
