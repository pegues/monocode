<?php include 'header.php' ?>

<ul class="topiclist">
    
	<li class="topiclistheader">
        <span class="activity right">Activity</span>
        <span class="views right">Views</span>
        <span class="replies right">Replies</span>
        <span class="users right">Users</span>
        <?php if (!isset($category_id) || $category_id <= 0) { ?>
            <span class="category right">Category</span>
        <?php } ?>
        <span class="title">Topic</span>
    </li>
	
    <?php
    if (isset($entities) && sizeof($entities) > 0) {
        foreach ($entities as $ety) {
            $span = explode(',', timespan(strtotime($ety->updated_at), time()));
            $ago = $span[0];
            ?>
            <li class="topiclistrow <?php echo $ety->pinned ? 'pinned' : '' ?>">
                <span class="activity right">
                    <a href='<?php echo $base_url; ?><?php echo $ety->slug ? $ety->slug : $ety->id; ?>'>
                        <?php echo $ago; ?>
                    </a>
                </span>
                <span class="views right">
                    <?php echo $ety->views; ?>
                </span>
                <span class="replies right">
                    <a href='<?php echo $base_url; ?><?php echo $ety->slug ? $ety->slug : $ety->id; ?>'>
                        <?php echo $ety->reply_count; ?>
                    </a>
                </span>
                <span class="users right">
                    <a href='<?php echo $base_url; ?><?php echo $ety->slug ? $ety->slug : $ety->id; ?>'>
                        <?php echo $ety->user_name; ?>
                    </a>
                </span>
                <?php if (!isset($category_id) || $category_id <= 0) { ?>
                    <span class="category right">
                        <a href='<?php echo $base_url; ?>?c=<?php echo $ety->category_id; ?>' style='background-color: <?php echo $ety->category_color; ?>'>
                            <span class='badge'></span>
                            <?php echo $ety->category_name; ?>
                        </a>
                    </span>
                <?php } ?>
                <span class="title"><a href="<?php echo $base_url; ?><?php echo $ety->slug ? $ety->slug : $ety->id; ?>">
					<?php if ($ety->pinned) { ?>
						<i class='fa fa-pinterest-p'></i>&nbsp;
					<?php } ?>
					<?php echo $ety->title; ?></a>
                </span>
            </li>
            <?php
        }
    } else { ?>
		<li class="topiclistrow noresults"><span>No results</span></li>
	<?php }
    ?>
</ul>

<?php /* Limiter: Start */ ?>
<div class="forumlistpagelimiter scformrow">
	<div class="scfield">
		<select id="page-limiter" class="select limiter">
			<option value="5" <?php echo isset($pp) && $pp == 5 ? 'selected="selected"' : ''; ?>>5</option>
			<option value="10" <?php echo isset($pp) && $pp == 10 ? 'selected="selected"' : ''; ?>>10</option>
			<option value="15" <?php echo isset($pp) && $pp == 15 ? 'selected="selected"' : ''; ?>>15</option>
			<option value="20" <?php echo isset($pp) && $pp == 20 ? 'selected="selected"' : ''; ?>>20</option>
			<option value="25" <?php echo isset($pp) && $pp == 25 ? 'selected="selected"' : ''; ?>>25</option>
			<option value="50" <?php echo isset($pp) && $pp == 50 ? 'selected="selected"' : ''; ?>>50</option>
		</select>
		
		<div class="clear"></div>
	</div>
	
	<div class="clear"></div>
</div>
<?php /* Limiter: End */ ?>

<?php echo $pagination; ?>

<div class="clear"></div>

<script type="text/javascript">
    $(document).ready(function () {
        $("#page-limiter").change(function () {
            var form = $("#forum-search-form")[0];
            if (typeof form.pp == 'undefined') {
                $(form).append("<input type='hidden' name='pp' />");
            }
            form.pp.value = this.value;
            form.submit();
        });
    });
</script>
