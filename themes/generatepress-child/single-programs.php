<?php
/*
Template Name: Enrollment Page
*/
get_header();
?>


<?php echo do_shortcode("[rev_slider alias=\"Home Page Slider\"]"); ?>
    <div class="container-fluid">
        <div class="row display-flex">
            <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
                <div class="featured-fix programs-sidebar-container">
                    <h3 class="programs-sidebar-title">Programs</h3>
                    <ul class="fa-ul">
                    <?php

                    $currentID = get_the_ID();

                    $args = array(
                        'post_type'   => 'programs',
                        'post_status' => 'publish',
                        'orderby' => 'title',
                        'post__not_in' => array($currentID),

                    );

                    $programsMenu = new WP_Query( $args );
                    if( $programsMenu->have_posts() ) :

                        ?>
                        <?php
                        while( $programsMenu->have_posts() ) : $programsMenu->the_post();
                            $description = get_field('program_description');
                            $color = get_field('program_color');
                            ?>
                            <li>
                                <h3 class="programs-sidebar-headings">
                                    <span class="fa-li" >
                                    <i class="fas fa-chevron-circle-right programs-sidebar-icons" style="color: <?php echo $color
                                    ?>"></i>
                                </span>
                                    <a href="<?php echo get_permalink(); ?>" style="color: <?php echo $color
                                    ?>"><?php echo the_title(); ?></a>
                                </h3>
                            </li>
                        <?php
                        endwhile;
                    endif;
                    wp_reset_query();
                    wp_reset_postdata();
                    ?>
                    </ul>
                </div>
            </div>
            <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                <div class="featured-fix programs-main-container">
                    <div class="container">
                        <h1 class="programs-main-heading"><?php echo the_title();?></h1>
                        <hr class="programs-main-hr" style="background-color:<?php the_field('program_color'); ?>"/>
                        <div class="programs-main-body">
                            <?php the_field('programs_main_content_area'); ?>
                        </div>
                        <a href="https://lotsoflove-learningcenter.com/enrollment-information/">
                            <button type="button" class="btn btn-primary programs-main-button" style="background-color:<?php the_field('program_color'); ?> !important;">Enroll Here!</button>
                        </a>
                        <hr class="programs-other-hr" style="background-color:<?php the_field('program_color'); ?> !important;"/>

                        <h2 class="programs-other-heading">Other Programs</h2>

                        <?php

                        $currentID = get_the_ID();

                        $args = array(
                            'post_type'   => 'programs',
                            'post_status' => 'publish',
                            'orderby' => 'title',
                            'post__not_in' => array($currentID),

                        );

                        $programs = new WP_Query( $args );
                        if( $programs->have_posts() ) :

                            ?>
                            <?php
                            while( $programs->have_posts() ) : $programs->the_post();
                                $description = get_field('program_description');
                                $color = get_field('program_color');
                                ?>
                                <br/>
                                <div class="row">
                                    <div class="col">
                                        <h3 class="programs-other-titles"><?php echo the_title(); ?></h3>
                                        <hr class="programs-other-hrLines" style="background-color:<?php echo $color ?> !important;" />
                                        <p class="programs-other-des">
                                            <?php echo $description ?>
                                        </p>
                                        <a href="<?php echo get_permalink(); ?>">
                                            <button type="button" class="btn btn-primary programs-other-buttons"
                                                    style="background-color:<?php echo $color ?> !important;">Learn More</button>
                                        </a>
                                    </div>
                                </div>
                                <br/>
                            <?php
                            endwhile;
                        endif;
                        wp_reset_query();
                        wp_reset_postdata();
                        ?>

                    </div>
                </div>
            </div>
        </div>
    </div>
<?php
get_footer();