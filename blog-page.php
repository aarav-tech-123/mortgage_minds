<?php
// Enable debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// --------------------
// Database connection
// --------------------
$servername = "153.92.15.63";
$username = "u464227444_iAMsy";
$password = ";t(1}482s.";
$dbname = "u464227444_i7wsj";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Database Connection failed: " . $conn->connect_error);
}

// --------------------
// Validate blog slug
// --------------------
if (!isset($_GET['slug'])) {
    die("Invalid blog slug");
}

$slug = $_GET['slug'];
$sql = "SELECT * FROM wp_posts WHERE post_name = ? AND post_type='post' AND post_status='publish'";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $slug);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    die("Blog not found!");
}

$blog = $result->fetch_assoc();
$stmt->close();

// Get post meta
$sql_meta = "SELECT meta_key, meta_value FROM wp_postmeta WHERE post_id = ?";
$stmt_meta = $conn->prepare($sql_meta);
$stmt_meta->bind_param("i", $blog['ID']);
$stmt_meta->execute();
$result_meta = $stmt_meta->get_result();

$post_meta = [];
while ($row = $result_meta->fetch_assoc()) {
    $post_meta[$row['meta_key']] = $row['meta_value'];
}
$stmt_meta->close();

// Get author
$author_id = $blog['post_author'];
$author_result = $conn->query("SELECT display_name FROM wp_users WHERE ID = $author_id");
$author = ($author_result && $author_result->num_rows > 0)
    ? $author_result->fetch_assoc()['display_name']
    : "Mortgage Minds Team";

