<?php
namespace EmbeddedTemplateSigning\Form;

use Zend\Form\Form;
use Zend\InputFilter\InputFilter;

class EmbeddedTemplateSigningForm extends Form
{
    public function __construct()
    {
        parent::__construct('embedded_template_signing');

        $this->add(array(
            'type' => 'Zend\Form\Element\Collection',
            'name' => 'signers',
            'options' => array(
                'label' => 'Signers',
                'target_element' => array(
                    'type' => 'Application\Form\SignerFieldset'
                )
            )
        ));
        $this->add(array(
            'type' => 'Zend\Form\Element\Collection',
            'name' => 'ccs',
            'options' => array(
                'label' => 'CCs',
                'target_element' => array(
                    'type' => 'Application\Form\CCFieldset'
                )
            )
        ));
        $this->add(array(
            'type' => 'Application\Form\CustomFieldFieldset',
            'name' => 'custom_fields',
            'options' => array(
                'label' => 'Custom fields',
            )
        ));
        $this->add(array(
            'name' => 'submit',
            'type' => 'button',
            'options' => array(
                'label' => 'Launch Demo',
                'column-size' => 'sm-10 col-sm-offset-2',
            ),
            'attributes' => array(
                'type' => 'submit',
                'class' => 'btn-lg btn-primary'
            ),
        ));
    }

    public function addCustomField($name, $type, $label = null)
    {
        return $this->get('custom_fields')
            ->addCustomField($name, $type, $label);
    }
 }