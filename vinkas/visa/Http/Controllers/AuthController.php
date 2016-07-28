<?php

namespace Vinkas\Visa\Http\Controllers;

use Vinkas\Firebase\Auth\Http\AuthController as BaseController;
use App\User;
use Illuminate\Http\Request;
use Auth;

abstract class AuthController extends BaseController
{

  protected $firebaseAuthView = "vinkas.visa.auth";
  protected $firebaseProjectId;

  public function postAuth(Request $request) {
    $this->firebaseProjectId = config('vinkas.visa.project_id');
    $response = parent::postAuth($request);
    if(Auth::check() && $request->old('client'))
    return redirect()->route('discourse');
    return $response;
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