// Get featured image
$image_result = $conn->query("
    SELECT meta_value FROM wp_postmeta
    WHERE post_id = {$blog['ID']} AND meta_key = '_thumbnail_id' LIMIT 1
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

// Get categories
$categories_result = $conn->query("
    SELECT t.name, t.slug 
    FROM wp_terms t 
    INNER JOIN wp_term_taxonomy tt ON t.term_id = tt.term_id 
    INNER JOIN wp_term_relationships tr ON tr.term_taxonomy_id = tt.term_taxonomy_id 
    WHERE tr.object_id = {$blog['ID']} AND tt.taxonomy = 'category'
");

$categories = [];
if ($categories_result && $categories_result->num_rows > 0) {
    while ($cat = $categories_result->fetch_assoc()) {
        $categories[] = $cat;
    }
}

// Get tags
$tags_result = $conn->query("
    SELECT t.name 
    FROM wp_terms t 
    INNER JOIN wp_term_taxonomy tt ON t.term_id = tt.term_id 
    INNER JOIN wp_term_relationships tr ON tr.term_taxonomy_id = tt.term_taxonomy_id 
    WHERE tr.object_id = {$blog['ID']} AND tt.taxonomy = 'post_tag'
");

function reading_time($text)
{
    $word_count = str_word_count(strip_tags($text));
    $minutes = (int) ceil($word_count / 200);
    return $minutes > 0 ? $minutes : 1;
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

    <meta name="title" content="<?php echo htmlspecialchars($post_meta['rank_math_title'] ?? $blog['post_title']); ?>">
    <meta name="description"
        content="<?php echo htmlspecialchars($post_meta['rank_math_description'] ?? substr(strip_tags($blog['post_content']), 0, 160)); ?>">
    <meta name="keywords" content="<?php echo htmlspecialchars($post_meta['rank_math_focus_keyword'] ?? 'mortgage, home loan, refinancing, property investment, first home buyer'); ?>">
    <link rel="canonical" href="https://mortgageminds.co.nz/blogs/<?php echo $slug; ?>/" />

    <!-- ========== Page Title ========== -->
    <title><?php echo htmlspecialchars($post_meta['rank_math_title'] ?? $blog['post_title']); ?></title>

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

    <!-- Blog Post Custom Styles -->
    <style>
        .blog-post-hero {
            padding: 180px 0 100px;
            position: relative;
            overflow: hidden;
            background: linear-gradient(135deg, #1a3a5c 0%, #2a5298 100%);
            color: white;
            text-align: center;
        }

        .blog-post-hero::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -20%;
            width: 500px;
            height: 500px;
            border-radius: 50%;
            background: rgba(201, 168, 76, 0.08);
        }

        .blog-post-badge {
            display: inline-flex;
            align-items: center;
            background: rgba(255, 255, 255, 0.2);
            color: white;
            padding: 8px 20px;
            border-radius: 30px;
            font-size: 13px;
            font-weight: 600;
            margin-bottom: 20px;
            border: 1px solid rgba(255, 255, 255, 0.3);
            backdrop-filter: blur(10px);
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .blog-post-hero h1 {
            font-size: 48px;
            margin-bottom: 20px;
            font-weight: 700;
            line-height: 1.2;
            letter-spacing: -1px;
            color: white;
            max-width: 900px;
            margin-left: auto;
            margin-right: auto;
        }

        .blog-post-meta {
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 25px;
            color: rgba(255, 255, 255, 0.9);
            font-size: 15px;
            margin-bottom: 30px;
            flex-wrap: wrap;
        }

        .blog-post-meta i {
            color: #c9a84c;
            margin-right: 6px;
        }

        .blog-post-meta .category-link {
            color: #c9a84c;
            text-decoration: none;
            font-weight: 600;
        }

        .blog-post-meta .category-link:hover {
            text-decoration: underline;
        }

        /* Blog Content Section */
        .blog-content-section {
            padding: 80px 0;
            background: #f8f9fa;
        }

        .blog-content-wrapper {
            max-width: 900px;
            margin: 0 auto;
            background: white;
            border-radius: 15px;
            padding: 50px;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.05);
        }

        .blog-featured-image {
            width: 100%;
            height: auto;
            max-height: 500px;
            object-fit: cover;
            border-radius: 12px;
            margin-bottom: 40px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        }

        .blog-featured-placeholder {
            width: 100%;
            height: 400px;
            background: linear-gradient(135deg, #1a3a5c, #2a5298);
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 12px;
            margin-bottom: 40px;
        }

        .blog-post-content {
            font-size: 17px;
            line-height: 1.85;
            color: #2c3e50;
        }

        .blog-post-content h1,
        .blog-post-content h2,
        .blog-post-content h3,
        .blog-post-content h4 {
            color: #2c3e50;
            margin: 35px 0 20px;
            font-weight: 600;
        }

        .blog-post-content h1 {
            font-size: 30px;
            border-bottom: 2px solid #e8f4fd;
            padding-bottom: 10px;
        }

        .blog-post-content h2 {
            font-size: 26px;
            color: #1a3a5c;
        }

        .blog-post-content h3 {
            font-size: 22px;
        }

        .blog-post-content p {
            margin-bottom: 20px;
            color: #555;
        }

        .blog-post-content a {
            color: #1a3a5c;
            text-decoration: none;
            transition: all 0.3s;
            font-weight: 500;
        }

        .blog-post-content a:hover {
            color: #c9a84c;
            text-decoration: underline;
        }

        .blog-post-content ul,
        .blog-post-content ol {
            margin: 20px 0;
            padding-left: 30px;
            color: #555;
        }

        .blog-post-content li {
            margin-bottom: 10px;
        }

        .blog-post-content blockquote {
            border-left: 4px solid #c9a84c;
            padding: 20px 20px 20px 30px;
            margin: 30px 0;
            font-style: italic;
            color: #666;
            background: #fef9ed;
            border-radius: 0 10px 10px 0;
        }

        .blog-post-content img {
            max-width: 100%;
            height: auto;
            border-radius: 10px;
            margin: 20px 0;
        }

        /* Tags */
        .blog-tags-section {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            margin: 40px 0;
            padding-top: 30px;
            border-top: 1px solid #e0e0e0;
            align-items: center;
        }

        .blog-tag {
            background: #e8f4fd;
            color: #1a3a5c;
            padding: 6px 14px;
            border-radius: 20px;
            font-size: 13px;
            text-decoration: none;
            transition: all 0.3s;
        }

        .blog-tag:hover {
            background: #1a3a5c;
            color: white;
        }

        /* Author Section */
        .blog-author-section {
            background: #f8f9fa;
            padding: 30px;
            border-radius: 12px;
            margin: 50px 0;
            display: flex;
            align-items: center;
            gap: 20px;
            border-left: 4px solid #c9a84c;
        }

        .author-avatar {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            background: #1a3a5c;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 28px;
            font-weight: bold;
            flex-shrink: 0;
        }

        .author-info h4 {
            color: #2c3e50;
            margin-bottom: 5px;
            font-size: 18px;
        }

        .author-info p {
            color: #6c757d;
            margin-bottom: 0;
            font-size: 14px;
        }

        /* Share Section */
        .share-section {
            display: flex;
            align-items: center;
            gap: 15px;
            margin-top: 30px;
            padding-top: 25px;
            border-top: 1px solid #e0e0e0;
        }

        .share-btn {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            text-decoration: none;
            transition: all 0.3s;
            font-size: 16px;
        }

        .share-btn.facebook {
            background: #1877f2;
        }

        .share-btn.twitter {
            background: #000000;
        }

        .share-btn.linkedin {
            background: #0a66c2;
        }

        .share-btn.email {
            background: #6c757d;
        }

        .share-btn:hover {
            transform: translateY(-3px);
            color: white;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
        }

        /* Back to Blog Button */
        .back-to-blog {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            color: #1a3a5c;
            text-decoration: none;
            font-weight: 600;
            margin-bottom: 30px;
            transition: all 0.3s;
        }

        .back-to-blog:hover {
            color: #c9a84c;
            transform: translateX(-5px);
        }

        /* Back to Top */
        .back-to-top {
            position: fixed;
            bottom: 30px;
            right: 30px;
            background: #1a3a5c;
            color: white;
            width: 50px;
            height: 50px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            text-decoration: none;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
            transition: all 0.3s;
            z-index: 1000;
            opacity: 0;
            visibility: hidden;
        }

        .back-to-top.show {
            opacity: 1;
            visibility: visible;
        }

        .back-to-top:hover {
            transform: translateY(-3px);
            background: #c9a84c;
            color: white;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .blog-post-hero {
                padding: 140px 0 60px;
            }

            .blog-post-hero h1 {
                font-size: 32px;
            }

            .blog-post-meta {
                flex-direction: column;
                gap: 10px;
            }

            .blog-content-wrapper {
                padding: 30px 20px;
            }

            .blog-featured-placeholder {
                height: 250px;
            }

            .blog-post-content {
                font-size: 16px;
            }

            .blog-author-section {
                flex-direction: column;
                text-align: center;
            }
        }

        @media (max-width: 576px) {
            .blog-post-hero h1 {
                font-size: 26px;
            }

            .blog-post-content h1 {
                font-size: 24px;
            }

            .blog-post-content h2 {
                font-size: 20px;
            }
        }
    </style>
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

    <!-- Blog Post Hero 
    ============================================= -->
    <section class="blog-post-hero">
        <div class="container">
            <div class="blog-post-badge">
                <i class="fas fa-blog"></i>
                <?php echo !empty($categories) ? htmlspecialchars($categories[0]['name']) : 'Blog Post'; ?>
            </div>
            <h1><?php echo htmlspecialchars($blog['post_title']); ?></h1>
            <div class="blog-post-meta">
                <span><i class="fas fa-calendar-alt"></i> <?php echo date("F j, Y", strtotime($blog['post_date'])); ?></span>
                <span><i class="fas fa-user"></i> <?php echo htmlspecialchars($author); ?></span>
                <span><i class="fas fa-clock"></i> <?php echo reading_time($blog['post_content']); ?> min read</span>
                <?php if (!empty($categories)): ?>
                    <span>
                        <i class="fas fa-folder"></i>
                        <?php foreach ($categories as $cat): ?>
                            <a href="#" class="category-link"><?php echo htmlspecialchars($cat['name']); ?></a>
                        <?php endforeach; ?>
                    </span>
                <?php endif; ?>
            </div>
        </div>
    </section>
    <!-- End Blog Post Hero -->

    <!-- Blog Content Section 
    ============================================= -->
    <section class="blog-content-section">
        <div class="container">
            <div class="blog-content-wrapper">
                <!-- Back to Blogs -->
                <a href="blogs.php" class="back-to-blog">
                    <i class="fas fa-arrow-left"></i> Back to All Blogs
                </a>

                <!-- Featured Image -->
                <?php if ($img_url): ?>
                    <img src="<?php echo $img_url; ?>" class="blog-featured-image"
                        alt="<?php echo htmlspecialchars($blog['post_title']); ?>">
                <?php else: ?>
                    <div class="blog-featured-placeholder">
                        <i class="fas fa-home" style="font-size: 72px; color: rgba(255,255,255,0.5);"></i>
                    </div>
                <?php endif; ?>

                <!-- Blog Content -->
                <div class="blog-post-content">
                    <?php echo $blog['post_content']; ?>
                </div>

                <!-- Tags -->
                <div class="blog-tags-section">
                    <strong style="color: #2c3e50;">Tags:</strong>
                    <?php if ($tags_result && $tags_result->num_rows > 0): ?>
                        <?php while ($tag = $tags_result->fetch_assoc()): ?>
                            <a href="#" class="blog-tag"><?php echo htmlspecialchars($tag['name']); ?></a>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <a href="#" class="blog-tag">Mortgage</a>
                        <a href="#" class="blog-tag">Home Loans</a>
                        <a href="#" class="blog-tag">Property</a>
                    <?php endif; ?>
                </div>

                <!-- Share Section -->
                <div class="share-section">
                    <strong style="color: #2c3e50;">Share:</strong>
                    <a href="https://www.facebook.com/sharer/sharer.php?u=https://mortgageminds.co.nz/<?php echo $slug; ?>/"
                        class="share-btn facebook" target="_blank" rel="noopener">
                        <i class="fab fa-facebook-f"></i>
                    </a>
                    <a href="https://twitter.com/intent/tweet?url=https://mortgageminds.co.nz/<?php echo $slug; ?>/&text=<?php echo urlencode($blog['post_title']); ?>"
                        class="share-btn twitter" target="_blank" rel="noopener">
                        <i class="fab fa-twitter"></i>
                    </a>
                    <a href="https://www.linkedin.com/sharing/share-offsite/?url=https://mortgageminds.co.nz/<?php echo $slug; ?>/"
                        class="share-btn linkedin" target="_blank" rel="noopener">
                        <i class="fab fa-linkedin-in"></i>
                    </a>
                    <a href="mailto:?subject=<?php echo urlencode($blog['post_title']); ?>&body=Check out this article: https://mortgageminds.co.nz/<?php echo $slug; ?>/"
                        class="share-btn email">
                        <i class="fas fa-envelope"></i>
                    </a>
                </div>

                <!-- Author Section -->
                <div class="blog-author-section">
                    <div class="author-avatar">
                        <?php echo strtoupper(substr($author, 0, 1)); ?>
                    </div>
                    <div class="author-info">
                        <h4><?php echo htmlspecialchars($author); ?></h4>
                        <p>Mortgage Advisor at Mortgage Minds. Helping Kiwis make smarter home loan decisions with expert guidance and personalized mortgage solutions across New Zealand.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- End Blog Content Section -->

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

    <!-- Back to Top -->
    <a href="#" class="back-to-top">
        <i class="fas fa-arrow-up"></i>
    </a>

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
    <script src="assets/js/SplitText.min.js