<div class="forumheader">
	
	<?php /* Forum Header New Topic Button: Start */ ?>
	<div class="forumheaderrightcol">
        <?php if (isset($account) && $account) { ?>
            <a id="add-topic" class="forumheaderbttn right add-topic" href="<?php echo $base_url; ?>#new_topic">
                <i class="fa fa-plus"></i> New Topic
            </a>
        <?php } else { ?>
            <a class="forumheaderbttn right add-topic" href="<?php echo base_url() ?>guest/login?returnUrl=<?php echo urlencode(current_url()); ?>#new_topic">
                <i class="fa fa-plus"></i> New Topic
            </a>
        <?php } ?>
		
		<div class="clear"></div>
    </div>
	<?php /* Forum Header New Topic Button: End */ ?>
	
	<?php /* Form Header Form Wrapper: Start */ ?>
    <form id="forum-search-form" action="<?php echo $base_url; ?>">
        <div class="forumheaderoptions">
			
			<?php /* Header Category Select and Search: Start */ ?>
			<div class="forumheadercatandsearch">
				
				<?php /* Forum Header Categories: Start */ ?>
				<div class="forumheadercategories scformrow">
					<div class="scfield">
						<select name="c" onchange="$(this).closest('form').submit();" class="select forumheadercatsel">
							<option value="">All Categories</option>
							<?php
							if (isset($categories) && sizeof($categories) > 0) {
								foreach ($categories as $category) {
									?>
									<option value="<?php echo $category->id ?>" <?php echo isset($category_id) && $category_id == $category->id ? 'selected="selected"' : ''; ?>><?php echo $category->name; ?></option>
									<?php
								}
							}
							?>
						</select>
						
						<div class="clear"></div>
					</div>
					
					<div class="clear"></div>
				</div>
				<?php /* Forum Header Categories: End */ ?>
				
				<?php if (isset($pp)) { ?>
					<input type="hidden" name="pp" value="<?php echo $pp; ?>" />
				<?php } ?>
				
				<?php /* Forum Header Search: Start */ ?>
				<div class="forumheadersearch scformrow">
					<div class="scfield">
						<input type="text" 
							name="q" 
							class="text forumsearchfld" 
							value="<?php echo isset($q) ? $q : ''; ?>" 
							placeholder="Search forum here ..." />
						
						<button class="forumheaderbttn left">
							<span><i class="fa fa-search"></i>Search</span>
						</button>
						
						<div class="clear"></div>
					</div>
					
					<div class="clear"></div>
				</div>
				<?php /* Forum Header Search: End */ ?>
				
				<div class="clear"></div>
			</div>
			<?php /* Header Category Select and Search: End */ ?>
			
			<?php /* Header Type List: Start */ ?>
			<div class="forumtypelist">
				<ul class="typelist">
					<li class="<?php echo (isset($sort) && $sort == 'l' ? 'active' : ''); ?>">
						<a href="#" class="sort">
							<span>Latest</span>
							<input type="radio" name="s" value="l" <?php echo (isset($sort) && $sort == 'l' ? 'checked="checked"' : ''); ?> />
						</a>
					</li>
					<li class="<?php echo (isset($sort) && $sort == 't' ? 'active' : ''); ?>">
						<a href="#" class="sort">
							<span>Top</span>
							<input type="radio" name="s" value="t" <?php echo (isset($sort) && $sort == 't' ? 'checked="checked"' : ''); ?> />
						</a>
					</li>
					<?php if (!isset($category_id) || !$category_id) { ?>
						<li class="<?php echo (!isset($category_id) ? 'active' : ''); ?>">
							<a href="<?php echo $base_url; ?>categories">
								<span>Categories</span>
							</a>
						</li>
					<?php } ?>
				</ul>
				
				<div class="clear"></div>
			</div>
			<?php /* Header Type List: End */ ?>
			
			<div class="clear"></div>
		</div>
    </form>
	<?php /* Form Header Form Wrapper: End */ ?>
	
    <div class="clear"></div>
</div>

<?php /* Response Container: Start */ ?>
<div id="newtopiccontainer" class="forumresponsewrapper">
    <div class="forumresponse_inner">
        <?php include 'new_topic.php'; ?>
		
        <div class="clear"></div>
    </div>
	
    <div class="clear"></div>
</div>
<?php /* Response Container: End */ ?>

<script type="text/javascript">
    $(document).ready(function () {
        $(".typelist>li>a.sort").click(function () {
            $(this).find('input').prop('checked', true);
            $(this).closest('form').submit();
            return false;
        });
		
        $("#add-topic").click(function () {
            $("#newtopiccontainer").slideDown();
            return false;
        });
		
        $("#newtopiccontainer .cancel").click(function () {
            $("#newtopiccontainer").slideUp();
        });
		
        if (location.href.indexOf('#new_topic') > -1) {
            $("#add-topic").click();
        }
    });
</script>
