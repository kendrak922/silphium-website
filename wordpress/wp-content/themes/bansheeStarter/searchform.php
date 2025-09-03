<div class="search">
	<form method="get" id="searchform" class="searchform" action="<?php bloginfo('url'); ?>/">
		<label class="search-form--label" for="s" aria-labelledby="search-label">
			<span id="search-label" class="sr_only">Search this site</span>
			<button type="submit" name="submit" class="searchform__submit btn--normalize">
				<?php echo GetIconMarkup('icon-search'); ?>
			</button>
			<input type="text" value="<?php echo isset($_GET['s']) ? filter_var($_GET['s'], FILTER_SANITIZE_STRING) : ''; ?>" name="s" id="s" placeholder="Search&hellip;" />
		</label>
		<input type="hidden" name="search-type" value="normal" />
		<button type="submit" name="submit" class="searchform__submit btn--normalize searchform__submit--arrow">
			<?php echo GetIconMarkup('icon-arrow-right'); ?>
		</button>
	</form>
</div>