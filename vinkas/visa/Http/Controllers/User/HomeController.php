<?php

namespace Vinkas\Visa\Http\Controllers\User;

use Vinkas\Visa\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Validator;
use Auth;
use Vinkas\Visa\Http\Controllers\SSO\DiscourseController as Discourse;

class HomeController extends Controller
{

  public function get(Request $request) {
    return redirect(getRedirectPath($request));
  }

}
