                        
						<div class="clear"></div>
					</div>
					
                    <div class="clear"></div>
                </div>
            </form>
			
            <?php /* Footer Copyright: Start */ ?>
            <div class="loginftr">
				Copyright &copy; <?php echo date('Y'); ?> <a href="<?php echo base_url(); ?>">Monocode, LLC</a>. All Rights Reserved.
			</div>
            <?php /* Footer Copyright: End */ ?>
			
            <div class="clear"></div>
        </div>
		
        <div class="clear"></div>
    </div>
	
	<script type="text/javascript">
	$(document).ready(function(e) {
		
		if (document.getElementsByTagName) {
			var inputElements = document.getElementsByTagName("input");
			
			for (i=0; inputElements[i]; i++) {
				if (inputElements[i].className && (inputElements[i].className.indexOf("disableAutoComplete") != -1)) {
					inputElements[i].setAttribute("autocomplete","off");
				}
			}
		}
		
		if (navigator.userAgent.toLowerCase().indexOf("chrome") >= 0) {
			var _interval = window.setInterval(function () {
				var autofills = $('input:-webkit-autofill');
				
				if (autofills.length > 0) {
					window.clearInterval(_interval); // stop polling
					autofills.each(function() {
						var clone = $(this).clone(true, true);
						$(this).after(clone).remove();
					});
				}
			}, 20);
		}
	});
	</script>

</body>
</html>
 