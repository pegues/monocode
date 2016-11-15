<div class="scform_container">

    <h3>Create New Forum Topic</h3>

    <form class="form" method="post" action="<?php echo base_url(); ?>forum/new_topic" onsubmit="return validateForm(this);">
        <div class="scformsection">

            <?php /* Title: Start */ ?>
            <div class="scformrow dblcol">
                <div class="scformcol">
                    <label for="newtopictitle">Title</label>
                    <div class="scfield">
                        <input type="text" 
                               id="newtopictitle" 
                               class="text" 
                               name="title" 
                               value="<?php echo isset($title) ? $title : '' ?>" 
                               placeholder="Topic Title" required="" />

                        <div class="clear"></div>
                    </div>

                    <div class="clear"></div>
                </div>

                <div class="clear"></div>
            </div>
            <?php /* Title: End */ ?>

            <?php /* Category: Start */ ?>
            <div class="scformrow dblcol">
                <div class="scformcol">
                    <label for="newtopiccategory">Category</label>
                    <div class="scfield">
                        <select name="category_id" id="newtopiccategory" class="select">
                            <?php
                            if (isset($categories) && sizeof($categories) > 0) {
                                foreach ($categories as $category) {
                                    if ($category->postable) {
                                        ?>
                                        <option value="<?php echo $category->id ?>"><?php echo $category->name; ?></option>
                                        <?php
                                    }
                                }
                            }
                            ?>
                        </select>

                        <div class="clear"></div>
                    </div>

                    <div class="clear"></div>
                </div>

                <div class="clear"></div>
            </div>
            <?php /* Category: End */ ?>

            <?php /* Post: Start */ ?>
            <div class="scformrow">
                <label for="newtopicpost">Post</label>
                <div class="scfield">
                    <textarea required="" 
                              name="content" 
                              id="newtopicpost" 
                              class="textarea" 
                              placeholder="Your post here"><?php echo isset($content) ? $content : '' ?></textarea>

                    <div class="clear"></div>
                </div>

                <div class="clear"></div>
            </div>
            <?php /* Post: End */ ?>

            <div class="buttoncontainer">
                <button class="button buttonright">
                    <span>Create</span>
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
        if (form.content.value.length < 100) {
            $('<div class="notice error"><div class="notice_inside">Please post with more than 100 characters.</div></div>').insertAfter($(form.content)).delay(6500).slideUp(function() {$(this).remove();});
            form.content.focus();
            return false;
        }
        
        return true;
    }
</script>