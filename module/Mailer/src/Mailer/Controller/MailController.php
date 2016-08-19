<?php
/**
 * Created by PhpStorm.
 * User: blinov
 * Date: 16.08.16
 * Time: 13:31
 */

namespace Mailer\Controller;


use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Zend\View\Model\JsonModel;
use Zend\Http\Request;
use Zend\Console\Request as ConsoleRequest;

use Mailer\Service\MailService;
use Mailer\Service\SyncService;
use Mailer\Form\MailForm;

class MailController extends AbstractActionController
{
    public function viewAction()
    {
        $id  = $this->params('id', null);
        $model = new ViewModel(['mail' => $this->getMailService()->getMail($id)]);
        $model->setTerminal(true);
        return $model;
    }

    public function removeAction()
    {
        $id  = $this->params('id', null);
        $result = $this->getMailService()->removeMail($id);
        return new JsonModel([
            'status' => $result ? 0 : -1
        ]);
    }

    public function getMailsAction()
    {
        /** @var SyncService $syncService */
        $syncService = $this->getServiceLocator()->get('Mailer\Service\Sync');
        $result = $syncService->getMails();
        return new JsonModel([
            'status' => $result ? 0 : -1,
        ]);
    }

    public function getMailsConsoleAction()
    {
        $request = $this->getRequest();
        if (!$request instanceof ConsoleRequest) {
            throw new \Exception('Данный метод доступен только через консоль');
        }
        /** @var SyncService $syncService */
        $syncService = $this->getServiceLocator()->get('Mailer\Service\Sync');
        $syncService->getMails();
    }

    public function newAction()
    {
        /** @var MailForm $form */
        $form = $this->getServiceLocator()->get('FormElementManager')->get('Mailer\Form\MailForm');
        $model = new ViewModel(['form' => $form]);
        $model->setTemplate('mailer/mail/form.phtml');
        return $model;
    }

    public function sendAction()
    {
        /** @var Request $request */
        $request = $this->getRequest();
        /** @var MailForm $form */
        $form = $this->getServiceLocator()->get('FormElementManager')->get('Mailer\Form\MailForm');
        $form->setData($request->getPost()->toArray());
        $status = -2;
        if ($form->isValid()) {
            $result = $this->getMailService()->sendMail($form->getData());
            $status = $result ? 0 : -1;
        }
        $model = new ViewModel(['form' => $form]);
        $model->setTemplate('mailer/mail/form.phtml');
        $model->setTerminal(true);

        $viewRender = $this->getServiceLocator()->get('ViewRenderer');
        $html = $viewRender->render($model);

        return new JsonModel([
            'status' => $status,
            'html' => $html
        ]);
    }

    /**
     * @return MailService
     */
    protected function getMailService()
    {
        return $this->getServiceLocator()->get('Mailer\Service\Mail');
    }
}