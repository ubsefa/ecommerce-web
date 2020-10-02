<?php class_exists("Helpers\TemplateHelper") or exit; ?>
<!DOCTYPE html>
<html class="fixed" lang="<?php echo $language ?>">

<head>
    <!-- Basic -->
    <title></title>
    <meta charset="UTF-8" />
    <meta name="keywords" content="<?php echo $keywords ?>" />
    <meta name="description" content="<?php echo $description ?>" />
    <meta name="author" content="<?php echo $author ?>" />
    <!-- Mobile Metas -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
    <!-- Web Fonts  -->
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:100,300,400,600,800,900|Shadows+Into+Light" rel="stylesheet" type="text/css" />
    <!-- Vendor CSS -->
    <link rel="stylesheet" href="<?php echo $themeUrl ?>/vendor/bootstrap/css/bootstrap.css" />
    <link rel="stylesheet" href="<?php echo $themeUrl ?>/vendor/animate/animate.css">
    <link rel="stylesheet" href="<?php echo $themeUrl ?>/vendor/font-awesome/css/all.min.css" />
    <link rel="stylesheet" href="<?php echo $themeUrl ?>/vendor/magnific-popup/magnific-popup.css" />
    <link rel="stylesheet" href="<?php echo $themeUrl ?>/vendor/bootstrap-datepicker/css/bootstrap-datepicker3.css" />
    <!-- Theme CSS -->
    <link rel="stylesheet" href="<?php echo $themeUrl ?>/css/theme.css" />
    <!-- Skin CSS -->
    <link rel="stylesheet" href="<?php echo $themeUrl ?>/css/skins/default.css" />
    <!-- Theme Custom CSS -->
    <link rel="stylesheet" href="<?php echo $themeUrl ?>/css/custom.css">
    <!-- Head Libs -->
    <script src="<?php echo $themeUrl ?>/vendor/modernizr/modernizr.js"></script>
</head>
 
<body>
    <!-- Page -->
    
<section class="body-sign">
    <div class="center-sign">
        <a href="/" class="logo float-left">
            <img src="<?php echo $logo ?>" height="54" alt="<?php echo $title ?>" />
        </a>
        <div class="panel card-sign">
            <div class="card-title-sign mt-3 text-right">
                <h2 class="title text-uppercase font-weight-bold m-0"><i class="fas fa-user mr-1"></i> Sign In</h2>
            </div>
            <div class="card-body">
                <form action="index.html" method="post">
                    <div class="form-group mb-3">
                        <label>Username</label>
                        <div class="input-group">
                            <input name="username" type="text" class="form-control form-control-lg" />
                            <span class="input-group-append">
                                <span class="input-group-text">
                                    <i class="fas fa-user"></i>
                                </span>
                            </span>
                        </div>
                    </div>

                    <div class="form-group mb-3">
                        <div class="clearfix">
                            <label class="float-left">Password</label>
                            <a href="pages-recover-password.html" class="float-right">Lost Password?</a>
                        </div>
                        <div class="input-group">
                            <input name="pwd" type="password" class="form-control form-control-lg" />
                            <span class="input-group-append">
                                <span class="input-group-text">
                                    <i class="fas fa-lock"></i>
                                </span>
                            </span>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-sm-8">
                            <div class="checkbox-custom checkbox-default">
                                <input id="RememberMe" name="rememberme" type="checkbox" />
                                <label for="RememberMe">Remember Me</label>
                            </div>
                        </div>
                        <div class="col-sm-4 text-right">
                            <button id="buttonLogin" type="button" class="btn btn-primary mt-2">Sign In</button>
                        </div>
                    </div>

                    <span class="mt-3 mb-3 line-thru text-center text-uppercase">
                        <span>or</span>
                    </span>

                    <div class="mb-1 text-center">
                        <a class="btn btn-facebook mb-3 ml-1 mr-1" href="#">Connect with <i
                                class="fab fa-facebook-f"></i></a>
                        <a class="btn btn-twitter mb-3 ml-1 mr-1" href="#">Connect with <i
                                class="fab fa-twitter"></i></a>
                    </div>

                    <p class="text-center">Don't have an account yet? <a href="pages-signup.html">Sign Up!</a></p>

                </form>
            </div>
        </div>

        <p class="text-center text-muted mt-3 mb-3">&copy; Copyright 2017. All Rights Reserved.</p>
    </div>
</section>

    <!-- Vendor -->
    <script src="<?php echo $themeUrl ?>/vendor/jquery/jquery.js"></script>
    <script src="<?php echo $themeUrl ?>/vendor/jquery-browser-mobile/jquery.browser.mobile.js"></script>
    <script src="<?php echo $themeUrl ?>/vendor/popper/umd/popper.min.js"></script>
    <script src="<?php echo $themeUrl ?>/vendor/bootstrap/js/bootstrap.js"></script>
    <script src="<?php echo $themeUrl ?>/vendor/bootstrap-datepicker/js/bootstrap-datepicker.js"></script>
    <script src="<?php echo $themeUrl ?>/vendor/common/common.js"></script>
    <script src="<?php echo $themeUrl ?>/vendor/nanoscroller/nanoscroller.js"></script>
    <script src="<?php echo $themeUrl ?>/vendor/magnific-popup/jquery.magnific-popup.js"></script>
    <script src="<?php echo $themeUrl ?>/vendor/jquery-placeholder/jquery.placeholder.js"></script>
    <!-- Specific Page Vendor -->
    <!-- Theme Base, Components and Settings -->
    <script src="<?php echo $themeUrl ?>/js/theme.js"></script>
    <!-- Theme Custom -->
    <script src="<?php echo $themeUrl ?>/js/custom.js?version=<?php echo uniqid() ?>"></script>
    <!-- Theme Initialization Files -->
    <script src="<?php echo $themeUrl ?>/js/theme.init.js"></script>
</body>

</html>

