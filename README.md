# Features
* Routes for retrieving menu by name: pschild-angular/v1/menu/<MENU_NAME>
* Additional image sizes. A scaled version of an image is automatically created when uploaded.
* Adds post types "Timeline" and "Project" (with taxonomies)
* Enables custom templates for type "post" (not only page) in combination with "Advanced Custom Fields"
* Shortcodes
    * [codeblock params]
        * Params:
            * language (string): Language of code. [codeblock language=html]
            * url (string): URL of a file, e.g. [codeblock url=https<nolink>://raw.githubusercontent.com/[...]/script.js]
            * code (string): Code, e.g. [codeblock code=alert('Hello World')]
        * Example: [codeblock url=https://raw.githubusercontent.com/pschild/CodeRadarVisualization/master/index.html]
* sharer/sharer.php
    * og-tags cannot be set dynamically via JavaScript.
    * Use this static site to generate og-tags for sharing on Facebook.

## templates
### template-page-....php
* Templates for Wordpress type "page"

### template-post-....php
* Templates for Wordpress type "post"

# Necessary Wordpress Plugins
* [Advanced Custom Fields](https://www.advancedcustomfields.com/): Customise WordPress with powerful, professional and intuitive fields. Version: 4.4.11