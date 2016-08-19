<?php
/**
 * Created by PhpStorm.
 * User: blinov
 * Date: 18.08.16
 * Time: 19:30
 */

namespace Mailer\Form;


use Zend\Form\Fieldset;
use Zend\InputFilter\InputFilterProviderInterface;

class AttachmentFieldset extends Fieldset implements InputFilterProviderInterface
{
    public function init()
    {
        $this->add(array(
            'name' => 'id',
            'type' => 'Text',
            'attributes' => array(
                'hidden' => true,
                'class' => 'send-mail-attachment-id'
            ),
        ));

        $this->add(array(
            'name' => 'name',
            'type' => 'Text',
            'attributes' => array(
                'hidden' => true,
                'class' => 'send-mail-attachment-name'
            ),
        ));
    }

    public function getInputFilterSpecification()
    {
        return array(
            'id' => array(
                'required'   => true,
            ),
            'name' => array(
                'required'   => true,
            ),
        );
    }
}