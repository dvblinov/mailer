<?php
/**
 * Created by PhpStorm.
 * User: blinov
 * Date: 16.08.16
 * Time: 16:41
 */

namespace Mailer\Repository;

use Doctrine\ORM\EntityRepository;
use Mailer\Entity\MailBox;

class MailRepository extends EntityRepository
{
    public function getGridQueryBuilder($boxType)
    {
        /** @var MailBox $box */
        $box = $this->getEntityManager()->getRepository('Mailer\Entity\MailBox')->findOneBy(['alias' => $boxType]);
        if (!$box) {
            throw new \Exception('Не найден тип ящика' . $boxType);
        }
        $qb = $this->getEntityManager()->createQueryBuilder();
        $qb
            ->from($this->getEntityName(), 'm')
            ->where($qb->expr()->eq('m.box', $box->getId()))
        ;
        return $qb;
    }
}