@extends('fe.master')

@section('header')
    @include('fe.services.header')
@endsection

@section('service')
    @include('fe.home.service')
@endsection

@section('booking')
    @include('fe.home.booking')
@endsection

@section('testimonial')
@include('fe.home.testimonial')
@endsection
