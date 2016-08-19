<?php
return array(
    'router' => array(
        'routes' => array(
            'box' => array(
                'type' => 'segment',
                'options' => array(
                    'route' => '/box',
                ),
                'may_terminate' => true,
                'child_routes' => array(
                    'box-type' => array(
                        'type' => 'segment',
                        'options' => array(
                            'route' => '[/:type]',
                            'constraints' => array(
                                'type' => '[a-zA-Z][a-zA-Z0-9_-]*',
                            ),
                            'defaults' => array(
                                'controller' => 'Mailer\Controller\Box',
                                'action' => 'box',
                            ),
                        ),
                    ),
                ),
            ),
            'mail' => array(
                'type' => 'segment',
                'options' => array(
                    'route'    => '/mail[/:action][/:id]',
                    'constraints' => array(
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id'     => '[0-9]+',
                    ),
                    'defaults' => array(
                        'controller' => 'Mailer\Controller\Mail',
                        'action'     => 'index',
                    ),
                )
            ),
            'attachment' => array(
                'type' => 'segment',
                'options' => array(
                    'route'    => '/attachment[/:action][/:id]',
                    'constraints' => array(
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id'     => '[0-9]+',
                    ),
                    'defaults' => array(
                        'controller' => 'Mailer\Controller\Attachment',
                        'action'     => 'index',
                    ),
                )
            ),
        )
    ),
    'console' => array(
        'router' => array(
            'routes' => array(
                'getMails' => array(
                    'options' => array(
                        'route'    => 'get mails',
                        'defaults' => array(
                            'controller' => 'Mailer\Controller\Mail',
                            'action'     => 'getMailsConsole'
                        )
                    )
                )
            )
        )
    ),
);