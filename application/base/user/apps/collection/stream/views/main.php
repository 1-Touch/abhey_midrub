<section class="section stream-page" data-mobile-installed="<?php echo (get_user_option('mobile_installed'))?'1':'0'; ?>">
    <div class="row">
        <div class="col-xl-12 stream-tabs">
            <ul class="nav nav-tabs stream-tabs-list" id="myTab" role="tablist">
                <?php
                if ( $tabs ) {
                    
                    $e = 0;
                    
                    foreach ( $tabs['tabs'] as $tab ) {
                        
                        $active = '';
                        $is_true = 'false';
                        
                        if ( $e < 1 ) {
                            $active = ' active';
                            $is_true = 'true';
                            $e = 1;
                        }
                        
                        echo '<li class="nav-item">'
                                . '<a class="nav-link' . $active . '" id="nav-' . $tab->tab_id . '" data-toggle="tab" href="#tab-' . $tab->tab_id . '" role="tab" aria-controls="tab-' . $tab->tab_id . '" aria-selected="' . $is_true . '">'
                                    . '<i class="' . $tab->tab_icon . '"></i> ' . $tab->tab_name
                                    . '<small></small>'
                                . '</a>'
                            . '</li>';
                        
                    }
                    
                }
                ?>
            </ul>
            <a href="#create-new-stream-tab" data-toggle="modal">
                <i class="icon-plus"></i>
            </a>
        </div>
    </div>
    <div class="row">
        <div class="col-xl-12 stream-content">
            <div class="tab-content" id="nav-tabContent-streams">
                <?php
                if ( $tabs ) {
                    
                    $p = 0;

                    foreach ( $tabs['tabs'] as $tab ) {
                        
                        $is_active = '';
                        
                        $streams = '<div class="col-xl-3">'
                                        . '<div class="col-xl-12 stream-single stream-cover">'
                                        . '</div>'
                                    . '</div>'
                                    . '<div class="col-xl-3">'
                                        . '<div class="col-xl-12 stream-single stream-cover">'
                                        . '</div>'
                                    . '</div>'
                                    . '<div class="col-xl-3">'
                                        . '<div class="col-xl-12 stream-single stream-cover">'
                                        . '</div>'
                                    . '</div> '
                                    . '<div class="col-xl-3">'
                                        . '<div class="col-xl-12 stream-single stream-cover">'
                                        . '</div>'
                                    . '</div>';                        
                        
                        if ( $p < 1 ) {
                            $is_active = ' show active';
                            $p = 1;
                            $streams = $tabs['tab_streams'];
                        }
                        
                        if ( file_exists(MIDRUB_BASE_USER_APPS_STREAM . 'cache/backup-' . $tab->tab_id . '.html') ) {
                            $streams = file_get_contents(MIDRUB_BASE_USER_APPS_STREAM . 'cache/backup-' . $tab->tab_id . '.html');
                        }
                        
                        $refresh = ($tab->refresh)?$tab->refresh:0;
                        
                        echo '<div class="tab-pane fade' . $is_active . '" data-tab="' . $tab->tab_id . '" data-refresh="' . $refresh . '" id="tab-' . $tab->tab_id . '" role="tabpanel" aria-labelledby="tab-' . $tab->tab_id . '">'
                                    . '<div class="panel panel-default">'
                                        . '<div class="panel-heading">'
                                            . '<a href="#create-new-stream" data-toggle="modal">'
                                                . '<i class="icon-doc"></i> ' . $tabs['new_stream']
                                            . '</a>'
                                            . '<a href="#stream-tab-settings" data-toggle="modal" class="tab-stream-load">'
                                                . '<i class="icon-settings"></i> ' . $tabs['settings']
                                            . '</a>'
                                            . '<a href="#" class="stream-tab-refresh pull-right">'
                                                . '<i class="fas fa-sync-alt"></i>'
                                            . '</a>'
                                        . '</div>'
                                        . '<div class="panel-body stream-all-tab-streams">'
                                            . '<div class="row">'
                                                . $streams
                                            . '</div>'
                                        . '</div>'
                                    . '</div>'
                                . '</div>';
                        
                    }
                    
                }
                ?>
            </div>
        </div>
    </div>
</section>

