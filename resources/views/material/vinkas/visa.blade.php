@extends('material.skeleton')

@section('body')
<div class="mdl-layout mdl-js-layout mdl-layout--fixed-header">
  <header class="mdl-layout__header mdl-layout__header--scroll mdl-color--white">
    <div class="mdl-layout__header-row small">
    </div>
    <div class="mdl-layout__header-row mdl-align_center logo">
      <img src="/images/logo.png" alt="{{ getEnv('APP_NAME')}}" />
    </div>
    <div class="mdl-layout__header-row small">
    </div>
  </header>
  <main class="mdl-layout__content">
    <div class="page-content">
      @yield('content')
    </div>
  </main>
  <div id="snackbar" class="mdl-js-snackbar mdl-snackbar">
    <div class="mdl-snackbar__text"></div>
    <button class="mdl-snackbar__action" type="button"></button>
  </div>
</div>
@endsection
