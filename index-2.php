<?php
require_once __DIR__ . '/config/database.php';

// tiny esc helper
function h($v) { return htmlspecialchars((string)$v, ENT_QUOTES, 'UTF-8'); }
function imgp($p){ if(!$p) return ''; if(strpos($p, '../')===0) return substr($p, 3); return $p; }

// fetch a few latest cars to showcase (optional)
$cars = [];
try {
    $stmt = $pdo->query("SELECT id, name, brand, model, car_type, featured_image, main_location, daily_price, transmission, odometer, fuel_type, year_of_car, passengers, seats FROM tblcars WHERE status IS NULL OR TRIM(status) = '' OR LOWER(TRIM(status)) = 'active' ORDER BY created_at DESC LIMIT 6");
  $cars = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (Throwable $e) {
  $cars = [];
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0">
    <title>Dreams Rent | Template</title>

    <link rel="shortcut icon" href="assets/img/favicon.png">
    <link rel="stylesheet" href="assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/plugins/fontawesome/css/fontawesome.min.css">
    <link rel="stylesheet" href="assets/plugins/fontawesome/css/all.min.css">
    <link rel="stylesheet" href="assets/plugins/select2/css/select2.min.css">
    <link rel="stylesheet" href="assets/css/bootstrap-datetimepicker.min.css">
    <link rel="stylesheet" href="assets/plugins/aos/aos.css">
    <link rel="stylesheet" href="assets/css/feather.css">
    <link rel="stylesheet" href="assets/css/owl.carousel.min.css">
    <link rel="stylesheet" href="assets/plugins/boxicons/css/boxicons.min.css">
    <link rel="stylesheet" href="assets/css/style.css">
        <style>
            /* Uniform thumbnails across cards */
            .card-thumb{width:100%;height:401px;object-fit:cover;background:#f5f5f7;border-radius:8px}
            .thumb-wrap{position:relative}
            /* Rely on theme defaults for .featured-text to avoid stretching */
        </style>
</head>
<body>
<div class="main-wrapper">

    <!-- Header (kept as in HTML, but link to PHP) -->
    <header class="header">
        <div class="container-fluid">
            <nav class="navbar navbar-expand-lg header-nav">
                <div class="navbar-header">
                    <a id="mobile_btn" href="javascript:void(0);">
                        <span class="bar-icon"><span></span><span></span><span></span></span>
                    </a>
                    <a href="index-2.php" class="navbar-brand logo">
                        <img src="assets/img/logo.svg" class="img-fluid" alt="Logo">
                    </a>
                    <a href="index-2.php" class="navbar-brand logo-small">
                        <img src="assets/img/logo-small.png" class="img-fluid" alt="Logo">
                    </a>
                </div>
                <div class="main-menu-wrapper">
                    <div class="menu-header">
                        <a href="index-2.php" class="menu-logo">
                            <img src="assets/img/logo.svg" class="img-fluid" alt="Logo">
                        </a>
                        <a id="menu_close" class="menu-close" href="javascript:void(0);"> <i class="fas fa-times"></i></a>
                    </div>
                    <ul class="main-nav">
                        <li class="has-submenu megamenu active">
                            <a href="index-2.php">Home <i class="fas fa-chevron-down"></i></a>
                            <ul class="submenu mega-submenu"><li><div class="megamenu-wrapper"><div class="row"></div></div></li></ul>
                        </li>
                        <li class="has-submenu">
                            <a href="#">Listings <i class="fas fa-chevron-down"></i></a>
                            <ul class="submenu">
                                <li><a href="listing-grid.php">Listing Grid</a></li>
                                <li><a href="listing-list.html">Listing List</a></li>
                                <li><a href="listing-map.html">Listing With Map</a></li>
                                <li><a href="listing-grid.php">Listing Details</a></li>
                            </ul>
                        </li>
                        <li class="has-submenu"><a href="#">Pages <i class="fas fa-chevron-down"></i></a><ul class="submenu"></ul></li>
                        <li class="has-submenu"><a href="#">Blog <i class="fas fa-chevron-down"></i></a><ul class="submenu"></ul></li>
                        <li class="has-submenu"><a href="#">Dashboard <i class="fas fa-chevron-down"></i></a></li>
                        <li class="login-link"><a href="register.html">Sign Up</a></li>
                        <li class="login-link"><a href="login.html">Sign In</a></li>
                    </ul>
                </div>
                <ul class="nav header-navbar-rht">
                    <li class="nav-item"><a class="nav-link header-login" href="login.html"><span><i class="fa-regular fa-user"></i></span>Sign In</a></li>
                    <li class="nav-item"><a class="nav-link header-reg" href="register.html"><span><i class="fa-solid fa-lock"></i></span>Sign Up</a></li>
                </ul>
            </nav>
        </div>
    </header>

    <!-- Banner -->
    <section class="banner-section banner-slider">
        <div class="container">
            <div class="home-banner">
                <div class="row align-items-center">
                    <div class="col-lg-6" data-aos="fade-down">
                        <p class="explore-text"><span><i class="fa-solid fa-thumbs-up me-2"></i></span>100% Trusted car rental platform in the World</p>
                        <h1><span>Find Your Best</span><br>Dream Car for Rental</h1>
                        <p>Experience the ultimate in comfort, performance, and sophistication with our luxury car rentals.</p>
                        <div class="view-all">
                            <a href="listing-grid.php" class="btn btn-view d-inline-flex align-items-center">View all Cars <span><i class="feather-arrow-right ms-2"></i></span></a>
                        </div>
                    </div>
                    <div class="col-lg-6" data-aos="fade-down">
                        <div class="banner-imgs"><img src="assets/img/car-right.png" class="img-fluid aos" alt="bannerimage"></div>
                    </div>
                </div>
            </div>
        </div>
    </section>

        <!-- Search -->	
        <div class="section-search"> 
            <div class="container">	  
                <div class="search-box-banner">
                    <form action="listing-grid.php">
                        <ul class="align-items-center">
                            <li class="column-group-main">
                                <div class="input-block">
                                    <label>Pickup Location</label>												
                                    <div class="group-img">
                                        <input type="text" class="form-control" placeholder="Enter City, Airport, or Address">
                                        <span><i class="feather-map-pin"></i></span>
                                    </div>
                                </div>
                            </li>
                            <li class="column-group-main">						
                                <div class="input-block">																	
                                    <label>Pickup Date</label>
                                </div>
                                <div class="input-block-wrapp">
                                    <div class="input-block date-widget">												
                                        <div class="group-img">
                                        <input type="text" class="form-control datetimepicker" placeholder="04/11/2023">
                                        <span><i class="feather-calendar"></i></span>
                                        </div>
                                    </div>
                                    <div class="input-block time-widge">											
                                        <div class="group-img">
                                        <input type="text" class="form-control timepicker" placeholder="11:00 AM">
                                        <span><i class="feather-clock"></i></span>
                                        </div>
                                    </div>
                                </div>	
                            </li>
                            <li class="column-group-main">						
                                <div class="input-block">																	
                                    <label>Return Date</label>
                                </div>
                                <div class="input-block-wrapp">
                                    <div class="input-block date-widge">												
                                        <div class="group-img">
                                        <input type="text" class="form-control datetimepicker" placeholder="04/11/2023">
                                        <span><i class="feather-calendar"></i></span>
                                        </div>
                                    </div>
                                    <div class="input-block time-widge">											
                                        <div class="group-img">
                                        <input type="text" class="form-control timepicker" placeholder="11:00 AM">
                                        <span><i class="feather-clock"></i></span>
                                        </div>
                                    </div>
                                </div>	
                            </li>
                            <li class="column-group-last">
                                <div class="input-block">
                                    <div class="search-btn">
                                        <button class="btn search-button" type="submit"> <i class="fa fa-search" aria-hidden="true"></i>Search</button>
                                    </div>
                                </div>
                            </li>
                        </ul>
                    </form>	
                </div>
            </div>	
        </div>	
        <!-- /Search -->

        <!-- services -->
        <section class="section services">
            <div class="service-right">
                <img src="assets/img/bg/service-right.svg" class="img-fluid" alt="services right">
            </div>		
            <div class="container">	
                <!-- Heading title-->
                <div class="section-heading" data-aos="fade-down">
                    <h2>How It Works</h2>
                    <p>Booking a car rental is a straightforward process that typically involves the following steps</p>
                </div>
                <!-- /Heading title -->
                <div class="services-work">
                    <div class="row">
                        <div class="col-lg-4 col-md-4 col-12 d-flex" data-aos="fade-down">
                            <div class="services-group service-date flex-fill">
                                <div class="services-icon border-secondary">
                                    <img class="icon-img bg-secondary" src="assets/img/icons/services-icon-01.svg" alt="Choose Locations">
                                </div>
                                <div class="services-content">
                                    <h3>1. Choose Date &  Locations</h3>
                                    <p>Determine the date & location for your car rental. Consider factors such as your travel itinerary, pickup/drop-off locations (e.g., airport, city center), and duration of rental.</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-4 col-md-4 col-12 d-flex" data-aos="fade-down">
                            <div class="services-group service-loc flex-fill">
                                <div class="services-icon border-warning">
                                    <img class="icon-img bg-warning" src="assets/img/icons/services-icon-02.svg" alt="Choose Locations">
                                </div>
                                <div class="services-content">
                                    <h3>2. Pick-Up Locations</h3>
                                    <p>Check the availability of your desired vehicle type for your chosen dates and location. Ensure that the rental rates, taxes, fees, and any additional charges.</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-4 col-md-4 col-12 d-flex" data-aos="fade-down">
                            <div class="services-group service-book flex-fill">
                                <div class="services-icon border-dark">
                                    <img class="icon-img bg-dark" src="assets/img/icons/services-icon-03.svg" alt="Choose Locations">
                                </div>
                                <div class="services-content">
                                    <h3>3. Book your Car</h3>
                                    <p>Once you've found car rental option, proceed to make a reservation. Provide the required information, including your details, driver's license, contact info, and payment details.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <!-- /services -->

    <!-- Optional: Latest cars preview replacing a portion of Popular Cars section -->
    <section class="section popular-services popular-explore">
        <div class="container">
        <div class="section-heading" data-aos="fade-down">
        <h2>Explore Most Popular Cars</h2>
        <p>Discover great choices available now</p>
            </div>
            <!-- Brand chips (static) -->
            <div class="row justify-content-center">
					<div class="col-lg-12" data-aos="fade-down">
						<div class="listing-tabs-group">
							<ul class="nav nav-tabs listing-buttons gap-3" data-bs-tabs="tabs">
								<li>
									<a class="active" aria-current="true" data-bs-toggle="tab" href="#Carall">
										All
									</a>
								</li>
								<li>
									<a data-bs-toggle="tab" href="#Carmazda">
										<span>
											<img src="assets/img/icons/car-icon-01.svg" alt="Mazda">
										</span>
										Mazda
									</a>
								</li>
								<li>
									<a data-bs-toggle="tab" href="#Caraudi">
										<span>
											<img src="assets/img/icons/car-icon-02.svg" alt="Audi">
										</span>
										Audi
									</a>
								</li>
								<li>
									<a data-bs-toggle="tab" href="#Carhonda">
										<span>
											<img src="assets/img/icons/car-icon-03.svg" alt="Honda">
										</span>
										Honda
									</a>
								</li>
								<li>
									<a data-bs-toggle="tab" href="#Cartoyota">
										<span>
											<img src="assets/img/icons/car-icon-04.svg" alt="Toyota">
										</span>
										Toyota
									</a>
								</li>
								<li>
									<a data-bs-toggle="tab" href="#Caracura">
										<span>
											<img src="assets/img/icons/car-icon-05.svg" alt="Acura">
										</span>
										Acura 
									</a>
								</li>
								<li>
									<a data-bs-toggle="tab" href="#Cartesla">
										<span>
											<img src="assets/img/icons/car-icon-06.svg" alt="Tesla">
										</span>
										Tesla 
									</a>
								</li>
							</ul>
						</div>
					</div>
				</div>
			<?php
			// Prepare brand tabs and group cars by brand (case-insensitive)
			$brandTabs = [
				'all'   => ['id' => 'Carall',   'label' => 'All'],
				'mazda' => ['id' => 'Carmazda', 'label' => 'Mazda'],
				'audi' => ['id' => 'Caraudi', 'label' => 'Audi'],
				'honda' => ['id' => 'Carhonda', 'label' => 'Honda'],
				'toyota' => ['id' => 'Cartoyota', 'label' => 'Toyota'],
				'acura' => ['id' => 'Caracura', 'label' => 'Acura'],
				'tesla' => ['id' => 'Cartesla', 'label' => 'Tesla'],
			];
			$byBrand = [];
			foreach ($cars as $c) {
				$b = strtolower(trim((string)($c['brand'] ?? '')));
				if ($b === '') { continue; }
				$byBrand[$b][] = $c;
			}
			?>

			<?php if (!$cars): ?>
				<div class="row">
					<div class="col-12"><div class="alert alert-info">No cars yet. Please add some in Admin.</div></div>
				</div>
			<?php endif; ?>

			<div class="tab-content">
				<?php foreach ($brandTabs as $key => $meta): ?>
					<div class="tab-pane fade<?= $key === 'all' ? ' show active' : '' ?>" id="<?= h($meta['id']) ?>">
						<div class="row">
							<?php
								$list = $key === 'all' ? $cars : ($byBrand[$key] ?? []);
							?>
							<?php if (!empty($list)): ?>
								<?php foreach ($list as $c): ?>
									<div class="col-lg-4 col-md-6 col-12" data-aos="fade-down">
										<div class="listing-item">
											<div class="listing-img thumb-wrap">
												<a href="listing-details.php?id=<?= (int)$c['id'] ?>">
													<img src="<?= h(imgp($c['featured_image']) ?: 'assets/img/cars/car-01.jpg') ?>" class="card-thumb" alt="car">
												</a>
												<span class="featured-text"><?= h($c['brand'] ?: ($c['car_type'] ?: '')) ?></span>
												<div class="fav-item justify-content-end">
													<a href="javascript:void(0)" class="fav-icon"><i class="feather-heart"></i></a>
												</div>
											</div>
											<div class="listing-content">
												<div class="listing-features d-flex align-items-end justify-content-between">
													<div class="list-rating">
														<h3 class="listing-title"><a href="listing-details.php?id=<?= (int)$c['id'] ?>"><?= h($c['name']) ?></a></h3>
														<div class="list-rating">
															<i class="fas fa-star filled"></i>
															<i class="fas fa-star filled"></i>
															<i class="fas fa-star filled"></i>
															<i class="fas fa-star filled"></i>
															<i class="fas fa-star"></i>
															<span>(4.0) 138 Reviews</span>
														</div>
													</div>
													<div class="list-km">
														<span class="km-count"><img src="assets/img/icons/map-pin.svg" alt="loc">3.2m</span>
													</div>
												</div>
												<div class="listing-details-group">
													<ul>
														<li>
															<span><img src="assets/img/icons/car-parts-05.svg" alt="Transmission"></span>
															<p><?= h($c['transmission'] ?: 'Auto') ?></p>
														</li>
														<li>
															<span><img src="assets/img/icons/car-parts-02.svg" alt="Odometer"></span>
															<p><?= h(($c['odometer'] !== null && $c['odometer'] !== '') ? $c['odometer'].' KM' : '—') ?></p>
														</li>
														<li>
															<span><img src="assets/img/icons/car-parts-03.svg" alt="Fuel"></span>
															<p><?= h($c['fuel_type'] ?: 'Petrol') ?></p>
														</li>
													</ul>
													<ul>
														<li>
															<span><img src="assets/img/icons/car-parts-04.svg" alt="Power"></span>
															<p>Power</p>
														</li>
														<li>
															<span><img src="assets/img/icons/car-parts-05.svg" alt="Year"></span>
															<p><?= h($c['year_of_car'] ?: '—') ?></p>
														</li>
														<li>
															<span><img src="assets/img/icons/car-parts-06.svg" alt="Persons"></span>
															<p><?= h(($c['passengers'] ?: $c['seats'] ?: 4)) ?> Persons</p>
														</li>
													</ul>
												</div>
												<div class="listing-location-details">
													<div class="listing-price"><span><i class="feather-map-pin"></i></span><?= h($c['main_location'] ?: '-') ?></div>
													<div class="listing-price"><h6>$<?= number_format((float)$c['daily_price'], 2) ?> <span>/ Day</span></h6></div>
												</div>
												<div class="listing-button">
													<a href="listing-details.php?id=<?= (int)$c['id'] ?>" class="btn btn-order"><span><i class="feather-calendar me-2"></i></span>Rent Now</a>
												</div>
											</div>
										</div>
									</div>
								<?php endforeach; ?>
							<?php else: ?>
								<div class="col-12"><div class="alert alert-info">No <?= h($meta['label']) ?> cars yet.</div></div>
							<?php endif; ?>
						</div>
					</div>
				<?php endforeach; ?>
			</div>
        </div>
    </section>

        <!-- Popular Cartypes -->
		<section class="section popular-car-type">
			<div class="container">
				<!-- Heading title-->
				<div class="section-heading"  data-aos="fade-down">
					<h2>Most Popular Cartypes</h2>
					<p>Most popular worldwide Car Category due to their reliability, affordability, and features.</p>
				</div>
				<!-- /Heading title -->
				<div class="row">
			        <div class="popular-slider-group">
			        	<div class="owl-carousel popular-cartype-slider owl-theme">
							<!-- owl carousel item -->
						    <div class="listing-owl-item">
							    <div class="listing-owl-group">
								    <div class="listing-owl-img">
								        <img src="assets/img/cars/mp-vehicle-01.svg" class="img-fluid" alt="Popular Cartypes">
									</div>
									<h6>Crossover</h6>
									<p>35 Cars</p>								
								</div>
							</div>
							<!-- /owl carousel item -->

							<!-- owl carousel item -->
						    <div class="listing-owl-item">
							    <div class="listing-owl-group">
								    <div class="listing-owl-img">
								        <img src="assets/img/cars/mp-vehicle-02.svg" class="img-fluid" alt="Popular Cartypes">
									</div>
									<h6>Sports Coupe</h6>
									<p>45 Cars</p>								
								</div>
							</div>
							<!-- /owl carousel item -->

							<!-- owl carousel item -->
						    <div class="listing-owl-item">
							    <div class="listing-owl-group">
								    <div class="listing-owl-img">
								        <img src="assets/img/cars/mp-vehicle-03.svg" class="img-fluid" alt="Popular Cartypes">
									</div>
									<h6>Sedan</h6>
									<p>15 Cars</p>								
								</div>
							</div>
							<!-- /owl carousel item -->

							<!-- owl carousel item -->
						    <div class="listing-owl-item">
							    <div class="listing-owl-group">
								    <div class="listing-owl-img">
								        <img src="assets/img/cars/mp-vehicle-04.svg" class="img-fluid" alt="Popular Cartypes">
									</div>
									<h6>Pickup</h6>
									<p>17 Cars</p>								
								</div>
							</div>
							<!-- /owl carousel item -->

							<!-- owl carousel item -->
						    <div class="listing-owl-item">
							    <div class="listing-owl-group">
								    <div class="listing-owl-img">
								        <img src="assets/img/cars/mp-vehicle-05.svg" class="img-fluid" alt="Popular Cartypes">
									</div>
									<h6>Family MPV</h6>
									<p>24 Cars</p>								
								</div>
							</div>
							<!-- /owl carousel item -->
						</div>	
					</div>
				</div>
				<!-- View More -->
				<div class="view-all text-center" data-aos="fade-down">
					<a href="listing-grid.html" class="btn btn-view d-inline-flex align-items-center">View all Cars <span><i class="feather-arrow-right ms-2"></i></span></a>
				</div>
				<!-- View More -->
			</div>
		</section>
		<!-- /Popular Cartypes -->

        <!-- Facts By The Numbers -->
		<section class="section facts-number">
			<div class="facts-left">
				<img src="assets/img/bg/facts-left.png" class="img-fluid" alt="facts left">
			</div>
			<div class="facts-right">
				<img src="assets/img/bg/facts-right.png" class="img-fluid" alt="facts right">
			</div>
			<div class="container">
				<!-- Heading title-->
				<div class="section-heading" data-aos="fade-down">
					<h2 class="title text-white">Facts By The Numbers</h2>
					<p class="description">Here are some dreamsrent interesting facts presented by the numbers</p>
				</div>
				<!-- /Heading title -->
				<div class="counter-group">
			        <div class="row">
						<div class="col-lg-3 col-md-6 col-12 d-flex" data-aos="fade-down">
							<div class="count-group flex-fill">
								<div class="customer-count d-flex align-items-center">
									<div class="count-img">
										<img src="assets/img/icons/bx-heart.svg" alt="Icon">
									</div>
									<div class="count-content">
										<h4><span class="counterUp">16</span>K+</h4>
										<p>Happy Customers</p>
									</div>
								</div>
							</div>
						</div>
						<div class="col-lg-3 col-md-6 col-12 d-flex" data-aos="fade-down">
							<div class="count-group flex-fill">
								<div class="customer-count d-flex align-items-center">
									<div class="count-img">
										<img src="assets/img/icons/bx-car.svg" alt="Icon">
									</div>
									<div class="count-content">
										<h4><span class="counterUp">2547</span>+</h4>
										<p>Count of Cars</p>
									</div>
								</div>
							</div>
						</div>
						<div class="col-lg-3 col-md-6 col-12 d-flex" data-aos="fade-down">
							<div class="count-group flex-fill">
								<div class="customer-count d-flex align-items-center">
									<div class="count-img">
										<img src="assets/img/icons/bx-headphone.svg" alt="Icon">
									</div>
									<div class="count-content">
										<h4><span class="counterUp">625</span>+</h4>
										<p>Car Center Solutions</p>
									</div>
								</div>
							</div>
						</div>
						<div class="col-lg-3 col-md-6 col-12 d-flex" data-aos="fade-down">
							<div class="count-group flex-fill">
								<div class="customer-count d-flex align-items-center">
									<div class="count-img">
										<img src="assets/img/icons/bx-history.svg" alt="Icon">
									</div>
									<div class="count-content">
										<h4><span class="counterUp">15000</span>+</h4>
										<p>Total Kilometer</p>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</section>
		<!-- /Facts By The Numbers -->

        <!-- Recommended Car Rental deals -->
        <section class="section popular-services">
            <div class="container">
                <div class="section-heading" data-aos="fade-down">
                    <h2>Recommended Car Rental deals</h2>
                    <p>Versatile options that cater to different needs</p>
                </div>
                <div class="row">
                    <div class="popular-slider-group">
                        <div class="owl-carousel rental-deal-slider owl-theme">
                            <div class="rental-car-item">
                                <div class="listing-item pb-0">
                                    <div class="listing-img thumb-wrap"><a href="#"><img src="assets/img/cars/car-03.jpg" alt="car" class="card-thumb"></a><span class="featured-text">Audi</span></div>
                                    <div class="listing-content"><h3 class="listing-title"><a href="#">Audi A3 2019 new</a></h3><div class="listing-location-details d-flex justify-content-between"><div><i class="feather-map-pin"></i> Newyork, USA</div><div><strong>$45</strong> <span>/ Day</span></div></div></div>
                                </div>
                            </div>
                            <div class="rental-car-item">
                                <div class="listing-item pb-0">
                                    <div class="listing-img thumb-wrap"><a href="#"><img src="assets/img/cars/car-05.jpg" alt="car" class="card-thumb"></a><span class="featured-text">KIA</span></div>
                                    <div class="listing-content"><h3 class="listing-title"><a href="#">KIA Soul 2016</a></h3><div class="listing-location-details d-flex justify-content-between"><div><i class="feather-map-pin"></i> Belgium</div><div><strong>$80</strong> <span>/ Day</span></div></div></div>
                                </div>
                            </div>
                            <div class="rental-car-item">
                                <div class="listing-item pb-0">
                                    <div class="listing-img thumb-wrap"><a href="#"><img src="assets/img/cars/car-01.jpg" alt="car" class="card-thumb"></a><span class="featured-text">Toyota</span></div>
                                    <div class="listing-content"><h3 class="listing-title"><a href="#">Toyota Camry SE 350</a></h3><div class="listing-location-details d-flex justify-content-between"><div><i class="feather-map-pin"></i> Washington</div><div><strong>$160</strong> <span>/ Day</span></div></div></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="view-all text-center" data-aos="fade-down"><a href="listing-grid.php" class="btn btn-view d-inline-flex align-items-center">Go to all Cars <span><i class="feather-arrow-right ms-2"></i></span></a></div>
            </div>
        </section>

        <!-- Why Choose Us -->
		<section class="section why-choose popular-explore">
			<div class="choose-left">
				<img src="assets/img/bg/choose-left.png" class="img-fluid" alt="Why Choose Us">
			</div>		
			<div class="container">	
				<!-- Heading title-->
				<div class="row">
					<div class="col-lg-4 mx-auto">
						<div class="section-heading" data-aos="fade-down">
							<h2>Why Choose Us</h2>
							<p>We are innovative and passionate about the work we do. </p>
						</div>
					</div>
				</div>
				<!-- /Heading title -->
				<div class="why-choose-group">
			        <div class="row">
						<div class="col-lg-4 col-md-6 col-12 d-flex" data-aos="fade-down">
							<div class="card flex-fill">
								<div class="card-body">
									<div class="choose-img choose-black">
										<img src="assets/img/icons/bx-selection.svg" alt="Icon">
									</div>
									<div class="choose-content">
										<h4>Easy & Fast Booking</h4>
										<p>Completely carinate e business testing process whereas fully researched customer service. Globally extensive content with quality.</p>
									</div>
								</div>
							</div>
						</div>
						<div class="col-lg-4 col-md-6 col-12 d-flex" data-aos="fade-down">
							<div class="card flex-fill">
								<div class="card-body">
									<div class="choose-img choose-secondary">
										<img src="assets/img/icons/bx-crown.svg" alt="Icon">
									</div>
									<div class="choose-content">
										<h4>Many Pickup Location</h4>
										<p>Enthusiastically magnetic initiatives with cross-platform sources. Dynamically target testing procedures through effective.</p>
									</div>
								</div>
							</div>
						</div>
						<div class="col-lg-4 col-md-6 col-12 d-flex" data-aos="fade-down">
							<div class="card flex-fill">
								<div class="card-body">
									<div class="choose-img choose-primary">
										<img src="assets/img/icons/bx-user-check.svg" alt="Icon">
									</div>
									<div class="choose-content">
										<h4>Customer Satisfaction</h4>
										<p>Globally user centric method interactive. Seamlessly revolutionize unique portals orporate collaboration.</p>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</section>
		<!-- /Why Choose Us -->

		<!-- About us Testimonials -->
		<section class="section about-testimonial testimonials-section">
			<div class="container">
				<!-- Heading title-->
				<div class="section-heading" data-aos="fade-down">
					<h2 class="title text-white">What People say about us? </h2>
					<p class="description text-white">Discover what our customers have think about us</p>
				</div>
				<!-- /Heading title -->
				<div class="owl-carousel about-testimonials testimonial-group mb-0 owl-theme">

					<!-- Carousel Item -->
					<div class="testimonial-item d-flex">							
						<div class="card flex-fill">
							<div class="card-body">								
								<div class="quotes-head"></div>
								<div class="review-box">
									<div class="review-profile">
										<div class="review-img">
											<img src="assets/img/profiles/avatar-02.jpg" class="img-fluid" alt="img">
										</div>															
									</div>
									<div class="review-details">
										<h6>Rabien Ustoc</h6>
										<p>Newyork, USA</p>												
									</div>
								</div>									
								<p>Renting a car from Dreams rent made my vacation so much smoother! The process was quick and easy, the car was clean and well-maintained, and the staff were friendly and helpful.</p>
								<div class="list-rating">
									<div class="list-rating-star">
										<i class="fas fa-star filled"></i>
										<i class="fas fa-star filled"></i>
										<i class="fas fa-star filled"></i>
										<i class="fas fa-star filled"></i>
										<i class="fas fa-star filled"></i>
									</div>
									<p><span>(5.0)</span></p>
								</div>							
							</div>
						</div>
					</div>
					<!-- /Carousel Item  -->

					<!-- Carousel Item -->
					<div class="testimonial-item d-flex">							
						<div class="card flex-fill">
							<div class="card-body">								
								<div class="quotes-head"></div>
								<div class="review-box">
									<div class="review-profile">
										<div class="review-img">
											<img src="assets/img/profiles/avatar-03.jpg" class="img-fluid" alt="img">
										</div>															
									</div>
									<div class="review-details">
										<h6>Valerie L. Ellis</h6>
										<p>Las Vegas, USA</p>												
									</div>
								</div>									
								<p>As a frequent business traveller, I rely on Dreams rent for all my needs. Their wide selection of vehicles, convenient locations, and competitive prices make them my go-to choice every time. Plus, their customer service is top-notch!</p>
								<div class="list-rating">
									<div class="list-rating-star">
										<i class="fas fa-star filled"></i>
										<i class="fas fa-star filled"></i>
										<i class="fas fa-star filled"></i>
										<i class="fas fa-star filled"></i>
										<i class="fas fa-star filled"></i>
									</div>
									<p><span>(5.0)</span></p>
								</div>							
							</div>
						</div>
					</div>
					<!-- /Carousel Item  -->

					<!-- Carousel Item -->
					<div class="testimonial-item d-flex">							
						<div class="card flex-fill">
							<div class="card-body">								
								<div class="quotes-head"></div>
								<div class="review-box">
									<div class="review-profile">
										<div class="review-img">
											<img src="assets/img/profiles/avatar-04.jpg" class="img-fluid" alt="img">
										</div>															
									</div>
									<div class="review-details">
										<h6>Laverne Marier</h6>
										<p>Nevada, USA</p>													
									</div>
								</div>									
								<p>Renting a car from Dreams rent made our family vacation unforgettable and top-notch customer service. The spacious SUV we rented comfortably fit our family and all our luggage, and it was a smooth ride throughout our trip.</p>	
								<div class="list-rating">
									<div class="list-rating-star">
										<i class="fas fa-star filled"></i>
										<i class="fas fa-star filled"></i>
										<i class="fas fa-star filled"></i>
										<i class="fas fa-star filled"></i>
										<i class="fas fa-star filled"></i>
									</div>
									<p><span>(5.0)</span></p>
								</div>						
							</div>
						</div>
					</div>
					<!-- /Carousel Item  -->

					<!-- Carousel Item -->
					<div class="testimonial-item d-flex">							
						<div class="card flex-fill">
							<div class="card-body">								
								<div class="quotes-head"></div>
								<div class="review-box">
									<div class="review-profile">
										<div class="review-img">
											<img src="assets/img/profiles/avatar-06.jpg" class="img-fluid" alt="img">
										</div>															
									</div>
									<div class="review-details">
										<h6>Sydney Salmons</h6>
										<p>Newyork, USA</p>											
									</div>
								</div>									
								<p>As a frequent business traveller, I rely on Dreams rent for all my needs. Their wide selection of vehicles, convenient locations, and competitive prices make them my go-to choice every time. Plus, their customer service is top-notch!</p>	
								<div class="list-rating">
									<div class="list-rating-star">
										<i class="fas fa-star filled"></i>
										<i class="fas fa-star filled"></i>
										<i class="fas fa-star filled"></i>
										<i class="fas fa-star filled"></i>
										<i class="fas fa-star filled"></i>
									</div>
									<p><span>(5.0)</span></p>
								</div>						
							</div>
						</div>
					</div>
					<!-- /Carousel Item  -->

					<!-- Carousel Item -->
					<div class="testimonial-item d-flex">							
						<div class="card flex-fill">
							<div class="card-body">								
								<div class="quotes-head"></div>
								<div class="review-box">
									<div class="review-profile">
										<div class="review-img">
											<img src="assets/img/profiles/avatar-07.jpg" class="img-fluid" alt="img">
										</div>															
									</div>
									<div class="review-details">
										<h6>Lucas Moquin</h6>
										<p>Nevada, USA</p>																	
									</div>
								</div>									
								<p>Renting a car from Dreams rent made our family vacation unforgettable and top-notch customer service. The spacious SUV we rented comfortably fit our family and all our luggage, and it was a smooth ride throughout our trip.</p>	
								<div class="list-rating">
									<div class="list-rating-star">
										<i class="fas fa-star filled"></i>
										<i class="fas fa-star filled"></i>
										<i class="fas fa-star filled"></i>
										<i class="fas fa-star filled"></i>
										<i class="fas fa-star filled"></i>
									</div>
									<p><span>(5.0)</span></p>
								</div>	
							</div>
						</div>
					</div>
					<!-- /Carousel Item  -->
				</div>
			</div>
		</section>
		<!-- About us Testimonials -->

		<!-- FAQ  -->
		<section class="section faq-section bg-light-primary">
			<div class="container">				
				<!-- Heading title-->
				<div class="section-heading" data-aos="fade-down">
					<h2>Frequently Asked Questions </h2>
					<p>Find answers to your questions from our previous answers</p>
				</div>
				<!-- /Heading title -->
				<div class="faq-info">
					<div class="faq-card bg-white" data-aos="fade-down">
						<h4 class="faq-title">
							<a class="collapseds" data-bs-toggle="collapse" href="#faqOne" aria-expanded="true">How old do I need to be to rent a car?</a>
						</h4>
						<div id="faqOne" class="card-collapse collapse show">
							<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.</p>
						</div>
					</div>	
					<div class="faq-card bg-white" data-aos="fade-down">
						<h4 class="faq-title">
							<a class="collapsed" data-bs-toggle="collapse" href="#faqTwo" aria-expanded="false">What documents do I need to rent a car?</a>
						</h4>
						<div id="faqTwo" class="card-collapse collapse">
							<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.</p>
						</div>
					</div>
					<div class="faq-card bg-white" data-aos="fade-down">
						<h4 class="faq-title">
							<a class="collapsed" data-bs-toggle="collapse" href="#faqThree" aria-expanded="false">What types of vehicles are available for rent?</a>
						</h4>
						<div id="faqThree" class="card-collapse collapse">
							<p>We offer a diverse fleet of vehicles to suit every need, including compact cars, sedans, SUVs and luxury vehicles. You can browse our selection online or contact us for assistance in choosing the right vehicle for you</p>
						</div>
					</div>	
					<div class="faq-card bg-white" data-aos="fade-down">
						<h4 class="faq-title">
							<a class="collapsed" data-bs-toggle="collapse" href="#faqFour" aria-expanded="false">Can I rent a car with a debit card?</a>
						</h4>
						<div id="faqFour" class="card-collapse collapse">
							<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.</p>
						</div>
					</div>	
					<div class="faq-card bg-white" data-aos="fade-down">
						<h4 class="faq-title">
							<a class="collapsed" data-bs-toggle="collapse" href="#faqFive" aria-expanded="false">What is your fuel policy?</a>
						</h4>
						<div id="faqFive" class="card-collapse collapse">
							<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.</p>
						</div>
					</div>	
					<div class="faq-card bg-white" data-aos="fade-down">
						<h4 class="faq-title">
							<a class="collapsed" data-bs-toggle="collapse" href="#faqSix" aria-expanded="false">Can I add additional drivers to my rental agreement?</a>
						</h4>
						<div id="faqSix" class="card-collapse collapse">
							<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.</p>
						</div>
					</div>	
					<div class="faq-card bg-white" data-aos="fade-down">
						<h4 class="faq-title">
							<a class="collapsed" data-bs-toggle="collapse" href="#faqSeven" aria-expanded="false">What happens if I return the car late?</a>
						</h4>
						<div id="faqSeven" class="card-collapse collapse">
							<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.</p>
						</div>
					</div>													
				</div>		
		    </div>	
		</section>	
		<!-- /FAQ -->

		<!-- Pricing Plan -->
		<section class="pricing-section pricing-page pricing-section-bottom">
			<div class="container">
				<!-- Heading title-->
				<div class="section-heading" data-aos="fade-down">
					<h2>Transparent Pricing For you</h2>
					<p>Choose a package that suits you</p>
				</div>
				<!-- /Heading title -->
									
				<!-- Plan Selected -->
				<div class="plan-selected" data-aos="fade-down">
					<h4>Monthly</h4>
					<div class="status-toggle me-2 ms-2">
						<input id="list-rating_1" class="px-4 check" type="checkbox" checked>
						<label for="list-rating_1" class="px-4 checktoggle checkbox-bg">checkbox</label>
					</div>
					<h4>Annually</h4>
				</div>
				<!-- /Plan Selected -->
				<div class="row">
					<div class="col-lg-3 d-flex col-md-6 col-12" data-aos="fade-down">
						<div class="price-card price-selected flex-fill">
							<div class="price-head">
								<h2>Save more with Good Plans</h2>	
								<p>Choose a plan and get onboard in Minutes, then get $100 with next payment</p>
								<a href="javascript:void(0);"><i class="bx bx-right-arrow-alt"></i></a>			
							</div>	
							<div class="price-body">
								<img class="img-fluid" src="assets/img/price-plan.png" alt="Price Plan">		
							</div>							
						</div>
				   	</div>
					<div class="col-lg-3 d-flex col-md-6 col-12" data-aos="fade-down">
						<div class="price-card flex-fill">
							<div class="price-head">
								<div class="price-level">
									<h6>Basic Rental </h6>
									<p>For the basics</p>
								</div>
								<h4>$49</h4>	
								<span>Per user per month</span>							
							</div>	
							<div class="price-details">
								<ul>
								 	<li class="price-check"><span><i class="fa-regular fa-circle-check"></i></span>50% Downpayment</li>
									<li class="price-check"><span><i class="fa-regular fa-circle-check"></i></span>Insurance not Included</li>
									<li class="price-check"><span><i class="fa-regular fa-circle-check"></i></span>Doorstep delivery</li>
									<li class="price-uncheck"><span><i class="fa-regular fa-circle-xmark"></i></span>Safe & Sanitized</li>
									<li class="price-uncheck"><span><i class="fa-regular fa-circle-xmark"></i></span>No Long term Commitment</li>
									<li class="price-uncheck"><span><i class="fa-regular fa-circle-xmark"></i></span>Refundable deposit has to pay</li>
									<li class="price-uncheck"><span><i class="fa-regular fa-circle-xmark"></i></span>No Flexible timing & extension</li>
								</ul>
								<a href="login.html" class="btn viewdetails-btn">Buy Package</a>							
							</div>							
						</div>
				   	</div>
					<div class="col-lg-3 d-flex col-md-6 col-12" data-aos="fade-down">
						<div class="price-card flex-fill active">
						 	<div class="price-head">
							 	<div class="price-level price-level-popular">
								 	<h6>Recommended</h6>
								 	<p>For the Users</p>
							 	</div>
							 	<h4>$95</h4>	
							 	<span>Per user per month</span>							
						 	</div>		
							<div class="price-details">
								<ul>
									<li class="price-check"><span><i class="fa-regular fa-circle-check"></i></span>50% Downpayment</li>
									<li class="price-check"><span><i class="fa-regular fa-circle-check"></i></span>Insurance not Included</li>
									<li class="price-check"><span><i class="fa-regular fa-circle-check"></i></span>Doorstep delivery</li>
									<li class="price-check"><span><i class="fa-regular fa-circle-check"></i></span>Safe & Sanitized</li>
									<li class="price-check"><span><i class="fa-regular fa-circle-check"></i></span>Long term Commitment 1 month</li>
									<li class="price-uncheck"><span><i class="fa-regular fa-circle-xmark"></i></span>Refundable deposit has to pay</li>
									<li class="price-uncheck"><span><i class="fa-regular fa-circle-xmark"></i></span>No Flexible timing & extension</li>
								</ul>
								<a href="login.html" class="btn viewdetails-btn btn-popular">Buy Package</a>							
							</div>							
						</div>
					</div>
					<div class="col-lg-3 d-flex col-md-6 col-12" data-aos="fade-down">
						<div class="price-card flex-fill">
							<div class="price-head">
								<div class="price-level">
									<h6>Pro</h6>
										<p>For the Pro</p>
							 		</div>
							 		<h4>$154</h4>	
									<span>Per user per month</span>							
						 		</div>	
						 		<div class="price-details">
							 		<ul>
										<li class="price-check"><span><i class="fa-regular fa-circle-check"></i></span>50% Downpayment</li>
										<li class="price-check"><span><i class="fa-regular fa-circle-check"></i></span>Insurance not Included</li>
										<li class="price-check"><span><i class="fa-regular fa-circle-check"></i></span>Doorstep delivery</li>
										<li class="price-check"><span><i class="fa-regular fa-circle-check"></i></span>Safe & Sanitized</li>
										<li class="price-check"><span><i class="fa-regular fa-circle-check"></i></span>Long term Commitment 1 month</li>
										<li class="price-check"><span><i class="fa-regular fa-circle-check"></i></span>No Refundable deposit</li>
										<li class="price-check"><span><i class="fa-regular fa-circle-check"></i></span>No Flexible timing & extension</li>
							 		</ul>
								<a href="login.html" class="btn viewdetails-btn">Buy Package</a>						
							</div>							
					 	</div>
					</div>
				</div>

				<!-- App Available -->
				<div class="user-app-group">
					<div class="app-left">
						<img src="assets/img/bg/app-left.png" class="img-fluid" alt="App Available">
					</div>
					<div class="app-right">
						<img src="assets/img/bg/app-right.png" class="img-fluid" alt="App Available">
					</div>
					<div class="row">
						<div class="col-lg-7">
							<div class="userapp-heading">
								<h2 data-aos="fade-down">Dreamsrental User Friendly App Available</h2>
								<p data-aos="fade-down">Appropriately monetize one-to-one interfaces rather than cutting-edge Competently disinte rmediate backward.</p>
								<div class="download-btn">
									<div class="app-avilable" data-aos="fade-down">								
										<a href="javascript:void(0)"><img src="assets/img/play-store.svg" alt="PlayStore"></a>
									</div>
									<div class="app-avilable" data-aos="fade-down">								
										<a href="javascript:void(0)"><img src="assets/img/apple.svg" alt="AppStore"></a>
									</div>
								</div>
							</div>
						</div>
						<div class="col-lg-5 d-none d-lg-block">
							<div class="car-holder">
								<img class="app-car img-fluid" src="assets/img/app-car.png" alt="App Available"  data-aos="fade-down">
							</div>
						</div>
					</div>
				</div>
				<!-- /App Available -->

			</div>
		</section>
		<!-- /Pricing Plan -->

		<!-- Blog Section -->
		<section class="blog-section news-section pt-0">
			<div class="container">
				<!-- Heading title-->
				<div class="section-heading" data-aos="fade-down">
					<h2>News & Insights For You</h2>
					<p>This blog post provides valuable insights into the benefits</p>
				</div>
				<!-- /Heading title -->

				<div class="row">
					<div class="col-lg-4 col-md-6 d-lg-flex">
						<div class="blog grid-blog">
							<div class="blog-image">
								<a href="blog-details.html"><img class="img-fluid" src="assets/img/blog/blog-4.jpg" alt="Post Image"></a>
							</div>
							<div class="blog-content">
								 <p class="blog-category">
								   <a href="javascript:void(0)"><span>Journey</span></a>
								</p>
								<h3 class="blog-title"><a href="blog-details.html">The 2023 Ford F-150 Raptor – A First Look</a></h3>
								<p class="blog-description">Covers all aspects of the automotive industry with a focus on accessibility and consumer relevance.....</p>
								<ul class="meta-item mb-0">
									<li>
										<div class="post-author">
											<div class="post-author-img">
												<img src="assets/img/profiles/avatar-04.jpg" alt="author">
											</div>
											<a href="javascript:void(0)"> <span> Hellan </span></a>
										</div>
									</li>
									<li class="date-icon"><i class="fa-solid fa-calendar-days"></i> <span>October 6, 2022</span></li>
								</ul>
							</div>
						</div>
					</div>

					<div class="col-lg-4 col-md-6 d-lg-flex">
						<div class="blog grid-blog">
						  <div class="blog-image">
							  <a href="blog-details.html"><img class="img-fluid" src="assets/img/blog/blog-3.jpg" alt="Post Image"></a>
						  </div>
						  <div class="blog-content">
							   <p class="blog-category">
								 <a href="javascript:void(0)"><span>Tour & tip</span></a>
							  </p>
							  <h3 class="blog-title"><a href="blog-details.html">Tesla Model S: Top Secret Car Collector’s Garage</a></h3>
							  <p class="blog-description">Catering to driving enthusiasts, Road & Track provides engaging content on...</p>
							  <ul class="meta-item mb-0">
								  <li>
									  <div class="post-author">
										  <div class="post-author-img">
											  <img src="assets/img/profiles/avatar-13.jpg" alt="author">
										  </div>
										  <a href="javascript:void(0)"> <span> Alphonsa Daniel </span></a>
									  </div>
								  </li>
								  <li class="date-icon"><i class="fa-solid fa-calendar-days"></i> <span>March 6, 2023</span></li>
							  </ul>
						  </div>
						</div>
					</div>					

					<div class="col-lg-4 col-md-6 d-lg-flex">
						<div class="blog grid-blog">
						  <div class="blog-image">
							  <a href="blog-details.html"><img class="img-fluid" src="assets/img/blog/blog-10.jpg" alt="Post Image"></a>
						  </div>
						  <div class="blog-content">
							   <p class="blog-category">
								 <a href="javascript:void(0)"><span>Updates</span></a>
							  </p>
							  <h3 class="blog-title"><a href="blog-details.html">Dedicated To Cars, Covering Everything</a></h3>
							  <p class="blog-description">Known for its irreverent take on car culture, offers a mix of news, reviews...</p>
							  <ul class="meta-item mb-0">
								  <li>
									  <div class="post-author">
										  <div class="post-author-img">
											  <img src="assets/img/profiles/avatar-13.jpg" alt="author">
										  </div>
										  <a href="javascript:void(0)"> <span> Hellan</span></a>
									  </div>
								  </li>
								  <li class="date-icon"><i class="fa-solid fa-calendar-days"></i> <span>March 6, 2023</span></li>
							  </ul>
						  </div>
						</div>
					</div>
				</div>
				<div class="view-all text-center aos-init aos-animate" data-aos="fade-down">
					<a href="blog-details.html" class="btn btn-view d-inline-flex align-items-center">View all Blogs <span><i class="feather-arrow-right ms-2"></i></span></a>
				</div>

			</div>
		</section>
		<!-- /Blog Section -->

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
										<form action="#">
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

<script src="assets/js/jquery-3.7.1.min.js"></script>
<script src="assets/js/bootstrap.bundle.min.js"></script>
<script src="assets/plugins/aos/aos.js"></script>
<script src="assets/plugins/select2/js/select2.min.js"></script>
<script src="assets/js/backToTop.js"></script>
<script src="assets/js/owl.carousel.min.js"></script>
<script src="assets/plugins/moment/moment.min.js"></script>
<script src="assets/js/bootstrap-datetimepicker.min.js"></script>
<script src="assets/js/script.js"></script>
<script>AOS.init();</script>
</body>
</html>
