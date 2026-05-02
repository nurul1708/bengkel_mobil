@extends('fe.master')

@section('header')
    @include('fe.booking.header')
@endsection

@section('service')
    @include('fe.booking.service')
@endsection

@section('booking')
    @include('fe.home.booking')
@endsection

@section('action')
    @include('fe.booking.action')
@endsection