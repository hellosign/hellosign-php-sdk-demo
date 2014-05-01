<?php
return array(
    'controllers' => array(
        'invokables' => array(
            'OAuth\Controller\OAuth' => 'OAuth\Controller\OAuthController',
        ),
    ),
    'router' => array(
        'routes' => array(
            'oauth' => array(
                'type'    => 'segment',
                'options' => array(
                    'route'    => '/oauth',
                    'defaults' => array(
                        'controller' => 'OAuth\Controller\OAuth',
                        'action'     => 'index',
                    ),
                ),
            ),
            'oauth_callback' => array(
                'type'    => 'segment',
                'options' => array(
                    'route'    => '/oauth_callback',
                    'defaults' => array(
                        'controller' => 'OAuth\Controller\OAuth',
                        'action'     => 'callback',
                    ),
                ),
            ),
        ),
    ),
    'view_manager' => array(
        'template_path_stack' => array(
            'oauth' => __DIR__ . '/../view',
        ),
    ),
);
