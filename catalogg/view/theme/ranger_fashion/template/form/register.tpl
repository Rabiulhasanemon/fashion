<?php echo $header; ?>
<section class="after-header p-tb-10">
    <div class="container">
        <div class="row">
            <div class="col-sm-6">
                <ul class="breadcrumb">
                    <?php foreach ($breadcrumbs as $breadcrumb) { ?>
                    <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
                    <?php } ?>
                </ul>
                <div class="clear"></div>
            </div>
            <div class="col-sm-6">
                <h6 class="page-heading"><?php echo $heading_title; ?></h6>
            </div>
        </div>
    </div>
</section>
<div class="container alert-container">
        <?php if ($error_warning) { ?>
        <div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> <?php echo $error_warning; ?></div>
        <?php } ?>
</div>
<div class="container account_layout customer_registration body">
  <div class="row"><?php echo $column_left; ?>
    <?php if ($column_left && $column_right) { ?>
    <?php $class = 'col-sm-6'; ?>
    <?php } elseif ($column_left || $column_right) { ?>
    <?php $class = 'col-sm-9'; ?>
    <?php } else { ?>
    <?php $class = 'col-sm-12'; ?>
    <?php } ?>
    <div id="content" class="<?php echo $class; ?>">
        <div class="main_content">
        <?php echo $content_top; ?>
        <h1><?php echo $heading_title; ?></h1>
        <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" class="registration_form">
            <div class="form-group required">
                <label class="control-label" for="input-full-name">Full Name</label>
                <input type="text" name="full_name" value="<?php echo $full_name; ?>" placeholder="Full Name" id="input-full-name" class="form-control" />
                <?php if ($error_full_name) { ?>
                <div class="text-danger"><?php echo $error_full_name; ?></div>
                <?php } ?>

            </div>
            <div class="form-group required">
                <label class="control-label" for="input-email">Email</label>
                <input type="email" name="email" value="<?php echo $email; ?>" placeholder="Email" id="input-email" class="form-control" />
                <?php if ($error_email) { ?>
                <div class="text-danger"><?php echo $error_email; ?></div>
                <?php } ?>
            </div>
            <div class="form-group required">
                <label class="control-label" for="input-phone">Phone</label>
                <input type="tel" name="phone" value="<?php echo $phone; ?>" placeholder="ie. 01700112233" id="input-phone" class="form-control" />
                <?php if ($error_phone) { ?>
                <div class="text-danger"><?php echo $error_phone; ?></div>
                <?php } ?>
            </div>

            <div class="form-group required">
                <label class="control-label" for="input-university">University Name</label>
                <select class="form-control" name="university" id="input-university">
                    <option value="EWU" <?php echo $university == 'EWU' ? "selected" : "" ?>>East West University</option>
                    <option value="IUB" <?php echo $university == 'IUB' ? "selected" : "" ?>>Independent University Bangladesh</option>
                    <option value="NSU" <?php echo $university == 'NSU' ? "selected" : "" ?>>North South University</option>
                </select>
                <?php if ($error_university) { ?>
                <div class="text-danger"><?php echo $error_university; ?></div>
                <?php } ?>
            </div>
            <div class="form-group required">
                <label class="control-label" for="input-student-id">Student ID</label>
                <input type="tel" name="student_id" value="<?php echo $student_id; ?>" placeholder="Student ID" id="input-student-id" class="form-control" />
                <?php if ($error_student_id) { ?>
                <div class="text-danger"><?php echo $error_student_id; ?></div>
                <?php } ?>
            </div>
            <div class="form-group">
                <label class="control-label"><input type="checkbox" name="is_want_to_experience" value="true" <?php echo $is_want_to_experience == 'true' ? "checked" : "" ; ?>>&nbsp;&nbsp;<span>I want to experience VR at campus</span></label><br>
                <label class="control-label"><input type="checkbox" name="is_want_to_play" value="true" <?php echo $is_want_to_play == 'true' ? "checked" : "" ; ?>>&nbsp;&nbsp;<span>I want to participate in ROG Kick N' Drive tournament</span></label>
                <?php if ($error_participation) { ?>
                <div class="text-danger"><?php echo $error_participation; ?></div>
                <?php } ?>
            </div>

            <div class="dependants">
                <div class="form-group required">
                    <label class="control-label" for="input-game">Game want to play</label>
                    <select class="form-control" name="game" id="input-game">
                        <option value="MW" <?php echo $game == 'MW' ? "selected" : "" ?>>Most Wanted</option>
                        <option value="FIFA2018" <?php echo $game == 'FIFA2018' ? "selected" : "" ?>>FIFA 2018</option>
                    </select>
                    <?php if ($error_game) { ?>
                    <div class="text-danger"><?php echo $error_game; ?></div>
                    <?php } ?>
                </div>
                <div class="form-group required">
                    <label class="control-label" for="input-payed-before">Have you ever played the selected game before?</label>
                    <select class="form-control" name="is_participant_played_before" id="input-payed-before">
                        <option value="YES" <?php echo $is_participant_played_before == 'YES' ? "selected" : "" ?>>Yes</option>
                        <option value="NO" <?php echo $is_participant_played_before == 'NO' ? "selected" : "" ?>>No</option>
                    </select>
                    <?php if ($error_is_participant_played_before) { ?>
                    <div class="text-danger"><?php echo $error_is_participant_played_before; ?></div>
                    <?php } ?>
                </div>
                <div class="form-group required">
                    <label class="control-label" for="input-gamer-type">You are</label>
                    <select class="form-control" name="gamer_type" id="input-gamer-type">
                        <option value="AMATEUR" <?php echo $gamer_type == 'AMATEUR' ? "selected" : "" ?>>I am an amateur</option>
                        <option value="MEDIUM" <?php echo $gamer_type == 'MEDIUM' ? "selected" : "" ?>>I think I can beat 'em up</option>
                        <option value="PRO" <?php echo $gamer_type == 'PRO' ? "selected" : "" ?>>You are talking with a pro</option>
                    </select>
                    <?php if ($error_gamer_type) { ?>
                    <div class="text-danger"><?php echo $error_gamer_type; ?></div>
                    <?php } ?>
                </div>
            </div>
            <div class="form-group required">
                <label class="control-label" for="input-how-din-participant-know">Where did you come to know about the event ?</label>
                <select class="form-control" name="how_did_participant_know" id="input-how-din-participant-know">
                    <option value="SOCIAL_MEDIA" <?php echo $how_did_participant_know == 'SOCIAL_MEDIA' ? "selected" : "" ?>>Social Media</option>
                    <option value="SMS" <?php echo $how_did_participant_know == 'SMS' ? "selected" : "" ?>>SMS</option>
                    <option value="WEB" <?php echo $how_did_participant_know == 'WEB' ? "selected" : "" ?>>Website</option>
                    <option value="UNIVERSITY_EVENT" <?php echo $how_did_participant_know == 'UNIVERSITY_EVENT' ? "selected" : "" ?>>University event</option>
                    <option value="FROM_FRIENDS" <?php echo $how_did_participant_know == 'FROM_FRIENDS' ? "selected" : "" ?>> Form a friends</option>
                </select>
                <?php if ($error_how_did_participant_know) { ?>
                <div class="text-danger"><?php echo $error_how_did_participant_know; ?></div>
                <?php } ?>
            </div>
        <?php if ($text_agree) { ?>
        <div class="buttons">
            <div class="pull-right"><?php echo $text_agree; ?>
                <?php if ($agree) { ?>
                <input type="checkbox" name="agree" value="1" checked="checked" />
                <?php } else { ?>
                <input type="checkbox" name="agree" value="1" />
                <?php } ?>
                &nbsp;
                <input type="submit" value="<?php echo $button_continue; ?>" class="btn btn-primary" />
            </div>
        </div>
        <?php } else { ?>
        <div class="buttons">
            <div class="pull-right">
                <input type="submit" value="<?php echo $button_continue; ?>" class="btn btn-primary" />
            </div>
        </div>
        <?php } ?>
        <div class="clearfix"></div>
        </form>
        <h2>Fixture Table</h2>
            <table class="table">
                <thead>
                    <tr>
                        <th style="width: 28%; border: 1px solid #333; padding: 10px;">University Name</th>
                        <th style="width: 36%; border: 1px solid #333; padding: 10px">Asus laptop experience VR<br/>registration ID collection </th>
                        <th style="width: 36%; border: 1px solid #333; padding: 10px">Game Day</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td colspan="3" style="padding: 0px !important;">
                            <table class="table">
                                <tr style="text-align: left">
                                    <th style="width: 28%; border: 1px solid #333;"></th>
                                    <th style="width: 10%; border: 1px solid #333; padding: 7px">Date</th>
                                    <th style="width: 26%; border: 1px solid #333; padding: 7px">Time</th>
                                    <th style="width: 10%; border: 1px solid #333; padding: 7px">Date</th>
                                    <th style="width: 26%; border: 1px solid #333; padding: 7px">Time</th>
                                </tr>
                                <tr>
                                    <td style="border: 1px solid #333;">IUB</td>
                                    <td style="border: 1px solid #333;">12 Nov 2018</td>
                                    <td style="border: 1px solid #333;">10:00 AM</td>
                                    <td style="border: 1px solid #333;">13 Nov 2018</td>
                                    <td style="border: 1px solid #333;">10:00 AM</td>
                                </tr>
                                <tr>
                                    <td style="border: 1px solid #333;">NSU</td>
                                    <td style="border: 1px solid #333;">14 Nov 2018</td>
                                    <td style="border: 1px solid #333;">10:00 AM</td>
                                    <td style="border: 1px solid #333;">15 Nov 2018</td>
                                    <td style="border: 1px solid #333;">10:00 AM</td>
                                </tr>
                                <tr>
                                    <td style="border: 1px solid #333;">EWU</td>
                                    <td style="border: 1px solid #333;">19 Nov 2018</td>
                                    <td style="border: 1px solid #333;">10:00 AM</td>
                                    <td style="border: 1px solid #333;">20 Nov 2018</td>
                                    <td style="border: 1px solid #333;">10:00 AM</td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </tbody>
            </table>
        <?php echo $content_bottom; ?>
        </div>
    </div>
    <?php echo $column_right; ?></div>
</div>
<script type="text/javascript"><!--
app.onReady(window, "$", function () {
    var is_want_to_play = $('[name=is_want_to_play]')
    is_want_to_play.on("change", function () {
        if(is_want_to_play.get(0).checked) {
            $(".dependants").show()
        } else {
            $(".dependants").hide()
        }
    })
    is_want_to_play.trigger("change");
}, 10);
//--></script>
<?php echo $footer; ?>