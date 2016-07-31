<?php

namespace Vinkas\Visa\Traits;

use Illuminate\Http\Request;

trait Redirects {

  protected $callback_url;

  public function getRedirectPath(Request $request) {
    if(!$this->callback_url) $this->callback_url = config('vinkas.visa.default_redirect_url');
    if($request->session()->has(self::CLIENT)) $this->callback_url = route('discourse');
    return $this->callback_url;
  }

}
