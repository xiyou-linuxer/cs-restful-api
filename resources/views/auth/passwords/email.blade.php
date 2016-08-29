@extends('layouts.auth')

@section('page_title')
找回密码
@endsection

<!-- Main Content -->
@section('g_content')
  <form role="form" method="POST" action="{{ url('/password/email') }}">
    {!! csrf_field() !!}

    <div class="form-group">
      @if (session('status'))
        <div class="alert alert-success">
          {{ session('status') }}
        </div>
      @endif
    </div>
    <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
      <input type="email" class="form-control" name="email" value="{{ $email or old('email') }}" placeholder="请输入邮箱地址">

      @if ($errors->has('email'))
        <span class="help-block">
          <strong>{{ $errors->first('email') }}</strong>
        </span>
      @endif
    </div>
    @if (session('status'))
      <button type="submit" id="resend_btn" class="btn btn-success btn-block" disabled>60秒后可重新发送</button>
    @else
      <button type="submit" class="btn btn-success btn-block">发送邮件</button>
    @endif
    <a class="btn btn-link" href="{{ url('/login') }}">返回登录</a>
  </form>
@endsection

@section('g_footer')
  <script>
    $(document).ready(function () {
      var time = 60;
      var timer = setInterval(function () {
        time--;
        if (time > 0) {
          $('#resend_btn').html(time + '秒后可重新发送');
        } else {
          $('#resend_btn').html('发送邮件').removeAttr('disabled');
          clearInterval(timer);
        }
      }, 1000);
    });
  </script>
@endsection
