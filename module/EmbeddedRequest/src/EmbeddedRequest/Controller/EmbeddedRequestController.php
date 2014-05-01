<?php
namespace EmbeddedRequest\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use EmbeddedRequest\Form\EmbeddedRequestForm;
use HelloSign\Client;
use HelloSign\SignatureRequest;
use HelloSign\UnclaimedDraft;
use HelloSign\Exception;

class EmbeddedRequestController extends AbstractActionController
{
    public function indexAction()
    {
        $error = null;
        $sign_url = null;
        $form = new EmbeddedRequestForm();
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
        // print_r($temp_files);

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

        $draft = new UnclaimedDraft($request, $client_id);
        $response = $client->createUnclaimedDraft($draft);

        return $response->getClaimUrl();
    }
}
