<?php // Template Name: Febrewary ?>
<?php element('header'); ?>

    <div class="febrewary">
        <div class="febrewary__mobile">
            <?php Img(6625); ?>
            <?php Img(6626); ?>
            <?php Img(6627); ?>
            <?php Img(6628); ?>
        </div>

        <div class="febrewary__desk">
            <?php Img(6619); ?>

            <div 
                class="febrewary__col-wrap"
                <?php static_bg(
                    "images/MM_Feb_desktop_bottom_background.png"
                ); ?>
                style="background-position: center; background-size: cover;"
            >
                <section class="febrewary__cols">
                    <div class="col">
                        <?php Img(6621); ?>
                    </div>
                    <div class="col">
                        <?php Img(6620); ?>
                    </div>
                    <div class="col">
                        <?php Img(6622); ?>
                    </div>
                </section>
            </div>
        </div>
    </div>

<?php element('footer'); ?>
