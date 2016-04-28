<!DOCTYPE html>
<html lang="zh-CN">
<head>
  <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
  <link rel="stylesheet" href="//cdn.bootcss.com/bootstrap/3.3.5/css/bootstrap.min.css">
</head>
<body>
  <div class="jumbotron">
    <div class="container">
      <h1>Xiyou Linux Ouath 登陆</h1>
      <p>欢迎使用西邮Linux兴趣小组Oauth授权服务</p>
      <p>
        <form method="post" action="{{route('oauth.authorize.post', $params)}}">
          {!! csrf_field() !!}
          <input type="hidden" name="client_id" value="{{$params['client_id']}}">
          <input type="hidden" name="redirect_uri" value="{{$params['redirect_uri']}}">
          <input type="hidden" name="response_type" value="{{$params['response_type']}}">
          <input type="hidden" name="state" value="{{$params['state']}}">
          <input type="hidden" name="scope" value="{{$params['scope']}}">
          <button class="btn btn-success btn-lg" href="#" role="button" type="submit" name="approve" value="1">同意</button>
          <a class="btn btn-default btn-lg" href="#" role="button" type="submit" name="deny" value="1">拒绝</a>
        </form>
      </p>
    </div>
  </div>

  <script src="//cdn.bootcss.com/jquery/1.11.3/jquery.min.js"></script>
  <script src="//cdn.bootcss.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>
</body>
</html>
