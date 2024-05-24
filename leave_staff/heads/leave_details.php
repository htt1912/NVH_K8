<?php error_reporting(0);?>
<?php include('includes/header.php')?>
<?php include('../includes/session.php')?>

<?php
	// code for update the read notification status
	$isread=1;
	$did=intval($_GET['leaveid']);  
	date_default_timezone_set('Asia/Kolkata');
	$admremarkdate=date('Y-m-d G:i:s ', strtotime("now"));
	$sql="update tblleaves set IsRead=:isread where id=:did";
	$query = $dbh->prepare($sql);
	$query->bindParam(':isread',$isread,PDO::PARAM_STR);
	$query->bindParam(':did',$did,PDO::PARAM_STR);
	$query->execute();

	// code for action taken on leave
	if(isset($_POST['update']))
	{ 
		$did=intval($_GET['leaveid']);
		$description=$_POST['description'];
		$status=$_POST['status'];   
		$av_leave=$_POST['av_leave'];
		$num_days=$_POST['num_days'];

		// $REMLEAVE = $av_leave - $num_days;
		$reg_remarks = 'Leave was Rejected. Registra/Registry will not see it';
		$reg_status = 2;
		date_default_timezone_set('Asia/Kolkata');
		$admremarkdate=date('Y-m-d G:i:s ', strtotime("now"));

		if ($status === '2') {
			$result = mysqli_query($conn,"update tblleaves, tblemployees set tblleaves.AdminRemark='$description',tblleaves.Status='$status',tblleaves.AdminRemarkDate='$admremarkdate', tblleaves.registra_remarks = '$reg_remarks', tblleaves.admin_status = '$reg_status' where tblleaves.empid = tblemployees.emp_id AND tblleaves.id='$did'");

				if ($result) {
			     	echo "<script>alert('Leave updated Successfully');</script>";
					} else{
					  die(mysqli_error());
				   }
		}
		elseif ($status === '1') {
				$result = mysqli_query($conn,"update tblleaves, tblemployees set tblleaves.AdminRemark='$description',tblleaves.Status='$status',tblleaves.AdminRemarkDate='$admremarkdate' where tblleaves.empid = tblemployees.emp_id AND tblleaves.id='$did'");

				if ($result) {
			     	echo "<script>alert('Cập nhật thành công!');</script>";
					} else{
					  die(mysqli_error());
				   }
		}
	}

?>

<style>
	input[type="text"]
	{
	    font-size:16px;
	    color: #0f0d1b;
	    font-family: Verdana, Helvetica;
	}

	.btn-outline:hover {
	  color: #fff;
	  background-color: #524d7d;
	  border-color: #524d7d; 
	}

	textarea { 
		font-size:16px;
	    color: #0f0d1b;
	    font-family: Verdana, Helvetica;
	}

	textarea.text_area{
        height: 8em;
        font-size:16px;
	    color: #0f0d1b;
	    font-family: Verdana, Helvetica;
      }

	</style>

