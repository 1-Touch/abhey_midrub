<?php
error_reporting(0);
?>
<style>
.chart-container 
{
  	position: relative;
  	margin: auto;
}
</style>
<section>
<div class="content-body">

    <div class="container-fluid mt-3" id="text-custom">
	<h1 class="header-t">PHP Analyzer</h3>
       
<ul class="nav nav-pills mb-3" id="taBB">
                             <li class="nav-item"><a href="#navpills-1" class="nav-link active" data-toggle="tab" aria-expanded="false">Dashboard</a>
                            </li>
                           <!-- <li class="nav-item"><a href="#navpills-2" class="nav-link" data-toggle="tab" aria-expanded="false"> Storage</a>
                            </li>-->
							
							<li class="nav-item"><a href="#navpills-7" class="nav-link" data-toggle="tab" aria-expanded="false"> Compare</a>
                            </li>
							
                            <li class="nav-item"><a href="#navpills-3" class="nav-link" data-toggle="tab" aria-expanded="true"> My Reports </a>
                            </li>
							
							
							<li class="nav-item"><a href="#navpills-4" class="nav-link" data-toggle="tab" aria-expanded="false">Favourites</a>
                            </li>
                            <li class="nav-item"><a href="#navpills-5" class="nav-link" data-toggle="tab" aria-expanded="false"> Account Settings </a>
                            </li>
							
							 <!--<li class="nav-item"><a href="#navpills-6" class="nav-link" data-toggle="tab" aria-expanded="false"> API Documentation </a>
                            </li>-->
							
							
                        </ul>
                     <div class="tab-content br-n pn">
					 
                            <div id="navpills-1" class="tab-pane active">
                                <div class="row">
								
                           
				<div class="col-lg-12">
                    <div class="card">
                        <div class="card-body">
						
						
							
					
					<div class="pHP_ana_up">
					<ul>
					<li><i class="fa fa-calendar-check-o"></i>Joined on 2020-04-06</li>
					<li><i class="fa fa-credit-card"></i>Account Balance of total points</li>
					<li><i class="fa fa-heart-o"></i>Total Favourites</li>
					<li><i class="fa fa-file-text-o"></i>Total Unlock Reports</li>
					</ul>
					
					
					
					<div class="clearfix"></div>
					
					<h4 class="color">PHP Analyzer <span>Analyze and track your social media account</span></h4>
					
					
					<form action="" method="get" name="search-user" id="search-user"> 
					<div class="div_diff">
                                            <div class="basic-dropdown">
                                                <div class="dropdown">
                                                    <button type="button" class="btn btn-primary dropdown-toggle action-button" data-toggle="dropdown" aria-expanded="false">Facebook</button>
                                                    <div class="dropdown-menu" x-placement="top-start" style="position: absolute; transform: translate3d(207px, -107px, 0px); top: 0px; left: 0px; will-change: transform;"><a class="dropdown-item" href="#">Twitter</a> <a class="dropdown-item" href="#">Linkedin</a> <a class="dropdown-item" href="#">Instagram</a>
                                                </div>
                                            </div>
                                        </div>
                                        <h4 class="card-title color">
                                            <i class="fa fa-search upload_ico"></i> 
                                            <input type="text" placeholder="Enter facebook page username" name="username" id="username" value="" >
                                            <div id="usernameList"></div>
                                            <!-- <input type="hidden" name="<?php //echo $this->security->get_csrf_token_name();?>" value="<?php //echo $this->security->get_csrf_hash();?>"> -->
                                        </h4>
                                        <div class="basic-dropdown float-right" id="mobile-left">
                                            <div class="dropdown">
                                                <button type="submit" class="btn btn-primary action-button2">Search</button>
                                            </div>
                                        </div>
                                    </div>
					</form>
					
					</div>
					
                        </div>
                    </div>
                    
					
					 <div class="card">
                        <div class="card-body">
					
					<div class="pHP_ana_up">
					
					
					<h4 class="color">Profile 
					
					<button type="button" onclick="window.print()" class="btn btn-primary action-button2"> <i class="fa fa-file-pdf-o"></i> PDF Export</button>
					
					</h4>
					
					<div class="col-lg-6  col-sm-6 float-left">
					<div class="float-left">
					<img src="<?php echo $user->profile_picture_url;?>" class="img-fluid right_margin">
					
					</div>
					
					<div class="float-left color">
					<h1><?php echo $user->username;?></h1>
					<h2><?php echo $user->name;?></h2>
					<h3>Facebook Page</h3>
					</div>
					</div>
					
					<div class="col-lg-6  col-sm-6 float-right">
										
					<div class="float-left color right_margin">
					<h1>Likes</h1>
					<h2><?php echo $user->likes;?></h2>
					
					</div>
					
					<div class="float-left color">
					<h1>Followers</h1>
					<h2><?php echo $user->followers;?></h2>
					
					</div>
					
					</div>
					
					<div class="border-bottom"></div>
					
					</div>
					<div class="clearfix"></div>
					
					<div class="col-lg-12 stats">
					
					<h4>Statistics Summary</h4>
					<div class="col-lg-6 float-left">
					<!-- <h5 class="color">Likes Evolution Chart </h5> -->
					<!-- <div id="flotLine1" class="flot-chart"></div> -->
					<div class="chart-container">
            			<canvas id="likes_chart" style="display: block; height: 250px; width: 1110px;" width="1665" height="375"></canvas>
        			</div>
					</div>
				
					
					
					<div class="col-lg-6 float-right">
					<!-- <h5 class="color">Followers Evolution Chart </h5> -->
					<!-- <div id="flotLine2" class="flot-chart"></div> -->
					<div class="chart-container mt-3">
		                <canvas id="followers_chart" style="display: block; height: 250px; width: 1110px; margin-top: -10px;" width="1665" height="375"></canvas>
		            </div>
					</div>
					
					</div>

					<div class="border-bottom"></div>
					<div class="clearfix"></div>
					<div class="col-lg-12 stats">
					<h4 class="color">Account Stats Summary <em>Showing Last 15 Enteries</em><span><a href="<?= base_url() ?>admin/facebook/exportCSV/<?php echo $user->username?>"><i class="fa fa-download"></i>Export to CSV</a></span></h4>
					<div class="col-lg-12 float-left">
												
					<div class="table-responsive">
                            <table class="table header-border">
                                <thead>
                                    <tr>
                                        <th>Date</th>
										<th>Day</th>
										<th>Likes</th>
                                        <th>Likes Difference </th>
                                        <th>Followers</th>
                                        <th>Followers Difference</th>
										 
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $likes = [];
                                    $followers = [];
                                    $dates = [];
	                                 foreach ($user_logs as $summary) 
	                                 {
	                                     $i = 0;
	                                     $likes[] = $summary->likes;
	                                     $followers[] = $summary->followers;
	                                     $date1 = date_create($summary->date);
	                                     $dates[] = date_format($date1,'Y-m-d');
	                                     
	                                     $i++;
	                                 }
	                                for($i=0;$i<count($user_logs);$i++) 
	                                {
	                                    if($i==(count($user_logs)-1)) 
	                                    {
	                                    	break;
	                                    } 
	                                    else 
	                                    {
	                                    	$likes_diff[] = $likes[$i]-$likes[$i+1];
	                                        $follow_diff[] = $followers[$i]-$followers[$i+1];
	                                    }
	                                }
	                                $j = 0;
                                    foreach($user_logs as $user)
                                    {
                                        $date = date_create($user->date);
                                        echo '<tr>';
                                        echo '<td>'.date_format($date,'Y-m-d').'</td>';
                                        echo '<td>'.date_format($date,'l').'</td>';
                                        echo '<td>'.$user->likes.'</span></td>';
                                        if($likes_diff[$j]==0)
                                        {
                                        	echo '<td>-</td>';
                                        }
                                        else
                                        {
                                        	
                                        	echo '<td><span class="badge badge-primary px-2">'.$likes_diff[$j].'</span></td>';
                                        }
                                        echo '<td>'.$user->followers.'</span></td>';
                                        if($follow_diff[$j]==0)
                                        {
                                        	echo '<td>-</td>';
                                        }
                                        else
                                        {
                                        	echo '<td><span class="badge badge-primary px-2">'.$follow_diff[$j].'</span></td>';
                                        }
                                        
                                        echo '</tr>';
                                        $j++;
                                    }
                                    ?>
                                            
                                              
                                </tbody>
                            </table>
                        </div>
					</div>
					
                        </div>   
						
						<div class="clearfix"></div>
						
						
						<div class="border-bottom"></div>
						<div class="col-lg-12 stats">
					<h4 class="color">Future Projection <em>Here you can see the future projection based on your previuos days average.</em></h4>
					<div class="col-lg-12 float-left">
												
					<div class="table-responsive">
                            <table class="table header-border">
                                <thead>
                                    <tr>
                                        <th>Date</th>
										<th>Day</th>
										<th>Likes</th>
                                        <th>Followers</th>
										  
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $user_log = array_reverse($user_logs);
                                    foreach($user_log as $user)
                                    {
                                        $date = date_create($user->date);
                                        echo '<tr>';
                                        echo '<td>'.date_format($date,'Y-m-d').'</td>';
                                        echo '<td>'.date_format($date,'l').'</td>';
                                        echo '<td>'.$user->likes.'</span></td>';
                                        echo '<td>'.$user->followers.'</span></td>';
                                        echo '</tr>';
                                    }
                                    ?>              
                                </tbody>
                            </table>
                        </div>
					</div>
					
			
					
                        </div>   
						
						
						<div class="clearfix"></div>

						 
						
                        </div>
                    </div>
					
                </div>   
				  
                                </div>
                            </div>
                            <div id="navpills-2" class="tab-pane">
                                <div class="row align-items-center">
                                   <div class="col-lg-12">
                                  <img src="<?= base_url(); ?>assets/images/sch-graph.jpg" width="100%"> </div>  

					
                                </div>
                            </div>
							
							
							 <div id="navpills-7" class="tab-pane">
                                <div class="row align-items-center">
                                   <div class="col-lg-12">
                                  
								  <div class="card">
                        <div class="card-body">
					
					<div class="pHP_ana_up">
					<ul>
					<li><i class="fa fa-calendar-check-o"></i>Joined on 2020-04-06</li>
					<li><i class="fa fa-credit-card"></i>Account Balance of total points</li>
					<li><i class="fa fa-heart-o"></i>Total Favourites</li>
					<li><i class="fa fa-file-text-o"></i>Total Unlock Reports</li>
					</ul>
					
					
					
					<div class="clearfix"></div>
					
					<h4 class="color">Compare Facebook Account <span>Analyze and track your social media account</span></h4>
					
					
					
					<div class="div_diff">
						<div class="basic-dropdown">
                            <div class="dropdown">
                                <button type="button" class="btn btn-primary dropdown-toggle action-button" data-toggle="dropdown" aria-expanded="false">Facebook</button>
                                <div class="dropdown-menu" x-placement="top-start" style="position: absolute; transform: translate3d(207px, -107px, 0px); top: 0px; left: 0px; will-change: transform;"><a class="dropdown-item" href="#">Twitter</a> <a class="dropdown-item" href="#">Linkedin</a> <a class="dropdown-item" href="#">Instagram</a>
                                </div>
                            </div>
                        </div>
						
						<div class="vs">vs</div>
						<div class="basic-dropdown">
                            <div class="dropdown">
                                <button type="button" class="btn btn-primary dropdown-toggle action-button" data-toggle="dropdown" aria-expanded="false">Instagram</button>
                                <div class="dropdown-menu" x-placement="top-start" style="position: absolute; transform: translate3d(207px, -107px, 0px); top: 0px; left: 0px; will-change: transform;"><a class="dropdown-item" href="#">Twitter</a> <a class="dropdown-item" href="#">Linkedin</a> <a class="dropdown-item" href="#">Facebook</a>
                                </div>
                            </div>
                        </div>
														
							<div class="basic-dropdown float-right">
                            <div class="dropdown">
                                <button type="submit" class="btn btn-primary action-button2">Search</button>
                               
                            </div>
                        </div>
																</div>
					
					
					
					<div class="border-bottom"></div>
					<div class="clearfix"></div>
					
		
					
					<div class="border-bottom"></div>
					<div class="clearfix"></div>
					
					<div class="col-lg-12 stats">
					<h4 class="color">Statistics Summary</h4>
					<div class="col-lg-12 float-left">
												
					<div class="table-responsive">
                            <table class="table header-border">
                                <thead>
                                    <tr>
                                        <th>Date</th>
										<th>Day</th>
										<th>Likes</th>
                                        <th>Likes Difference </th>
                                        <th>Followers</th>
                                        <th>Followers Difference</th>
										  <th>Uploads</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>2020-04-09
                                        </td>
                                        <td>Sunday</td>
                                        <td>3,07,111
                                        </td>
										<td><span class="badge badge-primary px-2">365</span>
                                        </td>
										<td>37,32,2111
                                        </td>
										<td> <span class="badge badge-primary px-2">312</span>
                                        </td>
										<td>1200
                                        </td>
                                        
                                   
                                    </tr>
									
									 <tr>
                                        <td>2020-04-09
                                        </td>
                                        <td>Sunday</td>
                                        <td>3,07,111
                                        </td>
										<td><span class="badge badge-primary px-2">365</span>
                                        </td>
										<td>37,32,2111
                                        </td>
										<td> <span class="badge badge-primary px-2">312</span>
                                        </td>
										<td>1200
                                        </td>
                                        
                                   
                                    </tr>
									
									 <tr>
                                        <td>2020-04-09
                                        </td>
                                        <td>Sunday</td>
                                        <td>3,07,111
                                        </td>
										<td><span class="badge badge-primary px-2">365</span>
                                        </td>
										<td>37,32,2111
                                        </td>
										<td> <span class="badge badge-primary px-2">312</span>
                                        </td>
										<td>1200
                                        </td>
                                        
                                   
                                    </tr>
                                   
                                   
                                  
                                </tbody>
                            </table>
                        </div>
					</div>
					
					
					
                        </div>   
					
					
					<div class="border-bottom"></div>
					
					<div class="clearfix"></div>
					
					<div class="col-lg-12">
					<h4 class="color">Flow Chart</h4>
				
					</div>
					
					<div class="border-bottom"></div>
					
					<div class="clearfix"></div>
					
					<div class="col-lg-12">
					<h4 class="color">Average Engagement Chart</h4>
				
					</div>
					
					<div class="border-bottom"></div>
					<div class="clearfix"></div>
					<div class="col-lg-12 stats">
					<h4 class="color">Top Posts <em>Top posts from the last 6 posts</em></h4>
					
						<div class="col-lg-4 col-md-4 col-sm-6 float-left">

						<img src="<?= base_url(); ?>assets/images/social-ad.jpg" class="img-fluid">
						</div>								
					
					<div class="col-lg-4 col-md-4 col-sm-6 float-left">

						<img src="<?= base_url(); ?>assets/images/social-ad.jpg" class="img-fluid">
						</div>	
						
						<div class="col-lg-4 col-md-4 col-sm-6 float-left">

						<img src="<?= base_url(); ?>assets/images/social-ad.jpg" class="img-fluid">
						</div>	
						
						<div class="col-lg-4 col-md-4 col-sm-6 float-left">

						<img src="<?= base_url(); ?>assets/images/social-ad.jpg" class="img-fluid">
						</div>	
						
						<div class="col-lg-4 col-md-4 col-sm-6 float-left">

						<img src="<?= base_url(); ?>assets/images/social-ad.jpg" class="img-fluid">
						</div>	
						<div class="col-lg-4 col-md-4 col-sm-6 float-left">

						<img src="<?= base_url(); ?>assets/images/social-ad.jpg" class="img-fluid">
						</div>	
					
					
					
                        </div>
					
					</div>
                        </div>
                    </div>
								  
								  </div>  

					
                                </div>
                            </div>
							
							
                            <div id="navpills-3" class="tab-pane">
                                <div class="row">
                                    
                                    <div class="col-lg-12 col-sm-12">
									
									 <div>
                                          
                       
                        
                        <div id="accordion-two" class="accordion custom_CSS">
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="mb-0" data-toggle="collapse" data-target="#collapseOne1" aria-expanded="true" aria-controls="collapseOne1"><i class="fa" aria-hidden="true"></i>Facebook Accounts Weekly Report</h5>
                                </div>
                                <div id="collapseOne1" class="collapse show" data-parent="#accordion-two">
                                	
                             <div class="card" id="white">
						 <div class="card-body">
						<div class="pHP_ana_up" id="center-img">
						 
													<h4 class="color">Report for <?php echo $user->name;?> <span>We are even sending you notification of your tracked  social media accounts progress by email</span>
						
						</h4>
						
						<div class="col-lg-3 col-md-3 col-sm-6 float-left">
						<img src="<?php echo $user->profile_picture_url; ?>" class="img-fluid text-center">
						<h5 class="text-center"><?php echo $user->name;?></h5>
						</div>
						
						
						<div class="col-lg-7 col-md-7 col-sm-6 float-left">
						
						<div class="table-responsive stats">
                            <table class="table header-border">
                                <thead>
                                    <tr>
									 <th><?php echo $user->name;?></th>
                                        <th>Previous</th>
                                        <th>Latest</th>
                                        <th>Difference</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
									<td>Likes</td>
									<?php 

									$likes_diff = ($user_logs[0]->likes) - ($user_logs[1]->likes);
									?>
                                        <td><?php echo $user_logs[1]->likes;?>
                                        </td>
                                        <td><?php echo $user_logs[0]->likes;?></td>
                                        <td><span class="badge badge-primary px-2"><?php echo $likes_diff;?></span>
                                        </td>
                                        
                                   
                                    </tr>
                                    <tr>
									<td>Followers</td>
									<?php 
									$follow_diff = ($user_logs[0]->followers) - ($user_logs[1]->followers);
									?>
                                        <td><?php echo $user_logs[1]->followers;?>
                                        </td>
                                        <td><?php echo $user_logs[0]->followers;?></td>
                                        <td><span class="badge badge-primary px-2"><?php echo $follow_diff;?></span>
                                        </td>
                                        
                                    </tr>
                                    
                                  
                                </tbody>
                            </table>
                        </div>
						</div>
						
						
                           </div>
				</div>  
						</div>
						
					
						   <div class="clearfix"></div>
                    	   
                                </div>
                            </div>
                        
                        </div>
               
            </div>
			
                </div> 
				
                                </div>
                            </div>
							
							
							 <div id="navpills-4" class="tab-pane">
                                <div class="row">
                					


