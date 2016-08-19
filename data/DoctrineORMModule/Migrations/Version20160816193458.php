<?php

namespace DoctrineORMModule\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20160816193458 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql("
            CREATE TABLE mail (
                id INT AUTO_INCREMENT NOT NULL,
                box INT DEFAULT NULL,
                receiveDate DATETIME DEFAULT NULL,
                sendDate DATETIME DEFAULT NULL,
                syncDate DATETIME DEFAULT NULL,
                subject LONGTEXT DEFAULT NULL,
                fromMail LONGTEXT NOT NULL,
                toMail LONGTEXT NOT NULL,
                ccMail LONGTEXT DEFAULT NULL,
                messageId VARCHAR(1000) DEFAULT NULL,
                content LONGTEXT DEFAULT NULL,
                INDEX IDX_5126AC488A9483A (box),
                PRIMARY KEY(id)
            ) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB;

            CREATE TABLE mailBox (
                id INT AUTO_INCREMENT NOT NULL,
                title VARCHAR(256) NOT NULL,
                mark VARCHAR(256) DEFAULT NULL,
                `order` INT NOT NULL,
                alias VARCHAR(256) NOT NULL,
                PRIMARY KEY(id)
            ) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB;
            ALTER TABLE mail ADD CONSTRAINT FK_5126AC488A9483A FOREIGN KEY (box) REFERENCES mailBox (id);

            INSERT INTO mailBox (`title`, `mark`, `order`, `alias`) VALUES
                ('Inbox', null, 10, 'inbox'),
                ('Spam', '#spam', 100, 'spam'),
                ('Promo', '#promo', 20, 'promo'),
                ('Отправленные', null, 60, 'send'),
                ('Удаленные', null, 80, 'removed');

            CREATE TABLE mailAttachment (
                id INT AUTO_INCREMENT NOT NULL,
                mail INT DEFAULT NULL,
                systemName VARCHAR(256) DEFAULT NULL,
                fileName VARCHAR(256) NOT NULL,
                path VARCHAR(256) NOT NULL,
                INDEX IDX_34465155126AC48 (mail),
                PRIMARY KEY(id)
            ) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB;
            ALTER TABLE mailAttachment ADD CONSTRAINT FK_34465155126AC48 FOREIGN KEY (mail) REFERENCES mail (id)
        ");
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs

    }
}
