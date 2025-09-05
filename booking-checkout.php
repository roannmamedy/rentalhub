<?php
require_once __DIR__ . '/includes/booking.php';

$carId = isset($_GET['car_id']) ? (int)$_GET['car_id'] : (int)($_SESSION['booking']['car']['id'] ?? 0);
if (!ensure_car_in_booking($carId)) {
  http_response_code(404);
  echo 'Car not found';
  exit;
}

$b = booking_session();
$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $rent_type = $_POST['rent_type'] ?? 'delivery';
  // Default to daily; honor posted or preselected
  $booking_type = $_POST['booking_type'] ?? ($b['itinerary']['booking_type'] ?? 'daily');
  if ($rent_type === 'pickup') {
    $pickup_location = trim($_POST['pickup_location'] ?? '');
    $dropoff_location = trim($_POST['pickup_return_location'] ?? '');
    $same = isset($_POST['pickup_return_same_location']);
    if ($same) $dropoff_location = $pickup_location;
  } else {
    $pickup_location = trim($_POST['delivery_location'] ?? '');
    $dropoff_location = trim($_POST['delivery_return_location'] ?? '');
    $same = isset($_POST['return_same_location']);
    if ($same) $dropoff_location = $pickup_location;
  }
  $pickup_date = trim($_POST['pickup_date'] ?? '');
  $pickup_time = trim($_POST['pickup_time'] ?? '');
  $dropoff_date = trim($_POST['dropoff_date'] ?? '');
  $dropoff_time = trim($_POST['dropoff_time'] ?? '');

  if ($pickup_date === '' || $dropoff_date === '') {
    $errors[] = 'Please select pickup and dropoff dates.';
  }

  if (!$errors) {
    $b['itinerary'] = [
      'rent_type' => $rent_type,
      'booking_type' => $booking_type,
      'pickup_location' => $pickup_location,
      'dropoff_location' => $dropoff_location,
      'pickup_date' => $pickup_date,
      'pickup_time' => $pickup_time,
      'dropoff_date' => $dropoff_date,
      'dropoff_time' => $dropoff_time,
      'return_same_location' => ($rent_type==='delivery') ? $same : null,
      'pickup_return_same_location' => ($rent_type==='pickup') ? $same : null,
    ];
    $b['totals']['days'] = calculate_days($pickup_date,$pickup_time,$dropoff_date,$dropoff_time);
    calculate_totals($b);
    set_booking_session($b);
    redirect_to('booking-addon.php');
  }
}

$car = $b['car'];
$imgs = get_car_images((int)$car['id']);

// If arriving with a booking_type via query (e.g., from listing-details), remember it for when the form posts
if (isset($_GET['booking_type']) && !isset($b['itinerary']['booking_type'])) {
  $bt = strtolower(trim($_GET['booking_type']));
  if (in_array($bt, ['daily','weekly','monthly','yearly'], true)) {
    $b['itinerary'] = $b['itinerary'] ?? [];
    $b['itinerary']['booking_type'] = $bt;
    set_booking_session($b);
  }
}

