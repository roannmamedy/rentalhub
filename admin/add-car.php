<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0">
	<meta name="description" content="Dreamsrent - Bootstrap Admin Template">
	<meta name="keywords" content="admin, estimates, bootstrap, business, html5, responsive, Projects">
	<meta name="author" content="Dreams technologies - Bootstrap Admin Template">
	<meta name="robots" content="noindex, nofollow">
	<title>Dreamsrent - Admin Template</title>

	<!-- Favicon -->
	<link rel="shortcut icon" type="image/x-icon" href="assets/img/favicon.png">

	<!-- Apple Touch Icon -->
	<link rel="apple-touch-icon" sizes="180x180" href="assets/img/apple-touch-icon.png">

	<!-- Theme Settings Js -->
	<script src="assets/js/theme-script.js"></script>

	<!-- Bootstrap CSS -->
	<link rel="stylesheet" href="assets/css/bootstrap.min.css">

	<!-- Tabler Icon CSS -->
	<link rel="stylesheet" href="assets/plugins/tabler-icons/tabler-icons.min.css">

    <!-- Daterangepikcer CSS -->
	<link rel="stylesheet" href="assets/plugins/daterangepicker/daterangepicker.css">

	<!-- Datetimepicker CSS -->
	<link rel="stylesheet" href="assets/css/bootstrap-datetimepicker.min.css">

	<!-- Fontawesome CSS -->
	<link rel="stylesheet" href="assets/plugins/fontawesome/css/fontawesome.min.css">
	<link rel="stylesheet" href="assets/plugins/fontawesome/css/all.min.css">

	<!-- Bootstrap Tagsinput CSS -->
    <link rel="stylesheet" href="assets/plugins/bootstrap-tagsinput/bootstrap-tagsinput.css">

	<!-- Fancybox CSS -->
	<link rel="stylesheet" href="assets/plugins/fancybox/jquery.fancybox.min.css">

	<!-- Select2 CSS -->
	<link rel="stylesheet" href="assets/plugins/select2/css/select2.min.css">

	<!-- Main CSS -->
	<link rel="stylesheet" href="assets/css/style.css">

</head>

