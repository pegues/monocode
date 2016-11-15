
<script type="text/javascript">
  $(document).ready(function(e) {
		$('body').css({'overflow' : 'hidden'}).addClass('config');
		
		$("input").keyup(function(){
			parent.configInput($(this).attr('data-key'),$(this).val());
		})
		
		$("select").change(function(){
			parent.configSelect($(this).attr('data-key'),$(this).val());
		})
	});
	
	function cancelled(){
		$("input").each(function(){
			parent.configInput($(this).attr('data-key'),$(this).attr('data-value'));
		});
		
		$("select").each(function(){
			parent.configSelect($(this).attr('data-key'),$(this).attr('data-value'));
			if($(this).attr('data-key')=='editor_page_theme'){
				parent.configSelect('cancle'+$(this).attr('data-key'),$(this).attr('data-value'));
			}
		});
		
		parent.scpopupclose();
	}
	
	function backclose(){
		$("input").each(function(){
			parent.configInput($(this).attr('data-key'),$(this).attr('data-value'));
		});
		
		$("select").each(function(){
			parent.configSelect($(this).attr('data-key'),$(this).attr('data-value'));
		});
	}
	
	function function_save(){
		$("#config").submit();
	}
	
	//data-height="250"
</script>

<?php
if (isset($_POST['editor_page_theme'])) {
	update_option($_POST['editor_page_theme'],$_POST['val']);
}
?>
