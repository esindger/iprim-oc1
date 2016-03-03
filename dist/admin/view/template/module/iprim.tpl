<?php echo $header; ?>
<div id="content">
    <div class="breadcrumb">
        <?php foreach ($breadcrumbs as $breadcrumb) { ?>
        <?php echo $breadcrumb['separator']; ?><a
                href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a>
        <?php } ?>
    </div>
    <?php if ($error_warning) { ?>
    <div class="warning"><?php echo $error_warning; ?></div>
    <?php } ?>
    <div class="box">
        <div class="heading">
            <h1><img src="view/image/module.png" alt=""/> <?php echo $heading_title; ?></h1>

            <div class="buttons"><a onclick="$('#form').submit();" class="button"><?php echo $button_save; ?></a><a
                        href="<?php echo $cancel; ?>" class="button"><?php echo $button_cancel; ?></a></div>
        </div>
        <div class="content">

            <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form">
                <p><?php echo $description; ?></p>

                <h3><?php echo $text_edit; ?></h3>
                <table class="form">
                    <tr>
                        <td><?php echo $entry_secret_key; ?></td>
                        <td>
                            <input type="text" name="iprim_secret_key" value="<?php echo $iprim_secret_key; ?>"
                                   placeholder="<?php echo $entry_secret_key; ?>" id="input-secret_key"
                                   class="form-control"/>

                            <?php if ($error_secret_key) { ?>
                            <div class="error"><?php echo $error_secret_key; ?></div>
                            <?php } elseif ($help_secret_key) { ?>
                            <div class="help"><?php echo $help_secret_key; ?></div>
                            <?php } ?>

                        </td>
                    </tr>
                    <tr>
                        <td><?php echo $entry_comment_add; ?></td>
                        <td>
                            <input type="text" name="iprim_comment_add" value="<?php echo $iprim_comment_add; ?>"
                                   placeholder="<?php echo $entry_comment_add; ?>" id="input-comment_add"
                                   class="form-control"/>

                            <?php if ($error_comment_add) { ?>
                            <div class="error"><?php echo $error_comment_add; ?></div>
                            <?php } elseif ($help_comment_add) { ?>
                            <div class="help"><?php echo $help_comment_add; ?></div>
                            <?php } ?>
                        </td>
                    </tr>
                    <tr>
                        <td><?php echo $entry_comment_simple; ?></td>
                        <td>
                            <input type="text" name="iprim_comment_simple" value="<?php echo $iprim_comment_simple; ?>"
                                   placeholder="<?php echo $entry_comment_simple; ?>" id="input-comment_simple"
                                   class="form-control"/>

                            <?php if ($error_comment_simple) { ?>
                            <div class="error"><?php echo $error_comment_simple; ?></div>
                            <?php } elseif ($help_comment_simple) { ?>
                            <div class="help"><?php echo $help_comment_simple; ?></div>
                            <?php } ?>
                        </td>
                    </tr>
                    <tr>
                        <td><?php echo $entry_comment_advanced; ?></td>
                        <td>
                            <input type="text" name="iprim_comment_advanced"
                                   value="<?php echo $iprim_comment_advanced; ?>"
                                   placeholder="<?php echo $entry_comment_advanced; ?>" id="input-comment_advanced"
                                   class="form-control"/>

                            <?php if ($error_comment_advanced) { ?>
                            <div class="error"><?php echo $error_comment_advanced; ?></div>
                            <?php } elseif ($help_comment_advanced) { ?>
                            <div class="help"><?php echo $help_comment_advanced; ?></div>
                            <?php } ?>
                        </td>
                    </tr>
                    <tr>
                        <td><?php echo $entry_comment_complete; ?></td>
                        <td>
                            <input type="text" name="iprim_comment_complete"
                                   value="<?php echo $iprim_comment_complete; ?>"
                                   placeholder="<?php echo $entry_comment_complete; ?>" id="input-comment_complete"
                                   class="form-control"/>

                            <?php if ($error_comment_complete) { ?>
                            <div class="error"><?php echo $error_comment_complete; ?></div>
                            <?php } elseif ($help_comment_complete) { ?>
                            <div class="help"><?php echo $help_comment_complete; ?></div>
                            <?php } ?>
                        </td>
                    </tr>
                    <tr>
                        <td><?php echo $entry_comment_delete; ?></td>
                        <td>
                            <input type="text" name="iprim_comment_delete" value="<?php echo $iprim_comment_delete; ?>"
                                   placeholder="<?php echo $entry_comment_delete; ?>" id="input-comment_delete"
                                   class="form-control"/>

                            <?php if ($error_comment_delete) { ?>
                            <div class="error"><?php echo $error_comment_delete; ?></div>
                            <?php } elseif ($help_comment_delete) { ?>
                            <div class="help"><?php echo $help_comment_delete; ?></div>
                            <?php } ?>
                        </td>
                    </tr>

                </table>

            </form>
        </div>
    </div>
</div>

<?php echo $footer; ?>