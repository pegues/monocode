<?php if (isset($topic)) { ?>
    <div class="topic">
        <h1 class="title"><?php echo $topic->title; ?></h1>
		
        <div class="category">
            <a href='<?php echo $base_url; ?>?c=<?php echo $topic->category_id; ?>' style='background-color: <?php echo $topic->category_color; ?>'>
                <span class='badge'></span>
                <?php echo $topic->category_name; ?>
            </a>
			
            <div class="clear"></div>
        </div>
		
        <div class="clear"></div>
    </div>
<?php } ?>
<?php if (isset($post)) { ?>
    <ul class="postlist">
        <li class="postlistitem">
            <div class="postlistitemheader">
                <span class="avatar">
                    <img />
                </span>
                <span class="username">
                    <i class="fa fa-user"></i>
                    <?php echo $post->user_name; ?>
                </span>
                <span class="time">
                    <i class="fa fa-clock-o"></i>
                    <?php echo $post->created_at; ?>
                </span>
            </div>
			
            <div class="postlistitemcontent">
                <?php echo $post->cooked; ?>
            </div>
        </li>
    </ul>
<?php } ?>
<div class="scform_container">
	
    <h3 id="post-to-reply">Reply to the Post</h3>
	
    <form id="forum-reply-form" class="form" method="post" action="<?php echo base_url(); ?>forum/reply" onsubmit="return validateForm(this)">
        <div class="scformsection">
			
            <?php /* Reply: Start */ ?>
            <div class="scformrow">
                <div class="scfield">
                    <textarea name="content" 
						required=""
						id="forumpostreply" 
						class="textarea" 
						placeholder="Type your reply here."><?php echo isset($content) ? $content : '' ?></textarea>
						
                    <div class="clear"></div>
                </div>
				
                <div class="clear"></div>
            </div>
            <?php /* Reply: End */ ?>
			
            <div class="buttoncontainer">
                <input type="hidden" name="p" value="<?php echo isset($post) ? $post->id : '' ?>" />
                <button class="button buttonright">
                    <span>Reply</span>
                </button>
                <button type="button" class="button red cancel buttonright">
                    <span>Cancel</span>
                </button>
            </div>
			
            <div class="clear"></div>
        </div>
    </form>
	
    <div class="clear"></div>
</div>

<script>
    function validateForm(form) {
        if (form.content.value.length <= 25) {
            $('<div class="notice error"><div class="notice_inside">Your reply must be longer than 25 characters.</div></div>')
				.insertAfter($(form.content))
				.delay(6500)
				.slideUp(function() {
					$(this).remove();
				});
            form.content.focus();
			
            return false;
        }
        
        return true;
    }
</script>