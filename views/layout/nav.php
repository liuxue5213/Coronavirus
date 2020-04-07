<nav class="navbar navbar-expand-lg navbar-dark  bg-dark">
	<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarTogglerDemo03" aria-controls="navbarTogglerDemo03" aria-expanded="false" aria-label="Toggle navigation">
		<span class="navbar-toggler-icon"></span>
	</button>
	<span class="text-white" style="padding-bottom: 3px;">
                <svg style="width: 25px;" aria-hidden="true" focusable="false" data-prefix="fas" data-icon="globe" class="svg-inline--fa fa-globe fa-w-16" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 496 512">
                <path fill="currentColor" d="M336.5 160C322 70.7 287.8 8 248 8s-74 62.7-88.5 152h177zM152 256c0 22.2 1.2 43.5 3.3 64h185.3c2.1-20.5 3.3-41.8 3.3-64s-1.2-43.5-3.3-64H155.3c-2.1 20.5-3.3 41.8-3.3 64zm324.7-96c-28.6-67.9-86.5-120.4-158-141.6 24.4 33.8 41.2 84.7 50 141.6h108zM177.2 18.4C105.8 39.6 47.8 92.1 19.3 160h108c8.7-56.9 25.5-107.8 49.9-141.6zM487.4 192H372.7c2.1 21 3.3 42.5 3.3 64s-1.2 43-3.3 64h114.6c5.5-20.5 8.6-41.8 8.6-64s-3.1-43.5-8.5-64zM120 256c0-21.5 1.2-43 3.3-64H8.6C3.2 212.5 0 233.8 0 256s3.2 43.5 8.6 64h114.6c-2-21-3.2-42.5-3.2-64zm39.5 96c14.5 89.3 48.7 152 88.5 152s74-62.7 88.5-152h-177zm159.3 141.6c71.4-21.2 129.4-73.7 158-141.6h-108c-8.8 56.9-25.6 107.8-50 141.6zM19.3 352c28.6 67.9 86.5 120.4 158 141.6-24.4-33.8-41.2-84.7-50-141.6h-108z"></path>
                </svg>
                <a class="navbar-brand text-white" href="index.php" style="padding-left:5px;">JohnScott</a>
            </span>
	<div class="collapse navbar-collapse" id="navbarTogglerDemo03">
		<ul class="navbar-nav mr-auto mt-2 mt-lg-0">
			<li class="nav-item <?php echo empty($nav) || $nav == 'index' ? 'active': '';?>">
				<a class="nav-link text-white" href="index.php?nav=index"><b>Data</b></a>
			</li>
			<li class="nav-item <?php echo $nav == 'map' ? 'active': '';?>">
				<a class="nav-link text-white" href="map.php?nav=map"><b>Map</b></a>
			</li>
			<li class="nav-item <?php echo $nav == 'map' ? 'active': '';?>">
				<a class="nav-link text-white" href="wiki.php?nav=wiki"><b>Wiki</b></a>
			</li>
			<li class="nav-item <?php echo $nav == 'baidu' ? 'active': '';?>">
				<a class="nav-link text-white" href="baidu.php?nav=baidu"><b>Baidu</b></a>
			</li>
			<li class="nav-item <?php echo $nav == 'subscribe' ? 'active': '';?>">
				<a class="nav-link text-white" data-toggle="modal" data-target="#Subscribe" style="text-decoration: cou"><b>Subscribe</b></a>
			</li>
            <li class="nav-item <?php echo $nav == 'about' ? 'active': '';?>">
                <a class="nav-link text-white" href="about.php?nav=about"><b>About</b></a>
            </li>
		</ul>
	</div>
</nav>

<!-- Modal -->
<div class="modal fade" id="Subscribe" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="exampleModalLongTitle"><b>订阅</b></h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <h2>Get Info on the Coronavirus</h2>
                <div class="form-group">
                    <label for="email">Email Address <span style="color: red">*</span></label><br>
                    <input id="email" class="form-control" type="email">
                </div>
                <div class="form-group">
                    <label for="name">Name</label><br>
                    <input id="name" class="form-control" type="text">
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button id="sub" type="button" class="btn btn-primary" onclick="sub()">Subscribe</button>
            </div>
        </div>
    </div>
    <button type="button" class="btn btn-secondary" data-container="body" data-toggle="popover-dismiss" data-placement="top" data-content="Vivamus sagittis lacus vel augue laoreet rutrum faucibus.">
        Popover on top
    </button>
</div>
<script>
    function sub() {
        var pass = true;
        var email = $('#email').val();
        var name = $('#name').val();
        if (email.trim() == '') {
            alert('11111');
            pass = false;
            return false;
        }
        if (name.trim() == '') {
            alert('22222');
            pass = false;
            return false;
        }
        if (pass) {
            $('.popover-dismiss').popover({
                trigger: 'focus'
            })
            return false;

        }
    }
</script>