<?php
return [
    'controllers' => [
        'invokables' => [
            'WhisperTranscribe\Controller\Whisper' => 'WhisperTranscribe\Controller\WhisperController',
        ],
    ],
    'router' => [
        'routes' => [
            'admin' => [
                'child_routes' => [
                    'whisper' => [
                        'type' => \Laminas\Router\Http\Segment::class,
                        'options' => [
                            'route' => '/whisper[/:action]',
                            'defaults' => [
                                '__NAMESPACE__' => 'WhisperTranscribe\Controller',
                                'controller' => 'Whisper',
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
