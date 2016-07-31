<?php

namespace Vinkas\Visa\Http\Controllers;

use Vinkas\Firebase\Auth\Http\AuthController as BaseController;
use App\User;
use Illuminate\Http\Request;
use Auth;
use Vinkas\Visa\Http\Controllers\SSO\DiscourseController as Discourse;
use Vinkas\Visa\Traits\Redirects;

abstract class AuthController extends BaseController
{

  use Redirects;
  
  const CLIENT = "client";

  protected $firebaseAuthView = "vinkas.visa.auth";
  protected $firebaseProjectId;
  protected $redirectTo;

  public function postAuth(Request $request) {
    $this->firebaseProjectId = config('vinkas.visa.project_id');
    $this->redirectTo = $this->getRedirectPath($request);
    $response = parent::postAuth($request);
    return $response;
  }

  public function redirectPath() {
    if(Auth::check()) {
      $user = Auth::user();
      if(!$user->username)
      $this->redirectTo = route('getUsername');
    }
    return $this->redirectTo;
  }

  /**
  * Create a new user instance after a valid registration.
  *
  * @param  array  $data
  * @return User
  */
  protected function create(array $data)
  {
    return User::create([
      'id' => $data['id'],
      'name' => $data['name'],
      'email' => $data['email'],
      'photo_url' => $data['photo_url'],
    ]);
  }

}
