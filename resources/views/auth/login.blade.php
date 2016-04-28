<!DOCTYPE html>
<html lang="zh-CN">
<head>
  <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
  <link rel="stylesheet" href="//cdn.bootcss.com/bootstrap/3.3.5/css/bootstrap.min.css">
</head>
<body>
  <div class="jumbotron">
    <div class="container">
      <div class="text-center">
        <h1>Xiyou Linux SSO</h1>
        <p>欢迎使用西邮Linux兴趣小组统一授权平台</p>
      </div>
      <div class="panel panel-default" style="width:360px;margin:0 auto">
        <div class="panel-body">
          <form method="post" action="/auth/login">
            {!! csrf_field() !!}
            <div class="form-group">
              <label>邮箱</label>
              <input class="form-control" name="email">
            </div>
            <div class="form-group">
              <label>密码</label>
              <input class="form-control" name="password">
            </div>
            <button class="btn btn-success" type="submit">登录</button>
          </form>
        </div>
      </div>
    </div>
  </div>

  <script src="//cdn.bootcss.com/jquery/1.11.3/jquery.min.js"></script>
  <script src="//cdn.bootcss.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>
</body>
</html>
