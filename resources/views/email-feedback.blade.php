  <!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Contact Us</title>
</head>

<body>
  <table width="800" border="0" align="center" cellpadding="0" cellspacing="0" style="background:#efefef; margin-top:10px;">
  <tr>
    <td width="20" align="left" valign="top">
      <table width="100%" border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td width="20" align="left" valign="top">&nbsp;</td>
        <td align="center" valign="top" style="padding:20px 0;">
        	<a href="javascript:void(0);" style="border:0; outline:0;"><img src="{{$message->embed(url('public/website/images/MO_COIN.png'))}}" alt="" width="100"/></a>
        </td>
        <td width="20" align="left" valign="top">&nbsp;</td>
      </tr>
    </table>
  </td>
  </tr>
  <tr>
    <td height="1" align="left" valign="top" bgcolor="#d9d9d9"></td>
  </tr>
  <tr>
    <td align="left" valign="top" style="background:#efefef; padding:30px 20px;"><table width="100%" border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td align="left" valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="0">
          <tr>
            <td align="left" valign="top">&nbsp;</td>
          </tr>
		  	  <tr>
            <td style="">
              <h2 style="font-family:Arial, sans-serif, 'Helvetica Neue', Helvetica; font-size:20px;background:#efefef; color:#393939; font-weight:600; margin:0; padding-left: 0;">
                Hello, Admin
                      
              </h2><br><br><br>
            </td>
          </tr>
          <tr>
            <td align="left" valign="top">&nbsp;</td>
          </tr>
          <tr>
            <td padding-left="20px;" align="left" valign="top" style="font-family:arial, sans-serif; font-size:15px; color:#474747;">
            <span style="padding-left: 20px;">  New query from user.</span>  <br><br>
            </td>
          </tr>

          <tr >
            <td align="left" valign="top" style="padding-left: 20px;font-family:arial, sans-serif; font-size:15px; color:#474747;">

           <span style="display: block;"> <b style="color:#f05726; display: block;">Query Details:</b></span><br><br>
           
            <span style="display: block;"><b style="padding-left: 0px !important; display: block;">Message</b> :<span style="padding-left: 0px; display: block;"> {{$text_message}}</span></span> <br><br><br>
            </td>
          </tr>

          <tr >
            <td align="left" valign="top" style="padding-left: 20px;font-family:arial, sans-serif; font-size:15px; color:#474747;">

            <span style="display: block;"> <b style="padding-left: 0px !important;">
            <b style="color:#f05726; display: block;">User Details:</b>
          </span> <br><br>
            <span style="display: block;"> <b style="padding-left: 0px !important;">Name</b> : <?php if(!empty($user_data['name'])) echo $user_data['name']; else echo'User'; ?></span><br><br>
            <span style="display: block;"> <b style="padding-left: 0px !important;">Email</b> : <?php if(!empty($user_data['email'])) echo $user_data['email']; else echo'N/A'; ?> </span> <br><br>
            <!-- <span style="display: block;"><b style="padding-left: 0px !important;">Mobile Number</b> :<?php if(!empty($user_data['phone_number'])) echo $user_data['phone_number']; else echo'N/A'; ?></span> --> <br><br>
            </td>
          </tr>

          <tr>
            <td height="10" align="left" valign="top"></td>
          </tr>
                 

          <tr>
            <td align="left" valign="top">&nbsp;</td>
          </tr>
            
		 <tr>
            <td align="left" valign="top">&nbsp;</td>
          </tr>
		   <tr>
            <td align="left" valign="top">&nbsp;</td>
          </tr>
          
        </table></td>
      </tr>
      <tr>
        <td height="40" align="left" valign="top">&nbsp;</td>
      </tr>
    
    </table>
  </td>
  </tr>


  <tr>
    <td align="left" valign="top" style="background:#413e3e; padding:20px; text-align:center;">
      <table width="100%" border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td align="center" valign="top">
        	<a href="http://facebook.com" target="_blank" style="border:0; outline:0; text-decoration:none;"><img src="assets/icon-facebook.png" alt=""/></a> &nbsp;
            <a href="http://twitter.com" target="_blank" style="border:0; outline:0; text-decoration:none;"><img src="assets/icon-twitter.png" alt=""/></a> &nbsp;
            <a href="http://instagram.com" target="_blank" style="border:0; outline:0; text-decoration:none;"><img src="assets/icon-instagram.png" alt=""/></a>
        </td>
      </tr>
      <tr>
        <td align="center" valign="top" style="font-family:arial, sans-serif; font-size:13px; color:#727272; padding-top:10px;">© MoTiv {{date('Y')}}</td>
      </tr>
    </table></td>
  </tr>
</table>

</body>
</html>
