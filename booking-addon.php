<?php
require_once __DIR__ . '/includes/booking.php';

// Must have car and itinerary from previous step
if (empty($_SESSION['booking']['car'])) {
  redirect_to('booking-checkout.php');
}
if (empty($_SESSION['booking']['itinerary'])) {
  redirect_to('booking-checkout.php');
}

$b = booking_session();
$car = $b['car'];
$errors = [];

$availableExtras = decode_arr($car['extra_services'] ?? '');
// Count extras excluding insurance, since insurance is shown in its own section
$availableExtrasCount = 0;
if ($availableExtras) {
  foreach ($availableExtras as $ek) { if ($ek !== 'insurance') $availableExtrasCount++; }
}
$insurancePlans = fetch_car_insurance((int)$car['id']);
// previously selected driver type
$driverType = $b['addons']['driver']['type'] ?? 'self';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $extras = $_POST['extras'] ?? [];
  if (!is_array($extras)) $extras = [];
  // Filter to only allowed extras
  $extras = array_values(array_intersect($extras, $availableExtras));

  $insSel = $_POST['insurance'] ?? '';
  $chosenIns = null;
  if ($insSel !== '') {
    foreach ($insurancePlans as $ins) {
      if ((string)$ins['id'] === (string)$insSel) {
        $chosenIns = [ 'id' => (int)$ins['id'], 'name' => $ins['insurance_name'], 'price' => (float)$ins['price'] ];
        break;
      }
    }
  }

  // Driver details selection (self vs acting)
  $driverType = $_POST['driver_type'] ?? 'self';
  $driverInfo = null;
  if ($driverType === 'acting') {
    // In absence of a drivers table, use a fixed sample to match template
    $sel = $_POST['acting_driver_id'] ?? '1';
    // simple catalog
    $drivers = [
      '1' => [ 'id' => 1, 'name' => 'Ruban', 'rides' => 32, 'price' => 100.00, 'avatar' => 'assets/img/drivers/driver-02.jpg' ],
    ];
    if (isset($drivers[$sel])) {
      $driverInfo = [ 'type' => 'acting', 'id' => $drivers[$sel]['id'], 'name' => $drivers[$sel]['name'], 'price' => $drivers[$sel]['price'], 'avatar' => $drivers[$sel]['avatar'] ];
    }
  } else {
    $driverInfo = [ 'type' => 'self' ];
  }

  $b['addons'] = [ 'extras' => $extras, 'insurance' => $chosenIns, 'driver' => $driverInfo ];
  calculate_totals($b);
  set_booking_session($b);
  redirect_to('booking-detail.php');
}

// Map extras to readable names
function pretty_name($k){ return ucwords(str_replace(['_','-'],' ', (string)$k)); }
$priceMap = get_extra_price_map();
$extraIcons = [
  'navigation' => 'ti ti-gps',
  'wifi_hotspot' => 'ti ti-wifi-2',
  'child_safety_seats' => 'ti ti-baby-carriage',
  'fuel_pre_purchase' => 'ti ti-baby-carriage',
  'roadside_assistance' => 'ti ti-user-star',
  'satellite_radio' => 'ti ti-satellite',
  'usb_charger' => 'ti ti-usb',
  'express_checkin_checkout' => 'ti ti-checkup-list',
  'toll_pass' => 'ti ti-tallymark-2',
];
$extraLabels = [
  'navigation' => 'Navigation',
  'wifi_hotspot' => 'Wi-Fi Hotspot',
  'child_safety_seats' => 'Child Safety Seats',
  'fuel_pre_purchase' => 'Fuel Pre-Purchase',
  'roadside_assistance' => 'Roadside Assistance',
  'satellite_radio' => 'Satellite Radio',
  'usb_charger' => 'USB Charger',
  'express_checkin_checkout' => 'Express Check-in/out',
  'toll_pass' => 'Toll Pass',
];
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0">
  <title>Add-ons - <?= h($car['name']) ?></title>
  <link rel="shortcut icon" href="assets/img/favicon.png">
  <link rel="stylesheet" href="assets/css/bootstrap.min.css">
  <link rel="stylesheet" href="assets/plugins/fontawesome/css/fontawesome.min.css">
  <link rel="stylesheet" href="assets/plugins/fontawesome/css/all.min.css">
  <link rel="stylesheet" href="admin/assets/plugins/tabler-icons/tabler-icons.min.css">
  <link rel="stylesheet" href="assets/plugins/boxicons/css/boxicons.min.css">
  <link rel="stylesheet" href="assets/plugins/aos/aos.css">
  <link rel="stylesheet" href="assets/css/style.css">
