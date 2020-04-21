<?php
/**
 * Page Fans Template
 *
 * This file contains the Stream's Page Fans template 
 *
 * @author Scrisoft
 * @package Midrub
 * @since 0.0.7.7
 */

// Define the page namespace
namespace MidrubBase\User\Apps\Collection\Stream\Templates;

// Constants
defined('BASEPATH') OR exit('No direct script access allowed');

// Define the namespaces to use
use MidrubBase\User\Apps\Collection\Stream\Interfaces as MidrubBaseUserAppsCollectionStreamInterfaces;
use MidrubBase\User\Apps\Collection\Stream\Helpers as MidrubBaseUserAppsCollectionStreamHelpers;

/*
 * Page_fans contains the Stream's Page_fans template 
 * 
 * @author Scrisoft
 * @package Midrub
 * @since 0.0.7.7
 */
class Page_fans implements MidrubBaseUserAppsCollectionStreamInterfaces\Stream {
    
    /**
     * Class variables
     *
     * @since 0.0.7.7
     */
    protected $CI, $fb, $app_id, $app_secret;

    /**
     * Initialise the Class
     *
     * @since 0.0.7.7
     */
    public function __construct() {
        
        // Get codeigniter object instance
        $this->CI =& get_instance();
        
        // Get the Facebook App ID
        $this->app_id = get_option('facebook_pages_app_id');
        
        // Get the Facebook App Secret
        $this->app_secret = get_option('facebook_pages_app_secret');
        
        // Load the Vendor dependencies
        require_once FCPATH . 'vendor/autoload.php';
        
    }
    
    /**
     * The public method stream_process_action processes the stream's item action
     * 
     * @param object $stream contains the stream's configuration
     * 
     * @since 0.0.7.7
     * 
     * @return void
     */ 
    public function stream_process_action($stream) {
    }
    
    /**
     * The public method stream_colors_change changes stream's colors
     * 
     * @param integer $stream_id contains the stream's ID
     * @param string $name contains the stream's Name
     * @param string $value contains the Stream's Value
     * 
     * @since 0.0.7.7
     * 
     * @return void
     */ 
    public function stream_colors_change($stream_id, $name, $value) {
        
        // Set allowed names
        $allowed_name = array(
            'header_text_color',
            'item_text_color',
            'links_color',
            'icons_color',
            'background_color',
            'border_color'
        );
        
        // Verify if name is in array
        if ( in_array($name, $allowed_name) && !preg_match('/[^A-Za-z0-9.#\\-$]/', $value) ) {

            if ( $this->CI->stream_tabs_model->update_stream_field($stream_id, $name, $value) ) {
                
                $data = array(
                    'success' => TRUE,
                    'stream_id' => $stream_id,
                    'name' => $name,
                    'value' => $value
                );

                echo json_encode($data);  
                exit();
                
            }

        }
        
        $data = array(
            'success' => FALSE,
            'message' => $this->CI->lang->line('error_occurred')
        );

        echo json_encode($data);  
        
    }
    
