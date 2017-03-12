<?php
/****************************************************************************************//**
*   \file   bmlt-drupal-satellite-plugin.php                                                *
*                                                                                           *
*   \brief  This is a Drupal plugin of a BMLT satellite client.                             *
*   \version 3.4.4                                                                          *
*                                                                                           *
    This file is part of the Basic Meeting List Toolbox (BMLT).
    
    Find out more at: http://magshare.org/bmlt
    
    BMLT is free software: you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation, either version 3 of the License, or
    (at your option) any later version.
    
    BMLT is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.
    
    You should have received a copy of the GNU General Public License
    along with this code.  If not, see <http://www.gnu.org/licenses/>.
********************************************************************************************/

// define ( '_DEBUG_MODE_', 1 ); //Uncomment for easier JavaScript debugging.

// Include the satellite driver class.
require_once ( dirname ( __FILE__ ).'/BMLT-Satellite-Base-Class/bmlt-cms-satellite-plugin.php' );

global $bmlt_localization;  ///< Use this to control the localization.

if ( isset ( $_COOKIE ) && isset ( $_COOKIE['bmlt_lang_selector'] ) && $_COOKIE['bmlt_lang_selector'] )
    {
    $bmlt_localization = $_COOKIE['bmlt_lang_selector'];
    }
else
    {
    if ( !isset ( $bmlt_localization ) || !$bmlt_localization )
        {
        $language = 'en';
    
        if ( function_exists ( 'i18n_get_lang' ) )
            {
            $language = i18n_get_lang();
            }
    
        if ( $language )
            {
            $bmlt_localization = substr ( $language, 0, 2 );
            }
        }
    }
    
if ( !isset ( $bmlt_localization ) || !$bmlt_localization )
    {
    $bmlt_localization = 'en';  ///< Last-ditch default value.
    }

/****************************************************************************************//**
*   \class BMLTDrupalPlugin                                                                 *
*                                                                                           *
*   \brief This is the class that implements and encapsulates the plugin functionality.     *
*   A single instance of this is created, and manages the plugin.                           *
*                                                                                           *
*   This plugin registers errors by echoing HTML comments, so look at the source code of    *
*   the page if things aren't working right.                                                *
********************************************************************************************/

class BMLTDrupalPlugin extends BMLTPlugin
{
    /************************************************************************//**
    *                               LOCALIZABLE STRINGS                         *
    ****************************************************************************/
    var $local_strings = array ( 'en' => array (
                                    'list_text' => 'Substitute the BMLT shortcodes or HTML comments with instances of the BMLT',
                                    'add_instance' => 'Add a BMLT instance inline in text.',
                                    'bmlt' =>'Basic Meeting List Toolbox',
                                    'bmlt_settings' => 'BMLT Settings',
                                    'describe_admin' => 'Configure the BMLT Settings',
                                    'access_admin' => 'access administration pages'
                                    )
                                );
                                
    /************************************************************************************//**
    *   \brief Constructor.                                                                 *
    ****************************************************************************************/
    function __construct ()
        {
        parent::__construct ();
        }
    
    /************************************************************************************//**
    *   \brief Return an HTTP path to the AJAX callback target.                             *
    *                                                                                       *
    *   \returns a string, containing the path.                                             *
    ****************************************************************************************/
    protected function get_admin_ajax_base_uri()
        {
        return $this->get_ajax_base_uri().'?q=admin/settings/bmlt';
        }
    
    /************************************************************************************//**
    *   \brief Return an HTTP path to the basic admin form submit (action) URI              *
    *                                                                                       *
    *   \returns a string, containing the path.                                             *
    ****************************************************************************************/
    protected function get_admin_form_uri()
        {
        return $this->get_admin_ajax_base_uri();
        }
    
    /************************************************************************************//**
    *   \brief Return an HTTP path to the AJAX callback target.                             *
    *                                                                                       *
    *   \returns a string, containing the path.                                             *
    ****************************************************************************************/
    protected function get_ajax_base_uri()
        {
        $port = $_SERVER['SERVER_PORT'] ;
        // IIS puts "off" in the HTTPS field, so we need to test for that.
        $https = (!empty ( $_SERVER['HTTPS'] ) && (($_SERVER['HTTPS'] !== 'off') || ($port == 443))); 
        $server_path = $_SERVER['SERVER_NAME'];
        $my_path = $_SERVER['PHP_SELF'];
        $server_path .= trim ( (($https && ($port != 443)) || (!$https && ($port != 80))) ? ':'.$port : '', '/' );
        $server_path = 'http'.($https ? 's' : '').'://'.$server_path.$my_path;
        return $server_path;
        }
    
