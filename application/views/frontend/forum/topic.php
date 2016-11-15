<div class="topic">
    <h2 class="title"><?php echo $entity->title; ?></h2>

    <div class="category">
        <a href='<?php echo $base_url; ?>?c=<?php echo $entity->category_id; ?>'>
            <span class='badge' style='background-color: <?php echo $entity->category_color; ?>'></span>
            Category: <?php echo $entity->category_name; ?>
        </a>

        <div class="clear"></div>
    </div>

    <div class="clear"></div>
</div>

<ul class="postlist">
    <?php
    if (isset($entities) && sizeof($entities) > 0) {
        $index = 0;
        foreach ($entities as $ety) {
            ?>
            <li class="postlistitem" data-post-id="<?php echo $ety->id; ?>">
                <div class="postlistitemheader">
                    <span class="avatar">
                        <img />
                    </span>
                    <span class="username">
                        <i class="fa fa-user"></i>
                        <?php echo $ety->user_name; ?>
                    </span>
                    <span class="time">
                        <i class="fa fa-clock-o"></i>
                        <?php echo $ety->created_at; ?>
                    </span>

                    <div class="clear"></div>
                </div>

                <div class="postlistitemcontent"><?php echo $ety->cooked; ?></div>

                <div class="postlistitemfooter">
                    <div class="postlistitemtools">
                        <?php
                        if (!$entity->closed && $entity->postable) {
                            if ($index == 0) {
                                if (isset($account) && $account) {
                                    ?>
                                    <a class="button postcomment" href="<?php echo $base_url; ?>reply?p=<?php echo $ety->id; ?>">
                                        <i class="fa fa-pencil"></i> Post Comment
                                    </a>
                                <?php } else { ?>
                                    <a class="button loginregister" href="<?php echo base_url() ?>guest/login?returnUrl=<?php echo urlencode(current_url()); ?>#reply/<?php echo $ety->id; ?>">
                                        <i class="fa fa-sign-in"></i> Login to Post Comment
                                    </a>
                                <?php } ?>
                            <?php } else { ?>
                                <?php if (isset($account) && $account) { ?>

                                    <?php /* Must be Authenticated: Start */ ?>
                                    <?php if ($entity->closed && $entity->postable && (isset($account) || $account)) { ?>
                                        <a class="button reply" href="<?php echo $base_url; ?>reply?p=<?php echo $ety->id; ?>">
                                            <i class="fa fa-reply"></i> Reply
                                        </a>
                                    <?php } ?>
                                    <?php /* Must be Authenticated: End */ ?>

                                    <?php
                                }
                            }
                        }
                        ?>

                        <div class="clear"></div>
                    </div>

                    <?php if ($index ++ == 0) { ?>
                        <ul class="details">
                            <li>
                                <span class="username">
                                    <i class="fa fa-user"></i>
                                    <?php echo $entity->user_name; ?>
                                </span>
                            </li>
                            <li>
                                <span class="replycount">
                                    <i class="fa fa-reply"></i>
                                    <?php echo $entity->reply_count; ?> replies
                                </span>
                            </li>
                            <li>
                                <span class="viewcount">
                                    <i class="fa fa-eye"></i>
                                    <?php echo $entity->views; ?> views
                                </span>
                            </li>
                            <li>
                                <?php
                                $span = explode(',', timespan(strtotime($entity->updated_at), time()));
                                $ago = $span[0];
                                ?>
                                <span class="activity">
                                    <i class="fa fa-clock-o"></i>
                                    <?php echo $ago; ?>
                                </span>
                            </li>
                        </ul>

                        <?php if (!$entity->closed && $entity->postable && (!isset($account) || !$account)) { ?>
                            <?php /* Sign In or Register: Start */ ?>
                            <div class="clear"></div>

                            <div class="notice forumsignin signin pinned">
                                <div class="notice_inside">
                                    <a href="<?php echo base_url(); ?>guest/login">Sign in</a> or <a href="<?php echo base_url(); ?>guest/register">Register</a> to post a comment.

                                    <div class="clear"></div>
                                </div>

                                <div class="clear"></div>
                            </div>
                            <?php /* Sign In or Register: End */ ?>
                        <?php } ?>

                    <?php } ?>

                    <div class="clear"></div>

                </div>
            </li>
            <?php
        }
    }
    ?>
    <li class="postlistbottom">
        <?php if (!$entity->closed && $entity->postable && isset($entities) && count($entities) > 1) { ?>
            <?php if (isset($account) && $account) { ?>
                <a class="button postcomment" href="<?php echo current_url(); ?>#reply/<?php echo $entities[0]->id; ?>">
                    <i class="fa fa-pencil"></i> Post Comment
                </a>
            <?php } else { ?>
                <a class="button loginregister" href="<?php echo base_url() ?>guest/login?returnUrl=<?php echo urlencode(current_url()); ?>#reply/<?php echo $ety->id; ?>">
                    <i class="fa fa-sign-in"></i> Login to Post Comment
                </a>
            <?php } ?>
        <?php } ?>
    </li>
</ul>

<?php /* Response Container: Start */ ?>
<div id="replycontainer" class="forumresponsewrapper">
    <div class="forumresponse_inner">
        <?php include 'reply.php'; ?>

        <div class="clear"></div>
    </div>

    <div class="clear"></div>
</div>
<?php /* Response Container: End */ ?>

<script type="text/javascript">
    $(document).ready(function () {
        $(".typelist > li > a.sort").click(function () {
            $(this).find('input').prop('checked', true);
            $(this).closest('form').submit();
            return false;
        });

        $(".reply, .postcomment").click(function () {
            var item = $('.postlistitem').eq(0);
            $("#replycontainer #forum-reply-form input[name='p']").val(item.data('post-id'));
            var contentObj = item.find(".postlistitemcontent")[0];
            var title = contentObj.textContent || contentObj.innerText;
            if (title.length > 150) {
                title = title.substr(0, 150);
            }
            $("#replycontainer #post-to-reply").html(title);
            $("#replycontainer").slideDown();
            return false;
        });

        $("#replycontainer .cancel").click(function () {
            $("#replycontainer").slideUp();
        });

        var index = -1;
        if ((index = location.href.indexOf('#reply')) > -1) {
            var id = location.href.substr(index + 7);
            $(".postlistitem[data-post-id='" + id + "'] .reply").click();
        }

        $(".comment").click(function () {
            var index = -1;
            if ((index = this.href.indexOf('#reply')) > -1) {
                var id = this.href.substr(index + 7);
                $(".postlistitem[data-post-id='" + id + "'] .reply").click();
            }
        });
    });
</script>