    /**
     * The public method template_content contains the template's content
     * 
     * @param object $stream contains the stream's configuration
     * @param boolean $return contains the return option
     * 
     * @since 0.0.7.7
     * 
     * @return array with tempate's content
     */ 
    public function template_content( $stream, $return = FALSE ) {
        
        // Load language
        $this->CI->lang->load( 'stream_countries', $this->CI->config->item('language'), FALSE, TRUE, MIDRUB_BASE_USER_APPS_STREAM );
        
        // Set Countries array
        $countries = array(
            'BI' => $this->CI->lang->line('burundi'),
            'KM' => $this->CI->lang->line('comoros'),
            'DJ' => $this->CI->lang->line('djibouti'),
            'ER' => $this->CI->lang->line('eritrea'),
            'ET' => $this->CI->lang->line('ethiopia'),
            'KE' => $this->CI->lang->line('kenya'),
            'MG' => $this->CI->lang->line('madagascar'),
            'MW' => $this->CI->lang->line('malawi'),
            'MU' => $this->CI->lang->line('mauritius'),
            'YT' => $this->CI->lang->line('mayotte'),
            'MZ' => $this->CI->lang->line('mozambique'),
            'RE' => $this->CI->lang->line('reunion'),
            'RW' => $this->CI->lang->line('rwanda'),
            'SC' => $this->CI->lang->line('seychelles'),
            'UG' => $this->CI->lang->line('uganda'),
            'TZ' => $this->CI->lang->line('tanzania'),
            'ZM' => $this->CI->lang->line('zambia'),
            'AO' => $this->CI->lang->line('angola'),
            'CM' => $this->CI->lang->line('cameroon'),
            'PG' => $this->CI->lang->line('guinea'),
            'DZ' => $this->CI->lang->line('algeria'),
            'EG' => $this->CI->lang->line('egypt'),
            'MA' => $this->CI->lang->line('morocco'),
            'TN' => $this->CI->lang->line('tunisia'),
            'ZA' => $this->CI->lang->line('south_africa'),
            'NG' => $this->CI->lang->line('nigeria'),
            'KZ' => $this->CI->lang->line('kazakhstan'),
            'KG' => $this->CI->lang->line('kyrgyzstan'),
            'TJ' => $this->CI->lang->line('tajikistan'),
            'TM' => $this->CI->lang->line('turkmenistan'),
            'UZ' => $this->CI->lang->line('uzbekistan'),
            'CN' => $this->CI->lang->line('china'),
            'HK' => $this->CI->lang->line('hong_kong'),
            'JP' => $this->CI->lang->line('japan'),
            'BD' => $this->CI->lang->line('bangladesh'),
            'IN' => $this->CI->lang->line('india'),
            'NP' => $this->CI->lang->line('nepal'),
            'PK' => $this->CI->lang->line('pakistan'),
            'ID' => $this->CI->lang->line('indonesia'),
            'MY' => $this->CI->lang->line('malaysia'),
            'PH' => $this->CI->lang->line('philippines'),
            'SG' => $this->CI->lang->line('singapore'),
            'TH' => $this->CI->lang->line('thailand'),
            'AZ' => $this->CI->lang->line('azerbaijan'),
            'CY' => $this->CI->lang->line('cyprus'),
            'GE' => $this->CI->lang->line('georgia'),
            'IL' => $this->CI->lang->line('israel'),
            'JO' => $this->CI->lang->line('jordan'),
            'KW' => $this->CI->lang->line('kuwait'),
            'QA' => $this->CI->lang->line('qatar'),
            'SA' => $this->CI->lang->line('saudi_arabia'),
            'TR' => $this->CI->lang->line('turkey'),
            'AE' => $this->CI->lang->line('united_arab_emirates'),
            'BZ' => $this->CI->lang->line('belize'),
            'CR' => $this->CI->lang->line('costa_rica'),
            'SV' => $this->CI->lang->line('el_salvador'),
            'GT' => $this->CI->lang->line('guatemala'),
            'HN' => $this->CI->lang->line('honduras'),
            'MX' => $this->CI->lang->line('mexico'),
            'NI' => $this->CI->lang->line('nicaragua'),
            'PA' => $this->CI->lang->line('panama'),
            'BY' => $this->CI->lang->line('belarus'),
            'BG' => $this->CI->lang->line('bulgaria'),
            'CZ' => $this->CI->lang->line('czech_republic'),
            'HU' => $this->CI->lang->line('hungary'),
            'PL' => $this->CI->lang->line('poland'),
            'MD' => $this->CI->lang->line('moldova'),
            'RO' => $this->CI->lang->line('romania'),
            'RU' => $this->CI->lang->line('russia'),
            'SK' => $this->CI->lang->line('slovakia'),
            'UA' => $this->CI->lang->line('ukraine'),
            'DK' => $this->CI->lang->line('denmark'),
            'EE' => $this->CI->lang->line('estonia'),
            'FI' => $this->CI->lang->line('finland'),
            'IS' => $this->CI->lang->line('iceland'),
            'GL' => $this->CI->lang->line('greenland'),
            'IE' => $this->CI->lang->line('ireland'),
            'LV' => $this->CI->lang->line('latvia'),
            'LT' => $this->CI->lang->line('lithuania'),
            'NO' => $this->CI->lang->line('norway'),
            'SE' => $this->CI->lang->line('sweden'),
            'UK' => $this->CI->lang->line('united_kingdom'),
            'AL' => $this->CI->lang->line('albania'),
            'HR' => $this->CI->lang->line('croatia'),
            'GR' => $this->CI->lang->line('greece'),
            'IT' => $this->CI->lang->line('italy'),
            'MT' => $this->CI->lang->line('malta'),
            'ME' => $this->CI->lang->line('montenegro'),
            'PT' => $this->CI->lang->line('portugal'),
            'RS' => $this->CI->lang->line('serbia'),
            'SI' => $this->CI->lang->line('slovenia'),
            'ES' => $this->CI->lang->line('spain'),
            'MK' => $this->CI->lang->line('macedonia'),
            'BE' => $this->CI->lang->line('belgium'),
            'FR' => $this->CI->lang->line('france'),
            'DE' => $this->CI->lang->line('germany'),
            'LU' => $this->CI->lang->line('luxembourg'),
            'MC' => $this->CI->lang->line('monaco'),
            'NL' => $this->CI->lang->line('netherlands'),
            'CH' => $this->CI->lang->line('switzerland'),
            'BM' => $this->CI->lang->line('bermuda'),
            'CA' => $this->CI->lang->line('canada'),
            'US' => $this->CI->lang->line('united_states'),
            'AR' => $this->CI->lang->line('argentina'),
            'BO' => $this->CI->lang->line('bolivia'),
            'BR' => $this->CI->lang->line('brazil'),
            'CL' => $this->CI->lang->line('chile'),
            'CO' => $this->CI->lang->line('colombia'),
            'EC' => $this->CI->lang->line('ecuador'),
            'GY' => $this->CI->lang->line('guyana'),
            'PY' => $this->CI->lang->line('paraguay'),
            'UY' => $this->CI->lang->line('uruguay'),
            'VE' => $this->CI->lang->line('venezuela'),
            'AU' => $this->CI->lang->line('australia'),
            'NZ' => $this->CI->lang->line('new_zealand'),
            'NC' => $this->CI->lang->line('new_caledonia'),
            'PG' => $this->CI->lang->line('papua_new_guinea'),
            'GU' => $this->CI->lang->line('guam'),
            'GB' => $this->CI->lang->line('great_britain'),
            'PE' => $this->CI->lang->line('peru'),
            'VN' => $this->CI->lang->line('vietnam'),
            'XK' => $this->CI->lang->line('kosovo')
        );
        
        // Load the Facebook Class
        $this->fb = new \Facebook\Facebook(
            array(
                'app_id' => $this->app_id,
                'app_secret' => $this->app_secret,
                'default_graph_version' => 'v3.0',
                'default_access_token' => '{access-token}',
            )
        );
        
        // Get the template's info
        $template_info = $this->template_info();
        
        $border_bottom_color = '';
        $paragraph_color = '';
        $links_color = '';
        $icons_color = '';
        
        if ( $stream->border_color ) {
            $border_bottom_color = ' style="border-bottom-color: ' . $stream->border_color . '"';
        } 
        
        if ( $stream->item_text_color ) {
            $paragraph_color = ' style="color: ' . $stream->item_text_color . '"';
        }     
        
        if ( $stream->links_color ) {
            $links_color = ' style="color: ' . $stream->links_color . '"';
        }
        
        if ( $stream->icons_color ) {
            $icons_color = ' style="color: ' . $stream->icons_color . '"';
        } 
        
        $network_details = $this->CI->stream_networks_model->get_network_data('facebook_pages', $this->CI->user_id, $stream->network_id);
        
        try {

            $response = $this->fb->get(
                '/' . $network_details[0]->net_id . '/insights/page_fans_country',
                $network_details[0]->secret
            );
            
        } catch (Facebook\Exceptions\FacebookResponseException $e) {

            echo 'Graph returned an error: ' . $e->getMessage();
            exit;
            
        } catch (Facebook\Exceptions\FacebookSDKException $e) {
            
            echo 'Facebook SDK returned an error: ' . $e->getMessage();
            exit;
            
        }

        $graphNode = $response->getGraphEdge();
        
        $all_countries = '';
        
        if ( $graphNode->asArray() ) {
            
            $array = $graphNode->asArray();
            
            $content = array();
            
            $count = 0;
            
            foreach ( $array[0]['values'][0]['value'] as $key => $value ) {
                
                $country = $key;

                if ( isset($countries[$key]) ) {
                    $country = $countries[$key];
                }

                $content[] = array(
                    'name' => $country,
                    'value' => $value,
                    'background_color' => 'rgba(30, 99, 132, 0.6)',
                    'border_color' => 'rgba(30, 99, 132, 1)'
                );
                
                $count++;
                
            }
            
            $all_countries = '<li class="row"' . $border_bottom_color . '>'
                            . '<div class="col-xl-12">'
                                . '<div>'
                                    . '<canvas id="stream-template-graph-' . $stream->stream_id . '" class="stream-template-graph" data-content="' . base64_encode(json_encode($content)) . '" style="width:100%" height="' . ( $count * 40 ) . '"></canvas>'
                                . '</div>'
                            . '</div>'
                        . '</li>';
            
        } else {
            
            $all_countries .= '<li class="row"' . $border_bottom_color . '>'
                            . '<div class="col-xl-12">'
                                . '<div>'
                                    . '<p>' . $this->CI->lang->line('no_results_found') . '</p>'
                                . '</div>'
                            . '</div>'
                        . '</li>';
            
        }
        
        $active = '';
        $active_check = 0;
        
        if ( $active_check ) {
        
            $active = ' stream-mark-seen-item-active';
            
        } else if ( $stream->new_event ) {
            
            $active = ' stream-mark-seen-item-active';
            
        }
        
        $stream_data = array (
            'header' => '<i class="icon-social-facebook"></i>'
                        . $template_info['displayed_name']
                        . '<div class="float-right">'
                            . '<a href="#" class="stream-mark-seen-item' . $active . '">'
                                . '<i class="icon-bell"></i>'
                            . '</a>'            
                            . '<a href="#stream-settings" class="stream-open-settings" data-toggle="modal">'
                                . '<i class="icon-settings"></i>'
                            . '</a>'                                            
                        . '</div>',
            'content' => '<ul>'
                            . $all_countries
                        . '</ul>',
            'footer' => ''
            
        );
        
        $data = array(
            'success' => TRUE,
            'stream' => $stream_data,
            'message' => $this->CI->lang->line('selected_stream_connected'),
            'stream_id' => $stream->stream_id,
            'background_color' => $stream->background_color
        );
        
        if ( $return ) {
            
            return $data;
            
        } else {

            echo json_encode($data);  
        
        }
        
    }
    
