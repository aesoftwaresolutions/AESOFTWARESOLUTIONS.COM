<?php
/*
Template Name: Front Page
*/
get_header();
?>

<div class="front-page-container">
    <div class="hero-section">
        <ae-greetings 
            greeting-title="Transform Your Business with AI"
            greeting-text="We build custom AI solutions, intelligent chatbots, and automated workflows that drive growth and efficiency. Discover the future of business with AE Softwares."
        ></ae-greetings>
        <div class="iphone-container">
            <div class="iphone-screen">
                <ae-assistant></ae-assistant>
            </div>
            <svg class="iphone-svg" viewBox="0 0 375 812" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M0 54C0 24.1766 24.1766 0 54 0H321C350.823 0 375 24.1766 375 54V758C375 787.823 350.823 812 321 812H54C24.1766 812 0 787.823 0 758V54Z" fill="#2E2E2E"/>
                <path fill-rule="evenodd" clip-rule="evenodd" d="M19 54C19 35.2223 35.2223 19 54 19H321C339.778 19 356 35.2223 356 54V758C356 776.778 339.778 793 321 793H54C35.2223 793 19 776.778 19 758V54ZM110 30H265C266.105 30 267 30.8954 267 32V42C267 43.1046 266.105 44 265 44H110C108.895 44 108 43.1046 108 42V32C108 30.8954 108.895 30 110 30Z" fill="black"/>
                <rect x="367" y="118" width="8" height="35" rx="4" fill="#2E2E2E"/>
                <rect x="367" y="177" width="8" height="60" rx="4" fill="#2E2E2E"/>
                <rect x="367" y="247" width="8" height="60" rx="4" fill="#2E2E2E"/>
            </svg>
        </div>
    </div>
    <ae-products></ae-products>
    <ae-testimonials></ae-testimonials>
    <ae-cta></ae-cta>
</div>

<?php get_footer(); ?>
