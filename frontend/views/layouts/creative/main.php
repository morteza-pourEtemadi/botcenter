<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">

    <title><?= Yii::t('app_site', 'Ultimate Developers') ?></title>

    <!-- Bootstrap Core CSS -->
    <link href="creative/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom Fonts -->
    <link href="creative/vendor/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">
    <link href='https://fonts.googleapis.com/css?family=Open+Sans:300italic,400italic,600italic,700italic,800italic,400,300,600,700,800' rel='stylesheet' type='text/css'>
    <link href='https://fonts.googleapis.com/css?family=Merriweather:400,300,300italic,400italic,700,700italic,900,900italic' rel='stylesheet' type='text/css'>

    <!-- Plugin CSS -->
    <link href="creative/vendor/magnific-popup/magnific-popup.css" rel="stylesheet">

    <!-- Theme CSS -->
    <link href="creative/css/creative.min.css" rel="stylesheet">

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
        <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->

</head>

<body id="page-top" <?= Yii::$app->language == 'fa-IR' ? 'dir="rtl"' : ''?>>

    <nav id="mainNav" class="navbar navbar-default navbar-fixed-top">
        <div class="container-fluid">
            <!-- Brand and toggle get grouped for better mobile display -->
            <div class="navbar-header">
                <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
                    <span class="sr-only">Toggle navigation</span> Menu <i class="fa fa-bars"></i>
                </button>
                <a class="navbar-brand page-scroll" href="#page-top"><span><?= Yii::t('app_site', 'Ultimate Developers') ?></span></a>
            </div>
            <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                <ul class="nav navbar-nav navbar-left">
                    <li>
                        <span class="verticalline"></span>
                    </li>
                    <li class="text-center">
                        <a class="page-scroll" style="cursor: default;"> <?= Yii::t('app_site', 'Social: ') ?> </a>
                    </li>
                    <li>
                        <a href="https://instagram.com/__ultimate_developer__" target="_blank"><span class="social fa fa-instagram insta">&nbsp;</span></a>
                    </li>
                    <li>
                        <a href="https://tlgrm.me/ultimate_developer_official" target="_blank"><span class="social fa fa-telegram channel">&nbsp;</span></a>
                    </li>
                    <li>
                        <a href="https://twitter.com/ulti_developer" target="_blank"><span class="social fa fa-twitter tweeter">&nbsp;</span></a>
                    </li>
                    <li>
                        <a href="https://facebook.com/ultimate.developer" target="_blank"><span class="social fa fa-facebook-square facebook">&nbsp;</span></a>
                    </li>
                </ul>

                <ul class="nav navbar-nav navbar-right">
                    <li>
                        <a class="page-scroll" href="#about"><?= Yii::t('app_site', 'About') ?></a>
                    </li>
                    <li>
                        <a class="page-scroll" href="#services"><?= Yii::t('app_site', 'Services') ?></a>
                    </li>
                    <li>
                        <a class="page-scroll" href="#portfolio"><?= Yii::t('app_site', 'portfolio') ?></a>
                    </li>