    /**
     * The public method template_content_single provides the stream's item content
     * 
     * @param integer $stream contains the stream's configuration
     * 
     * @since 0.0.7.7
     * 
     * @return array with stream's item content
     */ 
    public function template_content_single( $stream ) {
        
        // Load the Facebook Class
        $this->fb = new \Facebook\Facebook(
            array(
                'app_id' => $this->app_id,
                'app_secret' => $this->app_secret,
                'default_graph_version' => 'v3.0',
                'default_access_token' => '{access-token}',
            )
        );
        
        // Check if data was submitted
        if ($this->CI->input->post()) {
            
            // Add form validation
            $this->CI->form_validation->set_rules('type', 'Type', 'trim|required');
            $this->CI->form_validation->set_rules('id', 'ID', 'trim|required');
            
            // Get data
            $type = $this->CI->input->post('type');
            $id = $this->CI->input->post('id');
            
            
            if ( $this->CI->form_validation->run() !== false ) {
                
                $network_details = $this->CI->stream_networks_model->get_network_data('facebook_pages', $this->CI->user_id, $stream[0]->network_id);
                
                if ( $network_details ) {
                    
                    switch ( $type ) {
                        
                        case 'comment': 
                        
                             $post_data = $this->fb->get(
                                '/' . $id . '?fields=from,message',
                                $network_details[0]->secret
                            );

                            $graphPost = $post_data->getGraphNode();

                            if ( $graphPost->asArray() ) {
                                
                                $post = $graphPost->asArray();
                                
                                $post_data = '<div class="col-xl-12 stream-simgle-item-template">'
                                                . '<img src="https://graph.facebook.com/' . $post['from']['id'] . '/picture?type=square&access_token=' . $network_details[0]->secret . '" alt="User Avatar" class="img-circle">'
                                                . '<div class="stream-post">'
                                                    . '<strong>'
                                                        . '<a href="https://www.facebook.com/' . $post['from']['id'] . '" target="_blank">'
                                                            . $post['from']['name']
                                                        . '</a>'
                                                    . '</strong>'
                                                    . '<p>'
                                                        . $post['message']
                                                    . '</p>'
                                                . '</div>'
                                            . '</div>';

                                $data = array(
                                    'success' => TRUE,
                                    'content' => $post_data,
                                    'form' => form_open('user/app/stream', ['class' => 'stream-send-react', 'data-csrf' => $this->CI->security->get_csrf_token_name()])
                                                . '<div class="form-group">'
                                                    . '<textarea name="comment" class="form-control" rows="3"></textarea>'
                                                . '</div>'
                                                . '<input type="hidden" name="id" value="' .  $post['id'] . '">'
                                                . '<input type="hidden" name="stream_id" value="' .  $stream[0]->stream_id . '">'
                                                . '<input type="hidden" name="type" value="comment">'                                    
                                                . '<button type="submit" class="btn btn-primary">' . $this->CI->lang->line('submit') . '</button>'
                                            . form_close(),
                                    'menu_text' => $this->CI->lang->line('post')
                                );

                                echo json_encode($data);  
                                exit();
                                
                            }                    
                            
                            break;
                            
                        case 'reply': 
                        
                            $comment = $this->fb->get(
                                '/' . $id . '?fields=message,attachment,from',
                                $network_details[0]->secret
                            );

                            $graphComment = $comment->getGraphNode();

                            if ( $graphComment->asArray() ) {
                                
                                $com = $graphComment->asArray();
                                
                                $media = '';

                                if ( isset($com['attachment']['media']['image']['src']) ) {

                                    $media = '<p><img src="' . $com['attachment']['media']['image']['src'] . '"></p>';

                                }
                                
                                $com_data = '<div class="col-xl-12 stream-simgle-item-template">'
                                            . '<img src="https://graph.facebook.com/' . $com['from']['id'] . '/picture?type=square&access_token=' . $network_details[0]->secret . '" alt="User Avatar" class="img-circle">'
                                            . '<div class="stream-post">'
                                                . '<strong>'
                                                    . '<a href="https://www.facebook.com/' . $com['from']['id'] . '" target="_blank">'
                                                        . $com['from']['name']
                                                    . '</a>'
                                                . '</strong>'
                                                . '<p>'
                                                    . $com['message']
                                                . '</p>'
                                                . $media
                                            . '</div>'
                                        . '</div>';

                                $data = array(
                                    'success' => TRUE,
                                    'content' => $com_data,
                                    'form' => form_open('user/app/stream', ['class' => 'stream-send-react', 'data-csrf' => $this->CI->security->get_csrf_token_name()])
                                                . '<div class="form-group">'
                                                    . '<textarea name="comment" class="form-control" rows="3"></textarea>'
                                                . '</div>'
                                                . '<input type="hidden" name="id" value="' .  $com['id'] . '">'
                                                . '<input type="hidden" name="stream_id" value="' .  $stream[0]->stream_id . '">'
                                                . '<input type="hidden" name="type" value="reply">'                                    
                                                . '<button type="submit" class="btn btn-primary">' . $this->CI->lang->line('submit') . '</button>'
                                            . form_close(),
                                    'menu_text' => $this->CI->lang->line('comment')
                                );

                                echo json_encode($data);  
                                exit();
                                
                            }                    
                            
                            break;
                            
                    }
                    

                    
                }
                
            }
            
        }
        
        $data = array(
            'success' => FALSE,
            'message' => $this->CI->lang->line('error_occurred')
        );

        echo json_encode($data); 
        
    }
    
