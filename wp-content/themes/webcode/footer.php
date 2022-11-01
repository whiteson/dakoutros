
	</div><!-- #content -->

	<?php get_template_part('partials/sound'); ?>


	<?php get_template_part('partials/footer-company'); ?>


	<?php get_template_part('partials/scroll-text-footer'); ?>

	<footer id="colophon" class="site-footer">


		
	</footer><!-- #colophon -->
</div><!-- #page -->

<?php wp_footer(); ?>


<?php if($_SERVER['SERVER_NAME'] == 'l.l') : ?>
<script id="__bs_script__">//<![CDATA[
    document.write("<script async src='http://HOST:3000/browser-sync/browser-sync-client.js?v=2.27.5'><\/script>".replace("HOST", location.hostname));
//]]></script>
<?php endif; ?>

</body>
</html>
