<?php

namespace Vinkas\Visa\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Validator;
use Auth;

class UsernameController extends Controller
{

  protected $bladeView = "vinkas.visa.username";

  public function __construct() {
    $this->middleware('auth');
  }

  public function get(Request $request) {
    return view($this->bladeView);
  }

  protected function isValid(Request $request) {
    $validator = Validator::make($request->all(), [
      'username' => 'required|unique:users,username|max:255',
    ]);
    if($validator->fails()) return false;
    return true;
  }

  public function check(Request $request) {
    if($this->isValid($request))
    return response()->json(['success' => true, 'username' => $request->input('username')]);
    else
    return response()->json(['success' => false, 'username' => $request->input('username')]);
  }

  public function post(Request $request) {
    $username = $request->input('username');
    if($this->isValid($request)) {
      $user = Auth::user();
      if($user) {
        $user->username = $username;
        $user->save;
        return response()->json(['success' => true, 'username' => $username, 'redirectTo' => $this->getRedirectPath()]);
      } else {
        return response()->json(['success' => false, 'username' => $username, 'redirectTo' => route('getAuth')]);
      }
    }
    else
    return response()->json(['success' => false, 'username' => $username, 'message' => "Username '" . $username . "' is not available"]);
  }

  public function getRedirectPath() {
    return "/";
  }

}
