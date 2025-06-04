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
        <h3><i class="fas fa-shield-alt"></i> 1. Introduction</h3>
        <p>
            At Gomeds Healthcare Pvt Ltd, we are committed to protecting your privacy and personal health information. 
            This Privacy Policy explains how we collect, use, store, and protect your information when you use our 
            healthcare platform for medicine orders, lab test bookings, doctor consultations, and other medical services.
        </p>
        <p>
            We comply with all applicable Indian healthcare and data protection laws, including the Information 
            Technology Act 2000, Digital Personal Data Protection Act 2023, and medical ethics guidelines.
        </p>
    </div>
    
    <div class="content-section">
        <h3><i class="fas fa-database"></i> 2. Information We Collect</h3>
        
        <h4><i class="fas fa-user"></i> Personal Information:</h4>
        <ul>
            <li>Name, age, gender, and contact details</li>
            <li>Address for medicine delivery and lab sample collection</li>
            <li>Identity verification documents (Aadhaar, PAN, etc.)</li>
            <li>Payment information and transaction history</li>
        </ul>
        
        <h4><i class="fas fa-heartbeat"></i> Medical Information:</h4>
        <ul>
            <li>Prescription details and medicine orders</li>
            <li>Medical history and health conditions</li>
            <li>Lab test results and diagnostic reports</li>
            <li>Doctor consultation records and treatment plans</li>
            <li>Insurance information and health card details</li>
        </ul>
        
        <h4><i class="fas fa-mobile-alt"></i> Technical Information:</h4>
        <ul>
            <li>Device information and app usage data</li>
            <li>Location data for delivery and service optimization</li>
            <li>Website and app interaction patterns</li>
        </ul>
    </div>
    
    <div class="content-section">
        <h3><i class="fas fa-cogs"></i> 3. How We Use Your Information</h3>
        <p>We use your information to provide healthcare services including:</p>
        <div class="services-grid">
            <div style="text-align: center;">
                <div class="service-icon" style="margin: 0 auto 1rem auto;">
                    <i class="fas fa-pills"></i>
                </div>
                <h4>Medicine Delivery</h4>
                <p>Processing prescriptions and delivering medicines safely</p>
            </div>
            <div style="text-align: center;">
                <div class="service-icon" style="margin: 0 auto 1rem auto;">
                    <i class="fas fa-microscope"></i>
                </div>
                <h4>Lab Services</h4>
                <p>Booking tests, sample collection, and report delivery</p>
            </div>
            <div style="text-align: center;">
                <div class="service-icon" style="margin: 0 auto 1rem auto;">
                    <i class="fas fa-user-md"></i>
                </div>
                <h4>Doctor Consultation</h4>
                <p>Facilitating online and offline medical consultations</p>
            </div>
            <div style="text-align: center;">
                <div class="service-icon" style="margin: 0 auto 1rem auto;">
                    <i class="fas fa-file-medical"></i>
                </div>
                <h4>Health Records</h4>
                <p>Maintaining digital health records for continuity of care</p>
            </div>
        </div>
    </div>
    
    <div class="content-section">
        <h3><i class="fas fa-share-alt"></i> 4. Information Sharing</h3>
        <p>We may share your information with:</p>
        <ul>
            <li><strong>Healthcare Providers:</strong> Doctors, pharmacists, and lab technicians for treatment</li>
            <li><strong>Partner Pharmacies:</strong> For medicine dispensing and delivery</li>
            <li><strong>Diagnostic Centers:</strong> For lab test processing and reporting</li>
            <li><strong>Insurance Companies:</strong> For claim processing and coverage verification</li>
            <li><strong>Delivery Partners:</strong> For medicine and sample collection/delivery</li>
            <li><strong>Government Authorities:</strong> When required by law or for public health purposes</li>
            <li><strong>Emergency Contacts:</strong> During medical emergencies for patient safety</li>
        </ul>
    </div>
    
    <div class="content-section">
        <h3><i class="fas fa-lock"></i> 5. Data Security</h3>
        <p>We protect your health information through:</p>
        <div class="services-grid">
            <div style="text-align: center;">
                <div class="service-icon" style="margin: 0 auto 1rem auto;">
                    <i class="fas fa-shield-check"></i>
                </div>
                <h4>256-bit SSL Encryption</h4>
                <p>All data transmission is encrypted</p>
            </div>
            <div style="text-align: center;">
                <div class="service-icon" style="margin: 0 auto 1rem auto;">
                    <i class="fas fa-cloud"></i>
                </div>
                <h4>Secure Cloud Storage</h4>
                <p>Regular backups and secure storage</p>
            </div>
            <div style="text-align: center;">
                <div class="service-icon" style="margin: 0 auto 1rem auto;">
                    <i class="fas fa-key"></i>
                </div>
                <h4>Multi-factor Authentication</h4>
                <p>Secure account access controls</p>
            </div>
            <div style="text-align: center;">
                <div class="service-icon" style="margin: 0 auto 1rem auto;">
                    <i class="fas fa-certificate"></i>
                </div>
                <h4>ISO 27001 Certified</h4>
                <p>International security standards</p>
            </div>
        </div>
    </div>
    
    <div class="content-section">
        <h3><i class="fas fa-user-shield"></i> 6. Your Rights</h3>
        <p>You have the right to:</p>
        <ul>
            <li>Access your personal and medical information</li>
            <li>Correct or update inaccurate health data</li>
            <li>Request deletion of your account and data</li>
            <li>Download your health records and reports</li>
            <li>Opt-out of marketing communications</li>
            <li>File complaints about privacy practices</li>
            <li>Withdraw consent for data processing</li>
        </ul>
    </div>
    
    <div class="contact-info">
        <h3><i class="fas fa-phone"></i> Contact Our Privacy Team</h3>
        <div class="services-grid">
            <div class="contact-item">
                <div class="contact-icon">
                    <i class="fas fa-user-shield"></i>
                </div>
                <div>
                    <strong>Privacy Officer</strong><br>
                    privacy@gomeds.in
                </div>
            </div>
            <div class="contact-item">
                <div class="contact-icon">
                    <i class="fas fa-shield-alt"></i>
                </div>
                <div>
                    <strong>Data Protection Officer</strong><br>
                    dpo@gomeds.in
                </div>
            </div>
            <div class="contact-item">
                <div class="contact-icon">
                    <i class="fas fa-phone"></i>
                </div>
                <div>
                    <strong>Privacy Helpline</strong><br>
                    1800-123-PRIVACY (1800-123-774822)
                </div>
            </div>
            <div class="contact-item">
                <div class="contact-icon">
                    <i class="fas fa-map-marker-alt"></i>
                </div>
                <div>
                    <strong>Office Address</strong><br>
                    123 Health Plaza, Cyber City, Gurgaon, Haryana 122002
                </div>
            </div>
        </div>
    </div>
@endsection
