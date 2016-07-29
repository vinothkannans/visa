<?php

namespace Vinkas\Visa\Http\Controllers\SSO;

use Auth;
use App\Http\Controllers\Controller as BaseController;
use Illuminate\Http\Request;
use Vinkas\Visa\Exceptions\SSO\Exception;
use Vinkas\Visa\Models\SSO\Client;

abstract class Controller extends BaseController {

  const CLIENT = "client";

  private $input;
  private $client;
  private $user;
  private $nonce;

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
    $host = $this->input[self::CLIENT];
    $client = Client::where('host', $host)->first();
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

  protected function putInSession(Request $request) {
    $request->session()->put(self::CLIENT, $request->input(self::CLIENT));
    $request->session()->put($this->payload_key, $request->input($this->payload_key));
    $request->session()->put($this->signature_key, $request->input($this->signature_key));
  }

  protected function forgotInSession(Request $request) {
    $request->session()->forget(self::CLIENT);
    $request->session()->forget($this->payload_key);
    $request->session()->forget($this->signature_key);
  }

  public function get(Request $request) {
    if($this->getUser()) {
      $this->setInput($request);
      if($this->isValid()) {
        $this->forgotInSession($request);
        return redirect($this->getCallbackUrl() . '?' . $this->getResponseQuery());
      }
      return response('Bad SSO request', 403)->header('Content-Type', 'text/plain');
    } else {
      $this->putInSession($request);
      return redirect()->route('getAuth');
    }
  }

  protected function setInput(Request $request) {
    if ($request->has(self::CLIENT) && $request->has($this->payload_key) && $request->has($this->signature_key)) {
      $this->setInputFromRequest($request);
    } elseif ($request->session()->has(self::CLIENT) && $request->session()->has($this->payload_key) && $request->session()->has($this->signature_key)) {
      $this->setInputFromSession($request);
    }
  }

  protected function setInputFromRequest(Request $request) {
    $this->input = $request->only([self::CLIENT, $this->payload_key, $this->signature_key]);
  }

  protected function setInputFromSession($request) {
    $client = $request->session()->get(self::CLIENT);
    $payload = $request->session()->get($this->payload_key);
    $signature = $request->session()->get($this->signature_key);
    $this->input = [self::CLIENT => $client, $this->payload_key => $payload, $this->signature_key => $signature];
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