<!--                    <li>-->
<!--                        <a class="page-scroll" href="#terms">--><?//= Yii::t('app_site', 'terms') ?><!--</a>-->
<!--                    </li>-->
                    <li>
                        <a class="page-scroll" href="#contact"><?= Yii::t('app_site', 'contact') ?></a>
                    </li>
                </ul>
            </div>
            <!-- /.navbar-collapse -->
        </div>
        <!-- /.container-fluid -->
    </nav>

    <header>
        <div class="header-content">
            <div class="header-content-inner">
                <h1 id="homeHeading"><?= Yii::t('app_site', 'YOUR FAVORITE SOURCE OF PHP CODES') ?></h1>
                <hr>
                <p><?= Yii::t('app_site', "Ultimate Developers can help you build your work websites, do your programming home-works, build telegram bots, etc!\n Just decide to make change in your life and get Online!") ?></p>
                <a href="#about" class="btn btn-primary btn-xl page-scroll"><?= Yii::t('app_site', 'Find Out More') ?></a>
            </div>
        </div>
    </header>

    <section class="bg-primary" id="about">
        <div class="container">
            <div class="row">
                <div class="col-lg-8 col-lg-offset-2 text-center">
                    <h2 class="section-heading"><?= Yii::t('app_site', 'We\'ve got what you need!') ?></h2>
                    <hr class="light">
                    <p class="text-faded"><?= Yii::t('app_site', 'Ultimate Developer has everything you need to make your business online and run it in no time! All the services in Ultimate Developer are cheap, user-friendly and easy to run and use. Want to test it?!') ?></p>
                    <a href="#services" class="page-scroll btn btn-default btn-xl sr-button"><?= Yii::t('app_site', 'GET STARTED!') ?></a>
                </div>
            </div>
        </div>
    </section>

    <section id="services">
        <div class="container">
            <div class="row">
                <div class="col-lg-12 text-center">
                    <h2 class="section-heading"><?= Yii::t('app_site', 'At Your Service') ?></h2>
                    <hr class="primary">
                </div>
            </div>
        </div>
        <div class="container">
            <div class="row">
                <div class="col-lg-3 col-md-6 text-center">
                    <div class="service-box">
                        <i class="fa fa-4x fa-diamond text-primary sr-icons"></i>
                        <h3><?= Yii::t('app_site', 'Valuable but cheap') ?></h3>
                        <p class="text-muted"><?= Yii::t('app_site', 'Services you get here are all cheapest in the market, but they are all first rate') ?></p>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 text-center">
                    <div class="service-box">
                        <i class="fa fa-4x fa-paper-plane text-primary sr-icons"></i>
                        <h3><?= Yii::t('app_site', 'Ready to Ship') ?></h3>
                        <p class="text-muted"><?= Yii::t('app_site', 'Our services are ready to use as fast as it possible. Our programmers` speed is maniac!') ?></p>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 text-center">
                    <div class="service-box">
                        <i class="fa fa-4x fa-newspaper-o text-primary sr-icons"></i>
                        <h3><?= Yii::t('app_site', 'Up to Date') ?></h3>
                        <p class="text-muted"><?= Yii::t('app_site', 'All the services use the last version of technologies. We are up-to-date!') ?></p>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 text-center">
                    <div class="service-box">
                        <i class="fa fa-4x fa-heart text-primary sr-icons"></i>
                        <h3><?= Yii::t('app_site', 'Made with Love') ?></h3>
                        <p class="text-muted"><?= Yii::t('app_site', 'You have to do your works with Love these days. We too!') ?></p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="no-padding" id="portfolio">
        <div class="container-fluid">
            <div class="row no-gutter popup-gallery">
                <div class="col-lg-4 col-sm-6">
                    <a href="img/portfolio/fullsize/1.jpg" class="portfolio-box">
                        <img src="img/portfolio/thumbnails/1.jpg" class="img-responsive" alt="">
                        <div class="portfolio-box-caption">
                            <div class="portfolio-box-caption-content">
                                <div class="project-category text-faded">
                                    <span><?= Yii::t('app_site', 'Website Design') ?></span>
                                </div>
                                <div class="project-name">
                                    <span><?= Yii::t('app_site', 'AutMav Group') ?></span>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>
                <div class="col-lg-4 col-sm-6">
                    <a href="img/portfolio/fullsize/2.jpg" class="portfolio-box">
                        <img src="img/portfolio/thumbnails/2.jpg" class="img-responsive" alt="">
                        <div class="portfolio-box-caption">
                            <div class="portfolio-box-caption-content">
                                <div class="project-category text-faded">
                                    <span><?= Yii::t('app_site', 'Website Design') ?></span>
                                </div>
                                <div class="project-name">
                                    <span><?= Yii::t('app_site', 'Gebeer Leather Shop') ?></span>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>
                <div class="col-lg-4 col-sm-6">
                    <a href="img/portfolio/fullsize/3.jpg" class="portfolio-box">
                        <img src="img/portfolio/thumbnails/3.jpg" class="img-responsive" alt="">
                        <div class="portfolio-box-caption">
                            <div class="portfolio-box-caption-content">
                                <div class="project-category text-faded">
                                    <span><?= Yii::t('app_site', 'Website Design') ?></span>
                                </div>
                                <div class="project-name">
                                    <span><?= Yii::t('app_site', 'Amirkabir University of Technology Energy Systems Group') ?></span>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>
                <div class="col-lg-4 col-sm-6">
                    <a href="img/portfolio/fullsize/4.jpg" class="portfolio-box">
                        <img src="img/portfolio/thumbnails/4.jpg" class="img-responsive" alt="">
                        <div class="portfolio-box-caption">
                            <div class="portfolio-box-caption-content">
                                <div class="project-category text-faded">
                                    <span><?= Yii::t('app_site', 'Telegram Bot') ?></span>
                                </div>
                                <div class="project-name">
                                    <span><?= Yii::t('app_site', 'Cake Sara Bot') ?></span>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>
                <div class="col-lg-4 col-sm-6">
                    <a href="img/portfolio/fullsize/5.jpg" class="portfolio-box">
                        <img src="img/portfolio/thumbnails/5.jpg" class="img-responsive" alt="">
                        <div class="portfolio-box-caption">
                            <div class="portfolio-box-caption-content">
                                <div class="project-category text-faded">
                                    <span><?= Yii::t('app_site', 'Telegram Bot') ?></span>
                                </div>
                                <div class="project-name">
                                    <span><?= Yii::t('app_site', 'Fun Zaban Bot') ?></span>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>
                <div class="col-lg-4 col-sm-6">
                    <a href="img/portfolio/fullsize/6.jpg" class="portfolio-box">
                        <img src="img/portfolio/thumbnails/6.jpg" class="img-responsive" alt="">
                        <div class="portfolio-box-caption">
                            <div class="portfolio-box-caption-content">
                                <div class="project-category text-faded">
                                    <span><?= Yii::t('app_site', 'Telegram Bot') ?></span>
                                </div>
                                <div class="project-name">
                                    <span><?= Yii::t('app_site', 'Iran DubeSmash Bot') ?></span>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>
            </div>
        </div>
    </section>

    <aside class="bg-dark">
        <div class="container text-center">
            <div class="call-to-action">
                <h2><?= Yii::t('app_site', 'A Few Steps to Get Your Favorite Product!') ?></h2>
                <a href="https://tlgrm.me/ultimate_developer" class="btn btn-default btn-xl sr-button"><span><?= Yii::t('app_site', 'Order Now!') ?></span></a>
            </div>
        </div>
    </aside>