<body>

	<!-- Main Wrapper -->
	<div class="main-wrapper">

		<!-- Header -->
		<div class="header">
			<div class="main-header">
			
				<div class="header-left">
					<a href="index.html" class="logo">
						<img src="assets/img/logo.svg" alt="Logo">
					</a>
					<a href="index.html" class="dark-logo">
						<img src="assets/img/logo-white.svg" alt="Logo">
					</a>
				</div>

				<a id="mobile_btn" class="mobile_btn" href="#sidebar">
					<span class="bar-icon">
						<span></span>
						<span></span>
						<span></span>
					</span>
				</a>

				<div class="header-user">
					<div class="nav user-menu nav-list">
	
						<div class="me-auto d-flex align-items-center" id="header-search">					
							<a id="toggle_btn" href="javascript:void(0);">
								<i class="ti ti-menu-deep"></i>
							</a>		
							<div class="add-dropdown">
								<a href="add-reservation.html" class="btn btn-dark d-inline-flex align-items-center">
									<i class="ti ti-plus me-1"></i>New Reservation
								</a>
							</div>			
						</div>
	
						<div class="d-flex align-items-center header-icons">	

							<!-- Flag -->
							<div class="nav-item dropdown has-arrow flag-nav nav-item-box">
								<a class="nav-link dropdown-toggle" data-bs-toggle="dropdown" href="javascript:void(0);"
									role="button">
									<img src="assets/img/flags/gb.svg" alt="Language" class="img-fluid">
								</a>
								<ul class="dropdown-menu p-2">
									<li>
										<a href="javascript:void(0);" class="dropdown-item">
											<img src="assets/img/flags/gb.svg" alt="" height="16">English
										</a>
									</li>
									<li>
										<a href="javascript:void(0);" class="dropdown-item">
											<img src="assets/img/flags/sa.svg" alt="" height="16">Arabic
										</a>
									</li>
									<li>
										<a href="javascript:void(0);" class="dropdown-item">
											<img src="assets/img/flags/de.svg" alt="" height="16">German
										</a>
									</li>
								</ul>
							</div>
							<!-- /Flag -->

							<div class="theme-item">
								<a href="javascript:void(0);" id="dark-mode-toggle" class="theme-toggle btn btn-menubar">
									<i class="ti ti-moon"></i>
								</a>
								<a href="javascript:void(0);" id="light-mode-toggle" class="theme-toggle btn btn-menubar">
									<i class="ti ti-sun-high"></i>
								</a>
							</div>
							
							<div class="notification_item">
								<a href="javascript:void(0);" class="btn btn-menubar position-relative" id="notification_popup" data-bs-toggle="dropdown" data-bs-auto-close="outside">
									<i class="ti ti-bell"></i>
									<span class="badge bg-violet rounded-pill"></span>
								</a>
								<div class="dropdown-menu dropdown-menu-end notification-dropdown">
									<div class="topnav-dropdown-header pb-0">
										<h5 class="notification-title">Notifications</h5>
										<ul class="nav nav-tabs nav-tabs-bottom">
											<li class="nav-item"><a class="nav-link active" href="#active-notification" data-bs-toggle="tab">Active<span class="badge badge-xs rounded-pill bg-danger ms-2">5</span></a></li>
											<li class="nav-item"><a class="nav-link" href="#unread-notification" data-bs-toggle="tab">Unread</a></li>
											<li class="nav-item"><a class="nav-link" href="#archieve-notification" data-bs-toggle="tab">Archieve</a></li>
										</ul>
									</div>
									<div class="noti-content">
										<div class="tab-content">
											<div class="tab-pane fade show active" id="active-notification">
												<div class="notification-list">
													<div class="d-flex align-items-center">
														<a href="javascript:void(0);" class="avatar avatar-lg offline me-2 flex-shrink-0">
															<img src="assets/img/profiles/avatar-02.jpg" alt="Profile" class="rounded-circle">
														</a>
														<div class="flex-grow-1">
															<p class="mb-1"><a href="javascript:void(0);"><span class="text-gray-9">Jerry Manas</span> Added New Task Creating <span class="text-gray-9">Login Pages</span></a></p>
															<span class="fs-12 noti-time"><i class="ti ti-clock me-1"></i>4 Min Ago</span>
														</div>
													</div>
												</div>
												<div class="notification-list">
													<div class="d-flex align-items-center">
														<a href="javascript:void(0);" class="avatar avatar-lg offline me-2 flex-shrink-0">
															<img src="assets/img/profiles/avatar-05.jpg" alt="Profile" class="rounded-circle">
														</a>
														<div class="flex-grow-1">
															<p class="mb-1"><a href="javascript:void(0);"><span class="text-gray-9">Robert Fox </span> Was Marked as Late Login <span class="text-danger">09:55 AM</span></a></p>
															<span class="fs-12 noti-time"><i class="ti ti-clock me-1"></i>5 Min Ago</span>
														</div>
													</div>
												</div>
												<div class="notification-list">
													<div class="d-flex align-items-center">
														<a href="javascript:void(0);" class="avatar avatar-lg me-2 flex-shrink-0">
															<img src="assets/img/profiles/avatar-04.jpg" alt="Profile" class="rounded-circle">
														</a>
														<div class="flex-grow-1">
															<p class="mb-1"><a href="javascript:void(0);"><span class="text-gray-9">Jenny Wilson </span> Completed <span class="text-gray-9">Created New Component</span></a></p>
															<div class="d-flex align-items-center">
																<span class="fs-12 noti-time"><i class="ti ti-clock me-1"></i>15 Min Ago</span>
															</div>
														</div>
													</div>
												</div>
												<div class="notification-list">
													<div class="d-flex align-items-center">
														<a href="javascript:void(0);" class="avatar avatar-lg me-2 flex-shrink-0">
															<img src="assets/img/profiles/avatar-02.jpg" alt="Profile" class="rounded-circle">
														</a>
														<div class="flex-grow-1">
															<p class="mb-1"><a href="javascript:void(0);"><span class="text-gray-9">Jacob Johnson </span> Added Manual Time <span class="text-gray-9">2 Hrs</span></a></p>
															<div class="d-flex align-items-center">
																<span class="fs-12 noti-time"><i class="ti ti-clock me-1"></i>20 Min Ago</span>
															</div>
														</div>
													</div>
												</div>
												<div class="notification-list">
													<div class="d-flex align-items-center">
														<a href="javascript:void(0);" class="avatar avatar-lg me-2 flex-shrink-0">
															<img src="assets/img/profiles/avatar-01.jpg" alt="Profile" class="rounded-circle">
														</a>
														<div class="flex-grow-1">
															<p class="mb-1"><a href="javascript:void(0);"><span class="text-gray-9">Annete Black </span> Completed <span class="text-gray-9">Improved Workflow React</span></a></p>
															<div class="d-flex align-items-center">
																<span class="fs-12 noti-time"><i class="ti ti-clock me-1"></i>22 Min Ago</span>
															</div>
														</div>
													</div>
												</div>
											</div>
											<div class="tab-pane fade" id="unread-notification">
												<div class="notification-list">
													<a href="javascript:void(0);">
														<div class="d-flex align-items-center">
															<span class="avatar avatar-lg offline me-2 flex-shrink-0">
																<img src="assets/img/profiles/avatar-02.jpg" alt="Profile" class="rounded-circle">
															</span>
															<div class="flex-grow-1">
																<p class="mb-1"><span class="text-gray-9">Jerry Manas</span> Added New Task Creating <span class="text-gray-9">Login Pages</span></p>
																<span class="fs-12 noti-time"><i class="ti ti-clock me-1"></i>4 Min Ago</span>
															</div>
														</div>
													</a>
												</div>
												<div class="notification-list">
													<a href="javascript:void(0);">
														<div class="d-flex align-items-center">
															<span class="avatar avatar-lg offline me-2 flex-shrink-0">
																<img src="assets/img/profiles/avatar-05.jpg" alt="Profile" class="rounded-circle">
															</span>
															<div class="flex-grow-1">
																<p class="mb-1"><span class="text-gray-9">Robert Fox </span> Was Marked as Late Login <span class="text-danger">09:55 AM</span></p>
																<span class="fs-12 noti-time"><i class="ti ti-clock me-1"></i>5 Min Ago</span>
															</div>
														</div>
													</a>
												</div>
												<div class="notification-list">
													<a href="javascript:void(0);">
														<div class="d-flex align-items-center">
															<span class="avatar avatar-lg offline me-2 flex-shrink-0">
																<img src="assets/img/profiles/avatar-06.jpg" alt="Profile" class="rounded-circle">
															</span>
															<div class="flex-grow-1">
																<p class="mb-1"><span class="text-gray-9">Robert Fox </span> Created New Component</p>
																<span class="fs-12 noti-time"><i class="ti ti-clock me-1"></i>5 Min Ago</span>
															</div>
														</div>
													</a>
												</div>
											</div>
											<div class="tab-pane fade" id="archieve-notification">
												<div class="d-flex justify-content-center align-items-center p-3">
													<div class="text-center ">
														<img src="assets/img/icons/nodata.svg" class="mb-2" alt="nodata">
														<p class="text-gray-5">No Data Available</p>
													</div>
												</div>
											</div>
										</div>
									</div>
									<div class="d-flex align-items-center justify-content-between topnav-dropdown-footer">
										<div class="d-flex align-items-center">
											<a href="javascript:void(0);" class="link-primary text-decoration-underline me-3">Mark all as Read</a>
											<a href="javascript:void(0);" class="link-danger text-decoration-underline">Clear All</a>
										</div>
										<a href="javascript:void(0);" class="btn btn-primary btn-sm d-inline-flex align-items-center">View All Notifications<i class="ti ti-chevron-right ms-1"></i></a>
									</div>
								</div>
							</div>
							<div>
								<a href="income-report.html" class="btn btn-menubar">
									<i class="ti ti-chart-bar"></i>
								</a>
							</div>
							<div class="dropdown">
								<a href="javascript:void(0);" class="btn btn-menubar" data-bs-toggle="dropdown"  data-bs-auto-close="outside">
									<i class="ti ti-grid-dots"></i>
								</a>
								<div class="dropdown-menu p-3">
									<ul>
										<li>
											<a href="add-car.html" class="dropdown-item d-inline-flex align-items-center">
												<i class="ti ti-car me-2"></i>Car
											</a>
										</li>
										<li>
											<a href="add-quotations.html" class="dropdown-item d-inline-flex align-items-center">
												<i class="ti ti-file-symlink me-2"></i>Quotation
											</a>
										</li>
										<li>
											<a href="pricing.html" class="dropdown-item d-inline-flex align-items-center">
												<i class="ti ti-file-dollar me-2"></i>Seasonal Pricing
											</a>
										</li>
										<li>
											<a href="extra-services.html" class="dropdown-item d-inline-flex align-items-center">
												<i class="ti ti-script-plus me-2"></i>Extra Service
											</a>
										</li>
										<li>
											<a href="inspections.html" class="dropdown-item d-inline-flex align-items-center">
												<i class="ti ti-dice-6 me-2"></i>Inspection
											</a>
										</li>
										<li>
											<a href="maintenance.html" class="dropdown-item d-inline-flex align-items-center">
												<i class="ti ti-color-filter me-2"></i>Maintenance
											</a>
										</li>
									</ul>
								</div>
							</div>
							<div class="dropdown profile-dropdown">
								<a href="javascript:void(0);" class="d-flex align-items-center" data-bs-toggle="dropdown"  data-bs-auto-close="outside">
									<span class="avatar avatar-sm">
										<img src="assets/img/profiles/avatar-05.jpg" alt="Img" class="img-fluid rounded-circle">
									</span>
								</a>
								<div class="dropdown-menu">
									<div class="profileset d-flex align-items-center">
										<span class="user-img me-2">
											<img src="assets/img/profiles/avatar-05.jpg" alt="">
										</span>
										<div>
											<h6 class="fw-semibold mb-1">Andrew Simmonds</h6>
											<p class="fs-13">andrew@example.com</p>
										</div>
									</div>
									<a class="dropdown-item d-flex align-items-center" href="profile-setting.html">
										<i class="ti ti-user-edit me-2"></i>Edit Profile
									</a>
									<a class="dropdown-item d-flex align-items-center" href="payments.html">
										<i class="ti ti-credit-card me-2"></i>Payments
									</a>
									<div class="dropdown-divider my-2"></div>
									<div class="dropdown-item">
										<div class="form-check form-switch  form-check-reverse  d-flex align-items-center justify-content-between">
											<label class="form-check-label" for="notify">
												<i class="ti ti-bell me-2"></i>Notificaions</label>
											<input class="form-check-input" type="checkbox" role="switch" id="notify" checked>
										</div>
									</div>
									<a class="dropdown-item d-flex align-items-center" href="security-setting.html">
										<i class="ti ti-exchange me-2"></i>Change Password
									</a>
									<a class="dropdown-item d-flex align-items-center" href="profile-setting.html">
										<i class="ti ti-settings me-2"></i>Settings
									</a>
									<div class="dropdown-divider my-2"></div>
									<a class="dropdown-item logout d-flex align-items-center justify-content-between" href="login.html">
										<span><i class="ti ti-logout me-2"></i>Logout Account</span> <i class="ti ti-chevron-right"></i>
									</a>
								</div>
							</div>
						</div>
					</div>
				</div>

				<!-- Mobile Menu -->
				<div class="dropdown mobile-user-menu">
					<a href="javascript:void(0);" class="nav-link dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
						<i class="fa fa-ellipsis-v"></i>
					</a>
					<div class="dropdown-menu dropdown-menu-end">
						<a class="dropdown-item" href="profile.html">My Profile</a>
						<a class="dropdown-item" href="profile-setting.html">Settings</a>
						<a class="dropdown-item" href="login.html">Logout</a>
					</div>
				</div>
				<!-- /Mobile Menu -->

			</div>

		</div>
		<!-- /Header -->

		<!-- Sidebar -->
		<div class="sidebar" id="sidebar">
			<!-- Logo -->
			<div class="sidebar-logo">
				<a href="index.html" class="logo logo-normal">
					<img src="assets/img/logo.svg" alt="Logo">
				</a>
				<a href="index.html" class="logo-small">
					<img src="assets/img/logo-small.svg" alt="Logo">
				</a>
				<a href="index.html" class="dark-logo">
					<img src="assets/img/logo-white.svg" alt="Logo">
				</a>
			</div>
			<!-- /Logo -->
			<div class="sidebar-inner slimscroll">
				<div id="sidebar-menu" class="sidebar-menu">
					
					<div class="form-group">
						<!-- Search -->
						<div class="input-group input-group-flat d-inline-flex">
							<span class="input-icon-addon">
								<i class="ti ti-search"></i>
							  </span>
							<input type="text" class="form-control" placeholder="Search">
							<span class="group-text">
								<i class="ti ti-command"></i>
							</span>
						</div>
						<!-- /Search -->
					</div>
					<ul>
						<li class="menu-title"><span>Main</span></li>
						<li>
							<ul>
								<li>
									<a href="index.html">
										<i class="ti ti-layout-dashboard"></i><span>Dashboard</span>
									</a>
								</li>
							</ul>
						</li>
						<li class="menu-title"><span>Bookings</span></li>
						<li>
							<ul>
								<li>
									<a href="reservations.html">
										<i class="ti ti-files"></i><span>Reservations</span><span class="track-icon"></span>
									</a>
								</li>
								<li>
									<a href="calendar.html">
										<i class="ti ti-calendar-bolt"></i><span>Calendar</span>
									</a>
								</li>
								<li>
									<a href="quotations.html">
										<i class="ti ti-file-symlink"></i><span>Quotations</span>
									</a>
								</li>
								<li>
									<a href="enquiries.html">
										<i class="ti ti-mail"></i><span>Enquiries</span>
									</a>
								</li>
							</ul>							
						</li>
						<li class="menu-title"><span>Manage</span></li>
						<li>
							<ul>
								<li>
									<a href="customers.html">
										<i class="ti ti-users-group"></i><span>Customers</span>
									</a>
								</li>
								<li>
									<a href="drivers.html">
										<i class="ti ti-user-bolt"></i><span>Drivers</span>
									</a>
								</li>
								<li>
									<a href="locations.html">
										<i class="ti ti-map-pin"></i><span>Locations</span>
									</a>
								</li>
							</ul>
						</li>
						<li class="menu-title"><span>RENTALS</span></li>
						<li>
							<ul>
								<li class="active">
									<a href="cars.php">
										<i class="ti ti-car"></i><span>Cars</span>
									</a>
								</li>
								<li class="submenu">
									<a href="javascript:void(0);">
										<i class="ti ti-device-camera-phone"></i><span>Car Attributes</span>
										<span class="menu-arrow"></span>
									</a>
									<ul>
										<li><a href="brands.html">Brands</a></li>
										<li><a href="types.html">Types</a></li>
										<li><a href="models.html">Models</a></li>
										<li><a href="transmissions.html">Transmissions</a></li>
										<li><a href="fuel.html">Fuels</a></li>
										<li><a href="color.html">Colors</a></li>
										<li><a href="steering.html">Steering</a></li>
										<li><a href="seats.html">Seats</a></li>
										<li><a href="cylinders.html">Cylinders</a></li>
										<li><a href="doors.html">Doors</a></li>
										<li><a href="features.html">Features</a></li>
										<li><a href="safety-features.html">Safty Features</a></li>
									</ul>
								</li>
								<li>
									<a href="extra-services.html">
										<i class="ti ti-script-plus"></i><span>Extra Service</span>
									</a>
								</li>
								<li>
									<a href="pricing.html">
										<i class="ti ti-file-dollar"></i><span>Seasonal Pricing</span>
									</a>
								</li>
								<li>
									<a href="inspections.html">
										<i class="ti ti-dice-6"></i><span>Inspections</span>
									</a>
								</li>
								<li>
									<a href="tracking.html">
										<i class="ti ti-map-pin-pin"></i><span>Tracking</span>
									</a>
								</li>
								<li>
									<a href="maintenance.html">
										<i class="ti ti-color-filter"></i><span>Maintenance</span>
									</a>
								</li>
								<!-- Reviews menu item commented out per requirements
								<li>
									<a href="reviews.html">
										<i class="ti ti-star"></i><span>Reviews</span>
									</a>
								</li>
								-->
							</ul>
						</li>	
						<li class="menu-title"><span>FINANCE & ACCOUNTS</span></li>					
						<li>
							<ul>
								<li>
									<a href="invoices.html">
										<i class="ti ti-file-invoice"></i><span>Invoices</span>
									</a>
								</li>
								<li>
									<a href="payments.html">
										<i class="ti ti-credit-card"></i><span>Payments</span>
									</a>
								</li>
							</ul>
						</li>
						<li class="menu-title"><span>OTHERS</span></li>
						<li>
							<ul>
								<li>
									<a href="chat.html">
										<i class="ti ti-message"></i><span>Messages</span><span class="count">5</span>
									</a>
								</li>
								<li>
									<a href="coupons.html">
										<i class="ti ti-discount-2"></i><span>Coupons</span>
									</a>
								</li>
								<li>
									<a href="newsletters.html">
										<i class="ti ti-file-horizontal"></i><span>Newsletters</span>
									</a>
								</li>
							</ul>
						</li>
						<li class="menu-title"><span>CMS</span></li>
						<li>
							<ul>
								<li>
									<a href="pages.html" >
										<i class="ti ti-file-invoice"></i><span>Pages</span>
									</a>
								</li>
								<li>
									<a href="menu-management.html" >
										<i class="ti ti-menu-2"></i><span>Menu Management</span>
									</a>
								</li>
								<li class="submenu">
									<a href="javascript:void(0);">
										<i class="ti ti-device-desktop-analytics"></i><span>Blogs</span>
										<span class="menu-arrow"></span>
									</a>
									<ul>
										<li><a href="blogs.html">All Blogs</a></li>
										<li><a href="blog-categories.html">Categories</a></li>
										<li><a href="blog-comments.html">Comments</a></li>
										<li><a href="blog-tags.html">Blog Tags</a></li>
									</ul>
								</li>
								<li class="submenu">
									<a href="javascript:void(0);">
										<i class="ti ti-map"></i><span>Locations</span>
										<span class="menu-arrow"></span>
									</a>
									<ul>
										<li><a href="countries.html">Countries</a></li>
										<li><a href="state.html">States</a></li>
										<li><a href="city.html">Cities</a></li>
									</ul>
								</li>
								<li>
									<a href="testimonials.html">
										<i class="ti ti-brand-hipchat"></i><span>Testimonials</span>
									</a>
								</li>
								<li class="submenu">
									<a href="javascript:void(0);">
										<i class="ti ti-question-mark"></i><span>FAQ's</span>
										<span class="menu-arrow"></span>
									</a>
									<ul>
										<li><a href="faq.html">FAQ's</a></li>
										<li><a href="faq-category.html">FAQ Category</a></li>
									</ul>
								</li>
							</ul>
						</li>
						<li class="menu-title"><span>SUPPORT</span></li>
						<li>
							<ul>
								<li>
									<a href="contact-messages.html" >
										<i class="ti ti-messages"></i><span>Contact Messages</span>
									</a>
								</li>
								<li>
									<a href="announcements.html">
										<i class="ti ti-speakerphone"></i><span>Announcements</span>
									</a>
								</li>
								<li>
									<a href="tickets.html">
										<i class="ti ti-ticket"></i><span>Tickets</span>
									</a>
								</li>
							</ul>
						</li>
						<li class="menu-title"><span>USER MANAGEMENT</span></li>
						<li>
							<ul>
								<li>
									<a href="users.html" >
										<i class="ti ti-user-circle"></i><span>Users</span>
									</a>
								</li>
								<li>
									<a href="roles-permissions.html">
										<i class="ti ti-user-shield"></i><span>Roles & Permissions</span>
									</a>
								</li>
							</ul>
						</li>
						<li class="menu-title"><span>REPORTS</span></li>
						<li>
							<ul>
								<li>
									<a href="income-report.html" >
										<i class="ti ti-chart-histogram"></i><span>Income vs Expense</span>
									</a>
								</li>
								<li>
									<a href="earnings-report.html">
										<i class="ti ti-chart-line"></i><span>Earnings</span>
									</a>
								</li>
								<li>
									<a href="rental-report.html">
										<i class="ti ti-chart-infographic"></i><span>Rentals</span>
									</a>
								</li>
							</ul>
						</li>
						<li class="menu-title"><span>AUTHENTICATION</span></li>
						<li>
							<ul>
								<li>
									<a href="login.html" >
										<i class="ti ti-login"></i><span>Login</span>
									</a>
								</li>
								<li>
									<a href="forgot-password.html">
										<i class="ti ti-help-triangle"></i><span>Forgot Password</span>
									</a>
								</li>
								<li>
									<a href="otp.html">
										<i class="ti ti-mail-exclamation"></i><span>Email Verification</span>
									</a>
								</li>
								<li>
									<a href="reset-password.html">
										<i class="ti ti-restore"></i><span>Reset Password</span>
									</a>
								</li>
							</ul>
						</li>
						<li class="menu-title"><span>SETTINGS & CONFIGURATION</span></li>
						<li>
							<ul>
								<li class="submenu">
									<a href="javascript:void(0);">
										<i class="ti ti-user-cog"></i><span>Account Settings</span>
										<span class="menu-arrow"></span>
									</a>
									<ul>
										<li>
											<a href="profile-setting.html">Profile</a>
										</li>
										<li>
											<a href="security-setting.html">Security</a>
										</li>
										<li>
											<a href="notifications-setting.html">Notifications</a>
										</li>
										<li>
											<a href="integrations-settings.html">Integrations</a>
										</li>
										<li>
											<a href="tracker-setting.html">Tracker</a>
										</li>
									</ul>
								</li>
								<li class="submenu">
									<a href="javascript:void(0);">
										<i class="ti ti-world-cog"></i><span>Website Settings</span>
										<span class="menu-arrow"></span>
									</a>
									<ul>
										<li>
											<a href="company-setting.html">Company Settings</a>
										</li>
										<li>
											<a href="localization-setting.html">Localization</a>
										</li>
										<li>
											<a href="prefixes.html">Prefixes</a>
										</li>
										<li>
											<a href="seo-setup.html">SEO Setup</a>
										</li>
										<li>
											<a href="language-setting.html">Language</a>
										</li>
										<li>
											<a href="maintenance-mode.html">Maintenance Mode</a>
										</li>
										<li>
											<a href="login-setting.html">Login & Register</a>
										</li>
										<li>
											<a href="ai-configuration.html">AI Configuration</a>
										</li>
										<li>
											<a href="plugin-managers.html">Plugin Managers</a>
										</li>
									</ul>
								</li>
								<li class="submenu">
									<a href="javascript:void(0);">
										<i class="ti ti-clock-cog"></i><span>Rental Settings</span>
										<span class="menu-arrow"></span>
									</a>
									<ul>
										<li>
											<a href="rental-setting.html">Rental</a>
										</li>
										<li>
											<a href="insurance-setting.html">Insurance</a>
										</li>
									</ul>
								</li>
								<li class="submenu">
									<a href="javascript:void(0);">
										<i class="ti ti-device-mobile-cog"></i><span>App Settings</span>
										<span class="menu-arrow"></span>
									</a>
									<ul>
										<li>
											<a href="invoice-setting.html">Invoice Settings</a>
										</li>
										<li>
											<a href="invoice-template.html">Invoice Templates</a>
										</li>
										<li>
											<a href="signatures-setting.html">Signatures</a>
										</li>
										<li>
											<a href="custom-fields.html">Custom Fields</a>
										</li>
									</ul>
								</li>
								<li class="submenu">
									<a href="javascript:void(0);">
										<i class="ti ti-device-desktop-cog"></i><span>System Settings</span>
										<span class="menu-arrow"></span>
									</a>
									<ul>
										<li>
											<a href="email-setting.html">Email Settings</a>
										</li>
										<li>
											<a href="email-templates.html">Email Templates</a>
										</li>
										<li>
											<a href="sms-gateways.html">SMS Gateways</a>
										</li>
										<li>
											<a href="gdpr-cookies.html">GDPR Cookies</a>
										</li>
									</ul>
								</li>
								<li class="submenu">
									<a href="javascript:void(0);">
										<i class="ti ti-settings-dollar"></i><span>Finance Settings</span>
										<span class="menu-arrow"></span>
									</a>
									<ul>
										<li>
											<a href="payment-methods.html">Payment Methods</a>
										</li>
										<li>
											<a href="bank-accounts.html">Bank Accounts</a>
										</li>
										<li>
											<a href="tax-rates.html">Tax Rates</a>
										</li>
										<li>
											<a href="currencies.html">Currencies</a>
										</li>
									</ul>
								</li>
								<li class="submenu">
									<a href="javascript:void(0);">
										<i class="ti ti-settings-2"></i><span>Other Settings</span>
										<span class="menu-arrow"></span>
									</a>
									<ul>
										<li>
											<a href="sitemap.html">Sitemap</a>
										</li>
										<li>
											<a href="clear-cache.html">Clear Cache</a>
										</li>
										<li>
											<a href="storage.html">Storage</a>
										</li>
										<li>
											<a href="cronjob.html">Cronjob</a>
										</li>
										<li>
											<a href="system-backup.html">System Backup</a>
										</li>
										<li>
											<a href="database-backup.html">Database Backup</a>
										</li>
										<li>
											<a href="system-update.html">System Update</a>
										</li>
									</ul>
								</li>
							</ul>
						</li>
						<li class="menu-title"><span>UI Interface</span></li>
						<li>
							<ul>
								<li class="submenu">
									<a href="javascript:void(0);">
										<i class="ti ti-hierarchy"></i><span>Base UI</span><span class="menu-arrow"></span>
									</a>
									<ul>
										<li><a href="ui-alerts.html">Alerts</a></li>
										<li><a href="ui-accordion.html">Accordion</a></li>
										<li><a href="ui-avatar.html">Avatar</a></li>
										<li><a href="ui-badges.html">Badges</a></li>
										<li><a href="ui-borders.html">Border</a></li>
										<li><a href="ui-buttons.html">Buttons</a></li>
										<li><a href="ui-buttons-group.html">Button Group</a></li>
										<li><a href="ui-breadcrumb.html">Breadcrumb</a></li>
										<li><a href="ui-cards.html">Card</a></li>
										<li><a href="ui-carousel.html">Carousel</a></li>
										<li><a href="ui-colors.html">Colors</a></li>
										<li><a href="ui-dropdowns.html">Dropdowns</a></li>
										<li><a href="ui-grid.html">Grid</a></li>
										<li><a href="ui-images.html">Images</a></li>
										<li><a href="ui-lightbox.html">Lightbox</a></li>
										<li><a href="ui-media.html">Media</a></li>
										<li><a href="ui-modals.html">Modals</a></li>
										<li><a href="ui-offcanvas.html">Offcanvas</a></li>
										<li><a href="ui-pagination.html">Pagination</a></li>
										<li><a href="ui-popovers.html">Popovers</a></li>
										<li><a href="ui-progress.html">Progress</a></li>
										<li><a href="ui-placeholders.html">Placeholders</a></li>
										<li><a href="ui-spinner.html">Spinner</a></li>
										<li><a href="ui-sweetalerts.html">Sweet Alerts</a></li>
										<li><a href="ui-nav-tabs.html">Tabs</a></li>
										<li><a href="ui-toasts.html">Toasts</a></li>
										<li><a href="ui-tooltips.html">Tooltips</a></li>
										<li><a href="ui-typography.html">Typography</a></li>
										<li><a href="ui-video.html">Video</a></li>
										<li><a href="ui-sortable.html">Sortable</a></li>
										<li><a href="ui-swiperjs.html">Swiperjs</a></li>
									</ul>
								</li>
								<li class="submenu">
									<a href="javascript:void(0);">
										<i class="ti ti-whirl"></i><span>Advanced UI</span><span class="menu-arrow"></span>
									</a>
									<ul>
										<li><a href="ui-ribbon.html">Ribbon</a></li>
										<li><a href="ui-clipboard.html">Clipboard</a></li>
										<li><a href="ui-drag-drop.html">Drag & Drop</a></li>
										<li><a href="ui-rangeslider.html">Range Slider</a></li>
										<li><a href="ui-rating.html">Rating</a></li>
										<li><a href="ui-text-editor.html">Text Editor</a></li>
										<li><a href="ui-counter.html">Counter</a></li>
										<li><a href="ui-scrollbar.html">Scrollbar</a></li>
										<li><a href="ui-stickynote.html">Sticky Note</a></li>
										<li><a href="ui-timeline.html">Timeline</a></li>
									</ul>
								</li>
								<li class="submenu">
									<a href="javascript:void(0);">
										<i class="ti ti-forms"></i><span>Forms</span><span class="menu-arrow"></span>
									</a>
									<ul>
										<li class="submenu submenu-two">
											<a href="javascript:void(0);">Form Elements<span class="menu-arrow inside-submenu"></span></a>
											<ul>
												<li><a href="form-basic-inputs.html">Basic Inputs</a></li>
												<li><a href="form-checkbox-radios.html">Checkbox & Radios</a></li>
												<li><a href="form-input-groups.html">Input Groups</a></li>
												<li><a href="form-grid-gutters.html">Grid & Gutters</a></li>
												<li><a href="form-select.html">Form Select</a></li>
												<li><a href="form-mask.html">Input Masks</a></li>
												<li><a href="form-fileupload.html">File Uploads</a></li>
											</ul>
										</li>
										<li class="submenu submenu-two">
											<a href="javascript:void(0);">Layouts<span class="menu-arrow inside-submenu"></span></a>
											<ul>
												<li><a href="form-horizontal.html">Horizontal Form</a></li>
												<li><a href="form-vertical.html">Vertical Form</a></li>
												<li><a href="form-floating-labels.html">Floating Labels</a></li>
											</ul>
										</li>
										<li><a href="form-validation.html">Form Validation</a></li>
										<li><a href="form-select2.html">Select2</a></li>
										<li><a href="form-wizard.html">Form Wizard</a></li>
										<li><a href="form-pickers.html">Form Picker</a></li>
									</ul>
								</li>
								<li class="submenu">
									<a href="javascript:void(0);">
										<i class="ti ti-table"></i><span>Tables</span><span class="menu-arrow"></span>
									</a>
									<ul>
										<li><a href="tables-basic.html">Basic Tables </a></li>
										<li><a href="data-tables.html">Data Table </a></li>
									</ul>
								</li>
								<li class="submenu">
									<a href="javascript:void(0);">
										<i class="ti ti-chart-pie-3"></i>
										<span>Charts</span><span class="menu-arrow"></span>
									</a>
									<ul>
										<li><a href="chart-apex.html">Apex Charts</a></li>
										<li><a href="chart-c3.html">Chart C3</a></li>
										<li><a href="chart-js.html">Chart Js</a></li>
										<li><a href="chart-morris.html">Morris Charts</a></li>
										<li><a href="chart-flot.html">Flot Charts</a></li>
										<li><a href="chart-peity.html">Peity Charts</a></li>
									</ul>
								</li>
								<li class="submenu">
									<a href="javascript:void(0);">
										<i class="ti ti-icons"></i>
										<span>Icons</span><span class="menu-arrow"></span>
									</a>
									<ul>
										<li><a href="icon-fontawesome.html">Fontawesome Icons</a></li>
										<li><a href="icon-tabler.html">Tabler Icons</a></li>
										<li><a href="icon-bootstrap.html">Bootstrap Icons</a></li>
										<li><a href="icon-remix.html">Remix Icons</a></li>
										<li><a href="icon-feather.html">Feather Icons</a></li>
										<li><a href="icon-ionic.html">Ionic Icons</a></li>
										<li><a href="icon-material.html">Material Icons</a></li>
										<li><a href="icon-pe7.html">Pe7 Icons</a></li>
										<li><a href="icon-simpleline.html">Simpleline Icons</a></li>
										<li><a href="icon-themify.html">Themify Icons</a></li>
										<li><a href="icon-weather.html">Weather Icons</a></li>
										<li><a href="icon-typicon.html">Typicon Icons</a></li>
										<li><a href="icon-flag.html">Flag Icons</a></li>
									</ul>
								</li>
								<li class="submenu">
									<a href="javascript:void(0);">
										<i class="ti ti-map-2"></i>
										<span>Maps</span>
										<span class="menu-arrow"></span>
									</a>
									<ul>
										<li>
											<a href="maps-vector.html">Vector</a>
										</li>
										<li>
											<a href="maps-leaflet.html">Leaflet</a>
										</li>
									</ul>
								</li>
							</ul>
						</li>
						<li class="menu-title"><span>Extras</span></li>
						<li>
							<ul>
								<li>
									<a href="javascript:void(0);"><i class="ti ti-file-shredder"></i><span>Documentation</span></a>
								</li>
								<li>
									<a href="javascript:void(0);"><i class="ti ti-exchange"></i><span>Changelog</span></a>
								</li>
								<li class="submenu">
									<a href="javascript:void(0);">
										<i class="ti ti-menu-2"></i><span>Multi Level</span>
										<span class="menu-arrow"></span>
									</a>
									<ul>
										<li><a href="javascript:void(0);">Multilevel 1</a></li>
										<li class="submenu submenu-two">
											<a href="javascript:void(0);">Multilevel 2<span
													class="menu-arrow inside-submenu"></span></a>
											<ul>
												<li><a href="javascript:void(0);">Multilevel 2.1</a></li>
												<li class="submenu submenu-two submenu-three">
													<a href="javascript:void(0);">Multilevel 2.2<span
															class="menu-arrow inside-submenu inside-submenu-two"></span></a>
													<ul>
														<li><a href="javascript:void(0);">Multilevel 2.2.1</a></li>
														<li><a href="javascript:void(0);">Multilevel 2.2.2</a></li>
													</ul>
												</li>
											</ul>
										</li>
										<li><a href="javascript:void(0);">Multilevel 3</a></li>
									</ul>
								</li>
							</ul>
						</li>
					</ul>
				</div>
			</div>
		</div>
		<!-- /Sidebar -->

		<!-- Page Wrapper -->
		<div class="page-wrapper">
			<div class="content me-0">
				<div class="mb-3">
					<a href="cars.php" class="d-inline-flex align-items-center fw-medium"><i class="ti ti-arrow-left me-1"></i>Back to List</a>
				</div>
				<div class="card mb-0">
					<div class="card-body">
						<?php
						session_start();
						if (isset($_SESSION['success_message'])) {
							echo '<div class="alert alert-success alert-dismissible fade show" role="alert">
									' . $_SESSION['success_message'] . '
									<button type="button" class="btn-close" data-bs-dismiss="alert"></button>
								  </div>';
							unset($_SESSION['success_message']);
						}
						if (isset($_SESSION['error_message'])) {
							echo '<div class="alert alert-danger alert-dismissible fade show" role="alert">
									' . $_SESSION['error_message'] . '
									<button type="button" class="btn-close" data-bs-dismiss="alert"></button>
								  </div>';
							unset($_SESSION['error_message']);
						}
						?>
							<form id="addCarForm" action="process-add-car.php" method="POST" enctype="multipart/form-data">
								<div class="bg-light p-20 mb-4 d-flex align-items-center justify-content-between flex-wrap gap-3" style="background-color: #e9ecef !important;">
										<h4 class="d-flex align-items-center"><i class="ti ti-info-circle text-secondary me-2"></i>Basic Info</h4>
										
									</div>
									<div class="border-bottom mb-4 pb-4">							
										<div class="row row-gap-4">
											<div class="col-xl-3">
												<h6 class="mb-1">Featured Image</h6>
												<p>Upload Featured Image</p>
											</div>
											<div class="col-xl-9">
												<div class="d-flex align-items-center flex-wrap row-gap-3 upload-pic">
													<div class="d-flex align-items-center justify-content-center avatar avatar-xxl me-3 flex-shrink-0 border rounded-circle frames">
														<img src="assets/img/car/car-02.jpg" class="img-fluid rounded-circle" alt="brands">
														<a href="javascript:void(0);" class="upload-img-trash trash-end btn btn-sm rounded-circle">
															<i class="ti ti-trash fs-12"></i>
														</a>
													</div>
													<div>
														<div class="drag-upload-btn btn btn-md btn-dark d-inline-flex align-items-center mb-2">
															<i class="ti ti-photo me-1"></i>Upload
															<input type="file" name="featured_image" class="form-control image-sign" accept=".jpg,.jpeg,.png,.gif,.webp">
														</div>
														<p>Recommended size is 500px x 500px</p>
													</div>
												</div>
											</div>
										</div>										
									</div>	
									<!-- Gallery Images -->
									<div class="border-bottom mb-4 pb-4">                          
										<div class="row row-gap-4">
											<div class="col-xl-3">
												<h6 class="mb-1">Gallery Images</h6>
												<p>Upload additional images for the car</p>
											</div>
											<div class="col-xl-9">
												<div class="d-flex align-items-center flex-wrap row-gap-3">
													<div>
														<div class="drag-upload-btn btn btn-md btn-dark d-inline-flex align-items-center mb-2">
															<i class="ti ti-photo me-1"></i>Upload Gallery
															<input type="file" name="gallery_images[]" class="form-control" accept=".jpg,.jpeg,.png,.gif,.webp" multiple>
														</div>
														<p>You can select multiple files. Max 5MB each.</p>
													</div>
												</div>
											</div>
										</div>
									</div>
									<div class="border-bottom mb-2 pb-2">								
										<div class="row row-gap-4">
											<div class="col-xl-3">
												<h6 class="mb-1">Car Info</h6>
												<p>Add Information About Car</p>
											</div>
											<div class="col-xl-9">
												<div class="mb-3">
													<label class="form-label">Name <span class="text-danger">*</span></label>
													<input type="text" name="name" class="form-control" required>
												</div>
												<!-- Permalink (commented out per requirements)
												<div class="mb-3">
													<label class="form-label">Permalink</label>
													<input type="text" name="permalink" class="form-control" placeholder="https://www.example.com/cars/">
													<p class="fs-13 fw-medium mt-1">Preview : <a href="javascript:void(0);" class="link-info">https://www.example.com/cars/</a></p>
												</div>
												-->
												<div class="mb-3">
													<label class="form-label">Description</label>
													<textarea name="description" class="form-control" rows="4" placeholder="Describe this car"></textarea>
												</div>
												<div class="row">
													<div class="col-lg-4 col-md-6">
														<div class="mb-3">
															<div class="d-flex align-items-center justify-content-between">
																<label class="form-label">Car Type <span class="text-danger">*</span></label>
																<a href="javascript:void(0);" class="link-info mb-2" data-bs-toggle="modal" data-bs-target="#add_type">Add New</a>
															</div>
															<select name="car_type" class="select">
																<option value="">Select</option>
																<option value="Sedan">Sedan</option>
																<option value="Hatchback">Hatchback</option>
																<option value="SUV">SUV</option>
																<option value="Coupes">Coupes</option>
															</select>
														</div>
													</div>
													<div class="col-lg-4 col-md-6">
														<div class="mb-3">
															<div class="d-flex align-items-center justify-content-between">
																<label class="form-label">Brand <span class="text-danger">*</span></label>
																<a href="javascript:void(0);" class="link-info mb-2" data-bs-toggle="modal" data-bs-target="#add_brand">Add New</a>
															</div>
															<select name="brand" class="select">
																<option value="">Select</option>
																<option value="Toyota">Toyota</option>
																<option value="Audi">Audi</option>
																<option value="Lamborghini">Lamborghini</option>
																<option value="BMW">BMW</option>
															</select>
														</div>
													</div>
													<div class="col-lg-4 col-md-6">
														<div class="mb-3">
															<div class="d-flex align-items-center justify-content-between">
																<label class="form-label">Model <span class="text-danger">*</span></label>
																<a href="javascript:void(0);" class="link-info mb-2" data-bs-toggle="modal" data-bs-target="#add_model">Add New</a>
															</div>
															<select name="model" class="select">
																<option value="">Select</option>
																<option value="Urban Cruiser">Urban Cruiser</option>
																<option value="Fortuner">Fortuner</option>
																<option value="V8">V8</option>
																<option value="Huracan">Huracan</option>
															</select>
														</div>
													</div>
													<div class="col-lg-4 col-md-6">
														<div class="mb-3">
															<label class="form-label">Category <span class="text-danger">*</span></label>
															<select name="category" class="select">
																<option value="">Select</option>
																<option value="Car">Car</option>
																<option value="Bike">Bike</option>
																<option value="Truck">Truck</option>
															</select>
														</div>
													</div>
													<div class="col-lg-4 col-md-6">
														<div class="mb-3">
															<label class="form-label">Plate Number</label>
															<input type="text" name="plate_number" class="form-control">
														</div>
													</div>
													<div class="col-lg-4 col-md-6">
														<div class="mb-3">
															<label class="form-label">VIN Number</label>
															<input type="text" name="vin_number" class="form-control">
														</div>
													</div>
													<div class="col-lg-4 col-md-6">
														<div class="mb-3">
															<label class="form-label">Main Location <span class="text-danger">*</span></label>
															<select name="main_location" class="select">
																<option value="">Select</option>
																<option value="Johnson Dealer Zone">Johnson Dealer Zone</option>
																<option value="Miller Auto Trade Zone">Miller Auto Trade Zone</option>
																<option value="Thompson Dealer Parking">Thompson Dealer Parking</option>
															</select>
														</div>
													</div>
													<div class="col-lg-4 col-md-6">
														<div class="mb-3">
															<label class="form-label">Link Other Location</label>
															<select class="limited-multi-select" multiple="multiple">
																<option value="1">Evans Dealer Car Zone</option>
																<option value="2">Allen Dealer Parking Lot</option>
																<option value="3">Walker Auto Trade Yard</option>
															</select>
														</div>
													</div>
													<div class="col-lg-4 col-md-6">
														<div class="mb-3">
															<label class="form-label">Fuel</label>
															<select name="fuel_type" class="select">
																<option value="">Select</option>
																<option value="Petrol">Petrol</option>
																<option value="Diesel">Diesel</option>
																<option value="Electric">Electric</option>
															</select>
														</div>
													</div>
													<div class="col-lg-4 col-md-6">
														<div class="mb-3">
															<label class="form-label">Odometer</label>
															<input type="text" name="odometer" class="form-control">
														</div>
													</div>
													<div class="col-lg-4 col-md-6">
														<div class="mb-3">
															<label class="form-label">Color <span class="text-danger">*</span></label>
															<select name="color" class="select2-color">
																<option value="">Select Color</option>
																<option value="Red" class="bg-danger">Red</option>
																<option value="Green" class="bg-success">Green</option>
																<option value="Blue" class="bg-info">Blue</option>
																<option value="Dark Blue" class="bg-primary">Dark Blue</option>
															</select>
														</div>
													</div>
													<div class="col-lg-4 col-md-6">
														<div class="mb-3">
															<label class="form-label">Year of Car <span class="text-danger">*</span></label>
															<div class="input-icon-end position-relative">
																<input type="number" name="year_of_car" class="form-control" min="1900" max="2030">
																<span class="input-icon-addon">
																	<i class="ti ti-calendar"></i>
																</span>
															</div>
														</div>
													</div>
													<div class="col-lg-4 col-md-6">
														<div class="mb-3">
															<label class="form-label">Transmission</label>
															<select name="transmission" class="select">
																<option value="">Select</option>
																<option value="Manual">Manual</option>
																<option value="Automatic">Automatic</option>
																<option value="Semi Automatic">Semi Automatic</option>
															</select>
														</div>
													</div>
													<div class="col-lg-4 col-md-6">
														<div class="mb-3">
															<label class="form-label">Status <span class="text-danger">*</span></label>
															<select name="status" class="select" required>
																<option value="Active" selected>Active</option>
																<option value="Inactive">Inactive</option>
																<option value="Maintenance">Maintenance</option>
																<option value="Rented">Rented</option>
															</select>
														</div>
													</div>
													<div class="col-lg-4 col-md-6">
														<div class="mb-3">
															<label class="form-label">Mileage</label>
															<input type="text" name="mileage" class="form-control">
														</div>
													</div>
													<div class="col-lg-4 col-md-6">
														<div class="mb-3">
															<label class="form-label">Passengers</label>
															<input type="number" name="passengers" class="form-control" min="1" max="20">
														</div>
													</div>
													<div class="col-lg-4 col-md-6">
														<div class="mb-3">
															<label class="form-label">No of Seats</label>
															<select name="seats" class="select">
																<option value="">Select</option>
																<option value="2 Seater">2 Seater</option>
																<option value="4 Seater">4 Seater</option>
																<option value="5 Seater">5 Seater</option>
															</select>
														</div>
													</div>
													<div class="col-lg-4 col-md-6">
														<div class="mb-3">
															<label class="form-label">No of Doors</label>
															<select name="doors" class="select">
																<option value="">Select</option>
																<option value="2 Doors">2 Doors</option>
																<option value="3 Doors">3 Doors</option>
																<option value="4 Doors">4 Doors</option>
															</select>
														</div>
													</div>
													<div class="col-lg-4 col-md-6">
														<div class="mb-3">
															<label class="form-label">No of Air Bags</label>
															<input type="number" name="air_bags" class="form-control" min="0" max="20">
														</div>
													</div>
												</div>
											</div>
										</div>
									</div>	
									</div>		
									<div class="bg-light p-20 mb-4 d-flex align-items-center justify-content-between flex-wrap gap-3" style="background-color: #e9ecef !important;">
										<h4 class="d-flex align-items-center"><i class="ti ti-flame text-secondary me-2"></i>Features & Amenities</h4>
									</div>									
									<div class="border-bottom mb-2 pb-2 amenity-wrap">								
										<div class="row row-gap-4">
											<div class="col-xl-3">
												<h6 class="mb-1">Features & Amenities</h6>
												<p>Add Information About Car</p>
											</div>
											<div class="col-xl-9">
												<div class="row">
													<div class="col-lg-12">
														<div class="mb-3">
															<div class="form-check mb-0">
																<input class="form-check-input select-all" type="checkbox" id="select-all-features">
																<label class="form-check-label" for="select-all-features">
																	Check All Features
																</label>
															</div>
														</div>
													</div>
													<div class="col-lg-4 col-md-6">
														<div class="mb-3">
															<div class="form-check form-checkbox mb-0">
																<input class="form-check-input feature-checkbox" type="checkbox" name="features[]" value="air_condition" id="amenity">
																<label class="form-check-label" for="amenity">
																	Air Condition
																</label>
															</div>
														</div>
													</div>
													<div class="col-lg-4 col-md-6">
														<div class="mb-3">
															<div class="form-check form-checkbox mb-0">
																<input class="form-check-input feature-checkbox" type="checkbox" name="features[]" value="climate_control" id="amenity1">
																<label class="form-check-label" for="amenity1">
																	Climate Control
																</label>
															</div>
														</div>
													</div>
													<div class="col-lg-4 col-md-6">
														<div class="mb-3">
															<div class="form-check form-checkbox mb-0">
																<input class="form-check-input feature-checkbox" type="checkbox" name="features[]" value="climate_control_two_zones" id="amenity2">
																<label class="form-check-label" for="amenity2">
																	Climate Control Two Zones
																</label>
															</div>
														</div>
													</div>
													<div class="col-lg-4 col-md-6">
														<div class="mb-3">
															<div class="form-check form-checkbox mb-0">
																<input class="form-check-input feature-checkbox" type="checkbox" name="features[]" value="luxury_climate_control" id="amenity3">
																<label class="form-check-label" for="amenity3">
																	Luxury Climate Control 
																</label>
															</div>
														</div>
													</div>
													<div class="col-lg-4 col-md-6">
														<div class="mb-3">
															<div class="form-check form-checkbox mb-0">
																<input class="form-check-input feature-checkbox" type="checkbox" name="features[]" value="sunroof" id="amenity4">
																<label class="form-check-label" for="amenity4">
																	Sunroof
																</label>
															</div>
														</div>
													</div>
													<div class="col-lg-4 col-md-6">
														<div class="mb-3">
															<div class="form-check form-checkbox mb-0">
																<input class="form-check-input feature-checkbox" type="checkbox" name="features[]" value="panoramic_sunroof" id="amenity5">
																<label class="form-check-label" for="amenity5">
																	Panoramic Sunroof 
																</label>
															</div>
														</div>
													</div>
													<div class="col-lg-4 col-md-6">
														<div class="mb-3">
															<div class="form-check form-checkbox mb-0">
																<input class="form-check-input feature-checkbox" type="checkbox" name="features[]" value="moonroof" id="amenity6">
																<label class="form-check-label" for="amenity6">
																	Moonroof  
																</label>
															</div>
														</div>
													</div>
													<div class="col-lg-4 col-md-6">
														<div class="mb-3">
															<div class="form-check form-checkbox mb-0">
																<input class="form-check-input feature-checkbox" type="checkbox" name="features[]" value="push_button_start" id="amenity7">
																<label class="form-check-label" for="amenity7">
																	Push-button Start
																</label>
															</div>
														</div>
													</div>
													<div class="col-lg-4 col-md-6">
														<div class="mb-3">
															<div class="form-check form-checkbox mb-0">
																<input class="form-check-input feature-checkbox" type="checkbox" name="features[]" value="keyless_access" id="amenity8">
																<label class="form-check-label" for="amenity8">
																	Keyless Access
																</label>
															</div>
														</div>
													</div>
													<div class="col-lg-4 col-md-6">
														<div class="mb-3">
															<div class="form-check form-checkbox mb-0">
																<input class="form-check-input feature-checkbox" type="checkbox" name="features[]" value="rear_parking_sensors" id="amenity9">
																<label class="form-check-label" for="amenity9">
																	Rear Parking Sensors
																</label>
															</div>
														</div>
													</div>
													<div class="col-lg-4 col-md-6">
														<div class="mb-3">
															<div class="form-check form-checkbox mb-0">
																<input class="form-check-input feature-checkbox" type="checkbox" name="features[]" value="parking_sensors" id="amenity10">
																<label class="form-check-label" for="amenity10">
																	Parking Sensors
																</label>
															</div>
														</div>
													</div>
													<div class="col-lg-4 col-md-6">
														<div class="mb-3">
															<div class="form-check form-checkbox mb-0">
																<input class="form-check-input feature-checkbox" type="checkbox" name="features[]" value="built_in_sat_nav" id="amenity11">
																<label class="form-check-label" for="amenity11">
																	Built-in Sat Nav
																</label>
															</div>
														</div>
													</div>
													<div class="col-lg-4 col-md-6">
														<div class="mb-3">
															<div class="form-check form-checkbox mb-0">
																<input class="form-check-input feature-checkbox" type="checkbox" name="features[]" value="mobile_phone_technology" id="amenity12">
																<label class="form-check-label" for="amenity12">
																	Mobile Phone Technology
																</label>
															</div>
														</div>
													</div>
													<div class="col-lg-4 col-md-6">
														<div class="mb-3">
															<div class="form-check form-checkbox mb-0">
																<input class="form-check-input feature-checkbox" type="checkbox" name="features[]" value="bluetooth" id="amenity13">
																<label class="form-check-label" for="amenity13">
																	Bluetooth
																</label>
															</div>
														</div>
													</div>
													<div class="col-lg-4 col-md-6">
														<div class="mb-3">
															<div class="form-check form-checkbox mb-0">
																<input class="form-check-input feature-checkbox" type="checkbox" name="features[]" value="usb" id="amenity14">
																<label class="form-check-label" for="amenity14">
																	Usb
																</label>
															</div>
														</div>
													</div>
													<div class="col-lg-4 col-md-6">
														<div class="mb-3">
															<div class="form-check form-checkbox mb-0">
																<input class="form-check-input feature-checkbox" type="checkbox" name="features[]" value="qi_wireless_charging" id="amenity15">
																<label class="form-check-label" for="amenity15">
																	Qi Wireless Charging
																</label>
															</div>
														</div>
													</div>
													<div class="col-lg-4 col-md-6">
														<div class="mb-3">
															<div class="form-check form-checkbox mb-0">
																<input class="form-check-input feature-checkbox" type="checkbox" name="features[]" value="audio_ipod" id="amenity16">
																<label class="form-check-label" for="amenity16">
																	Audio/ipod 
																</label>
															</div>
														</div>
													</div>
													<div class="col-lg-4 col-md-6">
														<div class="mb-3">
															<div class="form-check form-checkbox mb-0">
																<input class="form-check-input feature-checkbox" type="checkbox" name="features[]" value="cruise_control" id="amenity17" checked>
																<label class="form-check-label" for="amenity17">
																	Cruise Control
																</label>
															</div>
														</div>
													</div>
													<div class="col-lg-4 col-md-6">
														<div class="mb-3">
															<div class="form-check form-checkbox mb-0">
																<input class="form-check-input feature-checkbox" type="checkbox" name="features[]" value="adaptive_cruise_control" id="amenity18">
																<label class="form-check-label" for="amenity18">
																	Adaptive Cruise Control
																</label>
															</div>
														</div>
													</div>
													<div class="col-lg-4 col-md-6">
														<div class="mb-3">
															<div class="form-check form-checkbox mb-0">
																<input class="form-check-input feature-checkbox" type="checkbox" name="features[]" value="apple_carplay" id="amenity19">
																<label class="form-check-label" for="amenity19">
																	Apple Carplay
																</label>
															</div>
														</div>
													</div>
													<div class="col-lg-4 col-md-6">
														<div class="mb-3">
															<div class="form-check form-checkbox mb-0">
																<input class="form-check-input feature-checkbox" type="checkbox" name="features[]" value="android_auto" id="amenity20">
																<label class="form-check-label" for="amenity20">
																	Android Auto
																</label>
															</div>
														</div>
													</div>
													<div class="col-lg-4 col-md-6">
														<div class="mb-3">
															<div class="form-check form-checkbox mb-0">
																<input class="form-check-input feature-checkbox" type="checkbox" name="features[]" value="forward_collision_warning" id="amenity21">
																<label class="form-check-label" for="amenity21">
																	Forward Collision Warning
																</label>
															</div>
														</div>
													</div>
													<div class="col-lg-4 col-md-6">
														<div class="mb-3">
															<div class="form-check form-checkbox mb-0">
																<input class="form-check-input feature-checkbox" type="checkbox" name="features[]" value="lane_departure_warning" id="amenity22">
																<label class="form-check-label" for="amenity22">
																	Lane Departure Warning
																</label>
															</div>
														</div>
													</div>
													<div class="col-lg-4 col-md-6">
														<div class="mb-3">
															<div class="form-check form-checkbox mb-0">
																<input class="form-check-input feature-checkbox" type="checkbox" name="features[]" value="automatic_emergency_braking" id="amenity23">
																<label class="form-check-label" for="amenity23">
																	Automatic Emergency Braking
																</label>
															</div>
														</div>
													</div>
													<div class="col-lg-4 col-md-6">
														<div class="mb-3">
															<div class="form-check form-checkbox mb-0">
																<input class="form-check-input feature-checkbox" type="checkbox" name="features[]" value="active_parking_assist" id="amenity24">
																<label class="form-check-label" for="amenity24">
																	Active Parking Assist
																</label>
															</div>
														</div>
													</div>
													<div class="col-lg-4 col-md-6">
														<div class="mb-3">
															<div class="form-check form-checkbox mb-0">
																<input class="form-check-input feature-checkbox" type="checkbox" name="features[]" value="automatic_high_beams" id="amenity25">
																<label class="form-check-label" for="amenity25">
																	Automatic High Beams
																</label>
															</div>
														</div>
													</div>
													<div class="col-lg-4 col-md-6">
														<div class="mb-3">
															<div class="form-check form-checkbox mb-0">
																<input class="form-check-input feature-checkbox" type="checkbox" name="features[]" value="adaptive_headlights" id="amenity26">
																<label class="form-check-label" for="amenity26">
																	Adaptive Headlights
																</label>
															</div>
														</div>
													</div>
													<div class="col-lg-4 col-md-6">
														<div class="mb-3">
															<div class="form-check form-checkbox mb-0">
																<input class="form-check-input feature-checkbox" type="checkbox" name="features[]" value="360_degree_camera" id="amenity27">
																<label class="form-check-label" for="amenity27">
																	360-degree Camera
																</label>
															</div>
														</div>
													</div>
													<div class="col-lg-4 col-md-6">
														<div class="mb-3">
															<div class="form-check form-checkbox mb-0">
																<input class="form-check-input feature-checkbox" type="checkbox" name="features[]" value="rearview_camera" id="amenity29">
																<label class="form-check-label" for="amenity29">
																	Rearview Camera
																</label>
															</div>
														</div>
													</div>
													<div class="col-lg-4 col-md-6">
														<div class="mb-3">
															<div class="form-check form-checkbox mb-0">
																<input class="form-check-input feature-checkbox" type="checkbox" name="features[]" value="towing_hook" id="amenity30">
																<label class="form-check-label" for="amenity30">
																	Towing Hook
																</label>
															</div>
														</div>
													</div>
													<div class="col-lg-4 col-md-6">
														<div class="mb-3">
															<div class="form-check form-checkbox mb-0">
																<input class="form-check-input feature-checkbox" type="checkbox" name="features[]" value="leather_interior" id="amenity31">
																<label class="form-check-label" for="amenity31">
																	Leather Interior
																</label>
															</div>
														</div>
													</div>
													<div class="col-lg-4 col-md-6">
														<div class="mb-3">
															<div class="form-check form-checkbox mb-0">
																<input class="form-check-input feature-checkbox" type="checkbox" name="features[]" value="fabric_interior" id="amenity32">
																<label class="form-check-label" for="amenity32">
																	Fabric Interior
																</label>
															</div>
														</div>
													</div>
												</div>
											</div>
										</div>
									</div>
									</div>	
									<div class="bg-light p-20 mb-4 d-flex align-items-center justify-content-between flex-wrap gap-3" style="background-color: #e9ecef !important;">
										<h4 class="d-flex align-items-center"><i class="ti ti-files text-secondary me-2"></i>Pricing</h4>
									</div>
									<div class="border-bottom mb-4 pb-2 unlimited-price">
										<div class="row row-gap-4">
											<div class="col-xl-3">
												<h6 class="mb-1">Pricing</h6>
												<p>Add Pricing for Cars</p>
											</div>
											<div class="col-xl-9">
												<div class="row">
													<div class="col-lg-3 col-md-6">
														<div class="mb-3">
															<label class="form-label">Daily Price</label>
															<input type="number" name="daily_price" class="form-control" step="0.01">
														</div>
													</div>
													<div class="col-lg-6 col-md-6">
														<div class="mb-3">
															<div class="d-flex align-items-center justify-content-between">
																<label class="form-label">Base Kilometers (Per Day) <span class="text-danger">*</span></label>
																<div class="form-check unlimited-checkbox mb-2">
																	<input class="form-check-input" type="checkbox" name="unlimited_kilometers" id="unlimited">
																	<label class="form-check-label" for="unlimited">
																		Unlimited
																	</label>
																</div>
															</div>
															<input type="number" name="base_kilometers_per_day" class="form-control" min="0">
														</div>
													</div>
													<div class="col-lg-6 col-md-6">
														<div class="mb-3 unlimited-wrap">
															<label class="form-label">Kilometers Extra Price <span class="text-danger">*</span></label>
															<input type="number" name="kilometers_extra_price" class="form-control" step="0.01" min="0">
														</div>
													</div>
												</div>
											</div>
										</div>
									</div>


									<div class="border-bottom mb-2 pb-2">
										<div class="row row-gap-4">
											<div class="col-xl-3">
												<h6 class="mb-1">Insurance</h6>
												<p>Select Insurance for Car</p>
											</div>
											<div class="col-xl-9">
												<div class="mb-3">
													<label class="form-label">Insurance Option</label>
													<select name="insurance_option" class="select">
														<option value="none" selected>None</option>
														<option value="Full Premium Insurance">Full Premium Insurance ($200)</option>
														<option value="Roadside Assistance">Roadside Assistance ($250)</option>
														<option value="Liability Insurance">Liability Insurance ($150)</option>
														<option value="Personal Accident Insurance">Personal Accident Insurance ($300)</option>
													</select>
												</div>
											</div>
										</div>
									</div>
									</div>	
									<div class="bg-light p-20 mb-4 d-flex align-items-center justify-content-between flex-wrap gap-3" style="background-color: #e9ecef !important;">
										<h4 class="d-flex align-items-center"><i class="ti ti-float-center text-secondary me-2"></i>Extra Services</h4>
									</div>		
									<div class="border-bottom mb-2 pb-1 extra-service">	
										<div class="text-end">
											<a href="javascript:void(0);" class="link-purple text-decoration-underline fw-medium d-inline-block" data-bs-toggle="modal" data-bs-target="#edit_price">Edit Price</a>			
										</div>				
										<div class="row">
											<div class="col-xxl-4 col-md-6 d-flex">
												<div class="form-check form-checkbox d-flex align-items-center justify-content-between flex-wrap gap-3 flex-fill">
													<div class="d-flex align-items-center">
														<input class="form-check-input service-checkbox" type="checkbox" name="extra_services[]" value="navigation">
														<span class="service-icon bg-dark d-flex align-items-center justify-content-center me-2 ms-2">
															<i class="ti ti-gps"></i>
														</span>
														<div>
															<h6 class="fs-14 fw-semibold mb-1">Navigation</h6>
															<p class="fs-13">Using GPS while travel</p>
														</div>
													</div>
													<div>
														<p class="fs-13 mb-1">Per Day</p>
														<h6 class="fs-14 fw-semibold">$10</h6>
													</div>
												</div>
											</div>
											<div class="col-xxl-4 col-md-6 d-flex">
												<div class="form-check form-checkbox d-flex align-items-center justify-content-between flex-wrap gap-3 flex-fill">
													<div class="d-flex align-items-center">
														<input class="form-check-input service-checkbox" type="checkbox" name="extra_services[]" value="wifi_hotspot">
														<span class="service-icon bg-dark d-flex align-items-center justify-content-center me-2 ms-2">
															<i class="ti ti-wifi-2"></i>
														</span>
														<div>
															<h6 class="fs-14 fw-semibold mb-1">Wi-Fi Hotspot</h6>
															<p class="fs-13">Constant portable internet</p>
														</div>
													</div>
													<div>
														<p class="fs-13 mb-1">One time</p>
														<h6 class="fs-14 fw-semibold">$10</h6>
													</div>
												</div>
											</div>
											<div class="col-xxl-4 col-md-6 d-flex">
												<div class="form-check form-checkbox d-flex align-items-center justify-content-between flex-wrap gap-3 flex-fill active">
													<div class="d-flex align-items-center">
														<input class="form-check-input service-checkbox" type="checkbox" name="extra_services[]" value="child_safety_seats" checked>
														<span class="service-icon bg-dark d-flex align-items-center justify-content-center me-2 ms-2">
															<i class="ti ti-baby-carriage"></i>
														</span>
														<div>
															<h6 class="fs-14 fw-semibold mb-1">Child Safety Seats</h6>
															<p class="fs-13">Secure infant/toddler car seat</p>
														</div>
													</div>
													<div>
														<p class="fs-13 mb-1">Per Day</p>
														<h6 class="fs-14 fw-semibold">$10</h6>
													</div>
												</div>
											</div>											
											<div class="col-xxl-4 col-md-6 d-flex">
												<div class="form-check form-checkbox d-flex align-items-center justify-content-between flex-fill flex-wrap gap-3 active">
													<div class="d-flex align-items-center">
														<input class="form-check-input service-checkbox" type="checkbox" name="extra_services[]" value="fuel_pre_purchase" checked>
														<span class="service-icon bg-dark d-flex align-items-center justify-content-center me-2 ms-2">
															<i class="ti ti-baby-carriage"></i>
														</span>
														<div>
															<h6 class="fs-14 fw-semibold mb-1">Fuel Pre-Purchase</h6>
															<p class="fs-13">Full tank, return hassle-free</p>
														</div>
													</div>
													<div>
														<p class="fs-13 mb-1">Per Day</p>
														<h6 class="fs-14 fw-semibold">$10</h6>
													</div>
												</div>
											</div>											
											<div class="col-xxl-4 col-md-6 d-flex">
												<div class="form-check form-checkbox d-flex align-items-center justify-content-between flex-wrap gap-3 flex-fill">
													<div class="d-flex align-items-center">
														<input class="form-check-input service-checkbox" type="checkbox" name="extra_services[]" value="roadside_assistance">
														<span class="service-icon bg-dark d-flex align-items-center justify-content-center me-2 ms-2">
															<i class="ti ti-user-star"></i>
														</span>
														<div>
															<h6 class="fs-14 fw-semibold mb-1">Roadside Assistance</h6>
															<p class="fs-13">24/7 emergency car support</p>
														</div>
													</div>
													<div>
														<p class="fs-13 mb-1">Per Day</p>
														<h6 class="fs-14 fw-semibold">$10</h6>
													</div>
												</div>
											</div>											
											<div class="col-xxl-4 col-md-6 d-flex">
												<div class="form-check form-checkbox d-flex align-items-center justify-content-between flex-wrap gap-3 flex-fill">
													<div class="d-flex align-items-center">
														<input class="form-check-input service-checkbox" type="checkbox" name="extra_services[]" value="satellite_radio">
														<span class="service-icon bg-dark d-flex align-items-center justify-content-center me-2 ms-2">
															<i class="ti ti-satellite"></i>
														</span>
														<div>
															<h6 class="fs-14 fw-semibold mb-1">Satellite Radio</h6>
															<p class="fs-13"> Unlimited premium music</p>
														</div>
													</div>
													<div>
														<p class="fs-13 mb-1">Per Day</p>
														<h6 class="fs-14 fw-semibold">$10</h6>
													</div>
												</div>
											</div>											
											<div class="col-xxl-4 col-md-6 d-flex">
												<div class="form-check form-checkbox d-flex align-items-center justify-content-between flex-wrap gap-3 flex-fill">
													<div class="d-flex align-items-center">
														<input class="form-check-input service-checkbox" type="checkbox" name="extra_services[]" value="usb_charger">
														<span class="service-icon bg-dark d-flex align-items-center justify-content-center me-2 ms-2">
															<i class="ti ti-usb"></i>
														</span>
														<div>
															<h6 class="fs-14 fw-semibold mb-1">USB Charger</h6>
															<p class="fs-13">Fast charging for devices</p>
														</div>
													</div>
													<div>
														<p class="fs-13 mb-1">Per Day</p>
														<h6 class="fs-14 fw-semibold">$10</h6>
													</div>
												</div>
											</div>											
											<div class="col-xxl-4 col-md-6 d-flex">
												<div class="form-check form-checkbox d-flex align-items-center justify-content-between flex-wrap gap-3 flex-fill active">
													<div class="d-flex align-items-center">
														<input class="form-check-input service-checkbox" type="checkbox" name="extra_services[]" value="express_checkin_checkout" checked>
														<span class="service-icon bg-dark d-flex align-items-center justify-content-center me-2 ms-2">
															<i class="ti ti-checkup-list"></i>
														</span>
														<div>
															<h6 class="fs-14 fw-semibold mb-1">Express Check-in/out</h6>
															<p class="fs-13">Fast pickup & return process</p>
														</div>
													</div>
													<div>
														<p class="fs-13 mb-1">Per Day</p>
														<h6 class="fs-14 fw-semibold">$10</h6>
													</div>
												</div>
											</div>											
											<div class="col-xxl-4 col-md-6 d-flex">
												<div class="form-check form-checkbox d-flex align-items-center justify-content-between flex-wrap gap-3 flex-fill">
													<div class="d-flex align-items-center">
														<input class="form-check-input service-checkbox" type="checkbox" name="extra_services[]" value="toll_pass">
														<span class="service-icon bg-dark d-flex align-items-center justify-content-center me-2 ms-2">
															<i class="ti ti-tallymark-2"></i>
														</span>
														<div>
															<h6 class="fs-14 fw-semibold mb-1">Toll Pass</h6>
															<p class="fs-13">Skip toll booth lines</p>
														</div>
													</div>
													<div>
														<p class="fs-13 mb-1">Per Day</p>
														<h6 class="fs-14 fw-semibold">$10</h6>
													</div>
												</div>
											</div>											
											<div class="col-xxl-4 col-md-6 d-flex">
												<div class="form-check form-checkbox d-flex align-items-center justify-content-between flex-wrap gap-3 flex-fill">
													<div class="d-flex align-items-center">
														<input class="form-check-input service-checkbox" type="checkbox" name="extra_services[]" value="insurance">
														<span class="service-icon bg-dark d-flex align-items-center justify-content-center me-2 ms-2">
															<i class="ti ti-file-pencil"></i>
														</span>
														<div>
															<h6 class="fs-14 fw-semibold mb-1">Insurance</h6>
															<p class="fs-13">Full coverage protection</p>
														</div>
													</div>
													<div>
														<p class="fs-13 mb-1">Per Day</p>
														<h6 class="fs-14 fw-semibold">$10</h6>
													</div>
												</div>
											</div>											
											<div class="col-xxl-4 col-md-6 d-flex">
												<div class="form-check form-checkbox d-flex align-items-center justify-content-between flex-wrap gap-3 flex-fill">
													<div class="d-flex align-items-center">
														<input class="form-check-input service-checkbox" type="checkbox" name="extra_services[]" value="dash_cam">
														<span class="service-icon bg-dark d-flex align-items-center justify-content-center me-2 ms-2">
															<i class="ti ti-camera"></i>
														</span>
														<div>
															<h6 class="fs-14 fw-semibold mb-1">Dash Cam</h6>
															<p class="fs-13">Records trips extra security</p>
														</div>
													</div>
													<div>
														<p class="fs-13 mb-1">Per Day</p>
														<h6 class="fs-14 fw-semibold">$10</h6>
													</div>
												</div>
											</div>
										</div>
									</div>
									</div>	

									<div class="bg-light p-20 mb-4 d-flex align-items-center justify-content-between flex-wrap gap-3" style="background-color: #e9ecef !important;">
										<h4 class="d-flex align-items-center"><i class="ti ti-id text-secondary me-2"></i>Damages</h4>
									</div>
									<div class="border-bottom mb-2 pb-4">	
										<div class="row row-gap-4">
											<div class="col-xl-2">
												<h6 class="mb-1">Damages</h6>
												<p>Add Damages On Car</p>
											</div>
											<div class="col-xl-9">
												<a href="javascript:void(0);" class="btn btn-dark btn-md d-inline-flex align-items-center mb-3" data-bs-toggle="modal" data-bs-target="#add-damage"><i class="ti ti-plus me-1"></i>Add Damage</a>
												<div class="card border-0 bg-light mb-0">
													<div class="card-body">
														<div class="d-flex align-items-center justify-content-between flex-wrap gap-3 mb-3">
															<h6 id="damageCount">Total Damages : 0</h6>
															<div class="dropdown flag-dropdown">
																<a class="dropdown-toggle btn btn-white d-flex align-items-center justify-content-between" data-bs-toggle="dropdown" href="javascript:void(0);">
																	All Damages
																</a>
																<ul class="dropdown-menu p-2">
																	<li>
																		<a href="javascript:void(0);" class="dropdown-item d-flex align-items-center">
																			All Damages
																		</a>
																	</li>
																	<li>
																		<a href="javascript:void(0);" class="dropdown-item d-flex align-items-center">
																			Interior
																		</a>
																	</li>
																	<li>
																		<a href="javascript:void(0);" class="dropdown-item d-flex align-items-center">
																			Exterior
																		</a>
																	</li>
																</ul>
															</div>
														</div>
														<div id="damagesContainer">
															<!-- Damages will be added here dynamically -->
																	</div>
																</div>
															</div>
														</div>
													</div>
												</div>
												</div>
												<!-- Submit actions for Add Car form -->
												<div class="d-flex align-items-center justify-content-end pt-3 pb-2">
													<a href="cars.php" class="btn btn-light d-flex align-items-center me-2"><i class="ti ti-chevron-left me-1"></i>Cancel</a>
													<button type="submit" class="btn btn-primary d-flex align-items-center" id="saveCarBtn">Save Car<i class="ti ti-chevron-right ms-1"></i></button>
												</div>
												</form>
												<!-- End of addCarForm -->
												<!-- FAQ section commented out per requirements
									<div class="bg-light p-20 mb-4 d-flex align-items-center justify-content-between flex-wrap gap-3" style="background-color: #e9ecef !important;">
										<h4 class="d-flex align-items-center"><i class="ti ti-question-mark text-secondary me-2"></i>FAQ</h4>
									</div>
									<div class="border-bottom mb-2 pb-4">
										<div class="row row-gap-4">
											<div class="col-xl-3">
												<h6 class="mb-1">FAQ</h6>
												<p>Add FAQ of your Car</p>
											</div>
											<div class="col-xl-9">
												<a href="javascript:void(0);" class="btn btn-dark btn-md d-inline-flex align-items-center mb-3" data-bs-toggle="modal" data-bs-target="#add-faq"><i class="ti ti-plus me-1"></i>Add FAQ</a>
												<div class="card border-0 bg-light mb-0">
													<div class="card-body">
														<h6 class="mb-3" id="faqCount">Total FAQ : 0</h6>
														<div class="custom-accordion-icon" id="faqaccordion"></div>
													</div>
												</div>
											</div>
										</div>
									</div>
									-->

									<!-- SEO section commented out per requirements
									<form action="cars.php">
										<div class="bg-light p-20 mb-4 d-flex align-items-center justify-content-between flex-wrap gap-3" style="background-color: #e9ecef !important;">
											<h4 class="d-flex align-items-center"><i class="ti ti-question-mark text-secondary me-2"></i>SEO</h4>
										</div>
										<div class="border-bottom mb-2 pb-2">
											<div class="row row-gap-4">
												<div class="col-xl-3">
													<h6 class="mb-1">SEO</h6>
													<p>Add SEO Meta of the car</p>
												</div>
												<div class="col-xl-9">
													<div class="mb-3">
														<label class="form-label">Meta Title</label>
														<input type="text" name="meta_title" class="form-control">
													</div>
													<div class="mb-3">
														<label class="form-label">Keywords</label>
														<input type="text" name="meta_keywords" class="form-control" placeholder="car, rental, vehicle, etc.">
													</div>
													<div class="mb-3">
														<label class="form-label">Description</label>
														<textarea name="meta_description" class="form-control" rows="3" placeholder="Brief description of the car for search engines"></textarea>
													</div>
												</div>
											</div>
										</div>
										<div class="d-flex align-items-center justify-content-end pt-3">
											<a href="cars.php" class="btn btn-light d-flex align-items-center me-2"><i class="ti ti-chevron-left me-1"></i>Cancel</a>
											<button type="submit" class="btn btn-primary d-flex align-items-center">Save Car<i class="ti ti-chevron-right ms-1"></i></button>
										</div>
									</form>
									-->
						</div>
					</div>
				</div>
			</div>
			<!-- Footer-->
			<div class="footer d-sm-flex align-items-center justify-content-between bg-white p-3">
				<p class="mb-0">
                    <a href="javascript:void(0);">Privacy Policy</a>
                    <a href="javascript:void(0);" class="ms-4">Terms of Use</a>
                </p>
				<p>&copy; 2025 Dreamsrent, Made with <span class="text-danger">❤</span> by <a href="javascript:void(0);" class="text-secondary">Dreams</a></p>
			</div>
			<!-- /Footer-->
		</div>
		<!-- /Page Wrapper -->



		<!-- Add Brand -->
        <div class="modal fade" id="add_brand">
            <div class="modal-dialog modal-dialog-centered modal-md">
                <div class="modal-content">
					<div class="modal-header">
						<h5 class="mb-0">Create Brand</h5>
						<button type="button" class="btn-close custom-btn-close" data-bs-dismiss="modal" aria-label="Close">
							<i class="ti ti-x fs-16"></i>
						</button>
					</div>
                    <div class="modal-body pb-1">
						<div class="mb-3">
							<label class="form-label">Brand Image <span class="text-danger">*</span></label>
							<div class="d-flex align-items-center flex-wrap row-gap-3  mb-3">                                                
								<div class="d-flex align-items-center justify-content-center avatar avatar-xxl border me-3 flex-shrink-0 text-dark frames">
									<i class="ti ti-photo-up text-gray-4 fs-24"></i>
								</div>                                              
								<div class="profile-upload">
									<div class="profile-uploader d-flex align-items-center">
										<div class="drag-upload-btn btn btn-md btn-dark">
											<i class="ti ti-photo-up fs-14"></i>
											Upload
											<input type="file" class="form-control image-sign" multiple="">
										</div>
									</div>
									<div class="mt-2">
										<p class="fs-14">Upload Image size 180*180, within 5MB</p>
									</div>
								</div>
							</div>
						</div>                     
                        <div class="mb-3">
							<label class="form-label">Brand Name <span class="text-danger">*</span></label>
							<input type="text" class="form-control">
						</div>
						<div class="mb-3">
							<label class="form-label">Total Cars <span class="text-danger">*</span></label>
							<input type="text" class="form-control">
						</div>
                    </div>
					<div class="modal-footer">
						<div class="d-flex justify-content-center">
                            <a href="javascript:void(0);" class="btn btn-light me-3" data-bs-dismiss="modal">Cancel</a>
                            <button type="submit" class="btn btn-primary">Create New</button>
                        </div>
					</div>
                </div>
            </div>
        </div>
        <!-- /Add Brand -->

		<!-- Add Type -->
        <div class="modal fade" id="add_type">
            <div class="modal-dialog modal-dialog-centered modal-md">
                <div class="modal-content">
					<div class="modal-header">
						<h5 class="mb-0">Create Type</h5>
						<button type="button" class="btn-close custom-btn-close" data-bs-dismiss="modal" aria-label="Close">
							<i class="ti ti-x fs-16"></i>
						</button>
					</div>
                    <div class="modal-body pb-1">
						<div class="mb-3">
							<label class="form-label">Image <span class="text-danger">*</span></label>
							<div class="d-flex align-items-center flex-wrap row-gap-3  mb-3">                                                
								<div class="d-flex align-items-center justify-content-center avatar avatar-xxl border me-3 flex-shrink-0 text-dark frames">
									<i class="ti ti-photo-up text-gray-4 fs-24"></i>
								</div>                                              
								<div class="profile-upload">
									<div class="profile-uploader d-flex align-items-center">
										<div class="drag-upload-btn btn btn-md btn-dark">
											<i class="ti ti-photo-up fs-14"></i>
											Upload
											<input type="file" class="form-control image-sign" multiple="">
										</div>
									</div>
									<div class="mt-2">
										<p class="fs-14">Upload Image size 180*180, within 5MB</p>
									</div>
								</div>
							</div>
						</div>                     
                        <div class="mb-3">
							<label class="form-label">Name <span class="text-danger">*</span></label>
							<input type="text" class="form-control">
						</div>
                    </div>
					<div class="modal-footer">
						<div class="d-flex justify-content-center">
                            <a href="javascript:void(0);" class="btn btn-light me-3" data-bs-dismiss="modal">Cancel</a>
                            <button type="submit" class="btn btn-primary">Create New</button>
                        </div>
					</div>
                </div>
            </div>
        </div>
        <!-- /Add Type -->

		<!-- Add Model -->
        <div class="modal fade" id="add_model">
            <div class="modal-dialog modal-dialog-centered modal-md">
                <div class="modal-content">
					<div class="modal-header">
						<h5 class="mb-0">Create Model</h5>
						<button type="button" class="btn-close custom-btn-close" data-bs-dismiss="modal" aria-label="Close">
							<i class="ti ti-x fs-16"></i>
						</button>
					</div>
                    <div class="modal-body pb-1">
						<div class="mb-3">
							<label class="form-label">Model <span class="text-danger">*</span></label>
							<input type="text" class="form-control">							
						</div>                  
                        <div class="mb-3">
							<label class="form-label">Brand <span class="text-danger">*</span></label>
							<select class="select">
								<option>Select</option>
								<option>Toyota</option>
								<option>Audi</option>
								<option>Lamborghini</option>
							</select>
						</div>                  
                        <div class="mb-3">
							<label class="form-label">Total Cars <span class="text-danger">*</span></label>
							<input type="text" class="form-control">
						</div>
                    </div>
					<div class="modal-footer">
						<div class="d-flex justify-content-center">
                            <a href="javascript:void(0);" class="btn btn-light me-3" data-bs-dismiss="modal">Cancel</a>
                            <button type="submit" class="btn btn-primary">Create New</button>
                        </div>
					</div>
                </div>
            </div>
        </div>
        <!-- /Add Model -->



		<!-- Select Seasonal Pricing -->
        <div class="modal fade" id="select_price">
            <div class="modal-dialog modal-dialog-centered modal-lg">
                <div class="modal-content">
					<div class="modal-header">
						<h5 class="modal-title mb-0">Seasonal Pricing</h5>
						<button type="button" class="btn-close custom-btn-close" data-bs-dismiss="modal" aria-label="Close">
							<i class="ti ti-x fs-16"></i>
						</button>
					</div>
					<form action="add-car.html">
						<div class="modal-body pb-1">
							<div class="d-flex align-items-center justify-content-between flex-wrap bg-white gap-3 border br-5 p-20 mb-3">
								<div>
									<h6 class="fs-14 fw-semibold d-inline-flex align-items-center mb-1">Halloween<span class="badge bg-secondary-transparent ms-2">01 Oct 2025 - 31 Oct 2025 </span></h6>
									<div class="d-flex align-items-center gap-2 flex-wrap">
										<p class="fs-13 fw-medium border-end pe-2 mb-0">Daily Rate : <span class="text-gray-9">$200</span></p>
										<p class="fs-13 fw-medium border-end pe-2 mb-0">Weekly Rate : <span class="text-gray-9">$1400</span></p>
										<p class="fs-13 fw-medium border-end pe-2 mb-0">Monthly Rate : <span class="text-gray-9">$4800</span></p>
										<p class="fs-13 fw-medium mb-0 pe-2 mb-0">Late Fee : <span class="text-gray-9">$200</span></p>
									</div>
								</div>
								<div class="d-flex align-items-center icon-list delivery-add">
									<a href="javascript:void(0);"><i class="ti ti-plus plus-active"></i><i class="ti ti-check check-active"></i></a>
								</div>
							</div>  
							<div class="d-flex align-items-center justify-content-between flex-wrap bg-white gap-3 border br-5 p-20 mb-3">
								<div>
									<h6 class="fs-14 fw-semibold d-inline-flex align-items-center mb-1">Easter<span class="badge bg-secondary-transparent ms-2">01 Apr 2025 - 30 Apr 2025 </span></h6>
									<div class="d-flex align-items-center gap-2 flex-wrap">
										<p class="fs-13 fw-medium border-end pe-2 mb-0">Daily Rate : <span class="text-gray-9">$220</span></p>
										<p class="fs-13 fw-medium border-end pe-2 mb-0">Weekly Rate : <span class="text-gray-9">$1540</span></p>
										<p class="fs-13 fw-medium border-end pe-2 mb-0">Monthly Rate : <span class="text-gray-9">$6600</span></p>
										<p class="fs-13 fw-medium mb-0 pe-2 mb-0">Late Fee : <span class="text-gray-9">$250</span></p>
									</div>
								</div>
								<div class="d-flex align-items-center icon-list delivery-add">
									<a href="javascript:void(0);"><i class="ti ti-plus plus-active"></i><i class="ti ti-check check-active"></i></a>
								</div>
							</div>  
							<div class="d-flex align-items-center justify-content-between flex-wrap bg-white gap-3 border br-5 p-20 mb-3">
								<div>
									<h6 class="fs-14 fw-semibold d-inline-flex align-items-center mb-1">New Year<span class="badge bg-secondary-transparent ms-2">01 Jan 2025 - 15 Jan 2025</span></h6>
									<div class="d-flex align-items-center gap-2 flex-wrap">
										<p class="fs-13 fw-medium border-end pe-2 mb-0">Daily Rate : <span class="text-gray-9">$240</span></p>
										<p class="fs-13 fw-medium border-end pe-2 mb-0">Weekly Rate : <span class="text-gray-9">$1680</span></p>
										<p class="fs-13 fw-medium border-end pe-2 mb-0">Monthly Rate : <span class="text-gray-9">$6720</span></p>
										<p class="fs-13 fw-medium mb-0 pe-2 mb-0">Late Fee : <span class="text-gray-9">$150</span></p>
									</div>
								</div>
								<div class="d-flex align-items-center icon-list delivery-add">
									<a href="javascript:void(0);"><i class="ti ti-plus plus-active"></i><i class="ti ti-check check-active"></i></a>
								</div>
							</div>  
							<div class="d-flex align-items-center justify-content-between flex-wrap bg-white gap-3 border br-5 p-20 mb-3">
								<div>
									<h6 class="fs-14 fw-semibold d-inline-flex align-items-center mb-1">Christmas<span class="badge bg-secondary-transparent ms-2">01 Dec 2024 - 31 Dec 2025</span></h6>
									<div class="d-flex align-items-center gap-2 flex-wrap">
										<p class="fs-13 fw-medium border-end pe-2 mb-0">Daily Rate : <span class="text-gray-9">$250</span></p>
										<p class="fs-13 fw-medium border-end pe-2 mb-0">Weekly Rate : <span class="text-gray-9">$1750</span></p>
										<p class="fs-13 fw-medium border-end pe-2 mb-0">Monthly Rate : <span class="text-gray-9">$7000</span></p>
										<p class="fs-13 fw-medium mb-0 pe-2 mb-0">Late Fee : <span class="text-gray-9">$300</span></p>
									</div>
								</div>
								<div class="d-flex align-items-center icon-list delivery-add">
									<a href="javascript:void(0);"><i class="ti ti-plus plus-active"></i><i class="ti ti-check check-active"></i></a>
								</div>
							</div>  
						</div>
						<div class="modal-footer">
							<div class="d-flex justify-content-center">
								<a href="javascript:void(0);" class="btn btn-light me-3" data-bs-dismiss="modal">Cancel</a>
								<button type="submit" class="btn btn-primary">Save</button>
							</div>
						</div>
					</form>
                </div>
            </div>
        </div>
        <!-- /Select Seasonal Pricing -->

		<!-- Select Seasonal Pricing -->
        <div class="modal fade" id="select_insurance">
            <div class="modal-dialog modal-dialog-centered modal-md">
                <div class="modal-content">
					<div class="modal-header">
						<h5 class="modal-title mb-0">Insurance</h5>
						<button type="button" class="btn-close custom-btn-close" data-bs-dismiss="modal" aria-label="Close">
							<i class="ti ti-x fs-16"></i>
						</button>
					</div>
					<form action="add-car.html">
						<div class="modal-body pb-1">
							<div class="d-flex align-items-center justify-content-between flex-wrap bg-white gap-3 border br-5 p-20 mb-3">
								<div>
									<h6 class="fs-14 fw-semibold d-inline-flex align-items-center mb-1">Full Premium Insurance</h6>
									<div class="d-flex align-items-center gap-2 flex-wrap">
										<p class="fs-13 fw-medium border-end pe-2 mb-0">Price : <span class="text-gray-9">$200</span></p>
										<p class="fs-13 fw-medium mb-0">Benefits : <span class="text-gray-9">4</span>
											<i class="ti ti-info-circle-filled text-gray-5 ms-1" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-original-title="No additional charges for emergency roadside services"></i>
										</p>
									</div>
								</div>
								<div class="d-flex align-items-center icon-list delivery-add">
									<a href="javascript:void(0);"><i class="ti ti-plus plus-active"></i><i class="ti ti-check check-active"></i></a>
								</div>
							</div> 
							<div class="d-flex align-items-center justify-content-between flex-wrap bg-white gap-3 border br-5 p-20 mb-3">
								<div>
									<h6 class="fs-14 fw-semibold d-inline-flex align-items-center mb-1">Roadside Assistance</h6>
									<div class="d-flex align-items-center gap-2 flex-wrap">
										<p class="fs-13 fw-medium border-end pe-2 mb-0">Price : <span class="text-gray-9">$250</span></p>
										<p class="fs-13 fw-medium mb-0">Benefits : <span class="text-gray-9">6</span>
											<i class="ti ti-info-circle-filled text-gray-5 ms-1" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-original-title="No additional charges for emergency roadside services"></i>
										</p>
									</div>
								</div>
								<div class="d-flex align-items-center icon-list delivery-add">
									<a href="javascript:void(0);"><i class="ti ti-plus plus-active"></i><i class="ti ti-check check-active"></i></a>
								</div>
							</div>  
							<div class="d-flex align-items-center justify-content-between flex-wrap bg-white gap-3 border br-5 p-20 mb-3">
								<div>
									<h6 class="fs-14 fw-semibold d-inline-flex align-items-center mb-1">Liability Insurance</h6>
									<div class="d-flex align-items-center gap-2 flex-wrap">
										<p class="fs-13 fw-medium border-end pe-2 mb-0">Price : <span class="text-gray-9">$150</span></p>
										<p class="fs-13 fw-medium mb-0">Benefits : <span class="text-gray-9">4</span>
											<i class="ti ti-info-circle-filled text-gray-5 ms-1" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-original-title="No additional charges for emergency roadside services"></i>
										</p>
									</div>
								</div>
								<div class="d-flex align-items-center icon-list delivery-add">
									<a href="javascript:void(0);"><i class="ti ti-plus plus-active"></i><i class="ti ti-check check-active"></i></a>
								</div>
							</div>  
							<div class="d-flex align-items-center justify-content-between flex-wrap bg-white gap-3 border br-5 p-20 mb-3">
								<div>
									<h6 class="fs-14 fw-semibold d-inline-flex align-items-center mb-1">Personal Accident Insurance</h6>
									<div class="d-flex align-items-center gap-2 flex-wrap">
										<p class="fs-13 fw-medium border-end pe-2 mb-0">Price : <span class="text-gray-9">$300</span></p>
										<p class="fs-13 fw-medium mb-0">Benefits : <span class="text-gray-9">5</span>
											<i class="ti ti-info-circle-filled text-gray-5 ms-1" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-original-title="No additional charges for emergency roadside services"></i>
										</p>
									</div>
								</div>
								<div class="d-flex align-items-center icon-list delivery-add">
									<a href="javascript:void(0);"><i class="ti ti-plus plus-active"></i><i class="ti ti-check check-active"></i></a>
								</div>
							</div>  
						</div>
						<div class="modal-footer">
							<div class="d-flex justify-content-center">
								<a href="javascript:void(0);" class="btn btn-light me-3" data-bs-dismiss="modal">Cancel</a>
								<button type="submit" class="btn btn-primary">Save</button>
							</div>
						</div>
					</form>
                </div>
            </div>
        </div>
        <!-- /Select Seasonal Pricing -->

		<!-- Edit Insurance -->
		<div class="modal fade" id="edit_insurance">
			<div class="modal-dialog modal-dialog-centered">
				<div class="modal-content">
					<div class="modal-header">
						<h4 class="mb-0">Edit Insurance</h4>
						<button type="button" class="btn-close custom-btn-close" data-bs-dismiss="modal" aria-label="Close">
							<i class="ti ti-x fs-16"></i>
						</button>
					</div>
					<form action="add-car.html">
						<div class="modal-body">
							<div class="mb-3">
								<label class="form-label">Insurane Name <span class="text-danger"> *</span></label>
								<input type="text" class="form-control" value="Full Premium Insurance">
							</div>
							<div class="mb-3">
								<label class="form-label">Price Type <span class="text-danger"> *</span></label>
								<div class="d-flex align-items-center">
									<div class="form-check me-3">
										<input class="form-check-input" type="radio" name="Radio" id="Radio-sm" checked>
										<label class="form-check-label" for="Radio-sm">
											Daily
										</label>
									</div>
									<div class="form-check me-3">
										<input class="form-check-input" type="radio" name="Radio" id="Radio-sm2">
										<label class="form-check-label" for="Radio-sm2">
											Fixed
										</label>
									</div>
									<div class="form-check">
										<input class="form-check-input" type="radio" name="Radio" id="Radio-sm3">
										<label class="form-check-label" for="Radio-sm3">
											Percentage
										</label>
									</div>
								</div>
							</div>
							<div class="mb-3">
								<label class="form-label">Price <span class="text-danger"> *</span></label>
								<input type="text" class="form-control" value="$200">
							</div>
							<div class="add-insurance-benifit-2">
								<div class="mb-1">
									<label class="form-label">Benefit <span class="text-danger"> *</span></label>
									<input type="text" class="form-control" value="No additional charges for emergency roadside services.">
								</div>
							</div>
							<a href="javascript:void(0);" class="d-inline-flex align-items-center text-info add-new-benifit-2"><i class="ti ti-plus me-1"></i>Add New</a>
						</div>
						<div class="modal-footer">
							<div class="d-flex justify-content-center">
								<a href="javascript:void(0);" class="btn btn-light me-3" data-bs-dismiss="modal">Cancel</a>
								<button type="submit" class="btn btn-primary">Save Changes</button>
							</div>		
						</div>
					</form>
				</div>
			</div>
		</div>
		<!-- /Edit  Insurance -->

		<!-- Edit Seasonal Pricing -->
        <div class="modal fade" id="edit_seasonal_price">
            <div class="modal-dialog modal-dialog-centered modal-md">
                <div class="modal-content">
					<div class="modal-header">
						<h5 class="modal-title mb-0">Edit Seasonal Pricing</h5>
						<button type="button" class="btn-close custom-btn-close" data-bs-dismiss="modal" aria-label="Close">
							<i class="ti ti-x fs-16"></i>
						</button>
					</div>
					<form action="add-car.html">
						<div class="modal-body pb-1">
							<div class="row">
								<div class="col-md-12">
									<div class="mb-3">
										<label class="form-label">Season Name <span class="text-danger">*</span></label>	
										<input type="text" class="form-control" value="Halloween">
									</div>   
								</div>
								<div class="col-md-12">            
									<div class="mb-3">
										<label class="form-label">Start Date <span class="text-danger">*</span></label>
										<div class="input-icon-end position-relative">
											<input type="text" class="form-control datetimepicker" placeholder="dd/mm/yyyy" value="28-01-2025">
											<span class="input-icon-addon">
												<i class="ti ti-calendar"></i>
											</span>
										</div>
									</div>
								</div>
								<div class="col-md-12">            
									<div class="mb-3">
										<label class="form-label">End Date <span class="text-danger">*</span></label>
										<div class="input-icon-end position-relative">
											<input type="text" class="form-control datetimepicker" placeholder="dd/mm/yyyy" value="02-02-2025">
											<span class="input-icon-addon">
												<i class="ti ti-calendar"></i>
											</span>
										</div>
									</div>
								</div>
								<div class="col-md-6">            
									<div class="mb-3">
										<label class="form-label">Daily Rate <span class="text-danger">*</span></label>	
										<input type="text" class="form-control " value="50">
									</div>
								</div>
								<div class="col-md-6">            
									<div class="mb-3">
										<label class="form-label">Weekly Rate <span class="text-danger">*</span></label>	
										<input type="text" class="form-control " value="100">
									</div>
								</div>
								<div class="col-md-6">            
									<div class="mb-3">
										<label class="form-label">Monthly Rate <span class="text-danger">*</span></label>	
										<input type="text" class="form-control " value="150">
									</div>
								</div>
								<div class="col-md-6">            
									<div class="mb-3">
										<label class="form-label">Late Fees <span class="text-danger">*</span></label>	
										<input type="text" class="form-control " value="200">
									</div>
								</div>
							</div>  
						</div>
						<div class="modal-footer">
							<div class="d-flex justify-content-center">
								<a href="javascript:void(0);" class="btn btn-light me-3" data-bs-dismiss="modal">Cancel</a>
								<button type="submit" class="btn btn-primary">Save Changes</button>
							</div>
						</div>
					</form>
                </div>
            </div>
        </div>
        <!-- /Edit Seasonal Pricing -->

		<!-- Delete Pricing -->
        <div class="modal fade" id="delete_price">
            <div class="modal-dialog modal-dialog-centered modal-sm">
                <div class="modal-content">
                    <div class="modal-body text-center">
						<span class="avatar avatar-lg bg-transparent-danger rounded-circle text-danger mb-3">
							<i class="ti ti-trash-x fs-26"></i>
						</span>
						<h4 class="mb-1">Delete Pricing</h4>
						<p class="mb-3">Are you sure you want to delete Pricing?</p>
						<div class="d-flex justify-content-center">
							<a href="javascript:void(0);" class="btn btn-light me-3" data-bs-dismiss="modal">Cancel</a>
							<a href="add-car.html" class="btn btn-primary">Yes, Delete</a>
						</div>
                    </div>
                </div>
            </div>
        </div>
        <!-- /Delete Pricing -->

		<!-- Delete Insurance -->
        <div class="modal fade" id="delete_insurance">
            <div class="modal-dialog modal-dialog-centered modal-sm">
                <div class="modal-content">
                    <div class="modal-body text-center">
						<span class="avatar avatar-lg bg-transparent-danger rounded-circle text-danger mb-3">
							<i class="ti ti-trash-x fs-26"></i>
						</span>
						<h4 class="mb-1">Delete Insurance</h4>
						<p class="mb-3">Are you sure you want to delete Insurance?</p>
						<div class="d-flex justify-content-center">
							<a href="javascript:void(0);" class="btn btn-light me-3" data-bs-dismiss="modal">Cancel</a>
							<a href="add-car.html" class="btn btn-primary">Yes, Delete</a>
						</div>
                    </div>
                </div>
            </div>
        </div>
        <!-- /Delete Insurance -->

		<!-- Edit Pricing -->
        <div class="modal fade" id="edit_price">
            <div class="modal-dialog modal-dialog-centered modal-md">
                <div class="modal-content">
					<div class="modal-header">
						<h5 class="modal-title mb-0">Edit Pricing</h5>
						<button type="button" class="btn-close custom-btn-close" data-bs-dismiss="modal" aria-label="Close">
							<i class="ti ti-x fs-16"></i>
						</button>
					</div>
					<form action="add-car.html">
						<div class="modal-body pb-1">
							<table class="table custom-table1">
								<thead class="thead-white">
									<tr>
										<th class="py-0">Extra Features</th>
										<th class="py-0">Pricing</th>
									</tr>
								</thead>
								<tbody>
									<tr>
										<td class="fw-medium text-gray-9">Navigation</td>
										<td>		
											<div class="d-flex align-items-center">								
												<select class="select">
													<option>One Time</option>
													<option>Per Day</option>
												</select>
												<div class="input-icon-start position-relative w-100 ms-2">
													<span class="input-icon-addon">
														<i class="ti ti-currency-dollar"></i>
													</span>
													<input type="text" class="form-control" value="90">
												</div>
											</div>	
										</td>
									</tr>	
									<tr>
										<td class="fw-medium text-gray-9">Satellite Radio</td>
										<td>		
											<div class="d-flex align-items-center">								
												<select class="select">
													<option>One Time</option>
													<option selected>Per Day</option>
												</select>
												<div class="input-icon-start position-relative w-100 ms-2">
													<span class="input-icon-addon">
														<i class="ti ti-currency-dollar"></i>
													</span>
													<input type="text" class="form-control" value="25">
												</div>
											</div>	
										</td>
									</tr>		
									<tr>
										<td class="fw-medium text-gray-9">Roadside Assistance</td>
										<td>		
											<div class="d-flex align-items-center">								
												<select class="select">
													<option>One Time</option>
													<option selected>Per Day</option>
												</select>
												<div class="input-icon-start position-relative w-100 ms-2">
													<span class="input-icon-addon">
														<i class="ti ti-currency-dollar"></i>
													</span>
													<input type="text" class="form-control" value="47">
												</div>
											</div>	
										</td>
									</tr>		
									<tr>
										<td class="fw-medium text-gray-9">Express Check-in/out</td>
										<td>		
											<div class="d-flex align-items-center">								
												<select class="select">
													<option>One Time</option>
													<option selected>Per Day</option>
												</select>
												<div class="input-icon-start position-relative w-100 ms-2">
													<span class="input-icon-addon">
														<i class="ti ti-currency-dollar"></i>
													</span>
													<input type="text" class="form-control" value="75">
												</div>
											</div>	
										</td>
									</tr>		
									<tr>
										<td class="fw-medium text-gray-9">Child Safety Seats</td>
										<td>		
											<div class="d-flex align-items-center">								
												<select class="select">
													<option>One Time</option>
													<option selected>Per Day</option>
												</select>
												<div class="input-icon-start position-relative w-100 ms-2">
													<span class="input-icon-addon">
														<i class="ti ti-currency-dollar"></i>
													</span>
													<input type="text" class="form-control" value="22">
												</div>
											</div>	
										</td>
									</tr>		
									<tr>
										<td class="fw-medium text-gray-9">Roadside Assistance</td>
										<td>		
											<div class="d-flex align-items-center">								
												<select class="select">
													<option>One Time</option>
													<option selected>Per Day</option>
												</select>
												<div class="input-icon-start position-relative w-100 ms-2">
													<span class="input-icon-addon">
														<i class="ti ti-currency-dollar"></i>
													</span>
													<input type="text" class="form-control" value="48">
												</div>
											</div>	
										</td>
									</tr>								
								</tbody>
							</table>
						</div>
						<div class="modal-footer">
							<div class="d-flex justify-content-center">
								<a href="javascript:void(0);" class="btn btn-light me-3" data-bs-dismiss="modal">Cancel</a>
								<button type="submit" class="btn btn-primary">Save Changes</button>
							</div>
						</div>
					</form>
                </div>
            </div>
        </div>
        <!-- /Edit Pricing -->

		<!-- Add New Damage -->
        <div class="modal fade" id="add-damage">
            <div class="modal-dialog modal-dialog-centered modal-md">
                <div class="modal-content">
					<div class="modal-header">
						<h5 class="modal-title mb-0">Add New Damage</h5>
						<button type="button" class="btn-close custom-btn-close" data-bs-dismiss="modal" aria-label="Close">
							<i class="ti ti-x fs-16"></i>
						</button>
					</div>
					<form id="addDamageForm">
						<div class="modal-body pb-1">
							<div class="mb-3">
								<label class="form-label">Damage Location <span class="text-danger">*</span></label>	
								<select name="location" class="select" required>
									<option value="">Select</option>
									<option value="Interior">Interior</option>
									<option value="Exterior">Exterior</option>
								</select>
							</div>  
							<div class="mb-3">
								<label class="form-label">Damage Type <span class="text-danger">*</span></label>
								<select name="type" class="select" required>
									<option value="">Select</option>
									<option value="Scratch">Scratch</option>
									<option value="Dent">Dent</option>
									<option value="Crack">Crack</option>
									<option value="Clip">Clip</option>
								</select>
							</div> 
							<div class="mb-3">
								<label class="form-label">Description</label>
								<textarea name="description" class="form-control" rows="3"></textarea>
							</div> 
						</div>
						<div class="modal-footer">
							<div class="d-flex justify-content-center">
								<a href="javascript:void(0);" class="btn btn-light me-3" data-bs-dismiss="modal">Cancel</a>
								<button type="submit" class="btn btn-primary">Create New</button>
							</div>
						</div>
					</form>
                </div>
            </div>
        </div>
        <!-- /Add New Damage -->

		<!-- Edit Damage -->
        <div class="modal fade" id="edit-damage">
            <div class="modal-dialog modal-dialog-centered modal-md">
                <div class="modal-content">
					<div class="modal-header">
						<h5 class="modal-title mb-0">Edit Damage</h5>
						<button type="button" class="btn-close custom-btn-close" data-bs-dismiss="modal" aria-label="Close">
							<i class="ti ti-x fs-16"></i>
						</button>
					</div>
					<form action="add-car.html">
						<div class="modal-body pb-1">
							<div class="mb-3">
								<label class="form-label">Damage Location <span class="text-danger">*</span></label>	
								<select class="select">
									<option>Select</option>
									<option selected>Interior</option>
									<option>Exterior</option>
								</select>
							</div>  
							<div class="mb-3">
								<label class="form-label">Damage Type <span class="text-danger">*</span></label>
								<select class="select">
									<option>Select</option>
									<option selected>Scratch</option>
									<option>Dent</option>
									<option>Crack</option>
									<option>Clip</option>
								</select>
							</div> 
							<div class="mb-3">
								<label class="form-label">Description</label>
								<textarea class="form-control" rows="3">Cracks, scratches, or faded surfaces due to heat exposure.</textarea>
							</div> 
						</div>
						<div class="modal-footer">
							<div class="d-flex justify-content-center">
								<a href="javascript:void(0);" class="btn btn-light me-3" data-bs-dismiss="modal">Cancel</a>
								<button type="submit" class="btn btn-primary">Save Changes</button>
							</div>
						</div>
					</form>
                </div>
            </div>
        </div>
        <!-- /Edit Damage -->

		<!-- Delete Damage -->
        <div class="modal fade deletemodal" id="delete_damage">
            <div class="modal-dialog modal-dialog-centered modal-sm">
                <div class="modal-content">
                    <div class="modal-body text-center">
						<span class="avatar avatar-lg bg-transparent-danger rounded-circle text-danger mb-3">
							<i class="ti ti-trash-x fs-26"></i>
						</span>
						<h4 class="mb-1">Delete Damage</h4>
						<p class="mb-3">Are you sure you want to delete Damage?</p>
						<div class="d-flex justify-content-center">
							<a href="javascript:void(0);" class="btn btn-light me-3" data-bs-dismiss="modal">Cancel</a>
							<button type="button" class="btn btn-primary" id="confirmDeleteDamage">Yes, Delete</button>
						</div>
                    </div>
                </div>
            </div>
        </div>
        <!-- /Delete Damage -->

		<!-- Create FAQ -->
        <div class="modal fade" id="add-faq">
            <div class="modal-dialog modal-dialog-centered modal-md">
                <div class="modal-content">
					<div class="modal-header">
						<h5 class="modal-title mb-0">Create FAQ</h5>
						<button type="button" class="btn-close custom-btn-close" data-bs-dismiss="modal" aria-label="Close">
							<i class="ti ti-x fs-16"></i>
						</button>
					</div>
					<form id="addFaqForm">
						<div class="modal-body pb-1">
							<div class="mb-3">
								<label class="form-label">Question <span class="text-danger">*</span></label>	
								<input type="text" name="question" class="form-control" required>
							</div>  
							<div class="mb-3">
								<label class="form-label">Answer <span class="text-danger">*</span></label>
								<textarea name="answer" class="form-control" rows="3" required></textarea>
							</div> 
						</div>
						<div class="modal-footer">
							<div class="d-flex justify-content-center">
								<a href="javascript:void(0);" class="btn btn-light me-3" data-bs-dismiss="modal">Cancel</a>
								<button type="submit" class="btn btn-primary">Create New</button>
							</div>
						</div>
					</form>
                </div>
            </div>
        </div>
        <!-- /Create FAQ -->

	</div>
	<!-- /Main Wrapper -->

	<!-- jQuery -->
	<script src="assets/js/jquery-3.7.1.min.js"></script>

	<!-- Feather Icon JS -->
	<script src="assets/js/feather.min.js"></script>

	<!-- Bootstrap Core JS -->
	<script src="assets/js/bootstrap.bundle.min.js"></script>

	<!-- Slimscroll JS -->
	<script src="assets/js/jquery.slimscroll.min.js"></script>

	<!-- Sticky Sidebar JS -->
	<script src="assets/plugins/theia-sticky-sidebar/ResizeSensor.js"></script>
	<script src="assets/plugins/theia-sticky-sidebar/theia-sticky-sidebar.js"></script>

    <!-- Daterangepikcer JS -->
	<script src="assets/js/moment.min.js"></script>
	<script src="assets/plugins/daterangepicker/daterangepicker.js"></script>
	<script src="assets/js/bootstrap-datetimepicker.min.js"></script>

	<!-- Select2 JS -->
	<script src="assets/plugins/select2/js/select2.min.js"></script>

	<!-- Bootstrap Tagsinput JS -->
    <script src="assets/plugins/bootstrap-tagsinput/bootstrap-tagsinput.min.js"></script>

	<!-- Fancybox JS -->
	<script src="assets/plugins/fancybox/jquery.fancybox.min.js"></script>	

	<!-- Custom JS -->
	<script src="assets/js/script.js"></script>

	<!-- Damage and FAQ Management JS -->
	<script>
	// Load existing data from PHP session
	<?php
	$existingDamages = isset($_SESSION['damages']) ? $_SESSION['damages'] : [];
	$existingFaqs = isset($_SESSION['faqs']) ? $_SESSION['faqs'] : [];
	?>
	var existingDamages = <?php echo json_encode($existingDamages); ?>;
	var existingFaqs = <?php echo json_encode($existingFaqs); ?>;
	
	$(document).ready(function() {
		// Initialize counters
		let damageCount = existingDamages.length;
		let faqCount = existingFaqs.length;
		
		// Load existing damages
		existingDamages.forEach(function(damage) {
			addDamageToContainer(damage);
		});
		
	// FAQ UI commented out; skip rendering existing FAQs
		
		// Update counters
		$('#damageCount').text('Total Damages : ' + damageCount);
	// $('#faqCount').text('Total FAQ : ' + faqCount); // commented out with FAQ section
		
		// Handle Damage Form Submission
		$('#addDamageForm').on('submit', function(e) {
			e.preventDefault();
			
			const formData = new FormData(this);
			formData.append('action', 'add_damage');
			
			$.ajax({
				url: 'process-damage-faq.php',
				type: 'POST',
				data: formData,
				processData: false,
				contentType: false,
				success: function(response) {
					if (response.success) {
						// Add damage to the container
						addDamageToContainer(response.damage);
						damageCount++;
						$('#damageCount').text('Total Damages : ' + damageCount);
						
						// Reset form and close modal
						$('#addDamageForm')[0].reset();
						$('#add-damage').modal('hide');
						
						// Show success message
						alert(response.message);
					} else {
						alert('Error: ' + response.message);
					}
				},
				error: function() {
					alert('Error occurred while adding damage');
				}
			});
		});
		
	// FAQ add handler commented out per requirements
		
		// Function to add damage to container
		function addDamageToContainer(damage) {
			const badgeClass = damage.location === 'Interior' ? 'bg-pink-transparent' : 'bg-secondary-transparent';
			const damageHtml = `
				<div class="bg-white p-20 br-5 border mb-2" data-damage-id="${damage.id}">
					<div class="row align-items-center row-gap-3">
						<div class="col-xxl-8 col-md-7">
							<div class="d-flex align-items-center gap-2 mb-1">
								<h6 class="fs-14 fw-medium">${damage.type}</h6>
								<span class="badge ${badgeClass} badge-sm">${damage.location}</span>
							</div>
							<p class="fs-13">${damage.description || 'No description provided'}</p>
						</div>
						<div class="col-xxl-4 col-md-5">
							<div class="d-flex align-items-center justify-content-md-end gap-2 flex-wrap">
								<p class="mb-0">Added on : ${damage.date}</p>
								<div class="icon-list d-flex align-items-center">
									<a href="javascript:void(0);" class="edit-icon me-2" data-bs-toggle="modal" data-bs-target="#edit-damage"><i class="ti ti-edit"></i></a>
									<a href="javascript:void(0);" class="trash-icon" onclick="deleteDamage('${damage.id}')"><i class="ti ti-trash"></i></a>
								</div>
							</div>
						</div>
					</div>															
				</div>
			`;
			$('#damagesContainer').append(damageHtml);
		}
		
		// Function to add FAQ to container
	// FAQ UI function commented out
		
		// Function to delete damage
		window.deleteDamage = function(damageId) {
			// Store the damage ID to delete and show the confirmation modal
			window.damageToDelete = damageId;
			$('#delete_damage').modal('show');
		};
		
		// Function to actually delete damage (called from modal confirmation)
		function performDeleteDamage(damageId) {
			const formData = new FormData();
			formData.append('action', 'delete_damage');
			formData.append('damage_id', damageId);
			
			$.ajax({
				url: 'process-damage-faq.php',
				type: 'POST',
				data: formData,
				processData: false,
				contentType: false,
				success: function(response) {
					if (response.success) {
						$(`[data-damage-id="${damageId}"]`).remove();
						damageCount--;
						$('#damageCount').text('Total Damages : ' + damageCount);
						alert(response.message);
					} else {
						alert('Error: ' + response.message);
					}
				},
				error: function() {
					alert('Error occurred while deleting damage');
				}
			});
		}
		
		// Function to delete FAQ
	// window.deleteFaq = function(faqId) { /* commented out */ };
		
		// Handle confirm delete damage button click
		$('#confirmDeleteDamage').on('click', function() {
			// This will be triggered when the modal's "Yes, Delete" button is clicked
			if (window.damageToDelete) {
				performDeleteDamage(window.damageToDelete);
				window.damageToDelete = null; // Reset
				$('#delete_damage').modal('hide'); // Close the modal
			}
		});

		// Features & Amenities Section Functionality
		// Handle "Check All Features" checkbox
		$('#select-all-features').on('change', function() {
			const isChecked = $(this).is(':checked');
			$('.feature-checkbox').prop('checked', isChecked);
		});

		// Handle individual feature checkboxes
		$('.feature-checkbox').on('change', function() {
			const totalFeatures = $('.feature-checkbox').length;
			const checkedFeatures = $('.feature-checkbox:checked').length;
			
			// Update "Check All" checkbox state
			if (checkedFeatures === 0) {
				$('#select-all-features').prop('indeterminate', false).prop('checked', false);
			} else if (checkedFeatures === totalFeatures) {
				$('#select-all-features').prop('indeterminate', false).prop('checked', true);
			} else {
				$('#select-all-features').prop('indeterminate', true);
			}
		});

		// Extra Services Section Functionality
		// Handle service checkbox changes
		$('.service-checkbox').on('change', function() {
			const $serviceItem = $(this).closest('.form-checkbox');
			if ($(this).is(':checked')) {
				$serviceItem.addClass('active');
			} else {
				$serviceItem.removeClass('active');
			}
		});

	// Pricing type checkboxes removed; only Daily Price is used

		// Form Validation and Submission
	$('#addCarForm').on('submit', function(e) {
			// Validate required fields
			const requiredFields = $(this).find('[required]');
			let isValid = true;
			
			requiredFields.each(function() {
				if (!$(this).val()) {
					$(this).addClass('is-invalid');
					isValid = false;
				} else {
					$(this).removeClass('is-invalid');
				}
			});

			if (!isValid) {
				e.preventDefault();
				return false;
			}

			// Collect form data for AJAX submission
			const formData = new FormData(this);
			
			// Add features and services as JSON
			const selectedFeatures = [];
			$('.feature-checkbox:checked').each(function() {
				selectedFeatures.push($(this).val());
			});
			formData.append('features_amenities', JSON.stringify(selectedFeatures));

			const selectedServices = [];
			$('.service-checkbox:checked').each(function() {
				selectedServices.push($(this).val());
			});
			formData.append('extra_services', JSON.stringify(selectedServices));

			// Add pricing types
			// pricing_types not used; ensure backend tolerates empty
			formData.append('pricing_types', JSON.stringify(['daily']));

			// Add damages and FAQs
			// Don't send damages via JavaScript - let the backend use $_SESSION['damages']
			// formData.append('damages', JSON.stringify(existingDamages));
			// FAQ section commented out; still send empty array for backend compatibility
			formData.append('faqs', JSON.stringify([]));
			formData.append('ajax_request', '1');

			// Submit form via AJAX
			const $btn = $('#saveCarBtn');
			$btn.prop('disabled', true);
			$.ajax({
				url: $(this).attr('action'),
				type: 'POST',
				dataType: 'json',
				data: formData,
				processData: false,
				contentType: false,
				success: function(response) {
					if (response.success) {
						alert('Car added successfully!');
						window.location.href = 'cars.php';
					} else {
						alert('Error: ' + response.message);
					}
				},
				error: function() {
					alert('Error occurred while saving the car.');
				},
				complete: function() {
					$btn.prop('disabled', false);
				}
			});

			e.preventDefault();
			return false;
		});

		// Real-time validation
		$('input[required], select[required], textarea[required]').on('blur', function() {
			if (!$(this).val()) {
				$(this).addClass('is-invalid');
			} else {
				$(this).removeClass('is-invalid');
			}
		});

	// No price validation enforced

		// Initialize form state
		$(document).ready(function() {
			// Set initial state for service checkboxes
			$('.service-checkbox:checked').each(function() {
				$(this).closest('.form-checkbox').addClass('active');
			});

			// Set initial state for feature checkboxes
			const totalFeatures = $('.feature-checkbox').length;
			const checkedFeatures = $('.feature-checkbox:checked').length;
			
			if (checkedFeatures === 0) {
				$('#select-all-features').prop('indeterminate', false).prop('checked', false);
			} else if (checkedFeatures === totalFeatures) {
				$('#select-all-features').prop('indeterminate', false).prop('checked', true);
			} else {
				$('#select-all-features').prop('indeterminate', true);
			}
		});
	});
	</script>

</body>

</html>