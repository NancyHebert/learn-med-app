<?php 

require_once $_SERVER['DOCUMENT_ROOT'] . '/wp/wp-blog-header.php'; #TODO: Find home path instead of DOCUMENT_ROOT
use Symfony\Component\Yaml\Yaml;
use Symfony\Component\Yaml\Parser;

# set defaults

  $email = "NKAEF097@uottawa.ca";

# get the data

  # get the list of modules in order with their names and activity IDs
  
    $args = array( 'post_type' => 'gb_xapi_content', 'category_name' => 'pediatrics', 'posts_per_page' => 20, 'order' => 'ASC'  );
    $loop = new WP_Query( $args );
    
    $num = 1;
    
    $modules = array();
    
    while ( $loop->have_posts() ) {
      $loop->the_post();
      
      $module_title = get_the_title();
      $module_activity_id = get_post_meta(get_the_ID(), 'xapi_activity_id', true);
      
      # record the module into the array
      $modules[$module_activity_id] = $module_title;
          
      $num++;
    }

  # get the list of residents for each rotation
  
    $yaml = new Parser();
    
    $residents = $yaml->parse(file_get_contents('residents.yml'));
    
    # block 1
      # email address
        # name:
        # level:
        # username:
        # program:
    
  # prepare connections to the LRS
  
    $lrs_endpoint = get_option('grassblade_tincan_endpoint');
    $lrs_username = get_option('grassblade_tincan_user');
    $lrs_password = get_option('grassblade_tincan_password');
    
    # if LRS settings aren't set, return the default grassblade shortcode
    if (!$lrs_endpoint || !$lrs_username || !$lrs_password) {
      die('LRS not configured');
    }
   
    $lrs = new TinCan\RemoteLRS(
        $lrs_endpoint,
        '1.0.0',
        $lrs_username,
        $lrs_password
    );
    
  # for the resident to test
  
      if (isset($residents['block1'][$email])) {
        $details = &$residents['block1'][$email];
      } else {
        die("No user $email in block1");
      }

      # for each module
        
        # start the list of modules in the resident's details
        
        $details['modules'] = array();
      
        foreach($modules as $activity_id => $module_name) {
        
          s($module_name);
          
          # add an entry to the resident's details for the module
          
            $details['modules'][$activity_id] = array();
    
          # verify whether the resident has started the module or not
          
              s("Started?");

            # use the Tin Can API and check for the first statement on the activity ID for that actor with the verb 'attempted', starting from the oldest statements forward
            
              $response = $lrs->queryStatements([
                'agent' => new TinCan\Agent(['mbox' => 'mailto:'.$email]),
                'verb' => new TinCan\Verb(['id' => 'http://adlnet.gov/expapi/verbs/attempted']),
                'activity' => new TinCan\Activity(['id' => $activity_id]),
                'related_activities' => 'true',
              ]);
              
              $statements = $response->content;
              
              d($statements);
              
            # write to the residents array
            
              $oldest_item_index = count($statements->getStatements()) - 1;
              
              if (isset($statements->getStatements()[$oldest_item_index])) {
                $details['modules'][$activity_id]['started'] = $statements->getStatements()[$oldest_item_index]->getTimestamp();
              } 
//              else {
//                continue; // if no 'attempted' statement was found, then skip to the next module
//              }
      
      
          # get the completion for each module
          
              s("Completed?");

            # if an 'attempted' statement was found, then look for a statement with verb 'completed'
            
              $response = $lrs->queryStatements([
                'agent' => new TinCan\Agent(['mbox' => 'mailto:'.$email]),
                'verb' => new TinCan\Verb(['id' => 'http://adlnet.gov/expapi/verbs/passed']),
                'activity' => new TinCan\Activity(['id' => $activity_id]),
                'related_activities' => 'true',
              ]);
              
              $statements = $response->content;
              
              d($statements);


            # write to the residents array
            
              $oldest_item_index = count($statements->getStatements()) - 1;
              
              if (isset($statements->getStatements()[$oldest_item_index])) {
                $details['modules'][$activity_id]['completed'] = $statements->getStatements()[$oldest_item_index]->getTimestamp();
              }
        }  
  
  print "<pre>" . Yaml::dump($residents['block1'][$email], 6);

?>