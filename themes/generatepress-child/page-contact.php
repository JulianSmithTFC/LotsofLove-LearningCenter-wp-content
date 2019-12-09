<?php
/*
Template Name: Contact Page
*/
get_header();
?>


<?php echo do_shortcode("[rev_slider alias=\"Home Page Slider\"]"); ?>

<div class="container" align="center">
    <br/>
    <br/>
    <br/>

    <h1 class="contact-heading">Contact Us</h1>
    <hr class="contact-hr"/>

    <div class="row display-flex">
        <div class="col-sm contact-container-left" align="left">
            <div class="featured-fix">
                <h2 class="contact-option-heading"><i class="fas fa-phone contact-option-icon"></i> Phone - Laticia Benson, Director</h2>
                <hr class="contact-option-hr"/>
                <h3><a href="tel:314-381-8994" class="contact-option-subHeading">(314)-381-8994</a></h3>
                <br/>
                <h2 class="contact-option-heading"><i class="fas fa-envelope-open contact-option-icon"></i> E-mail</h2>
                <hr class="contact-option-hr"/>
                <h3><a href="mailto:lotsoflovelearning@gmail.com" target="_top" class="contact-option-subHeading">lotsoflovelearning@gmail.com</a></h3>
                <br/>
                <h2 class="contact-option-heading"><i class="fas fa-map-marker-alt contact-option-icon"></i> Address</h2>
                <hr class="contact-option-hr"/>
                <h3><a href="https://goo.gl/maps/1j24NmgVzXKduEFq7" target="_blank" class="contact-option-subHeading">7436 West
                        Florissant, <br/>St. Louis, MO 63136</a></h3>
                <br/>
                <h2 class="contact-option-heading"><i class="fas fa-hourglass-half contact-option-icon"></i> Hours</h2>
                <hr class="contact-option-hr"/>
                <h3 class="contact-option-subHeading">Monday - Friday: <br/>6:00 a.m. to 6:00 p.m.</h3>
                <br/>
            </div>
        </div>
        <div class="col-sm contact-container-right">
            <div class="featured-fix">
                <!--Google map-->
                <div id="map-container-google-1" class="z-depth-1-half map-container featured-fix">
                    <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3112.7682872245555!2d-90.27450018438755!3d38.72312856480194!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x87df4a3a66b1df19%3A0xbd438d967a9e5be!2sLots+of+Love+Learning+Center!5e0!3m2!1sen!2sus!4v1557103863686!5m2!1sen!2sus" frameborder="0"
                            style="border:0" allowfullscreen></iframe>
                </div>
                <!--Google Maps-->
            </div>
        </div>
    </div>

    <br/>
    <br/>
    <br/>
</div>

<?php
get_footer();