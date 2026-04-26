<?php
/*
Template Name: About Page
*/
get_header();
?>

<div class="content-area">
    <div class="content-container">
        <h1 class="page-title">About AE Softwares</h1>
        <div class="about-section">
            <h2>Our Mission</h2>
            <p>Our mission is to empower businesses of all sizes with the transformative power of artificial intelligence. We believe that custom-tailored AI solutions are the key to unlocking unprecedented levels of efficiency, innovation, and customer satisfaction. We are committed to building intelligent systems that are not only powerful but also intuitive, accessible, and seamlessly integrated into your existing workflows.</p>
        </div>
        <div class="about-section">
            <h2>Our Values</h2>
            <ul>
                <li><strong>Innovation:</strong> We are constantly exploring the cutting edge of AI to bring you the most advanced and effective solutions.</li>
                <li><strong>Partnership:</strong> We work collaboratively with our clients, building strong relationships to understand their unique challenges and goals.</li>
                <li><strong>Integrity:</strong> We are committed to transparency and ethical practices in everything we do. Your trust is our most valuable asset.</li>
                <li><strong>Excellence:</strong> We are passionate about quality and are relentless in our pursuit of excellence in our products, services, and support.</li>
            </ul>
        </div>
         <ae-testimonials 
            show-all="true"
            title="What Our Clients Say"
        ></ae-testimonials>
    </div>
</div>

<?php get_footer(); ?>
