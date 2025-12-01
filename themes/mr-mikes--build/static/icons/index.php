<!DOCTYPE html>
<html lang="en-US" prefix="og: http://ogp.me/ns#">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta http-equiv="X-UA-Compatible" content="IE=edge"> 
  <link rel="profile" href="http://gmpg.org/xfn/11">
  <title>Icons</title>
  <link rel='stylesheet' id='styles-css'  href='../../../style.css' type='text/css' media='all' />
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
  
  <style>

    .nav a{
      display: inline-block;
      padding: 10px 20px;
      margin-right: 20px;
      background: #333;
      color: #fff;
    }

    h2{
      width: 100%;
      border-bottom: 2px solid #333;
      padding: 40px 0 0;
    }

    svg{
      width: 50px;
      height: 50px;
    }
    path{
      fill: #333;
    }
    .icons{
      display: flex;
      flex-wrap: wrap;
    }
    .icon{
      position: relative;
      width: 10%;
      padding: 20px;
    }

    .icon > p {
      display: none;
      position: absolute;
    }
    .icon:hover > p{
      display: block;
    }

  </style>

</head>

<body>

  

  <div class="grid-edges">

    <div class="nav">
      <a href="#custom">Custom</a>
      <a href="#fa">Font Awesome</a>
      <a href="#md">Material Design</a>
      <a href="#sl">Simple Line</a>
    </div>

    <div class="icons">

      <h2 id="custom">Custom</h2>
      <?php
      $files = glob('custom/*.{svg}', GLOB_BRACE);
      foreach($files as $file) {
        $file_name = str_replace('custom/', '', $file);
        $file_name = str_replace('.svg', '', $file_name); ?>

        <div class="icon">
          <?php echo file_get_contents($file); ?>
          <input value="<?php echo $file_name; ?>"/>
          <!-- <button data-function="fai('<?php echo $file_name; ?>');" class="copyfunction">Copy</button> -->
        </div>

      <?php } ?>

      <h2 id="fa">Font-Awesome</h2>
      <?php
      $files = glob('font-awesome/*.{svg}', GLOB_BRACE);
      foreach($files as $file) {
        $file_name = str_replace('font-awesome/', '', $file);
        $file_name = str_replace('.svg', '', $file_name); ?>

        <div class="icon">
          <?php echo file_get_contents($file); ?>
          <input value="<?php echo $file_name; ?>"/>
          <!-- <button data-function="fai('<?php echo $file_name; ?>');" class="copyfunction">Copy</button> -->
        </div>

      <?php } ?>

      <h2 id="md">Material Design</h2>
      <?php
        $files = glob('material-design-icons/**/*.{svg}', GLOB_BRACE);
        foreach($files as $file) {
          $file_name = str_replace('material-design-icons/svg/', '', $file);
          $file_name = str_replace('.svg', '', $file_name); ?>

          <div class="icon">
            <?php echo file_get_contents($file); ?>
            <input value="<?php echo $file_name; ?>"/>
            <!-- <button data-function="fai('<?php echo $file_name; ?>');" class="copyfunction">Copy</button> -->
          </div>

        <?php } ?>


        <h2 id="sl">Simple Line Icons</h2>
        <?php
          $files = glob('simple-line-icons/*.{svg}', GLOB_BRACE);
          foreach($files as $file) {
            $file_name = str_replace('simple-line-icons/', '', $file);
            $file_name = str_replace('.svg', '', $file_name); ?>

            <div class="icon">
              <?php echo file_get_contents($file); ?>
              <input value="<?php echo $file_name; ?>"/>
              <!-- <button data-function="fai('<?php echo $file_name; ?>');" class="copyfunction">Copy</button> -->
            </div>

          <?php } ?>
    </div>
  </div>
</body>

<script>
  jQuery( ".copyfunction" ).click(function() {
    var the_function = jQuery(this).data('function');
  });
</script>

