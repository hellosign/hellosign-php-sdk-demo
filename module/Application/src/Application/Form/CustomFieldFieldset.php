<?php
namespace Application\Form;

use Zend\Form\Fieldset;
use Zend\InputFilter\InputFilterProviderInterface;

class CustomFieldFieldset extends Fieldset implements InputFilterProviderInterface
{
    public function __construct()
    {
        parent::__construct('custom_field');

        $this->setLabel('Custom field');

        $this->filters = array();
    }

    /**
     * @return array
     */
    public function getInputFilterSpecification()
    {
        return $this->filters;
    }

    public function addCustomField($name, $type, $label = null)
    {
        $this->add(array(
            'name' => $name,
            'type' => $type,
            'options' => array(
                'label' => $label,
                'label_attributes' => array('class' => 'col-sm-2'),
                'column-size' => 'sm-10',
            )
        ));

        $this->filters[$name] = array(
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
                    ),
                ),
            ),
            'properties' => array(),
        );
    }
}