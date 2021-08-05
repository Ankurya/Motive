 <div class="sidebar" data-background-color="white" data-active-color="danger">

    <!--
		Tip 1: you can change the color of the sidebar's background using: data-background-color="white | black"
		Tip 2: you can change the color of the active button using the data-active-color="primary | info | success | warning | danger"
	-->

    	<div class="sidebar-wrapper">
            <div class="logo">
                <a class="simple-text">
                    <img src="{{asset('public/admin/img/MO_COIN.png')}}">
                </a>
            </div>

            <ul class="nav">
                <li class="@if(Request::is('dashboard')) active @endif" >
                    <a href="{{url('dashboard')}}">
                        <i class="ti-panel"></i>
                        <p>Dashboard</p>
                    </a>
                </li>
                <li class="sub-menu @if(Request::is('user-list') || Request::is('view-user/*') || Request::is('edit-user/*') || Request::is('organizer-list') || Request::is('view-organizer/*')|| Request::is('edit-organizer/*')) active open-menu @endif" >
                    <a href="javascript:void(0);" onclick="return false;">
                        <i class="ti-user"></i>
                        <p>User Management<i class="fa fa-caret-down pull-right"></i></p>
                    </a>
					<ul class="inner-menu"> 
					<li class="@if(Request::is('user-list')|| Request::is('view-user/*') || Request::is('edit-user/*')) active @endif"><a href="{{url('user-list')}}">
                        <p>User List</p> </a> </li>
					<li class="@if(Request::is('organizer-list')|| Request::is('view-organizer/*') || Request::is('edit-organizer/*')) active @endif"><a href="{{url('organizer-list')}}">
                        <p>Organizer List</p> </a> </li>
					</ul>
                </li>  
               
			   
			   <li class="sub-menu @if(Request::is('private-events') || Request::is('private-view-event-details/*')  || Request::is('edit-private-event-details/*') || Request::is('public-view-event-details/*') || Request::is('public-events') ) active open-menu @endif">
                     <a href="javascript:void(0);" onclick="return false;">
                        <i class="ti-view-list-alt"></i>
                        <p>Event Organizer Management<i class="fa fa-caret-down pull-right"></i></p>
                     </a>
					 <ul class="inner-menu">
                        <li class="@if(Request::is('private-events')|| Request::is('private-view-event-details/*') || Request::is('edit-private-event-details/*')) active @endif">
                           <a href="{{url('private-events')}}">
                              <p>Private Events</p>
                           </a>
                        </li>
                        <li class="@if(Request::is('public-events')|| Request::is('public-view-event-details/*')) active @endif">
                           <a href="{{url('public-events')}}">
                              <p>Public Events</p>
                           </a>
                        </li>
                     </ul>
                  </li>
			   
			   
			   
                <li class="@if(Request::is('payment-list')) active @endif">
                    <a href="{{url('payment-list')}}">
                        <i class="fa fa-money"></i>
                        <p>Payment Management</p>
                    </a>
                </li>
                <li class="@if(Request::is('coin-management')) active @endif">
                    <a href="{{url('coin-management')}}">
                        <i class="ti-money"></i>
                        <p>Coin Management</p>
                    </a>
                </li>
				
                <li class="@if(Request::is('public-interest') || Request::is('edit-public-interest/*')) active @endif">
                    <a href="{{url('public-interest')}}">
                        <i class="ti-map"></i>
                        <p>Public Interest</p>
                    </a>
                </li>
				<li class="@if(Request::is('music-interest'))||Request::is('edit-music-interest/*') active @endif">
                    <a href="{{url('music-interest')}}">
                        <i class="ti-map"></i>
                        <p>Music Interest</p>
                    </a>
                </li>
				<li class="@if(Request::is('event-reports'))||Request::is('event-reports') active @endif">
                    <a href="{{url('event-reports')}}">
                        <i class="ti-map"></i>
                        <p>Event Reports</p>
                    </a>
                </li>
				<li class="@if(Request::is('send-push-message')) active @endif">
                    <a href="{{url('admin/send-push-message')}}">
                        <i class="ti-comment-alt"></i>
                        <p>Send Message</p>
                    </a>
                </li>
				
				<li class="@if(Request::is('maintenance')) active @endif">
                    <a href="{{url('admin/maintenance')}}">
                        <i class="ti-comment-alt"></i>
                        <p>Maintenance</p>
                    </a>
                </li>
			
            </ul>
    	</div>
    </div>