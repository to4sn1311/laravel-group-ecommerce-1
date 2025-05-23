<!DOCTYPE html>
<html lang="zxx">

<head>
    <meta charset="UTF-8">
    <meta name="description" content="Ogani Template">
    <meta name="keywords" content="Ogani, unica, creative, html">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Ogani | Template</title>

    <!-- Google Font -->
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@200;300;400;600;900&display=swap" rel="stylesheet">

    <!-- Css Styles -->
    <link rel="stylesheet" href="{{ asset('assets/css/bootstrap.min.css') }}" type="text/css">
    <link rel="stylesheet" href="{{ asset('assets/css/font-awesome.min.css') }}" type="text/css">
    <link rel="stylesheet" href="{{ asset('assets/css/elegant-icons.css') }}" type="text/css">
    <link rel="stylesheet" href="{{ asset('assets/css/nice-select.css') }}" type="text/css">
    <link rel="stylesheet" href="{{ asset('assets/css/jquery-ui.min.css') }}" type="text/css">
    <link rel="stylesheet" href="{{ asset('assets/css/owl.carousel.min.css') }}" type="text/css">
    <link rel="stylesheet" href="{{ asset('assets/css/slicknav.min.css') }}" type="text/css">
    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}" type="text/css">
    <link rel="stylesheet" href="{{ asset('assets/css/client/login.css') }}">

    <!-- Custom CSS for header section -->
    <style>
        /* Header Top Styles */
        .header__top {
            background-color: #f5f5f5;
            padding: 10px 0;
        }

        .header__top__left ul {
            padding: 0;
            margin: 0;
            list-style: none;
        }

        .header__top__left ul li {
            font-size: 14px;
            color: #1c1c1c;
            display: inline-block;
            margin-right: 45px;
            position: relative;
            line-height: 36px;
        }

        .header__top__left ul li:last-child {
            margin-right: 0;
        }

        .header__top__left ul li i {
            color: #7fad39;
            margin-right: 5px;
        }

        .header__top__right {
            display: flex;
            align-items: center;
            justify-content: flex-end;
        }

        .header__top__right__social {
            margin-right: 25px;
        }

        .header__top__right__social a {
            font-size: 14px;
            color: #1c1c1c;
            display: inline-block;
            margin-right: 15px;
        }

        .header__top__right__social a:last-child {
            margin-right: 0;
        }

        .header__top__right__social a:hover {
            color: #7fad39;
        }

        .header__top__right__language {
            position: relative;
            display: inline-block;
            margin-right: 25px;
            cursor: pointer;
            padding-top: 2px;
        }

        .header__top__right__language img {
            margin-right: 6px;
        }

        .header__top__right__language div {
            font-size: 14px;
            color: #1c1c1c;
            display: inline-block;
            margin-right: 4px;
        }

        .header__top__right__language span {
            font-size: 14px;
            color: #1c1c1c;
            position: relative;
            top: 2px;
        }

        .header__top__right__language ul {
            background: #ffffff;
            width: 100px;
            border-radius: 3px;
            padding: 5px 0;
            position: absolute;
            left: 0;
            top: 43px;
            z-index: 9;
            opacity: 0;
            visibility: hidden;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            transition: all 0.3s;
        }

        .header__top__right__language ul li {
            list-style: none;
        }

        .header__top__right__language ul li a {
            font-size: 14px;
            color: #1c1c1c;
            padding: 5px 10px;
            display: block;
        }

        .header__top__right__language ul li a:hover {
            color: #7fad39;
            background: #f5f5f5;
        }

        .header__top__right__language:hover ul {
            opacity: 1;
            visibility: visible;
            top: 33px;
        }

        /* User Auth Styles */
        .header__top__right__auth {
            display: flex;
            align-items: center;
        }

        .header__top__right__auth .user-info {
            color: #1c1c1c;
            font-size: 14px;
            display: inline-flex;
            align-items: center;
        }

        .header__top__right__auth .user-info i {
            margin-right: 5px;
        }

        .header__top__right__auth .d-inline-block {
            display: inline-block;
        }

        .header__top__right__auth .ml-2 {
            margin-left: 10px;
        }

        .header__top__right__auth .logout-btn {
            background: none;
            border: none;
            color: #1c1c1c;
            font-size: 14px;
            cursor: pointer;
            padding: 0;
            transition: color 0.3s;
        }

        .header__top__right__auth .logout-btn:hover {
            color: #7fad39;
        }

        .header__top__right__auth .logout-btn i {
            font-size: 16px;
        }

        .header__top__right__auth a {
            display: block;
            font-size: 14px;
            color: #1c1c1c;
        }

        .header__top__right__auth a i {
            margin-right: 6px;
        }

        .header__top__right__auth a:hover {
            color: #7fad39;
        }
    </style>
