<?php
/**
 * Created by PhpStorm.
 * User: blinov
 * Date: 16.08.16
 * Time: 16:04
 */

namespace Mailer\Factory;

use Doctrine\Common\Proxy\Exception\InvalidArgumentException;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

use Mailer\Grid\MailGrid;

class MailGridFactory implements FactoryInterface
{
    public function createService(ServiceLocatorInterface $sm)
    {
        $config = $sm->get('config');

        if (!isset($config['ZfcDatagrid'])) {
            throw new InvalidArgumentException('Config key "ZfcDatagrid" is missing');
        }

        /* @var $application \Zend\Mvc\Application */
        $application = $sm->get('application');

        $grid = new MailGrid();
        $grid->setServiceLocator($sm);
        $grid->setOptions($config['ZfcDatagrid']);
        $grid->setMvcEvent($application->getMvcEvent());
        if ($sm->has('translator') === true) {
            $grid->setTranslator($sm->get('translator'));
        }
        $grid->init();
        $grid->setToolbarTemplate('');

        return $grid;
    }
}
