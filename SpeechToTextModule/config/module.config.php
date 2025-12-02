<?php declare(strict_types= 1);

namespace SpeechToTextModule;

return [
     'view_manager' => [
        'template_path_stack' => [
            dirname(__DIR__) . '/view',
        ],
    ],
    'controllers' => [
        'invokables' => [
            'SpeechToTextController\Controller\Index' => 'SpeechToTextController\Controller\IndexController',
        ],
    ],
];