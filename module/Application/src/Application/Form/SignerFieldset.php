<?php
namespace Application\Form;

use Zend\Form\Fieldset;
use Zend\InputFilter\InputFilterProviderInterface;

class SignerFieldset extends Fieldset implements InputFilterProviderInterface
{
    public function __construct()
    {
        parent::__construct('signer');
        // $this->setHydrator(new ClassMethodsHydrator(false))
        //      ->setObject(new Signer());

        $this->setLabel('Signer');

        $this->add(array(
            'name' => 'name',
            'type' => 'text',
            'options' => array(
                'label' => 'Name',
                'label_attributes' => array('class' => 'col-sm-2'),
                'column-size' => 'sm-10',
            )
        ));
        $this->add(array(
            'name' => 'email_address',
            'type' => 'email',
            'options' => array(
                'label' => 'Email',
                'label_attributes' => array('class' => 'col-sm-2'),
                'column-size' => 'sm-10',
            )
        ));
    }

    /**
     * @return array
     */
    public function getInputFilterSpecification()
    {
        return array(
            'name' => array(
                // 'required' => true,
                'filters'  => array(
                    array('name' => 'StripTags'),
                    array('name' => 'StringTrim'),
                ),
                'validators' => array(
                    array(
                        'name'    => 'StringLength',
                        'options' => array(
                            'encoding' => 'UTF-8',
                            'min'      => 2,
                            'max'      => 100,
                        ),
                    ),
                ),
                'properties' => array(),
            )
        );
    }
}