<!DOCTYPE html>
<html>
<head>
	<meta http-equiv="content-type" content="text/html; charset=ISO-8859-1">
	<title>Replace</title>
	
	<link href="<?php echo base_url(); ?>core/css/popupcontent.css" rel="stylesheet" media="all" />
    <link href="<?php echo base_url(); ?>core/css/jqueryFileTree.css" rel="stylesheet" media="all" />
	<link href="<?php echo base_url(); ?>core/css/fontawesome/font-awesome.css" rel="stylesheet" media="all" />
    <link href="<?php echo base_url(); ?>core/css/contextmenu.css" rel="stylesheet" media="all" />
	<link href="<?php echo base_url(); ?>themes/editor/<?php echo get_option("editor_page_theme") ?>/theme.css" rel="stylesheet" data-default-theme="<?php echo get_option("editor_page_theme") ?>" media="all" />
	
	<script src="<?php echo base_url(); ?>core/js/jquery-1.10.2.min.js" type="text/javascript"></script>
	
	<style>
	html, body {
		width: 100%;
		height: 100%;
		}
		
	* {
		-webkit-box-sizing: border-box;
		-moz-box-sizing: 	border-box;
		box-sizing: 		border-box;
		}
		
		body {
			margin: 0;
			padding: 13px 13px 0;
			}
	
	.background {
        color: #f00 !important;
		}
	.green {
        color: #063 !important;
		}

	.connection_info > i{
        -webkit-transition: background 1.0s ease-in-out;
        -moz-transition:    background 1.0s ease-in-out;
        -ms-transition:     background 1.0s ease-in-out;
        transition:         background 1.0s ease-in-out;
		}
	
	div.ftpconn {}
		
		/* FTP Connection Details */
		div.ftpconn_details {
			margin: 0 0 10px;
			border-bottom: 1px solid #e9e9e9;
			}
			div.ftpconndetails_inside {
				padding: 0 0 10px;
				border-bottom: 1px solid #bebebe;
				}
				div.ftpconndetails_col {
					float: left;
					}
					div.ftpconndetails_col.host {
						width: 25%;
						}
					div.ftpconndetails_col.user {
						width: 25%;
						}
					div.ftpconndetails_col.pwd {
						width: 25%;
						}
					div.ftpconndetails_col.port {
						width: 10%;
						}
					div.ftpconndetails_col.type {
						width: 15%;
						}
					
					div.ftprow_inside {
						padding: 5px;
						}
						div.ftpconndetails_col.host div.ftprow_inside { padding-left: 0; }
						div.ftpconndetails_col.type div.ftprow_inside { padding-right: 0; }
					
						div.ftpconndetails_col label {
							padding: 0 0 5px;
							display: block;
							width: 100%;
							}
						div.ftpconndetails_col div.infopopupfield {
							float: none;
							width: 100%;
							}
							
							/* Fields */
							div.ftpconndetails_col input.ftptext,
							div.ftpconndetails_col select.ftpselect {
								width: 100%;
								}
							
							/* Buttons */
							input.button {
								margin: 0 0 0 7px;
								padding: 6px 15px;
								width: 100%;
								color: #575757;
								font-size: 12px;
								text-transform: uppercase;
								cursor: pointer;
								border: 1px solid #ababab;
								background: -moz-linear-gradient(center top , #e5e5e5 0%, #f0f0f0 100%) repeat scroll 0 0 rgba(0, 0, 0, 0);
								
								text-shadow: 1px 1px 0 rgba(255, 255, 255, 0.2);
								border-radius: 3px;
								border-radius: 3px;
								border-radius: 3px;
								-webkit-box-sizing: border-box;
								-moz-box-sizing: 	border-box;
								box-sizing: 		border-box;
								-webkit-transition: border-color 0.3s ease-in-out,
													-webkit-box-shadow 0.3s ease-in-out;
								-moz-transition: 	border-color 0.3s ease-in-out,
													-moz-box-shadow 0.3s ease-in-out;
								-ms-transition: 	border-color 0.3s ease-in-out,
													-ms-box-shadow 0.3s ease-in-out;
								-o-transition: 		border-color 0.3s ease-in-out,
													-o-box-shadow 0.3s ease-in-out;
								transition: 		border-color 0.3s ease-in-out,
													box-shadow 0.3s ease-in-out;
								}
								input.button.saveftp {}
		
		/* FTP Files */
		div.ftpconn_files {}
			div.ftpconnfiles_inside {}
				
				/* FTP Connection Files Columns */
				div.ftpconnfiles_col {
					width: 48%;
					border: 1px solid #e9e9e9;
					
					-webkit-box-sizing: border-box;
					-moz-box-sizing: 	border-box;
					box-sizing: 		border-box;
					}
					div.ftpconnfiles_col.left 	{ float: left; }
					div.ftpconnfiles_col.middle	{ float: left; }
					div.ftpconnfiles_col.right 	{ float: right; }
					
					div.ftpconnfiles_col.middle { width: 4%; border-width: 0; }
					
					div.ftpconnfilescol_inside {
						border: 1px solid #bebebe;
						}
						div.ftpconnfiles_col.middle div.ftpconnfilescol_inside { border-width: 0; }
					
					/* Controls */
					div.ftpconnfiles_controls {
						/* border-bottom: 1px solid #e9e9e9; */
						}
						div.ftpconnfilescontrols_inside {
							padding: 5px;
							border-bottom: 1px solid #bebebe;
							}
							ul.ftpconnfileslist {
								margin: 0;
								padding: 0;
								}
								ul.ftpconnfileslist li {
									margin: 0;
									padding: 0;
									display: block;
									float: left;
									}
									
									/* Control Item */
									ul.ftpconnfileslist li.ftpconnfilecontrol {
										margin: 0 10px 0 0;
										}
										ul.ftpconnfileslist li.ftpconnfilecontrol.folder {
											margin-left: 5px;
											}
										ul.ftpconnfileslist li.ftpconnfilecontrol a {
											color: #131313;
											
											-webkit-transition: color 0.3s ease-in-out;
											-moz-transition: 	color 0.3s ease-in-out;
											-ms-transition: 	color 0.3s ease-in-out;
											-o-transition: 		color 0.3s ease-in-out;
											transition: 		color 0.3s ease-in-out;
											}
											ul.ftpconnfileslist li.ftpconnfilecontrol:hover a {
												color: #f5b53c;
												}
									
									/* Server Info */
									ul.ftpconnfileslist li.serverinfo {
										float: right;
										}
					
					/* FTP Transfer Direction */
					div.ftpconnfilescol_filenav {
						height: 160px;
						margin-top:70px;
						}
						ul.ftptransferdireclist {
							margin: 0;
							padding: 0;
							list-style-type: none;
							}
							li.ftptransfer {
								padding: 0 10px;
								display: block;
								width: 100%;
								font-size: 30px;
								font-weight: normal;
								text-align: center;
								
								-webkit-box-sizing: border-box;
								-moz-box-sizing: 	border-box;
								box-sizing: 		border-box;
								}
								li.ftptransfer.left {}
								li.ftptransfer.right {}
								
								li.ftptransfer a {
									margin: 0 0 10px;
									padding: 5px;
									display: block;
									color: #787878;
									
									-webkit-transition: color 0.3s ease-in-out;
									-moz-transition: 	color 0.3s ease-in-out;
									-ms-transition: 	color 0.3s ease-in-out;
									-o-transition: 		color 0.3s ease-in-out;
									transition: 		color 0.3s ease-in-out;
									}
									li.ftptransfer.right a { margin-top: 35px; }
									li.ftptransfer a:hover {}
					
					/* File Tree */
					div.ftpconnfilescol_navtree {
						height: 200px;
						background-color: #d0d0d0;
						/*
						overflow-x: hidden;
						overflow-y: scroll;
						margin: 0;
						padding: 5px !important;
						*/
						}
							div.dataTables_scroll { position: relative; height: 100%; }
								div.dataTables_scrollHead {
									position: absolute;
									top: 0;
									left: 0;
									width: 100%;
									}
								div.dataTables_scrollBody {
									/*
									position: relative;
									padding: 0;
									height: 91% !important;
									*/
									}
								.dataTables_wrapper.no-footer .dataTables_scrollBody {
									border-bottom: 0 none;
									}
		
		/* FTP Log */
		div.ftpconn_log {}
			div.ftpconnlog_inside {}
				
				/* FTP Log Textarea */
				textarea.ftpconnlog,div.ftpconnlog {
					padding: 5px 8px;
					width: 100%;
					/* height: 170px; */
					height: 100%;
					color: #000;
					font-family: Monospace;
					font-size: 11px;
					border: 1px solid #bebebe;
					background-color: #fff;
					
					-webkit-box-sizing: border-box;
					-moz-box-sizing: 	border-box;
					box-sizing: 		border-box;
					overflow-y: scroll;
					}
					textarea.ftpconnlog:hover {}
					
					div.ftpconnlog span {
						width: 100%;
						height: auto;
						float: left;
						}
						
					div.ftpconnlog .warning {
						color: #0F0;
						
						}
					div.ftpconnlog .error {
						color: #F00;
						
						}
					div.ftpconnlog .success {
						color: #360;
						}
	
	ul.jqueryFileTree:first-child {
		padding: 0 !important;
	}
	ul.jqueryFileTree {
		min-height: 100%;
	}
	ul.jqueryFileTree li {
		background-position: 5px 3px !important;
		cursor: pointer;
		float: left;
		padding-bottom: 4px;
		padding-top: 3px;
		width: 94%;
		border: 1px dashed transparent;
		border-radius: 3px;
		margin-bottom: 2px;
	}
	ul.jqueryFileTree a:hover {
		background-color: transparent;
	}

	table.dataTable.row-border tbody th,
	table.dataTable.row-border tbody td,
	table.dataTable.display tbody th,
	table.dataTable.display tbody td {
		border-top: 1px solid #fff;
		border-bottom: 1px solid #ddd;
	}
	table.dataTable tbody tr.list_selected td {
		background-color: #bdf !important;
	}
	table.dataTable tbody tr.list_selected td:last-child {}
	table.dataTable tbody tr.list_selected td:first-child {}

	table.dataTable tbody tr.copied td {
		background-color: #ccc !important;
	}
	table.dataTable tbody tr.copied td:last-child {
		border-bottom-right-radius: 3px !important;
		border-top-right-radius: 3px !important;
		border-right: 1px dashed #999 !important;
	}
	table.dataTable tbody tr.copied td:first-child {
		border-bottom-left-radius: 3px!important;
		border-top-left-radius: 3px!important;
		border-left: 1px dashed #999!important;
	}

	table.dataTable tbody tr.sortable-placeholder td {
		border-top: 1px dashed #f00;
		border-bottom: 1px dashed #f00;
		height: 17px;
	}
	table.dataTable tbody tr.sortable-placeholder td:first-child {
		border-left: 1px dashed #f00;
		height: 17px;
	}
	table.dataTable tbody tr.sortable-placeholder td:last-child {
		border-right: 1px dashed #f00;
		height: 17px;
	}
	tbody tr td span {
		visibility:hidden;
		}
	
	ul.jqueryFileTree > li.copied {
		background-color: #ccc;
		border:1px dashed #999;
		border-radius: 3px;
	}
	ul.jqueryFileTree > li.list_hover {
		background-color: #cfc;
		border:1px dashed #fff;
		border-radius: 3px;
	}

	#cmroot > .disable,#cmroot > .b-m-idisable {
		background-color: #f00;
	}
	ul.jqueryFileTree li input.rename {
		border: 0;
		width: auto;
		float: left;
	}
	table.dataTable {
		table-layout: fixed !important;
	}
	.dataTables_scrollBody {
		overflow-x:hidden!important;
	}
	.dataTables_scrollBody {
		/* height:100% !important; */
	}
	.connection_info {
		float:right !important;
		float: right !important;
		padding-left: 7px !important;
		padding-right: 2px !important;
	}
	.connection_info i {
		color: #d0d0d0;
	}
	
	#workspace{
		position:absolute;
		margin-top:-3px;
	}
	.text{
		border: 1px solid #a9a9a9;
		border-radius: 2px;
		box-sizing: border-box;
		color: #353535;
		font-size: 13px;
		height: 30px;
		margin: 0;
		padding: 5px;
		transition: border-color 0.3s ease-in-out 0s, box-shadow 0.3s ease-in-out 0s;
		width: 100%;
		}
	</style>
