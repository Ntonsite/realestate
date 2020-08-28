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
            <h3>Create an account</h3>
            <form action="index.html" method="GET">
                <div class="form-group">
                    <input type="text" name="fullname" class="input-text" placeholder="Full Name">
                </div>
                <div class="form-group">
                    <input type="email" name="email" class="input-text" placeholder="Email Address">
                </div>
                <div class="form-group">
                    <input type="password" name="password" class="input-text" placeholder="Password">
                </div>
                <div class="form-group">
                    <input type="password" name="confirm_Password" class="input-text" placeholder="Confirm Password">
                </div>
                <div class="form-group">
                    <button type="submit" class="button-md button-theme btn-block">Signup</button>
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
                            Already a member? <a href="{{url('login')}}">Login here</a>
                        </span>
        </div>
    </div>
</div>
</body>