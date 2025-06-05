@extends('layouts.webpage')

@section('title', 'Contact Us - Gomeds Healthcare Platform')

@section('content')
    <div class="hero-section">
        <h2>Contact Gomeds Healthcare</h2>
        <p class="hero-subtitle">
            Need help with medicines, lab tests, or doctor consultation? Our healthcare experts are here to assist you 24/7.
        </p>
    </div>
    
     <div class="content-section">
        <p>
           {!! $contactUs->description !!}
        </p>
    </div>

    
    <style>
        @media (max-width: 768px) {
            div[style*="grid-template-columns: 1fr 1fr"] {
                display: block !important;
            }
            

        }
    </style>
@endsection
