<?php
/**
 * Created by PhpStorm.
 * User: blinov
 * Date: 17.08.16
 * Time: 9:25
 */
namespace Mailer\Service;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityManager;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorAwareTrait;
use Zend\Mail\Message;
use Zend\Mail\Transport\Smtp as SmtpTransport;
use Zend\Mail\Transport\SmtpOptions;
use Zend\Mime\Message as MimeMessage;
use Zend\Mime\Part as MimePart;
use Zend\Mime\Mime;

use Mailer\Entity\Mail;
use Mailer\Entity\MailBox;

class MailService implements ServiceLocatorAwareInterface
{
    use ServiceLocatorAwareTrait;

    /**
     * @return EntityManager
     */
    private function getEm()
    {
        return $this->getServiceLocator()->get('doctrine.entitymanager.orm_default');
    }

    /**
     * @param $id
     * @return Mail
     */
    public function getMail($id)
    {
        return $this->getEm()->getRepository('Mailer\Entity\Mail')->find($id);
    }

    /**
     * @param $id
     * @return bool
     */
    public function removeMail($id)
    {
        $em = $this->getEm();
        $mail = $this->getMail($id);
        if ($mail->isRemoved()) {
            /** @var AttachmentService $attachmentService */
            $attachmentService = $this->getServiceLocator()->get('Mailer\Service\Attachment');
            $attachmentService->removeMailAttachments($mail);
            $em->remove($mail);
        } else {
            /** @var MailBox $box */
            $box = $em->getRepository('Mailer\Entity\MailBox')->findOneBy(['alias' => MailBox::TYPE_REMOVED]);
            $mail->setBox($box);
            $em->persist($mail);
        }
        $em->flush();
        return true;
    }

    /**
     * @param $data
     * @return bool
     */
    public function sendMail($data)
    {
        $config = $this->getServiceLocator()->get('config');
        $smtpConfig = $config['smtp'];
        $em = $this->getEm();
        try {
            $mail = $this->hydrateMail($data, $smtpConfig);
            if ($this->sendTo($mail, $smtpConfig['connection'])) {
                $em->persist($mail);
                $em->flush();
            }
        } catch (\Exception $e) {
            return false;
        }

        return true;
    }

    /**
     * @param $data
     * @param $smtpConfig
     * @return Mail
     */
    private function hydrateMail($data, $smtpConfig)
    {
        $mail = new Mail();
        $mail->setSubject($data['subject'] ? $data['subject'] : "<Без темы>");
        $mail->setFrom($smtpConfig['from']);
        $mail->setTo($data['to']);
        $mail->setCc($data['cc'] ? $data['cc'] : null);
        $mail->setContent($data['content']);
        $mail->setSendDate(new \DateTime('now'));

        if (!empty($data['attachedFiles'])) {
            $mail->setAttachments($this->hydrateAttachments($data['attachedFiles'], $mail));
        }

        /** @var MailBox $box */
        $box = $this->getEm()->getRepository('Mailer\Entity\MailBox')->findOneBy(['alias' => MailBox::TYPE_SEND]);
        $mail->setBox($box);
        return $mail;
    }

    /**
     * @param $attachmentsData
     * @param Mail $mail
     * @return ArrayCollection
     */
    private function hydrateAttachments($attachmentsData, Mail $mail)
    {
        $result = new ArrayCollection();
        /** @var AttachmentService $attachmentService */
        $attachmentService = $this->getServiceLocator()->get('Mailer\Service\Attachment');
        foreach ($attachmentsData as $attachmentData) {
            $attachment = $attachmentService->getAttachment($attachmentData['id']);
            $attachment->setMail($mail);
            $this->getEm()->persist($attachment);
            $result->add($attachment);
        }
        return $result;
    }

    /**
     * @param Mail $mail
     * @param $smtpConfig
     * @return bool
     */
    private function sendTo(Mail $mail, $smtpConfig)
    {
        $message = new Message();

        // Add from
        $message->addFrom($mail->getFrom());
        foreach ($mail->getToList() as $to) {
            $message->addTo($to);
        }

        // Add cc
        if ($ccList = $mail->getCcList()) {
            foreach ($ccList as $cc) {
                $message->addCc($cc);
            }
        }

        // Set subject
        $message->setSubject($mail->getSubject());

        // Set body
        $attachments = $mail->getAttachments();
        if ($attachments->count()) {
            $body = new MimeMessage();
            $htmlPart = new MimePart($mail->getContent());
            $htmlPart->encoding = Mime::ENCODING_QUOTEDPRINTABLE;
            $htmlPart->type = "text/html; charset=UTF-8";

            $content = new MimeMessage();
            $content->addPart($htmlPart);

            $contentPart = new MimePart($content->generateMessage());
            $contentPart->type = "multipart/alternative;\n boundary=\"" . $content->getMime()->boundary() . '"';

            $body->addPart($htmlPart);
            $messageType = 'multipart/mixed';

            // Add each attachment
            foreach ($attachments as $attachment) {
                $fileContent = file_get_contents(dirname($_SERVER['DOCUMENT_ROOT']) . $attachment->getPath());
                $attachmentPart = new MimePart($fileContent);
                $attachmentPart->filename = $attachment->getFileName();
                $attachmentPart->type = Mime::TYPE_OCTETSTREAM;
                $attachmentPart->encoding = Mime::ENCODING_BASE64;
                $attachmentPart->disposition = Mime::DISPOSITION_ATTACHMENT;

                $body->addPart($attachmentPart);
            }

            $message->setBody($body);
            $message->getHeaders()->get('content-type')->setType($messageType);
        } else {
            $headers = $message->getHeaders();
            $headers->removeHeader('Content-Type');
            $headers->addHeaderLine('Content-Type', 'text/html; charset=UTF-8');
            $message->setHeaders($headers);
            $message->setBody($mail->getContent());
        }
        $message->setEncoding('UTF-8');

        // Send
        $options = new SmtpOptions();
        $options
            ->setHost($smtpConfig['hostname'])
            ->setName($smtpConfig['hostname'])
            ->setPort($smtpConfig['port'])
            ->setConnectionClass($smtpConfig['connection_class'])
            ->setConnectionConfig(array(
                'username' => $smtpConfig['username'],
                'password' => $smtpConfig['password'],
                'ssl' => $smtpConfig['ssl'],
            ));

        $transport = new SmtpTransport();
        $transport
            ->setOptions($options)
            ->send($message);

        return true;
    }
}