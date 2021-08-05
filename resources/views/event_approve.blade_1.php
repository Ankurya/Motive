<div style="margin-bottom: 20px;text-align: center;">
	<img src="{{$message->embed(url('public/website/images/logo-top.png'))}}" alt="MoTiv" height="170" width="170"/>
</div>
<body style="font-family:'Times New Roman';font-size:15px">
	<div>
 		<p style="Margin-top:5px;Margin-bottom:10px">Hello <?php if(!empty($user->name)) echo $user->name; else echo'User'; ?>,</p>
		<p style="Margin-bottom: 15px;">
		<p>Your event has been approved by Admin. In order to proceed with this request. </p>
		<p style="Margin-bottom:10px;"><a href="{{ $url }}"> Please Submit Your </a></p>
		<p style="Margin-bottom:10px;">Best Regards, <br/>
		Your Friends at MoTiv
		</p>	
	</div>
</body>