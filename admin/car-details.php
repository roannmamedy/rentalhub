<?php
session_start();
require_once '../config/database.php';

function h($v){return htmlspecialchars((string)$v, ENT_QUOTES, 'UTF-8');}

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if ($id <= 0) {
  $_SESSION['error_message'] = 'Invalid car ID';
  header('Location: cars.php');
  exit;
}

// Fetch main car
$car = null;
try {
  $stmt = $pdo->prepare("SELECT * FROM tblcars WHERE id = :id");
  $stmt->execute([':id' => $id]);
  $car = $stmt->fetch(PDO::FETCH_ASSOC);
  if (!$car) {
    $_SESSION['error_message'] = 'Car not found';
    header('Location: cars.php');
    exit;
  }
} catch (Throwable $e) {
  $_SESSION['error_message'] = 'Failed to load car: ' . $e->getMessage();
  header('Location: cars.php');
  exit;
}

// Fetch gallery images
$images = [];
try {
  $g = $pdo->prepare('SELECT image_path FROM car_images WHERE car_id = :id ORDER BY id ASC');
  $g->execute([':id' => $id]);
  $images = $g->fetchAll(PDO::FETCH_COLUMN);
} catch (Throwable $e) { /* ignore */ }

// Fetch damages
$damages = [];
try {
  $sqlDamages = 'SELECT id, location, `type` AS damage_type, description, date_added
                 FROM car_damages
                 WHERE car_id = :id
                 ORDER BY COALESCE(date_added, "0000-00-00") DESC, id DESC';
  $d = $pdo->prepare($sqlDamages);
  $d->execute([':id' => $id]);
  $damages = $d->fetchAll(PDO::FETCH_ASSOC);
} catch (Throwable $e) { /* ignore */ }

// Fetch insurance (latest if many)
$insurance = null;
try {
  $ins = $pdo->prepare('SELECT insurance_name, price, benefits FROM car_insurance WHERE car_id = :id ORDER BY id DESC LIMIT 1');
  $ins->execute([':id' => $id]);
  $insurance = $ins->fetch(PDO::FETCH_ASSOC);
} catch (Throwable $e) { /* ignore */ }

// Decode extras/features
// Decode helper with double-decode fallback
function decode_array_field($value) {
  if ($value === null || $value === '') return [];
  $first = json_decode($value, true);
  if (is_array($first)) return $first;
  // If value was encoded twice or escaped, try un-escaping
  $stripped = trim($value, "\"'");
  $stripped = str_replace(['\\"','\\\\'], ['"','\\'], $stripped);
  $second = json_decode($stripped, true);
  return is_array($second) ? $second : [];
}

$features = decode_array_field($car['features_amenities'] ?? null);
$extras = decode_array_field($car['extra_services'] ?? null);

