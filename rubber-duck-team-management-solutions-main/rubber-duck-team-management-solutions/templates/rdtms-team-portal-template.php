<?php
if (defined('ABSPATH')) {
    $custom_logo_id = get_theme_mod('custom_logo');
    $logo = wp_get_attachment_image_src($custom_logo_id, 'full');
}
?>
<div class="container-fluid text-center" style="margin-top:5em;">
    <img class="image-fluid mb-2" style="width:300px" src="<?= esc_url($logo[0]) ? esc_url($logo[0]) : 'https://rubberducktech.com/wp-content/uploads/2023/06/cropped-Rubber-Duck_final-file.png' ?>" />

    <!-- <h2 class="text-center">Clock In/Out</h2> -->
    <div class="row justify-content-center">
        <div class="col-auto">
            <div id="realTimeDate" class="m-3 lead"></div>
            <div id="realTimeClock" class="m-3 display-4"></div>
        </div>
    </div>
    <div class="row justify-content-center mb-4">
        <div class="col-auto">
            <button id="clockInBtn" class="btn btn-success">Clock In</button>
            <button id="startBreakBtn" class="btn btn-info" style="display:none;">Start Break</button>
            <button id="endBreakBtn" class="btn btn-info" style="display:none;">End Break</button>
            <button id="clockOutBtn" class="btn btn-danger" style="display:none;">Clock Out</button>
        </div>
    </div>
    <div id="statusMessage" class="alert" style="display:none;"></div>
    <div id="workTimer" style="display:none;"></div>
</div>