<?php
/**
 * Created by PhpStorm.
 * User: blinov
 * Date: 17.08.16
 * Time: 16:59
 */

namespace Mailer\Form;


use Zend\Form\Form;
use Zend\InputFilter\InputFilterProviderInterface;

class MailForm extends Form implements InputFilterProviderInterface
{
    public function init()
    {
        $this
            ->setAttribute('method', 'POST')
            ->setAttribute('novalidate', '')
            ->setAttribute('id', 'send-form')
            ->setAttribute('action', '/mail/send');

        $this->add(array(
            'name' => 'to',
            'type' => 'Text',
            'options' => array(
                'label' => 'Кому',
            ),
            'attributes' => array(
                'required' => true,
                'class' => 'form-control',
                'id' => 'send-form-to',
                'data-role' => 'tagsinput',
            ),
        ));

        $this->add(array(
            'name' => 'cc',
            'type' => 'Text',
            'options' => array(
                'label' => 'Копия',
            ),
            'attributes' => array(
                'class' => 'form-control',
                'id' => 'send-form-cc',
                'data-role' => 'tagsinput',
            ),
        ));

        $this->add(array(
            'name' => 'subject',
            'type' => 'Text',
            'options' => array(
                'label' => 'Тема',
            ),
            'attributes' => array(
                'class' => 'form-control',
                'id' => 'send-form-subject',
            ),
        ));

        $this->add(array(
            'name' => 'content',
            'type' => 'Textarea',
            'attributes' => array(
                'class' => 'form-control',
                'id' => 'send-form-content'
            ),
        ));

        $this->add(array(
            'name' => 'attachedFiles',
            'type' => 'Zend\Form\Element\Collection',
            'options' => array(
                'count' => 0,
                'should_create_template' => true,
                'target_element' => array(
                    'type' => 'Mailer\Form\AttachmentFieldset'
                )
            ),
            'attributes' => array(
                'id' => 'send-form-attachments'
            ),
        ));

        $this->add(array(
            'name' => 'send',
            'type' => 'Button',
            'options' => array(
                'label' => 'Отправить',
            ),
            'attributes' => array(
                'value' => 'Отправить',
                'class' => 'btn btn-default',
                'id' => 'send-form-send-btn'
            ),
        ));
    }

    public function getInputFilterSpecification()
    {
        return array(
            'to' => array(
                'required'   => true,
                'validators' => array(
                    array(
                        'name' => 'Mailer\Form\Validator\EmailListValidator',
                    )
                ),
            ),
            'cc' => array(
                'required' => false,
                'validators' => array(
                    array(
                        'name' => 'Mailer\Form\Validator\EmailListValidator',
                    )
                ),
            ),
            'subject' => array(
                'required' => false,
            ),
            'content' => array(
                'required' => false,
            ),
            'attachedFiles' => array(
                'required' => false,
            ),
        );
    }
}