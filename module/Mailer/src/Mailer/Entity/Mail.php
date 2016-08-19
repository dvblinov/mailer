<?php
/**
 * Created by PhpStorm.
 * User: blinov
 * Date: 16.08.16
 * Time: 16:04
 */

namespace Mailer\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Mail
 *
 * @ORM\Table(name="mail")
 * @ORM\Entity(repositoryClass="\Mailer\Repository\MailRepository")
 */
class Mail
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="receiveDate", type="datetime", nullable=true)
     */
    private $receiveDate;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="sendDate", type="datetime", nullable=true)
     */
    private $sendDate;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="syncDate", type="datetime", nullable=true)
     */
    private $syncDate;

    /**
     * @var string
     *
     * @ORM\Column(name="subject", type="text", nullable=true)
     */
    private $subject;

    /**
     * @var string
     *
     * @ORM\Column(name="fromMail", type="text", nullable=false)
     */
    private $from;

    /**
     * @var string
     *
     * @ORM\Column(name="toMail", type="text", nullable=false)
     */
    private $to;

    /**
     * @var string
     *
     * @ORM\Column(name="ccMail", type="text", nullable=true)
     */
    private $cc;

    /**
     * @var string
     *
     * @ORM\Column(name="messageId", type="string", length=1000, nullable=true)
     */
    private $messageId;

    /**
     * @var MailBox
     *
     * @ORM\ManyToOne(targetEntity="Mailer\Entity\MailBox")
     * @ORM\JoinColumn(name="box", referencedColumnName="id", nullable=true)
     */
    private $box;

    /**
     * @var string
     *
     * @ORM\Column(name="content", type="text", nullable=true)
     */
    private $content;

    /**
     * @var ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="Mailer\Entity\MailAttachment", mappedBy="mail")
     */
    private $attachments;

    public function __construct()
    {
        $this->attachments = new ArrayCollection();
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param int $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return \DateTime
     */
    public function getReceiveDate()
    {
        return $this->receiveDate;
    }

    /**
     * @param \DateTime $receiveDate
     */
    public function setReceiveDate($receiveDate)
    {
        $this->receiveDate = $receiveDate;
    }

    /**
     * @return \DateTime
     */
    public function getSendDate()
    {
        return $this->sendDate;
    }

    /**
     * @param \DateTime $sendDate
     */
    public function setSendDate($sendDate)
    {
        $this->sendDate = $sendDate;
    }

    /**
     * @return \DateTime
     */
    public function getSyncDate()
    {
        return $this->syncDate;
    }

    /**
     * @param \DateTime $syncDate
     */
    public function setSyncDate($syncDate)
    {
        $this->syncDate = $syncDate;
    }

    /**
     * @return string
     */
    public function getSubject()
    {
        return $this->subject;
    }

    /**
     * @param string $subject
     */
    public function setSubject($subject)
    {
        $this->subject = $subject;
    }

    /**
     * @return string
     */
    public function getFrom()
    {
        return $this->from;
    }

    /**
     * @param string $from
     */
    public function setFrom($from)
    {
        $this->from = $from;
    }

    /**
     * @return string
     */
    public function getTo()
    {
        return $this->to;
    }

    /**
     * @param string $to
     */
    public function setTo($to)
    {
        $this->to = $to;
    }

    /**
     * @return string
     */
    public function getCc()
    {
        return $this->cc;
    }

    /**
     * @param string $cc
     */
    public function setCc($cc)
    {
        $this->cc = $cc;
    }

    /**
     * @return string
     */
    public function getMessageId()
    {
        return $this->messageId;
    }

    /**
     * @param string $messageId
     */
    public function setMessageId($messageId)
    {
        $this->messageId = $messageId;
    }

    /**
     * @return MailBox
     */
    public function getBox()
    {
        return $this->box;
    }

    /**
     * @param MailBox $box
     */
    public function setBox($box)
    {
        $this->box = $box;
    }

    /**
     * @return string
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * @param string $content
     */
    public function setContent($content)
    {
        $this->content = $content;
    }

    /**
     * @return ArrayCollection
     */
    public function getAttachments()
    {
        return $this->attachments;
    }

    /**
     * @param ArrayCollection $attachments
     */
    public function setAttachments($attachments)
    {
        $this->attachments = $attachments;
    }

    public function isRemoved()
    {
        return $this->getBox()->getAlias() == MailBox::TYPE_REMOVED;
    }

    public function getToList()
    {
        return $this->getTo() ? explode(',', $this->getTo()) : [];
    }

    public function getCcList()
    {
        return $this->getCc() ? explode(',', $this->getCc()) : [];
    }
}