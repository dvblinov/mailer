<?php
/**
 * Created by PhpStorm.
 * User: blinov
 * Date: 17.08.16
 * Time: 11:48
 */

namespace Mailer\Service;

use Ddeboer\Imap\Connection;
use Ddeboer\Imap\Search\Flag\Unseen;
use Ddeboer\Imap\Search\LogicalOperator\All;
use Ddeboer\Imap\Server;
use Ddeboer\Imap\SearchExpression;
use Ddeboer\Imap\Message;
use Ddeboer\Imap\MessageIterator;

use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorAwareTrait;

class ImapService implements ServiceLocatorAwareInterface
{
    use ServiceLocatorAwareTrait;

    /**
     * @var Connection
     */
    private $connection = null;

    /**
     * @return Connection
     */
    private function getConnection()
    {
        if (is_null($this->connection)) {
            $config = $this->getServiceLocator()->get('config');
            $imapConfig = $config['imap']['connection'];
            $server = new Server(
                $imapConfig['hostname'],
                $imapConfig['port'],
                $imapConfig['flags']
            );
            $this->connection = $server->authenticate($imapConfig['username'], $imapConfig['password']);
        }
        return $this->connection;
    }

    /**
     * @param SearchExpression $searchExpression
     * @return \Ddeboer\Imap\Message[]|MessageIterator
     */
    private function getMails(SearchExpression $searchExpression)
    {
        return $this->getConnection()->getMailbox('INBOX')->getMessages($searchExpression);
    }

    /**
     * @return Message[]|MessageIterator
     */
    public function getUnreadMails()
    {
        $search = new SearchExpression();
        $search->addCondition(new Unseen());
        return $this->getMails($search);
    }

    /**
     * @param $messageId
     * @param $attachmentName
     * @return Message\Attachment|null
     */
    public function getAttachment($messageId, $attachmentName)
    {
        $search = new SearchExpression();
        $search->addCondition(new All());
        foreach ($this->getMails($search) as $message) {
            if ($message->getId() === $messageId) {
                foreach ($message->getAttachments() as $attachment) {
                    if ($attachment->getFilename() === $attachmentName) {
                        return $attachment;
                    }
                }
            }
        }
        return null;
    }
}