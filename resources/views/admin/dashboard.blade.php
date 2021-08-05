@extends('layouts.admin_hf')   
@section('title', '')  
@section('pageContent')


		<div class="custom-breadcrumb">
		<div class="container-fluid">
		<p><a href="{{url('dashboard')}}"> Home</a> <i class="ti-angle-right"></i> <span>Dashboard </span> </p>
		</div>
		</div>


        <div class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-sm-6">
                        <div class="card">
                            <div class="content">
                                <a href="{{url('user-list')}}">
                                <div class="row">
                                    <div class="col-xs-12 text-center">
                                        <div class="icon-big icon-warning text-center">
                                            <i class="ti-user"></i>
                                        </div>
                                        <hr>
                                    </div>
                                    <div class="col-xs-12 footer text-center">
                                        <div class="numbers stats">
                                            <p>User Management</p>
                                        </div>
                                    </div>
                                </div>
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="card">
                            <div class="content">
                                <a href="{{url('private-events')}}">
                                <div class="row">
                                    <div class="col-xs-12 text-center">
                                        <div class="icon-big icon-warning text-center">
                                            <i class="ti-view-list-alt"></i>
                                        </div>
                                        <hr>
                                    </div>
                                    <div class="col-xs-12 footer text-center">
                                        <div class="numbers stats">
                                            <p>Event Organizer Management</p>
                                        </div>
                                    </div>
                                </div>
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="card">
                            <div class="content">
                                <a href="{{url('payment-list')}}">
                                <div class="row">
                                    <div class="col-xs-12 text-center">
                                        <div class="icon-big icon-warning text-center">
                                            <i class="fa fa-money"></i>
                                        </div>
                                        <hr>
                                    </div>
                                    <div class="col-xs-12 footer text-center">
                                        <div class="numbers stats">
                                            <p>Payment Management</p>
                                        </div>
                                    </div>
                                </div>
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="card">
                            <div class="content">
                                <a href="{{url('coin-management')}}">
                                <div class="row">
                                    <div class="col-xs-12 text-center">
                                        <div class="icon-big icon-warning text-center">
                                            <i class="ti-money"></i>
                                        </div>
                                        <hr>
                                    </div>
                                    <div class="col-xs-12 footer text-center">
                                        <div class="numbers stats">
                                            <p>Coin Management</p>
                                        </div>
                                    </div>
                                </div>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
         
            </div>
        </div>

@endsection('pageContent') 