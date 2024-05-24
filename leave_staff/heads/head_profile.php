<?php include('includes/header.php')?>
<?php include('../includes/session.php')?>
<?php
if(isset($_POST['new_update']))
{
	$empid=$session_id;
	$fname=$_POST['fname'];
	$lname=$_POST['lastname'];   
	$email=$_POST['email'];  
	$dob=$_POST['dob']; 
	$department=$_POST['department']; 
	$address=$_POST['address']; 
	$gender=$_POST['gender'];  
	$phonenumber=$_POST['phonenumber'];

    $result = mysqli_query($conn,"update tblemployees set FirstName='$fname', LastName='$lname', EmailId='$email', Gender='$gender', Dob='$dob', Department='$department', Address='$address', Phonenumber='$phonenumber' where emp_id='$session_id'         
		")or die(mysqli_error());
    if ($result) {
     	echo "<script>alert('Cập nhật thành công!);</script>";
     	echo "<script type='text/javascript'> document.location = 'head_profile.php'; </script>";
	} else{
	  die(mysqli_error());
   }

}

if (isset($_POST["update_image"])) {

	$image = $_FILES['image']['name'];

	if(!empty($image)){
		move_uploaded_file($_FILES['image']['tmp_name'], '../uploads/'.$image);
		$location = $image;	
	}
	else {
		echo "<script>alert('Please Select Picture to Update');</script>";
	}

    $result = mysqli_query($conn,"update tblemployees set location='$location' where emp_id='$session_id'         
		")or die(mysqli_error());
    if ($result) {
     	echo "<script>alert('Đã cập nhật thành công');</script>";
     	echo "<script type='text/javascript'> document.location = 'head_profile.php'; </script>";
	} else{
	  die(mysqli_error());
   }
}

?>

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

	<div class="mobile-menu-overlay"></div>

	<div class="main-container">
		<div class="pd-ltr-20 xs-pd-20-10">
			<div class="min-height-200px">
				<div class="page-header">
					<div class="row">
						<div class="col-md-12 col-sm-12">
							<div class="title">
								<h4>Profile</h4>
							</div>
							<nav aria-label="breadcrumb" role="navigation">
								<ol class="breadcrumb">
									<li class="breadcrumb-item"><a href="admin_dashboard">Dashboard</a></li>
									<li class="breadcrumb-item active" aria-current="page">Trang cá nhân</li>
								</ol>
							</nav>
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-xl-4 col-lg-4 col-md-4 col-sm-12 mb-30">
						<div class="pd-20 card-box height-100-p">

							<?php $query= mysqli_query($conn,"select * from tblemployees LEFT JOIN tbldepartments ON tblemployees.Department = tbldepartments.DepartmentShortName where emp_id = '$session_id'")or die(mysqli_error());
								$row = mysqli_fetch_array($query);
							?>

							<div class="profile-photo">
								<a href="modal" data-toggle="modal" data-target="#modal" class="edit-avatar"><i class="fa fa-pencil"></i></a>
								<img src="<?php echo (!empty($row['location'])) ? '../uploads/'.$row['location'] : '../uploads/NO-IMAGE-AVAILABLE.jpg'; ?>" alt="" class="avatar-photo">
								<form method="post" enctype="multipart/form-data">
									<div class="modal fade" id="modal" tabindex="-1" role="dialog" aria-labelledby="modalLabel" aria-hidden="true">
										<div class="modal-dialog modal-dialog-centered" role="document">
											<div class="modal-content">
												<div class="weight-500 col-md-12 pd-5">
													<div class="form-group">
														<div class="custom-file">
															<input name="image" id="file" type="file" class="custom-file-input" accept="image/*" onchange="validateImage('file')">
															<label class="custom-file-label" for="file" id="selector">Chọn file</label>		
														</div>
													</div>
												</div>
												<div class="modal-footer">
													<input type="submit" name="update_image" value="Update" class="btn btn-primary">
													<button type="button" class="btn btn-default" data-dismiss="modal">Đóng</button>
												</div>
											</div>
										</div>
									</div>
								</form>
							</div>
							<h5 class="text-center h5 mb-0"><?php echo $row['FirstName']. " " .$row['LastName']; ?></h5>
							<p class="text-center text-muted font-14"><?php echo $row['DepartmentName']; ?></p>
							<div class="profile-info">
								<h5 class="mb-20 h5 text-blue">Thông tin liên hệ</h5>
								<ul>
									<li>
										<span>Email:</span>
										<?php echo $row['EmailId']; ?>
									</li>
									<li>
										<span>Số điện thoại:</span>
										<?php echo $row['Phonenumber']; ?>
									</li>
									<li>
										<span>Phân quyền của tôi:</span>
										<? $roles = $row['role']; ?>
										<?php if($roles = 'HOD'): ?>
										 <?php echo "Head of Department"; ?>
										<?php endif ?>
									</li>
									<li>
										<span>Địa chỉ:</span>
										<?php echo $row['Address']; ?>
									</li>
								</ul>
							</div>
						</div>
					</div>
					<div class="col-xl-8 col-lg-8 col-md-8 col-sm-12 mb-30">
						<div class="card-box height-100-p overflow-hidden">
							<div class="profile-tab height-100-p">
								<div class="tab height-100-p">
									<ul class="nav nav-tabs customtab" role="tablist">
										<li class="nav-item">
											<a class="nav-link active" data-toggle="tab" href="#timeline" role="tab">Nghỉ phép</a>
										</li>
										<li class="nav-item">
											<a class="nav-link" data-toggle="tab" href="#setting" role="tab">Cài đặt</a>
										</li>
									</ul>
									<div class="tab-content">
										<!-- Timeline Tab start -->
										<div class="tab-pane fade show active" id="timeline" role="tabpanel">
											<div class="pd-20">
												<div class="profile-timeline">
													<?php $query= mysqli_query($conn,"SELECT * from tblleaves where empid = '$session_id'")or die(mysqli_error());
																while ($row = mysqli_fetch_array($query)) {
		                        								$id = $row['id'];
															?>
													<div class="timeline-month">
														<h5><?php echo date('d M Y', strtotime($row['PostingDate'])); ?></h5>
													</div>
													<div class="profile-timeline-list">
														<ul>
															
															<li>
																<div class="date"><?php echo $row['num_days']; ?> Ngày</div>
																<div class="task-name"><i class="ion-ios-chatboxes"></i><?php echo $row['LeaveType']; ?></div>
																<p><?php echo $row['Description']; ?></p>

																<div class="task-time">
																	<?php $stats=$row['Status'];
								                                       if($stats==1){
								                                        ?>
								                                           <span style="color: green">Xác nhận</span>
								                                            <?php } if($stats==2)  { ?>
								                                           <span style="color: red">Không xác nhận</span>
								                                            <?php } if($stats==0)  { ?>
									                                       <span style="color: blue">Đang xử lý</span>
									                                <?php } ?>
																</div>

															</li>
															
															
														</ul>
													</div>
												<?php }?>
												</div>
											</div>
										</div>
										<!-- Timeline Tab End -->
										<!-- Setting Tab start -->
										<div class="tab-pane fade height-100-p" id="setting" role="tabpanel">
											<div class="profile-setting">
												<form method="POST" enctype="multipart/form-data">
													<div class="profile-edit-list row">
														<div class="col-md-12"><h4 class="text-blue h5 mb-20">Chỉnh sửa cài đặt cá nhân của bạn</h4></div>

														<?php
														$query = mysqli_query($conn,"select * from tblemployees where emp_id = '$session_id' ")or die(mysqli_error());
														$row = mysqli_fetch_array($query);
														?>
														<div class="weight-500 col-md-6">
															<div class="form-group">
																<label>Họ</label>
																<input name="fname" class="form-control form-control-lg" type="text" required="true" autocomplete="off" value="<?php echo $row['FirstName']; ?>">
															</div>
														</div>
														<div class="weight-500 col-md-6">
															<div class="form-group">
																<label>Tên</label>
																<input name="lastname" class="form-control form-control-lg" type="text" placeholder="" required="true" autocomplete="off" value="<?php echo $row['LastName']; ?>">
															</div>
														</div>
														<div class="weight-500 col-md-6">
															<div class="form-group">
																<label>Email</label>
																<input name="email" class="form-control form-control-lg" type="text" placeholder="" required="true" autocomplete="off" value="<?php echo $row['EmailId']; ?>">
															</div>
														</div>
														<div class="weight-500 col-md-6">
															<div class="form-group">
																<label>Số điện thoại</label>
																<input name="phonenumber" class="form-control form-control-lg" type="text" placeholder="" required="true" autocomplete="off" value="<?php echo $row['Phonenumber']; ?>">
															</div>
														</div>
														<div class="weight-500 col-md-6">
															<div class="form-group">
																<label>Ngày sinh</label>
																<input name="dob" class="form-control form-control-lg date-picker" type="text" placeholder="" required="true" autocomplete="off" value="<?php echo $row['Dob']; ?>">
															</div>
														</div>
														<div class="weight-500 col-md-6">
															<div class="form-group">
																<label>Giới tính</label>
																<select name="gender" class="custom-select form-control" required="true" autocomplete="off">
																<option value="<?php echo $row['Gender']; ?>"><?php echo $row['Gender']; ?></option>
																	<option value="male">Nam</option>
																	<option value="female">Nữ</option>
																</select>
															</div>
														</div>
														<div class="weight-500 col-md-6">
															
															<div class="form-group">
																<label>Địa chỉ</label>
																<input name="address" class="form-control form-control-lg" type="text" placeholder="" required="true" autocomplete="off" value="<?php echo $row['Address']; ?>">
															</div>
														</div>
														<div class="weight-500 col-md-6">
															<div class="form-group">
																<label>Phòng ban</label>
																<select name="department" class="custom-select form-control" required="true" autocomplete="off">
																	<?php
																		$query_staff = mysqli_query($conn,"select * from tblemployees join  tbldepartments where emp_id = '$session_id'")or die(mysqli_error());
																		$row_staff = mysqli_fetch_array($query_staff);
																		
																	 ?>
																	<option value="<?php echo $row_staff['DepartmentShortName']; ?>"><?php echo $row_staff['DepartmentName']; ?></option>
																		<?php
																		$query = mysqli_query($conn,"select * from tbldepartments");
																		while($row = mysqli_fetch_array($query)){
																		
																		?>
																		<option value="<?php echo $row['DepartmentShortName']; ?>"><?php echo $row['DepartmentName']; ?></option>
																		<?php } ?>
																</select>
															</div>
														</div>
														<div class="weight-500 col-md-6">
															<?php
																$query = mysqli_query($conn,"select * from tblemployees where emp_id = '$session_id' ")or die(mysqli_error());
																$row = mysqli_fetch_array($query);
															?>
															<div class="form-group">
																<label>Ngày nghỉ phép khả dụng</label>
																<input class="form-control form-control-lg" type="text" required="true" autocomplete="off" readonly value="<?php echo $row['Av_leave']; ?>">
															</div>
														</div>
														<div class="weight-500 col-md-6">
															<div class="form-group">
																<label></label>
																<div class="modal-footer justify-content-center">
																	<button class="btn btn-primary" name="new_update" id="new_update" data-toggle="modal">Lưu & &nbsp;Cập nhật</button>
																</div>
															</div>
														</div>
													</div>
												</form>
											</div>
										</div>
										<!-- Setting Tab End -->
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
			<?php include('includes/footer.php'); ?>
		</div>
	</div>
	<!-- js -->
	<?php include('includes/scripts.php')?>

	<script type="text/javascript">
		var loader = function(e) {
			let file = e.target.files;

			let show = "<span>Selected file : </span>" + file[0].name;
			let output = document.getElementById("selector");
			output.innerHTML = show;
			output.classList.add("active");
		};

		let fileInput = document.getElementById("file");
		fileInput.addEventListener("change", loader);
	</script>
	<script type="text/javascript">
		 function validateImage(id) {
		    var formData = new FormData();
		    var file = document.getElementById(id).files[0];
		    formData.append("Filedata", file);
		    var t = file.type.split('/').pop().toLowerCase();
		    if (t != "jpeg" && t != "jpg" && t != "png") {
		        alert('Please select a valid image file');
		        document.getElementById(id).value = '';
		        return false;
		    }
		    if (file.size > 1050000) {
		        alert('Max Upload size is 1MB only');
		        document.getElementById(id).value = '';
		        return false;
		    }

		    return true;
		}
	</script>
</body>
</html>