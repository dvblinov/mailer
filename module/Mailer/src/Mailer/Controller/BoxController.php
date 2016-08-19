<?php
/**
 * Created by PhpStorm.
 * User: blinov
 * Date: 17.08.16
 * Time: 8:47
 */

namespace Mailer\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

use Mailer\Grid\MailGrid;

class BoxController extends AbstractActionController
{
    public function indexAction()
    {
        return new ViewModel();
    }

    public function boxAction()
    {
        $boxType = $this->params('type', null);
        /* @var MailGrid $grid */
        $grid = $this->getServiceLocator()->get('Mailer\Grid\MailGrid');
        $grid
            ->setBoxType($boxType)
            ->setCols()
            ->setActions()
            ->setSource()
            ->render();
        return $grid->getResponse();
    }
}