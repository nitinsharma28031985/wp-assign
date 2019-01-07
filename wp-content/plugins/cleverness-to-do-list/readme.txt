== Description ==

This plugin provides users with a to-do list feature.

You can configure the plugin to have private to-do lists for each user, to have all users share a to-do list, or to have a master list with individual completion of items. The shared to-do list has a variety of settings
available. You can assign to-do items to a specific user (includes a setting to email a new to-do item to the assigned user) and optionally have those items only viewable by that user. You can also assign different
permission levels using capabilities. There are also settings to show deadline and progress fields. Category support is included as well as front-end administration.

A new menu item is added to the backend to manage your list and the to-do list is also listed on a dashboard widget.

A sidebar widget is available as well as a shortcode to display the to-do list items on your site.

There are two shortcodes for front-end administration of the list. Management of categories is restricted to the back-end.


== Installation ==

1. Upload the folder `/cleverness-to-do-list/` to the `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress
3. Configure the settings on the Settings page under the To-Do List menu

== Frequently Asked Questions ==

= What is the shortcode to display items in a post or page? =
[todolist]

Several options are available:

* **title** - default is no title.
* **type** - you can chose *list* or *table* view. Default is *list*.
* **priorities** - default is *show*. Use a blank value to hide (only applies to table view).
* **assigned** - default is *show*. Use a blank value to hide.
* **deadline** - default is *show*. Use a blank value to hide.
* **progress** - default is *show*. Use a blank value to hide.
* **addedby** - default is *show*. Use a blank value to hide.
* **date** - default is hide (0). Use 1 to show.
* **completed** - default is blank. Set to *show* to display completed items. Set to *only* to show just the completed items.
* **completed_title** - default is no title.
* **completed_date** – default is hide (0). Use 1 to show.
* **list_type** - default is *ol* (ordered list). Use *ul* to show an unordered list.
* **category** - default is *all*. Use the category ID to show a specific category.

Example:

Table view with the title of Upcoming Articles, hiding priorities, deadline, and added by.

[todolist title="Upcoming Articles" type="table" priorities="" deadline="" addedby=""]

= What is the shortcode to display a checklist in a post or page? =
[todochecklist]

The options are:

* **title** - default is no title.
* **priority** - default is hide (0). Use 1 to show.
* **assigned** - default is hide (0). Use 1 to show.
* **deadline** - default is hide (0). Use 1 to show.
* **progress** - default is hide (0). Use 1 to show.
* **category** - default is all categories (0). Use the category ID to show a specific category.
* **addedby** - default is hide (0). Use 1 to show.
* **date** - default is hide (0). Use 1 to show.
* **editlink** - default is hide (0). Use 1 to show.
* **todoid** - default is blank (""). Use the ID of the to-do item to display just one item.
* **completed** – default is 0. Use 1 to show completed items only

Example:

Set the title to "My To-Do List" and show the deadline and only items in a specific category.

[todochecklist title="My To-Do List" deadline=1 category=1]

= What is the shortcode to display the administration page in the front-end? =
[todoadmin]

The options are:

* **title** - default is no title.
* **priority** - default is hide (0). Use 1 to show.
* **assigned** - default is hide (0). Use 1 to show.
* **deadline** - default is hide (0). Use 1 to show.
* **progress** - default is hide (0). Use 1 to show.
* **categories** - default is hide (0). Use 1 to show.
* **addedby** - default is hide (0). Use 1 to show.
* **date** - default is hide (0). Use 1 to show.
* **editlink** - default is show (1). Use 0 to hide.
* **category** - default is all categories (0).  Use the category ID to show a specific category.
* **completed** – default is hide (0). Use 1 to show
* **completed_date** – default is hide (0). Use 1 to show
* **planner** – default is hide(0). Use 1 to show

Example:

Set the title to "Things to Do" and show the priority and the progress.

[todoadmin title="Things to Do" priority=1 progress=1]

= Can you explain the permissions in more detail? =

* **View To-Do Item Capability** - This allows the selected capability to view to-do items.
* **Complete To-Do Item Capability** - This allows the selected capability to mark to-do items as completed or uncompleted.
* **Add To-Do Item Capability** - This allows the selected capability to add new to-do items.
* **Edit To-Do Item Capability** - This allows the selected capability to edit existing to-do items.
* **Assign To-Do Item Capability** - This allows the selected capability to assign to-do items to individual users.
* **View All Assigned Tasks Capability** - This allows the selected capability to view all tasks even if *Show Each User Only Their Assigned Tasks* is set to *Yes*.
* **Delete To-Do Item Capability** - This allows the selected capability to delete individual to-do items.
* **Purge To-Do Items Capability** - This allows the selected capability to purge all the completed to-do items.
* **Add Categories Capability** - This allows the selected capability to add new categories.



You can create your own templates for the dashboard widget and the widget. You can find them in the `/templates/` directory. Place them in your theme's folder in a directory called `ctdl-templates`.


== Screenshots ==

1. Dashboard Widget
2. To-Do List Page
3. Settings Page
4. Everything Enabled

