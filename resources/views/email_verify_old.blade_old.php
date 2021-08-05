<body style="font-family:'Times New Roman';font-size:15px"><div style="margin-bottom: 20px;text-align: center;"><img src="{{ url('public/img/app-logo-for-website.png') }}" alt="Mo-Tiv" height="170" width="170"/>
</div><p style="Margin-top:5px;Margin-bottom:10px">Hello <?php if(!empty($user_data['name'])) echo $user_data['name']; else echo'User'; ?>,</p>
<p style="Margin-bottom: 15px;"><p>Thank you for downloading our app, please verify your email address. </p>		<p style="Margin-bottom:10px;"><a href="{{ $url }}"> Click here to verify your email</a></p>	
<p style="Margin-bottom:10px;">Best Regards, <br/>Your Friends at MoTiv</p></body>
		