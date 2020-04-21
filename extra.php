public function instagram_user($username) {
        // Check if the session exists and if the login user is admin
        $this->check_session($this->user_role, 1);
        $user_detail = $this->instagram->get_user_detail($username);
        $user_media = $this->instagram->get_all_user_media($user_detail['detail']->id);
        // Get users template
        $this->body = 'admin/instagram';
        //echo "<pre>";
        $details = [];
        $details = json_decode($user_detail['detail']->details);
        // $media = $this->instagram->get_media_detail($details);
        // print_r($media);die;
        $this->content = [
            'user' => $user_detail['detail'],
            'user_logs' => $user_detail['logs'],
            'details' => $details,
            'user_media' => $user_media
        ];
        $this->admin_layout();
    }



public function get_all_user_media($id) {
        $this->db->select('*');
        $this->db->from('instagram_media');
        $this->db->where('instagram_user_id', $id);
        $this->db->order_by('date', 'DESC');
        $query = $this->db->get();
        if ( $query->num_rows() > 0 ) {
            return $query->result();
        } else {
            return false;
        }
    }


public function get_user_detail($username) {
        $this->db->select('*');
        $this->db->from($this->table);
        $this->db->where('username', $username);
        $this->db->limit(1);
        $query = $this->db->get();
        if ( $query->num_rows() === 1 ) {    
            $source_account = $query->result(); 
            // $data = $source_account[0]->details;
            // $details = $this->json($data);
            //$this->json();           
        } else {            
            return false;            
        }
        $this->db->select('*');
        $this->db->from('instagram_logs');
        $this->db->where('username', $username);
        $this->db->order_by('date', 'DESC');
        $query = $this->db->get();
        if ( $query->num_rows() > 0 ) {    
            $source_account_logs = $query->result();
        }
       $user = [];
       $user['detail'] = $source_account[0];
       $user['logs'] = $source_account_logs;
       return $user;
    }


    <tr>
                                        <td><?php 
                                        $date = date_create($user_logs[0]->date);
                                        echo date_format($date,'Y-m-d');?>
                                        </td>
                                        <td><?php 
                                        $date = date_create($user_logs[0]->date);
                                        echo date_format($date,'l');?></td>
                                        <td><?php echo $user_logs[0]->likes;?>
                                        </td>
                                        <td>
                                        <?php 
                                        $likes_diff = ($user_logs[0]->likes) - ($user_logs[1]->likes);
                                        ?>
                                        <span class="badge badge-primary px-2"><?php echo $likes_diff;?></span>
                                        </td>
                                        <td><?php echo $user_logs[0]->followers;?>
                                        </td>
                                        <td> 
                                        <?php 
                                        $follow_diff = ($user_logs[0]->followers) - ($user_logs[1]->followers);
                                        ?>
                                        <span class="badge badge-primary px-2"><?php echo $follow_diff;?></span>
                                        </td>
                                        
                                        
                                   
                                    </tr>
                                    
                                    <tr>
                                        <td><?php 
                                        $date = date_create($user_logs[1]->date);
                                        echo date_format($date,'Y-m-d');?>
                                        </td>
                                        <td><?php 
                                        $date = date_create($user_logs[1]->date);
                                        echo date_format($date,'l');?></td>
                                        <td><?php echo $user_logs[1]->likes;?>
                                        </td>
                                        <td>
                                        </td>
                                        <td><?php echo $user_logs[1]->followers;?>
                                        </td>
                                        <td> </td>
                                    </tr>


                                    <tbody>
                                    <?php
                                    foreach($user_logs as $key=>$user)
                                    {
                                        $date = date_create($user->date);
                                        $likes_diff = ($user->likes) - ($user->likes);
                                        $follow_diff = ($user->followers) - ($user->followers);
                                        echo '<tr>';
                                        echo '<td>'.date_format($date,'Y-m-d').'</td>';
                                        echo '<td>'.date_format($date,'l').'</td>';
                                        echo '<td>'.$user->likes.'</span></td>';
                                        echo '<td><span class="badge badge-primary px-2">'.$likes_diff.'</span></td>';
                                        echo '<td>'.$user->followers.'</span></td>';
                                        echo '<td><span class="badge badge-primary px-2">'.$follow_diff.'</span></td>';
                                        echo '</tr>';
                                    }
                                    ?>
                                             
                                              
                                </tbody>

