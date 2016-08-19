<?php
/**
 * Created by PhpStorm.
 * User: blinov
 * Date: 18.08.16
 * Time: 14:22
 */

namespace Mailer\Entity;


use Doctrine\ORM\Mapping as ORM;

/**
 * MailAttachment
 *
 * @ORM\Table(name="mailAttachment")
 * @ORM\Entity
 */
class MailAttachment
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
     * @var Mail
     *
     * @ORM\ManyToOne(targetEntity="Mailer\Entity\Mail", inversedBy="attachments")
     * @ORM\JoinColumn(name="mail", referencedColumnName="id")
     **/
    private $mail;

    /**
     * @var string
     *
     * @ORM\Column(name="systemName", type="string", length=256, nullable=true)
     */
    private $systemName;

    /**
     * @var string
     *
     * @ORM\Column(name="fileName", type="string", length=256, nullable=false)
     */
    private $fileName;

    /**
     * @var string
     *
     * @ORM\Column(name="path", type="string", length=256, nullable=true)
     */
    private $path;

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
     * @return Mail
     */
    public function getMail()
    {
        return $this->mail;
    }

    /**
     * @param Mail $mail
     */
    public function setMail($mail)
    {
        $this->mail = $mail;
    }

    /**
     * @return string
     */
    public function getSystemName()
    {
        return $this->systemName;
    }

    /**
     * @param string $systemName
     */
    public function setSystemName($systemName)
    {
        $this->systemName = $systemName;
    }

    /**
     * @return string
     */
    public function getFileName()
    {
        return $this->fileName;
    }

    /**
     * @param string $fileName
     */
    public function setFileName($fileName)
    {
        $this->fileName = $fileName;
    }

    /**
     * @return string
     */
    public function getPath()
    {
        return $this->path;
    }

    /**
     * @param string $path
     */
    public function setPath($path)
    {
        $this->path = $path;
    }
}