<?php
/**
 * The template for displaying the footer.
 *
 * Contains the closing of the #content div and all content after
 *
 * @package FactoryHub
 */
?>
			</div> <!-- .row -->
		</div> <!-- .container -->
	</div><!-- #content -->

	<?php do_action( 'factoryhub_before_footer' ) ?>

	<footer id="colophon" class="site-footer">
		<?php do_action( 'factoryhub_footer' ) ?>
	</footer><!-- #colophon -->

	<?php do_action( 'factoryhub_after_footer' ) ?>
</div><!-- #page -->

<?php wp_footer(); ?>

</body>
</html>
