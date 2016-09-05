@extends('layouts.auth')

@section('page_title')
登录
@endsection

<!-- Main Content -->
@section('g_content')
  <form method="post" action="/login">
    {!! csrf_field() !!}
    <div class="form-group">
      <input class="form-control" name="email" placeholder="姓名/邮箱">
      @if ($errors->has('email'))
        <span class="help-block">
          <strong>{{ $errors->first('email') }}</strong>
        </span>
      @endif
    </div>
    <div class="form-group">
      <input class="form-control" type="password" name="password" placeholder="密码">
      @if ($errors->has('password'))
        <span class="help-block">
          <strong>{{ $errors->first('password') }}</strong>
        </span>
      @endif
    </div>
    <!--
    <div class="form-group">
      <input type="checkbox" class="form-control" name="remember"> 记住我
    </div>-->
    <button class="btn btn-success btn-block" type="submit" id="submit">登录</button>
    <a class="btn btn-link" href="{{ url('/password/reset') }}">忘记密码?</a>
  </form>
@endsection

@section('g_footer')
  <script>
    $('form').on('submit', function (e) {
      var password = $('[name="password"]').val();
      $('[name="password"]').val($.md5(password));

      $(this).get(0).submit();
    });
  </script>
@endsection
