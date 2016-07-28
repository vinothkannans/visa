<?php

namespace Vinkas\Visa\Http\Controllers\SSO;

use Illuminate\Http\Request;

class DiscourseController extends Controller {

  protected $payload_key = "sso";
  protected $signature_key = "sig";
  protected $nonce_key = "nonce";

  protected $payload_response_key = "sso";
  protected $signature_response_key = "sig";

}
