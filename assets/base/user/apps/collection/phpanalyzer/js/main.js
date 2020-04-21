/*
 * Main javascript file
*/

jQuery(document).ready( function ($) {
    'use strict';

    //$('#example').DataTable();

    /*
     * Get the website's url
     */
    var url =  $('meta[name=url]').attr('content');
    
    /*******************************
    METHODS
    ********************************/
       
    /*
     * Load media's employees
     * 
     * @since   0.0.7.6
     */
    Main.loadPHPAnalyzer = function () {

        var data = {
            action: 'phpanalyzer_user'
        };
        
        // Make ajax call
        Main.ajax_call(url + 'user/app-ajax/phpanalyzer', 'GET', data, 'phpanalyzer_user');
        
    };    
   

    /*
     * Display all categories response
     * 
     * @param string status contains the response status
     * @param object data contains the response content
     * 
     * @since   0.0.7.6
     */
    Main.methods.phpanalyzer_user = function ( status, data ) 
    { 
        //console.log(data.user.username);
        
            var test = '<div class="container-fluid mt-3" id="text-custom">'
                        +'<h1 class="header-t">PHP Analyzer</h3>'
       
                        +'<ul class="nav nav-pills mb-3" id="taBB">'
                             +'<li class="nav-item"><a href="#navpills-1" class="nav-link active" data-toggle="tab" aria-expanded="false">Dashboard</a></li>'
                           
                            
                            +'<li class="nav-item"><a href="#navpills-7" class="nav-link" data-toggle="tab" aria-expanded="false">Compare</a></li>'
                            
                            +'<li class="nav-item"><a href="#navpills-3" class="nav-link" data-toggle="tab" aria-expanded="true"> My Reports </a></li>'
                            
                            
                            +'<li class="nav-item"><a href="#navpills-4" class="nav-link" data-toggle="tab" aria-expanded="false">Favourites</a></li>'
                            +'<li class="nav-item"><a href="#navpills-5" class="nav-link" data-toggle="tab" aria-expanded="false"> Account Settings </a></li>'
                        
                        +'</ul>'
                        +'<div class="tab-content br-n pn">'
                     
                            +'<div id="navpills-1" class="tab-pane active">'
                                +'<div class="row">'
                                
                           
                +'<div class="col-lg-12">'
                    +'<div class="card">'
                        +'<div class="card-body">'
                        
                        
                            
                    
                    +'<div class="pHP_ana_up">'
                    +'<ul>'
                    +'<li><i class="fa fa-calendar-check-o"></i>Joined on 2020-04-06</li>'
                    +'<li><i class="fa fa-credit-card"></i>Account Balance of total points</li>'
                    +'<li><i class="fa fa-heart-o"></i>Total Favourites</li>'
                    +'<li><i class="fa fa-file-text-o"></i>Total Unlock Reports</li>'
                    +'</ul>'
                    
                    
                    
                    +'<div class="clearfix"></div>'
                    
                    +'<h4 class="color">PHP Analyzer <span>Analyze and track your social media account</span></h4>'
                    
                    
                    +'<form action="" method="get" name="search-user" id="search-user"> '
                    +'<div class="div_diff">'
                                            +'<div class="basic-dropdown">'
                                                +'<div class="dropdown">'
                                                    +'<button type="button" class="btn btn-primary dropdown-toggle action-button" data-toggle="dropdown" aria-expanded="false">Facebook</button>'
                                                    +'<div class="dropdown-menu" x-placement="top-start" style="position: absolute; transform: translate3d(207px, -107px, 0px); top: 0px; left: 0px; will-change: transform;"><a class="dropdown-item" href="#">Twitter</a> <a class="dropdown-item" href="#">Linkedin</a> <a class="dropdown-item" href="#">Instagram</a>'
                                                +'</div>'
                                            +'</div>'
                                        +'</div>'
                                        +'<h4 class="card-title color">'
                                            +'<i class="fa fa-search upload_ico"></i> '
                                            +'<input type="text" placeholder="Enter facebook page username" name="username" id="username" value="" >'
                                            +'<div id="usernameList"></div>'
                                        +'</h4>' 
                                        +'<div class="basic-dropdown float-right" id="mobile-left">'
                                            +'<div class="dropdown">'
                                                +'<button type="submit" class="btn btn-primary action-button2">Search</button>'
                                            +'</div>'
                                        +'</div>'
                                    +'</div>'
                    +'</form>'
                    
                    +'</div>'
                    
                        +'</div>'
                        
                    +'</div>'

        +'<div class="card">'
                        +'<div class="card-body">'
                    
                    +'<div class="pHP_ana_up">'
                    
                    
                    +'<h4 class="color">Profile' 
                    
                    +'<button type="button" onclick="window.print()" class="btn btn-primary action-button2"> <i class="fa fa-file-pdf-o"></i> PDF Export</button>'
                    
                    +'</h4>'
                    
                    +'<div class="col-lg-6  col-sm-6 float-left">'
                    +'<div class="float-left">'
                    +'<img src="'+data.user.profile_picture_url+'" class="img-fluid right_margin">'
                    
                    +'</div>'
                    
                    +'<div class="float-left color">'
                    +'<h1>'+data.user.username+'</h1>'
                    +'<h2>'+data.user.name+'</h2>'
                    +'<h3>Facebook Page</h3>'
                    +'</div>'
                    +'</div>'
                    
                    +'<div class="col-lg-6  col-sm-6 float-right">'
                                        
                    +'<div class="float-left color right_margin">'
                    +'<h1>Likes</h1>'
                    +'<h2>'+data.user.likes+'</h2>'
                    
                    +'</div>'
                    
                    +'<div class="float-left color">'
                    +'<h1>Followers</h1>'
                    +'<h2>'+data.user.followers+'</h2>'
                    
                    +'</div>'
                    
                    +'</div>'
                    
                    +'<div class="border-bottom"></div>'
                    
                    +'</div>'

                    +'</div>'

                    +'</div>'
        
           ;

        jQuery('.content-body').html(test);  
    };

        
    /*
     * Display delete media category response
     * 
     * @param string status contains the response status
     * @param object data contains the response content
     * 
     * @since   0.0.7.6
     */
    
        
    /*******************************
    DEPENDENCIES
    ********************************/
   
    // Load all media's categories
    Main.loadPHPAnalyzer();
    //$('#example').DataTable();
    
});