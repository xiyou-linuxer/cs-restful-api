@extends('layouts.auth')

@section('page_title')
重置密码
@endsection

@section('g_content')
  <form role="form" method="POST" action="{{ url('/password/reset') }}">
    {!! csrf_field() !!}

    <input type="hidden" name="token" value="{{ $token }}">

    <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
      <input type="email" class="form-control" name="email" value="{{ $email or old('email') }}">
      @if ($errors->has('email'))
        <span class="help-block">
          <strong>{{ $errors->first('email') }}。</strong>
          <a class="text-primary" href="{{ url('/password/reset') }}">重新发送邮件？</a>
        </span>
      @endif
    </div>

    <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
      <input type="password" class="form-control" name="password" placeholder="请输入新的密码">
      @if ($errors->has('password'))
        <span class="help-block">
          <strong>{{ $errors->first('password') }}</strong>
        </span>
      @endif
    </div>

    <div class="form-group{{ $errors->has('password_confirmation') ? ' has-error' : '' }}">
      <input type="password" class="form-control" name="password_confirmation" placeholder="请重复输入新的密码">

      @if ($errors->has('password_confirmation'))
        <span class="help-block">
          <strong>{{ $errors->first('password_confirmation') }}</strong>
        </span>
      @endif
    </div>

    <button type="submit" class="btn btn-success btn-block">重置密码</button>
    <a class="btn btn-link" href="{{ url('/login') }}">返回登录</a>
  </form>
@endsection

@section('g_footer')
  <script>
    $('form').on('submit', function (e) {
      var password = $('[name="password"]').val();
      $('[name="password"]').val($.md5(password));

      var password_confirmation = $('[name="password_confirmation"]').val();
      $('[name="password_confirmation"]').val($.md5(password_confirmation));
    });
  </script>
@endsection