<!-- Modal -->
<div class="modal fade" id="create-new-stream-tab" tabindex="-1" role="dialog" aria-labelledby="create-new-stream-tab" aria-hidden="true">
    <?php echo form_open('user/app/stream', array('class' => 'stream-create-tab', 'data-csrf' => $this->security->get_csrf_token_name())); ?>
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><?php echo $this->lang->line('new_tab'); ?></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <div class="dropdown show">
                            <a class="btn btn-secondary stream-selected-tab-icon btn-md dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="icon-flag"></i>
                            </a>

                            <div class="dropdown-menu dropdown-menu-action" aria-labelledby="dropdownMenuLink">
                                <table class="table stream-select-tab-icon clean table-striped">
                                    <tbody>
                                        <tr>
                                            <td>
                                                <a href="icon-social-youtube">
                                                   <i class="icon-social-youtube"></i>
                                                </a>
                                            </td>
                                            <td>
                                                <a href="icon-social-twitter">
                                                   <i class="icon-social-twitter"></i>
                                                </a>
                                            </td>
                                            <td>
                                                <a href="icon-social-facebook">
                                                   <i class="icon-social-facebook"></i>
                                                </a>
                                            </td>
                                            <td>
                                                <a href="icon-social-dropbox">
                                                   <i class="icon-social-dropbox"></i>
                                                </a>
                                            </td>
                                            <td>
                                                <a href="icon-social-dribbble">
                                                   <i class="icon-social-dribbble"></i>
                                                </a>
                                            </td>
                                            <td>
                                                <a href="icon-shield">
                                                   <i class="icon-shield"></i>
                                                </a>
                                            </td>
                                            <td>
                                                <a href="icon-screen-tablet">
                                                   <i class="icon-screen-tablet"></i>
                                                </a>
                                            </td>                                    
                                        </tr>
                                        <tr>
                                            <td>
                                                <a href="icon-screen-smartphone">
                                                   <i class="icon-screen-smartphone"></i>
                                                </a>
                                            </td>
                                            <td>
                                                <a href="icon-screen-desktop">
                                                   <i class="icon-screen-desktop"></i>
                                                </a>
                                            </td>
                                            <td>
                                                <a href="icon-magnet">
                                                   <i class="icon-magnet"></i>
                                                </a>
                                            </td>
                                            <td>
                                                <a href="icon-ghost">
                                                   <i class="icon-ghost"></i>
                                                </a>
                                            </td>
                                            <td>
                                                <a href="icon-briefcase">
                                                   <i class="icon-briefcase"></i>
                                                </a>
                                            </td>
                                            <td>
                                                <a href="icon-bubbles">
                                                   <i class="icon-bubbles"></i>
                                                </a>
                                            </td>
                                            <td>
                                                <a href="icon-basket-loaded">
                                                   <i class="icon-basket-loaded"></i>
                                                </a>
                                            </td>                                    
                                        </tr>
                                        <tr>
                                            <td>
                                                <a href="icon-bell">
                                                   <i class="icon-bell"></i>
                                                </a>
                                            </td>
                                            <td>
                                                <a href="icon-credit-card">
                                                   <i class="icon-credit-card"></i>
                                                </a>
                                            </td>
                                            <td>
                                                <a href="icon-envelope-open">
                                                   <i class="icon-envelope-open"></i>
                                                </a>
                                            </td>
                                            <td>
                                                <a href="icon-fire">
                                                   <i class="icon-fire"></i>
                                                </a>
                                            </td>
                                            <td>
                                                <a href="icon-compass">
                                                   <i class="icon-compass"></i>
                                                </a>
                                            </td>
                                            <td>
                                                <a href="icon-cup">
                                                   <i class="icon-cup"></i>
                                                </a>
                                            </td>
                                            <td>
                                                <a href="icon-diamond">
                                                   <i class="icon-diamond"></i>
                                                </a>
                                            </td>                                    
                                        </tr>
                                        <tr>
                                            <td>
                                                <a href="icon-directions">
                                                   <i class="icon-directions"></i>
                                                </a>
                                            </td>
                                            <td>
                                                <a href="icon-drawer">
                                                   <i class="icon-drawer"></i>
                                                </a>
                                            </td>
                                            <td>
                                                <a href="icon-earphones">
                                                   <i class="icon-earphones"></i>
                                                </a>
                                            </td>
                                            <td>
                                                <a href="icon-feed">
                                                   <i class="icon-feed"></i>
                                                </a>
                                            </td>
                                            <td>
                                                <a href="icon-film">
                                                   <i class="icon-film"></i>
                                                </a>
                                            </td>
                                            <td>
                                                <a href="icon-folder-alt">
                                                   <i class="icon-folder-alt"></i>
                                                </a>
                                            </td>
                                            <td>
                                                <a href="icon-globe-alt">
                                                   <i class="icon-globe-alt"></i>
                                                </a>
                                            </td>                                    
                                        </tr>
                                        <tr>
                                            <td>
                                                <a href="icon-handbag">
                                                   <i class="icon-handbag"></i>
                                                </a>
                                            </td>
                                            <td>
                                                <a href="icon-layers">
                                                   <i class="icon-layers"></i>
                                                </a>
                                            </td>
                                            <td>
                                                <a href="icon-map">
                                                   <i class="icon-map"></i>
                                                </a>
                                            </td>
                                            <td>
                                                <a href="icon-picture">
                                                   <i class="icon-picture"></i>
                                                </a>
                                            </td>
                                            <td>
                                                <a href="icon-pin">
                                                   <i class="icon-pin"></i>
                                                </a>
                                            </td>
                                            <td>
                                                <a href="icon-playlist">
                                                   <i class="icon-playlist"></i>
                                                </a>
                                            </td>
                                            <td>
                                                <a href="icon-present">
                                                   <i class="icon-present"></i>
                                                </a>
                                            </td>                                    
                                        </tr>
                                        <tr>
                                            <td>
                                                <a href="icon-wallet">
                                                   <i class="icon-wallet"></i>
                                                </a>
                                            </td>
                                            <td>
                                                <a href="icon-camera">
                                                   <i class="icon-camera"></i>
                                                </a>
                                            </td>
                                            <td>
                                                <a href="icon-bulb">
                                                   <i class="icon-bulb"></i>
                                                </a>
                                            </td>
                                            <td>
                                                <a href="icon-calendar">
                                                   <i class="icon-calendar"></i>
                                                </a>
                                            </td>
                                            <td>
                                                <a href="icon-dislike">
                                                   <i class="icon-dislike"></i>
                                                </a>
                                            </td>
                                            <td>
                                                <a href="icon-equalizer">
                                                   <i class="icon-equalizer"></i>
                                                </a>
                                            </td>
                                            <td>
                                                <a href="icon-grid">
                                                   <i class="icon-grid"></i>
                                                </a>
                                            </td>
                                        <tr>
                                            <td>
                                                <a href="icon-like">
                                                   <i class="icon-like"></i>
                                                </a>
                                            </td>
                                            <td>
                                                <a href="icon-home">
                                                   <i class="icon-home"></i>
                                                </a>
                                            </td>
                                            <td>
                                                <a href="icon-heart">
                                                   <i class="icon-heart"></i>
                                                </a>
                                            </td>
                                            <td>
                                                <a href="icon-info">
                                                   <i class="icon-info"></i>
                                                </a>
                                            </td>
                                            <td>
                                                <a href="icon-question">
                                                   <i class="icon-question"></i>
                                                </a>
                                            </td>
                                            <td>
                                                <a href="icon-support">
                                                   <i class="icon-support"></i>
                                                </a>
                                            </td>
                                            <td>
                                                <a href="icon-umbrella">
                                                   <i class="icon-umbrella"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <input type="text" class="form-control stream-tab-name" id="stream-tab-name" maxlength="50" placeholder="<?php echo $this->lang->line('enter_tab_name'); ?>" name="stream-tab-name" autocomplete="off" required="required">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" data-type="main" class="btn btn-success pull-right"><?php echo $this->lang->line('create_tab'); ?></button>
                </div>
            </div>
        </div>
    <?php echo form_close(); ?>
