<?php // Template Name: Spring ?>
<?php element('header'); ?>

    <div class="febrewary">
        <div class="febrewary__mobile">
            <?php Img(8541); ?>
            <?php Img(8540); ?>
            <?php Img(8539); ?>
        </div>

        <div class="febrewary__desk">
            <?php Img(8609); ?>

            <div 
                class="febrewary__col-wrap"
                <?php static_bg(
                    "images/mrmikes-desktop-bg.jpg"
                ); ?>
                style="background-position: center; background-size: cover;"
            >
                <section class="febrewary__cols">
                    <div class="col">
                        <?php Img(8610); ?>
                    </div>
                    <div class="col">
                        <?php Img(8538); ?>
                    </div>
                    <div class="col">
                        <?php Img(8536); ?>
                    </div>
                </section>
            </div>
        </div>
    </div>

<?php element('footer'); ?>
