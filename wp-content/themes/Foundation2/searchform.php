<?php do_action('foundationPress_before_searchform'); ?>
<form role="search" method="get" id="searchform" action="<?php echo home_url('/'); ?>">
	<div class="row">
		<?php do_action('foundationPress_searchform_top'); ?>
		<div class="large-8 columns">
			<input type="text" value="" name="s" id="s" placeholder="<?php esc_attr_e('Αναζητήστε προϊόντα ή κωδικούς', 'FoundationPress'); ?>">
		</div>
		<?php do_action('foundationPress_searchform_before_search_button'); ?>
		<div class="large-4 columns">
			<input type="submit" id="searchsubmit" value="<?php esc_attr_e('Αναζήτηση', 'FoundationPress'); ?>" class="prefix button">
		</div>
		<?php do_action('foundationPress_searchform_after_search_button'); ?>
	</div>
</form>
<?php do_action('foundationPress_after_searchform'); ?>
