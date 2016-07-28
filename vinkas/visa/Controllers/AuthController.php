<?php

namespace Vinkas\Visa\Controllers;

use Vinkas\Firebase\Auth\Http\AuthController as BaseController;
use App\User;

abstract class AuthController extends BaseController
{

  protected $firebaseAuthView = "vinkas.visa.auth";

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
