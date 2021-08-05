<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Guest Ticket</title>
<link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i,800&display=swap" rel="stylesheet">
</head>
<body>
  <table width="600" border="0" align="center" cellpadding="0" cellspacing="0" style="background:#efefef;
      border: 10px solid #fff;
">
  <tr>
    <td width="20" align="left" valign="top">
      <table width="100%" border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td width="20" align="left" valign="top">&nbsp;</td>
        <td align="center" valign="top" style="padding:10px 0;">
          <a href="#" style="border:0; outline:0;"><img src="{{$message->embed(url('public/website/images/MO_COIN.png'))}}" alt="" width="200"/></a>
        </td>
        <td width="20" align="left" valign="top">&nbsp;</td>
      </tr>
    </table>
  </td>
  </tr>
  <tr>
    <td align="left" valign="top" style="background:#efefef; padding:0px 20px;">
      <table width="100%" border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td align="left" valign="top">
          <table width="100%" border="0" cellspacing="0" cellpadding="0" style="background: #fff;
    border: 1px solid #ccc;border-top: 49px solid #daaa16;border-top-left-radius: 36px;
    border-top-right-radius: 36px;border-bottom-right-radius: 29px;
    border-bottom-left-radius: 29px;">
          
          <tr>
             <td align="center" valign="top" style="padding:20px 0;">
          <a href="#" style="border:0; outline:0;"><img src="{{$message->embed(url('public/website/mail.png'))}}" alt="" width=""/></a>
        </td>
          </tr>
          <tr>
            <td align="left" valign="top" style="font-family: 'Open Sans', sans-serif; font-size:18px; color:#474747; font-weight: 700;text-align:center;">Hello, {{$user_data['name'] ?? ''}}</td>
          </tr>
           <tr>
            <td height="10" align="left" valign="top"></td>
          </tr>
          <tr>
            <td height="10" align="left" valign="top"></td>
          </tr>
          <tr>
            <td height="10" align="left" valign="top"></td>
          </tr>
          <!-- <?php //dd($user_data); ?> -->
          <tr>
            <td align="center" valign="top" style="font-family: 'Open Sans', sans-serif; font-size:18px; color:#ff8a00; text-transform: uppercase; font-weight: 700;">Event Details</td>
          </tr>
          <tr>
            <td height="10" align="left" valign="top"></td>
          </tr>
           <tr>
             <td align="center" valign="top" style="padding:20px 0;">
          <a href="#" style="border:0; outline:0;"><img src="{{$message->embed($user_data['ticket'])}}" alt="" width=""/></a>
        </td>
          </tr>
                 

          <tr>
            <td align="left" valign="top">&nbsp;</td>
          </tr>
           <tr>
            <td align="left" valign="top">&nbsp;</td>
          </tr>

          <tr>
            <td align="center" valign="top" style="font-family: 'Open Sans', sans-serif; font-size:16px; color:#ff8a00;">Event Name</td>
          </tr>
          <tr>
            <td align="center" valign="top" style="font-family: 'Open Sans', sans-serif; font-size:18px; color:#2c2c2c;"><?php if(!empty($user_data['event_name'])) echo $user_data['event_name']; else echo'Facebook'; ?></td>
          </tr>
           <tr>
            <td align="left" valign="top">&nbsp;</td>
          </tr>
          <tr>
            <td align="center" valign="top" style="font-family: 'Open Sans', sans-serif; font-size:16px; color:#ff8a00;">Event Location</td>
          </tr>
          <tr>
            <td align="center" valign="top" style="font-family: 'Open Sans', sans-serif; font-size:18px; color:#2c2c2c; font-weight: 700;"><?php if(!empty($user_data['address'])) echo $user_data['address']; else echo'N/A'; ?></td>
          </tr>
           <tr>
            <td align="left" valign="top">&nbsp;</td>
          </tr>
          <tr>
            <td align="center" valign="top" style="font-family: 'Open Sans', sans-serif; font-size:16px; color:#ff8a00;">Event Date</td>
          </tr>
          <tr>
            <td align="center" valign="top" style="font-family: 'Open Sans', sans-serif; font-size:18px; color:#2c2c2c; font-weight: 700;"><?php if(!empty($user_data['event_date'])) echo $user_data['event_date']; else echo'N/A'; ?></td>
          </tr>
            <tr>
            <td align="left" valign="top">&nbsp;</td>
          </tr>
           <tr>
            <td align="center" valign="top" style="font-family: 'Open Sans', sans-serif; font-size:16px; color:#ff8a00;">Event Time</td>
          </tr>
          <tr>
            <td align="center" valign="top" style="font-family: 'Open Sans', sans-serif; font-size:18px; color:#2c2c2c; font-weight: 700;"><?php if(!empty($user_data['event_time'])) echo $user_data['event_time']; else echo'N/A'; ?></td>
          </tr>
            <tr>
            <td align="left" valign="top">&nbsp;</td>
          </tr>
            <tr>
            <td align="center" valign="top" style="font-family: 'Open Sans', sans-serif; font-size:16px; color:#ff8a00;">Guest ID</td>
          </tr>
          <tr>
            <td align="center" valign="top" style="font-family: 'Open Sans', sans-serif; font-size:18px; color:#2c2c2c; font-weight: 700;"><?php if(!empty($user_data['user_id'])) echo $user_data['user_id']; else echo'N/A'; ?></td>
          </tr>
           <tr>
            <td align="left" valign="top">&nbsp;</td>
          </tr>
          <!-- <tr>
            <td align="center" valign="top" style="font-family: 'Open Sans', sans-serif; font-size:18px; color:#2c2c2c; font-weight: 700;">ID required - Age - Dress code</td>
          </tr> -->
           <tr>
            <td align="left" valign="top">&nbsp;</td>
          </tr>
          <td align="left" valign="top" style="text-align: center;">
             <table width="100%" border="0" cellspacing="0" cellpadding="0">
           <tr>
            <a href="#" style="border:0; outline:0; margin-right: 12px;"><img src="{{$message->embed(url('public/website/google.png'))}}" alt="" width=""/></a>
             <a href="#" style="border:0; outline:0;"><img src="{{$message->embed(url('public/website/apple.png'))}}" alt="" width=""/></a>
          </tr>
           <tr>
            <td align="left" valign="top">&nbsp;</td>
          </tr>
          </table>



                  

      </td>
      </tr>
      <tr>
       
      </tr>
    
    </table>
  </td>
  </tr>


  <tr>
    <td align="left" valign="top" style="background:#f3f3f3; padding:20px; text-align:center;">
      <table width="100%" border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td align="center" valign="top">
          <a href="http://facebook.com" target="_blank" style="border:0; outline:0; text-decoration:none;"><img src="{{$message->embed(url('public/website/facebook.png'))}}" alt=""/></a> &nbsp;
            <a href="http://snapchat.com" target="_blank" style="border:0; outline:0; text-decoration:none;"><img src="{{$message->embed(url('public/website/snapchat.png'))}}" alt=""/></a> &nbsp;
            <a href="http://twitter.com" target="_blank" style="border:0; outline:0; text-decoration:none;"><img src="{{$message->embed(url('public/website/twitter.png'))}}" alt=""/></a> &nbsp;
            <a href="http://instagram.com" target="_blank" style="border:0; outline:0; text-decoration:none;"><img src="{{$message->embed(url('public/website/instagram.png'))}}" alt=""/></a>
        </td>
      </tr>
      <tr>
        <td align="center" valign="top" style="font-family:arial, sans-serif; font-size:13px; color:#727272; padding-top:10px;">© MoTiv <?php  $year=Date('Y'); echo $year ?></td>
      </tr>
    </table></td>
  </tr>
</table>

</body>
</html>
