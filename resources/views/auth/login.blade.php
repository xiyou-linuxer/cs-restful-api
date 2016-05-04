<!DOCTYPE html>
<html lang="zh-CN">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
  <link rel="stylesheet" href="/assets/css/style.css">
  <link rel="stylesheet" href="/assets/libs/bootstrap/bootstrap.min.css">
  <link href="/assets/libs/font-awesome/css/font-awesome.min.css" rel="stylesheet">

</head>
<body>
  <div class="page-bg" id="particles-js"></div>
  <div class="page-content">
    <div class="container">
      <div class="row">
        <div class="col-md-6">
          <div class="page-info text-center">
            <h2 class="title">西邮Linux兴趣小组</h2>
            <p class="desc">跨平台应用统一授权中心</p>
            <i class="icon fa fa-unlock-alt hidden-sm"></i>
          </div>
        </div>
        <div class="col-md-6">
          <div class="panel panel-default login-panel">
            <div class="rainbow">
              <div class="bar first-bar"></div>
              <div class="bar second-bar"></div>
              <div class="bar third-bar"></div>
              <div class="bar fourth-bar"></div>
            </div>
            <div class="panel-body">
              <div class="avatar-box">
                <div class="avatar">
                  <img src="http://gravatar.duoshuo.com/avatar/b1d5a08e180bf855654534117096f2a5?d=mm&s=150">
                </div>
              </div>
              <form method="post" action="/auth/login">
                {!! csrf_field() !!}
                <div class="form-group">
                  <input class="form-control" name="email" placeholder="姓名/邮箱">
                </div>
                <div class="form-group">
                  <input class="form-control" type="password" name="password" placeholder="密码">
                </div>
                <button class="btn btn-success btn-block" type="submit" id="submit">登录</button>
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <script src="/assets/libs/jquery/jquery.min.js"></script>
  <script src="/assets/libs/jquery/jquery.md5.js"></script>
  <script src="/assets/libs/jquery/jquery.serialize-object.min.js"></script>
  <script src="/assets/libs/bootstrap/bootstrap.min.js"></script>
  <script src="/assets/libs/particles-js/particles.js"></script>
  <script src="/assets/js/app.js"></script>
  <script>
    $('form').on('submit', function (e) {
      //e.preventDefault();
      //e.stopPropagation();

      var password = $('[name="password"]').val();
      $('[name="password"]').val($.md5(password));

      //$('form').submit();
    });
  </script>
</body>
</html>