    /************************************************************************************//**
    *   \brief Return an HTTP path to the plugin directory.                                 *
    *                                                                                       *
    *   \returns a string, containing the path.                                             *
    ****************************************************************************************/
    protected function get_plugin_path()
        {
        if ( function_exists ( 'drupal_get_path' ) )
            {
            global $base_url;

            $ret = $base_url.'/'.drupal_get_path ( 'module', 'bmlt' ).'/BMLT-Satellite-Base-Class/';
            }
        else
            {
            $ret = isset ( $this->my_http_vars['base_url'] ) ? $this->my_http_vars['base_url'] : null;
            }
            
        return $ret;
        }
    
    /************************************************************************************//**
    *   \brief This uses the Drupal text processor (t) to process the given string.         *
    *                                                                                       *
    *   This allows easier translation of displayed strings. All strings displayed by the   *
    *   plugin should go through this function.                                             *
    *                                                                                       *
    *   \returns a string, processed by WP.                                                 *
    ****************************************************************************************/
    function process_text (  $in_string  ///< The string to be processed.
                                    )
        {
        if ( function_exists ( 't' ) )
            {
            $in_string = htmlspecialchars ( t ( $in_string ) );
            }
        else
            {
            echo "<!-- BMLTPlugin Warning (process_text): t() does not exist! -->";
            }
            
        return $in_string;
        }

    /************************************************************************************//**
    *   \brief This gets the admin options from the database (allows CMS abstraction).      *
    *                                                                                       *
    *   \returns an associative array, with the option settings.                            *
    ****************************************************************************************/
    protected function cms_get_option ( $in_option_key   ///< The name of the option
                                        )
        {        
        $ret = null;
        
        $row = unserialize ( variable_get ( 'bmlt_settings', serialize ( array ( 0 => $this->geDefaultBMLTOptions() ) ) ) );
        
        if ( $in_option_key != self::$admin2OptionsName )
            {
            $index = max ( 1, intval(str_replace ( self::$adminOptionsName.'_', '', $in_option_key ) ));
            
            $ret = isset ( $row[$index - 1] ) ? $row[$index - 1] : $defaults[$index - 1];
            }
        else
            {
            $ret = array ( 'num_servers' => count ( $row ) );
            }
        
        return $ret;
        }
    
    /************************************************************************************//**
    *   \brief This gets the admin options from the database (allows CMS abstraction).      *
    ****************************************************************************************/
    protected function cms_set_option ( $in_option_key,   ///< The name of the option
                                        $in_option_value  ///< the values to be set (associative array)
                                        )
        {
        $ret = false;

        $index = 0;
        
        if ( $in_option_key != self::$admin2OptionsName )
            {
            $index = max ( 1, intval(str_replace ( self::$adminOptionsName.'_', '', $in_option_key ) ));

            $row_data = unserialize ( variable_get ( 'bmlt_settings', serialize ( array ( 0 => $this->geDefaultBMLTOptions() ) ) ) );
            
            if ( isset ( $row_data ) && is_array ( $row_data ) && count ( $row_data ) )
                {
                $row_data[$index - 1] = $in_option_value;
                
                variable_set ( 'bmlt_settings', serialize ( $row_data ) );
    
                $ret = true;
                }
            }
        else
            {
            $ret = true; // Fake it, till you make it.
            }
        
        return $ret;
        }
    
    /************************************************************************************//**
    *   \brief Deletes a stored option (allows CMS abstraction).                            *
    ****************************************************************************************/
    protected function cms_delete_option ( $in_option_key   ///< The name of the option
                                        )
        {
        $ret = false;
        
        $row = unserialize ( variable_get ( 'bmlt_settings', serialize ( array ( 0 => $this->geDefaultBMLTOptions() ) ) ) );
        if ( $in_option_key != self::$admin2OptionsName )
            {
            $index = max ( 1, intval(str_replace ( self::$adminOptionsName.'_', '', $in_option_key ) ));
            
            unset ( $row[$index - 1] );
            
            if ( variable_set ( 'bmlt_settings', serialize ( $row ) ) )
                {
                $ret = true;
                }
            }
            
        return $ret;
        }

    /************************************************************************************//**
    *   \brief This gets the page meta for the given page. (allows CMS abstraction).        *
    *                                                                                       *
    *   \returns a mixed type, with the meta data                                           *
    ****************************************************************************************/
    protected function cms_get_post_meta (  $in_page_id,    ///< The ID of the page/post
                                            $in_settings_id ///< The ID of the meta tag to fetch
                                            )
        {
        $ret = null;
        
        return $ret;
        }

