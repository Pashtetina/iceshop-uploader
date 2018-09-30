<?
spl_autoload_register(function ($class) {
	include 'classes/' . $class . '.php';
});

if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
	$uploader = new Image();
	$uploader->open();
}
?>
<!doctype html>
<html lang="en">
<head>
	<!-- Required meta tags -->
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

	<!-- Bootstrap CSS -->
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">

	<title>Hello, world!</title>
</head>
<body>

<div class="container">
	<div class="row">
		<div class="col-6">
			<h3>upload via link</h3>
			<form class="js-uploadbylink">
				<div class="form-group">
					<label for="link" class="sr-only">Upload image via link</label>
          <input type="text" class="form-control"  name="link" placeholder="Link here">
				</div>
<!--        <div class="form-group">-->
<!--					<label for="link" class="sr-only">Upload image via link</label>-->
<!--          <input type="text" class="form-control"  name="link[]" placeholder="Link here">-->
<!--				</div>-->
<!--        <div class="form-group">-->
<!--					<label for="link" class="sr-only">Upload image via link</label>-->
<!--          <input type="text" class="form-control"  name="link[]" placeholder="Link here">-->
<!--				</div>-->
				<button type="submit" class="btn btn-primary mb-2">Upload</button>
        <div class="js-message" style="display: none;"></div>
			</form>
		</div>
	</div>
</div>

<!-- Optional JavaScript -->
<!-- jQuery first, then Popper.js, then Bootstrap JS -->
<script src="https://code.jquery.com/jquery-3.3.1.min.js" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>
<script src="js/script.js"></script>
</body>
</html>