<?php
/**
 * Members Inc
 *
 * PHP Version 7.2
 *
 * This files contains the hooks for
 * the Team's component from the user Panel
 *
 * @category Social
 * @package  Midrub
 * @author   Brijesh
 * @license  https://www.gnu.org/licenses/gpl-2.0.html GNU General Public License
 * @link     https://www.midrub.com/
 */

 // Define the constants
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * The public set_member_permissions registers the team's permissions
 * 
 * @since 0.0.7.9
 */
set_member_permissions(
    array(
        'name' => $this->lang->line('phpanalyzer'),
        'icon' => '<i class="icon-drawer"></i>',
        'slug' => 'phpanalyzer',
        'fields' => array(
            array (
                'type' => 'checkbox_input',
                'slug' => 'phpanalyzer',
                'label' => $this->lang->line('phpanalyzer_allow'),
                'label_description' => $this->lang->line('phpanalyzer_allow_if_enabled')
            )
        )
    )
);