    /************************************************************************************//**
    *   \brief This function fetches the settings ID for a page (if there is one).          *
    *                                                                                       *
    *   If $in_check_mobile is set to true, then ONLY a check for mobile support will be    *
    *   made, and no other shortcodes will be checked.                                      *
    *                                                                                       *
    *   \returns a mixed type, with the settings ID.                                        *
    ****************************************************************************************/
    protected function cms_get_page_settings_id ($in_text,                  ///< Required (for the base version) content to check.
                                                 $in_check_mobile = false   ///< True if this includes a check for mobile. Default is false.
                                                )
        {
        $my_option_id = parent::cms_get_page_settings_id ( $in_text, $in_check_mobile );
                    
        if ( !$in_check_mobile && !$my_option_id )   // If nothing else gives, we go for the default (first) settings.
            {
            $options = $this->getBMLTOptions ( 1 );
            $my_option_id = $options['id'];
            }
        
        return $my_option_id;
        }
        
    /************************************************************************************//**
    *                                  THE DRUPAL CALLBACKS                                 *
    ****************************************************************************************/
        
    /************************************************************************************//**
    *   \brief Presents the admin page.                                                     *
    ****************************************************************************************/
    function admin_page ( )
        {
        echo $this->return_admin_page ( );
        }
       
    /************************************************************************************//**
    *   \brief Presents the admin menu options.                                             *
    *                                                                                       *
    * NOTE: This function requires WP. Most of the rest can probably be more easily         *
    * converted for other CMSes.                                                            *
    ****************************************************************************************/
    function option_menu ( )
        {
        if ( function_exists ( 'add_options_page' ) && (self::get_plugin_object() instanceof BMLTPlugin) )
            {
            add_options_page ( self::$local_options_title, self::$local_menu_string, 9, basename ( __FILE__ ), array ( self::get_plugin_object(), 'admin_page' ) );
            }
        elseif ( !function_exists ( 'add_options_page' ) )
            {
            echo "<!-- BMLTPlugin ERROR (option_menu)! No add_options_page()! -->";
            }
        else
            {
            echo "<!-- BMLTPlugin ERROR (option_menu)! No BMLTPlugin Object! -->";
            }
        }
        
    /************************************************************************************//**
    *   \brief Echoes any necessary head content.                                           *
    ****************************************************************************************/
    function standard_head ( $in_text = null   ///< This is the page content text.
                            )
        {
        $load_head = true;

        $additional_stuff = "<!-- Added by the BMLT plugin 2.0. -->\n<meta http-equiv=\"X-UA-Compatible\" content=\"IE=EmulateIE7\" />\n<meta http-equiv=\"Content-Style-Type\" content=\"text/css\" />\n<meta http-equiv=\"Content-Script-Type\" content=\"text/javascript\" />\n";
        
        $support_mobile = $this->cms_get_page_settings_id ( $in_text, true );
        
        if ( $support_mobile )
            {
            $mobile_options = $this->getBMLTOptions_by_id ( $support_mobile );
            }
        else
            {
            $support_mobile = null;
            }
        
        $options = $this->getBMLTOptions_by_id ( $this->cms_get_page_settings_id($in_text) );
        if ( $support_mobile && is_array ( $mobile_options ) && count ( $mobile_options ) )
            {
            $mobile_url = $_SERVER['PHP_SELF'].'?BMLTPlugin_mobile&bmlt_settings_id='.$support_mobile;
            if ( isset ( $this->my_http_vars['WML'] ) )
                {
                $mobile_url .= '&WML='.intval ( $this->my_http_vars['WML'] );
                }
            if ( isset ( $this->my_http_vars['simulate_smartphone'] ) )
                {
                $mobile_url .= '&simulate_smartphone';
                }
            
            if ( isset ( $this->my_http_vars['base_url'] ) )
                {
                $mobile_url .= '&base_url='.urlencode($this->my_http_vars['base_url']);
                }
            else
                {
                $mobile_url .= '&base_url='.urlencode($this->get_plugin_path());
                }

            ob_end_clean();
            header ( "location: $mobile_url" );
            die ( );
            }

        $this->load_params ( );
        
        $root_server_root = $options['root_server'];

        $additional_stuff .= '<meta name="BMLT-Root-URI" content="'.htmlspecialchars ( $root_server_root ).'" />';

        if ( $root_server_root )
            {
            $root_server = $root_server_root."/client_interface/xhtml/index.php";
            
            $additional_stuff = '';
            
            $url = $this->get_plugin_path().'themes/'.$options['theme'].'/';
            
            if ( !defined ('_DEBUG_MODE_' ) )
                {
                $url .= 'style_stripper.php?filename=';
                }

            if ( file_exists ( dirname ( __FILE__ ).'/BMLT-Satellite-Base-Class/themes/'.$options['theme'].'/styles.css' ) )
                {
                $additional_stuff .= '<link rel="stylesheet" type="text/css" href="'.htmlspecialchars ( $url.'styles.css' ).'" />';
                }
        
            if ( file_exists ( dirname ( __FILE__ ).'/BMLT-Satellite-Base-Class/themes/'.$options['theme'].'/nouveau_map_styles.css' ) )
                {
                $additional_stuff .= '<link rel="stylesheet" type="text/css" href="'.htmlspecialchars ( $url.'nouveau_map_styles.css' ).'" />';
                }
        
            if ( file_exists ( dirname ( __FILE__ ).'/BMLT-Satellite-Base-Class/table_styles.php' ) )
                {
                $additional_stuff .= '<link rel="stylesheet" type="text/css" href="'.$this->get_plugin_path().'/table_styles.php" />';
                }
            
            $additional_css = '.bmlt_container * {margin:0;padding:0 }';
            
            if ( $options['additional_css'] )
                {
                $additional_css .= $options['additional_css'];
                }
            
            if ( $additional_css )
                {
                $additional_stuff .= '<style type="text/css">'.preg_replace ( "|\s+|", " ", $additional_css ).'</style>';
                }
            
            if ( $additional_stuff )
                {
                if ( function_exists ( 'drupal_set_html_head' ) )
                    {
                    drupal_set_html_head ( $additional_stuff );
                    }
                elseif ( function_exists ( 'drupal_add_html_head' ) )
                    {
                    $element = array(
                                    '#type' => 'markup',
                                    '#markup' => $additional_stuff
                                    );
                    drupal_add_html_head ( $element, 'bmlt' );
                    }
                }
            }
        }
        
