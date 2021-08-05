<!doctype html>
<html>
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Wolf-e</title>
    <link href="css/font-awesome.min.css" rel="stylesheet">
  </head>
  <body style="margin:0;">
    <!-- Start of preheader -->
    <table align="center" cellpadding="0" cellspacing="0" border="0"  bgcolor="#e7f9fb" style="width:650px; margin:0 auto">
      <!--[if mso]>
      css here for outlook
      </style>
      <![endif]-->
      <thead style="background: url(image/template-head-bg.png) no-repeat;background-size: cover;background-position: bottom center;">
        <tr>
          <td align="center" valign="middle" style="background: #000; padding: 30px 0;"><a href="javascript:void(0)" style="border:0; outline:0;"><img src="{{url('public/website/images/logo-top.png')}}"  height="80px" alt="" style="border:0; outline:0; display:block;"/></a></td>
        </tr>
      </thead>
      <tbody style="background-color: #F7F7F7;">
        <tr>
          <td style="padding:50px 15px"><h2 style="font-family:Arial, sans-serif, 'Helvetica Neue', Helvetica; font-size:20px; color:#393939; font-weight:normal; margin:0;">Hello, <?php if(!empty($user_data['name'])) echo $user_data['name']; else echo'User'; ?></h2></td>
        </tr>
        <tr>
          <td style="padding:0 25px"><p style="font-family:Arial, sans-serif, 'Helvetica Neue', Helvetica; font-size:15px; color:#393939; font-weight:normal; margin:0">We have received your request for reset password of MoTiv. In order to proceed with this request, Please follow the link below:.</p></td>
        </tr>
        <tr>
          <td>&ensp;</td>
        </tr>
        <tr>
          <td>&nbsp;</td>
        </tr>
        <tr>
          <td style="padding:0 25px;"><a href="{{ $url }}" style="font-family:Arial, sans-serif, 'Helvetica Neue', Helvetica; font-size:14px; color:#fff; font-weight:normal; margin:0; border-radius: 25px; background: #00aeef; border: 1px solid #00aeef; font-weight: 400; cursor: pointer; text-decoration: none; line-height: 1.42857143; text-align: center; padding: 7px 25px;">Click here for reset your password</a></td>
        </tr>
        <tr>
          <td>&ensp;</td>
        </tr>
        <tr>
          <td>&nbsp;</td>
        </tr>
        <tr>
          <td style="padding:0 25px"><p style="font-family:Arial, sans-serif, 'Helvetica Neue', Helvetica; font-size:15px; color:#393939; font-weight:normal; margin:0">If you don’t want to reset your password, you can ignore this email.</p></td>
        </tr>
        <tr>
          <td style="padding:0 25px"><p style="font-family:Arial, sans-serif, 'Helvetica Neue', Helvetica; font-size:15px; color:#393939; font-weight:normal; margin:0">If you did not request this change, you may want to review your security settings or contact us for assistance.</p></td>
        </tr>
        <tr>
          <td>&ensp;</td>
        </tr>
        <tr>
          <td>&ensp;</td>
        </tr>
        <tr>
          <td>&ensp;</td>
        </tr>
        <tr>
          <td style="padding:0 25px"><h5 style="font-family:Arial, sans-serif, 'Helvetica Neue', Helvetica; font-size:15px; color:#393939; font-weight:normal; margin:0">Best Regards, </h5></td>
        </tr>
        <tr>
          <td style="padding:10px 25px 50px"><h5 style="font-family:Arial, sans-serif, 'Helvetica Neue', Helvetica; font-size:15px; color:#393939; font-weight:normal; margin:0">Your Friends at MoTiv.</h5></td>
        </tr>
      </tbody>
      <tfoot style="background-color: #000";>
      <!--<tr>
        <td>&nbsp;</td>
      </tr>
      <tr>
        <td style="padding:25px 25px 5px"><table cellpadding="0" cellspacing="0" border="0" width="450px" align="center" style="margin:0 auto">
          <tr>
            <td><a href="javascript:void(0)" style="font-family:Arial, sans-serif, 'Helvetica Neue', Helvetica; font-size:12px; color:#fff; text-decoration:none;">Home</a></td>
            <td><a href="javascript:void(0)" style="font-family:Arial, sans-serif, 'Helvetica Neue', Helvetica; font-size:12px; color:#fff; text-decoration:none;">Trip Review</a></td>
            <td><a href="javascript:void(0)" style="font-family:Arial, sans-serif, 'Helvetica Neue', Helvetica; font-size:12px; color:#fff; text-decoration:none;">Trip Assistance</a></td>
            <td><a href="javascript:void(0)" style="font-family:Arial, sans-serif, 'Helvetica Neue', Helvetica; font-size:12px; color:#fff; text-decoration:none;">Terms & Conditions</a></td>
            <td><a href="javascript:void(0)" style="font-family:Arial, sans-serif, 'Helvetica Neue', Helvetica; font-size:12px; color:#fff; text-decoration:none;">Contact Us</a></td>
          </tr>
        </table>
        <tr>
          <td align="center" style="padding:5px 25px"><a href="javascript:void(0)" style="border:0; outline:0;"><img src="images/icon-fb.jpg" alt="Facebook" style="border:0; outline:0;"/></a>&nbsp; <a href="javascript:void(0)" style="border:0; outline:0;"><img src="images/icon-twitter.jpg" alt="Twitter" style="border:0; outline:0;"/></a>&nbsp; <a href="javascript:void(0)" style="border:0; outline:0;"><img src="images/icon-linkedin.jpg" alt="Linkedin" style="border:0; outline:0;"/></a></td>-->
        </tr>
        <tr>
          <td align="center" style="padding:15px 25px; font-family:Arial, sans-serif, 'Helvetica Neue', Helvetica; font-size:11px; color:#9b9b9b">© MoTiv <?php  $year=Date('Y'); echo $year ?></td>
        </tr>
        </tfoot>
      </table>
      <!-- End of preheader -->
    </body>
  </html>