<?php
public function exportCSV($username)
{ 

$filename = 'users_'.date('Ymd').'.csv'; 
header("Content-Description: File Transfer"); 
header("Content-Disposition: attachment; filename=$filename"); 
header("Content-Type: application/csv; ");

// get data 
$user_detail = $this->facebook->get_facebook_user_detail($username);

// file creation 
$file = fopen('php://output', 'w');

$header = array("Username","Likes","Followers","Date"); 
fputcsv($file, $header);
foreach ($usersData as $key=>$line)
{ 
fputcsv($file,$line); 
}
fclose($file); 
exit; 
}
?>

<?php
                                         $followers = [];
                                         foreach ($user_logs as $summary) {
                                             $i = 0;
                                             $followers[] = $summary->followers;
                                             $following[] = $summary->following;
                                             $uploads[] = $summary->uploads;
                                             //echo "--".$summary->followers;
                                             $i++;
                                         }
                                        for($i=0;$i<count($user_logs);$i++) {
                                            if($i==(count($user_logs)-1)) {
                                            break;
                                            } else {
                                                $followers_diff[] = $followers[$i]-$followers[$i+1];
                                                $following_diff[] = $following[$i]-$following[$i+1];
                                                $uploads_diff[] = $uploads[$i]-$uploads[$i+1];
                                            }
                                        }
                                            $j = 0;
                                            foreach ($user_logs as $log) { 
                                                echo "<tr><td>".$log->date."</td>";
                                                echo "<td>".date('D', strtotime($log->date))."</td>";
                                                echo "<td>".$log->followers."</td>";
                                                echo "<td><span class='badge badge-primary px-2'>".$followers_diff[$j]."</td>";
                                                echo "<td>".$log->following."</td>";
                                                echo "<td><span class='badge badge-primary px-2'>".$following_diff[$j]."</span></td>";
                                                echo "<td>".$log->uploads."</td>";
                                                echo "<td>".$uploads_diff[$j]."</td></tr>";
                                                $j++;
                                            }
                                            ?>

//Search Functionality


<div class="card-body">
                                <form action="" method="get" name="search-user" id="search-user"> 
                                    <div class="pHP_ana_up">
                                        <ul>
                                        <li><i class="fa fa-calendar-check-o"></i>Joined on 2020-04-06</li>
                                        <li><i class="fa fa-credit-card"></i>Account Balance of total points</li>
                                        <li><i class="fa fa-heart-o"></i>Total Favourites</li>
                                        <li><i class="fa fa-file-text-o"></i>Total Unlock Reports</li>
                                        </ul>
                                        <div class="clearfix"></div>
                                        <h4 class="color">PHP Analyzer <span>Analyze and track your social media account</span></h4>
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
                                            <input type="text" placeholder="Enter instagram page username" name="username" id="username" value="" >
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




<?php
public function get_users_name($username) {
        $this->db->select('username');
        $this->db->from($this->table);
        $this->db->like('username', $username, 'after');
        $query = $this->db->get();
        if ( $query->num_rows() > 0 ) {
            return $query->result();
        } else {
            return false;
        }
    }



       public function instagram_user_ajax() {
        // Check if the session exists and if the login user is admin
        $this->check_session($this->user_role, 1);
        $username = $this->input->post('username');
        $user_detail = $this->instagram->get_users_name($username);
        //print_r($user_detail);
        $user_list = "<ul>";
        if(isset($user_detail)) { 
            foreach($user_detail as $list) {
                $user_list .= '<li>'.$list->username.'</li>';
            }
        } else {
            $user_list .= '<li>No User Found</li>';
        }
        $user_list .= '</ul>';
        echo $user_list;
    }

$route['admin/instagramuserajax']['post'] = 'adminarea/instagram_user_ajax';
?>