</div>

<!-- Modal -->
<div class="modal fade" id="create-new-stream" tabindex="-1" role="dialog" aria-labelledby="create-new-stream-tab" aria-hidden="true">
    <?php echo form_open('user/app/stream', array('class' => 'stream-create-stream', 'data-csrf' => $this->security->get_csrf_token_name())); ?>
        <div class="modal-dialog file-upload-box modal-xl modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <nav>
                        <div class="nav nav-tabs" id="nav-tab" role="tablist">
                            <a class="nav-item nav-link active show" id="nav-all-streams-tab" data-toggle="tab" href="#nav-all-streams" role="tab" aria-controls="nav-all-streams" aria-selected="true">
                                <?php echo $this->lang->line('stream'); ?>
                            </a>
                        </div>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </nav>
                </div>
                <div class="modal-body">
                    <div class="tab-content" id="nav-tabContent">
                        <div class="tab-pane fade active show" id="nav-all-streams" role="tabpanel" aria-labelledby="nav-all-streams">
                            <div class="row">
                                <div class="col-xl-3">
                                    <ul class="nav nav-tabs tabs-left stream-available-stream-categories">
                                        <?php
                                        if ( $categories ) {

                                            foreach ( $categories as $category ) {

                                                $selected = '';

                                                if ( $category['first'] ) {

                                                    $selected = ' class="network-selected"';

                                                }

                                                ?>
                                                <li<?php echo $selected; ?>>
                                                    <a href="#" data-category="<?php echo $category['category']; ?>">
                                                        <div>
                                                            <?php
                                                            echo ucwords(str_replace('_', ' ', $category['category_name']));
                                                            ?>
                                                        </div>
                                                        <span style="background-color:<?php echo $category['network_info']['color']; ?>;"><?php echo $category['count']; ?> <?php echo $this->lang->line('templates'); ?></span>
                                                    </a>
                                                </li>
                                                <?php

                                            }

                                        }
                                        ?>
                                    </ul>
                                </div>
                                <div class="col-xl-9">
                                    <ul class="stream-available-streams-by-category">
                                        <?php
                                        if ( $categories ) {

                                            foreach ( $categories as $category ) {

                                                foreach ( $category['templates'] as $template ) {

                                                    ?>
                                                    <li>
                                                        <div class="row">
                                                            <div class="col-xl-1">
                                                                <button type="button" class="btn btn-default" style="background-color:<?php echo $category['network_info']['color']; ?>;"><?php echo $category['network_info']['icon']; ?></button>
                                                            </div>
                                                            <div class="col-xl-8">
                                                                <h3><?php echo $template['displayed_name']; ?></h3>
                                                                <p><?php echo $template['description']; ?></p>
                                                            </div>
                                                            <div class="col-xl-3 text-right">
                                                                <button type="button" class="btn btn-success" id="nav-connect-stream-tab" data-network="<?php echo strtolower($template['template_name']); ?>">
                                                                    <i class="fas fa-plug"></i> <?php echo $this->lang->line('connect'); ?>
                                                                </button>
                                                            </div>
                                                        </div>
                                                    </li>
                                                    <?php

                                                }

                                                break;
                                            }

                                        }
                                        ?>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane fade" id="nav-connect-stream" role="tabpanel" aria-labelledby="nav-connect-stream">
                            
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <?php echo form_close(); ?>
</div>

