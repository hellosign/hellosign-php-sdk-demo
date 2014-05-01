<?php
namespace Application\Form;

use Zend\Form\Fieldset;
use Zend\InputFilter\InputFilterProviderInterface;

class CCFieldset extends Fieldset implements InputFilterProviderInterface
{
    public function __construct()
    {
        parent::__construct('cc');

        $this->setLabel('Carbon Copy');

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
        return array();
    }
}