</head>

<body>
    <!-- Page Preloder -->
    <!-- <div id="preloder">
        <div class="loader"></div>
    </div> -->

    <!-- Humberger Begin -->
    <div class="humberger__menu__overlay"></div>
    <div class="humberger__menu__wrapper">
        <div class="humberger__menu__logo">
            <a href="#"><img src="{{ asset('assets/img/logo.png') }}" alt=""></a>
        </div>
        <div class="humberger__menu__cart">
            <ul>
                <li><a href="#"><i class="fa fa-heart"></i> <span>1</span></a></li>
                <li><a href="#"><i class="fa fa-shopping-bag"></i> <span>3</span></a></li>
            </ul>
            <div class="header__cart__price">item: <span>$150.00</span></div>
        </div>
        <div class="humberger__menu__widget">
            <div class="header__top__right__language">
                <img src="{{ asset('assets/img/language.png') }}" alt="">
                <div>English</div>
                <span class="arrow_carrot-down"></span>
                <ul>
                    <li><a href="#">Spanis</a></li>
                    <li><a href="#">English</a></li>
                </ul>
            </div>
            <div class="header__top__right__auth">
                <a href="{{ route('client.login') }}"><i class="fa fa-user"></i> Login</a>
            </div>
        </div>
        <nav class="humberger__menu__nav mobile-menu">
            <ul>
                <li class="active"><a href="./index.html">Home</a></li>
                <li><a href="./shop-grid.html">Shop</a></li>
                <li><a href="#">Pages</a>
                    <!-- <ul class="header__menu__dropdown">
                        <li><a href="./shop-details.html">Shop Details</a></li>
                        <li><a href="./shoping-cart.html">Shoping Cart</a></li>
                        <li><a href="./checkout.html">Check Out</a></li>
                        <li><a href="./blog-details.html">Blog Details</a></li>
                    </ul> -->
                </li>
                <li><a href="./blog.html">Blog</a></li>
                <li><a href="./contact.html">Contact</a></li>
            </ul>
        </nav>
        <div id="mobile-menu-wrap"></div>
        <div class="header__top__right__social">
            <a href="#"><i class="fa fa-facebook"></i></a>
            <a href="#"><i class="fa fa-twitter"></i></a>
            <a href="#"><i class="fa fa-linkedin"></i></a>
            <a href="#"><i class="fa fa-pinterest-p"></i></a>
        </div>
        <div class="humberger__menu__contact">
            <ul>
                <li><i class="fa fa-envelope"></i> hello@colorlib.com</li>
                <li>Free Shipping for all Order of $99</li>
            </ul>
        </div>
    </div>
    <!-- Humberger End -->

    <!-- Header Section Begin -->
    <header class="header">
        <div class="header__top">
            <div class="container">
                <div class="row">
                    <div class="col-lg-6 col-md-6">
                        <div class="header__top__left">
                            <ul>
                                <li><i class="fa fa-envelope"></i> hello@deha-soft.com</li>
                                <li>Free Shipping for all Order of $99</li>
                            </ul>
                        </div>
                    </div>
                    <div class="col-lg-6 col-md-6">
                        <div class="header__top__right">
                            <div class="header__top__right__social">
                                <a href="#"><i class="fa fa-facebook"></i></a>
                                <a href="#"><i class="fa fa-twitter"></i></a>
                                <a href="#"><i class="fa fa-linkedin"></i></a>
                                <a href="#"><i class="fa fa-pinterest-p"></i></a>
                            </div>
                            <div class="header__top__right__language">
                                <img src="{{ asset('assets/img/language.png') }}" alt="">
                                <div>English</div>
                                <span class="arrow_carrot-down"></span>
                                <ul>
                                    <li><a href="#">Spanish</a></li>
                                    <li><a href="#">English</a></li>
                                </ul>
                            </div>
                            <div class="header__top__right__auth">
                                @auth
                                    <span class="user-info">
                                        <i class="fa fa-user"></i> {{ Auth::user()->name }}
                                    </span>
                                    <form method="POST" action="{{ route('logout') }}" class="d-inline-block ml-2">
                                        @csrf
                                        <button type="submit" class="logout-btn" title="Logout">
                                            <i class="fa fa-sign-out"></i>
                                        </button>
                                    </form>
                                @else
                                    <a href="{{ route('login') }}"><i class="fa fa-user"></i> Login</a>
                                @endauth
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="container">
            <div class="row">
                <div class="col-lg-3">
                    <div class="header__logo">
                        <a href="{{ route('client.index') }}"><img src="{{ asset('assets/img/logo.png') }}" alt=""></a>
                    </div>
                </div>
                <div class="col-lg-6">
                    <nav class="header__menu">
                        <ul>
                            <li class="{{ request()->routeIs('client.index') ? 'active' : '' }}"><a href="{{ route('client.index') }}">Home</a></li>
                            <li class="{{ request()->routeIs('client.shop') ? 'active' : '' }}"><a href="{{ route('client.shop') }}">Shop</a></li>
                        </ul>
                    </nav>
                </div>
                <div class="col-lg-3">
                    <div class="header__cart">
                        <ul>
                            <li><a href="#"><i class="fa fa-shopping-bag"></i></a></li>
                        </ul>
                        <!-- <div class="header__cart__price">item: <span>$150.00</span></div> -->
                    </div>
                </div>
            </div>
            <div class="humberger__open">
                <i class="fa fa-bars"></i>
            </div>
        </div>
    </header>
    <!-- Header Section End -->

    <!-- Flash Messages -->
    <div class="container mt-3">
        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger">
                {{ session('error') }}
            </div>
        @endif
    </div>
    <!-- End Flash Messages -->

    @yield('content')

    <!-- Footer Section Begin -->
    <footer class="footer spad">
        <div class="container">
            <div class="row">
                <div class="col-lg-3 col-md-6 col-sm-6">
                    <div class="footer__about">
                        <div class="footer__about__logo">
                            <a href="{{ route('client.index') }}"><img src="{{ asset('assets/img/logo.png') }}" alt=""></a>
                        </div>
                        <ul>
                            <li>Address: 60-49 Road 11378 New York</li>
                            <li>Phone: +65 11.188.888</li>
                            <li>Email: hello@colorlib.com</li>
                        </ul>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6 col-sm-6 offset-lg-1">
                    <div class="footer__widget">
                        <h6>Useful Links</h6>
                        <ul>
                            <li><a href="#">About Us</a></li>
                            <li><a href="#">About Our Shop</a></li>
                            <li><a href="#">Secure Shopping</a></li>
                            <li><a href="#">Delivery infomation</a></li>
                            <li><a href="#">Privacy Policy</a></li>
                            <li><a href="#">Our Sitemap</a></li>
                        </ul>
                        <ul>
                            <li><a href="#">Who We Are</a></li>
                            <li><a href="#">Our Services</a></li>
                            <li><a href="#">Projects</a></li>
                            <li><a href="#">Contact</a></li>
                            <li><a href="#">Innovation</a></li>
                            <li><a href="#">Testimonials</a></li>
                        </ul>
                    </div>
                </div>
                <div class="col-lg-4 col-md-12">
                    <div class="footer__widget">
                        <h6>Join Our Newsletter Now</h6>
                        <p>Get E-mail updates about our latest shop and special offers.</p>
                        <form action="#">
                            <input type="text" placeholder="Enter your mail">
                            <button type="submit" class="site-btn">Subscribe</button>
                        </form>
                        <div class="footer__widget__social">
                            <a href="#"><i class="fa fa-facebook"></i></a>
                            <a href="#"><i class="fa fa-instagram"></i></a>
                            <a href="#"><i class="fa fa-twitter"></i></a>
                            <a href="#"><i class="fa fa-pinterest"></i></a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-12">
                    <div class="footer__copyright">
                        <div class="footer__copyright__text"><p><!-- Link back to Colorlib can't be removed. Template is licensed under CC BY 3.0. -->
  Copyright &copy;<script>document.write(new Date().getFullYear());</script> All rights reserved | This template is made with <i class="fa fa-heart" aria-hidden="true"></i> by <a href="https://colorlib.com" target="_blank">Colorlib</a>
  <!-- Link back to Colorlib can't be removed. Template is licensed under CC BY 3.0. --></p></div>
                        <div class="footer__copyright__payment"><img src="{{ asset('assets/img/payment-item.png') }}" alt=""></div>
                    </div>
                </div>
            </div>
        </div>
    </footer>
    <!-- Footer Section End -->

    <!-- Js Plugins -->
    <script src="{{ asset('assets/js/jquery-3.3.1.min.js') }}"></script>
    <script src="{{ asset('assets/js/bootstrap.min.js') }}"></script>
    <script src="{{ asset('assets/js/jquery.nice-select.min.js') }}"></script>
    <script src="{{ asset('assets/js/jquery-ui.min.js') }}"></script>
    <script src="{{ asset('assets/js/jquery.slicknav.js') }}"></script>
    <script src="{{ asset('assets/js/mixitup.min.js') }}"></script>
    <script src="{{ asset('assets/js/owl.carousel.min.js') }}"></script>
    <script src="{{ asset('assets/js/main.js') }}"></script>



</body>

</html>