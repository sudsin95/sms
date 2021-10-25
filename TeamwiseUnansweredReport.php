
<?php 
// base function should include on every page
include("../site-config-inc.php");

$tableName    = $functions->getTableName($pageURL)['menu_table_name'];
//echo $pageURL;exit;
$PageName     = $functions->getTableName($pageURL)['menu_name'];
$headerTitle  = $functions->getTableName($pageURL)['menu_header_title'];
$owner_column = "";
// end base
// filter option

$dropDownFilter = "No";
$switchFilter	= "No";

if(isset($_POST['cancel'])){
	header("location:Teamwise_Unanswered_Report.php");	
	exit;
}



?>
<!DOCTYPE html>
<!--[if IE 8]> <html lang="en" class="ie8"> <![endif]-->
<!--[if !IE]><!-->
<html lang="en">
<!--<![endif]-->
<head>
	<?php 
	include("../includes/header.php");
	?>
	
	<link href="../assets/plugins/DataTables/extensions/Buttons/css/buttons.bootstrap.min.css" rel="stylesheet" />
	<link href="../assets/plugins/datatables.net-fixedcolumns-bs4/css/fixedColumns.bootstrap4.min.css" rel="stylesheet" />
	<style>
	.dataTables_filter{
		visibility:visible;
	};
	div.container { max-width: 1200px }
	.select2-selection__rendered{color:black}
	.backcolor{background:none !important;border-color:white !important;}
    .textMiddle {
		text-align: center;
	}
	</style>
