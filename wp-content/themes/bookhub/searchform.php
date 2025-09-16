<?php
$query = get_search_query();
?>
<form role="search" method="get" class="search-form" action="<?php echo esc_url( home_url('/') ); ?>">
	<label class="sr-only" for="searchform-input">Search for:</label>
	<input id="searchform-input" type="search" class="h-10 w-full rounded-md border border-gray-300 px-3 text-sm focus:border-brand focus:ring-1 focus:ring-brand" placeholder="Search..." value="<?php echo esc_attr( $query ); ?>" name="s" />
	<button type="submit" class="mt-3 inline-flex items-center justify-center rounded-md bg-brand px-4 py-2 text-white text-sm hover:bg-brand-dark">Search</button>
</form>


