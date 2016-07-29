<?php

namespace Vinkas\Visa\Http\Controllers;

use Vinkas\Firebase\Auth\Http\AuthController as BaseController;
use App\User;
use Illuminate\Http\Request;
use Auth;
use Vinkas\Visa\Http\Controllers\SSO\DiscourseController as Discourse;

abstract class AuthController extends BaseController
{

  protected $firebaseAuthView = "vinkas.visa.auth";
  protected $firebaseProjectId;
  private $callback_url;

  public function postAuth(Request $request) {
    $this->firebaseProjectId = config('vinkas.visa.project_id');
    if($request->session()->has(Discourse::CLIENT))
      $this->callback_url = route('discourse');
    $response = parent::postAuth($request);
    return $response;
  }

  public function redirectPath() {
    if(Auth::check() && $this->callback_url)
    return $this->callback_url;
    return parent::redirectPath();
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
