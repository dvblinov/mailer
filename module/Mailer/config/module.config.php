<?php

$route = include(__DIR__ . '/route.config.php');
$assets = include(__DIR__ .'/assetic.config.php');
return array_merge($route, $assets,
array(
    'doctrine' => array(
        'driver' => array(
            'mailerEntity' => array(
                'class' => 'Doctrine\ORM\Mapping\Driver\AnnotationDriver',
                'cache' => 'array',
                'paths' => __DIR__ . '/../src/Mailer/Entity',
            ),
            'orm_default' => array(
                'drivers' => array(
                    'Mailer\Entity' => 'mailerEntity'
                ),
            ),
        ),
    ),
    'controllers' => array(
        'invokables' => array(
            'Mailer\Controller\Mail' => 'Mailer\Controller\MailController',
            'Mailer\Controller\Box' => 'Mailer\Controller\BoxController',
            'Mailer\Controller\Attachment' => 'Mailer\Controller\AttachmentController',
        )
    ),
    'view_manager' => array(
        'template_path_stack' => array(
            __DIR__ . '/../view',
        ),
        'strategies' => array(
            'ViewJsonStrategy',
        ),
    ),
    'view_helpers' => array(
        'invokables' => array(
            'mailBoxList' => 'Mailer\View\Helper\MailBoxList',
        )
    ),
));
