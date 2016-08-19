<?php
/**
 * Created by PhpStorm.
 * User: blinov
 * Date: 16.08.16
 * Time: 13:25
 */
namespace Mailer;

class Module
{
    public function getConfig()
    {
        return include __DIR__ . '/config/module.config.php';
    }

    public function getServiceConfig()
    {
        return array(
            'factories' => array(
                'Mailer\Grid\MailGrid' => 'Mailer\Factory\MailGridFactory',
            ),
            'invokables' => array(
                'Mailer\Service\Mail' => 'Mailer\Service\MailService',
                'Mailer\Service\Imap' => 'Mailer\Service\ImapService',
                'Mailer\Service\Sync' => 'Mailer\Service\SyncService',
                'Mailer\Service\Attachment' => 'Mailer\Service\AttachmentService',
            )
        );
    }

    public function getAutoloaderConfig()
    {
        return array(
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                    __NAMESPACE__ => __DIR__ . '/src/' . __NAMESPACE__,
                ),
            ),
        );
    }
}
