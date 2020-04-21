<?php
/**
 * Plans Inc
 *
 * PHP Version 7.2
 *
 * This files contains the hooks for
 * the User component from the admin Panel
 *
 * @category Social
 * @package  Midrub
 * @author   Scrisoft <asksyn@gmail.com>
 * @license  https://www.gnu.org/licenses/gpl-2.0.html GNU General Public License
 * @link     https://www.midrub.com/
 */

 // Define the constants
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * The public set_plans_options registers the dashboard plans options
 * 
 * @since 0.0.7.9
 */
set_plans_options(

    array(
        'name' => $this->lang->line('stream'),
        'icon' => '<i class="icon-grid"></i>',
        'slug' => 'stream',
        'fields' => array(

            array (
                'type' => 'checkbox_input',
                'slug' => 'app_stream',
                'label' => $this->lang->line('enable_app'),
                'label_description' => $this->lang->line('if_is_enabled_plan')
            ), array (
                'type' => 'text_input',
                'slug' => 'stream_tabs_limit',
                'label' => $this->lang->line('stream_tabs_limit'),
                'label_description' => $this->lang->line('stream_tabs_limit_description'),
                'input_type' => 'number'
            )

        )

    )

);
