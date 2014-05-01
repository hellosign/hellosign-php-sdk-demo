<?php
namespace OAuth\Form;

use Zend\Form\Form;
use Zend\InputFilter\InputFilter;
use Zend\InputFilter\FileInput;

class OAuthForm extends Form
{
    public function __construct()
    {
        parent::__construct('oauth');

        $this->add(array(
            'name' => 'files',
            'type' => 'file',
            'options' => array(
                'label' => 'Files',
                'label_attributes' => array('class' => 'col-sm-2'),
                'column-size' => 'sm-10',
            ),
            'attributes' => array(
                'multiple' => true
            ),
        ));
        $this->add(array(
            'type' => 'Zend\Form\Element\Collection',
            'name' => 'signers',
            'options' => array(
                // 'label' => 'Who needs to sign it?',
                'count' => 1,
                'should_create_template' => true,
                // 'allow_add' => true,
                'target_element' => array(
                    'type' => 'Application\Form\SignerFieldset'
                )
            )
        ));
        $this->add(array(
            'name' => 'add_signer',
            'type' => 'button',
            'options' => array(
                'label' => 'Add a signer',
                'column-size' => 'sm-10 col-sm-offset-2',
            ),
            'attributes' => array(
                'class' => 'btn-info',
                'onclick' => 'return addElement(0)'
            ),
        ));
        $this->add(array(
            'type' => 'Zend\Form\Element\Collection',
            'name' => 'cc_email_addresses',
            'options' => array(
                // 'label' => 'CC email addresses',
                'count' => 1,
                'should_create_template' => true,
                'target_element' => array(
                    'type' => 'Application\Form\CCFieldset'
                )
            )
        ));
        $this->add(array(
            'name' => 'add_cc_email_address',
            'type' => 'button',
            'options' => array(
                'label' => 'Add a cc email address',
                'column-size' => 'sm-10 col-sm-offset-2',
            ),
            'attributes' => array(
                'class' => 'btn-info',
                'onclick' => 'return addElement(1)'
            ),
        ));
        $this->add(array(
            'name' => 'title',
            'type' => 'text',
            'options' => array(
                'label' => 'Title',
                'label_attributes' => array('class' => 'col-sm-2'),
                'column-size' => 'sm-10',
            ),
            'attributes' => array(
                'class' => 'input-lg'
            ),
        ));
        $this->add(array(
            'name' => 'subject',
            'type' => 'text',
            'options' => array(
                'label' => 'Subject',
                'label_attributes' => array('class' => 'col-sm-2'),
                'column-size' => 'sm-10',
            ),
            'attributes' => array(
                'class' => 'input-lg'
            ),
        ));
        $this->add(array(
            'name' => 'message',
            'type' => 'textarea',
            'options' => array(
                'label' => 'Message',
                'label_attributes' => array('class' => 'col-sm-2'),
                'column-size' => 'sm-10',
            ),
            'attributes' => array(
                'class' => 'input-lg'
            ),
        ));
        $this->add(array(
            'name' => 'submit',
            'type' => 'button',
            'options' => array(
                'label' => 'Upload and Launch Demo',
                'column-size' => 'sm-10 col-sm-offset-2',
            ),
            'attributes' => array(
               'type' => 'submit',
               'class' => 'btn-lg btn-primary'
            ),
        ));

        // set input filter
        $input_filter = new InputFilter();
        $input_filter->add(array(
            'name'     => 'title',
            'required' => false,
            'filters'  => array(
                array('name' => 'StripTags'),
                array('name' => 'StringTrim'),
            ),
        ));
        $input_filter->add(array(
            'name'     => 'subject',
            'required' => false,
            'filters'  => array(
                array('name' => 'StripTags'),
                array('name' => 'StringTrim'),
            ),
        ));
        $input_filter->add(array(
            'name'     => 'message',
            'required' => false,
            'filters'  => array(
                array('name' => 'StripTags'),
                array('name' => 'StringTrim'),
            ),
        ));
        // File Input
        $file_input = new FileInput('files');
        $file_input->setRequired(true);
        $file_input->getFilterChain()->attachByName(
            'filerenameupload',
            array(
                'target'    => './data/tmp/upload',
                'randomize' => true,
            )
        );
        $input_filter->add($file_input);
        $this->setInputFilter($input_filter);
    }
 }