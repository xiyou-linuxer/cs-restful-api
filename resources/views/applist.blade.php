<!DOCTYPE html>
<html lang="zh-CN">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
  <link rel="stylesheet" href="/assets/css/style.css">
  <link rel="stylesheet" href="/assets/libs/bootstrap/bootstrap.min.css">
  <link href="/assets/libs/font-awesome/css/font-awesome.min.css" rel="stylesheet">
  <style>
    .body {
      font-family: "Source Sans Pro", "Helvetica Neue", Helvetica, Arial, sans-serif;
    }
    .page-content {
      width: 100%;
      height: 100%;
      top: 0;
      left: 0;
      position: fixed;
      z-index: 1;
      padding-top: 100px;
      text-align: center;
      overflow: scroll;
    }
    .page-content .page-title {
      color: #fff;
      padding-bottom: 20px;
      margin-bottom: 50px;
      border-bottom: 2px solid rgba(255,255,255,0.6);
    }
    .app-info {
      text-align: center;
      margin-bottom: 50px;
    }
    .app-info .avatar {
      width: 80px;
      height: 80px;
      display: inline-block;
      border-radius: 50%;
      overflow: hidden;
    }
    .app-info .avatar img {
      width: 100%;
      height: 100%;
    }
    .app-info .title a {
      color: #fff;
    }
    .app-info .description {
      color: #eee;
      padding: 10px;
      line-height: 1.8;
      text-indent: 2em;
      text-align: left;
    }
  </style>
</head>
<body>
  <div class="page-bg" id="particles-js"></div>
  <div class="page-content">
    <div class="container">
      <h3 class="page-title">应用列表</h3>
      <div class="row">
        @foreach ($apps as $app)
          <div class="col-md-3 text-center">
            <div class="app-info">
              <a href="{{$app->homepage}}" class="avatar" target="_blank">
                <img src="{{$app->avatar}}">
              </a>
              <h3 class="title"><a href="{{$app->homepage}}" target="_blank">{{$app->name}}</a></h3>
              <p class="description">{{$app->description}}</p>
            </div>
          </div>
        @endforeach
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
