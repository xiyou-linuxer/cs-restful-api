<!DOCTYPE html>
<html lang="zh-CN">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
  <title>@yield('page_title') | 西邮Linux兴趣小组</title>
  <link rel="stylesheet" href="/libs/bootstrap/bootstrap.min.css">
  <link href="/libs/font-awesome/css/font-awesome.min.css" rel="stylesheet">
  <link rel="stylesheet" href="{{ elixir('assets/css/layouts/auth.css') }}">
  @yield('g_head')
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
            <i class="icon fa fa-unlock-alt hidden-sm hidden-xs"></i>
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
            <div class="panel-heading no-padding"><p class="panel-title text-center">@yield('page_title')</p></div>
            <div class="panel-body">
              <div class="avatar-box">
                <div class="avatar">
                  <img src="">
                </div>
              </div>
              @yield('g_content')
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
  <script src="{{ elixir('assets/js/common/particles-config.js') }}"></script>
  <script>
    var $emailInput = $('[name="email"]');

    $(document).ready(function () {
      var timer;

      setAvatar($emailInput.val());

      $emailInput.on('keyup change', function () {
        if (timer) {
          clearTimeout(timer);
        }

        timer = setTimeout(setAvatar, 300);
      });
    });

    function setAvatar () {
      var inputEmail = $emailInput.val();

      $.ajax({
        url: '{{action("Api\HelperController@getAvatarUrlByEmail") }}',
        dataType: "json",
        data: {
          email: inputEmail
        },
        success: function(data) {
          if (data && data.avatar_url) {
            $('.login-panel .avatar img').attr('src', data.avatar_url);
          }
        }
      });
    }
  </script>
  @yield('g_footer')
</body>
</html>
