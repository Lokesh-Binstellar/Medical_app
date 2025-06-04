@extends('layouts.webpage')

@section('title', 'Contact Us - Gomeds Healthcare Platform')

@section('content')
    <div class="hero-section">
        <h2>Contact Gomeds Healthcare</h2>
        <p class="hero-subtitle">
            Need help with medicines, lab tests, or doctor consultation? Our healthcare experts are here to assist you 24/7.
        </p>
    </div>
    
    <div style=" margin-bottom: 3rem;">
        <!-- Contact Form -->
       
        <!-- Contact Information -->
        <div class="content-section">
            <h3><i class="fas fa-info-circle"></i> Contact Information</h3>
            <div class="services-grid">
                
                <div class="contact-item">
                    <div class="contact-icon">
                        <i class="fas fa-envelope"></i>
                    </div>
                    <div>
                        <h4 style="margin: 0; font-size: 1.2rem; color: #2d3748;">Email Us</h4>
                        <p style="margin: 0; color: #667eea; font-weight: 600;">support@gomeds.in</p>
                    </div>
                </div>

                <div class="contact-item">
                    <div class="contact-icon">
                        <i class="fas fa-phone"></i>
                    </div>
                    <div>
                        <h4 style="margin: 0; font-size: 1.2rem; color: #2d3748;">Call Us</h4>
                        <p style="margin: 0; color: #667eea; font-weight: 600;">1800-123-GOMEDS</p>
                        <p style="margin: 0; color: #718096; font-size: 0.9rem;">Mon-Fri: 9:00 AM - 6:00 PM IST</p>
                    </div>
                </div>

                <div class="contact-item">
                    <div class="contact-icon">
                        <i class="fas fa-ambulance"></i>
                    </div>
                    <div>
                        <h4 style="margin: 0; font-size: 1.2rem; color: #2d3748;">Emergency Helpline</h4>
                        <p style="margin: 0; color: #e53e3e; font-weight: 600;">+91 9876543210 (24/7)</p>
                    </div>
                </div>

                <div class="contact-item">
                    <div class="contact-icon">
                        <i class="fas fa-map-marker-alt"></i>
                    </div>
                    <div>
                        <h4 style="margin: 0; font-size: 1.2rem; color: #2d3748;">Head Office</h4>
                        <p style="margin: 0; color: #718096;">
                            123 Health Plaza,<br>
                            Cyber City, Gurgaon,<br>
                            Haryana 122002
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Department Wise Contact -->
    <div class="content-section">
        <h3><i class="fas fa-building"></i> Department Wise Contact</h3>
        <div class="services-grid">
            <div class="contact-item">
                <div class="contact-icon">
                    <i class="fas fa-pills"></i>
                </div>
                <div>
                    <h4 style="margin: 0 0 0.5rem 0; color: #2d3748;">Medicine Orders</h4>
                    <p style="margin: 0; color: #667eea;">medicines@gomeds.in</p>
                    <p style="margin: 0; color: #718096;">1800-123-4663</p>
                </div>
            </div>
            <div class="contact-item">
                <div class="contact-icon">
                    <i class="fas fa-microscope"></i>
                </div>
                <div>
                    <h4 style="margin: 0 0 0.5rem 0; color: #2d3748;">Lab Test Booking</h4>
                    <p style="margin: 0; color: #667eea;">labs@gomeds.in</p>
                    <p style="margin: 0; color: #718096;">1800-123-5663</p>
                </div>
            </div>
            <div class="contact-item">
                <div class="contact-icon">
                    <i class="fas fa-user-md"></i>
                </div>
                <div>
                    <h4 style="margin: 0 0 0.5rem 0; color: #2d3748;">Doctor Consultation</h4>
                    <p style="margin: 0; color: #667eea;">doctors@gomeds.in</p>
                    <p style="margin: 0; color: #718096;">1800-123-6663</p>
                </div>
            </div>
            <div class="contact-item">
                <div class="contact-icon">
                    <i class="fas fa-handshake"></i>
                </div>
                <div>
                    <h4 style="margin: 0 0 0.5rem 0; color: #2d3748;">Business Partnerships</h4>
                    <p style="margin: 0; color: #667eea;">business@gomeds.in</p>
                    <p style="margin: 0; color: #718096;">+91 9876543211</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Emergency Medical Support -->
    <div class="stats-section">
        <h3 style="font-size: 2rem; margin-bottom: 2rem; font-weight: 700;">Emergency Medical Support</h3>
        <p style="font-size: 1.2rem; margin-bottom: 2rem; opacity: 0.95;">
            For urgent medicine requirements or medical emergencies, call our 24/7 helpline: 
            <strong style="font-size: 1.4rem;">+91 9876543210</strong>
        </p>
        <div class="services-grid">
            <div style="text-align: center;">
                <i class="fas fa-clock" style="font-size: 3rem; margin-bottom: 1rem; color: rgba(255,255,255,0.9);"></i>
                <h4 style="color: white; font-size: 1.3rem; margin-bottom: 0.5rem;">Urgent Medicine Delivery</h4>
                <p style="color: rgba(255,255,255,0.9); margin: 0;">Within 2 hours in major cities</p>
            </div>
            <div style="text-align: center;">
                <i class="fas fa-flask" style="font-size: 3rem; margin-bottom: 1rem; color: rgba(255,255,255,0.9);"></i>
                <h4 style="color: white; font-size: 1.3rem; margin-bottom: 0.5rem;">Emergency Lab Tests</h4>
                <p style="color: rgba(255,255,255,0.9); margin: 0;">Same day sample collection and results</p>
            </div>
            <div style="text-align: center;">
                <i class="fas fa-video" style="font-size: 3rem; margin-bottom: 1rem; color: rgba(255,255,255,0.9);"></i>
                <h4 style="color: white; font-size: 1.3rem; margin-bottom: 0.5rem;">Immediate Consultation</h4>
                <p style="color: rgba(255,255,255,0.9); margin: 0;">Connect with doctors instantly</p>
            </div>
        </div>
    </div>
    
    <style>
        @media (max-width: 768px) {
            div[style*="grid-template-columns: 1fr 1fr"] {
                display: block !important;
            }
            
            .contact-form {
                margin-bottom: 2rem;
            }
        }
    </style>
@endsection
