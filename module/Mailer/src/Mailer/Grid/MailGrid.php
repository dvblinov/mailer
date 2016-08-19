<?php
/**
 * Created by PhpStorm.
 * User: blinov
 * Date: 16.08.16
 * Time: 16:02
 */

namespace Mailer\Grid;

use Doctrine\ORM\EntityManager;

use Mailer\Entity\MailBox;
use ZfcDatagrid\Column\Action\Button;
use ZfcDatagrid\Column\Action;
use ZfcDatagrid\Datagrid;
use ZfcDatagrid\Column\Select;
use ZfcDatagrid\Column\Type\DateTime;

use Mailer\Repository\MailRepository;
use Mailer\Grid\Formatter\DateTimeFormatter;

class MailGrid extends Datagrid
{
    protected $boxType;

    /**
     * @param mixed $boxType
     * @return $this
     */
    public function setBoxType($boxType)
    {
        $this->boxType = $boxType;
        return $this;
    }

    /**
     * @return $this
     */
    public function setCols()
    {
        $dateTimeType = new DateTime('Y-m-d H:i:s', \IntlDateFormatter::SHORT, \IntlDateFormatter::SHORT);

        $col = new Select('id','m');
        $col->setIdentity();
        $col->setRowClickDisabled(true);
        $this->addColumn($col);

        $col = new Select('subject', 'm');
        $col->setLabel('Тема');
        $col->setRowClickDisabled(true);
        $this->addColumn($col);

        if ($this->boxType == MailBox::TYPE_SEND) {
            $col = new Select('to', 'm');
            $col->setLabel('Кому');
            $col->setRowClickDisabled(true);
            $this->addColumn($col);

            $col = new Select('sendDate', 'm');
            $col->setLabel('Дата');
            $col->setType($dateTimeType);
            $col->setFormatter(new DateTimeFormatter());
            $col->setRowClickDisabled(true);
            $this->addColumn($col);
        } else {
            $col = new Select('from', 'm');
            $col->setLabel('От');
            $col->setRowClickDisabled(true);
            $this->addColumn($col);

            $col = new Select('receiveDate', 'm');
            $col->setLabel('Дата');
            $col->setType($dateTimeType);
            $col->setFormatter(new DateTimeFormatter());
            $col->setRowClickDisabled(true);
            $this->addColumn($col);
        }

        return $this;
    }

    public function setActions()
    {
        $rowAction = new Button();
        $this->setRowClickAction($rowAction);

        $actions = new Action();
        $actions->setLabel('Действия');
        $actions->setWidth(1);

        $viewAction = new Button();
        $rowId = $viewAction->getRowIdPlaceholder();
        $viewAction->setLabel('Просмотр');
        $viewAction->setAttribute('class', 'mail-view hidden');
        $viewAction->setLink('/mail/view/' . $rowId);
        $actions->addAction($viewAction);
        $this->addColumn($actions);

        $viewAction = new Button();
        $rowId = $viewAction->getRowIdPlaceholder();
        $viewAction->setTitle('Удалить');
        $viewAction->setLabel('<span class="glyphicon glyphicon-remove" aria-hidden="true"></span>');
        $viewAction->setAttribute('class', 'mail-remove');
        $viewAction->setLink('/mail/remove/' . $rowId);
        $actions->addAction($viewAction);
        $this->addColumn($actions);

        return $this;
    }

    public function setSource()
    {
        /** @var EntityManager $em */
        $em = $this->getServiceLocator()->get('doctrine.entitymanager.orm_default');
        /** @var MailRepository $repository */
        $repository = $em->getRepository('\Mailer\Entity\Mail');
        $this->setDataSource($repository->getGridQueryBuilder($this->boxType));
        return $this;
    }
}