<!--    <section id="terms">-->
<!--        <div class="container">-->
<!--            <div class="row">-->
<!--                <div class="col-lg-8 col-lg-offset-2 text-center">-->
<!--                    <h2 class="section-heading">--><?//= Yii::t('app_site', 'Terms and Conditions') ?><!--</h2>-->
<!--                    <hr class="primary">-->
<!--                    <p>--><?//= Yii::t('app_site', 'Please pay attention to the terms below:') ?><!--</p>-->
<!--                </div>-->
<!--            </div>-->
<!--            <div class="col-lg-4 text-center">-->
<!--                <i class="fa fa-hand-o-down fa-3x sr-contact"></i>-->
<!--                <p>1. </p>-->
<!--            </div>-->
<!--            <div class="col-lg-4 text-center">-->
<!--                <i class="fa fa-hand-o-down fa-3x sr-contact"></i>-->
<!--                <p>123-456-6789</p>-->
<!--            </div>-->
<!--            <div class="col-lg-4 text-center">-->
<!--                <i class="fa fa-hand-o-down fa-3x sr-contact"></i>-->
<!--                <p>123-456-6789</p>-->
<!--            </div>-->
<!--        </div>-->
<!--    </section>-->

    <section id="contact">
        <div class="container">
            <div class="row">
                <div class="col-lg-8 col-lg-offset-2 text-center">
                    <h2 class="section-heading"><?= Yii::t('app_site', 'Let\'s Get In Touch!') ?></h2>
                    <hr class="primary">
                    <p><?= Yii::t('app_site', 'Ready to start your next project with us? That\'s great! Give us a text or send us an email and we will get back to you as soon as possible!') ?></p>
                </div>
                <div class="col-lg-4 text-center">
                    <i class="fa fa-mobile fa-3x sr-contact"></i>
                    <p>+98-933-636-5162</p>
                </div>
                <div class="col-lg-4 text-center">
                    <i class="fa fa-envelope-o fa-3x sr-contact"></i>
                    <p><a href="mailto:ultimate.developer.94@gmail.com">ultimate.developer.94@gmail.com</a></p>
                </div>
                <div class="col-lg-4 text-center">
                    <i class="fa fa-telegram fa-3x sr-contact" style="color: #2aabd2;"></i>
                    <p><a href="https://tlgrm.me/ultimate_developer">@Ultimate_Developer</a></p>
                </div>
            </div>
        </div>
    </section>

    <!-- jQuery -->
    <script src="creative/vendor/jquery/jquery.min.js"></script>

    <!-- Bootstrap Core JavaScript -->
    <script src="creative/vendor/bootstrap/js/bootstrap.min.js"></script>

    <!-- Plugin JavaScript -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-easing/1.3/jquery.easing.min.js"></script>
    <script src="creative/vendor/scrollreveal/scrollreveal.min.js"></script>
    <script src="creative/vendor/magnific-popup/jquery.magnific-popup.min.js"></script>

    <!-- Theme JavaScript -->
    <script src="creative/js/creative.min.js"></script>

</body>

</html>
