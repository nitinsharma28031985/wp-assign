<?php
/**
 * Cleverness To-Do List Plugin Help
 *
 * Creates the help tabs and displays the help content

 */

/**
 * Help tabs class
 * @package cleverness-to-do-list
 * @subpackage includes
 */
class CTDL_Help {

	/**
	 * Creates the Help tab
	 * @static
	 * @return mixed
	 */
	public static function cleverness_todo_help_tab() {
		global $cleverness_todo_page, $cleverness_todo_cat_page, $cleverness_todo_settings_page;
		$screen = get_current_screen();

		$cleverness_todo_help_sidebar = '';

		if ( $screen->id != $cleverness_todo_page && $screen->id != $cleverness_todo_cat_page && $screen->id != $cleverness_todo_settings_page ) return;

		$screen->add_help_tab( array(
		'id'         => 'cleverness_todo_list_help_tab',
		'title'      => __( 'To-Do List Help', 'cleverness-to-do-list' ),
		'callback'	 => __CLASS__.'::cleverness_todo_help',
		) );

		$screen->add_help_tab( array(
		'id'          => 'cleverness_todo_list_shortcode_tab',
		'title'       => __( 'Shortcodes', 'cleverness-to-do-list' ),
		'callback'	  => __CLASS__.'::cleverness_todo_shortcodes_help',
		) );

		$screen->add_help_tab( array(
		'id'          => 'cleverness_todo_list_faqs_tab',
		'title'       => __( 'FAQs', 'cleverness-to-do-list' ),
		'callback'	  => __CLASS__.'::cleverness_todo_faqs_help',
		) );

		$screen->add_help_tab( array(
			'id' => 'cleverness_todo_list_customizing_tab',
			'title' => __( 'Customizing', 'cleverness-to-do-list' ),
			'callback' => __CLASS__.'::cleverness_todo_customizing_help',
		) );

		if ( current_user_can( 'manage_options' ) ) {
			$screen->add_help_tab( array(
			'id'          => 'cleverness_todo_list_permissions_tab',
			'title'       => __( 'User Permissions', 'cleverness-to-do-list' ),
			'callback'	  => __CLASS__.'::cleverness_todo_permissions_help',
			) );
		}

		$screen->add_help_tab( array(
			'id'       => 'cleverness_todo_list_postplanner_tab',
			'title'    => __( 'Post Planner Integration', 'cleverness-to-do-list' ),
			'callback' => __CLASS__.'::cleverness_todo_postplanner_help',
		) );

		$screen->set_help_sidebar( $cleverness_todo_help_sidebar );

	}

	/**
	 * Creates the main help content
	 * @static
	 */
	public static function cleverness_todo_help() { ?>
		<h3><?php esc_html_e( 'To-Do List', 'cleverness-to-do-list' ); ?></h3>
		<p><?php esc_html_e( 'This plugin provides users with a to-do list feature. You can configure the plugin to have private to-do lists for each user, for all users to share a to-do list, or a master list with individual completing of items. The shared to-do list has a variety of settings available. You can assign to-do items to a specific user (includes a setting to email a new to-do item to the assigned user) and have only those to-do items assigned viewable to a user. You can also assign different permission levels using capabilities. There are also settings to show deadline and progress fields. Category support is included as well as front-end administration.', 'cleverness-to-do-list' ); ?></p>
		<p><?php esc_html_e( 'A new menu item is added to manage your list and it is also listed on a dashboard widget. A sidebar widget is available as well as shortcode to display the to-do list items on your site. There are two shortcodes for front-end administration of the list. Management of categories is restricted to the back-end.', 'cleverness - to -do-list' ); ?></p>
	<?php }

	/**
	 * Creates the FAQs help section
	 * @static
	 */
	public static function cleverness_todo_faqs_help() { ?>
		<h3><?php esc_html_e( 'Frequently Asked Questions', 'cleverness-to-do-list' ); ?></h3>

		<p><strong><?php esc_html_e( 'What should I do if I find a bug?', 'cleverness-to-do-list' ); ?></strong><br/>
		<?php esc_html_e( 'Visit the plugin website and leave a comment or contact me.', 'cleverness-to-do-list' ); ?><br/>
	
		</p>
	<?php }

