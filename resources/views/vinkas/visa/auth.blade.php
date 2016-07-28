@extends('material.vinkas.visa')

@section('content')
<section class="mdl-grid">

  <h4 class="mdl-cell mdl-cell--12-col mdl-typography--headline mdl-align_center">Sign in to continue&nbsp;<a href="#" class="mdl-color-text--primary"></a></h4>
  <div id="noticeboard"></div>
  <div id="firebaseui" class="mdl-cell mdl-cell--12-col mdl-align_center"></div>
  <div class="mdl-cell mdl-cell--12-col"></div>
  <a href="#" class="mdl-cell mdl-cell--12-col mdl-align_center">go back</a>

  <script src="https://www.gstatic.com/firebasejs/3.2.0/firebase-app.js"></script>
  <script src="https://www.gstatic.com/firebasejs/3.2.0/firebase-auth.js"></script>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.4/jquery.min.js"></script>
  <script>
  var token = "{{ csrf_token() }}";
  var config = {
    apiKey: "{{ config('vinkas.visa.api_key') }}",
    authDomain: "{{ config('vinkas.visa.auth_domain') }}",
  };
  firebase.initializeApp(config);
  </script>
  @if (Auth::check())
  @else
  <script src='/vinkas/visa/auth.js'></script>
  @endif
  <script>
  function notice(message) {
    showSnack(message, 10000, 'OK', null);
  }
  </script>
  <script src="https://www.gstatic.com/firebasejs/ui/live/0.4/firebase-ui-auth.js"></script>
  <link type="text/css" rel="stylesheet" href="https://www.gstatic.com/firebasejs/ui/live/0.4/firebase-ui-auth.css" />
  <script type="text/javascript">
  var uiConfig = {
    'signInSuccessUrl': '/',
    'signInOptions': [
      firebase.auth.GoogleAuthProvider.PROVIDER_ID,
      firebase.auth.FacebookAuthProvider.PROVIDER_ID,
      firebase.auth.TwitterAuthProvider.PROVIDER_ID,
      firebase.auth.GithubAuthProvider.PROVIDER_ID,
      firebase.auth.EmailAuthProvider.PROVIDER_ID
    ],
    'tosUrl': null,
    'callbacks': {
      'signInSuccess': function(currentUser, credential, redirectUrl) {
        if (currentUser.emailVerified) {
          auth(currentUser, token);
        } else {
          notice("{!! trans('vinkas.firebase.auth.warning_verify_email') !!}");
        }
        return false;
      }
    }
  };

  var ui = new firebaseui.auth.AuthUI(firebase.auth());
  ui.start('#firebaseui', uiConfig);
  </script>
</div>
@endsection
