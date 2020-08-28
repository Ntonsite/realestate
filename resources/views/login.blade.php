@extends ('layouts.main');
<body>

<div class="page_loader"></div>

<!-- Login section start -->
<div class="login-section">
    <div class="form-content-box">
        <!-- details -->
        <div class="details">
            <div class="logo">
                <a href="{{url('/')}}">
                    <img src="img/logos/logo.png" alt="logo">
                </a>
            </div>
            <div class="clearfix"></div>
            <h3>Sign into your account</h3>
            <form action="index.html" method="GET">
                <div class="form-group">
                    <input type="email" name="email" class="input-text" placeholder="Email Address">
                </div>
                <div class="form-group">
                    <input type="password" name="Password" class="input-text" placeholder="Password">
                </div>
                <div class="checkbox">
                    <div class="ez-checkbox pull-left">
                        <label>
                            <input type="checkbox" class="ez-hide">
                            Remember me
                        </label>
                    </div>
                    <a href="forgot-password.html" class="link-not-important pull-right">Forgot Password</a>
                    <div class="clearfix"></div>
                </div>
                <div class="form-group">
                    <button type="submit" class="button-md button-theme btn-block">login</button>
                </div>
            </form>
            <ul class="social-list clearfix">
                <li><a href="#" class="facebook-bg"><i class="fa fa-facebook"></i></a></li>
                <li><a href="#" class="twitter-bg"><i class="fa fa-twitter"></i></a></li>
                <li><a href="#" class="google-bg"><i class="fa fa-google-plus"></i></a></li>
                <li><a href="#" class="linkedin-bg"><i class="fa fa-linkedin"></i></a></li>
            </ul>
        </div>
        <div class="footer">
            <span>
                Don't have an account? <a href="{{url('register')}}">Register here</a>
            </span>
        </div>
    </div>
</div>
<!-- Login section end -->
</body>