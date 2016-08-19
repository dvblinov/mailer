<?php
/**
 * Created by PhpStorm.
 * User: blinov
 * Date: 16.08.16
 * Time: 17:41
 */

namespace Mailer\View\Helper;

use Doctrine\ORM\EntityManager;
use Zend\View\Helper\AbstractHelper;

use Mailer\Entity\MailBox;

class MailBoxList extends AbstractHelper
{
    public function __invoke()
    {
        /** @var EntityManager $em */
        $em = $this->getView()
            ->getHelperPluginManager()
            ->getServiceLocator()
            ->get('doctrine.entitymanager.orm_default');

        $boxes = $em->getRepository('\Mailer\Entity\MailBox')->findBy([], ['order' => 'ASC']);
        $result = '<ul class="nav nav-pills nav-stacked">';
        /** @var MailBox $box */
        foreach ($boxes as $box) {
            $result .= '<li><a href="/box/' . $box->getAlias() .'">' . $box->getTitle() . '</a></li>';
        }
        $result .= '</ul>';

        return $result;
    }
}