    /**
     * The public method stream_update_setup saves stream's setup
     * 
     * @since 0.0.7.7
     * 
     * @return void
     */ 
    public function stream_update_setup() {
    }
    
    /**
     * The public method stream_get_setup gets the stream's setup
     * 
     * @param object $stream contains the stream's configuration
     * 
     * @since 0.0.7.7
     * 
     * @return void
     */ 
    public function stream_get_setup( $stream ) {
        
        $network_details = $this->CI->stream_networks_model->get_network_data('facebook_pages', $this->CI->user_id, $stream[0]->network_id);
        
        $setup_data = '';
        
        if ( $network_details ) {
            
            $icon = '';

            $get_icon = (new MidrubBaseUserAppsCollectionStreamHelpers\Accounts)->get_network_info( 'facebook_pages' );

            if ( $get_icon ) {

                $icon = $get_icon['icon'];

            }
            
            $setup_data = '<div class="row">'
                            . '<div class="col-xl-12">'
                                . '<div class="row clean connected-account">'
                                    . '<div class="col-xl-8 col-8">'
                                        . '<p>'
                                            . '<i class="icon-user-following"></i> ' . $this->CI->lang->line('connected_page')
                                        . '</p>'
                                    . '</div>'
                                    . '<div class="col-xl-4 col-4 text-right">'
                                        . $icon . $network_details[0]->user_name
                                    . '</div>'
                                . '</div>'                    
                            . '</div>'    
                        . '</div>';
            
        }
        
        $data = array(
            'success' => TRUE,
            'setup_data' => $setup_data,
            'alert_sound' => $stream[0]->alert_sound,
            'tab_id' => $stream[0]->tab_id,
            'stream' => $stream
        );
        
        if ( !$stream[0]->alert_sound ) {
            $data['message'] = $this->CI->lang->line('select_a_sound');
        } else {
            $data['message'] = '';
        }

        echo json_encode($data); 
        
    }
    