<script>
$(document).ready(function(){  
      $('#username').keyup(function(){  
           var query = $(this).val(); 
           if(query.length > 3)  
           {  
                $.ajax({
                    url: base_url+"admin/instagramuserajax",
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
    });
</script>


<tr>
                                        <td><b>Total Summary</b></td>
                                        <td></td>
                                        <td>
                                        <?php

                                        for($i=0;$i<count($likes_diff);$i++) 
                                        {
                                            if($i==(count($likes_diff)-1)) 
                                            {   
                                                break;
                                            } 
                                            else 
                                            {
                                                $total_likes = $likes_diff[$i]+$likes_diff[$i+1];
                                            }
                                        }
                                        echo '<span class="badge badge-primary px-2">'.$total_likes.'</span>';
                                        ?>
                                        </td>
                                        <td></td>
                                        <td>
                                        <?php
                                        
                                        for($i=0;$i<count($follow_diff);$i++) 
                                        {
                                            if($i==(count($follow_diff)-1)) 
                                            {
                                                break;
                                            } 
                                            else 
                                            {
                                                $total_follow = $follow_diff[$i]+$follow_diff[$i+1];
                                            }
                                        }
                                        echo '<span class="badge badge-primary px-2">'.$total_follow.'</span>';
                                        ?>
                                        </td>
                                        <td></td>
                                    </tr> 


                        <div class="border-bottom"></div>
                        <div class="col-lg-12 stats">
                    <h4 class="color">Posts and Comments<em>Here you can see the posts and comments on facebook page.</em></h4>
                    <div class="col-lg-12 float-left">
                                                
                    <div class="table-responsive">
                            <table class="table header-border">
                                <thead>
                                    <tr>
                                        <th>Post Date</th>
                                        <th>Post Message</th>
                                        <th>Comment Date</th>
                                        <th>Comment Message</th>
                                    </tr>
                                </thead>
                                <?php
                                $json_string1 = file_get_contents('https://graph.facebook.com/v6.0/105800647713339?fields=posts&access_token=EAAEZA1NYmO70BAEVH0VCwL4A5ZAArBNOkC4nwKGc0Osq2viAdSaRCZAYGowXJwdYC03xXSC4tkLKw8saR5mL7cum1wmdrP4mcCMHA3ZCjeZAYgRTS3Qv4ZAwSPFlelaYFdIdvFSNZC8LA6ziZAOSVTZAl9rUBHPZCTB2jGk3k4IEP5bWExqZCESbmzCHQ2ZC1cUs7dgZAoOKhoYHE03bkoFEMcONEktJX5nvZAYCKZAgOnyKNwx8JrEjmLfTDV2');
                                $json1 = json_decode($json_string1, true);

                                ?>
                                <tbody>
                                <?php
                                    foreach($json1['posts']['data'] as $json)
                                    {
                                        
                                        echo '<tr>';
                                        echo '<td>'.$json['created_time'].'</td>';
                                        echo '<td>'.$json['message'].'</td>';
                                        $json_string2 = file_get_contents('https://graph.facebook.com/v6.0/'.$json['id'].'?fields=comments&access_token=EAAEZA1NYmO70BAEVH0VCwL4A5ZAArBNOkC4nwKGc0Osq2viAdSaRCZAYGowXJwdYC03xXSC4tkLKw8saR5mL7cum1wmdrP4mcCMHA3ZCjeZAYgRTS3Qv4ZAwSPFlelaYFdIdvFSNZC8LA6ziZAOSVTZAl9rUBHPZCTB2jGk3k4IEP5bWExqZCESbmzCHQ2ZC1cUs7dgZAoOKhoYHE03bkoFEMcONEktJX5nvZAYCKZAgOnyKNwx8JrEjmLfTDV2');
                                        $json2 = json_decode($json_string2, true); 

                                        foreach($json2['comments']['data'] as $jsons)
                                        {
                                            echo '<td>'.$jsons['created_time'].'</td>';
                                            echo '<td>'.$jsons['message'].'</td>';
                                        }
                                        echo '</tr>';
                                    }
                                ?>
                                       
                                </tbody>
                            </table>
                        </div>
                    </div>
                        </div>   
                        
                        
                        <div class="clearfix"></div>