<div class="col-lg-12">

<div id="accordion-three" class="accordion custom_CSS">
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="mb-0" data-toggle="collapse" data-target="#click1" aria-expanded="true" aria-controls="click1"><i class="fa" aria-hidden="true"></i>Your Favourite Facebook Accounts 
                                    <span><?php echo $user->id;?></span>
                                    
                                    </h5>
                                </div>
                                <div id="click1" class="collapse show" data-parent="#accordion-three">
                         
						   
                    <div class="card" id="white">
						 <div class="card-body">
						<div class="pHP_ana_up">
							
						
						<div class="col-lg-12">
						
						<div class="table-responsive stats">
                            <table class="table header-border">
                                <thead>
                                    <tr>
									<th></th>
									 <th>Username</th>
                                        <th>Likes</th>
                                        <th>Followers</th>
                                        
										<th></th>
                                        
                                    </tr>
                                </thead>
                                <tbody>
                                    
									<?php 
				                    
				                        echo "<tr>";
				                        echo "<td><img src=".$user->profile_picture_url." width='30'></td>";
				                        echo "<td>".$user->username."</td>";
				                        echo "<td>".$user->likes."</td>";
				                        echo "<td>".$user->followers."</td>";
				                        echo "</tr>";
				                    
				                    ?>
                                   
                                  
                                </tbody>
                            </table>
                        </div>
						</div>
						
						
                           </div>
						   
						   </div></div>
					
						   					
                                </div>
                            </div>
                    
					
					 
					
                        </div>
