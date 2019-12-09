<?php
/*
Template Name: Enrollment Page
*/
get_header();
?>


<?php echo do_shortcode("[rev_slider alias=\"Home Page Slider\"]"); ?>

    <br/>
    <br/>
    <br/>

    <div class="container entoll-blockOne" align="center">

        <h1 class="entoll-blockOne-heading">Enrollment Information</h1>
        <p class="entoll-blockOne-bodyText">Before the child begins enrollment, the parent will receive a packet of forms to be completed on or before the child’s first day of attendance. All forms in the enrollment packet must be completed in its entirety. A current copy of the child’s immunization record and physical are required.</p>
        <hr class="entoll-blockOne-hr"/>
    </div>
    <div class="container" align="center">
        <h2 class="entoll-blockOne-heading">1) Read Enrollment Requirements</h2>
        <div align="left">
            <p>1) Registration fee ($25.00 one child; $35.00 family)</p>
            <p>2) Tuition payment (paid in advance) or approval letters from State authorizing child care assistance</p>
            <p>3) Completely filled out enrollment packet (all spaces on all forms must be filled in)</p>
            <p>4) All immunizations must be up to date and a copy of immunization records must be on file on the first day of attendance.</p>
            <p>5) A current physical examination form for all children enrolled must be completed and signed by a physician.  If no current physical form is available, a physical examination appointment must be presented and a current physical must be completed and on file within the first 30 days of enrollment!</p>
            <p>6) A complete change of clothing and a small blanket.  (blankets are washed at least once per week onsite)  All items must be marked with child's name in a permanent marker.</p>
        </div>
    </div>
    <div class="container entoll-blockTwo" align="center">
        <h2 class="entoll-blockTwo-heading">2) Download Forms</h2>

        <div class="row">
            <div class="col-sm" align="center">
                <a href="https://lotsoflove-learningcenter.com/wp-content/uploads/2019/05/Income-Eligibility-Form.pdf" target="_blank">
                    <button type="button" class="btn btn-primary entoll-blockTwo-buttons">Income Eligibility Form</button>
                </a>
            </div>
            <div class="col-sm" align="center">
                <a href="https://lotsoflove-learningcenter.com/wp-content/uploads/2019/05/Child-Medical-Examination-Report.pdf" target="_blank">
                    <button type="button" class="btn btn-primary entoll-blockTwo-buttons">Medical Examination Form</button>
                </a>
            </div>
            <div class="col-sm" align="center">
                <a href="https://lotsoflove-learningcenter.com/wp-content/uploads/2019/05/Child-Care-Enrollment-Form.pdf"
                   target="_blank">
                    <button type="button" class="btn btn-primary entoll-blockTwo-buttons">Enrollment Form</button>
                </a>
            </div>
        </div>
    </div>
<br/>
<br/>
<br/>
    <div class="container-fluid" align="center">
        <div class="container entoll-blockThree">
            <div class="row display-flex" align="left">
                <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 entoll-blockThree-container-left">
                    <div class="featured-fix">
                        <div class="featured-fix">

                        </div>
                    </div>
                </div>
                <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 entoll-blockThree-container-right">
                    <div class="featured-fix">
                        <h2 class="entoll-blockThree-heading">3) Fill in the Form</h2>
                        <hr class="entoll-blockThree-hr"/>
                        <div class="entoll-blockThree-formContainer">

                            <form id="contact-form" name="contact-form" action="<?php echo get_stylesheet_directory_uri(); ?>/mail.php" method="POST">

                                <div class="row">
                                    <div class="col">
                                        <input type="text" id="fname" name="fname" class="form-control" placeholder="First name">
                                    </div>
                                    <div class="col">
                                        <input type="text" id="lname" name="lname" class="form-control" placeholder="Last name">
                                    </div>
                                </div>
                                <br/>
                                <input type="email" id="email" name="email" class="form-control mb-4" placeholder="E-mail">
                                <input type="email" id="phone" name="phone" class="form-control mb-4" placeholder="Phone Number">
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
                                    $programNumber = 0;
                                    while( $programsMenu->have_posts() ) : $programsMenu->the_post();
                                        $description = get_field('program_description');
                                        $color = get_field('program_color');
                                        ?>
                                        <div class="custom-control custom-checkbox mb-4">
                                            <input type="checkbox" class="custom-control-input" id="program_<?php echo $programNumber ?>" name="program_<?php echo $programNumber ?>" >
                                            <label class="custom-control-label" for="program_<?php echo $programNumber ?>"><?php echo the_title(); ?></label>
                                        </div>

                                        <?php
                                        $programNumber++;
                                    endwhile;
                                endif;
                                wp_reset_query();
                                wp_reset_postdata();
                                ?>

                                <textarea class="form-control rounded-0"  id="message" name="message" rows="3" placeholder="Message"></textarea>

                            </form>
                            <br/>
                            <div class="text-center text-md-left">
                                <a class="btn btn-primary entoll-blockThree-button" onclick="document.getElementById('contact-form').submit();">Send</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <br/>
    <br/>
    <br/>
<?php
get_footer();