    /************************************************************************************//**
    *   \brief Echoes any necessary head content for the admin.                             *
    ****************************************************************************************/
    function admin_head ( )
        {
        $head_content = "<!-- Added by the BMLT plugin 2.0. -->\n<meta http-equiv=\"X-UA-Compatible\" content=\"IE=EmulateIE7\" />\n<meta http-equiv=\"Content-Style-Type\" content=\"text/css\" />\n<meta http-equiv=\"Content-Script-Type\" content=\"text/javascript\" />\n";
        $head_content .= '<script type="text/javascript" src="';
        
        $url = $this->get_plugin_path();
        
        $head_content .= htmlspecialchars ( $url );
        
        if ( !defined ('_DEBUG_MODE_' ) )
            {
            $head_content .= 'js_stripper.php?filename=';
            }
        
        $head_content .= 'javascript.js"></script>';
        
        $options = $this->getBMLTOptions(1);    // All options contain the admin key.
        $key = $options['google_api_key'];

        $head_content .= '<script type="text/javascript" src="https://maps.google.com/maps/api/js?key='.$key.'"></script>';  // Load the Google Maps stuff for our map.
        
        $head_content .= '<link rel="stylesheet" type="text/css" href="';
        
        $head_content .= htmlspecialchars ( $url );
        
        if ( !defined ('_DEBUG_MODE_' ) )
            {
            $head_content .= 'style_stripper.php?filename=';
            }
        
        $head_content .= 'admin_styles.css" />';
        
        $head_content .= '<script type="text/javascript" src="';
        
        $head_content .= htmlspecialchars ( $url );
        
        if ( !defined ('_DEBUG_MODE_' ) )
            {
            $head_content .= 'js_stripper.php?filename=';
            }
        
        $head_content .= 'admin_javascript.js"></script>';
        
        if ( function_exists ( 'drupal_set_html_head' ) )
            {
            drupal_set_html_head ( $head_content );
            }
        elseif ( function_exists ( 'drupal_add_html_head' ) )
            {
            $element = array(
                            '#type' => 'markup',
                            '#markup' => $head_content
                            );
            drupal_add_html_head ( $element, 'bmlt' );
            }
        }
};

/****************************************************************************************//**
*                                   MAIN CODE CONTEXT                                       *
********************************************************************************************/
global $BMLTPluginOp;

if ( !isset ( $BMLTPluginOp ) && class_exists ( "BMLTDrupalPlugin" ) )
    {
    $BMLTPluginOp = new BMLTDrupalPlugin();
    }