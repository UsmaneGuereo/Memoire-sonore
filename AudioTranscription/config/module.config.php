<?php
namespace AudioTranscription;

return [
    'controllers' => [
        'factories' => [
            Controller\IndexController::class => Controller\IndexControllerFactory::class,
        ],
    ],
    'router' => [
        'routes' => [
            'admin' => [
                'child_routes' => [
                    'audio-transcription' => [
                        'type' => 'Literal',
                        'options' => [
                            'route' => '/audio-transcription',
                            'defaults' => [
                                '__NAMESPACE__' => 'AudioTranscription\Controller',
                                'controller' => Controller\IndexController::class,
                                'action' => 'index',
                            ],
                        ],
                        'may_terminate' => true,
                        'child_routes' => [
                            'transcribe' => [
                                'type' => 'Literal',
                                'options' => [
                                    'route' => '/transcribe',
                                    'defaults' => [
                                        'action' => 'transcribe',
                                    ],
                                ],
                            ],
                        ],
                    ],
                ],
            ],
        ],
    ],
    'view_manager' => [
        'template_path_stack' => [
            __DIR__ . '/../view',
        ],
    ],
    'navigation' => [
        'AdminModule' => [
            [
                'label' => 'Audio Transcription',
                'route' => 'admin/audio-transcription',
                'resource' => Controller\IndexController::class,
            ],
        ],
    ],
];