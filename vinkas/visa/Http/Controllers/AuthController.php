<?php

namespace Vinkas\Visa\Http\Controllers;

use Vinkas\Firebase\Auth\Http\AuthController as BaseController;
use App\User;
use Illuminate\Http\Request;

abstract class AuthController extends BaseController
{

  protected $firebaseAuthView = "vinkas.visa.auth";
  protected $firebaseProjectId;

  public function postAuth(Request $request) {
    $this->firebaseProjectId = config('vinkas.visa.project_id');
    return parent::postAuth($request);
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
