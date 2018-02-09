    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.4/jquery.min.js"></script>
    <script src="<?php echo site_url("assets/js/jquery.maskmoney.js")?>"></script>
    <script src="<?php echo site_url("assets/js/jquery.mask.js")?>"></script>
    <script src="//assets.locaweb.com.br/locastyle/3.8.5/javascripts/locastyle.js"></script>
    <!-- <script src="<?php echo site_url('assets/dist/js/datepicker.min.js'); ?>"></script> -->
    <script src="https://maps.googleapis.com/maps/api/js?v=3.exp&libraries=places&key=AIzaSyDyt4N0NPYZriABFHYpiukdm3fHqntMm2I&libraries=places"></script>
    <script src="<?php echo site_url('assets/js/main.js') ?>"></script>
    <!-- Include English language -->
    <!-- <script src="<?php echo site_url('assets/dist/js/i18n/datepicker.pt-BR.js'); ?>"></script> -->
    <?php foreach ($javascripts as $key => $value): ?>
        <script src="<?php echo site_url("$value.js")?>" type="text/javascript"></script>
    <?php endforeach ?>
  </body>
</html>
