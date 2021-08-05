@extends('website.common')
@section('title', 'Login')

@section('content')
<style type="text/css">
    .forget-new {
    float: right;
    color: #F9D367;
}

.forget-new a {
    color: #F9D367;
}
</style>
        <div class="row new-blue">
            <div class="col-md-6 col-md-offset-3">
                <div class="form-content register less">
                    <div class="form-long_content">
                        
                        <div class="form-info-settings lvert">
                            <form method="post" class="login_form">
                            {{csrf_field()}}
                            <div class="top-build-info">
                               
                                <div class="new-small-logo">
                            <img src="{{url('public/website/images/logo-inner.png')}}">
                             <h2>LOGIN</h2>
                             @include('website.notifications_message')
                            </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label>EMAIL ADDRESS</label>
                                        <input type="text" name="email" autocomplete="off" class="form-control" placeholder="EMAIL ADDRESS">
                                        <span class="text-danger error">{{$errors->first('email')}}</span>
                                      
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label>PASSWORD</label>
                                        <input type="password" name="password" autocomplete="off" class="form-control" placeholder="PASSWORD">
                                        <span class="text-danger error">{{$errors->first('password')}}</span>
                                    </div>
                                </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="forget-new">
                                <a href="{{url('website/forgot-password')}}">Forgot Password ?</a>
                              </div>
                          </div>
                                <div class="new-formula-home-sd">
                                    <button type="submit" class="new-homes">LOGIN</button>
                                    <P><a class="news-hover" href="{{url('website/current-events')}}" style="color:#fff;">Skip Login</a></P>
                                    <P>Don’t have an account?<a class="news-hover" href="{{url('website/register')}}" style="color:#fff;">  Sign up </a></P>
                                    <figure><img src="{{url('public/website/images/login-image.png')}}"></figure>
                                    <ul class="cosial-metting">
                                        <li><a href="javascript:void(0);"><img src="{{url('public/website/images/f1.png')}}"></a></li>
                                        <li><a href="{{url('website/social-login')}}"><img src="{{url('public/website/images/f2.png')}}"></a></li>
                                        <li><a href="javascript:void(0);"><img src="{{url('public/website/images/f3.png')}}"></a></li>

                                    </ul>
                                </div>
                            </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        

  @endsection()








