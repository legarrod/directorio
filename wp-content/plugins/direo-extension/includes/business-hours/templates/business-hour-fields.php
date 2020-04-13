<?php
if (!empty($args['listing_ino']['bdbh'])) extract($args['listing_ino']['bdbh']); // extract week days timing
$enable247hour = !empty($args['listing_ino']['enable247hour']) ? $args['listing_ino']['enable247hour'] : ''; // extract settings
$disable_bz_hour_listing = !empty($args['listing_ino']['disable_bz_hour_listing']) ? $args['listing_ino']['disable_bz_hour_listing'] : ''; // extract settings
$listing_id = !empty($args['listing_ino']['id_itself']) ? $args['listing_ino']['id_itself'] : '';
$db_zone = get_post_meta($listing_id, '_timezone', true);
if (empty($db_zone)) {
    $db_zone = get_directorist_option('timezone', 'America/New_York');
}
?>
<div class="row">
    <div class="col-md-12">
        <div class="dbh-wrapper">
            <div class="dbh-extras">
                <div class="disable-bh">
                    <input type="checkbox" name="disable_bz_hour_listing" value="1"
                           id="disable_bz_hour_listing" <?= (!empty($disable_bz_hour_listing)) ? 'checked' : ''; ?> >
                    <label for="disable_bz_hour_listing"> <?php _e('Hide business hours', 'direo-extension'); ?> </label>
                </div>
                <div class="enable247hour">
                    <input type="checkbox" name="enable247hour" value="1"
                           id="enable247hour" <?= (!empty($enable247hour)) ? 'checked' : ''; ?> >
                    <label for="enable247hour"> <?php _e('Is this listing open 24 hours 7 days a week?', 'direo-extension'); ?> </label>
                </div>
            </div>
            <div class="dbh-wrapper__tab">
                <div class="dbh-tab__nav">
                    <a href="" data-target="dbh-day-one"
                       class="dbh-tab__nav__item active"><?php _e('Monday', 'direo-extension') ?></a>
                    <a href="" data-target="dbh-day-two"
                       class="dbh-tab__nav__item"><?php _e('Tuesday', 'direo-extension') ?></a>
                    <a href="" data-target="dbh-day-three"
                       class="dbh-tab__nav__item"><?php _e('Wednesday', 'direo-extension') ?></a>
                    <a href="" data-target="dbh-day-four"
                       class="dbh-tab__nav__item"><?php _e('Thursday', 'direo-extension') ?></a>
                    <a href="" data-target="dbh-day-five"
                       class="dbh-tab__nav__item"><?php _e('Friday', 'direo-extension') ?></a>
                    <a href="" data-target="dbh-day-six"
                       class="dbh-tab__nav__item"><?php _e('Saturday', 'direo-extension') ?></a>
                    <a href="" data-target="dbh-day-seven"
                       class="dbh-tab__nav__item"><?php _e('Sunday', 'direo-extension') ?></a>
                </div><!-- ends: .dbh-tab__nav -->
                <div class="dbh-tab__contents">
                    <div class="dbh-tab-panel dbh-fade active dbh-in" id="dbh-day-one">
                        <div class="dbh-hours-type">
                            <div class="dbh-checkbox">
                                <input type="radio" class="dbh-enter-hours" value="time" id="dbh-mon-enter-hours"
                                    <?php echo (!empty($monday['remain_close']) && ($monday['remain_close'] === 'time')) ? 'checked' : ''; ?>
                                       name="bdbh[monday][remain_close]" checked>
                                <label for="dbh-mon-enter-hours"><?php _e('Enter Times', 'direo-extension') ?></label>
                            </div>
                            <div class="dbh-checkbox">
                                <input type="radio"
                                       value="open" <?php echo (!empty($monday['remain_close']) && ($monday['remain_close'] === 'open')) ? 'checked' : ''; ?>
                                       id="dbh-mon-open" name="bdbh[monday][remain_close]">
                                <label for="dbh-mon-open"><?php _e('Open All Day', 'direo-extension') ?></label>
                            </div>
                            <div class="dbh-checkbox">
                                <input type="radio" id="dbh-mon-closed"
                                    <?php echo (!empty($monday['remain_close']) && (($monday['remain_close'] === 'on') || ($monday['remain_close'] === '1'))) ? 'checked' : ''; ?>
                                       name="bdbh[monday][remain_close]">
                                <label for="dbh-mon-closed"><?php _e('Closed All Day', 'direo-extension') ?></label>
                            </div>
                        </div><!-- ends: .dbh-hours-type -->
                        <div class="dbh-select-hours--list">
                            <div class="dbh-select-hours-wrapper">
                                <div class="dbh-select-hours">
                                    <div class="dbh-select-from dbh-custom-select">
                                        <?php
                                        $start = (!empty($monday['start'])) ? esc_attr($monday['start']) : '';
                                        ?>
                                        <label for="dbh-mon-from">Time From</label>
                                        <input type="text" data-time="<?php echo $start; ?>" name="bdbh[monday][start]"
                                               class="dbh-time-input" id="dbh-mon-from" placeholder="From"
                                               value="<?php echo atbdp_get_old_hours($start) ?>">
                                        <?php
                                        echo atbdp_hours()
                                        ?>
                                    </div>
                                    <div class="dbh-select-from dbh-custom-select">
                                        <?php
                                        $close = (!empty($monday['close'])) ? esc_attr($monday['close']) : '';
                                        ?>
                                        <label for="dbh-mon-to">Time To</label>
                                        <input type="text" data-time="<?php echo $start; ?>" name="bdbh[monday][close]"
                                               class="dbh-time-input" id="dbh-mon-to" placeholder="From"
                                               value="<?php echo atbdp_get_old_hours($close) ?>">
                                        <?php
                                        echo atbdp_hours();
                                        ?>
                                    </div>
                                    <!--<button class="dbh-remove" type="button">&times;</button>-->
                                </div>
                            </div>
                            <!--<button class="dbh-add-hours"><i class="la la-plus"></i> Add Hours</button>-->
                        </div><!-- ends: .dbh-select-hours--list -->
                    </div><!-- ends: .dbh-tab-panel -->

                    <div class="dbh-tab-panel dbh-fade" id="dbh-day-two">
                        <div class="dbh-hours-type">
                            <div class="dbh-checkbox">
                                <input type="radio" value="time" class="dbh-enter-hours" id="dbh-tue-enter-hours"
                                    <?php echo (!empty($tuesday['remain_close']) && ($tuesday['remain_close'] === 'time')) ? 'checked' : ''; ?>
                                       name="bdbh[tuesday][remain_close]" checked>
                                <label for="dbh-tue-enter-hours"><?php _e('Enter Times', 'direo-extension') ?></label>
                            </div>
                            <div class="dbh-checkbox">
                                <input type="radio" value="open"
                                       id="dbh-tue-open" <?php echo (!empty($tuesday['remain_close']) && ($tuesday['remain_close'] === 'open')) ? 'checked' : ''; ?>
                                       name="bdbh[tuesday][remain_close]">
                                <label for="dbh-tue-open"><?php _e('Open All Day', 'direo-extension') ?></label>
                            </div>
                            <div class="dbh-checkbox">
                                <input type="radio" id="dbh-tue-closed"
                                    <?php echo (!empty($tuesday['remain_close']) && (($tuesday['remain_close'] === 'on') || ($tuesday['remain_close'] === '1'))) ? 'checked' : ''; ?>
                                       name="bdbh[tuesday][remain_close]">
                                <label for="dbh-tue-closed"><?php _e('Closed All Day', 'direo-extension') ?></label>
                            </div>
                        </div><!-- ends: .dbh-hours-type -->
                        <div class="dbh-select-hours--list">
                            <div class="dbh-select-hours-wrapper">
                                <div class="dbh-select-hours">
                                    <div class="dbh-select-from dbh-custom-select">
                                        <?php
                                        $start = (!empty($tuesday['start'])) ? esc_attr($tuesday['start']) : '';
                                        ?>
                                        <label for="dbh-tue-from">Time From</label>
                                        <input type="text" data-time="<?php echo $start; ?>" name="bdbh[tuesday][start]"
                                               class="dbh-time-input" id="dbh-tue-from" placeholder="From"
                                               value="<?php echo atbdp_get_old_hours($start) ?>">
                                        <?php
                                        echo atbdp_hours()
                                        ?>
                                    </div>
                                    <div class="dbh-select-from dbh-custom-select">
                                        <?php
                                        $close = (!empty($tuesday['close'])) ? esc_attr($tuesday['close']) : '';
                                        ?>
                                        <label for="dbh-tue-to">Time To</label>
                                        <input type="text" data-time="<?php echo $start; ?>" name="bdbh[tuesday][close]"
                                               class="dbh-time-input" id="dbh-tue-to" placeholder="From"
                                               value="<?php echo atbdp_get_old_hours($close) ?>">
                                        <?php
                                        echo atbdp_hours();
                                        ?>
                                    </div>
                                    <!--<button class="dbh-remove" type="button">&times;</button>-->
                                </div>
                            </div>
                            <!--<button class="dbh-add-hours"><i class="la la-plus"></i> Add Hours</button>-->
                        </div><!-- ends: .dbh-select-hours--list -->
                    </div><!-- ends: .dbh-tab-panel -->

                    <div class="dbh-tab-panel dbh-fade" id="dbh-day-three">
                        <div class="dbh-hours-type">
                            <div class="dbh-checkbox">
                                <input type="radio" class="dbh-enter-hours" value="time" id="dbh-wed-enter-hours"
                                    <?php echo (!empty($wednesday['remain_close']) && ($wednesday['remain_close'] === 'time')) ? 'checked' : ''; ?>
                                       name="bdbh[wednesday][remain_close]" checked>
                                <label for="dbh-wed-enter-hours"><?php _e('Enter Times', 'direo-extension') ?></label>
                            </div>
                            <div class="dbh-checkbox">
                                <input type="radio" id="dbh-wed-open" value="open"
                                    <?php echo (!empty($wednesday['remain_close']) && ($wednesday['remain_close'] === 'open')) ? 'checked' : ''; ?>
                                       name="bdbh[wednesday][remain_close]">
                                <label for="dbh-wed-open"><?php _e('Open All Day', 'direo-extension') ?></label>
                            </div>
                            <div class="dbh-checkbox">
                                <input type="radio"
                                    <?php echo (!empty($wednesday['remain_close']) && (($wednesday['remain_close'] === 'on') || ($wednesday['remain_close'] === '1'))) ? 'checked' : ''; ?>
                                       id="dbh-wed-closed" name="bdbh[wednesday][remain_close]">
                                <label for="dbh-wed-closed"><?php _e('Closed All Day', 'direo-extension') ?></label>
                            </div>
                        </div><!-- ends: .dbh-hours-type -->
                        <div class="dbh-select-hours--list">
                            <div class="dbh-select-hours-wrapper">
                                <div class="dbh-select-hours">
                                    <div class="dbh-select-from dbh-custom-select">
                                        <?php
                                        $start = (!empty($wednesday['start'])) ? esc_attr($wednesday['start']) : '';
                                        ?>
                                        <label for="dbh-wed-from">Time From</label>
                                        <input type="text" data-time="<?php echo $start; ?>"
                                               name="bdbh[wednesday][start]" class="dbh-time-input" id="dbh-wed-from"
                                               placeholder="From" value="<?php echo atbdp_get_old_hours($start) ?>">
                                        <?php
                                        echo atbdp_hours()
                                        ?>
                                    </div>
                                    <div class="dbh-select-from dbh-custom-select">
                                        <?php
                                        $close = (!empty($wednesday['close'])) ? esc_attr($wednesday['close']) : '';
                                        ?>
                                        <label for="dbh-wed-to">Time To</label>
                                        <input type="text" data-time="<?php echo $start; ?>"
                                               name="bdbh[wednesday][close]" class="dbh-time-input" id="dbh-wed-to"
                                               placeholder="From" value="<?php echo atbdp_get_old_hours($close) ?>">
                                        <?php
                                        echo atbdp_hours();
                                        ?>
                                    </div>
                                    <!--<button class="dbh-remove" type="button">&times;</button>-->
                                </div>
                            </div>
                            <!--<button class="dbh-add-hours"><i class="la la-plus"></i> Add Hours</button>-->
                        </div><!-- ends: .dbh-select-hours--list -->
                    </div><!-- ends: .dbh-tab-panel -->

                    <div class="dbh-tab-panel dbh-fade" id="dbh-day-four">
                        <div class="dbh-hours-type">
                            <div class="dbh-checkbox">
                                <input type="radio" class="dbh-enter-hours" id="dbh-thr-enter-hours" value="time"
                                    <?php echo (!empty($thursday['remain_close']) && ($thursday['remain_close'] === 'time')) ? 'checked' : ''; ?>
                                       name="bdbh[thursday][remain_close]" checked>
                                <label for="dbh-thr-enter-hours"><?php _e('Enter Times', 'direo-extension') ?></label>
                            </div>
                            <div class="dbh-checkbox">
                                <input type="radio" id="dbh-thr-open" value="open"
                                    <?php echo (!empty($thursday['remain_close']) && ($thursday['remain_close'] === 'open')) ? 'checked' : ''; ?>
                                       name="bdbh[thursday][remain_close]">
                                <label for="dbh-thr-open"><?php _e('Open All Day', 'direo-extension') ?></label>
                            </div>
                            <div class="dbh-checkbox">
                                <input type="radio"
                                    <?php echo (!empty($thursday['remain_close']) && (($thursday['remain_close'] === 'on') || ($thursday['remain_close'] === '1'))) ? 'checked' : ''; ?>
                                       id="dbh-thr-closed" name="bdbh[thursday][remain_close]">
                                <label for="dbh-thr-closed"><?php _e('Closed All Day', 'direo-extension') ?></label>
                            </div>
                        </div><!-- ends: .dbh-hours-type -->
                        <div class="dbh-select-hours--list">
                            <div class="dbh-select-hours-wrapper">
                                <div class="dbh-select-hours">
                                    <div class="dbh-select-from dbh-custom-select">
                                        <?php
                                        $start = (!empty($thursday['start'])) ? esc_attr($thursday['start']) : '';
                                        ?>
                                        <label for="dbh-thr-from">Time From</label>
                                        <input type="text" data-time="<?php echo $start; ?>"
                                               name="bdbh[thursday][start]" class="dbh-time-input" id="dbh-thr-from"
                                               placeholder="From" value="<?php echo atbdp_get_old_hours($start) ?>">
                                        <?php
                                        echo atbdp_hours()
                                        ?>
                                    </div>
                                    <div class="dbh-select-from dbh-custom-select">
                                        <?php
                                        $close = (!empty($thursday['close'])) ? esc_attr($thursday['close']) : '';
                                        ?>
                                        <label for="dbh-thr-to">Time To</label>
                                        <input type="text" data-time="<?php echo $start; ?>"
                                               name="bdbh[thursday][close]" class="dbh-time-input" id="dbh-thr-to"
                                               placeholder="From" value="<?php echo atbdp_get_old_hours($close) ?>">
                                        <?php
                                        echo atbdp_hours();
                                        ?>
                                    </div>
                                    <!--<button class="dbh-remove" type="button">&times;</button>-->
                                </div>
                            </div>
                            <!--<button class="dbh-add-hours"><i class="la la-plus"></i> Add Hours</button>-->
                        </div><!-- ends: .dbh-select-hours--list -->
                    </div><!-- ends: .dbh-tab-panel -->

                    <div class="dbh-tab-panel dbh-fade" id="dbh-day-five">
                        <div class="dbh-hours-type">
                            <div class="dbh-checkbox">
                                <input type="radio" class="dbh-enter-hours" id="dbh-fri-enter-hours" value="time"
                                    <?php echo (!empty($friday['remain_close']) && ($friday['remain_close'] === 'time')) ? 'checked' : ''; ?>
                                       name="bdbh[friday][remain_close]" checked>
                                <label for="dbh-fri-enter-hours"><?php _e('Enter Times', 'direo-extension') ?></label>
                            </div>
                            <div class="dbh-checkbox">
                                <input type="radio" id="dbh-fri-open" value="open"
                                    <?php echo (!empty($friday['remain_close']) && ($friday['remain_close'] === 'open')) ? 'checked' : ''; ?>
                                       name="bdbh[friday][remain_close]">
                                <label for="dbh-fri-open"><?php _e('Open All Day', 'direo-extension') ?></label>
                            </div>
                            <div class="dbh-checkbox">
                                <input type="radio"
                                    <?php echo (!empty($friday['remain_close']) && (($friday['remain_close'] === 'on') || ($friday['remain_close'] === '1'))) ? 'checked' : ''; ?>
                                       id="dbh-fri-closed" name="bdbh[friday][remain_close]">
                                <label for="dbh-fri-closed"><?php _e('Closed All Day', 'direo-extension') ?></label>
                            </div>
                        </div><!-- ends: .dbh-hours-type -->
                        <div class="dbh-select-hours--list">
                            <div class="dbh-select-hours-wrapper">
                                <div class="dbh-select-hours">
                                    <div class="dbh-select-from dbh-custom-select">
                                        <?php
                                        $start = (!empty($friday['start'])) ? esc_attr($friday['start']) : '';
                                        ?>
                                        <label for="dbh-fri-from">Time From</label>
                                        <input type="text" data-time="<?php echo $start; ?>" name="bdbh[friday][start]"
                                               class="dbh-time-input" id="dbh-fri-from" placeholder="From"
                                               value="<?php echo atbdp_get_old_hours($start) ?>">
                                        <?php
                                        echo atbdp_hours()
                                        ?>
                                    </div>
                                    <div class="dbh-select-from dbh-custom-select">
                                        <?php
                                        $close = (!empty($friday['close'])) ? esc_attr($friday['close']) : '';
                                        ?>
                                        <label for="dbh-fri-to">Time To</label>
                                        <input type="text" data-time="<?php echo $start; ?>" name="bdbh[friday][close]"
                                               class="dbh-time-input" id="dbh-fri-to" placeholder="From"
                                               value="<?php echo atbdp_get_old_hours($close) ?>">
                                        <?php
                                        echo atbdp_hours();
                                        ?>
                                    </div>
                                    <!--<button class="dbh-remove" type="button">&times;</button>-->
                                </div>
                            </div>
                            <!--<button class="dbh-add-hours"><i class="la la-plus"></i> Add Hours</button>-->
                        </div><!-- ends: .dbh-select-hours--list -->
                    </div><!-- ends: .dbh-tab-panel -->

                    <div class="dbh-tab-panel dbh-fade" id="dbh-day-six">
                        <div class="dbh-hours-type">
                            <div class="dbh-checkbox">
                                <input type="radio" class="dbh-enter-hours" id="dbh-sat-enter-hours" value="time"
                                    <?php echo (!empty($saturday['remain_close']) && ($saturday['remain_close'] === 'time')) ? 'checked' : ''; ?>
                                       name="bdbh[saturday][remain_close]" checked>
                                <label for="dbh-sat-enter-hours"><?php _e('Enter Times', 'direo-extension') ?></label>
                            </div>
                            <div class="dbh-checkbox">
                                <input type="radio" id="dbh-sat-open" value="open"
                                    <?php echo (!empty($saturday['remain_close']) && ($saturday['remain_close'] === 'open')) ? 'checked' : ''; ?>
                                       name="bdbh[saturday][remain_close]">
                                <label for="dbh-sat-open"><?php _e('Open All Day', 'direo-extension') ?></label>
                            </div>
                            <div class="dbh-checkbox">
                                <input type="radio" id="dbh-sat-closed"
                                    <?php echo (!empty($saturday['remain_close']) && (($saturday['remain_close'] === 'on') || ($saturday['remain_close'] === '1'))) ? 'checked' : ''; ?>
                                       name="bdbh[saturday][remain_close]">
                                <label for="dbh-sat-closed"><?php _e('Closed All Day', 'direo-extension') ?></label>
                            </div>
                        </div><!-- ends: .dbh-hours-type -->
                        <div class="dbh-select-hours--list">
                            <div class="dbh-select-hours-wrapper">
                                <div class="dbh-select-hours">
                                    <div class="dbh-select-from dbh-custom-select">
                                        <?php
                                        $start = (!empty($saturday['start'])) ? esc_attr($saturday['start']) : '';
                                        ?>
                                        <label for="dbh-sat-from">Time From</label>
                                        <input type="text" data-time="<?php echo $start; ?>"
                                               name="bdbh[saturday][start]" class="dbh-time-input" id="dbh-sat-from"
                                               placeholder="From" value="<?php echo atbdp_get_old_hours($start) ?>">
                                        <?php
                                        echo atbdp_hours()
                                        ?>
                                    </div>
                                    <div class="dbh-select-from dbh-custom-select">
                                        <?php
                                        $close = (!empty($saturday['close'])) ? esc_attr($saturday['close']) : '';
                                        ?>
                                        <label for="dbh-sat-to">Time To</label>
                                        <input type="text" data-time="<?php echo $start; ?>"
                                               name="bdbh[saturday][close]" class="dbh-time-input" id="dbh-sat-to"
                                               placeholder="From" value="<?php echo atbdp_get_old_hours($close) ?>">
                                        <?php
                                        echo atbdp_hours();
                                        ?>
                                    </div>
                                    <!--<button class="dbh-remove" type="button">&times;</button>-->
                                </div>
                            </div>
                            <!--<button class="dbh-add-hours"><i class="la la-plus"></i> Add Hours</button>-->
                        </div><!-- ends: .dbh-select-hours--list -->
                    </div><!-- ends: .dbh-tab-panel -->

                    <div class="dbh-tab-panel dbh-fade" id="dbh-day-seven">
                        <div class="dbh-hours-type">
                            <div class="dbh-checkbox">
                                <input type="radio" class="dbh-enter-hours" id="dbh-sun-enter-hours" value="time"
                                    <?php echo (!empty($sunday['remain_close']) && ($sunday['remain_close'] === 'time')) ? 'checked' : ''; ?>
                                       name="bdbh[sunday][remain_close]" checked>
                                <label for="dbh-sun-enter-hours"><?php _e('Enter Times', 'direo-extension') ?></label>
                            </div>
                            <div class="dbh-checkbox">
                                <input type="radio" id="dbh-sun-open" value="open"
                                    <?php echo (!empty($sunday['remain_close']) && ($sunday['remain_close'] === 'open')) ? 'checked' : ''; ?>
                                       name="bdbh[sunday][remain_close]">
                                <label for="dbh-sun-open"><?php _e('Open All Day', 'direo-extension') ?></label>
                            </div>
                            <div class="dbh-checkbox">
                                <input type="radio"
                                    <?php echo (!empty($sunday['remain_close']) && (($sunday['remain_close'] === 'on') || ($sunday['remain_close'] === '1'))) ? 'checked' : ''; ?>
                                       id="dbh-sun-closed" name="bdbh[sunday][remain_close]">
                                <label for="dbh-sun-closed"><?php _e('Closed All Day', 'direo-extension') ?></label>
                            </div>
                        </div><!-- ends: .dbh-hours-type -->
                        <div class="dbh-select-hours--list">
                            <div class="dbh-select-hours-wrapper">
                                <div class="dbh-select-hours">
                                    <div class="dbh-select-from dbh-custom-select">
                                        <?php
                                        $start = (!empty($sunday['start'])) ? esc_attr($sunday['start']) : '';
                                        ?>
                                        <label for="dbh-sun-from">Time From</label>
                                        <input type="text" data-time="<?php echo $start; ?>" name="bdbh[sunday][start]"
                                               class="dbh-time-input" id="dbh-sun-from" placeholder="From"
                                               value="<?php echo atbdp_get_old_hours($start) ?>">
                                        <?php
                                        echo atbdp_hours()
                                        ?>
                                    </div>
                                    <div class="dbh-select-from dbh-custom-select">
                                        <?php
                                        $close = (!empty($sunday['close'])) ? esc_attr($sunday['close']) : '';
                                        ?>
                                        <label for="dbh-sun-to">Time To</label>
                                        <input type="text" data-time="<?php echo $start; ?>" name="bdbh[sunday][close]"
                                               class="dbh-time-input" id="dbh-sun-to" placeholder="From"
                                               value="<?php echo atbdp_get_old_hours($close) ?>">
                                        <?php
                                        echo atbdp_hours();
                                        ?>
                                    </div>
                                    <!--<button class="dbh-remove" type="button">&times;</button>-->
                                </div>
                            </div>
                            <!--<button class="dbh-add-hours"><i class="la la-plus"></i> Add Hours</button>-->
                        </div><!-- ends: .dbh-select-hours--list -->
                    </div><!-- ends: .dbh-tab-panel -->
                </div>
            </div><!-- ends: .dbh-wrapper__tab -->
            <div class="dbh-timezone">
                <label for="dbh-select-timezone">Timezone</label>
                <select id="dbh-select-timezone" name="timezone">
                    <?php
                    $timezones = DateTimeZone::listIdentifiers(DateTimeZone::ALL);
                    foreach ($timezones as $key => $timezone) {
                        $checked = $timezone === $db_zone ? 'selected' : '';
                        printf('<option value="%s" %s>%s</option>', $timezone, $checked, $timezone);
                    }
                    ?>
                </select>
            </div>
        </div><!-- ends: .dbh-wrapper -->
    </div>

</div>
