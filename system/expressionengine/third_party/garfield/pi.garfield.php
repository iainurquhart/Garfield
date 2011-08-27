<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * ExpressionEngine - by EllisLab
 *
 * @package		ExpressionEngine
 * @author		ExpressionEngine Dev Team
 * @copyright	Copyright (c) 2003 - 2011, EllisLab, Inc.
 * @license		http://expressionengine.com/user_guide/license.html
 * @link		http://expressionengine.com
 * @since		Version 2.0
 * @filesource
 */
 
// ------------------------------------------------------------------------

/**
 * Garfield Plugin
 *
 * @package		ExpressionEngine
 * @subpackage	Addons
 * @category	Plugin
 * @author		Iain
 * @link		http://iain.co.nz
 * @license		Don't be a dick, 2011.
 */

$plugin_info = array(
	'pi_name'		=> 'Garfield',
	'pi_version'	=> '1.0',
	'pi_author'		=> 'Iain',
	'pi_author_url'	=> 'http://iain.co.nz',
	'pi_description'=> 'Fetch a given field from an entry',
	'pi_usage'		=> Garfield::usage()
);


class Garfield {

	public $return_data;
    
	/**
	 * Constructor
	 */
	public function __construct()
	{
		$this->EE =& get_instance();
		
		// fetch params
		$field_id 			= $this->_get_param('field_id', FALSE);
		$category_id 		= $this->_get_param('category_id', FALSE);
		$entry_id 			= $this->_get_param('entry_id', FALSE);
		$channel_id			= $this->_get_param('channel_id', FALSE);
		$parse_file_paths 	= $this->_get_param('parse_file_paths', FALSE);
		$random 			= $this->_get_param('random', FALSE);
		$show_future		= $this->_get_param('show_future', 'no');
		$show_expired 		= $this->_get_param('show_expired', 'no');
		$status 			= $this->_get_param('status', 'open');
		$timestamp 			= $this->EE->localize->now;
		
		//get tagdata
		$tagdata = $this->EE->TMPL->tagdata;
		
		if(!$field_id || !$tagdata)
			return NULL;
		
		// build our query		
		$this->EE->db->select('title, field_id_'.$field_id.' as field_data');
		$this->EE->db->from('channel_data');
		$this->EE->db->join('channel_titles', 'channel_data.entry_id = channel_titles.entry_id');
		$this->EE->db->where('status', $status);
		
		if($show_future != 'yes')
		{
			$this->EE->db->where('entry_date <', $timestamp);
		}
		
		if($show_expired != 'yes')
		{
			$where = "(expiration_date = 0 OR expiration_date > ".$timestamp.")";
			$this->EE->db->where($where);
		}
		
		if($category_id)
		{
			$this->EE->db->join('category_posts', 'channel_data.entry_id = category_posts.entry_id');
			$this->EE->db->where('category_posts.cat_id', $category_id);
		}
		
		if($entry_id)
		{
			$this->EE->db->where('channel_data.entry_id', $entry_id);
		}
		
		if($channel_id)
		{
			$this->EE->db->where('channel_titles.channel_id', $channel_id);
		}
		
		$this->EE->db->where('field_id_'.$field_id.' !=', '');
		
		if($random == 'yes')
		{
			$this->EE->db->order_by('RAND()'); 
		}
		
		$this->EE->db->limit(1);
		
		$query = $this->EE->db->get();
		
		$field_data = $query->row('field_data');
		$entry_title = $query->row('title');
				
		if(!$field_data)
		{
			$this->EE->TMPL->log_item("GARFIELD: No Data to return");
			return NULL;
		}
		
		if($parse_file_paths == 'yes')
		{
			$this->EE->load->library('typography');
			$this->EE->typography->parse_images = TRUE;
			$field_data = $this->EE->typography->parse_file_paths($field_data);
		}
		
		$variables = array(
							'garfield' => $field_data,
							'garfield_title' => $entry_title
						  );

		$tmp = $this->EE->functions->prep_conditionals($tagdata, $variables);
		$this->return_data = $this->EE->functions->var_swap($tmp, $variables);		
		
	}
	
	
	
	/**
     * Helper function for getting a parameter
	 */		 
	 function _get_param($key, $default_value = '')
	{
		$val = $this->EE->TMPL->fetch_param($key);
		
		if($val == '') {
			return $default_value;
		}
		return $val;
	}
	
	// ----------------------------------------------------------------
	
	/**
	 * Plugin Usage
	 */
	public static function usage()
	{
		ob_start();
?>

Lets say you had a channel where you published photos to via a single file field, and those photos/entries were filed under categories which represented your site's sections.

You want to pull a random image from a certain category for the banner in appropriate sections of your website. 

The channel module is overkill for a simple task, and the query module doesn't parse file paths, and you want to keep things simple for your client, and fast + lightweight in your templates...

In the following example, we want to return the contents of file_id 102, from a random entry which is in channel_id 20 filed under category id 105

{exp:garfield 
        category_id="105" 
        field_id="102"
        channel_id="20"
        random="yes" 
        parse_file_paths="yes"
        parse="inward"
    }
        {exp:ce_img:pair src="{garfield}" max_width="495"}
                <img src="{made}" alt="{garfield_title}" width="{width}" height="{height}" />
        {/exp:ce_img:pair}
{/exp:garfield}

Note the parse="inward" parameter so ce_img can do it's thing

Variables:

{garfield} - the contents of the field requested
{garfield_title} - the title of the entry requested

Parameters:

field_id
category_id
entry_id
channel_id
parse_file_paths (yes/no*)
random 			(yes/no*)
show_future 	(yes/no*)
show_expired 	(yes/no*)
status 			(default is open)

<?php
		$buffer = ob_get_contents();
		ob_end_clean();
		return $buffer;
	}
}


/* End of file pi.garfield.php */
/* Location: /system/expressionengine/third_party/garfield/pi.garfield.php */