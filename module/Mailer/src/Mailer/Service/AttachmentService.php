<?php
/**
 * Created by PhpStorm.
 * User: blinov
 * Date: 18.08.16
 * Time: 15:38
 */

namespace Mailer\Service;

use Doctrine\ORM\EntityManager;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorAwareTrait;

use Mailer\Entity\Mail;
use Mailer\Entity\MailAttachment;

class AttachmentService implements ServiceLocatorAwareInterface
{
    use ServiceLocatorAwareTrait;

    const FILES_ROOT_DIR = '/data/files/';

    /**
     * @return EntityManager
     */
    private function getEm()
    {
        return $this->getServiceLocator()->get('doctrine.entitymanager.orm_default');
    }

    /**
     * @param $id
     * @return MailAttachment
     */
    public function getAttachment($id)
    {
        return $this->getEm()->getRepository('Mailer\Entity\MailAttachment')->find($id);
    }

    /**
     * @param MailAttachment $attachment
     * @return null|string
     */
    public function getAttachmentContent(MailAttachment $attachment)
    {
        if ($attachment->getPath()) {
            // local files
            return file_get_contents(dirname($_SERVER['DOCUMENT_ROOT']) . $attachment->getPath());
        } else {
            /** @var ImapService $imapService */
            $imapService = $this->getServiceLocator()->get('Mailer\Service\Imap');
            $messageId = $attachment->getMail()->getMessageId();
            $msgAttachment = $imapService->getAttachment($messageId, $attachment->getFileName());
            return $msgAttachment !== null ? $msgAttachment->getDecodedContent() : null;
        }
    }

    /**
     * @param Mail $mail
     */
    public function removeMailAttachments(Mail $mail)
    {
        foreach ($mail->getAttachments() as $attachment) {
            $this->getEm()->remove($attachment);
        }
    }

    /**
     * @param $fileInfo
     * @return array|bool
     * @throws \Exception
     */
    public function uploadFile($fileInfo)
    {
        $ext = pathinfo($fileInfo['name'], PATHINFO_EXTENSION);
        $tmpUploadDir = $this->getUploadDirCommon();
        $fileName = uniqid('', true) . '.' . $ext;
        $dirRoot = getcwd(). self::FILES_ROOT_DIR;;
        $targetFilePath = $tmpUploadDir.$fileName;
        if ($this->checkDirPath($dirRoot, $targetFilePath) == false) {
            throw new \Exception("Can't create directory tree for file: " . $targetFilePath);
        }

        if (!move_uploaded_file($fileInfo['tmp_name'], $dirRoot . $targetFilePath)) {
            return false;
        }

        $attachment = new MailAttachment();
        $attachment->setFileName($fileInfo['name']);
        $attachment->setSystemName($fileName);
        $attachment->setPath(self::FILES_ROOT_DIR . $targetFilePath);
        $this->getEm()->persist($attachment);
        $this->getEm()->flush();

        return [
            'id' => $attachment->getId(),
            'name' => $attachment->getFileName(),
        ];
    }

    private function getUploadDirCommon()
    {
        $dateTime = new \DateTime();
        $dir = $dateTime->format('Y/m/d/');
        return $dir;
    }

    private function checkDirPath($target, $targetFilePath)
    {
        $pathArray = explode(DIRECTORY_SEPARATOR, $targetFilePath);
        array_pop($pathArray);
        foreach ($pathArray as $p) {
            if (!self::checkDir($target, $p)) {
                return false;
            }
        }
        return true;
    }

    private function checkDir(&$dir, $subDir)
    {
        if ($subDir == '') {
            return true;
        }

        $sep = '';
        if (substr($subDir, 0, 1) != '/') {
            $sep = '/';
        }
        $checkedDir = $dir . $sep . $subDir;

        if (!is_dir($checkedDir)) {
            mkdir($checkedDir);
        }

        if (!is_writeable($checkedDir)) {
            return false;
        }

        $dir .= $sep . $subDir;
        return true;
    }
}