</div>
									
                                </div>
                            </div>
							
							<!--favourite end-->
							 <div id="navpills-5" class="tab-pane">
                                <div class="row align-items-center">
                                    
                                    <div class="col-lg-12">
                   <div id="col_3">
                    <div class="card">
                        <div class="card-body">
                            <div class="pHP_ana_up" id="ac_form">        
						<h4 class="color">Account Settings </h4>
                           
						   <div class="col-lg-4 col-md-4 col-sm-6 float-left">
						   <div class="form-group">
						   <label>Username</label>
						   <input type="text" class="form-control" placeholder="username">
						   
						   </div>
						   </div>
						     <div class="col-lg-4 col-md-4 col-sm-6 float-left">
						    <div class="form-group">
						   <label>Name</label>
						   <input type="text" class="form-control" placeholder="Name">
						   </div>
						   </div>
						     <div class="col-lg-4 col-md-4 col-sm-6 float-left">
						    <div class="form-group">
						   <label>Email</label>
						   <input type="email" class="form-control" placeholder="Email">
						   </div>
						   </div>
						  
						   <div class="border-bottom"></div>
						   
						   <h4 class="color">Change Password <span>if you do not want to change your password do not fill any of those fields below.</span> </h4>
                           
						   <div class="col-lg-4 col-md-4 col-sm-6 float-left">
						   <div class="form-group">
						   <label>Current Password</label>
						   <input type="password" class="form-control" placeholder="password">
						   
						   </div>
						   </div>
						     <div class="col-lg-4 col-md-4 col-sm-6 float-left">
						    <div class="form-group">
						   <label>New Password</label>
						   <input type="password" class="form-control" placeholder="new password">
						   </div>
						   </div>
						     <div class="col-lg-4 col-md-4 col-sm-6 float-left">
						    <div class="form-group">
						   <label>Repeat Password</label>
						   <input type="password" class="form-control" placeholder="password">
						   </div>
						   </div>
						   <div class="col-lg-12">
						   
						    <button type="button" class="btn btn-primary action-button2 float-left">Submit</button>
						   </div>
						   
						   
						   <div class="clearfix"></div>
						   
						   <div class="border-bottom"></div>
						   
						   
						    <h4 class="color">Delete Account <span>by deleting the account, all of your stored data will be deleted. This action is irreversible once done.</span> </h4>
						   
						   <div class="col-lg-12">
						   
						    <button type="button" class="btn btn-primary action-button2 float-left">Delete</button>
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
    <!-- #/ container -->