	/**
	 * Creates the Customizing help section
	 * @static
	 * @since 3.4
	 */
	public static function cleverness_todo_customizing_help() { ?>
		<h3><?php esc_html_e( 'Customizing', 'cleverness-to-do-list' ); ?></h3>
		<p>I have numerous hooks in the plugin so you can customize how it looks and functions..</p>

		<p>You can create your own templates for the dashboard widget and the widget. You can find them in the /templates/ directory. Place them in your theme's folder in a directory called ctdl-templates.</p>
	<?php }

	/**
	 * Creates the shortcode help section
	 * @static
	 */
	public static function cleverness_todo_shortcodes_help() { ?>
		<h3><?php esc_html_e( 'Shortcode Documentation', 'cleverness-to-do-list' ); ?></h3>
		<h4><?php esc_html_e( 'Display a List of To-Do Items', 'cleverness-to-do-list' ); ?></h4>
		<p><strong>&#91;todolist&#93;</strong></p>

		<ul>
			<li><strong>title</strong> &#8211; <?php _e( 'you can chose <em>list</em> or <em>table</em> view. Default is <em>list</em>', 'cleverness-to-do-list' ); ?>.</li>
			<li><strong>priorities</strong> &#8211; <?php _e( 'default is <em>show</em>. Use a blank value to hide (only applies to table view)', 'cleverness-to-do-list' ); ?>.</li>
			<li><strong>assigned</strong> &#8211; <?php _e( 'default is <em>show</em>. Use a blank value to hide', 'cleverness-to-do-list' ); ?>.</li>
			<li><strong>deadline</strong> &#8211; <?php _e( 'default is <em>show</em>. Use a blank value to hide', 'cleverness-to-do-list' ); ?>.</li>
			<li><strong>progress</strong> &#8211; <?php _e( 'default is <em>show</em>. Use a blank value to hide', 'cleverness-to-do-list' ); ?>.</li>
			<li><strong>addedby</strong> &#8211; <?php _e( 'default is <em>show</em>. Use a blank value to hide', 'cleverness-to-do-list' ); ?>.</li>
			<li><strong>date</strong> &#8211; <?php _e( 'default is hide (0). Use 1 to show', 'cleverness-to-do-list' ); ?>.</li>
			<li><strong>completed</strong> &#8211; <?php _e( 'default is blank. Set to <em>show</em> to display completed items', 'cleverness-to-do-list' ); ?>. Set to <em>only</em> to show just the completed items.</li>
			<li><strong>completed_title</strong> &#8211; <?php _e( 'default is no title', 'cleverness-to-do-list' ); ?>.</li>
			<li><strong>completed_date</strong> &#8211; <?php _e( 'default is hide (0). Use 1 to show', 'cleverness-to-do-list' ); ?>.</li>
			<li><strong>list_type</strong> &#8211; <?php _e( 'default is <em>ol</em> (ordered list). Use <em>ul</em> to show an unordered list', 'cleverness-to-do-list' ); ?>.</li>
			<li><strong>category</strong> &#8211; <?php _e( 'default is <em>all</em>. Use the category ID to show a specific category', 'cleverness-to-do-list' ); ?>.</li>
		</ul>

		<p><strong><?php esc_html_e( 'Example:', 'cleverness-to-do-list' ); ?></strong><br/>
		<?php esc_html_e( 'Table view with the title of Upcoming Articles, hiding priorities, deadline, and added by.', 'cleverness-to-do-list' ); ?><br/>
		&#91;todolist title="Upcoming Articles" type="table" priorities="" deadline="" addedby=""&#93;</p>

		<hr/>

		<h4><?php esc_html_e( 'Display a Checklist of To-Do Items', 'cleverness-to-do-list' ); ?></h4>
		<p><strong>&#91;todochecklist&#93;</strong></p>

		<ul>
			<li><strong>title</strong> &#8211; <?php esc_html_e( 'default is no title', 'cleverness-to-do-list' ); ?>.</li>
			<li><strong>priority</strong> &#8211; <?php esc_html_e( 'default is hide (0). Use 1 to show', 'cleverness-to-do-list' ); ?>.</li>
			<li><strong>assigned</strong> &#8211; <?php esc_html_e( 'default is hide (0). Use 1 to show', 'cleverness-to-do-list' ); ?>.</li>
			<li><strong>deadline</strong> &#8211; <?php esc_html_e( 'default is hide (0). Use 1 to show', 'cleverness-to-do-list' ); ?>.</li>
			<li><strong>progress</strong> &#8211; <?php esc_html_e( 'default is hide (0). Use 1 to show', 'cleverness-to-do-list' ); ?>.</li>
			<li><strong>category</strong> &#8211; <?php esc_html_e( 'default is all categories (0).  Use the category ID to show a specific category', 'cleverness-to-do-list' ); ?>.</li>
			<li><strong>addedby</strong> &#8211; <?php esc_html_e( 'default is hide (0). Use 1 to show', 'cleverness-to-do-list' ); ?>.</li>
			<li><strong>date</strong> &#8211; <?php esc_html_e( 'default is hide (0). Use 1 to show', 'cleverness-to-do-list' ); ?>.</li>
			<li><strong>editlink</strong> &#8211; <?php esc_html_e( 'default is hide (0). Use 1 to show', 'cleverness-to-do-list' ); ?>.</li>
			<li><strong>todoid</strong> &#8211; <?php esc_html_e( 'default is blank (""). Use the ID of the to-do item to display just one item', 'cleverness-to-do-list' ); ?>.</li>
			<li><strong>completed</strong> &#8211; <?php esc_html_e( 'default is 0. Use 1 to show completed items only', 'cleverness-to-do-list' ); ?></li>
		</ul>
		<p><strong><?php esc_html_e( 'Example:', 'cleverness-to-do-list' ); ?></strong><br/>
		<?php esc_html_e( 'Set the title to "My To-Do List" and show the deadline and only items in a specific category.', 'cleverness-to-do-list' ); ?><br/>
		&#91;todochecklist title="My To-Do List" deadline=1 category=1&#93;</p>

		<hr/>

		<h4><?php esc_html_e( 'Display a To-Do List Administration Area', 'cleverness-to-do-list' ); ?></h4>
		<p><strong>&#91;todoadmin&#93;</strong></p>

		<ul>
			<li><strong>title</strong> &#8211; <?php esc_html_e( 'default is no title', 'cleverness-to-do-list' ); ?>.</li>
			<li><strong>priority</strong> &#8211; <?php esc_html_e( 'default is hide (0). Use 1 to show', 'cleverness-to-do-list' ); ?>.</li>
			<li><strong>assigned</strong> &#8211; <?php esc_html_e( 'default is hide (0). Use 1 to show', 'cleverness-to-do-list' ); ?>.</li>
			<li><strong>deadline</strong> &#8211; <?php esc_html_e( 'default is hide (0). Use 1 to show', 'cleverness-to-do-list' ); ?>.</li>
			<li><strong>progress</strong> &#8211; <?php esc_html_e( 'default is hide (0). Use 1 to show', 'cleverness-to-do-list' ); ?>.</li>
			<li><strong>categories</strong> &#8211; <?php esc_html_e( 'default is hide (0). Use 1 to show', 'cleverness-to-do-list' ); ?>.</li>
			<li><strong>addedby</strong> &#8211; <?php esc_html_e( 'default is hide (0). Use 1 to show', 'cleverness-to-do-list' ); ?>.</li>
			<li><strong>date</strong> &#8211; <?php esc_html_e( 'default is hide (0). Use 1 to show', 'cleverness-to-do-list' ); ?>.</li>
			<li><strong>editlink</strong> &#8211; <?php esc_html_e( 'default is show (1). Use 0 to hide', 'cleverness-to-do-list' ); ?>.</li>
			<li><strong>category</strong> &#8211; <?php esc_html_e( 'default is all categories (0). Use the category ID to show a specific category', 'cleverness-to-do-list' ); ?>.</li>
			<li><strong>completed</strong> &#8211; <?php esc_html_e( 'default is hide (0). Use 1 to show', 'cleverness-to-do-list' ); ?></li>
			<li><strong>completed_date</strong> &#8211; <?php esc_html_e( 'default is hide (0). Use 1 to show', 'cleverness-to-do-list' ); ?></li>
			<li><strong>planner</strong> &#8211; <?php esc_html_e( 'default is hide(0). Use 1 to show', 'cleverness-to-do-list' ); ?></li>
		</ul>
		<p><strong><?php esc_html_e( 'Example:', 'cleverness-to-do-list' ); ?></strong><br/>
		<?php esc_html_e( 'Set the title to "Things to Do" and show the priority and the progress.', 'cleverness-to-do-list' ); ?><br/>
		&#91;todoadmin title="Things to Do" priority=1 progress=1&#93;</p>
		<?php
	}