<body>
	<div class="pre-loader">
		<div class="pre-loader-box">
			<div class="loader-logo"><img src="../vendors/images/deskapp-logo-svg.png" alt=""></div>
			<div class='loader-progress' id="progress_div">
				<div class='bar' id='bar1'></div>
			</div>
			<div class='percent' id='percent1'>0%</div>
			<div class="loading-text">
				Loading...
			</div>
		</div>
	</div>

	<?php include('includes/navbar.php')?>

	<?php include('includes/right_sidebar.php')?>

	<?php include('includes/left_sidebar.php')?>

	<div class="mobile-menu-overlay"></div>

	<div class="main-container">
		<div class="pd-ltr-20">
			<div class="min-height-200px">
				<div class="page-header">
					<div class="row">
						<div class="col-md-6 col-sm-12">
							<div class="title">
								<h4>Chi tiết nghỉ phép</h4>
							</div>
							<nav aria-label="breadcrumb" role="navigation">
								<ol class="breadcrumb">
									<li class="breadcrumb-item"><a href="admin_dashboard.php">Trang chủ</a></li>
									<li class="breadcrumb-item active" aria-current="page">Nghỉ phép</li>
								</ol>
							</nav>
						</div>
					</div>
				</div>

				<div class="pd-20 card-box mb-30">
					<div class="clearfix">
						<div class="pull-left">
							<h4 class="text-blue h4">Chi tiết nghỉ phép</h4>
							<p class="mb-20"></p>
						</div>
					</div>
					<form method="post" action="">

						<?php 
						if(!isset($_GET['leaveid']) && empty($_GET['leaveid'])){
							header('Location: admin_dashboard.php');
						}
						else {
						
						$lid=intval($_GET['leaveid']);
						$sql = "SELECT tblleaves.id as lid,tblemployees.FirstName,tblemployees.LastName,tblemployees.emp_id,tblemployees.Gender,tblemployees.Phonenumber,tblemployees.EmailId,tblemployees.Av_leave,tblleaves.LeaveType,tblleaves.ToDate,tblleaves.FromDate,tblleaves.Description,tblleaves.PostingDate,tblleaves.Status,tblleaves.AdminRemark,tblleaves.admin_status,tblleaves.registra_remarks,tblleaves.AdminRemarkDate,tblleaves.num_days from tblleaves join tblemployees on tblleaves.empid=tblemployees.emp_id where tblleaves.id=:lid";
						$query = $dbh -> prepare($sql);
						$query->bindParam(':lid',$lid,PDO::PARAM_STR);
						$query->execute();
						$results=$query->fetchAll(PDO::FETCH_OBJ);
						$cnt=1;
						if($query->rowCount() > 0)
						{
						foreach($results as $result)
						{         
						?>  

						<div class="row">
							<div class="col-md-4 col-sm-12">
								<div class="form-group">
									<label style="font-size:16px;"><b>Tên đẩy đủ</b></label>
									<input type="text" class="selectpicker form-control" data-style="btn-outline-primary" readonly value="<?php echo htmlentities($result->FirstName." ".$result->LastName);?>">
								</div>
							</div>
							<div class="col-md-4 col-sm-12">
								<div class="form-group">
									<label style="font-size:16px;"><b>Email</b></label>
									<input type="text" class="selectpicker form-control" data-style="btn-outline-info" readonly value="<?php echo htmlentities($result->EmailId);?>">
								</div>
							</div>
							<div class="col-md-4 col-sm-12">
								<div class="form-group">
									<label style="font-size:16px;"><b>Giới tính</b></label>
									<input type="text" class="selectpicker form-control" data-style="btn-outline-success" readonly value="<?php echo htmlentities($result->Gender);?>">
								</div>
							</div>
							<div class="col-md-4 col-sm-12">
								<div class="form-group">
									<label style="font-size:16px;"><b>Số điện thoại</b></label>
									<input type="text" class="selectpicker form-control" data-style="btn-outline-primary" readonly value="<?php echo htmlentities($result->Phonenumber);?>">
								</div>
							</div>
							<div class="col-md-4 col-sm-12">
								<div class="form-group">
									<label style="font-size:16px;"><b>Loại nghỉ phép</b></label>
									<input type="text" class="selectpicker form-control" data-style="btn-outline-info" readonly value="<?php echo htmlentities($result->LeaveType);?>">
								</div>
							</div>
							<div class="col-md-4 col-sm-12">
								<div class="form-group">
									<label style="font-size:16px;"><b>Ngày áp dụng</b></label>
									<input type="text" class="selectpicker form-control" data-style="btn-outline-success" readonly value="<?php echo htmlentities($result->PostingDate);?>">
								</div>
							</div>

							<div class="col-md-4 col-sm-12">
								<div class="form-group">
									<label style="font-size:16px;"><b>Số ngày áp dụng</b></label>
									<input type="text" class="selectpicker form-control" data-style="btn-outline-info" readonly name="num_days" value="<?php echo htmlentities($result->num_days);?>">
								</div>
							</div>
							<div class="col-md-4 col-sm-12">
								<div class="form-group">
									<label style="font-size:16px;"><b>Số ngày khả dụng</b></label>
									<input type="text" class="selectpicker form-control" data-style="btn-outline-info" readonly name="av_leave" value="<?php echo htmlentities($result->Av_leave);?>">
								</div>
							</div>
							<div class="col-md-4">
								<div class="form-group">
									<label style="font-size:16px;"><b>Thời gian nghỉ phép</b></label>
									<input type="text" class="selectpicker form-control" data-style="btn-outline-info" readonly value="From <?php echo htmlentities($result->FromDate);?> to <?php echo htmlentities($result->ToDate);?>">
								</div>
							</div>

						</div>
						<div class="form-group row">
								<label style="font-size:16px;" class="col-sm-12 col-md-2 col-form-label"><b>Lý do  nghỉ phép</b></label>
								<div class="col-sm-12 col-md-10">
									<textarea name=""class="form-control text_area" readonly type="text"><?php echo htmlentities($result->Description);?></textarea>
								</div>
						</div>
						<div class="form-group row">
								<label style="font-size:16px;" class="col-sm-12 col-md-2 col-form-label"><b>Trưởng phòng nhận xét</b></label>
								<div class="col-sm-12 col-md-10">
									<?php
									if ($result->AdminRemark==""): ?>
									  <input type="text" class="selectpicker form-control" data-style="btn-outline-primary" readonly value="<?php echo "Chờ phê duyệt"; ?>">
									<?php else: ?>
									  <input type="text" class="selectpicker form-control" data-style="btn-outline-primary" readonly value="<?php echo htmlentities($result->AdminRemark); ?>">
									<?php endif ?>
								</div>
						</div>
						<div class="form-group row">
								<label style="font-size:16px;" class="col-sm-12 col-md-2 col-form-label"><b>Nhận xét đăng ký</b></label>
								<div class="col-sm-12 col-md-10">
									<?php
									if ($result->registra_remarks==""): ?>
									  <input type="text" class="selectpicker form-control" data-style="btn-outline-primary" readonly value="<?php echo "Chờ phê duyệt"; ?>">
									<?php else: ?>
									  <input type="text" class="selectpicker form-control" data-style="btn-outline-primary" readonly value="<?php echo htmlentities($result->registra_remarks); ?>">
									<?php endif ?>
								</div>
						</div>
						<div class="row">
							<div class="col-md-3">
								<div class="form-group">
								   <label style="font-size:16px;"><b>Ngày thực hiện hành động</b></label>
								   <?php
									if ($result->AdminRemarkDate==""): ?>
									  <input type="text" class="selectpicker form-control" data-style="btn-outline-primary" readonly value="<?php echo "NA"; ?>">
									<?php else: ?>
									  <input type="text" class="selectpicker form-control" data-style="btn-outline-primary" readonly value="<?php echo htmlentities($result->AdminRemarkDate); ?>">
									<?php endif ?>

								</div>
							</div>
							<div class="col-md-3">
								<div class="form-group">
									<label style="font-size:16px;"><b>Rời khỏi trạng thái từ trưởng phòng</b></label>
									<?php $stats=$result->Status;?>
									<?php
									if ($stats==1): ?>
									  <input type="text" style="color: green;" class="selectpicker form-control" data-style="btn-outline-primary" readonly value="<?php echo "Xác nhận"; ?>">
									<?php
									 elseif ($stats==2): ?>
									  <input type="text" style="color: red; font-size: 16px;" class="selectpicker form-control" data-style="btn-outline-primary" readonly value="<?php echo "Từ chối"; ?>">
									  <?php
									else: ?>
									  <input type="text" style="color: blue;" class="selectpicker form-control" data-style="btn-outline-primary" readonly value="<?php echo "Đang xử lý"; ?>">
									<?php endif ?>
								</div>
							</div>
							<div class="col-md-3">
								<div class="form-group">
									<label style="font-size:16px;"><b>Đăng ký/Trạng thái đăng ký</b></label>
									<?php $ad_stats=$result->admin_status;?>
									<?php
									if ($ad_stats==1): ?>
									  <input type="text" style="color: green;" class="selectpicker form-control" data-style="btn-outline-primary" readonly value="<?php echo "Xác nhận"; ?>">
									<?php
									 elseif ($ad_stats==2): ?>
									  <input type="text" style="color: red; font-size: 16px;" class="selectpicker form-control" data-style="btn-outline-primary" readonly value="<?php echo "Từ chối"; ?>">
									  <?php
									else: ?>
									  <input type="text" style="color: blue;" class="selectpicker form-control" data-style="btn-outline-primary" readonly value="<?php echo "Đang xử lý"; ?>">
									<?php endif ?>
								</div>
							</div>

							<?php 
							if(($stats==0 AND $ad_stats==0) OR ($stats==2 AND $ad_stats==0) OR ($stats==2 AND $ad_stats==2))
							  {

							 ?>
							<div class="col-md-3">
								<div class="form-group">
									<label style="font-size:16px;"><b></b></label>
									<div class="modal-footer justify-content-center">
										<button class="btn btn-primary" id="action_take" data-toggle="modal" data-target="#success-modal">Thực hiện&nbsp;Hành động</button>
									</div>
								</div>
							</div>

							<form name="adminaction" method="post">
  								<div class="modal fade" id="success-modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
									<div class="modal-dialog modal-dialog-centered" role="document">
										<div class="modal-content">
											<div class="modal-body text-center font-18">
												<h4 class="mb-20">Rời khỏi hành động</h4>
												<select name="status" required class="custom-select form-control">
													<option value="">Chọn</option>
				                                          <option value="1">Xác nhận</option>
				                                          <option value="2">Từ chối</option>
												</select>

												<div class="form-group">
													<label></label>
													<textarea id="textarea1" name="description" class="form-control" required placeholder="Mô tả" length="300" maxlength="300"></textarea>
												</div>
											</div>
											<div class="modal-footer justify-content-center">
												<input type="submit" class="btn btn-primary" name="update" value="Gửi">
											</div>
										</div>
									</div>
								</div>
  							</form>

							 <?php }?> 
						</div>

						<?php $cnt++;} } }?>
					</form>
				</div>

			</div>
			
			<?php include('includes/footer.php'); ?>
		</div>
	</div>
	<!-- js -->

	<?php include('includes/scripts.php')?>
</body>
</html>