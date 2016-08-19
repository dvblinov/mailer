<?php
/**
 * Created by PhpStorm.
 * User: blinov
 * Date: 17.08.16
 * Time: 11:48
 */

namespace Mailer\Service;

use Mailer\Entity\Mail;
use Mailer\Entity\MailAttachment;
use Mailer\Entity\MailBox;
use Ddeboer\Imap\Message;

use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorAwareTrait;
use Doctrine\ORM\EntityManager;

class SyncService implements ServiceLocatorAwareInterface
{
    use ServiceLocatorAwareTrait;

    /**
     * @var int
     */
    private $stepFlush = 1000;

    /**
     * @return EntityManager
     */
    private function getEm()
    {
        return $this->getServiceLocator()->get('doctrine.entitymanager.orm_default');
    }

    /**
     * @param string $subject
     * @return MailBox
     */
    private function getBoxType($subject)
    {
        $mailBoxRepository = $this->getEm()->getRepository('Mailer\Entity\MailBox');
        $boxes = $mailBoxRepository->findAll();
        /** @var MailBox $box */
        foreach ($boxes as $box) {
            $mark = $box->getMark();
            if ($mark && strpos($subject, $mark) !== false) {
                return $box;
            }
        }
        return $mailBoxRepository->findOneBy(['alias' => MailBox::TYPE_INBOX]);
    }

    public function getMails()
    {
        $em = $this->getEm();
        /** @var ImapService $imapService */
        $imapService = $this->getServiceLocator()->get('Mailer\Service\Imap');
        $newMails = $imapService->getUnreadMails();
        $i = 0;
        foreach ($newMails as $message) {
            $mail = new Mail();
            $mail->setFrom($message->getFrom()->getFullAddress());
            $mail->setTo(implode(',', $message->getTo()));
            $mail->setCc(implode(',', $message->getCc()));
            $mail->setSubject($message->getSubject());
            $mail->setReceiveDate($message->getDate());
            $content = $message->getBodyHtml();
            if (!$content) {
                $content = $message->getBodyText();
            }
            $mail->setContent($content);
            $mail->setSyncDate(new \DateTime('now'));
            $mail->setMessageId($message->getId());
            $mail->setBox($this->getBoxType($message->getSubject()));

            $this->getAttachments($mail, $message);

            $em->persist($mail);
            $i++;
            if ($i == $this->stepFlush) {
                $em->flush();
                $em->clear();
                $i = 0;
            }
        }
        if ($i != 0) {
            $em->flush();
        }
        return true;
    }

    public function getAttachments(Mail $mail, Message $message)
    {
        foreach ($message->getAttachments() as $attachment) {
            $mailAttachment = new MailAttachment();
            $mailAttachment->setFileName($attachment->getFilename());
            $mailAttachment->setMail($mail);
            $this->getEm()->persist($mailAttachment);
        }
    }
}