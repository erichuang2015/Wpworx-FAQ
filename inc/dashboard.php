<?php  if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly   ?>
<div class="dashboard-mid-content">
	<div class="container">
		
		<div class="row-mid-content accordion-outer">
			<button class="accordion active">Get Support</button>
			<div class="panel" style="max-height: 296px;">
			  <ul class="get-support inline-ul clearfix">
			  	<li>
			  		<a href="">
				  		<div class="get-support-outer">
				  			<div class="get-support-icon">
				  				<img src="<?php echo plugin_dir_url( dirname( __FILE__ ) ) . 'images/support-icon-1.png'; ?>">
				  			</div>
				  			<div class="get-support-txt">
				  				<h2>YouTube Tutorials</h2>
				  			</div>
				  		</div>
			  		</a>
			  	</li>

			  	<li>
			  		<a href="">
				  		<div class="get-support-outer">
				  			<div class="get-support-icon">
				  				<img src="<?php echo plugin_dir_url( dirname( __FILE__ ) ) . 'images/faq-icon.png'; ?>">
				  			</div>
				  			<div class="get-support-txt">
				  				<h2>Plugin FAQs</h2>
				  			</div>
				  		</div>
			  		</a>
			  	</li>

			  	<li>
			  		<a href="">
				  		<div class="get-support-outer">
				  			<div class="get-support-icon">
				  				<img src="<?php echo plugin_dir_url( dirname( __FILE__ ) ) . 'images/support-icon-3.png'; ?>">
				  			</div>
				  			<div class="get-support-txt">
				  				<h2>Support Forum</h2>
				  			</div>
				  		</div>
			  		</a>
			  	</li>

			  	<li>
			  		<a href="">
				  		<div class="get-support-outer">
				  			<div class="get-support-icon">
				  				<img src="<?php echo plugin_dir_url( dirname( __FILE__ ) ) . 'images/support-icon-4.png'; ?>">
				  			</div>
				  			<div class="get-support-txt">
				  				<h2>Documentation</h2>
				  			</div>
				  		</div>
			  		</a>
			  	</li>
			  </ul>
			</div>
		</div>



		<div class="row-mid-content accordion-outer">
			<h2 class="people-say-heading">Customer Sharing thoughts on Wpworx FAQ</h2>
			<div class="people-say-bx">
				<img src="<?php echo plugin_dir_url( dirname( __FILE__ ) ) . 'images/star-img.png'; ?>">
				<h2 class="people-say-bx-heading">"Great FAQ Plugin with Almost Everything I needed."</h2>
				<p class="people-sub-heading">- Darren</p>
		
			</div>
		</div>
	</div>

	<div class="premium-version-outer">
		<div class="container">
			<h2 class="premium-version">A comprehensive FAQ Plugin with Out-of-Imagination Features</h2>
			<div class="row clearfix">
				<div class="col-md-3">
					<div class="premium-version-inner">
						<ul class="check-ul">
							<li>Add unlimited Questions</li>
							<li>As many Categories as you want</li>
							<li>Numerous Styling Options</li>
						</ul>
					</div>
				</div>

				<div class="col-md-3">
					<div class="premium-version-inner">
						<ul class="check-ul">
							<li>SEO Friendly in Deployment</li>
							<li>Works well Custom Posts</li>
							<li>Reusable Questions and FAQs</li>
						</ul>
					</div>
				</div>

				<div class="col-md-3">
					<div class="premium-version-inner">
						<ul class="check-ul">
							<li>Integrate with shortcodes</li>
							<li>Add in any page for any number of times</li>
							<li>Allows Custom CSS</li>
						</ul>
					</div>
				</div>


			</div>
		</div>
	</div>

	<div class="footer">
		<div class="container">
			<ul class="inline-ul ftr-ul clearfix">
				<li class="star-li">
					<img src="<?php echo plugin_dir_url( dirname( __FILE__ ) ) . 'images/faq-icon.png'; ?>">
					<p>Wpworx FAQ Plugin is designed to let the WordPress website owners set up the best-in-appearance FAQs on their websites. Using a totally different approach, our plugin allows you to create Questions and map these questions to multiple or single categories. Using the category's short-code, Xtreem FAQ can be integrated anywhere on your website. From color to background and rendering style, users have full control on their FAQs, built using this robust, highly-customizable, mobile-responsive and feature-rich plugin.</p>
				</li>


			</ul>
		</div>
	</div>
</div>
</div>

<script>
var acc = document.getElementsByClassName("accordion");
var i;
for (i = 0; i < acc.length; i++) {
  acc[i].addEventListener("click", function() {
    this.classList.toggle("active");
    var panel = this.nextElementSibling;
    if (panel.style.maxHeight){
      panel.style.maxHeight = null;
    } else {
      panel.style.maxHeight = panel.scrollHeight + "px";
    } 
  });
}
</script>
