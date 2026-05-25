<?php
// Enable debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Connect to Mortgage Minds WordPress database
$servername = "153.92.15.63";
$username = "u464227444_iAMsy";
$password = ";t(1}482s.";
$dbname = "u464227444_i7wsj";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("❌ Connection failed: " . $conn->connect_error);
}

// Fetch published blog posts
$sql = "SELECT ID, post_title, post_content, post_date, post_author, post_name
        FROM wp_posts
        WHERE post_type='post' AND post_status='publish'
        ORDER BY post_date DESC";
$result = $conn->query($sql);

if ($result === false) {
    die("❌ SQL Error: " . $conn->error);
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <!-- ========== Meta Tags ========== -->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="robots" content="index, follow">

    <meta name="description"
        content="Read expert mortgage tips, home loan guides, and financial insights from Mortgage Minds, New Zealand's trusted mortgage brokers. Helping Kiwis make smarter home loan decisions.">

    <!-- ========== Page Title ========== -->
    <title>Mortgage Blog & Resources | Expert Home Loan Advice | Mortgage Minds NZ</title>
    <link rel="canonical" href="https://mortgageminds.co.nz/blogs.php/" />

    <!-- ========== Favicon Icon ========== -->
    <link rel="shortcut icon" href="assets/img/logo.png" type="image/x-icon">

    <!-- ========== Start Stylesheet ========== -->
    <link href="assets/css/bootstrap.min.css" rel="stylesheet">
    <link href="assets/css/font-awesome.min.css" rel="stylesheet">
    <link href="assets/css/magnific-popup.css" rel="stylesheet">
    <link href="assets/css/swiper-bundle.min.css" rel="stylesheet">
    <link href="assets/css/animate.min.css" rel="stylesheet">
    <link href="assets/css/validnavs.css" rel="stylesheet">
    <link href="assets/css/helper.css" rel="stylesheet">
    <link href="assets/css/unit-test.css" rel="stylesheet">
    <link href="assets/css/style.css" rel="stylesheet">
    <link href="style.css" rel="stylesheet">
    <!-- ========== End Stylesheet ========== -->

    <!-- Custom Blog Styles -->
    <style>
        .blog-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(350px, 1fr));
            gap: 30px;
        }

        .blog-card {
            background: white;
            border-radius: 12px;
            border: 1px solid rgba(0, 0, 0, 0.05);
            transition: all 0.4s;
            position: relative;
            overflow: hidden;
            z-index: 1;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.05);
            height: 100%;
            display: flex;
            flex-direction: column;
        }

        .blog-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.1);
        }

        .blog-image {
            width: 100%;
            height: 220px;
            object-fit: cover;
            border-radius: 12px 12px 0 0;
        }

        .blog-image-placeholder {
            width: 100%;
            height: 220px;
            background: linear-gradient(135deg, #1a3a5c, #2a5298);
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 12px 12px 0 0;
        }

        .blog-content {
            padding: 25px;
            flex-grow: 1;
            display: flex;
            flex-direction: column;
        }

        .blog-meta {
            display: flex;
            align-items: center;
            gap: 15px;
            margin-bottom: 12px;
            font-size: 13px;
            color: #6c757d;
            flex-wrap: wrap;
        }

        .blog-meta i {
            color: #c9a84c;
            margin-right: 5px;
        }

        .blog-title {
            font-size: 20px;
            margin-bottom: 12px;
            color: #2c3e50;
            font-weight: 600;
            line-height: 1.4;
        }

        .blog-excerpt {
            color: #6c757d;
            margin-bottom: 20px;
            font-size: 15px;
            line-height: 1.6;
            flex-grow: 1;
        }

        .read-more {
            background: transparent;
            color: #1a3a5c;
            border: 2px solid #1a3a5c;
            padding: 10px 22px;
            border-radius: 25px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            align-self: flex-start;
        }

        .read-more:hover {
            background: #1a3a5c;
            color: white;
            transform: translateX(3px);
            box-shadow: 0 4px 12px rgba(26, 59, 93, 0.2);
        }

        .category-tag {
            position: absolute;
            top: 15px;
            left: 15px;
            background: #c9a84c;
            color: white;
            padding: 6px 14px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
            z-index: 2;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .no-posts-message {
            grid-column: 1 / -1;
            text-align: center;
            padding: 60px 20px;
        }

        @media (max-width: 992px) {
            .blog-grid {
                grid-template-columns: repeat(2, 1fr);
            }
        }

        @media (max-width: 768px) {
            .blog-grid {
                grid-template-columns: 1fr;
                max-width: 500px;
                margin: 0 auto;
            }
        }
    </style>

    <!--[if lte IE 9]>
        <p class="browserupgrade">You are using an <strong>outdated</strong> browser. Please <a href="https://browsehappy.com/">upgrade your browser</a> to improve your experience and security.</p>
    <![endif]-->

</head>

<body>

    <!-- Header 
    ============================================= -->
    <header>
        <!-- Start Navigation -->
        <nav class="navbar mobile-sidenav navbar-sticky validnavs white navbar-fixed no-background">
            <div class="container-fluid">
                <div class="row align-items-center">

                    <!-- Start Header Navigation -->
                    <div class="col-xxl-2 col-md-2 col-sm-1 col-1">
                        <div class="navbar-header">
                            <button type="button" class="navbar-toggle" data-toggle="collapse"
                                data-target="#navbar-menu">
                                <i class="fa fa-bars"></i>
                            </button>
                            <a class="navbar-brand" href="/">
                                <img src="assets/img/logo.png" class="logo" alt="Logo">
                            </a>
                        </div>
                    </div>
                    <!-- End Header Navigation -->

                    <!-- Collect the nav links, forms, and other content for toggling -->
                    <div class="col-xxl-6 offset-xxl-1 col-lg-7 col-md-4 col-sm-4 col-4">
                        <div class="collapse navbar-collapse" id="navbar-menu">

                            <img src="assets/img/logo.png" alt="Logo">
                            <button type="button" class="navbar-toggle" data-toggle="collapse"
                                data-target="#navbar-menu">
                                <i class="fa fa-times"></i>
                            </button>

                            <ul class="nav navbar-nav navbar-center" data-in="fadeInDown" data-out="fadeOutUp">
                                <li><a href="/">Home</a></li>
                                <li><a href="about-us.html">About Us</a></li>
                                <li class="dropdown">
                                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">Services</a>
                                    <ul class="dropdown-menu">
                                        <li><a href="first-home-buyer.html">First Home Buyer Loan</a></li>
                                        <li><a href="refinance-mortgage.html">Refinancing Mortgage</a></li>
                                        <li><a href="property-investment.html">Property Investment</a></li>

                                        <!-- Insurance Services with Submenu -->
                                        <li class="dropdown-submenu insurance-submenu-item">
                                            <div class="submenu-wrapper">
                                                <a href="insurance-services.html" class="insurance-main-link">Insurance Services</a>
                                                <span class="submenu-arrow-trigger"><i class="fas fa-chevron-right"></i></span>
                                            </div>
                                            <ul class="dropdown-menu insurance-inner-submenu">
                                                <li><a href="life-insurance.html">Life Insurance</a></li>
                                                <li><a href="trauma-recovery-cover.html">Trauma Recovery Cover</a></li>
                                                <li><a href="medical-insurance.html">Medical Insurance</a></li>
                                                <li><a href="mortgage-insurance.html">Mortgage & Rent Protection Cover</a></li>
                                                <li><a href="permanent-disability-insurance-cover.html">Total Permanent Disability Benefit Cover</a></li>
                                                <li><a href="income-protection-insurance-cover.html">Income Protection Cover</a></li>
                                            </ul>
                                        </li>

                                        <li><a href="mortgage-calculators.html">Mortgage Calculators</a></li>
                                    </ul>
                                </li>
                                <li><a href="mortgage-rates.html">Mortgage Rates</a></li>
                                <li><a href="blogs.php">Blogs</a></li>
                                <li><a href="contact.html">Contact Us</a></li>
                            </ul>
                        </div>
                    </div>
                    <!-- /.navbar-collapse -->

                    <div class="col-xxl-3 col-lg-3 col-md-6 col-sm-7 col-7">
                        <div class="attr-right">
                            <!-- Start Atribute Navigation -->
                            <div class="attr-nav attr-box">
                                <ul>
                                    <li class="contact">
                                        <div class="call">
                                            <div class="icon">
                                                <i class="fas fa-comments-alt-dollar"></i>
                                            </div>
                                            <div class="info">
                                                <p>Have any Questions?</p>
                                                <h5><a href="mailto:nilesh@mortgageminds.co.nz">nilesh@mortgageminds.co.nz</a></h5>
                                            </div>
                                        </div>
                                    </li>
                                </ul>
                            </div>
                            <!-- End Atribute Navigation -->
                        </div>
                    </div>

                </div>
                <!-- Main Nav -->

                <!-- Overlay screen for menu -->
                <div class="overlay-screen"></div>
                <!-- End Overlay screen for menu -->
            </div>
        </nav>
        <!-- End Navigation -->
    </header>
    <!-- End Header -->

    <!-- Start Breadcrumb 
    ============================================= -->
    <div class="breadcrumb-area bg-cover shadow dark text-light"
        style="background-image: url(assets/img/contact-banner.webp);">
        <div class="container">
            <div class="row">
                <div class="col-lg-6">
                    <h1>Mortgage Minds Blog</h1>
                    <p>Expert mortgage advice, home loan tips, and financial insights for Kiwis</p>
                    <ul class="breadcrumb">
                        <li><a href="/"><i class="fas fa-home"></i> Home</a></li>
                        <li>Blogs</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
    <!-- End Breadcrumb -->

    <!-- Start Blog Section 
    ============================================= -->
    <div class="blog-style-one-area bg-gray default-padding">
        <div class="container">
            <div class="row">
                <div class="col-lg-8 offset-lg-2">
                    <div class="site-heading text-center">
                        <h4 class="sub-title">Our Blog</h4>
                        <h2 class="title split-text">Mortgage & Home Loan <span style="color: #1a3a5c;">Insights</span></h2>
                        <p>Stay informed with expert articles on home loans, refinancing, property investment, and financial planning for Kiwis.</p>
                    </div>
                </div>
            </div>

            <div class="blog-grid">
                <?php if ($result->num_rows > 0): ?>
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <?php
                        // Get author name
                        $author_id = $row['post_author'];
                        $author_result = $conn->query("SELECT display_name FROM wp_users WHERE ID = $author_id");
                        $author = ($author_result && $author_result->num_rows > 0)
                            ? $author_result->fetch_assoc()['display_name']
                            : "Mortgage Minds Team";

                        // Get featured image
                        $image_result = $conn->query("
                            SELECT meta_value FROM wp_postmeta
                            WHERE post_id = {$row['ID']} AND meta_key = '_thumbnail_id' LIMIT 1
                        ");
                        $thumbnail_id = ($image_result && $image_result->num_rows > 0)
                            ? $image_result->fetch_assoc()['meta_value']
                            : 0;

                        $img_url = '';
                        if ($thumbnail_id) {
                            $guid_result = $conn->query("SELECT guid FROM wp_posts WHERE ID = $thumbnail_id");
                            $img_url = ($guid_result && $guid_result->num_rows > 0)
                                ? $guid_result->fetch_assoc()['guid']
                                : '';
                        }

                        // Determine category based on content
                        $content = strtolower($row['post_content'] . ' ' . $row['post_title']);
                        $category = "Home Loans";
                        if (strpos($content, 'first home') !== false || strpos($content, 'first-home') !== false) {
                            $category = "First Home Buyer";
                        } elseif (strpos($content, 'refinanc') !== false) {
                            $category = "Refinancing";
                        } elseif (strpos($content, 'invest') !== false || strpos($content, 'property') !== false) {
                            $category = "Property Investment";
                        } elseif (strpos($content, 'interest rate') !== false || strpos($content, 'market') !== false) {
                            $category = "Market Updates";
                        } elseif (strpos($content, 'insur') !== false) {
                            $category = "Insurance";
                        }
                        ?>
                        <div class="blog-card">
                            <div class="category-tag"><?php echo $category; ?></div>
                            <?php if ($img_url): ?>
                                <img src="<?php echo $img_url; ?>" class="blog-image"
                                    alt="<?php echo htmlspecialchars($row['post_title']); ?>">
                            <?php else: ?>
                                <div class="blog-image-placeholder">
                                    <i class="fas fa-home" style="font-size: 48px; color: rgba(255,255,255,0.5);"></i>
                                </div>
                            <?php endif; ?>
                            <div class="blog-content">
                                <div class="blog-meta">
                                    <span><i class="fas fa-calendar-alt"></i> <?php echo date("F j, Y", strtotime($row['post_date'])); ?></span>
                                    <span><i class="fas fa-user"></i> <?php echo htmlspecialchars($author); ?></span>
                                </div>
                                <h3 class="blog-title"><?php echo htmlspecialchars($row['post_title']); ?></h3>
                                <p class="blog-excerpt"><?php echo wp_trim_words(strip_tags($row['post_content']), 20); ?>...</p>
                                <a href="https://mortgageminds.co.nz/blogs/<?php echo $row['post_name']; ?>/" class="read-more">
                                    Read More <i class="fas fa-arrow-right"></i>
                                </a>
                            </div>
                        </div>
                    <?php endwhile; ?>
                <?php else: ?>
                    <div class="no-posts-message">
                        <i class="fas fa-newspaper" style="font-size: 64px; color: #6c757d; margin-bottom: 20px; display: block;"></i>
                        <h3 style="color: #2c3e50; margin-bottom: 10px;">No Blog Posts Yet</h3>
                        <p style="color: #6c757d; font-size: 18px;">Check back soon for expert mortgage advice, home loan tips, and financial insights from our advisors.</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
    <!-- End Blog Section -->

    <!-- Start CTA Section -->
    <section class="cta-section-area" style="padding: 50px 0;">
        <div>
            <div
                style="background: #1a3a5c; border-radius: 12px; padding: 64px 48px; position: relative; overflow: hidden; text-align: center;">

                <!-- Background orbs -->
                <div
                    style="position: absolute; top: -80px; right: -80px; width: 300px; height: 300px; border-radius: 50%; background: rgba(255,255,255,0.04);">
                </div>
                <div
                    style="position: absolute; bottom: -60px; left: -60px; width: 220px; height: 220px; border-radius: 50%; background: rgba(255,255,255,0.04);">
                </div>

                <!-- Tag -->
                <span
                    style="display: inline-block; background: rgba(255,255,255,0.12); color: #a8cff5; letter-spacing: 0.08em; text-transform: uppercase; padding: 5px 14px; border-radius: 99px; margin-bottom: 1.5rem;">Take the first step</span>

                <!-- Heading -->
                <h2 style="color: #ffffff; margin: 0 0 1rem; line-height: 1.25;">
                    Talk to Our <span style="color: #5bb8f5;">Mortgage Experts</span>
                </h2>

                <!-- Subtext -->
                <p style="color: rgba(255,255,255,0.72); max-width: 560px; margin: 0 auto 0.75rem; line-height: 1.65;">
                    Mortgage minds assists the New Zealand homeowners, first
                    home buyers, and investors to get
                    intelligent and smart lending solutions. Our team is
                    prepared to assist you whether you require
                    refinancing advice, better mortgage rates, or to obtain
                    a home loan.
                </p>

            </div>
        </div>
    </section>
    <!-- End CTA Section -->

    <!-- Start Emergency Call -->
    <div class="newsletter-area bg-theme text-light" style="background-image: url(assets/img/shape/4.png);">
        <div class="container">
            <div class="row">
                <div class="col-lg-8 offset-lg-4">
                    <div class="newsletter-subscribe">
                        <h3><i class="fas fa-user-headset"></i> Let's Make a
                            Call</h3>
                        <a href="tel:+640800452105">+64 0800 452 105</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- End Emergency Call -->

    <!-- Start Footer 
    ============================================= -->
    <footer class="bg-dark footer-one text-light" style="background-image: url(assets/img/shape/27.png);">
        <div class="container">
            <div class="footer-style-one">

                <div class="row">
                    <!-- Singel Item -->
                    <div class="col-lg-4 col-md-12 footer-item mt-50">
                        <div class="f-item about">

                            <div class="logo">
                                <img src="assets/img//logo.png" alt="Image Not Found">
                            </div>
                            <p>
                                Mortgage Minds helps people in New Zealand
                                choose the perfect mortgage by giving them
                                expert guidance and a wide range of loan
                                options.
                            </p>
                            <div class="opening-hours">
                                <h5>Opening Hours</h5>
                                <ul class="opening-list">
                                    <li>
                                        Mon - Fri <span class="text-end">8:00 AM - 6:00
                                            PM</span>
                                    </li>
                                    <li>
                                        Saturday <span class="text-end">9:00
                                            AM - 5:00 PM</span>
                                    </li>
                                    <li>
                                        Sunday <span class="text-end">Closed</span>
                                    </li>
                                </ul>
                            </div>
                            <a class="btn btn-theme btn-md animation mt-30" href="contact.html">Contact Us</a>
                        </div>
                    </div>
                    <!-- End Singel Item -->

                    <!-- Singel Item -->
                    <div class="col-lg-2 col-md-6 mt-50 footer-item">
                        <div class="f-item link">
                            <h4 class="widget-title">Explore</h4>
                            <ul>
                                <li>
                                    <a href="about-us.html">About Us</a>
                                </li>
                                <li>
                                    <a href="contact.html">Contact Us</a>
                                <li>
                                    <a href="mortgage-calculators.html">Mortgage
                                        Calculator</a>
                                </li>
                                <li>
                                    <a href="mortgage-rates.html">Mortgage
                                        Rates</a>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <!-- End Singel Item -->

                    <!-- Singel Item -->
                    <div class="col-lg-3 col-md-6 mt-50 footer-item">
                        <div class="f-item link">
                            <h4 class="widget-title">Our Services</h4>
                            <ul>
                                <li>
                                    <a href="first-home-buyer.html">First
                                        Home Buyer</a>
                                </li>
                                <li>
                                    <a href="refinance-mortgage.html">Refinancing
                                        Mortgage</a>
                                </li>
                                <li>
                                    <a href="property-investment.html">Property
                                        Investment</a>
                                </li>
                                <li>
                                    <a href="insurance-services.html">Insurance Services
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <!-- End Singel Item -->

                    <!-- Singel Item -->
                    <div class="col-lg-3 col-md-12 footer-item  mt-50">
                        <div class="f-item contact">
                            <h4 class="widget-title">Contact Info</h4>
                            <ul>
                                <li>
                                    <div class="icon">
                                        <i class="fas fa-map-marker-alt"></i>
                                    </div>
                                    <div class="content">
                                        Mortgage Minds Limited 1/10
                                        Salford Crescent, Flatbush,
                                        Auckland 2019
                                    </div>
                                </li>
                                <li>
                                    <div class="icon">
                                        <i class="fas fa-phone"></i>
                                    </div>
                                    <div class="content">
                                        <a href="tel:0800452105">+64
                                            0800 452 105</a>
                                    </div>
                                </li>
                                <li>
                                    <div class="icon">
                                        <i class="fas fa-envelope"></i>
                                    </div>
                                    <div class="content">
                                        <a href="mailto:nilesh@mortgageminds.co.nz">nilesh@mortgageminds.co.nz</a>
                                    </div>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <!-- End Singel Item -->

                </div>
            </div>
        </div>

        <!-- Footer Bottom -->
        <div class="footer-bottom text-light">
            <div class="container">
                <div class="row">
                    <div class="col-lg-8">
                        <p>
                            © Copyright 2025 Mortgage Minds. All
                            Rights Reserved | Designed by <a href="https://aaravtech.net">Aarav Tech Services LLP</a> <i
                                class="fa fa-heart"></i>
                        </p>
                    </div>
                    <div class="col-lg-4 text-end">
                        <ul>
                            <li>
                                <a href="terms-conditions.html">Terms</a>
                            </li>
                            <li>
                                <a href="privacy-policy.html">Privacy</a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        <!-- End Footer Bottom -->
    </footer>
    <!-- End Footer -->

    <!-- jQuery Frameworks
    ============================================= -->
    <script src="assets/js/jquery-3.6.0.min.js"></script>
    <script src="assets/js/bootstrap.bundle.min.js"></script>
    <script src="assets/js/jquery.appear.js"></script>
    <script src="assets/js/swiper-bundle.min.js"></script>
    <script src="assets/js/progress-bar.min.js"></script>
    <script src="assets/js/isotope.pkgd.min.js"></script>
    <script src="assets/js/imagesloaded.pkgd.min.js"></script>
    <script src="assets/js/magnific-popup.min.js"></script>
    <script src="assets/js/count-to.js"></script>
    <script src="assets/js/jquery.nice-select.min.js"></script>
    <script src="assets/js/jquery.scrolla.min.js"></script>
    <script src="assets/js/YTPlayer.min.js"></script>
    <script src="assets/js/validnavs.js"></script>
    <script src="assets/js/gsap.js"></script>
    <script src="assets/js/ScrollTrigger.min.js"></script>
    <script src="assets/js/rangeSlider.min.js"></script>
    <script src="assets/js/jquery-ui.min.js"></script>
    <script src="assets/js/SplitText.min.js"></script>
    <script src="assets/js/main.js"></script>

</body>

</html>

<?php
// Helper function to trim words (WordPress-like)
function wp_trim_words($text, $num_words = 20)
{
    $text = strip_tags($text);
    $words = explode(' ', $text);
    if (count($words) > $num_words) {
        $words = array_slice($words, 0, $num_words);
        return implode(' ', $words);
    }
    return $text;
}

$conn->close();
?>