    /**
     * The public method stream_delete_setup deletes stream's setup
     * 
     * @since 0.0.7.7
     * 
     * @return void
     */ 
    public function stream_delete_setup() {
        
    }
    
    /**
     * The public method template_connect contains the template's connection settings
     * 
     * @since 0.0.7.7
     * 
     * @return array with settings
     */ 
    public function template_connect() {
        
        // Load language
        $this->CI->lang->load( 'stream_instructions', $this->CI->config->item('language'), FALSE, TRUE, MIDRUB_BASE_USER_APPS_STREAM );
        
        // Get Active Accounts
        $active_accounts = (new MidrubBaseUserAppsCollectionStreamHelpers\Accounts)->get_active_accounts($this->CI->stream_networks_model->get_accounts_by_network( $this->CI->user_id, 'facebook_pages', 1 ), 1);

        // Get Expired Accounts
        $expired_accounts = (new MidrubBaseUserAppsCollectionStreamHelpers\Accounts)->get_active_accounts($this->CI->stream_networks_model->get_accounts_by_network( $this->CI->user_id, 'facebook_pages', 0 ), 0);        
        
        $icon = '';
        
        $get_icon = (new MidrubBaseUserAppsCollectionStreamHelpers\Accounts)->get_network_info( 'facebook_pages' );
        
        if ( $get_icon ) {
            
            $icon = $get_icon['icon'];
            
        }
        
        $connect_button_text = (isset($get_icon['custom_connect']))?$get_icon['custom_connect']:$this->CI->lang->line('new_account');
        
        return array (
            'active_accounts' => $active_accounts,
            'expired_accounts' => $expired_accounts,
            'network' => 'facebook_pages',
            'instructions' => $this->CI->lang->line('associated_social_account'),
            'new_account' => $icon . $connect_button_text,
            'placeholder' => $this->CI->lang->line('search_pages'),
            'type' => 1
        );
        
    }   
    
