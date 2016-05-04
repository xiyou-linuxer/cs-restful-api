<!DOCTYPE html>
<html lang="zh-CN">
<head>
  <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
</head>
<body>
  <p>西邮Linux兴趣小组统一授权中心，信息同步中...</p>
  <form method="post" action="{{route('oauth.authorize.post', $params)}}">
    {!! csrf_field() !!}
    <input type="hidden" name="client_id" value="{{$params['client_id']}}">
    <input type="hidden" name="redirect_uri" value="{{$params['redirect_uri']}}">
    <input type="hidden" name="response_type" value="{{$params['response_type']}}">
    <input type="hidden" name="state" value="{{$params['state']}}">
    <input type="hidden" name="scope" value="{{$params['scope']}}">
    <input type="hidden" name="approve" value="1">
  </form>
  <script>
    document.forms[0].submit();
  </script>
</body>
</html>
