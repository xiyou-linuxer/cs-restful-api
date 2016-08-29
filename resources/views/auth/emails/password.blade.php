亲爱的{{$user->name}}：<br/>

您在 <?$datetime = new DateTime();echo $datetime->format('Y-m-d H:i:s');?> 提交了找回 密码请求。请点击下面的链接重置密码。<br/>

<a href="{{ $link = url('password/reset', $token).'?email='.urlencode($user->getEmailForPasswordReset()) }}"> {{ $link }} </a><br/>

如果您确认您没有进行此操作，请忽略此邮件内容。<br/>
本邮件为西邮Linux兴趣小组统一授权平台自动发送,请勿直接回复。
