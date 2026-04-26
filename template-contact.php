<?php
/*
Template Name: Contact Page
*/
get_header();
?>

<div class="content-area">
    <main class="site-main">
        <div class="contact-page-container">
            <div class="contact-info">
                <h1 class="entry-title">Get in Touch</h1>
                <p class="section-subtitle">We're here to answer your questions and help you get started with your next AI project. The fastest way to get information is by interacting with our intelligent AE Assistant. It can provide you with detailed service information, answer frequently asked questions, and even schedule a consultation.</p>
                <p>For more complex inquiries, you can reach us through the following channels:</p>
                <ul>
                    <li><strong>Email:</strong> contact@aesoftwaresolutions.com</li>
                    <li><strong>Phone:</strong> (555) 123-4567</li>
                </ul>
            </div>
            <div class="contact-assistant">
                 <div class="iphone-container">
                    <div class="iphone-screen">
                        <ae-assistant></ae-assistant>
                    </div>
                    <svg class="iphone-svg" viewBox="0 0 400 794" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M0 59C0 26.4152 26.4152 0 59 0H341C373.585 0 400 26.4152 400 59V735C400 767.585 373.585 794 341 794H59C26.4152 794 0 767.585 0 735V59Z" fill="#1C1C1E"/>
                        <path d="M13.5 61C13.5 35.2324 35.2324 13.5 61 13.5H339C364.768 13.5 386.5 35.2324 386.5 61V733C386.5 758.768 364.768 780.5 339 780.5H61C35.2324 780.5 13.5 758.768 13.5 733V61Z" fill="#000000" stroke="#333333" stroke-width="1"/>
                        <rect x="392" y="116" width="8" height="35" rx="4" fill="#1C1C1E"/>
                        <rect x="392" y="177" width="8" height="60" rx="4" fill="#1C1C1E"/>
                        <rect x="392" y="247" width="8" height="60" rx="4" fill="#1C1C1E"/>
                        <path d="M119.5 32H280.5C281.052 32 281.5 32.4477 281.5 33V43C281.5 43.5523 281.052 44 280.5 44H119.5C118.948 44 118.5 43.5523 118.5 43V33C118.5 32.4477 118.948 32 119.5 32Z" fill="#1C1C1E"/>
                    </svg>
                </div>
            </div>
        </div>
    </main>
</div>

<?php get_footer(); ?>