</head>
<body data-width='600' data-height="310" data-controls="{'Cancel' : 'closeme','Save' : 'ftp_save'}" style="height: 100%;">
<div class="infopopup" style="height: 100%;">
	<form id="" class="" style="height: 100%;">
		<div class="infooptionscontainer" style="padding-right: 0; height: 100%;"> <!-- -->
			<div class="infopopupoptions" style="height: 100%;">
				<div class="infopopupoptrow first" style="padding: 0; height: 100%;">
					
					<!-- FTP: Start -->
					<div class="ftpconn" style="position: relative; height: 100%;">
						<!-- FTP Files: Start -->
						<div class="ftpconn_files" style="position: relative; height: 100%;">
							<div class="ftpconnfiles_inside" style="height: 100%;">
								<div style="float: left; margin-bottom: 5px; width: 100%; height: auto;">
									Find: 
									<input id="file_name" class="text" style="width: 357px;" type="text" value="" />
									<input type="button" style="width:auto;" value="Find" id="savetococal" />
                                </div>
								<div style="float: left; margin-bottom: 5px; width: 100%; height: auto;">
									Replace: 
									<input id="file_name" class="text" style="width:357px;" type="text" value="" />
									<input type="button" class="button " style="width:auto;" value="Find and Replace" id="savetococal" />
                                </div>
							
							</div>
							
							<div class="clear"></div>
						</div>
						<!-- FTP Files: End -->
						
						<!-- FTP Log: Start -->
						
						<!-- FTP Log: End -->
						
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