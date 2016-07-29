@extends('material.vinkas.visa')

@section('content')
<style type="text/css">
input.available {
  background-image: url('http://dummyimage.com/26x26/00ff00/fff.png&text=+');
  background-position: top right;
  background-size: 26px 26px;
  background-repeat: no-repeat;
}
input.taken {
  background-image: url('http://dummyimage.com/26x26/ff0000/fff.png&text=+');
  background-position: top right;
  background-size: 26px 26px;
  background-repeat: no-repeat;
}
</style>
<section class="mdl-grid">

  <div class="mdl-cell mdl-cell--12-col mdl-align_center">
    <form id="form" action="#" method="post">
      {{ csrf_field() }}
      <div class="mdl-card mdl-shadow--2dp">
        <div id="p2" class="mdl-progress mdl-js-progress mdl-progress__indeterminate" style="visibility: hidden"></div>
        <div class="mdl-card__title mdl-card--expand">
          <h2 class="mdl-card__title-text">One more step...</h2>
        </div>
        <div class="mdl-card__supporting-text">
          <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
            <input class="mdl-textfield__input" type="text" id="username" name="username" autofocus>
            <label class="mdl-textfield__label" for="username">Choose your username</label>
          </div>
        </div>
        <div class="mdl-card__actions mdl-card--border">
          <button id="btnOk" type="submit" class="mdl-button mdl-button--colored mdl-js-button mdl-js-ripple-effect">
            OK
          </button>
        </div>
      </div>
    </form>
  </div>
</section>
@endsection

@section('script')
<script>
var oVal;
var token = "{{ csrf_token() }}";
var ajaxPool = new Array();
$( "#username" ).bind("propertychange change click keyup input paste", function(e) {
  var nVal = $(this).val();
  if(oVal != nVal) {
    oVal = nVal;
    var v = oVal;
    if(v.length > 5)
    checkAvailability(v);
  }
});
$( "#btnOk" ).bind("click", function(e) {
  return submit();
});
$('#form').bind('submit',function(e){
  return submit();
});
function submit() {
  var username = $("#username").val();
  $("#p2").css('visibility', 'visible');
  $("#btnOk").prop('disabled', true);
  $("#form").prop('disabled', true);
  $.ajax({
    url: "{{ route('postUsername') }}",
    type: "post",
    data: {
      'username': username,
      '_token': token
    },
    success: function(data){
      if(data.success) {
        window.location.replace(data.redirectTo);
      }
      else {
        if(data.message)
        showSnack(data.message, 10000, 'OK', null);
        else if(data.redirectTo)
        window.location.replace(data.redirectTo);
        else
        showSnack("Error", 10000, 'OK', null);
      }
      $("#p2").css('visibility', 'hidden');
    },
    error: function(xhr, textStatus, errorThrown) {
      if(textStatus != "abort") {
        $("#p2").css('visibility', 'hidden');
        showSnack(textStatus, 10000, 'OK', null);
      }
    }
  });
  return false;
}
function checkAvailability(username) {
  $("#p2").css('visibility', 'visible');
  $("#username").removeClass('taken');
  $("#username").removeClass('available');
  $.each(ajaxPool, function(){
    this.abort();
  });
  var request = $.ajax({
    url: "{{ route('checkUsername') }}",
    type: "post",
    data: {
      'username': username,
      '_token': token
    },
    success: function(data){
      if($("#username").val() == data.username) {
        if(data.success) {
          $("#username").removeClass('taken');
          $("#username").addClass('available');
        }
        else {
          $("#username").removeClass('available');
          $("#username").addClass('taken');
        }
        $("#p2").css('visibility', 'hidden');
      }
    },
    error: function(xhr, textStatus, errorThrown) {
      if(textStatus != "abort") {
        $("#p2").css('visibility', 'hidden');
      }
    }
  });
  ajaxPool.push(request);
}
</script>
@endsection
