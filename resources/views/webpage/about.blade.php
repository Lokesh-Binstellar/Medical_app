@extends('layouts.webpage')

@section('title', 'About Us - Gomeds Healthcare Platform')

@section('content')
    <div class="hero-section">
        <h2>About Gomeds Healthcare</h2>
        <p class="hero-subtitle">
            Transforming healthcare delivery through innovative digital solutions
        </p>
    </div>
    
    <div class="content-section">
        <p>
          {!! $aboutUs->description !!}
        </p>
    </div>

@endsection
