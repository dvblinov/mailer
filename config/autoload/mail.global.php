<?php

return array(
    'imap' => array(
        'connection' => array(
            'hostname' => 'imap.gmail.com',
            'port' => '993',
            'username' => 'username@gmail.com',
            'password' => 'password!',
            'flags' => '/imap/ssl'
        )
    ),
    'smtp' => array(
        'from' => 'username@gmail.com',
        'connection' => array(
            'hostname' => 'smtp.gmail.com',
            'port' => '587',
            'username' => 'username@gmail.com',
            'password' => 'password',
            'connection_class' => 'login',
            'ssl' => 'tls'
        )
    ),
);