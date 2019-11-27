# Load DSL and Setup Up Stages
require 'capistrano/setup'

# Includes default deployment tasks
require 'capistrano/deploy'

# Automate running composer installs
require 'capistrano/composer'

# Automate wordpress commands
require 'capistrano/wpcli'

# Notify Slack
require 'slackistrano/capistrano'

# Loads custom tasks from `config/deploy/tasks' if you have any defined.
Dir.glob('config/deploy/tasks/*.cap').each { |r| import r }
