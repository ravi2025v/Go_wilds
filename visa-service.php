<?php
$title = 'Gowilds';
include 'includes/header.php';
?>

      
        <!--====== Start Breadcrumb Section ======-->
        <section class="page-banner overlay pt-170 pb-170 bg_cover" style="background-image: url(assets/images/abt-bg.jpg);">
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-lg-10">
                        <div class="page-banner-content text-center text-white">
                            <h1 class="page-title text-white">Visa service</h1>
                            <ul class="breadcrumb-link text-white">
                                <li><a href="index.php">Home</a></li>
                                <li class="active">Visa service</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </section>
		<!--====== End Breadcrumb Section ======-->
        <section class="booking mt-5 mb-5">
		  <div class="container mt-5">
			<div class="row w-75 mx-auto">
				<div class="progress px-1 mb-3">
				  <div class="progress-bar" role="progressbar" style="width: 0%;"></div>
				</div>

				<div class="step-container d-flex justify-content-between mb-3">
				  <div class="step-circle active" onclick="displayStep(1)">1</div>
				  <div class="step-circle" onclick="displayStep(2)">2</div>
				  <div class="step-circle" onclick="displayStep(3)">3</div>
				</div>

				<form id="multi-step-form">
				  <!-- STEP 1 -->
				  <div class="step step-1">
					<h2 class="py-5 text-center">Travel Details </h2>
					  <div class="row mb-3 pt-5">
						<div class="col-md-6 mb-4">
						  <label class="form-label">Nationality</label>
							 <div class="bk-item booking-user">
								 <select class="wide border rounded-3 pe-5">                                 
									<option value="">Select Country</option>
									<option>India</option>
									<option>USA</option>
									<option>UK</option>
									<option>Australia</option>
								</select>
							 </div>
						</div>
						<div class="col-md-6 ">
						 <label class="form-label">Travelling To</label>
							 <div class="bk-item booking-user">
								 <select class="wide border rounded-3" required>
									<option value="">Select Destination</option>
									<option>France</option>
									<option>Germany</option>
									<option>Japan</option>
									<option>UAE</option>
								  </select>
							 </div>
						</div>
						<div class="col-md-6">
							<label class="form-label">Trip Start Date</label>
							<input type="date" class="form-control" required>
						</div>
						<div class="col-md-6">
							<label class="form-label">From which country are you travelling?</label>
							<div class="bk-item booking-user">
								<select class="wide border rounded-3" required>
									<option value="">Select Departure Country</option>
									<option>India</option>
									<option>USA</option>
									<option>Singapore</option>
									<option>UAE</option>
								</select>
							</div>
						</div>
					</div>
					<button type="button" class="btn b-green next-step mt-3">Next</button>
				  </div>

				  <!-- STEP 2 -->
				  <div class="step step-2">
					<h2 class="py-5 text-center">Profile Details</h2>
					<div class="row align-items-center">
						<div class="col-5">
							<h4>Profile Image </h4>
							<div class="cont">
							  <div class="img-area position-relative">
								<i class="fas fa-cloud-upload-alt icon fs-1"></i>
								<h6>Upload Image</h6>
							  </div>
							  <button type="button" class="btn select-image open-modal">Select Image</button>
						   </div>
						</div>
						<div class="col-7 ps-5">
							<h4>Travel Info </h4>
							<div class="cont ">
							 <p><span class="highlight">Nationality: </span> Indian </p>
							 <p><span class="highlight">Travelling To:  </span> France </p>
							 <p><span class="highlight">Trip Start Date:  </span> 02/10/2025 </p>
							 <p><span class="highlight"> Your Country:  </span> India </p>
						   </div>
						</div>
					</div>	
					<button type="button" class="btn b-green2 prev-step">Previous</button>
					<button type="button" class="btn b-green next-step">Next</button>
				  </div>

				  <!-- STEP 3 -->
				  <div class="step step-3">
					<h2 class="py-5 text-center">Passport Details</h4>
					<div class="row">
						<div class="col-12">
							<h4>Upload Passport Photo </h4>
							<div class="cont">
								<div class="img-area position-relative">
									<i class="fas fa-cloud-upload-alt icon fs-1"></i>
									<h6>Upload Image</h6>
								</div>
								<button type="button" class="btn select-image direct-upload">Passport photo</button>
							</div>
							<div class="passport-details">
								<form class="comment-form">
									 <div class="row">
										<div class="col-lg-6 mb-3">
											<div class="form-group">
												<label for="name" class="form-label">First Name</label>
												<input type="text" class="form-control" id="name" name="name" placeholder="" required>
											</div>
										</div>
										<div class="col-lg-6 mb-3">
											<div class="form-group">
												<label for="name" class="form-label">Last Name</label>
												<input type="text" class="form-control" id="lname" name="lname" placeholder="" required>
											</div>
										</div>
										<div class="col-md-6">
											<label class="form-label">Gender</label>
											<div class="bk-item booking-user">
												<select class="wide border rounded-3" required>
													<option value="">Select</option>
													<option>Male</option>
													<option>Female</option>
												</select>
											</div>
										</div>
										<div class="col-lg-6 mb-3">
											<div class="form-group">
												<label for="passport" class="form-label">Passport Number</label>
												<input type="text" class="form-control" placeholder="" required>
											</div>
										</div>
										<div class="col-md-6">
											<label class="form-label">Date Of Birth</label>
											<input type="date" class="form-control" required>
										</div>
										<div class="col-lg-6 mb-3">
											<div class="form-group">
												<label for="place" class="form-label">Place Of Issue</label>
												<input type="text" class="form-control" id="Place" name="Place" placeholder="" required>
											</div>
										</div>
										<div class="col-lg-6 mb-3">
											<div class="form-group">
												<label for="Issue" class="form-label">Passport Issue On</label>
												<input type="text" class="form-control" id="issue" name="issue" placeholder="" required>
											</div>
										</div>
										<div class="col-lg-6 mb-3">
											<div class="form-group">
												<label for="Valid" class="form-label">Passport Valid Till</label>
												<input type="text" class="form-control" id="valid" name="valid" placeholder="" required>
											</div>
										</div>
										<!-- Email -->
										<div class="col-lg-6 mb-3">
											<div class="form-group">
											   <label for="email" class="form-label">Email Address</label>
												<input type="email" class="form-control" id="email" name="email" placeholder="Enter your email" required>
											</div>
										</div>
										<div class="col-lg-6 mb-3">
											<div class="form-group">
												<label for="email" class="form-label">Enter Number</label>
												<input type="text" class="form-control" placeholder="Enter phone number" required>
											</div>
										</div>
									</div>
								</form>
							</div>
						</div>
					</div>
					
					<button type="button" class="btn b-green2 prev-step">Previous</button>
					<button type="submit" class="btn b-green">Submit</button>
				  </div>
				</form>
			</div>
		  </div>
		</section>
		
		<!-- Hidden File Input -->
		<input type="file" id="fileInput" accept="image/*" hidden>

		<!-- Modal (already in your code) -->
		<div class="modal fade" id="uploadModal" tabindex="-1" aria-labelledby="uploadModalLabel" aria-hidden="true">
		  <div class="modal-dialog modal-dialog-centered">
			<div class="modal-content">
			  <div class="modal-header">
				<h5 class="modal-title">Choose Image Option</h5>
				<button type="button" class="btn-close" data-bs-dismiss="modal"></button>
			  </div>
			  <div class="modal-body text-center">
				<button id="btnFile" class="btn btn-outline-primary w-100 mb-2">
				  <i class="fa fa-upload"></i> Upload from File
				</button>
				<button id="btnCamera" class="btn btn-outline-success w-100">
				  <i class="fa fa-camera"></i> Live Capture
				</button>

				<div id="cameraContainer" class="mt-3" style="display:none;">
				  <video id="video" width="100%" autoplay></video>
				  <button id="capture" class="btn btn-primary mt-2">Capture</button>
				  <canvas id="canvas" width="400" height="300" style="display:none;"></canvas>
				</div>
			  </div>
			</div>
		  </div>
		</div>
		
        <!--====== Start Places Section ======-->
		
		
		
        
        
		
		
        		
		

        
        
<?php include 'includes/footer.php'; ?>



