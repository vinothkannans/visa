<?php

namespace Vinkas\Visa\Http\Controllers\SSO;

use Auth;
use App\Http\Controllers\Controller as BaseController;
use Illuminate\Http\Request;
use Vinkas\Visa\Exceptions\SSO\Exception;
use Vinkas\Visa\Models\SSO\Client;

abstract class Controller extends BaseController {

  private $input;
  private $user;
  private $client;
  private $nonce;

  protected $client_key = "client";
  protected $payload_key = "payload";
  protected $signature_key = "signature";
  protected $nonce_key = "nonce";

  protected $payload_response_key = "payload";
  protected $signature_response_key = "signature";

  protected function getCallbackUrl() {
    return $this->getClient()->callback_url;
  }

  protected function getSecret() {
    return $this->getClient()->secret;
  }

  protected function getClient() {
    if($this->client)
    return $this->client;
    $name = $this->input[$this->client_key];
    $client = Client::where('name', $name)->first();
    if($client)
    $this->client = $client;
    return $this->client;
    throw new Exception('Invalid client');
  }

  protected function getPayload() {
    return $this->input[$this->payload_key];
  }

  protected function getDecodedPayload() {
    return urldecode($this->getPayload());
  }

  protected function getRequestPayloadSignature() {
    return hash_hmac('sha256', $this->getDecodedPayload(), $this->getSecret());
  }

  protected function getSignature() {
    return $this->input[$this->signature_key];
  }

  protected function getUser() {
    if ($this->user)
    return $this->user;
    if(Auth::check()) {
      $this->user = Auth::user();
      return $this->user;
    }
    return false;
  }

  public function get(Request $request) {
    if($this->getUser()) {
      $this->setInput($request);
      if($this->isValid()) {
        $request->flush();
        return redirect($this->getCallbackUrl() . '?' . $this->getResponseQuery());
      }
      return response('Bad SSO request', 403)->header('Content-Type', 'text/plain');
    } else {
      $request->flashOnly([$this->payload_key, $this->signature_key]);
      return redirect()->route('getAuth');
    }
  }

  protected function setInput(Request $request) {
    if ($request->has($this->client_key) && $request->has($this->payload_key) && $request->has($this->signature_key)) {
      $this->setInputFromRequest($request);
    } elseif ($request->old($this->client_key) && $request->old($this->payload_key) && $request->old($this->signature_key)) {
      $this->setInputFromOld($request);
    }
  }

  protected function setInputFromRequest(Request $request) {
    $this->input = $request->only([$this->client_key, $this->payload_key, $this->signature_key]);
  }

  protected function setInputFromOld($request) {
    $client = $request->old($this->client_key);
    $payload = $request->old($this->payload_key);
    $signature = $request->old($this->signature_key);
    $this->input = [$this->client_key => $client, $this->payload_key => $payload, $this->signature_key => $signature];
  }

  protected function isValid() {
    $valid = ($this->input[$this->payload_key] && $this->input[$this->signature_key] && $this->getClient() && $this->getNonceFromPayload());
    return ($valid && ($this->getRequestPayloadSignature() === $this->getSignature()));
  }

  protected function getNonceFromPayload() {
    if($this->nonce)
    return $this->nonce;
    $payloads = array();
    parse_str(base64_decode($this->getDecodedPayload()), $payloads);
    if(!array_key_exists($this->nonce_key, $payloads))
    return false;
    $this->nonce = $payloads[$this->nonce_key];
    return $this->nonce;
  }

  public function getResponseParameters() {
    $user = $this->getUser();
    return array(
      'nonce' => $this->getNonceFromPayload(),
      'external_id' => $user->id,
      'email' => $user->email,
      'name' => $user->name
    );
  }

  protected function getResponsePayload() {
    return base64_encode(http_build_query($this->getResponseParameters()));
  }

  protected function getResponseQuery() {
    $response = array(
      $this->payload_response_key => $this->getResponsePayload(),
      $this->signature_response_key => $this->getResponsePayloadSignature()
    );
    return http_build_query($response);
  }

  protected function getResponsePayloadSignature() {
    return hash_hmac('sha256', $this->getResponsePayload(), $this->getSecret());
  }

}
