<!DOCTYPE html>
<html>
<head>
	<meta http-equiv="content-type" content="text/html; charset=ISO-8859-1">
	<title>FTP Connection Details</title>
	
	<link href="<?php echo base_url(); ?>core/css/popupcontent.css" rel="stylesheet" media="all" />
	<link href="<?php echo base_url(); ?>core/css/fontawesome/font-awesome.css" rel="stylesheet" media="all" />
	<link href="<?php echo base_url(); ?>themes/editor/<?php echo get_option("editor_page_theme") ?>/theme.css" rel="stylesheet" data-default-theme="<?php echo get_option("editor_page_theme") ?>" media="all" />
	
	<script src="<?php echo base_url(); ?>core/js/jquery-1.10.2.min.js" type="text/javascript"></script>
	
	<script type="text/javascript">
		function save() {
			var form = $("form");
			form[0].ftptitle.value = $.trim(form[0].ftptitle.value);

			if (form[0].ftptitle.value.length > 20) {

			}
			if (form[0].ftptitle.value == '') {
				parent.sceditor.call("base.notify()", {msg: 'Please enter the FTP Title.', 'type': 'error'});
				return;
			}

			var titleValidator = /^[A-Za-z0-9.@ ']{0,20}$/;
			if (!titleValidator.test(form[0].ftptitle.value)) {
				parent.sceditor.call("base.notify()", {msg: 'You may only use letters, numbers, <.> and <@> for the FTP Title.', 'type': 'error'});
				return;
			}

			form[0].ftphost.value = $.trim(form[0].ftphost.value);

			if (form[0].ftphost.value.length > 35) {

			}
			if (form[0].ftphost.value == '') {
				parent.sceditor.call("base.notify()", {msg: 'Please enter the FTP Host.', 'type': 'error'});
				return;
			}

			var hostValidator = /^[A-Za-z0-9.@']{0,35}$/;
			if (!hostValidator.test(form[0].ftphost.value)) {
				parent.sceditor.call("base.notify()", {msg: 'You may only use letters, numbers, <.> and <@> for FTP Host.', 'type': 'error'});
				return;
			}

			form[0].ftpuser.value = $.trim(form[0].ftpuser.value);

			if (form[0].ftpuser.value.length > 20) {

			}
			if (form[0].ftpuser.value == '') {
				parent.sceditor.call("base.notify()", {msg: 'Please enter the FTP User.', 'type': 'error'});
				return;
			}

			var userValidator = /^[A-Za-z0-9.@']{0,20}$/;
			if (!userValidator.test(form[0].ftpuser.value)) {
				parent.sceditor.call("base.notify()", {msg: 'You may only use letters, numbers, <.> and <@> for FTP User.', 'type': 'error'});
				return;
			}

			$("form").submit();
		}

		function closeme() {
			parent.sceditor.call('base.closePopup()', {}, POPUP_ID);
			//parent.sceditor.call("base.ftp.reloadList()", {});
		}

		function changePort(protocol) {
			if (protocol == 'SFTP') {
				$("[name='ftpport']").val(22);
			} else {
				$("[name='ftpport']").val(21);
			}
		}
	</script>
	<style>
		body {
			margin-bottom: 0;
			padding-bottom: 0;
		}
	</style>
</head>
<body data-width='400px' data-height="370px" data-controls="{'Save FTP Details' : 'save','Close':'closeme'}">
	<div class="infopopup">
		<form class="" method="post">
			<div class="infooptionscontainer" style="padding-right: 0;">
				<?php
				if (isset($_GET['id']) && $_GET['id'] != '') {
					$ftps = get_option('ftps');
					$ftps = json_decode($ftps, true);
					$id = search_in_array($ftps, $_GET['id']);
					if ($id != '' && $id != false) {
						if (isset($_POST['ftphost']) && $_POST['ftphost'] != '') {
							$array_ftp = array();
							$array_ftp['ftp_host'] = $_POST['ftphost'];
							$array_ftp['ftp_username'] = $_POST['ftpuser'];
							$array_ftp['ftp_password'] = Cipher::encrypt($_POST['ftppwd']);
							$array_ftp['ftp_mode'] = $_POST['ftpmode'];
							$array_ftp['ftp_domain'] = $_POST['ftptitle'];
							$array_ftp['ftp_port'] = $_POST['ftpport'];
							$array_ftp['ftp_protocol'] = $_POST['ftpprotocol'];
							$array_ftp['ftp_log_id'] = $_GET['id'];

							unset($ftps[$_GET['id']]);

							$ftps[$_GET['id']] = $array_ftp;

							update_option('ftps', json_encode($ftps, true));
							?>
							<script type="text/javascript">
								$(document).ready(function(e) {
									parent.sceditor.call("base.notify()", {msg: 'Successfully updated.', 'type': 'success'});
									parent.sceditor.call("base.ftp.reloadList()", {});
								});
							</script>
							<?php
						}
						$ftp = $ftps[$id];
						$ftphost = $ftp['ftp_host'];
						$ftpuser = $ftp['ftp_username'];
						$ftppwd = Cipher::decrypt($ftp['ftp_password']);
						$ftptitle = $ftp['ftp_domain'];
						$ftpport = $ftp['ftp_port'];
						if (isset($ftp['ftp_protocol'])) {
							$ftpprotocol = $ftp['ftp_protocol'];
						}
						if (isset($ftp['ftp_mode'])) {
							$ftpmode = $ftp['ftp_mode'];
						}
					}
				}
				?>
				<?php
				if (isset($_POST['ftphost']) && $_POST['ftphost'] != '' && !isset($_GET['id'])) {

					$array_ftp = array();
					$array_ftp['ftp_host'] = $_POST['ftphost'];
					$array_ftp['ftp_username'] = $_POST['ftpuser'];
					$array_ftp['ftp_password'] = Cipher::encrypt($_POST['ftppwd']);
					$array_ftp['ftp_domain'] = $_POST['ftptitle'];
					$array_ftp['ftp_port'] = $_POST['ftpport'];
					$array_ftp['ftp_protocol'] = $_POST['ftpprotocol'];
					$array_ftp['ftp_mode'] = $_POST['ftpmode'];
					$timestamp = (int) time();
					$array_ftp['ftp_log_id'] = 'xey9zenz' . strtolower($timestamp);
					$availableConn = 10;

					$old = get_option('ftps');
					$old = json_decode($old, true);

					if (sizeof($old) < $availableConn) {
						$old[$array_ftp['ftp_log_id']] = $array_ftp;

						update_option('ftps', json_encode($old, true));
						?>
						<script type="text/javascript">
							$(document).ready(function(e) {
								parent.sceditor.call("base.notify()", {msg: 'Successfully saved.', 'type': 'success'});
								parent.sceditor.call("base.ftp.reloadList()", {});
							});
						</script>
					<?php } else { ?>
						<script type="text/javascript">
							$(document).ready(function(e) {
								parent.sceditor.call("base.notify()", {msg: 'Your membership does not allow any more FTP connections. Click <a href="<?php echo base_url(); ?>/membership">here</a> to update now to add FTP functionality.', 'type': 'error'});
								parent.sceditor.call("base.ftp.reloadList()", {});
							});
						</script>
						<?php
					}
				}
				?>

				<div class="infopopupoptions">
					<div class="infopopupoptrow first" style="padding: 0;">

						<!-- FTP: Start -->
						<div class="newftpconn">

							<!-- FTP Connection Details: Start -->
							<div class="newftpconn_details">
								<div class="newftpconndetails_inside">
									<div class="newftpconndetails_col host">
										<div class="newftprow_inside">
											<label for="ftphost">FTP Title</label>
											<div class="infopopupfield">
												<input type="text" 
													   class="text" 
													   id="ftptitle" 
													   name="ftptitle" 
													   maxlength="20" 
													   value="<?php echo isset($ftptitle) ? $ftptitle : ''; ?>" 
													   placeholder="My Website" />

												<div class="clear"></div>
											</div>

											<div class="clear"></div>
										</div>

										<div class="clear"></div>
									</div>
									<div class="newftpconndetails_col host">
										<div class="newftprow_inside">
											<label for="ftphost">Host</label>
											<div class="infopopupfield">
												<input type="text" 
													   class="text" 
													   id="ftphost" 
													   name="ftphost" 
													   maxlength="35" 
													   value="<?php echo isset($ftphost) ? $ftphost : ''; ?>" 
													   placeholder="www.domain.com" />

												<div class="clear"></div>
											</div>

											<div class="clear"></div>
										</div>

										<div class="clear"></div>
									</div>
									<div class="newftpconndetails_col user">
										<div class="newftprow_inside">
											<label for="ftpuser">User</label>
											<div class="infopopupfield">
												<input type="text" 
													   class="text" 
													   id="ftpuser" 
													   name="ftpuser" 
													   maxlength="20" 
													   value="<?php echo isset($ftpuser) ? $ftpuser : ''; ?>" 
													   placeholder="username" />

												<div class="clear"></div>
											</div>

											<div class="clear"></div>
										</div>

										<div class="clear"></div>
									</div>
									<div class="newftpconndetails_col pwd">
										<div class="newftprow_inside">
											<label for="ftppwd">Password</label>
											<div class="infopopupfield">
												<input type="password" 
													   class="text" 
													   id="ftppwd" 
													   name="ftppwd" 
													   maxlength="20" 
													   value="<?php echo isset($ftppwd) ? $ftppwd : ''; ?>" 
													   placeholder="123456" />

												<div class="clear"></div>
											</div>

											<div class="clear"></div>
										</div>

										<div class="clear"></div>
									</div>
									<div class="newftpconndetails_col double">

										<?php /* Port: Start */ ?>
										<div class="newftprow_inside">
											<label for="ftpport">Port</label>
											<div class="infopopupfield">
												<input type="text" 
													   class="text" 
													   id="ftpport" 
													   name="ftpport" 
													   value="<?php echo (isset($ftpport) && $ftpport != '') ? $ftpport : (isset($ftpprotocol) && $ftprotocol == 'SFTP' ? '22' : '21'); ?>" 
													   placeholder="21" />

												<div class="clear"></div>
											</div>

											<div class="clear"></div>
										</div>
										<?php /* Port: End */ ?>

										<?php /* Protocol: Start */ ?>
										<div class="newftprow_inside">
											<label for="ftpprotocol">Protocol</label>
											<div class="infopopupfield">
												<select class="select" name="ftpprotocol" id="ftpprotocol" onchange="changePort(this.options[this.selectedIndex].value)">
													<option value="FTP">FTP</option>
													<option value="SFTP" <?php echo (isset($ftpprotocol) && $ftpprotocol == 'SFTP' ? 'selected=""' : ''); ?>>SFTP</option>
												</select>

												<?php /* NOTE: Default protocol is FTP */ ?>
												<?php /* NOTE: If SFTP is selected, port field automatically switched to 22 */ ?>
												<?php /* NOTE: If  FTP is selected, port field automatically switched to 21 */ ?>

												<div class="clear"></div>
											</div>

											<div class="clear"></div>
										</div>
										<?php /* Protocol: End */ ?>

										<div class="clear"></div>
									</div>
                                                                        <div class="newftpconndetails_col mode">
                                                                            <div class="newftprow_inside">
                                                                                <label>Transfer Mode</label>
                                                                                <div class="infopopupfield">
                                                                                    <label for="ftpmodepassive"><input id="ftpmodepassive" type="radio" name="ftpmode" value="0" <?php echo !isset($ftpmode) || $ftpmode != '1' ? 'checked="checked"' : ''; ?> /> Passive</label>
                                                                                    <label for="ftpmodeactive"><input id="ftpmodeactive" type="radio" name="ftpmode" value="1" <?php echo isset($ftpmode) && $ftpmode == '1' ? 'checked="checked"' :''; ?> /> Active</label>
                                                                                    <div class="clear"></div>
                                                                                </div>
                                                                                <div class="clear"></div>
                                                                            </div>
                                                                         </div>

									<?php /*
									  <div class="newftpconndetails_col type">
									  <div class="newftprow_inside">
									  <label for="ftptransfertype">Transfer Type</label>
									  <div class="infopopupfield">
									  <select class="select" id="ftptransfertype">
									  <option>ASCII</option>
									  <option>Binary</option>
									  </select>

									  <div class="clear"></div>
									  </div>

									  <div class="clear"></div>
									  </div>

									  <div class="clear"></div>
									  </div>
									 */ ?>

									<div class="clear"></div>
								</div>

								<div class="clear"></div>
							</div>
							<!-- FTP Connection Details: End -->

							<div class="clear"></div>
						</div>
						<!-- FTP: End -->

						<div class="clear"></div>
					</div>

					<div class="clear"></div>
				</div>

				<div class="clear"></div>
			</div>
		</form>

		<div class="clear"></div>
	</div>
</body>
</html>