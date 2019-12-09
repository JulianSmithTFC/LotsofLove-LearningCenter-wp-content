<?php
/*
Template Name: Home Page
*/
get_header();
?>


<?php echo do_shortcode("[rev_slider alias=\"Home Page Slider\"]"); ?>

    <div class="container-fluid home-blockOne">
        <div class="container" align="center">
            <br/>
            <br/>
            <br/>
            <h1 class="home-blockOne-heading">Welcome to Lots of Love Learning Center!</h1>
            <p class="home-blockOne-bodyText">Lots of Love Learning Center believe that children are unique people and their individuality is to be acknowledged and respected. Our philosophy is to encourage the children and to give them a sense of self-respect and self-worth. We provide children with opportunities and developmentally appropriate activities needed to develop socially, emotionally, physically, and intellectually.</p>
            <br/>
            <a href="https://lotsoflove-learningcenter.com/enrollment-information/">
            <button type="button" class="btn btn-primary home-blockOne-button">Enroll Here</button>
            </a>
            <br/>
            <br/>
        </div>
    </div>

    <div class="container-fluid" align="center">
        <br/>
        <br/>
        <br/>
        <div class="container">
            <h2 class="home-blockTwo-heading">Our Programs</h2>
            <p class="home-blockTwo-subHeading">Our Featured Programs are selected through a rigorous process and
                uniquely created for each semester.</p>
        </div>

        <?php

        $currentID = get_the_ID();

        $args = array(
            'post_type'   => 'programs',
            'post_status' => 'publish',
            'meta_key' => 'order',
            'orderby' => 'meta_value',
            'order'	=> 'ASC',
            'post__not_in' => array($currentID),

        );

        session_start();

        $programs = new WP_Query( $args );
        if( $programs->have_posts() ) :

            ?>
            <?php
            $counter = 0;

            while( $programs->have_posts() ) : $programs->the_post();
                $description = get_field('program_description');
                $color = get_field('program_color');
                if ($counter == 0){
                  ?>
                    <div class="row display-flex justify-content-center" align="center">
                    <?php
                }
                elseif($counter == 3){
                    ?>
                    <div class="row display-flex justify-content-center" align="center">
                    <?php
                }
                elseif($counter == 6){
                    ?>
                        <div class="row display-flex justify-content-center" align="center">
                            <?php
                }

                ?>
                    <div class="col-lg-4 col-md-12 col-sm-12 col-xs-12 home-blockTwo-containers align-self-center ">
                        <!-- Card -->
                        <div class="card featured-fix" style="background-color:<?php echo $color ?> !important;">

                            <!-- Card content -->
                            <div class="card-body home-blockTwo-cardBody featured-fix">

                                <!-- Title -->
                                <h3 class="card-title home-blockTwo-cardTitles"><?php echo the_title(); ?></h3>
                                <!-- Text -->
                                <p class="card-text home-blockTwo-cardDes"><?php echo $description;
                                ?></p>
                                <!-- Button -->
                                <a href="<?php echo get_permalink(); ?>"
                                   class="btn btn-primary home-blockTwo-cardButton" style="background-color:<?php
                                echo $color ?> !important;">Learn More</a>

                            </div>

                        </div>
                        <!-- Card -->
                    </div>
            <?php
            if ($counter == 2){
            ?>
                </div>
            <?php
            }
            elseif($counter == 5){
                ?>
                </div>
                <?php
            }
            elseif($counter == 8){
                ?>
                </div>
                <?php
            }
                ++$counter;

                $_SESSION["counter"] = $counter;
            endwhile;
        endif;

        wp_reset_query();
        wp_reset_postdata();

        $test = $_SESSION["counter"];

        if (($test != 2) and ($test != 6) and ($test != 8)){
            ?>
            </div>
            <?php
        }
        session_unset();
        session_destroy();

        ?>
<br/>
<br/>
<br/>

    <div class="row display-flex">
        <div class="container-fluid" align="center">
            <div class="container">
                <div class="row display-flex " align="left">
                    <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 home-blockThree-leftContainer" style="">
                        <div class="featured-fix">
                            <h2 class="home-blockThree-heading">Contact Us!</h2>
                            <h3 class="home-blockThree-subHeading">Lots of Love Learning Center</h3>
                            <br/>
                            <div class="container">
                                <div class="row">
                                    <div class="col-lg-2 col-md-2 col-sm-12 col-xs-12" align="center">
                                        <a href="tel:314-381-8994" class="home-blockThree-iconLink">
                                        <i class="fas fa-phone contact-option-icon fa-2x home-blockThree-icon"></i>
                                        </a>
                                    </div>
                                    <div class="col-lg-10 col-md-10 col-sm-12 col-xs-12">
                                        <h3 class="home-blockThree-contactInfo"><a href="tel:314-381-8994" class="home-blockThree-contactLink">(314)-381-8994</a></h3>
                                    </div>
                                </div>
                                <br/>
                                <div class="row">
                                    <div class="col-lg-2 col-md-2 col-sm-12 col-xs-12" align="center">
                                        <a href="mailto:lotsoflovelearning@gmail.com" target="_top" class="home-blockThree-iconLink">
                                        <i class="fas fa-envelope-open contact-option-icon fa-2x home-blockThree-icon"></i>
                                        </a>
                                    </div>
                                    <div class="col-lg-10 col-md-10 col-sm-12 col-xs-12">
                                        <h3 class="home-blockThree-contactInfo"><a href="mailto:lotsoflovelearning@gmail.com" target="_top" class="home-blockThree-contactLink">lotsoflovelearning@gmail.com</a></h3>
                                    </div>
                                </div>
                                <br/>
                                <br/>
                                <div class="row">
                                    <div class="col-lg-2 col-md-2 col-sm-12 col-xs-12" align="center">
                                        <a href="https://goo.gl/maps/1j24NmgVzXKduEFq7" target="_blank" class="home-blockThree-iconLink">
                                        <i class="fas fa-map-marker-alt contact-option-icon fa-2x home-blockThree-icon"></i>
                                        </a>
                                    </div>
                                    <div class="col-lg-10 col-md-10 col-sm-12 col-xs-12">
                                        <h3 class="home-blockThree-contactInfo"><a href="https://goo.gl/maps/1j24NmgVzXKduEFq7" target="_blank" class="home-blockThree-contactLink">7436 West Florissant, <br/>St. Louis, MO 63136</a></h3>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 home-blockThree-rightContainer">
                        <div class="featured-fix">

                            <!--Google map-->
                            <div id="map-container-google-1" class="z-depth-1-half map-container featured-fix">
                                <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3112.7682872245555!2d-90.27450018438755!3d3.72312856480194!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x87df4a3a66b1df19%3A0xbd438d967a9e5be!2sLots+of+Love+Learning+Center!5e0!3m2!1sen!2sus!4v1557103863686!5m2!1sen!2sus" frameborder="0"
                                        style="border:0" allowfullscreen></iframe>
                            </div>
                            <!--Google Maps-->
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <br/>
        <br/>
        <br/>
    </div>

    <div class="container-fluid">
        <div class="container">

        </div>
    </div>

<?php
get_footer();