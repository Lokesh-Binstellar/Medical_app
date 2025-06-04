@extends('layouts.webpage')

@section('title', 'Gomeds - Digital Healthcare Platform')

@section('content')
    <div class="hero-section">
        <h2>Welcome to Gomeds</h2>
        <p class="hero-subtitle">
            India's leading digital healthcare platform connecting patients, pharmacies, and laboratories
        </p>
    </div>
    
  
    
    <div class="services-grid">
        <div class="service-card">
            <div class="service-icon">
                <i class="fas fa-pills"></i>
            </div>
            <h3>Online Pharmacy Services</h3>
            <p>Get your medicines delivered to your doorstep with our comprehensive pharmacy network</p>
            <ul>
                <li>Upload prescription and order medicines online</li>
                <li>24/7 medicine delivery across India</li>
                <li>Genuine medicines with quality guarantee</li>
                <li>Medicine reminders and refill alerts</li>
                <li>Consultation with licensed pharmacists</li>
                <li>Insurance claim assistance</li>
                <li>Special discounts for chronic patients</li>
            </ul>
        </div>
        
        <div class="service-card">
            <div class="service-icon">
                <i class="fas fa-microscope"></i>
            </div>
            <h3>Laboratory & Diagnostic Services</h3>
            <p>Book lab tests from the comfort of your home with certified laboratories</p>
            <ul>
                <li>Home sample collection service</li>
                <li>500+ diagnostic tests available</li>
                <li>Digital reports within 24-48 hours</li>
                <li>Doctor consultation for report analysis</li>
                <li>Health packages for complete checkups</li>
                <li>Corporate health screening programs</li>
                <li>Preventive health monitoring</li>
            </ul>
        </div>
        
        <div class="service-card">
            <div class="service-icon">
                <i class="fas fa-user-md"></i>
            </div>
            <h3>Doctor Consultation</h3>
            <p>Connect with qualified doctors and specialists through our platform</p>
            <ul>
                <li>Video consultation with certified doctors</li>
                <li>Specialist appointments in 15+ medical fields</li>
                <li>Digital prescription and treatment plans</li>
                <li>Follow-up consultations and monitoring</li>
                <li>Emergency medical advice 24/7</li>
                <li>Second opinion from expert doctors</li>
                <li>Mental health and wellness counseling</li>
            </ul>
        </div>
        
        <div class="service-card">
            <div class="service-icon">
                <i class="fas fa-mobile-alt"></i>
            </div>
            <h3>Gomeds Mobile App</h3>
            <p>Download our app for easy access to all healthcare services</p>
            <ul>
                <li>Easy medicine ordering with barcode scanner</li>
                <li>Lab test booking with slot selection</li>
                <li>Digital health records and reports</li>
                <li>Medicine reminder notifications</li>
                <li>Family health profile management</li>
                <li>Emergency contact and SOS features</li>
                <li>Health tips and wellness articles</li>
            </ul>
        </div>
    </div>
    
    <div class="content-section">
        <h3><i class="fas fa-star"></i> Why Choose Gomeds?</h3>
        <p>India's most trusted healthcare platform offering comprehensive medical services with the highest quality standards.</p>
        <div class="services-grid" style="margin-top: 2rem;">
            <div style="text-align: center;">
                <div class="service-icon" style="margin: 0 auto 1rem auto;">
                    <i class="fas fa-shield-check"></i>
                </div>
                <h4>100% Genuine Medicines</h4>
                <p>All medicines sourced directly from licensed manufacturers</p>
            </div>
            <div style="text-align: center;">
                <div class="service-icon" style="margin: 0 auto 1rem auto;">
                    <i class="fas fa-clock"></i>
                </div>
                <h4>24/7 Support</h4>
                <p>Round-the-clock customer support and emergency assistance</p>
            </div>
            <div style="text-align: center;">
                <div class="service-icon" style="margin: 0 auto 1rem auto;">
                    <i class="fas fa-truck"></i>
                </div>
                <h4>Fast Delivery</h4>
                <p>Express delivery within 2-4 hours in major cities</p>
            </div>
            <div style="text-align: center;">
                <div class="service-icon" style="margin: 0 auto 1rem auto;">
                    <i class="fas fa-lock"></i>
                </div>
                <h4>Secure & Private</h4>
                <p>Your health data is protected with bank-level security</p>
            </div>
        </div>
    </div>
@endsection
