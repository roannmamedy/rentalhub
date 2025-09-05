<?php
require_once __DIR__ . '/includes/booking.php';

if (empty($_SESSION['booking']['order'])) {
  // If direct access, redirect to home
  redirect_to('index-2.php');
}

$b = booking_session();
$orderNo = $_GET['order'] ?? ($b['order']['order_number'] ?? '');
$car = $b['car'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0">
  <title>Booking Success</title>
  <link rel="shortcut icon" href="assets/img/favicon.png">
  <link rel="stylesheet" href="assets/css/bootstrap.min.css">
  <link rel="stylesheet" href="assets/plugins/fontawesome/css/fontawesome.min.css">
  <link rel="stylesheet" href="assets/plugins/fontawesome/css/all.min.css">
  <link rel="stylesheet" href="assets/plugins/boxicons/css/boxicons.min.css">
  <link rel="stylesheet" href="assets/css/feather.css">
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
                  <li class="active activated">
                    <span><img src="assets/img/icons/booking-head-icon-02.svg" alt="Booking Icon"></span>
                    <h6>Extra Services</h6>
                  </li>
                  <li class="active activated">
                    <span><img src="assets/img/icons/booking-head-icon-03.svg" alt="Booking Icon"></span>
                    <h6>Detail</h6>
                  </li>
                  <li class="active activated">
                    <span><img src="assets/img/icons/booking-head-icon-04.svg" alt="Booking Icon"></span>
                    <h6>Checkout</h6>
                  </li>
                  <li class="active">
                    <span><img src="assets/img/icons/booking-head-icon-05.svg" alt="Booking Icon"></span>
                    <h6>Booking Confirmed</h6>
                  </li>
                </ul>
              </div>
            </div>
          </div>
        </div>
        <div class="booking-card">
          <div class="success-book">
            <span class="success-icon">
              <i class="fa-solid fa-check-double"></i>
            </span>
            <h5>Thank you! Your Order has been Recieved</h5>
            <h5 class="order-no">Order Number : <span>#<?= h($orderNo) ?></span></h5>
          </div>
          <div class="booking-header">
            <div class="booking-img-wrap">
              <div class="book-img">
                <img src="<?= h($car['featured_image'] ?? 'assets/img/cars/car-05.jpg') ?>" alt="img">
              </div>
              <div class="book-info">
                <h6><?= h($car['name'] ?? 'Chevrolet Camaro') ?></h6>
                <p><i class="feather-map-pin"></i> Location : <?= h($b['itinerary']['pickup_location'] ?? 'Miami St, Destin, FL 32550, USA') ?></p>
              </div>
            </div>
            <div class="book-amount">
              <p>Total Amount</p>
              <h6>$<?= number_format((float)($b['totals']['total'] ?? 4700),2) ?></h6>
            </div>
          </div>
          <div class="row">

            <!-- Car Pricing -->
            <div class="col-lg-6 col-md-6 d-flex">
              <div class="book-card flex-fill">
                <div class="book-head">
                  <h6>Car Pricing</h6>
                </div>
                <div class="book-body">
                  <ul class="pricing-lists">
                    <li>
                      <div>
                        <p>Rental Charges Rate <span>(<?= (int)($b['totals']['days'] ?? 1) ?> day<?= (int)($b['totals']['days'] ?? 1) > 1 ? 's' : '' ?> )</span></p>
                        <p class="text-danger">(This does not include fuel)</p>
                      </div>
                      <span> + $<?= number_format((float)($b['totals']['base'] ?? 300),2) ?></span>
                    </li>
                    <li>
                      <p>Doorstep delivery</p>
                      <span> + $60</span>
                    </li>
                    <li>
                      <p>Trip Protection Fees</p>
                      <span> + $25</span>
                    </li>
                    <li>
                      <p>Convenience Fees</p>
                      <span> + $2</span>
                    </li>
                    <li>
                      <p>Tax</p>
                      <span> + $2</span>
                    </li>
                    <li>
                      <p>Refundable Deposit</p>
                      <span> +$1200</span>
                    </li>
                    <li>
                      <p>Full Premium Insurance</p>
                      <span>+$<?= number_format((float)($b['addons']['insurance']['price'] ?? 200),2) ?></span>
                    </li>
                    <li class="total">
                      <p>Subtotal</p>
                      <span>+$<?= number_format((float)($b['totals']['subtotal'] ?? 1604),2) ?></span>
                    </li>
                  </ul>
                </div>
              </div>
            </div>
            <!-- /Car Pricing -->

            <!-- Location & Time -->
            <div class="col-lg-6 col-md-6 d-flex">
              <div class="book-card flex-fill">
                <div class="book-head">
                  <h6>Location & Time</h6>
                </div>
                <div class="book-body">
                  <ul class="location-lists">
                    <li>
                      <h6>Booking Type</h6>
                      <p><?= isset($b['itinerary']['rent_type']) && $b['itinerary']['rent_type']==='pickup' ? 'Self Pickup' : 'Delivery' ?></p>
                    </li>
                    <li>
                      <h6>Rental Type</h6>
                      <p><?= ucfirst($b['itinerary']['booking_type'] ?? 'Daily') ?></p>
                    </li>
                    <li>
                      <h6>Pickup</h6>
                      <p><?= h($b['itinerary']['pickup_location'] ?? '1230 E Springs Rd, Los Angeles, CA, USA') ?></p>
                      <p><?= h(($b['itinerary']['pickup_date'] ?? '04/18/2024') . ' - ' . ($b['itinerary']['pickup_time'] ?? '14:00')) ?></p>
                    </li>
                    <li>
                      <h6>Return</h6>
                      <p><?= h($b['itinerary']['dropoff_location'] ?? '1230 E Springs Rd, Los Angeles, CA, USA') ?></p>
                      <p><?= h(($b['itinerary']['dropoff_date'] ?? '04/18/2024') . ' - ' . ($b['itinerary']['dropoff_time'] ?? '14:00')) ?></p>
                    </li>
                  </ul>
                </div>
              </div>
            </div>
            <!-- /Location & Time -->

            <!-- Add-ons Pricing -->
            <div class="col-lg-6 col-md-6 d-flex">
              <div class="book-card flex-fill">
                <div class="book-head">
                  <h6>Extra Services Pricing</h6>
                </div>
                <div class="book-body">
                  <ul class="pricing-lists">
                    <?php if (!empty($b['addons']['extras'])): ?>
                      <?php foreach ($b['addons']['extras'] as $k): $price = (get_extra_price_map()[$k] ?? 25); ?>
                        <li>
                          <p><?= h(ucwords(str_replace('_',' ', $k))) ?></p>
                          <span>$<?= number_format((float)$price,2) ?></span>
                        </li>
                      <?php endforeach; ?>
                    <?php else: ?>
                      <li>
                        <p>GPS Navigation Systems</p>
                        <span>$25</span>
                      </li>
                      <li>
                        <p>Wi-Fi Hotspot</p>
                        <span>$25</span>
                      </li>
                      <li>
                        <p>Child Safety Seats</p>
                        <span>$50</span>
                      </li>
                    <?php endif; ?>
                    <li class="total">
                      <p>Extra Services Charges Rate</p>
                      <span>$<?= number_format((float)($b['totals']['extras'] ?? 100),2) ?></span>
                    </li>
                  </ul>
                </div>
              </div>
            </div>
            <!-- /Add-ons Pricing -->

            <!-- Driver Details -->
            <div class="col-lg-6 col-md-6 d-flex">
              <div class="book-card flex-fill">
                <div class="book-head">
                  <h6>Driver Details</h6>
                </div>
                <div class="book-body">
                  <ul class="location-lists">
                    <li>
                      <h6>Driver Type</h6>
                      <p><?= isset($b['addons']['driver_type']) && $b['addons']['driver_type']==='acting' ? 'Acting Driver' : 'Self Driver' ?></p>
                    </li>
                  </ul>
                  <?php if (isset($b['addons']['driver_type']) && $b['addons']['driver_type']==='acting'): ?>
                    <div class="driver-info">
                      <span>
                        <img src="assets/img/drivers/driver-02.jpg" alt="img">
                      </span>
                      <div class="driver-name">
                        <h6>Ruban</h6>
                        <ul>
                          <li>No of Rides Completed : 32</li>
                          <li>Price : $100</li>
                        </ul>
                      </div>
                    </div>
                  <?php else: ?>
                    <div class="driver-info">
                      <span>
                        <img src="assets/img/user.jpg" alt="img">
                      </span>
                      <div class="driver-name">
                        <h6><?= h($b['driver']['name'] ?? 'Self Driver') ?></h6>
                        <ul>
                          <li>License: <?= h($b['driver']['license'] ?? '-') ?></li>
                          <li>Phone: <?= h($b['driver']['phone'] ?? '-') ?></li>
                        </ul>
                      </div>
                    </div>
                  <?php endif; ?>
                </div>
              </div>
            </div>
            <!-- /Driver Details -->

            <!-- Billing Information -->
            <div class="col-lg-6 col-md-6 d-flex">
              <div class="book-card flex-fill">
                <div class="book-head">
                  <h6>Billing Information</h6>
                </div>
                <div class="book-body">
                  <ul class="billing-lists">
                    <li><?= h($b['driver']['name'] ?? '-') ?></li>
                    <li><?= h($b['driver']['company'] ?? '-') ?></li>
                    <li><?= h($b['driver']['address'] ?? '-') ?></li>
                    <li><?= h($b['driver']['phone'] ?? '-') ?></li>
                    <li><?= h($b['driver']['email'] ?? '-') ?></li>
                  </ul>
                </div>
              </div>
            </div>
            <!-- /Billing Information -->

            <!-- Payment  Details -->
            <div class="col-lg-6 col-md-6 d-flex">
              <div class="book-card flex-fill">
                <div class="book-head">
                  <h6>Payment  Details</h6>
                </div>
                <div class="book-body">
                  <ul class="location-lists">
                    <li>
                      <h6>Payment Mode</h6>
                      <p><?= ucfirst($b['payment']['method'] ?? 'Card') ?></p>
                    </li>
                    <li>
                      <h6>Transaction ID</h6>
                      <p><span>#<?= h($b['order']['order_number'] ?? '13245454455454') ?></span></p>
                    </li>
                  </ul>
                </div>
              </div>
            </div>
            <!-- /Payment  Details -->

            <!-- Additional Information -->
            <div class="col-lg-12">
              <div class="book-card mb-0">
                <div class="book-head">
                  <h6>Additional Information</h6>
                </div>
                <div class="book-body">
                  <ul class="location-lists">
                    <li>
                      <p><?= h($b['driver']['notes'] ?? 'Rental companies typically require customers to return the vehicle with a full tank of fuel. If the vehicle is returned with less than a full tank, customers may be charged for refueling the vehicle at a premium rate, often higher than local fuel prices.') ?></p>
                    </li>
                  </ul>
                </div>
              </div>
            </div>
            <!-- /Additional Information -->

          </div>
        </div>
        <div class="print-btn text-center mt-3">
          <a href="javascript:window.print();" class="btn btn-secondary">Print Order</a>
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
										<form action="booking-success.html">
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
  <script src="assets/js/script.js"></script>
</body>
</html>
