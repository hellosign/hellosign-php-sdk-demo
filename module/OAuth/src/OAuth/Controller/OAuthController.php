<?php
namespace OAuth\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\Session\Container;
use OAuth\Form\OAuthForm;
use HelloSign\Client;
use HelloSign\Exception;
use HelloSign\OAuthTokenRequest;
use HelloSign\OAuthToken;
use HelloSign\SignatureRequest;

class OAuthController extends AbstractActionController
{
    public function __construct()
    {
        $this->client_id = $_ENV['HELLOSIGN_CLIENT_ID'];
        $this->client_secret = $_ENV['HELLOSIGN_CLIENT_SECRET'];
        $this->session = $this->createSession();
    }

    public function indexAction()
    {
        // try to populate token from session
        if ($this->session->access_token) {
            $token = new OAuthToken;
            $token->fromArray(array(
                'access_token' => $this->session->access_token,
                'token_type' => $this->session->token_type,
            ));
        } else {
            $token = null;
        }

        $error = null;
        $form = new OAuthForm();
        $response = null;
        $prg = $this->fileprg($form);

        if ($prg instanceof \Zend\Http\PhpEnvironment\Response) {
            return $prg; // Return PRG redirect response
        } elseif (is_array($prg)) {
            if ($form->isValid()) {
                try {
                    $response = $this->createSignatureRequest($form->getData(), $token);

                    // For demo purposes only:
                    // Clear the session so the demo provides an example of retrieving
                    // authorization from HelloSign. Normally, you would want to
                    // maintain the access token in the user's session so they do not need
                    // to continually authorize the application.
                    $this->session->getManager()->getStorage()->clear('hellosign_oauth');
                }
                catch (Exception $e) {
                    $error = $e;
                }
            }
        }

        return array(
            'token'    => $token,
            'form'     => $form,
            'error'    => $error,
            'response' => $response,
            'client_id'=> $_ENV['HELLOSIGN_CLIENT_ID'],
        );
    }

    public function callbackAction()
    {
        if ($this->params()->fromQuery('code')) {
            $client = new Client;
            $token_request = $this->createOAuthTokenRequest();
            $token = $client->requestOAuthToken($token_request);

            $this->session->exchangeArray($token->toArray());
        }
    }

    protected function createSignatureRequest($data, $token)
    {
        // pre-process post data
        $data['cc_email_addresses'] = array_map(function($cc) {
            return $cc['email_address'];
        }, $data['cc_email_addresses']);

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

        // Send it to HelloSign
        $client = new Client($token);
        return $client->sendSignatureRequest($request);
    }

    protected function createOAuthTokenRequest()
    {
        $code = $this->params()->fromQuery('code');
        $state = $this->params()->fromQuery('state');

        return new OAuthTokenRequest(array(
            'code'          => $code,
            'state'         => $state,
            'client_id'     => $this->client_id,
            'client_secret' => $this->client_secret
        ));
    }

    protected function createSession()
    {
        return new Container('hellosign_oauth');
    }

    // protected function getEnvironment()
    // {
    //     return isset($_ENV['APP_ENV']) ? $_ENV['APP_ENV'] : 'development';
    // }
}