    /**
     * The public method stream_post processes the post actions
     * 
     * @param object $stream contains the stream's configuration
     * 
     * @since 0.0.7.7
     * 
     * @return void
     */ 
    public function stream_post($stream) {
        
        // Load the Facebook Class
        $this->fb = new \Facebook\Facebook(
            array(
                'app_id' => $this->app_id,
                'app_secret' => $this->app_secret,
                'default_graph_version' => 'v3.0',
                'default_access_token' => '{access-token}',
            )
        );
        
        // Check if data was submitted
        if ($this->CI->input->post()) {
            
            // Add form validation
            $this->CI->form_validation->set_rules('type', 'Type', 'trim|required');
            $this->CI->form_validation->set_rules('id', 'ID', 'trim|required');
            $this->CI->form_validation->set_rules('comment', 'Comment', 'trim|required');
            
            // Get data
            $type = $this->CI->input->post('type');
            $id = $this->CI->input->post('id');
            $comment = $this->CI->input->post('comment');
            
            if ( $this->CI->form_validation->run() !== false ) {
                
                $network_details = $this->CI->stream_networks_model->get_network_data('facebook_pages', $this->CI->user_id, $stream[0]->network_id);
                
                if ( $network_details ) {
                
                    switch ( $type ) {

                        case 'comment':

                            try {

                                $response = $this->fb->post(
                                    '/' . $id . '/comments',
                                    array('message' => $comment),
                                    $network_details[0]->secret
                                );

                            } catch (Facebook\Exceptions\FacebookResponseException $e) {

                                echo 'Graph returned an error: ' . $e->getMessage();
                                exit;

                            } catch (Facebook\Exceptions\FacebookSDKException $e) {

                                echo 'Facebook SDK returned an error: ' . $e->getMessage();
                                exit;

                            }
                            
                            $graphNode = $response->getGraphNode();

                            if ( $graphNode->asArray() ) {
                                
                                $status = $graphNode->asArray();
                                
                                if ( isset($status['id']) ) {
                                    
                                    $data = array(
                                        'success' => TRUE,
                                        'tab_id' => $stream[0]->tab_id,
                                        'message' => $this->CI->lang->line('comment_was_published')
                                    );

                                    echo json_encode($data); 
                                    
                                } else {
                                    
                                    $data = array(
                                        'success' => FALSE,
                                        'message' => $this->CI->lang->line('comment_was_not_published')
                                    );

                                    echo json_encode($data);                                     
                                    
                                }
                                
                                exit();
                                
                            }

                            break;
                            
                        case 'reply':

                            try {

                                $response = $this->fb->post(
                                    '/' . $id . '/comments',
                                    array('message' => $comment),
                                    $network_details[0]->secret
                                );

                            } catch (Facebook\Exceptions\FacebookResponseException $e) {

                                echo 'Graph returned an error: ' . $e->getMessage();
                                exit;

                            } catch (Facebook\Exceptions\FacebookSDKException $e) {

                                echo 'Facebook SDK returned an error: ' . $e->getMessage();
                                exit;

                            }
                            
                            $graphNode = $response->getGraphNode();

                            if ( $graphNode->asArray() ) {
                                
                                $status = $graphNode->asArray();
                                
                                if ( isset($status['id']) ) {
                                    
                                    $data = array(
                                        'success' => TRUE,
                                        'tab_id' => $stream[0]->tab_id,
                                        'message' => $this->CI->lang->line('comment_was_published')
                                    );

                                    echo json_encode($data); 
                                    
                                } else {
                                    
                                    $data = array(
                                        'success' => FALSE,
                                        'message' => $this->CI->lang->line('comment_was_not_published')
                                    );

                                    echo json_encode($data);                                     
                                    
                                }
                                
                                exit();
                                
                            }

                            break;

                    }
                
                }
                
            }
            
        }
        
        $data = array(
            'success' => FALSE,
            'message' => $this->CI->lang->line('error_occurred')
        );

        echo json_encode($data);
        
    }
    
    /**
     * The public method stream_delete processes the deletion actions
     * 
     * @param object $stream contains the stream's configuration
     * 
     * @since 0.0.7.7
     * 
     * @return void
     */ 
    public function stream_delete($stream) {
        
    }
    
    /**
     * The public method stream_extra processes the unexpected actions
     * 
     * @param string $type contains the request's type
     * @param array $data contains the request's data
     * 
     * @since 0.0.7.7
     * 
     * @return void
     */ 
    public function stream_extra($type, $data) {
        
    }
    
    /**
     * The public method template_info contains the template's information
     * 
     * @since 0.0.7.7
     * 
     * @return array with tempate's information
     */ 
    public function template_info() {
        
        return array (
            'displayed_name' => $this->CI->lang->line('page_fans_country'),
            'icon' => '<i class="icon-social-facebook"></i>',
            'description' => $this->CI->lang->line('page_fans_country_description'),
            'parent' => 'facebook_pages'
        );
        
    }
    
}

/* End of file Page_fans.php */
