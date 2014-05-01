<?php
namespace EmbeddedTemplateSigning\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use EmbeddedTemplateSigning\Form\EmbeddedTemplateSigningForm;
use HelloSign\Client;
use HelloSign\TemplateSignatureRequest;
use HelloSign\EmbeddedSignatureRequest;

class EmbeddedTemplateSigningController extends AbstractActionController
{
    public function __construct()
    {
        $this->client = new Client($_ENV['HELLOSIGN_API_KEY']);
        $this->client_id = $_ENV['HELLOSIGN_CLIENT_ID'];
    }

    public function indexAction()
    {
        if ($template_id = $this->params()->fromRoute('id')) {
            $form = $this->getForm($template_id);
            $sign_url = null;

            if ($this->getRequest()->isPost() && $form->isValid()) {
                $sign_url = $this->createSignUrl($form->getData(), $template_id);
            }

            $view = new ViewModel(array(
                'template_id' => $template_id,
                'form'        => $form,
                'client_id'   => $this->client_id,
                'sign_url'    => $sign_url
            ));
            $view->setTemplate('embedded-template-signing/embedded-template-signing/request.phtml');
            return $view;
        } else {
            $templates = $this->client->getTemplates();
            return array(
                'templates' => $templates,
            );
        }

    }

    protected function createSignUrl($data, $template_id)
    {
        unset($data['submit']);
        $request = new TemplateSignatureRequest;
        $request->enableTestMode();
        $request->setTemplateId($template_id);
        $request->fromObject($data);

        // Turn it into an embedded request
        $embedded_request = new EmbeddedSignatureRequest($request, $this->client_id);

        // Send it to HelloSign
        $response = $this->client->createEmbeddedSignatureRequest($embedded_request);

        // Grab the signature ID for the signature page that will be embedded in
        // the page (for the demo, we'll just use the first one)
        $signatures = $response->getSignatures();
        $signature = $signatures[0];

        // Retrieve the URL to sign the document
        $response = $this->client->getEmbeddedSignUrl($signature->getId());

        // Store it to use with the embedded.js HelloSign.open() call
        return $response->getSignUrl();
    }

    protected function getForm($template_id)
    {
        $form = new EmbeddedTemplateSigningForm();
        $template = $this->client->getTemplate($template_id);

        // add custom fields
        foreach ($template->getCustomFields() as $field) {
            $form->addCustomField($field->name, $field->type, $field->name);
        }

        // set data
        $data = $this->getRequest()->isPost()
            ? $this->getRequest()->getPost()
            : $this->createBlankFormData($template);
        $form->setData($data);
        $this->reSetLabels($form, $data);

        return $form;
    }

    protected function createBlankFormData($template)
    {
        $data = array(
            'signers'       => array(),
            'ccs'           => array(),
            // 'custom_fields' => array(),
        );

        foreach ($template->getSignerRoles() as $role) {
            $data['signers'][$role->name] = array();
        }

        foreach ($template->getCCRoles() as $role) {
            $data['ccs'][$role->name] = array();
        }

        // foreach ($template->getCustomFields() as $field) {
        //     $data['custom_fields'][$field->name] = null;
        // }

        return $data;
    }

    protected function reSetLabels($form, $data)
    {
        foreach ($data as $key => $value) {
            if (is_array($value)) {
                $labels = array_keys($value);

                foreach ($form->get($key) as $i => $f) {
                    $f->setLabel($labels[$i]);
                }
            }
        }
    }
}
