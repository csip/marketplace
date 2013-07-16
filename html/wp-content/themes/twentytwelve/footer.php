<?php
/**
 * The template for displaying the footer.
 *
 * Contains footer content and the closing of the
 * #main and #page div elements.
 *
 * @package WordPress
 * @subpackage Twenty_Twelve
 * @since Twenty Twelve 1.0
 */
?>
	</div><!-- #main .wrapper -->
	<footer id="colophon" role="contentinfo">
		<div class="center-auto-wrapper">
			<div class="site-info">
				<?php do_action( 'twentytwelve_credits' ); ?>
				<a href="/"><img class="footer-logo" src="/wp-content/uploads/2013/07/csip-logo.png"></img></a>
			</div>
			<div class="footer-links">
				<ul>
					<li style="font-weight:bold; color:#686868">Information</li>				
					<li><a style="text-decoration:none" href="/">About</a></li>
					<li><a style="text-decoration:none" href="/">Blog</a></li>
					<li><a style="text-decoration:none" href="/">Contact</a></li>				
				</ul>
			</div>
			<div class="footer-address">
				<p style="font-weight:bold"><a style="text-decoration:none" href="http://csip.vn/">Center for Social Initiatives Promotion (CSIP)</a></p>
				<p>Room 14*06B, 14*th Floor, B Tower, Ha Thanh Plaza, 102 Thai Thinh Str., Dong Da Dist., Hanoi</p>
				<p>Tel: +84 435378746 Fax: +84 435378992 / Email: <a style="text-decoration:none" href="mailto:csipvn@gmail.com">csipvn@gmail.com</a></p>
				<p>Copyright &copy; CSIP 2009-2013</p>
			</div><!-- .site-info -->
		</div>
	</footer><!-- #colophon -->
</div><!-- #page -->

<?php wp_footer(); ?>
</body>
</html>