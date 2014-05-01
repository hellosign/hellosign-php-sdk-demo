<?php
namespace EmbeddedSigning\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use EmbeddedSigning\Form\EmbeddedSigningForm;
use HelloSign\Client;
use HelloSign\SignatureRequest;
use HelloSign\EmbeddedSignatureRequest;
use HelloSign\Exception;

class EmbeddedSigningController extends AbstractActionController
{
    public function indexAction()
    {
        $error = null;
        $sign_url = null;
        $form = new EmbeddedSigningForm();
        $prg = $this->fileprg($form);

        if ($prg instanceof \Zend\Http\PhpEnvironment\Response) {
            return $prg; // Return PRG redirect response
        } elseif (is_array($prg)) {
            if ($form->isValid()) {
                try {
                    $sign_url = $this->createSignUrl($form->getData());
                }
                catch (Exception $e) {
                    $error = $e;
                }
            }
        }

        return array(
            'form'     => $form,
            'error'    => $error,
            'client_id'=> $_ENV['HELLOSIGN_CLIENT_ID'],
            'sign_url' => $sign_url
        );
    }

    protected function createSignUrl($data)
    {
        // pre-process post data
        $data['cc_email_addresses'] = array_map(function($cc) {
            return $cc['email_address'];
        }, $data['cc_email_addresses']);

        $client  = new Client($_ENV['HELLOSIGN_API_KEY']);
        $client_id = $_ENV['HELLOSIGN_CLIENT_ID'];

        $request = new SignatureRequest;
        $request->enableTestMode();
        $request->fromArray($data, array(
            'except' => array(
                'files',
                'add_signer',
                'add_cc_email_address',
                'submit'
            )
        ));
        foreach ($data['files'] as $file) {
            $request->addFile($file['tmp_name']);
        }

        // Turn it into an embedded request
        $embedded_request = new EmbeddedSignatureRequest($request, $client_id);

        // Send it to HelloSign
        $response = $client->createEmbeddedSignatureRequest($embedded_request);

        // Grab the signature ID for the signature page that will be embedded in
        // the page (for the demo, we'll just use the first one)
        $signatures = $response->getSignatures();
        $signature = $signatures[0];

        // Retrieve the URL to sign the document
        $response = $client->getEmbeddedSignUrl($signature->getId());

        // Store it to use with the embedded.js HelloSign.open() call
        return $response->getSignUrl();
    }
}
