<?php
include 'util.php';
my_session_start();
?>
<html>

<head>
	<title>Home | Dancestry</title>
</head>

<body>
	<?php include 'menu.php'; ?>
	<div id="splash-logo" class="col-sm-12">
		<img src="data/images/dancestry_logo_horizontal_larger.png" alt="">
	</div>
	<div class="row text-with-image">
		<div class="column">
			<section>
				<div class="zig-zag-right-image credited-image">
					<img src="data/images/TranquilUnrest_web_PaulHokanson.jpg" alt="Trio For Common Man">
					<span class="image-credits">Image credit: Paul Hokanson</span>
				</div>
				<div>
					<h3 class="zig-zag-left-text">Welcome to Dancestry!</h3>
					<p class="zig-zag-left-text">Thank you for visiting our site. We hope you will spend time browsing through artists
						and their lineal connections on the network. We also hope that you will contribute
						your own lineage to this expanding global resource.
					</p>
				</div>
			</section>
		</div>

	</div>
	<hr class="between-text-with-image">
	<div class="row text-with-image">
		<div class="column">
			<section>
				<div class="zig-zag-left-image credited-image">
					<img src="data/images/Reverie_web_PaulHokanson.jpg" alt="Reverie">
					<span class="image-credits">Image credit: Paul Hokanson</span>
				</div>
				<div>
					<h3 class="zig-zag-right-text">What is Dancestry?</h3>
					<p class="zig-zag-right-text">Dancestry is an interactive, web-based network illustrating
						connections between <font color=#0820aa> dance artists,
						</font> their <font color=#016400> teachers, </font> their <font color=#016400> students,
						</font> their <font color=#969101> collaborators</font> and people who they were
						influenced by. The main goal is to establish a knowledge base documenting 20th and
						21st century dance that is searchable and minable and that will continue to grow as new
						generations of artists are added. Dancestry is intended as a global resource
						for investigating artistic influences, career paths, choreographic connections, and
						complex and obscure relationships.
					</p>
				</div>
			</section>
		</div>
	</div>
	<hr class="between-text-with-image">
	<div class="row text-with-image">
		<div class="column">
			<section>
				<div class="zig-zag-right-image credited-image">
					<img src="data/images/Aspen_KenSmith.JPG" alt="Aspen">
					<span class="image-credits">Image credit: Ken Smith</span>
				</div>
				<div>
					<h3 class="zig-zag-left-text">Contribute your Lineage</h3>
					<p class="zig-zag-left-text">Your thoughtful completion of the lineage survey regarding your dance background and major
						influences will be invaluable to the creation of this resource. The completion of the
						Dancestry survey is voluntary and can be accessed by
						clicking <a href="profiles.php">here.</a>
					</p>
				</div>
			</section>
			<section>
				<p class="zig-zag-left-text">For assistance using this website resource please write to
					<a href="mailto:Dancestryglobal@gmail.com">Dancestryglobal@gmail.com</a>
				</p>
			</section>
		</div>
	</div>
	<script>
		var acc = document.getElementsByClassName("accordion");
		var i;

		for (i = 0; i < acc.length; i++) {
			acc[i].onclick = function() {
				this.classList.toggle("active");
				var panel = this.nextElementSibling;
				if (panel.style.display === "block") {
					panel.style.display = "none";
				} else {
					panel.style.display = "block";
				}
			}
		}
	</script>
	<?php
	include 'footer.php';
	?>
</body>

</html>