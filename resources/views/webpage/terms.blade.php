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
        <h3><i class="fas fa-check-circle"></i> 1. Acceptance of Terms</h3>
        <p>
            By using Gomeds healthcare platform, mobile app, or website, you agree to these Terms & Conditions. 
            These terms govern your use of our services including medicine ordering, lab test booking, doctor 
            consultations, and other healthcare services provided by Gomeds Healthcare Pvt Ltd.
        </p>
        <p>
            If you do not agree to these terms, please do not use our services. These terms apply to all users 
            including patients, healthcare providers, and business partners.
        </p>
    </div>

    <div class="content-section">
        <h3><i class="fas fa-hospital"></i> 2. Healthcare Services</h3>
        <p style="margin-bottom: 2rem;">Gomeds provides the following healthcare services:</p>
        <div class="services-grid">
            <div style="text-align: center;">
                <div class="service-icon" style="margin: 0 auto 1rem auto;">
                    <i class="fas fa-pills"></i>
                </div>
                <h4>Online Pharmacy</h4>
                <p>Medicine ordering, prescription upload, and home delivery</p>
            </div>
            <div style="text-align: center;">
                <div class="service-icon" style="margin: 0 auto 1rem auto;">
                    <i class="fas fa-microscope"></i>
                </div>
                <h4>Diagnostic Services</h4>
                <p>Lab test booking, sample collection, and digital reports</p>
            </div>
            <div style="text-align: center;">
                <div class="service-icon" style="margin: 0 auto 1rem auto;">
                    <i class="fas fa-user-md"></i>
                </div>
                <h4>Doctor Consultation</h4>
                <p>Online and offline medical consultations</p>
            </div>
            <div style="text-align: center;">
                <div class="service-icon" style="margin: 0 auto 1rem auto;">
                    <i class="fas fa-file-medical"></i>
                </div>
                <h4>Health Records</h4>
                <p>Digital storage and management of medical records</p>
            </div>
        </div>
    </div>

    <div class="content-section">
        <h3><i class="fas fa-user-check"></i> 3. User Responsibilities</h3>
        
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 2rem; margin-top: 2rem;">
            <div style="background: #f0fff4; padding: 2rem; border-radius: 16px; border-left: 4px solid #48bb78;">
                <h4 style="color: #2d3748; display: flex; align-items: center; gap: 0.5rem;">
                    <i class="fas fa-user-injured" style="color: #48bb78;"></i> For Patients:
                </h4>
                <ul style="margin-top: 1rem;">
                    <li>Provide accurate personal and medical information</li>
                    <li>Upload valid prescriptions from licensed doctors</li>
                    <li>Follow prescribed medication dosage and instructions</li>
                    <li>Keep account credentials secure and confidential</li>
                    <li>Pay for services as per agreed terms</li>
                    <li>Report any adverse drug reactions or side effects</li>
                </ul>
            </div>
            
            <div style="background: #eff6ff; padding: 2rem; border-radius: 16px; border-left: 4px solid #3b82f6;">
                <h4 style="color: #2d3748; display: flex; align-items: center; gap: 0.5rem;">
                    <i class="fas fa-user-md" style="color: #3b82f6;"></i> For Healthcare Providers:
                </h4>
                <ul style="margin-top: 1rem;">
                    <li>Maintain valid medical licenses and certifications</li>
                    <li>Provide accurate medical advice and prescriptions</li>
                    <li>Follow medical ethics and professional standards</li>
                    <li>Maintain patient confidentiality and privacy</li>
                    <li>Report any system issues affecting patient care</li>
                </ul>
            </div>
        </div>
    </div>

    <div class="content-section">
        <h3><i class="fas fa-ban"></i> 4. Prohibited Activities</h3>
        <p style="margin-bottom: 1.5rem;">Users are strictly prohibited from:</p>
        <div style="background: #fef2f2; padding: 2rem; border-radius: 16px; border-left: 4px solid #ef4444;">
            <ul style="margin: 0;">
                <li>Uploading fake or fraudulent prescriptions</li>
                <li>Sharing account credentials with others</li>
                <li>Ordering controlled substances without valid prescription</li>
                <li>Providing false medical information</li>
                <li>Using the platform for illegal drug activities</li>
                <li>Attempting to hack or compromise system security</li>
                <li>Reselling medicines purchased through the platform</li>
                <li>Misusing emergency services for non-urgent matters</li>
            </ul>
        </div>
    </div>

    <div class="content-section">
        <h3><i class="fas fa-credit-card"></i> 5. Pricing and Payment</h3>
        
        <div class="services-grid">
            <div style="background: #f0f9ff; padding: 2rem; border-radius: 16px; border-top: 4px solid #0ea5e9;">
                <h4 style="color: #2d3748; display: flex; align-items: center; gap: 0.5rem;">
                    <i class="fas fa-pills" style="color: #0ea5e9;"></i> Medicine Orders:
                </h4>
                <ul style="margin-top: 1rem;">
                    <li>Prices displayed include all applicable taxes</li>
                    <li>Delivery charges may apply based on location</li>
                    <li>Payment via UPI, cards, net banking, and COD</li>
                    <li>Refunds processed as per our refund policy</li>
                </ul>
            </div>
            <div style="background: #f0fdf4; padding: 2rem; border-radius: 16px; border-top: 4px solid #22c55e;">
                <h4 style="color: #2d3748; display: flex; align-items: center; gap: 0.5rem;">
                    <i class="fas fa-microscope" style="color: #22c55e;"></i> Lab Tests:
                </h4>
                <ul style="margin-top: 1rem;">
                    <li>Test prices include sample collection charges</li>
                    <li>Home collection available for additional charges</li>
                    <li>Corporate packages for bulk bookings</li>
                    <li>Insurance coverage for eligible tests</li>
                </ul>
            </div>
        </div>
    </div>

    <div class="content-section">
        <h3><i class="fas fa-stethoscope"></i> 6. Medical Disclaimer</h3>
        <div style="background: #fefce8; padding: 2rem; border-radius: 16px; border-left: 4px solid #eab308;">
            <p style="margin-bottom: 1rem;">
                Gomeds is a healthcare platform that facilitates access to medical services. We do not practice medicine 
                or provide medical advice directly. All medical advice, prescriptions, and treatment recommendations 
                come from licensed healthcare providers on our platform.
            </p>
            <p style="margin: 0; font-weight: 600; color: #92400e;">
                <i class="fas fa-exclamation-triangle" style="color: #eab308; margin-right: 0.5rem;"></i>
                In case of medical emergencies, contact local emergency services immediately rather than relying solely on our platform.
            </p>
        </div>
    </div>

    <div class="content-section">
        <h3><i class="fas fa-truck"></i> 7. Delivery and Returns</h3>
        
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 2rem; margin-top: 2rem;">
            <div style="background: #fef3c7; padding: 2rem; border-radius: 16px;">
                <h4 style="color: #2d3748; display: flex; align-items: center; gap: 0.5rem;">
                    <i class="fas fa-pills" style="color: #f59e0b;"></i> Medicine Delivery:
                </h4>
                <ul style="margin-top: 1rem;">
                    <li>Standard delivery within 24-48 hours</li>
                    <li>Express delivery available in select cities</li>
                    <li>Cold chain for temperature-sensitive medicines</li>
                    <li>Returns accepted for damaged or wrong medicines</li>
                </ul>
            </div>
            <div style="background: #ddd6fe; padding: 2rem; border-radius: 16px;">
                <h4 style="color: #2d3748; display: flex; align-items: center; gap: 0.5rem;">
                    <i class="fas fa-vial" style="color: #8b5cf6;"></i> Lab Sample Collection:
                </h4>
                <ul style="margin-top: 1rem;">
                    <li>Home collection as per your convenience</li>
                    <li>Trained phlebotomists for safe collection</li>
                    <li>Proper sample handling protocols</li>
                    <li>Re-collection in case of sample rejection</li>
                </ul>
            </div>
        </div>
    </div>

    <div class="contact-info">
        <h3><i class="fas fa-phone"></i> Contact Information</h3>
        <p style="margin-bottom: 2rem;">For questions about these Terms & Conditions:</p>
        <div class="services-grid">
            <div class="contact-item">
                <div class="contact-icon">
                    <i class="fas fa-gavel"></i>
                </div>
                <div>
                    <h4 style="margin: 0 0 0.5rem 0; color: #2d3748;">Legal Department</h4>
                    <p style="margin: 0; color: #667eea;">legal@gomeds.in</p>
                </div>
            </div>
            <div class="contact-item">
                <div class="contact-icon">
                    <i class="fas fa-headset"></i>
                </div>
                <div>
                    <h4 style="margin: 0 0 0.5rem 0; color: #2d3748;">Customer Support</h4>
                    <p style="margin: 0; color: #667eea;">support@gomeds.in</p>
                </div>
            </div>
            <div class="contact-item">
                <div class="contact-icon">
                    <i class="fas fa-phone"></i>
                </div>
                <div>
                    <h4 style="margin: 0 0 0.5rem 0; color: #2d3748;">Phone Support</h4>
                    <p style="margin: 0; color: #667eea;">1800-123-GOMEDS</p>
                </div>
            </div>
            <div class="contact-item">
                <div class="contact-icon">
                    <i class="fas fa-map-marker-alt"></i>
                </div>
                <div>
                    <h4 style="margin: 0 0 0.5rem 0; color: #2d3748;">Office Address</h4>
                    <p style="margin: 0; color: #718096;">123 Health Plaza, Cyber City, Gurgaon, Haryana 122002</p>
                </div>
            </div>
        </div>
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