</head>
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
<body>
  <div class="main-wrapper">
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
                  <li class="active activated">
                    <span><img src="assets/img/icons/booking-head-icon-01.svg" alt="Booking Icon"></span>
                    <h6>Location & Time</h6>
                  </li>
                  <li class="active">
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
                <form method="post" action="">
                  <div class="booking-information-card">
                    <div class="booking-info-head justify-content-between">
                      <div class="d-flex align-items-center">
                        <span><i class="bx bx-add-to-queue"></i></span>
                        <h5>Extra Services</h5>
                      </div>
                      <h6>Total : <?= (int)$availableExtrasCount ?> Extra Services</h6>
                    </div>
                    <div class="booking-info-body">
                      <div class="row">
                        <div class="col-md-12">
                          <?php $selectedExtras = $b['addons']['extras'] ?? []; ?>
                          <?php if (!$availableExtras): ?>
                            <p class="text-muted mb-0">No extra services available for this car.</p>
                          <?php else: ?>
                            <ul class="adons-lists" id="js-extras-list">
                              <?php foreach ($availableExtras as $ekey): if ($ekey === 'insurance') continue; $price = isset($priceMap[$ekey]) ? (float)$priceMap[$ekey] : 5.00; $checked = in_array($ekey, $selectedExtras, true); $icon = $extraIcons[$ekey] ?? 'ti ti-plus'; $label = $extraLabels[$ekey] ?? pretty_name($ekey); ?>
                                <li>
                                  <div class="adons-types align-items-center">
                                    <div class="d-flex align-items-center adon-name-info">
                                      <span class="adon-icon"><i class="<?= h($icon) ?>"></i></span>
                                      <div class="adon-name">
                                        <h6><?= h($label) ?></h6>
                                        <small class="text-muted">Optional add-on</small>
                                      </div>
                                    </div>
                                    <span class="adon-price">$<?= number_format($price,2) ?></span>
                                    <label class="booking_custom_check ms-3 mb-0">
                                      <input type="checkbox" class="js-extra" name="extras[]" value="<?= h($ekey) ?>" <?= $checked?'checked':'' ?> data-price="<?= number_format($price,2,'.','') ?>">
                                      <span class="booking_checkmark">
                                        <span class="checked-title">Add</span>
                                      </span>
                                    </label>
                                  </div>
                                </li>
                              <?php endforeach; ?>
                            </ul>
                          <?php endif; ?>
                        </div>
                      </div>
                    </div>
                  </div>

                  <div class="booking-information-card">
                    <div class="booking-info-head">
                      <span><i class="bx bx-user-pin"></i></span>
                      <h5>Driver details</h5>
                    </div>
                    <div class="booking-info-body">
                      <ul class="booking-radio-btns">
                        <li>
                          <label class="booking_custom_check">
                            <input type="radio" name="driver_type" value="self" <?= $driverType==='self'?'checked':'' ?>>
                            <span class="booking_checkmark">
                              <span class="checked-title">Self Driver</span>
                            </span>
                          </label>
                        </li>
                        <li>
                          <label class="booking_custom_check">
                            <input type="radio" name="driver_type" value="acting" <?= $driverType==='acting'?'checked':'' ?>>
                            <span class="booking_checkmark">
                              <span class="checked-title">Driver</span>
                            </span>
                          </label>
                        </li>
                      </ul>
                      <div class="booking-timings self-driver-info" style="<?= $driverType==='acting'?'display:none;':'' ?>">
                        <div class="row">
                          <div class="col-md-12">
                            <div class="form-title-head">
                              <h5>Driver details</h5>
                            </div>
                          </div>
                          <div class="col-md-6">
                            <div class="input-block date-widget">	
                              <label class="form-label">First Name <span class="text-danger"> *</span></label>											
                              <input type="text" class="form-control" placeholder="Enter First Name" name="self_first_name">
                            </div>
                          </div>
                          <div class="col-md-6">
                            <div class="input-block date-widget">	
                              <label class="form-label">Last Name <span class="text-danger"> *</span></label>										
                              <input type="text" class="form-control" placeholder="Enter Last Name" name="self_last_name">
                            </div>
                          </div>
                          <div class="col-md-6">
                            <div class="input-block date-widget">	
                              <label class="form-label">Driver Age <span class="text-danger"> *</span></label>										
                              <input type="text" class="form-control" placeholder="Enter Age of Driver" name="self_age">
                            </div>
                          </div>
                          <div class="col-md-6">
                            <div class="input-block date-widget">	
                              <label class="form-label">Mobile Number <span class="text-danger"> *</span></label>										
                              <input type="text" class="form-control" placeholder="Enter Phone Number" name="self_phone">
                            </div>
                          </div>
                          <div class="col-md-12">
                            <div class="input-block date-widget">	
                              <label class="form-label">Driving Licence Number <span class="text-danger"> *</span></label>										
                              <input type="text" class="form-control" placeholder="Enter Driving Licence Number" name="self_license">
                            </div>
                          </div>
                          <div class="col-md-12">
                            <div class="input-block date-widget">	
                              <label class="form-label">Upload Document <span class="text-danger"> *</span></label>										
                              <div class="upload-div">
                                <input type="file" name="self_document">
                                <div class="upload-photo-drag">
                                  <span><i class="fa fa-upload me-2"></i> Upload Photo</span>
                                  <h6>or Drag Photos</h6>
                                </div>
                              </div>
                              <div class="upload-list">
                                <ul>
                                  <li>The maximum photo size is 8 MB. Formats: jpeg, jpg, png. Put the main picture first</li>
                                </ul>
                              </div>
                            </div>
                          </div>
                          <div class="col-md-12">
                            <div class="input-block m-0">
                              <label class="custom_check d-inline-flex location-check m-0"><span>I Confirm Driver's Age is above 20 years old</span>
                                <input type="checkbox" name="self_age_confirm">
                                <span class="checkmark"></span>
                              </label>
                            </div>
                          </div>
                        </div>
                      </div>
                      <div class="booking-timings acting-driver-info" style="<?= $driverType==='acting'?'':'display:none;' ?>">
                        <div class="form-title-head">
                          <h5>Driver</h5>
                        </div>
                        <ul class="acting-driver-list">
                          <li>
                            <div class="driver-profile-info">
                              <span class="driver-profile">
                                <img src="assets/img/drivers/driver-02.jpg" alt="Img">
                              </span>
                              <div class="driver-name">
                                <h5>Ruban</h5>
                                <ul>
                                  <li>No of Rides Completed : 32</li>
                                  <li>Price : $100</li>
                                </ul>
                              </div>
                            </div>
                            <div class="change-driver">
                              <input type="hidden" name="acting_driver_id" value="1">
                              <a href="javascript:void(0);" class="btn btn-secondary d-inline-flex align-items-center"><i class="bx bx-check-circle me-2"></i>Change Driver</a>
                            </div>
                          </li>
                        </ul>
                      </div>
                    </div>
                  </div>

                  <div class="booking-information-card pb-1">
                    <div class="booking-info-head">
                      <span><i class="bx bx-file-blank"></i></span>
                      <h5>Insurance</h5>
                    </div>
                    <div class="booking-info-body">
                      <?php if (!$insurancePlans): ?>
                        <p class="text-muted mb-0">No insurance plans.</p>
                      <?php else: ?>
                        <?php $selId = $b['addons']['insurance']['id'] ?? ''; ?>
                        <?php foreach ($insurancePlans as $ins): ?>
                          <div class="insurance-select custom-checkbox <?= ((string)$selId === (string)$ins['id']) ? 'active' : '' ?>">
                            <div>
                              <p class="fs-14 d-inline-flex align-items-center mb-1"><?= h($ins['insurance_name']) ?></p>
                              <div>
                                <input class="form-check-input js-insurance" type="radio" name="insurance" id="ins_<?= (int)$ins['id'] ?>" value="<?= (int)$ins['id'] ?>" data-price="<?= number_format((float)$ins['price'],2,'.','') ?>" <?= ((string)$selId === (string)$ins['id'])?'checked':'' ?>>
                                <label class="form-check-label" for="ins_<?= (int)$ins['id'] ?>">Select this plan for protection</label>
                              </div>
                            </div>
                            <div class="text-end">
                              <span class="d-block mb-1">Onetime Ride</span>
                              <h6 class="fw-normal">$<?= number_format((float)$ins['price'],2) ?></h6>
                            </div>
                          </div>
                        <?php endforeach; ?>
                        <div class="insurance-select custom-checkbox <?= $selId===''?'active':'' ?>">
                          <div>
                            <p class="fs-14 d-inline-flex align-items-center mb-1">No Insurance</p>
                            <div>
                              <input class="form-check-input js-insurance" type="radio" name="insurance" id="ins_none" value="" data-price="0" <?= $selId===''?'checked':'' ?>>
                              <label class="form-check-label" for="ins_none">I will proceed without insurance</label>
                            </div>
                          </div>
                          <div class="text-end">
                            <span class="d-block mb-1">Onetime Ride</span>
                            <h6 class="fw-normal">$0.00</h6>
                          </div>
                        </div>
                      <?php endif; ?>
                    </div>
                  </div>

                  <div class="booking-info-btns d-flex justify-content-end">
                    <a href="booking-checkout.php" class="btn btn-secondary">Back to Location & Time</a>
                    <button type="submit" class="btn btn-primary continue-book-btn">Continue Booking</button>
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
                              <img src="assets/img/car-list-4.jpg" class="img-fluid" alt="Car">
                            </span>
                            <div class="care-more-info">
                              <h5>Chevrolet Camaro</h5>
                              <p>Miami St, Destin, FL 32550, USA</p>
                              <a href="listing-details.html">View Car Details</a>
                            </div>
                          </div>
                          <div class="booking-vehicle-rates">
                            <ul>
                              <li>
                                <div class="rental-charge">
                                  <h6>Rental Charges Rate <span> (1 day )</span></h6>
                                  <span class="text-danger">(This does not include fuel)</span>
                                </div>
                                <h5>+ $300</h5>
                              </li>
                              <li>
                                <h6>Doorstep delivery</h6>
                                <h5>+ $60</h5>
                              </li>
                              <li>
                                <h6>Trip Protection Fees</h6>
                                <h5>+ $25</h5>
                              </li>
                              <li>
                                <h6>Convenience Fees</h6>
                                <h5>+ $2</h5>
                              </li>
                              <li>
                                <h6>Tax</h6>
                                <h5>+ $2</h5>
                              </li>
                              <li>
                                <h6>Refundable Deposit</h6>
                                <h5>+$1200</h5>
                              </li>
                              <li>
                                <h6>Full Premium Insurance <i class="bx bxs-x-circle text-danger"></i></h6>
                                <h5>+$200</h5>
                              </li>
                              <li class="total-rate">
                                <h6>Subtotal</h6>
                                <h5>+$1604</h5>
                              </li>
                            </ul>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="booking-sidebar-card">
                    <div class="accordion-item border-0 mb-4">
                      <div class="accordion-header p-3 d-flex align-center justify-content-between">
                        <div class="accordion-button collapsed" role="button" data-bs-toggle="collapse" data-bs-target="#accordion_collapse_three" aria-expanded="true">
                          <div class="booking-sidebar-head p-0 d-flex justify-content-between align-items-center">
                            <h5>Location & Time<i class="fas fa-chevron-down"></i></h5>
                          </div>
                        </div>
                        <a href="booking-checkout.php" class="d-flex align-items-center sidebar-edit"><i class="bx bx-edit-alt me-2"></i>Edit</a>
                      </div>
                      <div id="accordion_collapse_three" class="accordion-collapse collapse">
                        <div class="booking-sidebar-body">
                          <ul class="location-address-info">
                            <li>
                              <h6>Rental Type</h6>
                              <p><?= isset($b['itinerary']['rent_type']) && $b['itinerary']['rent_type']==='pickup' ? 'Self Pickup' : 'Delivery' ?></p>
                            </li>
                            <li>
                              <h6>Booking Type</h6>
                              <p><?= ucfirst($b['itinerary']['booking_type'] ?? 'days') ?></p>
                            </li>
                            <li>
                              <h6>Delivery Location & time</h6>
                              <p><?= h($b['itinerary']['pickup_location'] ?? '') ?></p>
                              <p><?= h(($b['itinerary']['pickup_date'] ?? '').' - '.($b['itinerary']['pickup_time'] ?? '')) ?></p>
                            </li>
                            <li>
                              <h6>Return Location & time</h6>
                              <p><?= h($b['itinerary']['dropoff_location'] ?? '') ?></p>
                              <p><?= h(($b['itinerary']['dropoff_date'] ?? '').' - '.($b['itinerary']['dropoff_time'] ?? '')) ?></p>
                            </li>
                          </ul>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="booking-sidebar-card">
                    <div class="accordion-item border-0 mb-4">
                      <div class="accordion-header d-flex align-center justify-content-between p-3">
                        <div class="accordion-button collapsed" role="button" data-bs-toggle="collapse" data-bs-target="#accordion_collapse_two" aria-expanded="true">
                          <div class="booking-sidebar-head p-0 d-flex justify-content-between align-items-center">
                            <h5>Coupon<i class="fas fa-chevron-down"></i></h5>
                          </div>
                          <a href="#" class="coupon-view">View Coupons</a>
                        </div>
                      </div>
                      <div id="accordion_collapse_two" class="accordion-collapse collapse">
                        <div class="booking-sidebar-body">
                          <form action="booking-checkout.php">
                            <div class="d-flex align-items-center">
                              <div class="form-custom flex-fill">														
                                <input type="text" class="form-control mb-0" placeholder="Coupon code">
                              </div>
                              <button type="submit" class="btn btn-secondary apply-coupon-btn d-flex align-items-center ms-2">Apply<i class="feather-arrow-right ms-2"></i></button>
                            </div>
                          </form>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="total-rate-card">
                    <div class="vehicle-total-price">
                      <h5>Estimated Total</h5>
                      <span id="js-grand-total">$<?= number_format((float)($b['totals']['total'] ?? 0),2) ?></span>
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
  <script src="assets/plugins/theia-sticky-sidebar/ResizeSensor.js"></script>
  <script src="assets/plugins/theia-sticky-sidebar/theia-sticky-sidebar.js"></script>
  <script src="assets/js/script.js"></script>
  <script>
    (function(){
      // Pricing context from server
      const priceMap = <?= json_encode($priceMap) ?>;
      const baseVal = <?= json_encode((float)($b['totals']['base'] ?? 0)) ?>;
      const driverPrice = 100.00; // acting driver flat fee used in PHP too
      const totalEl = document.getElementById('js-grand-total');
      function fmt(n){ return '$' + (Number(n)||0).toFixed(2); }

      function calc(){
        let extras = 0;
        document.querySelectorAll('input.js-extra:checked').forEach(cb => {
          extras += parseFloat(cb.getAttribute('data-price') || '0');
        });
        let insurance = 0;
        const ins = document.querySelector('input.js-insurance:checked');
        if (ins) insurance = parseFloat(ins.getAttribute('data-price') || '0');
        let driver = 0;
        const dt = document.querySelector('input[name="driver_type"]:checked');
        if (dt && dt.value === 'acting') driver = driverPrice;
        const total = baseVal + extras + insurance + driver;
        if (totalEl) totalEl.textContent = fmt(total);
      }

      // Toggle driver sections
      function toggleDriver(){
        const dt = document.querySelector('input[name="driver_type"]:checked');
        const selfBox = document.querySelector('.self-driver-info');
        const actBox = document.querySelector('.acting-driver-info');
        if (dt && dt.value === 'acting'){
          if (selfBox) selfBox.style.display = 'none';
          if (actBox) actBox.style.display = '';
        } else {
          if (selfBox) selfBox.style.display = '';
          if (actBox) actBox.style.display = 'none';
        }
      }

      function updateInsuranceActive(){
        const groups = document.querySelectorAll('.insurance-select');
        groups.forEach(g => g.classList.remove('active'));
        const sel = document.querySelector('input.js-insurance:checked');
        if (sel){
          const card = sel.closest('.insurance-select');
          if (card) card.classList.add('active');
        }
      }

      // Listeners
      document.querySelectorAll('input.js-extra').forEach(cb => cb.addEventListener('change', calc));
      document.querySelectorAll('input.js-insurance').forEach(r => r.addEventListener('change', function(){ updateInsuranceActive(); calc(); }));
      // Make entire insurance cards clickable to select and recalc
      document.querySelectorAll('.insurance-select').forEach(card => {
        card.addEventListener('click', function(e){
          // Let native click on inputs/labels work
          const tag = e.target && e.target.tagName;
          if (tag === 'INPUT' || tag === 'LABEL') return;
          const input = card.querySelector('input.js-insurance');
          if (input){
            input.checked = true;
            input.dispatchEvent(new Event('change', { bubbles: true }));
          }
        });
      });
      document.querySelectorAll('input[name="driver_type"]').forEach(r => r.addEventListener('change', function(){ toggleDriver(); calc(); }));

      // Initial
      toggleDriver();
      updateInsuranceActive();
      calc();
    })();
  </script>
</body>
</html>
