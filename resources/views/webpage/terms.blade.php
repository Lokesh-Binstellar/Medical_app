@extends('layouts.webpage')

@section('title', 'Terms & Conditions - Gomeds Healthcare Platform')

@section('content')
    <div class="hero-section">
        <h2>Gomeds Terms & Conditions</h2>
        <p class="hero-subtitle">
            Please read these terms carefully before using our healthcare services.
        </p>
        <p style="color: #718096; font-size: 1rem; margin-top: 1rem;">
            <strong>Last updated:</strong> {{ date('F d, Y') }} | <strong>Effective Date:</strong> {{ date('F d, Y') }}
        </p>
    </div>

    <div style="background: #fef5e7; border-left: 6px solid #f6ad55; padding: 2rem; border-radius: 12px; margin-bottom: 2rem;">
        <div style="display: flex; align-items: center; gap: 1rem;">
            <i class="fas fa-exclamation-triangle" style="color: #f6ad55; font-size: 1.5rem;"></i>
            <p style="color: #744210; font-weight: 600; margin: 0; font-size: 1.1rem;">
                Important: By using Gomeds services, you agree to these Terms & Conditions
            </p>
        </div>
    </div>

    <div class="content-section">
        {{-- <h3><i class="fas fa-check-circle"></i> 1. Acceptance of Terms</h3> --}}
        <p>
                 {!! $termsAndCondition->description !!}
        </p>
        
    </div>

 



    
    <div class="stats-section">
        <p style="font-style: italic; font-size: 1.2rem; font-weight: 500;">
            <i class="fas fa-handshake" style="margin-right: 0.5rem;"></i>
            By using Gomeds services, you acknowledge that you have read, understood, and agree to be bound by these Terms & Conditions.
        </p>
    </div>
    
    <style>
        @media (max-width: 768px) {
            div[style*="grid-template-columns: 1fr 1fr"] {
                display: block !important;
            }
            
            div[style*="grid-template-columns: 1fr 1fr"] > div {
                margin-bottom: 1.5rem;
            }
        }
    </style>
@endsection
