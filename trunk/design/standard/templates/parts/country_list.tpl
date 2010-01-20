<select name="{$id}" id="{$id}">
	{foreach fetch( 'content', 'country_list' ) as $country}
		<option value="{$country.Alpha2}"{if eq($value, $country.Alpha2)} selected="selected"{/if}>{$country.Name}</option>
	{/foreach}
</select>