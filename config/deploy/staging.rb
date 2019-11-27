set :stage, :staging

set :server_name, 'staging.learn.med.uottawa.ca'


set :log_level, :info

server "#{fetch :server_name}", user: 'deploy', roles: %w{web app db}

set :wpcli_remote_url, "https://#{fetch :server_name}/"
set :tmp_dir, '/tmp' # remote_tmp_dir

ask :branch, proc { `git rev-parse --abbrev-ref HEAD`.chomp }

set :ssh_options, {
  forward_agent: true
}