</div>
</section>
<?php
// echo '<pre>';
// print_r($dates);
// die();
?>

  
    <script src="<?php echo base_url(); ?>assets/js/Chart.min.js"></script>
    <script src="<?php echo base_url(); ?>assets/js/utils.js"></script>
<script>

	//Start Likes Evolution Chart//
	let likes_chart_context = document.getElementById('likes_chart').getContext('2d');

    let gradient = likes_chart_context.createLinearGradient(0, 0, 0, 250);
    gradient.addColorStop(0, 'rgba(43, 227, 155, 0.6)');
    gradient.addColorStop(1, 'rgba(43, 227, 155, 0.05)');

    new Chart(likes_chart_context, {

		
			type: 'line',
			data: {
            labels: [<?= '"'.implode('" , "', array_reverse($dates)).'"'; ?>],
            datasets: [{
                label: 'Likes',
                data: [<?= implode(",", array_reverse($likes)); ?>],
                backgroundColor: gradient,
                borderColor: '#2BE39B',
                fill: true
            }]
        },
			options: {
            tooltips: {
                mode: 'index',
                intersect: false,
                
            },
            title: {
                text: 'Likes Evolution Chart',
                display: true
            },
            legend: {
                display: false
            },
            responsive: true,
            maintainAspectRatio: false,
            scales: {
					xAxes: [{
						display: true,
						scaleLabel: {
							display: true,
							labelString: 'Month'
						}
					}],
					yAxes: [{
						display: true,
						scaleLabel: {
							display: true,
							labelString: 'Value'
						}
					}]
				}
        }
		});
    //End Likes Evolution Chart//


    //Start Followers Evolution Chart//
    let followers_chart_context = document.getElementById('followers_chart').getContext('2d');

    gradient = followers_chart_context.createLinearGradient(0, 0, 0, 250);
    gradient.addColorStop(0, 'rgba(62, 193, 255, 0.6)');
    gradient.addColorStop(1, 'rgba(62, 193, 255, 0.05)');

    new Chart(followers_chart_context, {

		
			type: 'line',
			data: {
            labels: [<?= '"'.implode('" , "', array_reverse($dates)).'"'; ?>],
            datasets: [{
                label: 'Likes',
                data: [<?= implode(",", array_reverse($followers)); ?>],
                backgroundColor: gradient,
                borderColor: '#3ec1ff',
                fill: true
            }]
        },
			options: {
            tooltips: {
                mode: 'index',
                intersect: false,
                
            },
            title: {
                text: 'Followers Evolution Chart',
                display: true
            },
            legend: {
                display: false
            },
            responsive: true,
            maintainAspectRatio: false,
            scales: {
					xAxes: [{
						display: true,
						scaleLabel: {
							display: true,
							labelString: 'Month'
						}
					}],
					yAxes: [{
						display: true,
						scaleLabel: {
							display: true,
							labelString: 'Value'
						}
					}]
				}
        }
		});
    //End Followers Evolution Chart//
		

</script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
<script>
$(document).ready(function()
{
	var base_url = '<?=base_url()?>'; 
      $('#username').keyup(function(){  
           var query = $(this).val(); 
           if(query.length > 3)  
           {  
                $.ajax({
                    url: base_url+"admin/facebookuserajax",
                    type: 'POST',
                    data: {username: query},
                    success: 
                    function(data){
                        console.log(data);  //as a debugging message.
                        $('#usernameList').fadeIn();  
                        $('#usernameList').html(data);
                    }
                });
           }  
      });  
      $(document).on('click', 'li', function(){  
           $('#username').val($(this).text());  
           $('#usernameList').fadeOut();  
      }); 
      $("form").submit(function(e)
      {
        e.preventDefault();
        var username = $('#username').val();
        window.location.href = base_url+"admin/facebook/"+username;
    }); 
    });
</script>
<style>
	canvas{
		-moz-user-select: none;
		-webkit-user-select: none;
		-ms-user-select: none;
	}
	</style>
