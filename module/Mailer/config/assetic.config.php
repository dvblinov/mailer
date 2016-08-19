<?php
return array(
    'assetic_configuration' => array(
        'controllers' => array(
            'Mailer\Controller\Box' => array(
                '@head_mail_actions_js',
            ),
            'Mailer\Controller\Mail' => array(
                '@head_mail_actions_js',
                'actions' => array(
                    'new' => array(
                        '@head_ckeditor_js',
                        '@head_send_mail_js',
                        '@head_send_mail_css',
                    )
                )
            ),
        ),
        'modules' => array(
            'public' => array(
                'root_path' => __DIR__ . '/../../../public',
                'collections' => array(
                    'head_ckeditor_js' => array(
                        'assets' => array(
                            'ckeditor/ckeditor.js',
                        )
                    )
                )
            ),
            'mailer' => array(
                'root_path' => __DIR__ . '/../assets',
                'collections' => array(
                    'head_mail_actions_js' => array(
                        'assets' => array(
                            'js/mail-actions.js',
                        )
                    ),
                    'head_send_mail_js' => array(
                        'assets' => array(
                            //'js/jquery.inputmask.bundle.min.js',
                            'js/bootstrap-tagsinput.min.js',
                            'js/jquery.form.min.js',
                            'js/mail-send-form.js'
                        )
                    ),
                    'head_send_mail_css' => array(
                        'assets' => array(
                            'css/bootstrap-tagsinput.css',
                            'css/mail-send-form.css'
                        )
                    ),
                )
            ),
        )
    ),
);