<?php // Template Name: Deeds Well Done Landing Page ?>
<?php element('header'); ?>
<div class="deeds-well-done-landing-page">
    <div class="deeds-well-done-landing-page__container">
        <div class="deeds-header lazyload" style='background-position: center; background-size: cover;'
            <?php echo lazy_background(get_field('top_background_image')) ?>
        >
            <div class="grid-edges">
                <div class="deeds-logo">
                    <img class="lazyload"<?php echo " " . mrm_get_lazy_loaded_image_attrs(get_field('logo')); ?> />
                </div>
            </div>
            <div class="grid-edges--small">
                <?php if(!get_field('success_page')): ?>
                <h1>To Celebrate &amp; Reward the Good Deed Doers</h1>
                <h2>MR MIKES is donating $22,000 across 44 communities in Canada.</h2>
                <p class="red">
                    We invite you to nominate a charity in your community that constantly goes above and beyond, and
                    deserves to be rewarded for the good deeds they do.
                </p>
                <p>Please submit your nomination below. Contest ends December 31, 2019.</p>
                <?php else: ?>
                <div class="success">
                    <?php the_field('success_message'); ?>
                </div>
                <?php endif; ?>
            </div>
        </div>
        <?php if (!get_field('success_page')): ?>
        <div class="grid-edges--small side-icons">
            <div class="side-icon">
                <div class="side-icon__icon">
                    <img src="<?php echo get_stylesheet_directory_uri(); ?>/static/images/dollar_card.png"
                        alt="Dollar card">
                </div>
                <div class="side-icon__content">
                    1 winning charity per community will win a $500 donation from MR MIKES.<br>
                    <h4>($22,000 total across 44 Canadian communities)</h4>
                </div>
            </div>

            <div class="side-icon">
                <div class="side-icon__icon">
                    <img src="<?php echo get_stylesheet_directory_uri(); ?>/static/images/certificate.png"
                        alt="Dollar card">
                </div>
                <div class="side-icon__content">
                    Every nominator will receive a $25 MR MIKES bonus gift card.<br>
                    <h4>(Your bonus gift card will be sent to you within 48 hours of entry. Valid from Jan 2 - Feb 29,
                        2020, excluding Feb 14.)</h4>
                </div>
            </div>
        </div>
        <section class="deeds-form lazyload" style='background-position: center; background-size: cover;'
            <?php echo lazy_background(get_field('bottom_background_image')) ?>>
            <div class="grid-edges">
                <h2 style="text-align: center; margin-bottom: 20px;">
                    Winning Charities
                </h2>
                <?php

                // winning charities
                // this array was created by copy pasting all the rows from the excel file with no formatting (so every row was on a new line with a few spaces between each column)
                // it was pasted into https://t.yctin.com/en/excel/to-php-array/
                $winning_charities = array(
                    0 => array('Airdrie', 'Tails to Tell Animal Rescue Shelter Ltd'),
                    1 => array('Bonnyville', 'Boys & Girls Club of bonnyville'),
                    2 => array('Camrose', 'Camrose and Area Animal Shelter Society'),
                    3 => array('Calgary NW', 'Made by Momma'),
                    4 => array('Chilliwack', 'Meadow Rose'),
                    5 => array('Cochrane', 'Cochrane Search & Rescue'),
                    6 => array('Coquitlam', 'Hope for Freedom'),
                    7 => array('Cranbrook', 'Angel Flight East Kootenay Association'),
                    8 => array('Dauphin', 'Parkland Humane Society'),
                    9 => array('Dawson Creek', 'Peace Pregnancy Support Society'),
                    10 => array('Drayton Valley', 'Drayton Valley Health Services Foundation'),
                    11 => array('Duncan', 'Nourish Cowichan Society'),
                    12 => array('Edmonton', 'Zoe\'s Animal Rescue'),
                    13 => array('Estevan', 'Challenger Baseball'),
                    14 => array('Fort McMurray', 'Autism Society of the RMWB'),
                    15 => array('Fort St John', 'Women\'s Resource Society'),
                    16 => array('Grande Prairie', 'Helping Hands'),
                    17 => array('High River', 'Food for Thought'),
                    18 => array('Hinton', 'Hinton Friendship Center'),
                    19 => array('Kamloops', 'Kamloops Hospice Society'),
                    20 => array('Kitimat', 'Food Share Program'),
                    21 => array('Langford', 'Constable Gerald Breese Centre for Traumatic Life'),
                    22 => array('Langley', 'Langley Memorial Hospital Auxiliary'),
                    23 => array('Martensville', 'Sanctum Care Group'),
                    24 => array('Olds', 'Hope 4 mvc kids Society'),
                    25 => array('Peace River', 'Peace River SPCA'),
                    26 => array('Prince Albert', 'Victoria Hospital Give a Little Life'),
                    27 => array('Prince George', 'Police Victim Services'),
                    28 => array('Quesnel', 'Crooked Leg Ranch'),
                    29 => array('Red Deer', 'Whisker Rescue'),
                    30 => array('Regina', 'The Compassionate Friends'),
                    31 => array('Regina 2', 'Hope Air'),
                    32 => array('Salmon Arm', 'Shuswap Children\'s Association'),
                    33 => array('Saskatoon', 'CHEP'),
                    34 => array('Slave Lake', 'Animal Rescue Committee of Slave Lake'),
                    35 => array('St Catharines', 'Community Crew'),
                    36 => array('Terrace', 'Kimmunity Angels Society'),
                    37 => array('Vernon', 'Nexus BC Community Resource Centre'),
                    38 => array('Welland', 'Rose City Kids'),
                    39 => array('Whitecourt', 'Friends of whitecourt society'),
                    40 => array('Williams Lake', 'Potato House Project'),
                    41 => array('Winnipeg', 'Recreation Opportunities for Kids (ROK)'),
                    42 => array('Winkler', 'Candlelighters Childhood Cancer Support Group'),
                    43 => array('Yorkton', 'SIGN'),
                );
                ?>
                <ul class="charities">
                    <?php foreach($winning_charities as $charity): ?>
                        <li class='charity'>
                            <h3 class="charity__location headline">
                                <?php echo $charity[0]; ?>
                            </h3>
                            <span class="charity__name">
                                <?php echo $charity[1]; ?>
                            </span>
                        </li>
                    <?php endforeach; ?>
                </ul>
           </div>
        </section>
        <?php endif; ?>
    </div>
</div>
<?php element('footer'); ?>