<!-- Modal -->
<div class="modal fade" id="stream-settings" tabindex="-1" role="dialog" aria-labelledby="stream-settings-tab" aria-hidden="true">
    <div class="modal-dialog file-upload-box modal-xl modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <nav>
                    <div class="nav nav-tabs" id="nav-tab" role="tablist">
                        <a class="nav-item nav-link active show" id="nav-stream-settings-tab" data-toggle="tab" href="#nav-stream-settings" role="tab" aria-controls="nav-stream-settings" aria-selected="true">
                            <?php echo $this->lang->line('settings'); ?>
                        </a>
                        <a class="nav-item nav-link" id="nav-stream-setup-tab" data-toggle="tab" href="#nav-stream-setup" role="tab" aria-controls="nav-all-streams" aria-selected="false">
                            <?php echo $this->lang->line('setup'); ?>
                        </a>
                        <a class="nav-item nav-link" id="nav-stream-faq-tab" data-toggle="tab" href="#nav-stream-faq" role="tab" aria-controls="nav-all-streams" aria-selected="false">
                            <?php echo $this->lang->line('faq'); ?>
                        </a>
                    </div>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </nav>
            </div>
            <div class="modal-body">
                <div class="tab-content" id="nav-stream-settings-tabContent">
                    <div class="tab-pane fade active show" id="nav-stream-settings" role="tabpanel" aria-labelledby="nav-stream-settings-tab">
                        <div class="stream-settings-sounds">
                            <?php
                            // Get all available sounds
                            foreach ( glob(FCPATH . '/assets/sounds/*.mp3') as $filename ) {

                                $audioFile = trim(basename($filename, '.mp3').PHP_EOL);
                                
                                $ogg = '';
                                
                                if ( file_exists(FCPATH . '/assets/sounds/' . $audioFile . '.ogg') ) {
                                    
                                    $ogg .= '<source src="' . base_url('/assets/sounds/' . $audioFile . '.ogg') . '" type="audio/ogg">';
                                    
                                }

                                echo '<div class="row">'
                                        . '<div class="col-8">'
                                            . '<span>'
                                                . ucwords(str_replace('-', ' ', $audioFile))
                                            . '</span>'
                                            . '<audio controls controlsList="nodownload">'
                                                . $ogg
                                                . '<source src="' . base_url('/assets/sounds/' . $audioFile . '.mp3') . '" type="audio/mpeg">'
                                                . 'Your browser does not support the audio element.'
                                            . '</audio>'
                                        . '</div>'
                                        . '<div class="col-4">'
                                            . '<div class="checkbox-option pull-right">'
                                                . '<input id="' . $audioFile . '" name="' . $audioFile . '" class="stream-select-sound" type="checkbox">'
                                                . '<label for="' . $audioFile . '"></label>'
                                            . '</div>'
                                        . '</div>'                           
                                    . '</div>';

                            }
                            ?>
                        </div>
                        <ul class="settings-list-options">
                            <li>
                                <div class="row">
                                    <div class="col-xl-8 col-md-7 col-7">
                                        <h4><?php echo $this->lang->line('sound_alerts'); ?></h4>
                                        <p><?php echo $this->lang->line('select_sound_played'); ?></p>
                                    </div>
                                    <div class="col-xl-4 col-md-5 col-5 text-right">
                                        <a href="#" class="select-a-sound"><?php echo $this->lang->line('select_a_sound'); ?></a>
                                    </div>
                                </div>
                            </li>
                            <li>
                                <div class="row">
                                    <div class="col-xl-8 col-md-7 col-7">
                                        <h4><?php echo $this->lang->line('header_text_color'); ?></h4>
                                        <p><?php echo $this->lang->line('select_the_header_text_color'); ?></p>
                                    </div>
                                    <div class="col-xl-4 col-md-5 col-5 text-right">
                                        <input type="text" class="stream-settings-input stream-header-text-color" id="header_text_color" value="#1e1c21" data-color="#1e1c21">
                                    </div>
                                </div>
                            </li>                            
                            <li>
                                <div class="row">
                                    <div class="col-xl-8 col-md-7 col-7">
                                        <h4><?php echo $this->lang->line('item_text_color'); ?></h4>
                                        <p><?php echo $this->lang->line('select_the_item_text_color'); ?></p>
                                    </div>
                                    <div class="col-xl-4 col-md-5 col-5 text-right">
                                        <input type="text" class="stream-settings-input stream-item-text-color" id="item_text_color" value="#6c757d" data-color="#6c757d">
                                    </div>
                                </div>
                            </li>
                            <li>
                                <div class="row">
                                    <div class="col-xl-8 col-md-7 col-7">
                                        <h4><?php echo $this->lang->line('links_color'); ?></h4>
                                        <p><?php echo $this->lang->line('select_the_links_color'); ?></p>
                                    </div>
                                    <div class="col-xl-4 col-md-5 col-5 text-right">
                                        <input type="text" class="stream-settings-input stream-links-color" id="links_color" value="#346cb0" data-color="#346cb0">
                                    </div>
                                </div>
                            </li>
                            <li>
                                <div class="row">
                                    <div class="col-xl-8 col-md-7 col-7">
                                        <h4><?php echo $this->lang->line('icons_color'); ?></h4>
                                        <p><?php echo $this->lang->line('select_small_text_color'); ?></p>
                                    </div>
                                    <div class="col-xl-4 col-md-5 col-5 text-right">
                                        <input type="text" class="stream-settings-input stream-icons-color" id="icons_color" value="#657786" data-color="#657786">
                                    </div>
                                </div>
                            </li>
                            <li>
                                <div class="row">
                                    <div class="col-xl-8 col-md-7 col-7">
                                        <h4><?php echo $this->lang->line('background_color'); ?></h4>
                                        <p><?php echo $this->lang->line('select_the_background_color'); ?></p>
                                    </div>
                                    <div class="col-xl-4 col-md-5 col-5 text-right">
                                        <input type="text" class="stream-settings-input stream-background-color" id="background_color" value="#FFFFFF" data-color="#FFFFFF">
                                    </div>
                                </div>
                            </li>
                            <li>
                                <div class="row">
                                    <div class="col-xl-8 col-md-7 col-7">
                                        <h4><?php echo $this->lang->line('border_color'); ?></h4>
                                        <p><?php echo $this->lang->line('select_the_border_color'); ?></p>
                                    </div>
                                    <div class="col-xl-4 col-md-5 col-5 text-right">
                                        <input type="text" class="stream-settings-input stream-background-color" id="border_color" value="#416aa6" data-color="#416aa6">
                                    </div>
                                </div>
                            </li>
                            <li>
                                <div class="row">
                                    <div class="col-xl-8 col-md-7 col-7">
                                        <h4><?php echo $this->lang->line('delete_stream'); ?></h4>
                                        <p><?php echo $this->lang->line('delete_stream_description'); ?></p>
                                    </div>
                                    <div class="col-xl-4 col-md-5 col-5 text-right">
                                        <a href="#" class="stream-delete-btn">
                                            <i class="icon-trash"></i>
                                            <?php echo $this->lang->line('delete_stream'); ?>
                                        </a>
                                    </div>
                                </div>
                            </li>
                        </ul>
                    </div>
                    <div class="tab-pane fade" id="nav-stream-setup" role="tabpanel" aria-labelledby="nav-stream-setup-tab">
                    </div>
                    <div class="tab-pane fade" id="nav-stream-faq" role="tabpanel" aria-labelledby="nav-stream-faq-tab">
                        <div class="row">
                            <div class="col-xl-3">
                                <div class="panel panel-default">
                                    <div class="panel-heading">
                                        <h3 class="panel-title">
                                            <?php echo $this->lang->line('categories'); ?>                                    
                                        </h3>
                                    </div>
                                    <div class="panel-body">
                                        <nav>
                                            <div class="nav nav-tabs" id="nav-tab" role="tablist">
                                                <a class="nav-item nav-link active" id="faq-settings-nav" data-toggle="tab" href="#faq-settings-tab" role="tab" aria-controls="faq-settings-tab" aria-selected="true"><?php echo $this->lang->line('settings'); ?> <i class="fas fa-angle-right"></i></a>
                                                <a class="nav-item nav-link" id="faq-setup-nav" data-toggle="tab" href="#faq-setup-tab" role="tab" aria-controls="faq-setup-tab" aria-selected="false"><?php echo $this->lang->line('setup'); ?>  <i class="fas fa-angle-right"></i></a>
                                                <a class="nav-item nav-link" id="faq-alerts-nav" data-toggle="tab" href="#faq-alerts-tab" role="tab" aria-controls="faq-alerts-tab" aria-selected="false"><?php echo $this->lang->line('alerts'); ?> <i class="fas fa-angle-right"></i></a>
                                            </div>
                                        </nav>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xl-9">
                                <div class="tab-content" id="nav-tabContent">
                                    <?php echo $this->lang->line('stream_faq_tabs'); ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="stream-tab-settings" tabindex="-1" role="dialog" aria-labelledby="stream-tab-settings-tab" aria-hidden="true">
    <div class="modal-dialog file-upload-box modal-xl modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <nav>
                    <div class="nav nav-tabs" id="nav-tab" role="tablist">
                        <a class="nav-item nav-link active show" id="nav-tab-stream-settings-tab" data-toggle="tab" href="#nav-tab-stream-settings" role="tab" aria-controls="nav-tab-stream-settings" aria-selected="true">
                            <?php echo $this->lang->line('settings'); ?>
                        </a>
                    </div>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </nav>
            </div>
            <div class="modal-body">
                <div class="tab-content" id="nav-stream-settings-tabContent">
                    <div class="tab-pane fade active show" id="nav-tab-stream-settings" role="tabpanel" aria-labelledby="nav-tab-stream-settings-tab">
                        <ul class="settings-list-options">
                            <li>
                                <div class="row">
                                    <div class="col-xl-8 col-md-7 col-7">
                                        <h4><?php echo $this->lang->line('auto_refresh'); ?></h4>
                                        <p><?php echo $this->lang->line('auto_refresh_description'); ?></p>
                                    </div>
                                    <div class="col-xl-4 col-md-5 col-5 text-right">
                                        <div class="dropdown">
                                            <button class="btn btn-secondary stream-tab-refresh-interval dropdown-toggle stream-settings-select" data-interval="0" type="button" id="dropdownMenu3" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                <i class="icon-refresh"></i> <?php echo $this->lang->line('never'); ?>
                                            </button>
                                            <div class="dropdown-menu stream-tab-refresh-interval-options" aria-labelledby="dropdownMenu3" x-placement="bottom-start">
                                                <button class="dropdown-item" data-interval="0" type="button">
                                                    <i class="icon-refresh"></i> <?php echo $this->lang->line('never'); ?>
                                                </button>                                                
                                                <button class="dropdown-item" data-interval="15" type="button">
                                                    <i class="icon-refresh"></i> 15 <?php echo $this->lang->line('minutes'); ?>
                                                </button>
                                                <button class="dropdown-item" data-interval="30" type="button">
                                                    <i class="icon-refresh"></i> 30 <?php echo $this->lang->line('minutes'); ?>
                                                </button>
                                                <button class="dropdown-item" data-interval="45" type="button">
                                                    <i class="icon-refresh"></i> 45 <?php echo $this->lang->line('minutes'); ?>
                                                </button>
                                                <button class="dropdown-item" data-interval="60" type="button">
                                                    <i class="icon-refresh"></i> 1 <?php echo $this->lang->line('hour'); ?>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </li>
                            <li>
                                <div class="row">
                                    <div class="col-xl-8 col-md-7 col-7">
                                        <h4><?php echo $this->lang->line('delete_tab'); ?></h4>
                                        <p><?php echo $this->lang->line('delete_tab_description'); ?></p>
                                    </div>
                                    <div class="col-xl-4 col-md-5 col-5 text-right">
                                        <a href="#" class="stream-delete-tab-btn">
                                            <i class="icon-trash"></i>
                                            <?php echo $this->lang->line('delete_tab'); ?>
                                        </a>
                                    </div>
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="stream-item-share" tabindex="-1" role="dialog" aria-labelledby="stream-item-share-tab" aria-hidden="true">
    <div class="modal-dialog file-upload-box modal-xl modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <nav>
                    <div class="nav nav-tabs" id="nav-tab" role="tablist">
                        <a class="nav-item nav-link active show">
                            <?php echo $this->lang->line('share_post'); ?>
                        </a>
                    </div>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </nav>
            </div>
            <div class="modal-body">
                <div class="row stream-share-accounts-manager">
                    <div class="col-xl-12">
                        <nav>
                            <div class="nav nav-tabs" id="nav-tab" role="tablist">
                                <a class="nav-item nav-link active show" id="nav-accounts-manager-tab" data-toggle="tab" href="#nav-accounts-manager" role="tab" aria-controls="nav-accounts-manager" aria-selected="true">
                                    <?php echo $this->lang->line('accounts'); ?> 
                                </a>
                                <a class="nav-item nav-link" id="nav-groups-manager-tab" data-toggle="tab" href="#nav-groups-manager" role="tab" aria-controls="nav-groups-manager" aria-selected="false">
                                    <?php echo $this->lang->line('groups'); ?> 
                                </a>
                            </div>
                        </nav>
                        <div class="tab-content" id="nav-tabContent">
                            <div class="tab-pane fade active show" id="nav-accounts-manager" role="tabpanel" aria-labelledby="nav-accounts-manager">
                            </div>
                            <div class="tab-pane fade" id="nav-groups-manager" role="tabpanel" aria-labelledby="nav-groups-manager">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xl-6 col-lg-6 stream-post-composer-area">
                        <?php echo form_open('user/app/stream', array('class' => 'stream-share-post', 'data-csrf' => $this->security->get_csrf_token_name())); ?>
                            <div class="col-xl-12 post-composer">
                                <div class="row">
                                    <div class="col-xl-12 post-body">
                                        <div class="col-xl-12 composer-title">
                                            <input type="text" placeholder="<?php echo $this->lang->line('enter_post_title'); ?>">
                                        </div>
                                        <div class="row post-composer-editor"> 
                                            <div class="col-xl-12 composer">
                                                <textarea class="new-post" placeholder="<?php echo $this->lang->line('share_what_new'); ?>"></textarea>
                                            </div>
                                        </div>
                                        <div class="row post-composer-buttons">
                                            <div class="col-xl-12">
                                                <button type="button" class="btn show-title">
                                                    <i class="fas fa-text-width"></i>
                                                </button>
                                                <?php
                                                if ( get_user_option('settings_character_count') ) {
                                                    echo '<div class="numchar">0</div>';
                                                }
                                                ?>
                                            </div>                                        
                                        </div>
                                    </div>
                                </div> 
                                <div class="row stream-media-post-save-area">
                                    <div class="col-8">
                                        <p>
                                            <?php echo $this->lang->line('use_post_media'); ?>
                                        </p>
                                    </div>
                                    <div class="col-4">
                                        <a class="btn btn-primary stream-media-post-save-btn">
                                            <i class="icon-cloud-download"></i>
                                        </a>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-xl-12 post-footer">
                                        <div class="multimedia-gallery">
                                            <ul>
                                            </ul>
                                            <a href="#" class="load-more-medias" data-page="1"><?php echo $this->lang->line('load_more'); ?></a>
                                            <p class="no-medias-found"><?php echo $this->lang->line('no_photos_videos_uploaded'); ?></p>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-xl-12 buttons-control">
                                        <input type="text" class="datetime">
                                        <div class="btn-group dropup">
                                            <button type="submit" class="btn btn-success"><i class="icon-share-alt"></i> <?php echo $this->lang->line('share_now'); ?></button>
                                            <button type="button" class="btn btn-success dropdown-toggle" data-toggle="dropdown">
                                                <span class="caret"></span>
                                            </button>
                                            <ul class="dropdown-menu" role="menu">
                                                <li><a href="#" class="open-midrub-planner"><?php echo $this->lang->line('schedule'); ?></a></li>
                                                <li><a href="#" class="composer-draft-post"><?php echo $this->lang->line('draft_it'); ?></a></li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php echo form_close(); ?>
                    </div>
                    <div class="col-xl-6 col-lg-6 clean post-destination">
                        <div class="col-xl-12">
                            <div class="row">
                                <div class="col-xl-10 col-sm-10 col-9 input-group stream-share-modal-accounts-search">
                                    <div class="input-group-prepend">
                                        <i class="icon-magnifier"></i>
                                    </div>
                                    <?php
                                    if (get_user_option('settings_display_groups')) {

                                        echo '<input type="text" class="form-control stream-share-search-for-groups" placeholder="' . $this->lang->line('search_for_groups') . '">'
                                        . '<button type="button" class="stream-share-cancel-search-for-groups">'
                                        . '<i class="icon-close"></i>'
                                        . '</button>';
                                    } else {

                                        echo '<input type="text" class="form-control stream-share-search-for-accounts" placeholder="' . $this->lang->line('search_for_accounts') . '">'
                                        . '<button type="button" class="stream-share-cancel-search-for-accounts">'
                                        . '<i class="icon-close"></i>'
                                        . '</button>';
                                    }
                                    ?>
                                </div>
                                <div class="col-xl-2 col-sm-2 col-3">
                                    <button type="button" class="stream-share-manage-members"><i class="icon-user-follow"></i></button>
                                </div>
                            </div>
                            <div class="row">
                                <?php
                                if (get_user_option('settings_display_groups')) {

                                    echo '<div class="col-xl-12 stream-schedule-groups-list">'
                                    . '<ul>';

                                    if ($groups_list) {

                                        foreach ($groups_list as $group) {
                                            ?>
                                            <li>
                                                <a href="#" data-id="<?php echo $group->list_id; ?>">
                                                    <?php echo '<i class="icon-folder-alt"></i>'; ?>
                                                    <?php echo $group->name; ?>
                                                    <i class="icon-check"></i>
                                                </a>
                                            </li>
                                            <?php
                                        }
                                    } else {

                                        echo '<li class="no-groups-found">' . $this->lang->line('no_groups_found') . '</li>';
                                    }

                                    echo '</ul>'
                                    . '</div>';
                                } else {

                                    echo '<div class="col-xl-12 stream-schedule-accounts-list">'
                                    . '<ul>';

                                    if ($accounts_list) {

                                        foreach ($accounts_list as $account) {
                                            ?>
                                            <li>
                                                <a href="#" data-id="<?php echo $account['network_id']; ?>" data-net="<?php echo $account['net_id']; ?>" data-network="<?php echo $account['network_name']; ?>" data-category="<?php echo in_array('categories', $account['network_info']['types']) ? 'true' : 'value'; ?>">
                                                    <?php echo str_replace(' class', ' style="color: ' . $account['network_info']['color'] . '" class', $account['network_info']['icon']); ?>
                                                    <?php echo $account['user_name']; ?>
                                                    <i class="icon-check"></i>
                                                </a>
                                            </li>
                                            <?php
                                        }
                                    } else {

                                        echo '<li class="no-accounts-found">' . $this->lang->line('no_accounts_found') . '</li>';
                                    }

                                    echo '</ul>'
                                    . '</div>';
                                }
                                ?>
                            </div>
                            <div class="row stream-share-modal-colapse-selected-accounts">
                                <div class="col-6">
                                    <p class="stream-share-modal-colapse-selected-accounts-count">0 <?php echo $this->lang->line('selected_accounts'); ?></p>
                                </div>
                                <div class="col-6">
                                    <a class="btn btn-primary" data-toggle="collapse" href="#collapseExample" role="button" aria-expanded="false" aria-controls="collapseExample">
                                        <i class="icon-plus"></i>
                                    </a>
                                </div>
                                <div class="col-xl-12">
                                    <div class="collapse" id="collapseExample">
                                        <div class="card card-body stream-share-modal-colapse-selected-accounts-list">
                                            <ul>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="stream-item-react" tabindex="-1" role="dialog" aria-labelledby="stream-item-react-tab" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <nav>
                    <div class="nav nav-tabs" id="nav-tab" role="tablist">
                        <a class="nav-item nav-link active" id="nav-tab-stream-react">
                        </a>
                    </div>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </nav>
            </div>
            <div class="modal-body">
            </div>
        </div>
    </div>