	/**
	 * Creates the user permission help section
	 * @static
	 */
	public static function cleverness_todo_permissions_help() { ?>
		<h3><?php esc_html_e( 'Additional Information on User Permissions', 'cleverness-to-do-list' ); ?></h3>

		<ul>
			<li><strong><?php esc_html_e( 'View To-Do Item Capability', 'cleverness-to-do-list' ); ?></strong> &#8211; <?php esc_html_e( 'This allows the selected capability to view to-do items', 'cleverness-to-do-list' ); ?>.</li>
			<li><strong><?php esc_html_e( 'Complete To-Do Item Capability', 'cleverness-to-do-list' ); ?></strong>
			&#8211; <?php esc_html_e( 'This allows the selected capability to mark to-do items as completed or uncompleted', 'cleverness-to-do-list' ); ?>.</li>
			<li><strong><?php esc_html_e( 'Add To-Do Item Capability', 'cleverness-to-do-list' ); ?></strong> &#8211; <?php esc_html_e( 'This allows the selected capability to add new to-do items', 'cleverness-to-do-list' ); ?>.</li>
			<li><strong><?php esc_html_e( 'Edit To-Do Item Capability', 'cleverness-to-do-list' ); ?></strong> &#8211; <?php esc_html_e( 'This allows the selected capability to edit existing to-do items', 'cleverness-to-do-list' ); ?>.</li>
			<li><strong><?php esc_html_e( 'Assign To-Do Item Capability', 'cleverness-to-do-list' ); ?></strong>
			&#8211; <?php esc_html_e( 'This allows the selected capability to assign to-do items to individual users', 'cleverness-to-do-list' ); ?>.</li>
			<li><strong><?php esc_html_e( 'View All Assigned Tasks Capability', 'cleverness-to-do-list' ); ?></strong>
			&#8211; <?php esc_html_e( 'This allows the selected capability to view all tasks even if Show Each User Only Their Assigned Tasks is set to Yes', 'cleverness-to-do-list' ); ?>.</li>
			<li><strong><?php esc_html_e( 'Delete To-Do Item Capability', 'cleverness-to-do-list' ); ?></strong> &#8211; <?php esc_html_e( 'This allows the selected capability to delete individual to-do items', 'cleverness-to-do-list' ); ?>.</li>
			<li><strong><?php esc_html_e( 'Purge To-Do Items Capability', 'cleverness-to-do-list' ); ?></strong>
			&#8211; <?php esc_html_e( 'This allows the selected capability to purge all the completed to-do items', 'cleverness-to-do-list' ); ?>.</li>
			<li><strong><?php esc_html_e( 'Add Categories Capability', 'cleverness-to-do-list' ); ?></strong> &#8211; <?php esc_html_e( 'This allows the selected capability to add new categories', 'cleverness-to-do-list' ); ?>.</li>
		</ul>

	<?php
	}

	/**
	 * Creates the post planner help section
	 * @static
	 * @since 3.2
	 */
	public static function cleverness_todo_postplanner_help() {
	}

}