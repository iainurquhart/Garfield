# Garfield

Fetch the contents of a single field based on a number of parameters

## Example

Lets say you had a channel where you published photos to via a single file field, and those photos/entries were filed under categories which represented your site's sections.

![Example channel entry](http://iain.co.nz/dev/garfield.png)

On your front-end, you want to pull a random image from a certain category for the banner in appropriate sections of your website. 

The channel module is overkill for a simple task, and the query module doesn't parse file paths, and you want to keep things simple for your client, and fast + lightweight in your templates...

In the following example, our 'file' field id is 102 - so we want to return the contents of field_id_102, from a random entry which is in channel_id 20, filed under category id 105

	{exp:garfield 
	        category_id=&quot;105&quot; 
	        field_id=&quot;102&quot;
	        channel_id=&quot;20&quot;
	        random=&quot;yes&quot; 
	        parse_file_paths=&quot;yes&quot;
	        parse=&quot;inward&quot;
	    }
	        {exp:ce_img:pair src=&quot;{garfield}&quot; max_width=&quot;495&quot;}
	                &lt;img src=&quot;{made}&quot; alt=&quot;{garfield_title}&quot; width=&quot;{width}&quot; height=&quot;{height}&quot; /&gt;
	        {/exp:ce_img:pair}
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