</div>

<!--Upload image form !-->
<?php
$attributes = array('class' => 'upim d-none', 'id' => 'upim', 'method' => 'post', 'data-csrf' => $this->security->get_csrf_token_name() );
echo form_open_multipart('user/app/posts', $attributes);
?>
<input type="hidden" name="type" id="type" value="video">
<input type="file" name="file[]" id="file" accept=".gif,.jpg,.jpeg,.png,.mp4,.avi" multiple>
<?php echo form_close(); ?>

<!--Midrub Planner !-->
<div class="midrub-planner">
    <div class="row">
        <div class="col-xl-12">
            <table class="midrub-calendar iso">
                <thead>
                    <tr>
                        <th class="text-center"><a href="#" class="go-back"><i class="icon-arrow-left"></i></a></th>
                        <th colspan="5" class="text-center year-month"></th>
                        <th class="text-center"><a href="#" class="go-next"><i class="icon-arrow-right"></i></a></th>
                    </tr>
                </thead>
                <tbody class="calendar-dates">
                </tbody>
            </table>
        </div>
    </div>
    <div class="row">
        <div class="col-xl-12 text-center">
            <?php echo scheduler_time(); ?>
        </div>
    </div>
    <div class="row">
        <div class="col-xl-12 text-center">
            <button type="btn" class="btn composer-schedule-post">
                <?php echo $this->lang->line('schedule'); ?>
            </button>
        </div>
    </div>
</div>

<!-- Translations !-->
<script language="javascript">
    var words = {
        mon: '<?php echo $this->lang->line('mon'); ?>',
        tue: '<?php echo $this->lang->line('tue'); ?>',
        wed: '<?php echo $this->lang->line('wed'); ?>',
        thu: '<?php echo $this->lang->line('thu'); ?>',
        fri: '<?php echo $this->lang->line('fri'); ?>',
        sat: '<?php echo $this->lang->line('sat'); ?>',
        sun: '<?php echo $this->lang->line('sun'); ?>',
        selected_accounts: '<?php echo $this->lang->line('selected_accounts'); ?>',
        selected_groups: '<?php echo $this->lang->line('selected_groups'); ?>',
        please_select_at_least_one_account: '<?php echo $this->lang->line('please_select_at_least_one_account'); ?>',
        please_select_group: '<?php echo $this->lang->line('please_select_group'); ?>',
        media_can_not_be_downloaded: '<?php echo $this->lang->line('media_can_not_be_downloaded'); ?>',
        select_saved_media: '<?php echo $this->lang->line('select_saved_media'); ?>',
        please_install_the_mobile_client: '<?php echo $this->lang->line('please_install_the_mobile_client'); ?>',
    };
</script>
