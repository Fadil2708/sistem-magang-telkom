@extends('layouts.public')

@section('content')
@include('landing.hero')
@include('landing.testimonials')
@include('landing.offer-slider')
@include('landing.how-it-works')
@include('landing.timeline')
@include('landing.stats')
@include('landing.faq')
<x-partner-marquee/>
@include('landing.gallery')
@include('landing.vacancies')
@include('landing.cta')
@include('landing.back-to-top')
@endsection