?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0">
  <title>Car Details</title>
  <link rel="stylesheet" href="assets/css/bootstrap.min.css">
  <link rel="stylesheet" href="assets/plugins/tabler-icons/tabler-icons.min.css">
  <link rel="stylesheet" href="assets/plugins/fancybox/jquery.fancybox.min.css">
  <link rel="stylesheet" href="assets/css/style.css">
  <script src="assets/js/theme-script.js"></script>
  <style>
    .car-cover { width: 100%; max-height: 280px; object-fit: cover; border-radius: 8px; }
    .car-thumb { width: 72px; height: 72px; object-fit: cover; border-radius: 6px; }
    .chip { display:inline-block; padding: 2px 8px; border-radius: 12px; font-size: 12px; background: #f6f6f7; margin: 2px; }
  </style>
</head>
<body>
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
              <div class="nav-item dropdown has-arrow flag-nav nav-item-box">
                <a class="nav-link dropdown-toggle" data-bs-toggle="dropdown" href="javascript:void(0);" role="button">
                  <img src="assets/img/flags/gb.svg" alt="Language" class="img-fluid">
                </a>
                <ul class="dropdown-menu p-2">
                  <li><a href="javascript:void(0);" class="dropdown-item"><img src="assets/img/flags/gb.svg" alt="" height="16">English</a></li>
                  <li><a href="javascript:void(0);" class="dropdown-item"><img src="assets/img/flags/sa.svg" alt="" height="16">Arabic</a></li>
                  <li><a href="javascript:void(0);" class="dropdown-item"><img src="assets/img/flags/de.svg" alt="" height="16">German</a></li>
                </ul>
              </div>
              <div class="theme-item">
                <a href="javascript:void(0);" id="dark-mode-toggle" class="theme-toggle btn btn-menubar"><i class="ti ti-moon"></i></a>
                <a href="javascript:void(0);" id="light-mode-toggle" class="theme-toggle btn btn-menubar"><i class="ti ti-sun-high"></i></a>
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
                        <div class="notification-list"><div class="d-flex align-items-center"><a href="javascript:void(0);" class="avatar avatar-lg offline me-2 flex-shrink-0"><img src="assets/img/profiles/avatar-02.jpg" alt="Profile" class="rounded-circle"></a><div class="flex-grow-1"><p class="mb-1"><a href="javascript:void(0);"><span class="text-gray-9">Jerry Manas</span> Added New Task Creating <span class="text-gray-9">Login Pages</span></a></p><span class="fs-12 noti-time"><i class="ti ti-clock me-1"></i>4 Min Ago</span></div></div></div>
                      </div>
                      <div class="tab-pane fade" id="unread-notification"></div>
                      <div class="tab-pane fade" id="archieve-notification"></div>
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
                <a href="income-report.html" class="btn btn-menubar"><i class="ti ti-chart-bar"></i></a>
              </div>
              <div class="dropdown">
                <a href="javascript:void(0);" class="btn btn-menubar" data-bs-toggle="dropdown"  data-bs-auto-close="outside"><i class="ti ti-grid-dots"></i></a>
                <div class="dropdown-menu p-3">
                  <ul>
                    <li><a href="add-car.html" class="dropdown-item d-inline-flex align-items-center"><i class="ti ti-car me-2"></i>Car</a></li>
                    <li><a href="add-quotations.html" class="dropdown-item d-inline-flex align-items-center"><i class="ti ti-file-symlink me-2"></i>Quotation</a></li>
                    <li><a href="pricing.html" class="dropdown-item d-inline-flex align-items-center"><i class="ti ti-file-dollar me-2"></i>Seasonal Pricing</a></li>
                    <li><a href="extra-services.html" class="dropdown-item d-inline-flex align-items-center"><i class="ti ti-script-plus me-2"></i>Extra Service</a></li>
                    <li><a href="inspections.html" class="dropdown-item d-inline-flex align-items-center"><i class="ti ti-dice-6 me-2"></i>Inspection</a></li>
                    <li><a href="maintenance.html" class="dropdown-item d-inline-flex align-items-center"><i class="ti ti-color-filter me-2"></i>Maintenance</a></li>
                  </ul>
                </div>
              </div>
              <div class="dropdown profile-dropdown">
                <a href="javascript:void(0);" class="d-flex align-items-center" data-bs-toggle="dropdown"  data-bs-auto-close="outside"><span class="avatar avatar-sm"><img src="assets/img/profiles/avatar-05.jpg" alt="Img" class="img-fluid rounded-circle"></span></a>
                <div class="dropdown-menu">
                  <div class="profileset d-flex align-items-center"><span class="user-img me-2"><img src="assets/img/profiles/avatar-05.jpg" alt=""></span><div><h6 class="fw-semibold mb-1">Andrew Simmonds</h6><p class="fs-13">andrew@example.com</p></div></div>
                  <a class="dropdown-item d-flex align-items-center" href="profile-setting.html"><i class="ti ti-user-edit me-2"></i>Edit Profile</a>
                  <a class="dropdown-item d-flex align-items-center" href="payments.html"><i class="ti ti-credit-card me-2"></i>Payments</a>
                  <div class="dropdown-divider my-2"></div>
                  <div class="dropdown-item"><div class="form-check form-switch  form-check-reverse  d-flex align-items-center justify-content-between"><label class="form-check-label" for="notify"><i class="ti ti-bell me-2"></i>Notificaions</label><input class="form-check-input" type="checkbox" role="switch" id="notify" checked></div></div>
                  <a class="dropdown-item d-flex align-items-center" href="security-setting.html"><i class="ti ti-exchange me-2"></i>Change Password</a>
                  <a class="dropdown-item d-flex align-items-center" href="profile-setting.html"><i class="ti ti-settings me-2"></i>Settings</a>
                  <div class="dropdown-divider my-2"></div>
                  <a class="dropdown-item logout d-flex align-items-center justify-content-between" href="login.html"><span><i class="ti ti-logout me-2"></i>Logout Account</span> <i class="ti ti-chevron-right"></i></a>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="dropdown mobile-user-menu">
          <a href="javascript:void(0);" class="nav-link dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false"><i class="fa fa-ellipsis-v"></i></a>
          <div class="dropdown-menu dropdown-menu-end">
            <a class="dropdown-item" href="profile.html">My Profile</a>
            <a class="dropdown-item" href="profile-setting.html">Settings</a>
            <a class="dropdown-item" href="login.html">Logout</a>
          </div>
        </div>
      </div>
    </div>
    <!-- /Header -->

    <!-- Sidebar -->
    <div class="sidebar" id="sidebar">
      <div class="sidebar-logo">
        <a href="index.html" class="logo logo-normal"><img src="assets/img/logo.svg" alt="Logo"></a>
        <a href="index.html" class="logo-small"><img src="assets/img/logo-small.svg" alt="Logo"></a>
        <a href="index.html" class="dark-logo"><img src="assets/img/logo-white.svg" alt="Logo"></a>
      </div>
      <div class="sidebar-inner slimscroll">
        <div id="sidebar-menu" class="sidebar-menu">
          <div class="form-group">
            <div class="input-group input-group-flat d-inline-flex">
              <span class="input-icon-addon"><i class="ti ti-search"></i></span>
              <input type="text" class="form-control" placeholder="Search">
              <span class="group-text"><i class="ti ti-command"></i></span>
            </div>
          </div>
          <ul>
            <li class="menu-title"><span>Main</span></li>
            <li><ul><li><a href="index.html"><i class="ti ti-layout-dashboard"></i><span>Dashboard</span></a></li></ul></li>
            <li class="menu-title"><span>Bookings</span></li>
            <li><ul>
              <li><a href="reservations.html"><i class="ti ti-files"></i><span>Reservations</span><span class="track-icon"></span></a></li>
              <li><a href="calendar.html"><i class="ti ti-calendar-bolt"></i><span>Calendar</span></a></li>
              <li><a href="quotations.html"><i class="ti ti-file-symlink"></i><span>Quotations</span></a></li>
              <li><a href="enquiries.html"><i class="ti ti-mail"></i><span>Enquiries</span></a></li>
            </ul></li>
            <li class="menu-title"><span>Manage</span></li>
            <li><ul>
              <li><a href="customers.html"><i class="ti ti-users-group"></i><span>Customers</span></a></li>
              <li><a href="drivers.html"><i class="ti ti-user-bolt"></i><span>Drivers</span></a></li>
              <li><a href="locations.html"><i class="ti ti-map-pin"></i><span>Locations</span></a></li>
            </ul></li>
            <li class="menu-title"><span>RENTALS</span></li>
            <li><ul>
              <li class="active"><a href="cars.php"><i class="ti ti-car"></i><span>Cars</span></a></li>
              <li class="submenu">
                <a href="javascript:void(0);"><i class="ti ti-device-camera-phone"></i><span>Car Attributes</span><span class="menu-arrow"></span></a>
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
              <li><a href="extra-services.html"><i class="ti ti-script-plus"></i><span>Extra Service</span></a></li>
              <li><a href="pricing.html"><i class="ti ti-file-dollar"></i><span>Seasonal Pricing</span></a></li>
              <li><a href="inspections.html"><i class="ti ti-dice-6"></i><span>Inspections</span></a></li>
              <li><a href="tracking.html"><i class="ti ti-map-pin-pin"></i><span>Tracking</span></a></li>
              <li><a href="maintenance.html"><i class="ti ti-color-filter"></i><span>Maintenance</span></a></li>
            </ul></li>
            <li class="menu-title"><span>FINANCE & ACCOUNTS</span></li>
            <li><ul>
              <li><a href="invoices.html"><i class="ti ti-file-invoice"></i><span>Invoices</span></a></li>
              <li><a href="payments.html"><i class="ti ti-credit-card"></i><span>Payments</span></a></li>
            </ul></li>
            <li class="menu-title"><span>OTHERS</span></li>
            <li><ul>
              <li><a href="chat.html"><i class="ti ti-message"></i><span>Messages</span><span class="count">5</span></a></li>
              <li><a href="coupons.html"><i class="ti ti-discount-2"></i><span>Coupons</span></a></li>
              <li><a href="newsletters.html"><i class="ti ti-file-horizontal"></i><span>Newsletters</span></a></li>
            </ul></li>
            <li class="menu-title"><span>CMS</span></li>
            <li><ul>
              <li><a href="pages.html" ><i class="ti ti-file-invoice"></i><span>Pages</span></a></li>
              <li><a href="menu-management.html" ><i class="ti ti-menu-2"></i><span>Menu Management</span></a></li>
              <li class="submenu">
                <a href="javascript:void(0);"><i class="ti ti-device-desktop-analytics"></i><span>Blogs</span><span class="menu-arrow"></span></a>
                <ul>
                  <li><a href="blogs.html">All Blogs</a></li>
                  <li><a href="blog-categories.html">Categories</a></li>
                  <li><a href="blog-comments.html">Comments</a></li>
                  <li><a href="blog-tags.html">Blog Tags</a></li>
                </ul>
              </li>
              <li class="submenu">
                <a href="javascript:void(0);"><i class="ti ti-map"></i><span>Locations</span><span class="menu-arrow"></span></a>
                <ul>
                  <li><a href="countries.html">Countries</a></li>
                  <li><a href="state.html">States</a></li>
                  <li><a href="city.html">Cities</a></li>
                </ul>
              </li>
              <li><a href="testimonials.html"><i class="ti ti-brand-hipchat"></i><span>Testimonials</span></a></li>
              <li class="submenu">
                <a href="javascript:void(0);"><i class="ti ti-question-mark"></i><span>FAQ's</span><span class="menu-arrow"></span></a>
                <ul>
                  <li><a href="faq.html">FAQ's</a></li>
                  <li><a href="faq-category.html">FAQ Category</a></li>
                </ul>
              </li>
            </ul></li>
            <li class="menu-title"><span>SUPPORT</span></li>
            <li><ul>
              <li><a href="contact-messages.html" ><i class="ti ti-messages"></i><span>Contact Messages</span></a></li>
              <li><a href="announcements.html"><i class="ti ti-speakerphone"></i><span>Announcements</span></a></li>
              <li><a href="tickets.html"><i class="ti ti-ticket"></i><span>Tickets</span></a></li>
            </ul></li>
            <li class="menu-title"><span>USER MANAGEMENT</span></li>
            <li><ul>
              <li><a href="users.html" ><i class="ti ti-user-circle"></i><span>Users</span></a></li>
              <li><a href="roles-permissions.html"><i class="ti ti-user-shield"></i><span>Roles & Permissions</span></a></li>
            </ul></li>
            <li class="menu-title"><span>REPORTS</span></li>
            <li><ul>
              <li><a href="income-report.html" ><i class="ti ti-chart-histogram"></i><span>Income vs Expense</span></a></li>
              <li><a href="earnings-report.html"><i class="ti ti-chart-line"></i><span>Earnings</span></a></li>
              <li><a href="rental-report.html"><i class="ti ti-chart-infographic"></i><span>Rentals</span></a></li>
            </ul></li>
            <li class="menu-title"><span>AUTHENTICATION</span></li>
            <li><ul>
              <li><a href="login.html" ><i class="ti ti-login"></i><span>Login</span></a></li>
              <li><a href="forgot-password.html"><i class="ti ti-help-triangle"></i><span>Forgot Password</span></a></li>
              <li><a href="otp.html"><i class="ti ti-mail-exclamation"></i><span>Email Verification</span></a></li>
              <li><a href="reset-password.html"><i class="ti ti-restore"></i><span>Reset Password</span></a></li>
            </ul></li>
            <li class="menu-title"><span>SETTINGS & CONFIGURATION</span></li>
            <li><ul>
              <li class="submenu"><a href="javascript:void(0);"><i class="ti ti-user-cog"></i><span>Account Settings</span><span class="menu-arrow"></span></a>
                <ul>
                  <li><a href="profile-setting.html">Profile</a></li>
                  <li><a href="security-setting.html">Security</a></li>
                  <li><a href="notifications-setting.html">Notifications</a></li>
                  <li><a href="integrations-settings.html">Integrations</a></li>
                  <li><a href="tracker-setting.html">Tracker</a></li>
                </ul>
              </li>
              <li class="submenu"><a href="javascript:void(0);"><i class="ti ti-world-cog"></i><span>Website Settings</span><span class="menu-arrow"></span></a>
                <ul>
                  <li><a href="company-setting.html">Company Settings</a></li>
                  <li><a href="localization-setting.html">Localization</a></li>
                  <li><a href="prefixes.html">Prefixes</a></li>
                  <li><a href="seo-setup.html">SEO Setup</a></li>
                  <li><a href="language-setting.html">Language</a></li>
                  <li><a href="maintenance-mode.html">Maintenance Mode</a></li>
                  <li><a href="login-setting.html">Login & Register</a></li>
                  <li><a href="ai-configuration.html">AI Configuration</a></li>
                  <li><a href="plugin-managers.html">Plugin Managers</a></li>
                </ul>
              </li>
              <li class="submenu"><a href="javascript:void(0);"><i class="ti ti-clock-cog"></i><span>Rental Settings</span><span class="menu-arrow"></span></a>
                <ul>
                  <li><a href="rental-setting.html">Rental</a></li>
                  <li><a href="insurance-setting.html">Insurance</a></li>
                </ul>
              </li>
              <li class="submenu"><a href="javascript:void(0);"><i class="ti ti-device-mobile-cog"></i><span>App Settings</span><span class="menu-arrow"></span></a>
                <ul>
                  <li><a href="invoice-setting.html">Invoice Settings</a></li>
                  <li><a href="invoice-template.html">Invoice Templates</a></li>
                  <li><a href="signatures-setting.html">Signatures</a></li>
                  <li><a href="custom-fields.html">Custom Fields</a></li>
                </ul>
              </li>
              <li class="submenu"><a href="javascript:void(0);"><i class="ti ti-device-desktop-cog"></i><span>System Settings</span><span class="menu-arrow"></span></a>
                <ul>
                  <li><a href="email-setting.html">Email Settings</a></li>
                  <li><a href="email-templates.html">Email Templates</a></li>
                  <li><a href="sms-gateways.html">SMS Gateways</a></li>
                  <li><a href="gdpr-cookies.html">GDPR Cookies</a></li>
                </ul>
              </li>
              <li class="submenu"><a href="javascript:void(0);"><i class="ti ti-settings-dollar"></i><span>Finance Settings</span><span class="menu-arrow"></span></a>
                <ul>
                  <li><a href="payment-methods.html">Payment Methods</a></li>
                  <li><a href="bank-accounts.html">Bank Accounts</a></li>
                  <li><a href="tax-rates.html">Tax Rates</a></li>
                  <li><a href="currencies.html">Currencies</a></li>
                </ul>
              </li>
              <li class="submenu"><a href="javascript:void(0);"><i class="ti ti-settings-2"></i><span>Other Settings</span><span class="menu-arrow"></span></a>
                <ul>
                  <li><a href="sitemap.html">Sitemap</a></li>
                  <li><a href="clear-cache.html">Clear Cache</a></li>
                  <li><a href="storage.html">Storage</a></li>
                  <li><a href="cronjob.html">Cronjob</a></li>
                  <li><a href="system-backup.html">System Backup</a></li>
                  <li><a href="system-update.html">System Update</a></li>
                </ul>
              </li>
            </ul></li>
            <li class="menu-title"><span>UI Interface</span></li>
            <li><ul>
              <li class="submenu"><a href="javascript:void(0);"><i class="ti ti-hierarchy"></i><span>Base UI</span><span class="menu-arrow"></span></a>
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
              <li class="submenu"><a href="javascript:void(0);"><i class="ti ti-whirl"></i><span>Advanced UI</span><span class="menu-arrow"></span></a>
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
              <li class="submenu"><a href="javascript:void(0);"><i class="ti ti-forms"></i><span>Forms</span><span class="menu-arrow"></span></a>
                <ul>
                  <li class="submenu submenu-two"><a href="javascript:void(0);">Form Elements<span class="menu-arrow inside-submenu"></span></a>
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
                  <li class="submenu submenu-two"><a href="javascript:void(0);">Layouts<span class="menu-arrow inside-submenu"></span></a>
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
              <li class="submenu"><a href="javascript:void(0);"><i class="ti ti-table"></i><span>Tables</span><span class="menu-arrow"></span></a>
                <ul>
                  <li><a href="tables-basic.html">Basic Tables </a></li>
                  <li><a href="data-tables.html">Data Table </a></li>
                </ul>
              </li>
              <li class="submenu"><a href="javascript:void(0);"><i class="ti ti-chart-pie-3"></i><span>Charts</span><span class="menu-arrow"></span></a>
                <ul>
                  <li><a href="chart-apex.html">Apex Charts</a></li>
                  <li><a href="chart-c3.html">Chart C3</a></li>
                  <li><a href="chart-js.html">Chart Js</a></li>
                  <li><a href="chart-morris.html">Morris Charts</a></li>
                  <li><a href="chart-flot.html">Flot Charts</a></li>
                  <li><a href="chart-peity.html">Peity Charts</a></li>
                </ul>
              </li>
              <li class="submenu"><a href="javascript:void(0);"><i class="ti ti-icons"></i><span>Icons</span><span class="menu-arrow"></span></a>
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
              <li class="submenu"><a href="javascript:void(0);"><i class="ti ti-map-2"></i><span>Maps</span><span class="menu-arrow"></span></a>
                <ul>
                  <li><a href="maps-vector.html">Vector</a></li>
                  <li><a href="maps-leaflet.html">Leaflet</a></li>
                </ul>
              </li>
            </ul></li>
            <li class="menu-title"><span>Extras</span></li>
            <li><ul>
              <li><a href="javascript:void(0);"><i class="ti ti-file-shredder"></i><span>Documentation</span></a></li>
              <li><a href="javascript:void(0);"><i class="ti ti-exchange"></i><span>Changelog</span></a></li>
              <li class="submenu"><a href="javascript:void(0);"><i class="ti ti-menu-2"></i><span>Multi Level</span><span class="menu-arrow"></span></a>
                <ul>
                  <li><a href="javascript:void(0);">Multilevel 1</a></li>
                  <li class="submenu submenu-two"><a href="javascript:void(0);">Multilevel 2<span class="menu-arrow inside-submenu"></span></a>
                    <ul>
                      <li><a href="javascript:void(0);">Multilevel 2.1</a></li>
                      <li class="submenu submenu-two submenu-three"><a href="javascript:void(0);">Multilevel 2.2<span class="menu-arrow inside-submenu inside-submenu-two"></span></a>
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
            </ul></li>
          </ul>
        </div>
      </div>
    </div>
    <!-- /Sidebar -->
  <div class="page-wrapper">
    <div class="content me-4">
      <div class="d-md-flex d-block align-items-center justify-content-between page-breadcrumb mb-3">
        <div class="my-auto mb-2">
          <h4 class="mb-1">Car Details</h4>
          <p class="text-muted mb-0"><a href="cars.php" class="text-decoration-none">Cars</a> / <?= h($car['name'] ?: 'Untitled') ?></p>
        </div>
        <div class="d-flex my-xl-auto right-content align-items-center flex-wrap ">
          <div class="mb-2">
            <a href="cars.php" class="btn btn-light d-flex align-items-center"><i class="ti ti-chevron-left me-1"></i>Back to List</a>
          </div>
        </div>
      </div>

      <!-- Summary card -->
      <div class="card mb-3">
        <div class="card-body">
          <div class="row g-3">
            <div class="col-lg-4">
              <?php $img = $car['featured_image'] ?: 'assets/img/car/car-02.jpg'; ?>
              <img src="<?= h($img) ?>" alt="Car" class="car-cover w-100">
            </div>
            <div class="col-lg-8">
              <h3 class="mb-1"><?= h($car['name'] ?: 'Untitled') ?></h3>
              <div class="text-muted mb-2">Type: <?= h($car['car_type'] ?: '-') ?> · Brand: <?= h($car['brand'] ?: '-') ?> · Model: <?= h($car['model'] ?: '-') ?></div>
              <div class="mb-2">Location: <strong><?= h($car['main_location'] ?: '-') ?></strong></div>
              <div class="mb-2">Daily Price: <strong>$<?= number_format((float)$car['daily_price'], 2) ?></strong></div>
              <div class="mb-2">Status: 
                <?php $status = $car['status'] ?: 'Inactive'; $cls = ($status==='Active'?'text-success':($status==='Inactive'?'text-danger':'text-info')); ?>
                <span class="badge badge-dark-transparent"><i class="ti ti-point-filled <?= $cls ?> me-1"></i><?= h($status) ?></span>
              </div>
              <?php if (!empty($car['description'])): ?>
                <p class="mb-0 mt-2"><?= nl2br(h($car['description'])) ?></p>
              <?php endif; ?>
            </div>
          </div>
        </div>
      </div>

      <!-- Gallery -->
      <div class="card mb-3">
        <div class="card-header"><h5 class="mb-0">Gallery</h5></div>
        <div class="card-body">
          <?php if (empty($images)): ?>
            <p class="text-muted mb-0">No gallery images.</p>
          <?php else: ?>
            <div class="d-flex flex-wrap gap-2">
              <?php foreach ($images as $gp): ?>
                <a href="<?= h($gp) ?>" data-fancybox="gallery">
                  <img src="<?= h($gp) ?>" class="car-thumb" alt="Gallery">
                </a>
              <?php endforeach; ?>
            </div>
          <?php endif; ?>
        </div>
      </div>

      <!-- Specifications -->
      <div class="card mb-3">
        <div class="card-header"><h5 class="mb-0">Specifications</h5></div>
        <div class="card-body">
          <div class="row g-3">
            <div class="col-md-3"><div class="text-muted">Category</div><div><?= h($car['category'] ?: '-') ?></div></div>
            <div class="col-md-3"><div class="text-muted">Fuel</div><div><?= h($car['fuel_type'] ?: '-') ?></div></div>
            <div class="col-md-3"><div class="text-muted">Transmission</div><div><?= h($car['transmission'] ?: '-') ?></div></div>
            <div class="col-md-3"><div class="text-muted">Color</div><div><?= h($car['color'] ?: '-') ?></div></div>
            <div class="col-md-3"><div class="text-muted">Year</div><div><?= h($car['year_of_car'] ?: '-') ?></div></div>
            <div class="col-md-3"><div class="text-muted">Passengers</div><div><?= h($car['passengers'] ?: '-') ?></div></div>
            <div class="col-md-3"><div class="text-muted">Seats</div><div><?= h($car['seats'] ?: '-') ?></div></div>
            <div class="col-md-3"><div class="text-muted">Doors</div><div><?= h($car['doors'] ?: '-') ?></div></div>
            <div class="col-md-3"><div class="text-muted">Air Bags</div><div><?= h($car['air_bags'] ?: '-') ?></div></div>
            <div class="col-md-3"><div class="text-muted">Odometer</div><div><?= h($car['odometer'] ?: '-') ?></div></div>
            <div class="col-md-3"><div class="text-muted">Mileage</div><div><?= h($car['mileage'] ?: '-') ?></div></div>
            <div class="col-md-3"><div class="text-muted">VIN</div><div><?= h($car['vin_number'] ?: '-') ?></div></div>
            <div class="col-md-3"><div class="text-muted">Plate</div><div><?= h($car['plate_number'] ?: '-') ?></div></div>
          </div>
        </div>
      </div>

      <!-- Pricing -->
      <div class="card mb-3">
        <div class="card-header"><h5 class="mb-0">Pricing</h5></div>
        <div class="card-body">
          <div class="row g-3">
            <div class="col-md-3"><div class="text-muted">Daily Price</div><div>$<?= number_format((float)$car['daily_price'], 2) ?></div></div>
            <div class="col-md-3"><div class="text-muted">Base KM/Day</div><div><?= h($car['base_kilometers_per_day'] ?: 0) ?></div></div>
            <div class="col-md-3"><div class="text-muted">Extra KM Price</div><div>$<?= number_format((float)$car['kilometers_extra_price'], 2) ?></div></div>
            <div class="col-md-3"><div class="text-muted">Unlimited KM</div><div><?= ($car['unlimited_kilometers'] ? 'Yes' : 'No') ?></div></div>
          </div>
        </div>
      </div>

      <!-- Features & Extras -->
      <div class="card mb-3">
        <div class="card-header"><h5 class="mb-0">Features & Amenities</h5></div>
        <div class="card-body">
          <?php if (empty($features)): ?>
            <p class="text-muted mb-0">No features selected.</p>
          <?php else: foreach ($features as $f): ?>
            <span class="chip"><?= h(str_replace('_', ' ', $f)) ?></span>
          <?php endforeach; endif; ?>
        </div>
      </div>

      <div class="card mb-3">
        <div class="card-header"><h5 class="mb-0">Extra Services</h5></div>
        <div class="card-body">
          <?php if (empty($extras)): ?>
            <p class="text-muted mb-0">No extra services selected.</p>
          <?php else: foreach ($extras as $ex): ?>
            <span class="chip"><?= h(str_replace('_', ' ', $ex)) ?></span>
          <?php endforeach; endif; ?>
        </div>
      </div>

      <!-- Insurance -->
      <div class="card mb-3">
        <div class="card-header"><h5 class="mb-0">Insurance</h5></div>
        <div class="card-body">
          <?php if (!$insurance): ?>
            <p class="text-muted mb-0">No insurance selected.</p>
          <?php else: ?>
            <div class="row g-2">
              <div class="col-md-4"><div class="text-muted">Name</div><div><?= h($insurance['insurance_name']) ?></div></div>
              <div class="col-md-4"><div class="text-muted">Price</div><div>$<?= number_format((float)$insurance['price'], 2) ?></div></div>
              <div class="col-md-4"><div class="text-muted">Benefits</div><div><?= h($insurance['benefits'] ?? '-') ?></div></div>
            </div>
          <?php endif; ?>
        </div>
      </div>

      <!-- Damages -->
      <div class="card mb-4">
        <div class="card-header"><h5 class="mb-0">Damages</h5></div>
        <div class="card-body">
          <?php if (empty($damages)): ?>
            <p class="text-muted mb-0">No damages recorded.</p>
          <?php else: ?>
            <div class="table-responsive">
              <table class="table">
                <thead>
                  <tr>
                    <th>Location</th>
                    <th>Type</th>
                    <th>Description</th>
                    <th>Date</th>
                  </tr>
                </thead>
                <tbody>
                  <?php foreach ($damages as $dg): ?>
                    <tr>
                      <td><?= h($dg['location']) ?></td>
                      <td><?= h($dg['damage_type']) ?></td>
                      <td><?= h($dg['description'] ?: '-') ?></td>
                      <td><?= h($dg['date_added']) ?></td>
                    </tr>
                  <?php endforeach; ?>
                </tbody>
              </table>
            </div>
          <?php endif; ?>
        </div>
      </div>

    </div>

    <div class="footer d-sm-flex align-items-center justify-content-between bg-white p-3">
      <p class="mb-0">
        <a href="#">Privacy Policy</a>
        <a href="#" class="ms-4">Terms of Use</a>
      </p>
      <p>&copy; <?= date('Y') ?> RentalHub</p>
    </div>
  </div>
</div>

<script src="assets/js/jquery-3.7.1.min.js"></script>
<script src="assets/js/bootstrap.bundle.min.js"></script>
<script src="assets/js/feather.min.js"></script>
<script src="assets/js/jquery.slimscroll.min.js"></script>
<script src="assets/plugins/fancybox/jquery.fancybox.min.js"></script>
<script src="assets/js/script.js"></script>
</body>
</html>