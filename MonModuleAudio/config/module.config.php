<?php
return [
    'controllers' => [
        'invokables' => [
            'MonModuleAudio\Controller\Audio' => 'MonModuleAudio\Controller\AudioController',
        ],
    ],
    'router' => [
        'routes' => [
            'admin' => [
                'child_routes' => [
                    'monmodule-audio' => [
                        'type' => \Laminas\Router\Http\Segment::class,
                        'options' => [
                            'route' => '/monmodule-audio[/:action]',
                            'defaults' => [
                                '__NAMESPACE__' => 'MonModuleAudio\Controller',
                                'controller' => 'Audio',
                                'action' => 'index',
                            ],
                        ],
                    ],
                ],
            ],
        ],
    ],
    'view_manager' => [
        'template_path_stack' => [
            dirname(__DIR__) . '/view',
        ],
    ],
];
