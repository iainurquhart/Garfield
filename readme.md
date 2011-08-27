# Garfield

Fetch the contents of a single field based on a number of parameters

## Example

Lets say you had a channel where you published photos to via a single file field, and those photos/entries were filed under categories which represented your site's sections.

![Example channel entry](http://iain.co.nz/dev/garfield.png)

On your front-end, you want to pull a random image from a certain category for the banner in appropriate sections of your website. 

The channel module is overkill for a simple task, and the query module doesn't parse file paths, and you want to keep things simple for your client, and fast + lightweight in your templates...

In the following example, our 'file' field id is 102 - so we want to return the contents of field_id_102, from a random entry which is in channel_id 20, filed under category id 105

	{exp:garfield 
			category_id="105" 
			field_id="102" 
			channel_id="20"
			random="yes" 
			parse_file_paths="yes"
			parse="inward"
		}
		<div id="feature_photo">
			{exp:ce_img:pair src="{garfield}" max_width="495"}
					<img src="{made}" alt="{garfield_title}" width="{width}" height="{height}" />
			{/exp:ce_img:pair}
		</div>
	{/exp:garfield}

Note the parse=&quot;inward&quot; parameter so ce_img can do it's thing

### Variables:

	{garfield} - the contents of the field requested
	{garfield_title} - the title of the entry requested

### Parameters:

	field_id
	category_id
	entry_id
	channel_id
	parse_file_paths (yes/no*)
	random 			(yes/no*)
	show_future 	(yes/no*)
	show_expired 	(yes/no*)
	status 			(open*)