</head>
<body>
	<!-- begin #page-loader -->
	<div id="page-loader" class="fade"><span class="spinner"></span></div>
	<!-- end #page-loader -->
	
	<!-- begin #page-container -->
	<div id="page-container" class="fade page-sidebar-fixed page-header-fixed <?php echo $navBarClass;?>">
		
		<!-- begin #header -->
		<div id="header" class="header navbar-default">
			<!-- begin navbar-header -->
			<?php 
			include("../includes/header_account.php");
			?>
			<!-- end header navigation right -->
		</div>
		<!-- end #header -->
		
		<!-- begin #sidebar -->
		<div id="sidebar" class="sidebar">
			<!-- begin sidebar scrollbar -->
			<?php 
			include("../includes/navbar.php");
			?>
			<!-- end sidebar scrollbar -->
		</div>
		<div class="sidebar-bg-dark"></div>
		<!-- end #sidebar -->
	
		<!-- begin #content -->
		<div id="content" class="content">
			<!-- begin breadcrumb -->
			<h1 class="page-header">TeamWise Unanswered Report</h1>
			<!-- end breadcrumb -->
			<!-- begin page-header -->
			
			<!-- end page-header -->
			<!-- begin row -->
			<div class="row">
			    
			    <!-- begin col-12 -->
			    <div class="col-lg-12">
                    <!-- begin panel -->
                    <div class="container-fluid">
                        <div class="panel panel-inverse">
							<div class="panel-body">
                            <table id='report' class='order-column table table-striped table-bordered' style="width:100%;">
										<thead>
										<tr>
											<?php
																						
											echo '<th>Team Name</th>';
											
											echo '<th>Total Unanswered</th>';
											
											?>
											
										</tr>
										</thead>
                                            <?php 

                                            $teamSQL = $functions->query("SELECT 
                                            team_members.team_id,
                                            team_master.team_name,
                                            SUM(IF(ticketplus.ost_ticket.isanswered = 0 AND TIMESTAMPDIFF(HOUR, ticketplus.ost_ticket.updated, NOW()) > 24,1,0)) AS Total 
                                            FROM team_members 
                                            LEFT JOIN ticketplus.ost_ticket ON team_members.staff_id = ticketplus.ost_ticket.staff_id 
                                            LEFT JOIN team_master ON team_members.team_id = team_master.id
                                            WHERE ticketplus.ost_ticket.status_id != 31 GROUP BY team_members.team_id");

                                            if($functions->num_rows($teamSQL) > 0){
                                                while($teamRow = $functions->fetch($teamSQL)){
													$teamMemberSQL = $functions->query("SELECT ost_staff.FullName,
													SUM(IF(ticketplus.ost_ticket.isanswered = 0 AND TIMESTAMPDIFF(HOUR, ticketplus.ost_ticket.updated, NOW()) > 24,1,0)) AS MemberTotal 
													FROM team_members 
													LEFT JOIN 
													ticketplus.ost_ticket ON team_members.staff_id = ticketplus.ost_ticket.staff_id 
													LEFT JOIN 
													ost_staff ON team_members.staff_id = ost_staff.staff_id
													WHERE 
													ticketplus.ost_ticket.status_id != 31 AND team_members.team_id = '".$teamRow['team_id']."' GROUP BY team_members.staff_id");
													$memberHTML = "<div><small><table>";
													$memberHTML .= "<tr><td><strong>Staff Name</strong></td><td><strong>Unanswered Total</strong></td></tr>";
													if(!empty($functions->num_rows($teamMemberSQL))){
														while($memberRow = $functions->fetch($teamMemberSQL)){
															$ticketUrl   = "https://spinesupport.in/staff_ticket?Filter&page=1&unAnswered&state=Open&assigned_filter=".$memberRow['staff_id'];
															$memberHTML .= '<tr><td>'.$memberRow['FullName'].'</td><td class=textMiddle>'.$memberRow['MemberTotal'].'</td></tr>';
														}
													}
													$memberHTML .= "</table></small></div>";
                                                    echo '<tr>';
                                                    echo '<td>'.$teamRow['team_name'].'</td>';
                                                    echo '<td><a href="#"  html = "'.$memberHTML.'" class="more">'.$teamRow['Total'].'</a></td>';
                                                    echo '</tr>';
                                                }
                                                
                                            }
                                            ?>
										<tbody>
										
									  </tbody>
									</table>
                            </div>	
                        </div>
                    </div>
                    <!-- end panel -->
                </div>
                <!-- end col-10 -->
            </div>
            <!-- end row -->
		</div>
		
		<!-- Model -->

		
		<!-- Model End -->
		
		<!-- begin scroll to top btn -->
		<a href="javascript:;" class="btn btn-icon btn-circle btn-success btn-scroll-to-top fade" data-click="scroll-top"><i class="fa fa-angle-up"></i></a>
		<!-- end scroll to top btn -->
	</div>
	<!-- end page container -->
	
	<!-- ================== BEGIN BASE JS ================== -->
	<script src="../assets/plugins/jquery/jquery-3.2.1.min.js"></script>
	<script src="../assets/plugins/jquery-ui/jquery-ui.min.js"></script>
	<script src="../assets/plugins/bootstrap/4.0.0/js/bootstrap.bundle.min.js"></script>
	<!--[if lt IE 9]>
		<script src="../assets/crossbrowserjs/html5shiv.js"></script>
		<script src="../assets/crossbrowserjs/respond.min.js"></script>
		<script src="../assets/crossbrowserjs/excanvas.min.js"></script>
	<![endif]-->
	
	<script src="../assets/plugins/slimscroll/jquery.slimscroll.min.js"></script>
	<script src="../assets/plugins/js-cookie/js.cookie.js"></script>
	<script src="../assets/js/theme/default.min.js"></script>
	<script src="../assets/js/apps.min.js"></script>

	<!-- <script src="../assets/plugins/parsley/dist/parsley.js"></script> -->
	<script src="../assets/plugins/highlight/highlight.common.js"></script>
	<script src="../assets/js/demo/render.highlight.js"></script>
	<!-- ================== END BASE JS ================== -->
	
	<!-- ================== BEGIN PAGE LEVEL JS ================== -->
	<script src="../assets/plugins/DataTables/media/js/jquery.dataTables.js"></script>
	<script src="../assets/plugins/DataTables/media/js/dataTables.bootstrap.min.js"></script>
	<script src="../assets/plugins/DataTables/extensions/FixedHeader/js/dataTables.fixedHeader.min.js"></script>
	<script src="../assets/js/demo/table-manage-fixed-header.demo.min.js"></script>
	<script src="../assets/js/select2.min.js"></script>
	<script src="../assets/plugins/gritter/js/jquery.gritter.js"></script>
	<script src="../assets/plugins/bootstrap-sweetalert/sweetalert.min.js"></script>
	<script src="../assets/js/demo/ui-modal-notification.demo.min.js"></script>

	<script src="../assets/plugins/bootstrap-datepicker/js/bootstrap-datepicker.js"></script>
	<script src="../assets/plugins/ionRangeSlider/js/ion-rangeSlider/ion.rangeSlider.min.js"></script>
	<script src="../assets/plugins/bootstrap-colorpicker/js/bootstrap-colorpicker.min.js"></script>
	<script src="../assets/plugins/masked-input/masked-input.min.js"></script>
	<script src="../assets/plugins/bootstrap-timepicker/js/bootstrap-timepicker.min.js"></script>
	<script src="../assets/plugins/password-indicator/js/password-indicator.js"></script>
	<script src="../assets/plugins/bootstrap-combobox/js/bootstrap-combobox.js"></script>
	<script src="../assets/plugins/bootstrap-select/bootstrap-select.min.js"></script>
	<script src="../assets/plugins/bootstrap-tagsinput/bootstrap-tagsinput.min.js"></script>
	<script src="../assets/plugins/bootstrap-tagsinput/bootstrap-tagsinput-typeahead.js"></script>
	<script src="../assets/plugins/jquery-tag-it/js/tag-it.min.js"></script>
    <script src="../assets/plugins/bootstrap-daterangepicker/moment.js"></script>
    <script src="../assets/plugins/bootstrap-daterangepicker/daterangepicker.js"></script>
   
    <script src="../assets/plugins/bootstrap-eonasdan-datetimepicker/build/js/bootstrap-datetimepicker.min.js"></script>
    <script src="../assets/plugins/bootstrap-show-password/bootstrap-show-password.js"></script>
    <script src="../assets/plugins/bootstrap-colorpalette/js/bootstrap-colorpalette.js"></script>
    <script src="../assets/plugins/jquery-simplecolorpicker/jquery.simplecolorpicker.js"></script>
    <script src="../assets/plugins/clipboard/clipboard.min.js"></script>
	<script src="../assets/js/demo/form-plugins.demo.min.js"></script>
	<script src="../assets/js/demo/dashboard.min.js"></script>
	<script src="../assets/plugins/DataTables/extensions/Buttons/js/dataTables.buttons.min.js"></script>
	<script src="../assets/plugins/DataTables/extensions/Buttons/js/buttons.bootstrap.min.js"></script>
	<script src="../assets/plugins/DataTables/extensions/Buttons/js/buttons.flash.min.js"></script>
	<script src="../assets/plugins/DataTables/extensions/Buttons/js/jszip.min.js"></script>
	<script src="../assets/plugins/DataTables/extensions/Buttons/js/pdfmake.min.js"></script>
	<script src="../assets/plugins/DataTables/extensions/Buttons/js/vfs_fonts.min.js"></script>
	<script src="../assets/plugins/DataTables/extensions/Buttons/js/buttons.html5.min.js"></script>
	<script src="../assets/plugins/DataTables/extensions/Buttons/js/buttons.print.min.js"></script>
	<script src="../assets/plugins/datatables.net-fixedcolumns/js/dataTables.fixedColumns.min.js"></script>
	<script src="../assets/plugins/datatables.net-fixedcolumns-bs4/js/fixedColumns.bootstrap4.min.js"></script>
	<!-- ================== END PAGE LEVEL JS ================== -->
	<?php 
	include("../includes/footer.php");
	?>	
	
	<script>
        function fetchData(){  
            var element = $(this);  
            var fetch_data = element.attr("html");  	
            return fetch_data;  
        } 
		$(document).ready(function() {
			App.init();
			Highlight.init();
			Notification.init();
			FormPlugins.init();
			$(document).on('click',"[name='filter']", function (e){
				$(this).html('<i class="fas fa-sync fa-spin"></i><span>&nbsp; Wait..</span>');
			})
            //$("#end_date").prop("disabled",true);
            $(document).on("change","#start_date",function(){
                var start_date_val = $(this).val();
                $("#end_date").prop("disabled",false);
                $("#end_date").attr("min",start_date_val);
            })

			$("#team_filter").select2({	
				width:'100%',
				placeholder: "Search Team"
				
			});

            $(".more").popover({
                html:true,
                trigger: 'manual',
                container: 'body',
                title: fetchData,
                sanitize: true,
                animation:false,
                
                
            }).on("mouseenter", function () {
                var _this = this;
                $(this).popover("show");
                $(".popover").on("mouseleave", function () {
                    $(_this).popover('hide');
                });
            }).on("mouseleave", function () {
                var _this = this;
                setTimeout(function () {
                    if (!$(".popover:hover").length) {
                        $(_this).popover("hide");
                    }
                }, 300);
            });
			
			
			$('#report').DataTable({
				
				paging:false,
				"responsive": true,
                "scrollY":   "400px",
                "scrollX":  true,
                "scrollCollapse": true,
				
			});
			
			
			$(".active").closest(".has-sub").addClass("active");
		});	
		
		
	</script>
	<script type="text/javascript">

		
		$(function() {

		$('input[name="datefilter"]').daterangepicker({
			//var startdate = moment();
			startDate: moment().subtract(7, "days"),
        	endDate: moment().endOf('day'),
			autoUpdateInput: false,
			locale: {
				cancelLabel: 'Clear',
				format: 'YYYY-MM-DD'
			}
		});

		});
	</script>
	
</body>
</html>