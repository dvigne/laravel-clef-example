<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Clef\Clef;
use App\User;
class ClefController extends Controller
{
    public function index()
    {
      return view('clef');
    }
    // public function assert_state_is_valid($state) {
    //   $is_valid = isset(Session::get('state')) && strlen(Session::get('state')) > 0 && Session::get('state') == $state;
    //   unset(Session::get('state'));
    //   if (!$is_valid) {
    //     header('HTTP/1.0 403 Forbidden');
    //     echo "The state parameter didn't match what was passed in to the Clef button.";
    //     exit;
    //   }
    //   return $is_valid;
    // }

    private function createOrGetUserInfo($request) {
      $data = $request->all();
      Clef::initialize(getenv('CLEF_ID'), getenv('CLEF_SECRET'));
       try {
         $response = Clef::get_login_information($data['code']);
         $user_information = $response->info;
       }
       catch (Exception $e){
         echo "Login with Clef Failed: " . $e->getMessage();
       }
       if (Auth::user()->clef_id == null) {
         $user = User::find(Auth::user()->id);
         $user->clef_id = $user_information->id;
         $user->save();
       }
       return $user_information;
      //
      // // Get the state parameter passed as a query arg and verify it
      // $this->assert_state_is_valid($_GET["state"]);
      //
      // // Get user information using the authorization code passed as a query arg
      // try {
      //   $response = Clef::get_login_information($_GET["code"]);
      //   $user_information = $response->info;
      // } catch (Exception $e) {
      //   // An error occurred while trying to get user information
      //   echo "Login with Clef failed: " . $e->getMessage();
      // }
    }
    public function login(Request $request) {
      $user = $this->createOrGetUserInfo($request);
      $current_user_id = User::find(Auth::user()->id);
      if ($current_user_id->clef_id == $user->id) {
        session_start();
        session_regenerate_id(true);

        session(['user_id' => $user->id]);
        session(['logged_in_at' => date( 'Y-m-d H:i:s', time())]);

        return redirect('/dashboard');
     }
     else {
       return response(401);
     }
   }
   public function logout(Request $request)
   {
     $data = $request->all();
     Clef::initialize(getenv('CLEF_ID'), getenv('CLEF_SECRET'));
     if ($data['logout_token'] != null) {
       try {
        $clef_id =  Clef::get_logout_information($data['logout_token']);
      }
      catch (Exception $e) {
        die(json_encode(array('error' => $e->getMessage())));
      }
      User::where('clef_id', $clef_id)->update(['logged_out_at' => date( 'Y-m-d H:i:s', time())]);
    }
  }
}