// Pricing context for UI
$rates = [
  'daily'   => (float)($car['daily_price']   ?? 0),
  'weekly'  => (float)($car['weekly_price']  ?? 0),
  'monthly' => (float)($car['monthly_price'] ?? 0),
  'yearly'  => (float)($car['yearly_price']  ?? 0),
];
$labels = [ 'daily' => 'Daily', 'weekly' => 'Weekly', 'monthly' => 'Monthly', 'yearly' => 'Yearly' ];
$bookingTypeSel = $b['itinerary']['booking_type'] ?? 'daily';
if (!in_array($bookingTypeSel, ['daily','weekly','monthly','yearly'], true)) $bookingTypeSel = 'daily';
$daysPreview = (int)($b['totals']['days'] ?? 1); if ($daysPreview < 1) $daysPreview = 1;
// Estimated Total should start from the car's daily price as base
$estimatedBase = (float)($car['daily_price'] ?? 0);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0">
  <title>Checkout - <?= h($car['name']) ?></title>
  <link rel="shortcut icon" href="assets/img/favicon.png">
  <link rel="stylesheet" href="assets/css/bootstrap.min.css">
  <link rel="stylesheet" href="assets/plugins/fontawesome/css/fontawesome.min.css">
  <link rel="stylesheet" href="assets/plugins/fontawesome/css/all.min.css">
  <link rel="stylesheet" href="assets/plugins/boxicons/css/boxicons.min.css">
  <link rel="stylesheet" href="assets/css/bootstrap-datetimepicker.min.css">
  <link rel="stylesheet" href="assets/plugins/aos/aos.css">
  <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
  <div class="main-wrapper">
    <!-- Header -->
		<header class="header">
			<div class="container-fluid">
				<nav class="navbar navbar-expand-lg header-nav">
					<div class="navbar-header">
						<a id="mobile_btn" href="javascript:void(0);">
							<span class="bar-icon">
								<span></span>
								<span></span>
								<span></span>
							</span>
						</a>
						<a href="index.html" class="navbar-brand logo">
							<img src="assets/img/logo.svg" class="img-fluid" alt="Logo">
						</a>	
						<a href="index.html" class="navbar-brand logo-small">
							<img src="assets/img/logo-small.png" class="img-fluid" alt="Logo">
						</a>						
					</div>
					<div class="main-menu-wrapper">
						<div class="menu-header">
							<a href="index.html" class="menu-logo">
								<img src="assets/img/logo.svg" class="img-fluid" alt="Logo">
							</a>
							<a id="menu_close" class="menu-close" href="javascript:void(0);"> <i class="fas fa-times"></i></a>
						</div>
						<ul class="main-nav">
							<li class="has-submenu megamenu">
								<a href="index.html">Home <i class="fas fa-chevron-down"></i></a>
								<ul class="submenu mega-submenu">
									<li>
										<div class="megamenu-wrapper">
                                            <div class="row">
                                                <div class="col-lg-3">
                                                    <div class="single-demo">
                                                        <div class="demo-img">
                                                            <a href="index.html">
																<img src="assets/img/menu/home-01.svg" class="img-fluid " alt="img">
															</a>
                                                        </div>
                                                        <div class="demo-info">
                                                            <a href="index.html">Car Rental<span class="new">New</span> </a>
                                                        </div>
                                                    </div>
                                                </div>
												<div class="col-lg-3">
                                                    <div class="single-demo">
                                                        <div class="demo-img">
                                                            <a href="index-2.html">
																<img src="assets/img/menu/home-02.svg" class="img-fluid " alt="img">
															</a>
                                                        </div>
                                                        <div class="demo-info">
                                                            <a href="index-2.html">Car Rental 1<span class="hot">Hot</span> </a>
                                                        </div>
                                                    </div>
                                                </div>
												<div class="col-lg-3">
                                                    <div class="single-demo">
                                                        <div class="demo-img">
                                                            <a href="index-3.html">
																<img src="assets/img/menu/home-03.svg" class="img-fluid " alt="img">
															</a>
                                                        </div>
                                                        <div class="demo-info">
                                                            <a href="index-3.html">Bike Rental<span class="new">New</span> </a>
                                                        </div>
                                                    </div>
                                                </div>
												<div class="col-lg-3">
                                                    <div class="single-demo">
                                                        <div class="demo-img">
                                                            <a href="index-4.html">
																<img src="assets/img/menu/home-04.svg" class="img-fluid " alt="img">
															</a>
                                                        </div>
                                                        <div class="demo-info">
                                                            <a href="index-4.html">Yacht Rental<span class="new">New</span> </a>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
									</li>						
								</ul>
							</li>
							<li class="has-submenu">
								<a href="#">Listings <i class="fas fa-chevron-down"></i></a>
								<ul class="submenu">
								    <li><a href="listing-grid.html">Listing Grid</a></li>
								    <li><a href="listing-list.html">Listing List</a></li>
									<li><a href="listing-map.html">Listing With Map</a></li>						
								    <li><a href="listing-details.html">Listing Details</a></li>								
								</ul>
							</li>
							<li class="has-submenu active">
								<a href="#">Pages <i class="fas fa-chevron-down"></i></a>
								<ul class="submenu">
								    <li ><a href="about-us.html">About Us</a></li>
								    <li><a href="contact-us.html">Contact</a></li>
									<li class="has-submenu">
										<a href="javascript:void(0);">Authentication</a>
										<ul class="submenu">
											<li><a href="register.html">Sign Up</a></li>
											<li><a href="login.html">Sign In</a></li>
											<li><a href="forgot-password.html">Forgot Password</a></li>
											<li><a href="reset-password.html">Reset Password</a></li>
										</ul>
									</li>
									<li class="has-submenu active">
										<a href="javascript:void(0);">Booking</a>
										<ul class="submenu">
											<li class="active"><a href="booking-checkout.html">Booking Checkout</a></li>
											<li><a href="booking.html">Booking</a></li>
											<li><a href="invoice-details.html">Invoice Details</a></li>
										</ul>
									</li>
									<li class="has-submenu">
										<a href="javascript:void(0);">Error Page</a>
										<ul class="submenu">
											<li><a href="error-404.html">404 Error</a></li>
											<li><a href="error-500.html">500 Error</a></li>
										</ul>
									</li>
								    <li><a href="pricing.html">Pricing</a></li>
								    <li><a href="faq.html">FAQ</a></li>
								    <li><a href="gallery.html">Gallery</a></li>
								    <li><a href="our-team.html">Our Team</a></li>
								    <li><a href="testimonial.html">Testimonials</a></li>
									<li><a href="terms-condition.html">Terms & Conditions</a></li>
									<li><a href="privacy-policy.html">Privacy Policy</a></li>
									<li><a href="maintenance.html">Maintenance</a></li>
									<li><a href="coming-soon.html">Coming Soon</a></li>							
								</ul>
							</li>
							<li class="has-submenu">
								<a href="#">Blog <i class="fas fa-chevron-down"></i></a>
								<ul class="submenu">
								    <li><a href="blog-list.html">Blog List</a></li>
									<li><a href="blog-grid.html">Blog Grid</a></li>
									<li><a href="blog-details.html">Blog Details</a></li>																		
								</ul>
							</li>
							<li class="has-submenu">
								<a href="#">Dashboard <i class="fas fa-chevron-down"></i></a>
								<ul class="submenu">
									<li class="has-submenu">
										<a href="javascript:void(0);">User Dashboard</a>
										<ul class="submenu">
											<li><a href="user-dashboard.html">Dashboard</a></li>
											<li><a href="user-bookings.html">My Bookings</a></li>
											<li><a href="user-reviews.html">Reviews</a></li>
											<li><a href="user-wishlist.html">Wishlist</a></li>
											<li><a href="user-messages.html">Messages</a></li>
											<li><a href="user-wallet.html">My Wallet</a></li>
											<li><a href="user-payment.html">Payments</a></li>
											<li><a href="user-settings.html">Settings</a></li>			
										</ul>
									</li>		
									<li class="has-submenu">
										<a href="javascript:void(0);">Admin Dashboard</a>
										<ul class="submenu">
											<li><a href="../template/admin/index.html">Dashboard</a></li>
											<li><a href="../template/admin/reservations.html">Bookings</a></li>
											<li><a href="../template/admin/customers.html">Manage</a></li>
											<li><a href="../template/admin/cars.html">Rentals</a></li>
											<li><a href="../template/admin/invoices.html">Finance & Accounts</a></li>
											<li><a href="../template/admin/coupons.html">Others</a></li>
											<li><a href="../template/admin/pages.html">CMS</a></li>			
											<li><a href="../template/admin/contact-messages.html">Support</a></li>			
											<li><a href="../template/admin/users.html">User Management</a></li>			
											<li><a href="../template/admin/earnings-report.html">Reports</a></li>			
											<li><a href="../template/admin/profile-setting.html">Settings & Configuration</a></li>		
										</ul>
									</li>				
								</ul>
							</li>	
							<li class="login-link">
								<a href="register.html">Sign Up</a>
							</li>
							<li class="login-link">
								<a href="login.html">Sign In</a>
							</li>
						</ul>
					</div>
					<ul class="nav header-navbar-rht">
						<li class="nav-item">
							<a class="nav-link header-login" href="login.html"><span><i class="fa-regular fa-user"></i></span>Sign In</a>
						</li>
						<li class="nav-item">
							<a class="nav-link header-reg" href="register.html"><span><i class="fa-solid fa-lock"></i></span>Sign Up</a>
						</li>
					</ul>
				</nav>
			</div>
		</header>
		<!-- /Header -->

    <div class="breadcrumb-bar">
      <div class="container">
        <div class="row align-items-center text-center">
          <div class="col-md-12 col-12">
            <h2 class="breadcrumb-title">Checkout</h2>
            <nav aria-label="breadcrumb" class="page-breadcrumb">
              <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="index.html">Home</a></li>
                <li class="breadcrumb-item active" aria-current="page">Checkout</li>
              </ol>
            </nav>
          </div>
        </div>
      </div>
    </div>

    <div class="booking-new-module">
      <div class="container">
        <div class="booking-wizard-head">
          <div class="row align-items-center">
            <div class="col-xl-4 col-lg-3">
              <div class="booking-head-title">
                <h4>Reserve Your Car</h4>
                <p>Complete the following steps</p>
              </div>
            </div>
            <div class="col-xl-8 col-lg-9">
              <div class="booking-wizard-lists">
                <ul>
                  <li class="active">
                    <span><img src="assets/img/icons/booking-head-icon-01.svg" alt="Booking Icon"></span>
                    <h6>Location & Time</h6>
                  </li>
                  <li>
                    <span><img src="assets/img/icons/booking-head-icon-02.svg" alt="Booking Icon"></span>
                    <h6>Extra Services</h6>
                  </li>
                  <li>
                    <span><img src="assets/img/icons/booking-head-icon-03.svg" alt="Booking Icon"></span>
                    <h6>Detail</h6>
                  </li>
                  <li>
                    <span><img src="assets/img/icons/booking-head-icon-04.svg" alt="Booking Icon"></span>
                    <h6>Checkout</h6>
                  </li>
                  <li>
                    <span><img src="assets/img/icons/booking-head-icon-05.svg" alt="Booking Icon"></span>
                    <h6>Booking Confirmed</h6>
                  </li>
                </ul>
              </div>
            </div>
          </div>
        </div>
        <div class="booking-detail-info">
          <div class="row">
            <div class="col-lg-8">
              <div class="booking-information-main">
                <?php if ($errors): ?>
                  <div class="alert alert-danger"><?php foreach($errors as $e) echo '<div>'.h($e).'</div>'; ?></div>
                <?php endif; ?>
                <form method="post" action="">
                  <div class="booking-information-card">
                    <div class="booking-info-head">
                      <span><i class="bx bxs-car-garage"></i></span>
                      <h5>Rental Type</h5>
                    </div>
                    <div class="booking-info-body">
                      <ul class="booking-radio-btns">
                        <li>
                          <label class="booking_custom_check">
                            <input type="radio" name="rent_type" id="location_delivery" value="delivery" <?= (($b['itinerary']['rent_type'] ?? 'delivery')==='delivery')?'checked':'' ?>>
                            <span class="booking_checkmark">
                              <span class="checked-title">Delivery</span>
                            </span>
                          </label>
                        </li>
                        <li>
                          <label class="booking_custom_check">
                            <input type="radio" name="rent_type" id="location_pickup" value="pickup" <?= (($b['itinerary']['rent_type'] ?? '')==='pickup')?'checked':'' ?>>
                            <span class="booking_checkmark">
                              <span class="checked-title">Self Pickup</span>
                            </span>
                          </label>
                        </li>
                      </ul>
                    </div>
                  </div>

                  <div class="booking-information-card delivery-location">
                    <div class="booking-info-head">
                      <span><i class="bx bxs-car-garage"></i></span>
                      <h5>Location</h5>
                    </div>
                    <div class="booking-info-body">
                      <div class="form-custom">
                        <label class="form-label">Delivery Location</label>
                        <div class="d-flex align-items-center">
                          <input type="text" name="delivery_location" class="form-control mb-0" placeholder="Add Location" value="<?= h($b['itinerary']['pickup_location'] ?? ($car['main_location'] ?? '')) ?>">
                          <a href="#" class="btn btn-secondary location-btn d-flex align-items-center"><i class="bx bx-current-location me-2"></i>Current Location</a>
                        </div>
                      </div>
                      <div class="input-block m-0">
                        <label class="custom_check d-inline-flex location-check"><span>Return to same location</span>
                          <input type="checkbox" name="return_same_location" <?= !empty($b['itinerary']['return_same_location'])?'checked':'' ?>>
                          <span class="checkmark"></span>
                        </label>
                      </div>
                      <div class="form-custom">
                        <label class="form-label">Return Location</label>
                        <div class="d-flex align-items-center">
                          <input type="text" name="delivery_return_location" class="form-control mb-0" placeholder="Add Location" value="<?= h($b['itinerary']['dropoff_location'] ?? ($car['main_location'] ?? '')) ?>">
                          <a href="#" class="btn btn-secondary location-btn d-flex align-items-center"><i class="bx bx-current-location me-2"></i>Current Location</a>
                        </div>
                      </div>
                    </div>
                  </div>

                  <div class="booking-information-card pickup-location">
                    <div class="booking-info-head">
                      <span><i class="bx bxs-car-garage"></i></span>
                      <h5>Location</h5>
                    </div>
                    <div class="booking-info-body">
                      <div class="form-custom">
                        <label class="form-label">Pickup Location</label>
                        <div class="d-flex align-items-center">
                          <input type="text" name="pickup_location" class="form-control mb-0" value="<?= h($b['itinerary']['pickup_location'] ?? ($car['main_location'] ?? '')) ?>">
                        </div>
                      </div>
                      <div class="input-block m-0">
                        <label class="custom_check d-inline-flex location-check"><span>Return to same location</span>
                          <input type="checkbox" name="pickup_return_same_location" <?= !empty($b['itinerary']['pickup_return_same_location'])?'checked':'' ?>>
                          <span class="checkmark"></span>
                        </label>
                      </div>
                      <div class="form-custom">
                        <label class="form-label">Return Location</label>
                        <div class="d-flex align-items-center">
                          <input type="text" name="pickup_return_location" class="form-control mb-0" value="<?= h($b['itinerary']['dropoff_location'] ?? ($car['main_location'] ?? '')) ?>">
                        </div>
                      </div>
                    </div>
                  </div>

                  <div class="booking-information-card booking-type-card">
                    <div class="booking-info-head">
                      <span><i class="bx bxs-location-plus"></i></span>
                      <h5>Booking type & Time</h5>
                    </div>
                    <div class="booking-info-body">
                      <ul class="booking-radio-btns">
                        <?php foreach (['daily','weekly','monthly','yearly'] as $t): $price = $rates[$t] ?? 0; if ($price <= 0) continue; ?>
                          <li>
                            <label class="booking_custom_check">
                              <input type="radio" name="booking_type" value="<?= h($t) ?>" <?= ($bookingTypeSel===$t)?'checked':'' ?>>
                              <span class="booking_checkmark"><span class="checked-title"><?= h(ucfirst($t)) ?> ($<?= number_format($price,2) ?>)</span></span>
                            </label>
                          </li>
                        <?php endforeach; ?>
                      </ul>
                      <div class="booking-timings">
                        <div class="row">
                          <div class="col-md-6">
                            <div class="input-block date-widget">
                              <label class="form-label">Start Date</label>
                              <div class="group-img">
                                <input type="text" name="pickup_date" class="form-control datetimepicker" placeholder="Choose Date" value="<?= h($b['itinerary']['pickup_date'] ?? '') ?>">
                                <span class="input-cal-icon"><i class="bx bx-calendar"></i></span>
                              </div>
                            </div>
                          </div>
                          <div class="col-md-6">
                            <div class="input-block time-widge">
                              <label class="form-label">Start Time</label>
                              <div class="group-img">
                                <input type="text" name="pickup_time" class="form-control timepicker" placeholder="Choose Time" value="<?= h($b['itinerary']['pickup_time'] ?? '14:00') ?>">
                                <span class="input-cal-icon"><i class="bx bx-time"></i></span>
                              </div>
                            </div>
                          </div>
                          <div class="col-md-6">
                            <div class="input-block date-widget">
                              <label class="form-label">Return Date</label>
                              <div class="group-img">
                                <input type="text" name="dropoff_date" class="form-control datetimepicker" placeholder="Choose Date" value="<?= h($b['itinerary']['dropoff_date'] ?? '') ?>">
                                <span class="input-cal-icon"><i class="bx bx-calendar"></i></span>
                              </div>
                            </div>
                          </div>
                          <div class="col-md-6">
                            <div class="input-block time-widge">
                              <label class="form-label">Return Time</label>
                              <div class="group-img">
                                <input type="text" name="dropoff_time" class="form-control timepicker" placeholder="Choose Time" value="<?= h($b['itinerary']['dropoff_time'] ?? '10:00') ?>">
                                <span class="input-cal-icon"><i class="bx bx-time"></i></span>
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>

                  <div class="booking-info-btns d-flex justify-content-end">
                    <a href="listing-details.php?id=<?= (int)$car['id'] ?>" class="btn btn-secondary">Back to Car details</a>
                    <button class="btn btn-primary continue-book-btn" type="submit">Continue Booking</button>
                  </div>
                </form>
              </div>
            </div>
            <div class="col-lg-4 theiaStickySidebar">
              <div class="booking-sidebar">
                <div class="booking-sidebar-card">
                  <div class="accordion-item border-0 mb-4">
                    <div class="accordion-header">
                      <div class="accordion-button collapsed" role="button" data-bs-toggle="collapse" data-bs-target="#accordion_collapse_one" aria-expanded="true">
                        <div class="booking-sidebar-head">
                          <h5>Booking Details<i class="fas fa-chevron-down"></i></h5>
                        </div>
                      </div>
                    </div>
                    <div id="accordion_collapse_one" class="accordion-collapse collapse">
                      <div class="booking-sidebar-body">
                        <div class="booking-car-detail">
                          <span class="car-img">
                            <img src="<?= h($car['featured_image']) ?>" class="img-fluid" alt="Car">
                          </span>
                          <div class="care-more-info">
                            <h5><?= h($car['name']) ?></h5>
                            <p><?= h($car['main_location'] ?? '') ?></p>
                            <a href="listing-details.php?id=<?= (int)$car['id'] ?>">View Car Details</a>
                          </div>
                        </div>
                        <div class="booking-vehicle-rates">
                          <ul>
                            <li class="d-flex justify-content-between"><span>Rate (<?= h(ucfirst($bookingTypeSel)) ?>)</span> <strong>$<span id="js-rate-val"><?php
                              $rateVal = $rates[$bookingTypeSel] ?? 0; echo number_format((float)$rateVal,2);
                            ?></span></strong></li>
                            <li class="d-flex justify-content-between"><span>Days</span> <strong><span id="js-days"><?= (int)$daysPreview ?></span></strong></li>
                            <li class="d-flex justify-content-between"><span>Base</span> <strong>$<span id="js-base"><?= number_format($estimatedBase,2) ?></span></strong></li>
                            <li class="d-flex justify-content-between"><span>Add-ons</span> <strong>$<span id="js-addons"><?= number_format((float)($b['totals']['addons'] ?? 0),2) ?></span></strong></li>
                          </ul>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="booking-sidebar-card">
                  <div class="accordion-item border-0 mb-4">
                    <div class="accordion-header">
                      <div class="accordion-button collapsed" role="button" data-bs-toggle="collapse" data-bs-target="#accordion_collapse_two" aria-expanded="true">
                        <div class="booking-sidebar-head d-flex justify-content-between align-items-center">
                          <h5>Coupon<i class="fas fa-chevron-down"></i></h5>
                          <a href="#" class="coupon-view">View Coupons</a>
                        </div>
                      </div>
                    </div>
                    <div id="accordion_collapse_two" class="accordion-collapse collapse">
                      <div class="booking-sidebar-body">
                        <form action="#">
                          <div class="d-flex align-items-center">
                            <div class="form-custom flex-fill">
                              <input type="text" class="form-control mb-0" placeholder="Coupon code">
                            </div>
                            <button type="button" class="btn btn-secondary apply-coupon-btn d-flex align-items-center ms-2">Apply<i class="feather-arrow-right ms-2"></i></button>
                          </div>
                        </form>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="total-rate-card">
                  <div class="vehicle-total-price">
                    <h5>Estimated Total</h5>
                    <span>$<span id="js-total"><?= number_format($estimatedBase,2) ?></span></span>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
 <!-- Footer -->
		<footer class="footer">	
			<!-- Footer Top -->	
			<div class="footer-top aos" data-aos="fade-down">
				<div class="container">
					<div class="row">
						<div class="col-lg-7">
							<div class="row">
								<div class="col-lg-4 col-md-6">
									<!-- Footer Widget -->
									<div class="footer-widget footer-menu">
										<h5 class="footer-title">About Company</h5>
										<ul>
											<li>
												<a href="about.html">Our Company</a>
											</li>
											<li>
												<a href="javascript:void(0)">Shop Toyota</a>
											</li>
											<li>
												<a href="javascript:void(0)">Dreamsrentals USA</a>
											</li>
											<li>
												<a href="javascript:void(0)">Dreamsrentals Worldwide</a>
											</li>
											<li>
												<a href="javascript:void(0)">Dreamsrental Category</a>
											</li>										
										</ul>
									</div>
									<!-- /Footer Widget -->
								</div>
								<div class="col-lg-4 col-md-6">
									<!-- Footer Widget -->
									<div class="footer-widget footer-menu">
										<h5 class="footer-title">Vehicles Type</h5>
										<ul>
											<li>
												<a href="javascript:void(0)">All  Vehicles</a>
											</li>
											<li>
												<a href="javascript:void(0)">SUVs</a>
											</li>
											<li>
												<a href="javascript:void(0)">Trucks</a>
											</li>
											<li>
												<a href="javascript:void(0)">Cars</a>
											</li>
											<li>
												<a href="javascript:void(0)">Crossovers</a>
											</li>								
										</ul>
									</div>
									<!-- /Footer Widget -->
								</div>
								<div class="col-lg-4 col-md-6">
									<!-- Footer Widget -->
									<div class="footer-widget footer-menu">
										<h5 class="footer-title">Quick links</h5>
										<ul>
											<li>
												<a href="javascript:void(0)">My Account</a>
											</li>
											<li>
												<a href="javascript:void(0)">Champaigns</a>
											</li>
											<li>
												<a href="javascript:void(0)">Dreamsrental Dealers</a>
											</li>
											<li>
												<a href="javascript:void(0)">Deals and Incentive</a>
											</li>
											<li>
												<a href="javascript:void(0)">Financial Services</a>
											</li>								
										</ul>
									</div>
									<!-- /Footer Widget -->
								</div>
							</div>
						</div>
						<div class="col-lg-5">
							<div class="footer-contact footer-widget">
								<h5 class="footer-title">Contact Info</h5>
								<div class="footer-contact-info">									
									<div class="footer-address">											
										<span><i class="feather-phone-call"></i></span>
										<div class="addr-info">
											<a href="tel:+1(888)7601940">+ 1 (888) 760 1940</a>
										</div>
									</div>
									<div class="footer-address">
										<span><i class="feather-mail"></i></span>
										<div class="addr-info">
											<a href="mailto:support@example.com">support@example.com</a>
										</div>
									</div>
									<div class="update-form">
										<form action="booking-payment.html">
											<span><i class="feather-mail"></i></span> 
											<input type="email" class="form-control" placeholder="Enter You Email Here">
											<button type="submit" class="btn btn-subscribe"><span><i class="feather-send"></i></span></button>
										</form>
									</div>
								</div>								
								<div class="footer-social-widget">
									<ul class="nav-social">
										<li>
											<a href="javascript:void(0)"><i class="fa-brands fa-facebook-f fa-facebook fi-icon"></i></a>
										</li>
										<li>
											<a href="javascript:void(0)"><i class="fab fa-instagram fi-icon"></i></a>
										</li>
										<li>
											<a href="javascript:void(0)"><i class="fab fa-behance fi-icon"></i></a>
										</li>
										<li>
											<a href="javascript:void(0)"><i class="fab fa-twitter fi-icon"></i> </a>
										</li>
										<li>
											<a href="javascript:void(0)"><i class="fab fa-linkedin fi-icon"></i></a>
										</li>
									</ul>
								</div>
							</div>
						</div>
					</div>					
				</div>
			</div>
			<!-- /Footer Top -->

			<!-- Footer Bottom -->
			<div class="footer-bottom">
				<div class="container">
					<!-- Copyright -->
					<div class="copyright">
						<div class="row align-items-center">
							<div class="col-md-6">
								<div class="copyright-text">
									<p>© 2024 Dreams Rent. All Rights Reserved.</p>
								</div>
							</div>
							<div class="col-md-6">
								<!-- Copyright Menu -->
								<div class="copyright-menu">
									<div class="vistors-details">
										<ul class="d-flex">											
											<li><a href="javascript:void(0)"><img class="img-fluid" src="assets/img/icons/paypal.svg" alt="Paypal"></a></li>											
											<li><a href="javascript:void(0)"><img class="img-fluid" src="assets/img/icons/visa.svg" alt="Visa"></a></li>
											<li><a href="javascript:void(0)"><img class="img-fluid" src="assets/img/icons/master.svg" alt="Master"></a></li>
											<li><a href="javascript:void(0)"><img class="img-fluid" src="assets/img/icons/applegpay.svg" alt="applegpay"></a></li>
										</ul>										   								
									</div>
								</div>
								<!-- /Copyright Menu -->
							</div>
						</div>
					</div>
					<!-- /Copyright -->
				</div>
			</div>
			<!-- /Footer Bottom -->			
		</footer>
		<!-- /Footer -->
				
	</div>

    <!-- scrollToTop start -->
	<div class="progress-wrap active-progress">
		<svg class="progress-circle svg-content" width="100%" height="100%" viewBox="-1 -1 102 102">
		<path d="M50,1 a49,49 0 0,1 0,98 a49,49 0 0,1 0,-98" style="transition: stroke-dashoffset 10ms linear 0s; stroke-dasharray: 307.919px, 307.919px; stroke-dashoffset: 228.265px;"></path>
		</svg>
	</div>
	<!-- scrollToTop end -->
  </div>
  <script src="assets/js/jquery-3.7.1.min.js"></script>
  <script src="assets/js/bootstrap.bundle.min.js"></script>
  <script src="assets/plugins/aos/aos.js"></script>
  <script>AOS.init();</script>
  <script src="assets/plugins/moment/moment.min.js"></script>
  <script src="assets/js/bootstrap-datetimepicker.min.js"></script>
  <script src="assets/plugins/theia-sticky-sidebar/ResizeSensor.js"></script>
  <script src="assets/plugins/theia-sticky-sidebar/theia-sticky-sidebar.js"></script>
  <script src="assets/js/script.js"></script>
  <script>
    (function(){
      const rates = <?= json_encode($rates) ?>;
      const radios = document.querySelectorAll('input[name="booking_type"]');
      const daysEl = document.getElementById('js-days');
      const rateEl = document.getElementById('js-rate-val');
      const baseEl = document.getElementById('js-base');
  const totalEl = document.getElementById('js-total');
  // On checkout, we only show base amount; add-ons are not chosen yet.
      function days(){ return parseInt(daysEl?.textContent || '<?= (int)$daysPreview ?>',10)||1; }
  function base(){ return (rates.daily||0); }
      function fmt(n){ return (Number(n)||0).toFixed(2); }
      function update(){
        const sel = document.querySelector('input[name="booking_type"]:checked');
        const t = sel ? sel.value : 'daily';
  const rv = rates.daily; // show daily rate only for Estimated Total base
  const b = base();
        if (rateEl) rateEl.textContent = fmt(rv);
        if (baseEl) baseEl.textContent = fmt(b);
        if (totalEl) totalEl.textContent = fmt(b);
      }
      radios.forEach(r=>r.addEventListener('change', update));
      update();
    })();
  </script>
</body>
</html>
