@extends('layouts.webpage')

@section('title', 'Privacy Policy - Gomeds Healthcare Platform')

@section('content')
    <div class="hero-section">
        <h2>Gomeds Privacy Policy</h2>
        <p class="hero-subtitle">
            Your privacy and health data security is our top priority
        </p>
        <p style="color: #718096; font-size: 1rem;"><strong>Last updated:</strong> {{ date('F d, Y') }}</p>
    </div>
    
    <div class="content-section">
        <p>
            {!! $privacyPolicy->description !!}
        </p>
    </div>
    
    
@endsection
